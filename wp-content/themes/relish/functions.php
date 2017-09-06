<?php

# CONSTANTS

define('RELISH_URI', get_template_directory_uri());
define('RELISH_THEME_DIR', get_template_directory());
define('RELISH_BEFORE_CE_TITLE', '<div class="ce_title">');
define('RELISH_AFTER_CE_TITLE', '</div>');
define('RELISH_V_SEP', '<span class="v_sep"></span>');
define('RELISH_MB_PAGE_LAYOUT_KEY', 'cws_mb_post');
define('RELISH_COLOR', '#76c08a');
define('RELISH_FOOTER_COLOR', '#556359');
define('RELISH_SECONDARY_COLOR', '#465048');
define('RELISH_SLUG', 'relish');


# \CONSTANTS

# TEXT DOMAIN

load_theme_textdomain( 'relish', get_template_directory() .'/languages' );

# \TEXT DOMAIN

# REDUX FRAMEWORK

require_once (get_template_directory() . '/fw/config.php');

# \REDUX FRAMEWORKr

# INCLUDE MODULES

# \INCLUDE MODULES

# PAGEBUILDER
$cws_theme_funcs = new relish_Funcs();
// CWS PB settings
class relish_Funcs {
	public function __construct() {
		$this->init();
		$this->theme_options_customizer_init();
	}

	public function wp_title_filter ( $title_text ) {
		$site_name = get_bloginfo( 'name' );
		$site_tagline = get_bloginfo( 'description' );
		if ( is_home() ) {
			$title_text = $site_name . " | " . $site_tagline;
		}
		else{
			$title_text .= $site_name;
		}
		return $title_text;
	}

	# UPDATE THEME
	public function check_for_update($transient) {
		if (empty($transient->checked)) { return $transient; }

		$theme_pc = trim(relish_get_option('_theme_purchase_code'));
		if (empty($theme_pc)) {
			add_action( 'admin_notices', array($this, 'cws_an_purchase_code') );
		}

		$result = wp_remote_get('http://up.creaws.com/products-updater.php?pc=' . $theme_pc . '&tname=' . 'relish');
		if (!is_wp_error( $result ) ) {
			if (200 == $result['response']['code'] && 0 != strlen($result['body']) ) {
				$resp = json_decode($result['body'], true);
				$h = isset( $resp['h'] ) ? (float) $resp['h'] : 0;
				$theme = wp_get_theme(get_template());
				if ( version_compare( $theme->get('Version'), $resp['new_version'], '<' ) ) {
					$transient->response['relish'] = $resp;
				}
			}
			else{
				unset($transient->response['relish']);
			}
		}
		return $transient;
	}

	// an stands for admin notice
	public function cws_an_purchase_code() {
		$cws_theme = wp_get_theme();
		echo "<div class='update-nag'>" . $cws_theme->get('Name') . esc_html__(' theme notice: Please insert your Item Purchase Code in Theme Options to get the latest theme updates!', 'relish') ."</div>";
	}
	# \UPDATE THEME

	public function fix_shortcodes_autop($content){
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']'
		);

		$content = strtr($content, $array);
		return $content;
	}

	private function init() {
		include_once get_template_directory() . '/pbf.php';
		require_once get_template_directory() . '/core/plugins.php';

		// metaboxes
		require_once(get_template_directory() . '/core/cws_thumb.php');
		include_once(get_template_directory() . '/core/breadcrumbs.php');
		include_once(get_template_directory() . '/core/shortcodes.php');
		require_once(get_template_directory() . '/core/metaboxes.php' );

		set_transient('update_themes', 24*3600);

		add_action('after_setup_theme', array($this, 'cws_after_setup_theme') );
		add_filter('wp_title', array($this, 'wp_title_filter') );
		add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update') );
		add_action('admin_enqueue_scripts', array($this, 'cws_admin_init' ) );

		add_action('wp_enqueue_scripts', array($this, 'cws_theme_enqueue_scripts') );
		add_action('wp_enqueue_scripts', array($this, 'cws_theme_standart_script') );
		
		add_action('wp_enqueue_scripts', array($this, 'cws_theme_youtube_api_init') );
		add_action('wp_enqueue_scripts', array($this, 'cws_theme_enqueue_styles') );

		add_action('wp_enqueue_scripts', array($this, 'cws_register_fonts') ); // add fonts

		add_action('wp_enqueue_scripts', array($this, 'cws_enqueue_theme_stylesheet'), 999 );
		add_action('widgets_init', array($this, 'cws_widgets_init') );
		add_filter('body_class', array($this, 'cws_layout_class') );

		add_action('menu_font_hook', array($this, 'cws_menu_font_action') );
		add_action('header_font_hook', array($this, 'cws_header_font_action') );
		add_action('body_font_hook', array($this, 'cws_body_font_action') );
		add_action('rgb_hook', array($this, 'cws_rgb_color_shadow') );

		add_action('theme_color_hook', array($this, 'cws_theme_color_action') );
		add_action('theme_color_hook', array($this, 'cws_page_title_custom_color_action') );
		add_action('theme_gradient_hook', array($this, 'cws_theme_gradient_action') );
		add_filter('body_class', array($this, 'cws_gradients_body_class') );
		add_filter('cws_dbl_to_sngl_quotes', array($this, 'cws_dbl_to_sngl_quotes') );

		add_action('wp_enqueue_scripts', array($this, 'js_vars_init') );
		add_action('wp_enqueue_scripts', array($this, 'relish_js_vars_init') );
		add_action('wp', array($this, 'cws_page_meta_vars') );
		add_action('template_redirect', array($this, 'cws_ajax_redirect') );
		add_filter('excerpt_length', array($this, 'cws_custom_excerpt_length'), 999 );
		add_filter('embed_oembed_html', array($this, 'cws_oembed_wrapper'),10,3);
		add_filter('body_class', array($this, 'cws_loading_body_class') );

		add_action('wp_enqueue_scripts', array($this, 'cws_add_style') );

		add_filter( 'post_gallery', array($this, 'cws_gallery_output'), 10, 3);
		add_filter('wp_list_categories', array($this, 'cws_categories_postcount_filter'));
		add_filter('get_archives_link', array($this, 'cws_archive_postcount_filter'));
		add_filter('the_content', array($this, 'cws_cleanup_shortcode_fix'));



	}

	public function theme_options_customizer_init (){
	  if ( is_customize_preview() ) {
	   if ( isset( $_POST['wp_customize'] ) && $_POST['wp_customize'] == "on" ) {
	    if (strlen($_POST['customized']) > 10) {
	     global $cwsfw_settings;
					global $cwsfw_mb_settings;
	     $post_values = json_decode( stripslashes_deep( $_POST['customized'] ), true );
					if (isset($post_values['cwsfw_settings'])) {
	     $cwsfw_settings = $post_values['cwsfw_settings'];
	    }
					if (isset($post_values['cwsfw_mb_settings'])) {
						$cwsfw_mb_settings = $post_values['cwsfw_mb_settings'];
						$this->cws_page_meta_vars();
					}
				}
			}
	   }
	 }

	public function cws_theme_youtube_api_init (){
		?>
		<script type="text/javascript">
			// Loads the IFrame Player API code asynchronously.
				var tag = document.createElement("script");
				tag.src = "https://www.youtube.com/player_api";
				var firstScriptTag = document.getElementsByTagName("script")[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		</script>
		<?php
	}

	public function cws_dbl_to_sngl_quotes ( $content ) {
		return preg_replace( "|\"|", "'", $content );
	}

	public function cws_loading_body_class ( $classes ) {
			$classes[] = 'relish-new-layout';
			$show_loader = relish_get_option('show_loader');
			if($show_loader == false){
				$classes[] = 'no-loader';
			}
		return $classes;
	}

	public function cws_oembed_wrapper( $html, $url, $args ) {
		return !empty( $html ) ? "<div class='cws_oembed_wrapper'>$html</div>" : '';
	}

	public function cws_custom_excerpt_length( $length ) {
		return 1400;
	}

	public function cws_ajax_redirect() {
		$ajax = isset( $_POST['ajax'] ) ? (bool)$_POST['ajax'] : false;
		if ( $ajax ) {
			$template = isset( $_POST['template'] ) ? $_POST['template'] : '';
			if ( !empty( $template ) ) {
				if ( strpos( $template, '-' ) ) {
					$template_parts = explode( '-', $template );
					if ( count( $template_parts ) == 2 ) {
						get_template_part( $template_parts[0], $template_parts[1] );
					}
					else {
						return;
					}
				}	else {
					get_template_part( $template );
				}
				exit();
			}
		}
		return;
	}

	public function cws_page_meta_vars() {
		if ( is_page() ) {
			$pid = get_query_var('page_id');
			$pid = !empty($pid) ? $pid : get_queried_object_id();
			$post = get_post( $pid );
			$cws_stored_meta = cwsfw_get_post_meta( $pid, RELISH_MB_PAGE_LAYOUT_KEY );
			$cws_stored_meta = isset( $cws_stored_meta[0] ) && !empty( $cws_stored_meta[0] ) ? $cws_stored_meta[0] : array();
			$GLOBALS['relish_page_meta'] = array();
			$GLOBALS['relish_page_meta']['sb'] = relish_get_sidebars($pid);
			$GLOBALS['relish_page_meta']['is_blog'] = isset( $cws_stored_meta['is_blog'] ) ? $cws_stored_meta['is_blog'] === '1' : ( is_home() ? true : false );
			$GLOBALS['relish_page_meta']['blog'] = array( 'blogtype' => isset( $cws_stored_meta['blogtype'] ) ? $cws_stored_meta['blogtype'] : 'large',
				'cats' => isset( $cws_stored_meta['category'] ) ? implode( $cws_stored_meta['category'], ',' ) : ( is_home() ? relish_get_option('def-home-category') : '' ) );
			$GLOBALS['relish_page_meta']['footer'] = array( 'footer_sb_top' => '', 'footer_sb_bottom' => '' );
			if ( (!is_404()) && (!empty($post)) ) {
				if (isset( $cws_stored_meta['sb_foot_override'] ) && $cws_stored_meta['sb_foot_override'] === '1') {
					$GLOBALS['relish_page_meta']['footer']['footer_sb_top'] = isset( $cws_stored_meta['footer-sidebar-top'] ) ? $cws_stored_meta['footer-sidebar-top'] : '';
				} else {
					$GLOBALS['relish_page_meta']['footer']['footer_sb_top'] = relish_get_option('footer-sidebar-top');
				}
			} else {
				$GLOBALS['relish_page_meta']['footer']['footer_sb_top'] = relish_get_option('footer-sidebar-top');
			}
			$GLOBALS['relish_page_meta']['slider'] = array( 'slider_override' => '', 'slider_shortcode' => '' );
			$GLOBALS['relish_page_meta']['slider']['slider_override'] = isset($cws_stored_meta['sb_slider_override']) && $cws_stored_meta['sb_slider_override'] === '1' ? $cws_stored_meta['sb_slider_override'] : false;
			$GLOBALS['relish_page_meta']['slider']['slider_options'] = isset($cws_stored_meta['slider_shortcode']) && $cws_stored_meta['sb_slider_override'] === '1' ? $cws_stored_meta['slider_shortcode'] : '';
			return true;
		} else {
			return false;
		}
	}

	/************** JAVASCRIPT VARIABLES INIT **************/

	public function js_vars_init() {
		$is_user_logged = is_user_logged_in();
		$stick_menu = relish_get_option('menu-stick');
		$use_blur = relish_get_option('use_blur');
		$logged_var = $is_user_logged ? 'true' : 'false';
		?>
		<script type="text/javascript">
			var is_user_logged = <?php echo esc_js($logged_var)?>;
			var stick_menu = true;
			var use_blur = false;
		</script>
		<?php
	}

	public function relish_js_vars_init() {
		$is_user_logged = is_user_logged_in();
		$stick_menu = relish_get_option('menu-stick');
		$sticky_menu_mode = relish_get_option('stick-mode');
		$sticky_menu_mode = (!empty($sticky_menu_mode) ? $sticky_menu_mode : 'null');
		$use_blur = relish_get_option('use_blur');
		$logged_var = $is_user_logged ? 'true' : 'false';
		$sticky_sidebars = relish_get_option('sticky_sidebars');
		$sticky_sidebars = (!empty($sticky_sidebars) ? $sticky_sidebars : 'null');

		wp_add_inline_script('img_loaded', '
			var is_user_logged = '.esc_js($logged_var).';
			var stick_menu = true;
			var sticky_menu_mode = "'.esc_js($sticky_menu_mode).'";
			var sticky_sidebars = '.esc_js($sticky_sidebars).';
			var use_blur = false;
			');  
	}

	/************** \JAVASCRIPT VARIABLES INIT **************/

	/******************** TYPOGRAPHY ********************/
	// MENU FONT HOOK

	private function cws_print_font_css($font_array) {
		$out = '';
		foreach ($font_array as $style=>$v) {
			if ($style != 'font-weight' && $style != 'font-sub' && $style != 'font-type') {
				$out .= !empty($v) ? $style .':'.$v.';' : '';
			}
		}
		return $out;
	}

	private function cws_print_menu_font() {
		ob_start();
		do_action( 'menu_font_hook' );
		return ob_get_clean();
	}

	public function cws_menu_font_action() {
		$out = '';
		$font_array = relish_get_option('menu-font');
		if (isset($font_array)) {
			$out .= '.main-nav-container .menu-item a,
				.main-nav-container .menu-item .button_open,
				.mobile_nav .main-menu>.menu-item>a,
				.copyrights_area .copyrights,
				.mobile_menu_header{'. $this->cws_print_font_css($font_array) . '}';

			$out .= '.main-nav-container .menu-item a,
				.main-nav-container .menu-item .button_open,
				.mobile_menu_header
						{font-weight:'.($font_array['font-weight'][0] == 'regular' ? $font_array['font-weight'][0] = 400 : $font_array['font-weight'][0]) . '}';

			$out .= '.main-menu .search_menu{
						font-size : '. $font_array["font-size"] . ';
					}';
		}
		if (relish_get_option('show_header_outside_slider') === true) {

			$out .= '.header_wrapper_container .header_nav_part:not(.mobile_nav) .main-nav-container > .main-menu > .menu-item:not(.current-menu-ancestor):not(:hover):not(.current-menu-item) > a,
		.header_wrapper_container .site_header .search_menu{
						color : '. relish_get_option('header_outside_slider_font_color') . ';
					}';
		}
		echo preg_replace('/\s+/',' ', $out);
	}

	// \MENU FONT HOOK

	// HEADER FONT HOOK

	private function cws_print_header_font () {
		ob_start();
		do_action( 'header_font_hook' );
		return ob_get_clean();
	}

	public function cws_header_font_action () {
		$out = '';
		$font_array = relish_get_option('header-font');
		if (isset($font_array)) {
			$out .= '.ce_title,body .site-main main h1,body .site-main main h1.ce_title,body h2,body h3,body h4,body h5,body h6,
				.comments-area .comments_title.ce_title,
				.comments-area .comment-reply-title,
				#main.site-main .cws-widget .widget-title,
				.relish-new-layout .cws-widget .widget-title,
				h1.header_site_title,

				.woocommerce span.onsale, .woocommerce-page span.onsale,
				.woocommerce div[class^="post-"] h1.product_title.entry-title{'. $this->cws_print_font_css($font_array) . '}';
			
			$out .= '.cws_portfolio .desc_part, .style-offers-four .desc_part,.gallery-size-new-size .gallery-icon + dd .title-img,.gallery-size-new-size-medium .gallery-icon + dd .title-img,.gallery-size-new-size-small .gallery-icon + dd .title-img,.gallery-size-new-size .gallery-icon + dd .description-image,.gallery-size-new-size-medium .gallery-icon + dd .description-image,.gallery-size-new-size-small .gallery-icon + dd .description-image,.cws_flex_row.cws_equal_height > div form.wpcf7-form p label,.mobile_nav .mobile_menu_header,.testimonial figcaption,.pagination .page_links>*,.pagination_load_more .page_links a,.cws_portfolio_items .item .title_part,.cws_portfolio_filter.fw_filter a,.cws_ourteam:not(.single) .cws_ourteam_items .title_wrap>.positions,.cws_ourteam_items .ourteam_item_wrapper .ourteam_content + a,.news.news-pinterest .cws_button.large.cws_load_more,
				form.wpcf7-form > div:not(.wpcf7-response-output) p label,
				.pagination_load_more .page_links a,.title-milestone,.cws_milestone.iconless .title-milestone,.cws_progress_bar .pb_title,.cws_progress_bar .pb_title span,.dropcap-l:first-letter,.dropcap-g:first-letter,.pricing_table_column .title_section,.pricing_table_column .price_section .price_container,.cws_callout .callout_title,.cws_callout .callout_sub_title,.banner-section .banner-price,.banner-section  .banner-title,.banner-section  .banner-sub-title,.cws_portfolio_items article .square .post_info .title_part,.cws_button.icon-on.regular,.site-main .cws-widget .widget-title,.wrapper-circle-offers.no_hover.style-offers-four .title_part,.wrapper-circle .style-offers-two .text-information .title,.wrapper-circle .style-offers-three .text-information .title,.ih-item.circle .info h3,.ih-item .title_part,.wrapper-testimonials .cws_wrapper .author-testimonials,.wrapper-testimonials .cws_wrapper .author-testimonials a,.title-services,.gallery-icon + dd .title-img,.header_nav_part.mobile_nav .main-menu .switch-menu-mobile li.menu-item a,.cws_button,input[type="submit"],.comments-area .comment-respond .comment-form .submit,.det-price,.style_one .text-information .price,.style_one .text-information .title,.style_one .text-information .sub-title,.style_one .button-offers .ribbon-content,.wrapper-circle .style-offers-two .text-information .sub-title,
				.woo_mini_cart .button,
				.woocommerce .button:not(.add_to_cart_button),
				.cws-widget .button,
				.woocommerce .added_to_cart,
				.woocommerce-page .button:not(.add_to_cart_button),
				.woocommerce-page .added_to_cart{font-family:'.$font_array['font-family'].';}';
			$out .= '.ce_sub_title.und-title,.wrapper-circle .style-offers-three .step-txt{font-family:'.$font_array['font-family'].';}';
			$out .= '.news .item > .ce_title a{'. $this->cws_print_font_css($font_array) . ';}';

			$out .= 'main .news .item > .ce_title a{font-size:'.round((int)$font_array['font-size']/35,5).'em;}';
			
			$out .= '.relish-new-layout .cws-widget .widget-title{'. $this->cws_print_font_css($font_array) . ';}';

			$out .= '.relish-new-layout .cws-widget .widget-title{font-size:'.round((int)$font_array['font-size']/32,5).'em;}';

			$out .= '#main.site-main .cws-widget .widget-title{'. $this->cws_print_font_css($font_array) . ';}';

			$out .= '#main.site-main .cws-widget .widget-title{font-size:'.round((int)$font_array['font-size']/33,5).'em;}';

			$out .= '.woocommerce .products li h3{'. $this->cws_print_font_css($font_array) . ';}';

			$out .= '.woocommerce .products li h3{font-size:'.round((int)$font_array['font-size']/32,5).'em;}';

			$out .= '.testimonial .author figcaption,
				.cws-widget .post_title a,
				.testimonial .quote .quote_link:hover,
				.news .post_tags>*,
				.news .post_categories>*,
				.pagination a,
				.widget-title,
				a:hover,
				.ce_toggle.alt .accordion_title:hover,
				.cws_portfolio_items .item .title_part,
				.pricing_table_column .price_section,
				.comments-area .comments_title,
				.comments-area .comment-meta,
				.comments-area .comment-reply-title,
				.comments-area .comment-respond .comment-form input:not([type=\'submit\']),
				
				.page_title .bread-crumbs,
				.benefits_container .cws_textwidget_content .link a:hover,
				.cws_portfolio_fw .title,
				.cws_portfolio_fw .cats a:hover,
				.msg_404,
				.relish-new-layout .news .item .post_info
					{color:' . $font_array['color'] . ';}';

		}
		echo preg_replace('/\s+/',' ', $out);
	}

	// \HEADER FONT HOOK

	// BODY FONT HOOK

	private function cws_print_body_font () {
		ob_start();
		do_action( 'body_font_hook' );
		return ob_get_clean();
	}

	private function cws_rgb_color(){
		ob_start();
		do_action( 'rgb_hook' );
		return ob_get_clean();	
	}

	public function cws_body_font_action () {
		$out = '';
		$font_array = relish_get_option('body-font');
		if (isset($font_array)) {
			$out .= 'body
						{'. $this->cws_print_font_css($font_array) . '}';

			$out .= 'body
						{font-weight:'.($font_array['font-weight'][0] == 'regular' ? $font_array['font-weight'][0] = 400 : $font_array['font-weight'][0]) . '}';

			$out .= '.comments-area .comment-respond .comment-form textarea,
			.cws-widget .widget_carousel.owl-carousel .post_item .post_preview .post_content{color:' . $font_array['color'] . ';}';
			$out .= '.mini-cart .woo_mini_cart,
					body input,body  textarea
						{font-size:' . $font_array['font-size'] . ';}';
			$out .= '.comments-area .comment-reply-title,.comments-area .comments_title.ce_title{color:' . $font_array['color'] . ';}';
			$out .= 'body input,body  textarea
						{line-height:' . $font_array['line-height'] . ';}';
			$out .= 'abbr
						{border-bottom-color:' . $font_array['color'] . ';}';
			$fs_match = preg_match( '#(\d+)(.*)#', $font_array['font-size'], $fs_matches );
			$lh_match = preg_match( '#(\d+)(.*)#', $font_array['line-height'], $lh_matches );
			if ( $fs_match && $lh_match ) {
				$fs_number = (int)$fs_matches[1];
				$fs_units = $fs_matches[2];
				$lh_number = (int)$lh_matches[1];
				$lh_units = $lh_matches[2];
			}
		}
		echo preg_replace('/\s+/',' ', $out);
	}

	public function cws_rgb_color_shadow () {
		$out = '';
		$font_array = relish_get_option('theme-custom-color');
		$rgb_color = $this->cws_Hex2RGB( $font_array );
		$rgb_color = esc_attr($rgb_color);

		$lighter_rgba_color_hover = $rgb_color .  ",0.6";
		$lighter_rgba_color_hover = esc_attr($lighter_rgba_color_hover);

		$lighter_rgba_color_hover_portfolio = $rgb_color .  ",0.9";
		$lighter_rgba_color_hover_portfolio = esc_attr($lighter_rgba_color_hover_portfolio);

		$lighter_rgba_color = $rgb_color . ",0.85098";
		$lighter_rgba_color = esc_attr($lighter_rgba_color);

		$out = ".wrapper-circle .style-offers-two .text-information{box-shadow:rgba($lighter_rgba_color) 0px 0px 0px 200px inset !important;
		}";
		$out .= ".media_part .pic a:hover:before, .media_part.gallery_post .pic:hover:before{background-color:rgba($lighter_rgba_color_hover) !important
		}";
		$out .= ".cws_portfolio .square:hover .img_cont.img a:hover:before{background-color:rgba($lighter_rgba_color_hover) !important
		}";
		$out .= ".ih-item.circle.effect5 .info .info-back,
		.ih-item.circle.effect1 .info, .ih-item.circle.effect2 .info, .ih-item.circle.effect13 .info, .ih-item.circle.effect17 .info, .ih-item.circle.effect20 .info .info-back, .ih-item.circle.effect18 .info .info-back, .ih-item.circle.effect19 .info{background-color:rgba($lighter_rgba_color_hover_portfolio) 

		}";

		$out .= ".wrapper-circle-offers.no_hover.style-offers-four .item_content.info{background-color:rgba($lighter_rgba_color_hover_portfolio)}";


		$out .= "
		.ih-item.circle.effect17 .pic_alt:hover .img:before{ box-shadow: inset 0 0 0 210px rgba($lighter_rgba_color_hover), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(118, 192, 138, 0.1);
		}";
		echo preg_replace('/\s+/',' ', $out);
	}

	// \BODY FONT HOOK

	public function cws_process_fonts() {
		$out = $this->cws_print_menu_font();
		$out .= $this->cws_print_header_font();
		$out .= $this->cws_print_body_font();
		$out .= $this->cws_rgb_color();
		return $out;
	}

	public function cws_process_blur() {
		$blur_intensity = relish_get_option('blur_intensity');
		$use_blur = relish_get_option('use_blur');
		$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false;
		if (!$use_blur) return;
		$out = '.pic.blured img.blured-img,
				.item .pic_alt .img_cont>img.blured-img,
				.pic .img_cont>img.blured-img,
				.cws-widget .post_item .post_thumb:hover img,
				.cws_img_frame:hover img,
				.cws-widget .portfolio_item_thumb .pic .blured-img{
					-webkit-filter: blur('.$blur_intensity.'px);
						-moz-filter: blur('.$blur_intensity.'px);
						-o-filter: blur('.$blur_intensity.'px);
						-ms-filter: blur('.$blur_intensity.'px);
						filter: blur('.$blur_intensity.'px);
				}';
		return $out;
	}

	/******************** \TYPOGRAPHY ********************/

	public function cws_layout_class ($classes=array()) {
		$boxed_layout = relish_get_option('boxed-layout');
		if ( $boxed_layout=='0' ) {
			array_push( $classes, 'wide' );
		}
		return $classes;
	}

	public function cws_widgets_init() {
		$sidebars = relish_get_option('sidebars');
		if (!empty($sidebars) && function_exists('register_sidebars')) {
			foreach ($sidebars as $sb) {
				if ($sb) {
					register_sidebar( array(
						'name' => $sb['title'],
						'id' => strtolower(preg_replace("/[^a-z0-9\-]+/i", "_", $sb['title'])),
						'before_widget' => '<div class="cws-widget">',
						'after_widget' => '</div>',
						'before_title' => '<div class="widget-title">',
						'after_title' => '</div>',
						));
				}
			}
		}
	}

	public function cws_theme_enqueue_styles() {
		if((is_admin() && !is_shortcode_preview()) || 'wp-login.php' == basename($_SERVER['PHP_SELF'])) {
			return;
		}

		$styles =	array(
			'cws_loader' => 'cws_loader.css',
			'font-awesome' => 'font-awesome.css',
			'fancybox' => 'jquery.fancybox.css',
			'odometer' => 'odometer-theme-default.css',
			'select2_init' => 'select2.css',
			'ihover'  => 'ihover.css',
			'animate' => 'animate.css'
		);

		foreach($styles as $key=>$sc){
			wp_enqueue_style( $key, RELISH_URI . '/css/' . $sc);
		}

		$cwsfi = get_option('cwsfi');
		if ( !is_plugin_active( 'cws-flaticons/cws-flaticons.php'))  {
			$cwsfi = "";
		}	
		if (!empty($cwsfi) && isset($cwsfi['css'])) {
			wp_enqueue_style( 'cwsfi-css', $cwsfi['css']);			
		}else{		
		wp_enqueue_style( 'flaticon', RELISH_URI . '/fonts/flaticon/flaticon.css' );
		};

		wp_enqueue_style( 'cws-iconpack', RELISH_URI . '/fonts/cws-iconpack/flaticon.css' );

		$is_custom_color = relish_get_option('is-custom-color');
		if ($is_custom_color != '1') {
			$style = relish_get_option('stylesheet');
			if (!empty($style)) {
				wp_enqueue_style( 'style-color', RELISH_URI . '/css/' . $style . '.css' );
			}
		}

		wp_enqueue_style( 'reset', RELISH_URI . '/css/reset.css' );
		wp_enqueue_style( 'layout', RELISH_URI . '/css/layout.css' );
		wp_enqueue_style( 'font-awesome', RELISH_URI . '/css/font-awesome.css' );
		
		wp_enqueue_style( 'main', RELISH_URI . '/css/main.css' );
		}

	public function cws_enqueue_theme_stylesheet () {
		wp_enqueue_style( 'style', get_stylesheet_uri() );
	}

	public function cws_theme_enqueue_scripts() {
		$footer_scripts = array (
				'owl_carousel' => 'owl.carousel.js',
				'isotope' => 'isotope.pkgd.min.js',
				'odometer' => 'odometer.js',
				'wow' => 'wow.min.js',
				'cws_parallax' => 'cws_parallax.js',
				'parallax' => 'parallax.js',
				'cws_YT_bg' => 'cws_YT_bg.js',
				'cws_self&vimeo_bg' => 'cws_self&vimeo_bg.js',
				'vimeo' => 'jquery.vimeo.api.min.js',		
						
				);

		foreach ($footer_scripts as $alias => $src) {
			wp_register_script ($alias, RELISH_URI . "/js/$src", array(), "1.0", true);
		}

	}

	public function cws_theme_standart_script(){
		$scripts = array (
				'sticky_sidebar' => 'sticky_sidebar.js',
				'retina' => 'retina_1.3.0.js',
				'fancybox' => 'jquery.fancybox.js',
				'img_loaded' => 'imagesloaded.pkgd.min.js',
				'select2_init' => 'select2.js',
				'relish_main' => 'scripts.js',
				);

		if ( '0' != relish_get_option('enable_mob_menu') ) {
			wp_enqueue_script ('modernizr', RELISH_URI . "/js/modernizr.js", array(), "1.0", true);
		}

		wp_enqueue_script( 'tweenmax', RELISH_URI . "/js/tweenmax.min.js", array( "jquery" ), "1.0", false );
		wp_enqueue_script( 'cws_loader', RELISH_URI . "/js/cws_loader.js", array( "jquery", "tweenmax" ), "1.0", false );
		wp_enqueue_script( 'yt_player_api', 'https://www.youtube.com/player_api', array(), '1.0', true );
		
		foreach ($scripts as $alias => $src) {
			wp_enqueue_script ($alias, RELISH_URI . "/js/$src", array(), "1.0", true);
			
		}

		wp_localize_script('relish_main', 'custom', array(
			'templateDir' => esc_url( get_template_directory_uri() ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			));
	}

	public function cws_admin_init( $hook ) {
		wp_enqueue_style('admin-css', RELISH_URI . '/core/css/mb-post-styles.css' );
		wp_enqueue_style('font-awesome-css', RELISH_URI . '/css/font-awesome.css' );
		wp_enqueue_style('FlatIcon-css', RELISH_URI . '/fonts/flaticon/flaticon.css' );

		wp_enqueue_style('wp-color-picker');
		if ('widgets.php' !==$hook) {
			wp_enqueue_script('relish-metaboxes-js', get_template_directory_uri() . '/core/js/metaboxes.js', array('jquery') );
		}
		if ('toplevel_page_cwsfw' !==$hook) {
			wp_enqueue_style('relish-metaboxes-css', get_template_directory_uri() . '/core/css/metaboxes.css', false, '2.0.0' );			
		}
		wp_enqueue_script('custom-admin', RELISH_URI . '/core/js/custom-admin.js', array( 'jquery' ) );

		$cwsfi = get_option('cwsfi');
		if ( !is_plugin_active( 'cws-flaticons/cws-flaticons.php'))  {
			$cwsfi = "";
		}	
			if (!empty($cwsfi) && isset($cwsfi['css'])) {
		}else{
			wp_enqueue_style( 'flaticon', RELISH_URI . '/fonts/flaticon/flaticon.css' );
		};

		if (('toplevel_page_relish' == $hook) || ('toplevel_page_relishChildTheme' == $hook)) {
			wp_enqueue_style( 'cws-redux-style' , RELISH_URI . '/core/css/cws-redux-style.css' );
		}
	}

	public function cws_register_fonts () {
		relish_render_fonts_url ();
		$gf_url = esc_url( relish_render_fonts_url () );
		wp_enqueue_style( '', $gf_url );
	}

	private function cws_register_widgets( $cws_widgets ) {
		foreach ($cws_widgets as $w) {
			require_once (get_template_directory() . '/core/widgets/' . strtolower($w) . '.php');
			register_widget($w);
		}
	}

	public function cws_after_setup_theme() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(' widgets ');
		add_theme_support( 'title-tag' );

		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

		register_nav_menu( 'header-menu', esc_html__( 'Navigation Menu', 'relish' ) );
		register_nav_menu( 'sidebar-menu', esc_html__( 'SideBar Menu', 'relish' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'custom-background', array('default-color' => '616262') );

		$this->cws_register_widgets( array(
			'CWS_Text',
			'CWS_Latest_Posts',
			'CWS_Portfolio',
			'CWS_Twitter'
		) );

		$user = wp_get_current_user();
		$user_nav_adv_options = get_user_option( 'managenav-menuscolumnshidden', get_current_user_id() );
		if ( is_array($user_nav_adv_options) ) {
			$css_key = array_search('css-classes', $user_nav_adv_options);
			if (false !== $css_key) {
				unset($user_nav_adv_options[$css_key]);
				update_user_option($user->ID, 'managenav-menuscolumnshidden', $user_nav_adv_options,	true);
			}
		}
	}
	// THEME COLOR HOOK

	private function cws_print_theme_color() {
		ob_start();
		do_action( 'theme_color_hook' );
		return ob_get_clean();
	}

	public function cws_theme_color_action() {
		$out = '';
		$theme_color = relish_get_option('theme-custom-color');
		if (isset($theme_color)) {
			global $wp_filesystem;
			if( empty( $wp_filesystem ) ) {
				require_once( ABSPATH .'/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$file = get_template_directory() . '/css/theme-color.css';
			if ( $wp_filesystem->exists($file) ) {
				$file = $wp_filesystem->get_contents( $file );
				$new_css = preg_replace('|#[^\s]+#|', $theme_color, $file);
				$out .= $new_css;

			}
		}
		else{
			global $wp_filesystem;
			if( empty( $wp_filesystem ) ) {
				require_once( ABSPATH .'/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$file = get_template_directory() . '/css/theme-color.css';
			if ( $wp_filesystem->exists($file) ) {
				$file = $wp_filesystem->get_contents( $file );
				$new_css = preg_replace('|#[^\s]+#|', "#76c08a", $file);
				$out .= $new_css;

			}
		}

		echo preg_replace('/\s+/',' ', $out);
	}

	public function cws_page_title_custom_color_action () {
		$header_bg_settings = relish_get_option( "header_bg_settings" );
		$header_bg_settings = isset( $header_bg_settings["@"] ) ? $header_bg_settings["@"] : array();
		$hex_color = isset( $header_bg_settings['font_color'] ) ? $header_bg_settings['font_color'] : '';
		$rgb_color = '';
		$lighter_rgba_color = '';
		if ( !empty( $hex_color ) ) {
			$rgb_color = $this->cws_Hex2RGB( $hex_color );
			$rgb_color = esc_attr($rgb_color);
			$lighter_rgba_color = $rgb_color . ",0.2";
			$lighter_rgba_color = esc_attr($lighter_rgba_color);
			echo ".page_title.customized{\ncolor: $hex_color;\n}\n.page_title.customized .bread-crumbs{\nbackground-color: rgba($lighter_rgba_color);\n}";
		}
	}

	private function cws_print_theme_gradient () {
		ob_start();
		do_action( 'theme_gradient_hook' );
		return ob_get_clean();
	}

	public function cws_theme_gradient_action () {
		$out = '';
		$use_gradients = relish_get_option('use_gradients');
		if ( $use_gradients ) {
			$gradient_settings = relish_get_option( 'gradient_settings' );
			require_once( get_template_directory() . "/css/gradient_selectors.php" );
			if ( function_exists( "get_gradient_selectors" ) ) {
				$gradient_selectors = get_gradient_selectors();
				$out .= relish_render_gradient_rules( array(
					'settings' => $gradient_settings,
					'selectors' => $gradient_selectors,
					'use_extra_rules' => true
				));
			}
		}
		echo preg_replace('/\s+/',' ', $out);
	}

	public function cws_gradients_body_class ( $classes ) {
		$use_gradients = relish_get_option('use_gradients');
		if ( $use_gradients ) {
			$classes[] = "cws_gradients";
		}
		return $classes;
	}

	public function cws_process_colors() {
		$out = $this->cws_print_theme_color();
		$out .= $this->cws_print_theme_gradient();
		return preg_replace('/\s+/',' ', $out);
	}

	public function cws_Hex2RGB($hex) {
		$hex = str_replace('#', '', $hex);
		$color = '';

		if(strlen($hex) == 3) {
			$color = hexdec(mb_substr($hex, 0, 1)) . ',';
			$color .= hexdec(mb_substr($hex, 1, 1)) . ',';
			$color .= hexdec(mb_substr($hex, 2, 1));
		}
		else if(strlen($hex) == 6) {
			$color = hexdec(mb_substr($hex, 0, 2)) . ',';
			$color .= hexdec(mb_substr($hex, 2, 2)) . ',';
			$color .= hexdec(mb_substr($hex, 4, 2));
		}
		return $color;
	}

	public function cws_add_style() {
		wp_add_inline_style('cws_loader', relish_theme_header_process_colors() );
		wp_add_inline_style('cws_loader', relish_theme_header_process_fonts() );
		wp_add_inline_style('cws_loader', relish_theme_header_process_blur () );
		if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			wp_add_inline_style('cws_loader', relish_theme_wpml_process_colors () );
		}
	}


	public function cws_categories_postcount_filter ($variable) {
		if(strpos($variable,'</a> (')){
			$variable = str_replace('</a> (', '<span class="post_count"> ', $variable);
			$variable = str_replace(')', ' </span> </a>', $variable);		
		}
		else{
			$variable = str_replace('</a> <span class="count">(', '<span class="post_count">', $variable);
			$variable = str_replace(')', ' </span> </a>', $variable);		
		}	

		return $variable;
	}

	public function cws_archive_postcount_filter ($variable) {

		$variable = str_replace('(', ' ', $variable);
		$variable = str_replace(')', ' ', $variable);
		return $variable;
	}

	public function cws_gallery_output($output, $attr, $instance ) {
		global $post, $wp_locale;
		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => $html5 ? 'figure'     : 'dl',
			'icontag'    => $html5 ? 'div'        : 'dt',
			'captiontag' => $html5 ? 'figcaption' : 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => ''
			), $attr, 'gallery' );
		$id = intval( $atts['id'] );
		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}
		if ( empty( $attachments ) ) {
			return '';
		}
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}
		$itemtag = tag_escape( $atts['itemtag'] );
		$captiontag = tag_escape( $atts['captiontag'] );
		$icontag = tag_escape( $atts['icontag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) ) {
			$itemtag = 'dl';
		}
		if ( ! isset( $valid_tags[ $captiontag ] ) ) {
			$captiontag = 'dd';
		}
		if ( ! isset( $valid_tags[ $icontag ] ) ) {
			$icontag = 'dt';
		}
		$columns = intval( $atts['columns'] );
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';
		$selector = "gallery-{$instance}";
		$gallery_style = '';

		if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
				margin: auto;
			}
				#{$selector} .gallery-item {
			float: {$float};
			margin-top: 10px;
			text-align: center;
			width: {$itemwidth}%;
		}
				#{$selector} img {
		border: 2px solid #cfcfcf;
		}
				#{$selector} .gallery-caption {
		margin-left: 0;
		}
		/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
		}

		$size_class = sanitize_html_class( $atts['size'] );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

		$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
			}
			$image_meta  = wp_get_attachment_metadata( $id );
			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}
			$class_empty_txt = (empty($attachment->post_excerpt) && empty($attachment->post_content) ? " empty_txt" : "");
			$output .= "<{$itemtag} class='gallery-item{$class_empty_txt}'>";
			$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
			$image_output
			</{$icontag}>";
			
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'><div class='title-img'>
				" . wptexturize($attachment->post_excerpt) ."</div><div class='description-image'>". wptexturize($attachment->post_content) ."</div>
				</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
				$output .= '<br style="clear: both" />';
			}
		}
		if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
			$output .= "
			<br style='clear: both' />";
		}
		$output .= "
		</div>\n";
		return $output;
	}

	public function cws_cleanup_shortcode_fix($content) {  
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']',
			']<br>' => ']'
			);
		$content = strtr($content, $array);
		return $content;
	}
	// \  COLOR HOOK
}

