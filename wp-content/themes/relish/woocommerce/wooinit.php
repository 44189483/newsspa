<?php

// Declare Woo Support
add_theme_support( 'woocommerce' );

// Posts per Page
function show_products_per_page() {
	return (int) relish_get_option( 'woo_num_products' );
}

add_filter( 'loop_shop_per_page', 'show_products_per_page', 20 );

// Font Settings
function woocommerce_header_font_filter() {
	$out = '';
	$font_array = relish_get_option( 'header-font' );
	if ( isset( $font_array ) ) {
		$out .= 'div.woocommerce form p.form-row label:not(.checkbox),.woocommerce-tabs form p label,.woocommerce-tabs .tabs li a,
            #comments .comment_container,.woocommerce .order .order-total,#searchform label.screen-reader-text,.widget_shopping_cart_content p,
			.woocommerce .woocommerce-tabs .shop_attributes th{color:' . esc_attr( $font_array['color'] ) . '}';
	}
	echo sprintf("%s", $out);
}

add_action( 'header_font_hook', 'woocommerce_header_font_filter' );

function woocommerce_body_font_filter() {
	$out = '';
	$font_array = relish_get_option( 'body-font' );
	if ( isset( $font_array ) ) {
		$out .= '.woocommerce .toggle_sidebar .switcher{line-height:' . esc_attr( $font_array['line-height'] ) . '}';
	}
	echo sprintf("%s", $out);
}

add_action( 'body_font_hook', 'woocommerce_body_font_filter' );

// disable woocomerece stylesheets
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// declare woocomerece custom theme stylesheets
function wp_enqueue_woocommerce_style() {
	wp_register_style( 'woocommerce', get_template_directory_uri() . '/woocommerce/css/woocommerce.css' );
	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'woocommerce' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_style' );

function wp_enqueue_woocommerce_script() {
	wp_register_script( 'cws_woo', get_template_directory_uri() . '/woocommerce/js/woocommerce.js' );
	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_script( 'cws_woo' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_script' );


// Change the breadcrumb delimiter from '/' to '>'
add_filter( 'woocommerce_breadcrumb_defaults', 'my_change_breadcrumb_delimiter' );
function my_change_breadcrumb_delimiter( $defaults ) {
	$defaults['delimiter'] = ' &raquo; ';
	return $defaults;
}

// Reposition WooCommerce breadcrumb
function woocommerce_remove_breadcrumb() {
	remove_action(
	'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
}
add_action(
	'woocommerce_before_main_content', 'woocommerce_remove_breadcrumb'
);

function woocommerce_custom_breadcrumb() {
	woocommerce_breadcrumb();
}
add_action( 'woo_custom_breadcrumb', 'woocommerce_custom_breadcrumb' );

// Remove Page tile from the Archive
function override_page_title() {
	return false;}
add_filter( 'woocommerce_show_page_title', 'override_page_title' );

// Hook in on activation
add_action( 'init', 'happykids_woocommerce_image_dimensions', 1 );

// Define image sizes
function happykids_woocommerce_image_dimensions() {
	$catalog = array(
		'width' 	=> '270',	// px
		'height'	=> '270',	// px
		'crop'		=> 1,// true
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
}
?>
