<?php
load_template( trailingslashit( get_template_directory() ) . '/core/scg.php');
new relish_SCG();

function add_cws_shortcode($name, $callback)  {
	$short = 'shortcode';
	call_user_func('add_' . $short, $name, $callback);
}

function relish_shortcode_dropcap ( $atts, $content ) {
	return !empty( $content ) ? "<span class='dropcap'>$content</span>" : "";
}
add_cws_shortcode( 'cws_sc_dropcap', 'relish_shortcode_dropcap' );

function relish_shortcode_mark ( $atts, $content ) {
	extract( shortcode_atts( array(
		'font_color' => '#fff',
		'bg_color' => RELISH_COLOR
	), $atts));
	$font_color = esc_attr( $font_color );
	$bg_color = esc_attr( $bg_color );
	return !empty( $content ) ? "<mark style='color:$font_color;background-color:$bg_color;'>$content</mark>" : "";
}
add_cws_shortcode( 'cws_sc_mark', 'relish_shortcode_mark' );

function relish_shortcode_carousel ( $atts, $content ) {
	extract( shortcode_atts( array(
		'title' => '',
		'control_color' => '',
		'columns' => '1'
	), $atts));
	$control_color = esc_html($control_color);

	$has_title = !empty( $title );
	$section_class = "cws_sc_carousel cws_widget_carousel";
	$section_class .= $control_color != RELISH_COLOR ? " custom-control-color" : "";
	$section_class .= !$has_title ? " bullets_nav" : "";
	$section_class = esc_attr($section_class);
	$title = esc_html( $title );
	$section_atts = ' data-columns='. (int) $columns;
	$section_atts .= $control_color != RELISH_COLOR ? ' data-customcontrol="'.$control_color.'"' : '';
	$uniqid = uniqid('id-for-ccc-');
	$section_atts .= (!$has_title && $control_color != RELISH_COLOR) ? ' id="'.$uniqid.'"' : '';
	$out = "";

	if (!$has_title && $control_color != RELISH_COLOR) {
		$style_out = '<style type="text/css">';
			$style_out .= '#'.$uniqid.' .owl-pagination .owl-page{
				-webkit-box-shadow: 0px 0px 0px 1px '.$control_color.';
				-moz-box-shadow: 0px 0px 0px 1px '.$control_color.';
				box-shadow: 0px 0px 0px 1px '.$control_color.';
			}';
			$style_out .= '#'.$uniqid.' .owl-pagination .owl-page.active{
				background-color: '.$control_color.';
			}';
		$style_out .= '</style>';

	}

	if ( !empty( $content ) ) {
		wp_enqueue_script ('owl_carousel');
		$out .= "<div class='$section_class'" . ( !empty( $section_atts ) ? $section_atts : "" ) . ">";
			if ( $has_title ) {
				$out .= "<div class='cws_sc_carousel_header clearfix'>";
					$out .= "<div class='carousel_nav_panel'>";
						$out .= "<span class='prev'></span>";
						$out .= "<span class='next'></span>";
					$out .= "</div>";
					$out .= $has_title ? "<div class='ce_title module_title'>$title</div>" : "";
				$out .= "</div>";
			}
			$out .= "<div class='cws_wrapper'>";
				$out .= do_shortcode( $content );
			$out .= "</div>";
			$out .= (!$has_title && $control_color != RELISH_COLOR) ? $style_out : '';
		$out .= "</div>";
	}
	return $out;
}
add_cws_shortcode( 'cws_sc_carousel', 'relish_shortcode_carousel' );

/* PB SHORTCODES */

function relish_shortcode_row ( $sc_atts, $content ) {
	
extract( shortcode_atts( array(
		'flags' => '0',
		'cols' => '1',
		'render' => '',
		'atts' => ''
	), $sc_atts));
	$out = "";
	$row_bg_atts = "";
	$row_atts = "";
	$row_bg_class = "row_bg cws-container";
	$row_class = "grid_row";
	$row_bg_styles = "";
	$row_styles = "";
	$padding_styles = "";
	$row_bg_html = "";
	$row_content = "";
	$has_bg = false;

	$atts_arr = json_decode( $atts, true );

	extract( shortcode_atts( array(
		'margins' => array(),
		'paddings' => array(),
		'section_border' => '',
		'section_border_width' => '',
		'section_border_style' => '',
		'section_border_color' => '',
		'triangle_width' => '',
		'triangle_color' => '',
		'customize_bg' => '0',
		'bg_media_type' => 'none',
		'bg_img' => array(),
		'bg_attach' => '',
		'bg_possition' => '',
		'bg_repeat' => '',
		'is_bg_img_high_dpi' => '0',
		'bg_video_type' => '1',
		'sh_bg_video_source' => array(),
		'yt_bg_video_source' => '',
		'vimeo_bg_video_source' => '',
		'bg_color_type' => 'none',
		'bg_color' => RELISH_COLOR,
		'bg_color_opacity' => '100',
		'bg_pattern' => array(),
		'font_color' => '',
		'animate' => '0',
		'ani_duration' => '2',
		'ani_delay' => '0',
		'ani_offset' => '10',
		'ani_iteration' => '1',
		'ani_effect' => '',
		'use_prlx' => '0',
		'prlx_speed' => '0',
		'eclass' => '',
		'row_style' => 'def',
		'equal_height' => '0',
		'content_position' => ''
	), $atts_arr));

	$margin_styles = '';
	if (! empty( $margins ) ) {
		$margin_styles .= isset( $margins['left'] ) && $margins['left'] != ""  ? 'margin-left: '.esc_attr( $margins['left'] ). 'px;' : '';
		$margin_styles .= isset( $margins['top'] ) && $margins['top'] != "" ? 'margin-top: '.esc_attr( $margins['top'] ). 'px;' : '';
		$margin_styles .= isset( $margins['right'] ) && $margins['right'] != "" ? 'margin-right: '.esc_attr( $margins['right'] ). 'px;' : '';
		$margin_styles .= isset( $margins['bottom'] ) && $margins['bottom'] != "" ? 'margin-bottom: '.esc_attr( $margins['bottom'] ). 'px;' : '';
	}

	if (! empty( $paddings ) ) {
		$padding_styles .= isset($paddings['left']) && $paddings['left'] != "" ? 'padding-left: '.esc_attr( $paddings['left'] ). 'px;' : '';
		$padding_styles .= isset($paddings['top']) && $paddings['top'] != "" ? 'padding-top: '.esc_attr( $paddings['top'] ). 'px;' : '';
		$padding_styles .= isset($paddings['right'])  && $paddings['right'] != "" ? 'padding-right: '.esc_attr( $paddings['right'] ). 'px;' : '';
		$padding_styles .= isset($paddings['bottom'])  && $paddings['bottom'] != ""  ? 'padding-bottom: '.esc_attr( $paddings['bottom'] ). 'px;' : '';
	}
	if(isset($row_style) && !empty($row_style)){
		if ($row_style == 'benefits') {
			$row_class .= ' benefits';
			$row_bg_class .= ' benefits_bg';
		}else if ($row_style == 'fullwidth_item') {
			$row_class .= ' fullwidth_items';
			$row_bg_class .= ' fullwidth_items_bg';
		}else if($row_style == 'fullwidth_item_no_padding'){
			$row_class .= ' fullwidth_items no_paddings';
			$row_bg_class .= ' fullwidth_items_bg';
		}else if($row_style == 'fullwidth_background'){
			$row_class .= ' fullwidth_background';
			$row_bg_class .= ' fullwidth_background_bg';
		}		
	}
	if ($equal_height == '1' || (!empty($content_position) &&  $content_position == 'middle') || (!empty($content_position) &&  $content_position == 'bottom')) {
		$row_class .= ' cws_flex_row';
	}

	if ($equal_height == '1') {
		$row_class .= ' cws_equal_height';
	}
	if (!empty($content_position) &&  $content_position == 'top') {
		$row_class .= ' cws_content_top';
	}

	if (!empty($content_position) &&  $content_position == 'middle') {
		$row_class .= ' cws_content_middle';
	}

	if (!empty($content_position) &&  $content_position == 'bottom') {
		$row_class .= ' cws_content_bottom';
	}

	$row_bg_img = isset( $bg_img['row'] ) ? esc_url( $bg_img['row'] ) : '';
	$row_bg_img_w = isset( $bg_img['w'] ) ?  $bg_img['w'] : '';
	$row_bg_img_h = isset( $bg_img['h'] ) ? $bg_img['h'] : '';
	$img_style = '';

	if ( ! empty( $row_bg_img ) ) {
		$img_src = $row_bg_img;
		$img_style = '';
	} else {
		$img_src = '';
	}

	if ($use_prlx) {
		$img_style .= 'width:'.$row_bg_img_w.'px;';
		$img_style .= 'height:'.$row_bg_img_h.'px;';
	}

	if (!empty($bg_possition)) {
		$img_style .= 'background-position:'.$bg_possition.';';
	}	


	if (!empty($bg_repeat)) {
		if ($bg_repeat == "no-repeat" || $bg_repeat == "cover" || $bg_repeat == "contain") {
			$img_style .= 'background-repeat: no-repeat;';
		}	
		if ($bg_repeat == "no-repeat") {
			$img_style .= 'background-size: inherit;';
		}	
		if ($bg_repeat == "repeat") {
			$img_style .= 'background-repeat: repeat;';
			$img_style .= 'background-size: inherit;';
		}
		if ($bg_repeat === "cover") {
			$img_style .= 'background-size: cover;';
		}
		if ($bg_repeat === "contain") {
			$img_style .= 'background-size: contain;';
		}
	}

	if (!empty($bg_attach) && !$use_prlx) {
		$img_style .= 'background-attachment:'.$bg_attach.';';
		if($bg_attach == 'fixed'){

		}
	}

	$prlx_speed = (int) $prlx_speed;

	if ( ! empty( $img_src ) ) {
		$row_bg_class .= ' cws_wrapper';
		$row_bg_class .= $use_prlx ? ' cws_prlx_section' : '';
		$row_bg_html .= "<div  style='background-image:url(".$img_src.");".(!empty($img_style) ? $img_style : '')."' class='" . ( $use_prlx ? 'cws_prlx_layer' : 'row_bg_img' ).
				( !empty($bg_attach) && $bg_attach == 'fixed' ? ' cws_prlx_fixed' : '' ).
		 "'" . ( ! empty( $prlx_speed ) ? " data-scroll-speed='$prlx_speed'" : '' ) . '></div>';
		$has_bg = true;
	}
    if ($use_prlx) {
		wp_enqueue_script ('cws_parallax');
	}

	$video_src = "";
	if ( $bg_media_type == 'video' ) {
		switch ( $bg_video_type ) {
			case '1':
				$video_src = isset( $sh_bg_video_source['row'] ) ? esc_url( $sh_bg_video_source['row'] ) : '';
				$row_bg_class .= ' cws_self_hosted_video';
				$row_bg_html .= "<video class='self_hosted_video" . ( $use_prlx ? ' cws_prlx_layer' : '' ) . "' src='$video_src' autoplay='autoplay' loop='loop' muted='muted'" . ( ! empty( $prlx_speed ) ? " data-scroll-speed='$prlx_speed'" : '' ) . '></video>';
				break;
			case '2':
                wp_enqueue_script ('cws_YT_bg');
				$video_src =  $yt_bg_video_source;
				$uniqid = uniqid( 'video-' );
				$row_bg_class .= ' cws_Yt_video_bg loading';
				$row_bg_atts .= " data-video-source='$video_src' data-video-id='$uniqid'";
				$row_bg_html .= "<div id='$uniqid'" . ( $use_prlx ? " class='cws_prlx_layer'" : '' ) . ( ! empty( $prlx_speed ) ? " data-scroll-speed='$prlx_speed'" : '' ) . '></div>';
				break;
			case '3':
                wp_enqueue_script ('vimeo');
				wp_enqueue_script ('cws_self&vimeo_bg');
				$video_src = $vimeo_bg_video_source;
				$uniqid = uniqid( 'video-' );
				$row_bg_class .= ' cws_Vimeo_video_bg';
				$row_bg_atts .= " data-video-source='$video_src' data-video-id='$uniqid'";
				$row_bg_html .= "<iframe id='$uniqid'" . ( $use_prlx ? " class='cws_prlx_layer'" : '' ) . ( ! empty( $prlx_speed ) ? " data-scroll-speed='$prlx_speed'" : '' ) . " src='" . $video_src . "?api=1&player_id=$uniqid' frameborder='0'></iframe>";
				break;
		}
		if ( !empty( $video_src ) ) {
			$row_bg_class .= " row_bg_video";
			$has_bg = true;
		}
	}

	if ( ( !empty( $img_src ) || !empty( $video_src ) ) && ( !empty( $bg_pattern ) && isset( $bg_pattern['row'] ) && !empty( $bg_pattern['row'] ) ) ) {
		$bg_pattern_src = esc_attr( $bg_pattern['row'] );
		$layer_styles = "background-image:url($bg_pattern_src);";
		$row_bg_html .= "<div class='row_bg_layer' style='$layer_styles'></div>";
		$has_bg = true;
	}

	if ( in_array( $bg_color_type, array( 'color', 'gradient' ) ) ) {
		$layer_styles = "";
		$bg_color = esc_attr( $bg_color );
		if ( $bg_color_type == 'color' ) {
			$layer_styles .= "background-color:$bg_color;";
		}
		else if ( $bg_color_type == 'gradient' ) {
			$layer_styles .= relish_render_builder_gradient_rules( $atts_arr );
		}
		$layer_styles .= !empty( $bg_color_opacity ) ? "opacity:" . (float)$bg_color_opacity / 100 . ";" : "";
		if ( !empty( $layer_styles ) ) {
			$row_bg_html .= "<div class='row_bg_layer' style='$layer_styles'></div>";
			$has_bg = true;
		}
	}

	$section_border_css = '';
	$triangle_border = '';
	$triangle_border_bottom = '';
	$triangle_css_u = '';
	$triangle_css_d = '';

	if (!empty($section_border) && $section_border != 'none') {
		if ($section_border == 'top-border' || $section_border == 'top-bottom') {
			$section_border_css .= 'border-top: '.$section_border_width.'px '.$section_border_style.' '.$section_border_color.';' ;
		}
		if ($section_border == 'top-bottom' || $section_border == 'top-bottom') {
			$section_border_css .= 'border-bottom: '.$section_border_width.'px '.$section_border_style.' '.$section_border_color.';' ;
		}
		if ($section_border == 'triangle') {
			if(isset($triangle_width) && !empty($triangle_width)){

				$triangle_css_u .= 'border-right-width: '.$triangle_width.'px;' ;
				$triangle_css_u .= 'border-left-width: '.$triangle_width.'px;' ;
				
			}
			
			$triangle_border .= "<div class='triangle_c triangle_u_d tr_u' ".(!empty($triangle_css_u) ? 'style="'.$triangle_css_u.';border-top:'.$triangle_width.'px solid '.(isset($triangle_color) && !empty($triangle_color) ? $triangle_color : "#fff").'"' : "")."></div>";
			
			$triangle_border_bottom .= "<div class='triangle_c triangle_u_d tr_d' ".(!empty($triangle_css_u) ? 'style="'.$triangle_css_u.';border-bottom:'.$triangle_width.'px solid '.(isset($triangle_color) && !empty($triangle_color) ? $triangle_color : "#fff").'"' : "")."></div>";
			
			$row_bg_class .= ' triangle';

		}
		if ($section_border == 'triangle_up') {
			if(isset($triangle_width) && !empty($triangle_width)){

				$triangle_css_u .= 'border-right-width: '.$triangle_width.'px;' ;
				$triangle_css_u .= 'border-left-width: '.$triangle_width.'px;' ;
				
			}

			$triangle_border .= "<div class='triangle_c triangle_u_d tr_u' ".(!empty($triangle_css_u) ? 'style="'.$triangle_css_u.';border-top:'.$triangle_width.'px solid '.(isset($triangle_color) && !empty($triangle_color) ? $triangle_color : "#fff").'"' : "")."></div>";
			$row_bg_class .= ' triangle_up';

		}
		if ($section_border == 'triangle_down') {
			if(isset($triangle_width) && !empty($triangle_width)){

				$triangle_css_u .= 'border-right-width: '.$triangle_width.'px;' ;
				$triangle_css_u .= 'border-left-width: '.$triangle_width.'px;' ;
				
			}
			$triangle_border .= "<div class='triangle_c triangle_u_d tr_d' ".(!empty($triangle_css_u) ? 'style="'.$triangle_css_u.';border-bottom:'.$triangle_width.'px solid '.(isset($triangle_color) && !empty($triangle_color) ? $triangle_color : "#fff").'"' : "")."></div>";
			$row_bg_class .= ' triangle_down';
			

		}
	}

	if ( $has_bg && ($row_style == 'fullwidth_background') ) {
		$font_color = esc_attr( $font_color );
		$row_bg_styles .= $padding_styles;
		$row_bg_styles .= $margin_styles;
		$row_bg_styles .= $section_border_css;
		if(isset($customize_bg) && !empty($customize_bg)){
			$row_bg_styles .= !empty($font_color) ? "color:$font_color;" : "";
		}
		
	}
	else{
		$font_color = esc_attr( $font_color );
		$row_styles .= $padding_styles;
		$row_styles .= $margin_styles;
		if(isset($customize_bg) && !empty($customize_bg)){
			$row_styles .= !empty($font_color) ? "color:$font_color;" : "";
		}
		if ($has_bg && !empty( $row_bg_html )) {
			$row_bg_styles .= $section_border_css;
		}else{
		$row_styles .= $section_border_css;
	}
	}

	$row_class .= (bool) ($flags & 1) ? ' cws_equal_height cws_flex_row' : ' clearfix';
	$row_class .= in_array( $render, array( 'portfolio_fw' ) ) ? ' full_width' : '';
	$row_class .= ! empty( $eclass ) ? ' ' .  trim( $eclass )  : '';
	if (has_filter('cwsfe_row_class')) {
		$row_class = apply_filters("cwsfe_row_class",$row_class);
	}
	$row_bg_class .= $use_prlx ? ' cws_prlx_section' : '';	

	

	$row_bg_class .= ! empty( $eclass ) ? ' ' .  trim( $eclass )  : '';


	$row_atts .= !empty( $row_class ) ? " class='$row_class'" : "";

	if(!empty( $row_styles ) && $row_style != 'benefits'){
		$row_atts .= " style='$row_styles'";
	}


	switch ( $render ) {
		case "portfolio":
			if ( is_plugin_active( 'cws-portfolio-staff/cws-portfolio-staff.php' ) ) {
				wp_enqueue_script ('isotope');
				$row_content .= relish_cws_portfolio( $atts_arr );
			}
			break;
		case "portfolio_fw":
			if ( is_plugin_active( 'cws-portfolio-staff/cws-portfolio-staff.php' ) ) {
				wp_enqueue_script ('isotope');
				$row_content .= relish_cws_portfolio_fw( $atts_arr );
			}
			break;
		case "ourteam":
			if ( is_plugin_active( 'cws-portfolio-staff/cws-portfolio-staff.php' ) ) {
				wp_enqueue_script ('isotope');
				$row_content .= relish_cws_ourteam( $atts_arr  );
			}
			break;
		case "blog":
			break;
		default:
			$row_content .= do_shortcode( $content );
	}

	if ($row_style == 'fullwidth_background' ) {
		$has_bg = true;
	}
	
	$out .= "<div class='grid_row_content'>";
	
	if($row_style == 'benefits'){
		$row_bg_class .= " benefits_cont";
	}
	if($row_style == 'def'){
		$row_bg_class .= " def_cont";
	}
	if(isset($customize_bg) && !empty($customize_bg)){
		$row_bg_class .= " cusomize-bg";
	}else{
		$row_bg_class .= " no-cusomize-bg";
	}

	(!empty($row_styles) && $row_style != 'fullwidth_item' && $render != 'portfolio_fw' && $row_style != 'fullwidth_item_no_padding' ) && !$has_bg ?  $row_bg_class .= ' grid_row_cont' : '' ;
	
	if (has_filter('cwsfe_row_atts')) {
		$row_atts .= apply_filters('cwsfe_row_atts', $atts);
	}

	$row_bg_atts .= !empty( $row_bg_class ) ? " class='$row_bg_class'" : "";
	$row_bg_atts .= !empty( $row_bg_styles ) ? " style='$row_bg_styles'" : "";

	$out .= "<div" . ( !empty( $row_bg_atts ) ? $row_bg_atts  : "" ) . ( !empty( $row_atts ) ? 
		preg_replace(array("/class='.*?'/i","/style='.*?'/i"), '', $row_atts)  : ""). ">";
		$out .= (!empty($has_bg) && !empty( $row_bg_html ) ? $row_bg_html : "");
		$out .= (!empty($triangle_border) ? $triangle_border : "");
		$out .= "<div" . ( !empty( $row_atts ) ? $row_atts : "" ) . ">";
			$out .= $row_content;
		$out .= "</div>";
		$out .= (!empty($triangle_border_bottom) ? $triangle_border_bottom : "");
	$out .= "</div>";


	$out .= "</div>";
	return $out;
	
}
add_cws_shortcode( 'cws-row', "relish_shortcode_row" );

