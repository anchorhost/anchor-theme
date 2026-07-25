<?php
/**
 * Command palette — static commands plus a live content search endpoint.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The commands that do not need a query to be useful. Shipped to the browser
 * with the page so the palette opens instantly.
 *
 * kind: page | link | demo | copy | action
 */
function anchor_palette_commands() {
	$company = anchor_company();

	$nav = [];
	foreach ( [
		[ 'Home',          home_url( '/' ),          'home start' ],
		[ 'Hosting plans', home_url( '/plans/' ),    'plans pricing cost' ],
		[ 'Blog',          home_url( '/blog/' ),     'blog posts writing' ],
		[ 'About',         home_url( '/about/' ),    'about austin story' ],
		[ 'Security',      home_url( '/security/' ), 'security scanning cve' ],
		[ 'Contact',       home_url( '/contact/' ),  'contact email migrate' ],
	] as $item ) {
		$nav[] = [
			'group' => __( 'Go to', 'anchor-theme' ),
			'label' => $item[0],
			'kind'  => 'page',
			'keys'  => $item[2],
			'url'   => $item[1],
		];
	}

	$demo = [
		[ 'label' => 'Preview: fleet table',       'keys' => 'sites list dashboard',     'tab' => 'fleet' ],
		[ 'label' => 'Preview: security scanning', 'keys' => 'cve vulnerability patch',  'tab' => 'security' ],
		[ 'label' => 'Preview: browser terminal',  'keys' => 'wp-cli ssh console shell', 'tab' => 'terminal' ],
	];
	foreach ( $demo as &$d ) {
		$d['group'] = __( 'Dashboard preview', 'anchor-theme' );
		$d['kind']  = 'demo';
		$d['home']  = home_url( '/' );
	}
	unset( $d );

	$account = [
		[ 'label' => 'Open dashboard',        'keys' => 'account login sign in panel',      'url' => $company['account'] ],
		[ 'label' => 'Network status',        'keys' => 'uptime incident statuspage',       'url' => $company['status'] ],
		[ 'label' => 'Plan calculator',       'keys' => 'pricing estimate sites storage',   'url' => home_url( '/hosting-plan-calculator/' ) ],
		[ 'label' => 'CaptainCore on GitHub', 'keys' => 'open source repo code',            'url' => $company['github'] ],
		[ 'label' => 'Subscribe by email',    'keys' => 'newsletter rss follow',            'url' => home_url( '/subscribe/' ) ],
	];
	foreach ( $account as &$a ) {
		$a['group'] = __( 'Account & links', 'anchor-theme' );
		$a['kind']  = 'link';
	}
	unset( $a );

	$snippets = [
		[ 'label' => 'Copy: update every plugin, fleet-wide', 'keys' => 'wp-cli bulk update',        'text' => 'wp plugin update --all --sites=all' ],
		[ 'label' => 'Copy: restore last night\'s backup',    'keys' => 'wp-cli restore snapshot',   'text' => 'captaincore restore <site> --when=last-night' ],
		[ 'label' => 'Copy: verify core checksums',           'keys' => 'wp-cli integrity checksum', 'text' => 'wp core verify-checksums --sites=all' ],
	];
	foreach ( $snippets as &$s ) {
		$s['group'] = __( 'Snippets', 'anchor-theme' );
		$s['kind']  = 'copy';
	}
	unset( $s );

	$actions = [
		[
			'group'  => __( 'Actions', 'anchor-theme' ),
			'label'  => __( 'Toggle light / dark', 'anchor-theme' ),
			'kind'   => 'action',
			'keys'   => 'theme mode appearance dark light',
			'action' => 'toggle-theme',
		],
		[
			'group' => __( 'Actions', 'anchor-theme' ),
			'label' => __( 'Email Austin about a migration', 'anchor-theme' ),
			'kind'  => 'action',
			'keys'  => 'contact support move switch',
			'url'   => home_url( '/contact/' ),
		],
	];

	return apply_filters(
		'anchor_palette_commands',
		array_merge( $nav, $demo, $account, $snippets, $actions )
	);
}

/**
 * Live search across posts and pages, shaped like a palette command.
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'anchor/v1', '/palette', [
		'methods'             => WP_REST_Server::READABLE,
		'permission_callback' => '__return_true',
		'args'                => [
			'q' => [
				'type'              => 'string',
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
			],
		],
		'callback'            => function ( WP_REST_Request $request ) {
			$query = trim( (string) $request->get_param( 'q' ) );

			if ( strlen( $query ) < 2 ) {
				return rest_ensure_response( [] );
			}

			$posts = get_posts( [
				'post_type'        => [ 'post', 'page' ],
				'post_status'      => 'publish',
				's'                => $query,
				'posts_per_page'   => 8,
				'suppress_filters' => false,
			] );

			$results = [];

			foreach ( $posts as $post ) {
				$tag = null;
				if ( 'post' === $post->post_type ) {
					$tag = anchor_primary_tag( $post );
				}

				$results[] = [
					'group' => 'post' === $post->post_type
						? __( 'Blog posts', 'anchor-theme' )
						: __( 'Pages', 'anchor-theme' ),
					'label' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ),
					'kind'  => $tag ? strtolower( $tag->name ) : $post->post_type,
					'url'   => get_permalink( $post ),
					'keys'  => '',
				];
			}

			return rest_ensure_response( $results );
		},
	] );
} );