/* End of Theme's Class */

/* THE HEADER META */

if(!function_exists('relish_theme_header_meta')) {
		/**
		 * Function that echoes meta data if our seo is enabled
		 */
		function relish_theme_header_meta() {
			?>
				<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php
	}
		add_action('relish_theme_header_meta', 'relish_theme_header_meta');
}

if (!function_exists('relish_theme_header_process_fonts')) {
	function relish_theme_header_process_fonts (){
		global $cws_theme_funcs;
		return $cws_theme_funcs->cws_process_fonts();
	}
}

if (!function_exists('relish_theme_header_process_colors')) {
	function relish_theme_header_process_colors (){
		global $cws_theme_funcs;
		return $cws_theme_funcs->cws_process_colors();
	}
}

if (!function_exists('relish_theme_header_process_blur')) {
	function relish_theme_header_process_blur (){
		global $cws_theme_funcs;
		return $cws_theme_funcs->cws_process_blur();
	}
}

/* END THE HEADER META */

/**
 * Register the stylesheets for the public-facing side of the site.
 * @since    0.5
 */
add_action( 'wp_enqueue_scripts', 'relish_sl_enqueue_scripts' );
function relish_sl_enqueue_scripts() {
	wp_enqueue_script( 'simple-likes-public-js', get_template_directory_uri() . '/js/simple-likes-public.js', array( 'jquery' ), '0.5', false );
	wp_localize_script( 'simple-likes-public-js', 'simpleLikes', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => __( 'Like', 'relish' ),
		'unlike' => __( 'Unlike', 'relish' )
		) ); 
}
/**
 * Processes like/unlike
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_relish_process_simple_like', 'relish_process_simple_like' );
add_action( 'wp_ajax_relish_process_simple_like', 'relish_process_simple_like' );
function relish_process_simple_like() {
	// Security
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( __( 'Not permitted', 'relish' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
	// Test if this is a comment
	$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
	// Get plugin options
	if ( $post_id != '' ) {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !relish_already_liked( $post_id, $is_comment ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = relish_post_user_likes( $user_id, $post_id, $is_comment );
				if ( $is_comment == 1 ) {
					// Update User & Comment
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					}
				} else {
					// Update User & Post
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = relish_sl_get_ip();
				$post_users = relish_post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = relish_get_liked_icon();
		} else { // Unlike the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = relish_post_user_likes( $user_id, $post_id, $is_comment );
				// Update User
				if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
				}
				// Update Post
				if ( $post_users ) {	
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[$uid_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = relish_sl_get_ip();
				$post_users = relish_post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					$uip_key = array_search( $user_ip, $post_users );
					unset( $post_users[$uip_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = "unliked";
			$response['icon'] = relish_get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "_comment_like_count", $like_count );
			update_comment_meta( $post_id, "_comment_like_modified", date( 'Y-m-d H:i:s' ) );
		} else { 
			update_post_meta( $post_id, "_post_like_count", $like_count );
			update_post_meta( $post_id, "_post_like_modified", date( 'Y-m-d H:i:s' ) );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}

function relish_already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else { // user is anonymous
		$user_id = relish_sl_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" ); 
		if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
}

function relish_get_simple_likes_button( $post_id, $is_comment = NULL ) {
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
		$comment_class = esc_attr( ' sl-comment' );
		$like_count = get_comment_meta( $post_id, "_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' sl-button-' . $post_id );
		$comment_class = esc_attr( '' );
		$like_count = get_post_meta( $post_id, "_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = relish_get_unliked_icon();
	$icon_full = relish_get_liked_icon();
	// Loader
	$loader = '<span class="sl-loader"></span>';
	// Liked/Unliked Variables
	if ( relish_already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
		$title = __( 'Unlike', 'relish' );
		$icon = $icon_full;
	} else {
		$class = '';
		$title = __( 'Like', 'relish' );
		$icon = $icon_empty;
	}
	$output = '<span class="sl-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=relish_process_simple_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true' ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
	return $output;
} 

add_cws_shortcode( 'jmliker', 'relish_sl_shortcode' );
function relish_sl_shortcode() {
	return relish_get_simple_likes_button( get_the_ID(), 0 );
}

function relish_post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_id, $post_users ) ) {
		$post_users['user-' . $user_id] = $user_id;
	}
	return $post_users;
}

function relish_post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
	// Retrieve post information
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_ip, $post_users ) ) {
		$post_users['ip-' . $user_ip] = $user_ip;
	}
	return $post_users;
}

function relish_sl_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
}

function relish_get_liked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
	$icon = '<span class="sl-icon unliked"></span>';
	return $icon;
}

function relish_get_unliked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart-o"></i> */
	$icon = '<span class="sl-icon liked"></span>';
	return $icon;
}

function relish_sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
}

function get_like_count( $like_count ) {
	$like_text = __( '0', 'relish' );
	if ( is_numeric( $like_count ) && $like_count > 0 ) { 
		$number = relish_sl_format_count( $like_count );
	} else {
		$number = $like_text;
	}
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
}
// User Profile List
add_action( 'show_user_profile', 'relish_show_user_likes' );
add_action( 'edit_user_profile', 'relish_show_user_likes' );
function relish_show_user_likes( $user ) { ?>        
	<table class="form-table">
		<tr>
			<th><label for="user_likes"><?php _e( 'You Like:', 'relish' ); ?></label></th>
			<td>
				<?php
				$types = get_post_types( array( 'public' => true ) );
				$args = array(
					'numberposts' => -1,
					'post_type' => $types,
					'meta_query' => array (
						array (
							'key' => '_user_liked',
							'value' => $user->ID,
							'compare' => 'LIKE'
							)
						) );		
				$sep = '';
				$like_query = new WP_Query( $args );
				if ( $like_query->have_posts() ) : ?>
					<p>
						<?php while ( $like_query->have_posts() ) : $like_query->the_post(); 
						echo sprintf('%s', $sep); ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php
						$sep = ' &middot; ';
						endwhile; 
						?>
					</p>
				<?php else : ?>
				<p><?php _e( 'You do not like anything yet.', 'relish' ); ?></p>
				<?php 
				endif; 
				wp_reset_postdata(); 
				?>
			</td>
		</tr>
	</table>
<?php }

/* HEDER LOADER */
if (!function_exists('relish_page_loader')) {
	function relish_page_loader (){
		echo '<div id="cws_page_loader_container" class="cws_loader_container">
			<div id="cws_page_loader" class="cws_loader"></div>
		</div>';
	}
}

/* END HEDER LOADER */
function merge_fonts_options ( $fonts_arr = array(), $ind = 0 ){
	
	$r = $temp = $rem_inds = array();

	return $fonts_arr;
}

function relish_render_fonts_url (){
	$url = "";
	$query_args = "";
	$body_font_opts = call_user_func( "relish_get_option", "body-font" );
	$menu_font_opts = call_user_func( "relish_get_option", "menu-font" );
	$header_font_opts = call_user_func( "relish_get_option", "header-font" );
	
	$fonts_opts = merge_fonts_options( array( $body_font_opts, $menu_font_opts, $header_font_opts) );

	if ( empty( $fonts_opts ) ) return $url;
	$fonts_urls = array( count( $fonts_opts ) );
	$subsets_arr = array();
	$base_url = "//fonts.googleapis.com/css";
	$url = "";

	for ( $i = 0; $i < count( $fonts_opts ); $i++ ){
		$fonts_urls[$i] = "" . $fonts_opts[$i]['font-family'];
		$fonts_urls[$i] .= !empty( $fonts_opts[$i]['font-weight'] ) ? ":" . implode( $fonts_opts[$i]['font-weight'], ',' ) : "";
		if(!empty($fonts_opts[$i]['font-sub'])){
			for ( $j = 0; $j < count( $fonts_opts[$i]['font-sub'] ); $j++ ){
				if ( !in_array( $fonts_opts[$i]['font-sub'][$j], $subsets_arr ) ){
					array_push( $subsets_arr, $fonts_opts[$i]['font-sub'][$j] );
				}
			}			
		}

	};
	$query_args = array(
		'family'	=> urlencode( implode( $fonts_urls, '|' ) )
	);
	if ( !empty( $subsets_arr ) ){
		$query_args['subset']	= urlencode( implode( $subsets_arr, ',' ) );
	}
	$url = add_query_arg( $query_args, $base_url );
	return $url;
}
	
function relish_render_gradient ($arrs) {
 $gradient = array(
  '@' => array(
   'first_color' => (!empty($arrs[ 'first_color' ]) ? $arrs[ 'first_color' ] : ''),
   'second_color' => (!empty($arrs[ 'second_color' ]) ? $arrs[ 'second_color' ] : ''),
   'type' => (!empty($arrs[ 'type' ]) ? $arrs[ 'type' ] : ''),
   'linear_settings' => (!empty($arrs[ 'linear_settings' ]) ? $arrs[ 'linear_settings' ] : ''),
   'radial_settings' => array(
    '@' => array(
     'shape_settings' => (!empty($arrs['radial_settings']['shape_settings']) ? $arrs['radial_settings']['shape_settings'] : ''),
     'shape' => (!empty($arrs['radial_settings']['shape']) ? $arrs['radial_settings']['shape'] : ''),
     'size_keyword' => (!empty($arrs['radial_settings']['size_keyword']) ? $arrs['radial_settings']['size_keyword'] : ''),
     'size' => (!empty($arrs['radial_settings']['size']) ? $arrs['radial_settings']['size'] : ''),
     )
    )
   )
 );
 return $gradient;
}

