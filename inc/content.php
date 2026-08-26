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
 * Hosting Plan Calculator page copy.
 */
function anchor_calculator_copy() {
	return apply_filters( 'anchor_calculator_copy', [
		'lede'         => 'Build your own plan: pick a base, stack on extras, and watch the total update as you go.',
		'plan_note'    => 'Each plan is a bundle of sites, storage and traffic. Start with the one closest to your portfolio.',
		'extras_note'  => 'Outgrow one dimension without re-platforming — every allowance can be extended on its own.',
		'summary_note' => 'The receipt updates live. Everything below is included no matter how you configure it.',
		'footnote'     => 'No setup fees, no contracts — plans can change or cancel at any time.',
	] );
}

/**
 * Brand page — palette documentation, type, usage rules and the asset kit.
 * Hex values here document the tokens in assets/css/theme.css; if a token
 * changes there, update it here too.
 */
function anchor_brand_kit() {
	return apply_filters( 'anchor_brand_kit', [
		'lede'   => 'The mark, wordmark, colour and type that make Anchor Hosting look like Anchor Hosting. Download the assets below — everything else on this page explains how to use them.',
		'colors' => [
			'light' => [
				[ 'name' => 'Navy',       'var' => '--navy',      'hex' => '#123E8C', 'use' => 'The brand colour. Mark, primary buttons, links.' ],
				[ 'name' => 'Ink',        'var' => '--text',      'hex' => '#15181D', 'use' => 'Headings and body text.' ],
				[ 'name' => 'Slate',      'var' => '--text-2',    'hex' => '#565C66', 'use' => 'Secondary text.' ],
				[ 'name' => 'Mist',       'var' => '--bg',        'hex' => '#F5F7FA', 'use' => 'Page background.' ],
				[ 'name' => 'Surface',    'var' => '--surface',   'hex' => '#FFFFFF', 'use' => 'Cards and panels.' ],
				[ 'name' => 'Border',     'var' => '--border',    'hex' => '#E3E7EE', 'use' => 'Hairlines and card edges.' ],
				[ 'name' => 'Good',       'var' => '--good',      'hex' => '#1C8A55', 'use' => 'Positive status.' ],
				[ 'name' => 'Warn',       'var' => '--warn',      'hex' => '#B0761B', 'use' => 'Caution status.' ],
				[ 'name' => 'Bad',        'var' => '--bad',       'hex' => '#BF3B2E', 'use' => 'Error status.' ],
			],
			'dark' => [
				[ 'name' => 'Sky',        'var' => '--navy',      'hex' => '#5C97F7', 'use' => 'The mark and actions on dark.' ],
				[ 'name' => 'Fog',        'var' => '--text',      'hex' => '#E9ECF1', 'use' => 'Headings and body text.' ],
				[ 'name' => 'Haze',       'var' => '--text-2',    'hex' => '#A3ACB9', 'use' => 'Secondary text.' ],
				[ 'name' => 'Depth',      'var' => '--bg',        'hex' => '#0B0E13', 'use' => 'Page background.' ],
				[ 'name' => 'Hull',       'var' => '--surface',   'hex' => '#141922', 'use' => 'Cards and panels.' ],
				[ 'name' => 'Seam',       'var' => '--border',    'hex' => '#242C39', 'use' => 'Hairlines and card edges.' ],
				[ 'name' => 'Good',       'var' => '--good',      'hex' => '#3FBE7F', 'use' => 'Positive status.' ],
				[ 'name' => 'Warn',       'var' => '--warn',      'hex' => '#E0A64A', 'use' => 'Caution status.' ],
				[ 'name' => 'Bad',        'var' => '--bad',       'hex' => '#EE7264', 'use' => 'Error status.' ],
			],
		],
		'usage'  => [
			'do'   => [
				'Use the bare navy mark on light surfaces, the white mark on navy, photos or dark surfaces.',
				'Scale the mark as a whole — the stroke weight is part of the drawing.',
				'Keep clearspace of at least half the mark&#8217;s height on every side.',
				'Set the wordmark in Plus Jakarta Sans Bold with −1% tracking, cap-height aligned to the mark.',
				'Use the rounded tile only where a square avatar or app icon is required.',
			],
			'dont' => [
				'Don&#8217;t redraw, outline, thicken or thin the anchor glyph.',
				'Don&#8217;t place the bare mark inside a tile of any other colour.',
				'Don&#8217;t retype the wordmark in another typeface or weight.',
				'Don&#8217;t introduce colours outside the palette on this page.',
				'Don&#8217;t use the mark smaller than 16px, or the lockup smaller than 24px tall.',
				'Don&#8217;t mix the retired 2015 logo with the current mark.',
			],
		],
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
			'3,000 sites under management',
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
			'label' => 'Security threats patched',
			'meta'  => 'Fleet-wide, before anyone asks',
			'time'  => 'today',
		],
		[
			'label' => 'Plugins, themes and core updated',
			'meta'  => 'Every site, on schedule',
			'time'  => 'daily',
		],
		[
			'label' => 'Backups verified',
			'meta'  => 'Offsite, restorable anytime',
			'time'  => 'nightly',
		],
	] );
}

