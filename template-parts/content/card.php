<?php
/**
 * Post card used in the blog grid, archives and search results.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a class="post-card" href="<?php the_permalink(); ?>">

	<div class="post-card__thumb" style="<?php echo esc_attr( anchor_thumb_style( 'anchor-card' ) ); ?>" aria-hidden="true"></div>

	<div class="post-card__body">
		<?php anchor_post_meta( [ 'tag_link' => false, 'class' => 'post-card__meta' ] ); ?>

		<h3 class="post-card__title"><?php the_title(); ?></h3>

		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
	</div>

</a>
