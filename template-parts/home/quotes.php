<?php
/**
 * Home — customer quotes.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quotes = anchor_quotes();

if ( empty( $quotes ) ) {
	return;
}
?>
<section class="wrap section">
	<div class="quotes__grid">
		<?php foreach ( $quotes as $quote ) : ?>
			<figure class="card quote">
				<div class="quote__mark" aria-hidden="true">&ldquo;</div>
				<blockquote class="quote__text"><?php echo esc_html( $quote['text'] ); ?></blockquote>
				<figcaption class="quote__cite">
					<?php if ( ! empty( $quote['avatar'] ) ) : ?>
						<img class="quote__avatar" src="<?php echo esc_url( $quote['avatar'] ); ?>" alt="" width="36" height="36" loading="lazy" />
					<?php else : ?>
						<span class="quote__avatar" aria-hidden="true"></span>
					<?php endif; ?>
					<span>
						<span class="quote__name">
							<?php if ( ! empty( $quote['url'] ) ) : ?>
								<a href="<?php echo esc_url( $quote['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $quote['name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $quote['name'] ); ?>
							<?php endif; ?>
						</span>
						<span class="quote__role"><?php echo esc_html( $quote['role'] ); ?></span>
					</span>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
