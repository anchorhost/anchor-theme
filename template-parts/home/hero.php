<?php
/**
 * Home hero: the layers between your site and the server.
 *
 * An exploded stack of the layers between you and the server, from "The
 * Layers of Web Hosting". Problems rise from the server toward you and stop
 * at the layer whose job it is. The stack is server-rendered SVG, so it reads
 * fine without JavaScript; assets/js/hero-layers.js runs the problems and the
 * layer selection.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero   = anchor_hero();
$layers = anchor_layers();

$pause_svg = '<svg class="lyr__icon-pause" viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><rect x="6" y="4.5" width="4" height="15" rx="1"></rect><rect x="14" y="4.5" width="4" height="15" rx="1"></rect></svg>';
$play_svg  = '<svg class="lyr__icon-play" viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M7 4.5v15l13-7.5z"></path></svg>';

/*
 * Geometry, in SVG user units. Each layer is an isometric slab: a top face
 * (rhombus, W wide each way, H deep), a left and a right face T thick.
 * hero-layers.js reads these from data attributes, so they only live here.
 */
$geo = [
	'vbw' => 600,
	'vbh' => 606,
	'cx'  => 152,
	'w'   => 138,
	'h'   => 58,
	't'   => 18,
	'gap' => 80,
	'y0'  => 80,
	'lx'  => 318, // where the labels start.
];
$p = static function ( $pts ) {
	return implode( ' ', array_map( static function ( $pt ) {
		return round( $pt[0], 1 ) . ',' . round( $pt[1], 1 );
	}, $pts ) );
};

