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
			<script type="application/json" data-fleet-config><?php echo wp_json_encode( $filters ); // phpcs:ignore WordPress.Security.EscapeOutput ?></script>
			<div class="console__filters">
				<label class="fleet-search">
					<?php echo anchor_icon( 'search', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<input
						type="text"
						data-fleet-filter
						placeholder="<?php echo esc_attr( $filters['search'] ); ?>"
						aria-label="<?php esc_attr_e( 'Filter sites', 'anchor-theme' ); ?>"
					/>
				</label>
				<span class="fleet-chips" data-fleet-chips>
					<?php foreach ( $filters['chips'] as $chip ) : ?>
						<span class="filter-chip"><?php echo esc_html( ucfirst( $chip['facet'] ) . ': ' . $chip['value'] . ( isset( $chip['qual'] ) ? ' · ' . $chip['qual'] : '' ) ); ?><span class="filter-chip__x">✕</span></span>
					<?php endforeach; ?>
					<button type="button" class="filter-chip filter-chip--add" data-fleet-add><?php esc_html_e( '+ Filter', 'anchor-theme' ); ?></button>
				</span>
				<div class="header-spacer"></div>
				<span class="fleet-count" data-fleet-count></span>
			</div>

			<div class="fleet-pins" data-fleet-pins hidden>
				<?php echo anchor_icon( 'pin', 14, 1.8 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="fleet-pins__list" data-fleet-pins-list></span>
			</div>

			<div class="fleet__head">
				<span><?php esc_html_e( 'Site', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Environments', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Core', 'anchor-theme' ); ?></span>
				<span><?php esc_html_e( 'Visits / wk', 'anchor-theme' ); ?></span>
			</div>

			<?php foreach ( anchor_fleet_rows() as $row ) : ?>
				<?php $row_url = ! empty( $row['url'] ) ? $row['url'] : ( 'https://' . $row['site'] ); ?>
				<div
					class="fleet__row"
					data-fleet-site="<?php echo esc_attr( strtolower( $row['site'] . ' ' . $row['owner'] ) ); ?>"
					data-fleet-domain="<?php echo esc_attr( $row['site'] ); ?>"
					data-fleet-url="<?php echo esc_url( $row_url ); ?>"
					data-fleet-theme="<?php echo esc_attr( $row['theme'] ?? '' ); ?>"
					data-fleet-core="<?php echo esc_attr( $row['core'] ); ?>"
					data-fleet-plugins="<?php echo esc_attr( wp_json_encode( $row['plugins'] ?? new stdClass() ) ); ?>"
				>
					<div class="fleet__site">
						<span class="fleet__thumb" aria-hidden="true">
							<?php echo anchor_icon( 'image', 15, 1.8 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php if ( ! empty( $row['screenshot'] ) ) : ?>
								<img src="<?php echo esc_url( $row['screenshot'] ); ?>" alt="" width="34" height="26" loading="lazy" decoding="async" onerror="this.hidden=true" />
							<?php endif; ?>
						</span>
						<div style="min-width:0">
							<a class="fleet__name" href="<?php echo esc_url( $row_url ); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html( $row['site'] ); ?></a>
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
			<div class="fleet__empty" data-fleet-empty hidden><?php esc_html_e( 'No sites match that filter.', 'anchor-theme' ); ?></div>
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

		<!-- Terminal — Activity dock chrome: @ targets, cookbook, run -->
		<div class="console__pane term" data-console-pane="terminal" id="console-pane-terminal" role="tabpanel" aria-labelledby="console-tab-terminal" hidden>
			<script type="application/json" data-term-targets><?php echo wp_json_encode( anchor_terminal_targets() ); // phpcs:ignore WordPress.Security.EscapeOutput ?></script>
			<script type="application/json" data-term-recipes><?php echo wp_json_encode( anchor_terminal_recipes() ); // phpcs:ignore WordPress.Security.EscapeOutput ?></script>
			<div class="term__scroll">
				<div class="term__fill"></div>
				<div class="term__lines" data-term-lines>
					<div class="term__idle" data-term-idle><?php echo esc_html( '$ idle – run Sync or a command from a site to stream output here' ); ?></div>
				</div>
			</div>
			<div class="term__bar">
				<div class="term__pop" data-term-tp hidden>
					<input type="text" data-term-tp-q placeholder="<?php esc_attr_e( 'Search targets…', 'anchor-theme' ); ?>" />
					<div class="term__pop-meta">
						<span data-term-tp-count>0 selected</span>
						<button type="button" class="term__pop-clear" data-term-tp-clear hidden><?php esc_html_e( 'Clear', 'anchor-theme' ); ?></button>
					</div>
					<div class="term__pop-list" data-term-tp-list></div>
				</div>
				<div class="term__pop term__pop--cook" data-term-cook hidden>
					<input type="text" data-term-cook-q placeholder="<?php esc_attr_e( 'Search cookbook…', 'anchor-theme' ); ?>" />
					<div class="term__pop-list" data-term-cook-list></div>
				</div>
				<div class="term__tools">
					<button type="button" class="term__chip" data-term-tp-btn title="<?php esc_attr_e( 'Select target environments', 'anchor-theme' ); ?>">
						<?php echo anchor_icon( 'at', 12, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span data-term-tp-label><?php esc_html_e( 'Select target', 'anchor-theme' ); ?></span>
					</button>
					<button type="button" class="term__chip term__chip--brand" data-term-cook-btn title="<?php esc_attr_e( 'Cookbook recipes', 'anchor-theme' ); ?>"><?php esc_html_e( 'Cookbook', 'anchor-theme' ); ?></button>
					<span class="term__keys"><?php echo esc_html( '⌘⏎ run · ⌃` toggle' ); ?></span>
				</div>
				<div class="term__composer">
					<textarea data-term-input rows="1" spellcheck="false" placeholder="<?php esc_attr_e( 'Run a command across the fleet…', 'anchor-theme' ); ?>"></textarea>
					<button type="button" class="term__run" data-term-run disabled><?php esc_html_e( 'Run', 'anchor-theme' ); ?></button>
				</div>
			</div>
		</div>

	</div>
</section>