function anchor_glance_rows() {
	return apply_filters( 'anchor_glance_rows', [
		[ 'label' => 'Updates',        'value' => 'automatic' ],
		[ 'label' => 'Backups',        'value' => 'nightly' ],
		[ 'label' => 'Security scans', 'value' => 'continuous' ],
		[ 'label' => 'Migrations',     'value' => 'free' ],
	] );
}

function anchor_stats() {
	return apply_filters( 'anchor_stats', [
		[ 'value' => '3,000', 'label' => 'Sites under management', 'icon' => 'wheel' ],
		[ 'value' => '800+',   'label' => 'Customers since 2014', 'icon' => 'buoy' ],
		[ 'value' => '20+',    'label' => 'Plugin vulnerabilities disclosed', 'icon' => 'shield', 'modal' => 'cve-modal' ],
		[ 'value' => '3+',     'label' => 'Backdoor operations uncovered', 'icon' => 'bug', 'url' => home_url( '/tag/security-research/' ) ],
	] );
}

/**
 * Published CVE reports, from the same JSON feed austinginder.com renders.
 *
 * Cached in a transient for 12 hours; returns an empty array (and the
 * stat degrades to plain text) if the feed is unreachable.
 *
 * @return array{cves: array, total: int, wordfence_researcher_url: string, patchstack_researcher_url: string}|array
 */
function anchor_cve_reports() {
	$feed_url = apply_filters(
		'anchor_cve_reports_url',
		add_query_arg(
			'v',
			gmdate( 'YmdH' ),
			'https://austinginder.com/content/1/themes/austinginder-v2/assets/data/cve-reports.json'
		)
	);

	$cached = get_transient( 'anchor_cve_reports' );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get( $feed_url, [ 'timeout' => 5 ] );
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		// Cache the miss briefly so a dead feed doesn't slow every pageload.
		set_transient( 'anchor_cve_reports', [], 15 * MINUTE_IN_SECONDS );
		return [];
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $data['cves'] ) || ! is_array( $data['cves'] ) ) {
		set_transient( 'anchor_cve_reports', [], 15 * MINUTE_IN_SECONDS );
		return [];
	}

	set_transient( 'anchor_cve_reports', $data, 12 * HOUR_IN_SECONDS );
	return $data;
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
 * Dashboard preview — Austin's own sites (not customer domains).
 *
 * Names, URLs and visits are real. Core / theme / plugins are a staged
 * filter-demo set so + Filter looks like the product (latest core on
 * almost every row, one site a version behind, a small plugin/theme
 * vocabulary) rather than a dump of whatever is actually installed.
 * Screenshots and environments still overlay from CaptainCore when a
 * matching site exists. Visits overlay from Fathom (transient, 6h).
 */
