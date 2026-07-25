<?php
/**
 * Front page.
 *
 * @package AnchorTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php get_template_part( 'template-parts/home/hero' ); ?>
<?php get_template_part( 'template-parts/home/statband' ); ?>
<?php get_template_part( 'template-parts/home/arrangement' ); ?>
<?php get_template_part( 'template-parts/home/console' ); ?>
<?php get_template_part( 'template-parts/home/infrastructure' ); ?>
<?php get_template_part( 'template-parts/home/quotes' ); ?>
<?php get_template_part( 'template-parts/home/cta' ); ?>

<?php
get_footer();
