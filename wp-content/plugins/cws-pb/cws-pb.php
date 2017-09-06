<?php
/*
Plugin Name: CWS Builder
Plugin URI: http://pb.creaws.com/
Description: internal use for CreaWS themes only.
Text Domain: cws_pb
Version: 3.0.1
*/

define( 'CWS_PB_VERSION', '3.0.0' );
define( 'CWS_PB_REQUIRED_WP_VERSION', '4.0' );

if (!defined('CWS_PB_THEME_DIR'))
	define('CWS_PB_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('CWS_PB_HOST'))
	define('CWS_PB_HOST', 'http://up.creaws.com/cwsbuilder/2');

if (!defined('CWS_PB_PLUGIN_NAME'))
	define('CWS_PB_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('CWS_PB_PLUGIN_DIR'))
	define('CWS_PB_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CWS_PB_PLUGIN_NAME);

if (!defined('CWS_PB_PLUGIN_URL'))
	define('CWS_PB_PLUGIN_URL', WP_PLUGIN_URL . '/' . CWS_PB_PLUGIN_NAME);

$theme = wp_get_theme();
if ($theme->get( 'Template' )) {
	define('PB_THEME_SLUG', $theme->get( 'Template' ));
} else {
	define('PB_THEME_SLUG', $theme->get( 'TextDomain' ));
}

require_once CWS_PB_PLUGIN_DIR . '/shortcodes.php';

function admin_scripts ($hook) {
	global $typenow;

	//$is_richedit = get_user_option('rich_editing', get_current_user_id());
	if ( ('post-new.php' === $hook || 'post.php' === $hook) && 'page' === $typenow ) {
		wp_enqueue_script( 'pbpage-js', CWS_PB_PLUGIN_URL . '/page.js', '', CWS_PB_VERSION, true );
	}
}

add_filter('wp_insert_post_data' , 'filter_post_data', '11', 2);

//add_filter('the_editor', 'cws_content');
//add_filter('the_editor_content', 'cws_ed_content');

add_action( 'admin_enqueue_scripts', 'admin_scripts', 11);

add_action( 'wp_enqueue_scripts', 'fe_scripts', 11);

function fe_scripts($hook) {
	if (isset($_GET['prev']) && 'true' === $_GET['prev']) {
		wp_enqueue_media();

		wp_enqueue_script( 'jquery-ui-draggable');
		wp_enqueue_script( 'jquery-ui-droppable');

		//wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script( 'cwsfe-dialog', CWS_PB_PLUGIN_URL . '/dialog.fix.js', array('jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-button', 'jquery-ui-position'), '', true );
		wp_enqueue_style('wp-jquery-ui-dialog');

		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_style( 'common');
/*		wp_enqueue_style( 'forms');
		wp_enqueue_style( 'dashboard');
		wp_enqueue_style( 'media');*/
		//wp_enqueue_script( 'wp-color-picker');

		// remove retina script just in case
		if (wp_script_is('retina')) {
			wp_dequeue_script('retina');
		}

		wp_enqueue_script('iris',	admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1);
		wp_enqueue_script('wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), false, 1);
		$colorpicker_l10n = array(
			'clear' => __( 'Clear' ),
			'defaultString' => __( 'Default' ),
			'pick' => __( 'Select Color' ),
			'current' => __( 'Current Color' ),
		);
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

		wp_enqueue_style( 'cws-pbfecss', CWS_PB_PLUGIN_URL . '/cwspbfe.css' );
		wp_enqueue_script( 'cws-pbfejs', CWS_PB_PLUGIN_URL . '/pbfe.js', '', '', true );
	}
}

function cws_ed_content($a) {
	echo '<div id="cws-pb-cont" style="display:none">';
	echo $a;
	echo '</div>';
	return $a;
}

function cws_content ( $content ) {
	preg_match("/<textarea[^>]*id=[\"']([^\"']+)\"/", $content, $matches);
	$id = $matches[1];
	if( $id !== "content" )	return $content;
	ob_start();
	include_once( CWS_PB_PLUGIN_DIR . '/pb.php' );
	return $content . ob_get_clean();
}

//add_filter( 'pre_set_site_transient_update_plugins', 'cws_check_for_update_pb' );
//set_transient('update_plugins', 24);

function filter_post_data( $data, $post_arr ) {
	global $typenow;
	//global $cws_pb_content;
	$is_richedit = get_user_option('rich_editing', get_current_user_id());
	if ( 'page' === $typenow && isset($post_arr['cws-pb-out']) ) {

		//var_dump($data);
		//var_dump($post_arr);
		//die;

		$cws_pb_content = !empty($post_arr['cws-pb-out']) ? $post_arr['cws-pb-out'] : '';
		$data['post_content'] = $cws_pb_content;
		$cws_pb_content = '';
	}
	//$data['post_content'] = $post_arr['cws-pb-out'];

	return $data;
}

function cws_check_for_update_pb($transient) {
	if (empty($transient->checked))
		return $transient;
	$pb_path = CWS_PB_PLUGIN_NAME . '/' . CWS_PB_PLUGIN_NAME . '.php';

	$result = wp_remote_get(CWS_PB_HOST . '/cws-pb.php?tname=' . THEME_SLUG);
	if ( isset($result->errors) ) {
		return $transient;
	} else {
		if (200 == $result['response']['code']) {
			$resp = json_decode($result['body']);
			if ( version_compare( CWS_PB_VERSION, $resp->new_version, '<' ) ) {
				$transient->response[$pb_path] = $resp;
			}
		}
	}
	return $transient;
}

$file   = basename( __FILE__ );
$folder = basename( dirname( __FILE__ ) );
$hook = "in_plugin_update_message-{$folder}/{$file}";

function cws_plugins_api($res, $action = null, $args = null) {
	if ( ($action == 'plugin_information') && isset($args->slug) && ($args->slug == CWS_PB_PLUGIN_NAME) ) {
		$result = wp_remote_get(CWS_PB_HOST . '/cws-pb.php?info=1');
		if (200 == $result['response']['code']) {
			$res = json_decode($result['body'], true);
			$res = (object) array_map(__FUNCTION__, $res);
		}
	}
	return $res;
}

add_filter('plugins_api', 'cws_plugins_api', 20, 3);

// front-end part
add_action('template_redirect', 'cwspb_template_redir_hook');

function cwspb_template_redir_hook() {
}

add_action( 'wp_footer', 'cwspb_footer_hook');

add_action( 'init', 'cwsfe_init');

function cwsfe_init() {
	if (isset($_GET['prev']) && 'true' === $_GET['prev']) {
	}
}

function cwspb_footer_hook() {
	if (isset($_GET['prev']) && 'true' === $_GET['prev']) {
		global $post, $tinymce_version;

		//var_dump($post->post_content);
		include_once "pbfe.php";
	}
}

function cwsfe_row_class($class) 		{ return trim($class) . ' cwsfe_row'; }
function cwsfe_widget_class($class) { return trim($class) . ' cwsfe_widget'; }
function cwsfe_col_class($class) 		{ return trim($class) . ' cwsfe_col'; }
function cwsfe_grid_class($class) 	{ return trim($class . ' cwsfe_grid'); }
function cwsfe_igrid_class($class) 	{ return trim($class . ' cwsfe_igrid'); }
function cwsfe_row_atts($atts) { return !empty($atts) ? ' data-atts=\'' . $atts . '\'' : ''; }
function cwsfe_widget_atts($type, $content, $atts) {
	$out = ' data-type="'.$type.'"';
	if (!empty($content)) {
		$out .= ' data-cont=\''.esc_attr($content).'\'';
	}
	if (!empty($atts)) {
		//$atts = str_replace( array('\"'), array('\\\\&quot;'), $atts);
		$out .= ' data-atts=\''.$atts.'\'';
	}
	return $out;
}

add_action( 'wp_ajax_cwsfe_ajax_get_raw_page', 'cwsfe_ajax_get_raw_page' );
add_action( 'wp_ajax_cwsfe_ajax_update_page', 'cwsfe_ajax_update_page' );
add_action( 'wp_ajax_cwsfe_ajax_add_template', 'cwsfe_ajax_add_template' );
add_action( 'wp_ajax_cwsfe_ajax_update_template', 'cwsfe_ajax_update_template' );

function cwsfe_ajax_update_page() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cwsfe_ajax') ) {
		$pid = $_POST['id'];
		$content = $_POST['content'];
		$content = str_replace('\\\"', '\\\\\\"', $content);
		$post_id = wp_update_post( array('ID' => $pid, 'post_content' => $content), true );
		if (is_wp_error($post_id)) {
			$errors = $post_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error;
			}
		}
	} else {
		echo esc_html('Security issues, try to reload this page.', 'cws_pb');
	}
	die();
}

function cwsfe_ajax_update_template() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cwsfe_ajax') ) {
		$templates = get_option('cwsfe_t');
		switch ($_POST['reason']) {
			case 'rename':
				$templates[PB_THEME_SLUG][$_POST['new']] = $templates[PB_THEME_SLUG][$_POST['old']];
				unset($templates[PB_THEME_SLUG][$_POST['old']]);
				break;
			case 'del':
				unset($templates[PB_THEME_SLUG][$_POST['old']]);
				break;
		}
		update_option('cwsfe_t', $templates);
	} else {
		echo esc_html('Security issues, try to reload this page.', 'cws_pb');
	}
	die();
}

