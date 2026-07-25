<?php
/**
 * Home — closing call to action.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cta">
	<div class="cta__inner">

		<div>
			<h2 class="cta__title"><?php esc_html_e( 'Ready to switch?', 'anchor-theme' ); ?></h2>
			<p class="cta__text">
				<?php
				printf(
					/* translators: %s: the emphasised phrase "not you". */
					esc_html__( "Free migrations with every plan. I'll move all of your sites, %s. Most agencies are fully moved within a week.", 'anchor-theme' ),
					'<em>' . esc_html__( 'not you', 'anchor-theme' ) . '</em>'
				);
				?>
			</p>
			<div class="cta__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/plans/' ) ); ?>"><?php esc_html_e( 'Get started', 'anchor-theme' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Talk to Austin', 'anchor-theme' ); ?></a>
			</div>
		</div>

		<div class="cta__art">
			<?php anchor_illustration( 'ship.svg', __( 'Migration ship', 'anchor-theme' ) ); ?>
		</div>

	</div>
</section>
