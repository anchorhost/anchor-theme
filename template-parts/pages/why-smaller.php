<?php
/**
 * "Why smaller is better" page layout.
 *
 * Hero → to-scale host comparison chart → powered-by callout → the
 * no-opt-out management grid. Editor content, when present, renders below
 * the hero so the page stays editable without losing the designed shell.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = trim( get_the_content() );
$compare = anchor_host_comparison();
$powered = anchor_powered_by();
$managed = anchor_managed_services();

$max_sites = 0;
foreach ( $compare['hosts'] as $host ) {
	$max_sites = max( $max_sites, (int) $host['sites'] );
}
?>
<section class="page-hero">

	<div>
		<div class="eyebrow"><?php esc_html_e( 'Philosophy', 'anchor-theme' ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php esc_html_e( 'The big hosts measure themselves in millions of sites. Anchor Hosting manages 3,000. On purpose. At this size every single site can be fully managed, and there is no plan where it isn\'t.', 'anchor-theme' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="page-hero__art">
		<?php // The current brand mark (never the retired 2015 keystone logo), navy per the brand page: navy on light, sky on dark. ?>
		<?php anchor_illustration( 'anchor-mark.svg', __( 'Anchor mark', 'anchor-theme' ), 'max-width:220px;height:220px;background-color:var(--navy)' ); ?>
	</div>

</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<?php if ( $compare['hosts'] ) : ?>
<section class="compare">
	<div class="compare__panel">

		<div class="eyebrow"><?php echo esc_html( $compare['eyebrow'] ); ?></div>
		<h2 class="compare__title"><?php echo esc_html( $compare['title'] ); ?></h2>
		<p class="compare__lede"><?php echo esc_html( $compare['lede'] ); ?></p>

		<div class="compare-rows">
			<?php foreach ( $compare['hosts'] as $host ) : ?>
				<?php
				$is_us = ! empty( $host['us'] );
				$is_na = ! empty( $host['unknown'] );
				$pct   = $max_sites ? round( (int) $host['sites'] / $max_sites * 78, 2 ) : 0;
				?>
				<div class="compare-row<?php echo $is_us ? ' compare-row--us' : ''; ?><?php echo $is_na ? ' compare-row--na' : ''; ?>">
					<div class="compare-row__name">
						<?php echo esc_html( $host['name'] ); ?>
						<span class="compare-row__what"><?php echo esc_html( $host['counts'] ); ?></span>
					</div>
					<div class="compare-row__track">
						<?php if ( ! $is_na ) : ?>
							<div class="compare-row__bar" style="--bar:<?php echo esc_attr( $pct ); ?>%"></div>
						<?php endif; ?>
						<div class="compare-row__value" style="--bar:<?php echo esc_attr( $pct ); ?>%">
							<?php echo esc_html( $host['display'] ); ?><?php if ( $is_us ) : ?> <span class="compare-row__us-tag"><?php esc_html_e( '← that\'s us', 'anchor-theme' ); ?></span><?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="compare__note">
			<?php echo esc_html( $compare['note'] ); ?>
			<?php
			$links = [];
			foreach ( $compare['hosts'] as $host ) {
				if ( ! empty( $host['source_url'] ) ) {
					$links[] = sprintf(
						'<a href="%s" target="_blank" rel="noopener">%s</a>',
						esc_url( $host['source_url'] ),
						esc_html( $host['name'] )
					);
				}
			}
			if ( $links ) {
				printf(
					/* translators: %s: comma-separated list of source links. */
					esc_html__( 'Sources: %s.', 'anchor-theme' ),
					implode( ', ', $links ) // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above.
				);
			}
			?>
		</p>

	</div>
</section>
<?php endif; ?>

<section class="powered">
	<div class="section-head">
		<div>
			<div class="eyebrow"><?php echo esc_html( $powered['eyebrow'] ); ?></div>
			<h2 class="powered__title"><?php echo esc_html( $powered['title'] ); ?></h2>
			<p class="powered__lede"><?php echo esc_html( $powered['lede'] ); ?></p>
		</div>
	</div>

	<div class="powered__grid">
		<?php foreach ( $powered['partners'] as $item ) : ?>
			<a class="infra-card" href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener">
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
</section>

<section class="managed">
	<div class="section-head">
		<div>
			<div class="eyebrow"><?php echo esc_html( $managed['eyebrow'] ); ?></div>
			<h2 class="managed__title"><?php echo esc_html( $managed['title'] ); ?></h2>
			<p class="managed__lede"><?php echo esc_html( $managed['lede'] ); ?></p>
		</div>
	</div>

	<div class="managed__grid">
		<?php foreach ( $managed['items'] as $card ) : ?>
			<div class="security-card">
				<div class="security-card__tag"><?php echo esc_html( $card['tag'] ); ?></div>
				<h3 class="security-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
				<p class="security-card__text"><?php echo esc_html( $card['body'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</section>