function relish_shortcode_col ( $sc_atts, $content ) {
	extract( shortcode_atts( array(
		'flags' => '0',
		'span' => '12',
		'atts' => '',
		'_pcol' => '',
	), $sc_atts));

	$atts_arr = !empty($atts) ? json_decode( $atts, true ) : array();
	extract( shortcode_atts( array(
		'customize' => '0',
		'margins' => array(),
		'paddings' => array(),
		'customize_bg' => '0',
		'bg_media_type' => 'none',
		'bg_img' => array(),
		'bg_attach' => '',
		'bg_possition' => '',
		'bg_repeat' => '',
		'is_bg_img_high_dpi' => '0',
		'bg_color_type' => 'none',
		'bg_color' => RELISH_COLOR,
		'bg_color_opacity' => '100',
		'bg_pattern' => array(),
		'font_color' => '',
		'animate' => '0',
		'ani_duration' => '',
		'ani_delay' => '',
		'ani_offset' => '',
		'ani_iteration' => ''
		), $atts_arr));

	$padding_styles = '';
	$margin_styles = '';

	$has_bg = false;
	$row_bg_html = '';
	$row_bg_styles = '';
	$row_styles = '';
	$row_bg_atts = '';
	$row_bg_class = 'widget_cont';
	$col_bg_styles = '';
	$row_bg_img = isset( $bg_img['row'] ) ? esc_url( $bg_img['row'] ) : '';
	$row_bg_img_w = isset( $bg_img['w'] ) ? esc_url( $bg_img['w'] ) : '';

	foreach ($margins as $k => $v) {
	    $margin_styles .= 'margin-'.$k.':'.($v).'px;';
	}

	foreach ($paddings as $k => $v) {
	    $padding_styles .= 'padding-'.$k.':'.($v).'px;';
	}


	if ( ! empty( $row_bg_img ) ) {
		if ( $is_bg_img_high_dpi && ! empty( $row_bg_img_w ) && is_numeric( $row_bg_img_w ) ) {
			$thumb_obj = cws_thumb( $row_bg_img,array( 'width' => floor( (float) $row_bg_img_w / 2 ), 'crop' => true ), false );
			$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ?  esc_url( $thumb_obj[0] )  :  esc_url( $thumb_obj[0] ) ;
			$img_src = $thumb_path_hdpi;
		} else {
			$img_src = $row_bg_img;
		}
	} else {
		$img_src = '';
	}

	if (!empty($bg_possition)) {
		$col_bg_styles .= 'background-position:'.$bg_possition.';';
	}

	if (!empty($bg_repeat)) {
		if ($bg_repeat == 'no-repeat' || $bg_repeat == 'cover' || $bg_repeat == 'contain') {
			$col_bg_styles .= 'background-repeat: no-repeat;';
		}
		if ($bg_repeat == "no-repeat") {
			$col_bg_styles .= 'background-size: inherit;';
		}	
		if ($bg_repeat == "repeat") {
			$col_bg_styles .= 'background-repeat: repeat;';
			$col_bg_styles .= 'background-size: inherit;';
		}
		if ($bg_repeat == 'cover') {
			$col_bg_styles .= 'background-size: cover;';
		}
		if ($bg_repeat == 'contain') {
			$col_bg_styles .= 'background-size: contain;';
		}
	}

	if (!empty($bg_attach)) {
		$col_bg_styles .= 'background-attachment:'.$bg_attach.';';
	}

	if ( ! empty( $img_src ) ) {
		$row_bg_html .= "<div class='row_bg_img_wrapper' style='background-image:url(".$img_src.");".(!empty($col_bg_styles) ? $col_bg_styles : '')."'></div>";
		$has_bg = true;
	}


	if ( ( !empty( $img_src ) ) && ( !empty( $bg_pattern ) && isset( $bg_pattern['row'] ) && !empty( $bg_pattern['row'] ) ) ) {
		$bg_pattern_src = esc_attr( $bg_pattern['row'] );
		$layer_styles = "background-image:url($bg_pattern_src);";
		$row_bg_html .= "<div class='row_bg_layer' style='$layer_styles'></div>";
		$has_bg = true;
	}

	if ( in_array( $bg_color_type, array( 'color', 'gradient' ) ) ) {
		$layer_styles = "";
		$bg_color = esc_attr( $bg_color );
		if ( $bg_color_type == 'color' ) {
			$layer_styles .= "background-color:$bg_color;";
		}
		else if ( $bg_color_type == 'gradient' ) {
			$layer_styles .= relish_render_builder_gradient_rules( $atts_arr );
		}
		$layer_styles .= !empty( $bg_color_opacity ) ? "opacity:" . (float)$bg_color_opacity / 100 . ";" : "";
		if ( !empty( $layer_styles ) ) {
			$row_bg_html .= "<div class='row_bg_layer' style='$layer_styles'></div>";
			$has_bg = true;
		}
	}


		$font_color = esc_attr( $font_color );
		$row_styles .= $padding_styles;
		$row_styles .= $margin_styles;
		$row_styles .= !empty($font_color) ? "color:$font_color;" : '';


	$row_bg_atts .= !empty( $row_bg_class ) ? " class='".esc_attr($row_bg_class)."'" : "";
	$row_bg_atts .= !empty( $row_bg_styles ) ? " style='$row_bg_styles'" : "";


	$out = "";
	$section_atts = "";
	$section_class = "grid_col grid_col_$span";

	$section_class .= (bool)($flags & 1) ? ' pricing_table_column' : '';
	$section_class .= (bool)($flags & 2) ? ' active_table_column' : '';

	$section_class .= $has_bg ? ' section-has-bg' : '';

	if (has_filter('cwsfe_col_class')) {
		$section_class = apply_filters( 'cwsfe_col_class',$section_class);
	}
	$section_atts .= (isset($customize) && $customize == '1' && !empty($color)) ? " style='border-color:".($color).";'" : '';
	$section_atts .= ! empty( $section_class ) ? " class='$section_class'" : '';
	
	$out .= '<div' . ( ! empty( $section_atts ) ? $section_atts : '' ) . '>';
		$out .= '<div class="cols_wrapper"'.(!empty($row_styles) ? " style='".$row_styles."'" : "").'>';
			$out .= $has_bg && !empty( $row_bg_html ) ? $row_bg_html : "";
			$out .= '<div class="widget_wrapper"'.(!empty($color) ? 'style="border-color:'.$color.';"' : '').'>';
				$out .= do_shortcode( $content );
			$out .= '</div>';
		$out .= '</div>';
	$out .= '</div>';

	return $out;
}
add_cws_shortcode( 'col', 'relish_shortcode_col' );

function relish_shortcode_icol ( $sc_atts, $content ) {
	extract( shortcode_atts( array(
		'span' => '12',
		'atts' => '',
	), $sc_atts));
	$atts_arr = !empty($atts) ? json_decode( $atts, true ) : array();
	$out = "";
	$row_styles = "";
	$section_atts = "";
	$section_class = "igrid_section grid_col_$span";
	if (has_filter('cwsfe_col_class')) {
		$section_class = apply_filters("cwsfe_col_class",$section_class);
	}
	$section_atts .= (isset($customize) && $customize == '1' && !empty($color)) ? " style='border-color:".($color).";'" : '';
	$section_atts .= ! empty( $section_class ) ? " class='$section_class'" : '';
	$out .= '<div' . ( ! empty( $section_atts ) ? $section_atts : '' ) . '>';
		$out .= '<div class="icols_wrapper"'.(!empty($row_styles) ? " style='".$row_styles."'" : "").'>';
			$out .= '<div class="igrid_content"'.(!empty($color) ? 'style="border-color:'.$color.';"' : '').'>';
				$out .= do_shortcode( $content );
			$out .= '</div>';
		$out .= '</div>';
	$out .= '</div>';
	return $out;
}
add_cws_shortcode( 'icol', 'relish_shortcode_icol' );

function relish_shortcode_widget ( $sc_atts, $content ) {
	extract( shortcode_atts( array(
		'type' => 'text',
		'atts' => ''
	), $sc_atts));

	$GLOBALS['widget_type'] = $type;

	$has_bg = false;
	$atts = str_replace(array("\r\n","\r","\n", '%22;', '%5B;', '%5D;'), array('\r\n','\r\n','\r\n', '\"', '[', ']'), $atts);

	$atts_arr = json_decode( $atts, true );

	extract( shortcode_atts( array(
		'title' => '',
		'margins' => array(),
		'border' => array(),
		'paddings' => array(),
		'border_style' => 'solid',
		'border_color' => '',
		'eclass' => '',
		'centertitle' => '0',
		'animate' => '0',
		'ani_effect' => '',
		'ani_duration' => '',
		'ani_delay' => '',
		'ani_offset' => '',
		'ani_iteration' => '',
		'customize_bg' => '0',
		'bg_media_type' => 'none',
		'bg_img' => array(),
		'bg_attach' => '',
		'bg_possition' => '',
		'bg_repeat' => '',
		'is_bg_img_high_dpi' => '0',
		'bg_color_type' => 'none',
		'bg_color' => RELISH_COLOR,
		'bg_color_opacity' => '100',
		'bg_pattern' => array(),
		'font_color' => '',
	), $atts_arr));

	$section_atts = "";

	$section_class = "ce clearfix";

	if(isset($atts_arr['display_inline']) && !empty($atts_arr['display_inline'])){
		$section_class .= " inline";
	}

	if (has_filter('cwsfe_widget_class')) {
		$section_class = apply_filters('cwsfe_widget_class', $section_class); // !!!
	}
	$section_atts .= !empty( $section_class ) ? " class='" . trim( $section_class ) . "'" : "";

	if (has_filter('cwsfe_widget_atts')) {
		$section_atts .= apply_filters('cwsfe_widget_atts', $type, $content, $atts);
	}


	/*-----------------------------------------------------------------------------------*/
	/* Widget Text
	/*-----------------------------------------------------------------------------------*/
	$el_atts_style = '';

	$spacing = cws_spacing_styles($atts_arr);
	if (!empty($spacing['paddings'])) {
		$el_atts_style .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_style .= $spacing['margins'];
	}
	$e_class = '';
	if(isset($atts_arr['eclass']) && !empty($atts_arr['eclass'])){
		$e_class = esc_attr($atts_arr['eclass']);
	}
	$img_src = '';

	if(!empty($atts_arr['customize_bg'])){
		if(!empty($atts_arr['bg_media_type'])){
			if(!empty($atts_arr['bg_img']['row'])){
				$row_bg_img = isset( $atts_arr['bg_img']['row'] ) ? esc_url( $bg_img['row'] ) : '';

				if ( ! empty( $row_bg_img ) ) {
					$img_src = $row_bg_img;						
				} 

				if (!empty($atts_arr['bg_possition'])) {
					$el_atts_style .= 'background-position:'.$atts_arr['bg_possition'].';';
				}

				if (!empty($atts_arr['bg_repeat'])) {
					if ($atts_arr['bg_repeat'] == 'no-repeat' || $atts_arr['bg_repeat'] == 'cover' || $atts_arr['bg_repeat'] == 'contain') {
						$el_atts_style .= 'background-repeat: no-repeat;';
					}
					if ($atts_arr['bg_repeat'] == "no-repeat") {
						$el_atts_style .= 'background-size: inherit;';
					}	
					if ($atts_arr['bg_repeat'] == "repeat") {
						$el_atts_style .= 'background-repeat: repeat;';
						$el_atts_style .= 'background-size: inherit;';
					}
					if ($atts_arr['bg_repeat'] == 'cover') {
						$el_atts_style .= 'background-size: cover;';
					}
					if ($atts_arr['bg_repeat'] == 'contain') {
						$el_atts_style .= 'background-size: contain;';
					}
				}

				if (!empty($atts_arr['bg_attach'])) {
					$el_atts_style .= 'background-attachment:'.$atts_arr['bg_attach'].';';
				}

				if ( ! empty( $img_src ) ) {
					$el_atts_style .= 'background-image:url("'.$img_src.'");"';

				}

			}
		}
	}

	$row_bg_html = '';
	if ( ( !empty( $img_src ) ) && isset( $atts_arr['bg_pattern']['row']) && !empty( $atts_arr['bg_pattern']['row'] ) ) {
		$bg_pattern_src = esc_attr( $atts_arr['bg_pattern']['row'] );
		$layer_styles = "background-image:url($bg_pattern_src);";
		$row_bg_html .= "<div class='row_bg_layer txt_layer' style='$layer_styles'></div>";
	}

	if ( isset($atts_arr['bg_color_type']) && !empty($atts_arr['bg_color_type'])) {
		$layer_styles = "";
		$bg_color = esc_attr( $atts_arr['bg_color'] );
		if ( $atts_arr['bg_color_type'] == 'color' ) {
			$layer_styles .= "background-color:$bg_color;";
		}
		else if ( $atts_arr['bg_color_type'] == 'gradient' ) {
			$layer_styles .= relish_render_builder_gradient_rules( $atts_arr['bg_color_type'] );
		}
		$layer_styles .= !empty( $atts_arr['bg_color_opacity'] ) ? "opacity:" . (float)$atts_arr['bg_color_opacity'] / 100 . ";" : "";
		if ( !empty( $layer_styles ) ) {
			$row_bg_html .= "<div class='row_bg_layer txt_layer' style='$layer_styles'></div>";
		}
	}
	if(isset($font_color) && !empty($font_color)){
		$font_color = esc_attr( $font_color );
	}
	
	$el_atts_style .= !empty($font_color) ? "color:$font_color;" : '';

	$out = "";
	if ( $type == 'tcol' ) {
		
	}
	else{
		if ( in_array( $type, array( 'callout', 'tweet' ) ) ) {
			switch ( $type ) {
				case 'callout':
					$out .= relish_callout_renderer( $atts_arr, $content );
					break;
				case 'tweet':
					$out .= relish_twitter_renderer( $atts_arr, $content );
					break;
			}
		}
		else{
			$t_out = '';
			switch ( $type ) {
				case 'text':
					if(!empty($content)){
						$out .= "<div class='cws_wrapper_container".(!empty($e_class) ? ' '.$e_class : '')."' ".(!empty($el_atts_style) ? "style='".$el_atts_style."'" : '').">";
							$out .= (!empty($row_bg_html) ? $row_bg_html : "");
							$out .= do_shortcode( $content );						
						$out .= '</div>';						
					}

					break;
				case 'igrid':
					$out .= relish_igrid_renderer( $atts_arr, $content );
					break;
				case 'accs':
					$out .= relish_accs_renderer( $atts_arr, $content );
					break;
				case 'banners':
					$out .= relish_shortcode_special_offers( $atts_arr);
					break;
				case 'tabs':
					$out .= relish_tabs_renderer( $atts_arr, $content );
					break;
				case 'button':
					$out .= relish_button( $atts_arr );
					break;
				case 'carousel':
					$out .= relish_carousel( $atts_arr, $content);
					break;
				case 'pricing':
					$out .= relish_pricing( $atts_arr);
					break;
				case 'testimonials':
					$out .= relish_testimonials( $atts_arr );
					break;
				case 'services':
					$out .= relish_services( $atts_arr );
					break;
				case 'portfolio':
					$out .= relish_portfolio( $atts_arr );
					break;
				case 'flaticon':
					$out .= relish_flaticon( $atts_arr );
					break;
				case 'our_team':
					$out .= relish_team( $atts_arr );
					break;
				case 'products_gallery':
					$out .= relish_products_gallery( $atts_arr );
					break;
				case 'pricing_lists':
					$out .= relish_pricing_list( $atts_arr );
					break;
				case 'msg_box':
					$out .= relish_msgbox( $atts_arr );
					break;
				case 'progress':
					$out .= relish_progress( $atts_arr );
					break;
				case 'embed':
					$out .= relish_embed( $atts_arr );
					break;
				case 'milestone':
					$out .= relish_milestone( $atts_arr );
					break;
				case 'blog':
					$out .= relish_shortcode_blog( $atts_arr );
					break;
				case 'divider':
					$out .= relish_shortcode_divider($atts_arr);
					break;
				case 'twitter':
					$out .= relish_twitter( $atts_arr, $content );
					break;
			}
		}

		if(has_filter('cwsfe_widget_atts')){
			$cwsfe_atts = apply_filters('cwsfe_widget_atts', $type, $content, $atts);
		}
		
		$out = !empty( $out ) ? "<div" . ( !empty( $section_atts ) ? $section_atts : "" ) . (!empty($cwsfe_atts) ? $cwsfe_atts : "") . ">$out</div>" : "";

	}	

	return $out;
}
add_cws_shortcode( 'cws-widget', 'relish_shortcode_widget' );

/* WIDGET RENDERERS */


function cws_pb_item($args) {
	extract( shortcode_atts( array(
		'type' => '',
		'atts' => array(),
	), $args));	
	
	return relish_pb_item($type, $atts);
}

add_cws_shortcode( 'pb_item', 'cws_pb_item' );


