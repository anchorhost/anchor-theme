<?php
/**
 * Search form.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="anchor-search-<?php echo esc_attr( wp_unique_id() ); ?>"><?php esc_html_e( 'Search for:', 'anchor-theme' ); ?></label>
	<input
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search…', 'anchor-theme' ); ?>"
	/>
	<button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Search', 'anchor-theme' ); ?></button>
</form>
