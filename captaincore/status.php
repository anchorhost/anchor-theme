<?php
/**
 * Status page — the anchor.host look for CaptainCore Manager's public status
 * page. The Manager owns the route (/status/), the data and the email; it
 * renders this file instead of its own template when the theme has one.
 *
 * $GLOBALS['captaincore_status'] comes from CaptainCore\StatusPage::view():
 * view = page | incident | missing | unsubscribe. Every *_html value is
 * already safe HTML from the Manager; everything else is plain text.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$v = $GLOBALS['captaincore_status'];

add_filter(
	'pre_get_document_title',
	function () use ( $v ) {
		$base = $v['name'] . ' status';
		return 'incident' === $v['view'] ? $v['incident']['title'] . ' · ' . $base : $base;
	}
);
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'status-page';
		return $classes;
	}
);
add_action(
	'wp_head',
	function () use ( $v ) {
		printf( '<link rel="alternate" type="application/atom+xml" title="%s" href="%s">' . "\n", esc_attr( $v['name'] . ' status' ), esc_url( $v['feed_url'] ) );
		if ( 'page' !== $v['view'] || $v['page'] > 1 ) {
			echo '<meta name="robots" content="noindex">' . "\n";
		}
	}
);

if ( ! function_exists( 'anchor_status_incident' ) ) {
	/**
	 * One incident card. $full shows every update; otherwise the latest one.
	 */
	function anchor_status_incident( $i, $full = false ) {
		$updates = $full ? $i['updates'] : array_slice( $i['updates'], 0, 1 );
		?>
		<article class="status-incident status-incident--<?php echo esc_attr( $i['impact'] ); ?>">
			<header class="status-incident__head">
				<h3 class="status-incident__title">
					<?php if ( $full ) : ?>
						<?php echo esc_html( $i['title'] ); ?>
					<?php else : ?>
						<a href="<?php echo esc_url( $i['url'] ); ?>"><?php echo esc_html( $i['title'] ); ?></a>
					<?php endif; ?>
				</h3>
				<span class="status-pill status-pill--<?php echo esc_attr( $i['status'] ); ?>"><?php echo esc_html( $i['status_label'] ); ?></span>
			</header>
			<div class="status-incident__meta">
				<?php echo esc_html( $i['impact_label'] ); ?>
				·
				<?php
				if ( 'maintenance' === $i['kind'] && $i['scheduled_for_local'] ) {
					echo esc_html( 'Scheduled ' . $i['scheduled_for_local'] . ( $i['scheduled_until_local'] ? ' until ' . $i['scheduled_until_local'] : '' ) );
				} else {
					echo esc_html( $i['started_local'] );
					if ( $i['duration'] ) {
						echo esc_html( ( $i['resolved_local'] ? ' · lasted ' : ' · ongoing for ' ) . $i['duration'] );
					}
				}
				?>
			</div>

			<?php if ( $i['postmortem_html'] ) : ?>
				<div class="status-postmortem">
					<div class="status-update__label"><?php esc_html_e( 'Postmortem', 'anchor-theme' ); ?> <time><?php echo esc_html( $i['postmortem_local'] ); ?></time></div>
					<div class="status-update__body prose"><?php echo $i['postmortem_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- safe HTML from the Manager. ?></div>
				</div>
			<?php endif; ?>

			<ol class="status-updates">
				<?php foreach ( $updates as $u ) : ?>
					<li class="status-update">
						<div class="status-update__label status-update__label--<?php echo esc_attr( $u['status'] ); ?>"><?php echo esc_html( $u['status_label'] ); ?> <time datetime="<?php echo esc_attr( $u['created_at'] ); ?>"><?php echo esc_html( $u['created_local'] ); ?></time></div>
						<div class="status-update__body prose"><?php echo $u['body_html']; // phpcs:ignore WordPress.Security.EscapeOutput -- safe HTML from the Manager. ?></div>
					</li>
				<?php endforeach; ?>
			</ol>

			<?php if ( ! $full && count( $i['updates'] ) > 1 ) : ?>
				<a class="status-incident__more" href="<?php echo esc_url( $i['url'] ); ?>">
					<?php
					/* translators: %d: number of updates */
					echo esc_html( sprintf( _n( 'See %d update', 'See all %d updates', count( $i['updates'] ), 'anchor-theme' ), count( $i['updates'] ) ) );
					?>
					→</a>
			<?php endif; ?>
		</article>
		<?php
	}
}

get_header();
?>

<section class="page-hero status-hero">
	<div>
		<div class="eyebrow status-eyebrow status-eyebrow--<?php echo esc_attr( $v['status']['indicator'] ?? 'operational' ); ?>"><span class="status-eyebrow__dot" aria-hidden="true"></span><?php esc_html_e( 'System status', 'anchor-theme' ); ?></div>
		<h1 class="page-hero__title">
			<?php
			if ( 'incident' === $v['view'] ) {
				echo esc_html( $v['incident']['title'] );
			} elseif ( 'unsubscribe' === $v['view'] ) {
				esc_html_e( 'Status alerts', 'anchor-theme' );
			} else {
				echo esc_html( $v['status']['description'] );
			}
			?>
		</h1>
		<?php if ( 'page' === $v['view'] ) : ?>
			<p class="page-hero__text">
				<?php
				/* translators: %d: year */
				echo esc_html( sprintf( __( 'Outages, slowdowns and scheduled maintenance affecting sites we host, with every update we posted along the way. History back to %d.', 'anchor-theme' ), $v['since'] ) );
				?>
			</p>
			<p class="page-hero__text status-hero__alerts">
				<?php
				printf(
					/* translators: %s: link to the account profile */
					esc_html__( 'Customers can get these by email: turn on Status alerts in %s.', 'anchor-theme' ),
					'<a href="' . esc_url( home_url( '/account/profile' ) ) . '">' . esc_html__( 'your account profile', 'anchor-theme' ) . '</a>'
				);
				?>
			</p>
		<?php elseif ( 'incident' === $v['view'] ) : ?>
			<p class="page-hero__text"><a href="<?php echo esc_url( $v['url'] ); ?>"><?php esc_html_e( '← All incidents', 'anchor-theme' ); ?></a></p>
		<?php endif; ?>
	</div>
	<div class="page-hero__art">
		<div class="status-beacon status-beacon--<?php echo esc_attr( $v['status']['indicator'] ?? 'operational' ); ?>">
			<?php anchor_illustration( 'lighthouse.svg', __( 'Lighthouse', 'anchor-theme' ), 'max-width:260px;height:240px' ); ?>
		</div>
	</div>
