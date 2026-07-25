<?php
/**
 * Contact page layout.
 *
 * The right column renders the page's editor content when there is any —
 * that is where a real form (Gravity Forms shortcode or a block) belongs.
 * The designed static form is the fallback for an empty page.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$company = anchor_company();
$content = trim( get_the_content() );
?>
<section class="contact">

	<div>
		<h1 class="contact__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="contact__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="contact__lede"><?php esc_html_e( "Tell me how many sites you're carrying and where they live now. I'll come back with a plan, a price, and a migration window, usually same day.", 'anchor-theme' ); ?></p>
		<?php endif; ?>

		<div class="contact__details">

			<div>
				<div class="contact__kicker"><?php esc_html_e( 'Mailing address', 'anchor-theme' ); ?></div>
				<div class="contact__value">
					<?php echo esc_html( $company['name'] ); ?><br />
					<?php echo wp_kses( $company['address'], [ 'br' => [] ] ); ?>
				</div>
			</div>

			<div>
				<div class="contact__kicker"><?php esc_html_e( 'Elsewhere', 'anchor-theme' ); ?></div>
				<div class="contact__links">
					<a href="<?php echo esc_url( $company['github'] ); ?>" rel="noopener"><?php echo esc_html( preg_replace( '#^https?://#', '', untrailingslashit( $company['github'] ) ) ); ?></a>
					<a href="<?php echo esc_url( $company['x'] ); ?>" rel="noopener">@anchorhost</a>
				</div>
			</div>

		</div>
	</div>

	<?php if ( $content ) : ?>

		<div class="contact__form">
			<?php the_content(); ?>
		</div>

	<?php else : ?>

		<form class="contact__form" method="post" action="">

			<div class="contact__form-row">
				<label class="field">
					<?php esc_html_e( 'Name', 'anchor-theme' ); ?>
					<input type="text" name="anchor_name" placeholder="<?php esc_attr_e( 'Austin Ginder', 'anchor-theme' ); ?>" />
				</label>
				<label class="field">
					<?php esc_html_e( 'Email', 'anchor-theme' ); ?>
					<input type="email" name="anchor_email" placeholder="<?php esc_attr_e( 'you@agency.com', 'anchor-theme' ); ?>" />
				</label>
			</div>

			<label class="field">
				<?php esc_html_e( 'How many sites?', 'anchor-theme' ); ?>
				<input type="text" name="anchor_sites" placeholder="<?php esc_attr_e( '18 sites, currently on WP Engine', 'anchor-theme' ); ?>" />
			</label>

			<label class="field">
				<?php esc_html_e( 'Anything else', 'anchor-theme' ); ?>
				<textarea name="anchor_message" rows="4" placeholder="<?php esc_attr_e( 'Timeline, quirks, that one legacy site nobody wants to touch.', 'anchor-theme' ); ?>"></textarea>
			</label>

			<button type="submit" class="contact__submit"><?php esc_html_e( 'Send it over', 'anchor-theme' ); ?></button>

		</form>

	<?php endif; ?>

</section>
