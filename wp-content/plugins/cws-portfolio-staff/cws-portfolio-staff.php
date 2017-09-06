<?php
/*
Plugin Name: CWS Portfolio-Staff
Description: internal use for CreaWS themes only.
Text Domain: cws_portfolio
Version: 1.0.1
*/
add_action( "init", "register_cws_portfolio_cat" );
add_action( "init", "register_cws_portfolio" );
$theme = wp_get_theme();
//var_dump($theme);
define('CWS_THEME_SLUG', $theme->get( 'TextDomain' ));

function register_cws_portfolio_cat(){

	$portfolio_slug = get_slug('portfolio_slug');
	$portfolio_slug = empty( $portfolio_slug ) ? 'portfolio' : sanitize_title_with_dashes($portfolio_slug);

	register_taxonomy( 'cws_portfolio_cat', 'cws_portfolio', array(
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $portfolio_slug . '_cat' )
		));
}


function get_slug($taxonomy) {
	return call_user_func_array(CWS_THEME_SLUG . '_get_option', array($taxonomy));
}

function register_cws_portfolio (){
	$labels = array(
		'name' => __( 'Portfolio items', 'kiddy' ),
		'singular_name' => __( 'Portfolio item', 'kiddy' ),
		'menu_name' => __( 'Portfolio', 'kiddy' ),
		'add_new' => __( 'Add Portfolio Item', 'kiddy' ),
		'add_new_item' => __( 'Add New Portfolio Item', 'kiddy' ),
		'edit_item' => __('Edit Portfolio Item', 'kiddy' ),
		'new_item' => __( 'New Portfolio Item', 'kiddy' ),
		'view_item' => __( 'View Portfolio Item', 'kiddy' ),
		'search_items' => __( 'Search Portfolio Item', 'kiddy' ),
		'not_found' => __( 'No Portfolio Items found', 'kiddy' ),
		'not_found_in_trash' => __( 'No Portfolio Items found in Trash', 'kiddy' ),
		'parent_item_colon' => '',
		);
	$portfolio_slug = get_slug( 'portfolio_slug' );
	$portfolio_slug = empty( $portfolio_slug ) ? 'portfolio' : sanitize_title_with_dashes($portfolio_slug);
	register_post_type( 'cws_portfolio', array(
		'label' => __( 'Portfolio items', 'kiddy' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $portfolio_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail'
			),
		'menu_position' => 23,
		'menu_icon' => 'dashicons-format-gallery',
		'taxonomies' => array( 'cws_portfolio_cat' ),
		'has_archive' => true
	));
}

function cws_get_portfolio_thumbnail_dims ( $columns = null, $p_id = null, $forcibly_is_single = null/*, $xset = null*/ ,$portfolio_mode = null){

	$image_data = wp_get_attachment_metadata( get_post_thumbnail_id( $p_id ) );
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$sb = call_user_func_array(CWS_THEME_SLUG . '_get_sidebars', array($p_id));
	$sb_layout = $sb['sb_layout_class'];
	$single = isset( $forcibly_is_single ) ? $forcibly_is_single : is_single();
	$width_correction = 0;
	$height_correction = 0;
	$old_version = relish_get_option( 'relish-old-layout' );
	if ($old_version == '1') {
		$width_correction = 22;
		$height_correction = 22;
	}
	$dims = array( 'width' => 0, 'height' => 0, 'crop' => true );

	if ($single){
			if ( $sb_layout == 'single' ){
				$image_data['width']<870 ? $dims['width'] = 0 : $dims['width'] = 870;
			}
			else if ( $sb_layout == 'double' ){
				$image_data['width']<570 ? $dims['width'] = 0 : $dims['width'] = 570;
			}
			else{
				$image_data['width']<1170 ? $dims['width'] = 0 : $dims['width'] = 1170;
			}

	}

	else{
		switch ($columns){
			case "1":
				if ( $sb_layout == 'single' ){
					$dims['width'] = 870;
					/*$dims['height'] =  490;*/
				}
				else if ( $sb_layout == 'double' ){
					$dims['width'] = 570;
					/*$dims['height'] =  321;*/
				}
				else{
					if($portfolio_mode != "circle"){
						$dims['width'] = 1170;
					}
					else{
						$dims['height'] =  370;
						$dims['width'] = 370;
					}
					
					/*$dims['height'] = 659;*/
				}
				break;
			case '2':
				if ( $sb_layout == 'single' ){
					$dims['width'] = 420;
					/*$dims['height'] =  420;*/
				}
				else if ( $sb_layout == 'double' ){
					$dims['width'] = 370;
					$dims['height'] =  370;
				}
				else{
					$dims['width'] = 370;
					$dims['height'] = 370;
				}
				if($portfolio_mode == 'square'){
					$dims['width'] = 570;
					$dims['height'] = 340;	
				}
				break;
			case '3':
				if ( $sb_layout == 'single' ){
					$dims['width'] = 270;
					$dims['height'] =  270;
				}
				else if ( $sb_layout == 'double' ){
					$dims['width'] = 270;
					$dims['height'] =  270;
				}
				else{
					$dims['width'] = 370;
					$dims['height'] = 370;
					/*if ($xset == 2) {
						$dims['height'] = 650;
					}*/
				}
				if($portfolio_mode == 'square'){
					$dims['width'] = 370;
					$dims['height'] = 220;	
				}
				break;
			case '4':
				if($portfolio_mode != "circle"){
					$dims['width'] = 270;
				}
				else{
					$dims['height'] =  370;
					$dims['width'] = 370;
				}
				break;
			default:
				if ( $sb_layout == 'single' ){
					$dims['width'] = 870;
					/*$dims['height'] =  490;*/
				}
				else if ( $sb_layout == 'double' ){
					$dims['width'] = 570;
					/*$dims['height'] =  321;*/
				}
				else{
					if($portfolio_mode != "circle"){
						$dims['width'] = 1170;
					}
					else{
						$dims['height'] =  370;
						$dims['width'] = 370;
					}
					/*$dims['height'] = 659;*/
				}
		}
	}
	$dims['width'] = $dims['width'] != 0 ? $dims['width'] - $width_correction : $dims['width'];
	$dims['height'] = $dims['height'] != 0 ? $dims['height'] - $height_correction : $dims['height'];
	return $dims;
}

