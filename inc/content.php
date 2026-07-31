<?php
/**
 * Marketing content model.
 *
 * Everything the design hardcodes lives here as a filterable array, so copy
 * changes never mean touching a template. Each function has a matching
 * `anchor_<name>` filter.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hosting plans. `m` is the monthly base price.
 */
function anchor_plans() {
	return apply_filters( 'anchor_plans', [
		[ 'name' => 'Basic',    'm' => 20,  'sites' => 1,  'gb' => 10, 'pv' => 100000 ],
		[ 'name' => 'Standard', 'm' => 50,  'sites' => 3,  'gb' => 15, 'pv' => 500000 ],
		[ 'name' => 'Business', 'm' => 100, 'sites' => 8,  'gb' => 20, 'pv' => 1000000 ],
		[ 'name' => 'Agency',   'm' => 200, 'sites' => 20, 'gb' => 30, 'pv' => 2000000 ],
	] );
}

/**
 * Which plan carries the "Most agencies" badge.
 */
function anchor_featured_plan() {
	return apply_filters( 'anchor_featured_plan', 'Business' );
}

/**
 * Overage pricing used by both the PHP render and the JS calculator.
 */
function anchor_addon_rates() {
	return apply_filters( 'anchor_addon_rates', [
		'site'      => 12.5,
		'storage'   => 10,
		'pageviews' => 100,
	] );
}

function anchor_billing_cycles() {
	return apply_filters( 'anchor_billing_cycles', [
		'monthly'   => [ 'label' => 'Monthly',   'mult' => 1,  'period' => '/mo',      'word' => 'monthly' ],
		'quarterly' => [ 'label' => 'Quarterly', 'mult' => 3,  'period' => '/quarter', 'word' => 'quarterly' ],
		'yearly'    => [ 'label' => 'Yearly',    'mult' => 12, 'period' => '/year',    'word' => 'yearly' ],
	] );
}

function anchor_plan_includes() {
	return apply_filters( 'anchor_plan_includes', [
		'Automated updates',
		'Nightly backups',
		'Security scanning',
		'Managed hosting',
		'HTTPS everywhere',
		'Free migration',
		'Premium themes',
		'Premium plugins',
		'Personal support',
	] );
}

/**
 * Hero copy.
 */
function anchor_hero() {
	return apply_filters( 'anchor_hero', [
		'flag'     => 'Hassle-free hosting for WordPress professionals',
		'since'    => 'since 2014',
		'title'    => 'Hand it off.<br />It gets done.',
		'lede'     => "Managed hosting for the people who manage everyone else's WordPress sites. DNS, migrations, updates, security and backups, handled by a real person and surfaced in one dashboard.",
		'facts'    => [
			'2,900+ sites under management',
			'800+ customers',
			'Free migrations',
		],
	] );
}

/**
 * The "Needs attention" dashboard panel in the hero.
 *
 * Mirrors the CaptainCore v3 home screen's all-clear state: on a normal
 * morning the feed is empty — that IS the product. Rows with `clear` are
 * the empty-state row and don't count toward the badge.
 */
function anchor_attention_rows() {
	return apply_filters( 'anchor_attention_rows', [
		[
			'clear'       => true,
			'tone'        => 'good',
			'label'       => 'All clear, nothing needs attention',
			'meta'        => '2,921 sites under management',
			'action'      => 'View sites',
			'action_tone' => 'good',
		],
	] );
}

/**
 * The "Handled for you" feed under the all-clear row — the same numbers the
 * old to-do rows carried, recast as work that already happened.
 */
function anchor_handled_rows() {
	return apply_filters( 'anchor_handled_rows', [
		[
			'label' => '20 security threats patched',
			'meta'  => 'Fleet-wide, before anyone asked · 20 critical',
			'time'  => '2h',
		],
		[
			'label' => '822 components updated',
			'meta'  => 'Plugins, themes and core, on schedule',
			'time'  => '7h',
		],
		[
			'label' => 'Nightly backups verified',
			'meta'  => '6.9 TB to redundant storage',
			'time'  => '02:14',
		],
	] );
}

