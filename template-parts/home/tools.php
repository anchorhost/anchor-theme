<?php
/**
 * Home — free tools (Cove, Disembark).
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tools = anchor_free_tools();

if ( empty( $tools['items'] ) ) {
	return;
}
?>
<section class="wrap section section--tools" id="free-tools">

	<div class="section-head">
		<div class="section-head__narrow">
			<div class="eyebrow"><?php echo esc_html( $tools['eyebrow'] ); ?></div>
			<h2 class="section-title"><?php echo esc_html( $tools['title'] ); ?></h2>
			<p class="section-lede"><?php echo esc_html( $tools['lede'] ); ?></p>
		</div>
		<?php if ( ! empty( $tools['perks'] ) ) : ?>
			<ul class="tools__perks">
				<?php foreach ( $tools['perks'] as $perk ) : ?>
					<li><?php echo anchor_icon( 'check', 15, 2.4 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $perk ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="tools__grid">
		<?php foreach ( $tools['items'] as $tool ) : ?>
			<article class="card card--lift free-tool">
				<div class="free-tool__head">
					<img class="free-tool__logo" src="<?php echo esc_url( ANCHOR_THEME_URI . '/assets/icons/' . $tool['icon'] ); ?>" alt="" width="48" height="48" loading="lazy" />
					<div>
						<div class="free-tool__kind"><?php echo esc_html( $tool['kind'] ); ?></div>
						<h3 class="free-tool__name"><?php echo esc_html( $tool['name'] ); ?></h3>
						<div class="free-tool__meta"><?php echo esc_html( $tool['meta'] ); ?></div>
					</div>
				</div>
				<p class="free-tool__text"><?php echo esc_html( $tool['text'] ); ?></p>
				<?php if ( ! empty( $tool['points'] ) ) : ?>
					<ul class="free-tool__points">
						<?php foreach ( $tool['points'] as $point ) : ?>
							<li><?php echo anchor_icon( 'check', 15, 2.4 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $point ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="free-tool__foot">
					<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $tool['url'] ); ?>" rel="noopener"><?php echo esc_html( $tool['label'] ); ?> <?php echo anchor_icon( 'external', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				</div>
			</article>
		<?php endforeach; ?>
	</div>

</section>