function cws_portfolio_get_chars_count ( $cols = null, $p_id = null ){
	$number = 155;
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$sb = call_user_func_array(CWS_THEME_SLUG . '_get_sidebars', array($p_id));
	$sb_layout = isset( $sb['sb_layout_class'] ) ? $sb['sb_layout_class'] : '';
	switch ( $cols ){
		case '1':
			switch ( $sb_layout ){
				case 'double':
					$number = 60;
					break;
				case 'single':
					$number = 70;
					break;
				default:
					$number = 90;
			}
			break;
		case '2':
			switch ( $sb_layout ){
				case 'double':
					$number = 55;
					break;
				case 'single':
					$number = 90;
					break;
				default:
					$number = 130;
			}
			break;
		case '3':
			switch ( $sb_layout ){
				case 'double':
					$number = 60;
					break;
				case 'single':
					$number = 60;
					break;
				default:
					$number = 70;
			}
			break;
		case '4':
			switch ( $sb_layout ){
				case 'double':
					$number = 55;
					break;
				case 'single':
					$number = 55;
					break;
				default:
					$number = 55;
			}
			break;
	}
	return $number;
}

function cws_get_portfolio_cat_slugs (){
	$cat_slugs = array();
	$cat_objects = get_terms( "cws_portfolio_cat" );
	foreach ( $cat_objects as $cat_obj ) {
		$cat_slugs[] = $cat_obj->slug;
	}
	return $cat_slugs;
}

function render_cws_portfolio( $q, $columns = 4, $effectsIhover = null, $effect_ani = null,$portfolio_mode = null,  $p_id = null ){
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$gallery_id = uniqid( 'cws-portfolio-gallery-' );
	$chars_count = cws_portfolio_get_chars_count( $columns, $p_id );
	$portcontent = isset($q->query_vars['portcontent']) ? $q->query_vars['portcontent'] : '';
	while( $q->have_posts() ):

		$q->the_post();
		$pid = get_the_id();
		/*if ($columns == 3) {
			if (!isset($xset)) {
			$xset = 2;
			}

			if ($xset == 1) {
				$xset = 2;
			} else {
				$xset = 1;
			}
			if (!isset($yset)) {
				$yset = 1;			
			} else if (isset($yset)) {
				$yset += 1;
				if ($yset % 5 === 0) {
					$xset = 2;
				}
				if ($yset % 7 === 0) {
					$yset = 1;
					$xset = 1;
				}
			}
		} else {
			$xset = 1;
		}*/
		
		$forcibly_is_single = $p_id == $pid;

		$dims = cws_get_portfolio_thumbnail_dims( $columns, $p_id, $forcibly_is_single/*, $xset*/,$portfolio_mode );

		echo '<article class="item">';
			render_cws_portfolio_item( $pid, $dims, $chars_count, $gallery_id , null , $portcontent,$effectsIhover,$effect_ani, $portfolio_mode);
		echo "</article>";
	endwhile;
	wp_reset_query();
}