function relish_pb_item($type, $atts) {
	$out = '';
	$content_styles = '';
	$border = '';
	$icon_style = '';
	$styles = '';

	$icon_style = isset($atts['bgcolor']) ? ' style="box-shadow: 0px 0px 0px 1px ' . esc_attr($atts['bgcolor']) .'"' : '';

	if ( !empty( $atts['iconimg'] )) {
		$thumb_obj = '';
		$thumb_path_hdpi = '';
		if(!empty($atts['iconimg']['row'])){
			$thumb_obj = cws_thumb( $atts['iconimg']['row'],array( 'width' => 30, 'height' => 30, 'crop' => true ), false );
		}
		if(!empty($thumb_obj[3])){
			$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
		}
		if(!empty($thumb_path_hdpi)){
			$img_src = $thumb_path_hdpi;
			$out .= '<img ' . $img_src . " class='icon' />";			
		}
	} else if ( !empty( $atts['iconfa'] )) {
		$out .= '<i class=" ' . esc_attr($atts['iconfa']) . '"'. $icon_style .'></i>';
		if ( !empty( $atts['iconfa_active'] )) {
			$out .= "<i class='" . esc_attr($atts['iconfa_active']) . " active'></i>";
		}
	} else {
		$out .= '<i class="icon"'.$icon_style.'></i>';
	}
	
	$out .= !empty( $atts['title'] ) ? '<span'.$title_text_style.'>' . $atts['title'] . '</span>' : '';

	if($type == 'accs_one'){
		
		$renderers = '';
		$accordion_atts = '';
		if(!empty($atts)){

			$renderers = json_decode($atts);

			$out = '';
			if ( !empty( $renderers->iconimg )) {
				$thumb_obj = cws_thumb( $renderers->iconimg->row,array( 'width' => 30, 'height' => 30, 'crop' => true ), false );
				$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				$img_src = $thumb_path_hdpi;
				$out .= '<img ' . $img_src . " class='icon' />";
			} else if ( !empty( $renderers->iconfa )) {
				$icon_style = '';	
				$icon_border ="";
				$border_less = isset($renderers->borderless) && !empty($renderers->borderless) ? "border-less " : "";
				$out .= '<i class="'.(!empty($border_less) ? $border_less : "") . $renderers->iconfa . '"'. $icon_style .' style="'.$icon_border.'"></i>';
				if ( !empty( $renderers->iconfa_active )) {
					$out .= "<i class='".(!empty($border_less) ? $border_less : "") . $renderers->iconfa_active . " active'></i>";
				}
				else{

				}
			} else {
				$icon_style = '';	
				$icon_border ="";
				$out .= '<i class="icon"'.$icon_style.' style="'.$icon_border.'"></i>';
			}

			$out .= !empty( $renderers->title ) ? '<span>' .$renderers->title . '</span>' : '';
		}
	}

	if($type == 'tabs_one'){
		$renderers = '';
		$accordion_atts = '';
		if(!empty($atts)){
			$renderers = json_decode($atts);

			$out = '';
			$img_result = '';
			$width_img = "";
			$height_img = "";
			$width_img = isset($renderers->width_img) && !empty($renderers->width_img) ? (int) $renderers->width_img : 30;
			$height_img = isset($renderers->height_img) && !empty($renderers->height_img) ? (int) $renderers->height_img : 30;

			if (!empty( $renderers->iconimg)){
				$thumb_obj = cws_thumb( $renderers->iconimg->row,array( 'width' => $width_img, 'height' => $height_img, 'crop' => true ), false );$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";$img_src = $thumb_path_hdpi;$img_result = '<img ' . $img_src . " class='icon' />";
			} 
			$out .= 
				(!empty($renderers->customize) ? (isset( $renderers->iconfa) && !empty($renderers->iconfa) ? "<span class='".$renderers->iconfa. " fa-". (!empty($renderers->size) ? $renderers->size : "2x")."'></span>" : "") : "").(!empty($renderers->customize) ? (!empty($img_result) ? "<span class='img-tabs'>".$img_result."</span>" : "") : "").

				(isset( $renderers->title) && !empty( $renderers->title ) ? '<span>' . $renderers->title . '</span>' : "");

		}
	}
	return $out . '|||'.$content_styles. '|||'. $border;
}


function relish_render_accordion_item($atts){
	$out = '';
	$icon_style = '';	
	$icon_border ="";
	if (!empty($atts->textcolor)) {
		$icon_border .= 'box-shadow: 0px 0px 0px 1px '.$atts->textcolor.';';
	}
	if ( !empty( $atts->iconimg )) {
		$thumb_obj = cws_thumb( $atts->iconimg->row,array( 'width' => 24, 'height' => 24, 'crop' => true ), false );
		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
		$img_src = $thumb_path_hdpi;
		$out .= '<img ' . $img_src . " class='icon' />";
	} else if ( !empty( $atts->iconfa )) {

		$out .= '<i class="' . $atts->iconfa . '"'. $icon_style .' style="'.$icon_border.'"></i>';
		if ( !empty( $atts->iconfa_active )) {
			$out .= "<i class='" . $atts->iconfa_active . " active'></i>";
		}
		else{

		}
	} else {
		$out .= '<i class="icon"'.$icon_style.' style="'.$icon_border.'"></i>';
	}


	$content_styles = '';
	$styles = cws_build_styles((array) $atts);	

	if (!empty($atts->border_radius)) {
		$content_styles .= 'border-radius:'.$atts->border_radius . 'px;';
	}

	if (!empty($atts->bgopacity)) {
		$opacity = $atts->bgopacity;
		$opacity = $opacity > 100 ? $opacity % 100 : $opacity;
		$opacity /= 100;
		
		$content_styles .= 'opacity:' . $opacity . ';';
	}


	$content_styles .= $styles['content'];
	if (!empty($atts->textcolor)) {
		$content_styles .= 'color:'.$atts->textcolor . ';';
	}
	$border = '';
	if (!empty($atts->bgcolor) && $atts->bgtype === 'bgcolor') {
		$border .= 'background-color:'.$atts->bgcolor;
	}
	$out .= !empty( $atts->title ) ? '<span>' . $atts->title . '</span>' : '';
	return $out . '|||'.$content_styles . '|||'. $border;
}

add_cws_shortcode( 'cws-widget-t', 'relish_shortcode_widget_t' );

function relish_shortcode_widget_t ( $atts, $content ) {
	return relish_shortcode_widget($atts, $content);
}

function relish_item_shortcode ( $args, $content ) {
	extract( shortcode_atts( array(
		'title' => '',
		'open' => '0',
		'atts' => '',
	), $args));
	$accordion_atts = '';
	if(isset($args['atts']) && !empty($args['atts'])){
		$accordion_atts = json_decode($args['atts']);
	}

	$type = $GLOBALS['widget_type'];
	$out = '';
	$content_styles = '';
	$border = '';
	$item_render = '';
	$accs_atts = '';


	if ( isset( $GLOBALS['cws_tabs_currently_rendered'] ) && is_array( $GLOBALS['cws_tabs_currently_rendered'] ) ) {		
		$tab_item = $args;	
		$tab_item['content'] = $content;	
		$tab_item['ti'] = $accordion_atts;		
		$tab_item['tabindex'] = count( $GLOBALS['cws_tabs_currently_rendered'] );
		array_push( $GLOBALS['cws_tabs_currently_rendered'], $tab_item );			
	}
	if(isset($accordion_atts) && !empty($accordion_atts)){
		$item_render = explode('|||', relish_render_accordion_item($accordion_atts));
		$content_styles .= !empty($item_render[1]) ? $item_render[1] : '';			
		$border .= !empty($item_render[2]) ? $item_render[2] : '';

		if(isset($args['atts']) && !empty($args['atts'])){
			if (has_filter('cwsfe_row_atts')) {
				$accs_atts = apply_filters('cwsfe_row_atts', $args['atts']);
			}
		}
	}


	$out .= "<div class='accordion_section" . ( isset($accordion_atts->active) && !empty($accordion_atts->active) ? " active" : "" )."'" .(!empty($accs_atts) ? $accs_atts : '') .">";

	$out .= "<div class='accordion_title' style='".$content_styles."'>";

		$out .= !empty($item_render) ? $item_render[0] : "Accordion";
	$out .= "</div>";
	$out .= !empty( $content ) ? "<div class='accordion_content'" . ( !empty($open) ? "" : " style='display:none;'" ) . ">". do_shortcode($content) ."</div>" : "";
	$out .= "</div>";

	return $out;
}
add_cws_shortcode( 'item', 'relish_item_shortcode' );

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/* CWS Background Styles
/*-----------------------------------------------------------------------------------*/
function cws_build_styles($atts) {
	$title = '';
	$content = '';
	$border = '';

	if (isset($atts['bgtype']) || isset($atts['bgcolor'])) {
		$type = isset($atts['bgtype']) ? $atts['bgtype'] : 'bgcolor';
		switch ($type) {
			case 'gradient':
				if (isset($atts['gradient'])) {
					$atts['gradient'] = (array)$atts['gradient'];
					$content .= sprintf('background:linear-gradient(%sdeg,%s 0%%,%s 100%%);', $atts['gradient']['orientation'], $atts['gradient']['s_color'], $atts['gradient']['e_color']);
					$border .= sprintf('border-color:linear-gradient(%sdeg, %s 0%%,%s 100%%);', $atts['gradient']['orientation'], $atts['gradient']['s_color'], $atts['gradient']['e_color']);
					$title = 'color:' . $atts['gradient']['s_color'] .';';
				}
				break;
			case 'bgcolor':
				$color = $atts['bgcolor'];
				if (isset($atts['opacity']) ) {
					if (strlen($color) > 4) {
						list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
					} else {
						list($r, $g, $b) = array_map(create_function('$el', 'return $el * 17;'), sscanf($color, "#%01x%01x%01x"));
					}
					$color = sprintf('rgba(%s, %s, %s, %s)', $r, $g, $b, $atts['opacity']/100);
				}
				if (!empty($color)) {
					$content = 'background-color:'.$color.';';
					$border = 'border-color:'.$color.';';
					$title = 'color:'.$color.';';
				}
				break;
			case 'bgimage':
				if (isset($atts['bgimage'])) {
					$content .= 'background:url(\''.$atts['bgimage']['row'].'\')';
				}
				break;
		}
	}
	return array('title' => $title, 'content' => $content, 'border' => $border);
}

/*-----------------------------------------------------------------------------------*/
/* CWS Spacing Styles
/*-----------------------------------------------------------------------------------*/
function cws_spacing_styles($atts) {
	$padding = '';
	$margin = '';

	if(isset($atts['paddings'])){
		$padding .= isset($atts['paddings']['left']) && $atts['paddings']['left'] != '' ? 'padding-left: '. $atts['paddings']['left'] . 'px;' : '';
		$padding .= isset($atts['paddings']['top']) && $atts['paddings']['top'] != '' ? 'padding-top: '. $atts['paddings']['top'] . 'px;' : '';
		$padding .= isset($atts['paddings']['right']) && $atts['paddings']['right'] != '' ? 'padding-right: '. $atts['paddings']['right'] . 'px;' : '';
		$padding .= isset($atts['paddings']['bottom']) && $atts['paddings']['bottom'] != '' ? 'padding-bottom: '. $atts['paddings']['bottom'] . 'px;' : '';
	}

	if (isset($atts['margins']) ) {
		$margin .= isset( $atts['margins']['left'] ) && $atts['margins']['left'] != ''  ? 'margin-left: '. $atts['margins']['left'] . 'px;' : '';
		$margin .= isset( $atts['margins']['top'] ) && $atts['margins']['top'] != '' ? 'margin-top: '. $atts['margins']['top'] . 'px;' : '';
		$margin .= isset( $atts['margins']['right'] ) && $atts['margins']['right'] != '' ? 'margin-right: '. $atts['margins']['right'] . 'px;' : '';
		$margin .= isset( $atts['margins']['bottom'] ) && $atts['margins']['bottom'] != '' ? 'margin-bottom: '. $atts['margins']['bottom'] . 'px;' : '';
	}
	return array('paddings' => $padding, 'margins' => $margin);
}


/*-----------------------------------------------------------------------------------*/
/* Banners
/*-----------------------------------------------------------------------------------*/

function relish_shortcode_special_offers ( $atts ) {

	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Banners', 'relish' ),
		),
		'width' => 'auto',
	);


	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	/*
	Spacings
	*/
	$el_atts = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$out = "";
	$out .= "<div class='banners-wrapper' ".(!empty($el_atts) ? "style='".$el_atts."'" : "").">";
	$out .= (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");
	if ($items){
		$out .= '<div class="wrapper-circle">';
		foreach ($items as $key => $value) {
			if($value['banner_style'] === 'style_one'){
				$out .=  '<div class="wrapper-circle-offers style_one">';
				$out .= '<a href="'.(!empty($value['url']['text']) ? esc_url($value['url']['text']) : "#").'">';
				
				$out .=  '<div class="img" '.($value['price']['bgtype'] == 'bgcolor' ? "style=background-color:".esc_url($value['price']['bgcolor']).";". ($value['price']['opacity'] != "100" ? "opacity:0.".esc_attr($value['price']['opacity']) : "opacity:1").";": '').'>';

				if($value['price']['bgtype'] == 'bgimg'){
					if(!empty($value['price']['bgimg']['row'])){	
						$thumb_obj = cws_thumb($value['price']['bgimg']['row'], array( 'width'=>262, 'height'=>262, 'crop' => true ), false);
						$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url($thumb_obj[0]) ."' data-at2x='" . esc_attr($thumb_obj[3]['retina_thumb_url']) ."'" : " src='". esc_url($thumb_obj[0]) . "' data-no-retina";
						$thumb_url = $thumb_path_hdpi;
						$src = $thumb_url;
						$out .=  '<img style="opacity:'.($value['price']['opacity'] != "100" ? "0.".esc_attr($value['price']['opacity']) : "1").'" '.$src.' alt="" class="img-responsive">';
					}
				}
				$out .=  '</div>';
				$out .=  '<div class="info-offers">';
				$out .=  '<div class="text-information">';
				$out .=  '<div class="price">';
				$out .=  $value['price']['sale'];
				$out .=  '</div>';
				$out .=  '<div class="title">';
				$out .=  $value['title']['text'];
				$out .=  '</div>';
				$out .=  '<div class="sub-title">';
				$out .=  $value['subtitle']['text'];
				$out .=  '</div>';
				$out .=  '</div>';
				$out .=  '<div class="button-offers">';
				$out .=  '<div class="ribbon-content" style="color:'.(isset($value['button']['textcolor']) && !empty($value['button']['textcolor']) ? $value['button']['textcolor'] : "#fff").';">';
				$out .=  $value['button']['text'];
				$out .=  '</div>';
				$out .=  '</div>';
				$out .=  '<div class="button-offers-inside-white"></div>';
				$out .=  '</div>';
				$out .= "</a>";
				$out .=  '</div>';
			}
			elseif($value['banner_style'] === 'style_two'){
				$out .=  '<div class="wrapper-circle-offers style-offers-two">';
				$out .= '<a href="'.(!empty($value['url']['text']) ? esc_url($value['url']['text']) : "#").'">';
				$out .=  '<div class="img" '.($value['price']['bgtype'] == 'bgcolor' ? "style=background-color:".esc_url($value['price']['bgcolor']).";". ($value['price']['opacity'] != "100" ? "opacity:0.".esc_attr($value['price']['opacity']) : "opacity:1").";": '').'>';
				if($value['price']['bgtype'] == 'bgimg'){
					if(!empty($value['price']['bgimg']['row'])){
						$thumb_obj = cws_thumb($value['price']['bgimg']['row'], array( 'width'=>242, 'height'=>242, 'crop' => true ), false);
						$thumb_path_hdpi = (!empty($thumb_obj[3]) ? $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url($thumb_obj[0]) ."' data-at2x='" . esc_attr($thumb_obj[3]['retina_thumb_url']) ."'" : " src='". esc_url($thumb_obj[0]) . "' data-no-retina" : "");
						$thumb_url = $thumb_path_hdpi;
						$src = $thumb_url;
						$out .=  '<img style="opacity:'.($value['price']['opacity'] != "100" ? "0.".esc_attr($value['price']['opacity']) : "1").'" '.$src.' alt="" class="img-responsive">';
					}
				}
				$out .=  '</div>';
				$out .=  '<div class="info-offers">';
				$out .=  '<div class="text-information">';

				$out .=  '<div class="sub-title">';
				$out .=  $value['subtitle']['text'];
				$out .=  '</div>';					
				$out .=  '<div class="title">';
				$out .=  $value['title']['text'];
				$out .=  '</div>';					
				$out .=  '<div class="price">';
				$out .=  $value['price']['text'];
				$out .=  '</div>';
				$out .=  '</div>';					
				$out .=  '</div>';

				$out .= "</a>";
				$out .=  '</div>';
			}
			elseif($value['banner_style'] === 'style_three'){
				$out .=  '<div class="wrapper-circle-offers style-offers-three">';
				$out .= '<a href="'.(!empty($value['url']['text']) ? esc_url($value['url']['text']) : "#").'">';
				$out .=  '<div class="img" '.($value['price']['bgtype'] == 'bgcolor' ? "style=background-color:".esc_url($value['price']['bgcolor']).";". ($value['price']['opacity'] != "100" ? "opacity:0.".esc_attr($value['price']['opacity']) : "opacity:1").";": '').'>';
				if($value['price']['bgtype'] == 'bgimg'){
					if(!empty($value['price']['bgimg']['row'])){
						$thumb_obj = cws_thumb($value['price']['bgimg']['row'], array( 'width'=>242, 'height'=>242, 'crop' => true ), false);
						$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url($thumb_obj[0]) ."' data-at2x='" . esc_attr($thumb_obj[3]['retina_thumb_url']) ."'" : " src='". esc_url($thumb_obj[0]) . "' data-no-retina";
						$thumb_url = $thumb_path_hdpi;
						$src = $thumb_url;
						$out .=  '<img style="opacity:'.($value['price']['opacity'] != "100" ? "0.".esc_attr($value['price']['opacity']) : "1").'" '.$src.' alt="" class="img-responsive">';
					}
				}
				$out .=  '</div>';
				if(isset($value['add_item_count']) && !empty($value['add_item_count'])){
					$out .=  '<div class="step-txt">';
					$out .=  '0'.++$key/*.'<span>'.esc_html__( 'step', 'relish' ).'</span>'*/;
					$out .=  '</div>';					
				}					

				$out .=  '<div class="info-offers">';

				$out .=  '<div class="text-information">';				
				$out .=  '<div class="title">';
				$out .=  $value['title']['text'];
				$out .=  '</div>';					
				$out .=  '</div>';					
				$out .=  '</div>';

				$out .= "</a>";
				$out .=  '</div>';		
			}
			else{
				
				/*
				Effect
				*/
				
				$effectsIhover = (isset($value['effectsIhover']) && !empty($value['effectsIhover']) ? " ".$value['effectsIhover'] : "effect1");
				$effect_ani = '';				
				
				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == 'effect2' || $value['effectsIhover'] == 'effect3' || $value['effectsIhover'] == 'effect4' || $value['effectsIhover'] == 'effect7' || $value['effectsIhover'] == 'effect8'  || $value['effectsIhover'] == 'effect9'  || $value['effectsIhover'] == 'effect11'  || $value['effectsIhover'] == 'effect12'  || $value['effectsIhover'] == 'effect14'  || $value['effectsIhover'] == 'effect18'){
						$effect_ani = ' '.$value['direction'];
					}	
					elseif($value['effectsIhover'] == 'effect10' || $value['effectsIhover'] == 'effect20' ){
						$effect_ani = ' '.$value['directionUPDown'];
					}

					elseif($value['effectsIhover'] == 'effect6'){
						$effect_ani = ' '.$value['scale'];
					}
					elseif($value['effectsIhover'] == 'effect13'){
						$effect_ani = ' '.$value['directionTwoChoice'];
					}
					elseif($value['effectsIhover'] == 'effect15'){
						$effect_ani = " left_to_right";
					}
					elseif($value['effectsIhover'] == 'effect16'){
						$effect_ani = ' '.$value['directionThreeChoice'];
					}
					else{
						$effect_ani = "";
					}					
				}
				/*
				\Effect
				*/
				/*
				Customize
				*/
				$styles = '';
				if(!empty($value['customize'])){
					if($value['bgtype'] == 'bgcolor'){
						//$styles .= (!empty($value['bgcolor']) ? 'background:'.$value['bgcolor'] . ';' : '');
						$args = array();
						$args['bgtype'] = $value['bgtype'];
						$args['bgcolor'] = $value['bgcolor'];
						$args['opacity'] = $value['opacity'];

						$bg_opacity = '';
						$bg_opacity = cws_build_styles($args);
						$styles .= (!empty($bg_opacity['content']) ? $bg_opacity['content'] : '');
					}
					else{
						$args = array();
						$args['bgtype'] = $value['bgtype'];
						$args['gradient'] = $value['gradient'];
						$args['gradient']['orientation'] = $value['gradient']['orientation'];
						$args['gradient']['s_color'] = $value['gradient']['s_color'];
						$args['gradient']['e_color'] = $value['gradient']['e_color'];

						$bg_opacity = '';
						$bg_opacity = cws_build_styles($args);


						$styles .= (!empty($bg_opacity['content']) ? $bg_opacity['content'] : '');
						
					}	
				}

				$out_back = '';	
				$out_back_close = '';	
				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == "effect5" || $value['effectsIhover'] == "effect1" || $value['effectsIhover'] == "effect18" || $value['effectsIhover'] == "effect20"){
						$out_back =  '<div class="info-back" '.(!empty($styles) ? 'style="'.$styles.'"' : "").'>';
						$out_back_close =  '</div>';
					}					
				}

				/*
				\Customize
				*/

				$out .=  '<div class="wrapper-circle-offers '.(empty($value['add_hover']) ? "no_hover " : "ih-item circle ").'style-offers-four'.(!empty($value['add_hover']) ? $effectsIhover.$effect_ani : "").'">';
				$out .= '<a href="'.(!empty($value['url']['text']) ? esc_url($value['url']['text']) : "#").'" class="pic_alt">';
				$out .=  '<div class="info-offers pic_alt">';				
				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == "effect8"){
						$out .=  '<div class="info-container">';
					}				
				}

				$out .= '<div class="item_content info" '.(!empty($styles) ? 'style="'.$styles.'"' : "").'>';

				
				$out .=  (!empty($out_back) ? $out_back : "");
				
				$out .= '<div class="post_info">';
					$out .= '<div class="title">';
						$out .= '<div class="title_part">';
							$out .= $value['title']['text'];
						$out .= '</div>';
					$out .= '</div>';
					$out .= '<div class="desc_part">';
						$out .= !empty($value['description_img']) ? $value['description_img'] : "";
					$out .= "</div>";			
				$out .= '</div>';

				$out .=  (!empty($out_back_close) ? $out_back_close : "");

				$out .= '</div>';
				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == "effect8"){
						$out .= '</div>';
					}					
				}

				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == "effect8"){
						$out .= '<div class="img-container">';
					}
				}
				$out .= "<div class='img_cont img'>";
				$img = wp_get_attachment_image_src( $value['gallery']['id'], $value['gallery']['size'] );
				if(!empty($value['gallery']['id'])){
					$out .= '<img src="'.$img[0].'" alt="" class="attachment-'.$value['gallery']['size'].' size-'.$value['gallery']['size'].'">';
				}
				else{
					$out .= "<span class='empty-circle'></span>";
				}
				$out .= "</div>";
				if(!empty($value['effectsIhover'])){
					if($value['effectsIhover'] == "effect8"){
						$out .= '</div>';
					}
				}		
				$out .= "</div>";				
				$out .= "</a>";
				$out .=  '</div>';
			}
		}
		$out .= '</div>';	
	}
	$out .= "</div>";	
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Button
/*-----------------------------------------------------------------------------------*/

