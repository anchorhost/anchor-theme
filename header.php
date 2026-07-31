<?php
/**
 * Site header.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$company      = anchor_company();
$signin_label = is_user_logged_in() ? __( 'Dashboard', 'anchor-theme' ) : __( 'Sign in', 'anchor-theme' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'anchor-theme' ); ?></a>

<div class="site">

	<header class="site-header">
		<div class="site-header__inner">

			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php anchor_brand( 34 ); ?>
			</a>

			<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Primary', 'anchor-theme' ); ?>">
				<?php anchor_primary_nav(); ?>
				<a class="main-nav__signin" href="<?php echo esc_url( $company['account'] ); ?>"><?php echo esc_html( $signin_label . ' →' ); ?></a>
			</nav>

			<div class="header-spacer"></div>

			<div class="header-actions">

				<button type="button" class="icon-btn nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-nav" aria-label="<?php esc_attr_e( 'Menu', 'anchor-theme' ); ?>">
					<?php echo anchor_icon( 'menu', 18, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>

				<button type="button" class="search-trigger" data-palette-open title="<?php esc_attr_e( 'Search (⌘K)', 'anchor-theme' ); ?>">
					<?php echo anchor_icon( 'search', 15, 2.1 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="kbd">⌘K</span>
				</button>

				<button type="button" class="icon-btn" data-theme-toggle title="<?php esc_attr_e( 'Toggle theme', 'anchor-theme' ); ?>" aria-label="<?php esc_attr_e( 'Toggle light or dark theme', 'anchor-theme' ); ?>">
					<?php echo anchor_icon( 'moon', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>

				<a class="header-cta" href="<?php echo esc_url( $company['account'] ); ?>"><?php echo esc_html( $signin_label ); ?></a>

			</div>
		</div>
	</header>

	<?php get_template_part( 'template-parts/palette' ); ?>

	<main class="site-main" id="content">