function render_cws_portfolio_item( $pid, $dims, $chars_count, $gallery_id = '', $forcibly_is_single = null , $portcontent = 'exerpt', $effectsIhover = null, $effect_ani= null, $portfolio_mode = null){

	$post = get_post( $pid );
	$p_meta = get_post_meta( $pid, 'cws_mb_post' );
	$p_meta = isset( $p_meta[0] ) ? $p_meta[0] : array();
	$single = isset( $forcibly_is_single ) ? $forcibly_is_single : is_single( $pid );
	$title = get_the_title( $pid );
	$permalink = get_the_permalink( $pid );
	$use_blur = relish_get_option('use_blur');
	$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false; 
	$p_category_terms = wp_get_post_terms( $pid, 'cws_portfolio_cat' );
	if($portcontent == 'categories'){
		$p_cats = "";
		for ( $i=0; $i<count( $p_category_terms ); $i++ ){
			$p_category_term = $p_category_terms[$i];
			$p_cat_permalink = get_term_link( $p_category_term->term_id, 'cws_portfolio_cat' );
			$p_cat_name = $p_category_term->name;
			$p_cats .= "<a href='$p_cat_permalink'>$p_cat_name</a>";
			$p_cats .= $i < count( $p_category_terms ) - 1 ? __( ", ", 'kiddy' ) : "";
		}
	}

	if ( has_post_thumbnail( $pid ) ){
		$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id( $pid ) );

		$thumb_obj = cws_thumb($featured_img_url, $dims, false);
		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". $thumb_obj[0] ."' data-at2x='" . $thumb_obj[3]['retina_thumb_url'] ."'" : " src='". $thumb_obj[0] . "' data-no-retina";
		$thumb_url = $thumb_path_hdpi;

		$display_mode = "";
		if($portfolio_mode == 'circle'){
			$display_mode = "ih-item circle media_part ".$effectsIhover." ".$effect_ani."";
		}
		else{
			$display_mode = "square";
		}
		echo "<div class='shortcode portfolio-fancybox ".$display_mode."'>";
			echo "<div class='pic_alt'>";

			if($effectsIhover == "effect8"){
				echo '<div class="img-container">';
			}
			if ( isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] )){
					$custom_url = isset($p_meta['link_options_url']) && !empty( $p_meta['link_options_url'] );
					$fancybox = isset($p_meta['link_options_fancybox']) && !empty( $p_meta['link_options_fancybox'] );
					$url = $custom_url ? $p_meta['link_options_url'] : $featured_img_url;
					$icon = $fancybox ? ( $custom_url ? 'other' : ( !empty( $gallery_id ) ? 'photo2463' : 'magnifying-glass84' ) ) : 'links21'; 
			echo isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "<div class='img_cont img'>" : '';
				if($portfolio_mode != 'circle'){
						echo "<a ".($fancybox ? "class='fancy cwsicon-$icon'" : "class='cwsicon-$icon'")."  href='$url' ".(($fancybox && $custom_url) ? 'data-fancybox-type="iframe"' : '')."  " . ( $fancybox && ( !empty( $gallery_id ) ) ? " data-fancybox-group='$gallery_id'" : "" ) . ">";
				}
				echo "<img $thumb_url alt />";
				echo $use_blur && isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "<img $thumb_url class='blured-img' alt />" : '' ;
				if($portfolio_mode != 'circle'){ echo "</a>"; }

			echo isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "</div>" : "";

				if($effectsIhover == "effect8"){
					echo '</div>';
				}
				

					if($effectsIhover == "effect8"){
						echo '<div class="info-container">';
					}
							echo "<div class='item_content info'>";
								if($effectsIhover == "effect5" || $effectsIhover == "effect1" || $effectsIhover == "effect18" || $effectsIhover == "effect20"){
									echo '<div class="info-back">';
								}
								if ( !$single ) {
									echo "<div class='post_info'>";
										
											echo !empty( $title ) ? "<div class='title'><div class='title_part'>".(!$single ? "<a href='$permalink'>$title</a>" : "$title")."</div></div>" : "";
										
										$content = "";
										
											$content = !empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
											$content = trim( preg_replace( "/[\s]{2,}/", " ", strip_shortcodes( strip_tags( $content ) ) ) );
											$content = wptexturize( $content );
											$content = substr( $content, 0, $chars_count );
											echo !empty( $content ) ? "<div class='desc_part'>$content</div>" : "";
										
									echo "</div>";	
								}
								/*echo "	<div class='links'>
											<a ".($fancybox ? "class='fancy cwsicon-$icon'" : "class='cwsicon-$icon'")."  href='$url' ".(($fancybox && $custom_url) ? 'data-fancybox-type="iframe"' : '')."  " . ( $fancybox && ( !empty( $gallery_id ) ) ? " data-fancybox-group='$gallery_id'" : "" ) . ">

											</a>
										</div>
									";*/
									if($effectsIhover == "effect5" || $effectsIhover == "effect1" || $effectsIhover == "effect18" || $effectsIhover == "effect20"){
											echo '</div>';
								}
							echo "</div>";
					if($effectsIhover == "effect8"){
						echo '</div>';
					}  

				}
			echo "</div>";
		echo "</div>";
		if ( $single){
			echo !empty( $title ) ? "<div class='title'><h3>".(!$single ? "<a href='$permalink'>$title</a>" : "$title")."</h3></div>" : "";
			$content = apply_filters( 'the_content', $post->post_content );
			echo !empty( $content ) ? "<div class='desc_part'>$content</div>" : "";
		}
	}

	

	
}

/* Portfolio ajax */

add_action( "wp_ajax_cws_portfolio_pagination", "cws_portfolio_pagination" );
add_action( "wp_ajax_nopriv_cws_portfolio_pagination", "cws_portfolio_pagination" );