function relish_button ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Click me', 'relish' ),
			'color' => '#ffffff',
			'size' => '16',
			),
		'icon' => '',
		'alt_style' => '0',
		'icon_pos' => 'before',
		'url' => '#',
		'new_window' => '0',
		'size' => 'regular',
		'bgtype' => 'bgcolor',
		'bgcolor' => relish_get_option('theme-custom-color'),
		'width' => 'auto',
		'paddings' => 0,
		"margins" => 0,
		'add_hover' => '',
		'border_radius' => '3',
		'float' => 'none',
		'alignment' => 'center'
	);
	$bg = '';
	$el_atts = '';
	$el_hover = '';
	if (empty($atts)) {
		$atts = $atts_default;
	}
	extract($atts);
	
	$title['text'] = !empty($title['text']) ? $title['text'] : $atts_default['title']['text'];
	$new_window = (!empty($new_window) ? $new_window : ""); 
	
	$icon = (!empty($icon) ? $icon : '');
	$alt_style = (isset($alt_style) && !empty($alt_style) ? $alt_style : "");
	$styles = cws_build_styles($atts);
	if (!empty($styles['content'])) {
		if(!$alt_style){
			$bg .= $styles['content'];	
			if(isset($bgcolor) && !empty($bgcolor)){
				$bg .= 'border-color:' .esc_attr($bgcolor). ';';
			}		
		}
	}
	$color = '';

	$color = 'color:'.($title['color'] && !$alt_style  ? $title['color']. ';' : $title['color'].(!empty($bgcolor) ? ';border-color:' .esc_attr($bgcolor). ';' : ''));

	if (!empty($width)) {
		switch ($width) {
			case 'auto':
				$width = "width:auto;";
				break;
			case 'full':
				$width = "width:100%;display:block;";
				break;
			case 'custom':
				$width_button = (!empty($width_button) ? $width_button : "");
				$height_button = (!empty($height_button) ? $height_button : "");
				$width = (!empty($width_button) ? 'width:'.esc_attr($width_button).'px;' : "");
				$width .= (!empty($height_button) ? 'height:'.esc_attr($height_button).'px;' : "");
				$width .= "display:flex;";
				$width .= "align-items: center;";
				break;
		}
	}

	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}	
	$size = (isset($size) && !empty($size) ? $size : $atts_default['size']);
	
	$el_atts .= (!empty($atts['border_radius']) ? 'border-radius:'.esc_attr($atts['border_radius']) . 'px;' : 'border-radius:'.esc_attr($atts_default['border_radius']) . 'px;');      
	
	$el_atts .= (!empty($atts['float']) ? 'float:'.esc_attr($atts['float']) . ';' : 'float:'.esc_attr($atts_default['float']) . ';');  

	$el_hover .= (!empty($add_hover) ?  'color:'.esc_attr($font_color) . ' !important;' : '');
	$out = "<div class='btn-container".(!empty($icon) ? " icon-position-".$icon_pos : "" )."' ".(isset($alignment) && !empty($alignment) ? "style=text-align:". esc_attr($alignment) ."" : "").">";
	$alt_hover = '';
	if(isset($alt_style) && !empty($alt_style)){
		$alt_hover = "background:".(!empty($bgcolor) ? esc_attr($bgcolor) . ';' : '');
		$alt_hover .= (!empty($add_hover) ?  'color:'.esc_attr($font_color) . ' !important;' : '');
		$alt_hover .= (!empty($add_hover) ?  'border-color:'.esc_attr($bgcolor) . ';' : '');
	}

	$data_attribute = (!empty($title['color']) ? ' data-based-color="'.$title['color'].'"' : "");

	$data_attribute .= (!empty($float) ? ' data-side="'.$float.'"' : "");

	$data_attribute .= (!empty($font_color) && empty($alt_style) ? ' data-attr-color="'.$font_color.'"'.' data-standart-color="'.(!empty($title['color']) ? $title['color'] : "#fff").'"' : "");

	$data_attribute .= (!empty($alt_style) ? ' data-attr-color="'.$font_color.'"'.' data-attr-bgcolor="'.$bgcolor.'"'. ' data-attr-border-color="'.$bgcolor.'"' : "");

	$out .= '<a 
				href="'.(esc_url($url) ? esc_url($url) : '#').'"
				'.(!empty($new_window) ? 'target="_blank"' : '').$data_attribute.'
				style="font-size:'.($title['size'] ? esc_attr($title['size']) : $atts_default['title']['size']).'px;'.$color.$bg.$width.$el_atts.'"	class="cws_button'.(isset($add_hover) && !empty($add_hover) && empty($alt_style) ? " add_hover add_hover".substr($font_color, 1) : " add_hover add_hover".substr($bgcolor, 1)).(isset($alt_style) && !empty($alt_style) ? " alt-style" : "")." ".(isset($size) && !empty($size) ? $size : "").(empty($add_hover) ? ' no_hover' : '').(!empty($float) ? " elemfloat ".$float : "").(!empty($icon) ? " add_icon " : "").'">'
					.(($icon && $icon_pos == 'before') ? '<i class="'.esc_attr($icon).'"></i>' : '').
					$title['text'].
					(($icon && $icon_pos == 'after') ? '<i class="'.esc_attr($icon).'"></i>' : '').
			'</a>';

	$out .= "</div>";
			
	return $out;
}


/*-----------------------------------------------------------------------------------*/
/* Carousel
/*-----------------------------------------------------------------------------------*/
function relish_carousel ( $atts,$content ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Carousel', 'relish' ),
			),
		'autoplay_carousel' => '',
		'pagination_carousel' => '',
		'navigation_carousel' => '',
		'colums_carousel' => '',
		'color_carousel' => '',
		'width' => 'auto',
		'paddings' => 0,
		"margins" => 0
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);	
	$out = '';

	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	$out .= '<div class="ce_title module_title">'.$title_text.'</div>';

	/*
	Carousel
	*/
	$autoplay_carousel = (isset($autoplay_carousel) && !empty($autoplay_carousel) ? true : false);
	$pagination_carousel = (isset($pagination_carousel) && !empty($pagination_carousel) ? "bullets_nav" : false);
	$navigation_carousel = (isset($navigation_carousel) && !empty($navigation_carousel) ? true : false);

	$filter_columns = "";
	if(isset($colums_carousel) && !empty($colums_carousel)){
		switch ($colums_carousel) {
			case 'one':
				$filter_columns = "1";
				break;
			case 'two':
				$filter_columns = "2";
				break;
			case 'three':
				$filter_columns = "3";
				break;
			case 'four':
				$filter_columns = "4";
				break;
			case 'five':
				$filter_columns = "5";
				break;
		}		
	}
	else{
		$filter_columns = "3";
	}
	/*
	\Carousel
	*/
	wp_enqueue_script ('owl_carousel');

	$el_atts = '';
	$spacing = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}	

	$out .= '<div class="shortcode-carousel cws_sc_carousel'.(!empty($navigation_carousel) ? " use_nav" : "").' '.$pagination_carousel.'" style="'.$el_atts.'" data-autoplay="'.$autoplay_carousel.'" data-columns="'.($filter_columns ? $filter_columns : "3").'">';
		if($navigation_carousel){
			$out .= "<div class='cws_sc_carousel_header clearfix'>";
				$out .= "<div class='carousel_nav_panel'>";
					$out .= "<span class='prev'></span>";
					$out .= "<span class='next'></span>";
				$out .= "</div>";
			$out .= "</div>";			
		}
		$out .= '<div class="cws_wrapper">';
			if(isset($content) && !empty($content)){
				$out .= do_shortcode($content);
			}
		$out .= '</div>';
		$out .= '</div>';		
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Testimonials
/*-----------------------------------------------------------------------------------*/
function relish_testimonials ( $atts ) {

	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Testimonials', 'relish' ),
			),
		'slider' => array(
			'carousel' => "",
			),
		'img_alignment' => 'left',
		'width' => 'auto'
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	$out = '';


	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	/*
	Carousel
	*/
	$carousel_init = (isset($slider['carousel']) && !empty($slider['carousel']) ? "cws_sc_carousel" : "");
	$autoplay_carousel = (isset($slider['autoplay']) && !empty($slider['autoplay']) ? true : false);
	$pagination_carousel = (isset($slider['pagination']) && !empty($slider['pagination']) ? "bullets_nav" : false);
	$navigation_carousel = (isset($slider['navigation']) && !empty($slider['navigation']) ? true : false);

	$columns = "";
	if(isset($slider['colums']) && !empty($slider['colums'])){
		switch ($slider['colums']) {
			case 'one':
				$columns = "1";
				break;
			case 'two':
				$columns = "2";
				break;
			case 'three':
				$columns = "3";
				break;
			case 'four':
				$columns = "4";
				break;
		}		
	}
	/*
	\Carousel
	*/


	/*
	Spacings
	*/
	$el_atts = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$img_alignment = isset($img_alignment) && !empty($img_alignment) ? $img_alignment : "center";

	$out .= "<div class='wrapper_testimonials' ".(!empty($el_atts) ? "style='".$el_atts."'" : "").">";

	$out .= "<div class='ce_title module_title'>".$title_text."</div>";
	wp_enqueue_script ('owl_carousel');
	$out .= '<div class="wrapper-testimonials '.$pagination_carousel.' '.$carousel_init.'" data-autoplay="'.esc_attr($autoplay_carousel).'" data-columns="'.($columns ? esc_attr($columns) : "5").'">';
		if($navigation_carousel){
			$out .= "<div class='cws_sc_carousel_header clearfix'>";
				$out .= "<div class='carousel_nav_panel'>";
					$out .= "<span class='prev'></span>";
					$out .= "<span class='next'></span>";
				$out .= "</div>";
			$out .= "</div>";			
		}

		$out .= '<div class="cws_wrapper">';
			foreach ($items as $key => $value) {
				$border = RELISH_COLOR;		
				$out .= "<div class='testimonials-wrapper' style=text-align:".(!empty($img_alignment) ? esc_attr($img_alignment) : "center" ).">";
				$out .= "<div class='container-img".(!empty($img_alignment) ? ' elem-'.esc_attr($img_alignment) : "center" )."'>";
				$thumb_obj = cws_thumb( $value['iconimg']['row'], array( 'width' => 95, 'height' => 95, 'crop' => true ), false );
				$out .= "<img src='".esc_url($thumb_obj[0])."' ".(isset($value['img_border']) && !empty($value['img_border']) ? 'style=border-width:1px;border-style:solid;border-color:'.esc_attr($border).";"  : "" )." alt=''>";	
				$out .= "</div>";
				$out .=  "<div class='content-testimonials'>".$value['text_content']."</div>";	
				$out .=  "<div class='author-testimonials'>";
				$out .= '<a href="'.esc_url($value['url']).'" target="'.(isset($value['new_window']) && !empty($value['new_window']) ? "_blank" : "_self").'">';
				$out .= $value['author'];
				$out .= '</a>';
				$out .= "</div>";
				$out .= "</div>";
			}
		$out .= "</div>";
		$out .= "</div>";
	$out .= "</div>";
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Portfolio
/*-----------------------------------------------------------------------------------*/
function relish_portfolio ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Portfolio', 'relish' ),
			),
		'slider' => array(
			'carousel' => "",
			),
		'width' => 'auto',
		'user_pagination' => 'standard',
		'portfolio_display' => 'grid',
		'portfolio_mode' => 'circle',
		'items_per_page' => 1,
		'portfolio_columns' => '3',
		'filter' => '',
		'direction' => '',
		'portfolio_categories' => '',
		'filter_categories'	=> '',
		'scale' => '',
		'directionUPDown' => '',
		'directionTwoChoice' => '',
		'directionThreeChoice' => '',
		'dis_pagination' => '',
		'exclude' => array(),
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	/*
	Carousel
	*/
	$carousel_init = (isset($slider['carousel']) && !empty($slider['carousel']) ? "cws_sc_carousel" : "");
	$autoplay_carousel = (isset($slider['autoplay']) && !empty($slider['autoplay']) ? true : false);
	$pagination_carousel = (isset($slider['pagination']) && !empty($slider['pagination']) ? "bullets_nav" : false);
	$navigation_carousel = (isset($slider['navigation']) && !empty($slider['navigation']) ? true : false);

	$filter_columns = "";
	if(isset($portfolio_columns) && !empty($portfolio_columns)){
		switch ($portfolio_columns) {
			case 'one':
				$filter_columns = "1";
				break;
			case 'two':
				$filter_columns = "2";
				break;
			case 'three':
				$filter_columns = "3";
				break;
			case 'four':
				$filter_columns = "4";
				break;
			case '1':
				$filter_columns = "1";
				break;
			case '2':
				$filter_columns = "2";
				break;
			case '3':
				$filter_columns = "3";
				break;
			case '4':
				$filter_columns = "4";
				break;
		}		
	}
	else{
		$filter_columns = "3";
	}
	/*
	\Carousel
	*/

	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");
	
	/*
	Effect
	*/
	$effect_ani = '';

	$effectsIhover = (isset($effectsIhover) && !empty($effectsIhover) ? $effectsIhover : "effect1");

	if($effectsIhover == 'effect2' || $effectsIhover == 'effect3' || $effectsIhover == 'effect4' || $effectsIhover == 'effect7' || $effectsIhover == 'effect8'  || $effectsIhover == 'effect9'  || $effectsIhover == 'effect11'  || $effectsIhover == 'effect12'  || $effectsIhover == 'effect14'  || $effectsIhover == 'effect18'){
		$effect_ani = $direction;
	}	
	elseif($effectsIhover == 'effect10' || $effectsIhover == 'effect20' ){
		$effect_ani = $directionUPDown;
	}

	elseif($effectsIhover == 'effect6'){
		$effect_ani = $scale;
	}
	elseif($effectsIhover == 'effect13'){
		$effect_ani = $directionTwoChoice;
	}
	elseif($effectsIhover == 'effect15'){
		$effect_ani = "left_to_right";
	}
	elseif($effectsIhover == 'effect16'){
		$effect_ani = $directionThreeChoice;
	}
	else{
		$effect_ani = "";
	}	
	/*
	\Effect
	*/

	/*
	Spacings
	*/
	$el_atts = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$out = "";
	$out .= "<div class='wrapper_portolio' ".(!empty($el_atts) ? "style='".$el_atts."'" : "").">";
	$out .= (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");

	$p_id = get_queried_object_id();
	$filter = "all";
	
	$items_per_page = (isset($items_per_page) && !empty($items_per_page) ? (int) $items_per_page : 3);
	
    $categories = "";
	$name_cats = array();

	$query_args = array(
		'post_type' => 'cws_portfolio',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish'
	);


	$categories = isset($portfolio_categories) && !empty($portfolio_categories) ? $portfolio_categories : "";
	if(isset($atts['filter_categories']) && !empty($atts['filter_categories'])){
		$categories = $atts['filter_categories'];
	}
	
	if(isset($categories) && !empty($categories)){
		$categories = explode( ',', $categories );
		$categories = relish_filter_by_empty( $categories );
	}

	$i = 0; 
    foreach (get_categories('taxonomy=cws_portfolio_cat') as $key => $value) { 
    	$name_cats[$i]['name'] = $value->name;
    	$name_cats[$i]['slug'] = $value->slug;
    	$i++;
   	}

	$portfolio_mode = isset($portfolio_mode) && !empty($portfolio_mode) ? $portfolio_mode : "circle";
   	
   	if ( !empty($portfolio_display) || ($portfolio_mode =='square') ) $query_args['posts_per_page'] = $items_per_page;
	$tax_query = array();

	$vale = '';
	foreach ($name_cats as $key => $value) {
		$vale .= $value['slug'].',';

	}
	$vale = substr($vale, 0, -1);
	$cats = explode(",", $vale);
	$sel_posts_by = isset($filter_by) && !empty($filter_by) ? $filter_by : "";

	if($sel_posts_by == 'filter_cat'){
		$sel_posts_by = true;
	}else{
		$sel_posts_by = false;
	}


	if ( !empty( $categories )) {
		$categories[0] = str_replace("null", "", $categories[0]);
		$tax_query[] = array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $categories
			);
	}		

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	$excluded_posts = '';

	$excluded_posts = (!empty($exclude) && is_array( $exclude ) ? $exclude : "");
	

	$excluded_posts = $excluded_posts ? $excluded_posts : array();
	if ( !empty( $excluded_posts ) ) {
		$query_args["post__not_in"] = $excluded_posts;
	}

	$q = new WP_Query( $query_args );

	$section_class = "cws_portfolio";
	$section_class .= " massonry";

	if ( $q->have_posts() ) {
		$out .= "<section class='$section_class'>";

			ob_start();

			$use_filter = false;
			$use_carousel = false;

			if(isset($portfolio_display) && !empty($portfolio_display)){
				if ( $portfolio_display == "grid_with_filter" ) {
					$avail_cats = $categories;
					if ( empty( $avail_cats ) ) {
						$avail_cats = cws_get_portfolio_cat_slugs ();
					}
					if ( !empty( $avail_cats ) ) {
						$use_filter = true;
					}
				}
				else if ( $portfolio_display == "carousel" ) {
					$use_carousel = true;
					wp_enqueue_script ('owl_carousel');
				}				
			}
			

			if ( $use_filter ) {
				echo "<div class='cws_portfolio_filter_container'>";
					echo "<div class='cws_portfolio_filter fw_filter'>";
						echo "<a href='#' data-filter='".esc_attr($filter)."' class='active'> " . esc_html__( 'All', 'relish' ) . "</a>";
						foreach( $avail_cats as $avail_cat ) {
							$cat = get_term_by( 'slug', $avail_cat, 'cws_portfolio_cat' );
							$cat_name = esc_html( $cat->name );
							echo '<a href="#" data-filter="'. esc_attr( $avail_cat ). "\">$cat_name</a>";
						}
					echo "</div>";
				echo "</div>";
			}

			wp_enqueue_script ('isotope');

			$header_content = ob_get_clean();
			$out .= !empty( $header_content ) ? "<div class='cws_portfolio_header'>$header_content</div>" : "";

			
			$items_section_class = "cws_portfolio_items cws-mode-$portfolio_mode portfolio-section portfolio-grid grid" . (($filter_columns != '1') ? " grid-$filter_columns" : "" );

			$items_section_class .= isset($user_pagination) && !empty($user_pagination) ? " pagination-ajax" : "";

			$data_attr = "";
			$portfolio_display = (isset($portfolio_display) && !empty($portfolio_display) ? $portfolio_display : "grid");
			if(isset($portfolio_display) && !empty($portfolio_display)){
				$items_section_class .= (($portfolio_display == "carousel") ? " portfolio_carousel" : " isotope");

				if($portfolio_display == "carousel"){
					$data_attr =  "data-autoplay='".esc_attr($autoplay_carousel)."'";
					$data_attr .= " data-nav='".($navigation_carousel ? esc_attr($navigation_carousel) : '')."'";
				}
			}
			
			$out .= "<div class='cws_wrapper'>";
				$out .= "<div class='$items_section_class $pagination_carousel'  data-columns='".($filter_columns ? esc_attr($filter_columns) : '5')."' $data_attr>";
					ob_start();
					render_cws_portfolio( $q, $filter_columns, $effectsIhover, $effect_ani,$portfolio_mode);
					relish_portfolio_loader();
					$out .= ob_get_clean();
				$out .= "</div>";
			$out .= "</div>";
			if(isset($portfolio_display) && !empty($portfolio_display)){
				if ( $portfolio_display != 'carousel' ) {
						
						$out .= "<input type='hidden' class='cws_portfolio_ajax_data' value='" . esc_attr(
							json_encode(
								array(
									'p_id' => $p_id,
									'effectsIhover' => $effectsIhover,
									'effect_ani' => $effect_ani,
									'portfolio_columns' => $filter_columns,
									'portfolio_display' => $portfolio_display,
									'portfolio_mode' => $portfolio_mode,			
									'cats' => $categories,
									'posts_per_page' => $items_per_page,
									'disable_pagination' => !empty($dis_pagination) ? $dis_pagination : "",
									'pagination_style' => !empty($user_pagination) ? $user_pagination : '',
									'exclude' => $excluded_posts,
									)
								)
							) . "' />";
						$max_paged = ceil( $q->found_posts / $items_per_page );
						$disable_pagination = !empty($dis_pagination) ? $dis_pagination : "";
						if ( !empty($disable_pagination) && $max_paged > 1 ) {
							ob_start();

							if(isset($user_pagination) && $user_pagination == 'standard'){
								relish_pagination( 1, $max_paged );
							}
							else{
								relish_pagination( 1, $max_paged, $user_pagination = 'load_more');
							}
							$out .= ob_get_clean();
						}						
				}
			}	
		$out .= "</section>";
	}

	$out .= "</div>";

	return $out;
}


