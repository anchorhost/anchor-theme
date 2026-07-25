<?php
/**
 * Template helpers.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG icons. Kept inline so they inherit currentColor and never
 * cost an extra request.
 */
function anchor_icon( $name, $size = 16, $stroke = 1.9 ) {
	$paths = [
		'anchor' => '<circle cx="12" cy="4.5" r="2"></circle><path d="M12 6.5V21"></path><path d="M7.5 10h9"></path><path d="M4 14.5a8 8 0 0 0 16 0"></path><path d="M4 14.5h2.6M20 14.5h-2.6"></path>',
		'search' => '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.6-3.6"></path>',
		'moon'   => '<path d="M20.5 14.5A8.5 8.5 0 1 1 9.5 3.5a6.8 6.8 0 0 0 11 11Z"></path>',
		'check'  => '<path d="M20 6 9 17l-5-5"></path>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h16"></path>',
	];

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		(int) $size,
		esc_attr( $stroke ),
		$paths[ $name ]
	);
}

/**
 * The brand lockup.
 *
 * A custom logo is rendered on its own — dropping an arbitrary image inside
 * the navy tile squashes it and usually collides with the tile's colour. The
 * default is the design's navy tile + anchor glyph + site name.
 */
function anchor_brand( $size = 34 ) {
	$logo_id = get_theme_mod( 'custom_logo' );

	if ( $logo_id ) {
		$src = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( $src ) {
			printf(
				'<img class="brand__logo" src="%s" alt="%s" style="height:%dpx" />',
				esc_url( $src ),
				esc_attr( get_bloginfo( 'name' ) ),
				(int) $size
			);
			return;
		}
	}

	printf(
		'<span class="brand__mark" style="width:%1$dpx;height:%1$dpx">%2$s</span><span class="brand__name">%3$s</span>',
		(int) $size,
		anchor_icon( 'anchor', (int) round( $size * 0.56 ) ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * A mask-based SVG illustration that recolours with the theme.
 */
function anchor_illustration( $file, $alt = '', $extra_style = '' ) {
	$url = ANCHOR_THEME_URI . '/assets/icons/' . ltrim( $file, '/' );

	printf(
		'<div role="img" aria-label="%1$s" class="illus" style="-webkit-mask-image:url(\'%2$s\');mask-image:url(\'%2$s\');%3$s"></div>',
		esc_attr( $alt ),
		esc_url( $url ),
		esc_attr( $extra_style )
	);
}

/**
 * Estimated reading time, matching the design's "9 min" format.
 */
function anchor_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );
	$minutes = max( 1, (int) round( $words / 220 ) );

	/* translators: %d: reading time in minutes. */
	return sprintf( __( '%d min', 'anchor-theme' ), $minutes );
}

/**
 * The post's primary category, used as the coloured tag in post meta.
 */
function anchor_primary_tag( $post = null ) {
	$cats = get_the_category( get_post( $post ) ? get_post( $post )->ID : null );

	foreach ( (array) $cats as $cat ) {
		if ( 'Uncategorized' !== $cat->name ) {
			return $cat;
		}
	}

	return null;
}

/**
 * Renders the mono meta line: TAG · date · read time.
 *
 * @param array $args tag_link, show_read, extra_class.
 */
function anchor_post_meta( $args = [] ) {
	$args = wp_parse_args( $args, [
		'tag_link'    => true,
		'show_read'   => true,
		'read_suffix' => '',
		'class'       => '',
		'label'       => '',
	] );

	$parts = [];

	if ( $args['label'] ) {
		$parts[] = '<span class="post-meta__tag">' . esc_html( $args['label'] ) . '</span>';
	} else {
		$tag = anchor_primary_tag();
		if ( $tag ) {
			$parts[] = $args['tag_link']
				? '<a class="post-meta__tag" href="' . esc_url( get_category_link( $tag ) ) . '">' . esc_html( $tag->name ) . '</a>'
				: '<span class="post-meta__tag">' . esc_html( $tag->name ) . '</span>';
		}
	}

	$parts[] = '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';

	if ( $args['show_read'] ) {
		$parts[] = '<span>' . esc_html( trim( anchor_reading_time() . ' ' . $args['read_suffix'] ) ) . '</span>';
	}

	$class = trim( 'post-meta ' . $args['class'] );

	echo '<div class="' . esc_attr( $class ) . '">' . implode( '<span aria-hidden="true">·</span>', $parts ) . '</div>';
}

/**
 * Inline background-image style for a card thumbnail.
 */
function anchor_thumb_style( $size = 'anchor-card', $post = null ) {
	$url = get_the_post_thumbnail_url( $post, $size );
	return $url ? 'background-image:url(' . esc_url( $url ) . ')' : '';
}

/**
 * Format a price the way the design does: $1,234.50, trailing zeros trimmed.
 */
function anchor_money( $n ) {
	$n = round( (float) $n, 2 );
	return '$' . number_format( $n, ( floor( $n ) === $n ) ? 0 : 2 );
}

/**
 * Pick the cheapest plan that covers the requested portfolio.
 *
 * Mirrors the JS in calculator.js — keep the two in step.
 *
 * @return array{plan: array, over: array, total: float}
 */
function anchor_quote( $sites, $storage, $views ) {
	$rates = anchor_addon_rates();
	$best  = null;

	foreach ( anchor_plans() as $plan ) {
		$over = [
			'sites' => max( 0, $sites - $plan['sites'] ) * $rates['site'],
			'gb'    => ceil( max( 0, $storage - $plan['gb'] ) / 10 ) * $rates['storage'],
			'pv'    => ceil( max( 0, $views - $plan['pv'] ) / 1000000 ) * $rates['pageviews'],
		];

		$total = $plan['m'] + $over['sites'] + $over['gb'] + $over['pv'];

		if ( null === $best || $total < $best['total'] ) {
			$best = [ 'plan' => $plan, 'over' => $over, 'total' => $total ];
		}
	}

	return $best;
}

/**
 * Which designed layout a page should use.
 */
function anchor_page_layout( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 'default';
	}

	$layout = get_post_meta( $post->ID, '_anchor_page_layout', true );

	// Fall back to matching by slug so a fresh install works with no config.
	if ( ! $layout || 'default' === $layout ) {
		$by_slug = [
			'plans'    => 'plans',
			'pricing'  => 'plans',
			'about'    => 'about',
			'security' => 'security',
			'contact'  => 'contact',
		];
		if ( isset( $by_slug[ $post->post_name ] ) ) {
			$layout = $by_slug[ $post->post_name ];
		}
	}

	return $layout ?: 'default';
}