function anchor_glance_rows() {
	return apply_filters( 'anchor_glance_rows', [
		[ 'label' => 'WP core', 'value' => '97% on 7.0.2' ],
		[ 'label' => 'Traffic', 'value' => '197.4M visits/wk' ],
		[ 'label' => 'Storage', 'value' => '6.9 TB' ],
	] );
}

function anchor_stats() {
	return apply_filters( 'anchor_stats', [
		[ 'value' => '2,900+', 'label' => 'Sites under management' ],
		[ 'value' => '800+',   'label' => 'Customers since 2014' ],
		[ 'value' => '20+',    'label' => 'Plugin vulnerabilities disclosed' ],
		[ 'value' => '3+',     'label' => 'Backdoor operations uncovered' ],
	] );
}

/**
 * The three "arrangement" cards. `icon` is a filename in assets/icons/.
 */
function anchor_arrangement_cards() {
	return apply_filters( 'anchor_arrangement_cards', [
		[
			'icon'  => 'captain.svg',
			'alt'   => 'Captain at the helm',
			'title' => 'Hands-off',
			'text'  => 'DNS, domains, migrations, updates, security. Hand it off and it gets done. No portal diving, no plugin wrangling, no vendor ping-pong.',
		],
		[
			'icon'  => 'lighthouse.svg',
			'alt'   => 'Lighthouse',
			'title' => 'Proactive',
			'text'  => 'Security scanning, uptime monitoring and performance checks run continuously. Issues get caught and fixed before a client emails you about them.',
		],
		[
			'icon'  => 'proactive.svg',
			'alt'   => 'Charted course',
			'title' => 'Complete',
			'text'  => 'Plugin, theme and core updates on schedule. Nightly backups to redundant storage. Premium licenses included. No extra tools to buy.',
		],
	] );
}

/**
 * Dashboard preview — fleet table rows. Fictional sites and agencies; the
 * real dashboard shows real customers, the marketing preview must not.
 */
function anchor_fleet_rows() {
	return apply_filters( 'anchor_fleet_rows', [
		[ 'site' => 'bakerstreetbistro.com',    'owner' => 'bakerstreetbistro.com',  'envs' => [ 'Staging', 'Prod' ], 'core' => '7.0.2', 'visits' => '302,874' ],
		[ 'site' => 'blueheronfarm.org',        'owner' => 'Harborlight Studio',     'envs' => [ 'Prod' ],            'core' => '7.0.2', 'visits' => '46,675' ],
		[ 'site' => 'cedarcreekdental.com',     'owner' => 'North & Main Creative',  'envs' => [ 'Prod' ],            'core' => '7.0.2', 'visits' => '1,327' ],
		[ 'site' => 'driftwoodgallery.com',     'owner' => 'Signal Hill Design',     'envs' => [ 'Staging', 'Prod' ], 'core' => '7.0.2', 'visits' => '1,897' ],
		[ 'site' => 'fairviewpediatrics.com',   'owner' => 'Brightworks Agency',     'envs' => [ 'Prod', 'Staging' ], 'core' => '7.0.2', 'visits' => '102,282' ],
		[ 'site' => 'graniteledgebuilders.com', 'owner' => 'Copperline Media',       'envs' => [ 'Prod', 'Staging' ], 'core' => '7.0.2', 'visits' => '202,872' ],
	] );
}

/**
 * Dashboard preview — the fleet filter bar. Mirrors the real console's
 * facet pills: slice the whole fleet by plugin (at a version and status),
 * core, host — then act on the slice.
 */
function anchor_fleet_filters() {
	return apply_filters( 'anchor_fleet_filters', [
		'search' => 'Filter sites…',
		'chips'  => [
			'Plugin: woocommerce · active',
			'Version: < 10.2',
		],
		'count'  => '214 of 2,921 sites',
		'total'  => '2,921 sites',
	] );
}

