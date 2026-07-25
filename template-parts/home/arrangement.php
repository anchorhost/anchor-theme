<?php
/**
 * Home — "The arrangement" feature cards.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="wrap section--arrangement">

	<div class="section-head__narrow">
		<div class="eyebrow"><?php esc_html_e( 'The arrangement', 'anchor-theme' ); ?></div>
		<h2 class="section-title"><?php esc_html_e( 'You run the agency. I run the infrastructure.', 'anchor-theme' ); ?></h2>
	</div>

	<div class="arrangement__grid">
		<?php foreach ( anchor_arrangement_cards() as $card ) : ?>
			<div class="card card--lift feature-card">
				<div class="feature-card__art">
					<?php anchor_illustration( $card['icon'], $card['alt'] ); ?>
				</div>
				<div class="feature-card__body">
					<h3 class="feature-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="feature-card__text"><?php echo esc_html( $card['text'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

</section>