function anchor_is_plans_page() {
	if ( ! is_page() ) {
		return false;
	}
	return 'plans' === anchor_page_layout() || is_page_template( 'templates/plans.php' );
}

/**
 * Header nav — the assigned menu, or the design's default set.
 */
function anchor_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( [
			'theme_location' => 'primary',
			'container'      => false,
			'depth'          => 1,
			'walker'         => new Anchor_Nav_Walker(),
			'fallback_cb'    => false,
		] );
		return;
	}

	$items = apply_filters( 'anchor_default_nav', [
		[ 'label' => 'Home',     'url' => home_url( '/' ) ],
		[ 'label' => 'Plans',    'url' => home_url( '/plans/' ) ],
		[ 'label' => 'Blog',     'url' => home_url( '/blog/' ) ],
		[ 'label' => 'About',    'url' => home_url( '/about/' ) ],
		[ 'label' => 'Security', 'url' => home_url( '/security/' ) ],
		[ 'label' => 'Contact',  'url' => home_url( '/contact/' ) ],
	] );

	echo '<ul>';
	foreach ( $items as $item ) {
		$is_current = untrailingslashit( $item['url'] ) === untrailingslashit( anchor_current_url() );
		printf(
			'<li class="nav-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$is_current ? ' is-active' : '',
			esc_url( $item['url'] ),
			$is_current ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

function anchor_current_url() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	return ( is_ssl() ? 'https://' : 'http://' ) . $host . $uri;
}

/**
 * Footer column links — assigned menu if present, otherwise the defaults.
 */
function anchor_footer_column( $location, $column ) {
	echo '<div class="footer-col">';
	echo '<div class="footer-col__title">' . esc_html( $column['title'] ) . '</div>';

	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( [
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		] );
	} else {
		echo '<ul>';
		foreach ( $column['links'] as $link ) {
			printf(
				'<li><a href="%s">%s</a></li>',
				esc_url( $link['href'] ),
				esc_html( $link['label'] )
			);
		}
		echo '</ul>';
	}

	echo '</div>';
}
