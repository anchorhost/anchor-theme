<?php
/**
 * Anchor Theme — bootstrap.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANCHOR_THEME_VERSION', '1.0.0' );
define( 'ANCHOR_THEME_DIR', get_template_directory() );
define( 'ANCHOR_THEME_URI', get_template_directory_uri() );

require_once ANCHOR_THEME_DIR . '/app/Updater.php';
require_once ANCHOR_THEME_DIR . '/inc/setup.php';
require_once ANCHOR_THEME_DIR . '/inc/enqueue.php';
require_once ANCHOR_THEME_DIR . '/inc/content.php';
require_once ANCHOR_THEME_DIR . '/inc/template-tags.php';
require_once ANCHOR_THEME_DIR . '/inc/nav-walker.php';
require_once ANCHOR_THEME_DIR . '/inc/palette.php';
require_once ANCHOR_THEME_DIR . '/inc/blocks.php';

new AnchorTheme\Updater();
