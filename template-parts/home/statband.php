<?php
/**
 * Home — headline numbers band.
 *
 * A stat may carry a `url` (renders as a link) or a `modal` (renders as a
 * button opening the matching <dialog>). Plain stats stay a <div>.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$anchor_cve_reports = anchor_cve_reports();
?>
<section class="statband">
	<div class="statband__inner">
		<?php foreach ( anchor_stats() as $stat ) : ?>
			<?php
			$has_modal = ! empty( $stat['modal'] ) && ! empty( $anchor_cve_reports['cves'] );
			$has_url   = ! empty( $stat['url'] );
			?>
			<?php if ( $has_url ) : ?>
				<a class="statband__stat statband__stat--go" href="<?php echo esc_url( $stat['url'] ); ?>">
			<?php elseif ( $has_modal ) : ?>
				<button class="statband__stat statband__stat--go" type="button" data-modal-open="<?php echo esc_attr( $stat['modal'] ); ?>">
			<?php else : ?>
				<div class="statband__stat">
			<?php endif; ?>
				<span class="statband__value">
					<?php if ( ! empty( $stat['icon'] ) ) : ?>
						<span class="statband__icon"><?php echo anchor_icon( $stat['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php endif; ?>
					<?php echo esc_html( $stat['value'] ); ?>
				</span>
				<span class="statband__label"><?php echo esc_html( $stat['label'] ); ?></span>
			<?php if ( $has_url ) : ?>
				</a>
			<?php elseif ( $has_modal ) : ?>
				</button>
			<?php else : ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
<?php if ( ! empty( $anchor_cve_reports['cves'] ) ) : ?>
	<?php get_template_part( 'template-parts/home/cve-modal', null, [ 'reports' => $anchor_cve_reports ] ); ?>
<?php endif; ?>
