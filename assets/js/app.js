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

	function osTheme() {
		try {
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
		} catch (e) {
			return 'light';
		}
	}

	function themePref() {
		try {
			var saved = localStorage.getItem('ah-theme');
			if (saved === 'light' || saved === 'dark' || saved === 'system') {
				return saved;
			}
		} catch (e) {}
		return 'system';
	}

	function applyTheme(pref) {
		var p = pref === 'light' || pref === 'dark' || pref === 'system' ? pref : themePref();
		document.documentElement.dataset.themePref = p;
		if (p === 'system') {
			delete document.documentElement.dataset.theme;
		} else {
			document.documentElement.dataset.theme = p;
		}
	}

	function setThemePref(pref) {
		var p = pref === 'light' || pref === 'dark' ? pref : 'system';
		try {
			localStorage.setItem('ah-theme', p);
		} catch (e) {}
		applyTheme(p);
		syncToggleChrome();
	}

	function toggleTheme() {
		// Click is light ↔ dark only. System stays a right-click pick. From
		// System, flip whatever the OS is showing now and lock that.
		var now = themePref() === 'system' ? osTheme() : themePref();
		setThemePref(now === 'light' ? 'dark' : 'light');
	}

	function syncToggleChrome() {
		var pref = themePref();
		var labels = { system: 'System', light: 'Light', dark: 'Dark' };
		var title =
			'Theme: ' +
			(labels[pref] || 'System') +
			' (click to switch light and dark, right-click for options)';
		document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
			btn.title = title;
			btn.setAttribute('aria-label', title);
		});
	}

	var themeCtx = document.createElement('div');
	themeCtx.className = 'theme-ctx';
	themeCtx.hidden = true;
	themeCtx.setAttribute('role', 'menu');
	document.body.appendChild(themeCtx);

	function closeThemeCtx() {
		themeCtx.hidden = true;
	}

	function openThemeMenu(e) {
		e.preventDefault();
		e.stopPropagation();
		var cur = themePref();
		var opts = [
			{ id: 'system', label: 'System' },
			{ id: 'light', label: 'Light' },
			{ id: 'dark', label: 'Dark' },
		];
		themeCtx.innerHTML = '';
		opts.forEach(function (opt) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'theme-ctx__entry';
			b.setAttribute('role', 'menuitem');
			b.textContent = (cur === opt.id ? '✓ ' : '') + opt.label;
			b.addEventListener('click', function () {
				closeThemeCtx();
				setThemePref(opt.id);
			});
			themeCtx.appendChild(b);
		});
		themeCtx.hidden = false;
		var w = 180;
		var h = 10 + opts.length * 37;
		var rect = e.currentTarget.getBoundingClientRect();
		var left = rect.right - w;
		var top = rect.bottom + 6;
		themeCtx.style.left =
			Math.max(8, Math.min(left, window.innerWidth - w - 12)) + 'px';
		themeCtx.style.top =
			Math.max(8, Math.min(top, window.innerHeight - h - 12)) + 'px';
	}

	document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
		btn.addEventListener('click', toggleTheme);
		btn.addEventListener('contextmenu', openThemeMenu);
	});
	document.addEventListener('click', closeThemeCtx);
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeThemeCtx();
		}
	});
	syncToggleChrome();

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
	 * Fleet preview — Austin's own sites. Search, facet chips (removable),
	 * a + Filter dropdown with plugin version/status sub-facets, and a
	 * row context menu. Open site is a real new-tab link.
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
			var img = row.querySelector('.fleet__thumb img');
			if (!img) {
				return;
			}
			function hideBroken() {
				img.hidden = true;
			}
			img.addEventListener('error', hideBroken);
			if (img.complete && img.naturalWidth === 0) {
				hideBroken();
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

		function renderPins() {
			var wrap = fleetPane.querySelector('[data-fleet-pins]');
			var list = fleetPane.querySelector('[data-fleet-pins-list]');
			if (!wrap || !list) {
				return;
			}
			list.innerHTML = '';
			if (!fleet.pinned.length) {
				wrap.hidden = true;
				return;
			}
			wrap.hidden = false;
			fleet.pinned.forEach(function (row) {
				var chip = document.createElement('span');
				chip.className = 'fleet-pin';
				chip.setAttribute('role', 'button');
				chip.tabIndex = 0;
				var dot = document.createElement('span');
				dot.className = 'fleet-pin__dot';
				var name = document.createElement('span');
				name.className = 'fleet-pin__name';
				name.textContent = row.dataset.fleetDomain;
				var x = document.createElement('button');
				x.type = 'button';
				x.className = 'fleet-pin__x';
				x.title = 'Unpin';
				x.setAttribute('aria-label', 'Unpin ' + row.dataset.fleetDomain);
				x.textContent = '✕';
				x.addEventListener('click', function (e) {
					e.stopPropagation();
					togglePin(row);
				});
				chip.appendChild(dot);
				chip.appendChild(name);
				chip.appendChild(x);
				chip.addEventListener('click', function (e) {
					e.stopPropagation();
					var url = row.dataset.fleetUrl || ('https://' + row.dataset.fleetDomain);
					window.open(url, '_blank', 'noopener,noreferrer');
				});
				chip.addEventListener('contextmenu', function (e) {
					openRowMenu(e, row);
				});
				list.appendChild(chip);
			});
		}

		function togglePin(row) {
			var i = fleet.pinned.indexOf(row);
			if (i === -1) {
				fleet.pinned.unshift(row);
			} else {
				fleet.pinned.splice(i, 1);
			}
			apply();
		}

		function apply() {
			var shown = 0;
			fleet.rows.forEach(function (row) {
				var match = rowMatches(row);
				row.hidden = !match;
				if (match) {
					shown++;
				}
			});
			renderPins();
			if (emptyEl) {
				emptyEl.hidden = shown !== 0;
			}
			if (countEl) {
				var total = fleet.rows.length;
				countEl.textContent = (fleet.chips.length || fleet.q)
					? fmt(shown) + ' of ' + fmt(total) + ' sites'
					: fmt(total) + ' sites';
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
					list.appendChild(ddOption(o.name, fmt(o.count) + ' site' + (o.count === 1 ? '' : 's'), function () {
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

		function openRowMenu(e, row) {
			var domain = row.dataset.fleetDomain;
			var url = row.dataset.fleetUrl || ('https://' + domain);
			var pinned = fleet.pinned.indexOf(row) !== -1;
			openCtx(e, [
				{ label: 'Open site', act: function () { window.open(url, '_blank', 'noopener,noreferrer'); } },
				{ label: 'Login to WordPress ↗', act: function () { toast('One-click magic login lives in the real console.'); } },
				{ label: pinned ? 'Unpin' : 'Pin to top', act: function () { togglePin(row); } },
				{ label: 'Visit site ↗', act: function () { window.open(url, '_blank', 'noopener,noreferrer'); } },
				{ label: 'Open terminal', act: function () {
					showConsoleTab('terminal');
					if (window.anchorTermPrefill) window.anchorTermPrefill(row.dataset.fleetDomain);
				} },
				{ label: 'Copy domain', act: function () {
					if (navigator.clipboard) {
						navigator.clipboard.writeText(domain).then(function () { toast('Copied ' + domain + '.'); }).catch(function () {});
					}
				} },
			]);
		}

		fleet.rows.forEach(function (row) {
			row.querySelectorAll('a').forEach(function (a) {
				a.addEventListener('click', function (e) { e.stopPropagation(); });
			});
			var handler = function (e) { openRowMenu(e, row); };
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
	 * Terminal tab — v3 Activity dock: @ targets, cookbook, run.
	 * Commands are real WP-CLI; selected environments are the scope.
	 * ------------------------------------------------------------------ */

	var termPane = consoleRoot ? consoleRoot.querySelector('[data-console-pane="terminal"]') : null;
	if (termPane) {
		var termTargets = [];
		var termRecipes = [];
		try { termTargets = JSON.parse(termPane.querySelector('[data-term-targets]').textContent) || []; } catch (e) {}
		try { termRecipes = JSON.parse(termPane.querySelector('[data-term-recipes]').textContent) || []; } catch (e) {}

		var termSel = [];
		var termLinesEl = termPane.querySelector('[data-term-lines]');
		var termIdleEl = termPane.querySelector('[data-term-idle]');
		var termInput = termPane.querySelector('[data-term-input]');
		var termRunBtn = termPane.querySelector('[data-term-run]');
		var termTp = termPane.querySelector('[data-term-tp]');
		var termCook = termPane.querySelector('[data-term-cook]');
		var termTpBtn = termPane.querySelector('[data-term-tp-btn]');
		var termCookBtn = termPane.querySelector('[data-term-cook-btn]');
		var termTpLabel = termPane.querySelector('[data-term-tp-label]');
		var termTpList = termPane.querySelector('[data-term-tp-list]');
		var termTpQ = termPane.querySelector('[data-term-tp-q]');
		var termTpCount = termPane.querySelector('[data-term-tp-count]');
		var termTpClear = termPane.querySelector('[data-term-tp-clear]');
		var termCookList = termPane.querySelector('[data-term-cook-list]');
		var termCookQ = termPane.querySelector('[data-term-cook-q]');

		function selectedTargets() {
			return termTargets.filter(function (t) { return termSel.indexOf(t.id) !== -1; });
		}

		function closeTermPops() {
			if (termTp) termTp.hidden = true;
			if (termCook) termCook.hidden = true;
		}

		function placeTermPop(pop, anchor, align) {
			if (!pop || !anchor) return;
			if (pop.parentNode !== document.body) {
				document.body.appendChild(pop);
			}
			pop.hidden = false;
			var r = anchor.getBoundingClientRect();
			var pw = Math.min(360, window.innerWidth - 24);
			pop.style.width = pw + 'px';
			var left = align === 'right' ? r.right - pw : r.left;
			left = Math.max(12, Math.min(left, window.innerWidth - pw - 12));
			var h = pop.offsetHeight;
			var top = r.top - 10 - h;
			if (top < 12) {
				top = r.bottom + 10;
			}
			pop.style.left = left + 'px';
			pop.style.top = Math.max(12, top) + 'px';
		}

		function syncTargetChip() {
			var n = termSel.length;
			termTpLabel.textContent = n === 0 ? 'Select target' : n === 1 ? selectedTargets()[0].label : n + ' environments selected';
			termTpBtn.classList.toggle('is-on', n > 0);
			termTpCount.textContent = n + ' selected';
			termTpClear.hidden = n === 0;
		}

		function syncRun() {
			termRunBtn.disabled = !(termInput.value || '').trim();
		}

		function renderTpList() {
			var q = (termTpQ.value || '').trim().toLowerCase();
			termTpList.innerHTML = '';
			termTargets.filter(function (t) {
				return !q || (t.label + ' ' + t.url).toLowerCase().indexOf(q) !== -1;
			}).forEach(function (t) {
				var on = termSel.indexOf(t.id) !== -1;
				var b = document.createElement('button');
				b.type = 'button';
				b.className = 'term__pop-opt' + (on ? ' is-on' : '');
				b.innerHTML = '<span class="term__pop-mark">' + (on ? '✓' : '') + '</span><span><div class="term__pop-opt-title"></div><div class="term__pop-opt-sub"></div></span>';
				b.querySelector('.term__pop-opt-title').textContent = t.label;
				b.querySelector('.term__pop-opt-sub').textContent = t.url;
				b.addEventListener('click', function (e) {
					e.stopPropagation();
					var i = termSel.indexOf(t.id);
					if (i === -1) termSel.push(t.id); else termSel.splice(i, 1);
					syncTargetChip();
					renderTpList();
					placeTermPop(termTp, termTpBtn, 'left');
				});
				termTpList.appendChild(b);
			});
		}

		function renderCookList() {
			var q = (termCookQ.value || '').trim().toLowerCase();
			termCookList.innerHTML = '';
			termRecipes.filter(function (r) {
				return !q || (r.title || '').toLowerCase().indexOf(q) !== -1;
			}).forEach(function (r) {
				var b = document.createElement('button');
				b.type = 'button';
				b.className = 'term__pop-opt';
				b.innerHTML = '<span><div class="term__pop-opt-title"></div><div class="term__pop-opt-sub"></div></span>';
				b.querySelector('.term__pop-opt-title').textContent = r.title;
				b.querySelector('.term__pop-opt-sub').textContent = r.sub || '';
				b.addEventListener('click', function (e) {
					e.stopPropagation();
					termInput.value = r.content || '';
					termInput.style.height = 'auto';
					termInput.style.height = Math.min(termInput.scrollHeight, 160) + 'px';
					termInput.focus();
					syncRun();
					closeTermPops();
				});
				termCookList.appendChild(b);
			});
		}

		function appendLine(text, cls) {
			if (termIdleEl) {
				termIdleEl.remove();
				termIdleEl = null;
			}
			var div = document.createElement('div');
			div.className = 'term__line' + (cls ? ' ' + cls : '');
			div.textContent = text;
			termLinesEl.appendChild(div);
			termPane.querySelector('.term__scroll').scrollTop = 99999;
		}

		function fakeOutput(cmd, targets) {
			appendLine('$ ' + cmd, 'term__line--cmd');
			targets.forEach(function (t) {
				if (/plugin list/.test(cmd)) {
					appendLine(t.label);
					var plugs = t.plugins || {};
					var keys = Object.keys(plugs);
					if (!keys.length) {
						appendLine('  (no plugins)');
					} else {
						keys.forEach(function (slug) {
							var p = plugs[slug];
							appendLine('  ' + slug + '\t' + (p.s || 'active') + '\t' + (p.v || ''));
						});
					}
				} else if (/core version/.test(cmd)) {
					appendLine(t.core || '');
				} else if (/option get home/.test(cmd)) {
					appendLine(t.url || '');
				} else if (/plugin update/.test(cmd)) {
					appendLine('✔ ' + t.label + ' · plugins updated', 'term__line--ok');
				} else {
					appendLine(t.label);
					appendLine('ok');
				}
			});
		}

		function termRun() {
			var cmd = (termInput.value || '').trim();
			if (!cmd) return;
			var targets = selectedTargets();
			if (!targets.length) {
				closeTermPops();
				renderTpList();
				placeTermPop(termTp, termTpBtn, 'left');
				return;
			}
			fakeOutput(cmd, targets);
			termInput.value = '';
			termInput.style.height = 'auto';
			syncRun();
		}

		termTpBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = termTp.hidden;
			closeTermPops();
			if (open) {
				renderTpList();
				placeTermPop(termTp, termTpBtn, 'left');
				termTpQ.focus();
			}
		});
		termCookBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = termCook.hidden;
			closeTermPops();
			if (open) {
				renderCookList();
				placeTermPop(termCook, termCookBtn, 'left');
				termCookQ.focus();
			}
		});
		termTp.addEventListener('click', function (e) { e.stopPropagation(); });
		termCook.addEventListener('click', function (e) { e.stopPropagation(); });
		termTpQ.addEventListener('input', function () {
			renderTpList();
			if (!termTp.hidden) placeTermPop(termTp, termTpBtn, 'left');
		});
		termCookQ.addEventListener('input', function () {
			renderCookList();
			if (!termCook.hidden) placeTermPop(termCook, termCookBtn, 'left');
		});
		window.addEventListener('resize', function () {
			if (termTp && !termTp.hidden) placeTermPop(termTp, termTpBtn, 'left');
			if (termCook && !termCook.hidden) placeTermPop(termCook, termCookBtn, 'left');
		});
		termTpClear.addEventListener('click', function (e) {
			e.stopPropagation();
			termSel = [];
			syncTargetChip();
			renderTpList();
		});
		termRunBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			termRun();
		});
		termInput.addEventListener('input', function () {
			termInput.style.height = 'auto';
			termInput.style.height = Math.min(termInput.scrollHeight, 160) + 'px';
			syncRun();
		});
		termInput.addEventListener('keydown', function (e) {
			if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
				e.preventDefault();
				termRun();
			}
		});
		document.addEventListener('click', closeTermPops);
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') closeTermPops();
		});

		window.anchorTermPrefill = function (domain) {
			var t = termTargets.filter(function (x) { return x.site === domain && x.env === 'Prod'; })[0]
				|| termTargets.filter(function (x) { return x.site === domain; })[0];
			if (!t) return;
			if (termSel.indexOf(t.id) === -1) termSel = [t.id];
			syncTargetChip();
		};

		syncTargetChip();
		syncRun();
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

			var external = isExternalUrl(cmd.url);
			html +=
				'<button type="button" role="option" aria-selected="' +
				(i === cursor ? 'true' : 'false') +
				'" class="palette__item' +
				(i === cursor ? ' is-active' : '') +
				'" data-index="' +
				i +
				'"' +
				(external ? ' aria-label="' + escapeHtml(cmd.label) + ' (opens in a new tab)"' : '') +
				'>' +
				'<span class="palette__label">' +
				escapeHtml(cmd.label) +
				'</span>' +
				(external
					? '<span class="palette__kind palette__kind--external">' + externalIcon + '</span>'
					: '<span class="palette__kind">' + escapeHtml(cmd.kind || '') + '</span>') +
				'</button>';
		});

		resultsEl.innerHTML = html;

		var active = resultsEl.querySelector('.palette__item.is-active');
		if (active && active.scrollIntoView) {
			active.scrollIntoView({ block: 'nearest' });
		}
	}

	function isExternalUrl(url) {
		if (!url) {
			return false;
		}
		try {
			return new URL(url, window.location.origin).origin !== window.location.origin;
		} catch (e) {
			return false;
		}
	}

	var externalIcon =
		'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
		'<path d="M15 3h6v6"></path><path d="M10 14 21 3"></path>' +
		'<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>' +
		'</svg>';

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
