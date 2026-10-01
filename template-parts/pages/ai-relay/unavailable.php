<?php
/**
 * AI Relay without CaptainCore Manager: explainer only, point at contact.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="relay-form relay-form--narrow">
	<h2 class="relay-panel__title"><?php esc_html_e( 'AI Relay is not open here yet.', 'anchor-theme' ); ?></h2>
	<p class="relay-panel__text"><?php esc_html_e( 'Get in touch and we will set up your build by hand.', 'anchor-theme' ); ?></p>
	<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'anchor-theme' ); ?></a>
</div>
