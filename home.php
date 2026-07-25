<?php
/**
 * Blog index — featured post plus a card grid.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$is_first_page = ! is_paged();
$featured_id   = 0;

if ( $is_first_page && have_posts() ) {
	$posts_list  = $GLOBALS['wp_query']->posts;
	$featured_id = isset( $posts_list[0] ) ? $posts_list[0]->ID : 0;
}
?>

<section class="blog-hero">
	<div class="blog-hero__inner">
		<div>
			<div class="eyebrow"><?php esc_html_e( 'From the wheelhouse', 'anchor-theme' ); ?></div>
			<h1 class="blog-hero__title">
				<?php
				if ( is_home() && ! is_front_page() && get_option( 'page_for_posts' ) ) {
					echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
				} else {
					esc_html_e( 'Notes from running the fleet', 'anchor-theme' );
				}
				?>
			</h1>
		</div>
		<p class="blog-hero__text"><?php esc_html_e( 'Security research, WordPress internals and the tooling behind Anchor. Written while doing the work, not after.', 'anchor-theme' ); ?></p>
	</div>
</section>

<?php if ( ! have_posts() ) : ?>

	<div class="util">
		<h2 class="util__title"><?php esc_html_e( 'Nothing published yet', 'anchor-theme' ); ?></h2>
		<p class="util__text"><?php esc_html_e( 'The first post is on its way.', 'anchor-theme' ); ?></p>
	</div>

<?php else : ?>

	<?php if ( $featured_id ) : ?>
		<?php
		the_post();
		get_template_part( 'template-parts/content/featured' );
		?>
	<?php endif; ?>

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
			'mid_size'           => 1,
			'end_size'           => 1,
			'prev_text'          => esc_html__( '← Previous', 'anchor-theme' ),
			'next_text'          => esc_html__( 'Next →', 'anchor-theme' ),
			'screen_reader_text' => esc_html__( 'Posts navigation', 'anchor-theme' ),
			'class'              => 'pagination',
		] );
		?>

		<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( home_url( '/subscribe/' ) ); ?>"><?php esc_html_e( 'Subscribe by email', 'anchor-theme' ); ?></a>
	</div>

<?php endif; ?>

<?php
get_footer();