function anchor_threats() {
	return apply_filters( 'anchor_threats', [
		[ 'sev' => 'critical', 'cve' => 'CVE-2026-1188', 'name' => 'Arbitrary file upload in form builder',      'affected' => '412 sites affected',   'status' => 'Patched' ],
		[ 'sev' => 'critical', 'cve' => 'CVE-2026-0977', 'name' => 'Privilege escalation in membership add-on',  'affected' => '38 sites affected',    'status' => 'Patched' ],
		[ 'sev' => 'high',     'cve' => 'CVE-2026-0844', 'name' => 'Stored XSS in slider library',               'affected' => '1,204 sites affected', 'status' => 'Patched' ],
		[ 'sev' => 'high',     'cve' => 'CVE-2026-0712', 'name' => 'Unauthenticated option update',              'affected' => '96 sites affected',    'status' => 'Patched' ],
		[ 'sev' => 'medium',   'cve' => 'CVE-2026-0655', 'name' => 'Open redirect in SEO plugin',                'affected' => '1,271 sites affected', 'status' => 'Patched' ],
	] );
}

/**
 * Terminal pane lines. `tone` maps to a .terminal__* class.
 */
function anchor_terminal_lines() {
	return apply_filters( 'anchor_terminal_lines', [
		[ 'tone' => 'comment', 'text' => '# run across the whole fleet, in parallel' ],
		[ 'tone' => 'prompt',  'text' => 'wp plugin update --all --sites=all' ],
		[ 'tone' => 'out',     'text' => '→ 2,958 sites queued · 24 workers' ],
		[ 'tone' => 'out',     'text' => '✔ bakerstreetbistro.com · 3 plugins updated · 4.2s' ],
		[ 'tone' => 'out',     'text' => '✔ blueheronfarm.org · 1 plugin updated · 2.8s' ],
		[ 'tone' => 'out',     'text' => '✔ cavendishbooks.com · up to date · 0.9s' ],
		[ 'tone' => 'out',     'text' => '✔ cedarcreekdental.com · 5 plugins updated · 6.1s' ],
		[ 'tone' => 'done',    'text' => '✔ 822 components updated', 'suffix' => '· 0 failures · 3m 41s' ],
		[ 'tone' => 'cursor',  'text' => '' ],
	] );
}

function anchor_console_tabs() {
	return apply_filters( 'anchor_console_tabs', [
		'fleet'    => [ 'label' => 'Fleet',    'url' => 'anchor.host/account/sites' ],
		'security' => [ 'label' => 'Security', 'url' => 'anchor.host/account/security' ],
		'terminal' => [ 'label' => 'Terminal', 'url' => 'anchor.host/account/console' ],
	] );
}

/**
 * Infrastructure partner cards.
 */
function anchor_infrastructure() {
	return apply_filters( 'anchor_infrastructure', [
		[ 'kind' => 'Hosting',           'name' => 'Kinsta',           'icon' => 'kinsta.svg',     'url' => 'https://kinsta.com' ],
		[ 'kind' => 'Stats',             'name' => 'Fathom Analytics', 'icon' => 'fathom.svg',     'url' => 'https://usefathom.com' ],
		[ 'kind' => 'Longterm backups',  'name' => 'Backblaze B2',     'icon' => 'backblaze.svg',  'url' => 'https://www.backblaze.com/cloud-storage' ],
		[ 'kind' => 'DNS',               'name' => 'Constellix',       'icon' => 'constellix.svg', 'url' => 'https://constellix.com' ],
	] );
}

/**
 * Customer quotes — verbatim excerpts from public Google reviews of
 * Anchor Hosting. Trim by dropping whole sentences, never by rewording.
 */
function anchor_quotes() {
	return apply_filters( 'anchor_quotes', [
		[
			'text' => "We feel very confident in setting up our client's websites on Anchor's platform. A truly stress free hosting solution for agency owners looking to give their clients a great experience.",
			'name'   => 'Caleb Towers',
			'role'   => '★★★★★ · Google review',
			'avatar' => get_theme_file_uri( 'assets/img/quotes/caleb-towers.png' ),
		],
		[
			'text' => 'Anchor hosting is the only hosting company I recommend. I have my website there, along with several of my clients. Austin is responsive and results-oriented.',
			'name'   => 'Justin Quinn',
			'role'   => '★★★★★ · Google review',
			'avatar' => get_theme_file_uri( 'assets/img/quotes/justin-quinn.png' ),
		],
		[
			'text' => 'Migrating our sites to Anchor Hosting was a breeze, off to great start. And the dashboard is super simple and user friendly! Highly recommend Austin.',
			'name'   => 'Michael Leone',
			'role'   => '★★★★★ · Google review',
			'avatar' => get_theme_file_uri( 'assets/img/quotes/michael-leone.jpg' ),
		],
	] );
}

