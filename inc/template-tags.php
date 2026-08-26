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
		'sun'    => '<circle cx="12" cy="12" r="4.2"></circle><path d="M12 3v1.6M12 19.4V21M3 12h1.6M19.4 12H21M5.6 5.6l1.1 1.1M17.3 17.3l1.1 1.1M18.4 5.6l-1.1 1.1M6.7 17.3l-1.1 1.1"></path>',
		'system' => '<circle cx="12" cy="12" r="9"></circle><path d="M12 3a9 9 0 000 18z" fill="currentColor" stroke-width="0"></path>',
		'check'  => '<path d="M20 6 9 17l-5-5"></path>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h16"></path>',
		'close'  => '<path d="M6 6l12 12M18 6 6 18"></path>',
		'wheel'  => '<circle cx="12" cy="12" r="7.5"></circle><circle cx="12" cy="12" r="2.2"></circle><path d="M12 2v7.6M12 14.2V22M2 12h7.6M14.2 12H22M4.9 4.9l5.5 5.5M13.6 13.6l5.5 5.5M19.1 4.9l-5.5 5.5M10.4 13.6l-5.5 5.5"></path>',
		'buoy'   => '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle><path d="M12 3v5M12 16v5M3 12h5M16 12h5"></path>',
		'shield' => '<path d="M12 3l7 2.8V11c0 4.6-3 7.9-7 10-4-2.1-7-5.4-7-10V5.8Z"></path><path d="m9 11.5 2 2 4-4"></path>',
		'bug'    => '<path d="M9 8h6v5.5a3 3 0 0 1-6 0Z"></path><path d="M10 8a2 2 0 0 1 4 0"></path><path d="M9.5 5.8 8 4.3M14.5 5.8 16 4.3M9 10.5H4.5M15 10.5h4.5M9 13.5l-3.5 2M15 13.5l3.5 2"></path>',
		'heart'    => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>',
		'external' => '<path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>',
		'image'    => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><circle cx="8.5" cy="10.5" r="1.5"></circle><path d="m21 15-5-5-9 9"></path>',
		'pin'      => '<path d="M12 17v5M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"></path>',
		'at'       => '<circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>',
		// Brand marks are filled shapes, so they override the stroke defaults.
		'github' => '<path fill="currentColor" stroke="none" d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"></path>',
		'x'      => '<path fill="currentColor" stroke="none" d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"></path>',
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
 * A custom logo is rendered on its own — dropping an arbitrary image beside
 * the glyph squashes it and usually collides with the mark's colour. The
 * default is a bare navy anchor glyph + site name (the anchor silhouette is
 * iconic enough to stand without a tile).
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
		anchor_icon( 'anchor', (int) round( $size * 0.85 ), 2.1 ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * A mask-based SVG illustration that recolours with the theme.
 */
function anchor_illustration( $file, $alt = '', $extra_style = '' ) {
	// Version param matches the enqueued assets so illustrations bust cache on release too.
	$url = ANCHOR_THEME_URI . '/assets/icons/' . ltrim( $file, '/' ) . '?ver=' . ANCHOR_THEME_VERSION;

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
function anchor_reading_minutes( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}

	$words = str_word_count( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );

	return max( 1, (int) round( $words / 220 ) );
}

function anchor_reading_time( $post = null ) {
	$minutes = anchor_reading_minutes( $post );
	if ( ! $minutes ) {
		return '';
	}

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
			'plans'                   => 'plans',
			'pricing'                 => 'plans',
			'hosting-plan-calculator' => 'calculator',
			'calculator'              => 'calculator',
			'brand'                   => 'brand',
			'branding'                => 'brand',
			'about'                   => 'about',
			'security'                => 'security',
			'security-docs'           => 'security-docs',
			'security-documentation'  => 'security-docs',
			'contact'                 => 'contact',
			'why-smaller-is-better'   => 'why-smaller',
			'why-smaller'             => 'why-smaller',
			'smaller'                 => 'why-smaller',
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

function anchor_is_calculator_page() {
	return is_page() && 'calculator' === anchor_page_layout();
}

/**
 * Title format without the "Private:" prefix, for designed layouts that
 * live on private pages (the brand page).
 */
function anchor_brand_title_format() {
	return '%s';
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

	// No Home item — the brand lockup is the way home, and Plans leads the row.
	$items = apply_filters( 'anchor_default_nav', [
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
 * True when a URL leaves this site.
 */
function anchor_is_external_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	$home = wp_parse_url( home_url(), PHP_URL_HOST );
	return $host && $home && strcasecmp( $host, $home ) !== 0;
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
			$badge    = empty( $link['badge'] ) ? '' : sprintf( '<span class="footer-col__badge">%s</span>', esc_html( $link['badge'] ) );
			$external = anchor_is_external_url( $link['href'] );
			$icon     = $external
				? '<span class="footer-col__external" aria-hidden="true">' . anchor_icon( 'external', 12, 2.2 ) . '</span>'
				: '';
			$atts     = $external
				? ' target="_blank" rel="noopener" aria-label="' . esc_attr( $link['label'] . ' (opens in a new tab)' ) . '"'
				: '';
			printf(
				'<li><a href="%s"%s>%s%s%s</a></li>',
				esc_url( $link['href'] ),
				$atts,
				esc_html( $link['label'] ),
				$badge, // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
				$icon   // phpcs:ignore WordPress.Security.EscapeOutput -- SVG from anchor_icon().
			);
		}
		echo '</ul>';
	}

	echo '</div>';
}

/**
 * GitHub Sponsors Austin currently supports. Rendered on /giving-back/.
 */
function anchor_sponsor_cards_markup() {
	$sponsors = anchor_sponsors();
	if ( empty( $sponsors ) ) {
		return '';
	}

	ob_start();
	echo '<div class="sponsors">';
	foreach ( $sponsors as $sponsor ) {
		$handle  = $sponsor['handle'];
		$profile = 'https://github.com/' . $handle;
		$donate  = 'https://github.com/sponsors/' . $handle;
		$avatar  = 'https://github.com/' . $handle . '.png?size=120';
		?>
		<article class="sponsor-card">
			<img
				class="sponsor-card__avatar"
				src="<?php echo esc_url( $avatar ); ?>"
				alt="<?php echo esc_attr( $sponsor['name'] ); ?>"
				width="48"
				height="48"
				loading="lazy"
				decoding="async"
			/>
			<div class="sponsor-card__body">
				<a class="sponsor-card__name" href="<?php echo esc_url( $profile ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $sponsor['name'] ); ?></a>
				<div class="sponsor-card__handle">@<?php echo esc_html( $handle ); ?></div>
				<p class="sponsor-card__blurb"><?php echo esc_html( $sponsor['blurb'] ); ?></p>
			</div>
			<a class="btn btn--sm btn--ghost sponsor-card__btn" href="<?php echo esc_url( $donate ); ?>" target="_blank" rel="noopener">
				<?php echo anchor_icon( 'heart', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php esc_html_e( 'Sponsor', 'anchor-theme' ); ?>
			</a>
		</article>
		<?php
	}
	echo '</div>';
	return ob_get_clean();
}

add_shortcode( 'anchor_sponsors', 'anchor_sponsor_cards_markup' );
