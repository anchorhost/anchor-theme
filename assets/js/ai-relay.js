/**
 * AI Relay page. One script for every panel anchor_ai_relay_view() can show:
 *
 *   gate    email signup  → POST /ai-relay/signup
 *   finish  password      → POST /ai-relay/signup/finish, then reload signed in
 *   form    files upload one per request as they are dropped (POST/DELETE
 *           /ai-relay/files), the card goes to Stripe's hosted field, and the
 *           submission posts only the Stripe source id (POST /ai-relay/requests).
 *
 * Every route lives in CaptainCore Manager (app/AiRelay.php).
 */
(function () {
	'use strict';

	var cfg = window.anchorRelay || {};
	var t = cfg.i18n || {};

	function api(path, opts) {
		opts = opts || {};
		var headers = { 'X-WP-Nonce': cfg.nonce || '' };
		var body = opts.body;
		if (body && !(body instanceof FormData)) {
			headers['Content-Type'] = 'application/json';
			body = JSON.stringify(body);
		}
		return fetch(cfg.rest + path, {
			method: opts.method || 'POST',
			credentials: 'same-origin',
			headers: headers,
			body: body
		}).then(function (res) {
			return res.json().catch(function () { return {}; }).then(function (data) {
				if (!res.ok || (data && data.code && data.message)) {
					throw new Error((data && data.message) || t.failed);
				}
				return data;
			});
		});
	}

	function notifier(el) {
		return function (msg) {
			if (!el) return;
			el.textContent = msg || '';
			el.hidden = !msg;
		};
	}

	function busy(button, on) {
		if (!button) return;
		button.disabled = on;
		button.classList.toggle('is-busy', on);
	}

	/* --- Gate: create an account by email -------------------------------- */

	var signup = document.querySelector('[data-relay-signup]');
	if (signup) {
		var sayGate = notifier(signup.querySelector('[data-relay-notice]'));
		signup.addEventListener('submit', function (e) {
			e.preventDefault();
			var email = signup.elements.email;
			if (!email.value.trim() || !email.checkValidity()) {
				sayGate(t.needEmail);
				email.focus();
				return;
			}
			var tokenField = signup.querySelector('[name="cf-turnstile-response"]');
			var button = signup.querySelector('button[type="submit"]');
			busy(button, true);
			api('/signup', { body: {
				email: email.value.trim(),
				name: signup.elements.name.value.trim(),
				turnstile: tokenField ? tokenField.value : ''
			} }).then(function (data) {
				signup.querySelectorAll('.field, .cf-turnstile, button').forEach(function (el) { el.hidden = true; });
				sayGate(data.message);
			}).catch(function (err) {
				busy(button, false);
				if (window.turnstile) window.turnstile.reset();
				sayGate(err.message);
			});
		});
	}

	/* --- Finish: choose a password --------------------------------------- */

	var finish = document.querySelector('[data-relay-finish]');
	if (finish) {
		var sayFinish = notifier(finish.querySelector('[data-relay-notice]'));
		finish.addEventListener('submit', function (e) {
			e.preventDefault();
			var pass = finish.elements.password.value;
			if (pass.length < 10 || !/[a-z]/i.test(pass) || !/[0-9]/.test(pass)) {
				sayFinish(t.weakPass);
				finish.elements.password.focus();
				return;
			}
			var button = finish.querySelector('button[type="submit"]');
			busy(button, true);
			api('/signup/finish', { body: { token: finish.elements.token.value, password: pass } })
				.then(function () {
					// Signed in now. Reload without the token so the form renders
					// with a nonce that belongs to the new session.
					location.replace(location.pathname + '#relay-start');
				})
				.catch(function (err) {
					busy(button, false);
					sayFinish(err.message);
				});
		});
	}

	/* --- Form ------------------------------------------------------------ */

	var form = document.querySelector('[data-relay-form]');
	if (!form) {
		return;
	}

	var limits = cfg.limits || {};
	var maxFiles = Number(limits.files) || 40;
	var maxBytes = Number(limits.bytes) || 250 * 1024 * 1024;
	var fileBytes = Number(limits.file_bytes) || maxBytes;
	var extensions = limits.extensions || [];

	var drop = form.querySelector('[data-relay-drop]');
	var input = form.querySelector('[data-relay-input]');
	var wrap = form.querySelector('[data-relay-files]');
	var list = form.querySelector('[data-relay-list]');
	var count = form.querySelector('[data-relay-count]');
	var size = form.querySelector('[data-relay-size]');
	var port = form.querySelector('[data-relay-port]');
	var submitBtn = form.querySelector('[data-relay-submit]');
	var say = notifier(form.querySelector('[data-relay-notice]'));

	// Each entry: { id, name, size, state: 'done'|'uploading'|'error', error, file }
	var files = (cfg.staged || []).map(function (f) {
		return { id: f.id, name: f.name, size: Number(f.size), state: 'done' };
	});

	function human(bytes) {
		if (bytes < 1024) return bytes + ' B';
		if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
		return (bytes / 1024 / 1024).toFixed(1) + ' MB';
	}

	function live() {
		return files.filter(function (f) { return f.state !== 'error'; });
	}

	function total() {
		return live().reduce(function (sum, f) { return sum + f.size; }, 0);
	}

	function icon(name) {
		var paths = {
			file: '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z"></path><path d="M14 3v5h5"></path>',
			close: '<path d="M6 6l12 12M18 6 6 18"></path>'
		};
		return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + paths[name] + '</svg>';
	}

	function render() {
		list.querySelectorAll('img[data-url]').forEach(function (img) { URL.revokeObjectURL(img.src); });
		list.innerHTML = '';

		files.forEach(function (f) {
			var li = document.createElement('li');
			li.className = 'relay-file' + (f.state === 'uploading' ? ' is-uploading' : '') + (f.state === 'error' ? ' is-error' : '');

			var thumb;
			if (f.file && /^image\//.test(f.file.type)) {
				thumb = document.createElement('img');
				thumb.src = URL.createObjectURL(f.file);
				thumb.setAttribute('data-url', '');
				thumb.alt = '';
			} else {
				thumb = document.createElement('span');
				thumb.innerHTML = icon('file');
			}
			thumb.className = 'relay-file__thumb';

			var meta = document.createElement('span');
			meta.className = 'relay-file__meta';
			var name = document.createElement('span');
			name.className = 'relay-file__name';
			name.textContent = f.name;
			name.title = f.name;
			var sz = document.createElement('span');
			sz.className = 'relay-file__size';
			sz.textContent = f.state === 'uploading' ? (t.uploading || 'Uploading') + '…'
				: f.state === 'error' ? f.error
				: human(f.size);
			meta.appendChild(name);
			meta.appendChild(sz);

			var rm = document.createElement('button');
			rm.type = 'button';
			rm.className = 'relay-file__remove';
			rm.disabled = f.state === 'uploading';
			rm.setAttribute('aria-label', (t.remove || 'Remove') + ' ' + f.name);
			rm.innerHTML = icon('close');
			rm.addEventListener('click', function () { remove(f); });

			li.appendChild(thumb);
			li.appendChild(meta);
			li.appendChild(rm);
			list.appendChild(li);
		});

		var n = live().length;
		wrap.hidden = !files.length;
		count.textContent = n === 1 ? (t.fileOne || '1 file') : (t.fileCount || '%d files').replace('%d', n);
		size.textContent = human(total());
	}

	function remove(f) {
		files.splice(files.indexOf(f), 1);
		render();
		if (f.id) {
			api('/files/' + f.id, { method: 'DELETE' }).catch(function () {});
		}
	}

	function upload(f) {
		var body = new FormData();
		body.append('file', f.file, f.name);
		return api('/files', { body: body }).then(function (data) {
			f.id = data.id;
			f.state = 'done';
		}).catch(function (err) {
			f.state = 'error';
			f.error = err.message || t.uploadFail;
		}).then(render);
	}

	// Uploads run one after another so a big drop does not open 40 requests.
	var queue = Promise.resolve();

	function add(incoming) {
		var skippedCount = false;
		var skippedSize = false;
		var bytes = total();

		Array.prototype.forEach.call(incoming, function (file) {
			var dup = files.some(function (f) { return f.name === file.name && f.size === file.size && f.state !== 'error'; });
			if (dup) return;
			if (live().length >= maxFiles) { skippedCount = true; return; }
			if (bytes + file.size > maxBytes) { skippedSize = true; return; }

			var ext = (file.name.split('.').pop() || '').toLowerCase();
			var entry = { name: file.name, size: file.size, file: file, state: 'uploading' };
			if (extensions.length && extensions.indexOf(ext) === -1) {
				entry.state = 'error';
				entry.error = t.badType;
			} else if (file.size > fileBytes) {
				entry.state = 'error';
				entry.error = (t.tooLarge || 'Too large') + ' (' + human(fileBytes) + ')';
			} else {
				bytes += file.size;
				queue = queue.then(function () { return upload(entry); });
			}
			files.push(entry);
		});

		say(skippedCount ? t.tooMany : skippedSize ? t.tooBig : '');
		render();
	}

	input.addEventListener('change', function () {
		add(Array.prototype.slice.call(input.files));
		input.value = '';
	});

	['dragenter', 'dragover'].forEach(function (type) {
		drop.addEventListener(type, function (e) {
			e.preventDefault();
			drop.classList.add('is-over');
		});
	});

	['dragleave', 'dragend', 'drop'].forEach(function (type) {
		drop.addEventListener(type, function (e) {
			if (type === 'dragleave' && drop.contains(e.relatedTarget)) return;
			drop.classList.remove('is-over');
		});
	});

	drop.addEventListener('drop', function (e) {
		e.preventDefault();
		if (e.dataTransfer && e.dataTransfer.files.length) {
			add(e.dataTransfer.files);
		}
	});

	// A file dropped just outside the zone would otherwise navigate away.
	window.addEventListener('dragover', function (e) { e.preventDefault(); });
	window.addEventListener('drop', function (e) { e.preventDefault(); });

	form.querySelectorAll('input[name="relay_mode"]').forEach(function (radio) {
		radio.addEventListener('change', function () {
			port.hidden = radio.value !== 'port' || !radio.checked;
			if (!port.hidden) port.querySelector('input').focus();
		});
	});

	/* --- Card ------------------------------------------------------------ */

	var cardNew = form.querySelector('[data-relay-card-new]');
	var saved = form.querySelector('[data-relay-saved]');
	var billing = form.querySelector('[data-relay-billing]');
	var stripe = null;
	var card = null;

	function mountCard() {
		if (card || !cfg.stripeKey || !window.Stripe) return;
		stripe = window.Stripe(cfg.stripeKey);
		var style = getComputedStyle(document.documentElement);
		card = stripe.elements().create('card', {
			hidePostalCode: !!billing,
			style: {
				base: {
					color: style.getPropertyValue('--text').trim() || '#15181D',
					fontFamily: 'system-ui, -apple-system, sans-serif',
					fontSize: '15px',
					'::placeholder': { color: style.getPropertyValue('--text-3').trim() || '#666D7A' }
				},
				invalid: { color: style.getPropertyValue('--bad').trim() || '#BF3B2E' }
			}
		});
		card.mount('#relay-card-element');
	}

	function usingNewCard() {
		return cardNew && !cardNew.hidden;
	}

	var change = form.querySelector('[data-relay-new-card]');
	if (change) {
		change.addEventListener('click', function () {
			saved.hidden = true;
			cardNew.hidden = false;
			mountCard();
		});
	}
	if (usingNewCard()) {
		mountCard();
	}

	function billingValues() {
		var out = {};
		if (!billing) return out;
		billing.querySelectorAll('input, select').forEach(function (el) { out[el.name] = el.value.trim(); });
		return out;
	}

	function createSource() {
		var b = billingValues();
		var owner = { email: cfg.email || undefined };
		if (billing) {
			owner.name = (b.first_name + ' ' + b.last_name).trim();
			owner.address = { line1: b.address_1, city: b.city, state: b.state, postal_code: b.postcode, country: b.country };
		}
		return stripe.createSource(card, { type: 'card', owner: owner }).then(function (result) {
			if (result.error) throw new Error(result.error.message);
			return result.source.id;
		});
	}

	/* --- Submit ---------------------------------------------------------- */

	form.addEventListener('submit', function (e) {
		e.preventDefault();

		var url = form.elements.relay_url.value.trim();
		var notes = form.elements.relay_notes.value.trim();
		var mode = form.querySelector('input[name="relay_mode"]:checked').value;

		if (files.some(function (f) { return f.state === 'uploading'; })) {
			say(t.waitUploads);
			return;
		}
		if (!live().length && !notes && !(mode === 'port' && url)) {
			say(t.needInput);
			input.focus();
			return;
		}
		if (usingNewCard()) {
			if (!card) {
				say(t.cardUnavailable);
				return;
			}
			if (billing) {
				var missing = Array.prototype.find.call(billing.querySelectorAll('[required]'), function (el) { return !el.value.trim(); });
				if (missing) {
					say(t.needBilling);
					missing.focus();
					return;
				}
			}
		}

		say('');
		busy(submitBtn, true);
		var label = submitBtn.querySelector('span');
		var original = label.textContent;
		label.textContent = t.submitting;

		(usingNewCard() ? createSource() : Promise.resolve(''))
			.then(function (sourceId) {
				return api('/requests', { body: {
					mode: mode,
					url: url,
					notes: notes,
					site_name: form.elements.relay_site_name.value.trim(),
					billing: billingValues(),
					source_id: sourceId
				} });
			})
			.then(function (res) {
				form.hidden = true;
				var done = document.querySelector('[data-relay-done]');
				var link = done.querySelector('[data-relay-project-link]');
				if (link && res && res.project) link.href += res.project.id;
				done.hidden = false;
				done.scrollIntoView({ block: 'center' });
			})
			.catch(function (err) {
				busy(submitBtn, false);
				label.textContent = original;
				say(err.message);
			});
	});

	render();
})();