/* THEME HEADER */
if (!function_exists('relish_page_header')) {
	function relish_page_header (){
		$stick_menu = relish_get_option( 'menu-stick' );
		$stick_menu =  $stick_menu == 1 ? true : false;
		$use_blur = relish_get_option( 'use_blur' );
		$custom_header_bg_color = false;
		$customize_headers = relish_get_option( 'customize_headers' );
		$header_bg_settings = relish_get_option( "header_bg_settings" );
		$use_gradient = relish_get_option( "use_gradient" );
		$bg_color_color = relish_get_option( "bg_color_color" );
		$bg_img = relish_get_option( "bg_img" );
		$bg_color_gradient_arrs = relish_get_option("gradient_settings");
		$arrs = relish_get_option("gradient_settings");
		$bg_color_gradient_settings = relish_render_gradient ($bg_color_gradient_arrs); // get gradient array 
		$parallax_opt_arr = relish_get_option('parallax_options');
		$parallax_options = array(
			'@' => array(
				'parallaxify' => relish_get_option("parallaxify"),
				'scalar_x' => (!empty($parallax_opt_arr["scalar_x"]) ? $parallax_opt_arr["scalar_x"] : ''),
				'scalar_y' => (!empty($parallax_opt_arr["scalar_y"]) ? $parallax_opt_arr["scalar_y"] : ''),
				'limit_x' => (!empty($parallax_opt_arr["limit_x"]) ? $parallax_opt_arr["limit_x"] : ''),
				'limit_y' => (!empty($parallax_opt_arr["limit_y"]) ? $parallax_opt_arr["limit_y"] : ''),
		));

		$parallax_options = isset( $parallax_options['@'] ) ? $parallax_options['@'] : array();
		$img_section_atts = "";
		$img_section_class = "header_bg_img";
		$img_section_styles = "";

		$img_section_atts .= !empty( $img_section_class ) ? " class='$img_section_class'" : "";
		$img_section_atts .= !empty( $img_section_styles ) ? " style='$img_section_styles'" : "";
		$parallax_section_atts = "";
		$parallax_section_class = "cws_parallax_section";
		wp_enqueue_script ('parallax');
		$parallax_section_styles = "";
		$parallax_section_styles .= isset( $parallax_options['limit_x'] ) && !empty( $parallax_options['limit_x'] ) ? "margin-left:-" . $parallax_options['limit_x'] . "px;margin-right:-" . $parallax_options['limit_x'] . "px;" : "";
		$parallax_section_styles .= isset( $parallax_options['limit_y'] ) && !empty( $parallax_options['limit_y'] ) ? "margin-top:-" . $parallax_options['limit_y'] . "px;margin-bottom:-" . $parallax_options['limit_y'] . "px;" : "";
		$parallax_section_atts .= !empty( $parallax_section_class ) ? " class='$parallax_section_class'" : "";
		$parallax_section_atts .= isset( $parallax_options['scalar_x'] ) && !empty( $parallax_options['scalar_x'] ) ? " data-scalar-x='" . $parallax_options['scalar_x'] . "'" : "";
		$parallax_section_atts .= isset( $parallax_options['scalar_y'] ) && !empty( $parallax_options['scalar_y'] ) ? " data-scalar-y='" . $parallax_options['scalar_y'] . "'" : "";
		$parallax_section_atts .= isset( $parallax_options['limit_x'] ) && !empty( $parallax_options['limit_x'] ) ? " data-limit-x='" . $parallax_options['limit_x'] . "'" : "";
		$parallax_section_atts .= isset( $parallax_options['limit_y'] ) && !empty( $parallax_options['limit_y'] ) ? " data-limit-y='" . $parallax_options['limit_y'] . "'" : "";
		$parallax_section_atts .= !empty( $parallax_section_styles ) ? " style='$parallax_section_styles'" : "";

		$show_header_outside_slider = relish_get_option( 'show_header_outside_slider');

		$show_page_title = true;
		/***** Boxed Layout *****/
		$boxed_layout = ('0' != relish_get_option('boxed-layout') ) ? 'boxed' : '';
		if($boxed_layout) {
			echo'<div class="page_boxed">';
		}
		/***** \Boxed Layout *****/

		ob_start();
			$social_links_location = relish_get_option( 'social_links_location' );
			$top_panel_text = relish_get_option( 'top_panel_text' );
			$social_toggle = relish_get_option('toggle-share-icon');

			$top_panel_switcher = relish_get_option( 'top_panel_switcher');

			$social_links = "";
			$show_wpml_header = relish_is_wpml_active() ? true : false;
			if (relish_get_option('woo_cart_enable')) {
				ob_start();
					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
						woocommerce_mini_cart();
					}
				$woo_mini_cart = ob_get_clean();

				ob_start();
					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
						?>
						<a class="woo_icon" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_html_e( 'View your shopping cart', 'relish' ); ?>"><i class='woo_mini-count fa fa-shopping-cart'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></i></a>
						<?php
					}
				$woo_mini_icon = ob_get_clean();
			}

			if ( in_array( $social_links_location, array( 'top', 'top_bottom' ) ) ){
				$social_links = relish_render_social_links();
			}

			if($top_panel_switcher == 1){
				if ( (!empty( $social_links ) || !empty( $top_panel_text ) || relish_get_option('search_place') != "none") || relish_get_option('woo_cart_enable') && $top_panel_switcher == 1 ){
					$position_top_bar = relish_get_option('search_place');	
					
					$style_top_bar = '';
					$style_bg_top_bar = relish_get_option('theme_top_bar_color');
					$style_font_top_bar = relish_get_option('theme_top_bar_font_color');
					
					if(isset($style_bg_top_bar) && !empty($style_bg_top_bar)){
						$style_top_bar .= "background-color:".$style_bg_top_bar.";";
					}
					else{
						$style_top_bar .= "background-color:#f0f7f2;";
					}
					if(isset($style_font_top_bar) && !empty($style_font_top_bar)){
						$style_top_bar .= "color:".$style_font_top_bar.";";
					}
					else{
						$style_top_bar .= "color:#777777;";
					}

					echo "<div id='site_top_panel'".(!empty($style_top_bar) ? " style='".$style_top_bar."'" : "").">";
						echo "<div class='container'>";
							echo "<div class='row_text_search'>"; ?>							
								<?php
								echo ! empty( $top_panel_text ) ? "<div id='top_panel_text' ".($position_top_bar == 'left' ? 'class="padding-left-search"' : "").">$top_panel_text</div>" : '';
								
								if($position_top_bar != "none"){
									echo "<div class='elements-pos toggle-".$position_top_bar."'>";
									if ( $show_wpml_header ) : ?>
										<div class="lang_bar">
											<?php do_action( 'icl_language_selector' ); ?>
										</div>
										<span class='separate-search-icon'>|</span>
									<?php endif;
								}else{
									echo "<div class='elements-pos toggle-right'>";
									if ( $show_wpml_header ) : ?>
										<div class="lang_bar">
											<?php do_action( 'icl_language_selector' ); ?>
										</div><span class='separate-search-icon'>|</span>
									<?php endif;
								}
								
								echo !empty( $social_links ) ? "<div id='top_social_links_wrapper' class='".($social_toggle == 1 ? 'toggle-on' : 'toggle-off')."'> <span class='social-btn-open'></span> $social_links<span class='separate-search-icon'>|</span></div>" : "";
								if($position_top_bar != "none"){					
									get_search_form();
									echo "<div class='search_back_button'></div>";	
								}

									if ( !empty( $social_links ) || !empty($position_top_bar) || relish_get_option('woo_cart_enable')){
										
										echo "<div id='top_panel_links'>";
											if(relish_get_option('woo_cart_enable')) {
												echo is_plugin_active( 'woocommerce/woocommerce.php' ) ? "<div class='mini-cart'>$woo_mini_icon$woo_mini_cart</div>" : '';
											} 
											?>
											<?php
									if($position_top_bar != "none"){
										if(relish_get_option('woo_cart_enable')) {
											if($position_top_bar != 'left'){
												echo "<span class='separate-search-icon'>|</span>";
											}
										}
										if($position_top_bar == 'left'){
											echo "</div>";
										}
										echo "<div class='search_icon'>";
										if($position_top_bar == 'left'){
											echo "<span class='separate-search-icon'>|</span>";
										}	
										echo "</div>";
										
									}
										if($position_top_bar != 'left'){
											echo "</div>";
										}
									}
									echo "</div>";

							echo '</div>';
							
						echo "</div>";
						echo "<div id='top_panel_curtain'></div>";
					echo "</div>";

				}
			}
		$top_panel_content = ob_get_clean();
		ob_start();
			$spacings = relish_get_option( "spacings" );
			$custom_header_bg_spacings = (isset( $spacings ) && ($customize_headers == 1)) ? $spacings : array();

			$page_title_section_atts = "";
			$page_title_section_class = "page_title";

			$page_title_section_class .= $custom_header_bg_spacings ? (!empty($custom_header_bg_spacings["top"]) || !empty($custom_header_bg_spacings["bottom"])) ? " custom_spacing" : "" : "";
			$page_title_section_styles = "";
			$page_title_section_styles = esc_attr( $page_title_section_styles );
			$page_title_section_atts .= !empty( $page_title_section_class ) ? " class='$page_title_section_class'" : "";
			$page_title_section_atts .= !empty( $page_title_section_styles ) ? " style='$page_title_section_styles'" : "";

			$bg_header_color_overlay_type = ($customize_headers == 1) ? relish_get_option( 'bg_header_color_overlay_type' ) : '';
			$bg_header_overlay_color = ($customize_headers == 1) ? relish_get_option( 'bg_header_overlay_color' ) : '';
			$bg_header_color_overlay_opacity = ($customize_headers == 1) ? relish_get_option( 'bg_header_color_overlay_opacity' ) : '';
			$bg_header_use_pattern = ($customize_headers == 1) ? relish_get_option( 'bg_header_use_pattern' ) : '';
			$bg_header_pattern_image = ($customize_headers == 1) ? relish_get_option( 'bg_header_pattern_image' ) : '';
			$bg_header_use_blur = ($customize_headers == 1) ? relish_get_option('bg_header_use_blur') : 0;
			$bg_header_blur_intensity = ($customize_headers == 1) ? relish_get_option('bg_header_blur_intensity') : '';
			$font_color = ($customize_headers == 1) ? relish_get_option( "font_color" ) : '';
			$bg_header_parallaxify = ($customize_headers == 1) ? relish_get_option("parallaxify") : '';
			$bg_header_scalar_x = ($customize_headers == 1) ? $parallax_opt_arr["scalar_x"] : '';
			$bg_header_scalar_y = ($customize_headers == 1) ? $parallax_opt_arr["scalar_y"] : '';
			$bg_header_limit_x = ($customize_headers == 1) ? $parallax_opt_arr["limit_x"] : '';
			$bg_header_limit_y = ($customize_headers == 1) ? $parallax_opt_arr["limit_y"] : '';
			$bg_header_options = relish_get_option('header_bg_image');			


			if ($bg_header_use_blur == 1) {
				$bg_header_use_blur_style = '-webkit-filter: blur('.$bg_header_blur_intensity.'px);-moz-filter: blur('.$bg_header_blur_intensity.'px);-o-filter: blur('.$bg_header_blur_intensity.'px);-ms-filter: blur('.$bg_header_blur_intensity.'px);filter: blur('.$bg_header_blur_intensity.'px);';
			}
			$bg_header_gradient = relish_get_option('bg_header_settings');
			$bg_header_gradient_settings = relish_render_gradient($bg_header_gradient); // get gradient array

			$bg_header_gradient_settings = ($customize_headers == 1) ? $bg_header_gradient_settings : '';

			$bg_header_color_overlay_opacity = (int)$bg_header_color_overlay_opacity / 100;

			$bg_header_parallaxify_atts = ' data-scalar-x="'.esc_attr($bg_header_scalar_x).'" data-scalar-y="'.esc_attr($bg_header_scalar_y).'" data-limit-y="'.esc_attr($bg_header_limit_y).'" data-limit-x="'.esc_attr($bg_header_limit_x).'"';
			$bg_header_parallaxify_layer_atts = 'position: absolute; z-index: 1; left: -'.esc_attr($bg_header_limit_y).'px; right: -'.esc_attr($bg_header_limit_y).'px; top: -'.esc_attr($bg_header_limit_x).'px; bottom: -'.esc_attr($bg_header_limit_x).'px;';

			$bg_header = false;
			$bg_header_feature = false;
			$bg_header_url = "";				

			if ( isset( $bg_header_options['src'] ) && ! empty( $bg_header_options['src'] )){
				$bg_header_url = $bg_header_options['src'];
				$bg_header = true;
			}
			$bg_header_url = esc_url( $bg_header_url );
			$bg_header_html = '';

			$page_title_container_styles = '';

			if ( $custom_header_bg_spacings ){
				foreach ( $custom_header_bg_spacings as $key => $value ){
					if ( !empty( $value ) || $value == '0' ){
						$page_title_container_styles .= "padding-".$key . ": " . (int) $value . "px;";
						$page_title_section_atts .= " data-init-$key='$value'";
					}
				}
			}
			$show_breadcrumbs = relish_get_option( 'breadcrumbs' ) == 1 ? true : false;
			$text['home']	 = esc_html__( 'Home', 'relish' ); // text for the 'Home' link
			$text['category'] = esc_html__( 'Category "%s"', 'relish' ); // text for a category page
			$text['search']   = esc_html__( 'Search for "%s"', 'relish' ); // text for a search results page
			$text['taxonomy'] = esc_html__( 'Archive by %s "%s"', 'relish' );
			$text['tag']	  = esc_html__( 'Posts Tagged "%s"', 'relish' ); // text for a tag page
			$text['author']   = esc_html__( 'Articles Posted by %s', 'relish' ); // text for an author page
			$text['404']	  = esc_html__( 'Error 404', 'relish' ); // text for the 404 page

			$page_title = "";

			if ( is_404() ) {
				$page_title = esc_html__( '404 Page', 'relish' );
			} else if ( is_search() ) {
				$page_title = esc_html__( 'Search', 'relish' );
			} else if ( is_front_page() ) {
				$page_title = esc_html__( 'Home', 'relish' );
			} else if ( is_category() ) {
				$cat = get_category( get_query_var( 'cat' ) );
				$cat_name = isset( $cat->name ) ? $cat->name : '';
				$page_title = sprintf( $text['category'], $cat_name );
			} else if ( is_tag() ) {
				$page_title = sprintf( $text['tag'], single_tag_title( '', false ) );
			} elseif ( is_day() ) {
				echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
				echo sprintf( $link, get_month_link( get_the_time( 'Y' ),get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $delimiter;
				$page_title = get_the_time( 'd' );

			} elseif ( is_month() ) {
				$page_title = get_the_time( 'F' );

			} elseif ( is_year() ) {
				$page_title = get_the_time( 'Y' );

			} elseif ( has_post_format() && ! is_singular() ) {
				$page_title = get_post_format_string( get_post_format() );
			} else if ( is_tax( array( 'cws_portfolio_cat', 'cws_staff_member_department', 'cws_staff_member_position' ) ) ) {
				$tax_slug = get_query_var( 'taxonomy' );
				$term_slug = get_query_var( $tax_slug );
				$tax_obj = get_taxonomy( $tax_slug );
				$term_obj = get_term_by( 'slug', $term_slug, $tax_slug );

				$singular_tax_label = isset( $tax_obj->labels ) && isset( $tax_obj->labels->singular_name ) ? $tax_obj->labels->singular_name : '';
				$term_name = isset( $term_obj->name ) ? $term_obj->name : '';
				$page_title = $singular_tax_label . ' ' . $term_name ;
			} elseif ( is_archive() ) {
				$post_type = get_post_type();
				$post_type_obj = get_post_type_object( $post_type );
				$post_type_name = isset( $post_type_obj->label ) ? $post_type_obj->label : '';
				$page_title = $post_type_name ;
			} else if ( relish_is_woo() ) {
				$page_title = woocommerce_page_title( false );
			} else if (get_post_type() == 'cws_portfolio') {
				$portfolio_slug = relish_get_option('portfolio_slug');
				$post_type = get_post_type();
				$post_type_obj = get_post_type_object( $post_type );
				$post_type_name = isset( $post_type_obj->labels->menu_name ) ? $post_type_obj->labels->menu_name : '';
				$page_title = !empty($portfolio_slug) ? $portfolio_slug : $post_type_name ;
			}else if (get_post_type() == 'cws_staff') {
				$stuff_slug = relish_get_option('staff_slug');
				$post_type = get_post_type();
				$post_type_obj = get_post_type_object( $post_type );
				$post_type_name = isset( $post_type_obj->labels->menu_name ) ? $post_type_obj->labels->menu_name : '';
				$page_title = !empty($stuff_slug) ? $stuff_slug : $post_type_name ;
			}else {
				$blog_title = relish_get_option('blog_title');
				$page_title = (!is_page() && !empty($blog_title)) ? $blog_title : get_the_title();
			}
				$breadcrumbs = "";
				if ( $show_breadcrumbs ){
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						$breadcrumbs = yoast_breadcrumb( "<nav class='bread-crumbs'>", '</nav>', false );
					} else {
						ob_start();
						relish_dimox_breadcrumbs();
						$breadcrumbs = ob_get_clean();
					}
				}

				$page_title = esc_html($page_title);

			/* !Breadcrumbs!  */

			if (($customize_headers == 1) && is_single() || (($customize_headers == 1) && (get_post_type() == 'cws_portfolio')) || (($customize_headers == 1) && (relish_is_woo())) || (($customize_headers == 1) && (is_category())) || (($customize_headers == 1) && (is_search()))
				 || (($customize_headers == 1) && (is_archive()))
				) {

				if ( isset( $bg_header_url ) ){


					if ( $bg_header_use_pattern && !empty( $bg_header_pattern_image ) && isset( $bg_header_pattern_image['src'] ) && !empty( $bg_header_pattern_image['src'] ) ){
						$bg_header = true;
						$bg_header_pattern_image_src = $bg_header_pattern_image['src'];
						$bg_header_html .= "<div class='bg_layer' style='background-image:url(" . $bg_header_pattern_image_src . ");".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
					if ( $bg_header_color_overlay_type == 'color' && !empty( $bg_header_overlay_color ) ){
						$bg_header = true;
						$bg_header_html .= "<div class='bg_layer' style='background-color:" . $bg_header_overlay_color . ";" . ( isset( $bg_header_color_overlay_opacity ) ? "opacity:$bg_header_color_overlay_opacity" : "" ) . ";".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
					else if ( $bg_header_color_overlay_type == 'gradient' ){
						$bg_header = true;
						$bg_header_gradient_rules = relish_render_gradient_rules( array( 'settings' => $bg_header_gradient_settings ) );
						$bg_header_html .= "<div class='bg_layer' style='$bg_header_gradient_rules" . ( !empty( $bg_header_color_overlay_opacity ) ? "opacity:$bg_header_color_overlay_opacity" : "" ) . ";".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
				}
				if ( !empty( $bg_header_url ) ){
					echo "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.esc_attr($font_color).';"' : '').">";
							if($bg_header_parallaxify){
								echo '<div class="cws_parallax_section" '.esc_attr($bg_header_parallaxify_atts).'>';
							}
							wp_enqueue_script ('parallax');
									if($bg_header_parallaxify){
										echo '<div class="layer" data-depth="1.00">';
									}
									if ( !empty( $page_title ) || (!empty( $breadcrumbs ) && $show_breadcrumbs ) ){
										echo "<section" . ( !empty( $page_title_section_atts ) ? $page_title_section_atts : "" ) . ">";
											echo "<div class='container'" . ( !empty( $page_title_container_styles ) ? " style='$page_title_container_styles'" : "" ) . ">";
												echo !empty( $page_title ) ? "<div class='title'><h1>$page_title</h1></div>" : "";
												echo (!empty( $breadcrumbs ) && $show_breadcrumbs) ? $breadcrumbs : "";
											echo "</div>";
										echo "</section>";
									}
								echo sprintf("%s", $bg_header_html);
								echo "<div class='stat_img_cont' style='". ($bg_header_use_blur == 1 ? $bg_header_use_blur_style : '') . ($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '') ."background-image: url(".$bg_header_url.");background-size: cover;background-position: center center;'></div>";
							if($bg_header_parallaxify){
							echo "</div>";
						} 
						if($bg_header_parallaxify){
							echo "</div>";
						}
					echo '</div>';

				}else{
					echo "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.$font_color.';"' : '').">";
						if ( !empty( $page_title ) || (!empty( $breadcrumbs ) && $show_breadcrumbs ) ){
							echo "<section" . ( !empty( $page_title_section_atts ) ? $page_title_section_atts : "" ) . ">";
								echo "<div class='container'" . ( !empty( $page_title_container_styles ) ? " style='$page_title_container_styles'" : "" ) . ">";
									echo !empty( $page_title ) ? "<div class='title'><h1>$page_title</h1></div>" : "";
									echo (!empty( $breadcrumbs ) && $show_breadcrumbs) ? $breadcrumbs : "";
								echo "</div>";
							echo "</section>";
						}
						echo sprintf("%s", $bg_header_html);
					echo '</div>';
				}

			}
			else{
				if (!$bg_header_feature) {
					$bg_header = false;
				}

				echo !empty($bg_header_url) && $bg_header_feature ? "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.esc_attr($font_color).';"' : '').">" : '';
					if ( !empty( $page_title ) || (!empty( $breadcrumbs ) && $show_breadcrumbs ) ){
						echo "<section" . ( !empty( $page_title_section_atts ) ? $page_title_section_atts : "" ) . ">";
							echo "<div class='container'" . ( !empty( $page_title_container_styles ) ? " style='$page_title_container_styles'" : "" ) . ">";
								echo !empty( $page_title ) ? "<div class='title'><h1>$page_title</h1></div>" : "";
								echo (!empty( $breadcrumbs ) && $show_breadcrumbs) ? $breadcrumbs : "";
							echo "</div>";
						echo "</section>";
					}
					echo sprintf("%s", $bg_header_html);
					echo !empty($bg_header_url) && $bg_header_feature ? "<div class='stat_img_cont' style='". ($bg_header_use_blur == 1 ? $bg_header_use_blur_style : '') . ($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '') ."background-image: url(".esc_attr($bg_header_url).");background-size: cover;background-position: center center;'></div>" : '';
				echo !empty($bg_header_url) && $bg_header_feature ? '</div>' : '';
			}	
		$page_title_content = ob_get_clean();
		ob_start();
			$bg_header_color_overlay_type = ($customize_headers == 1) ? relish_get_option( 'bg_header_color_overlay_type' ) : '';
			$bg_header_overlay_color = ($customize_headers == 1) ? relish_get_option( 'bg_header_overlay_color' ) : '';
			$bg_header_color_overlay_opacity = ($customize_headers == 1) ? relish_get_option( 'bg_header_color_overlay_opacity' ) : '';
			$bg_header_use_pattern = ($customize_headers == 1) ? relish_get_option( 'bg_header_use_pattern' ) : '';
			$bg_header_pattern_image = ($customize_headers == 1) ? relish_get_option( 'bg_header_pattern_image' ) : '';
			$bg_header_use_blur = ($customize_headers == 1) ? relish_get_option('bg_header_use_blur') : 0;
			$bg_header_blur_intensity = ($customize_headers == 1) ? relish_get_option('bg_header_blur_intensity') : '';
			$font_color = ($customize_headers == 1) ? relish_get_option( "font_color" ) : '';
			$bg_header_parallaxify = ($customize_headers == 1) ? relish_get_option("parallaxify") : '';
			$bg_header_scalar_x = ($customize_headers == 1) ? $parallax_opt_arr["scalar_x"] : '';
			$bg_header_scalar_y = ($customize_headers == 1) ? $parallax_opt_arr["scalar_y"] : '';
			$bg_header_limit_x = ($customize_headers == 1) ? $parallax_opt_arr["limit_x"] : '';
			$bg_header_limit_y = ($customize_headers == 1) ? $parallax_opt_arr["limit_y"] : '';
			$bg_header_options = relish_get_option('header_bg_image');


			if ($bg_header_use_blur == 1) {
				$bg_header_use_blur_style = '-webkit-filter: blur('.$bg_header_blur_intensity.'px);-moz-filter: blur('.$bg_header_blur_intensity.'px);-o-filter: blur('.$bg_header_blur_intensity.'px);-ms-filter: blur('.$bg_header_blur_intensity.'px);filter: blur('.$bg_header_blur_intensity.'px);';
			}
			$bg_header_gradient = relish_get_option('bg_header_settings');
			$bg_header_gradient_settings = relish_render_gradient($bg_header_gradient); // get gradient array

			$bg_header_gradient_settings = ($customize_headers == 1) ? $bg_header_gradient_settings : '';

			$bg_header_color_overlay_opacity = (int)$bg_header_color_overlay_opacity / 100;

			$bg_header_parallaxify_atts = ' data-scalar-x="'.esc_attr($bg_header_scalar_x).'" data-scalar-y="'.esc_attr($bg_header_scalar_y).'" data-limit-y="'.esc_attr($bg_header_limit_y).'" data-limit-x="'.esc_attr($bg_header_limit_x).'"';
			$bg_header_parallaxify_layer_atts = 'position: absolute; z-index: 1; left: -'.esc_attr($bg_header_limit_y).'px; right: -'.esc_attr($bg_header_limit_y).'px; top: -'.esc_attr($bg_header_limit_x).'px; bottom: -'.esc_attr($bg_header_limit_x).'px;';

			$bg_header = false;
			$bg_header_feature = false;
			$bg_header_url = "";
			if ( has_post_thumbnail() && ! relish_is_woo() && ! is_single() && ! (get_post_type() == 'post') && ! (get_post_type() == 'cws_staff') && ! (get_post_type() == 'cws_portfolio') ) {
				$img_object = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$bg_header_url = isset( $img_object[0] ) ? $img_object[0] : '';
				$bg_header = true;
				$bg_header_feature = true;
			} else if ( isset( $bg_header_options['src'] ) && ! empty( $bg_header_options['src'] ) && (!has_post_thumbnail() && ! relish_is_woo() && ! is_single() && ! (get_post_type() == 'post') && ! (get_post_type() == 'cws_staff') && ! (get_post_type() == 'cws_portfolio')) ) {
				$bg_header_url = $bg_header_options['src'];
				$bg_header = true;
			}

			$bg_header_url = esc_url( $bg_header_url );

			$bg_header_html = '';

			if (($customize_headers == 1) && ! relish_is_woo() && ! is_single() && ! (get_post_type() == 'post') && ! (get_post_type() == 'cws_staff') && ! (get_post_type() == 'cws_portfolio')) {

				if ( isset( $bg_header_url ) ){

					if ( $bg_header_use_pattern && !empty( $bg_header_pattern_image ) && isset( $bg_header_pattern_image['src'] ) && !empty( $bg_header_pattern_image['src'] ) ){
						$bg_header = true;
						$bg_header_pattern_image_src = $bg_header_pattern_image['src'];
						$bg_header_html .= "<div class='bg_layer' style='background-image:url(" . $bg_header_pattern_image_src . ");".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
					if ( $bg_header_color_overlay_type == 'color' && !empty( $bg_header_overlay_color ) ){
						$bg_header = true;
						$bg_header_html .= "<div class='bg_layer' style='background-color:" . $bg_header_overlay_color . ";" . ( isset( $bg_header_color_overlay_opacity ) ? "opacity:$bg_header_color_overlay_opacity" : "" ) . ";".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
					else if ( $bg_header_color_overlay_type == 'gradient' ){
						$bg_header = true;
						$bg_header_gradient_rules = relish_render_gradient_rules( array( 'settings' => $bg_header_gradient_settings ) );
						$bg_header_html .= "<div class='bg_layer' style='$bg_header_gradient_rules" . ( !empty( $bg_header_color_overlay_opacity ) ? "opacity:$bg_header_color_overlay_opacity" : "" ) . ";".($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '')."'></div>";
					}
				}

				if ( !empty( $bg_header_url ) ){

					echo "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.esc_attr($font_color).';"' : '').">";
						if($page_title_content){
							echo sprintf("%s", $page_title_content);
						}
						if($bg_header_parallaxify){
							echo '<div class="cws_parallax_section" '.esc_attr($bg_header_parallaxify_atts).'>';
						}
							wp_enqueue_script ('parallax');
							if($bg_header_parallaxify){
								echo '<div class="layer" data-depth="1.00">';
							}
							
								echo sprintf("%s", $bg_header_html);
								echo "<div class='stat_img_cont' style='". ($bg_header_use_blur == 1 ? $bg_header_use_blur_style : '') . ($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '') ."background-image: url(".$bg_header_url.");background-size: cover;background-position: center center;'></div>";
							if($bg_header_parallaxify){
								echo '</div>';
							}
						if($bg_header_parallaxify){
							echo  '</div>';
						}		
					echo '</div>';

				}else{
					echo "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.$font_color.';"' : '').">";
						if($page_title_content){
							echo sprintf("%s", $page_title_content);
						}						
						echo sprintf("%s", $bg_header_html);
					echo '</div>';
				}

			}else{
				if (!$bg_header_feature) {
					$bg_header = false;
				}
				echo !empty($bg_header_url) && $bg_header_feature ? "<div class='bg_page_header'".(!empty($font_color) ? ' style="color:'.esc_attr($font_color).';"' : '').">" : '';
					if($page_title_content){
						echo sprintf("%s", $page_title_content);
					}
					
					echo sprintf("%s", $bg_header_html);
					echo !empty($bg_header_url) && $bg_header_feature ? "<div class='stat_img_cont' style='". ($bg_header_use_blur == 1 ? $bg_header_use_blur_style : '') . ($bg_header_parallaxify ? $bg_header_parallaxify_layer_atts : '') ."background-image: url(".esc_attr($bg_header_url).");background-size: cover;background-position: center center;'></div>" : '';
				echo !empty($bg_header_url) && $bg_header_feature ? '</div>' : '';
			}

		$page_header_content = ob_get_clean();

		ob_start();
			$is_revslider_active = is_plugin_active( 'revslider/revslider.php' );
			$cws_revslider_content = "";
			$slider_type = "none";
			if ( is_front_page() ){
				$slider_type = relish_get_option( 'home-slider-type' );
				switch( $slider_type ){
					case 'img-slider':
						$bg_header = false;
						if ( is_page() ){
							$slider_settings = relish_get_page_meta_var( "slider" );
							if ( isset( $slider_settings['slider_override'] ) && $slider_settings['slider_override'] ){
								$slider_options = isset( $slider_settings['slider_options'] ) ? $slider_settings['slider_options'] : array();
							}
							else{
								$slider_options = relish_get_option( 'home-header-slider-options' );
							}
						}
						else if ( is_home() ){
							$slider_options = relish_get_option( 'home-header-slider-options' );
						}
						$slider_options = htmlspecialchars_decode($slider_options, ENT_QUOTES);
						if (!empty($slider_options)) {
							$bg_header = true;
						}
						echo do_shortcode( $slider_options );
						$show_page_title = !empty( $slider_options ) ? false : $show_page_title;
						break;
					case 'video-slider':
						$bg_header = false;
						$video_slider_settings = relish_get_option('slidersection-start');

						$slider_shortcode = $video_slider_settings[ 'slider_shortcode' ];
						$slider_switch = $video_slider_settings[ 'slider_switch' ];
						$video_type = $video_slider_settings[ 'video_type' ];
						$set_video_header_height = $video_slider_settings[ 'set_video_header_height' ];
						$video_header_height = $video_slider_settings[ 'video_header_height' ];
						$sh_source = $video_slider_settings[ 'sh_source' ];
						$youtube_source = $video_slider_settings[ 'youtube_source' ];
						$vimeo_source = $video_slider_settings[ 'vimeo_source' ];
						$color_overlay_type = $video_slider_settings[ 'color_overlay_type' ];
						$overlay_color = $video_slider_settings[ 'overlay_color' ];
						$color_overlay_opacity = $video_slider_settings[ 'color_overlay_opacity' ];
						$use_pattern = $video_slider_settings[ 'use_pattern' ];
						$pattern_image = $video_slider_settings[ 'pattern_image' ];

						$video_header_height = $set_video_header_height == "1" ? $video_header_height : false;

						$gradient_video_set = $video_slider_settings["slider_gradient_settings"];

						$gradient_settings = relish_render_gradient($gradient_video_set);

						$sh_source = isset( $sh_source['src'] ) && !empty( $sh_source['src'] ) ? $sh_source['src'] : "";
						$color_overlay_opacity = (int)$color_overlay_opacity / 100;
						$has_video_src = false;
						$header_video_atts = "";
						$header_video_class = "fs_video_bg";
						$header_video_styles = "";
						$header_video_html = "";
						$uniqid = uniqid( 'video-' );
						$uniqid_esc = esc_attr( $uniqid );
						switch ( $video_type ){
							case 'self_hosted':
								if ( !empty( $sh_source ) ){
									$has_video_src = true;
									$header_video_class .= " cws_self_hosted_video";
									$header_video_html .= "<video class='self_hosted_video' src='$sh_source' autoplay='autoplay' loop='loop' muted='muted'></video>";
								}
								break;
							case 'youtube':
								if ( !empty( $youtube_source ) ){
									wp_enqueue_script ('cws_YT_bg');
									$has_video_src = true;
									$header_video_class .= " cws_Yt_video_bg loading";
									$header_video_atts .= " data-video-source='$youtube_source' data-video-id='$uniqid'";
									$header_video_html .= "<div id='$uniqid_esc'></div>";
								}
								break;
							case 'vimeo':
								if ( !empty( $vimeo_source ) ){
									wp_enqueue_script ('vimeo');
									wp_enqueue_script ('cws_self&vimeo_bg');
									$has_video_src = true;
									$header_video_class .= " cws_Vimeo_video_bg";
									$header_video_atts .= " data-video-source='$vimeo_source' data-video-id='$uniqid'";
									$header_video_html .= "<iframe id='$uniqid_esc' src='" . $vimeo_source . "?api=1&player_id=$uniqid' frameborder='0'></iframe>";
								}
								break;
						}
						if ( $has_video_src ){
							$bg_header = true;
							if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['src'] ) && !empty( $pattern_image['src'] ) ){
								$pattern_img_src = $pattern_image['src'];
								$header_video_html .= "<div class='bg_layer' style='background-image:url(" . $pattern_img_src . ")'></div>";
							}
							if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
								$header_video_html .= "<div class='bg_layer' style='background-color:" . $overlay_color . ";" . ( isset( $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : "" ) . "'></div>";
							}
							else if ( $color_overlay_type == 'gradient' ){
								$gradient_rules = relish_render_gradient_rules( array( 'settings' => $gradient_settings ) );
								$header_video_html .= "<div class='bg_layer' style='$gradient_rules" . ( !empty( $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : "" ) . "'></div>";
							}
						}

						$header_video_atts .= !empty( $header_video_class ) ? " class='" . trim( $header_video_class ) . "'" : "";
						$header_video_atts .= !empty( $header_video_styles ) ? " style='". esc_attr($header_video_styles) ."'" : "";


						if ( !empty( $slider_shortcode ) && $has_video_src && $slider_switch == 1 ){
							$bg_header = true;
							echo "<div class='fs_video_slider'>";
							if ( $is_revslider_active ) {
								echo  do_shortcode( $slider_shortcode );
							} else {
								echo do_shortcode( "[cws_sc_msg_box type='warning' is_closable='1' text='Install and activate Slider Revolution plugin'][/cws_sc_msg_box]" );
							}
								echo '<div ' . $header_video_atts . '>';
								echo sprintf("%s", $header_video_html);
								echo '</div>';
								echo '</div>';
						} elseif ( $has_video_src && $slider_switch == 0 ) {
							$bg_header = true;
							$header_video_fs_view = $video_header_height == false ? 'header_video_fs_view' : '';
							$video_height_coef = $video_header_height == false ? '' : " data-wrapper-height='".(960 / $video_header_height)."'";
							$video_header_height = $video_header_height == false ? '' : "style='height:" . $video_header_height ."px'";
							echo "<div class='fs_video_slider ". esc_attr( $header_video_fs_view ) ."' " . $video_header_height . " ". $video_height_coef .">";
							echo '<div ' . $header_video_atts . '>';
							echo sprintf("%s", $header_video_html);
							echo '</div>';
							echo '</div>';
						}elseif ( ! empty( $slider_shortcode ) && $slider_switch == 1 && ! $has_video_src ) {
							$bg_header = true;
							if ( $is_revslider_active ) {
								echo  do_shortcode( $slider_shortcode );
							} else {
								echo do_shortcode( "[cws_sc_msg_box type='warning' is_closable='1' text='Install and activate Slider Revolution plugin'][/cws_sc_msg_box]" );
							}
						}else{
							$bg_header = true;
							if ( $has_video_src ){
								echo "<div class='fs_video_slider'></div>";
							}
						}

						break;
					case 'stat-img-slider':

						$static_img_section = relish_get_option('static_img_section');
						$image_options = $static_img_section['home-header-image-options'];

						$bg_header = false;
						$set_img_header_height = !empty($static_img_section['set_static_image_height']) ? $static_img_section['set_static_image_height'] : "";
						$img_header_height = !empty($static_img_section['static_image_height']) ? $static_img_section['static_image_height'] : "";

						$color_overlay_type = !empty($static_img_section['img_header_color_overlay_type']) ? $static_img_section['img_header_color_overlay_type'] : "";
						$overlay_color = !empty($static_img_section['img_header_overlay_color']) ? $static_img_section['img_header_overlay_color'] : "";
						$color_overlay_opacity = !empty($static_img_section['img_header_color_overlay_opacity']) ? $static_img_section['img_header_color_overlay_opacity'] : "";
						$use_pattern = !empty($static_img_section['img_header_use_pattern']) ? $static_img_section['img_header_use_pattern'] : "";
						$pattern_image = !empty($static_img_section['img_header_pattern_image']) ? $static_img_section['img_header_pattern_image'] : "";

						$img_header_height = $set_img_header_height == "1" ? $img_header_height : false;
						$gradient_settings = !empty($static_img_section["img_header_gradient_settings"]) ? relish_render_gradient( $static_img_section["img_header_gradient_settings"] ) : ""; // get gradient array
						$color_overlay_opacity = !empty($color_overlay_opacity) ? (int)$color_overlay_opacity / 100 : "";

						$parallax_header_opt = !empty($static_img_section['img_header_parallax_options']) ? $static_img_section['img_header_parallax_options'] : "";
						$img_header_parallaxify = !empty($static_img_section["img_header_parallaxify"]) ? $static_img_section["img_header_parallaxify"] : "";
						$img_header_scalar_x = !empty($parallax_header_opt["img_header_scalar_x"]) ? $parallax_header_opt["img_header_scalar_x"] : "";
						$img_header_scalar_y = !empty($parallax_header_opt["img_header_scalar_y"]) ? $parallax_header_opt["img_header_scalar_y"] : "";
						$img_header_limit_x = !empty($parallax_header_opt["img_header_limit_x"]) ? $parallax_header_opt["img_header_limit_x"] : "";
						$img_header_limit_y = !empty($parallax_header_opt["img_header_limit_y"]) ? $parallax_header_opt["img_header_limit_y"] : "";

						$img_header_parallaxify_atts = ' data-scalar-x="'.$img_header_scalar_x.'" data-scalar-y="'.$img_header_scalar_y.'" data-limit-y="'.$img_header_limit_y.'" data-limit-x="'.$img_header_limit_x.'"';
						$img_header_parallaxify_layer_atts = 'position: absolute; z-index: 1; left: -'.$img_header_limit_y.'px; right: -'.$img_header_limit_y.'px; top: -'.$img_header_limit_x.'px; bottom: -'.$img_header_limit_x.'px;';				

						$default_img = false;
						$override_img = false;
						$img_url = "";

						$header_img_html = '';
						if ( isset( $image_options['src'] ) ){
							if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['src'] ) && !empty( $pattern_image['src'] ) ){
								$pattern_img_src = $pattern_image['src'];
								$header_img_html .= "<div class='bg_layer' style='background-image:url(" . $pattern_img_src . ");".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
							if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
								$header_img_html .= "<div class='bg_layer' style='background-color:" . $overlay_color . ";" . ( isset( $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : "" ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
							else if ( $color_overlay_type == 'gradient' ){
								$gradient_rules = relish_render_gradient_rules( array( 'settings' => $gradient_settings ) );
								$header_img_html .= "<div class='bg_layer' style='$gradient_rules" . ( !empty( $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : "" ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
						}

						if ( isset( $image_options['src'] ) ) {
							$bg_header = true;
							$header_img_fs_view = $img_header_height== false ? 'header_video_fs_view' : '';
							$header_img_height_coef = $img_header_height == false ? '' : " data-wrapper-height='".(960 / $img_header_height)."'";
							$img_header_height = $img_header_height == false ? '' : "style='height:" . esc_attr($img_header_height) ."px'";

							echo "<div class='fs_img_header " . esc_attr( $header_img_fs_view ) ."' " . $img_header_height . " ". $header_img_height_coef .">";
							if($img_header_parallaxify){
								echo '<div class="cws_parallax_section" '.esc_attr($img_header_parallaxify_atts).'>';
							}
							
								wp_enqueue_script ('parallax');
								if($img_header_parallaxify){
									echo '<div class="layer" data-depth="1.00">';
								}
								
									echo sprintf("%s", $header_img_html);
									echo "<div class='stat_img_cont' style='". ($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '') ."background-image: url(".$image_options['src'].");background-size: cover;background-position: center center;'></div>";
								if($img_header_parallaxify){
									echo '</div>';
								}
								if($img_header_parallaxify){
									echo '</div>';
								}
							
							echo '</div>';

						}

					break;
					default:
						echo !empty($page_header_content) ? $page_header_content : '';
				}
			}
			else if ( is_page() ){

				$slider_settings = relish_get_page_meta_var( "slider" );

				if ( $slider_settings['slider_override'] ){
					$bg_header = true;
					$slider_options = htmlspecialchars_decode($slider_settings['slider_options'], ENT_QUOTES);
					echo do_shortcode( $slider_options );
				}

				if ( !$slider_settings['slider_override'] ){
					echo !empty($page_header_content) ? $page_header_content : '';

				}
			}

		$slider_content = ob_get_clean();

		ob_start();
			header_menu_and_logo ();
		$header_content = ob_get_clean();


		if (relish_get_option( 'menu-stick' ) == 1) {
			echo "<div class='sticky_header'>";
				echo header_menu_and_logo ();
			echo "</div>";
		}
	

		echo "<script type='text/javascript'>window.header_after_slider=false;</script>";
		echo ( !empty($top_panel_content) || !empty($header_content) ) ? '<div class="header_wrapper_container'.((($show_header_outside_slider == 1) && ($bg_header == true) ) ? ' header_outside_slider' : '').'">' : '';
		echo sprintf("%s", $top_panel_content);
		echo sprintf("%s", $header_content);
		echo ( !empty($top_panel_content) || !empty($header_content) ) ? '</div>' : '';
		echo sprintf("%s", $slider_content);

		echo ( ! ( is_front_page() ) && empty( $slider_content )) ? $page_title_content :  '';
	}
}
/* END THEME HEADER */
/* header address form */
function relish_render_header_adrs_form () {
	$out = '';
	$address_groups_option = relish_get_option( 'address_group' );
	if ( !empty( $address_groups_option ) && is_array( $address_groups_option ) ) {
		$address_groups = array();
		for ( $i=0; $i<count( $address_groups_option ); $i++ ) {
			if ( isset( $address_groups_option[$i]['icon'] ) && !empty( $address_groups_option[$i]['icon'] ) ) {
				$address_groups[] = $address_groups_option[$i];
			}
		}
		foreach ( $address_groups as $address_group ) {
			$first_line = isset( $address_group['first_line'] ) ? esc_html($address_group['first_line']) : '';
			$icon = $address_group['icon'];
			$second_line = isset( $address_group['second_line'] ) ? esc_html($address_group['second_line']) : '';
		}

	}
	return $out;
}
	
/* end header address form */

/**/

if (!function_exists('header_menu_and_logo')) {
function header_menu_and_logo (){
	$stick_menu = relish_get_option( 'menu-stick' );
	$stick_menu =  $stick_menu == 1 ? true : false;
	ob_start();
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			woocommerce_mini_cart();
		}
	$woo_mini_cart = ob_get_clean();

	ob_start();
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
					<a class="woo_icon" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_html_e( 'View your shopping cart', 'relish' ); ?>"><i class='woo_mini-count fa fa-shopping-cart'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></i></a>
				<?php
		}
	$woo_mini_icon = ob_get_clean();

	/***** Logo Settings *****/
	$logo =relish_get_option('logo');
	if(!empty($logo)){
		$logo['height'] = wp_get_attachment_image_src($logo['id'], 'full');
		$logo['height'] = $logo['height'][2];
		$logo['width'] = wp_get_attachment_image_src($logo['id'], 'full');
		$logo['width'] = $logo['width'][1];		
	}

	$logo_is_high_dpi = relish_get_option('logo');
	$logo_is_high_dpi = $logo_is_high_dpi['logo_is_high_dpi'];
		$logo_lr_spacing = "";
		$logo_tb_spacing = "";
	if ( isset( $logo['src'] ) ) {
		$logo_hw = relish_get_option('logo-dimensions');
		$logo_m = relish_get_option('logo-margin');
		$cwsfi_args = array();
		if (is_array($logo_hw)){
			foreach ($logo_hw as $key => $value) {
				if ( !empty($value) ){
					$cwsfi_args[$key] =(int) $value;
					$cwsfi_args['crop'] = true;
				}
			}
		}
			
		$logo_src = '';
		$main_logo_height = '';
		if ( is_array( $logo_m ) ) {
			$logo_lr_spacing .= ( isset($logo_m['margin-left']) && $logo_m['margin-left'] != '' ? 'margin-left:' . (int) $logo_m['margin-left'] . 'px;' : '' ) . ( isset( $logo_m['margin-right'] ) && $logo_m['margin-right'] != '' ? 'margin-right:' . (int) $logo_m['margin-right'] . 'px;' : '' ) . ( isset( $logo_m['margin-top'] ) && $logo_m['margin-top'] != '' ? 'margin-top:' . (int) $logo_m['margin-top'] . 'px;' : '' ) . ( isset( $logo_m['margin-bottom'] ) && $logo_m['margin-bottom'] != '' ? 'margin-bottom:' . (int) $logo_m['margin-bottom'] . 'px;' : '' );
			$logo_tb_spacing .= ( isset( $logo_m['margin-top'] ) && $logo_m['margin-top'] != '' ? 'padding-top:' . (int) $logo_m['margin-top'] . 'px;' : '' ) . ( isset( $logo_m['margin-bottom'] ) && $logo_m['margin-bottom'] != ''  ? 'padding-bottom:' . (int) $logo_m['margin-bottom'] . 'px;' : '' );
		}

		$logo_retina_thumb_exists = false;
		$logo_retina_thumb_url = "";
		if ( isset( $logo['src'] ) && ( ! empty( $logo['src'] ) ) ) {
			if ( empty( $cwsfi_args ) ) {
				if ( $logo_is_high_dpi ) {
					$thumb_obj = cws_thumb( $logo['src'],array( 'width' => floor( (int) $logo['width'] / 2 ), 'crop' => true ),false );
					$main_logo_height = $thumb_obj[2];
					$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
					$logo_src = $thumb_path_hdpi;
				} else {
					$main_logo_height = $logo["height"];
					$logo_src = " src='".esc_url( $logo['src'] )."' data-no-retina";
				}
			} else {
				$thumb_obj = cws_thumb( $logo['src'],$cwsfi_args,false );
				$main_logo_height =  !empty($cwsfi_args["height"]) ? $cwsfi_args["height"] : '';
				if(!empty($thumb_obj[3])){
					$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				}
				if(isset($thumb_path_hdpi) && !empty($thumb_path_hdpi)){
					$logo_src = $thumb_path_hdpi;
				}
			}
		}
	}

	$logo_lr_spacing .= !empty($main_logo_height) ? 'height:' . $main_logo_height . 'px;' : '';
	$main_logo_height = !empty($main_logo_height) ? ' style="height:' . $main_logo_height . 'px;"' : '';
		/***** \Logo Settings *****/

	$logo_sticky = relish_get_option( 'logo_sticky' );
	if(!empty($logo_sticky)){
		$logo_sticky_src = '';
		$logo_sticky_is_high_dpi = relish_get_option( 'logo_sticky' );
		$logo_sticky_is_high_dpi = $logo_sticky_is_high_dpi['logo_sticky_is_high_dpi'];

		$logo_sticky['height'] = wp_get_attachment_image_src($logo_sticky['id'], 'full');
		$logo_sticky['height'] = $logo_sticky['height'][2];

		$logo_sticky['width'] = wp_get_attachment_image_src($logo_sticky['id'], 'full');
		$logo_sticky['width'] = $logo_sticky['width'][1];		
	}


	if ( isset( $logo_sticky['src'] ) && ( ! empty( $logo_sticky['src'] ) ) ) {
		$logo_sticky_src = '';
		if ( $logo_sticky_is_high_dpi ) {
			$thumb_obj = cws_thumb( $logo_sticky['src'],array( 'width' => floor( (int) $logo_sticky['width'] / 2 ), 'crop' => true ),false );
			$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
			$logo_sticky_src = $thumb_path_hdpi;
		} else {
			$logo_sticky_src = " src='".esc_url( $logo_sticky['src'] )."' data-no-retina";
		}
	}


	$logo_mobile = relish_get_option( 'logo_mobile' );
	$logo_mobile_src = '';
	if(!empty($logo_mobile)){
		$logo_mobile['height'] = wp_get_attachment_image_src($logo_mobile['id'], 'full');
		$logo_mobile['height'] = $logo_mobile['height'][2];

		$logo_mobile['width'] = wp_get_attachment_image_src($logo_mobile['id'], 'full');
		$logo_mobile['width'] = $logo_mobile['width'][1];		
	}



	$logo_mobile_is_high_dpi = relish_get_option( 'logo_mobile_is_high_dpi' );
	if ( isset( $logo_mobile['src'] ) && ( ! empty( $logo_mobile['src'] ) ) ) {
		$logo_mobile_src = '';
		if ( $logo_mobile_is_high_dpi ) {
			$thumb_obj = cws_thumb( $logo_mobile['src'],array( 'width' => floor( (int) $logo_mobile['width'] / 2 ), 'crop' => true ),false );
			$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
			$logo_mobile_src = $thumb_path_hdpi;

		} else {
			$logo_mobile_src = " src='".esc_url( $logo_mobile['src'] )."' data-no-retina";
		}
	}

	/***** Logo Position *****/
	$logo_position = relish_get_option('logo-position');
	$header_class = 'site_header';
	$header_class .= $stick_menu ? ' sticky_enable' : '';
	if ( isset( $logo_position ) && ! empty( $logo_position ) ) {
		$header_class .= ' logo-' . sanitize_html_class( $logo_position );
	}
	if ( isset( $logo_sticky_src ) && ! empty( $logo_sticky_src ) ) {
		$header_class .= ' custom_sticky_logo';
	}
	if ( isset( $logo_mobile_src ) && ! empty( $logo_mobile_src ) ) {
		$header_class .= ' custom_mobile_logo';
	}
	$header_class .= !empty( $slider_content ) && ($show_header_outside_slider == '1') && ($bg_header == true) ? " with_background" : "";

	$sandwich_menu = relish_get_option( 'show_sandwich_menu' );
	$header_class .= !empty ($sandwich_menu) ? ' active-sandwich-menu' : ' none-sandwich-menu';
	/***** \Logo Position *****/

	/***** Menu Position *****/
	global $current_user;
	$menu_locations = get_nav_menu_locations();
		$menu_position = relish_get_option("menu-position");


	/***** \Menu Position *****/
	ob_start();

		echo "<div class='header_cont' >";
			
			$logo_exists = isset( $logo["src"] ) && ( !empty( $logo["src"] ) );
			
			$logo_center = isset($logo_position) && !empty($logo_position) && $logo_position == "center";
			$header_style = '';
			$header_margin = relish_get_option( 'header_margin' );
			$header_top_margin = (int)$header_margin['margin-top'];
			$header_bot_margin = (int)$header_margin['margin-bottom'];
			$header_style .= ! empty( $header_top_margin ) ? 'padding-top: '.$header_top_margin.'px;' : '' ;
			$header_style .= ! empty( $header_bot_margin ) ? 'padding-bottom: '.$header_bot_margin.'px;' : '' ;
				if (relish_get_option('show_header_outside_slider') == '1') {
					global $cws_theme_funcs;

					$header_outside_slider_style = '';
					$header_outside_slider_bg_color = $cws_theme_funcs->cws_Hex2RGB( relish_get_option('theme-custom-color') );
					$header_outside_slider_bg_color_opacity = relish_get_option('header_outside_slider_bg_opacity')/100;
					$header_outside_slider_style .= 'background-color:rgba(255,255,255,'.$header_outside_slider_bg_color_opacity.');';

					$header_style .= $header_outside_slider_style;
				}
			?>

			<header <?php echo !empty($header_class) ? "class='$header_class'" : ""; ?>>
				<div class="header_box" <?php echo ! empty( $header_style ) ? ' style="'.esc_attr($header_style).'"' : ''; ?>>
				<div class="container">
					<?php
						if ( $logo_exists){					
							if(relish_get_option('logo-position') != "in-menu"){
								echo empty($sandwich_menu) ? '<div class="header-first-floor">' : '';
							
							?>
							<div class="header_logo_part" role="banner" <?php echo isset( $logo_position ) && !empty( $logo_position ) && $logo_position == 'center' && ! empty( $logo_tb_spacing ) ? " style='".esc_attr($logo_tb_spacing)."'" : ''; ?>>
								<a <?php echo ( ! empty( $logo_lr_spacing ) ? " style='".esc_attr($logo_lr_spacing)."'" : '') ?> class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
									<?php echo ! empty( $logo_sticky_src ) ? '<img ' . $logo_sticky_src . " class='logo_sticky' alt />" : '';?>
									<?php echo ! empty( $logo_mobile_src ) ? '<img ' . $logo_mobile_src . " class='logo_mobile' alt />" : '';?>
									<img <?php echo sprintf("%s", $logo_src); echo sprintf("%s", $main_logo_height) ?> alt /></a>
								</div>
							<?php
								echo empty($sandwich_menu) ? '</div>' : '';
							}
							?>
								<?php
						}else{
							if(relish_get_option('logo-position') != "in-menu"){
								echo empty($sandwich_menu) ? '<div class="header-first-floor">' : '';
							?>
							<div class="header_logo_part" role="banner" <?php echo isset( $logo_position ) && !empty( $logo_position ) && $logo_position == 'center' && ! empty( $logo_tb_spacing ) ? " style='".esc_attr($logo_tb_spacing)."'" : ''; ?>>
								<a <?php echo ( ! empty( $logo_lr_spacing ) ? " style='".esc_attr($logo_lr_spacing)."'" : '') ?> class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
									<?php echo ! empty( $logo_sticky_src ) ? '<img ' . $logo_sticky_src . " class='logo_sticky' alt />" : '';?>
										<?php echo ! empty( $logo_mobile_src ) ? '<img ' . $logo_mobile_src . " class='logo_mobile' alt />" : '';?>
									<h1 class='header_site_title'><?php echo get_bloginfo( 'name' ); ?></h1>
								</a>
							</div>

								<?php
									echo empty($sandwich_menu) ? '</div>' : '';
							}
							else{
								$logo_t = relish_get_option('logo-margin-in-menu');		
								$logo_i = relish_get_option('logo-margin');		
								$logo_title_spacing = '';
								if ( is_array( $logo_t ) && relish_get_option('logo-position') == "in-menu" ) {
									$logo_title_spacing = ( isset( $logo_t['margin-top'] ) && $logo_t['margin-top'] != '' ? 'margin-top:' . (int) $logo_t['margin-top'] . 'px;' : '' ) . ( isset( $logo_t['margin-bottom'] ) && $logo_t['margin-bottom'] != ''  ? 'margin-bottom:' . (int) $logo_t['margin-bottom'] . 'px;' : '' );
									$logo_title_spacing .= ( isset( $logo_t['margin-left'] ) && $logo_t['margin-left'] != '' ? 'margin-left:' . (int) $logo_t['margin-left'] . 'px;' : '' ) . ( isset( $logo_t['margin-right'] ) && $logo_t['margin-right'] != ''  ? 'margin-right:' . (int) $logo_t['margin-right'] . 'px;' : '' );
								}
								?>
								<div class="header_logo_part" role="banner" <?php echo isset( $logo_position ) && !empty( $logo_position ) && $logo_position == 'center' && ! empty( $logo_ttitle_spacing ) ? " style='".esc_attr($logo_ttitle_spacing)."'" : ''; ?>>
									<a <?php echo ( ! empty( $logo_title_spacing ) ? " style='".esc_attr($logo_title_spacing)."'" : '') ?> class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
										<?php echo ! empty( $logo_sticky_src ) ? '<img ' . $logo_sticky_src . " class='logo_sticky' alt />" : '';?>
											<?php echo ! empty( $logo_mobile_src ) ? '<img ' . $logo_mobile_src . " class='logo_mobile' alt />" : '';?>
										<h1 class='header_site_title'><?php echo get_bloginfo( 'name' ); ?></h1>
									</a>
								</div>
							<?php	
							}
						}

						if ( isset( $menu_locations['header-menu'] ) && !empty($menu_locations['header-menu']) ) {
							?>
							<div class="header_nav_part" <?php echo !empty( $header_nav_part_atts ) ? trim( $header_nav_part_atts ) : ""; ?>>

								<div class="appoitionment_btn pt">
									<a><span onclick="showAppointment()"><i class="fa fa-calendar"></i>&nbsp;&nbsp;REQUEST AN APPOINTMENT</span></a>
								</div>

								<nav class="main-nav-container <?php echo !empty($menu_position) ? 'a-' . $menu_position : ''; ?><?php echo empty($logo_src) ? ' none-logotype' : ''; ?>">
								
									<div class="mobile_menu_header">
											<?php
												if ( $logo_exists && relish_get_option('logo-position') != "in-menu"){		
														echo empty($sandwich_menu) ? '<div class="header-first-floor">' : '';
											
											?>
											<div class="header_logo_part" role="banner" <?php echo isset( $logo_position ) && !empty( $logo_position ) && $logo_position == 'center' && ! empty( $logo_tb_spacing ) ? " style='".esc_attr($logo_tb_spacing)."'" : ''; ?>>
												<a <?php echo ( ! empty( $logo_lr_spacing ) ? " style='".esc_attr($logo_lr_spacing)."'" : '') ?> class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
													<?php echo ! empty( $logo_sticky_src ) ? '<img ' . $logo_sticky_src . " class='logo_sticky' alt />" : '';?>
													<?php echo ! empty( $logo_mobile_src ) ? '<img ' . $logo_mobile_src . " class='logo_mobile' alt />" : '';?>
													<img <?php echo sprintf("%s", $logo_src); echo sprintf("%s", $main_logo_height) ?> alt /></a>
												</div>
											<?php
												echo empty($sandwich_menu) ? '</div>' : '';
											}
											?>
																				<?php																				
											$position_top_bar = relish_get_option('search_place');
											echo "<div class='row_text_search mobile_search'>"; ?>							
											<?php							

												if ( !empty( $social_links ) || !empty($position_top_bar) || relish_get_option('woo_cart_enable')){
													
													echo "<div class='top_panel_links_mobile'>";
														if(relish_get_option('woo_cart_enable')) {
															echo is_plugin_active( 'woocommerce/woocommerce.php' ) ? "<div class='mini-cart'>$woo_mini_icon$woo_mini_cart</div>" : '';
														} 
														?>
														<?php
												if($position_top_bar != "none"){
													if(relish_get_option('woo_cart_enable')) {
														if($position_top_bar != 'left'){
															echo "<span class='separate-search-icon'>|</span>";
														}
													}
													if($position_top_bar == 'left'){
														echo "</div>";
													}
													echo "<div class='search_icon'>";
													if($position_top_bar == 'left'){
														echo "<span class='separate-search-icon'>|</span>";
													}	
													echo "</div>";
													
												}
													if($position_top_bar != 'left'){
														echo "</div>";
													}
												}
											echo "</div>";

										?>
										
										<i class="mobile_menu_switcher"></i>
									</div>
									<?php
										if($position_top_bar != "none"){
											echo "<div class='search_mobile_menu_cont'>";				
											get_search_form();
											echo "<div class='search_back_button'></div>";
											echo "</div>";	
										}
									?>
									<?php
									ob_start();
									if ($menu_position == 'left' && !empty($sandwich_menu) ) {
										echo '<a href="#" class="menu-bar"><span class="ham"></span></a>';
									}
									wp_nav_menu( array(
										'theme_location'  => 'header-menu',
										'menu_class' => 'main-menu',
										'container' => false,
										'walker' => new Relish_Walker_Nav_Menu(),

										) );
									$menu = ob_get_clean();
									if ( ! empty ( $menu ) ){
										echo sprintf("%s", $menu);
									}

									if ( !empty($sandwich_menu) ) { 
										echo relish_render_header_adrs_form ();

										if ( $menu_position == 'right' || $menu_position == 'center') {	
											echo '<a href="#" class="menu-bar"><span class="ham"></span></a>';
										}			
									}
									
									?>
								</nav>
							</div>
							<?php
						}
					?>
				</div>
			</div>
			<?php
				if((relish_get_option('search_place') == 'right') || (relish_get_option('search_place') == 'left')){
					echo "<div class='search_menu_cont'>";
						echo "<div class='container'>";
							get_search_form();
							echo "<div class='search_back_button'></div>";
						echo "</div>";
					echo "</div>";
				}
			?>
		</header><!-- #masthead -->
	<?php
		echo "</div>";
	$header_content = ob_get_clean();
	if (!empty($header_content)) {

		echo sprintf("%s", $header_content);
	}
}
}

/* THEME FOOTER */
if (!function_exists('relish_page_footer')) {

	function relish_page_footer (){
		$color_bg_copy_footer = relish_get_option('theme_footer_copy_color');
		$color_f_copy_footer = relish_get_option('theme_footer_copy_font_color');

		$footer_bg_img_set = relish_get_option('footer_img_settings');
		$footer_bg_im = relish_get_option('footer_img_settings');
		$footer_bg_im = (!empty($footer_bg_im['footer_bg_im']) ? $footer_bg_im['footer_bg_im'] : "");
		$footer_pattern = relish_get_option('footer_pattern');

		if ( !empty($footer_bg_im["src"]) ) {

			$footer_img_set = '';
			$footer_img_set .= "background-image: url(".$footer_bg_im['src'].");";
			$footer_img_set .= " background-size: ".$footer_bg_img_set['footer_img_size'].";";
			$footer_img_set .= " background-position: ".$footer_bg_img_set['footer_img_pos_x']." ".$footer_bg_img_set['footer_img_pos_y'] .";";
			$footer_img_set .= " background-repeat: ".$footer_bg_img_set['footer_img_repeat'].";";
			$footer_img_set .= " background-attachment: ".$footer_bg_img_set['footer_img_attachment'].";";

		}
		
		$footer_sidebar = is_page() ? relish_get_page_meta_var( array( 'footer', 'footer_sb_top' ) ) : relish_get_option( "footer-sidebar-top" );
		$footer_layout = '';
		$footer_layout = relish_get_option('footer_layouts_columns');
		if ( !empty( $footer_sidebar ) && is_active_sidebar( $footer_sidebar ) ) {
			echo "<footer class='page_footer".(!empty($footer_layout) ? ' footer-columns-'.$footer_layout : " footer-columns-4")."' style='background-color:".(esc_attr(relish_get_option('theme-footer-color')))."; ".(!empty($footer_bg_im['src']) ? $footer_img_set : "" )." color:".(relish_get_option('theme-footer-font-color'))."'>";
				if ( !empty($footer_pattern["src"]) ) {
					echo "<div class='footer-pattern' style='background-image: url(".$footer_pattern['src'].");'></div>";
				}
				echo "<div class='container'>";
					echo "<div class='footer_container'>";
						$GLOBALS['footer_is_rendered'] = true;	
						dynamic_sidebar( $footer_sidebar );
						unset( $GLOBALS['footer_is_rendered'] );
					echo "</div>";
				echo "</div>";
			echo "</footer>";
		}

		$copyrights = esc_html(relish_get_option( "footer_copyrights_text" ));
		$social_links = "";
		$social_links_location = relish_get_option( 'social_links_location' );

		$show_wpml_footer = ( relish_is_wpml_active() ) ? true : false;
		if ( in_array( $social_links_location, array( 'bottom', 'top_bottom' ) ) ) {
			$social_links = relish_render_social_links();
		}
		$ret = '';
		if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			global $wpml_language_switcher;
			$slot = $wpml_language_switcher->get_slot( 'statics', 'footer' );	
			$template = $slot->get_model();
			$ret = $slot->is_enabled();
		}
		ob_start();

		if ( !empty( $copyrights ) ) {
			echo "<div class='copyrights' >$copyrights</div>";
		}
		if ( !empty( $social_links ) || ! empty( $show_wpml_footer ) ) {
			echo "<div class='copyrights_panel'>";
				echo "<div class='copyrights_panel_wrapper'>";
					echo !empty( $social_links ) ? $social_links : "";
					$class_wpml = '';
					if(isset($template['template']) && !empty($template['template'])){
						if($template['template'] == 'wpml-legacy-vertical-list'){
							$class_wpml = 'wpml_language_switch lang_bar '. $template['template'];
						}
						else{
							$class_wpml = 'wpml_language_switch horizontal_bar '.$template['template'];
						}						
					}else{
						$class_wpml = 'lang_bar';
					}

					if ( $show_wpml_footer && !empty($ret) ) : ?>
						<div class="<?php echo esc_attr($class_wpml);?>">
							<?php 
								do_action( 'wpml_footer_language_selector'); 
							?>
						</div>
					<?php 	endif;
				echo "</div>";
			echo "</div>";
		}
	
		$copyrights_content = ob_get_clean();
		if ( !empty( $copyrights_content ) ) {
			echo "<div class='copyrights_area' style='background-color:".(!empty($color_bg_copy_footer) ? esc_attr($color_bg_copy_footer) : "#404b43" )."; color:".(!empty($color_f_copy_footer) ? esc_attr($color_f_copy_footer) : "#e1e1e1" )."'>";
				echo "<div class='container'>";
					echo "<div class='copyrights_container'>";
						echo sprintf("%s", $copyrights_content);
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		echo "<div class='scroll_top animated'><i class='fa fa-angle-up'></i>Top</div>";
		$boxed_layout = ('0' != relish_get_option('boxed-layout') ) ? 'boxed' : '';
		if($boxed_layout){
			echo "</div>";
		}
		
	}
}
/* END THEME FOOTER */


/* WPML STYLE FOOTER COLOR */
function relish_theme_wpml_process_colors(){
	if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
		global $wpml_language_switcher;

		$slot_footer = $wpml_language_switcher->get_slot( 'statics', 'footer' );		
		$style_wpml_footer = $slot_footer->get_model();
		$lang_bar = '';
		if($style_wpml_footer['template'] == 'relish-cws-dropdown'){

			$style_wpml_footer['font_current_hover'] = $style_wpml_footer['font_current_hover'] ? $style_wpml_footer['font_current_hover'] : "inherit";

			$style_wpml_footer['font_other_hover'] = $style_wpml_footer['font_other_hover'] ? $style_wpml_footer['font_other_hover'] : "inherit";

			$lang_bar .= '.wpml-ls-legacy-dropdown a:hover, .wpml-ls-legacy-dropdown a:focus, .wpml-ls-legacy-dropdown .wpml-ls-current-language:hover>a{color:'.$style_wpml_footer['font_current_hover'].'}';

			$lang_bar .= '.wpml-ls-statics-footer a:hover, .wpml-ls-statics-footer a:focus{color:'.$style_wpml_footer['font_other_hover'].'}';
		}
		
		
		return $lang_bar;
	}		
	else{
		return false;
	}
	
}

/* END WPML STYLE FOOTER COLOR */

/* FA ICONS */
function relish_get_all_fa_icons() {
	$meta = get_option('cws_fa');
	if (!empty($meta) || (time() - $meta['t']) > 3600*7 ) {
		global $wp_filesystem;
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$file = get_template_directory() . '/css/font-awesome.css';
		$fa_content = '';
		if ( $wp_filesystem && $wp_filesystem->exists($file) ) {
			$fa_content = $wp_filesystem->get_contents($file);
			if ( preg_match_all( "/fa-((\w+|-?)+):before/", $fa_content, $matches, PREG_PATTERN_ORDER ) ) {
				return $matches[1];
			}
		}
	} else {
		return $meta['fa'];
	}
}
/* \FA ICONS */

/* FA ICONS */
function relish_get_all_flaticon_icons() {
	$cwsfi = get_option('cwsfi');
	if (!empty($cwsfi) && isset($cwsfi['entries'])) {
		return $cwsfi['entries'];
	} else {
		global $wp_filesystem;
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$file = get_template_directory() . '/fonts/flaticon/flaticon.css';
		$fi_content = '';
		$out = '';
		if ( $wp_filesystem && $wp_filesystem->exists($file) ) {
			$fi_content = $wp_filesystem->get_contents($file);
			if ( preg_match_all( "/flaticon-((\w+|-?)+):before/", $fi_content, $matches, PREG_PATTERN_ORDER ) ){
				return $matches[1];
			}
		}
	}
}
/* \FA ICONS */

/********************************** !!! **********************************/

function relish_getTweets( $count = 20 ) {
	$res = null;
	if ( '0' != relish_get_option( 'turn-twitter' ) ) {
		$twitt_name = trim(relish_get_option( 'tw-username' )) ? trim(relish_get_option( 'tw-username' )) : 'Creative_WS';
		if (function_exists('getTweets')) {
			$res = getTweets($twitt_name, $count);
		}
	}

	return $res;
}

function relish_filter_by_empty ( $arr = array() ) {

	if ( empty( $arr ) || !is_array( $arr ) ) return false;

	for ( $i=0; $i<count( $arr ); $i++ ) {
		if ( empty( $arr[$i]) ) {	
			array_splice( $arr, $i, 1 );
		}
	}	

	return $arr;
}



/******************** CUSTOM COLOR ********************/

function relish_render_gradient_rules( $args ) {

	extract( shortcode_atts( array(
		'settings' => array(),
		'selectors' => '',
		'use_extra_rules' => false
	), $args));

	$selectors_wth_pseudo = '';

	$settings = is_object( $settings ) ? get_object_vars( $settings ) : $settings;

	$settings = isset( $settings['@'] ) ? $settings['@'] : array();

	extract( shortcode_atts( array(
		'first_color' => RELISH_COLOR,
		'second_color' => '#0eecbd',
		'type' => 'linear',
		'linear_settings' => array(),
		'radial_settings' => array()
	), $settings));

	$out = '';
	$rules = '';
	$border_extra_rules = "border-color: transparent;\n-moz-background-clip: border;\n-webkit-background-clip: border;\nbackground-clip: border-box;\n-moz-background-origin:border;\n-webkit-background-origin:border;\nbackground-origin:border-box;\nbackground-repeat: no-repeat;";
	$transition_extra_rules = "-webkit-transition-property: background, color, border-color, opacity;\n-webkit-transition-duration: 0s, 0s, 0s, 0.6s;\n-o-transition-property: background, color, border-color, opacity;\n-o-transition-duration: 0s, 0s, 0s, 0.6s;\n-moz-transition-property: background, color, border-color, opacity;\n-moz-transition-duration: 0s, 0s, 0s, 0.6s;\ntransition-property: background, color, border-color, opacity;\ntransition-duration: 0s, 0s, 0s, 0.6s;";
	if ( $type == 'linear' ) {

		$linear_settings = is_object( $linear_settings ) ? get_object_vars( $linear_settings ) : $linear_settings;
		$linear_settings = isset( $linear_settings['@'] ) ? $linear_settings['@'] : array();
		extract( shortcode_atts( array(
			'angle' => '45'
		), $linear_settings));

		$rules .= "background: -webkit-linear-gradient(" . $angle . "deg, $first_color, $second_color);";
		$rules .= "background: -o-linear-gradient(" . $angle . "deg, $first_color, $second_color);";
		$rules .= "background: -moz-linear-gradient(" . $angle . "deg, $first_color, $second_color);";
		$rules .= "background: linear-gradient(" . $angle . "deg, $first_color, $second_color);";
	}
	else if ( $type == 'radial' ) {
		$radial_settings = is_object( $radial_settings ) ? get_object_vars( $radial_settings ) : $radial_settings;
		$radial_settings = isset( $radial_settings['@'] ) ? $radial_settings['@'] : array();
		extract( shortcode_atts( array(
			'shape_settings' => 'simple',
			'shape' => 'ellipse',
			'size_keyword' => '',
			'size' => ''
		), $radial_settings));
		if ( $shape_settings == 'simple' ) {
			$rules .= "background: -webkit-radial-gradient(" . ( !empty( $shape ) ? " " . $shape . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: -o-radial-gradient(" . ( !empty( $shape ) ? " " . $shape . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: -moz-radial-gradient(" . ( !empty( $shape ) ? " " . $shape . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: radial-gradient(" . ( !empty( $shape ) ? " " . $shape . "," : "" ) . " $first_color, $second_color);";
		}
		else if ( $shape_settings == 'extended' ) {
			$rules .= "background: -webkit-radial-gradient(" . ( !empty( $size ) ? " " . $size . "," : "" ) . ( !empty( $size_keyword ) ? " " . $size_keyword . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: -o-radial-gradient(" . ( !empty( $size ) ? " " . $size . "," : "" ) . ( !empty( $size_keyword ) ? " " . $size_keyword . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: -moz-radial-gradient(" . ( !empty( $size ) ? " " . $size . "," : "" ) . ( !empty( $size_keyword ) ? " " . $size_keyword . "," : "" ) . " $first_color, $second_color);";
			$rules .= "background: radial-gradient(" . ( !empty( $size_keyword ) && !empty( $size ) ? " $size_keyword at $size" : "" ) . " $first_color, $second_color);";
		}
	}
	if ( !empty( $rules ) ) {
		$out .= !empty( $selectors ) ? "$selectors{\n$rules\n}" : $rules;
		if ( $use_extra_rules ) {
			$out .= !empty( $selectors ) ? "$selectors{\n$border_extra_rules\n}" : $border_extra_rules;
			$out .= !empty( $selectors ) ? "\n$selectors{\ncolor: #fff !important;\n}" : "color: #fff !important;";
			if ( !empty( $selectors ) ) {
				$selectors_wth_pseudo = str_replace( array( ":hover" ), "", $selectors );
				if ( !empty( $selectors_wth_pseudo ) ) {
					$out .= "\n$selectors_wth_pseudo{\n$transition_extra_rules\n}";
				}
			}
			else{
				$out .= $transition_extra_rules;
			}
		}
	}
	return preg_replace('/\s+/',' ', $out);
}

function relish_render_builder_gradient_rules( $options ) {
	extract(shortcode_atts(array(
		'gradient_start_color' => RELISH_COLOR,
		'gradient_end_color' => '#0eecbd',
		'gradient_type' => 'linear',
		'gradient_linear_angle' => '45',
		'gradient_radial_shape_settings_type' => 'simple',
		'gradient_radial_shape' => 'ellipse',
		'gradient_radial_size_keyword' => 'farthest-corner',
		'gradient_radial_size' => '',
	), $options));
	$out = '';
	if ( $gradient_type == 'linear' ) {
		$out .= "background: -webkit-linear-gradient(" . $gradient_linear_angle . "deg, $gradient_start_color, $gradient_end_color);";
		$out .= "background: -o-linear-gradient(" . $gradient_linear_angle . "deg, $gradient_start_color, $gradient_end_color);";
		$out .= "background: -moz-linear-gradient(" . $gradient_linear_angle . "deg, $gradient_start_color, $gradient_end_color);";
		$out .= "background: linear-gradient(" . $gradient_linear_angle . "deg, $gradient_start_color, $gradient_end_color);";
	}
	else if ( $gradient_type == 'radial' ) {
		if ( $gradient_radial_shape_settings_type == 'simple' ) {
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $gradient_radial_shape ) ? " " . $gradient_radial_shape . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $gradient_radial_shape ) ? " " . $gradient_radial_shape . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $gradient_radial_shape ) ? " " . $gradient_radial_shape . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: radial-gradient(" . ( !empty( $gradient_radial_shape ) ? " " . $gradient_radial_shape . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
		}
		else if ( $gradient_radial_shape_settings_type == 'extended' ) {
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $gradient_radial_size ) ? " " . $gradient_radial_size . "," : "" ) . ( !empty( $gradient_radial_size_keyword ) ? " " . $gradient_radial_size_keyword . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $gradient_radial_size ) ? " " . $gradient_radial_size . "," : "" ) . ( !empty( $gradient_radial_size_keyword ) ? " " . $gradient_radial_size_keyword . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $gradient_radial_size ) ? " " . $gradient_radial_size . "," : "" ) . ( !empty( $gradient_radial_size_keyword ) ? " " . $gradient_radial_size_keyword . "," : "" ) . " $gradient_start_color, $gradient_end_color);";
			$out .= "background: radial-gradient(" . ( !empty( $gradient_radial_size_keyword ) && !empty( $gradient_radial_size ) ? " $gradient_radial_size_keyword at $gradient_radial_size" : "" ) . " $gradient_start_color, $gradient_end_color);";
		}
	}
	$out .= "border-color: transparent;-webkit-background-clip: border;-moz-background-clip: border;background-clip: border-box;-webkit-background-origin: border;-moz-background-origin: border;background-origin: border-box;";
	return preg_replace('/\s+/',' ', $out);
}

/******************** \CUSTOM COLOR ********************/

function relish_is_woo() {
	global $woocommerce;
	return ! empty( $woocommerce ) ? is_woocommerce() || is_shop() || is_product() || is_product_tag() || is_product_category() || is_account_page() ||  is_cart() || is_checkout() : false;
}

if ( ! isset( $content_width ) ) $content_width = 1170;

function relish_get_sidebars( $p_id = null ) { /*!*/
	if ($p_id){
		$post_type = get_post_type($p_id);
		if ( in_array( $post_type, array( "page" ) ) ) {
			$cws_stored_meta = cwsfw_get_post_meta($p_id, RELISH_MB_PAGE_LAYOUT_KEY);
			$sidebar1 = $sidebar2 = $sidebar_pos = $sb_block = '';
			$page_type = "page";
				if ( isset( $cws_stored_meta[0] ) && !empty( $cws_stored_meta[0] ) ) {
					$sidebar_pos = $cws_stored_meta[0]['sb_layout'];
					if ($sidebar_pos == 'default') {
						if($cws_stored_meta[0]['is_blog'] !== '0' ) {
							$page_type = "blog";
						}
						else if(is_front_page()) {
							$page_type = "home";
						}

						$sidebar_pos = relish_get_option("def-" . $page_type . "-layout");
						$sidebar1 = relish_get_option("def-" . $page_type . "-sidebar1");
						$sidebar2= relish_get_option("def-" . $page_type . "-sidebar2");
					}
					else{
						$sidebar1 = isset( $cws_stored_meta[0]['sidebar1'] ) ? $cws_stored_meta[0]['sidebar1'] : '';
						$sidebar2 = isset( $cws_stored_meta[0]['sidebar2'] ) ? $cws_stored_meta[0]['sidebar2'] : '';
					}
				}
				else{
					$page_type = "page";
					$sidebar_pos = relish_get_option("def-" . $page_type . "-layout");
					$sidebar1 = relish_get_option("def-" . $page_type . "-sidebar1");
					$sidebar2= relish_get_option("def-" . $page_type . "-sidebar2");
				}
			}
		else if ( in_array( $post_type, array( 'post' ) ) ) {
			$sidebar_pos = relish_get_option("def-blog-layout");
			$sidebar1 = relish_get_option("def-blog-sidebar1");
			$sidebar2 = relish_get_option("def-blog-sidebar2");
		}
		else if( in_array( $post_type, array( 'attachment', 'cws_portfolio', 'cws_staff' ) ) ) {
			$sidebar_pos = relish_get_option("def-page-layout");
			$sidebar1 = relish_get_option("def-page-sidebar1");
			$sidebar2 = relish_get_option("def-page-sidebar2");
		}
	}
	else if (is_home()) { 										/* default home page hasn't ID */
		$sidebar_pos = relish_get_option("def-home-layout");
		$sidebar1 = relish_get_option("def-home-sidebar1");
		$sidebar2 = relish_get_option("def-home-sidebar2");
	}
	else if ( is_category() || is_tag() || is_archive() ) {
		$sidebar_pos = relish_get_option("def-blog-layout");
		$sidebar1 = relish_get_option("def-blog-sidebar1");
		$sidebar2 = relish_get_option("def-blog-sidebar2");
		if (get_post_type() == 'cws_portfolio' || get_post_type() == 'cws_staff') {
			$page_type = "page";
			$sidebar_pos = relish_get_option("def-" . $page_type . "-layout");
			$sidebar1 = relish_get_option("def-" . $page_type . "-sidebar1");
			$sidebar2= relish_get_option("def-" . $page_type . "-sidebar2");
		}
	}
	else if ( is_search() ) {
		$sidebar_pos = relish_get_option("def-page-layout");
		$sidebar1 = relish_get_option("def-page-sidebar1");
		$sidebar2 = relish_get_option("def-page-sidebar2");
	}

	$ret = array();
	$ret['sb_layout'] = isset( $sidebar_pos ) ? $sidebar_pos : '';
	$ret['sidebar1'] = isset( $sidebar1 ) ? $sidebar1 : '';
	$ret['sidebar2'] = isset( $sidebar2 ) ? $sidebar2 : '';

	$sb_enabled = $ret['sb_layout'] != 'none';
	$ret['sb1_exists'] = $sb_enabled && is_active_sidebar( $ret['sidebar1'] );
	$ret['sb2_exists'] = $sb_enabled && $ret['sb_layout'] == 'both' && is_active_sidebar( $ret['sidebar2'] );

	$ret['sb_exist'] = $ret['sb1_exists'] || $ret['sb2_exists'];
	$ret['sb_layout_class'] = ( $ret['sb1_exists'] xor $ret['sb2_exists'] ) ? 'single' : ( ( $ret['sb1_exists'] && $ret['sb2_exists'] ) ? 'double' : '' );

	return $ret;
}

function relish_get_option($name) {
 $ret = null;
 if (is_customize_preview()) {
	global $cwsfw_settings;
	if (isset($cwsfw_settings[$name])) {
	 $ret = $cwsfw_settings[$name];
	 if (is_array($ret)) {
		$theme_options = get_option( "relish" );
		if (isset($theme_options[$name])) {
		 $to = $theme_options[$name];
		 foreach ($ret as $key => $value) {
			$to[$key] = $value;
		 }
		 $ret = $to;
		}
	 }
	 return $ret;
	}
 }
 $theme_options = get_option( "relish" );
 $ret = isset($theme_options[$name]) ? $theme_options[$name] : null;
 $ret = stripslashes_deep( $ret );
 return $ret;


}

function relish_widget_title_icon_rendering( $args = array() ) {
	extract( shortcode_atts(
		array(
			'icon_type' => '',
			'icon_fa' => '',
			'icon_img' => array(),
			'icon_color' => '#fff',
			'icon_bg_type' => 'color',
			'icon_bgcolor' => RELISH_COLOR,
			'gradient_first_color' => RELISH_COLOR,
			'gradient_second_color' => '#0eecbd',
			'gradient_type' => '',
			'gradient_linear_angle' => '',
			'gradient_radial_shape' => '',
			'gradient_radial_type' => '',
			'gradient_radial_size_key' => '',
			'gradient_radial_size' => '',
			), $args));

	$is_benefits_area_rendered = isset( $GLOBALS['benefits_area_is_rendered'] );
	$r = '';
	$icon_styles = '';
	if ( $icon_type == 'fa' && !empty( $icon_fa ) ) {
		if ( $icon_bg_type == 'none' ) {
			$icon_styles .= "border-width: 1px; border-style: solid;";
		}else if ( $icon_bg_type == 'color' ) {
			$icon_styles .= "background-color:$icon_bgcolor;";
		}
		else if ( $icon_bg_type == 'gradient' ) {
			$gradient_settings = relish_extract_array_prefix($args, 'gradient');

			$gradient_settings_arr = array(
				'first_color' => $gradient_settings["first_color"],
				'second_color' => $gradient_settings["second_color"],
				'type' => $gradient_settings["type"],
				'linear_settings' => array(
					'@' =>  array(
						'angle' => $gradient_settings["linear_angle"],
						),
					),
				'radial_settings' => array(
					'@' =>  array(
						'shape_settings' => $gradient_settings["radial_shape"],
						'shape' => $gradient_settings["radial_type"],
						'size_keyword' => $gradient_settings["radial_size_key"],
						'size' => $gradient_settings["radial_size"],
						),
					),
			);

			$gradient_settings = isset( $gradient_settings_arr ) ? $gradient_settings_arr : new stdClass ();
			$settings = new stdClass();

			foreach ($gradient_settings_arr as $key => $value){
					$settings->$key = $value;
			}

			$settings_arr = array('@' => $settings);

			$icon_styles .= relish_render_gradient_rules( array( 'settings' => $settings_arr ) );
		}
		$icon_styles .= "color:$icon_color;";
		$r .= "<i class='$icon_fa'" . ( !empty( $icon_styles ) ? " style='$icon_styles'" : "" ) . "></i>";
	}
	else if ( $icon_type == 'img' && isset( $icon_img['src'] ) && !empty( $icon_img['src'] ) ) {
		$img_url = $icon_img['src'];
		$body_font_settings = relish_get_option( 'body-font' );
		$font_size = isset( $body_font_settings['font_size'] ) ? preg_replace( 'px', '', $body_font_settings['font_size'] ) : '15';
		$thumb_size = $is_benefits_area_rendered ? 102 : (int)round( (float)$font_size * 2 );
		$thumb_obj = cws_thumb( $img_url, array( 'width' => $thumb_size, 'height' => $thumb_size ), false );
		$thumb_url = esc_url($thumb_obj[0]);
		extract( $thumb_obj[3] );
		$icon_styles = esc_attr($icon_styles);
		if ( $retina_thumb_exists ) {
			$r .= "<img src='$thumb_url' data-at2x='$retina_thumb_url'" . ( !empty( $icon_styles ) ? " style='$icon_styles'" : "" ) . " alt />";
		}
		else{
			$r .= "<img src='$thumb_url' data-no-retina" . ( !empty( $icon_styles ) ? " style='$icon_styles'" : "" ) . " alt />";
		}
	}
	return $r;
}

	function relish_extract_array_prefix($arr, $prefix) {
		$ret = array();
		$pref_len = strlen($prefix);
		foreach ($arr as $key => $value) {
			if (0 === strpos($key, $prefix . '_') ) {
				$ret[mb_substr($key, $pref_len+1)] = $value;
			}
		}
		return $ret;
	}

	function relish_get_post_thumbnail_dims ( $eq_thumb_height = false, $real_dims = array() ) {
		$p_id = get_queried_object_id();
		$blogtype = is_page() ? relish_get_page_meta_var( array( "blog", "blogtype" ) ) : relish_get_option( "def_blogtype" );

		$sb = relish_get_sidebars( $p_id );
		$sb_block = isset( $sb['sb_layout'] ) ? $sb['sb_layout'] : 'none';
		$single = is_single();
		$width_correction =  0;
		$height_correction = 0;
		$dims = array( 'width'=>0, 'height'=>0 );

		if ($single){
			if ($sb_block == 'none') {
				if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) && $real_dims['width'] > 1170 ) ) || $eq_thumb_height ) {
					$dims['width'] = 1170;
					if ( $eq_thumb_height ) $dims['height'] = 659;
				}
			}
			else if (in_array($sb_block, array('left','right'))) {
				if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) && $real_dims['width'] > 870 ) ) || $eq_thumb_height ) {
					$dims['width'] = 870;
					if ( $eq_thumb_height ) $dims['height'] = 490;
				}
			}
			else if ($sb_block == 'both') {
				if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) && $real_dims['width'] > 570 ) ) || $eq_thumb_height ) {
					$dims['width'] = 570;
					if ( $eq_thumb_height ) $dims['height'] = 321;
				}
			}
		}
		else{
			switch ($blogtype){
				case "large":
					if ($sb_block == 'none') {
						$dims['width'] = 1170;
						$dims['height'] = 690;
					}
					else if (in_array($sb_block, array('left','right'))) {
						$dims['width'] = 870;
						$dims['height'] =  490;
					}
					else if ($sb_block == 'both') {
						$dims['width'] = 400;
						$dims['height'] =  260;
					}
					break;
				case "medium":
					$dims['width'] = 400;
					$dims['height'] = 260;
					break;
				case "small":
					$dims['width'] = 370;
					$dims['height'] = 208;
					break;
				case '2':
					if ($sb_block == 'none') {
						$dims['width'] = 570;
						$dims['height'] = 351;
					}
					else if (in_array($sb_block, array('left','right'))) {
						$dims['width'] = 570;
						$dims['height'] =  351;
					}
					else if ($sb_block == 'both') {
						$dims['width'] = 270;
						$dims['height'] =  152;
					}
					break;
				case '3':
					if ($sb_block == 'none') {
						$dims['width'] = 370;
						$dims['height'] = 251;
					}
					else if (in_array($sb_block, array('left','right'))) {
						$dims['width'] = 370;
						$dims['height'] =  251;
					}
					else if ($sb_block == 'both') {
						$dims['width'] = 270;
						$dims['height'] =  152;
					}
					break;
			}
		}
		$dims['width'] = $dims['width'] != 0 ? $dims['width'] - $width_correction : $dims['width'];
		$dims['height'] = $dims['height'] != 0 ? $dims['height'] - $height_correction : $dims['height'];
		return $dims;
	}

	function relish_output_media_part ( $custom_layout_arr = false ) {
		$column_style = $custom_layout_arr['column_style'];
		$custom_layout = intval( $custom_layout_arr['custom_layout'] );
		$use_blur = relish_get_option( 'use_blur' ) == 1 ? true : false;
		$post_url = esc_url(get_the_permalink());
		$single = is_single();
		$post_format = get_post_format( );
		$eq_thumb_height = in_array( $post_format, array( 'gallery' ) );
		$media_meta = cwsfw_get_post_meta( get_the_ID(), 'cws_mb_post' );
		$media_meta = isset( $media_meta[0] ) ? $media_meta[0] : array();

		$thumbnail = has_post_thumbnail( ) ? wp_get_attachment_image_src( get_post_thumbnail_id( ),'full' ) : '';
		$thumbnail = ! empty( $thumbnail ) ? $thumbnail[0] : '';
		$thumbnail_dims = relish_get_post_thumbnail_dims( $eq_thumb_height );

		$real_thumbnail_dims = array();
		if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[1] ) ) $real_thumbnail_dims['width'] = $thumbnail_props[1];
		if ( !empty(  $thumbnail_props ) && isset( $thumbnail_props[2] ) ) $real_thumbnail_dims['height'] = $thumbnail_props[2];
		$thumbnail_dims = relish_get_post_thumbnail_dims( $eq_thumb_height, $real_thumbnail_dims );
		$crop_thumb = isset( $thumbnail_dims['width'] ) && $thumbnail_dims['width'] > 0;
		$thumb_media = false;

		$image_data = wp_get_attachment_metadata( get_post_thumbnail_id( get_the_ID() ) );
		if ( ! empty( $thumbnail ) ) {
			if ( $single ) {
				if ( $image_data['width'] < 1170 ) {
					$img_data['width'] = 0;
					$img_data['height'] = 0;
				} else {
					$img_data = $thumbnail_dims;
				}
			} else {
				$img_data = $thumbnail_dims;
			}
		}

		$only_link = ($post_format == 'link' && empty( $thumbnail ) ) ? ' only_link' : '';
		$only_link = ($post_format == 'link' && !empty( $thumbnail ) ) ? ' link_post' : '';
		$quote = ('quote' === $post_format && isset( $media_meta['quote'] )) ? $media_meta['quote'] : '';
		$quote_post = ( ! empty( $quote )) ? ' quoute_post' : '';
		$video_post = ('video' === $post_format)  ? ' video_post' : '';
		$audio_post = ( 'audio' === $post_format && isset( $media_meta['audio'] ) ) ? ' audio_post' : '';
		$audio_post .= isset( $media_meta['audio'] ) ? ( is_int( strpos( $media_meta['audio'], 'https://soundcloud' ) ) ? ' soundcloud' : '') : '';
		$gallery_post = ('gallery' === $post_format && isset( $media_meta['gallery'] ) && !empty($media_meta['gallery'])) ? ' gallery_post' : '';
		$some_media = false;
		ob_start();
		?>
			<div class="media_part<?php echo esc_attr($only_link);
			echo esc_attr($quote_post);
			echo esc_attr($video_post);
			echo esc_attr($audio_post);
			echo esc_attr($gallery_post);?>">
				<?php
					switch ($post_format) {
						case 'link':
							$link = isset( $media_meta['link'] ) ? esc_url( $media_meta['link'] ) : '';
							if ( !empty($thumbnail) ) {
								?>
								<div class="pic <?php echo !empty( $link ) ? 'link_post' : ''; if($use_blur && (( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox'])) && $media_meta['enable_lightbox'] == '1'))): echo ' blured'; endif; ?>">
									<?php
									echo !empty($link) ? "<a href='".esc_url($link)."'>" : '';
									if (!empty($thumbnail)) {
										echo '<div class="link_bg" style="background-image: url('.esc_attr($thumbnail).');background-position: center center;"></div>';
									}
									if ( !empty( $link ) ) {
										echo "<div class='link'><span>$link</span></div>";
									} elseif(( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox']) && $media_meta['enable_lightbox'] == '1')) )  {
									}
									echo !empty($link) ? "</a>" : '';
									?>
								</div>
								<?php
								$thumb_media = true;
								$some_media = true;
							}
							else{
								if ( !empty( $link ) ) {
									echo "<div class='link'><a href='".esc_url($link)."'>$link</a></div>";
									$some_media = true;
								}
							}
							break;
						case 'video':
							$video = isset( $media_meta['video'] ) ? $media_meta['video'] : '';
							if ( ! empty( $video ) ) {
								$video_dims = relish_get_post_thumbnail_dims( false );
								echo "<div class='video'>" . apply_filters( 'the_content',"[embed width='" . $video_dims['width'] . "']" . $video . '[/embed]' ) . '</div>';
								$some_media = true;
							}
							break;
						case 'audio':
							$audio = isset( $media_meta['audio'] ) ? $media_meta['audio'] : '';
							$is_sounfcloud = is_int( strpos( (string) $audio, 'https://soundcloud' ) );

							if ( $is_sounfcloud == false ) {
								if ( ! empty( $thumbnail ) ) {
									$thumb_obj = cws_thumb( $thumbnail,$img_data,false );
									$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
									echo "<div class='pic".($use_blur ? ' blured' : '')."'>
												<img ". $thumb_path_hdpi ." alt />
												".($use_blur ? "<img ". $thumb_path_hdpi ." class='blured-img' alt />" : '' )."
											</div>";
									$thumb_media = true;
									$some_media = true;
								}
								if ( ! empty( $audio ) ) {
									echo "<div class='audio'>" . apply_filters( 'the_content','[audio src="' . esc_url( $audio ) . '"]' ) . '</div>';
									$some_media = true;
								}
							} else {
								echo apply_filters( 'the_content',"$audio" );
								$some_media = true;
							}

							break;
						case 'quote':
							$quote = isset( $media_meta['quote'] ) ? $media_meta['quote']['quote'] : '';
							$author = isset( $media_meta['quote']['author'] ) ? $media_meta['quote']['author'] : '';
							$avatar = isset( $media_meta['quote']['avatar'] ) ? $media_meta['quote']['avatar'] : '';
							if ( !empty( $quote ) ) {
								echo relish_testimonial_renderer( (!empty($avatar['src']) ? $avatar['src'] : ''), $quote, $author, $author_prof = null );
								$some_media = true;
							}
							if (!empty($thumbnail) && !empty( $quote )) {
								echo '<div class="testimonial_bg" style="background-image: url('.esc_attr($thumbnail).');background-position: center center;"></div>';
							}
							break;
						case 'gallery':
							$gallery = isset( $media_meta['gallery'] ) ? $media_meta['gallery'] : '';
							if ( !empty( $gallery ) ) {
								$match = preg_match_all("/\d+/",$gallery,$images);
								if ($match){
									$images = $images[0];
									$image_srcs = array();
									foreach ( $images as $image ) {
										$image_src = wp_get_attachment_image_src($image,'full');
										$image_url = $image_src[0];
										array_push( $image_srcs, $image_url );

									}
									$thumb_media = $some_media = count( $image_srcs ) > 0 ? true : false;
									$carousel = count($image_srcs) > 1 ? true : false;
									$gallery_id = uniqid( 'cws-gallery-' );
									if ($carousel) {
										wp_enqueue_script ('owl_carousel');
										wp_enqueue_script ('isotope');
									}
									echo  $carousel ? "<a class='carousel_nav prev'><span></span></a>
														<a class='carousel_nav next'><span></span></a>
														<div class='gallery_post_carousel'>" : '';
									foreach ( $image_srcs as $image_src ) {
										$img_obj = cws_thumb( $image_src, $thumbnail_dims , false );
										$img_url = esc_url($img_obj[0]);
										$retina_img_exists = isset($img_obj[3]['retina_thumb_exists']) ? $img_obj[3]['retina_thumb_exists'] : false;
										$retina_img_url = isset($img_obj[3]['retina_thumb_url']) ? esc_attr($img_obj[3]['retina_thumb_url']) : false;
										?>
										<?php if($img_url):?>
											<div class='pic<?php if($use_blur && (( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox'])) && $media_meta['enable_lightbox'] == '1'))): echo ' blured'; endif;  ?>'>
												<?php if ( $retina_img_exists ) {
													echo "<img src='".esc_url($img_url)."' data-at2x='$retina_img_url' alt />";
													if($use_blur  && ((($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && ( isset($media_meta['enable_lightbox']) && ($media_meta['enable_lightbox'] == '1'))))): echo "<img src='$img_url' data-at2x='$retina_img_url' class='blured-img' alt />"; endif;
												}
												else{
													echo "<img src='".esc_url($img_url)."' data-no-retina alt />";
													if($use_blur  && ((($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && ( isset($media_meta['enable_lightbox']) && ($media_meta['enable_lightbox'] == '1'))))): echo "<img src='".esc_url($img_url)."' data-no-retina class='blured-img' alt />"; endif;

												}
												if (( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox'])) && $media_meta['enable_lightbox'] == '1'))  {
											?>
												<div class="links">
													<a href="<?php echo esc_url($image_src); ?>" <?php if($carousel): echo " data-fancybox-group='".esc_attr($gallery_id)."'"; endif; ?> class="fancy <?php if($carousel): echo 'cwsicon-photo246 fancy_gallery'; else: echo 'cwsicon-magnifying-glass84'; endif; ?>"></a>
												</div>
											<?php } 
												?>
											</div>
										<?php
										endif;
									}
									echo  $carousel ? "</div>" : '';
								}
								$some_media = true;
							}
							break;
					}
					if ( !$some_media && !empty( $thumbnail ) ) {
						
						$thumb_obj = cws_thumb( $thumbnail, $img_data, false );
						$thumb_url = esc_url($thumb_obj[0]);
						$retina_thumb_url = '';
						
						if(!empty($thumb_obj[3])){
							extract( $thumb_obj[3] );
							$retina_thumb_url = esc_attr($retina_thumb_url);
						}				
						
						echo "<div class='pic".( $use_blur && (( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox'])) && $media_meta['enable_lightbox'] == '1')) ? ' blured' : '' )."'>";

							if ( !empty($retina_thumb_exists) ) {

								if(!is_single()){
									echo "<a href='".esc_url($post_url)."'>";
								}
								echo "<img src='".esc_url($thumb_url)."' data-at2x='$retina_thumb_url' alt />";
								if($use_blur  && ((($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && ( isset($media_meta['enable_lightbox']) && ($media_meta['enable_lightbox'] == '1'))))): echo "<img src='".esc_url($thumb_url)."' data-at2x='$retina_thumb_url' class='blured-img' alt />"; endif;
								if(!is_single()){
									echo "</a>";
								}
							}
							else{
								if(!is_single()){
										echo "<a href='".esc_url($post_url)."'>";
								}
								echo "<img src='".esc_url($thumb_url)."' data-no-retina alt />";
								if($use_blur  && ((($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && ( isset($media_meta['enable_lightbox']) && ($media_meta['enable_lightbox'] == '1'))))): echo "<img src='".esc_url($thumb_url)."' data-no-retina class='blured-img' alt />";endif;
								if(!is_single()){
									echo "</a>";
								}
							}
							if(is_single()){
								if (( ($custom_layout == 1) && (intval( $custom_layout_arr['enable_lightbox'] ) == 0) ) || (($custom_layout != 1) && (isset($media_meta['enable_lightbox'])) && $media_meta['enable_lightbox'] == '1'))  {
								echo "<div class='links'><a class='fancy' href='".esc_url($thumbnail)."'></a>" . ( !$single ? "<a class='cwsicon-links21' href='$post_url'></a>" : "" ) . "</div>";
								}							
							}


						echo "</div>";
						$thumb_media = true;
						$some_media = true;
					}
				?>
			</div>
		<?php
		$some_media ? ob_end_flush() : ob_end_clean();
	}

	function relish_get_page_meta_var ( $keys ) {
		$p_meta = array();
		if ( isset( $GLOBALS['relish_page_meta'] ) && !empty($keys) ) {
			$p_meta = $GLOBALS['relish_page_meta'];
			if ( is_string( $keys ) ) {
				if ( isset( $p_meta[$keys] ) ) {
					return $p_meta[$keys];
				}
			} else if ( is_array( $keys ) ) {
				for ( $i=0; $i < count($keys); $i++ ) {
					if ( isset( $p_meta[$keys[$i]] ) ) {
						if ( $i < count($keys) - 1 ) {
							if ( is_array( $p_meta[$keys[$i]] ) ) {
								$p_meta = $p_meta[$keys[$i]];
							}	else {
								return false;
							}
						}	else {
							return $p_meta[$keys[$i]];
						}
					}	else {
						return false;
					}
				}
			}
		}
		return false;
	}

	function relish_set_page_meta_var($keys, $value = '') {
		$p_meta = array();
	if (isset($GLOBALS['relish_page_meta']) && !empty($keys) ) {
		$p_meta = &$GLOBALS['relish_page_meta'];

			if ( is_string( $keys ) ) {
				if ( isset($p_meta[$keys]) ) {
					$p_meta[$keys] = $value;
					return true;
				}
			} else if ( is_array( $keys ) && !empty( $keys ) ) {
				for ( $i=0; $i < count($keys); $i++ ) {
					if ( isset( $p_meta[$keys[$i]] ) ) {
						if ( $i < count($keys) - 1 ) {
							if ( is_array( $p_meta[$keys[$i]] ) ) {
								$p_meta = &$p_meta[$keys[$i]];
							} else {
								return false;
							}
						}	else {
							$p_meta[$keys[$i]] = $value;
							return true;
						}
					}	else {
						return false;
					}
				}
			}
		}
		return false;
	}

	function relish_blog_output ( $query = false ) {
		$custom_layout_arr = array(
			'this_shortcode' => isset( $query->query_vars['this_shortcode'] ) ? $query->query_vars['this_shortcode'] : false,
			'column_style' => isset( $query->query_vars['column_style'] ) ? $query->query_vars['column_style'] : false,
			'custom_layout' => isset( $query->query_vars['custom_layout'] ) ? $query->query_vars['custom_layout'] : 0,
			'post_text_length' => ! empty( $query->query_vars['post_text_length'] ) ? $query->query_vars['post_text_length'] : '',
			'button_name' => ! empty( $query->query_vars['button_name'] ) ? $query->query_vars['button_name'] : '',
			'enable_lightbox' => isset( $query->query_vars['enable_lightbox'] ) ? $query->query_vars['enable_lightbox'] : '',
			'hide_meta' => isset( $query->query_vars['hide_meta'] ) ? $query->query_vars['hide_meta'] : '',
			'date_style' => isset( $query->query_vars['date_style'] ) ? $query->query_vars['date_style'] : '',
			'boxed_style' => isset( $query->query_vars['boxed_style'] ) ? $query->query_vars['boxed_style'] : '',
			'column_count' => isset( $query->query_vars['column_count'] ) ? intval( $query->query_vars['column_count'] ) : 3,

		);

		global $wp_query;
		$query = $query ? $query : $wp_query;

		if ( is_page() ) {
			$blogtype = relish_get_page_meta_var( array( 'blog', 'blogtype' ) );
		} else {
			$blogtype = relish_get_option( 'def_blogtype' );

		}

		if ($blogtype == 'default') {
			$blogtype = relish_get_option( 'def_blogtype' );
		}

		if (!empty($custom_layout_arr['date_style']) && $custom_layout_arr['date_style'] == 'unwrapped') {
			$blogtype .= ' unwrapped_date';
		}

		if (!empty($custom_layout_arr['boxed_style']) && $custom_layout_arr['boxed_style'] != 'none') {
			$blogtype .= ' boxed_style';
			if ($custom_layout_arr['boxed_style'] == 'with_shadow') {
				$blogtype .= ' with_shadow';
			}
		}
		if ($query->have_posts()):
			ob_start();
			while($query->have_posts()):
				$query->the_post();
				echo "<article class='item ".(is_sticky(get_the_id()) ? "sticky-posts ": "")."".$blogtype."'>";
					relish_post_output( $custom_layout_arr );
				echo "</article>";		
			endwhile;
			echo relish_portfolio_loader();
			wp_reset_postdata();
			ob_end_flush();
		endif;
	}

	function relish_load_more( $paged = 0, $template = "", $max_paged = PHP_INT_MAX ) {
		?>
			<a class="cws_button large cws_load_more icon-on" href="#" data-paged="<?php echo esc_attr($paged); ?>" data-max-paged="<?php echo esc_attr($max_paged); ?>" data-template="<?php echo esc_attr($template); ?>"><?php echo esc_html__( "Load More", 'relish' ); ?><i class="button-icon fa fa-refresh"></i></a>
		<?php
	}
	function relish_portfolio_loader(){
		ob_start();
		?>
			<div class='portfolio_loader_wraper'>
				<div class='portfolio_loader_container'>
	
				<svg width='104px' height='104px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-default"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(0 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(30 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.08333333333333333s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(60 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.16666666666666666s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(90 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.25s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(120 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.3333333333333333s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(150 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.4166666666666667s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(180 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(210 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5833333333333334s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(240 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.6666666666666666s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(270 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.75s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(300 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.8333333333333334s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(330 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.9166666666666666s' repeatCount='indefinite'/></rect></svg>

				</div>
			</div>
		<?php
		echo ob_get_clean();	
	}

	function relish_pagination ( $paged=1, $max_paged=1, $pagination_style = 'paged') {
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts	= explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$permalink_structure = get_option('permalink_structure');

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = $permalink_structure ? trailingslashit( $pagenum_link ) . '%_%' : trailingslashit( $pagenum_link ) . '?%_%';
		$pagenum_link = add_query_arg( $query_args, $pagenum_link );

		$format  = $permalink_structure && preg_match( '#^/*index.php#', $permalink_structure ) && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $permalink_structure ? user_trailingslashit( 'page/%#%', 'paged' ) : 'paged=%#%';
		?>
		<div class='pagination <?php echo ($pagination_style == 'load_more' ? "pagination_load_more" : "");?> separated'>
			<div class='page_links'>
			<?php
			$pagination_args = array( 'base' => $pagenum_link,
				'format' => $format,
				'current' => $paged,
				'total' => $max_paged,
				"prev_text" => "<i class='fa fa-angle-left'></i>",
				"next_text" => ($pagination_style == 'paged' ? "<i class='fa fa-angle-right'></i>" : esc_html__("View More",'relish')),
				"link_before" => "",
				"link_after" => "",
				"before" => "",
				"after" => "",
				"mid_size" => 2,
			);
			$pagination = paginate_links($pagination_args);
			echo sprintf("%s", $pagination);
			?>
			</div>
		</div>
		<?php
	}

	function relish_page_links() {
		$args = array(
		 'before'		   => ''
		,'after'			=> ''
		,'link_before'	  => '<span>'
		,'link_after'	   => '</span>'
		,'next_or_number'   => 'number'
		,'nextpagelink'	 =>  esc_html__("Next Page",'relish')
		,'previouspagelink' => esc_html__("Previous Page",'relish')
		,'pagelink'		 => '%'
		,'echo'			 => 0 );
		$pagination = wp_link_pages( $args );
		echo !empty( $pagination ) ? "<div class='pagination'><div class='page_links'>$pagination</div></div>" : '';
	}

	function relish_post_output ( $custom_layout_arr = false ) {
		$old_version = 0;
		$pid = get_the_id();
		$is_single = is_single( $pid );
		$title = esc_html( get_the_title() );
		$permalink = esc_url( get_the_permalink() );
		$show_author = relish_get_option( "blog_author" );
		if ($old_version == 1) {
			echo !empty( $title ) ?
			RELISH_BEFORE_CE_TITLE . "<div>" . ( !$is_single ? "<a href='$permalink'>" : "" ) . $title . ( !$is_single ? "</a>" : "" ) . "</div>" . RELISH_AFTER_CE_TITLE : '';

			relish_post_content_output( $custom_layout_arr );

		}
		if ($old_version == 0) {
			?>
					<?php
						/* RELISH CONTENT POSTS */
						relish_post_content_output( $custom_layout_arr );			
					?>
			<?php
		}
		relish_page_links();
	}

	function relish_post_content_output ( $custom_layout_arr = false ) {

		$old_version = 0;
		$show_author = relish_get_option( "blog_author" );
		$permalink = get_permalink();
		$title = esc_html( get_the_title() );
		ob_start();
		
		relish_output_media_part($custom_layout_arr);
		$media_content = ob_get_clean();
		$section_class = "post_info_part";
		$section_class .= empty( $media_content ) ? " full_width" : '';
		$section_class .= $old_version == 1 ? " old_version" : '';
		$header_class = "post_info_header";
		$post_format = get_post_format();
		$pid = get_the_id();
		$is_single = is_single( $pid );

		$date = esc_html( get_the_time( get_option( 'date_format' ) ) );
		$first_word_boundary = strpos( $date, ' ' );

		$header_class .= ( in_array( $post_format, array( 'quote', 'audio' ) ) || ( $post_format == 'link' && !has_post_thumbnail() ) ) ? " rounded" : '';
		if ($old_version) {
			echo !empty( $title ) ?
			RELISH_BEFORE_CE_TITLE . "<div>" . ( !$is_single ? "<a href='$permalink'>" : "" ) . $title . ( !$is_single ? "</a>" : "" ) . "</div>" . RELISH_AFTER_CE_TITLE : '';
		?>

		<div class="<?php echo esc_attr($section_class); ?>">
			<div class="post_info_box">
				<div class="<?php echo esc_attr($header_class); ?>">
					<div class="date">
						<?php
						if (empty( $title )) {
							echo "<a href='".$permalink."'>";
						}
						$date = get_the_time( get_option("date_format") );
						$first_word_boundary = strpos( $date, " " );
						if ( $first_word_boundary ) {
							$first_word = esc_html(mb_substr( $date, 0, $first_word_boundary ));
							$date = "<span class='first_word'>$first_word</span>" . esc_html(trim(mb_substr( $date, $first_word_boundary + 1)));
						}
						echo sprintf("%s", $date);
						if (empty( $title )) {
							echo "</a>";
						}
						?>
					</div>
					<div class="post_info">
						<?php
							$author = '';
							$author .= $show_author ? esc_html(get_the_author()) : '';
							$special_pf = relish_is_special_post_format();
							if ( !empty($author) || $special_pf ) {
								echo "<div class='info'>";
									echo !empty($author) ? (esc_html_e('by ', 'relish'))."<span>$author</span>" : '';
									if($special_pf): 
										if(!empty($author)): echo RELISH_V_SEP; endif; 
									echo relish_post_format_mark(); endif;
								echo "</div>";
							}?>

						<?php
							$comments_n = get_comments_number();
							if ( (int)$comments_n > 0 ) {
								$permalink .= "#comments";
								echo "<div class='comments_link'><a href='$permalink'>$comments_n <span><i class='fa fa-comment-o'></i></span></a></div>";
							}
						?>						
						<div class="like new_style">
						<?php
							echo relish_get_simple_likes_button( get_the_ID() );
							?>
						</div>
					</div>
				</div>
				<?php
					echo sprintf("%s", $media_content);
				?>
			</div>
		</div>
		<?php
		}else{

			if (!empty($media_content)) {
			?>
				<div class="<?php echo esc_attr($section_class); ?>">
					<?php
						echo sprintf("%s", $media_content);
						if (!empty($custom_layout_arr['boxed_style']) && $custom_layout_arr['boxed_style'] != 'none' && $custom_layout_arr['date_style'] != 'none' ) {
							echo '<div class="date new_style">';
							$first_word_full = esc_attr( mb_substr( $date, 0, $first_word_boundary ) );

							$first_word_short = esc_attr( mb_substr( $date, 0, 3 ) );
							$date = "<span class='date-cont'><span class='day'>". esc_html( str_replace( ',','',mb_substr( $date, $first_word_boundary + 1, 2 ) ) )."</span><span class='month' title='$first_word_full'><span>$first_word_short</span></span><span class='year'>". esc_html( mb_substr( $date, -4 ) ) ."</span></span>";
							echo sprintf("%s", $date);
							echo '</div>';
						}
					?>
				</div>

			<?php

			}
			echo !empty( $title ) ?
			RELISH_BEFORE_CE_TITLE . "<div>" . ( !$is_single ? "<a ".($post_format == 'link' ? "class='{$post_format}'" : '')." href='$permalink'>" : "" ) . $title . ( !$is_single ? "</a>" : "" ) . "</div>" . RELISH_AFTER_CE_TITLE : '';
		}


		global $post;
		global $more;
		$old_version = 0;
		$more = 0;
		$content = '';
		$button_word = '';
		$button_add = false;
		$chars_count = relish_blog_get_chars_count( $custom_layout_arr['column_count'] );
		$char_length = intval( $custom_layout_arr['post_text_length'] ) !== 0 ? intval( $custom_layout_arr['post_text_length'] ) : $chars_count;
		$special_pf = relish_is_special_post_format();
		$comments_n = get_comments_number();
		$author = '';
		$author .= $show_author ? esc_html(get_the_author()) : '';
		if(is_single()){
			if ( ! (intval( $custom_layout_arr['hide_meta'] ) == 1) ) {
				echo '<div class="post_info">';
				if ( !empty($author) || $special_pf ) {
					echo "<div class='info'>";
					echo !empty($author) ? (esc_html_e('by ', 'relish'))."<span>$author</span>" : '';
					if($special_pf): 
						if(!empty($author)): echo RELISH_V_SEP; endif; 
					echo relish_post_format_mark(); endif;
					echo "</div>";
				}
				?>
				<?php
				if ( ( !(!empty($custom_layout_arr['boxed_style']) && $custom_layout_arr['boxed_style'] != 'none') || empty($media_content) ) && $custom_layout_arr['date_style'] != 'none' ) {
				?>

					<div class="date new_style">
						<?php
						echo "<a href='".$permalink."'>";
						if ( $first_word_boundary ) {

							$first_word_full = esc_attr( mb_substr( $date, 0, $first_word_boundary ) );

							$first_word_short = esc_attr( mb_substr( $date, 0, 3 ) );
							$date = "<span class='date-cont'><span class='month' title='$first_word_full'><span>$first_word_short</span> <span class='day'>". esc_html( str_replace( ',','',mb_substr( $date, $first_word_boundary + 1, 2 ) ) )."</span></span>, <span class='year'>". esc_html( mb_substr( $date, -4 ) ) ."</span></span>";
							echo sprintf("%s", $date);
						}
						echo "</a>";
						?>
					</div>
				<?php
				}

				?>				

				<?php
				if ( (int)$comments_n > 0 ) {
					$permalink .= "#comments";
					echo "<div class='comments_link'><a href='$permalink'>$comments_n <span> comments</span></a></div>";
				}
				?>				
				<div class="like new_style">
					<?php
						echo relish_get_simple_likes_button( get_the_ID() );
					?>
				</div>
				<?php
				echo "</div>";
			}
		}
		if ( is_single() ) {
			if(strpos( (string) $post->post_content, '<!--more-->' )){
				$content .= apply_filters('the_content', $post->post_content);
			}
			else{
				$content .= apply_filters('the_content', get_the_content());
			}
			
		} else {
			if ( ! empty( $post->post_excerpt ) ) {
				$content .= $post->post_excerpt;
			} else {
				$button_word = esc_html__( 'Read More', 'relish' );
				$pos = strpos( (string) $post->post_content, '<!--more-->' );
				if ( $pos ) {
					$button_add = true;
				}
				$content .= get_the_content( '[...]' );
			}
		}
		if ( $custom_layout_arr['this_shortcode'] ) {
			$content = ! empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
			$content = trim( preg_replace( '/[\s]{2,}/u', ' ', strip_shortcodes( strip_tags( $content ) ) ) );
			$content = wptexturize( $content );
			$content_length = strlen( $content );
			if ( $content_length > $char_length ) {
				$button_add = false;
				$content = mb_substr( $content, 0, $char_length );
				if ( strlen( $custom_layout_arr['button_name'] ) !== 0 && $custom_layout_arr['custom_layout'] == 1 ) {
					$button_add = true;
					$button_word = esc_html( $custom_layout_arr['button_name'] );
				} else if ( $custom_layout_arr['custom_layout'] == 0 ) {
					$button_add = true;
					$button_word = esc_html__( 'Read More', 'relish' );
				}
				$content .= "<a class='p_cut' href='".esc_url( get_the_permalink() )."'> ...</a>";
			}
		}

		if (! empty( $content ) ) {
			if(strpos(apply_filters( 'the_content', $content ), 'Add Row') !== false){
				echo "<div class='post_content clearfix'>" . apply_filters( 'the_content', $content );
				echo "</div>";
			}else{
				echo "<div class='post_content clearfix'>" . apply_filters( 'the_content', $content );
				echo "</div>";
			}
			
		}else{
			if ( $button_add ) {
				echo "<div class='button_cont'><a href='".esc_url( get_the_permalink() )."' class='cws_button icon-on regular'>" . $button_word . '</a></div>';
			}
		}

		if ($old_version == 1 && !is_single($post) ) {
			if ( ! (intval( $custom_layout_arr['hide_meta'] ) == 1) ) {
				if ( has_tag() ) {
					echo "<div class='post_tags'><i class='fa fa-tag'></i>";
					the_tags ( RELISH_V_SEP );
					echo "</div>";
				}
			}
		}
		if(!is_single()){
			if ( ! (intval( $custom_layout_arr['hide_meta'] ) == 1) ) {
				echo '<div class="post_info">';
				if ( !empty($author) || $special_pf ) {
					echo "<div class='info'>";
					echo !empty($author) ? (esc_html_e('by ', 'relish'))."<span>$author</span>" : '';								
					if($special_pf): 
						if(!empty($author)): echo RELISH_V_SEP; endif; 
					echo relish_post_format_mark(); endif;
					echo "</div>";
				}
				?>
				<?php
				if ( ( !(!empty($custom_layout_arr['boxed_style']) && $custom_layout_arr['boxed_style'] != 'none') || empty($media_content) ) && $custom_layout_arr['date_style'] != 'none' ) {
				?>

					<div class="date new_style">
						<?php
						echo "<a href='".$permalink."'>";
						if ( $first_word_boundary ) {

							$first_word_full = esc_attr( mb_substr( $date, 0, $first_word_boundary ) );

							$first_word_short = esc_attr( mb_substr( $date, 0, 3 ) );
							$date = "<span class='date-cont'><span class='month' title='$first_word_full'><span>$first_word_short</span> <span class='day'>". esc_html( str_replace( ',','',mb_substr( $date, $first_word_boundary + 1, 2 ) ) )."</span></span>, <span class='year'>". esc_html( mb_substr( $date, -4 ) ) ."</span></span>";
							echo sprintf('%s',$date);
						}
						echo "</a>";
						?>
					</div>
				<?php
				}?>


				
				
				<?php
				if ( (int)$comments_n > 0 ) {
					$permalink .= "#comments";
					echo "<div class='comments_link'><a href='$permalink'>$comments_n <span> comments</span></a></div>";
				}				
				?>
				<div class="like new_style">
					<?php
						echo relish_get_simple_likes_button( get_the_ID() );
					?>
				</div>
				<?php

				echo "</div>";
			}
		}

		if ( $button_add ) {
			echo "<div class='button_cont'><a href='".esc_url( get_the_permalink() )."' class='cws_button icon-on regular'>" . $button_word . '</a></div>';
		}
		else{
			if(!is_single()){
				echo "<div class='button_cont'><a href='".esc_url( get_the_permalink() )."' class='cws_button icon-on regular'>" . esc_html__( 'Read More', 'relish' ) . '</a></div>';
			}
		}

	}

function relish_blog_get_chars_count( $cols = null, $p_id = null ) {
	$number = 155;
	$p_id = isset( $p_id ) ? $p_id : get_queried_object_id();
	$sb = relish_get_sidebars( $p_id );
	$sb_layout = isset( $sb['sb_layout_class'] ) ? $sb['sb_layout_class'] : '';
	switch ( $cols ) {
		case '1':
			switch ( $sb_layout ) {
				case 'double':
					$number = 130;
					break;
				case 'single':
					$number = 200;
					break;
				default:
					$number = 300;
			}
			break;
		case '2':
			switch ( $sb_layout ) {
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
			switch ( $sb_layout ) {
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
			switch ( $sb_layout ) {
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

/****************** WALKER *********************/


class relish_Walker_Nav_Menu extends Walker {
	private $elements;
	private $elements_counter = 0;
	private $logo_in_menu_number;
	private $logo_position;
	function __construct() {
		$this->logo_in_menu_number = relish_get_option( 'logo-position-inner' );
		$this->logo_position = relish_get_option( 'logo-position' );
	}

	function walk( $items, $depth ) {
		$this->elements = $this->get_number_of_root_elements( $items );
		return parent::walk( $items, $depth );
	}

	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<span class='button_open'></span><ul class=\"sub-menu\">";
		$output .= "\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	function logo_ini( $indent, $item ) {

		$logo_position = relish_get_option( 'logo-position' );
		$logo = relish_get_option( 'logo' );
		$logo_is_high_dpi = $logo['logo_is_high_dpi'];
		if(!empty($logo)){
		$logo['height'] = wp_get_attachment_image_src($logo['id'], 'full');
			$logo['height'] = $logo['height'][2];
		$logo['width'] = wp_get_attachment_image_src($logo['id'], 'full');
		$logo['width'] = $logo['width'][1];		
		}


		if ( $logo_position == 'in-menu' ) {

			if ( isset( $logo['src'] ) && ( ! empty( $logo['src'] ) ) ) {
				
				$logo_hw = relish_get_option( 'logo-dimensions' );
				$logo_m = relish_get_option( 'logo-margin-in-menu' );
				$cwsfi_args = array();
				if ( is_array( $logo_hw ) ) {
					foreach ( $logo_hw as $key => $value ) {
						if ( ! empty( $value ) ) {
							$cwsfi_args[ $key ] = (int) $value;
							$cwsfi_args['crop'] = true;
						}
					}
				}

				$logo_lr_spacing = '';
				$logo_tb_spacing = '';

				if ( is_array( $logo_m ) ) {
					$logo_lr_spacing .= ( isset( $logo_m['margin-left']) && $logo_m['margin-left'] != '' ? 'margin-left:' . (int) $logo_m['margin-left'] . 'px;' : '' ) .
						( isset( $logo_m['margin-right'] ) && $logo_m['margin-right'] != ''  ? 'margin-right:' . (int) $logo_m['margin-right'] . 'px;' : '' );
					$logo_tb_spacing .= ( isset( $logo_m['margin-top'] ) && $logo_m['margin-top'] != '' ? 'padding-top:' . (int) $logo_m['margin-top'] . 'px;' : '' ) .
						( isset( $logo_m['margin-bottom'] ) && $logo_m['margin-bottom'] != '' ? 'padding-bottom:' . (int) $logo_m['margin-bottom'] . 'px;' : '' );
				}
				$logo_src = '';

				if ( isset( $logo['src'] ) && ( ! empty( $logo['src'] ) ) ) {
					if ( empty( $cwsfi_args ) ) {
						if ( $logo_is_high_dpi ) {
							$thumb_obj = cws_thumb( $logo['src'],array( 'width' => floor( (int) $logo['width'] / 2 ), 'crop' => true ),false );
							$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina class='cwsfi_args'";
							$logo_src = $thumb_path_hdpi;
						} else {
							$logo_src = " src='" . esc_url( $logo['src'] ) . "' data-no-retina class='cwsfi_args'";
						}
					} else {
						$thumb_obj = cws_thumb( $logo['src'],$cwsfi_args,false );
						$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
						$logo_src = $thumb_path_hdpi;
					}
				}
			}

			$logo_sticky = relish_get_option( 'logo_sticky' );
			$logo_sticky_src = '';
			$logo_sticky_is_high_dpi = $logo_sticky['logo_sticky_is_high_dpi'];

			if(!empty($logo_sticky)){
				$logo_sticky['height'] = wp_get_attachment_image_src($logo_sticky['id'], 'full');
				$logo_sticky['height'] = $logo_sticky['height'][2];
				$logo_sticky['width'] = wp_get_attachment_image_src($logo_sticky['id'], 'full');
				$logo_sticky['width'] = $logo_sticky['width'][1];		
			}

			if ( isset( $logo_sticky['src'] ) && ( ! empty( $logo_sticky['src'] ) ) ) {
				$logo_sticky_src = '';
				if ( $logo_sticky_is_high_dpi ) {
					$thumb_obj = cws_thumb( $logo_sticky['src'],array( 'width' => floor( (int) $logo_sticky['width'] / 2 ), 'crop' => true ),false );
					$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
					$logo_sticky_src = $thumb_path_hdpi;
				} else {
					$logo_sticky_src = ' src="' . esc_url( $logo_sticky['src'] ) . '" data-no-retina';
				}
			}

			$logo_mobile = relish_get_option( 'logo_mobile' );
			$logo_mobile_src = '';
			$logo_mobile_is_high_dpi = $logo_mobile['logo_mobile_is_high_dpi'];
			if ( isset( $logo_mobile['src'] ) && ( ! empty( $logo_mobile['src'] ) ) ) {
				if ( $logo_mobile_is_high_dpi ) {
					$thumb_obj = cws_thumb( $logo_mobile['src'],array(),false );
					$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
					$logo_mobile_src = $thumb_path_hdpi;
				} else {
					$logo_mobile_src = " src='" . esc_url( $logo_mobile['src'] ) . "' data-no-retina";
				}
			}

			$rety = esc_url( home_url( '/' ) );
			$img_mrg = ! empty( $logo_lr_spacing ) ? "style='".esc_attr( $logo_lr_spacing )."'" : '';
		}
		if ( $indent == 0 && $logo_position == 'in-menu' && ! empty( $logo['src'] ) && $logo_position == 'in-menu' ) {
			$logo_cont = '<li class="header_logo_part" role="banner">
							<a style="'.(!empty($logo_tb_spacing) ? $logo_tb_spacing : "").'" class="logo" href="'.$rety.'">'.($logo_sticky_src ?  '<img '.$logo_sticky_src." class='logo_sticky' alt />" : '').($logo_mobile_src ?  '<img '.$logo_mobile_src." class='logo_mobile' alt />" : '').'<img '. $logo_src .' '.$img_mrg.' alt /></a>
						</li>';																							               
		} else {
			$logo_cont = '';
		};
		return $logo_cont;
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . sanitize_html_class( $item->ID );

		if ( $item->menu_item_parent == '0' ) {

			$this->elements_counter += 1;			
			if ( $this->elements_counter > $this->elements / 2 ) {
				array_push( $classes,'right' );		
			}
			else{
				array_push( $classes,'left-element' );
			}
			if ( $this->elements_counter == $this->logo_in_menu_number - 1) {
				array_push( $classes,'none-separator' );		
			}
		}			


		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. sanitize_html_class( $item->ID ), $item, $args );
		$id = $id ? ' id="' . $id . '"' : '';

		// logo in cont init;
		if ( $item->menu_item_parent == '0' && $this->elements_counter == $this->logo_in_menu_number ) {
			$logo_container = $this->logo_ini( $indent, $item );
		} else {
			$logo_container = '';
		}


		// bees init
		$bees = '';
		$bees_class = '';

		if ( $item->menu_item_parent == '0' && relish_get_option('menu-with-bees') == 1 ) {
			if ( (1 != $this->logo_in_menu_number && $this->elements_counter == 1) || ($this->logo_position !== 'in-menu' && $this->elements_counter == 1) ) {
				$bees_class = ' bees-start';
			} elseif ( ($this->elements_counter == $this->elements && $this->elements >= $this->logo_in_menu_number) || ($this->logo_position !== 'in-menu' && $this->elements_counter == $this->elements) ) {
				$bees_class = ' bees-end';
			} else {
				$bees = '';
				$bees_class = '';
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . $class_names . $bees_class . '"' : $bees_class;

		$output .= $indent . $logo_container .'<li' . $id . $value . $class_names . '>';
		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']	= ! empty( $item->xfn )	? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? $value : $value;
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = ! empty( $args->before ) ? $args->before : '';
		$item_output .= '<a'. $attributes .'>' . $bees . '';

		$item_output .= ( ! empty( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( ! empty( $args->link_after ) ? $args->link_after : '' );
		$item_output .= $item->menu_item_parent == '0' ? '</a>' : '</a>';
		$item_output .= ( ! empty( $args->after ) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @see Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int    $depth Depth of page. Not Used.
	 */

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$logo_container = '';
		if ( $item->menu_item_parent == '0' && $this->elements < $this->logo_in_menu_number && $this->elements == $this->elements_counter ) {
			$logo_container = $this->logo_ini( $indent, $item );
		}
		$output .= "</li>\n".$logo_container;
	}
}

function relish_themeblvd_time_ago() {

	global $post;

	$date = get_post_time('G', true, $post);

	/**
	 * Where you see 'themeblvd' below, you'd
	 * want to replace those with whatever term
	 * you're using in your theme to provide
	 * support for localization.
	 */ 

	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year', 'relish' ), __( 'years', 'relish' ) ),
		array( 60 * 60 * 24 * 30 , __( 'month', 'relish' ), __( 'months', 'relish' ) ),
		array( 60 * 60 * 24 * 7, __( 'week', 'relish' ), __( 'weeks', 'relish' ) ),
		array( 60 * 60 * 24 , __( 'day', 'relish' ), __( 'days', 'relish' ) ),
		array( 60 * 60 , __( 'hour', 'relish' ), __( 'hours', 'relish' ) ),
		array( 60 , __( 'minute', 'relish' ), __( 'minutes', 'relish' ) ),
		array( 1, __( 'second', 'relish' ), __( 'seconds', 'relish' ) )
	);

	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}

	$current_time = current_time( 'mysql', $gmt );
	$newer_date = ( !$newer_date ) ? strtotime( $current_time ) : $newer_date;

	// Difference in seconds
	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since )
		return __( 'sometime', 'relish' );

	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */

	//Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];

		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 )
			break;
	}

	// Set output var
	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];


	if ( !(int)trim($output) ){
		$output = '0 ' . __( 'seconds', 'relish' );
	}

	$output .= __(' ago', 'relish');

	return $output;
}

// Filter our relish_themeblvd_time_ago() function into WP's the_time() function
add_filter('the_time', 'relish_themeblvd_time_ago');



function relish_testimonial_renderer( $thumbnail, $text, $author, $author_prof, $link = "", $custom_color_settings = null ) {
	ob_start();
	if ( isset( $custom_color_settings->fill_type ) && $custom_color_settings->fill_type == 'color' && isset( $custom_color_settings->fill_color ) ) {
		$figure_color = $custom_color_settings->fill_color;
	}
	$author_section = '';
	if ( !empty( $thumbnail ) || !empty( $author ) ) {
		$author_section .= "<figure class='author'>";
			if ( !empty( $thumbnail ) ) {
				$thumb_obj = cws_thumb( $thumbnail, array( 'width'=>58, 'height'=>58 ), false );
				$thumb_url = esc_url($thumb_obj[0]);
				extract( $thumb_obj[3] );
				if ( $retina_thumb_exists ) {
					$author_section .= "<img src='$thumb_url' data-at2x='$retina_thumb_url'".(!empty($figure_color) ? 'style="border-color:'.$figure_color.';"' : '')." alt />";
				}
				else{
					$author_section .= "<img src='$thumb_url' data-no-retina alt />";
				}
			}
		$author_section .= "</figure>";
	}
	$quote_section_class = "quote";
	$quote_section_styles = '';
	$quote_section_atts = '';
	$quote_section_class .= !empty( $link ) ? " with_link" : '';
	$link = esc_url($link);
	$custom_colors = false;

	if ( is_object( $custom_color_settings ) && !empty( $custom_color_settings ) ) {

		$custom_colors = true;
		$fill_type = isset( $custom_color_settings->fill_type ) ? $custom_color_settings->fill_type : 'color';
		$fill_color = isset( $custom_color_settings->fill_color ) ? $custom_color_settings->fill_color : '';
		$font_color = isset( $custom_color_settings->font_color ) ? $custom_color_settings->font_color : '';

		$gradient_settings = isset( $custom_color_settings->gradient_settings ) ? $custom_color_settings->gradient_settings : new stdClass ();
		$gradient_settings = get_object_vars( $gradient_settings );


		if ( $custom_colors && $fill_type == 'color' ) {
			$quote_section_styles .= isset( $custom_color_settings->fill_color ) || isset( $custom_color_settings->font_color ) ? "border-color:$fill_color;color:$font_color;" : "";
		}
		if ( $custom_colors && $fill_type == 'gradient' ) {
			$quote_section_styles .= relish_render_gradient_rules( array('settings' => $gradient_settings));
			$quote_section_styles .= "color:$font_color;";
		}
	}
	$quote_section_class .= $custom_colors ? " custom_colors" : '';
	$quote_section_atts .= !empty( $quote_section_class ) ? " class='" . trim( $quote_section_class ) . "'" : '';
	$quote_section_atts .= !empty( $quote_section_styles ) ? " style='" . trim( $quote_section_styles ) . "'" : '';
	$text = esc_html($text);
	$quote_section = !empty( $text ) ? "<div" . ( !empty( $quote_section_atts ) ? $quote_section_atts : "" ) . ">$text" .
		 "</div>" : '';
	
	$quote_section .= !empty($author) ? "<figcaption>" . esc_html($author).(!empty($author_prof) ? "<br><span>[ ".esc_html($author_prof)." ]</span>" : '') . "</figcaption>" : '';
	?>
	<div class="testimonial clearfix <?php if(empty($thumbnail)): echo 'without_image';endif;?>">
		<?php
			echo sprintf("%s%s",$author_section, $quote_section);
		?>
	</div>
	<?php
	return ob_get_clean();
}

function relish_get_grid_shortcodes() {
	return array( 'cws-row', 'col', 'cws-widget' );
}

function relish_get_special_post_formats() {
	return array( 'aside' );
}

function relish_is_special_post_format() {
	global $post;
	$sp_post_formats = relish_get_special_post_formats();
	if ( isset($post) ) {
		return in_array( get_post_format(), $sp_post_formats );
	}
	else{
		return false;
	}
}

function relish_post_format_mark() {
	global $post;
	$out = '';
	if ( isset( $post ) ) {
		$pf = get_post_format();
		$post_format_icons = array(
			'aside' => 'bullseye',
			'gallery' =>'bullseye',
			'link' => 'chain',
			'image' => 'image',
			'quote' => 'quote-lef',
			'status' => 'flag',
			'video' => 'video-camer',
			'audio' => 'music',
			'chat' => 'wechat',
		);
		$icon = 'book';
		if (isset($post_format_icons[$pf])) {
			$icon = $post_format_icons[$pf];
		}
		$out = "<i class='fa fa-$icon'></i> $pf";
	}
	return $out;
}

function relish_strip_grid_shortcodes($text) {
	$shortcodes = relish_get_grid_shortcodes ();
	$find = array();
	foreach ( $shortcodes as $shortcode ) {
		$shortcode = preg_replace( "|-|", "\-", $shortcode );
		$op_tag = "|\[.*" . $shortcode . ".*\]|";
		$cl_tag = "|\[/.*" . $shortcode . ".*\]|";
		array_push( $find, $op_tag, $cl_tag );
	}
	$text = preg_replace( $find, "", $text );
	return $text;
}

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require_once( get_template_directory() . '/woocommerce/wooinit.php' ); // WooCommerce Shop ini file
};

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'wp_ajax_woocommerce_remove_from_cart',array( &$this, 'woocommerce_ajax_remove_from_cart' ),1000 );
	add_action( 'wp_ajax_nopriv_woocommerce_remove_from_cart', array( &$this, 'woocommerce_ajax_remove_from_cart' ),1000 );
}

function woocommerce_ajax_remove_from_cart() {
	global $woocommerce;

	$woocommerce->cart->set_quantity( $_POST['remove_item'], 0 );

	$ver = explode( '.', WC_VERSION );

	if ( $ver[1] == 1 && $ver[2] >= 2 ) :
		$wc_ajax = new WC_AJAX();
		$wc_ajax->get_refreshed_fragments();
	else :
		woocommerce_get_refreshed_fragments();
	endif;

	die();
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
}

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
		?>
			<i class='woo_mini-count fa fa-shopping-cart'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></i>
		<?php
		$fragments['.woo_mini-count'] = ob_get_clean();

		ob_start();
		woocommerce_mini_cart();
		$fragments['div.woo_mini_cart'] = ob_get_clean();

		return $fragments;
}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) {
	$args['posts_per_page'] = relish_get_option( 'woo_related_num_products' ); // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}




// Check if WPML is active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
	define('CWS_WPML_ACTIVE', true);
	$GLOBALS['wpml_settings'] = get_option('icl_sitepress_settings');
	global $icl_language_switcher;
}

function relish_is_wpml_active() {
	return defined('CWS_WPML_ACTIVE') && CWS_WPML_ACTIVE;
}

function relish_show_flags_in_footer () {
	return isset( $GLOBALS['wpml_settings']['icl_lang_sel_footer'] ) ? $GLOBALS['wpml_settings']['icl_lang_sel_footer'] : false;
}


// shortcode json attribute conversion
function relish_json_sc_attr_conversion ( $attr ) {
	return is_string( $attr ) ? json_decode( preg_replace ( array( '/\\^\\*/', '/\\*\\$/' ), array( '[', ']' ), $attr ) ) : false;
}

// declare ajaxurl on frontend

/* Comments */

class CWS_Walker_Comment extends Walker_Comment {
	// init classwide variables
	var $tree_type = 'comment';
	var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
	/** CONSTRUCTOR
	 * You'll have to use this if you plan to get to the top of the comments list, as
	 * start_lvl() only goes as high as 1 deep nested comments */
	function __construct() { ?>

		<div class="comment_list">

	<?php }

	/** START_LVL
	 * Starts the list before the CHILD elements are added. Unlike most of the walkers,
	 * the start_lvl function means the start of a nested comment. It applies to the first
	 * new level under the comments that are not replies. Also, it appear that, by default,
	 * WordPress just echos the walk instead of passing it to &$output properly. Go figure.  */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<div class="comments_children">
	<?php }

	/** END_LVL
	 * Ends the children list of after the elements are added. */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		</div><!-- /.children -->

	<?php }

	/** START_EL */
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );
		$old_version = 0;
		?>

		<div <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
			<div id="comment-body-<?php comment_ID() ?>" class="comment-body clearfix">

				<div class="avatar_section">
					<?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>
					
				</div>
				<div class="comment_info_section">
					<div class="comment_info_header">
							<?php $reply_args = array(
								'reply_text' => "<i class='fa fa-reply'></i> &nbsp;",
								'depth' => $depth,
								'max_depth' => $args['max_depth']
							);
							 ?>

						<?php
						echo "<div class='button-content reply'>";
						comment_reply_link( array_merge( $args, $reply_args ) );
						echo "</div>";
						?>
						<div class="comment-meta comment-meta-data">
							<cite class="fn n author-name"><?php echo get_comment_author_link(); ?></cite>
							<span class="comment_date"><?php
								echo "<span class='date'>";
									comment_date('F d');
								echo "</span><span class='sep'></span>";
								
								echo " <span class='time'>";
									comment_time();
								echo "</span>";
								?>
							</span>
							<?php edit_comment_link( '(Edit)' ); ?>
						</div><!-- /.comment-meta -->
						

					</div>

					<div id="comment-content-<?php comment_ID(); ?>" class="comment-content">
						<?php if( !$comment->comment_approved ) : ?>
						<em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'relish'); ?></em>
						<?php else: comment_text(); ?>
						<?php endif; ?>
					</div><!-- /.comment-content -->
				</div>
			</div><!-- /.comment-body -->

	<?php }

	function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

		</div><!-- /#comment-' . get_comment_ID() . ' -->

	<?php }

	/** DESTRUCTOR
	 * I just using this since we needed to use the constructor to reach the top
	 * of the comments list, just seems to balance out :) */
	function __destruct() { ?>

	</div><!-- /#comment-list -->

	<?php }
}

