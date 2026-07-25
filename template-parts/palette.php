<?php
/**
 * Command palette shell. Contents are rendered by assets/js/app.js.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="palette" data-palette hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search and commands', 'anchor-theme' ); ?>">
	<div class="palette__panel" data-palette-panel>

		<div class="palette__search">
			<?php echo anchor_icon( 'search', 17, 2.1 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<input
				type="text"
				class="palette__input"
				data-palette-input
				autocomplete="off"
				spellcheck="false"
				placeholder="<?php esc_attr_e( 'Search pages, posts, commands…', 'anchor-theme' ); ?>"
				aria-label="<?php esc_attr_e( 'Search pages, posts and commands', 'anchor-theme' ); ?>"
				aria-controls="palette-results"
			/>
			<span class="palette__esc">esc</span>
		</div>

		<div class="palette__results" id="palette-results" data-palette-results role="listbox"></div>

		<div class="palette__footer">
			<span>↑↓ <?php esc_html_e( 'navigate', 'anchor-theme' ); ?></span>
			<span>↵ <?php esc_html_e( 'open', 'anchor-theme' ); ?></span>
			<span>⌘K <?php esc_html_e( 'toggle', 'anchor-theme' ); ?></span>
			<span>/ <?php esc_html_e( 'search', 'anchor-theme' ); ?></span>
		</div>

	</div>
</div>