function cwsfe_ajax_add_template() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cwsfe_ajax') ) {
		$pid = $_POST['id'];
		cwsfe_addFilters(); // since we do do_shortcode
		$content = stripslashes($_POST['content']);
		//$content = str_replace('\\\"', '\\\\\\"', $content);
		$content = str_replace('\\\"', '\\\\\\"', $content);
		$temp = array(
			$_POST['name'] => array(
				'content' => $content,
			),
		);
		$templates = get_option('cwsfe_t');
		if (empty($templates)) {
			$templates = array(PB_THEME_SLUG => $temp);
		} else {
			$templates[PB_THEME_SLUG][$_POST['name']] = array('content' => $content);
		}
		update_option('cwsfe_t', $templates);
		echo html_entity_decode( do_shortcode($content), ENT_QUOTES);
	} else {
		echo esc_html('Security issues, try to reload this page.', 'cws_pb');
	}
	die();
}

function cwsfe_addFilters() {
	add_filter( 'cwsfe_row_class', 'cwsfe_row_class');
	add_filter( 'cwsfe_widget_class', 'cwsfe_widget_class');
	add_filter( 'cwsfe_col_class', 'cwsfe_col_class');
	add_filter( 'cwsfe_grid_class', 'cwsfe_grid_class');
	add_filter( 'cwsfe_igrid_class', 'cwsfe_igrid_class');
	add_filter( 'cwsfe_widget_atts', 'cwsfe_widget_atts', 10, 3);
	add_filter( 'cwsfe_row_atts', 'cwsfe_row_atts', 10, 1);
}

