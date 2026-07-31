<?php
/**
 * Styles and scripts.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'anchor-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'anchor-theme',
		ANCHOR_THEME_URI . '/assets/css/theme.css',
		[ 'anchor-fonts' ],
		ANCHOR_THEME_VERSION
	);

	wp_enqueue_script(
		'anchor-theme',
		ANCHOR_THEME_URI . '/assets/js/app.js',
		[],
		ANCHOR_THEME_VERSION,
		true
	);

	wp_localize_script( 'anchor-theme', 'anchorTheme', [
		'searchRest' => esc_url_raw( rest_url( 'anchor/v1/palette' ) ),
		'nonce'      => wp_create_nonce( 'wp_rest' ),
		'commands'   => anchor_palette_commands(),
		'i18n'       => [
			'noResults' => __( 'Nothing matched. Try', 'anchor-theme' ),
			'copied'    => __( 'Copied', 'anchor-theme' ),
			'posts'     => __( 'Search results', 'anchor-theme' ),
		],
	] );

	if ( is_singular( 'post' ) ) {
		wp_enqueue_script(
			'anchor-outline',
			ANCHOR_THEME_URI . '/assets/js/outline.js',
			[],
			ANCHOR_THEME_VERSION,
			true
		);
	}

	if ( anchor_is_plans_page() ) {
		wp_enqueue_script(
			'anchor-calculator',
			ANCHOR_THEME_URI . '/assets/js/calculator.js',
			[ 'anchor-theme' ],
			ANCHOR_THEME_VERSION,
			true
		);

		wp_localize_script( 'anchor-calculator', 'anchorPlans', [
			'plans'  => array_values( anchor_plans() ),
			'addons' => anchor_addon_rates(),
		] );
	}
} );

/**
 * Preconnect to the font CDN so the first paint is not blocked on DNS.
 */
add_filter( 'wp_resource_hints', function ( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = [ 'href' => 'https://fonts.googleapis.com' ];
		$hints[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' ];
	}
	return $hints;
}, 10, 2 );

/**
 * Apply the saved colour scheme before first paint.
 *
 * Without this the page renders in the OS scheme and then flips, which is a
 * visible flash on every navigation. Runs inline in <head> on purpose.
 */
add_action( 'wp_head', function () {
	?>
	<script>
	(function () {
		try {
			var saved = localStorage.getItem('ah-theme');
			if (saved === 'light' || saved === 'dark') {
				document.documentElement.dataset.theme = saved;
			}
		} catch (e) {}
	})();
	</script>
	<?php
}, 1 );

/**
 * Editor styles need the fonts and the token layer too, otherwise the block
 * editor renders the content in the admin's default typography.
 */
add_action( 'enqueue_block_assets', function () {
	if ( ! is_admin() ) {
		return;
	}
	wp_enqueue_style(
		'anchor-editor-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap',
		[],
		null
	);
} );
