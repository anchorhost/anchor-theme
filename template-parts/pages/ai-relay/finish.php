<?php
/**
 * AI Relay, arriving from the signup email: choose a password.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$relay   = $args['relay'];
$token   = anchor_ai_relay_token();
$pending = CaptainCore\AiRelay::signup_pending( $token );
?>
<form class="relay-form relay-form--narrow" data-relay-finish novalidate>
	<h2 class="relay-panel__title"><?php echo esc_html( $relay['finish']['title'] ); ?></h2>
	<p class="relay-panel__text"><?php echo esc_html( $relay['finish']['text'] ); ?></p>

	<input type="hidden" name="token" value="<?php echo esc_attr( $token ); ?>" />
	<?php // Lets password managers file the new password under the right login. ?>
	<input type="email" name="username" value="<?php echo esc_attr( $pending['email'] ); ?>" autocomplete="username" readonly class="relay-finish__email" />

	<label class="field">
		<?php esc_html_e( 'Password', 'anchor-theme' ); ?>
		<input type="password" name="password" autocomplete="new-password" minlength="10" required />
	</label>

	<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Create account and continue', 'anchor-theme' ); ?></button>
	<p class="relay-notice" data-relay-notice role="status" aria-live="polite" hidden></p>
</form>
