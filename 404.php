<?php
/**
 * 404.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="util">
	<div class="util__code">404 · <?php esc_html_e( 'off the chart', 'anchor-theme' ); ?></div>
	<h1 class="util__title"><?php esc_html_e( 'This page slipped its mooring', 'anchor-theme' ); ?></h1>
	<p class="util__text"><?php esc_html_e( 'The link is broken or the page has moved. Search for it, or head back to shore.', 'anchor-theme' ); ?></p>

	<?php get_search_form(); ?>

	<div class="util__actions">
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'anchor-theme' ); ?></a>
		<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Read the blog', 'anchor-theme' ); ?></a>
	</div>
</div>

<?php
get_footer();
