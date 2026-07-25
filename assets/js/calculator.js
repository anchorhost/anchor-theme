/**
 * Plans page — billing cycle switch and the "size it yourself" calculator.
 *
 * The quote logic mirrors anchor_quote() in inc/template-tags.php. If one
 * changes, change the other.
 */
(function () {
	'use strict';

	var data = window.anchorPlans || {};
	var PLANS = Array.isArray(data.plans) ? data.plans : [];
	var RATES = data.addons || { site: 12.5, storage: 10, pageviews: 100 };

	if (!PLANS.length) {
		return;
	}

	var CYCLES = {
		monthly: { mult: 1, period: '/mo', word: 'monthly' },
		quarterly: { mult: 3, period: '/quarter', word: 'quarterly' },
		yearly: { mult: 12, period: '/year', word: 'yearly' }
	};

	var cycle = 'monthly';

	function money(n) {
		var rounded = Math.round(n * 100) / 100;
		return (
			'$' +
			rounded.toLocaleString('en-US', {
				minimumFractionDigits: rounded % 1 === 0 ? 0 : 2,
				maximumFractionDigits: 2
			})
		);
	}

	/* ------------------------------------------------------------------
	 * Billing cycle
	 * ------------------------------------------------------------------ */

	function applyCycle() {
		var c = CYCLES[cycle];

		document.querySelectorAll('[data-cycle]').forEach(function (btn) {
			var active = btn.dataset.cycle === cycle;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
		});

		document.querySelectorAll('.plan').forEach(function (card) {
			var priceEl = card.querySelector('[data-plan-price]');
			var periodEl = card.querySelector('[data-plan-period]');
			var equivEl = card.querySelector('[data-plan-equiv]');

			if (!priceEl) {
				return;
			}

			var base = parseFloat(priceEl.dataset.base);
			priceEl.textContent = money(base * c.mult);

			if (periodEl) {
				periodEl.textContent = c.period;
			}

			if (equivEl) {
				equivEl.textContent =
					cycle === 'monthly' ? 'billed monthly' : money(base) + '/mo equivalent';
			}
		});

		var word = document.querySelector('[data-calc-cycle-word]');
		if (word) {
			word.textContent = c.word;
		}
	}

	document.querySelectorAll('[data-cycle]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			cycle = btn.dataset.cycle;
			applyCycle();
		});
	});

	/* ------------------------------------------------------------------
	 * Calculator
	 * ------------------------------------------------------------------ */

	var calc = document.querySelector('[data-calculator]');

	if (!calc) {
		return;
	}

	var sitesEl = calc.querySelector('[data-calc-sites]');
	var storageEl = calc.querySelector('[data-calc-storage]');
	var viewsEl = calc.querySelector('[data-calc-views]');

	var sitesLabel = calc.querySelector('[data-calc-sites-label]');
	var storageLabel = calc.querySelector('[data-calc-storage-label]');
	var viewsLabel = calc.querySelector('[data-calc-views-label]');

	var planEl = calc.querySelector('[data-calc-plan]');
	var linesEl = calc.querySelector('[data-calc-lines]');
	var totalEl = calc.querySelector('[data-calc-total]');

	function quote(sites, storage, views) {
		var best = null;

		PLANS.forEach(function (p) {
			var over = {
				sites: Math.max(0, sites - p.sites) * RATES.site,
				gb: Math.ceil(Math.max(0, storage - p.gb) / 10) * RATES.storage,
				pv: Math.ceil(Math.max(0, views - p.pv) / 1000000) * RATES.pageviews
			};

			var total = p.m + over.sites + over.gb + over.pv;

			if (!best || total < best.total) {
				best = { plan: p, over: over, total: total };
			}
		});

		return best;
	}

	function formatViews(v) {
		return v >= 1000000
			? String((v / 1000000).toFixed(1)).replace(/\.0$/, '') + 'M'
			: v / 1000 + 'k';
	}

	function update() {
		var sites = parseInt(sitesEl.value, 10);
		var storage = parseInt(storageEl.value, 10);
		var views = parseInt(viewsEl.value, 10);

		sitesLabel.textContent = sites;
		storageLabel.textContent = storage + ' GB';
		viewsLabel.textContent = formatViews(views);

		var q = quote(sites, storage, views);

		planEl.textContent = q.plan.name;
		totalEl.textContent = money(q.total);

		var lines = [{ label: q.plan.name + ' base', amount: money(q.plan.m) }];

		if (q.over.sites) {
			lines.push({ label: '+' + (sites - q.plan.sites) + ' sites', amount: money(q.over.sites) });
		}
		if (q.over.gb) {
			lines.push({
				label: '+' + (storage - q.plan.gb) + ' GB storage',
				amount: money(q.over.gb)
			});
		}
		if (q.over.pv) {
			lines.push({ label: 'extra pageviews', amount: money(q.over.pv) });
		}
		if (lines.length === 1) {
			lines.push({ label: 'no add-ons needed', amount: '$0' });
		}

		linesEl.innerHTML = lines
			.map(function (l) {
				return (
					'<div class="calc__line"><span>' +
					escapeHtml(l.label) +
					'</span><span>' +
					escapeHtml(l.amount) +
					'</span></div>'
				);
			})
			.join('');
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
		});
	}

	[sitesEl, storageEl, viewsEl].forEach(function (el) {
		if (el) {
			el.addEventListener('input', update);
		}
	});

	applyCycle();
	update();
})();
