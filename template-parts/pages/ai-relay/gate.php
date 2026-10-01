<?php
/**
 * AI Relay, signed out: sign in, or create an account by email.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$relay   = $args['relay'];
$config  = CaptainCore\AiRelay::public_config();
$expired = isset( $_GET['relay_token'] ); // phpcs:ignore WordPress.Security.NonceVerification
?>
<div class="relay-gate">

	<div class="relay-gate__intro">
		<h2 class="relay-panel__title"><?php echo esc_html( $relay['gate']['title'] ); ?></h2>
		<p class="relay-panel__text"><?php echo esc_html( $relay['gate']['text'] ); ?></p>
		<?php if ( $expired ) : ?>
			<p class="relay-notice"><?php esc_html_e( 'That link has expired or was already used. Enter your email again for a new one.', 'anchor-theme' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="relay-gate__card">
		<div class="contact__kicker"><?php echo esc_html( $relay['gate']['signin'] ); ?></div>
		<a class="btn btn--primary relay-gate__signin" href="<?php echo esc_url( wp_login_url( CaptainCore\AiRelay::page_path() ) ); ?>">
			<?php esc_html_e( 'Sign in', 'anchor-theme' ); ?>
		</a>
	</div>

	<form class="relay-gate__card" data-relay-signup novalidate>
		<div class="contact__kicker"><?php echo esc_html( $relay['gate']['create'] ); ?></div>
		<label class="field">
			<?php esc_html_e( 'Name', 'anchor-theme' ); ?>
			<input type="text" name="name" autocomplete="name" placeholder="<?php esc_attr_e( 'Your name', 'anchor-theme' ); ?>" />
		</label>
		<label class="field">
			<?php esc_html_e( 'Email', 'anchor-theme' ); ?>
			<input type="email" name="email" autocomplete="email" required placeholder="<?php esc_attr_e( 'you@example.com', 'anchor-theme' ); ?>" />
		</label>
		<?php if ( $config['turnstileKey'] ) : ?>
			<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( $config['turnstileKey'] ); ?>" data-theme="auto"></div>
		<?php endif; ?>
		<button type="submit" class="btn btn--ghost"><?php esc_html_e( 'Create account', 'anchor-theme' ); ?></button>
		<p class="relay-notice" data-relay-notice role="status" aria-live="polite" hidden></p>
	</form>

</div>
