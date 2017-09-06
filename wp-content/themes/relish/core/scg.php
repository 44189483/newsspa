<?php
class relish_SCG {
	public function __construct() {
		$this->init();
	}

	private function init() {
		add_filter( 'mce_external_plugins', array($this, 'mce_sc_plugin') );
		add_action( 'wp_ajax_cws_ajax_sc_settings', array($this, 'ajax_sc_settings_callback') );
		add_action( 'admin_enqueue_scripts', array($this, 'scg_scripts_enqueue'), 11 );

		$body_font = relish_get_option( 'body-font' );
		$body_font_color = $body_font['color'];

	}

	public function scg_scripts_enqueue($a) {
		if( $a == 'post-new.php' || $a == 'post.php' ) {
			$prefix = 'cws_sc_';

			wp_register_script( 'cws-redux-sc-settings', get_template_directory_uri() . '/core/js/cws_sc_settings_controller.js', array( 'jquery' ) );
			wp_enqueue_script( 'cws-redux-sc-settings' );
		}
	}

	public function mce_sc_plugin ( $plugin_array ) {
		$plugin_array['cws_shortcodes'] = get_template_directory_uri() . '/core/js/cws_tmce.js';
		return $plugin_array;
	}
	public function ajax_sc_settings_callback () {
		$shortcode = trim( $_POST['shortcode'] );
		$prefix = 'cws_sc_';
		$selection = isset( $_POST['selection'] ) ? stripslashes( trim( $_POST['selection'] ) ) : '';
		$def_content = isset( $_POST['def_content'] ) ? trim( $_POST['def_content'] ) : '';
		$shortcode = substr($shortcode, 7);
		?>
		<script type='text/javascript'>
			var controller = new cws_sc_settings_controller();
		</script>
		<div class="cws_sc_settings_container">
			<input type="hidden" name="cws_sc_name" id="cws_sc_name" value="<?php echo esc_attr($shortcode); ?>" />
			<input type="hidden" name="cws_sc_selection" id="cws_sc_selection" value="<?php echo apply_filters( 'cws_dbl_to_sngl_quotes', $selection); ?>" />
			<input type="hidden" name="cws_sc_def_content" id="cws_sc_def_content" value="<?php echo esc_attr($def_content); ?>" />
			<input type="hidden" name="cws_sc_prefix" id="cws_sc_prefix" value="<?php echo esc_attr($prefix); ?>" />
	<?php
		$meta = array(
			array (
				'text' => $selection,
				)
			);
	?>
		<input type="submit" class="button button-primary button-large" id="cws_insert_button" value="<?php esc_html_e('Insert Shortcode', 'relish' ) ?>">
		</div>
	<?php
		wp_die();
	}
}
?>
