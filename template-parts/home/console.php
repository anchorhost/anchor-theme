<?php
/**
 * Home — "The bridge" dashboard preview console.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs      = anchor_console_tabs();
$tab_keys  = array_keys( $tabs );
$first_tab = reset( $tab_keys );
?>
<section class="wrap section" data-console>

	<div class="section-head">
		<div class="section-head__wide">
			<div class="eyebrow"><?php esc_html_e( 'The bridge', 'anchor-theme' ); ?></div>
			<h2 class="section-title"><?php esc_html_e( 'Every site, every domain, one console', 'anchor-theme' ); ?></h2>
			<p class="section-lede"><?php esc_html_e( 'The same dashboard I use to run the fleet is the one you get. No read-only portal, no support ticket to change a DNS record.', 'anchor-theme' ); ?></p>
		</div>

		<div class="pill-group" role="tablist" aria-label="<?php esc_attr_e( 'Dashboard preview', 'anchor-theme' ); ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<button
					type="button"
					class="pill<?php echo ( $key === $first_tab ) ? ' is-active' : ''; ?>"
					data-console-tab="<?php echo esc_attr( $key ); ?>"
					data-console-url="<?php echo esc_attr( $tab['url'] ); ?>"
					role="tab"
					id="console-tab-<?php echo esc_attr( $key ); ?>"
					aria-controls="console-pane-<?php echo esc_attr( $key ); ?>"
					aria-selected="<?php echo ( $key === $first_tab ) ? 'true' : 'false'; ?>"
				><?php echo esc_html( $tab['label'] ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="console">

		<div class="console__chrome">
			<div class="console__lights" aria-hidden="true"><span></span><span></span><span></span></div>
			<div class="console__url" data-console-url-display><?php echo esc_html( $tabs[ $first_tab ]['url'] ); ?></div>
		</div>

		<!-- Fleet -->
		<div class="console__pane" data-console-pane="fleet" id="console-pane-fleet" role="tabpanel" aria-labelledby="console-tab-fleet">
			<?php $filters = anchor_fleet_filters(); ?>
			<div class="console__filters" aria-hidden="true">
				<span class="fleet-search"><?php echo anchor_icon( 'search', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $filters['search'] ); ?></span>
				<?php foreach ( $filters['chips'] as $chip ) : ?>
					<span class="filter-chip"><?php echo esc_html( $chip ); ?><span class="filter-chip__x">✕</span></span>
				<?php endforeach; ?>
				<span class="filter-chip filter-chip--add"><?php esc_html_e( '+ Filter', 'anchor-theme' ); ?></span>
				<div class="header-spacer"></div>
				<span class="fleet-count"><?php echo esc_html( $filters['count'] ); ?></span>
			</div>

			<div class="fleet__head">
				<span><?php esc_html_e( 'Site', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Environments', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Core', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Visits / wk', 'anchor-theme' ); ?></span>
			</div>

			<?php foreach ( anchor_fleet_rows() as $row ) : ?>
				<div class="fleet__row">
					<div class="fleet__site">
						<span class="fleet__thumb" aria-hidden="true"></span>
						<div style="min-width:0">
							<div class="fleet__name"><?php echo esc_html( $row['site'] ); ?></div>
							<div class="fleet__owner"><?php echo esc_html( $row['owner'] ); ?></div>
						</div>
					</div>
					<div class="fleet__envs">
						<?php foreach ( $row['envs'] as $env ) : ?>
							<span class="fleet__env"><?php echo esc_html( $env ); ?></span>
						<?php endforeach; ?>
					</div>
					<span class="fleet__core"><?php echo esc_html( $row['core'] ); ?></span>
					<span class="fleet__visits"><?php echo esc_html( $row['visits'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Security -->
		<div class="console__pane threats" data-console-pane="security" id="console-pane-security" role="tabpanel" aria-labelledby="console-tab-security" hidden>
			<?php foreach ( anchor_threats() as $threat ) : ?>
				<div class="threat">
					<span class="threat__sev threat__sev--<?php echo esc_attr( $threat['sev'] ); ?>"><?php echo esc_html( $threat['sev'] ); ?></span>
					<div style="min-width:0">
						<div class="threat__name"><?php echo esc_html( $threat['name'] ); ?></div>
						<div class="threat__cve"><?php echo esc_html( $threat['cve'] ); ?></div>
					</div>
					<span class="threat__affected"><?php echo esc_html( $threat['affected'] ); ?></span>
					<span class="threat__status"><?php echo esc_html( $threat['status'] ); ?></span>
				</div>
			<?php endforeach; ?>
			<div class="threats__note"><?php esc_html_e( 'Checksums verified against WordPress.org for every core, plugin and theme file, nightly.', 'anchor-theme' ); ?></div>
		</div>

		<!-- Terminal -->
		<div class="console__pane terminal" data-console-pane="terminal" id="console-pane-terminal" role="tabpanel" aria-labelledby="console-tab-terminal" hidden>
			<?php foreach ( anchor_terminal_lines() as $line ) : ?>
				<?php if ( 'comment' === $line['tone'] ) : ?>
					<div class="terminal__comment"><?php echo esc_html( $line['text'] ); ?></div>
				<?php elseif ( 'prompt' === $line['tone'] ) : ?>
					<div><span class="terminal__ok">anchor</span> <span class="terminal__dim">~</span> $ <?php echo esc_html( $line['text'] ); ?></div>
				<?php elseif ( 'done' === $line['tone'] ) : ?>
					<div><span class="terminal__ok"><?php echo esc_html( $line['text'] ); ?></span> <span class="terminal__dim"><?php echo esc_html( $line['suffix'] ?? '' ); ?></span></div>
				<?php elseif ( 'cursor' === $line['tone'] ) : ?>
					<div><span class="terminal__ok">anchor</span> <span class="terminal__dim">~</span> $ <span class="terminal__cursor" aria-hidden="true"></span></div>
				<?php else : ?>
					<div class="terminal__out"><?php echo esc_html( $line['text'] ); ?></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

	</div>
</section>