/*-----------------------------------------------------------------------------------*/
/* Pricing Table
/*-----------------------------------------------------------------------------------*/
function relish_pricing ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Pricing Table', 'relish' ),
			),
		
		'width' => 'auto',
		'add_active_pricing' => '0',
	);


	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");



	$el_atts = '';
	$title = $atts['title']['text'];

	$author = 'Test';
	$title_styles = '';

	/*
	Spacings
	*/
	$el_atts_sp = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_sp .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_sp .= $spacing['margins'];
	}
	/*
	\Spacings
	*/
	$out = "";
	$out .= "<div class='wrapper-pricing-t' ".(!empty($el_atts_sp) ? "style='".$el_atts_sp."'" : "").">";
	$out .= (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");
	$out .= "<div class='pricing_table'>";
	foreach ($items as $key => $v) {
		$add_active_pricing = !empty($v['add_active_pricing']) ? " active" : "";

		$out .= '<div class="pricing_table_column'.$add_active_pricing.'">';
		
		preg_match('/(.*[^0-9])(\d+)([\.,]\d+)/', $v['price']['text'], $matches);
		$border = '';
		list(, $currency, $price, $pfraction) = $matches;

		
		$ps = cws_build_styles($v['price']);
		$price_styles = ' style="' .$ps['content'] . ';background-repeat:no-repeat;background-size:cover;background-position: center center;color:' .$v['price']['color'] . ';"';
	
		$active_price_bg = '';
		if(!empty($v['add_active_pricing'])){
			if(!empty($v['color_active_pricing'])){
				$active_price_bg = 'style="background-color:'.$v['color_active_pricing'].'"';
			}
			else{
				$active_price_bg = 'style="background-color:#ffca28"';
			}
		}

		$out .= sprintf('<div class="price_section"%s><div class="price_container" %s><div class="wrap-price"><span class="main_price_part">%s</span><span class="price_details"><span class="fract_price_part">%s</span><sup>%s</sup><span class="det-price">%s</span></span></div></div></div>',$price_styles,$active_price_bg, trim($currency), isset($price) ? $price : $v['price']['text'], $pfraction,  (isset($v['price_details']['text'])) ? $v['price_details']['text'] : "" );


		$title_section = sprintf('<div class="title_section ce_title">%s</div>', $v['title']['text']);		
		
		$out .= $title_section;


		$out .= '<div class="desc_section">';

		$out .= $v['text_content'];
		$out .= '</div>';

		$out .= '<div class="btn_section">';
		$color = 'color:'.esc_attr($v['button']['textcolor']) . ';';
		$border_color = (!empty($v['button']['bgcolor']) ? 'border-color:'.esc_attr($v['button']['bgcolor']) . ';' : "");
		$background_color = (!empty($v['button']['bgcolor']) ? 'background-color:'.esc_attr($v['button']['bgcolor']) . ';' : "");
		$el_atts = '';
		if ('1' === $v['button']['new_window']) {
			$el_atts .= ' target="_blank"';
		}
		// first two are hover values
		$out .= sprintf('<a class="cws_button alt custom_colors" data-bg-color="%s" data-font-color="%s" href="%s" style="%s"%s>%s</a>', !empty($v['button']['customize']) ? $v['button']['bgcolor'] : '', $v['button']['textcolor'], $v['button']['url'], $color.$border_color.$background_color, $el_atts, $v['button']['text']);
		$out .= '</div>';

		$out .= '</div>';
	}
	$out .= '</div>';
	$out .= "</div>";
	return $out;

}

/*-----------------------------------------------------------------------------------*/
/* Our Team
/*-----------------------------------------------------------------------------------*/
function relish_team ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Our Team', 'relish' ),
			),
		'slider' => array(
			'carousel' => "",
			),
		'columns' => '3',
		'items_per_page' => '1',
		'width' => 'auto',
		'display' => 'grid',

		'filter' => '',
		'order_by' => 'desc',
		'dis_pagination' => '',

	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	/*
	Carousel
	*/
	$carousel_init = (isset($display) && !empty($display) && $display == 'carousel' ? "cws_sc_carousel" : "");
	$autoplay_carousel = (isset($slider['autoplay']) && !empty($slider['autoplay']) ? true : false);
	$pagination_carousel = (isset($slider['pagination']) && !empty($slider['pagination']) ? "bullets_nav" : false);
	$navigation_carousel = (isset($slider['navigation']) && !empty($slider['navigation']) ? true : false);

	$filter_columns = "";
	if(isset($columns) && !empty($columns)){
		switch ($columns) {
			case 'one':
				$filter_columns = "1";
				break;
			case 'two':
				$filter_columns = "2";
				break;
			case 'three':
				$filter_columns = "3";
				break;
			case 'four':
				$filter_columns = "4";
				break;
		}		
	}
	else{
		$filter_columns = "3";
	}
	/*
	\Carousel
	*/
	$out = "";
	
	if(!is_archive()){
		$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	}

	/*
	Spacings
	*/
	$el_atts_sp = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_sp .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_sp .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$out .= "<div class='wrapper-out-team' ".(!empty($el_atts_sp) ? "style='".$el_atts_sp."'" : "").">";

	$out .= (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");

	$p_id = get_queried_object_id();
	$filter = "all";
	
	$items_per_page = (isset($items_per_page) && !empty($items_per_page) ? (int) $items_per_page : 3);
	
    $categories = "";
	$name_cats = array();

	$categories = explode( ',', $categories );
	$categories = relish_filter_by_empty( $categories );
	$query_args = array(
		'post_type' => 'cws_staff',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish'
	);
	$i = 0; 
    foreach (get_categories('taxonomy=cws_staff_member_department') as $key => $value) { 
    	$name_cats[$i]['name'] = $value->name;
    	$name_cats[$i]['slug'] = $value->slug;
    	$i++;
   	}

   	if ( !empty($display) ) $query_args['posts_per_page'] = $items_per_page;
	$tax_query = array();

	$vale = '';
	foreach ($name_cats as $key => $value) {
		$vale .= $value['slug'].',';

	}
	$vale = substr($vale, 0, -1);
	$cats = explode(",", $vale);

	if(isset($display) && !empty($display)){

		if($display == 'grid_with_filter'){
			$tax_query[] = array(
				'taxonomy' => 'cws_staff_member_department',
				'field' => 'slug',
				'terms' => $cats
			);		
		}		
	}
	if(!empty($atts['tags'])){
		$tax_query[] = array(
			'taxonomy' => 'cws_staff_member_position',
			'field' => 'slug',
			'terms' => $atts['tags']
		);
		$query_args['posts_per_page'] = 100;
	}

	if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

	$order_by = (isset($order_by) && !empty($order_by) ? strtoupper($order_by) : "DESC");
	if ( !empty( $order_by ) ) $query_args['order'] = $order_by;
	
	$q = new WP_Query( $query_args );

	$section_class = "cws_ourteam";
	if ( $q->have_posts() ) {
		$out .= "<section class='$section_class'>";

			ob_start();

			$use_filter = false;
			$use_carousel = false;

				if(isset($display) && !empty($display)){
					if ( $display == "grid_with_filter" ) {
						$avail_cats = $cats;
						if ( empty( $avail_cats ) ) {
							$avail_cats = cws_get_portfolio_cat_slugs ();
						}
						if ( !empty( $avail_cats ) ) {
							$use_filter = true;
						}
					}
					else if ( $display == "carousel" ) {
						$use_carousel = true;
						wp_enqueue_script ('owl_carousel');
					}				
				}
				if ( $use_filter ) {
					echo "<div class='cws_ourteam_filter_container'>";
						echo "<select class='cws_ourteam_filter'>";
							echo "<option value='".esc_attr($filter)."'>" . esc_html__( 'All', 'relish' ) . '</option>';
							foreach( $avail_cats as $avail_cat ) {
								$cat = get_term_by( 'slug', $avail_cat, 'cws_staff_member_department' );
								$cat_name = $cat->name;
								echo '<option value="'.esc_attr( $avail_cat )."\">$cat_name</option>";
							}
						echo "</select>";
					echo "</div>";
				}
				wp_enqueue_script ('isotope');
				$header_content = ob_get_clean();
				$out .= !empty( $header_content ) ? "<div class='cws_ourteam_header'>$header_content</div>" : "";

				$items_section_class = "cws_ourteam_items grid grid-$filter_columns";
				if(!empty($display)){
					$items_section_class .= $display == "carousel" ? " ourteam_carousel" : " isotope";
				}
				

				$data_attr = "";
				if(isset($display) && !empty($display)){
					if($display == "carousel"){
						$data_attr =  "data-autoplay='".esc_attr($autoplay_carousel)."'";
						$data_attr .= " data-nav='".($navigation_carousel ? esc_attr($navigation_carousel) : '')."'";
					}
				}

				$out .= "<div class='cws_wrapper'>";
					$out .= "<div class='$items_section_class $pagination_carousel'  data-columns='".($filter_columns ? esc_attr($filter_columns) : '5')."' $data_attr>";
						ob_start();

						render_cws_ourteam( $q );
						$out .= ob_get_clean();
					$out .= "</div>";
				$out .= "</div>";
				$disable_pagination = !empty($dis_pagination) ? $dis_pagination : "";

					if ( in_array( $display, array( 'grid', 'grid_with_filter' ) ) ) {
						$out .= "<input type='hidden' class='cws_ourteam_ajax_data' value='" . esc_attr( json_encode( array( 'p_id' => $p_id, 'display' => $display, 'cats' => $cats, 'filter' => $filter, 'posts_per_page' => $items_per_page ) ) ) . "' />";				
					if(!empty($disable_pagination)){
						$max_paged = ceil( $q->found_posts / $items_per_page );
						if ( $max_paged > 1 ) {
							ob_start();
							relish_pagination( 1, $max_paged );
							$out .= ob_get_clean();
						}
					}				
					}


		$out .= "</section>";
	}
	$out .= "</div>";
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Message Box
/*-----------------------------------------------------------------------------------*/
function relish_msgbox ( $atts ) {
	$atts_default = array(
		'type' => 'info',
		'title' => esc_html__( 'INFORMATION BOX', 'relish' ),
		'text' => 'Vestibulum sodales pellentesque nibh quis imperdiet',
		'is_closable' => '0',
		'customize' => '0',
		'custom_options' => array (
			'color' => '#fff',
			'bg_color' => RELISH_COLOR,
			'icon' => "",
			'icon_bg' => RELISH_COLOR,
			'border_bg' => '#fff'
		),
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$out = "";
	if(!empty($customize)){
		$icon = $customize == '1' && isset($customize) && !empty( $custom_icon ) ? $custom_icon : 'cwsicon-info31';		
	}

	if ( empty( $icon ) ) {
		switch ($type){
			case 'info':
				$icon = 'cwsicon-info31';
				break;
			case 'warning':
				$icon = 'cwsicon-lightning24';
				break;
			case 'success':
				$icon = 'cwsicon-check-mark';
				break;
			case 'error':
				$icon = 'cwsicon-exclamation-mark1';
				break;
		}
	}



	$custom_styles = "";

	$custom_options_color = (isset($custom_options['color']) && !empty($custom_options['color']) ? "color:".esc_attr($custom_options['color']).";" : "");

	$custom_options_text_color = (isset($custom_options['txt_color']) && !empty($custom_options['txt_color']) ? "color:".esc_attr($custom_options['txt_color']).";" : "");
	
	$custom_options_bg = (isset($custom_options['bg_color']) && !empty($custom_options['bg_color']) ? "background-color:".esc_attr($custom_options['bg_color']).";" : "");

	$custom_options_border = (isset($custom_options['border_bg']) && !empty($custom_options['border_bg']) ? "border-color:".esc_attr($custom_options['border_bg']).";" : "");

	$custom_icon = (isset($custom_options['icon_bg']) && !empty($custom_options['icon_bg']) ? "background-color:".esc_attr($custom_options['icon_bg']).";" : "");

	$custom_icon .= (isset($custom_options['icon_font_color']) && !empty($custom_options['icon_font_color']) ? "color:".esc_attr($custom_options['icon_font_color']).";" : "");
	if(!empty($customize)){
		$custom_styles = $customize == '1' ? $custom_options_color.$custom_options_bg.$custom_options_border."" : "";		
	
		if($customize == '1' && isset($customize) && !empty($custom_options['icon'])){
			$icon = $custom_options['icon'];
		}	
	
		$close_icon_color = '';
		if($customize == '1' && (isset($custom_options['bg_color']) && !empty($custom_options['bg_color'])) && $custom_options['bg_color'] == "#ffffff"){
			$close_icon_color = " icon-bg-r";
		}	
	}

	$title = isset($title) && !empty($title) ? $title : $atts_default['title'];
	$text = isset($text) && !empty($text) ? $text : $atts_default['text'];
	ob_start();
		echo ! empty( $title ) ? '<div class="msg_box_title" '.(isset($custom_options_color) && !empty($custom_options_color) ? 'style='.$custom_options_color : "").'>'.esc_html( $title ).'</div>' : '';
		echo ! empty( $text ) ? '<div class="msg_box_text" '.(isset($custom_options_text_color) && !empty($custom_options_text_color) ? 'style='.$custom_options_text_color : "").'>'. $text .'</div>' : '';
	$content_box = ob_get_clean();

	$container_class = "cws_msg_box  clearfix";
	$container_class .= !empty($type) ? " ".$type."-box" : "";
	
	$container_class .= !empty($is_closable) && $is_closable == '1' ? " closable" : "";

	$out .= "<div class='$container_class'" . ( !empty( $custom_styles ) ? " style='$custom_styles'" : "" ) . ">";
		$out .= "<div class='icon_section' " . ( !empty( $custom_icon ) ? " style='$custom_icon'" : "" ) . "><i class='$icon'></i></div>";
		$out .= "<div class='content_section'>$content_box</div>";
		$out .= !empty($is_closable) && $is_closable == '1' ? "<div class='cls_btn".(isset($close_icon_color) && !empty($close_icon_color) ? $close_icon_color : "")."'></div>" : "";
	$out .= "</div>";
	
	return $out;

}
  			 
/*-----------------------------------------------------------------------------------*/
/* Services
/*-----------------------------------------------------------------------------------*/
function service_filter_gallery(){
	return add_filter( 'post_gallery', 'bootstrap_gallery', 11, 3 );
}

function bootstrap_gallery( $output = '', $atts, $instance )
{
    $atts = array_merge(array('columns' => 3), $atts);

    $columns = $atts['columns'];
    $images = explode(',', $atts['ids']);

    $return = '<div class="row gallery">';
    $i = 0;
    $gallery_id = uniqid( 'cws-portfolio-gallery-' );
    foreach ($images as $key => $value) {
        if ($i%$columns == 0 && $i > 0) {
            $return .= '</div><div class="row gallery">';
        }
        $image_attributes = wp_get_attachment_image_src($value, 'full');

        	
        $thumb_obj = cws_thumb($image_attributes[0], array( 'width'=>370, 'height'=>370, 'crop' => true ), false);

		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url($thumb_obj[0]) ."' data-at2x='" . esc_attr($thumb_obj[3]['retina_thumb_url']) ."'" : " src='". esc_url($thumb_obj[0]) . "' data-no-retina";
		$thumb_url = $thumb_path_hdpi;

		$thumb_obj_small = cws_thumb($image_attributes[0], array( 'width'=>170, 'height'=>170, 'crop' => true ), false);

		$thumb_path_hdpi_small = $thumb_obj_small[3]['retina_thumb_exists'] ? " src='". esc_url($thumb_obj_small[0]) ."' data-at2x='" . esc_attr($thumb_obj_small[3]['retina_thumb_url']) ."'" : " src='". esc_url($thumb_obj_small[0]) . "' data-no-retina";
		$thumb_url_small = $thumb_path_hdpi_small;

		$src = ($i == 0 ? $thumb_url : $thumb_url_small);
        	$return .= '
            <div '.($i == 0 ? 'class="first-item"' : '').'> 
                <a class="fancy" data-gallery="gallery" data-fancybox-group="'.$gallery_id.'" href="'.$image_attributes[0].'"
					>
                    <img '.$src.' alt="" class="img-responsive">
                </a>
            </div>';

        $i++;
    }
    $return .= '</div>';
    return $return;
}

function relish_services ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Services', 'relish' ),
			),
		'width' => 'auto',
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	
	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	$out = "";

	$out = (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");
		$out .= '<div class="services-container">';
			$out .= '<div class="cws_wrapper">';
				service_filter_gallery();
				foreach ($items as $key => $value) {
					$out .= "<div class='container-gallery'>";
					$out .= "<div class='gallery-services'>";
					$out .= do_shortcode($value['gallery']);
					$out .= "</div>";
					$out .= "<div class='content-services'>";
						$out .= "<div class='title-services'>";
							$out .= $value['title']['text'];
						$out .= "</div>";
						$out .= "<div class='text-content-services'>";
							$out .= $value['title']['text_content'];
						$out .= "</div>";
					$out .= "</div>";
					$out .= "</div>";
				}
			$out .= "</div>";
		$out .= "</div>";
		remove_filter('post_gallery', 'bootstrap_gallery', 11);
	return $out;
}

function relish_products_gallery( $atts ){
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Products Gallery', 'relish' ),
			),
		'gallery' => '',
		'side_second_img' => 'right',
		'width' => 'auto',
	);

	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	
	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");
	$side_second_img = (!empty($side_second_img) ? $side_second_img : "");

	$out = "";
	
	/*
	Spacings
	*/
	$el_atts_sp = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_sp .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_sp .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$out .= "<div class='wrapper-special-gallery' ".(!empty($el_atts_sp) ? "style='".$el_atts_sp."'" : "").">";

	$out .= (!empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "");
		$out .= '<div class="services-container '.$side_second_img.'" >';
			$out .= '<div class="cws_wrapper">';
				service_filter_gallery();
				if(!empty($gallery)){
					$out .= "<div class='container-gallery'>";
						$out .= "<div class='gallery-services'>";
							$out .= do_shortcode($gallery);
						$out .= "</div>";
					$out .= "</div>";					
				}
				remove_filter('post_gallery', 'bootstrap_gallery', 11);
			$out .= "</div>";
		$out .= "</div>";
	$out .= "</div>";

	return $out;	
}

