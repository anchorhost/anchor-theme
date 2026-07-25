<?php
/**
 * Home — headline numbers band.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="statband">
	<div class="statband__inner">
		<?php foreach ( anchor_stats() as $stat ) : ?>
			<div>
				<div class="statband__value"><?php echo esc_html( $stat['value'] ); ?></div>
				<div class="statband__label"><?php echo esc_html( $stat['label'] ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
