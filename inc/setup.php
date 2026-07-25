<?php
/**
 * Theme supports, menus, image sizes.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'custom-logo', [
		'height'      => 64,
		'width'       => 64,
		'flex-height' => true,
		'flex-width'  => true,
	] );

	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	] );

	register_nav_menus( [
		'primary'        => __( 'Primary Navigation', 'anchor-theme' ),
		'footer-hosting' => __( 'Footer — Hosting', 'anchor-theme' ),
		'footer-company' => __( 'Footer — Company', 'anchor-theme' ),
		'footer-support' => __( 'Footer — Support', 'anchor-theme' ),
	] );

	add_editor_style( 'assets/css/editor.css' );

	// Blog card thumbnails match the design's 300x168 / 340px hero crops.
	add_image_size( 'anchor-card', 720, 404, true );
	add_image_size( 'anchor-featured', 1200, 675, true );
} );

/**
 * Page-level meta: lets a page opt into one of the designed page layouts
 * without needing a separate file-based template.
 */
add_action( 'init', function () {
	register_post_meta( 'page', '_anchor_page_layout', [
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'default'       => 'default',
		'auth_callback' => function () {
			return current_user_can( 'edit_posts' );
		},
	] );
} );

add_filter( 'excerpt_length', function () {
	return 28;
} );

add_filter( 'excerpt_more', function () {
	return '&hellip;';
} );

/**
 * Body classes — expose the current layout so CSS/JS can branch on it.
 */
add_filter( 'body_class', function ( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'is-post';
	}
	if ( is_home() || is_front_page() ) {
		$classes[] = 'is-landing';
	}
	return $classes;
} );
