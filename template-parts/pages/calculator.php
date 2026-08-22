<?php
/**
 * Hosting Plan Calculator layout — pick a base plan, stack add-ons, live total.
 *
 * Rendered server-side at the defaults (first plan, no extras, monthly);
 * assets/js/plan-builder.js takes over for selection, steppers and cycles.
 * Unlike the plans-page calculator this one never picks a plan for you —
 * the visitor chooses the base and the receipt is plain arithmetic on top.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plans    = array_values( anchor_plans() );
$cycles   = anchor_billing_cycles();
$rates    = anchor_addon_rates();
$copy     = anchor_calculator_copy();
$content  = trim( get_the_content() );
$selected = $plans[0];

$format_views = static function ( $v ) {
	return $v >= 1000000
		? rtrim( rtrim( number_format( $v / 1000000, 1 ), '0' ), '.' ) . 'M'
		: ( $v / 1000 ) . 'k';
};
?>
<section class="plans-hero">
	<h1 class="plans-hero__title"><?php the_title(); ?></h1>

	<?php if ( has_excerpt() ) : ?>
		<p class="plans-hero__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
	<?php else : ?>
		<p class="plans-hero__lede"><?php echo esc_html( $copy['lede'] ); ?></p>
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

<div class="builder" data-builder>

	<section class="builder__step">
		<div class="builder__step-head">
			<span class="builder__step-num" aria-hidden="true">1</span>
			<h2 class="builder__step-title"><?php esc_html_e( 'Choose your base plan', 'anchor-theme' ); ?></h2>
		</div>
		<p class="builder__step-note"><?php echo esc_html( $copy['plan_note'] ); ?></p>

		<div class="plans__grid builder__plans">
			<?php foreach ( $plans as $i => $plan ) : ?>
				<label class="plan plan--select<?php echo 0 === $i ? ' is-selected' : ''; ?>">
					<input
						type="radio"
						class="screen-reader-text"
						name="builder-plan"
						value="<?php echo esc_attr( $plan['name'] ); ?>"
						<?php checked( 0 === $i ); ?>
						data-plan-choice
					/>

					<div class="plan__head">
						<span class="plan__name"><?php echo esc_html( $plan['name'] ); ?></span>
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
							printf(
								/* translators: %s: pageview allowance, e.g. "1M". */
								esc_html__( '%s pageviews/year', 'anchor-theme' ),
								esc_html( $format_views( $plan['pv'] ) )
							);
							?>
						</li>
					</ul>

					<span class="plan__pick" data-pick-label aria-hidden="true">
						<?php echo 0 === $i ? esc_html__( 'Selected', 'anchor-theme' ) : esc_html__( 'Choose', 'anchor-theme' ) . ' ' . esc_html( $plan['name'] ); ?>
					</span>
				</label>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="builder__step">
		<div class="builder__step-head">
			<span class="builder__step-num" aria-hidden="true">2</span>
			<h2 class="builder__step-title"><?php esc_html_e( 'Add extras', 'anchor-theme' ); ?></h2>
		</div>
		<p class="builder__step-note"><?php echo esc_html( $copy['extras_note'] ); ?></p>

		<div class="builder__extras">

			<div class="extra-card">
				<div class="extra-card__label"><?php esc_html_e( 'Extra sites', 'anchor-theme' ); ?></div>
				<div class="extra-card__rate"><?php echo esc_html( anchor_money( $rates['site'] ) ); ?>/mo <?php esc_html_e( 'each', 'anchor-theme' ); ?></div>
				<div class="stepper">
					<button type="button" class="stepper__btn" data-step="-1" aria-label="<?php esc_attr_e( 'Fewer extra sites', 'anchor-theme' ); ?>">&minus;</button>
					<input class="stepper__value" type="number" inputmode="numeric" min="0" max="500" step="1" value="0" data-extra="sites" aria-label="<?php esc_attr_e( 'Extra sites', 'anchor-theme' ); ?>" />
					<button type="button" class="stepper__btn" data-step="1" aria-label="<?php esc_attr_e( 'More extra sites', 'anchor-theme' ); ?>">+</button>
				</div>
				<div class="extra-card__unit" data-extra-note-sites>
					<?php
					printf(
						/* translators: %d: sites included in the selected plan. */
						esc_html__( 'beyond the %d included', 'anchor-theme' ),
						(int) $selected['sites']
					);
					?>
				</div>
			</div>

			<div class="extra-card">
				<div class="extra-card__label"><?php esc_html_e( 'Extra storage', 'anchor-theme' ); ?></div>
				<div class="extra-card__rate"><?php echo esc_html( anchor_money( $rates['storage'] ) ); ?>/mo <?php esc_html_e( 'per 10 GB', 'anchor-theme' ); ?></div>
				<div class="stepper">
					<button type="button" class="stepper__btn" data-step="-1" aria-label="<?php esc_attr_e( 'Less extra storage', 'anchor-theme' ); ?>">&minus;</button>
					<input class="stepper__value" type="number" inputmode="numeric" min="0" max="100" step="1" value="0" data-extra="storage" aria-label="<?php esc_attr_e( 'Extra storage, blocks of 10 GB', 'anchor-theme' ); ?>" />
					<button type="button" class="stepper__btn" data-step="1" aria-label="<?php esc_attr_e( 'More extra storage', 'anchor-theme' ); ?>">+</button>
				</div>
				<div class="extra-card__unit"><?php esc_html_e( 'blocks of 10 GB', 'anchor-theme' ); ?></div>
			</div>

			<div class="extra-card">
				<div class="extra-card__label"><?php esc_html_e( 'Extra traffic', 'anchor-theme' ); ?></div>
				<div class="extra-card__rate"><?php echo esc_html( anchor_money( $rates['pageviews'] ) ); ?>/mo <?php esc_html_e( 'per 1M pageviews/yr', 'anchor-theme' ); ?></div>
				<div class="stepper">
					<button type="button" class="stepper__btn" data-step="-1" aria-label="<?php esc_attr_e( 'Less extra traffic', 'anchor-theme' ); ?>">&minus;</button>
					<input class="stepper__value" type="number" inputmode="numeric" min="0" max="50" step="1" value="0" data-extra="views" aria-label="<?php esc_attr_e( 'Extra traffic, blocks of 1M pageviews per year', 'anchor-theme' ); ?>" />
					<button type="button" class="stepper__btn" data-step="1" aria-label="<?php esc_attr_e( 'More extra traffic', 'anchor-theme' ); ?>">+</button>
				</div>
				<div class="extra-card__unit"><?php esc_html_e( 'blocks of 1M pageviews/yr', 'anchor-theme' ); ?></div>
			</div>

		</div>
	</section>

	<section class="builder__step">
		<div class="builder__step-head">
			<span class="builder__step-num" aria-hidden="true">3</span>
			<h2 class="builder__step-title"><?php esc_html_e( 'Your plan breakdown', 'anchor-theme' ); ?></h2>
		</div>
		<p class="builder__step-note"><?php echo esc_html( $copy['summary_note'] ); ?></p>

		<div class="calc builder__summary-card">
			<div class="builder__summary">

				<div>
					<div class="calc__summary-kicker"><?php esc_html_e( 'What you get', 'anchor-theme' ); ?></div>
					<div class="addons builder__capacity">
						<div class="addons__row">
							<span><?php esc_html_e( 'Sites', 'anchor-theme' ); ?></span>
							<span class="addons__price" data-sum-sites><?php echo esc_html( $selected['sites'] ); ?></span>
						</div>
						<div class="addons__row">
							<span><?php esc_html_e( 'Storage', 'anchor-theme' ); ?></span>
							<span class="addons__price" data-sum-storage><?php echo esc_html( $selected['gb'] ); ?> GB</span>
						</div>
						<div class="addons__row">
							<span><?php esc_html_e( 'Traffic', 'anchor-theme' ); ?></span>
							<span class="addons__price" data-sum-views><?php echo esc_html( $format_views( $selected['pv'] ) ); ?> <?php esc_html_e( 'pageviews/yr', 'anchor-theme' ); ?></span>
						</div>
					</div>

					<div class="builder__includes-title"><?php esc_html_e( 'Always included', 'anchor-theme' ); ?></div>
					<div class="includes">
						<?php foreach ( anchor_plan_includes() as $item ) : ?>
							<div class="includes__item">
								<span class="check"><?php echo anchor_icon( 'check', 15, 2.6 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<?php echo esc_html( $item ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="calc__summary">
					<div class="calc__summary-kicker"><?php esc_html_e( 'Your total', 'anchor-theme' ); ?></div>
					<div class="calc__plan">
						<span data-builder-plan><?php echo esc_html( $selected['name'] ); ?></span> <?php esc_html_e( 'plan', 'anchor-theme' ); ?>
					</div>

					<div class="calc__lines" data-builder-lines>
						<div class="calc__line">
							<span><?php echo esc_html( $selected['name'] ); ?> base</span>
							<span><?php echo esc_html( anchor_money( $selected['m'] ) ); ?></span>
						</div>
						<div class="calc__line">
							<span><?php esc_html_e( 'no extras added', 'anchor-theme' ); ?></span>
							<span>$0</span>
						</div>
					</div>

					<div class="calc__total-row">
						<span class="calc__total-label"><?php esc_html_e( 'Total', 'anchor-theme' ); ?></span>
						<span class="calc__total" data-builder-total><?php echo esc_html( anchor_money( $selected['m'] ) ); ?></span>
					</div>

					<div class="calc__cycle-note">
						<span data-builder-period><?php esc_html_e( 'per month', 'anchor-theme' ); ?></span>, <?php esc_html_e( 'billed', 'anchor-theme' ); ?> <span data-builder-cycle-word><?php esc_html_e( 'monthly', 'anchor-theme' ); ?></span>
					</div>

					<div class="builder__persite" data-builder-persite>
						<?php echo esc_html( anchor_money( $selected['m'] / $selected['sites'] ) ); ?>/mo <?php esc_html_e( 'per site', 'anchor-theme' ); ?>
					</div>

					<a class="builder__cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get started', 'anchor-theme' ); ?></a>
				</div>

			</div>
		</div>
	</section>

	<?php if ( $content ) : ?>
		<div class="builder__prose prose"><?php the_content(); ?></div>
	<?php endif; ?>

	<p class="builder__footnote"><?php echo esc_html( $copy['footnote'] ); ?></p>

</div>