function cwsfe_ajax_get_raw_page() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cwsfe_ajax') ) {
		$pid = $_POST['id'];
		cwsfe_addFilters();
		$p = get_post($pid);
		$pc = $p->post_content;
		if (0 !== strpos(trim($pc), '[cws-row')) {
			$pc = '[cws-row][cws-grid atts=\'{"_cols":"1"}\'][col span=12][cws-widget type=text]'.$pc.'[/cws-widget][/col][/cws-grid][/cws-row]';
		} else if (false === strpos($pc, '[cws-grid') ) {
			// add grid shortcodes if missing
			$pc = preg_replace('/(\[cws-row.*?\])(.*?)(\[\/cws-row\])/', "$1[cws-grid]$2[/cws-grid]$3", $pc);
		}
		echo json_encode(array('sc' => $pc, 'render' => do_shortcode($pc) ));
	} else {
		echo esc_html('Security issues, try to reload this page.', 'cws_pb');
	}
	die();
}

add_action( 'wp_ajax_cwsfe_ajax_doshortcode', 'cwsfe_ajax_doshortcode' );

function cwsfe_ajax_doshortcode() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cwsfe_ajax') ) {
		global $post, $wp_embed;
		cwsfe_addFilters();
		$post = get_post( $_POST['pid'] );
		$sc = str_replace(
			array('"[', ']"', '\"', '\\\''),
			array('"%5B;', '%5D;"', '%22;', '&#39;'),
			stripslashes($_POST['data']));
		$out = do_shortcode($sc);
		echo $out;
	} else {
		echo esc_html('Security issues, try to reload this page.', 'cws_pb');
	}
	die();
}

add_filter( 'the_content', 'cwspb_content');

function cwspb_content($cont) {
	if (isset($_GET['prev']) && 'true' === $_GET['prev'] && in_the_loop() ) {
		//$cont = '<textarea id="cwsfe_rendered_content" style="display:none">' . $cont . '</textarea>';
		return '<div id="cwspb_lower_panel"><a href="add_row">Add Row</a><div class="clearfix"></div><div class="cloned_d" style="display:none"></div></div>';
	}
	return $cont;
}

add_filter('body_class','cwspb_body_class');

function cwspb_body_class($classes = '') {
	if (isset($_GET['prev']) && 'true' === $_GET['prev']) {
		$classes[] = 'cwspb-active';
	}
	return $classes;
}

add_action( 'admin_bar_menu', 'cwsfe_admin_bar_edit_menu', 81 );

function cwsfe_admin_bar_edit_menu( $wp_admin_bar ) {
	global $tag, $wp_the_query;

	if (isset($_GET['prev']) && 'true' === $_GET['prev'])
		return;

	$current_object = $wp_the_query->get_queried_object();

	if ( empty( $current_object ) || empty($current_object->ID) )
		return;
	//var_dump($current_object);
	$page_link = get_page_link( $current_object->ID );
	$arg_delim = '?';
	if (strpos($page_link, '/?page_id') ) {
		$arg_delim = '&';
	}

	if ( ! empty( $current_object->post_type )
		&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
		&& current_user_can( 'edit_post', $current_object->ID )
		&& $post_type_object->show_in_admin_bar
		&& $edit_post_link = get_edit_post_link( $current_object->ID )
		&& !is_single())
	{
		$wp_admin_bar->add_menu( array(
			'id' => 'edit_with_cwsfe',
			'title' => esc_html('Live Editor', 'cws_pb'),
			'href' => get_page_link( $current_object->ID ) . $arg_delim . 'prev=true',
		) );
	}
}

remove_filter('the_content','wpautop');
remove_filter('the_content','shortcode_unautop');
?>
