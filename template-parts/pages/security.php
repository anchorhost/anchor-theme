<?php
/**
 * Security page layout.
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
		<div class="eyebrow"><?php esc_html_e( 'Security', 'anchor-theme' ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php esc_html_e( 'Vulnerability feeds, file checksums, uptime probes and nightly backups run across all 2,900+ sites, not just the ones you remembered to check. Along the way I have reported 20+ plugin vulnerabilities to their authors and uncovered three backdoor operations hiding in the WordPress plugin repository.', 'anchor-theme' ); ?></p>
		<?php endif; ?>
	</div>

	<div class="page-hero__art">
		<?php anchor_illustration( 'lighthouse.svg', __( 'Lighthouse', 'anchor-theme' ), 'max-width:300px;height:280px' ); ?>
	</div>

</section>

<?php if ( $content ) : ?>
	<div class="page-simple">
		<div class="prose"><?php the_content(); ?></div>
	</div>
<?php endif; ?>

<section class="security-grid">
	<?php foreach ( anchor_security_cards() as $card ) : ?>
		<div class="security-card">
			<div class="security-card__tag"><?php echo esc_html( $card['tag'] ); ?></div>
			<h3 class="security-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
			<p class="security-card__text"><?php echo esc_html( $card['body'] ); ?></p>
		</div>
	<?php endforeach; ?>
</section>