function relish_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<div class="comments_nav carousel_nav_panel clearfix">
		<?php
			if ( $prev_link = get_previous_comments_link( "<span class='prev'></span><span>" . esc_html__( 'Older Comments', 'relish' ) . "</span>" ) ) :
				printf( '<div class="prev_section">%s</div>', esc_html($prev_link) );
			endif;

			if ( $next_link = get_next_comments_link( "<span>" . esc_html__( 'Newer Comments', 'relish' ) . "</span><span class='next'></span>" ) ) :
				printf( '<div class="next_section">%s</div>', esc_html($next_link) );
			endif;
		?>
	</div><!-- .comment-navigation -->
	<?php
	endif;
}

function relish_comment_post( $incoming_comment ) {
	$comment = strip_tags($incoming_comment['comment_content']);
	$comment = esc_html($comment);
	$incoming_comment['comment_content'] = $comment;
	return( $incoming_comment );
}
add_filter('preprocess_comment', 'relish_comment_post', '', 1);

/* \Comments */

/* Social Links */

function relish_render_social_links() {
	$out = '';
	$social_groups_option = relish_get_option( 'social_group' );
	if ( !empty( $social_groups_option ) && is_array( $social_groups_option ) ) {
		$social_groups = array();
		for ( $i=0; $i<count( $social_groups_option ); $i++ ) {
			if ( isset( $social_groups_option[$i]['icon'] ) && !empty( $social_groups_option[$i]['icon'] ) ) {
				$social_groups[] = $social_groups_option[$i];
			}
		}
		foreach ( $social_groups as $social_group ) {
			$title = isset( $social_group['title'] ) ? esc_attr($social_group['title']) : '';
			$icon = $social_group['icon'];
			$url = isset( $social_group['url'] ) ? $social_group['url'] : '';
			$out .= "<a href='" . ( !empty( $url ) ? esc_url($url) : '#' ) . "' class='cws_social_link $icon'" . ( !empty( $title ) ? " title='$title'" : "" ) . " target='_blank'></a>";
		}
		$out = !empty( $out ) ? "<div class='cws_social_links'>$out</div>" : '';
	}
	return $out;
}

