<?php
/*
Plugin Name: CWS Demo Importer
Plugin URI: http://creaws.com/
Description: internal use for creaws themes only.
Text Domain: cws_demo_imp
Version: 2.0.2
*/
define( 'CWS_IMP_VERSION', '2.0.2' );

if (!defined('CWS_IMP_PLUGIN_NAME'))
	define('CWS_IMP_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('CWS_IMP_PLUGIN_DIR'))
	define('CWS_IMP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CWS_IMP_PLUGIN_NAME);

if (!defined('CWS_IMP_PLUGIN_URL'))
	define('CWS_IMP_PLUGIN_URL', WP_PLUGIN_URL . '/' . CWS_IMP_PLUGIN_NAME);


add_action( 'admin_init', 'register_importers' );

function register_importers() {
	register_importer( 'cws_demo_imp', __( 'CWS Demo Importer', 'cws_demo_imp' ), __( 'Import CWS theme\'s demo content.', 'cws_demo_imp'), 'cws_importer' );
}

add_action( 'admin_enqueue_scripts', 'cws_imp_enqueue', 11);

function cws_imp_enqueue($h) {
	if ('admin.php' === $h) {
		if (isset($_GET['import']) && 'cws_demo_imp' === $_GET['import'] && isset($_GET['step']) && '1' === $_GET['step']) {
			wp_enqueue_script( 'cws-imp-js',  CWS_IMP_PLUGIN_URL . '/imp.js', '', CWS_IMP_VERSION );
			wp_enqueue_style( 'cws-imp-css',  CWS_IMP_PLUGIN_URL . '/imp.css', '', CWS_IMP_VERSION );
		}
	}
}

function cws_importer() {
	require_once dirname( __FILE__ ) . '/importer.php';
	// Dispatch
	$importer = new WP_CWS_Demo_Import();
	$importer->dispatch();
}

add_action( 'wp_ajax_cws_imp_run', 'cws_imp_run' );

function cws_imp_run() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cws_imp_ajax') ) {
		$id = $_POST['id'];
		$options = isset($_POST['options']) ? $_POST['options'] : array();
		$upload_dir = wp_upload_dir();
		$xml_upload_dir = $upload_dir['basedir'] . '/cws_demo/';
		$demo_f = sprintf($xml_upload_dir. 'demo%02d.xml', $id);
		require_once dirname( __FILE__ ) . '/importer.php';
		$importer = new WP_CWS_Demo_Import();
		if (file_exists($demo_f)) {
			$importer->id = $demo_f;
			$messages = '';
			ob_start();
			$importer->import( $importer->id, $options, $id );
			$messages = ob_get_clean();
			if (!is_string($messages)) {
				$messages = '';
			}
			//if (!$id) {$id = 21;}
			//if ($id > 27) {$id = 100;}
			echo json_encode(array('id' => $id, 'messages' => $messages));
		} else {
			$importer->finalize();
			delete_option('cwsimp_temp');
		}
	}
	die();
}
?>