<?php
/**
 * Plans page layout — pricing cards, self-serve calculator, add-ons.
 *
 * Rendered server-side at the default monthly cycle; assets/js/calculator.js
 * takes over for cycle switching and the sliders.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plans    = anchor_plans();
$cycles   = anchor_billing_cycles();
$featured = anchor_featured_plan();
$rates    = anchor_addon_rates();
$content  = trim( get_the_content() );

// Server-rendered defaults, matched by the JS initial state.
$default_sites   = 12;
$default_storage = 40;
$default_views   = 1200000;
$quote           = anchor_quote( $default_sites, $default_storage, $default_views );

$lines = [ [ 'label' => $quote['plan']['name'] . ' base', 'amount' => anchor_money( $quote['plan']['m'] ) ] ];

if ( $quote['over']['sites'] ) {
	$lines[] = [
		'label'  => '+' . ( $default_sites - $quote['plan']['sites'] ) . ' sites',
		'amount' => anchor_money( $quote['over']['sites'] ),
	];
}
if ( $quote['over']['gb'] ) {
	$lines[] = [
		'label'  => '+' . ( $default_storage - $quote['plan']['gb'] ) . ' GB storage',
		'amount' => anchor_money( $quote['over']['gb'] ),
	];
}
if ( $quote['over']['pv'] ) {
	$lines[] = [ 'label' => 'extra pageviews', 'amount' => anchor_money( $quote['over']['pv'] ) ];
}
if ( 1 === count( $lines ) ) {
	$lines[] = [ 'label' => 'no add-ons needed', 'amount' => '$0' ];
}

$views_label = $default_views >= 1000000
	? rtrim( rtrim( number_format( $default_views / 1000000, 1 ), '0' ), '.' ) . 'M'
	: ( $default_views / 1000 ) . 'k';
?>
<section class="plans-hero">
	<h1 class="plans-hero__title"><?php the_title(); ?></h1>

	<?php if ( has_excerpt() ) : ?>
		<p class="plans-hero__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
	<?php else : ?>
		<p class="plans-hero__lede"><?php esc_html_e( 'Every plan includes free migration, automated updates, nightly backups, security scanning and personal support. Grow by add-on, not by re-platforming.', 'anchor-theme' ); ?></p>
	<?php endif; ?>

	<div class="plans-hero__cycles">
		<div class="pill-group" role="group" aria-label="<?php esc_attr_e( 'Billing cycle', 'anchor-theme' ); ?>">
			<?php $first = true; ?>
			<?php foreach ( $cycles as $key => $cycle ) : ?>
				<button
					type="button"
					class="pill<?php echo $first ? ' is-active' : ''; ?>"
					data-cycle="<?php echo esc_attr( $key ); ?>"
					aria-pressed="<?php echo $first ? 'true' : 'false'; ?>"
				><?php echo esc_html( $cycle['label'] ); ?></button>
				<?php $first = false; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="plans__grid">
	<?php foreach ( $plans as $plan ) : ?>
		<?php $is_featured = ( $plan['name'] === $featured ); ?>
		<div class="plan<?php echo $is_featured ? ' is-featured' : ''; ?>">

			<div class="plan__head">
				<h3 class="plan__name"><?php echo esc_html( $plan['name'] ); ?></h3>
				<?php if ( $is_featured ) : ?>
					<span class="plan__badge"><?php esc_html_e( 'Most agencies', 'anchor-theme' ); ?></span>
				<?php endif; ?>
			</div>

			<div class="plan__pricing">
				<span class="plan__price" data-plan-price data-base="<?php echo esc_attr( $plan['m'] ); ?>"><?php echo esc_html( anchor_money( $plan['m'] ) ); ?></span>
				<span class="plan__period" data-plan-period>/mo</span>
			</div>

			<div class="plan__equiv" data-plan-equiv><?php esc_html_e( 'billed monthly', 'anchor-theme' ); ?></div>

			<ul class="plan__features">
				<li>
					<span class="check"><?php echo anchor_icon( 'check', 16, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php
					printf(
						/* translators: %d: number of sites. */
						esc_html( _n( '%d site', '%d sites', $plan['sites'], 'anchor-theme' ) ),
						(int) $plan['sites']
					);
					?>
				</li>
				<li>
					<span class="check"><?php echo anchor_icon( 'check', 16, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php
					printf(
						/* translators: %d: storage in gigabytes. */
						esc_html__( '%d GB storage', 'anchor-theme' ),
						(int) $plan['gb']
					);
					?>
				</li>
				<li>
					<span class="check"><?php echo anchor_icon( 'check', 16, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php
					$pv = $plan['pv'] >= 1000000 ? ( $plan['pv'] / 1000000 ) . 'M' : ( $plan['pv'] / 1000 ) . 'k';
					printf(
						/* translators: %s: pageview allowance, e.g. "1M". */
						esc_html__( '%s pageviews/year', 'anchor-theme' ),
						esc_html( $pv )
					);
					?>
				</li>
			</ul>

			<a class="plan__cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get started', 'anchor-theme' ); ?></a>

		</div>
	<?php endforeach; ?>
</section>

<section class="calc-section">
	<div class="calc" data-calculator>
		<div class="calc__grid">

			<div>
				<h2 class="calc__title"><?php esc_html_e( 'Size it yourself', 'anchor-theme' ); ?></h2>
				<p class="calc__text"><?php esc_html_e( "Drag to match your portfolio. I'll pick the cheapest plan that covers it and add on from there.", 'anchor-theme' ); ?></p>

				<div class="calc__controls">

					<div>
						<div class="calc__row-head">
							<label class="calc__label" for="calc-sites"><?php esc_html_e( 'Sites', 'anchor-theme' ); ?></label>
							<span class="calc__value" data-calc-sites-label><?php echo esc_html( $default_sites ); ?></span>
						</div>
						<input class="calc__range" id="calc-sites" type="range" min="1" max="60" step="1" value="<?php echo esc_attr( $default_sites ); ?>" data-calc-sites />
					</div>

					<div>
						<div class="calc__row-head">
							<label class="calc__label" for="calc-storage"><?php esc_html_e( 'Storage', 'anchor-theme' ); ?></label>
							<span class="calc__value" data-calc-storage-label><?php echo esc_html( $default_storage ); ?> GB</span>
						</div>
						<input class="calc__range" id="calc-storage" type="range" min="10" max="300" step="10" value="<?php echo esc_attr( $default_storage ); ?>" data-calc-storage />
					</div>

					<div>
						<div class="calc__row-head">
							<label class="calc__label" for="calc-views"><?php esc_html_e( 'Pageviews / year', 'anchor-theme' ); ?></label>
							<span class="calc__value" data-calc-views-label><?php echo esc_html( $views_label ); ?></span>
						</div>
						<input class="calc__range" id="calc-views" type="range" min="100000" max="8000000" step="100000" value="<?php echo esc_attr( $default_views ); ?>" data-calc-views />
					</div>

				</div>
			</div>

			<div class="calc__summary">
				<div class="calc__summary-kicker"><?php esc_html_e( 'Recommended', 'anchor-theme' ); ?></div>
				<div class="calc__plan">
					<span data-calc-plan><?php echo esc_html( $quote['plan']['name'] ); ?></span> <?php esc_html_e( 'plan', 'anchor-theme' ); ?>
				</div>

				<div class="calc__lines" data-calc-lines>
					<?php foreach ( $lines as $line ) : ?>
						<div class="calc__line">
							<span><?php echo esc_html( $line['label'] ); ?></span>
							<span><?php echo esc_html( $line['amount'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="calc__total-row">
					<span class="calc__total-label"><?php esc_html_e( 'Total', 'anchor-theme' ); ?></span>
					<span class="calc__total" data-calc-total><?php echo esc_html( anchor_money( $quote['total'] ) ); ?></span>
				</div>

				<div class="calc__cycle-note">
					<?php esc_html_e( 'per month, billed', 'anchor-theme' ); ?> <span data-calc-cycle-word><?php esc_html_e( 'monthly', 'anchor-theme' ); ?></span>
				</div>
			</div>

		</div>
	</div>
</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<section class="plans-extras">
	<div class="plans-extras__grid">

		<div class="extras-card">
			<h3 class="extras-card__title"><?php esc_html_e( 'Add-ons', 'anchor-theme' ); ?></h3>
			<div class="addons">
				<div class="addons__row">
					<span><?php esc_html_e( 'Additional site', 'anchor-theme' ); ?></span>
					<span class="addons__price"><?php echo esc_html( anchor_money( $rates['site'] ) ); ?>/mo</span>
				</div>
				<div class="addons__row">
					<span><?php esc_html_e( 'Extra 10 GB storage', 'anchor-theme' ); ?></span>
					<span class="addons__price"><?php echo esc_html( anchor_money( $rates['storage'] ) ); ?>/mo</span>
				</div>
				<div class="addons__row">
					<span><?php esc_html_e( 'Extra 1M pageviews/yr', 'anchor-theme' ); ?></span>
					<span class="addons__price"><?php echo esc_html( anchor_money( $rates['pageviews'] ) ); ?>/mo</span>
				</div>
			</div>
		</div>

		<div class="extras-card extras-card--wide">
			<h3 class="extras-card__title"><?php esc_html_e( 'Every plan includes', 'anchor-theme' ); ?></h3>
			<div class="includes">
				<?php foreach ( anchor_plan_includes() as $item ) : ?>
					<div class="includes__item">
						<span class="check"><?php echo anchor_icon( 'check', 15, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php echo esc_html( $item ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
