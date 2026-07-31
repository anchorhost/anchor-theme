<?php
/**
 * Security documentation layout — the full defense-in-depth reference.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$docs    = anchor_security_docs();
$content = trim( get_the_content() );
?>
<section class="page-hero">

	<div>
		<div class="eyebrow"><?php esc_html_e( 'Security documentation', 'anchor-theme' ); ?></div>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php else : ?>
			<p class="page-hero__text"><?php esc_html_e( 'How 2,900+ WordPress sites are monitored, hardened, backed up and patched — the actual checks, schedules and tooling, documented.', 'anchor-theme' ); ?></p>
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

<div class="docs">

	<?php foreach ( $docs['sections'] as $section ) : ?>
		<section class="docs-section">
			<div class="eyebrow"><?php echo esc_html( $section['kicker'] ); ?></div>
			<h2 class="docs-section__title"><?php echo esc_html( $section['title'] ); ?></h2>
			<p class="docs-section__lede"><?php echo esc_html( $section['lede'] ); ?></p>

			<div class="docs-grid">
				<?php foreach ( $section['items'] as $item ) : ?>
					<div class="security-card">
						<div class="security-card__tag"><?php echo esc_html( $item['tag'] ); ?></div>
						<h3 class="security-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="security-card__text"><?php echo esc_html( $item['body'] ); ?></p>
						<?php if ( ! empty( $item['points'] ) ) : ?>
							<ul class="security-card__points">
								<?php foreach ( $item['points'] as $point ) : ?>
									<li><?php echo esc_html( $point ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endforeach; ?>

	<?php foreach ( [ $docs['alerts'], $docs['schedule'] ] as $table ) : ?>
		<section class="docs-section">
			<h2 class="docs-section__title"><?php echo esc_html( $table['title'] ); ?></h2>
			<p class="docs-section__lede"><?php echo esc_html( $table['lede'] ); ?></p>

			<div class="docs-table-wrap">
				<table class="docs-table">
					<thead>
						<tr>
							<?php foreach ( $table['head'] as $th ) : ?>
								<th scope="col"><?php echo esc_html( $th ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $table['rows'] as $row ) : ?>
							<tr>
								<?php foreach ( $row as $i => $cell ) : ?>
									<td<?php echo ( 1 === $i ) ? ' class="docs-table__mono"' : ''; ?>><?php echo esc_html( $cell ); ?></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>
	<?php endforeach; ?>

	<p class="docs-updated">
		<?php
		printf(
			/* translators: %s: last modified date. */
			esc_html__( 'WordPress security documentation · last updated %s', 'anchor-theme' ),
			esc_html( get_the_modified_date() )
		);
		?>
	</p>

</div>
