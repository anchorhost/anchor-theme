<?php
/**
 * Single post.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$author_id  = get_the_author_meta( 'ID' );
	$author_bio = get_the_author_meta( 'description' );
	$hero_url   = get_the_post_thumbnail_url( null, 'anchor-featured' );
	$blog_url   = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
	?>

	<article <?php post_class( 'post-article' ); ?>>

		<a class="back-link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( '← All posts', 'anchor-theme' ); ?></a>

		<header class="post-header">
			<?php anchor_post_meta( [ 'read_suffix' => __( 'read', 'anchor-theme' ), 'class' => 'post-header__meta' ] ); ?>

			<h1 class="post-header__title"><?php the_title(); ?></h1>

			<div class="byline">
				<?php echo get_avatar( $author_id, 34, '', '', [ 'class' => 'byline__avatar' ] ); ?>
				<span class="byline__text">
					<span class="byline__name"><?php echo esc_html( get_the_author() ); ?></span>
					<span class="byline__org"><?php bloginfo( 'name' ); ?></span>
				</span>
			</div>
		</header>

		<?php if ( $hero_url ) : ?>
			<div class="post-hero" style="background-image:url(<?php echo esc_url( $hero_url ); ?>)" aria-hidden="true"></div>
		<?php endif; ?>

		<div class="post-body">

			<aside class="post-outline" id="post-outline" hidden aria-label="<?php esc_attr_e( 'On this page', 'anchor-theme' ); ?>"
				data-minutes="<?php echo (int) anchor_reading_minutes(); ?>"
				data-read-label="<?php echo esc_attr( sprintf( /* translators: %d: reading time in minutes. */ __( '%d min read', 'anchor-theme' ), anchor_reading_minutes() ) ); ?>"
				data-left-template="<?php esc_attr_e( '≈ %d min left', 'anchor-theme' ); ?>"
				data-done-label="<?php esc_attr_e( 'Finished', 'anchor-theme' ); ?>">
				<div class="post-outline__head">
					<span class="post-outline__estimate" id="post-outline-estimate"></span>
				</div>
				<nav class="post-outline__list" id="post-outline-list"></nav>
			</aside>

			<div class="prose">
				<?php if ( has_excerpt() ) : ?>
					<p class="post-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<?php
				the_content();

				wp_link_pages( [
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'anchor-theme' ),
					'after'  => '</div>',
				] );
				?>

				<div class="author-box">
					<?php echo get_avatar( $author_id, 52, '', '', [ 'class' => 'author-box__avatar' ] ); ?>
					<div class="author-box__body">
						<div class="author-box__name"><?php echo esc_html( get_the_author() ); ?></div>
						<?php if ( $author_bio ) : ?>
							<p class="author-box__bio"><?php echo esc_html( $author_bio ); ?></p>
						<?php endif; ?>
					</div>
					<a class="btn btn--primary btn--sm" href="<?php echo esc_url( home_url( '/subscribe/' ) ); ?>"><?php esc_html_e( 'Subscribe', 'anchor-theme' ); ?></a>
				</div>
			</div>

		</div>

	</article>

	<?php get_template_part( 'template-parts/content/related' ); ?>

	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="wrap" style="padding-bottom:60px">';
		comments_template();
		echo '</div>';
	}
	?>

	<?php
endwhile;

get_footer();