/* \Social Links */


function relish_get_date_part ( $part = '' ) {
	$part_val = '';
	$p_id = get_queried_object_id();
	$perm_struct = get_option( 'permalink_structure' );
	$use_perms = !empty( $perm_struct );
	$merge_date = get_query_var( 'm' );
	$match = preg_match( '#(\d{4})?(\d{1,2})?(\d{1,2})?#', $merge_date, $matches );
	switch ( $part ) {
		case 'y':
			$part_val = $use_perms ? get_query_var( 'year' ) : ( isset( $matches[1] ) ? $matches[1] : '' );
			break;
		case 'm':
			$part_val = $use_perms ? get_query_var( 'monthnum' ) : ( isset( $matches[2] ) ? $matches[2] : '' );
			break;
		case 'd':
			$part_val = $use_perms ? get_query_var( 'day' ) : ( isset( $matches[3] ) ? $matches[3] : '' );
			break;
	}
	return $part_val;
}


add_action( 'after_setup_theme', 'relish_after_setup_theme_size_image' );
function relish_after_setup_theme_size_image() {
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'new-size', 370, 370, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes');
	function my_image_sizes($sizes) {
		$addsizes = array(
			"new-size" => __( "Circle Image(Large)",'relish')
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}
}
add_action( 'after_setup_theme', 'relish_after_setup_theme_size_image_circle_small' );
function relish_after_setup_theme_size_image_circle_small() {
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'new-size-small', 271, 271, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes_circle_small');
	function my_image_sizes_circle_small($sizes) {
		$addsizes = array(
			"new-size-small" => __( "Circle Image(Small)",'relish')
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}
}
add_action( 'after_setup_theme', 'relish_after_setup_theme_size_image_circle_medium' );
function relish_after_setup_theme_size_image_circle_medium() {
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'new-size-medium', 340, 340, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes_circle_medium');
	function my_image_sizes_circle_medium($sizes) {
		$addsizes = array(
			"new-size-medium" => __( "Circle Image(Medium)",'relish')
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}
}
add_action( 'after_setup_theme', 'relish_after_setup_theme_size_image_square_small' );
function relish_after_setup_theme_size_image_square_small() {
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'square-size-small', 370, 220, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes_square_small');
	function my_image_sizes_square_small($sizes) {
		$addsizes = array(
			"square-size-small" => __( "Square Image(Small)",'relish')
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}
}

add_action( 'after_setup_theme', 'relish_after_setup_theme_size_image_square_medium' );
function relish_after_setup_theme_size_image_square_medium() {
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'square-size-medium', 570, 340, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes_square_medium');
	function my_image_sizes_square_medium($sizes) {
		$addsizes = array(
			"square-size-medium" => __( "Square Image(Medium)",'relish')
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}
}

add_action( 'after_setup_theme', 'relish_after_setup_theme' );

function relish_after_setup_theme() {
	add_editor_style();
}

add_filter( 'mce_buttons_2', 'relish_mce_buttons_2' );

function relish_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

add_filter( 'tiny_mce_before_init', 'relish_tiny_mce_before_init' );

function relish_tiny_mce_before_init( $settings ) {

	$font_array = relish_get_option( 'header-font' );

	$settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';

	$style_formats = array(
	array( 'title' => 'Title', 'block' => 'h1', 'classes' => 'ce_title' ),
	array( 'title' => 'Sub-title', 'block' => 'p', 'classes' => 'ce_sub_title' ),
	array( 'title' => 'Font-size', 'items' => array(
		array( 'title' => '65px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '65px' , 'line-height' => '2em') ),
		array( 'title' => '40px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '40px' , 'line-height' => '1.2em') ),
		array( 'title' => '30px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '30px' , 'line-height' => '1.4em') ),
		array( 'title' => '20px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '20px' , 'line-height' => '1.6em') ),
		array( 'title' => '16px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '16px' , 'line-height' => '1.75em') ),
		array( 'title' => '14px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em', 'styles' => array( 'font-size' => '14px' , 'line-height' => '1.75em') ),
		)
	),
	array( 'title' => 'margin-top', 'items' => array(
		array( 'title' => '0px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '0' ) ),
		array( 'title' => '10px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '10px' ) ),
		array( 'title' => '15px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '15px' ) ),
		array( 'title' => '20px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '20px' ) ),
		array( 'title' => '25px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '25px' ) ),
		array( 'title' => '30px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '30px' ) ),
		array( 'title' => '40px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '40px' ) ),
		array( 'title' => '50px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '50px' ) ),
		array( 'title' => '60px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '60px' ) ),
		)
	),
	array( 'title' => 'margin-bottom', 'items' => array(
		array( 'title' => '0px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '0px' ) ),
		array( 'title' => '10px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '10px' ) ),
		array( 'title' => '15px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '15px' ) ),
		array( 'title' => '20px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '20px' ) ),
		array( 'title' => '25px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '25px' ) ),
		array( 'title' => '30px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '30px' ) ),
		array( 'title' => '40px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '40px' ) ),
		array( 'title' => '50px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '50px' ) ),
		array( 'title' => '60px', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '60px' ) ),
		)
	),
	array( 'title' => 'Underline Sub-title', 'items' => array(
		array( 'title' => 'gray line', 'selector' => '.ce_sub_title:not(.und-title.white):not(.und-title.themecolor)', 'classes' => 'und-title gray' ),
		array( 'title' => 'Separator', 'selector' => '.ce_sub_title:not(.und-title.themecolor):not(.und-title.gray)', 'classes' => 'und-title white' ),
		array( 'title' => 'theme color line', 'selector' => '.ce_sub_title:not(.und-title.white):not(.und-title.gray)', 'classes' => 'und-title themecolor' ),
		)
	),
	array( 'title' => 'Dropcap', 'items' => array(
		array( 'title' => 'gray', 'selector' => 'h1:not(.dropcap-g),h2:not(.dropcap-g),h3:not(.dropcap-g),h4:not(.dropcap-g),h5:not(.dropcap-g),h6:not(.dropcap-g),p:not(.dropcap-g),span:not(.dropcap-g),i:not(.dropcap-g),b:not(.dropcap-g),strong:not(.dropcap-g),em:not(.dropcap-g),div:not(.dropcap-g)', 'classes' => 'dropcap-l' ),
		array( 'title' => 'green', 'selector' => 'h1:not(.dropcap-l),h2:not(.dropcap-l),h3:not(.dropcap-l),h4:not(.dropcap-l),h5:not(.dropcap-l),h6:not(.dropcap-l),p:not(.dropcap-l),span:not(.dropcap-l),i:not(.dropcap-l),b:not(.dropcap-l),strong:not(.dropcap-l),em:not(.dropcap-l),div:not(.dropcap-l)', 'classes' => 'dropcap-g' ),

	),
	),
	array( 'title' => 'Bullet List', 'items' => array(
		array( 'title' => 'Circle-O', 'selector' => 'ul:not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-check):not(.bullets-list.bullets-list-circle),ol:not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-check):not(.bullets-list.bullets-list-circle)','classes' => 'bullets-list bullets-list-circle-o'),
		array( 'title' => 'Arrow Right', 'selector' => 'ul:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-check):not(.bullets-list.bullets-list-circle),ol:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-check):not(.bullets-list.bullets-list-circle)','classes' => 'bullets-list bullets-list-arrow'),
		array( 'title' => 'Check', 'selector' => 'ul:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-circle),ol:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-circle)','classes' => 'bullets-list bullets-list-check'),
		array( 'title' => 'Circle', 'selector' => 'ul:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-check),ol:not(.bullets-list.bullets-list-circle-o):not(.bullets-list.bullets-list-arrow):not(.bullets-list.bullets-list-check)','classes' => 'bullets-list bullets-list-circle'),
		)
	),
	array( 'title' => 'Float Left', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div,ul', 'classes' => 'fl-l','styles' => array( 'float' => 'left' ) ),
	array( 'title' => 'Float Right', 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div,ul', 'classes' => 'fl-r','styles' => array( 'float' => 'right' ) ),
	);
	// Before 3.1 you needed a special trick to send this array to the configuration.
	// See this post history for previous versions.
	$settings['style_formats'] = str_replace( '"', "'", json_encode( $style_formats ) );

	return $settings;
}
add_cws_shortcode('wp_caption', 'new_caption');
add_cws_shortcode('caption', 'new_caption');

function new_caption( $attr, $content = null ) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	} elseif ( strpos( $attr['caption'], '<' ) !== false ) {
		$attr['caption'] = wp_kses( $attr['caption'], 'post' );
	}

	$output = apply_filters( 'new_caption', '', $attr, $content );
	if ( $output != '' )
		return $output;

	$atts = shortcode_atts( array(
		'id'	  => '',
		'align'	  => 'alignnone',
		'width'	  => '',
		'caption' => '',
		'class'   => '',
	), $attr, 'caption' );


	$atts['width'] = (int) $atts['width'];
	if ( $atts['width'] < 1 || empty( $atts['caption'] ) )
		return $content;

	if ( ! empty( $atts['id'] ) )
		$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';

	$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );

	$html5 = current_theme_supports( 'html5', 'caption' );
	$width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );
	$caption_width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );

	$style = '';
	if ( $caption_width )
		$style = 'style="width: ' . (int) $caption_width . 'px" ';

	$html = '';

	if ( $html5 ) {
		$html = '<div class="cws-wrapper-caption"><figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
		. do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption></figure>';
	} else {
		$html = '<div class="cws-wrapper-caption"><div ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
		. do_shortcode( $content ) . '<p class="wp-caption-text">' . $atts['caption'] . '</p></div>';
	}
	global $post;

	$result = (int) substr($attr['id'], 11);
	$newargs = array('ID'=>$result, 'post_type' => 'attachment', 'post_mime_type' => 'image' ,'post_status' => 'inherit' , 'post_parent' => $post->ID);

	$attachments = get_posts($newargs);
	    if (!empty($attachments)) {
	    	foreach ($attachments as $key => $value) {
	    		if($value->ID == $result)
	    		$html .= "<div class='description-caption'>".$value->post_content."</div>";
	    	}
	    	
	    }
	wp_reset_postdata(); 	
	$html .= "</div>";
	return $html;
}

add_action("wp_ajax_plugin_get_post", "plugin_get_post");
				
add_action("wp_ajax_nopriv_plugin_get_post", "plugin_get_post"); 


//Plugin initialization 
 function plugin_get_post(){
	$data = $_POST['data'];
	extract( shortcode_atts( array(
		'p_id' => $p_id,
		'items_per_page' => $items_per_page,
		'paged' => $paged,
		'columns' => !empty($columns) ? $columns : 'three',
		'categories' => !empty( $categories ) ? $categories : 0,
		'custom_layout' => (int) $custom_layout,
		'dis_meta_info' => !empty($dis_meta_info) ? $dis_meta_info : 0,
		'disable_meta' => !empty( $disable_meta) ?  $disable_meta : 0,
		'post_text_length' => $post_text_length,
		'button_name' => $button_name,
		'url' => '',
		'column_count' => (int) $filter_columns,
		'dis_pagination' => '0',
		'user_pagination' => '0'
	), $data));
	
	if ( empty( $url ) ) return;
	$match = preg_match( "#paged?(=|/)(\d+)#", $url, $matches );
	$paged = $match ? $matches[2] : 1;

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

	$p_id = get_queried_object_id();
	if(isset($categories)){
		$categories = explode( ',', $categories );
		$categories = relish_filter_by_empty( $categories );		
	}

	$disable_meta = (isset($dis_meta_info) && !empty( $dis_meta_info) ?  $dis_meta_info : 0);
	$post_text_length = (isset($post_text_length) && !empty( $post_text_length) ?  $post_text_length : false);
	$button_name = (isset($button_name) && !empty( $button_name) ?  $button_name : false);
	$items_per_page = (isset($items_per_page) && !empty($items_per_page) ? (int) $items_per_page : 3);
	$custom_layout = (isset($custom_layout) && !empty($custom_layout) ? $custom_layout : "0");

	$args = array(
		'post_type' => 'post',
		'paged' => $paged,
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
		'posts_per_page' => $items_per_page,
		'this_shortcode' => true,
		'column_style' => $columns,
		'custom_layout' => (int) $custom_layout,
		'post_text_length' => (int) $post_text_length,
		'button_name' => $button_name,
		'hide_meta' => $disable_meta,
		'column_count' => (int) $columns,
	);

	if ( !empty( $categories ) ) {
		$categories[0] = str_replace("null", "", $categories[0]);
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $categories
			)
		);
	}
	$q = new WP_Query( $args );

	echo "<div class='cws_ajax_response'>";
	if ( $q->have_posts() ) {

		relish_blog_output($q);

		$max_paged = ceil( $q->found_posts / $items_per_page );

		if(isset($data['pagination_style']) && $data['pagination_style'] == 'standard_with_ajax'){
			echo relish_pagination( $paged, $max_paged );
		}
		else{
			echo relish_pagination( $paged, $max_paged, $user_pagination = 'load_more');
		}	
	}		
	
	echo "</div>";


	die();
 }

/*
remove_action( 'wp_head', 'wp_enqueue_scripts', 1 ); //Javascript的调用
remove_action( 'wp_head', 'feed_links', 2 ); //移除feed
remove_action( 'wp_head', 'feed_links_extra', 3 ); //移除feed
remove_action( 'wp_head', 'rsd_link' ); //移除离线编辑器开放接口
remove_action( 'wp_head', 'wlwmanifest_link' );  //移除离线编辑器开放接口
remove_action( 'wp_head', 'index_rel_link' );//去除本页唯一链接信息
remove_action('wp_head', 'parent_post_rel_link', 10, 0 );//清除前后文信息
remove_action('wp_head', 'start_post_rel_link', 10, 0 );//清除前后文信息

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'locale_stylesheet' );
remove_action('publish_future_post','check_and_publish_future_post',10, 1 );
remove_action( 'wp_head', 'noindex', 1 );
remove_action( 'wp_head', 'wp_print_styles', 8 );//载入css
remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
remove_action( 'wp_head', 'wp_generator' ); //移除WordPress版本
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_footer', 'wp_print_footer_scripts' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
add_action('widgets_init', 'my_remove_recent_comments_style');

function my_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ,'recent_comments_style'));
}

//禁止加载WP自带的jquery.js
if ( !is_admin() ) { // 后台不禁止
	function my_init_method() {
		wp_deregister_script( 'jquery' ); // 取消原有的 jquery 定义
	}
	add_action('init', 'my_init_method');
}
wp_deregister_script( 'l10n' );


//清除不需要的样式及JS
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
if ( !function_exists( 'disable_embeds_init' ) ) :
	function disable_embeds_init(){
		global $wp;
		$wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
		remove_action('rest_api_init', 'wp_oembed_register_route');
		add_filter('embed_oembed_discover', '__return_false');
		remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
		remove_action('wp_head', 'wp_oembed_add_discovery_links');
		remove_action('wp_head', 'wp_oembed_add_host_js');
		add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
		add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
	}
	add_action('init', 'disable_embeds_init', 9999);
endif;
*/

//自定义会员后台菜单
add_action('admin_menu', 'register_custom_users_page');

function register_custom_users_page(){

    add_menu_page('Users list', 'Users list', 'administrator', 'customusers', 'custom_users_page', '', 199);
    //plugins_url('myplugin/images/icon.png')

}

//会员统计
function custom_users_page(){

	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

	$where = "WHERE 1=1 ";

	$name = $_GET['name'];
	if(!empty($name)){
		$where .= " AND name LIKE '%{$name}%'";
	}

	$email = $_GET['email'];
	if(!empty($email)){
		$where .= " AND email='{$email}'";
	}

	$mobile = $_GET['mobile'];
	if(!empty($mobile)){
		$where .= " AND mobile='{$mobile}'";
	}

	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

	$per_page = 10;

	$cut = ($page - 1) * $per_page;

	$count = $wpdb->get_var("SELECT COUNT(*) AS total FROM (SELECT COUNT(*) FROM wp_appointment {$where} GROUP BY email) u");

	$sql = "SELECT name,email,mobile FROM wp_appointment {$where} GROUP BY email ORDER BY id DESC LIMIT $cut,{$per_page}";

	$results = $wpdb->get_results($sql);

    echo '
    	<div class="wrap">
    		<h2 class="screen-reader-text">users list</h2>
	       	<ul class="subsubsub">
				<li class="all">
					<a href="edit.php?post_type=post" class="current">All <span class="count">('.$count.')</span></a>
				</li>
			</ul>
	    	<form method="get" action="">
				<div class="tablenav top">
					<div class="alignleft actions">
						<input type="text" id="name" name="name" value="'.$name.'" placeholder="user name"/>
						<input type="text" id="email" name="email" value="'.$email.'" placeholder="user email"/>
						<input type="text" id="mobile" name="mobile" value="'.$mobile.'" placeholder="user mobile"/>
						<input type="hidden" name="page" value="customusers"/>
						<input type="submit" class="button action" value="seach" />
						<input type="button" class="button action" onclick="javascript:window.location.href=\'../wp-content/themes/relish/export.php\'" value="Export Excel" />
					</div>
				</div>
				<h2 class="screen-reader-text">Users list</h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th class="manage-column">name</th>
							<th class="manage-column">email</th>
							<th class="manage-column">mobile</th>
						</tr>
					</thead>
					<tbody>
	';

	foreach ($results as $key => $val):
		echo '<tr><td><strong>'.$val->name.'</strong></td><td><strong>'.$val->email.'</strong></td><td><strong>'.$val->mobile.'</strong></td></tr>';
	endforeach;
							
	echo '
					</tbody>
				</table>
			</form>
		</div>

    ';
		 
	echo paginate_links( array(
	    'base' => add_query_arg( 'cpage', '%#%' ),
	    'format' => '',
	    'prev_text' => __('&laquo;'),
	    'next_text' => __('&raquo;'),
	    'total' => ceil($count / $per_page),
	    'current' => $page
	));

}

//自定义短信后台菜单
add_action('admin_menu', 'register_custom_sms_page');

function register_custom_sms_page(){

    add_menu_page('SMS', 'SMS', 'administrator', 'customsms', 'custom_sms_page', '', 200);
    //plugins_url('myplugin/images/icon.png')

}

//短信统计
function custom_sms_page(){

	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

	$table = 'wp_options';

	$sql = "SELECT option_value FROM {$table} WHERE option_name='used-message'";

	$row = $wpdb->get_row($sql);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$sql = "UPDATE {$table} SET option_value=0 WHERE option_name='used-message'";
    	$wpdb->query($sql);
    	echo '<script>window.location.href="'.admin_url().'admin.php?page=customsms"</script>';
	}

    echo '
    	<div class="wrap">
    		<h2 class="screen-reader-text">Message</h2>
    		<form method="POST" action="">
	       	<ul class="subsubsub">
				<li class="all">
					USED <span class="count">('.$row->option_value.')</span>
				</li>
				<li class="publish">
					<input type="submit" name="submit" onclick="return confirm(\'Do you want to reset the SMS value?\');" class="button action" value="clear" />
				</li>
			</ul>
			</form>
		</div>

    ';
		 

}


//自定义预约时间后台菜单
add_action('admin_menu', 'register_custom_appointment_page');

function register_custom_appointment_page(){

    add_menu_page('Appointment Time', 'Appointment Time', 'administrator', 'customtime', 'custom_appointment_page', '', 201);
    //plugins_url('myplugin/images/icon.png')

}

//预约时间统计
function custom_appointment_page(){

	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

	$sql = "SELECT CONCAT(SUBSTRING(appointmentTime, 11,4),'00') AS time,COUNT(appointmentTime) AS count FROM wp_appointment GROUP BY SUBSTRING(appointmentTime, 11,3) ORDER BY COUNT(appointmentTime) DESC";

	$results = $wpdb->get_results($sql);

    echo '
    	<div class="wrap">
			<h2 class="screen-reader-text">Time list</h2>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th class="manage-column">time</th>
						<th class="manage-column">count</th>
					</tr>
				</thead>
				<tbody>
	';

	foreach ($results as $key => $val):
		echo '<tr><td><strong>'.$val->time.'</strong></td><td><strong>'.$val->count.'</strong></td></tr>';
	endforeach;
							
	echo '
				</tbody>
			</table>
		</div>

    ';

}