function cws_portfolio_pagination (){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => null,
		'cols' => '4',
		'mode' => 'grid_with_filter',
		'effectsIhover' => '',
		'portfolio_columns' => '4',
		'effect_ani' => '',
		'portfolio_mode' => 'circle',
		'cats' => array(),
		'exclude' => array(),
		'filter' => 'all',
		'portcontent' => 'exerpt',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'url' => '',
		'disable_pagination' => '',
		'pagination_style' => '',
	), $data));

	if ( empty( $url ) ) return;
	$match = preg_match( "#paged?(=|/)(\d+)#", $url, $matches );
	$paged = $match ? $matches[2] : 1;

	$query_args = array(
		'post_type' => 'cws_portfolio',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'portcontent' => $portcontent,
		'posts_per_page' => $posts_per_page,
		'paged' => $paged
	);

	$categories = array();

	if ( $mode == "grid_with_filter" ){
		if ( $filter == "all" ){
			if ( !empty( $cats ) ){
				$categories = $cats;
			}
		}
		else{
			$categories[] = $filter;
		}
	}
	else{
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	if ( !empty( $exclude ) ){
		$query_args["post__not_in"] = $exclude;
	}

	$q = new WP_Query( $query_args );

	echo "<div class='cws_ajax_response'>";
		render_cws_portfolio( $q, $filter_columns,$effectsIhover,$effect_ani,$portfolio_mode, $p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if(!empty($disable_pagination)){
			if ( $max_paged > 1 ){
				call_user_func_array(CWS_THEME_SLUG . '_pagination', array($paged, $max_paged,$pagination_style));
			}			
		}

	echo "</div>";

	die();

}

add_action( "wp_ajax_cws_portfolio_filter", "cws_portfolio_filter" );
add_action( "wp_ajax_nopriv_cws_portfolio_filter", "cws_portfolio_filter" );

function cws_portfolio_filter (){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => null,
		'cols' => '4',
		'portfolio_columns' => '4',
		'cats' => array(),
		'exclude' => array(),
		'filter' => 'all',
		'effectsIhover' => '',
		'effect_ani' => '',
		'portfolio_mode' => 'circle',
		'portcontent' => 'exerpt',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'disable_pagination' => '',
		'pagination_style' => '',
	), $data));
	$query_args = array(
		'post_type' => 'cws_portfolio',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'portcontent' => $portcontent,
		'posts_per_page' => $posts_per_page,
	);

	$categories = array();

	if ( $filter == "all" ){
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}
	else{
		$categories[] = $filter;
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	if ( !empty( $exclude ) ){
		$query_args["post__not_in"] = $exclude;
	}

	$q = new WP_Query( $query_args );

	echo "<div class='cws_ajax_response'>";
		render_cws_portfolio( $q, $portfolio_columns,$effectsIhover,$effect_ani,$portfolio_mode,$p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if(!empty($disable_pagination)){
			if ( $max_paged > 1 ){
				call_user_func_array(CWS_THEME_SLUG . '_pagination', array(1, $max_paged,$pagination_style));
			}			
		}

	echo "</div>";

	die();

}

add_action( "wp_ajax_cws_portfolio_single", "cws_portfolio_single" );
add_action( "wp_ajax_nopriv_cws_portfolio_single", "cws_portfolio_single" );

function cws_portfolio_single(){
	$data = isset( $_POST['data'] ) ? $_POST['data'] : array();
	extract( shortcode_atts( array(
			'initial_id' => '',
			'requested_id' => ''
		), $data));
	if ( empty( $initial_id ) || empty( $requested_id ) ) die();
	$dims = cws_get_portfolio_thumbnail_dims( '1', $requested_id, true,$portfolio_mode );
	$chars_count = cws_portfolio_get_chars_count( '1', $initial_id, true );


	echo "<div class='cws_ajax_response'>";
		echo "<article class='item'>";
			render_cws_portfolio_item( $requested_id, $dims, $chars_count, null, true );
		echo "</article>";
	echo "</div>";
	die();
}

/* \Portfolio ajax */

/* Full width portfolio */

function cws_portfolio_fw_settings_init (){
	$GLOBALS['cws_portfolio_fw_settings'] = array();
	$GLOBALS['cws_portfolio_fw_settings']['screen_width'] = 1920;
	$GLOBALS['cws_portfolio_fw_settings']['height_to_width_ratio'] = 0.78;
}
add_action( 'init', 'cws_portfolio_fw_settings_init' );

function get_cws_portfolio_fw_thumb_dims ( $columns = 1 ){
	$thumb_dims = array();
	global $cws_portfolio_fw_settings;
	$thumb_dims['width'] = $cws_portfolio_fw_settings['screen_width'] / (int)$columns;
	$thumb_dims['height'] = $thumb_dims['width'] * $cws_portfolio_fw_settings['height_to_width_ratio'];
	return $thumb_dims;
}

function render_cws_portfolio_fw( $q, $columns = 4, $p_id = null){
	
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$gallery_id = uniqid( 'cws-portfolio-gallery-' );
	$chars_count = cws_portfolio_get_chars_count( $columns, $p_id );
	$portcontent = isset($q->query_vars['portcontent']) ? $q->query_vars['portcontent'] : '';

	$thumb_dims = get_cws_portfolio_fw_thumb_dims( $columns );

	while ( $q->have_posts() ):
		$q->the_post();
		$pid = get_the_id();
		$multiple = $q->post_count > 1;
		$gallery_id = uniqid( 'cws-gallery-' );
		if ( has_post_thumbnail() ) {
			$out = '';
			ob_start();
			render_cws_portfolio_fw_item ( $pid, $thumb_dims, $multiple, $gallery_id, $portcontent);
			$out .= ob_get_clean();
			echo $out;
		}
	endwhile;
	wp_reset_query();
}

function render_cws_portfolio_fw_item ( $pid, $dims, $multiple = false, $gallery_id = '',$portcontent){
	$hide_title = strpos( $portcontent, 'title' ) !== false ? '1' : '0';
	$hide_meta = strpos( $portcontent, 'categories' ) !== false ? '1' : '0';
	$hide_button = strpos( $portcontent, 'buttons' ) !== false ? '1' : '0';
	
	$p_meta = get_post_meta( $pid, 'cws_mb_post' );
	$p_meta = isset( $p_meta[0] ) ? $p_meta[0] : array();
	$use_blur = relish_get_option('use_blur');
	$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false; 
	$attachment= wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
	$attachment_url = $attachment[0];
	$thumb_obj = cws_thumb($attachment_url, $dims, false);
	$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". $thumb_obj[0] ."' data-at2x='" . $thumb_obj[3]['retina_thumb_url'] ."'" : " src='". $thumb_obj[0] . "' data-no-retina";
	$thumb_url = $thumb_path_hdpi;
	$single = isset( $forcibly_is_single ) ? $forcibly_is_single : is_single( $pid );
	$p_title = get_the_title( $pid );
	$p_permalink = get_permalink( $pid );

	$p_cats = "";
	$p_category_terms = wp_get_post_terms( $pid, 'cws_portfolio_cat' );
	for ( $i=0; $i<count( $p_category_terms ); $i++ ){
		$p_category_term = $p_category_terms[$i];
		$p_cat_permalink = get_term_link( $p_category_term->term_id, 'cws_portfolio_cat' );
		$p_cat_name = $p_category_term->name;
		$p_cats .= "<a href='$p_cat_permalink'>$p_cat_name</a>";
		$p_cats .= $i < count( $p_category_terms ) - 1 ? __( ", ", 'kiddy' ) : "";
	}


	$post_info = "";
	$post_info .= (!empty( $p_title ) && $hide_title !== '1') ? "<div class='title'>".(!$single ? '<a href='.$p_permalink.'>'.$p_title.'</a>' : $p_title)."</div>" : "";
	$post_info .= (!empty( $p_cats ) && $hide_meta !== '1') ? "<div class='cats'>$p_cats</div>" : "";
	$links = "";
	$links .= "<a href='$p_permalink' class='cwsicon-links21'></a>";
	$links .= $multiple ? "<a href='$attachment_url' class='fancy cwsicon-magnifying-glass84' data-fancybox-group='$gallery_id'></a>" : "<a href='$attachment_url' class='fancy cwsicon-magnifying-glass84'></a>";

	if ($hide_button === '1') {
		$links = '';
	}



	if ( has_post_thumbnail( $pid ) ) {
		$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id( $pid ) );

		$thumb_obj = cws_thumb($featured_img_url, $dims, false);
		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". $thumb_obj[0] ."' data-at2x='" . $thumb_obj[3]['retina_thumb_url'] ."'" : " src='". $thumb_obj[0] . "' data-no-retina";
		$thumb_url = $thumb_path_hdpi;
		echo "<div class='item'>";
			echo "<div class='pic_alt'>";
				echo isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "<div class='img_cont'>" : '';
					echo "<img $thumb_url alt />";
					echo $use_blur && isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "<img $thumb_url class='blured-img' alt />" : '' ;
					echo isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] ) ? "</div>" : "";

					if ( isset( $p_meta['enable_hover'] ) && !empty( $p_meta['enable_hover'] )){
						echo "<div class='hover-effect'></div>";
						echo "<div class='item_content'>";

							echo !empty( $post_info ) ? "<div class='post_info_wrapper'>
														<div class='post_info'>$post_info</div>
													</div>" : "";
							echo !empty( $links ) ? "<div class='links'>
														$links
													</div>" : "";
						echo "</div>";
					}
			echo "</div>";
		echo "</div>";
	}

}