</section>

<div class="status-wrap">

<?php if ( 'page' === $v['view'] ) : ?>

	<?php if ( $v['active'] ) : ?>
		<h2 class="status-heading status-heading--first"><?php esc_html_e( 'Happening now', 'anchor-theme' ); ?></h2>
		<?php
		foreach ( $v['active'] as $i ) {
			anchor_status_incident( $i, true );
		}
		?>
	<?php endif; ?>

	<?php if ( $v['upcoming'] ) : ?>
		<h2 class="status-heading"><?php esc_html_e( 'Scheduled maintenance', 'anchor-theme' ); ?></h2>
		<?php
		foreach ( $v['upcoming'] as $i ) {
			anchor_status_incident( $i, true );
		}
		?>
	<?php endif; ?>

	<h2 class="status-heading"><?php esc_html_e( 'Past incidents', 'anchor-theme' ); ?></h2>
	<?php
	$month = '';
	foreach ( $v['history'] as $i ) {
		$m = wp_date( 'F Y', strtotime( $i['started_at'] ) );
		if ( $m !== $month ) {
			echo '<div class="status-month">' . esc_html( $m ) . '</div>';
			$month = $m;
		}
		anchor_status_incident( $i );
	}
	if ( ! $v['history'] ) {
		echo '<p class="status-empty">' . esc_html__( 'No incidents reported.', 'anchor-theme' ) . '</p>';
	}
	?>

	<?php if ( $v['pages'] > 1 ) : ?>
		<nav class="status-pager" aria-label="<?php esc_attr_e( 'Incident history', 'anchor-theme' ); ?>">
			<span>
				<?php if ( $v['page'] > 1 ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( 2 === $v['page'] ? $v['url'] : $v['url'] . 'page/' . ( $v['page'] - 1 ) . '/' ); ?>"><?php esc_html_e( '← Newer', 'anchor-theme' ); ?></a>
				<?php endif; ?>
			</span>
			<span class="status-pager__count">
				<?php
				/* translators: 1: page number, 2: page count */
				echo esc_html( sprintf( __( 'Page %1$d of %2$d', 'anchor-theme' ), $v['page'], $v['pages'] ) );
				?>
			</span>
			<span>
				<?php if ( $v['page'] < $v['pages'] ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $v['url'] . 'page/' . ( $v['page'] + 1 ) . '/' ); ?>"><?php esc_html_e( 'Older →', 'anchor-theme' ); ?></a>
				<?php endif; ?>
			</span>
		</nav>
	<?php endif; ?>

<?php elseif ( 'incident' === $v['view'] ) : ?>

	<?php anchor_status_incident( $v['incident'], true ); ?>

<?php elseif ( 'unsubscribe' === $v['view'] ) : ?>

	<div class="status-box">
		<?php if ( ! $v['valid'] ) : ?>
			<p><?php esc_html_e( 'This unsubscribe link is not valid. Customers can turn status alerts off in their account profile.', 'anchor-theme' ); ?></p>
		<?php elseif ( $v['active'] ) : ?>
			<p>
				<?php
				/* translators: %s: masked email address */
				printf( esc_html__( 'Stop status alert emails to %s?', 'anchor-theme' ), '<strong>' . esc_html( $v['email'] ) . '</strong>' );
				?>
			</p>
			<form method="post" action="<?php echo esc_url( $v['action'] ); ?>"><button class="btn btn--primary" type="submit"><?php esc_html_e( 'Unsubscribe', 'anchor-theme' ); ?></button></form>
		<?php else : ?>
			<p>
				<?php
				/* translators: %s: masked email address */
				printf( esc_html__( '%s will no longer get status alert emails.', 'anchor-theme' ), '<strong>' . esc_html( $v['email'] ) . '</strong>' );
				?>
			</p>
			<form method="post" action="<?php echo esc_url( $v['action'] ); ?>"><input type="hidden" name="resubscribe" value="1"><button class="btn btn--ghost" type="submit"><?php esc_html_e( 'Subscribe again', 'anchor-theme' ); ?></button></form>
		<?php endif; ?>
	</div>

<?php else : ?>

	<div class="status-box">
		<p>
			<?php esc_html_e( 'That incident could not be found.', 'anchor-theme' ); ?>
			<a href="<?php echo esc_url( $v['url'] ); ?>"><?php esc_html_e( 'See all incidents', 'anchor-theme' ); ?></a>
		</p>
	</div>

<?php endif; ?>

	<p class="status-foot">
		<a href="<?php echo esc_url( $v['feed_url'] ); ?>"><?php esc_html_e( 'Atom feed', 'anchor-theme' ); ?></a>
	</p>
</div>

<?php
get_footer();
