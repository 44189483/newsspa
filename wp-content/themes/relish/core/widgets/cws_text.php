<?php
	/**
	 * CWS Text Widget Class
	 */
class CWS_Text extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'relish' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),
			'show_icon_opts' => array(
				'title' => esc_html__( 'Show icon options', 'relish' ),
				'type' => 'checkbox',
				'atts' => 'data-options="e:icon_type"',
			),
			'icon_type' => array(
				'title' => esc_html__( 'Icon type', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'subtype' => 'images',
				'value' => array(
					'fa' => array( esc_html__( 'icon', 'relish' ), 	true, 	'e:icon_fa;e:icon_color;e:icon_bg_type;d:icon_img', get_template_directory_uri() . '/core/images/align-left.png' ),
					'img' =>array( esc_html__( 'image', 'relish' ), false,	'd:icon_fa;d:icon_color;d:icon_bg_type;e:icon_img', get_template_directory_uri() . '/core/images/align-right.png' ),
				),
			),
			'icon_fa' => array(
				'title' => esc_html__( 'Font Awesome character', 'relish' ),
				'type' => 'select',
				'addrowclasses' => 'disable fai',
				'source' => 'fa',
			),
			'icon_img' => array(
				'title' => esc_html__( 'Custom icon', 'relish' ),
				'addrowclasses' => 'disable',
				'type' => 'media',
			),
			'icon_color' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Icon color', 'relish' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="#ffffff"',
			),
			'icon_bg_type' => array(
				'title' => esc_html__( 'Background', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'none' => array( esc_html__( 'None', 'relish' ), 	true, 	'd:icon_bgcolor;d:gradient_first_color;d:gradient_second_color;d:gradient_type' ),
					'color' => array( esc_html__( 'Color', 'relish' ), 	false, 	'e:icon_bgcolor;d:gradient_first_color;d:gradient_second_color;d:gradient_type' ),
					'gradient' =>array( esc_html__( 'Gradient', 'relish' ), false,'d:icon_bgcolor;e:gradient_first_color;e:gradient_second_color;e:gradient_type' ),
				),
			),
			'icon_bgcolor' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Icon background color', 'relish' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="'.RELISH_COLOR.'"',
			),

			'gradient_first_color' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'From', 'relish' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="'.RELISH_COLOR.'"',
			),
			'gradient_second_color' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'To', 'relish' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="#0eecbd"',
			),
			'gradient_type' => array(
				'title' => esc_html__( 'Gradient type', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'linear' => array( esc_html__( 'Linear', 'relish' ), 	true, 'e:gradient_linear_angle;d:gradient_radial_shape' ),
					'radial' =>array( esc_html__( 'Radial', 'relish' ), false,	'd:gradient_linear_angle;e:gradient_radial_shape' ),
				),
			),
			'gradient_linear_angle' => array(
				'type'      => 'number',
				'title'     => esc_html__( 'Angle', 'relish' ),
				'addrowclasses' => 'disable',
				'value' => '45',
			),
			'gradient_radial_shape' => array(
				'title' => esc_html__( 'Gradient type', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'simple' => array( esc_html__( 'Simple', 'relish' ), 	true, 'e:gradient_radial_type;d:gradient_radial_size_key;d:gradient_radial_size' ),
					'extended' =>array( esc_html__( 'Extended', 'relish' ), false, 'd:gradient_radial_type;e:gradient_radial_size_key;e:gradient_radial_size' ),
				),
			),
			'gradient_radial_type' => array(
				'title' => esc_html__( 'Gradient type', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'ellipse' => array( esc_html__( 'Ellipse', 'relish' ), 	true ),
					'circle' =>array( esc_html__( 'Cirle', 'relish' ), false ),
				),
			),
			'gradient_radial_size_key' => array(
				'type' => 'select',
				'title' => esc_html__( 'Size keyword', 'relish' ),
				'addrowclasses' => 'disable',
				'source' => array(
					'closest-side' => array(esc_html__( 'Closest side', 'relish' ), false),
					'farthest-side' => array(esc_html__( 'Farthest side', 'relish' ), false),
					'closest-corner' => array(esc_html__( 'Closest corner', 'relish' ), false),
					'farthest-corner' => array(esc_html__( 'Farthest corner', 'relish' ), true),
				),
			),
			'gradient_radial_size' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Size', 'relish' ),
				'addrowclasses' => 'disable',
				'atts' => 'placeholder="'.esc_html__('Two space separated percent values, for example (60% 55%)', 'relish').'"',
			),
			'text' => array(
				'title' => esc_html__( 'Text', 'relish' ),
				'type' => 'textarea',
				'atts' => 'rows="10"',
				'value' => '',
			),
			'with_paragraphs' => array(
				'title' => esc_html__( 'Automatically add paragraphs', 'relish' ),
				'type' => 'checkbox',
			),
			'add_link' => array(
				'title' => esc_html__( 'Add link', 'relish' ),
				'type' => 'checkbox',
				'atts' => 'data-options="e:link_url;e:link_text"',
			),
			'link_url' => array(
				'title' => esc_html__( 'Url', 'relish' ),
				'addrowclasses' => 'disable',
				'type' => 'text',
			),
			'link_text' => array(
				'title' => esc_html__( 'Link text', 'relish' ),
				'type' => 'text',
				'addrowclasses' => 'disable',
				'default' => '',
			),
		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-text', 'description' => esc_html__( 'Modified WP Text widget', 'relish' ) );
		parent::__construct( 'cws-text', esc_html__( 'CWS Text', 'relish' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'show_icon_opts' => '0',
			'text' => '',
			'add_link' => '0',
			'link_opts' => '0',
			'link_url' => '',
			'link_text' => '',
		), $instance));

		$show_icon_opts = ($show_icon_opts === 'on') ? '1' : $show_icon_opts;

		$widget_title_icon = $show_icon_opts === '1' ? relish_widget_title_icon_rendering( $instance ) : '';

		$title = esc_html($title);

		$add_link = $add_link === '1' ? true	: false;

		echo sprintf("%s",$before_widget);
			if ( !empty( $title ) ){
				echo sprintf("%s%s%s",$before_title, $title, $after_title);
			}

			$text = do_shortcode( $text );
			$text_section = !empty( $text ) ? "<div class='text'>$text</div>" : "";
			$link_text = esc_html($link_text);
			$link_section = !empty( $link_text ) ? "<div class='link'><a href='" . ( !empty( $link_url ) ? esc_url($link_url) : "#" ) . "' class='cws_button mini'>$link_text</a></div>" : "";

			if ( !empty( $text_section ) || !empty( $link_section ) ){
				echo "<div class='cws_textwidget_content'>";
					echo sprintf('%s', $text_section);
					echo sprintf('%s',$link_section);
				echo "</div>";
			}

		echo sprintf("%s",$after_widget);
	}

	function update( $new_instance, $old_instance ) {
		$instance = (array)$new_instance;
		foreach ($new_instance as $key => $v) {
			if ($v == 'on') {
				$v = '1';
			}
			switch ($this->fields[$key]['type']) {
				case 'text':
					$instance[$key] = strip_tags($v);
					break;
			}
		}
		return $instance;
	}

	function form( $instance ) {
		$this->init_fields();
		$args[0] = $instance;
		cws_mb_fillMbAttributes( $args, $this->fields );
		echo cws_mb_print_layout( $this->fields, 'widget-' . $this->id_base . '[' . $this->number . '][');
	}
}
?>
