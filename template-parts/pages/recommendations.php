<?php
/**
 * Recommendations page layout.
 *
 * Hero → in-page nav → directory of web professionals (filterable) →
 * premium plugin licenses → theme picks + shops → closing CTA. Editor
 * content, when present, renders below the hero so the page stays
 * editable without losing the designed shell.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = trim( get_the_content() );
$rec     = anchor_recommendations();
$pros    = anchor_recommended_pros();
$plugins = anchor_recommended_plugins();
$themes  = anchor_recommended_themes();

/**
 * Two-letter monogram for cards without a logo.
 */
$anchor_monogram = function ( $name ) {
	$words = preg_split( '/[\s\-]+/', trim( wp_strip_all_tags( $name ) ) );
	$words = array_values( array_filter( $words, function ( $w ) {
		return preg_match( '/^[\p{L}\p{N}]/u', $w );
	} ) );
	if ( count( $words ) >= 2 ) {
		return mb_strtoupper( mb_substr( $words[0], 0, 1 ) . mb_substr( $words[1], 0, 1 ) );
	}
	return mb_strtoupper( mb_substr( $words[0] ?? $name, 0, 2 ) );
};

/**
 * Bare domain for the card's link line.
 */
$anchor_domain = function ( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return $host ? preg_replace( '/^www\./', '', $host ) : $url;
};

// Only render facets that at least one pro matches.
$facets = [];
foreach ( anchor_recommended_pro_facets() as $facet ) {
	foreach ( $pros as $pro ) {
		if ( false !== stripos( $pro['tags'], $facet['match'] ) ) {
			$facets[] = $facet;
			break;
		}
	}
}

