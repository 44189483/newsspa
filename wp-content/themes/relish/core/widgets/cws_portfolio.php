<?php
	/**
	 * Latest Posts Widget Class
	 */
class CWS_Portfolio extends WP_Widget {

	function init_fields() {
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
				'atts' => 'data-default-color="'.RELISH_COLOR.'"',
			),
			'icon_bg_type' => array(
				'title' => esc_html__( 'Background type', 'relish' ),
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
			'cats' => array(
				'type' => 'taxonomy',
				'title' => esc_html__( 'Categories', 'relish' ),
				'atts' => 'multiple',
				'taxonomy' => 'cws_portfolio_cat',
				'source' => array(),
			),
			'count' => array(
				'title' => esc_html__( 'Items Count', 'relish' ),
				'type' => 'number',
			),
		);
	}

	function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'Portfolio Items', 'relish' ) );
		parent::__construct( 'cws-portfolio-widget', esc_html__( 'CWS Portfolio', 'relish' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'show_icon_opts' => '0',
			'cats' => array(),
			'count' => ''
		), $instance));

		$use_blur = relish_get_option('use_blur');
		$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false;

		$title = esc_html($title);

		$count = empty( $count ) ? get_option( 'posts_per_page' ) : $count;
		$show_icon_opts = ($show_icon_opts === 'on') ? '1' : $show_icon_opts;
		$widget_title_icon = $show_icon_opts === '1' ? relish_widget_title_icon_rendering( $instance ) : '';

		$query_args = array(
			'post_type' => 'cws_portfolio',
			'ignore_sticky_posts' => true,
			'post_status' => 'publish',
			'posts_per_page' => $count
		);

		$tax_query = array();
		if ( !empty( $cats ) ){
			$tax_query[] = array(
				'taxonomy' => 'cws_portfolio_cat',
				'field' => 'slug',
				'terms' => $cats
			);
		}

		if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

		$q = new WP_Query( $query_args );

		$several = $q->post_count > 1 ? true : false;
		$gallery_id = esc_attr(uniqid( 'cws-portfolio-gallery-' ));

		echo sprintf('%s',$before_widget);
			if ( !empty( $widget_title_icon ) ){
				if ( !empty( $title ) ){
					echo sprintf('%s',$before_title) . "<div class='widget_title_box'><div class='widget_title_icon_section'>$widget_title_icon</div><div class='widget_title_text_section'>$title</div></div>" . sprintf('%s',$after_title);
				}
				else{
					echo sprintf('%s%s%s',$before_title,$widget_title_icon,$after_title );
				}
			}
			else if ( !empty( $title ) ){
				echo sprintf('%s%s%s', $before_title,$title,$after_title);
			}
			if ( $q->have_posts() ):
				echo "<div class='portfolio_item_thumbs clearfix'>";
					while ( $q->have_posts() ):
						$q->the_post();
						if ( has_post_thumbnail() ){
							$img_url = wp_get_attachment_url( get_post_thumbnail_id() );
							$thumb_obj = cws_thumb( $img_url, array( 'width' => 115, 'height' => 115 ), false );
							$thumb_url = esc_url($thumb_obj[0]);
							extract( $thumb_obj[3] );
							echo "<div class='portfolio_item_thumb'>";
								echo "<div class='pic'>";
									echo "<a href='$img_url' class='fancy'" . ( $several ? " data-fancybox-group='$gallery_id'" : "" ) . ">";
										if ( $retina_thumb_exists ){
											echo "<img src='$thumb_url' data-at2x='$retina_thumb_url' alt />";
										}
										else{
											echo "<img src='$thumb_url' data-no-retina alt />";
										}
										if ( $use_blur ){
											echo "<img src='$thumb_url' class='blured-img' alt />";
										}
										echo "<div class='hover-effect'></div>";
										echo "<div class='links'>";
											echo "<span class='" . ( $several ? 'cwsicon-photo246' : 'cwsicon-magnifying-glass84' ) . "'></span>";
										echo "</div>";
									echo "</a>";
								echo "</div>";
							echo "</div>";
						}
					endwhile;
					wp_reset_postdata();
				echo "</div>";
			else:
				echo do_shortcode( "[cws_sc_msg_box text='" . esc_html__( 'There are no posts matching the query', 'relish' ) . "'][/cws_sc_msg_box]" );
			endif;
		echo sprintf("%s", $after_widget);
	}

	function update( $new_instance, $old_instance ) {
		$instance = (array)$new_instance;
		foreach ($new_instance as $key => $v) {
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