function relish_pricing_list( $atts ){
	$atts_default = array(
		'title' => array (
			'pricing_title' => esc_html__( 'Pricing Lists', 'relish' ),
			),
	);

	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$out = '';
		/*
	Spacings
	*/
	$el_atts_sp = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_sp .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_sp .= $spacing['margins'];
	}
	/*
	\Spacings
	*/

	$out .= "<div class='price-list-container' ".(!empty($el_atts_sp) ? "style='".$el_atts_sp."'" : "").">";
	$out .= (!empty($title['pricing_title']) ? '<div class="ce_title module_title">'.$title['pricing_title'].'</div>' : "");
	$out .= '<div class="cws_wrapper">';
		$out .= '<ul>';
			
			foreach ($items as $key => $value) {

				$title_text = (isset($value['title']['text']) && !empty($value['title']['text']) ? $value['title']['text'] : "");
				$desc = (isset($value['text_content']) && !empty($value['text_content']) ? $value['text_content'] : "");
						
				$type = (isset($value['type']) && !empty($value['type']) ? $value['type'] : "button");
				$type_discount = (isset($value['type_discount']) && !empty($value['type_discount']) ? $value['type_discount'] : "button");

				$url = (isset($value['url']) && !empty($value['url']) ? esc_url($value['url']) : "#");
				$new_window = (isset($value['new_window']) && !empty($value['new_window']) ? "_blank" : "_self");

				$class_list = '';
				switch ($type) {
					case 'button':
						$class_list .= 'btn pricing-lists';
						break;
					case 'text':
						$class_list .= 'txt pricing-lists';
						break;
				}

				/* Discount Price */
				$bg_d = '';

				$url_d = (isset($value['url_discount']) && !empty($value['url_discount']) ? esc_url($value['url_discount']) : "#");
				$new_window_d = (isset($value['new_window_discount']) && !empty($value['new_window_discount']) ? "_blank" : "_self");

				$alt_style_d = (!empty($value['alt_style_discount']) ? $value['alt_style_discount'] : "");
				

				if(!$alt_style_d){
					$bg_d .= 'background:'.(!empty($value['bgcolor_discount']) ? $value['bgcolor_discount'] : "").";";		
				}
				
				$color_d = '';
				$color_d .= (!empty($value['discount_color'])  ? 'color:'.$value['discount_color']. ';' : "");
				$color_d .= (!empty($value['bgcolor_discount']) ? 'border-color:' .esc_attr($value['bgcolor_discount']). ';' : '');

				

				$data_attribute_d = "";
				$data_attribute_d = (!empty($value['discount_color']) ? ' data-based-color="'.$value['discount_color'].'"' : "");

				$data_attribute_d .= (!empty($value['discount_color']) && empty($alt_style_d) ? ' data-attr-color="'.(!empty($value['font_color_discount']) ? $value['font_color_discount'] : '').'"'.' data-standart-color="'.(!empty($value['discount_color']) ? $value['discount_color'] : "#fff").'"' : "");

				$data_attribute_d .= (!empty($alt_style_d) ? ' data-attr-color="'.(!empty($value['font_color_discount']) ? $value['font_color_discount'] : "").'"'.' data-attr-bgcolor="'.(!empty($value['bgcolor_discount']) ? $value['bgcolor_discount'] : "").'"'. ' data-attr-border-color="'.(!empty($value['bgcolor_discount']) ? $value['bgcolor_discount'] : "").'"' : "");



				/* Standart Price Button */			
				$bg = '';
				$el_atts = '';


				$alt_style = (!empty($value['price_btn_alt_style']) ? $value['price_btn_alt_style'] : "");
				$styles = cws_build_styles($value);
				if (!empty($styles['content'])) {
					if(!$alt_style){
						$bg .= $styles['content'];		
					}
				}

				$color = '';
				$color .= (!empty($value['price_btn_color'])  ? 'color:'.$value['price_btn_color']. ';' : "");
				$color .= (!empty($value['bgcolor']) ? 'border-color:' .esc_attr($value['bgcolor']). ';' : '');	

				$data_attribute = "";
				$data_attribute = (!empty($value['price_btn_color']) ? ' data-based-color="'.$value['price_btn_color'].'"' : "");

				$data_attribute .= (!empty($value['price_btn_color']) && empty($alt_style) ? ' data-attr-color="'.(!empty($value['price_btn_font_color']) ? $value['price_btn_font_color'] : '').'"'.' data-standart-color="'.(!empty($value['price_btn_color']) ? $value['price_btn_color'] : "#fff").'"' : "");

				$data_attribute .= (!empty($alt_style) ? ' data-attr-color="'.$value['price_btn_font_color'].'"'.' data-attr-bgcolor="'.(!empty($value['bgcolor']) ? $value['bgcolor'] : "").'"'. ' data-attr-border-color="'.(!empty($value['bgcolor']) ? $value['bgcolor'] : "").'"' : "");

				$out .= '<li>';
					$out .= !empty($value['add_icon']) ? '<i class="icon-pricing-lists '.esc_attr($value['icon']).'"></i>' : "";
					$out .= "<div class='txt-align'>";
						$out .= !empty($title_text) ?  "<div class='title-pricing-lists'>".$title_text."</div>" : "";
						$out .= !empty($desc) ?  "<div class='desc-pricing-lists'>".$desc."</div>" : "";			
					$out .= "</div>";

					$out .= "<div class='serv-button'>";
						
							$out .= "<div class='btn-container'>";					
								if($type_discount == 'button'){
									if(!empty($value['add_discount_price'])){
										if(isset($value['discount_price']) && !empty($value['discount_price'])){
											preg_match('/\p{Sc}/', $value['discount_price'], $matches_discount);
											if(!empty($matches_discount)){
												list($cur_discount) = $matches_discount;	
											}													
										}
										$out .= '<a href="'.$url_d.'" '.$data_attribute_d.' target="'.$new_window_d.'" style="'.$color_d.$bg_d.'" class="cws_button '.(!empty($value['add_hover_discount']) ? " add_hover" : "") .(!empty($value['alt_style_discount']) ? " alt-style" : "").'">';
											if(isset($cur_discount) && !empty($cur_discount)){
												$out .= !empty($value['discount_price']) ? str_replace($cur_discount,"<span class='cur-price-list'>".$cur_discount."</span>",$value['discount_price']) : "Button";
											}
											else{
												$out .= !empty($value['discount_price']) ? $value['discount_price'] : "Button";
											}
											
										$out .= '</a>';									
									}
								}
								else{
									$out .= "<span class='discount_price_txt'>".(!empty($value['discount_price']) ? $value['discount_price'] : "Button")."</span>";
								}
								if($type == 'button'){
									if(isset($value['price_btn']) && !empty($value['price_btn'])){
										preg_match('/\p{Sc}/', $value['price_btn'], $matches_btn);
										if(!empty($matches_btn)){
											list($cur) = $matches_btn;
										}
												
									}

									$out .= '<a href="'.$url.'" '.$data_attribute.' target="'.$new_window.'" style="'.$color.$bg.'" class="cws_button'.(!empty($value['price_btn_add_hover']) ? " add_hover" : "").(!empty($alt_style) ? " alt-style" : "").'">';
									if($cur){
										$out .= !empty($value['price_btn']) ? str_replace($cur,"<span class='cur-price-list'>".$cur."</span>",$value['price_btn']) : "Button";
									}
									else{
										$out .= !empty($value['price_btn']) ? $value['price_btn'] : "Button";
									}
									
									$out .= '</a>';
								}	
								else{
									$out .= "<span class='price_txt'>".(!empty($value['price_btn']) ? $value['price_btn'] : "Button")."</span>";
								}								

							$out .= "</div>";	
					$out .= "</div>";
				$out .= '</li>';
			}
		$out .= '</ul>';
	$out .= "</div>";
	$out .= "</div>";
	
	return $out;

}

