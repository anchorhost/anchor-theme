<?php
/**
 * Brand page layout — logo previews, downloadable asset kit, palette,
 * type and usage rules. Meant to live on a private page.
 *
 * Asset files are generated outside the theme into uploads/brand/ (the
 * lockup wordmark is outlined from Plus Jakarta Sans Bold, so it renders
 * identically everywhere). The palette documented here mirrors the tokens
 * in assets/css/theme.css.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kit     = anchor_brand_kit();
$content = trim( get_the_content() );
$base    = wp_upload_dir()['baseurl'] . '/brand';

$downloads = [
	[
		'title'   => __( 'Lockup', 'anchor-theme' ),
		'desc'    => __( 'Mark + wordmark, for headers, documents and anywhere there is room.', 'anchor-theme' ),
		'preview' => 'anchor-lockup.svg',
		'files'   => [
			[ 'anchor-lockup.svg', 'SVG · light' ],
			[ 'anchor-lockup-dark.svg', 'SVG · dark scheme' ],
			[ 'anchor-lockup-white.svg', 'SVG · all white' ],
			[ 'anchor-lockup-large.png', 'PNG · 544px tall' ],
			[ 'anchor-lockup-white-large.png', 'PNG · white' ],
			[ 'anchor-lockup-dark-large.png', 'PNG · dark scheme' ],
		],
	],
	[
		'title'   => __( 'Mark', 'anchor-theme' ),
		'desc'    => __( 'The bare anchor glyph. The default — it stands without a tile.', 'anchor-theme' ),
		'preview' => 'anchor-mark.svg',
		'files'   => [
			[ 'anchor-mark.svg', 'SVG · navy' ],
			[ 'anchor-mark-white.svg', 'SVG · white' ],
			[ 'anchor-mark-ink.svg', 'SVG · ink' ],
			[ 'anchor-mark-512.png', 'PNG · 512' ],
			[ 'anchor-mark-1024.png', 'PNG · 1024' ],
			[ 'anchor-mark-white-512.png', 'PNG · white 512' ],
		],
	],
	[
		'title'   => __( 'App icon', 'anchor-theme' ),
		'desc'    => __( 'White mark on a navy rounded tile — avatars, favicons, app icons only.', 'anchor-theme' ),
		'preview' => 'anchor-icon-tile.svg',
		'files'   => [
			[ 'anchor-icon-tile.svg', 'SVG' ],
			[ 'anchor-icon-tile-256.png', 'PNG · 256' ],
			[ 'anchor-icon-tile-512.png', 'PNG · 512' ],
			[ 'anchor-icon-tile-1024.png', 'PNG · 1024' ],
		],
	],
	[
		'title'   => __( 'Legacy', 'anchor-theme' ),
		'desc'    => __( 'The 2015 logo, kept for reference. Retired — do not use in new work.', 'anchor-theme' ),
		'preview' => 'legacy-logo-2015.png',
		'files'   => [
			[ 'legacy-logo-2015.png', 'PNG · original' ],
		],
	],
];
?>
<section class="plans-hero">
	<?php
	// Drop WordPress's "Private:" prefix — the page being private is an
	// admin detail, not part of its name.
	add_filter( 'private_title_format', 'anchor_brand_title_format' );
	?>
	<h1 class="plans-hero__title"><?php the_title(); ?></h1>
	<?php remove_filter( 'private_title_format', 'anchor_brand_title_format' ); ?>
	<p class="plans-hero__lede"><?php echo esc_html( $kit['lede'] ); ?></p>
</section>

<div class="brandkit">

	<section class="brandkit__section">
		<h2 class="brandkit__h"><?php esc_html_e( 'Logo', 'anchor-theme' ); ?></h2>
		<p class="brandkit__note"><?php esc_html_e( 'One drawing, three treatments: the lockup where there is room, the bare mark where there is not, the tile only where a square icon is required.', 'anchor-theme' ); ?></p>

		<div class="brandkit__stage-grid">
			<div class="brand-stage brand-stage--light">
				<img src="<?php echo esc_url( $base . '/anchor-lockup.svg' ); ?>" alt="<?php esc_attr_e( 'Anchor Hosting lockup on light', 'anchor-theme' ); ?>" class="brand-stage__lockup" />
			</div>
			<div class="brand-stage brand-stage--navy">
				<img src="<?php echo esc_url( $base . '/anchor-lockup-white.svg' ); ?>" alt="<?php esc_attr_e( 'Anchor Hosting lockup in white on navy', 'anchor-theme' ); ?>" class="brand-stage__lockup" />
			</div>
			<div class="brand-stage brand-stage--light brand-stage--half">
				<img src="<?php echo esc_url( $base . '/anchor-mark.svg' ); ?>" alt="<?php esc_attr_e( 'Bare anchor mark', 'anchor-theme' ); ?>" class="brand-stage__mark" />
			</div>
			<div class="brand-stage brand-stage--light brand-stage--half">
				<img src="<?php echo esc_url( $base . '/anchor-icon-tile.svg' ); ?>" alt="<?php esc_attr_e( 'Anchor app icon tile', 'anchor-theme' ); ?>" class="brand-stage__mark brand-stage__mark--tile" />
			</div>
		</div>
	</section>

	<section class="brandkit__section">
		<div class="brandkit__head-row">
			<h2 class="brandkit__h"><?php esc_html_e( 'Downloads', 'anchor-theme' ); ?></h2>
			<a class="brandkit__zip" href="<?php echo esc_url( $base . '/anchor-brand-kit.zip' ); ?>"><?php esc_html_e( 'Download everything (.zip)', 'anchor-theme' ); ?></a>
		</div>

		<div class="brandkit__dl-grid">
			<?php foreach ( $downloads as $group ) : ?>
				<div class="dl-card">
					<div class="dl-card__preview">
						<img src="<?php echo esc_url( $base . '/' . $group['preview'] ); ?>" alt="" loading="lazy" />
					</div>
					<h3 class="dl-card__title"><?php echo esc_html( $group['title'] ); ?></h3>
					<p class="dl-card__desc"><?php echo esc_html( $group['desc'] ); ?></p>
					<div class="dl-card__files">
						<?php foreach ( $group['files'] as $file ) : ?>
							<a class="dl-card__file" href="<?php echo esc_url( $base . '/' . $file[0] ); ?>" download>
								<span class="dl-card__file-name"><?php echo esc_html( $file[0] ); ?></span>
								<span class="dl-card__file-kind"><?php echo esc_html( $file[1] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="brandkit__section">
		<h2 class="brandkit__h"><?php esc_html_e( 'Colour', 'anchor-theme' ); ?></h2>
		<p class="brandkit__note"><?php esc_html_e( 'The palette is defined twice — once for light, once for dark. Every colour in the product goes through a token; nothing is hardcoded.', 'anchor-theme' ); ?></p>

		<?php foreach ( [ 'light' => __( 'Light', 'anchor-theme' ), 'dark' => __( 'Dark', 'anchor-theme' ) ] as $scheme => $label ) : ?>
			<h3 class="brandkit__sub"><?php echo esc_html( $label ); ?></h3>
			<div class="brandkit__swatches">
				<?php foreach ( $kit['colors'][ $scheme ] as $color ) : ?>
					<div class="swatch">
						<span class="swatch__chip" style="background: <?php echo esc_attr( $color['hex'] ); ?>"></span>
						<span class="swatch__name"><?php echo esc_html( $color['name'] ); ?></span>
						<span class="swatch__hex"><?php echo esc_html( $color['hex'] ); ?> · <?php echo esc_html( $color['var'] ); ?></span>
						<span class="swatch__use"><?php echo esc_html( $color['use'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</section>

	<section class="brandkit__section">
		<h2 class="brandkit__h"><?php esc_html_e( 'Type', 'anchor-theme' ); ?></h2>

		<div class="brandkit__type-grid">
			<div class="specimen">
				<div class="specimen__sample"><?php esc_html_e( 'Hand it off. It gets done.', 'anchor-theme' ); ?></div>
				<div class="specimen__name"><?php esc_html_e( 'Plus Jakarta Sans', 'anchor-theme' ); ?></div>
				<div class="specimen__meta"><?php esc_html_e( 'Headings, UI and body. Weights 400–800; display sizes track tight (−2 to −3.5%).', 'anchor-theme' ); ?></div>
				<div class="specimen__weights">
					<span style="font-weight:400">Aa</span>
					<span style="font-weight:500">Aa</span>
					<span style="font-weight:600">Aa</span>
					<span style="font-weight:700">Aa</span>
					<span style="font-weight:800">Aa</span>
				</div>
			</div>
			<div class="specimen specimen--mono">
				<div class="specimen__sample">$12.50/mo · 99.98% · 4,128</div>
				<div class="specimen__name"><?php esc_html_e( 'JetBrains Mono', 'anchor-theme' ); ?></div>
				<div class="specimen__meta"><?php esc_html_e( 'Numbers, prices, code and anything terminal-flavoured. Weights 400–600.', 'anchor-theme' ); ?></div>
				<div class="specimen__weights">
					<span style="font-weight:400">0123</span>
					<span style="font-weight:500">0123</span>
					<span style="font-weight:600">0123</span>
				</div>
			</div>
		</div>
	</section>

	<section class="brandkit__section">
		<h2 class="brandkit__h"><?php esc_html_e( 'Using the mark', 'anchor-theme' ); ?></h2>

		<div class="brandkit__usage-grid">
			<div class="usage-card">
				<h3 class="usage-card__title usage-card__title--do"><?php esc_html_e( 'Do', 'anchor-theme' ); ?></h3>
				<ul class="usage-card__list">
					<?php foreach ( $kit['usage']['do'] as $rule ) : ?>
						<li><?php echo wp_kses( $rule, [] ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="usage-card">
				<h3 class="usage-card__title usage-card__title--dont"><?php esc_html_e( "Don't", 'anchor-theme' ); ?></h3>
				<ul class="usage-card__list">
					<?php foreach ( $kit['usage']['dont'] as $rule ) : ?>
						<li><?php echo wp_kses( $rule, [] ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<?php if ( $content ) : ?>
		<div class="brandkit__prose prose"><?php the_content(); ?></div>
	<?php endif; ?>

</div>
