<?php
/**
 * Home — hero with the "Needs attention" dashboard panel.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero      = anchor_hero();
$company   = anchor_company();
$attention = anchor_attention_rows();
$handled   = anchor_handled_rows();
$glance    = anchor_glance_rows();

$open_count = count( array_filter( $attention, fn( $row ) => empty( $row['clear'] ) ) );

$tone_class = [
	'bad'   => ' panel__bullet--bad',
	'warn'  => ' panel__bullet--warn',
	'navy'  => ' panel__bullet--navy',
	'good'  => ' panel__bullet--good',
	'muted' => '',
];
?>
<section class="hero">
	<div class="hero__inner">

		<div class="hero__copy">
			<div class="hero__flag">
				<span class="hero__dot" aria-hidden="true"></span>
				<span class="hero__flag-label"><?php echo esc_html( $hero['flag'] ); ?></span>
				<span class="hero__rule" aria-hidden="true"></span>
				<span><?php echo esc_html( $hero['since'] ); ?></span>
			</div>

			<h1 class="hero__title"><?php echo wp_kses( $hero['title'], [ 'br' => [] ] ); ?></h1>

			<p class="hero__lede"><?php echo esc_html( $hero['lede'] ); ?></p>

			<div class="hero__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/plans/' ) ); ?>"><?php esc_html_e( 'See the plans', 'anchor-theme' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $company['account'] ); ?>"><?php esc_html_e( 'Open dashboard →', 'anchor-theme' ); ?></a>
			</div>

			<div class="hero__facts">
				<?php foreach ( $hero['facts'] as $fact ) : ?>
					<span><?php echo esc_html( $fact ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="hero__panels">

			<div class="panel">
				<div class="panel__head">
					<span class="panel__bullet<?php echo ( 0 === $open_count ) ? ' panel__bullet--good' : ' panel__bullet--navy'; ?>" aria-hidden="true"></span>
					<span class="panel__title"><?php esc_html_e( 'Needs attention', 'anchor-theme' ); ?></span>
					<span class="panel__count<?php echo ( 0 === $open_count ) ? ' panel__count--zero' : ''; ?>"><?php echo esc_html( $open_count ); ?></span>
					<div class="header-spacer"></div>
					<span class="panel__url">anchor.host/account</span>
				</div>

				<?php foreach ( $attention as $row ) : ?>
					<div class="panel__row">
						<span class="panel__bullet<?php echo esc_attr( $tone_class[ $row['tone'] ] ?? '' ); ?>" aria-hidden="true"></span>
						<div class="panel__body">
							<div class="panel__label"><?php echo esc_html( $row['label'] ); ?></div>
							<div class="panel__meta"><?php echo esc_html( $row['meta'] ); ?></div>
						</div>
						<span class="panel__action<?php echo ( ( $row['action_tone'] ?? '' ) === 'good' ) ? ' panel__action--good' : ''; ?>">
							<?php echo esc_html( $row['action'] ); ?>
						</span>
					</div>
				<?php endforeach; ?>

				<?php if ( $handled ) : ?>
					<div class="panel__subhead"><?php esc_html_e( 'Handled for you', 'anchor-theme' ); ?></div>
					<?php foreach ( $handled as $row ) : ?>
						<div class="panel__row panel__row--handled">
							<span class="panel__bullet" aria-hidden="true"></span>
							<div class="panel__body">
								<div class="panel__label"><?php echo esc_html( $row['label'] ); ?></div>
								<div class="panel__meta"><?php echo esc_html( $row['meta'] ); ?></div>
							</div>
							<span class="panel__time"><?php echo esc_html( $row['time'] ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="panel panel--glance">
				<div class="glance__title"><?php esc_html_e( 'Fleet at a glance', 'anchor-theme' ); ?></div>
				<div class="glance__list">
					<?php foreach ( $glance as $row ) : ?>
						<div class="glance__row">
							<span><?php echo esc_html( $row['label'] ); ?></span>
							<span><?php echo esc_html( $row['value'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>