/* \Full width portfolio */

/* Portfolio full-width ajax */

function cws_portfolio_fw_filter (){
	$data = isset( $_POST["data"] ) ? $_POST["data"] : array();
	extract( shortcode_atts( array(
		'p_id' => null,
		'cols' => '4',
		'cats' => array(),
		'exclude' => array(),
		'filter' => 'all',
		'portcontent' => 'exerpt',
		'posts_per_page' => get_option( 'posts_per_page' ),
	), $data));

	$query_args = array(
		'post_type' => 'cws_portfolio',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'portcontent' => $portcontent,
		'posts_per_page' => $posts_per_page,
	);

	$categories = array();

	if ( $filter == "all" ){
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}
	else{
		$categories[] = $filter;
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	if ( !empty( $exclude ) ){
		$query_args["post__not_in"] = $exclude;
	}

	$q = new WP_Query( $query_args );	

	echo "<div class='cws_ajax_response'>";
		render_cws_portfolio_fw( $q, $cols, $p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if ( $max_paged > 1 ){
			call_user_func_array(CWS_THEME_SLUG . '_pagination', array(1, $max_paged));
		}
	echo "</div>";

	die();
}

add_action( "wp_ajax_cws_portfolio_fw_filter", "cws_portfolio_fw_filter" );
add_action( "wp_ajax_nopriv_cws_portfolio_fw_filter", "cws_portfolio_fw_filter" );



add_action( "wp_ajax_cws_portfolio_fw_pagination", "cws_portfolio_fw_pagination" );
add_action( "wp_ajax_nopriv_cws_portfolio_pagination", "cws_portfolio_fw_pagination" );

function cws_portfolio_fw_pagination (){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => null,
		'cols' => '4',
		'mode' => 'grid_with_filter',
		'cats' => array(),
		'exclude' => array(),
		'filter' => 'all',
		'portcontent' => 'exerpt',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'url' => ''
	), $data));

	if ( empty( $url ) ) return;
	$match = preg_match( "#paged?(=|/)(\d+)#", $url, $matches );
	$paged = $match ? $matches[2] : 1;

	$query_args = array(
		'post_type' => 'cws_portfolio',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'portcontent' => $portcontent,
		'posts_per_page' => $posts_per_page,
		'paged' => $paged
	);

	$categories = array();

	if ( $mode == "grid_with_filter" ){
		if ( $filter == "all" ){
			if ( !empty( $cats ) ){
				$categories = $cats;
			}
		}
		else{
			$categories[] = $filter;
		}
	}
	else{
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	if ( !empty( $exclude ) ){
		$query_args["post__not_in"] = $exclude;
	}

	$q = new WP_Query( $query_args );


	echo "<div class='cws_ajax_response'>";
		render_cws_portfolio_fw( $q, $cols, $p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if ( $max_paged > 1 ){
			call_user_func_array(CWS_THEME_SLUG . '_pagination', array($paged, $max_paged));
		}
	echo "</div>";

	die();

}



/* \Portfolio full-width ajax */

/* STAFF */
add_action( "init", "register_cws_staff_department" );
add_action( "init", "register_cws_staff_position" );
add_action( "init", "register_cws_staff" );

function register_cws_staff (){
	$labels = array(
		'name' => __( 'Staff members', 'kiddy' ),
		'singular_name' => __( 'Staff member', 'kiddy' ),
		'menu_name' => __( 'Our team', 'kiddy' ),
		'all_items' => __( 'All', 'kiddy' ),
		'add_new' => __( 'Add new', 'kiddy' ),
		'add_new_item' => __( 'Add New Staff Member', 'kiddy' ),
		'edit_item' => __('Edit Staff Member\'s info', 'kiddy' ),
		'new_item' => __( 'New Staff Member', 'kiddy' ),
		'view_item' => __( 'View Staff Member\'s info', 'kiddy' ),
		'search_items' => __( 'Find Staff Member', 'kiddy' ),
		'not_found' => __( 'No Staff Members found', 'kiddy' ),
		'not_found_in_trash' => __( 'No Staff Members found in Trash', 'kiddy' ),
		'parent_item_colon' => '',
		);
	$staff_slug = get_slug( 'staff_slug' );
	$staff_slug = empty( $staff_slug ) ? 'staff' : $staff_slug;
	register_post_type( 'cws_staff', array(
		'label' => __( 'Staff members', 'kiddy' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $staff_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'page-attributes',
			'thumbnail'
			),
		'menu_position' => 24,
		'menu_icon' => 'dashicons-groups',
		'taxonomies' => array( 'cws_staff_member_position' ),
		'has_archive' => true
	));
}

function register_cws_staff_department(){
	$staff_slug = get_slug( 'staff_slug' );
	$staff_slug = empty( $staff_slug ) ? 'staff' : $staff_slug;
	$labels = array(
		'name' => __( 'Departments', 'kiddy' ),
		'singular_name' => __( 'Staff department', 'kiddy' ),
		'all_items' => __( 'All Staff departments', 'kiddy' ),
		'edit_item' => __( 'Edit Staff department', 'kiddy' ),
		'view_item' => __( 'View Staff department', 'kiddy' ),
		'update_item' => __( 'Update Staff department', 'kiddy' ),
		'add_new_item' => __( 'Add Staff department', 'kiddy' ),
		'new_item_name' => __( 'New Staff department name', 'kiddy' ),
		'parent_item' => __( 'Parent Staff department', 'kiddy' ),
		'parent_item_colon' => __( 'Parent Staff department:', 'kiddy' ),
		'search_items' => __( 'Search Staff departments', 'kiddy' ),
		'popular_items' => __( 'Popular Staff departments', 'kiddy' ),
		'separate_items_width_commas' => __( 'Separate with commas', 'kiddy' ),
		'add_or_remove_items' => __( 'Add or Remove Staff departments', 'kiddy' ),
		'choose_from_most_used' => __( 'Choose from the most used Staff departments', 'kiddy' ),
		'not_found' => __( 'No Staff departments found', 'kiddy' )
	);
	register_taxonomy( 'cws_staff_member_department', 'cws_staff', array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $staff_slug . '_cat' )
	));
}

