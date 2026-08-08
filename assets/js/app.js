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
	 * Dialogs (stat band → CVE reports)
	 *
	 * Native <dialog>: showModal() gives us the focus trap and Esc for
	 * free; we add backdrop-click to close.
	 * ------------------------------------------------------------------ */

	document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
		var dialog = document.getElementById(btn.dataset.modalOpen);
		if (!dialog || typeof dialog.showModal !== 'function') {
			return;
		}
		btn.addEventListener('click', function () {
			dialog.showModal();
		});
	});

	document.querySelectorAll('dialog').forEach(function (dialog) {
		dialog.querySelectorAll('[data-modal-close]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				dialog.close();
			});
		});
		dialog.addEventListener('click', function (e) {
			// A click on the backdrop targets the dialog element itself.
			if (e.target === dialog) {
				dialog.close();
			}
		});
	});

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
	 * Fleet demo — a working slice of the real console: search, facet
	 * chips (removable), a + Filter dropdown with plugin version/status
	 * sub-facets, and a row context menu. Mirrors core-v3's patterns.
	 * ------------------------------------------------------------------ */

	var fleetPane = consoleRoot ? consoleRoot.querySelector('[data-console-pane="fleet"]') : null;
	var fleetConfigEl = fleetPane ? fleetPane.querySelector('[data-fleet-config]') : null;

	if (fleetPane && fleetConfigEl) {
		var fleetCfg;
		try {
			fleetCfg = JSON.parse(fleetConfigEl.textContent);
		} catch (e) {
			fleetCfg = null;
		}
	}

	if (fleetPane && fleetCfg) {
		var fleet = {
			chips: (fleetCfg.chips || []).map(function (c) { return Object.assign({}, c); }),
			q: '',
			rows: Array.prototype.slice.call(fleetPane.querySelectorAll('.fleet__row')),
			pinned: [],
		};

		var chipsWrap = fleetPane.querySelector('[data-fleet-chips]');
		var addBtn = fleetPane.querySelector('[data-fleet-add]');
		var countEl = fleetPane.querySelector('[data-fleet-count]');
		var emptyEl = fleetPane.querySelector('[data-fleet-empty]');
		var searchEl = fleetPane.querySelector('[data-fleet-filter]');

		fleet.rows.forEach(function (row) {
			try {
				row._plugins = JSON.parse(row.dataset.fleetPlugins || '{}');
			} catch (e) {
				row._plugins = {};
			}
		});

		var fmt = function (n) { return Number(n).toLocaleString(); };

		/* '< 10.2' style ranges against dotted versions. */
		function versionMatches(rule, version) {
			if (rule.indexOf('<') !== 0) {
				return rule === version;
			}
			var limit = rule.replace('<', '').trim().split('.');
			var got = String(version).split('.');
			for (var i = 0; i < Math.max(limit.length, got.length); i++) {
				var a = parseInt(got[i] || '0', 10);
				var b = parseInt(limit[i] || '0', 10);
				if (a !== b) {
					return a < b;
				}
			}
			return false;
		}

		function activePlugin() {
			var chip = fleet.chips.filter(function (c) { return c.facet === 'plugin'; })[0];
			return chip ? chip.value : null;
		}

		function rowMatches(row) {
			if (fleet.q && row.dataset.fleetSite.indexOf(fleet.q) === -1) {
				return false;
			}
			return fleet.chips.every(function (chip) {
				var plugin = activePlugin();
				switch (chip.facet) {
					case 'plugin':
						var p = row._plugins[chip.value];
						return !!p && (!chip.qual || p.s === chip.qual);
					case 'version':
						return plugin && row._plugins[plugin] && versionMatches(chip.value, row._plugins[plugin].v);
					case 'status':
						return plugin && row._plugins[plugin] && row._plugins[plugin].s === chip.value;
					case 'theme':
						return row.dataset.fleetTheme === chip.value;
					case 'core':
						return row.dataset.fleetCore === chip.value;
				}
				return true;
			});
		}

		function chipLabel(chip) {
			var label = chip.facet.charAt(0).toUpperCase() + chip.facet.slice(1) + ': ' + chip.value;
			return chip.qual ? label + ' · ' + chip.qual : label;
		}

		function renderChips() {
			chipsWrap.querySelectorAll('.filter-chip:not(.filter-chip--add)').forEach(function (el) { el.remove(); });
			fleet.chips.forEach(function (chip) {
				var el = document.createElement('button');
				el.type = 'button';
				el.className = 'filter-chip';
				el.setAttribute('aria-label', 'Remove filter ' + chipLabel(chip));
				el.innerHTML = '';
				el.appendChild(document.createTextNode(chipLabel(chip)));
				var x = document.createElement('span');
				x.className = 'filter-chip__x';
				x.textContent = '✕';
				el.appendChild(x);
				el.addEventListener('click', function () { removeChip(chip); });
				chipsWrap.insertBefore(el, addBtn);
			});
		}

		function removeChip(chip) {
			fleet.chips = fleet.chips.filter(function (c) { return c !== chip; });
			if (chip.facet === 'plugin') {
				// Version/status qualify the plugin — they go with it.
				fleet.chips = fleet.chips.filter(function (c) { return c.facet !== 'version' && c.facet !== 'status'; });
			}
			apply();
		}

		function setChip(facet, value, count, qual) {
			fleet.chips = fleet.chips.filter(function (c) { return c.facet !== facet; });
			if (facet === 'plugin') {
				fleet.chips = fleet.chips.filter(function (c) { return c.facet !== 'version' && c.facet !== 'status'; });
			}
			var chip = { facet: facet, value: value, count: count };
			if (qual) {
				chip.qual = qual;
			}
			fleet.chips.push(chip);
			apply();
		}

		function apply() {
			var order = fleet.pinned.concat(fleet.rows.filter(function (r) { return fleet.pinned.indexOf(r) === -1; }));
			var parent = fleet.rows[0].parentNode;
			var anchorNode = emptyEl;
			order.forEach(function (row) { parent.insertBefore(row, anchorNode); });

			var shown = 0;
			fleet.rows.forEach(function (row) {
				var match = rowMatches(row);
				row.hidden = !match;
				row.classList.toggle('fleet__row--pinned', fleet.pinned.indexOf(row) !== -1);
				if (match) {
					shown++;
				}
			});
			if (emptyEl) {
				emptyEl.hidden = shown !== 0;
			}
			if (countEl) {
				var counts = fleet.chips.map(function (c) { return c.count || fleetCfg.total; });
				var n = counts.length ? Math.min.apply(null, counts) : fleetCfg.total;
				countEl.textContent = counts.length
					? fmt(n) + ' of ' + fmt(fleetCfg.total) + ' sites'
					: fmt(fleetCfg.total) + ' sites';
			}
			renderChips();
		}

		if (searchEl) {
			searchEl.addEventListener('input', function () {
				fleet.q = searchEl.value.trim().toLowerCase();
				apply();
			});
		}

		/* --- + Filter dropdown (facet list → searchable options) --- */

		var dd = document.createElement('div');
		dd.className = 'fleet-dd';
		dd.hidden = true;
		fleetPane.appendChild(dd);

		function closeDd() {
			dd.hidden = true;
		}

		function ddOption(label, badge, onPick, marked) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'fleet-dd__opt' + (marked ? ' is-current' : '');
			var name = document.createElement('span');
			name.textContent = (marked ? '✓ ' : '') + label;
			b.appendChild(name);
			if (badge) {
				var bd = document.createElement('span');
				bd.className = 'fleet-dd__badge';
				bd.textContent = badge;
				b.appendChild(bd);
			}
			b.addEventListener('click', onPick);
			return b;
		}

		function showFacetList() {
			dd.innerHTML = '';
			var facets = Object.keys(fleetCfg.facets).map(function (key) {
				return { key: key, label: fleetCfg.facets[key].label };
			});
			var plugin = activePlugin();
			if (plugin && fleetCfg.subs[plugin]) {
				facets.push({ key: 'version', label: 'Version — ' + plugin });
				facets.push({ key: 'status', label: 'Status — ' + plugin });
			}
			facets.forEach(function (f) {
				dd.appendChild(ddOption(f.label, '▸', function (e) {
					e.stopPropagation();
					showOptions(f.key);
				}, false));
			});
		}

		function showOptions(facetKey) {
			dd.innerHTML = '';

			var back = document.createElement('button');
			back.type = 'button';
			back.className = 'fleet-dd__back';
			back.textContent = '‹ Back';
			back.addEventListener('click', function (e) {
				e.stopPropagation();
				showFacetList();
			});
			dd.appendChild(back);

			var plugin = activePlugin();
			var options;
			if (facetKey === 'version' || facetKey === 'status') {
				options = (fleetCfg.subs[plugin] || {})[facetKey === 'version' ? 'versions' : 'statuses'] || [];
			} else {
				options = fleetCfg.facets[facetKey].options;
			}

			var search;
			if (options.length > 4) {
				search = document.createElement('input');
				search.type = 'text';
				search.placeholder = 'Search…';
				search.className = 'fleet-dd__search';
				dd.appendChild(search);
			}

			var list = document.createElement('div');
			dd.appendChild(list);

			var current = fleet.chips.filter(function (c) { return c.facet === facetKey; })[0];

			function renderOptions() {
				list.innerHTML = '';
				var q = search ? search.value.trim().toLowerCase() : '';
				options.filter(function (o) {
					return !q || o.name.toLowerCase().indexOf(q) !== -1;
				}).forEach(function (o) {
					list.appendChild(ddOption(o.name, fmt(o.count) + ' sites', function () {
						setChip(facetKey, o.name, o.count, facetKey === 'plugin' ? 'active' : null);
						closeDd();
					}, !!current && current.value === o.name));
				});
			}

			if (search) {
				search.addEventListener('input', renderOptions);
				search.addEventListener('click', function (e) { e.stopPropagation(); });
				setTimeout(function () { search.focus(); }, 0);
			}
			renderOptions();
		}

		if (addBtn) {
			addBtn.addEventListener('click', function (e) {
				e.stopPropagation();
				if (!dd.hidden) {
					closeDd();
					return;
				}
				showFacetList();
				dd.hidden = false;
				var rect = addBtn.getBoundingClientRect();
				var paneRect = fleetPane.getBoundingClientRect();
				dd.style.left = Math.min(rect.left - paneRect.left, paneRect.width - 280) + 'px';
				dd.style.top = (rect.bottom - paneRect.top + 7) + 'px';
			});
		}

		/* --- Row context menu (the Minn pattern: entries built from the
		       row so the menu can never drift from it) --- */

		var ctx = document.createElement('div');
		ctx.className = 'fleet-ctx';
		ctx.hidden = true;
		document.body.appendChild(ctx);

		function closeCtx() {
			ctx.hidden = true;
		}

		function toast(msg) {
			var el = document.createElement('div');
			el.className = 'console-toast';
			el.textContent = msg;
			consoleRoot.querySelector('.console').appendChild(el);
			setTimeout(function () { el.classList.add('is-out'); }, 2200);
			setTimeout(function () { el.remove(); }, 2600);
		}

		function openCtx(e, entries) {
			e.preventDefault();
			e.stopPropagation();
			ctx.innerHTML = '';
			entries.forEach(function (en) {
				var b = document.createElement('button');
				b.type = 'button';
				b.className = 'fleet-ctx__entry';
				b.textContent = en.label;
				b.addEventListener('click', function () {
					closeCtx();
					en.act();
				});
				ctx.appendChild(b);
			});
			ctx.hidden = false;
			var w = 230;
			var h = 10 + entries.length * 37;
			ctx.style.left = Math.max(8, Math.min(e.clientX, window.innerWidth - w - 12)) + 'px';
			ctx.style.top = Math.max(8, Math.min(e.clientY, window.innerHeight - h - 12)) + 'px';
		}

		fleet.rows.forEach(function (row) {
			var domain = row.dataset.fleetDomain;
			var handler = function (e) {
				var pinned = fleet.pinned.indexOf(row) !== -1;
				openCtx(e, [
					{ label: 'Open site', act: function () { toast('Demo data — the real console opens the full site view.'); } },
					{ label: 'Login to WordPress ↗', act: function () { toast('Demo data — one click on a real site.'); } },
					{ label: pinned ? 'Unpin' : 'Pin to top', act: function () {
						if (pinned) {
							fleet.pinned = fleet.pinned.filter(function (r) { return r !== row; });
						} else {
							fleet.pinned.unshift(row);
						}
						apply();
					} },
					{ label: 'Open terminal', act: function () { showConsoleTab('terminal'); } },
					{ label: 'Copy domain', act: function () {
						if (navigator.clipboard) {
							navigator.clipboard.writeText(domain).then(function () { toast('Copied ' + domain + '.'); }).catch(function () {});
						}
					} },
				]);
			};
			row.addEventListener('contextmenu', handler);
			row.addEventListener('click', handler);
		});

		document.addEventListener('click', function () {
			closeDd();
			closeCtx();
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				closeDd();
				closeCtx();
			}
		});

		apply();
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
