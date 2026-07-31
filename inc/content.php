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
