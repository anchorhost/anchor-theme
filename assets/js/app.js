/**
 * Anchor Theme — header behaviour.
 *
 * Theme toggle, mobile nav, dashboard console tabs and the command palette.
 */
(function () {
	'use strict';

	var config = window.anchorTheme || {};

	/* ------------------------------------------------------------------
	 * Colour scheme
	 * ------------------------------------------------------------------ */

	function currentTheme() {
		return (
			document.documentElement.dataset.theme ||
			(window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
		);
	}

	function toggleTheme() {
		var next = currentTheme() === 'dark' ? 'light' : 'dark';
		document.documentElement.dataset.theme = next;
		try {
			localStorage.setItem('ah-theme', next);
		} catch (e) {}
	}

	document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
		btn.addEventListener('click', toggleTheme);
	});

	/* ------------------------------------------------------------------
	 * Mobile navigation
	 * ------------------------------------------------------------------ */

	var navToggle = document.querySelector('[data-nav-toggle]');
	var nav = document.getElementById('main-nav');

	if (navToggle && nav) {
		navToggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	/* ------------------------------------------------------------------
	 * Dashboard console tabs
	 * ------------------------------------------------------------------ */

	var consoleRoot = document.querySelector('[data-console]');

	function showConsoleTab(key) {
		if (!consoleRoot) {
			return;
		}

		consoleRoot.querySelectorAll('[data-console-tab]').forEach(function (btn) {
			var active = btn.dataset.consoleTab === key;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-selected', active ? 'true' : 'false');

			if (active) {
				var display = consoleRoot.querySelector('[data-console-url-display]');
				if (display) {
					display.textContent = btn.dataset.consoleUrl || '';
				}
			}
		});

		consoleRoot.querySelectorAll('[data-console-pane]').forEach(function (pane) {
			pane.hidden = pane.dataset.consolePane !== key;
		});
	}

	if (consoleRoot) {
		consoleRoot.querySelectorAll('[data-console-tab]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				showConsoleTab(btn.dataset.consoleTab);
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Command palette
	 * ------------------------------------------------------------------ */

	var palette = document.querySelector('[data-palette]');

	if (!palette) {
		return;
	}

	var input = palette.querySelector('[data-palette-input]');
	var resultsEl = palette.querySelector('[data-palette-results]');
	var panel = palette.querySelector('[data-palette-panel]');

	var staticCommands = Array.isArray(config.commands) ? config.commands : [];
	var remoteResults = [];
	var visible = [];
	var cursor = 0;
	var isOpen = false;
	var searchTimer = null;
	var lastQuery = '';
	var searchAbort = null;

	function matches(cmd, query) {
		if (!query) {
			return true;
		}
		var haystack = (cmd.label + ' ' + (cmd.group || '') + ' ' + (cmd.keys || '')).toLowerCase();
		return haystack.indexOf(query.toLowerCase().trim()) !== -1;
	}

	function buildList() {
		var query = input.value;
		var filtered = staticCommands.filter(function (cmd) {
			return matches(cmd, query);
		});
		return filtered.concat(remoteResults);
	}

	function render() {
		visible = buildList();

		if (cursor > visible.length - 1) {
			cursor = Math.max(0, visible.length - 1);
		}

		if (!visible.length) {
			resultsEl.innerHTML =
				'<div class="palette__empty">' +
				escapeHtml(config.i18n && config.i18n.noResults ? config.i18n.noResults : 'Nothing matched. Try') +
				' <code>backup</code>, <code>cve</code> ' +
				'or <code>plans</code>.</div>';
			return;
		}

		var html = '';
		var lastGroup = null;

		visible.forEach(function (cmd, i) {
			if (cmd.group !== lastGroup) {
				html += '<div class="palette__group">' + escapeHtml(cmd.group || '') + '</div>';
				lastGroup = cmd.group;
			}

			html +=
				'<button type="button" role="option" aria-selected="' +
				(i === cursor ? 'true' : 'false') +
				'" class="palette__item' +
				(i === cursor ? ' is-active' : '') +
				'" data-index="' +
				i +
				'">' +
				'<span class="palette__label">' +
				escapeHtml(cmd.label) +
				'</span>' +
				'<span class="palette__kind">' +
				escapeHtml(cmd.kind || '') +
				'</span>' +
				'</button>';
		});

		resultsEl.innerHTML = html;

		var active = resultsEl.querySelector('.palette__item.is-active');
		if (active && active.scrollIntoView) {
			active.scrollIntoView({ block: 'nearest' });
		}
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
		});
	}

	function open() {
		isOpen = true;
		palette.hidden = false;
		input.value = '';
		remoteResults = [];
		cursor = 0;
		render();
		setTimeout(function () {
			input.focus();
		}, 20);
	}

	function close() {
		isOpen = false;
		palette.hidden = true;
		if (searchAbort) {
			searchAbort.abort();
			searchAbort = null;
		}
	}

	function run(cmd) {
		if (!cmd) {
			return;
		}

		close();

		if (cmd.action === 'toggle-theme') {
			toggleTheme();
			return;
		}

		if (cmd.kind === 'copy' && cmd.text) {
			try {
				navigator.clipboard.writeText(cmd.text);
			} catch (e) {}
			return;
		}

		if (cmd.kind === 'demo' && cmd.tab) {
			// The console only exists on the front page; go there if we are elsewhere.
			if (consoleRoot) {
				showConsoleTab(cmd.tab);
				consoleRoot.scrollIntoView({ behavior: 'smooth', block: 'start' });
			} else if (cmd.home) {
				window.location.href = cmd.home + '#console-' + cmd.tab;
			}
			return;
		}

		if (cmd.url) {
			var external = cmd.url.indexOf(window.location.origin) !== 0;
			if (external) {
				window.open(cmd.url, '_blank', 'noopener');
			} else {
				window.location.href = cmd.url;
			}
		}
	}

	/* Live content search, debounced. */
	function scheduleSearch() {
		var query = input.value.trim();

		if (query === lastQuery) {
			return;
		}
		lastQuery = query;

		clearTimeout(searchTimer);

		if (query.length < 2 || !config.searchRest) {
			remoteResults = [];
			render();
			return;
		}

		searchTimer = setTimeout(function () {
			if (searchAbort) {
				searchAbort.abort();
			}
			searchAbort = typeof AbortController !== 'undefined' ? new AbortController() : null;

			fetch(config.searchRest + '?q=' + encodeURIComponent(query), {
				signal: searchAbort ? searchAbort.signal : undefined,
				headers: { Accept: 'application/json' }
			})
				.then(function (r) {
					return r.ok ? r.json() : [];
				})
				.then(function (data) {
					remoteResults = Array.isArray(data) ? data : [];
					render();
				})
				.catch(function () {
					/* aborted or offline — leave the static results in place */
				});
		}, 180);
	}

	input.addEventListener('input', function () {
		cursor = 0;
		render();
		scheduleSearch();
	});

	resultsEl.addEventListener('click', function (e) {
		var item = e.target.closest('[data-index]');
		if (item) {
			run(visible[parseInt(item.dataset.index, 10)]);
		}
	});

	resultsEl.addEventListener('mousemove', function (e) {
		var item = e.target.closest('[data-index]');
		if (!item) {
			return;
		}
		var i = parseInt(item.dataset.index, 10);
		if (i !== cursor) {
			cursor = i;
			render();
		}
	});

	palette.addEventListener('click', function (e) {
		if (!panel.contains(e.target)) {
			close();
		}
	});

	document.querySelectorAll('[data-palette-open]').forEach(function (btn) {
		btn.addEventListener('click', open);
	});

	document.addEventListener('keydown', function (e) {
		var typing = /^(INPUT|TEXTAREA|SELECT)$/.test((e.target && e.target.tagName) || '') ||
			(e.target && e.target.isContentEditable);

		if ((e.metaKey || e.ctrlKey) && e.key && e.key.toLowerCase() === 'k') {
			e.preventDefault();
			isOpen ? close() : open();
			return;
		}

		if (!isOpen) {
			if (e.key === '/' && !typing) {
				e.preventDefault();
				open();
			}
			return;
		}

		if (e.key === 'Escape') {
			e.preventDefault();
			close();
			return;
		}

		if (e.key === 'ArrowDown') {
			e.preventDefault();
			cursor = Math.min(cursor + 1, visible.length - 1);
			render();
		}

		if (e.key === 'ArrowUp') {
			e.preventDefault();
			cursor = Math.max(cursor - 1, 0);
			render();
		}

		if (e.key === 'Enter') {
			e.preventDefault();
			run(visible[cursor]);
		}
	});

	// Deep link: /#console-security opens that pane on arrival.
	if (window.location.hash.indexOf('#console-') === 0) {
		showConsoleTab(window.location.hash.replace('#console-', ''));
	}
})();
