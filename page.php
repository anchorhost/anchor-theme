<?php
/**
 * Page — routes to a designed layout based on the page's `_anchor_page_layout`
 * meta (or its slug, so a fresh install works with no configuration).
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$layout = anchor_page_layout();

	switch ( $layout ) {
		case 'plans':
			get_template_part( 'template-parts/pages/plans' );
			break;
		case 'about':
			get_template_part( 'template-parts/pages/about' );
			break;
		case 'security':
			get_template_part( 'template-parts/pages/security' );
			break;
		case 'security-docs':
			get_template_part( 'template-parts/pages/security-docs' );
			break;
		case 'contact':
			get_template_part( 'template-parts/pages/contact' );
			break;
		default:
			get_template_part( 'template-parts/pages/default' );
	}

endwhile;

get_footer();
