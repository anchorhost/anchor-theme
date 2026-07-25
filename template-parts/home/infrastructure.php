<?php
/**
 * Home — infrastructure partners.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section--band">
	<div class="wrap section--infra">

		<div class="section-head">
			<div>
				<h2 class="infra__title"><?php esc_html_e( 'Built on modern infrastructure', 'anchor-theme' ); ?></h2>
				<p class="infra__lede"><?php esc_html_e( 'Enterprise providers, chosen and managed for you. No reseller markup games.', 'anchor-theme' ); ?></p>
			</div>
		</div>

		<div class="infra__grid">
			<?php foreach ( anchor_infrastructure() as $item ) : ?>
				<a class="infra-card" href="<?php echo esc_url( $item['url'] ); ?>" rel="noopener">
					<span class="infra-card__chip">
						<img src="<?php echo esc_url( ANCHOR_THEME_URI . '/assets/icons/' . $item['icon'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" width="22" height="22" loading="lazy" />
					</span>
					<span>
						<span class="infra-card__kind"><?php echo esc_html( $item['kind'] ); ?></span>
						<span class="infra-card__name"><?php echo esc_html( $item['name'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
