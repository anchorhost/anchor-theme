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
 * Anchor Blocks (the companion plugin) styles its blocks against its own
 * --ab-* tokens. Map them onto the theme's tokens so the blocks follow the
 * light/dark scheme instead of staying locked to the light palette.
 *
 * Only runs when the plugin is active, and only sets variables the plugin
 * already consumes — no plugin file is modified.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! wp_style_is( 'anchor-blocks', 'enqueued' ) && ! wp_style_is( 'anchor-blocks', 'registered' ) ) {
		return;
	}

	wp_add_inline_style( 'anchor-blocks', anchor_blocks_token_bridge() );
}, 30 );

add_action( 'enqueue_block_assets', function () {
	if ( ! is_admin() ) {
		return;
	}

	foreach ( [ 'anchor-blocks-editor', 'anchor-blocks' ] as $handle ) {
		if ( wp_style_is( $handle, 'enqueued' ) || wp_style_is( $handle, 'registered' ) ) {
			wp_add_inline_style( $handle, anchor_blocks_token_bridge() );
			break;
		}
	}
}, 30 );

/**
 * The --ab-* → theme token bridge.
 */
function anchor_blocks_token_bridge() {
	return <<<'CSS'
:root {
	--ab-mono: var(--font-mono);
	--ab-border: var(--border);
	--ab-muted: var(--text-3);
	--ab-text: var(--text);
	--ab-anchor: var(--navy);
	--ab-anchor-dark: var(--surface-3);
	--ab-shadow: 0 1px 2px var(--shadow);
	--ab-surface: var(--surface);
	--ab-surface-2: var(--surface-2);
}
CSS;
}