function register_cws_staff_position(){
	$staff_slug = get_slug( 'staff_slug' );
	$staff_slug = empty( $staff_slug ) ? 'staff' : $staff_slug;
	$labels = array(
		'name' => __( 'Positions', 'kiddy' ),
		'singular_name' => __( 'Staff Member position', 'kiddy' ),
		'all_items' => __( 'All Staff Member positions', 'kiddy' ),
		'edit_item' => __( 'Edit Staff Member position', 'kiddy' ),
		'view_item' => __( 'View Staff Member position', 'kiddy' ),
		'update_item' => __( 'Update Staff Member position', 'kiddy' ),
		'add_new_item' => __( 'Add Staff Member position', 'kiddy' ),
		'new_item_name' => __( 'New Staff Member position name', 'kiddy' ),
		'search_items' => __( 'Search Staff Member positions', 'kiddy' ),
		'popular_items' => __( 'Popular Staff Member positions', 'kiddy' ),
		'separate_items_width_commas' => __( 'Separate with commas', 'kiddy' ),
		'add_or_remove_items' => __( 'Add or Remove Staff Member positions', 'kiddy' ),
		'choose_from_most_used' => __( 'Choose from the most used Staff Member positions', 'kiddy' ),
		'not_found' => __( 'No Staff Member positions found', 'kiddy' )
	);
	register_taxonomy( 'cws_staff_member_position', 'cws_staff', array(
		'labels' => $labels,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $staff_slug . '_tag' ),
		'show_tagcloud' => false
	));
}

function add_new_cws_staff_column($cws_staff_columns) {
  $cws_staff_columns['menu_order'] = "Order";
  return $cws_staff_columns;
}
add_action('manage_edit-cws_staff_columns', 'add_new_cws_staff_column');

/**
* show custom order column values
*/
function show_order_column($name){
  global $post;

  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
}
add_action('manage_cws_staff_posts_custom_column','show_order_column');

/**
* make column sortable
*/
function order_column_register_sortable($columns){
	$columns['menu_order'] = 'menu_order';
	return $columns;
}
add_filter('manage_edit-cws_staff_sortable_columns','order_column_register_sortable');


