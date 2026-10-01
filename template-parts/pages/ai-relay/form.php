<?php
/**
 * AI Relay, signed in: the intake form.
 *
 * Files upload one at a time as they are dropped (POST /ai-relay/files), so
 * a large batch never has to fit in a single request. Staged files survive a
 * reload. The card goes straight to Stripe's hosted field; only the Stripe
 * source id reaches the server. See assets/js/ai-relay.js.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$relay   = $args['relay'];
$user_id = get_current_user_id();
$cards   = CaptainCore\AiRelay::cards( $user_id );
$missing = captaincore_billing_address_missing( $user_id );
$billing = class_exists( 'WC_Customer' ) ? ( new WC_Customer( $user_id ) )->get_billing() : [];
$limits  = CaptainCore\AiRelay::limits();
$accept  = implode( ',', array_merge( [ 'image/*' ], array_map( function ( $ext ) {
	return '.' . $ext;
}, $limits['extensions'] ) ) );

$countries = function_exists( 'WC' ) ? WC()->countries->get_countries() : [ 'US' => 'United States' ];
$country   = ! empty( $billing['country'] ) ? $billing['country'] : 'US';
?>
<form class="relay-form" novalidate data-relay-form>

	<fieldset class="relay-modes">
		<legend class="screen-reader-text"><?php esc_html_e( 'What are we building?', 'anchor-theme' ); ?></legend>
		<?php foreach ( $relay['modes'] as $key => $mode ) : ?>
			<label class="relay-mode">
				<input type="radio" name="relay_mode" value="<?php echo esc_attr( $key ); ?>" <?php checked( 'new', $key ); ?> />
				<span class="relay-mode__icon"><?php echo anchor_icon( 'port' === $key ? 'globe' : 'sparkle', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span>
					<span class="relay-mode__label"><?php echo esc_html( $mode['label'] ); ?></span>
					<span class="relay-mode__hint"><?php echo esc_html( $mode['hint'] ); ?></span>
				</span>
			</label>
		<?php endforeach; ?>
	</fieldset>

	<label class="field relay-port" data-relay-port hidden>
		<?php esc_html_e( 'Current site address', 'anchor-theme' ); ?>
		<input type="url" name="relay_url" inputmode="url" placeholder="<?php esc_attr_e( 'https://your-current-site.com', 'anchor-theme' ); ?>" />
	</label>

	<label class="relay-drop" data-relay-drop>
		<input type="file" multiple accept="<?php echo esc_attr( $accept ); ?>" data-relay-input />
		<span class="relay-drop__icon"><?php echo anchor_icon( 'upload', 30, 1.7 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<span class="relay-drop__title"><?php echo esc_html( $relay['drop']['title'] ); ?></span>
		<span class="relay-drop__text"><?php echo esc_html( $relay['drop']['text'] ); ?></span>
		<span class="relay-drop__browse"><?php esc_html_e( 'or browse your computer', 'anchor-theme' ); ?></span>
		<span class="relay-drop__types mono"><?php echo esc_html( $relay['drop']['types'] ); ?></span>
	</label>

	<div class="relay-files" data-relay-files hidden>
		<div class="relay-files__head">
			<span data-relay-count></span>
			<span class="mono" data-relay-size></span>
		</div>
		<ul class="relay-files__list" data-relay-list></ul>
	</div>

	<div class="contact__form-row">
		<label class="field">
			<?php esc_html_e( 'Business or site name', 'anchor-theme' ); ?>
			<input type="text" name="relay_site_name" placeholder="<?php esc_attr_e( 'Main Street Bakery', 'anchor-theme' ); ?>" />
		</label>
	</div>

	<label class="field">
		<?php esc_html_e( 'Anything we should know?', 'anchor-theme' ); ?>
		<textarea name="relay_notes" rows="4" placeholder="<?php esc_attr_e( 'We are a family bakery in Lancaster. Want a menu, hours, a catering form and lots of photos of the bread.', 'anchor-theme' ); ?>"></textarea>
	</label>

	<div class="relay-card">
		<div class="relay-card__head">
			<?php echo anchor_icon( 'card', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php echo esc_html( $relay['card']['title'] ); ?></span>
			<span class="relay-card__tag"><?php esc_html_e( 'Not charged to try', 'anchor-theme' ); ?></span>
		</div>
		<p class="relay-card__text"><?php echo esc_html( $relay['card']['text'] ); ?></p>

		<?php if ( $cards ) : ?>
			<?php $card = current( array_filter( $cards, function ( $c ) { return $c['is_default']; } ) ) ?: $cards[0]; ?>
			<div class="relay-card__saved" data-relay-saved>
				<span class="mono"><?php echo esc_html( sprintf( '%s •••• %s', $card['brand'] ? $card['brand'] : __( 'Card', 'anchor-theme' ), $card['last4'] ) ); ?></span>
				<span class="relay-card__exp"><?php echo esc_html( sprintf( /* translators: %s: expiry */ __( 'expires %s', 'anchor-theme' ), $card['expires'] ) ); ?></span>
				<button type="button" class="relay-card__change" data-relay-new-card><?php esc_html_e( 'Use a different card', 'anchor-theme' ); ?></button>
			</div>
		<?php endif; ?>

		<div class="relay-card__new" data-relay-card-new <?php echo $cards ? 'hidden' : ''; ?>>

			<?php if ( $missing ) : ?>
				<div class="relay-billing" data-relay-billing>
					<div class="contact__form-row">
						<label class="field"><?php esc_html_e( 'First name', 'anchor-theme' ); ?><input type="text" name="first_name" autocomplete="given-name" value="<?php echo esc_attr( $billing['first_name'] ?? '' ); ?>" required /></label>
						<label class="field"><?php esc_html_e( 'Last name', 'anchor-theme' ); ?><input type="text" name="last_name" autocomplete="family-name" value="<?php echo esc_attr( $billing['last_name'] ?? '' ); ?>" required /></label>
					</div>
					<label class="field"><?php esc_html_e( 'Street address', 'anchor-theme' ); ?><input type="text" name="address_1" autocomplete="address-line1" value="<?php echo esc_attr( $billing['address_1'] ?? '' ); ?>" required /></label>
					<div class="contact__form-row">
						<label class="field"><?php esc_html_e( 'City', 'anchor-theme' ); ?><input type="text" name="city" autocomplete="address-level2" value="<?php echo esc_attr( $billing['city'] ?? '' ); ?>" required /></label>
						<label class="field"><?php esc_html_e( 'State', 'anchor-theme' ); ?><input type="text" name="state" autocomplete="address-level1" value="<?php echo esc_attr( $billing['state'] ?? '' ); ?>" placeholder="PA" /></label>
						<label class="field"><?php esc_html_e( 'ZIP', 'anchor-theme' ); ?><input type="text" name="postcode" autocomplete="postal-code" value="<?php echo esc_attr( $billing['postcode'] ?? '' ); ?>" required /></label>
					</div>
					<label class="field"><?php esc_html_e( 'Country', 'anchor-theme' ); ?>
						<select name="country" autocomplete="country">
							<?php foreach ( $countries as $code => $label ) : ?>
								<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $country, $code ); ?>><?php echo esc_html( html_entity_decode( $label ) ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			<?php endif; ?>

			<div class="relay-card__element" id="relay-card-element" data-relay-card></div>
		</div>
	</div>

	<div class="relay-submit">
		<button type="submit" class="btn btn--primary relay-submit__btn" data-relay-submit>
			<?php echo anchor_icon( 'sparkle', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php echo esc_html( $relay['submit'] ); ?></span>
		</button>
		<p class="relay-submit__fine"><?php echo esc_html( $relay['fine'] ); ?></p>
	</div>

	<p class="relay-notice" data-relay-notice role="status" aria-live="polite" hidden></p>

</form>

<div class="relay-form relay-form--narrow relay-done" data-relay-done hidden>
	<span class="relay-done__icon"><?php echo anchor_icon( 'check', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<h2 class="relay-panel__title"><?php echo esc_html( $relay['done']['title'] ); ?></h2>
	<p class="relay-panel__text"><?php echo esc_html( $relay['done']['text'] ); ?></p>
	<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/account/ai-relay/' ) ); ?>" data-relay-project-link><?php esc_html_e( 'Open your project', 'anchor-theme' ); ?></a>
</div>
