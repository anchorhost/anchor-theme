<?php
/**
 * Featured (latest) post card on the blog index.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="featured-post">
	<a class="featured-post__card" href="<?php the_permalink(); ?>">
		<div class="featured-post__grid">

			<div class="featured-post__thumb" style="<?php echo esc_attr( anchor_thumb_style( 'anchor-featured' ) ); ?>" aria-hidden="true"></div>

			<div class="featured-post__body">
				<?php anchor_post_meta( [ 'tag_link' => false, 'label' => __( 'Latest', 'anchor-theme' ) ] ); ?>

				<h2 class="featured-post__title"><?php the_title(); ?></h2>

				<p class="featured-post__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>

				<span class="featured-post__more"><?php esc_html_e( 'Read the post →', 'anchor-theme' ); ?></span>
			</div>

		</div>
	</a>
</section>