function anchor_fleet_row_defaults() {
	return [
		[
			'site'      => 'minnadmin.com',
			'owner'     => 'Minn Admin',
			'url'       => 'https://minnadmin.com',
			'envs'      => [ 'Prod' ],
			'core'      => '7.0.3',
			'visits_n'  => 1272,
			'theme'     => 'astra',
			'fathom_id' => 'QFFNVRHW',
			'plugins'   => [
				'woocommerce'  => [ 'v' => '10.1.4', 's' => 'active' ],
				'gravityforms' => [ 'v' => '2.9.3', 's' => 'active' ],
			],
		],
		[
			'site'      => 'disembark.host',
			'owner'     => 'Disembark',
			'url'       => 'https://disembark.host',
			'envs'      => [ 'Prod' ],
			'core'      => '7.0.3',
			'visits_n'  => 12,
			'theme'     => 'astra',
			'fathom_id' => 'PMKXMQVJ',
			'plugins'   => [
				'woocommerce'         => [ 'v' => '10.0.2', 's' => 'active' ],
				'the-events-calendar' => [ 'v' => '6.9.1', 's' => 'active' ],
			],
		],
		[
			'site'      => 'wpregistry.io',
			'owner'     => 'WP Registry',
			'url'       => 'https://wpregistry.io',
			'envs'      => [ 'Prod' ],
			'core'      => '7.0.2',
			'visits_n'  => 0,
			'theme'     => 'bricks',
			'fathom_id' => 'QAQDOUCP',
			'plugins'   => [
				'woocommerce'  => [ 'v' => '10.1.2', 's' => 'active' ],
				'gravityforms' => [ 'v' => '2.9.3', 's' => 'inactive' ],
			],
		],
		[
			'site'      => 'captaincore.io',
			'owner'     => 'CaptainCore',
			'url'       => 'https://captaincore.io',
			'envs'      => [ 'Prod', 'Staging' ],
			'core'      => '7.0.3',
			'visits_n'  => 48,
			'theme'     => 'bricks',
			'fathom_id' => 'WCPXOTGO',
			'plugins'   => [
				'woocommerce' => [ 'v' => '10.1.4', 's' => 'active' ],
				'wordfence'   => [ 'v' => '8.1.0', 's' => 'active' ],
				'rank-math'   => [ 'v' => '1.0.230', 's' => 'active' ],
			],
		],
		[
			'site'      => 'cove.run',
			'owner'     => 'Cove',
			'url'       => 'https://cove.run',
			'envs'      => [ 'Prod' ],
			'core'      => '7.0.3',
			'visits_n'  => 74,
			'theme'     => 'astra',
			'fathom_id' => 'RDOTPKPA',
			'plugins'   => [
				'woocommerce'  => [ 'v' => '10.1.4', 's' => 'active' ],
				'gravityforms' => [ 'v' => '2.9.3', 's' => 'active' ],
			],
		],
		[
			'site'      => 'wpbeacon.io',
			'owner'     => 'WP Beacon',
			'url'       => 'https://wpbeacon.io',
			'envs'      => [ 'Prod' ],
			'core'      => '7.0.3',
			'visits_n'  => 351,
			'theme'     => 'generatepress',
			'fathom_id' => 'AXGGNMQC',
			'plugins'   => [
				'woocommerce' => [ 'v' => '10.1.4', 's' => 'active' ],
				'wordfence'   => [ 'v' => '8.1.0', 's' => 'active' ],
			],
		],
	];
}

function anchor_fleet_rows() {
	static $rows = null;
	if ( null !== $rows ) {
		return $rows;
	}

	$live = anchor_fleet_live_overlay();
	$out  = [];
	foreach ( anchor_fleet_row_defaults() as $row ) {
		$key = $row['site'];
		if ( isset( $live[ $key ] ) && is_array( $live[ $key ] ) ) {
			$row = array_merge( $row, $live[ $key ] );
		}
		$row['visits'] = number_format( (int) ( $row['visits_n'] ?? 0 ) );
		if ( empty( $row['url'] ) ) {
			$row['url'] = 'https://' . $row['site'];
		}
		$out[] = $row;
	}

	usort( $out, static function ( $a, $b ) {
		return strcasecmp( $a['site'], $b['site'] );
	} );

	$rows = apply_filters( 'anchor_fleet_rows', $out );
	return $rows;
}

/**
 * Overlay live CaptainCore + Fathom data onto the preview rows.
 *
 * CaptainCore reads are local SQL. Fathom visits ride a 6-hour transient;
 * a miss schedules a refresh and the hardcoded weekly snapshot is used
 * until it lands, so a cache miss never blocks the homepage.
 */
function anchor_fleet_live_overlay() {
	$overlay = anchor_fleet_captaincore_overlay();
	$visits  = get_transient( 'anchor_theme_fleet_visits' );

	if ( ! is_array( $visits ) ) {
		if ( class_exists( '\CaptainCore\Remote\Fathom' ) && ! get_transient( 'anchor_theme_fleet_visits_lock' ) ) {
			set_transient( 'anchor_theme_fleet_visits_lock', 1, 2 * MINUTE_IN_SECONDS );
			if ( ! wp_next_scheduled( 'anchor_theme_refresh_fleet_visits' ) ) {
				wp_schedule_single_event( time(), 'anchor_theme_refresh_fleet_visits' );
			}
		}
		$visits = [];
	}

	foreach ( $visits as $site => $n ) {
		if ( ! isset( $overlay[ $site ] ) ) {
			$overlay[ $site ] = [];
		}
		$overlay[ $site ]['visits_n'] = (int) $n;
	}

	return $overlay;
}

