<?php
/**
 * About page layout.
 *
 * Editor content, when present, renders below the intro so the page stays
 * editable without losing the designed hero.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = trim( get_the_content() );
?>
<section class="page-hero">

	<div>
		<div class="eyebrow"><?php esc_html_e( 'About', 'anchor-theme' ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php esc_html_e( 'Anchor Hosting has been run by Austin Ginder out of Lancaster, Pennsylvania since 2014. No support tiers, no offshore first line. The person who answers your email is the person who built the platform.', 'anchor-theme' ); ?></p>
			<p class="page-hero__text"><?php esc_html_e( 'The dashboard is powered by CaptainCore, open source and built in the open. What runs your sites is code you can read.', 'anchor-theme' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="page-hero__art">
		<?php anchor_illustration( 'captain.svg', __( 'Captain', 'anchor-theme' ) ); ?>
	</div>

</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<section class="info-grid">
	<?php foreach ( anchor_about_cards() as $card ) : ?>
		<div class="info-card">
			<h3 class="info-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
			<p class="info-card__text"><?php echo esc_html( $card['body'] ); ?></p>
		</div>
	<?php endforeach; ?>
</section>
