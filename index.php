<?php
/**
 * Fallback template.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php if ( have_posts() ) : ?>

	<div class="post-grid" style="padding-top:66px">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content/card' );
		endwhile;
		?>
	</div>

	<div class="blog-footer">
		<?php
		the_posts_pagination( [
			'mid_size'  => 1,
			'end_size'  => 1,
			'prev_text' => esc_html__( '← Previous', 'anchor-theme' ),
			'next_text' => esc_html__( 'Next →', 'anchor-theme' ),
		] );
		?>
	</div>

<?php else : ?>

	<div class="util">
		<h1 class="util__title"><?php esc_html_e( 'Nothing here', 'anchor-theme' ); ?></h1>
		<p class="util__text"><?php esc_html_e( 'There is no content to show yet.', 'anchor-theme' ); ?></p>
	</div>

<?php endif; ?>

<?php
get_footer();
