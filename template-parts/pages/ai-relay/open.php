<?php
/**
 * AI Relay, signed in with a build already in progress.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$relay   = $args['relay'];
$project = CaptainCore\AiRelay::open_project( get_current_user_id() );
?>
<div class="relay-form relay-form--narrow relay-done">
	<span class="relay-done__icon"><?php echo anchor_icon( 'check', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<h2 class="relay-panel__title"><?php echo esc_html( $relay['open']['title'] ); ?></h2>
	<p class="relay-panel__text"><?php echo esc_html( $relay['open']['text'] ); ?></p>
	<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/account/ai-relay/' . (int) $project->ai_relay_project_id ) ); ?>"><?php esc_html_e( 'Open your project', 'anchor-theme' ); ?></a>
</div>
