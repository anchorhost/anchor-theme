<?php
/**
 * Default page layout — editor content in the prose column.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="page-simple">

	<header class="page-simple__header">
		<h1 class="page-simple__title"><?php the_title(); ?></h1>
	</header>

	<div class="prose">
		<?php
		the_content();

		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'anchor-theme' ),
			'after'  => '</div>',
		] );
		?>
	</div>

</div>
