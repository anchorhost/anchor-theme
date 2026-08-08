<?php
/**
 * Site footer.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$company = anchor_company();
?>
	</main><!-- .site-main -->

	<footer class="site-footer">
		<div class="site-footer__inner">

			<div class="footer-brand">
				<a class="footer-brand__row brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php anchor_brand( 30 ); ?>
				</a>
				<p class="footer-brand__address"><?php echo wp_kses( $company['address'], [ 'br' => [] ] ); ?></p>
				<p class="footer-brand__note">
					<?php
					printf(
						/* translators: %s: link to the open source project. */
						esc_html__( 'Made with ❤️ and %s.', 'anchor-theme' ),
						'<a href="https://captaincore.io">' . esc_html__( 'open source', 'anchor-theme' ) . '</a>'
					);
					?>
				</p>
			</div>

			<?php
			foreach ( anchor_footer_columns() as $location => $column ) {
				anchor_footer_column( $location, $column );
			}
			?>

		</div>

	</footer>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