function anchor_fleet_captaincore_overlay() {
	$cached = get_transient( 'anchor_theme_fleet_cc' );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	if ( ! class_exists( '\CaptainCore\Sites' ) || ! class_exists( '\CaptainCore\Environments' ) ) {
		return [];
	}

	$defaults = anchor_fleet_row_defaults();
	$names    = array_column( $defaults, 'site' );
	$sites    = \CaptainCore\Sites::where( [ 'name' => $names ] );
	if ( empty( $sites ) ) {
		return [];
	}

	$by_name = [];
	$ids     = [];
	foreach ( $sites as $site ) {
		$by_name[ $site->name ] = $site;
		$ids[]                  = $site->site_id;
	}

	$envs_all = \CaptainCore\Environments::where( [ 'site_id' => $ids ] );
	$by_site  = [];
	foreach ( (array) $envs_all as $env ) {
		$by_site[ $env->site_id ][] = $env;
	}

	$upload = rtrim( (string) get_option( 'options_remote_upload_uri' ), '/' );
	$out    = [];

	foreach ( $defaults as $row ) {
		$site = $by_name[ $row['site'] ] ?? null;
		if ( ! $site || empty( $by_site[ $site->site_id ] ) ) {
			continue;
		}

		$prod     = null;
		$env_chip = [];
		foreach ( $by_site[ $site->site_id ] as $env ) {
			$label = ( 'Production' === $env->environment ) ? 'Prod' : $env->environment;
			if ( $label ) {
				$env_chip[] = $label;
			}
			if ( 'Production' === $env->environment ) {
				$prod = $env;
			}
		}
		if ( ! $prod ) {
			$prod = $by_site[ $site->site_id ][0];
		}

		$details = json_decode( (string) $prod->details );
		$base    = ( is_object( $details ) && ! empty( $details->screenshot_base ) ) ? $details->screenshot_base : '';
		$shot    = ( $base && $upload ) ? "{$upload}/{$site->site}_{$site->site_id}/production/screenshots/{$base}_thumb-100.jpg" : '';

		// Core / theme / plugins stay staged on the row (the filter demo).
		// Only screenshot + environments come from the live site.
		$entry = [
			'envs' => $env_chip ?: $row['envs'],
		];
		if ( $shot ) {
			$entry['screenshot'] = $shot;
		}
		$out[ $row['site'] ] = $entry;
	}

	set_transient( 'anchor_theme_fleet_cc', $out, HOUR_IN_SECONDS );
	return $out;
}

function anchor_fleet_refresh_visits() {
	if ( ! class_exists( '\CaptainCore\Remote\Fathom' ) ) {
		return;
	}

	$from = gmdate( 'Y-m-d', strtotime( '-7 days' ) );
	$to   = gmdate( 'Y-m-d' );
	$out  = [];

	foreach ( anchor_fleet_row_defaults() as $row ) {
		if ( empty( $row['fathom_id'] ) ) {
			continue;
		}
		$res = \CaptainCore\Remote\Fathom::get(
			'aggregations',
			[
				'entity'     => 'pageview',
				'entity_id'  => $row['fathom_id'],
				'aggregates' => 'visits,uniques,pageviews',
				'date_from'  => $from,
				'date_to'    => $to,
			]
		);
		if ( is_array( $res ) && isset( $res[0]->visits ) ) {
			$out[ $row['site'] ] = (int) $res[0]->visits;
		}
	}

	if ( $out ) {
		set_transient( 'anchor_theme_fleet_visits', $out, 6 * HOUR_IN_SECONDS );
	}
	delete_transient( 'anchor_theme_fleet_visits_lock' );
}
add_action( 'anchor_theme_refresh_fleet_visits', 'anchor_fleet_refresh_visits' );

/**
 * Dashboard preview — the fleet filter bar. Facets and counts are derived
 * from the preview rows so they stay honest as the sites change. No default
 * chips: the table opens showing every site.
 */