/*-----------------------------------------------------------------------------------*/
/* Flaticon
/*-----------------------------------------------------------------------------------*/
function relish_flaticon ( $atts ) {
	$atts_default = array(
		'cws_icons' => '',
		'add_hover' => '',
		'alternative_style' => '',
		'size' => '4x',
		'align_element' => 'center',
		'url_icon' => '',
		'customize' => '',
		'customize_f' => '',	
		'paddings' => 0,
		"margins" => 0,
		'borderless' => '0',
		'alignment' => 'center',
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$el_atts_spacing = '';

	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_spacing .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_spacing .= $spacing['margins'];
	}


	$borderless = (isset($borderless) && !empty($borderless) ? "border" : "");

 	$el_hover = '';
 	if(isset($font_icon_hover_color) && !empty($font_icon_hover_color)){
		$el_hover .= (!empty($add_hover) ?  'color:'.$font_icon_hover_color . ' !important;' : '');
		if(isset($customize_bg_color) && !empty($customize_bg_color)){
			$el_hover .= (!empty($add_hover) ?  'box-shadow:inset 0 0 0 22px '.$customize_bg_color . ' !important;' : '');
			$el_hover .= (!empty($add_hover) ?  'border-color:'.$customize_bg_color . ' !important;' : '');
		}
	}

	$out = "";
	$out .= "<div class='cws-icon-container' ".(isset($alignment) && !empty($alignment) ? "style=text-align:". $alignment ."" : "").">";

	$alt = (isset($alternative_style) && !empty($alternative_style) ? true : false);
	$url_icon = (isset($url_icon) && !empty($url_icon)) ? true : false;
	$add_hover = (isset($add_hover) && !empty($add_hover)) ? true : false;

	$size = (isset($size) && !empty($size) ? $size : $atts_default['size']);

	$cws_icons = (isset($cws_icons) && !empty($cws_icons) ? $cws_icons : " fa fa-music");
	$attr_style = (isset($customize) && !empty($customize) ||  isset($customize_f) && !empty($customize_f) ? true : false);
	$el_atts = "";
	
	$el_atts .= (!empty($customize_bg_color) && empty($alt) ? 'background:'.$customize_bg_color . ';
		' : '');

	if(isset($customize_font_color) && !empty($customize_font_color)){
		$el_atts .= (!empty($customize_font_color) ? 'color:'.$customize_font_color . ';' : 'color:#fff;');
	}

	if(isset($customize_font_color_s) && !empty($customize_font_color_s)){
		$el_atts .= (!empty($customize_font_color_s) ? 'color:'.$customize_font_color_s . ';' : 'color:'.RELISH_COLOR.';');
	}

	if(isset($customize_bg_color) && !empty($customize_bg_color)){
		$el_atts .= (!empty($customize_bg_color) && empty($alt) ? 'box-shadow: 0 0 0 2px #fff inset, 0 0 0 5px '.$customize_bg_color . ';' : "");
	}
	if(isset($borderless) && !empty($borderless)){
		$el_atts .= (!empty($section_border_width) ? 'border-width:'.$section_border_width . 'px;' : '');
		$el_atts .= (!empty($section_border_style) ? 'border-style:'.$section_border_style . ';' : '');
		$el_atts .= (!empty($section_border_color) ? 'border-color:'.$section_border_color . ';' : '');
	}

	$data_attribute = (!empty($add_hover) ? " data-attr-color='".$font_icon_hover_color."'" : "");

	if(!empty($add_hover)){
		if(!empty($customize) || !empty($customize_f)){
			if(!empty($customize_font_color)){
				$data_attribute .= " data-standart-color='".$customize_font_color."'";
			}
			else if(!empty($customize_font_color_s)){
				$data_attribute .= " data-standart-color='".$customize_font_color_s."'";
			}
			else{
				$data_attribute .= " data-standart-color='#fff'";
			}
			if(!empty($customize_bg_color)){
				$data_attribute .= " data-standart-bgcolor='".$customize_bg_color."'";
			}
			else{
				$data_attribute .= " data-standart-bgcolor='".RELISH_COLOR."'";
			}
			
		}
		else if(empty($customize_font_color_s) && !empty($alt)){
			$data_attribute .= " data-standart-color='".RELISH_COLOR."'";
		}
		else{
			$data_attribute .= " data-standart-color='#fff'";
			$data_attribute .= " data-standart-bgcolor='".RELISH_COLOR."'";
		}
	}
	if(!empty($borderless)){
		if(!empty($section_border_width)){
			$data_attribute .= " data-border-width='".$section_border_width."'";
		}
	}

	$out .= "<div class='cws_fa_wrapper cws_icons_fa ".(!empty($add_hover) ? "add_hover" : "")." ".(!empty($alt) ? "alt " : "").(isset($add_hover) && !empty($add_hover) && isset($font_icon_hover_color) && !empty($font_icon_hover_color) ? "add_hover".substr($font_icon_hover_color, 1) : "")."' ".$data_attribute." style='".$el_atts_spacing."' >";
	$out .= (isset($url_icon) && !empty($url_icon)) ? "<a href='".(isset($url_cws_icon) && !empty($url_cws_icon) ? esc_url($url_cws_icon) : "#")."'>" : "";
	$out .= "<i class='cws_fa ".$cws_icons." fa-". $size." ".(!empty($alternative_style) ? "alt" : "")."".(!empty($borderless) ? " borderless" : "").(empty($customize) ? " no-customize" : '')."' style='".$el_atts."'></i>";
	$out .= (isset($url_icon) && !empty($url_icon)) ? "</a>" : "";
	$out .= "</div>";
		
	$out .= "</div>";
	
	return $out;

}

/*-----------------------------------------------------------------------------------*/
/* Progress Bar
/*-----------------------------------------------------------------------------------*/
function relish_progress ( $atts ) {
	$atts_default = array(
		'title' => esc_html__( 'Progress Bar', 'relish' ),
		'progress' => '50',
		'bgtype' => 'bgcolor',
		'customize' => '0',
		'bgcolor' => relish_get_option('theme-custom-color')

	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$title_text = (isset($title) && !empty($title) ? $title : $atts_default['title']['text']);

	$progress = (isset($progress) && !empty($progress) ? $progress : $atts_default['progress']);
	$progress_class = "progress";
	$out = "";
	
	$progress_styles = "";
	if(!empty($customize)){
		if($bgtype == 'bgcolor'){
			$progress_styles .= (!empty($bgcolor) ? 'background:'.$bgcolor . ';' : '');
		}
		else{

			$progress_styles .= sprintf('background:linear-gradient(%sdeg, %s 0%%,%s 100%%);', $gradient['orientation'], $gradient['s_color'], $gradient['e_color']);	
		}		
	}


	$color_styles = "";
	if(!empty($bgtype)){
		if($bgtype == 'bgcolor'){
			$color_styles .= (!empty($bgcolor) ? 'color:'.$bgcolor . ';' : '');
		}		
	}
	$el_atts_spacing = '';
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts_spacing .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts_spacing .= $spacing['margins'];
	}	


	$out .= "<div class='cws_progress_bar' ".(!empty($el_atts_spacing) ? "style='".$el_atts_spacing."'" : ""). ">";
		$out .= !empty( $title_text ) ? "<div class='pb_title'>$title_text <span class='indicator' ".(!empty($color_styles) ? "style='$color_styles'" : "")."> - $progress%</span></div>" : "";	
		
		$progress_styles .= (!empty($progress) ? 'width:'.$progress . '%;' : '');
		$progress_atts = '';
		$progress_atts .= " data-value='$progress'";
		$progress_atts .= !empty( $progress_class ) ? " class='$progress_class'" : "";
		$progress_atts .= !empty( $progress_styles ) ? " style='$progress_styles'" : "";

		$out .= "<div class='bar'><div " . trim( $progress_atts ) . "></div></div>";
	$out .= "</div>";
	return $out;


}

/*-----------------------------------------------------------------------------------*/
/* Embedded module
/*-----------------------------------------------------------------------------------*/

function relish_embed ( $atts ) {
	$atts_default = array(
		'title' => array (
			'text' =>  esc_html__( 'Our Video', 'relish' ),
			),
		'url' => 'https://youtube.com/watch?v=D2PPBRRh6_Q',
		'width' => 560,
		'height' => 315,
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$title = $atts['title']['text'];
	$out = '';
	global $wp_embed;	
	
	wp_enqueue_script( 'yt_player_api', 'https://www.youtube.com/player_api', array(), '1.0', true );
	$out .= "<div class='embedded-module'>";
	$out .= '<div class="ce_title module_title">'.esc_html( $title ).'</div>';

	$out .= wp_oembed_get($url, array('width'=>(!empty( $width ) && is_numeric( $width ) ? $width : ""), 'height' => (!empty( $height ) && is_numeric( $height ) ? $height : "")));
	$out .= "</div>";
	return $out;
}	


/*-----------------------------------------------------------------------------------*/
/* Milestone module
/*-----------------------------------------------------------------------------------*/

function relish_milestone($atts){
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Title', 'relish' ),
		),
		'mode' => 'circle',
		'iconless' => '0',
		'icon' => '',
		'number' => '950',
		'speed' => '',
		'custom_color_settings' => array (
			'font_color' => '',
			'border_color' => '',
		),
		'borderless' => '0',
		'bgcolor' => '',
		'bgimage' => '',
		'opacity' => '',
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	wp_enqueue_script ('odometer');

	$icon = (isset($icon) && !empty($icon) ? $icon : "");
	$number = (isset($number) && !empty($number) ? (int)$number : $atts_default['number']);
	$speed = (isset($speed) && !empty($speed) ? (int)$speed : '');
	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : $atts_default['title']['text']);

	$el_atts = "";
	$el_class = "";
	$el_styles = "";
	$el_styles_atts = "";
	$custom_color = "";
	$bg_user = "";
	$atts_user = "";

	$el_class = "cws_milestone";
	$el_class .= !empty($borderless) && $borderless == '1' ? " borderless" : "";

	$el_class .= (isset($mode) && !empty($mode) ? ' '.$mode : ' circle');

	$el_class .= (isset($icon) && !empty($icon) ? " icon-milestone" : "");
	$el_class .= !empty($iconless) && $iconless == '1' ? " iconless" : "";
	$el_class .= empty($icon) ? " iconless" : "";

	$img_src = "";
	$img_src = (isset($bgimage['row']) && !empty($bgimage['row']) ? $bgimage['row'] : "");


	$el_atts .= !empty( $el_class ) ? " class='$el_class'" : "";

	$el_styles .= (isset($img_src) && !empty($img_src) ? "background-image: url($img_src);" : "");
	
	$custom_color .= (isset($custom_color_settings['font_color']) && !empty($custom_color_settings['font_color']) ? "color: ".$custom_color_settings['font_color'].";" : "color:#fff;");
	$custom_color .= (isset($custom_color_settings['border_color']) && !empty($custom_color_settings['border_color']) ? "border-color: ".$custom_color_settings['border_color'].";" : "");

	$bg_user .= (isset($bgcolor) && !empty($bgcolor) ? "background-color: ".$bgcolor.";" : "");
	$bg_user .= (isset($opacity) && !empty($opacity) ?  "opacity:" . (float)$opacity / 100 . ";" : "");

	$el_styles_atts .= !empty( $el_styles ) ? " style='$el_styles'" : "";

	$el_atts .= !empty( $custom_color ) ? " style='$custom_color'" : "";
	$atts_user .= !empty( $bg_user ) ? " style='$bg_user'" : "";

	$out = "";

	$out .= "<span class='milestone_icon'><i class='$icon'></i></span>";
	$out .= "<span class='milestone_number'" . ( !empty( $speed ) ? " data-speed='$speed'" : "" ) . ">$number</span>";
	$out .= "<span class='title-milestone'>".$title_text."</span>";
	
	$out = "<div" . ( !empty( $el_atts ) ? $el_atts : '' ) . "><span ". ( !empty( $el_styles_atts ) ? $el_styles_atts : '' ) .">$out<span class='user-bg' " . ( !empty( $atts_user ) ? $atts_user : '' ) . "></span></span></div>";
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Callout module
/*-----------------------------------------------------------------------------------*/
function relish_callout_renderer ( $atts, $content ) {
	$atts_default = array(
		'title' => 'Callout',
		'sub_title' =>  esc_html__( 'You are Happy, Beautiful and Healthy!', 'relish' ),
		'button_mode' => 'Button',
		'c_btn_href' => '#',
		'c_btn_text' => 'Purchase Now',
		'border_radius' => '3',
		'bg_image_c' => '',
		'iconfa' => '',
		'custom_colors' => '0',
		'fill_type' => 'color',
		'fill_color' => RELISH_COLOR,
		'font_color' =>  RELISH_COLOR,
		'btn_font_color' => '#fff'
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	$out = "";

	$section_atts = "";
	$fill_attr = "";
	$section_class = "cws_callout";
	$section_styles = "";
	$fill_styles = "";
	$fill_color = "";



	$font_color = isset($font_color) && !empty($font_color) ? esc_attr( $font_color ) : $atts_default['font_color']; 
	$border_radius = isset($border_radius) ? $border_radius : $atts_default['border_radius'];


	$fill_color = !empty($atts['fill_color']) ? esc_attr($atts['fill_color']) : $atts_default['fill_color'];

	$btn_font_color = isset($btn_font_color) && !empty($btn_font_color) ? esc_url( $btn_font_color ) : $atts_default['btn_font_color']; 

	$c_btn_href =  isset($c_btn_href) && !empty($c_btn_href) ? esc_url( $c_btn_href ) : "";
	
	$c_btn_text =isset($c_btn_text) && !empty($c_btn_text) ? esc_html( $c_btn_text ) : "";
	$title = esc_html( $title );

	$sub_title = isset($sub_title) && !empty($sub_title) ? esc_html( $sub_title ) : "";

	if ( !empty($custom_colors) ) {
		$section_class .= " customized";
		$section_styles .= "color:$font_color;";
		if ( $fill_type == 'color' ) {

			$fill_styles .= "background-color:$fill_color;";
		}
		else if ( $fill_type == 'image' ) {
			$fill_styles .=  "background-image:url(".$bgimage['row'].");background-size:cover";
		}
		$section_styles .= "border-color:$font_color;";
		$section_styles .= "border-radius:$border_radius"."px;";
	}

	$section_atts .= !empty( $section_class ) ? " class='$section_class'" : "";
	$section_atts .= !empty( $section_styles ) ? " data-atts-color='$font_color'" : "";
	if(!empty($section_styles)){
		if ( !empty($btn_font_color) ) {
			$section_atts .= " data-atts-bg='$btn_font_color'";
		}
	}

	$section_atts .= !empty( $section_styles ) ? " style='$section_styles'" : "";
	$fill_attr .= !empty($fill_styles) ? " style='$fill_styles'" : "";

	ob_start();
	echo !empty( $title ) ? "<div class='callout_title'" . ( !empty($custom_colors) ? " style='color:$font_color;'" : "" ) . ">$title</div>" : "";
	echo !empty( $sub_title ) ? "<div class='callout_sub_title'" . ( !empty($custom_colors) ? " style='color:$font_color;'" : "" ) . ">$sub_title</div>" : "";
	echo !empty( $content ) ? "<div class='callout_text' ".(!empty($font_color) ? "style='color:$font_color'" : "").">" . wptexturize( do_shortcode( $content ) ) . "</div>" : "";

	$box1 = ob_get_clean();

	$out .= !empty( $box1 ) ? "<div class='content_section'>$box1</div>" : "";

	$out .= !empty( $c_btn_text ) ? "<div class='button_section'><a href='".esc_url($c_btn_href)."' class='cws_button' style='background-color:".$font_color.";border-color:".$font_color.";color:$btn_font_color'>$c_btn_text</a></div>" : "";
	$button_mode = (isset($button_mode) && !empty($button_mode) ? $button_mode : "");

	$out .= $button_mode == "banner"  ? "<div class='banner-section' style='color:$font_color'><a href='".esc_url($c_banner_href)."'><span class='banner-price'>$banner_price</span><span class='banner-title'>$banner_title</span><span class='banner-sub-title'>$banner_sub_title</span></a></div>" : "";

	$out .= "<div class='fill-callout'" . (!empty($fill_attr) ? $fill_attr : "") . " ></div>";

	$out = !empty( $out ) ? "<div" . ( !empty( $section_atts ) ? $section_atts : "" ) . ">$out</div>" : "";

	return $out;
} 

/*-----------------------------------------------------------------------------------*/
/* Divider module
/*-----------------------------------------------------------------------------------*/
function relish_shortcode_divider ( $atts ) {
	$atts_default = array(
		'type' => 'Long',
		'add_separate' => '0',
		'height_divider' => '5',
		'color_divider' => RELISH_COLOR,
		'margin_top_divider' => '10',
		'margin_bottom_divider' => '10'
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);
	
	$divi_class = '';
	$divi_css = '';
	$divi_wrapp_class = '';

	$divi_style = esc_attr( $type );
	$color = !empty($color_divider) ? esc_attr( $color_divider ) : "";
	$margin_top =  isset($margin_top_divider) && !empty($margin_top_divider) ? esc_attr( $margin_top_divider ) : "";
	$margin_bottom = isset($margin_bottom_divider) && !empty($margin_bottom_divider) ? esc_attr( $margin_bottom_divider ) : "";	
	$add_separate = isset($add_separate) && !empty($add_separate) ? $add_separate : "";

	$height = !empty($height_divider) ? esc_attr( $height_divider ) : '';
	$width = !empty($width_divider) ? esc_attr( $width_divider ) : '';

	$divi_css .= $divi_style == 'custom' && isset($divi_style) ? 'width:'.$width.'px;' : '';
	$divi_css .= empty($add_separate) ? (!empty($height_divider) ? "height:".esc_attr( $height_divider ).'px;' : "") : '';	



	$divi_class .= 'separator-container';
	$divi_class .= $divi_style != 'custom' ? ' ' . $divi_style : '';
	$divi_class .= !empty($add_separate) ? " add_separate" : "";
	$divi_class .= $divi_style == 'custom' ? " custom" : "";

	$divi_attr = " class='".trim($divi_class)."' style='".(!empty($divi_css) ? $divi_css : "")."'";

	$result = '';
	$size = isset($size) && !empty($size) ? $size : "3x";
	$icon_color = isset($color_icon) && !empty($color_icon) ? $color_icon : RELISH_COLOR;
	if (isset($iconimg) && !empty( $iconimg )) {
		$thumb_obj = cws_thumb( $iconimg['row'],array( 'width' => 40, 'height' => 40 ), false );
		$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
		$img_src = $thumb_path_hdpi;

		$result = '<img ' . $img_src . " class='icon' alt />";
	} 
	if(isset($iconfa) && !empty($iconfa)){
		$result = "<span class='".$iconfa." fa-". $size."' style='color:".$icon_color."'></span>"; 
	}

return '<div class="separator-wrapper">	<div '.$divi_attr.'>
		<div class="separator-container-inner">
			<div class="separator-container-left-line" style="'.(isset($height) && !empty($height) ? "height:".$height."px;margin-top:-".($height/2)."px;" : '').(isset($color) && !empty($color) ? "background-color:".$color.";" : '').'"></div>
				'.(!empty($result) ? $result : "").'
			<div class="separator-container-right-line" style="'.(isset($height) && !empty($height) ? "height:".$height."px;margin-top:-".($height/2)."px;" : '').(isset($color) && !empty($color) ? "background-color:".$color.";" : '').'"></div>
		</div>
	</div></div>';
}



/*-----------------------------------------------------------------------------------*/
/* Accordion module
/*-----------------------------------------------------------------------------------*/
function relish_accs_renderer ( $atts, $content ) {

	extract( shortcode_atts( array(
		'accordion' => '0',
		'items' => 2
	), $atts));


	$out = "";

	$title_text = (isset($title_acc) && !empty($title_acc) ? $title_acc : "");
	$accordion_type  = (isset($atts['layout']) && !empty($atts['layout']) ? $atts['layout'] : "accordion");

	$out .= '<div class="ce_title">'.$title_text.'</div>';
	if ( (int)$items > 0 ) {
		$section_class = "cws_ce_content ce_container_accs";
		$section_class .= !empty($accordion_type) ? " ce_".$accordion_type : "";
		
		$out .= "<div class='$section_class'>" . do_shortcode( $content ) . "</div>";
	}

	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* TABS module
/*-----------------------------------------------------------------------------------*/
function relish_tabs_renderer ( $atts, $content) {
	extract( shortcode_atts( array(
		'vertical' => '0'
	), $atts));

	$out = "";

	$vertical = (isset($atts['layout']) && !empty($atts['layout']) ? $atts['layout'] : "");
	if(!empty($vertical)){
		if($vertical == 'vertical'){
			$vertical = 'vertical';
		}
	}


	$section_class = "cws_ce_content ce_tabs";
	$section_class .= $vertical ? " ".$vertical : "";
	$GLOBALS['cws_tabs_currently_rendered'] = array();
	do_shortcode( $content );
	$tab_items = $GLOBALS['cws_tabs_currently_rendered'];

	unset( $GLOBALS['cws_tabs_currently_rendered'] );

	if ( !empty( $tab_items ) ) {
		$out .= "<div class='$section_class'>";
		$out .= "<div class='tabs" . ( !$vertical ? " clearfix" : "" ) . "'>";
		foreach ( $tab_items as $tab_item ) {
			$tab_atts = '';
			$tab_class = 'tab';
			if(!empty($tab_item['atts'])) {


				$json_decode_tab_item = json_decode($tab_item['atts'],true);
				$tab_item['title'] = $json_decode_tab_item['title'];

				if(!empty($json_decode_tab_item['icontype'])){
					$tab_item['iconfa'] = $json_decode_tab_item['icontype'];
				}
						
				if(!empty($json_decode_tab_item['iconfa'])){
					$tab_item['icon'] = $json_decode_tab_item['iconfa'];
				}	

				if (has_filter('cwsfe_row_atts')) {
					$tab_atts = apply_filters('cwsfe_row_atts', $tab_item['atts']);
				}
				$tab_class .= '1' === $json_decode_tab_item['active'] ? ' active' : '';
			}

			$out .= "<div class='$tab_class' role='tab'". (!empty($tab_atts) ? $tab_atts : '') ." tabindex='" . $tab_item['tabindex'] . "'>";

				$tab_item = (array)$tab_item;

				$img_result = '';

				$width_img = "";
				$height_img = "";
				$width_img = isset($tab_item['ti']->width_img) && !empty($tab_item['ti']->width_img) ? (int) $tab_item['ti']->width_img : 30;
				$height_img = isset($tab_item['ti']->height_img) && !empty($tab_item['ti']->height_img) ? (int) $tab_item['ti']->height_img : 30;


				if (isset($tab_item['ti']->iconimg->row) && !empty( $tab_item['ti']->iconimg->row)){
					$thumb_obj = cws_thumb( $tab_item['ti']->iconimg->row,array( 'width' => $width_img, 'height' => $height_img, 'crop' => true ), false );
					$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
					$img_src = $thumb_path_hdpi;
					$img_result = '<img ' . $img_src . " class='icon' alt='' />";
				} 
				
				$out .='<span class="tabs-item">' .(isset($tab_item['ti']->iconfa) && !empty($tab_item['ti']->iconfa) ? "<span class='".$tab_item['ti']->iconfa." fa-". (!empty($tab_item['ti']->size) ? $tab_item['ti']->size : "2x")."'></span>" : "").(!empty($img_result) ? "<span class='img-tabs'>".$img_result."</span>" : "")."<span>".$tab_item['title'].
				"</span></span>";

				$out .= '</div>';

		}
		$out .= "</div>";
		$out .= "<div class='tab_sections'>";

		foreach ( $tab_items as $tab_item_content ) {
			if(!empty($tab_item_content['atts'])) {
				$json_decode_tab_item = json_decode($tab_item_content['atts'],true);
			}
			$out .= "<div class='tab_section' role='tabpanel' tabindex='" . $tab_item_content['tabindex'] . "'" . ( !isset( $json_decode_tab_item['active'] ) || '1' === $json_decode_tab_item['active'] ? "" : " style='display:none;'" ) . ">";
			$out .= isset( $tab_item_content['content'] ) ? do_shortcode($tab_item_content['content']) : "";
			$out .= "</div>";
		}

		$out .= "</div>";
		$out .= "</div>";
	}
	return $out;

}

/*-----------------------------------------------------------------------------------*/
/* Blog module
/*-----------------------------------------------------------------------------------*/
function relish_shortcode_blog( $atts = array() ) {
	$atts_default = array(
		'title' => array (
			'text' => esc_html__( 'Blog', 'relish' ),
		),
		'columns' => 'one',
		'items_per_page' => '1',
		'display' => 'grid',
		'custom_layout' => '0',
		'dis_meta_info' => '0',
		'dis_pagination' => '0',
		'user_pagination' => '0'
	);
	if (empty($atts)) {
		$atts = $atts_default;
	}
	$items = array();
	extract($atts);

	/*
	Carousel
	*/
	$carousel_init = (isset($display) && !empty($display) && $display == 'carousel' ? "cws_sc_carousel" : "");
	$autoplay_carousel = (isset($slider['autoplay']) && !empty($slider['autoplay']) ? true : false);
	$pagination_carousel = (isset($slider['pagination']) && !empty($slider['pagination']) ? " bullets_nav" : false);
	$navigation_carousel = (isset($slider['navigation']) && !empty($slider['navigation']) ? true : false);

	$filter_columns = "";
	if(isset($columns) && !empty($columns)){
		switch ($columns) {
			case 'one':
				$filter_columns = "1";
				break;
			case 'two':
				$filter_columns = "2";
				break;
			case 'three':
				$filter_columns = "3";
				break;
			case 'four':
				$filter_columns = "4";
				break;
		}		
	}
	else{
		$filter_columns = "1";
	}
	/*
	\Carousel
	*/

	$title_text = (isset($title['text']) && !empty($title['text']) ? $title['text'] : "");

	$out = "";

	$p_id = get_queried_object_id();
	if(isset($categories)){
		$categories = explode( ',', $categories );
		$categories = relish_filter_by_empty( $categories );
	}

	$disable_meta = (isset($dis_meta_info) && !empty( $dis_meta_info) ?  $dis_meta_info : 0);
	$post_text_length = (isset($post_text_length) && !empty( $post_text_length) ?  $post_text_length : false);
	$button_name = (isset($button_name) && !empty( $button_name) ?  $button_name : false);
	$items_per_page = (isset($items_per_page) && !empty($items_per_page) ? (int) $items_per_page : 1);
	$custom_layout = (isset($custom_layout) && !empty($custom_layout) ? $custom_layout : "0");

	$paged = !empty($_POST['paged']) ? (int)$_POST['paged'] : (!empty($_GET['paged']) ? (int)$_GET['paged'] : ( get_query_var("paged") ? get_query_var("paged") : 1 ) );

	$column_style = ($filter_columns >= '2');
	$query_args = array(
		'post_type' => 'post',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'posts_per_page' => $items_per_page,
		'this_shortcode' => true,
		'column_style' => $filter_columns,
		'custom_layout' => (int) $custom_layout,
		'post_text_length' => (int) $post_text_length,
		'button_name' => $button_name,
		'hide_meta' => $disable_meta,
		'column_count' => (int) $filter_columns,
	);

	if(isset($dis_pagination) && !empty($dis_pagination)){
		$query_args['paged'] = $paged;
	}
	
	if ( !empty( $categories ) ) {
		$categories[0] = str_replace("null", "", $categories[0]);
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $categories
			)
		);
	}

	/*
	Spacings
	*/
	$el_atts = "";
	$spacing = cws_spacing_styles($atts);
	if (!empty($spacing['paddings'])) {
		$el_atts .= $spacing['paddings'];
	}
	if (!empty($spacing['margins'])) {
		$el_atts .= $spacing['margins'];
	}


	/*
	\Spacings
	*/
	$q = new WP_Query( $query_args );
	if ( $q->have_posts() ) {
		$section_class = "news";
		if(isset($dis_pagination) && !empty($dis_pagination) && ($user_pagination == "standard_with_ajax" || $user_pagination == "load_more")){
			$section_class .= " shortcode-blog-wrapper";
		}
		
		$section_class .= $filter_columns == '1' ? " news-large" : " news-pinterest";
		$grid_class = "grid";
		$grid_class .= $filter_columns != '1' ? " grid-$filter_columns" : "";
		$grid_class .= $carousel_init ? " news_carousel" : " isotope";

		if ($carousel_init) {
			wp_enqueue_script ('owl_carousel');
		} elseif ($filter_columns != '1') {
			wp_enqueue_script ('isotope');
		}

		$column_style = $filter_columns >= '2' ? true : false;

		$new_blogtype = $filter_columns == '1' ? 'large' : $filter_columns;
		$old_blogtype = relish_get_page_meta_var( array( 'blog', 'blogtype' ) );
		if ( !( is_bool( $old_blogtype ) && !$old_blogtype ) ) relish_set_page_meta_var( array( 'blog', 'blogtype' ), $new_blogtype );
		
		$data_attr = "";
			if(isset($display) && !empty($display)){
				if($display == "carousel"){
					$data_attr =  "data-autoplay='".esc_attr($autoplay_carousel)."'";
					$data_attr .= " data-nav='".($navigation_carousel ? esc_attr($navigation_carousel) : '')."'";
				}
			}
		wp_enqueue_script ('isotope');
		ob_start();

			echo "<section class='".$section_class.$pagination_carousel."'  data-columns='".($filter_columns ? esc_attr($filter_columns) : '5')."' $data_attr  ".(!empty($el_atts) ? "style='".$el_atts."'" : "").">";
				echo !empty($title_text) ? '<div class="ce_title module_title">'.$title_text.'</div>' : "";
				echo !empty( $header_content ) ? "<div class='cws_blog_header'>$header_content</div>" : "";
				echo "<div class='cws_wrapper".(isset($dis_pagination) && !empty($dis_pagination) && ($user_pagination == "standard_with_ajax" || $user_pagination == "load_more") ? " shortcode-blog" : '')."'>";
					echo "<div class='".$grid_class."'>";
						relish_blog_output( $q );
						
						$ajax = isset( $_POST['ajax'] ) ? (bool)$_POST['ajax'] : false;
						$paged_var = get_query_var( 'paged' );
						$paged = $ajax && isset( $_POST['paged'] ) ? $_POST['paged'] : ( $paged_var ? $paged_var : 1 );
						$max_paged = ceil( $q->found_posts / $items_per_page );
						$template = 'content-blog';

					echo "</div>";


				echo "</div>";	
				if(isset($dis_pagination) && !empty($dis_pagination)){
				if($user_pagination == "standard_with_ajax" || $user_pagination == "load_more"){				
				echo "<input type='hidden' class='cws_blog_ajax_data' value='" . esc_attr(
							json_encode(
								array(
									'p_id' => $p_id,
									'items_per_page' => $items_per_page,
									'paged' => $paged,
									'columns' => $filter_columns,
									'categories' => !empty( $categories ) ? $categories : 0,
									'custom_layout' => (int) $custom_layout,
									'dis_meta_info' => !empty($dis_meta_info) ? $dis_meta_info : 0,
									'disable_meta' => !empty( $disable_meta) ?  $disable_meta : 0,
									'post_text_length' => $post_text_length,
									'button_name' => $button_name,
									'pagination_style' => $user_pagination
									)
								)
							) . "' />";				
				}
							 //Plugin intall Actions
							
							add_action( 'wp_loaded', 'plugin_get_post' );
					
							if(isset($user_pagination) && $user_pagination == 'standard_with_ajax'){
								echo relish_pagination( $paged, $max_paged );
							}
							elseif (isset($user_pagination) && $user_pagination == 'standard') {
								echo relish_pagination( $paged, $max_paged );
							}
							else{
								echo relish_pagination( 1, $max_paged, $user_pagination = 'load_more');
							}

							}
					
			echo "</section>";
		$out .= ob_get_clean(); 	


		if ( !( is_bool( $old_blogtype ) && !$old_blogtype ) ) relish_set_page_meta_var( array( 'blog', 'blogtype' ), $old_blogtype );

	}						
	return $out;

}


/*-----------------------------------------------------------------------------------*/
/* Twitter Widget
/*-----------------------------------------------------------------------------------*/
function relish_twitter_renderer ( $atts, $content = "" ) {
	extract( shortcode_atts( array(
		'in_widget' => false,
		'title' => '',
		'centertitle' => '0',
		'items' => get_option( 'posts_per_page' ),
		'visible' => get_option( 'posts_per_page' ),
		'showdate' => '0',
		'alignment' => 'left'
	), $atts));
	$out = "";
	$tw_username = relish_get_option( "tw-username" );
	if ( !is_numeric( $items ) || !is_numeric( $visible ) ) return $out;
	$tweets = relish_getTweets( (int)$items );
	if ( is_string( $tweets ) ) {
		$out .= do_shortcode( "[cws_sc_msg_box title='" . esc_html__( 'Twitter responds:', 'relish' ) . "' text='$tweets' is_closable='1'][/cws_sc_msg_box]" );
	}
	else if ( is_array( $tweets ) && isset($tweets['error']) ){
		echo esc_html($tweets['error']);
	}
	else if ( is_array( $tweets ) ) {
		$use_carousel = count( $tweets ) > $visible;
		$section_class = "cws_tweets";
		$section_class .= $use_carousel ? " tweets_carousel" : "";
		$section_class .= $use_carousel && empty( $title ) ? " paginated" : "";
		$out .= !empty( $title ) ? RELISH_BEFORE_CE_TITLE . "<div" . ( $centertitle ? " style='text-align:center;'" : "" ) . ">$title</div>" . RELISH_AFTER_CE_TITLE : "";
		if ( $use_carousel && !$in_widget ) {
			$out .= "<div class='tweets_carousel_header'>";
				$out .= "<a href='http://twitter.com/$tw_username' class='follow_us fa fa-twitter' target='_blank'></a>";
			$out .= "</div>";
		}
		$out .= "<div class='$section_class'>";
			$out .= "<div class='cws_wrapper'>";
				$carousel_item_closed = false;
				for ( $i=0; $i<count( $tweets ); $i++ ) {
					$tweet = $tweets[$i];
					if ( $use_carousel && ( $i == 0 || $carousel_item_closed ) ) {
						wp_enqueue_script ('owl_carousel');
						$out .= "<div class='item'>";
						$carousel_item_closed = false;
					}
					$tweet_text = isset( $tweet['text'] ) ? $tweet['text'] : "";
					$tweet_entitties = isset( $tweet['entities'] ) ? $tweet['entities'] : array();
					$tweet_urls = isset( $tweet_entitties['urls'] ) && is_array( $tweet_entitties['urls'] ) ? $tweet_entitties['urls'] : array();
					foreach ( $tweet_urls as $tweet_url ) {
						$display_url = isset( $tweet_url['display_url'] ) ? $tweet_url['display_url'] : "";
						$received_url = isset( $tweet_url['url'] ) ? $tweet_url['url'] : "";
						$html_url = "<a href='$received_url'>$display_url</a>";
						$tweet_text = substr_replace( $tweet_text, $html_url, strpos( $tweet_text, $received_url ), strlen( $received_url ) );
					}
					$item_content = "";
					$item_content .= !empty( $tweet_text ) ? "<div class='tweet_content'>$tweet_text</div>" : "";
					if ( $showdate ) {
						$tweet_date = isset( $tweet['created_at'] ) ? $tweet['created_at'] : "";
						$tweet_date_formatted = time_elapsed_string( date( "U", strtotime( $tweet_date ) ) );
						$item_content .= "<div class='tweet_date'>$tweet_date_formatted</div>";
					}
					$out .= !empty( $item_content ) ? "<div class='cws_tweet".(isset($alignment) && !empty($alignment) ? ' align-content-'.$alignment : ' align-content-left"')."'".(isset($alignment) && !empty($alignment) ? ' style="text-align:'.$alignment.'"' : ' style="text-align:left"').">$item_content</div>" : "";
					$temp1 = ( $i + 1 ) / (int)$visible;
					if ( $use_carousel && ( $temp1 - floor( $temp1 ) == 0 || $i == count( $tweets ) - 1 ) ) {
						$out .= "</div>";
						$carousel_item_closed = true;
					}
				}
			$out .= "</div>";
		$out .= "</div>";
	}
	return $out;
}


/*-----------------------------------------------------------------------------------*/
/* Inner Grif module
/*-----------------------------------------------------------------------------------*/
function relish_igrid_renderer ( $args, $content ) {
	$atts_default = array(
			'atts' => '{"_cols":"1"}',
		);
	extract( shortcode_atts($atts_default , $args));
	if (!$args) {
		$args = $atts_default;
	}
	$grid_class = '';
	if (has_filter('cwsfe_igrid_class')) {
		$grid_class = apply_filters('cwsfe_igrid_class', '');
	}
	$row_atts = '';
	if (has_filter('cwsfe_row_atts')) {
		$row_atts = apply_filters('cwsfe_row_atts', $atts); // !!!
	}
	$out = '<div class="igrid-container '. $grid_class . '"'. $row_atts .'>';
	$out .= do_shortcode($content);
	$out .= "</div>";
	return $out;
}

/*-----------------------------------------------------------------------------------*/
/* Twitter module
/*-----------------------------------------------------------------------------------*/

function relish_twitter ( $atts, $content = "" ) {
	extract( shortcode_atts( array(
		'in_widget' => false,
		'title' => 'Twitter',
		'centertitle' => '0',
		'items' => '10',
		'visible' => '10',
		'showdate' => '0'
		), $atts));
	$out = "";
	
	$tw_username = relish_get_option( "tw-username" );
	if ( !is_numeric( $items ) || !is_numeric( $visible ) ) return 1;

	$tweets = relish_getTweets( (int)$items );
	if(isset($tweets) && !in_array('Missing Consumer Key - Check Settings',$tweets)){
		if ( is_string( $tweets ) ) {
			$out .= do_shortcode( "[cws_sc_msg_box title='" . esc_html__( 'Twitter responds:', 'relish' ) . "' text='$tweets' is_closable='1'][/cws_sc_msg_box]" );
		}
		else if ( is_array( $tweets ) && isset($tweets['error']) ){
			echo esc_html($tweets['error']);
		}
		else if ( is_array( $tweets ) ) {
			$use_carousel = count( $tweets ) > $visible;
			$section_class = "cws_tweets dfgfgfg";
			$section_class .= $use_carousel ? " tweets_carousel" : " module_msg_tweets";
			$section_class .= $use_carousel && empty( $title ) ? " paginated" : "";
			$out .= !empty( $title ) ? "<div class='ce_title module_title'>$title</div>" : "";
			if ( $use_carousel && !$in_widget ) {
				$out .= "<div class='tweets_carousel_header'>";
				$out .= "<a href='http://twitter.com/$tw_username' class='follow_us fa fa-twitter' target='_blank'></a>";
				$out .= "</div>";
			}
			$out .= "<div class='$section_class'>";
			$out .= "<div class='cws_wrapper'>";
			$carousel_item_closed = false;
			for ( $i=0; $i<count( $tweets ); $i++ ) {
				$tweet = $tweets[$i];
				if ( $use_carousel && ( $i == 0 || $carousel_item_closed ) ) {
					wp_enqueue_script ('owl_carousel');
					$out .= "<div class='item'>";
					$carousel_item_closed = false;
				}
				$tweet_text = isset( $tweet['text'] ) ? $tweet['text'] : "";
				$tweet_entitties = isset( $tweet['entities'] ) ? $tweet['entities'] : array();
				$tweet_urls = isset( $tweet_entitties['urls'] ) && is_array( $tweet_entitties['urls'] ) ? $tweet_entitties['urls'] : array();
				foreach ( $tweet_urls as $tweet_url ) {
					$display_url = isset( $tweet_url['display_url'] ) ? $tweet_url['display_url'] : "";
					$received_url = isset( $tweet_url['url'] ) ? $tweet_url['url'] : "";
					$html_url = "<a href='$received_url'>$display_url</a>";
					$tweet_text = substr_replace( $tweet_text, $html_url, strpos( $tweet_text, $received_url ), strlen( $received_url ) );
				}
				$item_content = "";
				$item_content .= !empty( $tweet_text ) ? "<div class='tweet_content'>$tweet_text</div>" : "";
				if ( $showdate ) {
					$tweet_date = isset( $tweet['created_at'] ) ? $tweet['created_at'] : "";
					$tweet_date_formatted = time_elapsed_string( date( "U", strtotime( $tweet_date ) ) );
					$item_content .= "<div class='tweet_date'>$tweet_date_formatted</div>";
				}
				$out .= !empty( $item_content ) ? "<div class='cws_tweet'>$item_content</div>" : "";
				$temp1 = ( $i + 1 ) / (int)$visible;
				if ( $use_carousel && ( $temp1 - floor( $temp1 ) == 0 || $i == count( $tweets ) - 1 ) ) {
					$out .= "</div>";
					$carousel_item_closed = true;
				}
			}
			$out .= "</div>";
			$out .= "</div>";
		}
		return $out;					
	}
	else{
		$out = "<div class='twitter-respond'>".do_shortcode( "[cws_sc_msg_box type='info' title='" . esc_html__( "Missing Consumer Key - Check Settings", 'relish' ) . "'][/cws_sc_msg_box]" )."</div>";
		return $out;
	}

}




function relish_shortcode_msg_box ( $atts, $content ) {
	extract( shortcode_atts( array(
		'type' => 'info',
		'title' => '',
		'text' => '',
		'is_closable' => '0',
		'customize' => '0',
		'custom_options' => array()
	), $atts));

	$custom_options = relish_json_sc_attr_conversion ( $custom_options );
	$custom_options = is_object( $custom_options ) ? get_object_vars( $custom_options ) : array();
	$custom_options = isset( $custom_options['@'] ) ? $custom_options['@'] : new stdClass();
	$custom_color = isset( $custom_options->color ) ? $custom_options->color : '';
	$custom_bg_color = isset( $custom_options->bg_color ) ? $custom_options->bg_color : '';
	$custom_brd_color = isset( $custom_options->brd_color ) ? $custom_options->brd_color : '';
	$custom_icon = isset( $custom_options->icon ) ? $custom_options->icon : '';

	$out = "";
	$icon = $customize == '1' && !empty( $custom_icon ) ? $custom_icon : '';
	if ( empty( $icon ) ) {
		switch ($type){
			case 'info':
				$icon = 'cwsicon-info31';
				break;
			case 'warning':
				$icon = 'cwsicon-lightning24';
				break;
			case 'success':
				$icon = 'cwsicon-check-mark';
				break;
			case 'error':
				$icon = 'cwsicon-exclamation-mark1';
				break;
		}
	}

	ob_start();
		echo ! empty( $title ) ? '<div class="msg_box_title">'.esc_html( $title ).'</div>' : '';
		echo ! empty( $text ) ? '<div class="msg_box_text">'. $text .'</div>' : '';
	$content_box = ob_get_clean();

	$custom_styles = $customize == '1' && !empty( $custom_color ) ? "color:$custom_color;background-color:$custom_bg_color;border-color:$custom_brd_color" : "";
	$container_class = "cws_msg_box $type-box clearfix";
	$container_class .= $is_closable == '1' ? " closable" : "";

	if ( !empty( $content_box ) ) {
		$out .= "<div class='$container_class'" . ( !empty( $custom_styles ) ? " style='$custom_styles'" : "" ) . ">";
			$out .= "<div class='icon_section'><i class='$icon'></i></div>";
			$out .= "<div class='content_section'>$content_box</div>";
			$out .= $is_closable == '1' ? "<div class='cls_btn'></div>" : "";
		$out .= "</div>";
	}

	return $out;
}
add_cws_shortcode( 'cws_sc_msg_box', 'relish_shortcode_msg_box' );

?>
