<?php
/**
 * AI Relay page layout.
 *
 * Hero with the three-step explainer → one panel picked by
 * anchor_ai_relay_view() (template-parts/pages/ai-relay/*.php) → FAQ.
 *
 * Signed-out visitors get the gate (sign in or create an account); the
 * intake form only renders for a signed-in user. CaptainCore Manager's
 * REST routes enforce the same thing server side. Card numbers only ever
 * go into Stripe's hosted field.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$relay   = anchor_ai_relay();
$content = trim( get_the_content() );
?>
<section class="page-hero relay-hero">

	<div>
		<div class="eyebrow"><?php echo esc_html( $relay['eyebrow'] ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php echo esc_html( $relay['lede'] ); ?></p>
		<?php endif; ?>

		<div class="relay-price">
			<span class="relay-price__free"><?php esc_html_e( '$0 to try', 'anchor-theme' ); ?></span>
			<span class="relay-price__then">
				<?php
				/* translators: 1: price, 2: term */
				printf( esc_html__( 'then %1$s/%2$s hosting', 'anchor-theme' ), esc_html( anchor_money( $relay['price']['amount'] ) ), esc_html( $relay['price']['term'] ) );
				?>
			</span>
			<span class="relay-price__note"><?php echo esc_html( $relay['price']['note'] ); ?></span>
		</div>

		<a class="btn btn--primary relay-hero__cta" href="#relay-start">
			<?php echo anchor_icon( 'upload', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php esc_html_e( 'Start with your files', 'anchor-theme' ); ?>
		</a>
	</div>

	<ol class="relay-steps">
		<?php foreach ( $relay['steps'] as $i => $step ) : ?>
			<li class="relay-step">
				<span class="relay-step__num mono"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
				<div>
					<div class="relay-step__title"><?php echo esc_html( $step['title'] ); ?></div>
					<p class="relay-step__text"><?php echo esc_html( $step['text'] ); ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>

</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<section class="relay" id="relay-start">
	<?php get_template_part( 'template-parts/pages/ai-relay/' . anchor_ai_relay_view(), null, [ 'relay' => $relay ] ); ?>
</section>

<?php if ( $relay['faq'] ) : ?>
<section class="wrap section section--faq relay-faq">
	<div class="section-head__narrow">
		<div class="eyebrow"><?php esc_html_e( 'The fine print', 'anchor-theme' ); ?></div>
		<h2 class="section-title"><?php esc_html_e( 'Free to try means free to try.', 'anchor-theme' ); ?></h2>
	</div>
	<div class="faq">
		<?php foreach ( $relay['faq'] as $item ) : ?>
			<details class="faq__item">
				<summary class="faq__q"><?php echo esc_html( $item['q'] ); ?></summary>
				<div class="faq__a"><p><?php echo esc_html( $item['a'] ); ?></p></div>
			</details>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>