$sections = [
	$rec['pros']['id']    => __( 'Web professionals', 'anchor-theme' ),
	$rec['plugins']['id'] => __( 'Plugins', 'anchor-theme' ),
	$rec['themes']['id']  => __( 'Themes', 'anchor-theme' ),
];
?>
<section class="page-hero page-hero--rec">

	<div>
		<div class="eyebrow"><?php echo esc_html( $rec['eyebrow'] ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php echo esc_html( $rec['lede'] ); ?></p>
		<?php endif; ?>

		<nav class="rec-nav" aria-label="<?php esc_attr_e( 'On this page', 'anchor-theme' ); ?>">
			<div class="pill-group">
				<?php foreach ( $sections as $id => $label ) : ?>
					<a class="pill" href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>
		</nav>
	</div>

	<div class="page-hero__art">
		<?php anchor_illustration( 'proactive.svg', __( 'Compass', 'anchor-theme' ), 'max-width:300px;height:260px' ); ?>
	</div>

</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<section class="rec-section" id="<?php echo esc_attr( $rec['pros']['id'] ); ?>">
	<div class="section-head">
		<div>
			<div class="eyebrow"><?php echo esc_html( $rec['pros']['eyebrow'] ); ?></div>
			<h2 class="rec-section__title"><?php echo esc_html( $rec['pros']['title'] ); ?></h2>
			<p class="rec-section__lede"><?php echo esc_html( $rec['pros']['lede'] ); ?></p>
		</div>
	</div>

	<?php if ( $facets ) : ?>
		<div class="rec-filter" data-rec-filter>
			<div class="pill-group" role="group" aria-label="<?php esc_attr_e( 'Filter by specialty', 'anchor-theme' ); ?>">
				<button type="button" class="pill is-active" data-facet="" aria-pressed="true"><?php esc_html_e( 'All', 'anchor-theme' ); ?></button>
				<?php foreach ( $facets as $facet ) : ?>
					<button type="button" class="pill" data-facet="<?php echo esc_attr( strtolower( $facet['match'] ) ); ?>" aria-pressed="false"><?php echo esc_html( $facet['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<span class="rec-filter__count" data-rec-count aria-live="polite"></span>
		</div>
	<?php endif; ?>

	<div class="rec-grid" data-rec-grid>
		<?php foreach ( $pros as $pro ) : ?>
			<?php
			$logo = ! empty( $pro['logo'] ) ? wp_get_attachment_image( (int) $pro['logo'], 'thumbnail', false, [
				'class'   => 'pro-card__img',
				'alt'     => '',
				'loading' => 'lazy',
			] ) : '';
			$tags = array_filter( array_map( 'trim', explode( ',', $pro['tags'] ) ) );
			?>
			<a class="pro-card" href="<?php echo esc_url( $pro['url'] ); ?>" target="_blank" rel="noopener" data-rec-tags="<?php echo esc_attr( strtolower( $pro['tags'] ) ); ?>">
				<span class="pro-card__logo<?php echo $logo ? '' : ' pro-card__logo--mono'; ?>" aria-hidden="true">
					<?php echo $logo ? $logo : esc_html( $anchor_monogram( $pro['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- wp_get_attachment_image() output. ?>
				</span>
				<span class="pro-card__body">
					<span class="pro-card__name"><?php echo esc_html( $pro['name'] ); ?></span>
					<span class="pro-card__domain"><?php echo esc_html( $anchor_domain( $pro['url'] ) ); ?> <?php echo anchor_icon( 'external', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<?php if ( $tags ) : ?>
						<span class="pro-card__tags">
							<?php foreach ( $tags as $tag ) : ?>
								<span class="tag"><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</span>
					<?php endif; ?>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
	<p class="rec-empty" data-rec-empty hidden><?php esc_html_e( 'Nobody matches that filter yet.', 'anchor-theme' ); ?></p>
</section>

<section class="rec-section" id="<?php echo esc_attr( $rec['plugins']['id'] ); ?>">
	<div class="section-head">
		<div>
			<div class="eyebrow"><?php echo esc_html( $rec['plugins']['eyebrow'] ); ?></div>
			<h2 class="rec-section__title"><?php echo esc_html( $rec['plugins']['title'] ); ?></h2>
			<p class="rec-section__lede"><?php echo esc_html( $rec['plugins']['lede'] ); ?></p>
		</div>
	</div>

	<div class="rec-tools">
		<?php foreach ( $plugins as $tool ) : ?>
			<div class="tool-card">
				<a class="tool-card__main" href="<?php echo esc_url( $tool['url'] ); ?>" target="_blank" rel="noopener">
					<span class="tool-card__chip" aria-hidden="true"><?php echo esc_html( $anchor_monogram( $tool['name'] ) ); ?></span>
					<span>
						<span class="tool-card__kind"><?php echo esc_html( $tool['kind'] ); ?></span>
						<span class="tool-card__name"><?php echo esc_html( $tool['name'] ); ?></span>
					</span>
				</a>
				<?php if ( ! empty( $tool['note'] ) ) : ?>
					<a class="tool-card__note" href="<?php echo esc_url( $tool['note']['href'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $tool['note']['label'] ); ?></a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="rec-section rec-section--last" id="<?php echo esc_attr( $rec['themes']['id'] ); ?>">
	<div class="section-head">
		<div>
			<div class="eyebrow"><?php echo esc_html( $rec['themes']['eyebrow'] ); ?></div>
			<h2 class="rec-section__title"><?php echo esc_html( $rec['themes']['title'] ); ?></h2>
			<p class="rec-section__lede"><?php echo esc_html( $rec['themes']['lede'] ); ?></p>
		</div>
	</div>

	<div class="rec-tools">
		<?php foreach ( $themes['picks'] as $theme ) : ?>
			<div class="tool-card">
				<a class="tool-card__main" href="<?php echo esc_url( $theme['url'] ); ?>" target="_blank" rel="noopener">
					<span class="tool-card__chip" aria-hidden="true"><?php echo esc_html( $anchor_monogram( $theme['name'] ) ); ?></span>
					<span>
						<span class="tool-card__kind"><?php esc_html_e( 'Theme', 'anchor-theme' ); ?></span>
						<span class="tool-card__name"><?php echo esc_html( $theme['name'] ); ?></span>
					</span>
				</a>
				<span class="tool-card__note">
					<?php
					if ( ! empty( $theme['by_url'] ) ) {
						printf(
							/* translators: %s: theme author, linked. */
							esc_html__( 'by %s', 'anchor-theme' ),
							'<a href="' . esc_url( $theme['by_url'] ) . '" target="_blank" rel="noopener">' . esc_html( $theme['by'] ) . '</a>'
						);
					} else {
						/* translators: %s: theme author. */
						printf( esc_html__( 'by %s', 'anchor-theme' ), esc_html( $theme['by'] ) );
					}
					?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( ! empty( $themes['shops'] ) ) : ?>
		<div class="rec-shops">
			<p class="rec-shops__lede"><?php echo esc_html( $rec['themes']['more'] ); ?></p>
			<ul class="rec-shops__list">
				<?php foreach ( $themes['shops'] as $shop ) : ?>
					<li><a class="rec-shop" href="<?php echo esc_url( $shop['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $shop['name'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( ! empty( $rec['themes']['further'] ) ) : ?>
				<p class="rec-shops__further">
					<?php esc_html_e( 'Still need more?', 'anchor-theme' ); ?>
					<a href="<?php echo esc_url( $rec['themes']['further']['href'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $rec['themes']['further']['label'] ); ?></a>.
				</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>

<section class="cta">
	<div class="cta__inner">
		<div>
			<h2 class="cta__title"><?php echo esc_html( $rec['cta']['title'] ); ?></h2>
			<p class="cta__text"><?php echo esc_html( $rec['cta']['text'] ); ?></p>
			<div class="cta__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( $rec['cta']['primary']['href'] ); ?>"><?php echo esc_html( $rec['cta']['primary']['label'] ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $rec['cta']['ghost']['href'] ); ?>"><?php echo esc_html( $rec['cta']['ghost']['label'] ); ?></a>
			</div>
		</div>
		<div class="cta__art">
			<?php anchor_illustration( 'ship.svg', __( 'Ship', 'anchor-theme' ) ); ?>
		</div>
	</div>
</section>
