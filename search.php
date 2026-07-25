<?php
/**
 * Search results.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="archive-hero">
	<div class="eyebrow"><?php esc_html_e( 'Search', 'anchor-theme' ); ?></div>
	<h1 class="archive-hero__title">
		<?php
		printf(
			/* translators: %s: search query. */
			esc_html__( 'Results for “%s”', 'anchor-theme' ),
			esc_html( get_search_query() )
		);
		?>
	</h1>
	<p class="archive-hero__text">
		<?php
		printf(
			/* translators: %d: number of results. */
			esc_html( _n( '%d match', '%d matches', (int) $GLOBALS['wp_query']->found_posts, 'anchor-theme' ) ),
			(int) $GLOBALS['wp_query']->found_posts
		);
		?>
	</p>
</section>

<?php if ( have_posts() ) : ?>

	<div class="post-grid">
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
		<h2 class="util__title"><?php esc_html_e( 'No matches', 'anchor-theme' ); ?></h2>
		<p class="util__text"><?php esc_html_e( 'Try a different phrase, or press ⌘K to search from anywhere.', 'anchor-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div>

<?php endif; ?>

<?php
get_footer();