function anchor_about_cards() {
	return apply_filters( 'anchor_about_cards', [
		[
			'title' => 'The bus factor plan',
			'body'  => 'A one-person host raises a fair question. There is a documented plan, escrowed access and a partner ready to take the helm. Written down, not implied.',
		],
		[
			'title' => 'Open source',
			'body'  => 'CaptainCore, the platform behind the dashboard, is open source. Take it with you if you ever leave. Most people do not.',
		],
		[
			'title' => 'Giving back',
			'body'  => 'A share of revenue goes back to the WordPress ecosystem and to Lancaster nonprofits. Hosting money should stay useful.',
		],
	] );
}

function anchor_security_cards() {
	return apply_filters( 'anchor_security_cards', [
		[ 'tag' => 'daily',    'title' => 'Vulnerability scanning', 'body' => 'Every plugin, theme and core version checked against live vulnerability feeds. Affected sites are identified fleet-wide in minutes.' ],
		[ 'tag' => 'nightly',  'title' => 'File checksums',         'body' => 'Core, plugin and theme files verified against WordPress.org. Anything modified gets flagged and diffed.' ],
		[ 'tag' => 'nightly',  'title' => 'Offsite backups',        'body' => 'Full site and database snapshots to redundant Backblaze B2 storage, retained long-term and restorable per file.' ],
		[ 'tag' => 'always',   'title' => 'Uptime monitoring',      'body' => 'Global probes on every production environment. Downtime is investigated before the first client notices.' ],
		[ 'tag' => 'always',   'title' => 'HTTPS everywhere',       'body' => 'Certificates issued, renewed and enforced automatically across every domain and subdomain.' ],
		[ 'tag' => 'on event', 'title' => 'Fleet-wide patching',    'body' => 'When a serious vulnerability lands, the patch deploys to every affected site in parallel, not one support ticket at a time.' ],
	] );
}

/**
 * Security documentation — the full defense-in-depth reference behind the
 * marketing-level security page. Sections render as card grids; `alerts`
 * and `schedule` render as tables.
 */
