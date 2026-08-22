/**
 * Hosting Plan Calculator — base plan choice, add-on steppers, billing cycles.
 *
 * Prices arrive localized from anchor_plans() / anchor_addon_rates(); the
 * initial state is server-rendered by template-parts/pages/calculator.php.
 * This is deliberately not the plans-page quote rule — the visitor picks the
 * base plan themselves and the receipt is plain arithmetic on top of it.
 */
(function () {
	'use strict';

	var data = window.anchorPlans || {};
	var PLANS = Array.isArray(data.plans) ? data.plans : [];
	var RATES = data.addons || { site: 12.5, storage: 10, pageviews: 100 };

	var root = document.querySelector('[data-builder]');

	if (!root || !PLANS.length) {
		return;
	}

	var CYCLES = {
		monthly: { mult: 1, period: '/mo', per: 'per month', word: 'monthly' },
		quarterly: { mult: 3, period: '/quarter', per: 'per quarter', word: 'quarterly' },
		yearly: { mult: 12, period: '/year', per: 'per year', word: 'yearly' }
	};

	var cycle = 'monthly';
	var plan = PLANS[0];
	var extras = { sites: 0, storage: 0, views: 0 };

	var planEl = root.querySelector('[data-builder-plan]');
	var linesEl = root.querySelector('[data-builder-lines]');
	var totalEl = root.querySelector('[data-builder-total]');
	var periodEl = root.querySelector('[data-builder-period]');
	var cycleWordEl = root.querySelector('[data-builder-cycle-word]');
	var persiteEl = root.querySelector('[data-builder-persite]');
	var sumSitesEl = root.querySelector('[data-sum-sites]');
	var sumStorageEl = root.querySelector('[data-sum-storage]');
	var sumViewsEl = root.querySelector('[data-sum-views]');
	var sitesNoteEl = root.querySelector('[data-extra-note-sites]');

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

	function formatViews(v) {
		return v >= 1000000
			? String((v / 1000000).toFixed(1)).replace(/\.0$/, '') + 'M'
			: v / 1000 + 'k';
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
		});
	}

	function update() {
		var c = CYCLES[cycle];

		// Billing pills + per-card prices (same pattern as calculator.js).
		document.querySelectorAll('[data-cycle]').forEach(function (btn) {
			var active = btn.dataset.cycle === cycle;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
		});

		root.querySelectorAll('.plan--select').forEach(function (card) {
			var input = card.querySelector('[data-plan-choice]');
			var pick = card.querySelector('[data-pick-label]');
			var priceEl = card.querySelector('[data-plan-price]');
			var periodTag = card.querySelector('[data-plan-period]');
			var equivEl = card.querySelector('[data-plan-equiv]');
			var on = input && input.checked;

			card.classList.toggle('is-selected', !!on);

			if (pick && input) {
				pick.textContent = on ? 'Selected' : 'Choose ' + input.value;
			}
			if (priceEl) {
				var base = parseFloat(priceEl.dataset.base);
				priceEl.textContent = money(base * c.mult);
				if (periodTag) {
					periodTag.textContent = c.period;
				}
				if (equivEl) {
					equivEl.textContent =
						cycle === 'monthly' ? 'billed monthly' : money(base) + '/mo equivalent';
				}
			}
		});

		// Receipt.
		var monthly =
			plan.m +
			extras.sites * RATES.site +
			extras.storage * RATES.storage +
			extras.views * RATES.pageviews;

		var lines = [{ label: plan.name + ' base', amount: money(plan.m * c.mult) }];

		if (extras.sites) {
			lines.push({
				label: '+' + extras.sites + (extras.sites === 1 ? ' site' : ' sites'),
				amount: money(extras.sites * RATES.site * c.mult)
			});
		}
		if (extras.storage) {
			lines.push({
				label: '+' + extras.storage * 10 + ' GB storage',
				amount: money(extras.storage * RATES.storage * c.mult)
			});
		}
		if (extras.views) {
			lines.push({
				label: '+' + extras.views + 'M pageviews/yr',
				amount: money(extras.views * RATES.pageviews * c.mult)
			});
		}
		if (lines.length === 1) {
			lines.push({ label: 'no extras added', amount: '$0' });
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

		planEl.textContent = plan.name;
		totalEl.textContent = money(monthly * c.mult);
		periodEl.textContent = c.per;
		cycleWordEl.textContent = c.word;

		// Capacity + per-site.
		var sitesTotal = plan.sites + extras.sites;

		sumSitesEl.textContent = sitesTotal;
		sumStorageEl.textContent = plan.gb + extras.storage * 10 + ' GB';
		sumViewsEl.textContent = formatViews(plan.pv + extras.views * 1000000) + ' pageviews/yr';
		persiteEl.textContent = money(monthly / sitesTotal) + '/mo per site';

		if (sitesNoteEl) {
			sitesNoteEl.textContent = 'beyond the ' + plan.sites + ' included';
		}
	}

	/* ------------------------------------------------------------------
	 * Wiring
	 * ------------------------------------------------------------------ */

	document.querySelectorAll('[data-cycle]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			cycle = btn.dataset.cycle;
			update();
		});
	});

	root.querySelectorAll('[data-plan-choice]').forEach(function (input) {
		input.addEventListener('change', function () {
			var next = PLANS.filter(function (p) {
				return p.name === input.value;
			})[0];
			if (next) {
				plan = next;
			}
			update();
		});
	});

	root.querySelectorAll('[data-extra]').forEach(function (input) {
		var key = input.dataset.extra;

		function read() {
			var min = parseInt(input.min, 10) || 0;
			var max = parseInt(input.max, 10) || 0;
			var val = parseInt(input.value, 10);

			if (isNaN(val)) {
				val = 0;
			}
			val = Math.min(max, Math.max(min, val));
			input.value = val;
			extras[key] = val;
			update();
		}

		input.addEventListener('input', read);
		input.addEventListener('change', read);
	});

	root.querySelectorAll('[data-step]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var input = btn.parentElement.querySelector('[data-extra]');
			if (!input) {
				return;
			}
			input.value = (parseInt(input.value, 10) || 0) + parseInt(btn.dataset.step, 10);
			input.dispatchEvent(new Event('change'));
		});
	});

	update();
})();