$selected = 3; // Cloudflare: the PHP2Shell story is the headline.
$count    = count( $layers['layers'] );
?>
<section class="hero hero--layers">
	<div class="hero__inner hero__inner--layers">

		<div class="hero__copy lyr-copy">
			<p class="hero__flag">
				<span class="hero__since"><span class="hero__dot" aria-hidden="true"></span><?php echo esc_html( $hero['since'] ); ?></span>
				<span class="hero__flag-label"><?php echo esc_html( $hero['flag'] ); ?></span>
			</p>
			<h1 class="hero__title"><?php echo wp_kses( $hero['title'], [ 'br' => [] ] ); ?></h1>
			<p class="hero__lede"><?php echo esc_html( $hero['lede'] ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/plans/' ) ); ?>"><?php esc_html_e( 'See the plans', 'anchor-theme' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $layers['post'] ); ?>"><?php esc_html_e( 'Read: The layers of web hosting →', 'anchor-theme' ); ?></a>
			</div>
		</div>

		<div
			class="lyr"
			data-layers
			data-geo="<?php echo esc_attr( wp_json_encode( $geo ) ); ?>"
			data-threats="<?php echo esc_attr( wp_json_encode( $layers['threats'] ) ); ?>"
			role="group"
			aria-label="<?php echo esc_attr( $layers['label'] ); ?>"
		>
			<div class="lyr__head">
				<div class="lyr__ask">
					<b><?php echo esc_html( $layers['ask'] ); ?></b>
				</div>
				<button type="button" class="lyr__pause" data-layers-pause aria-pressed="false" hidden>
					<?php echo $pause_svg . $play_svg; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span data-layers-pause-label><?php esc_html_e( 'Pause', 'anchor-theme' ); ?></span>
				</button>
			</div>

			<div class="lyr__stage" data-layers-stage style="aspect-ratio: <?php echo (int) $geo['vbw']; ?> / <?php echo (int) $geo['vbh']; ?>">
				<svg class="lyr__svg" viewBox="0 0 <?php echo (int) $geo['vbw']; ?> <?php echo (int) $geo['vbh']; ?>" aria-hidden="true" focusable="false">
					<?php
					// Floor shadow under the bottom slab.
					$fy = $geo['y0'] + ( $count - 1 ) * $geo['gap'] + $geo['t'] + 14;
					?>
					<polygon class="lyr__floor" points="<?php echo esc_attr( $p( [ [ $geo['cx'], $fy - $geo['h'] ], [ $geo['cx'] + $geo['w'] + 10, $fy ], [ $geo['cx'], $fy + $geo['h'] + 4 ], [ $geo['cx'] - $geo['w'] - 10, $fy ] ] ) ); ?>"></polygon>
					<?php
					// Paint bottom-up so each slab overlaps the one below it.
					for ( $i = $count - 1; $i >= 0; $i-- ) :
						$layer = $layers['layers'][ $i ];
						$y     = $geo['y0'] + $i * $geo['gap'];
						$cx    = $geo['cx'];
						$w     = $geo['w'];
						$h     = $geo['h'];
						$t     = $geo['t'];
						$k     = 1.45; // decal scale.
						$ins   = .76;  // inset outline.
						$ly    = $y + $t / 2;
						?>
						<g class="lyr__slab lyr--<?php echo esc_attr( $layer['tone'] ); ?><?php echo $i === $selected ? ' is-selected' : ''; ?>" data-slab="<?php echo (int) $i; ?>">
							<polygon class="lyr__face lyr__face--left" points="<?php echo esc_attr( $p( [ [ $cx - $w, $y ], [ $cx, $y + $h ], [ $cx, $y + $h + $t ], [ $cx - $w, $y + $t ] ] ) ); ?>"></polygon>
							<polygon class="lyr__face lyr__face--right" points="<?php echo esc_attr( $p( [ [ $cx, $y + $h ], [ $cx + $w, $y ], [ $cx + $w, $y + $t ], [ $cx, $y + $h + $t ] ] ) ); ?>"></polygon>
							<polygon class="lyr__face lyr__face--top" points="<?php echo esc_attr( $p( [ [ $cx, $y - $h ], [ $cx + $w, $y ], [ $cx, $y + $h ], [ $cx - $w, $y ] ] ) ); ?>"></polygon>
							<polygon class="lyr__inset" points="<?php echo esc_attr( $p( [ [ $cx, $y - $h * $ins ], [ $cx + $w * $ins, $y ], [ $cx, $y + $h * $ins ], [ $cx - $w * $ins, $y ] ] ) ); ?>"></polygon>
							<polygon class="lyr__pulse" points="<?php echo esc_attr( $p( [ [ $cx, $y - $h ], [ $cx + $w, $y ], [ $cx, $y + $h ], [ $cx - $w, $y ] ] ) ); ?>"></polygon>
							<g class="lyr__decal" transform="matrix(<?php echo esc_attr( implode( ' ', [ $k, round( $k * $h / $w, 4 ), -$k, round( $k * $h / $w, 4 ), $cx, round( $y + $h * .3 - 12 * 2 * $k * $h / $w, 2 ) ] ) ); ?>)">
								<?php echo anchor_icon( $layer['icon'], 24, 1.4 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
							</g>
							<text class="lyr__side" transform="matrix(1 <?php echo esc_attr( round( $h / $w, 4 ) ); ?> 0 1 <?php echo esc_attr( $cx - $w + 12 ); ?> <?php echo esc_attr( $y + $t - 5 ); ?>)"><?php echo esc_html( strtoupper( $layer['side'] ?? $layer['name'] ) ); ?></text>
							<?php for ( $d = 0; $d < 3; $d++ ) : $f = .66 + $d * .09; ?>
								<circle class="lyr__led" cx="<?php echo esc_attr( round( $cx + $f * $w, 1 ) ); ?>" cy="<?php echo esc_attr( round( $y + $h - $f * $h + $t / 2, 1 ) ); ?>" r="2"></circle>
							<?php endfor; ?>
							<path class="lyr__leader" d="M<?php echo esc_attr( ( $cx + $w + 6 ) . ' ' . $ly ); ?>H<?php echo esc_attr( $geo['lx'] - 10 ); ?>"></path>
							<circle class="lyr__leader-dot" cx="<?php echo esc_attr( $geo['lx'] - 10 ); ?>" cy="<?php echo esc_attr( $ly ); ?>" r="2.6"></circle>
						</g>
					<?php endfor; ?>
				</svg>

				<div class="lyr__labels" role="tablist" aria-label="<?php esc_attr_e( 'Layers', 'anchor-theme' ); ?>">
					<?php foreach ( $layers['layers'] as $i => $layer ) : ?>
						<?php $ly = $geo['y0'] + $i * $geo['gap'] + $geo['t'] / 2; ?>
						<button
							type="button"
							class="lyr__label lyr--<?php echo esc_attr( $layer['tone'] ); ?><?php echo $i === $selected ? ' is-selected' : ''; ?>"
							role="tab"
							id="lyr-tab-<?php echo esc_attr( $layer['key'] ); ?>"
							aria-controls="lyr-detail"
							aria-selected="<?php echo $i === $selected ? 'true' : 'false'; ?>"
							tabindex="<?php echo $i === $selected ? '0' : '-1'; ?>"
							data-layer="<?php echo esc_attr( $layer['key'] ); ?>"
							style="left: <?php echo esc_attr( round( $geo['lx'] / $geo['vbw'] * 100, 3 ) ); ?>%; top: <?php echo esc_attr( round( $ly / $geo['vbh'] * 100, 3 ) ); ?>%"
						>
							<b><?php echo esc_html( $layer['name'] ); ?></b>
							<small class="lyr__meta"><?php echo esc_html( $layer['meta'] ); ?></small>
							<small class="lyr__catch" data-layers-catch data-rest="<?php echo esc_attr( $layer['catch'] ); ?>"><?php echo esc_html( $layer['catch'] ); ?></small>
						</button>
					<?php endforeach; ?>
				</div>

				<span class="lyr__threat" data-layers-threat aria-hidden="true">
					<span class="lyr__threat-icon"><?php echo anchor_icon( 'alert', 12, 2.4 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="lyr__threat-done"><?php echo anchor_icon( 'check', 12, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span data-layers-threat-text></span>
				</span>
			</div>

			<p class="lyr__ticker" data-layers-ticker>
				<i aria-hidden="true"></i>
				<span data-layers-ticker-text><?php echo esc_html( $layers['threats'][0]['line'] ); ?></span>
			</p>
		</div>

		<div class="lyr-detail" id="lyr-detail" role="tabpanel" aria-labelledby="lyr-tab-<?php echo esc_attr( $layers['layers'][ $selected ]['key'] ); ?>" data-layers-detail>
			<?php foreach ( $layers['layers'] as $i => $layer ) : ?>
				<div class="lyr-detail__panel lyr--<?php echo esc_attr( $layer['tone'] ); ?><?php echo $i === $selected ? ' is-active' : ''; ?>" data-panel="<?php echo esc_attr( $layer['key'] ); ?>"<?php echo $i === $selected ? '' : ' aria-hidden="true"'; ?>>
					<div class="lyr-detail__head">
						<span class="lyr-detail__icon" aria-hidden="true"><?php echo anchor_icon( $layer['icon'], 18, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<div>
							<small><?php echo esc_html( sprintf( 'Layer %1$d of %2$d', $i + 1, $count ) ); ?></small>
							<b><?php echo esc_html( $layer['name'] ); ?></b>
						</div>
						<span class="lyr-detail__who"><?php echo esc_html( $layer['who'] ); ?></span>
					</div>
					<p><?php echo esc_html( $layer['text'] ); ?></p>
					<ul class="lyr-detail__list">
						<?php foreach ( $layer['list'] as $j => $item ) : ?>
							<li data-item="<?php echo (int) $j; ?>"><span><?php echo esc_html( $item[0] ); ?></span><i aria-hidden="true">→</i><em><?php echo esc_html( $item[1] ); ?></em></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