function anchor_security_docs() {
	return apply_filters( 'anchor_security_docs', [
		'sections' => [
			[
				'kicker' => 'Continuous monitoring',
				'title'  => 'Always watching',
				'lede'   => 'Signals that run around the clock on every managed site.',
				'items'  => [
					[
						'tag'    => 'every quicksave',
						'title'  => 'Malware scan on code change',
						'body'   => "When a quicksave detects file changes in plugins, themes or mu-plugins, the changed files are scanned with Wordfence CLI and CaptainCore's own signature database.",
						'points' => [
							'Covers .php, .js, .html, .svg, .phtml and .phar files',
							'Built-in signatures for blockchain C2, self-hiding plugins, remote eval, SEO spam and more',
							'Findings trigger an immediate alert with site details and matched signatures',
						],
					],
					[
						'tag'    => 'daily',
						'title'  => 'WordPress core checksums',
						'body'   => 'Every site\'s core files are verified against official WordPress.org checksums.',
						'points' => [
							'Detects modified core files — potential backdoor injection',
							'Detects extra files that should not exist in core directories',
							'Alerts list each file path and modification type',
						],
					],
					[
						'tag'    => 'daily',
						'title'  => 'Homepage capture & injection detection',
						'body'   => 'Homepage captures are diffed for newly injected scripts and stylesheets.',
						'points' => [
							'Pattern-matched against a signature database of known malicious and safe domains',
							'Findings carry a severity: critical, high or medium',
						],
					],
					[
						'tag'    => 'daily',
						'title'  => 'Google Web Risk',
						'body'   => "Every production site's home URL is checked against Google's Web Risk API.",
						'points' => [
							'Malware and unwanted-software distribution',
							'Social engineering, including extended phishing detection',
						],
					],
					[
						'tag'    => 'every 5 min',
						'title'  => 'Uptime monitoring',
						'body'   => 'HTTP health checks against every monitored homepage, with retry logic across system and Cloudflare DNS.',
						'points' => [
							'Validates the HTTP status code and HTML integrity',
							'Escalating alerts: immediate, then 1h, 4h and 24h follow-ups',
							'Recovery notifications when a site comes back',
						],
					],
				],
			],
			[
				'kicker' => 'Baseline hardening',
				'title'  => 'Secure by default',
				'lede'   => 'Every site ships with the CaptainCore Helper must-use plugin, which applies hardening automatically.',
				'items'  => [
					[
						'tag'    => 'always on',
						'title'  => 'Hardening defaults',
						'body'   => 'Baseline protections applied to every site with no configuration.',
						'points' => [
							'User enumeration blocked: ?author= queries, the REST users endpoint, author sitemaps and oEmbed author URLs',
							'Generic login errors that never confirm whether a username exists',
							'WordPress version hidden from HTML and RSS output',
							'Empty author archives return 404 instead of confirming usernames',
							'Password reset requests limited by IP geolocation',
						],
					],
					[
						'tag'    => 'always on',
						'title'  => 'Security audit log',
						'body'   => 'A tamper-evident trail of security-critical events on every site, queryable via WP-CLI.',
						'points' => [
							'User lifecycle: registrations, deletions, role changes, password resets, super admin grants',
							'Plugin and theme installs, updates, activations and deletions — admin UI and WP-CLI',
							'Security-critical options, file-editor access, application passwords and code snippets',
						],
					],
				],
			],
			[
				'kicker' => 'Nightly automation',
				'title'  => 'While you sleep',
				'lede'   => 'Backups, versioning and updates run every night — and each quicksave feeds the malware scanner.',
				'items'  => [
					[
						'tag'    => 'daily 12:05 AM',
						'title'  => 'Nightly backups',
						'body'   => 'Full-site backups across all sites, 40 in parallel.',
						'points' => [
							'All previous backups retained indefinitely on efficient incremental storage',
							'A clean restore point is always available within 24 hours',
						],
					],
					[
						'tag'    => 'daily 12:15 AM',
						'title'  => 'Nightly quicksaves',
						'body'   => 'Versioned snapshots of all plugins, themes and mu-plugins, 16 in parallel.',
						'points' => [
							'Git-based versioning records exactly what changed and when',
							'Changed files are automatically scanned for malware',
						],
					],
					[
						'tag'    => 'Wed & Fri 6:15 AM',
						'title'  => 'Managed updates',
						'body'   => 'Plugin and theme updates on a staggered schedule for sites with updates enabled.',
						'points' => [
							'Staging updates Friday, production the following Wednesday',
							'The staging window catches issues before they reach production',
						],
					],
					[
						'tag'    => 'PHP EOL schedule',
						'title'  => 'PHP lifecycle',
						'body'   => 'The fleet is kept on actively supported PHP versions, tracked against the official end-of-life schedule.',
						'points' => [
							'Theme and plugin compatibility issues fixed before or during each upgrade',
							'Deprecations, fatals and breaking changes addressed fleet-wide',
						],
					],
				],
			],
			[
				'kicker' => 'Scheduled scans',
				'title'  => 'Deeper, on a cycle',
				'lede'   => 'Slower, deeper passes that catch what the continuous monitors might miss.',
				'items'  => [
					[
						'tag'    => '~20 sites/day',
						'title'  => 'Vulnerability audits',
						'body'   => 'Installed plugins and themes are audited against the Security Finder vulnerability database on a rolling cycle.',
						'points' => [
							'Component versions checked against known CVEs and CVSS scores',
							'Covers production and staging environments',
							'Findings filtered to critical and high severity for immediate attention',
						],
					],
					[
						'tag'    => 'weekly',
						'title'  => 'PHP error sweep',
						'body'   => 'The sites with the largest PHP error logs are analyzed and fixed in batches through the week.',
						'points' => [
							'Error patterns analyzed and targeted fixes applied',
							'Every fix logged to the site\'s process log for an audit trail',
						],
					],
				],
			],
			[
				'kicker' => 'Vulnerability response',
				'title'  => 'When something is found',
				'lede'   => 'Centralized tracking, targeted alerts, and fleet-wide remediation.',
				'items'  => [
					[
						'tag'    => 'continuous',
						'title'  => 'Threat tracking',
						'body'   => 'Security Finder maps vulnerabilities onto the fleet-wide component inventory.',
						'points' => [
							'Which sites run a vulnerable component, with direct remediation access',
							'Status workflow from tracking to investigating to resolved, with timestamped notes',
							'Resolution actions logged on each affected site',
						],
					],
					[
						'tag'    => 'as needed',
						'title'  => 'Fleet-wide patching',
						'body'   => 'When a critical vulnerability is confirmed, patched builds deploy to every affected site in parallel.',
						'points' => [
							'Patched plugin and theme zips stored permanently in cloud storage',
							'Deployed to up to 20 sites concurrently, each deployment verified and logged',
							'Affected sites identified automatically from Security Finder data',
						],
					],
					[
						'tag'    => 'on incident',
						'title'  => 'Malware cleanup',
						'body'   => 'Full-stack remediation for compromised sites.',
						'points' => [
							'Kills persistence mechanisms and removes malicious files',
							'Reinstalls WordPress core and resets credentials',
							'Verification loops run until the site checks out clean',
						],
					],
				],
			],
			[
				'kicker' => 'Incident response toolkit',
				'title'  => 'On-demand forensics',
				'lede'   => 'Investigation tooling built from real incident response, runnable per site or fleet-wide.',
				'items'  => [
					[
						'tag'    => 'on demand',
						'title'  => 'Malware hunt',
						'body'   => 'A standalone scanner with over 50 detection patterns.',
						'points' => [
							'Backdoors and web shells: eval chains, cookie- and IP-gated shells',
							'PHP hidden in uploads, images and CSS; malicious drop-in files',
							'Self-hiding and trojanized plugins, C2 domains, blockchain-based resolvers',
							'Obfuscation: hex encoding, chr() building, goto spaghetti, XOR loops',
						],
					],
					[
						'tag'    => 'on demand',
						'title'  => 'Timestamp forensics',
						'body'   => 'Finds files with forged modification times — a common anti-forensic technique.',
						'points' => [
							'Five-pass filtering eliminates migration artifacts and known-benign files',
							'Content-based backdoor detection on whatever remains',
						],
					],
					[
						'tag'    => 'on demand',
						'title'  => 'Role & capability audit',
						'body'   => 'Audits WordPress roles and users for unauthorized privilege escalation.',
						'points' => [
							'Dangerous capabilities on non-administrator roles',
							'Capabilities injected directly into user meta',
							'Default registration role and open-registration settings',
						],
					],
					[
						'tag'    => 'on demand',
						'title'  => 'Database scan',
						'body'   => 'Scans options, code snippets and widgets for executable code.',
						'points' => [
							'Credit card skimmers, obfuscated eval patterns, fake payment forms',
							'PHP backdoor functions and superglobal access in stored code',
						],
					],
					[
						'tag'    => 'every 6 hours',
						'title'  => 'Third-party script integrity',
						'body'   => 'Embedded third-party scripts are hash-verified on a schedule.',
						'points' => [
							'SHA256 comparison catches upstream supply-chain tampering',
							'Companion checks for uploads-directory PHP and security-log growth',
						],
					],
				],
			],
		],
		'alerts'   => [
			'title' => 'Alerting',
			'lede'  => 'Targeted email alerts for each threat scenario.',
			'head'  => [ 'Alert', 'Trigger', 'Details included' ],
			'rows'  => [
				[ 'Malware detection', 'Signature match on quicksave', 'Filename, signature name, description' ],
				[ 'Core checksum failure', 'Modified or unexpected core files', 'File paths, modification type' ],
				[ 'Injection detected', 'New script or stylesheet in homepage capture', 'Page, severity, injected element' ],
				[ 'Google Web Risk', 'URL flagged by the Web Risk API', 'Threat type, confidence' ],
				[ 'Uptime failure', 'Site unreachable or invalid HTML', 'HTTP code, error, escalation count' ],
				[ 'Default role changed', 'Suspicious default user role setting', 'Role name' ],
			],
		],
		'schedule' => [
			'title' => 'Schedule summary',
			'lede'  => 'Every check, its cadence, and what it covers.',
			'head'  => [ 'Check', 'Frequency', 'Scope' ],
			'rows'  => [
				[ 'Uptime monitoring', 'every 5 min', 'All monitored sites' ],
				[ 'Malware scan on code change', 'every quicksave', 'Changed files' ],
				[ 'Homepage capture & injection detection', 'daily', 'All sites' ],
				[ 'WordPress core checksums', 'daily', 'All sites' ],
				[ 'Google Web Risk check', 'daily', 'All production sites' ],
				[ 'Nightly backups', 'daily 12:05 AM', 'All sites (40 parallel)' ],
				[ 'Nightly quicksaves + malware scan', 'daily 12:15 AM', 'All sites (16 parallel)' ],
				[ 'Third-party script integrity', 'every 6 hours', 'Embedded analytics' ],
				[ 'PHP upgrades & compatibility fixes', 'follows PHP EOL schedule', 'All sites' ],
				[ 'Managed updates — staging', 'Fri 6:15 AM', 'Staging (updates on)' ],
				[ 'Managed updates — production', 'Wed 6:15 AM', 'Production (updates on)' ],
				[ 'Vulnerability audit', '~20 sites/day', 'Rolling fleet coverage' ],
				[ 'PHP error sweep', 'weekly, 3–4 batches', 'Top error-log sites' ],
				[ 'Security patch deploy', 'as needed', 'All affected sites' ],
				[ 'Malware hunt / forensic tools', 'on demand', 'Individual or fleet' ],
			],
		],
	] );
}

