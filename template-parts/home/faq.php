<?php
/**
 * Home — FAQ accordion.
 *
 * Native details/summary, no JS. Copy lives in anchor_faq().
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq = anchor_faq();

if ( empty( $faq['items'] ) ) {
	return;
}
?>
<section class="wrap section section--faq" id="faq">

	<div class="section-head__narrow">
		<div class="eyebrow"><?php echo esc_html( $faq['eyebrow'] ); ?></div>
		<h2 class="section-title"><?php echo esc_html( $faq['title'] ); ?></h2>
		<?php if ( ! empty( $faq['lede'] ) ) : ?>
			<p class="section-lede"><?php echo esc_html( $faq['lede'] ); ?></p>
		<?php endif; ?>
	</div>

	<div class="faq">
		<?php foreach ( $faq['items'] as $item ) : ?>
			<details class="faq__item">
				<summary class="faq__q"><?php echo esc_html( $item['q'] ); ?></summary>
				<div class="faq__a">
					<p><?php echo wp_kses( $item['a'], anchor_faq_allowed_html() ); ?></p>
				</div>
			</details>
		<?php endforeach; ?>
	</div>

</section>
