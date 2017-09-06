<?php
	/**
	 * CWS Twitter Widget Class
	 */
class CWS_Twitter extends WP_Widget {
	function init_fields () {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget Title', 'relish' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
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
				'atts' => 'data-default-color="#fff"',
			),
			'icon_bg_type' => array(
				'title' => esc_html__( 'Background type', 'relish' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'none' => array( esc_html__( 'None', 'relish' ), 	true, 	'd:icon_bgcolor;d:gradient_first_color;d:gradient_second_color;d:gradient_type' ),
					'color' => array( esc_html__( 'Color', 'relish' ), 	true, 	'e:icon_bgcolor;d:gradient_first_color;d:gradient_second_color;d:gradient_type' ),
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
			'alignment' => array(
				'title' => esc_html__( 'Align', 'relish' ),
				'type' => 'radio',
				'subtype' => 'images',
				'value' => array(
					'left' => array(esc_html__( 'left', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-left.png'),
					'center' => array(esc_html__( 'Center', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/align-center.png'),
					'right' => array(esc_html__( 'right', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-right.png')
				),
			),
			'items' => array(
				'title' => esc_html__( 'Tweets to extract', 'relish' ),
				'type' => 'number',
				'value' => get_option( 'posts_per_page' )
			),
			'visible' => array(
				'title' => esc_html__( 'Tweets to show', 'relish' ),
				'type' => 'number',
				'value' => get_option( 'posts_per_page' )
			),
			'showdate' => array(
				'title' => esc_html__( 'Show date', 'relish' ),
				'type' => 'checkbox',
			)
		);
	}

	function __construct(){
		$widget_ops = array('classname' => 'widget_cws_twitter', 'description' => esc_html__( 'CWS Twitter Widget', 'relish' ) );
		parent::__construct('cws-twitter', esc_html__('CWS Twitter', 'relish' ), $widget_ops);
	}

	function widget ( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'show_icon_opts' => '0',
		), $instance));

		$show_icon_opts = ($show_icon_opts === 'on') ? '1' : $show_icon_opts;
		$widget_title_icon = $show_icon_opts === '1' ? relish_widget_title_icon_rendering( $instance ) : '';

		echo sprintf('%s',$before_widget);
			if ( !empty( $widget_title_icon ) ){
				if ( !empty( $title ) ){
					echo sprintf('%s',$before_title) . "<div class='widget_title_box'><div class='widget_title_icon_section'>$widget_title_icon</div><div class='widget_title_text_section'>$title</div></div>" . sprintf('%s',$after_title);
				}
				else{
					echo sprintf('%s%s%s',$before_title,$widget_title_icon,$after_title);
				}
			}
			else if ( !empty( $title ) ){
				echo sprintf('%s%s%s',$before_title,$title,$after_title);
			}

			$twitter_args = array(
				'in_widget' => true
			);
			if ( isset( $instance['items'] ) ) $twitter_args['items'] = $instance['items'];
			if ( isset( $instance['visible'] ) ) $twitter_args['visible'] = $instance['visible'];
			if ( isset( $instance['showdate'] ) ) $twitter_args['showdate'] = $instance['showdate'];

			if ( isset( $instance['alignment'] ) ) $twitter_args['alignment'] = $instance['alignment'];

			echo relish_twitter_renderer( $twitter_args );

		echo sprintf('%s',$after_widget);
	}

	function form ( $instance ){
		if (function_exists('getTweets')) {
		$this->init_fields();
		$args[0] = $instance;
		cws_mb_fillMbAttributes( $args, $this->fields );
		echo cws_mb_print_layout( $this->fields, 'widget-' . $this->id_base . '[' . $this->number . '][');
		} else {
			echo 'You need to install and activate <b>oAuth Twitter Feed for Developers</b> plugin';
		}
	}
}
?>