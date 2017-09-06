<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 , 1, 10 );
}

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php post_class(); ?>>
	<?php
	ob_start();
		woocommerce_show_product_loop_sale_flash();
	$woo_sale = ob_get_clean();
	$woo_sale = ! empty( $woo_sale ) ? '<div class="sale-wrapper">'.  $woo_sale  . '</div>' : '';

	$img = woocommerce_get_product_thumbnail( 'full' );
	preg_match( '|<img.*src="([^"]+)".*>|',$img,$matches );
	$img_url = isset( $matches[1] ) ? esc_url( $matches[1] ) : '';
	$lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;

	ob_start();
		the_permalink();
	$woo_link = ob_get_clean();

	ob_start();
		if ( $lightbox_en ) {
			echo "<div class='links'>
					<a class='fancy woocommerce-hover-effect' href='$img_url'></a>
				</div>";
		} else {
			echo '<a href="'. esc_url($woo_link). '" class="go_to_post"></a>';

		}
	$woo_lightbox = ob_get_clean();

	if ( ! empty( $img_url ) ) {
		$dims = get_option( 'shop_catalog_image_size' );

		$thumb_obj = cws_thumb( $img_url,$dims, false );
		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
		$thumb_url = $thumb_path_hdpi;

		echo "	<div class='media_part'>";
		
		echo "		<div class='pic'>
						".$woo_sale."
						<img $thumb_url alt='".get_the_title()."'>
						$woo_lightbox
						<div class='hover-effect'></div>
					</div>";
		echo '</div>';
	}

	//do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	echo "<div class='product-meta-info'>";
	echo "<a href='".$woo_link."' class='product-title'>";
	do_action( 'woocommerce_shop_loop_item_title' );
	echo "</a>";

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	echo "<div class='price_button_cont'>";
	
	do_action( 'woocommerce_after_shop_loop_item_title' );
	woocommerce_template_loop_add_to_cart();	
	echo "</div>";
	echo "</div>";
	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	//do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