function anchor_fleet_filters() {
	$rows = anchor_fleet_rows();

	$plugin_n = [];
	$theme_n  = [];
	$core_n   = [];
	$subs     = [];

	foreach ( $rows as $row ) {
		$theme = $row['theme'] ?? '';
		$core  = $row['core'] ?? '';
		if ( $theme ) {
			$theme_n[ $theme ] = ( $theme_n[ $theme ] ?? 0 ) + 1;
		}
		if ( $core ) {
			$core_n[ $core ] = ( $core_n[ $core ] ?? 0 ) + 1;
		}
		foreach ( ( $row['plugins'] ?? [] ) as $slug => $info ) {
			$plugin_n[ $slug ] = ( $plugin_n[ $slug ] ?? 0 ) + 1;
			if ( ! isset( $subs[ $slug ] ) ) {
				$subs[ $slug ] = [ 'versions' => [], 'statuses' => [] ];
			}
			$ver = $info['v'] ?? '';
			$st  = $info['s'] ?? 'inactive';
			if ( $ver ) {
				$subs[ $slug ]['versions'][ $ver ] = ( $subs[ $slug ]['versions'][ $ver ] ?? 0 ) + 1;
			}
			$subs[ $slug ]['statuses'][ $st ] = ( $subs[ $slug ]['statuses'][ $st ] ?? 0 ) + 1;
		}
	}

	arsort( $plugin_n );
	$plugin_n = array_slice( $plugin_n, 0, 6, true );
	arsort( $theme_n );
	arsort( $core_n );

	$opt = static function ( $map ) {
		$out = [];
		foreach ( $map as $name => $count ) {
			$out[] = [ 'name' => (string) $name, 'count' => (int) $count ];
		}
		return $out;
	};

	$sub_out = [];
	foreach ( $plugin_n as $slug => $_ ) {
		if ( empty( $subs[ $slug ] ) ) {
			continue;
		}
		arsort( $subs[ $slug ]['versions'] );
		$sub_out[ $slug ] = [
			'versions' => $opt( $subs[ $slug ]['versions'] ),
			'statuses' => $opt( $subs[ $slug ]['statuses'] ),
		];
	}

	return apply_filters( 'anchor_fleet_filters', [
		'search' => 'Filter sites…',
		'total'  => count( $rows ),
		'chips'  => [],
		'facets' => [
			'plugin' => [
				'label'   => 'Plugin',
				'options' => $opt( $plugin_n ),
			],
			'theme'  => [
				'label'   => 'Theme',
				'options' => $opt( $theme_n ),
			],
			'core'   => [
				'label'   => 'Core',
				'options' => $opt( $core_n ),
			],
		],
		'subs'   => $sub_out,
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
 * Terminal pane — environments the mock @ picker can target, one per
 * fleet-row env (Prod, and Staging where the row has it).
 */
function anchor_terminal_targets() {
	$out = [];
	foreach ( anchor_fleet_rows() as $row ) {
		foreach ( $row['envs'] as $env ) {
			$out[] = [
				'id'      => $row['site'] . ':' . $env,
				'site'    => $row['site'],
				'env'     => $env,
				'label'   => $row['site'] . ' · ' . $env,
				'url'     => $row['url'] ?? ( 'https://' . $row['site'] ),
				'core'    => $row['core'],
				'plugins' => $row['plugins'] ?? [],
			];
		}
	}
	return apply_filters( 'anchor_terminal_targets', $out );
}

/**
 * Cookbook recipes for the mock terminal. Clicking one fills the input
 * (v3 never auto-runs). Commands are real WP-CLI; the selected targets
 * are what scopes them, not a fake --sites=all flag.
 */
function anchor_terminal_recipes() {
	return apply_filters( 'anchor_terminal_recipes', [
		[ 'title' => 'List active plugins', 'content' => 'wp plugin list --status=active', 'sub' => 'public recipe' ],
		[ 'title' => 'WordPress version',   'content' => 'wp core version',                'sub' => 'public recipe' ],
		[ 'title' => 'Home URL',            'content' => 'wp option get home',             'sub' => 'public recipe' ],
		[ 'title' => 'Update all plugins',  'content' => 'wp plugin update --all',         'sub' => 'public recipe' ],
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
 * Customer quotes — verbatim excerpts from public Google reviews and
 * LinkedIn recommendations of Anchor Hosting. Trim by dropping whole
 * sentences, never by rewording. `url` (optional) links the name.
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
			'text' => 'I highly recommend Anchor Hosting to my clients and network partners for WordPress web hosting. Owned by Austin Ginder, who is both local and exceptionally knowledgeable, Anchor Hosting has been invaluable for troubleshooting and handling server-side issues.',
			'name'   => 'Matt Brubaker',
			'role'   => "WordPress developer & designer\u{00A0}·\u{00A0}LinkedIn",
			'avatar' => get_theme_file_uri( 'assets/img/quotes/matt-brubaker.jpg' ),
			'url'    => 'https://mattbru.me',
		],
		[
			'text' => 'Anchor Hosting is the only hosting company I recommend. I have my website there, along with several of my clients. Austin is responsive and results-oriented.',
			'name'   => 'Justin Quinn',
			'role'   => '★★★★★ · Google review',
			'avatar' => get_theme_file_uri( 'assets/img/quotes/justin-quinn.png' ),
		],
		[
			'text' => "I can't say enough wonderful things about Austin and Anchor Hosting. I am left completely worry-free when I use Anchor for website hosting, both on my site and for clients' sites. All the back-end techy stuff is handled, particularly plugin management which can be a real pain.",
			'name'   => 'Susan Harper',
			'role'   => "Marketing communications partner\u{00A0}·\u{00A0}LinkedIn",
			'avatar' => get_theme_file_uri( 'assets/img/quotes/susan-harper.jpg' ),
			'url'    => 'https://sharpernet.com',
		],
		[
			'text' => 'Migrating our sites to Anchor Hosting was a breeze, off to great start. And the dashboard is super simple and user friendly! Highly recommend Austin.',
			'name'   => 'Michael Leone',
			'role'   => '★★★★★ · Google review',
			'avatar' => get_theme_file_uri( 'assets/img/quotes/michael-leone.jpg' ),
		],
	] );
}

/**
 * Leave-a-review card rendered in the last cell of the quotes grid.
 */
function anchor_quotes_cta() {
	return apply_filters( 'anchor_quotes_cta', [
		'title' => 'Hosting with Anchor?',
		'body'  => 'A short review helps other WordPress folks find us.',
		'links' => [
			[ 'label' => 'Review on Google', 'url' => 'https://g.page/r/CV-qWIv9dGpKEBE/review' ],
			[ 'label' => 'Recommend on LinkedIn', 'url' => 'https://www.linkedin.com/in/austinginder/' ],
		],
	] );
}

/**
 * Homepage FAQ. Answers may contain a small HTML subset (links, emphasis);
 * the JSON-LD strip is generated from the same strings.
 *
 * @return array{eyebrow:string,title:string,lede:string,items:array<int,array{q:string,a:string}>}
 */
function anchor_faq() {
	return apply_filters( 'anchor_faq', [
		'eyebrow' => 'FAQ',
		'title'   => 'Fair questions.',
		'lede'    => 'The ones people actually ask before handing over a fleet.',
		'items'   => [
			[
				'q' => 'Do you provide email hosting?',
				'a' => 'We don\'t host mailboxes. WordPress hosting and inbox email are different jobs, and mixing them usually makes both worse. We recommend <a href="https://ref.fm/u27290104" target="_blank" rel="noopener sponsored">Fastmail</a> for you@yourdomain.com. See <a href="https://www.fastmail.com/pricing/" target="_blank" rel="noopener">their pricing</a> for current plans. Outbound WordPress mail (form notifications, password resets) is configured with the site.',
			],
			[
				'q' => 'Who answers when I write in?',
				'a' => 'Austin Ginder. Anchor has been run out of Lancaster, Pennsylvania since 2014. No support tiers, no offshore first line. The person who answers your email is the person who built the platform.',
			],
			[
				'q' => 'Where are the sites actually hosted?',
				'a' => 'On <a href="https://kinsta.com" target="_blank" rel="noopener">Kinsta</a>, with DNS on <a href="https://constellix.com" target="_blank" rel="noopener">Constellix</a> and long-term backups on <a href="https://www.backblaze.com/cloud-storage" target="_blank" rel="noopener">Backblaze B2</a>. Enterprise providers, chosen and managed for you. No reseller markup games.',
			],
			[
				'q' => 'Will you migrate my existing sites?',
				'a' => 'Yes, and it\'s free with every plan. I\'ll move the sites, not you. Most agencies are fully moved within a week.',
			],
			[
				'q' => 'What\'s included besides the server?',
				'a' => 'Automated plugin, theme and core updates. Nightly backups. Continuous security scanning. HTTPS on every domain. Premium plugin and theme licenses. Personal support. The dashboard is included, and the platform behind it (<a href="https://captaincore.io">CaptainCore</a>) is open source.',
			],
			[
				'q' => 'Is this for agencies, or can I host one site?',
				'a' => 'Both. Plans start at one site. The product is built for people who manage everyone else\'s WordPress: agencies, freelancers, and in-house teams who want the fleet in one place.',
			],
			[
				'q' => 'What happens if something happens to you?',
				'a' => 'A one-person host raises a fair question. There is a documented plan, escrowed access and a partner ready to take the helm. Written down, not implied. <a href="' . home_url( '/the-bus-factor-plan/' ) . '">Read the bus factor plan</a>.',
			],
			[
				'q' => 'Can I take my sites with me if I leave?',
				'a' => 'Yes. CaptainCore, the platform behind the dashboard, is open source. Take it with you if you ever leave. Most people do not.',
			],
			[
				'q' => 'How do updates and backups actually run?',
				'a' => 'Nightly backups go to redundant Backblaze B2 storage and are restorable per file. Plugin, theme and core updates run on a schedule: staging first, then production. When a serious vulnerability lands, the patch deploys to every affected site in parallel, not one support ticket at a time.',
			],
			[
				'q' => 'How is this priced?',
				'a' => 'By sites, storage and pageviews. Four plans from $20 a month, billed monthly, quarterly or yearly. Extra sites, storage and pageviews are add-ons if you outgrow a plan. The <a href="' . home_url( '/plans/' ) . '">plans page</a> has a calculator.',
			],
		],
	] );
}

/**
 * Allowed HTML inside FAQ answers.
 */
function anchor_faq_allowed_html() {
	return [
		'a'      => [
			'href'   => true,
			'target' => true,
			'rel'    => true,
		],
		'em'     => [],
		'strong' => [],
		'code'   => [],
		'br'     => [],
	];
}

function anchor_about_cards() {
	return apply_filters( 'anchor_about_cards', [
		[
			'title' => 'The bus factor plan',
			'body'  => 'A one-person host raises a fair question. There is a documented plan, escrowed access and a partner ready to take the helm.',
			'href'  => home_url( '/the-bus-factor-plan/' ),
		],
		[
			'title' => 'Open source',
			'body'  => 'CaptainCore, the platform behind the dashboard, is open source. Take it with you if you ever leave. Most people do not.',
			'href'  => 'https://captaincore.io',
		],
		[
			'title' => 'Giving back',
			'body'  => 'A share of revenue goes back to the WordPress ecosystem and to Lancaster nonprofits. Hosting money should stay useful.',
			'href'  => home_url( '/giving-back/' ),
		],
	] );
}

function anchor_sponsors() {
	return apply_filters( 'anchor_sponsors', [
		[
			'name'   => 'Aaron Jorbin',
			'handle' => 'aaronjorbin',
			'blurb'  => 'Independent WordPress core committer. Current priorities: build/test tools, PHP compatibility, minor releases, and security.',
		],
		[
			'name'   => 'Alain Schlesser',
			'handle' => 'schlessera',
			'blurb'  => 'Maintainer of WP-CLI, the command-line interface for WordPress, and a WordPress core contributor.',
		],
		[
			'name'   => 'Carl Alexander',
			'handle' => 'carlalexander',
			'blurb'  => 'WordPress engineer and educator. Helps keep independent open source work sustainable.',
		],
		[
			'name'   => 'Jonny Harris',
			'handle' => 'spacedmonkey',
			'blurb'  => 'WordPress core committer, working on the performance project.',
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
 * "Why smaller is better" — the head-to-head host comparison chart.
 *
 * `sites` is the numeric count used to scale the chart bars; `display` is
 * the label shown at the bar tip; `counts` says what the vendor's number
 * actually counts, since not everyone reports WordPress installs. Sources
 * render as footnotes under the chart.
 */
function anchor_host_comparison() {
	return apply_filters( 'anchor_host_comparison', [
		'eyebrow' => 'Scale',
		'title'   => 'This chart is to scale.',
		'lede'    => 'Sites under management, as reported by each host. Anchor is the thin line at the bottom. That\'s the point.',
		'note'    => 'Each figure is the company\'s own most recent public claim. They don\'t all count the same thing. Bluehost reports WordPress users. SiteGround reports domains. Kinsta reports businesses. GoDaddy\'s WordPress number dates to 2017. Rocket.net and Pressable don\'t publish a count at all. Mine is exact. ',
		'hosts'   => [
			[ 'name' => 'Bluehost',       'sites' => 5000000, 'display' => '5,000,000+',     'counts' => 'WordPress users, self-reported',       'source_url' => 'https://www.bluehost.com/about' ],
			[ 'name' => 'GoDaddy',        'sites' => 4000000, 'display' => '4,000,000+',     'counts' => 'WordPress sites, self-reported in 2017', 'source_url' => 'https://www.prnewswire.com/news-releases/godaddy-launches-pro-managed-wordpress-300537745.html' ],
			[ 'name' => 'SiteGround',     'sites' => 3000000, 'display' => '3,000,000+',     'counts' => 'domains, self-reported',               'source_url' => 'https://www.siteground.com/company' ],
			[ 'name' => 'WP Engine',      'sites' => 1500000, 'display' => '1,500,000+',     'counts' => 'websites, self-reported',              'source_url' => 'https://wpengine.com/wordpress/' ],
			[ 'name' => 'Kinsta',         'sites' => 230000,  'display' => '230,000+',       'counts' => 'businesses, self-reported',            'source_url' => 'https://kinsta.com/' ],
			[ 'name' => 'Rocket.net',     'sites' => 0,       'display' => 'not published',  'counts' => 'no public site count',                 'unknown' => true ],
			[ 'name' => 'Pressable',      'sites' => 0,       'display' => 'not published',  'counts' => 'no public site count',                 'unknown' => true ],
			[ 'name' => 'Anchor Hosting', 'sites' => 3000,    'display' => '3,000',          'counts' => 'WordPress sites, exact',               'us' => true ],
		],
	] );
}

/**
 * "Why smaller is better" — the infrastructure callout. Smaller does not
 * mean a rack in a basement: every Anchor site runs on the same enterprise
 * platforms the big managed hosts sell.
 */
function anchor_powered_by() {
	return apply_filters( 'anchor_powered_by', [
		'eyebrow'  => 'Infrastructure',
		'title'    => 'Powered by the big guys.',
		'lede'     => 'Every Anchor site runs on Kinsta or Rocket.net. That\'s the same enterprise infrastructure the giants advertise, right down to the data centers and CDN. Smaller doesn\'t mean weaker hardware. It means the same hardware, plus a person who actually manages the WordPress running on it.',
		'partners' => [
			[ 'kind' => 'Hosting', 'name' => 'Kinsta',     'icon' => 'kinsta.svg',       'url' => 'https://kinsta.com' ],
			[ 'kind' => 'Hosting', 'name' => 'Rocket.net', 'icon' => 'rocketdotnet.svg', 'url' => 'https://rocket.net' ],
		],
	] );
}

/**
 * "Why smaller is better" — everything included in management. There is no
 * opt-out tier: every site gets all of it.
 */
function anchor_managed_services() {
	return apply_filters( 'anchor_managed_services', [
		'eyebrow' => 'Fully managed',
		'title'   => 'There is no opt-out.',
		'lede'    => 'Every site is fully managed. Not as an add-on. Not as an upsell tier. This is what management means here, on all 3,000 sites:',
		'items'   => [
			[ 'tag' => 'ongoing',   'title' => 'PHP kept current',            'body' => 'Every site runs a modern, supported PHP version. I schedule and verify the upgrades. That job is never left to you.' ],
			[ 'tag' => 'as needed', 'title' => 'PHP compatibility fixes',     'body' => 'When a PHP upgrade breaks old plugin or theme code, I fix the code. The upgrade doesn\'t get skipped.' ],
			[ 'tag' => 'scheduled', 'title' => 'Plugin updates',              'body' => 'Plugins update on schedule across the fleet, with visual checks and rollbacks when an update misbehaves.' ],
			[ 'tag' => 'as needed', 'title' => 'Abandoned plugins patched',   'body' => 'When an author walks away from a plugin your site depends on, I patch it or fix it in place. It doesn\'t get left to rot.' ],
			[ 'tag' => 'scheduled', 'title' => 'Core updates',                'body' => 'WordPress core updates roll out to every site. Majors included, not just the security minors.' ],
			[ 'tag' => 'ahead',     'title' => 'Next-version core checks',    'body' => 'I test upcoming WordPress releases against the fleet before release day. Breaking changes get caught and fixed ahead of time.' ],
			[ 'tag' => 'day one',   'title' => 'Long-term backups',           'body' => 'Nightly offsite snapshots, retained long-term. Every previous version of your site since your first day is restorable.' ],
			[ 'tag' => 'as needed', 'title' => 'Security audits & cleanups',  'body' => 'Continuous scanning across the fleet. Hands-on audits when something looks wrong. Full cleanup if a site is ever compromised.' ],
			[ 'tag' => 'always',    'title' => 'Hardened defaults',           'body' => 'WordPress stops leaking usernames, and logins with known-compromised passwords are blocked. Every site, by default.' ],
			[ 'tag' => 'upstream',  'title' => 'Ecosystem reporting',         'body' => 'I report bugs to plugin and theme authors, and disclose security vulnerabilities through Wordfence and Patchstack. Fixing the ecosystem protects every WordPress site, not just ours.' ],
		],
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
		'account'  => home_url( '/account/' ),
		'status'   => 'https://status.anchor.host',
		'github'   => 'https://github.com/anchorhost/',
		'x'        => 'https://x.com/anchorhost',
	] );
}

/**
 * Footer columns. Falls back to these when no footer menu is assigned.
 */
function anchor_footer_columns() {
	$status = anchor_company()['status'];
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
				[
					'label' => 'Blog',
					'href'  => home_url( '/blog/' ),
					'badge' => number_format_i18n( (int) wp_count_posts( 'post' )->publish ),
				],
			],
		],
		'footer-support' => [
			'title' => 'Support',
			'links' => [
				[ 'label' => 'Contact',        'href' => home_url( '/contact/' ) ],
				[ 'label' => 'FAQ',            'href' => home_url( '/#faq' ) ],
				[ 'label' => 'Network status', 'href' => $status ],
				[ 'label' => 'Security',       'href' => home_url( '/security/' ) ],
				[ 'label' => 'Terms',          'href' => home_url( '/terms/' ) ],
				[ 'label' => 'Privacy',        'href' => home_url( '/privacy/' ) ],
			],
		],
	] );
}