function cws_get_ourteam_thumbnail_dims ( $p_id, $forcibly_is_single = null ){
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$sb = call_user_func_array(CWS_THEME_SLUG . '_get_sidebars', array($p_id));
	$sb_layout = $sb['sb_layout_class'];
	$single = isset( $forcibly_is_single ) ? $forcibly_is_single : is_single();
	$width_correction = 2;
	$height_correction = 2;
	if ($single) {
		$width_correction = 0;
		$height_correction = 0;
	}
	$dims = array( 'width' => 0, 'height' => 0, 'crop' => true );
	if ($single){
/*		if ( $sb_layout == 'single' ){
			$dims['width'] = 870;
		}
		else if ( $sb_layout == 'double' ){
			$dims['width'] = 570;
		}
		else{
			$dims['width'] = 1170;
		}*/
		$dims['width'] = 370;
		$dims['height'] = 370;
	}
	else{
		$dims['width'] = 270;
		$dims['height'] =  270;
	}
	$dims['width'] = $dims['width'] != 0 ? $dims['width'] - $width_correction : $dims['width'];
	$dims['height'] = $dims['height'] != 0 ? $dims['height'] - $height_correction : $dims['height'];
	return $dims;
}

function cws_get_staff_cat_slugs (){
	$cat_slugs = array();
	$cat_objects = get_terms( "cws_staff_member_department" );
	foreach ( $cat_objects as $cat_obj ) {
		$cat_slugs[] = $cat_obj->slug;
	}
	return $cat_slugs;
}

function render_cws_ourteam ( $q, $p_id = null ){
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	while( $q->have_posts() ):
		$q->the_post();
		$pid = get_the_id();
		$forcibly_is_single = $p_id == $pid;
		$dims = cws_get_ourteam_thumbnail_dims( $p_id, $forcibly_is_single );
		echo "<article class='item'>";
			render_cws_ourteam_item( $pid, $dims );
		echo "</article>";
	endwhile;
	wp_reset_query();
}

function render_cws_ourteam_item ( $pid, $dims, $forcibly_is_single = null ){
	$old_version = relish_get_option( 'relish-old-layout' );
	$post = get_post( $pid );
	$p_meta = get_post_meta( $pid, 'cws_mb_post' );
	$p_meta = isset( $p_meta[0] ) ? $p_meta[0] : array();

	$use_blur = relish_get_option('use_blur');
	$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false; 

	$single = isset( $forcibly_is_single ) ? $forcibly_is_single : is_single( $pid );
	$ref_to_single = isset( $p_meta['is_clickable'] ) ? $p_meta['is_clickable'] : false;
	$title = get_the_title( $pid );
	$permalink = get_the_permalink( $pid );
	$post_content = "";
	$departments_array = wp_get_post_terms( $pid, 'cws_staff_member_department' );
	$positions_array = wp_get_post_terms( $pid, 'cws_staff_member_position' );
	$departments_str = "";
	$positions_str = "";
	$social_links = "";

	if ( $single ){
		$post_content =  apply_filters( 'the_content', $post->post_content );
	}
	else{
		//$post_content = !empty( $post->post_excerpt ) ? $post->post_excerpt : "";		

		if(strpos(apply_filters( 'the_content',$post_content ), 'Add Row') !== false){
			$post_content = apply_filters( 'the_content', $post_content );	
		}else{
			$post_content = apply_filters( 'the_content', $post_content );	
		}
		if(!empty( $post->post_excerpt ) && !$single || empty($post_content) && !empty($post->post_excerpt)){
			$post_content = esc_html($post->post_excerpt);		
		}
	}

	for ( $i=0; $i<count( $departments_array ); $i++ ){
		$department = $departments_array[$i];
		$department_id = $department->term_id;
		$department_permalink = get_term_link( $department_id, 'cws_staff_member_department' );
		$department_name = $department->name;
		$departments_str .= "<a href='$department_permalink'>$department_name</a>";
		$departments_str .= $i < count( $departments_array ) - 1 ? __( ', ', 'kiddy' ) : "";
	}
	for ( $i=0; $i<count( $positions_array ); $i++ ){
		$position = $positions_array[$i];
		$position_id = $position->term_id;
		$position_permalink = get_term_link( $position_id, 'cws_staff_member_position' );
		$position_name = $position->name;
		$positions_str .= "<a>$position_name</a>";
		//$positions_str .= "<a href='$position_permalink'>$position_name</a>";
		$positions_str .= $i < count( $positions_array ) - 1 ? __( ', ', 'kiddy' ) : "";
	}

	$socials = isset( $p_meta['social_group'] ) ? $p_meta['social_group'] : array();
	foreach ( $socials as $social ) {
		$soc_title = isset( $social['title'] ) ? $social['title'] : "";
		$soc_icon = isset( $social['icon'] ) ? $social['icon'] : "";
		$soc_url = isset( $social['url'] ) ? $social['url'] : "";
		if ( !empty( $soc_icon ) ){
			$social_links .= "<a href='" . ( !empty( $soc_url ) ? $soc_url : "#" ) . "' class='fa fa-$soc_icon'" . ( !empty( $soc_title ) ? " title='$soc_title'" : "" ) . " target='_blank'></a>";
		}
	}

	echo !$single ? "<div class='ourteam_item_wrapper'>" : "";

		ob_start();
		if ( !empty( $title ) ){
			echo "<h3 class='title'>";
				echo $ref_to_single && !$single ? "<a href='$permalink'>" : "";
					echo $title;
				echo $ref_to_single && !$single ? "</a>" : "";
			echo "</h3>";
		}
		echo (!empty( $positions_str ) && !$old_version && !$single) ?  "<div class='positions'>$positions_str</div>" : "";

		$post_title = ob_get_clean();

		if ( has_post_thumbnail( $pid ) ){

			$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id( $pid ) );

			$thumb_obj = cws_thumb($featured_img_url, $dims, false);
			$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". $thumb_obj[0] ."' data-at2x='" . $thumb_obj[3]['retina_thumb_url'] ."'" : " src='". $thumb_obj[0] . "' data-no-retina";
			$thumb_url = $thumb_path_hdpi;

			echo $single && !empty( $social_links ) ? "<div class='media_part_wrapper'>" : "";
				echo "<div class='media_part'>";
					echo "<div class='pic'>";
					echo !$single ? "<div class='img_cont'>" : '';
							echo "<img $thumb_url alt />";
							echo $use_blur && !$single ? "<img $thumb_url class='blured-img' alt />" : '' ;
					echo !$single ? "</div>" : '';
							if ( !$single ){
								echo "<div class='hover-effect'></div>";
								echo "<div class='ourteam_content'>";
									echo !empty( $post_title ) && !$single ? "<div class='title_wrap'>$post_title</div>" : "";
									echo !$single && !empty( $post_content ) ? "<div class='desc'>$post_content</div>" : "";
									echo !$single && !empty( $social_links ) ? "<div class='social_links'>$social_links</div>" : "";
								echo"</div>";
								echo '<a href="javascript:showAppointment('.$pid.');">Book Her</a>';
								//echo '<a href="' .get_permalink().'">Ask Question</a>';
								/*echo "<div class='links'>";
										echo "<a href='$featured_img_url' class='fancy cwsicon-magnifying-glass84'></a>";
										echo $ref_to_single ? "<a href='$permalink' class='cwsicon-links21'></a>" : "";
								echo "</div>";*/
							}
					echo "</div>";
				echo "</div>";
				echo $single && !empty( $social_links ) ? "<div class='social_links'>$social_links</div>" : "";
			echo $single && !empty( $social_links ) ? "</div>" : "";
		}

		echo !empty( $post_title ) && $single ? "<div class='title_wrap ce_title'>$post_title</div>" : "";

		echo $single && !empty( $post_content ) ? "<div class='post_content'>$post_content</div>" : "";

		/**/
		ob_start();
		if ( !empty( $departments_str ) ){
			echo "<div class='departments'>";
				echo $single ? __( "Departments: ", 'kiddy' ) : "";
				echo $departments_str;
			echo "</div>";
		}
		$terms_list = ob_get_clean();

		ob_start();
		if ( !empty( $positions_str ) ){
			echo "<div class='positions'>";
				echo $single ? __( "Positions: ", 'kiddy' ) : "";
				echo $positions_str;
			echo "</div>";
		}
		$positions_list = ob_get_clean();

		if ( $single ){
			echo !empty( $terms_list ) || !empty( $positions_list ) ? '<div class="pos_term_cont">' : '';
				echo !empty( $terms_list ) ? "<div class='terms'>$terms_list</div>" : "";
				echo !empty( $positions_list ) ? "$positions_list" : "";
			echo !empty( $terms_list ) || !empty( $positions_list ) ? '</div>' : '';
		}
		/**/

	echo !$single ? "</div>" : "";
}

