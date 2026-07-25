<?php
/**
 * Block editor integration.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page layout picker in the editor sidebar.
 */
add_action( 'enqueue_block_editor_assets', function () {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script(
		'anchor-editor-layout',
		ANCHOR_THEME_URI . '/assets/js/editor-layout.js',
		[ 'wp-plugins', 'wp-edit-post', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n' ],
		ANCHOR_THEME_VERSION,
		true
	);
} );

/**
 * Carry the code block's language through to a Prism-compatible class.
 */
add_filter( 'render_block_core/code', function ( $html, $block ) {
	$lang = $block['attrs']['language'] ?? '';

	if ( $lang ) {
		$html = str_replace( '<code>', '<code class="language-' . esc_attr( $lang ) . '">', $html );
	}

	return $html;
}, 10, 2 );

/**
 * Anchor Blocks compatibility.
 *
 * No bridge is needed: from v1.8.0 the plugin's own tokens read the theme's
 * variables first (`--ab-surface: var(--surface, #fff)`), so the blocks pick
 * up this theme's palette and light/dark switching on their own.
 *
 * An earlier version of this file mapped --ab-* here instead. That was wrong —
 * it retinted the blocks' text without touching their hardcoded white card
 * backgrounds, which made headings invisible in dark mode. The fix belongs in
 * the plugin, where the backgrounds are.
 */
