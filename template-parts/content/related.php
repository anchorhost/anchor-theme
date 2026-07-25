<?php
/**
 * "Keep reading" — three related posts, category-matched with a recent-post
 * fallback so the section is never half empty.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current  = get_the_ID();
$cat_ids  = wp_list_pluck( get_the_category(), 'term_id' );
$exclude  = [ $current ];

$related = get_posts( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 3,
	'post__not_in'        => $exclude,
	'category__in'        => $cat_ids ?: [],
	'ignore_sticky_posts' => true,
] );

if ( count( $related ) < 3 ) {
	$fill = get_posts( [
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3 - count( $related ),
		'post__not_in'        => array_merge( $exclude, wp_list_pluck( $related, 'ID' ) ),
		'ignore_sticky_posts' => true,
	] );
	$related = array_merge( $related, $fill );
}

if ( empty( $related ) ) {
	return;
}
?>
<section class="related">
	<h2 class="related__title"><?php esc_html_e( 'Keep reading', 'anchor-theme' ); ?></h2>

	<div class="related__grid">
		<?php foreach ( $related as $post ) : ?>
			<?php setup_postdata( $post ); ?>
			<a class="related-card" href="<?php the_permalink(); ?>">
				<div class="related-card__meta">
					<?php $tag = anchor_primary_tag(); ?>
					<?php if ( $tag ) : ?>
						<span class="post-meta__tag"><?php echo esc_html( $tag->name ); ?></span> ·
					<?php endif; ?>
					<?php echo esc_html( get_the_date() ); ?>
				</div>
				<h3 class="related-card__title"><?php the_title(); ?></h3>
			</a>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
