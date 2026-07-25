<?php
/**
 * Category, tag, author and date archives.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="archive-hero">
	<div class="eyebrow"><?php echo esc_html( get_post_type_object( 'post' )->labels->name ); ?></div>
	<h1 class="archive-hero__title"><?php the_archive_title(); ?></h1>
	<?php if ( get_the_archive_description() ) : ?>
		<div class="archive-hero__text"><?php the_archive_description(); ?></div>
	<?php endif; ?>
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
		<h2 class="util__title"><?php esc_html_e( 'Nothing in this archive', 'anchor-theme' ); ?></h2>
		<p class="util__text"><?php esc_html_e( 'Try the blog index instead.', 'anchor-theme' ); ?></p>
		<div class="util__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'All posts', 'anchor-theme' ); ?></a>
		</div>
	</div>

<?php endif; ?>

<?php
get_footer();
