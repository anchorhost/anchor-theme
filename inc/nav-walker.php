<?php
/**
 * Minimal nav walker — the design's nav is a flat list of pills.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Anchor_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// Single level only.
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		// Single level only.
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = array_filter( (array) ( $item->classes ?? [] ) );

		$is_current = ! empty( array_intersect( $classes, [
			'current-menu-item',
			'current_page_item',
			'current-menu-ancestor',
			'current-post-ancestor',
		] ) );

		$output .= '<li class="nav-item' . ( $is_current ? ' is-active' : '' ) . '">';
		$output .= '<a href="' . esc_url( $item->url ) . '"';

		if ( $is_current ) {
			$output .= ' aria-current="page"';
		}

		if ( ! empty( $item->target ) ) {
			$output .= ' target="' . esc_attr( $item->target ) . '" rel="noopener"';
		}

		$output .= '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