/* Ourteam ajax */

add_action( "wp_ajax_cws_ourteam_pagination", "cws_ourteam_pagination" );
add_action( "wp_ajax_nopriv_cws_ourteam_pagination", "cws_ourteam_pagination" );

function cws_ourteam_pagination (){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => null,
		'mode' => 'grid_with_filter',
		'cats' => array(),
		'filter' => 'all',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'url' => ''
	), $data));

	if ( empty( $url ) ) return;
	$match = preg_match( "#paged?(=|/)(\d+)#", $url, $matches );
	$paged = $match ? $matches[2] : 1;

	$query_args = array(
		'post_type' => 'cws_staff',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged' => $paged
	);

	$categories = array();

	if ( $mode == "grid_with_filter" ){
		if ( $filter == "all" ){
			if ( !empty( $cats ) ){
				$categories = $cats;
			}
		}
		else{
			$categories[] = $filter;
		}
	}
	else{
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_staff_member_department',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	$q = new WP_Query( $query_args );

	echo "<div class='cws_ajax_response'>";
		render_cws_ourteam( $q, $p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if ( $max_paged > 1 ){
			call_user_func_array(CWS_THEME_SLUG . '_pagination', array($paged, $max_paged));
		}
	echo "</div>";
	die();
}

add_action( "wp_ajax_cws_ourteam_filter", "cws_ourteam_filter" );
add_action( "wp_ajax_nopriv_cws_ourteam_filter", "cws_ourteam_filter" );

function cws_ourteam_filter (){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => null,
		'cats' => array(),
		'filter' => 'all',
		'posts_per_page' => get_option( 'posts_per_page' ),
	), $data));

	$query_args = array(
		'post_type' => 'cws_staff',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
	);

	$categories = array();

	if ( $filter == "all" ){
		if ( !empty( $cats ) ){
			$categories = $cats;
		}
	}
	else{
		$categories[] = $filter;
	}

	$tax_query = array();
	if ( !empty( $categories ) ){
		$tax_query[] = array(
			'taxonomy' => 'cws_staff_member_department',
			'field' => 'slug',
			'terms' => $categories
		);
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	$q = new WP_Query( $query_args );

	echo "<div class='cws_ajax_response'>";
		render_cws_ourteam( $q, $p_id );
		$max_paged = ceil( $q->found_posts / $posts_per_page );
		if ( $max_paged > 1 ){
			call_user_func_array(CWS_THEME_SLUG . '_pagination', array(1, $max_paged));
		}
	echo "</div>";

	die();

}



/* \Ourteam ajax */
?>