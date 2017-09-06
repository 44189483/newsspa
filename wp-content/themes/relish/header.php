<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage The_8
 * @since The 8 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php
		do_action('relish_theme_header_meta');
		wp_head();
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo home_url(); ?>/wp-content/themes/relish/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo home_url(); ?>/wp-content/themes/relish/css/bootstrap-clockpicker.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo home_url(); ?>/wp-content/themes/relish/css/bootstrap-datepicker.min.css" />
</head>

<body <?php body_class(); ?>>
<!-- body cont -->
<div class='body-cont'>
	<?php
		echo relish_page_loader();

		echo relish_page_header();
		
		?>
	<div id="main" class="site-main">
