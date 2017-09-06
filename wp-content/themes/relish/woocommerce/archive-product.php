<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
get_header( 'shop' );
$class_container = 'page_content';
$woo_sb_layout = sanitize_html_class( relish_get_option( 'woo_sb_layout' ) );
$woo_sidebar = '';
if ( $woo_sb_layout != 'none' ) {
	ob_start();
	do_action( 'woocommerce_sidebar' );
	$woo_sidebar = ob_get_clean();
	$class_container .= ! empty( $woo_sidebar ) ? ' single_sidebar' : '';
}

$spacings_blog = relish_get_option( "spacings_blog" );
$page_title_container_styles = '';

if(isset($spacings_blog) && !empty($spacings_blog)){
	foreach ($spacings_blog as $key => $value) {
		$page_title_container_styles .= "padding-".$key . ": " . (int) $value . "px;";
	}
}
else{
	$page_title_container_styles .= "padding-top:90px;padding-bottom:40px;";
}							

// First we need to check to see if the plugin is enabled.
if ( class_exists( 'WC_List_Grid' ) ) {
    /**
     * Move the "WooCommerce Grid / List Toggle" plugin's UI element 
     * to a different position above the list of products.
     */
    function example_remove_list_grid_toggle_hooked_function() {
    
        // Make available the variable representing a specific instance of the object.
    	global $WC_List_Grid;
    	
    	// Prefix the function name with a hash identifying the specific instance of the object.
    	remove_action( 'woocommerce_before_shop_loop', spl_object_hash( $WC_List_Grid ) . 'gridlist_toggle_button' , 30 ); // Originally 30.
    }
    // We must carry out the removal as late as possible e.g while hooked into same action, otherwise
    // the removal will be unsuccessful.
    add_action( 'woocommerce_before_shop_loop', 'example_remove_list_grid_toggle_hooked_function' );
    
    // Now that we've removed the function, we need to add it back in again at the preferred position.
    if(!empty($WC_List_Grid)){
    	add_action( 'woocommerce_before_shop_loop', array( $WC_List_Grid, 'gridlist_toggle_button' ), 25 );
    }

}
?>

	<div class="<?php echo esc_attr($class_container); ?>" style='<?php echo esc_attr($page_title_container_styles);?>'>

		<?php
		?>

		<div class="container">
			<?php
				echo ( ! empty( $woo_sidebar ) && $woo_sb_layout != 'none' ) ? "<aside class='sb_" . $woo_sb_layout . "'>" . $woo_sidebar . '</aside>' : '';
			?>
			<main>

				<?php
					/**
					 * woocommerce_before_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					do_action( 'woocommerce_before_main_content' );
				?>

					<?php
					if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

						<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

					<?php endif; ?>

					<?php do_action( 'woocommerce_archive_description' ); ?>

					<?php if ( have_posts() ) : ?>

						<div class="woo_panel">
						<?php
							/**
							 * woocommerce_before_shop_loop hook
							 *
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */

							do_action( 'woocommerce_before_shop_loop' );

						?>
						</div>

						<?php woocommerce_product_loop_start(); ?>

							<?php woocommerce_product_subcategories(); ?>

							<?php while ( have_posts() ) : the_post(); ?>

								<?php wc_get_template_part( 'content', 'product' ); ?>

							<?php endwhile; // end of the loop. ?>

						<?php woocommerce_product_loop_end(); ?>

						<?php
							/**
							 * woocommerce_after_shop_loop hook
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
						?>

					<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

						<?php wc_get_template( 'loop/no-products-found.php' ); ?>

					<?php endif; ?>

				<?php
					/**
					 * woocommerce_after_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );
				?>

			</main>
		</div>


	</div>
<?php get_footer( 'shop' ); ?>
