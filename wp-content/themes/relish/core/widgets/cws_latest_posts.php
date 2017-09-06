<?php
	/**
	 * Latest Posts Widget Class
	 */
class CWS_Latest_Posts extends WP_Widget {
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
				'atts' => 'data-default-color="#ffffff"',
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
			'cats' => array(
				'title' => esc_html__( 'Post categories', 'relish' ),
				'type' => 'taxonomy',
				'taxonomy' => 'category',
				'atts' => 'multiple',
				'source' => array(),
				),
			'count' => array(
				'type' => 'number',
				'title' => esc_html__( 'Post count', 'relish' ),
				'value' => '3',
				),
			'visible_count' => array(
				'type' => 'number',
				'title' => esc_html__( 'Posts per slide', 'relish' ),
				'value' => '3',
				),
			'chars_count' => array(
				'type' => 'number',
				'title' => esc_html__( 'Count of chars from post content', 'relish' ),
				'value' => '50',
				),
			'show_date' => array(
				'type' => 'checkbox',
				'title' => esc_html__( 'Show date', 'relish' ),
				),
		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_cws_recent_entries', 'description' => esc_html__( 'CWS most recent posts', 'relish' ) );
		parent::__construct( 'cws-recent-posts', esc_html__( 'CWS Recent Posts', 'relish' ), $widget_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'show_icon_opts' => '0',
			'cats' => array(),
			'count' => get_option( 'posts_per_page' ),
			'visible_count' => get_option( 'posts_per_page' ),
			'chars_count' => '50',
			'show_date' => '0'
		), $instance));

		$use_blur = relish_get_option( 'use_blur' );

		$title = esc_html($title);

		for ( $i=0; $i<count($cats); $i++ ){
			$term_obj = get_term_by( 'slug', $cats[$i], 'category' );
			$cats[$i] = $term_obj->term_id;
		}

		$footer_is_rendered = isset( $GLOBALS['footer_is_rendered'] );

		/* defaults for empty text fields with number values */
		$count = empty( $count ) ? (int)get_option( 'posts_per_page' ) : (int)$count;
		$visible_count = empty( $visible_count ) ? $count : (int)$visible_count;
		$chars_count = empty( $chars_count ) ? 50 : (int)$chars_count;
		/* \defaults for empty text fields with number values */

		$q_args = array( 'category__in' => $cats, 'posts_per_page' => $count, 'ignore_sticky_posts' => true, 'post_status' => 'publish' );
		$q = new WP_Query( $q_args );

		$show_icon_opts = ($show_icon_opts === 'on') ? '1' : $show_icon_opts;
		$widget_title_icon = $show_icon_opts === '1' ? relish_widget_title_icon_rendering( $instance ) : '';
		$carousel_mode = $count > $visible_count;
		$counter = 0;

		echo sprintf('%s',$before_widget);
			if ( !empty( $title ) ){
				echo sprintf('%s%s%s', $before_title, $title, $after_title);
			}
			if ( $q->have_posts() ){
				$blur_class = $use_blur ? ' blurred' : '';
				if ( $carousel_mode ){
					wp_enqueue_script ('owl_carousel');
					echo "<div class='widget_carousel'>";
				}
				else if ( $footer_is_rendered ){
					echo "<div class='post_items'>";
				}
				while ( $q->have_posts() ):
					$q->the_post();
					$cur_post = get_queried_object();
					$date_format = get_option( 'date_format' );
					$date = esc_html( get_the_time( $date_format ) );
					$permalink = esc_url(get_permalink());
					if ( $carousel_mode && $counter <= 0 ){ /* open carousel item tag */
						echo "<div class='item$blur_class'>";
					}
						echo "<div class='post_item$blur_class'>";
							echo "<div class='post_preview clearfix'>";
								if ( has_post_thumbnail() ):
									$featured_img_url = wp_get_attachment_url( get_post_thumbnail_id() );
									$thumb_obj = cws_thumb( $featured_img_url, array( 'width' => 69, 'height' => 69, 'crop' => true ) , false );
									$thumb_url = esc_url($thumb_obj[0]);
									if(isset($thumb_obj[3]) && !empty($thumb_obj[3])){
										extract( $thumb_obj[3] );
									}
									echo "<div class='post_thumb'>";
										echo "<a href='$permalink'>";
											if ( $retina_thumb_exists ){
												echo "<img src='$thumb_url' data-at2x='$retina_thumb_url' alt />";
											}
											else{
												echo "<img src='$thumb_url' data-no-retina alt />";
											}
										echo "</a>";
									echo "</div>";
								endif;
								$post_title = esc_html( get_the_title() );
								echo !empty( $post_title ) ? "<div class='post_title'><a href='$permalink'>$post_title</a></div>" : "";
								$content = !empty( $cur_post->post_excerpt ) ? $cur_post->post_excerpt : get_the_content( '' );
								$content = trim( preg_replace( "/[\s]{2,}/", " ", strip_shortcodes( strip_tags( $content ) ) ) );
								$is_content_empty = empty( $content );
								if ( !$is_content_empty ){
									if ( strlen( $content ) > $chars_count ){
										$content = mb_substr( $content, 0, $chars_count );
										$content = wptexturize( $content ); /* apply wp filter */
										echo "<div class='post_content'>$content <a href='$permalink'>" . esc_html__( "...", 'relish' ) . "</a></div>";
									}
									else{
										$content = wptexturize( $content ); /* apply wp filter */
										echo "<div class='post_content'>$content</div>";
									}
								}
								if ( $show_date ){
									echo "<div class='post_date'>$date</div>";
								}
							echo "</div>";
						echo "</div>";
					if ( $carousel_mode ){
						if ( $counter >= $visible_count-1 || $q->current_post >= $q->post_count-1 ){
							echo "</div>";
							$counter = 0;
						}
						else{
							$counter ++;
						}
					}
				endwhile;
				wp_reset_postdata();

				if($carousel_mode || $footer_is_rendered){
					echo "</div>";
				}
			}
			else{
				echo do_shortcode( "[cws_sc_msg_box text='" . esc_html__( 'There are no posts matching the query', 'relish' ) . "'][/cws_sc_msg_box]" );
			}
		echo sprintf('%s',$after_widget);
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