/**
 * Company details used in the footer and on the contact page.
 */
function anchor_company() {
	return apply_filters( 'anchor_company', [
		'name'     => 'Anchor Hosting',
		'address'  => "342 N Queen St, Warehouse D<br />Lancaster, PA 17603",
		'account'  => 'https://anchor.host/account/',
		'status'   => 'https://anchorhost.statuspage.io/',
		'github'   => 'https://github.com/anchorhost/',
		'x'        => 'https://x.com/anchorhost',
		'footnote' => '2,900+ WordPress sites · 800+ customers · since 2014',
	] );
}

/**
 * Footer columns. Falls back to these when no footer menu is assigned.
 */
function anchor_footer_columns() {
	return apply_filters( 'anchor_footer_columns', [
		'footer-hosting' => [
			'title' => 'Hosting',
			'links' => [
				[ 'label' => 'Plans',                  'href' => home_url( '/plans/' ) ],
				[ 'label' => 'Plan calculator',        'href' => home_url( '/hosting-plan-calculator/' ) ],
				[ 'label' => 'For web professionals',  'href' => home_url( '/hosting-for-wordpress-professionals/' ) ],
				[ 'label' => 'Tech stack',             'href' => home_url( '/tech-stack/' ) ],
			],
		],
		'footer-company' => [
			'title' => 'Company',
			'links' => [
				[ 'label' => 'About',               'href' => home_url( '/about/' ) ],
				[ 'label' => 'The bus factor plan', 'href' => home_url( '/the-bus-factor-plan/' ) ],
				[ 'label' => 'Giving back',         'href' => home_url( '/giving-back/' ) ],
				[ 'label' => 'Blog',                'href' => home_url( '/blog/' ) ],
			],
		],
		'footer-support' => [
			'title' => 'Support',
			'links' => [
				[ 'label' => 'Contact',        'href' => home_url( '/contact/' ) ],
				[ 'label' => 'Network status', 'href' => 'https://anchorhost.statuspage.io/' ],
				[ 'label' => 'Security',       'href' => home_url( '/security/' ) ],
				[ 'label' => 'Terms',          'href' => home_url( '/terms/' ) ],
			],
		],
	] );
}
