<?php
	$pbf_redeclares = array( 'cws_callout_renderer', 'cws_tabs_renderer', 'cws_item_shortcode' );

	function relish_get_pb_options() {
		$body_font_settings = relish_get_option( 'body-font' );
		$body_font_color = isset( $body_font_settings['color'] ) ? $body_font_settings['color'] : '';
		return array(
			'fmodules' => array(

				'basic' => array(
					'title' => esc_html__( 'Content Elements', 'relish'),
					'modules' => array(
						'text' => esc_html__('Text', 'relish'),
						'igrid' => esc_html__('Inner Grid', 'relish'),
						'tabs' => esc_html__('Tabs', 'relish'),
						'accs' => esc_html__('Accordion', 'relish'),
						'button' => esc_html__('Button', 'relish'),
						'progress' => esc_html__("Progress Bar", 'relish'),
						'msg_box' => esc_html__("Message Box", 'relish'),
						'divider' => esc_html__('Divider', 'relish'),
						'callout' => esc_html__('Call to action', 'relish'),
						'flaticon' => esc_html__("CWS Icon", 'relish'),
					),
				),
				'advanced' => array(
					'title' => esc_html__( 'Modules', 'relish' ),
					'modules' => array(
						'blog' => esc_html__('Blog', 'relish'),
						'portfolio' => esc_html__('Portfolio', 'relish'),
						'testimonials' => esc_html__('Testimonials', 'relish'),
						'banners' => esc_html__('Banners', 'relish'),
						'pricing' => esc_html__('Pricing Table', 'relish'),
						'our_team' => esc_html__('Our Team', 'relish'),
						'products_gallery' => esc_html__("Products Gallery", 'relish'),
						'pricing_lists' => esc_html__("Pricing Lists", 'relish'),
						'milestone' => esc_html__('Milestone', 'relish'),
						'embed' => esc_html__('Embedd', 'relish'),
						'carousel' => esc_html__('Carousel', 'relish'),
						'twitter' => esc_html__('Twitter', 'relish'),
					),
				),
			),
			'text' => array(
				'content' => 'Sample text',
				'callback' => 'sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Content', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'insertmedia' => array(
								'type' => 'insertmedia',
								'rowclasses' => 'row',
							),
							'cws-pb-tmce' => array(
								'type' => 'textarea',
								'rowclasses' => 'cws-pb-tmce',
								'atts' => 'class="wp-editor-area" id="wp-editor-area-2"'
							)
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
					'tab2' => array(
						'title' => esc_html__( 'Styling', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'eclass' => array(
								'title' => esc_html__( 'Extra Class', 'relish' ),
								'description' => esc_html__( 'Space separated class names', 'relish' ),
								'type' => 'text',
							),
							'customize_bg' => array(
								'title' => esc_html__( 'Customize', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:bg_media_type;e:bg_color_type;e:font_color"'
							),
							'bg_media_type' => array(
								'title' => esc_html__( 'Media type', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array( 'None', true, 'd:bg_img;d:is_bg_img_high_dpi;d:bg_video_type;d:bg_pattern;d:use_prlx;d:bg_possition;d:bg_repeat;d:bg_attach;' ),
									'img' => array( 'Image', false, 'e:bg_img;e:is_bg_img_high_dpi;e:bg_pattern;e:use_prlx;d:bg_video_type;e:bg_possition;e:bg_repeat;e:bg_attach;' ),
									),
								'addrowclasses' => 'disable',
								),
							'bg_img' => array(
								'title' => esc_html__( 'Image', 'relish' ),
								'type' => 'media',
								'atts' => 'data-role="media"',
								'addrowclasses' => 'disable',
								),
							'bg_attach' => array(
								'title' => esc_html__( 'Background Attachment:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'scroll' => array( 'Scroll', true),
									'fixed' => array( 'Fixed', false ),
									),
								'addrowclasses' => 'disable',
								),
							'bg_possition' => array(
								'title' => esc_html__( 'Background Position:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'left top' => array( 'left top', false),
									'left center' => array( 'left center', false ),
									'left bottom' => array( 'left bottom', false ),
									'right top' => array( 'right top', false ),
									'right center' => array( 'right center', false ),
									'right bottom' => array( 'right bottom', false ),
									'center top' => array( 'center top', false ),
									'center center' => array( 'center center', true ),
									'center bottom' => array( 'center bottom', false )
									),
								'addrowclasses' => 'disable',
								),
							'bg_repeat' => array(
								'title' => esc_html__( 'Background Repeat:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'no-repeat' => array( 'No Repeat', false),
									'repeat' => array( 'Repeat', false ),
									'cover' => array( 'Cover', true ),
									'contain' => array( 'Contain', false )
									),
								'addrowclasses' => 'disable',
								),
							'bg_color_type' =>  array(
								'title' => esc_html__( 'Background Color', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array( 'None', true, 'd:bg_color;d:gradient_start_color;d:gradient_end_color;d:gradient_type;d:bg_color_opacity;' ),
									'color' => array( 'Color', false, 'e:bg_color;e:bg_color_opacity;d:gradient_start_color;d:gradient_end_color;d:gradient_type;' ),
									'gradient' => array( 'Gradient', false, 'e:gradient_start_color;e:gradient_end_color;e:gradient_type;e:bg_color_opacity;d:bg_color;' )
									),
								'addrowclasses' => 'disable',
								),
							'bg_color' => array(
								'title' => esc_html__( 'Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable',
								),
							'gradient_start_color' => array(
								'title' => esc_html__( 'From', 'relish' ),
								'type' => 'text',
								'addrowclasses' => 'disable',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable',
							),
							'gradient_end_color' => array(
								'title' => esc_html__( 'To', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'text',
								'atts' => 'data-default-color="#0eecbd"',
								'value' => '#0eecbd',
								'addrowclasses' => 'disable',
							),
							'gradient_type' => array(
								'title' => esc_html__( 'Type', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'radio',
								'value' => array(
									'linear' => array( 'Linear', true, 'e:gradient_linear_angle;d:gradient_radial_shape_settings_type;' ),
									'radial' => array( 'Radial', false, 'e:gradient_radial_shape_settings_type;d:gradient_linear_angle;' )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_linear_angle' => array(
								'title' => esc_html__( 'Angle', 'relish' ),
								'addrowclasses' => 'disable',
								'description' => esc_html__( 'Degrees: -360 to 360', 'relish' ),
								'type' => 'number',
								'atts' => ' min="-360" max="360" step="1"',
								'value' => '45',
								'addrowclasses' => 'disable',
							),
							'gradient_radial_shape_settings_type' => array(
								'addrowclasses' => 'disable',
								'title' => esc_html__( 'Shape variant', 'relish' ),
								'type' => 'radio',
								'value' => array(
									'simple' => array( 'Simple', true, 'e:gradient_radial_shape;d:gradient_radial_size_keyword;d:gradient_radial_size;' ),
									'extended' => array( 'Extended', false, 'e:gradient_radial_size_keyword;e:gradient_radial_size;d:gradient_radial_shape;' )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_shape' => array(
								'title' => esc_html__( 'Shape', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'radio',
								'value' => array(
									'ellipse' => array( 'Ellipse', true ),
									'circle' => array( 'Circle', false )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_size_keyword' => array(
								'title' => esc_html__( 'Size keyword', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'select',
								'source' => array(
									'closest-side' => array( 'Closest side', false ),
									'farthest-side' => array( 'Farthest side', false ),
									'closest-corner' => array( 'Closest corner', false ),
									'farthest-corner' => array( 'Farthest corner', true )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_size' => array(
								'title' => esc_html__( 'Size', 'relish' ),
								'addrowclasses' => 'disable',
								'description' => esc_html__( 'Two space separated percent values, for example (60%% 55%%)', 'relish' ),
								'type' => 'text',
								'value' => '',
								'addrowclasses' => 'disable',
							),
							'use_prlx' => array(
								'title' => esc_html__( 'Apply Parallax', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:prlx_speed"',
								'addrowclasses' => 'disable',
								),
							'prlx_speed' => array(
								'title' => esc_html__( 'Parallax speed', 'relish' ),
								'type' => 'number',
								'atts' => 'min="1" max="100" step="1"',
								'value' => '50',
								'addrowclasses' => 'disable',
							),
							'bg_color_opacity' => array(
								'title' => esc_html__( 'Opacity', 'relish' ),
								'description' => esc_html__( 'In percents', 'relish' ),
								'type' => 'number',
								'atts' => 'min="1" max="100" step="1"',
								'value' => '100',
								'addrowclasses' => 'disable',
							),
							'bg_pattern' => array(
								'title' => esc_html__( 'Pattern', 'relish' ),
								'type' => 'media',
								'addrowclasses' => 'disable',
							),
							'font_color' => array(
								'title' => esc_html__( 'Font color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . $body_font_color . '"',
								'value' => $body_font_color,
								'addrowclasses' => 'disable',
							),
						),
					),
				)
			),
			'igrid' => array(
				'callback' => 'igrid_sample',
				'content' => '[icol span=12][/icol]',
				'selectors' => array(
					'tabs' => '.igrid_section',
					'content' => '.igrid_content',
				),
				//'init' => 'cws_accordion',
					'layout' => array(
						'_cols' => array(
							'title' => esc_html__( 'Columns', 'relish' ),
							'addrowclasses' => 'columns',
							'type' => 'columns',
							'value' => '1',
						),
					),
			),
			'blog' => array(
				'callback' => 'blog_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Blog',
							),
							'columns' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
								'type' => 'select',
								'source' => array(
									'one' => array( 'One', true ),
									'two' => array( 'Two', false ),
									'three' => array( 'Three', false ),
									'four' => array( 'Four', false ),
								),
							),
							'items_per_page' => array(
								'title' => esc_html__( 'Items per page', 'relish' ),
								'type' => 'number',
								'value' => '1',
							),
							'display' => array(
								'title' => esc_html__( 'Display as', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'grid' => array( 'Grid', true, 'd:slider[autoplay];d:slider[delay];d:slider[colums];d:slider[pagination];d:slider[navigation];e:dis_pagination' ),
									'carousel' => array( 'Carousel', false, 'e:slider[autoplay];e:slider[delay];e:slider[colums];e:slider[pagination];e:slider[navigation];d:dis_pagination' )
								),
							),
							'slider[autoplay]' => array(
								'title' => esc_html__( 'Autoplay', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[pagination]' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[navigation]' => array(
								'title' => esc_html__( 'Navigation', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'custom_layout' => array(
								'title' => esc_html__( 'Custom layout', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:post_text_length;e:button_name;e:dis_meta_info"',
							),
							'post_text_length' => array(
								'title' => esc_html__( 'Post text length', 'relish' ),
								'type' => 'number',
								'value' => '1',
								'addrowclasses' => 'disable',
							),
							'button_name' => array(
								'title' => esc_html__( 'Button Name', 'relish' ),
								'type' => 'text',
								'value' => 'Read More',
								'addrowclasses' => 'disable',
							),
							'dis_meta_info' => array(
								'title' => esc_html__( 'Disable Meta Information', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable'
							),
							'categories' => array(
								'title' => esc_html__( 'Categories', 'relish' ),
								'type' => 'taxonomy',
								'atts' => 'multiple',
								'taxonomy' => 'category'
							),
							'dis_pagination' => array(
								'title' => esc_html__( 'Use Pagination', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:user_pagination"',
							),
							'user_pagination' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'standard' => array( 'Standard', true ),
									'standard_with_ajax' => array( 'Standard With Ajax', true ),
									'load_more' => array( 'Load More', false ),
								)
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'tabs' => array(
				'content' => '[item open=1 title="Tab"][cws-widget-t type=text]Some content 1[/cws-widget-t][/item][item title="Tab"][cws-widget-t type=text]Some content 2[/cws-widget-t][/item]',
				'selectors' => array(
					'tabs' => '.tab',
					'content' => '.tab_section',
				),
				'init' => 'cws_tabs',
				'layout' => array(
					'layout' => array(
						'title' => esc_html__( 'Tabs layout', 'relish' ),
						'type' => 'radio',
						'subtype' => 'images',
						'value' => array(
							'horizontal' => array( esc_html__( 'Horizontal', 'relish' ),  true, 'e:bgcolor;d:gradient;d:bgimage', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
							'vertical' =>array( esc_html__( 'Vertical', 'relish' ), false, 'd:bgcolor;e:gradient;d:bgimage', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
						),
					),
				),
			),
			'tabs_one' => array(
				'layout' => array(
					'title' => array(
						'title' => esc_html__( 'Tab Title', 'relish' ),
						'type' => 'text',
						'value' => 'Tab',
					),
					'customize' => array(
						'title' => esc_html__( 'Customize', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="e:icontype"'
					),
					'icontype' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'radio',
						'addrowclasses' => 'disable',
						'value' => array(
							'iconfa' => array( 'Iconic', true, 'e:iconfa;e:size;d:iconimg;d:width_img;d:height_img' ),
							'iconimg' => array( 'Image', false, 'd:iconfa;d:size;e:iconimg;e:width_img;e:height_img' )
						),
					),
					'iconfa' => array(
						'title' => esc_html__( 'Icon', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai disable',
						'source' => 'fa',
					),
					'size' => array(
						'title' => esc_html__( 'Size', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'disable',
						'source' => array(
							'1x' => array( 'Mini', false ),
							'2x' => array( 'Small', false ),
							'3x' => array( 'Medium', false ),
							'4x' => array( 'Large', true ),
							'5x' => array( 'Extra Large', false )
							),
					),
					'iconimg' => array(
						'title' => esc_html__( 'Image', 'relish' ),
						'type' => 'media',
						'atts' => 'data-role="media"',
						'addrowclasses' => 'disable',
					),
					'width_img' => array(
						'title' => esc_html__( 'Width (px)', 'relish' ),
						'type' => 'number',
						'value' => '30',
						'addrowclasses' => 'disable',
					),
					'height_img' => array(
						'title' => esc_html__( 'Height (px)', 'relish' ),
						'type' => 'number',
						'value' => '30',
						'addrowclasses' => 'disable',
					),
				),
			),

			'accs' => array(
				'content' => '[item open=1 atts=\'{"title":"Accordion"}\'][cws-widget-t type=text]Some content 1[/cws-widget-t][/item][item title="Accordion" atts=\'{"title":"Accordion"}\'][cws-widget-t type=text]Some content 2[/cws-widget-t][/item]',
				'selectors' => array(
					'tabs' => '.accordion_section',
					'content' => '.accordion_content',
				),
				'init' => 'cws_accordion',
				'layout' => array(
					'layout' => array(
						'title' => esc_html__( 'Accordion Type', 'relish' ),
						'type' => 'radio',
						'subtype' => 'images',
						'value' => array(
							'toggle' => array( esc_html__( 'Toggle', 'relish' ),  true, 'e:bgcolor;d:gradient;d:bgimage', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
							'accordion' =>array( esc_html__( 'Accordion', 'relish' ), false, 'd:bgcolor;e:gradient;d:bgimage', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
						),
					),
				),
			),
			'accs_one' => array(
				'layout' => array(
					'title' => array(
						'title' => esc_html__( 'Accordion Title', 'relish' ),
						'type' => 'text',
						'value' => 'Accordion',
					),
					'icontype' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'radio',
						'value' => array(
							'iconfa' => array( 'Iconic', true, 'e:borderless;e:iconfa;e:iconfa_active;d:iconimg' ),
							'iconimg' => array( 'Image', false, 'd:borderless;d:iconfa;d:iconfa_active;e:iconimg' )
						),
					),
					'iconfa_active' => array(
						'title' => esc_html__( 'Icon Active', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai',
						'source' => 'fa',
						'value' => '',
					),
					'iconfa' => array(
						'title' => esc_html__( 'Icon', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai',
						'source' => 'fa',
						'value' => '',
					),
					'iconimg' => array(
						'title' => esc_html__( 'Image', 'relish' ),
						'type' => 'media',
						'atts' => 'data-role="media"',
						'addrowclasses' => 'disable',
					),
					'borderless' => array(
						'title' => esc_html__( 'Draw border', 'relish' ),
						'type' => 'checkbox',
						'addrowclasses' => 'disable'
					),
				),
			),
			'banners' => array(
				'callback' => 'banners_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Banners',
							),
							'items' => array(
								'type' => 'group',
								'addrowclasses' => 'group special_offers sortable cloneable',
								'title' => esc_html__('Banners Columns', 'relish' ),
								'button_title' => esc_html__('Add new column', 'relish' ),
								'layout' => array(
									'tab0' => array(
										'title' => esc_html__( 'Title Settings', 'relish' ),
										'type' => 'tab',
										'layout' => array(
											'banner_style' => array(
												'title' => esc_html__( 'Banner Style:', 'relish' ),
												'type' => 'select',
												'atts' => 'data-options="select:options"',
												'source' => array(
													'style_one' => array( 'Style 1', true,"e:subtitle[text];e:price[sale];d:price[text];e:price[bgtype];e:price[opacity];e:button[text];e:button[textcolor];d:description_img;d:gallery;d:add_hover;d:effectsIhover;d:customize;d:add_item_count"),
													'style_two' => array( 'Style 2', false,"e:subtitle[text];d:price[sale];e:price[text];e:price[bgtype];e:price[opacity];d:button[text];d:button[textcolor];d:description_img;d:gallery;d:add_hover;d:effectsIhover;d:customize;d:add_item_count" ),
													'style_three' => array( 'Style 3', false,"d:subtitle[text];d:price[sale];d:price[text];e:price[bgtype];e:price[opacity];d:button[text];d:button[textcolor];d:description_img;d:gallery;d:add_hover;d:effectsIhover;d:customize;e:add_item_count" ),
													'style_four' => array( 'Style 4', false,"d:subtitle[text];d:price[sale];d:price[text];d:price[bgtype];d:price[opacity];d:button[text];d:button[textcolor];e:description_img;e:gallery;e:add_hover;e:effectsIhover;e:customize;d:add_item_count" ),
												),
											),
											'title[text]' => array(
												'title' => esc_html__('Title', 'relish' ),
												'type' => 'text',
												'atts' => 'data-role="title"',
												'value' => esc_html__('Winter Spa', 'relish' ),
											),
											'subtitle[text]' => array(
												'type' => 'text',
												'addrowclasses' => 'clearfix',
												'title' => esc_html__('Sub-title', 'relish' ),
												'value' => esc_html__('2 Night', 'relish' ),
											),

											'price[sale]' => array(
												'title' => esc_html__('Sale', 'relish' ),
												'type' => 'text',
												'value' => esc_html__('-50%', 'relish' ),
											),
											'price[text]' => array(
												'title' => esc_html__('Price', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'disable',
												'value' => esc_html__('$45', 'relish' ),
											),

											'price[bgtype]' => array(
												'title' => esc_html__( 'Background Type', 'relish' ),
												'type' => 'radio',
												'subtype' => 'images',
												'value' => array(
													'bgcolor' => array( 'Color', true, 'e:price[bgcolor];d:price[bgimg]', get_template_directory_uri() . '/img/fw_img/align-right.png' ),
													'bgimg' => array( 'Image', false, 'd:price[bgcolor];e:price[bgimg]', get_template_directory_uri() . '/img/fw_img/align-left.png' )
												),
											),
											'price[bgcolor]' => array(
												'title' => esc_html__( 'Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR,
											),
											'price[bgimg]' => array(
												'title' => esc_html__( 'Image', 'relish' ),
												'type' => 'media',
												'atts' => 'data-role="media"',
												'addrowclasses' => 'disable',
											),
											'price[opacity]' => array(
												'title' => esc_html__( 'Opacity (%)', 'relish' ),
												'type' => 'number',
												'atts' => 'min="0" max="100" step="1"',
												'value' => '100',
											),
											'button[text]' => array(
												'title' => esc_html__('Button text', 'relish' ),
												'type' => 'text',
												'value' => esc_html__('Best Offer', 'relish' ),
											),
											'button[textcolor]' => array(
												'title' => esc_html__( 'Button Text Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#fff"',
												'value' => '#fff',
											),
											'url[text]' => array(
												'title' => esc_html__( 'Url', 'relish' ),
												'type' => 'text',
												'atts' => 'class="wplink"',
												'value' => '#',
											),
											'target[text]' => array(
												'title' => esc_html__( 'Open in new window', 'relish' ),
												'addrowclasses' => 'clearfix',
												'type' => 'checkbox',
											),
											'description_img' => array(
												'title' => esc_html__('Description Img', 'relish' ),
												'type' => 'textarea',
												'atts' => 'rows="5"',
												'addrowclasses' => 'clearfix disable',
												'value' => "<ul>\n<li>20 Jacuzzi</li>\n<li>20 Steam Room</li>\n</ul>",
											),
											'gallery' => array(
												'title' => esc_html__('Add Gallery', 'relish' ),
												'type' => 'media',
												'addrowclasses' => 'disable',
												'atts' => 'data-role="media"',

											),
											'add_hover' => array(
												'title' => esc_html__( 'Add Hover?', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable',
												'atts' => 'data-options="e:effectsIhover"',
											),
											'effectsIhover' => array(
												'title' => esc_html__( 'Select an effect', 'relish' ),
												'type' => 'select',
												'atts' => 'data-options="select:options"',
												'addrowclasses' => 'disable',
												'source' => array(
													'effect1' => array('Effect 1', true,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect2' => array('Effect 2', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect3' => array('Effect 3', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect4' => array('Effect 4', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect5' => array('Effect 5', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect6' => array('Effect 6', false,'d:direction;e:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect7' => array('Effect 7', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect8' => array('Effect 8', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect9' => array('Effect 9', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect10' => array('Effect 10', false,'d:direction;d:scale;e:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect11' => array('Effect 11', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect12' => array('Effect 12', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect13' => array('Effect 13', false,'d:direction;d:scale;d:directionUPDown;e:directionTwoChoice;d:directionThreeChoice'),
													'effect14' => array('Effect 14', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect15' => array('Effect 15', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect16' => array('Effect 16', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;e:directionThreeChoice'),
													'effect17' => array('Effect 17', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect18' => array('Effect 18', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect19' => array('Effect 19', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
													'effect20' => array('Effect 20', false,'d:direction;d:scale;e:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
												),
											),
											'direction' => array(
												'title' => esc_html__( 'Select an direction', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'disable',
												'source' => array(
													'left_to_right' => array('Left to right', true),
													'right_to_left' => array('Right to left', false),
													'top_to_bottom' => array('Top to bottom', false),
													'bottom_to_top' => array('Bottom to top', false),
												),
											),

											'scale' => array(
												'title' => esc_html__( 'Select an Scale', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'disable',
												'source' => array(
													'scale_up' => array('Left to right', true),
													'scale_down' => array('Right to left', false),
													'scale_down_up' => array('Top to bottom', false),
												),
											),
											'directionUPDown' => array(
												'title' => esc_html__( 'Select an direction', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'disable',
												'source' => array(
													'top_to_bottom' => array('Top to bottom', true),
													'bottom_to_top' => array('Bottom to top', false),
												),
											),
											'directionTwoChoice' => array(
												'title' => esc_html__( 'Select an direction', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'disable',
												'source' => array(
													'from_left_and_right' => array('From left and right', true),
													'top_to_bottom' => array('Top to bottom', false),
													'bottom_to_top' => array('Bottom to top', false),
												),
											),
											'directionThreeChoice' => array(
												'title' => esc_html__( 'Select an direction', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'disable',
												'source' => array(
													'left_to_right' => array('Left to righ', true),
													'right_to_left' => array('Right to left', false),
												),
											),
											'customize' => array(
												'title' => esc_html__( 'Customize', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable',
												'atts' => 'data-options="e:style[bgtype];e:style[opacity]"'
											),
											'style[bgtype]' => array(
												'title' => esc_html__( 'Background type', 'relish' ),
												'type' => 'radio',
												'subtype' => 'images',
												'addrowclasses' => 'disable',
												'value' => array(
													'bgcolor' => array( esc_html__( 'Background color', 'relish' ),  true, 'e:style[bgcolor];d:style[gradient]', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
													'gradient' =>array( esc_html__( 'Background Gradient', 'relish' ), false, 'd:style[bgcolor];e:style[gradient]', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
												),
											),
											'style[bgcolor]' => array(
												'title' => esc_html__( 'Background Color', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'disable',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR,

											),
											'style[gradient]' => array(
												'title' => esc_html__( 'Background Gradient', 'relish' ),
												'type' => 'gradient',
												'atts' => 'disable',
												'addrowclasses' => 'disable',
												'value' => array(
													'orientation' => '90',
													's_color' => RELISH_COLOR,
													'e_color' => '#ffffff',
												),
											),
											'style[opacity]' => array(
												'title' => esc_html__( 'Background Opacity (%)', 'relish' ),
												'type' => 'number',
												'addrowclasses' => 'disable',
												'atts' => 'min="0" max="100" step="1"',
												'value' => '100',

											),
											'add_item_count' => array(
												'title' => esc_html__( 'Add Item Count', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable'
											),

										),
									),
								), // items layout
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'products_gallery' => array(
				'callback' => 'products_gallery_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Products Gallery',
							),
							'side_second_img' => array(
								'title' => esc_html__( 'Side Second Image', 'relish' ),
								'type' => 'select',
								'source' => array(
									'right' => array( 'Right', true ),
									'bottom' => array( 'Bottom', false ),
								),
							),
							'gallery' => array(
								'title' => esc_html__('Add Gallery', 'relish' ),
								'type' => 'gallery',
								'atts' => 'data-role="gallery"',
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'pricing_lists' => array(
				'callback' => 'pricing_lists_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[pricing_title]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Pricing Lists',
							),
							'items' => array(
								'type' => 'group',
								'addrowclasses' => 'group special_offers sortable cloneable',
								'title' => esc_html__('Pricing Columns', 'relish' ),
								'button_title' => esc_html__('Add new column', 'relish' ),
								'layout' => array(
									'tab0' => array(
										'title' => esc_html__( 'Title Settings', 'relish' ),
										'type' => 'tab',
										'layout' => array(
											'title[text]' => array(
												'title' => esc_html__('Title', 'relish' ),
												'type' => 'text',
												'atts' => 'data-role="title"',
												'value' => esc_html__('Special Offers', 'relish' ),
											),
											'text_content' => array(
												'title' => esc_html__('Description', 'relish' ),
												'type' => 'textarea',
												'atts' => 'rows="5"',
												'addrowclasses' => 'clearfix',
												'value' => "An awesome description",
											),
											'add_icon' => array(
												'title' => esc_html__( 'Add Icon?', 'relish' ),
												'type' => 'checkbox',
												'atts' => 'data-options="e:icon"'
											),
											'icon' => array(
												'title' => esc_html__( 'Icon', 'relish' ),
												'type' => 'select',
												'addrowclasses' => 'fai disable',
												'source' => 'fa',
											),

										),
									),
									'tab1' => array(
										'title' => esc_html__( 'Discount Price', 'relish' ),
										'type' => 'tab',
										'init' => 'closed',
										'layout' => array(
											'add_discount_price' => array(
												'title' => esc_html__( 'Add discount price', 'relish' ),
												'type' => 'checkbox',

												'atts' => 'data-options="e:type_discount"'
											),
											'type_discount' => array(
												'title' => esc_html__( 'Type Pricing', 'relish' ),
												'type' => 'select',
												'atts' => 'data-options="select:options"',
												'addrowclasses' => 'disable',
												'source' => array(
													'button' => array(esc_html__( 'Button', 'relish' ), true, "e:url_discount;e:new_window_discount;e:discount_price;e:discount_color;e:add_hover_discount;e:alt_style_discount;e:bgcolor_discount"),
													'text' => array(esc_html__( 'Text', 'relish' ), false, "d:price_btn;d:url_discount;d:new_window_discount;e:discount_price;d:discount_color;d:add_hover_discount;d:alt_style_discount;d:bgcolor_discount")
												),
											),
											'url_discount' => array(
												'title' => esc_html__( 'Url', 'relish' ),
												'type' => 'text',
												'atts' => 'class="wplink"',
												'addrowclasses' => 'disable',
												'value' => '#',
											),
											'new_window_discount' => array(
												'title' => esc_html__( 'Open in new window', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable',
											),
											'discount_price' => array(
												'title' => esc_html__( 'Discount Price', 'relish' ),
												'type' => 'text',
												'value' => '100',
												'addrowclasses' => 'disable'
											),
											'discount_color' => array(
												'title' => esc_html__( 'Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR,
												'addrowclasses' => 'disable'
											),
											'add_hover_discount' => array   (
												'title' => esc_html__( 'Add Hover', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable',
												'atts' => 'data-options="e:font_color_discount"'
											),
											'font_color_discount' => array(
												'title' => esc_html__( 'Font Color', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'disable',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR
											),
											'alt_style_discount' => array(
												'title' => esc_html__( 'Alt Style', 'relish' ),
												'type' => 'checkbox',
												'addrowclasses' => 'disable',
											),
											'bgcolor_discount' => array(
												'title' => esc_html__( 'Background Color', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'disable',
												'atts' => 'data-default-color="' . relish_get_option('theme-custom-color') . '"',
												'value' => relish_get_option('theme-custom-color'),
											),
										),
									),
									'tab2' => array(
										'title' => esc_html__( 'Price', 'relish' ),
										'type' => 'tab',
										'init' => 'closed',
										'layout' => array(

											'type' => array(
												'title' => esc_html__( 'Type Pricing', 'relish' ),
												'type' => 'select',
												'atts' => 'data-options="select:options"',
												'source' => array(
													'button' => array(esc_html__( 'Button', 'relish' ), true, "e:url;e:new_window;e:price_btn;e:price_btn_color;e:price_btn_add_hover;e:price_btn_alt_style;e:bgcolor"),
													'text' => array(esc_html__( 'Text', 'relish' ), false, "d:url;d:new_window;e:price_btn;d:price_btn_color;d:price_btn_add_hover;d:price_btn_alt_style;d:bgcolor")
												),
											),

											'url' => array(
												'title' => esc_html__( 'Url', 'relish' ),
												'type' => 'text',
												'atts' => 'class="wplink"',
												'value' => '#',
											),

											'new_window' => array(
												'title' => esc_html__( 'Open in new window', 'relish' ),
												'type' => 'checkbox',
											),

											'price_btn' => array(
												'title' => esc_html__( 'Price', 'relish' ),
												'type' => 'text',
												'value' => 'From $99.99',
											),

											'price_btn_color' => array(
												'title' => esc_html__( 'Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#fff"',
												'value' => '#ffffff',
											),
											'price_btn_add_hover' => array(
												'title' => esc_html__( 'Add Hover', 'relish' ),
												'type' => 'checkbox',
												'atts' => 'data-options="e:price_btn_font_color"'
											),
											'price_btn_font_color' => array(
												'title' => esc_html__( 'Font Color', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'disable',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR
											),
											'price_btn_alt_style' => array(
												'title' => esc_html__( 'Alt Style', 'relish' ),
												'type' => 'checkbox',
											),
											'bgcolor' => array(
												'title' => esc_html__( 'Background Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR,

											),
										),
									),
								), // items layout
							),

						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),

				),
			),
			'progress' => array(
				'callback' => 'progress_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title' => array(
								'title' => esc_html__( 'Progress Title', 'relish' ),
								'type' => 'text',
								'value' => 'PROGRESS BAR',
							),
							'progress' => array(
								'title' => esc_html__( 'Progress', 'relish' ),
								'desc' => esc_html__( 'In percents (number from 0 to 100)', 'relish' ),
								'type' => 'number',
								'atts' => 'min="1" max="100" step="1"',
								'value' => '50',
							),
							'customize' => array(
								'title' => esc_html__( 'Customize', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:bgtype"'
							),
							'bgtype' => array(
								'title' => esc_html__( 'Background type', 'relish' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'disable',
								'value' => array(
									'bgcolor' => array( esc_html__( 'Color', 'relish' ),  true, 'e:bgcolor;d:gradient', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
									'gradient' =>array( esc_html__( 'Gradient', 'relish' ), false, 'd:bgcolor;e:gradient', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
								),
							),
							'bgcolor' => array(
								'title' => esc_html__( 'Background Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable'
							),
							'gradient' => array(
								'title' => esc_html__( 'Background Gradient', 'relish' ),
								'type' => 'gradient',
								'atts' => 'disable',
								'addrowclasses' => 'disable',
								'value' => array(
									'orientation' => '90',
									's_color' => RELISH_COLOR,
									'e_color' => '#ffffff',
								),
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'twitter' => array(
				'callback' => 'twitter_sample',
				'title' => esc_html__( 'Twitter', 'relish' ),
				'layout' => array(
					'p_title' => array(
						'title' => esc_html__( 'Title', 'relish' ),
						'type' => 'text',
						'value' => 'Twitter',
					),
					'p_items' => array(
						'title' => esc_html__( 'Tweets to extract', 'relish' ),
						'type' => 'number',
						'value' => '10',
					),
					'p_visible' => array(
						'title' => esc_html__( 'Tweets to show', 'relish' ),
						'type' => 'number',
						'value' => '10',
					),
					'p_showdate' => array(
						'title' => esc_html__( 'Show date', 'relish' ),
						'type' => 'checkbox'
					),
				)
			),
			'divider' => array(
				'callback' => 'divider_sample',
				'title' => esc_html__( 'CWS Divider', 'relish' ),
				'layout' => array(
					'type' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'select',
						'atts' => 'data-options="select:options"',
						'source' => array(
							'long' => array(esc_html__( 'Long', 'relish' ), true, "d:width_divider"),
							'short' => array(esc_html__( 'Short', 'relish' ), false, "d:width_divider"),
							'custom' => array(esc_html__( 'Custom Size', 'relish' ), false, "e:width_divider")
						),
					),
					'width_divider' => array(
						'title' => esc_html__( 'Width (px)', 'relish' ),
						'type' => 'number',
						'value' => '10',
						'addrowclasses' => 'disable',
					),
					'add_separate' => array(
						'title' => esc_html__( 'Add Separate', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="e:type_separate"'
					),
					'type_separate' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'radio',
						'addrowclasses' => 'disable',
						'value' => array(
							'iconfa' => array( 'Iconic', true, 'e:iconfa;e:size;d:iconimg;e:color_icon' ),
							'iconimg' => array( 'Image', false, 'd:iconfa;d:size;e:iconimg;d:color_icon' )
						),
					),
					'iconfa' => array(
						'title' => esc_html__( 'Icon', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai disable',
						'source' => 'fa',
					),
					'size' => array(
						'title' => esc_html__( 'Size', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'disable',
						'source' => array(
							'1x' => array( 'Mini', false ),
							'2x' => array( 'Small', false ),
							'3x' => array( 'Medium', false ),
							'4x' => array( 'Large', true ),
							'5x' => array( 'Extra Large', false )
							),
					),
					'color_icon' => array(
						'title' => esc_html__( 'Icon Color', 'relish' ),
						'type' => 'text',
						'addrowclasses' => 'disable',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
					),
					'iconimg' => array(
						'title' => esc_html__( 'Image', 'relish' ),
						'type' => 'media',
						'atts' => 'data-role="media"',
						'addrowclasses' => 'disable',
					),
					'height_divider' => array(
						'title' => esc_html__( 'Height (px)', 'relish' ),
						'type' => 'number',
						'value' => '3',
					),
					'color_divider' => array(
						'title' => esc_html__( 'Divider Color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
					),
				)
			),
			'button' => array(
				'callback' => 'button_sample',
				'content' => 'Button',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Button Title', 'relish' ),
								'type' => 'text',
								'value' => 'Click me',
							),
							'size' => array(
								'title' => esc_html__( 'Size', 'relish' ),
								'type' => 'select',
								'source' => array(
									'mini' => array( 'Mini', false ),
									'small' => array( 'Small', false ),
									'regular' => array( 'Regular', true ),
									'large' => array( 'Large', false ),
								),
							),
							'title[size]' => array(
								'title' => esc_html__( 'Font size (px)', 'relish' ),
								'type' => 'number',
								'value' => '16'
							),
							'add_icon' => array(
								'title' => esc_html__( 'Add Icon?', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:icon;e:icon_pos"'
							),
							'icon' => array(
								'title' => esc_html__( 'Icon', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'fai disable',
								'source' => 'fa',
							),
							'icon_pos' => array(
								'title' => esc_html__( 'Icon position', 'relish' ),
								'type' => 'radio',
								'value' => array(
									'before' => array( esc_html__( 'Before text', 'relish' ),  true),
									'after' => array( esc_html__( 'After text', 'relish' ),  false),
								),
								'addrowclasses' => 'disable',
							),
							'url' => array(
								'title' => esc_html__( 'Url', 'relish' ),
								'type' => 'text',
								'atts' => 'class="wplink"',
								'value' => '#',
								),
							'new_window' => array(
								'title' => esc_html__( 'Open in new window', 'relish' ),
								'type' => 'checkbox',
							),
							'alignment' => array(
								'title' => esc_html__( 'Alignment', 'relish' ),
								'type' => 'radio',
								'subtype' => 'images',
								'value' => array(
									'left' => array(esc_html__( 'left', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-left.png'),
									'center' => array(esc_html__( 'Center', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/align-center.png'),
									'right' => array(esc_html__( 'right', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-right.png')
								),
							),
							'width' => array(
								'title' => esc_html__( 'Width', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'auto' => array( 'Auto Width', true,'d:width_button;d:height_button' ),
									'full' => array( 'Full width', false,'d:width_button;d:height_button' ),
									'custom' => array( 'Custom width', false,'e:width_button;e:height_button' )
								)
							),
							'width_button' => array(
								'title' => esc_html__( 'Width (px)', 'relish' ),
								'type' => 'number',
								'value' => '10',
								'addrowclasses' => 'disable',
							),
							'height_button' => array(
								'title' => esc_html__( 'Height (px)', 'relish' ),
								'type' => 'number',
								'value' => '10',
								'addrowclasses' => 'disable',
							),
							'border_radius' => array(
								'title' => esc_html__( 'Border Radius (px)', 'relish' ),
								'type' => 'number',
								'value' => '3',
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacing', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
					'tab2' => array(
						'title' => esc_html__( 'Styling', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'title[color]' => array(
								'title' => esc_html__( 'Title Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="#fff"',
								'value' => '#fff',
							),
							'add_hover' => array(
								'title' => esc_html__( 'Add Hover', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:font_color"'
							),
							'font_color' => array(
								'title' => esc_html__( 'Font Color', 'relish' ),
								'type' => 'text',
								'addrowclasses' => 'disable',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR
							),
							'alt_style' => array(
								'title' => esc_html__( 'Alt Style', 'relish' ),
								'type' => 'checkbox',
							),
							'bgtype' => array(
								'title' => esc_html__( 'Background type', 'relish' ),
								'type' => 'radio',
								'subtype' => 'images',
								'value' => array(
									'bgcolor' => array( esc_html__( 'Color', 'relish' ),  true, 'e:bgcolor;d:gradient', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
									'gradient' =>array( esc_html__( 'Gradient', 'relish' ), false, 'd:bgcolor;e:gradient', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
								),
							),
							'bgcolor' => array(
								'title' => esc_html__( 'Background Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . relish_get_option('theme-custom-color') . '"',
								'value' => relish_get_option('theme-custom-color'),
							),
							'gradient' => array(
								'title' => esc_html__( 'Background Gradient', 'relish' ),
								'type' => 'gradient',
								'atts' => 'disable',
								'value' => array(
									'orientation' => '90',
									's_color' => RELISH_COLOR,
									'e_color' => '#ffffff',
								),
							),
						),
					),
				),
			),
			'pricing' => array(
				'callback' => 'pricing_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Pricing Table',
							),
							'items' => array(
								'type' => 'group',
								'addrowclasses' => 'group pricing sortable cloneable',
								'title' => esc_html__('Pricing Columns', 'relish' ),
								'button_title' => esc_html__('Add new pricing column', 'relish' ),
								'layout' => array(
									'tab0' => array(
										'title' => esc_html__( 'Title Settings', 'relish' ),
										'type' => 'tab',
										'layout' => array(
											'title[text]' => array(
												'title' => esc_html__('Title', 'relish' ),
												'type' => 'text',
												'atts' => 'data-role="title"',
												'value' => esc_html__('Standard', 'relish' ),
											),

											'text_content' => array(
												'title' => esc_html__('Text', 'relish' ),
												'type' => 'textarea',
												'atts' => 'rows="5"',
												'addrowclasses' => 'clearfix',
												'value' => "<ul>\n<li>20 Jacuzzi</li>\n<li>20 Steam Room</li>\n</ul>",
											),
											'add_active_pricing' => array(
												'title' => esc_html__( 'Active Pricing Table', 'relish' ),
												'addrowclasses' => 'clearfix',
												'type' => 'checkbox',
												'atts' => 'data-options="e:color_active_pricing"'
											),
											'color_active_pricing' => array(
												'title' => esc_html__( 'Background color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#ffca28"',
												'value' => '#ffca28',
												'addrowclasses' => 'disable'
											),
											'price[text]' => array(
												'title' => esc_html__('Price', 'relish' ),
												'type' => 'text',
												'value' => esc_html__('$ 12.99', 'relish' ),
											),
											'price[color]' => array(
												'title' => esc_html__( 'Price Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#fff"',
												'value' => '#fff',
											),
											'price_details[text]' => array(
												'type' => 'text',
												'title' => esc_html__('Pricing details', 'relish' ),
												'value' => esc_html__('monthly', 'relish' ),
											),
											'price[bgtype]' => array(
												'title' => esc_html__( 'Background Type', 'relish' ),
												'type' => 'radio',
												'subtype' => 'images',
												'value' => array(
													'bgcolor' => array( 'Color', true, 'e:price[bgcolor];d:price[bgimage];e:price[opacity]', get_template_directory_uri() . '/img/fw_img/align-right.png' ),
													'bgimage' => array( 'Image', false, 'd:price[bgcolor];e:price[bgimage];d:price[opacity]', get_template_directory_uri() . '/img/fw_img/align-left.png' )
												),
											),
											'price[bgcolor]' => array(
												'title' => esc_html__( 'Background color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#fff"',
												'value' => '#fff',
											),
											'price[bgimage]' => array(
												'title' => esc_html__( 'Background Image', 'relish' ),
												'type' => 'media',
												'atts' => 'data-role="media"',
												'addrowclasses' => 'disable',
											),
											'price[opacity]' => array(
												'title' => esc_html__( 'Background Opacity (%)', 'relish' ),
												'type' => 'number',
												'addrowclasses' => 'disable',
												'atts' => 'min="0" max="100" step="1"',
												'value' => '100',
											),

											'button[url]' => array(
												'title' => esc_html__( 'Url', 'relish' ),
												'type' => 'text',
												'atts' => 'class="wplink"',
												'value' => '#',

											),
											'button[text]' => array(
												'title' => esc_html__('Button text', 'relish' ),
												'type' => 'text',
												'value' => esc_html__('Read more', 'relish' ),
											),
											'button[textcolor]' => array(
												'title' => esc_html__( 'Text Color', 'relish' ),
												'type' => 'text',
												'atts' => 'data-default-color="#fff"',
												'value' => '#fff',
											),
											'button[new_window]' => array(
												'title' => esc_html__( 'Open in new window', 'relish' ),
												'addrowclasses' => 'clearfix',
												'type' => 'checkbox',
											),

											'button[customize]' => array(
												'title' => esc_html__( 'Customize', 'relish' ),
												'type' => 'checkbox',
												'atts' => 'data-options="e:button[bgcolor]"'
											),

											'button[bgcolor]' => array(
												'title' => esc_html__( 'Background Color', 'relish' ),
												'type' => 'text',
												'addrowclasses' => 'grid-col-6 disable',
												'atts' => 'data-default-color="' . RELISH_COLOR . '"',
												'value' => RELISH_COLOR,
											),

										),
									),
								), // items layout
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'carousel' => array(
				'callback' => 'carousel_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Carousel Title', 'relish' ),
								'type' => 'text',
								'value' => 'Carousel',
							),
							'autoplay_carousel' => array(
								'title' => esc_html__( 'Autoplay', 'relish' ),
								'type' => 'checkbox',

							),
							'pagination_carousel' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'checkbox',

							),
							'navigation_carousel' => array(
								'title' => esc_html__( 'Navigation', 'relish' ),
								'type' => 'checkbox',
							),
							'colums_carousel' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
								'type' => 'select',
								'source' => array(
									'one' => array( 'One', false ),
									'two' => array( 'Two', false ),
									'three' => array( 'Three', true ),
									'four' => array( 'Four', false ),
									'five' => array("Five",false)
								),
							),
							'color_carousel' => array(
								'title' => esc_html__( 'Controls Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
							),
							'insertmedia' => array(
								'type' => 'insertmedia',
								'rowclasses' => 'row',
							),
							'cws-pb-tmce' => array(
								'type' => 'textarea',
								'rowclasses' => 'cws-pb-tmce',
								'atts' => 'class="wp-editor-area" id="wp-editor-area-4"',
								'value' => 'asdds',
							)

						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'callout' => array(
				'callback' => 'callout_sample',
				'options' => array (
					'icon_selection' => true,
				),
				'content' => 'Aenean ipsum justo, placerat faucibus egestas sed, fermentum eu est. Aliquam pellentesque nulla et orci sollicitudin tincidunt. Morbi iaculis orci sit amet ligula sodales facilisis',
				'layout' => array(
					'title' => array(
						'title' => esc_html__( 'Title', 'relish' ),
						'type' => 'text',
						'value' => esc_html__( 'CALLOUT', 'relish' ),
					),
					'sub_title' => array(
						'title' => esc_html__( 'Sub Title', 'relish' ),
						'type' => 'text',
						'value' => esc_html__( 'You are Happy, Beautiful and Healthy!', 'relish' ),
					),
					'button_mode' => array(
						'title' => esc_html__( 'Display', 'relish' ),
						'type' => 'select',
						'atts' => 'data-options="select:options"',
						'source' => array(
							'banner' => array( 'Banner', false,'d:c_btn_text;d:c_btn_href;e:banner_title;e:banner_price;e:banner_sub_title;e:c_banner_href' ),
							'button' => array( 'Button', true,'e:c_btn_text;e:c_btn_href;d:banner_title;d:banner_price;d:banner_sub_title;d:c_banner_href' ),
						),
					),
					'banner_price' => array(
						'title' => esc_html__('Price', 'relish' ),
						'type' => 'text',
						'value' => esc_html__('-30%', 'relish' ),
						'addrowclasses' => 'disable'
					),
					'banner_title' => array(
						'title' => esc_html__( 'Banner Title', 'relish' ),
						'type' => 'text',
						'value' => 'ON ROYAL SPA',
						'addrowclasses' => 'disable'
					),
					'banner_sub_title' => array(
						'title' => esc_html__( 'Banner Sub Title', 'relish' ),
						'type' => 'text',
						'value' => 'this week',
						'addrowclasses' => 'disable'
					),
					'c_btn_text' => array(
						'title' => esc_html__( 'Button', 'relish' ),
						'type' => 'text',
						'value' => 'Purchase Now',
					),
					'c_btn_href' => array(
						'title' => esc_html__( 'Button URL', 'relish' ),
						'type' => 'text',
						'atts' => 'class="wplink"',
						'value' => '#',
					),
					'c_banner_href' => array(
						'title' => esc_html__( 'Banner URL', 'relish' ),
						'type' => 'text',
						'atts' => 'class="wplink"',
						'value' => '#',
					),
					'custom_colors' => array(
						'title' => esc_html__( 'Customize', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="e:fill_type;e:font_color;e:btn_bg_color;e:btn_font_color;e:border_color;e:border_radius"'
					),
					'border_radius' => array(
						'title' => esc_html__( 'Border Radius (px)', 'relish' ),
						'type' => 'number',
						'value' => '3',
						'addrowclasses' => 'disable',
					),
					'fill_type' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'radio',
						'addrowclasses' => 'disable',
						'value' => array(
							'color' => array( 'Background Color', true, 'e:fill_color;d:bgimage' ),
							'image' => array( 'Background Image', false, 'd:fill_color;e:bgimage' )
						)
					),
					'fill_color' => array(
						'title' => esc_html__( 'Color', 'relish' ),
						'type' => 'text',
						'addrowclasses' => 'disable',
						'atts' => 'data-default-color="' . RELISH_COLOR . '"',
						'value' => RELISH_COLOR
					),
					'bgimage' => array(
						'title' => esc_html__( 'Image', 'relish' ),
						'type' => 'media',
						'atts' => 'data-role="media"',
					),
					'font_color' => array(
						'title' => esc_html__( 'Font color', 'relish' ),
						'addrowclasses' => 'disable',
						'type' => 'text',
						'atts' => 'data-default-color="' . RELISH_COLOR . '"',
						'value' => RELISH_COLOR
					),
					'btn_font_color' => array(
						'title' => esc_html__( 'Button text color', 'relish' ),
						'addrowclasses' => 'disable',
						'type' => 'text',
						'atts' => 'data-default-color="#fff"',
						'value' => '#fff'
					),
					'module' => array(
						'type' => 'module',
						'name' => 'ani_add'
					),
					'insertmedia' => array(
						'type' => 'insertmedia',
						'rowclasses' => 'row',
					),
					'cws-pb-tmce' => array(
						'type' => 'textarea',
						'rowclasses' => 'cws-pb-tmce',
						'atts' => 'class="wp-editor-area" id="wp-editor-area-3"',
					),
				)
			),
			'flaticon' => array(
				'callback' => 'flaticon_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'cws_icons' => array(
								'title' => esc_html__( 'Icon', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'fai',
								'source' => 'fa',
								'value' => 'fa fa-music',
								//'atts' => 'data-options="select:options"',
							),
							'size' => array(
								'title' => esc_html__( 'Size', 'relish' ),
								'type' => 'select',
								'source' => array(
									'1x' => array( 'Mini', false ),
									'2x' => array( 'Small', false ),
									'3x' => array( 'Medium', false ),
									'4x' => array( 'Large', true ),
									'5x' => array( 'Extra Large', false )
								),
							),
							'url_icon' => array(
								'title' => esc_html__( 'Icon Url', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:url_cws_icon"',
							),
							'url_cws_icon' => array(
								'title' => esc_html__( 'Url', 'relish' ),
								'type' => 'text',
								'atts' => 'class="wplink"',
								'value' => '#',
								'addrowclasses' => 'disable'
							),
							'alignment' => array(
								'title' => esc_html__( 'Alignment', 'relish' ),
								'type' => 'radio',
								'subtype' => 'images',
								'value' => array(
									'left' => array(esc_html__( 'left', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-left.png'),
									'center' => array(esc_html__( 'Center', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/align-center.png'),
									'right' => array(esc_html__( 'right', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-right.png')
								),
							),
							'display_inline' => array(
								'title' => esc_html__( 'Display Inline', 'relish' ),
								'type' => 'checkbox',
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
					'tab2' => array(
						'title' => esc_html__( 'Styling', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'add_hover' => array(
								'title' => esc_html__( 'Add Hover', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:font_icon_hover_color"',
							),
							'font_icon_hover_color' => array(
								'title' => esc_html__( 'Hover Color', 'relish' ),
								'type' => 'text',
								'addrowclasses' => 'disable',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
							),
							'alternative_style' => array(
								'title' => esc_html__( 'Simplify style', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:borderless;d:customize;e:customize_f"',
							),
							'borderless' => array(
								'title' => esc_html__( 'Draw border', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
								'atts' => 'data-options="e:section_border_color;e:section_border_width;e:section_border_style"',
							),
							'section_border_width' => array(
								'title' => esc_html__( 'Border width', 'relish' ),
								'type' => 'number',
								'atts' => ' min="1" step="1"',
								'value' => '1',
								'addrowclasses' => 'disable',
							),
							'section_border_style' => array(
								'title' => esc_html__( 'Border style:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'solid' => array( 'solid', true),
									'dashed' => array( 'dashed', false ),
									'dotted' => array( 'dotted', false )
								),
								'addrowclasses' => 'disable',
							),
							'section_border_color' => array(
								'title' => esc_html__( 'Border color', 'relish' ),
								'type' => 'text',
								'addrowclasses' => 'disable',
								'atts' => 'data-default-color="'.RELISH_COLOR.'"',
								'value' => RELISH_COLOR,
							),
							'customize' => array(
								'title' => esc_html__( 'Customize', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:customize_font_color;e:customize_bg_color"',
							),
							'customize_f' => array(
								'title' => esc_html__( 'Customize', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
								'atts' => 'data-options="e:customize_font_color_s"',
							),
							'customize_font_color_s' => array(
								'title' => esc_html__( 'Font Color', 'relish' ),
								'type' => 'text',
								'atts' =>  'data-default-color="'.RELISH_COLOR.'"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable'
							),

							'customize_font_color' => array(
								'title' => esc_html__( 'Font Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="#fff"',
								'value' => '#fff',
								'addrowclasses' => 'disable'
							),
							'customize_bg_color' => array(
								'title' => esc_html__( 'Background Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="'.RELISH_COLOR.'"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable'
							),
						),
					),
				),
			),
			'embed' => array(
				'callback' => 'embed_sample',
				'title' => esc_html__( 'Embed audio/video file', 'relish' ),
				'layout' => array(
					'title[text]' => array(
						'title' => esc_html__( 'Title', 'relish' ),
						'type' => 'text',
						'value' => 'Our Video',
					),
					'url' => array(
						'title' => esc_html__( 'Url', 'relish' ),
						'desc' => esc_html__( 'Embed url', 'relish' ),
						'type' => 'text',
						'value' => 'https://youtu.be/D2PPBRRh6_Q',
					),
					'width' => array(
						'title' => esc_html__( 'Width', 'relish' ),
						'desc' => esc_html__( 'Max width in pixels', 'relish' ),
						'type' => 'number',
						'value' => '560',
					),
					'height' => array(
						'title' => esc_html__( 'Height', 'relish' ),
						'desc' => esc_html__( 'Max height in pixels', 'relish' ),
						'type' => 'number',
						'value' => '315',
					)
				)
			),
			'testimonials' => array(
				'callback' => 'testimonials_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Testimonials',
							),
							'slider[carousel]' => array(
								'title' => esc_html__( 'Carousel', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:slider[autoplay];e:slider[delay];e:slider[colums];e:slider[pagination];e:slider[navigation]"',

							),
							'img_alignment' => array(
								'title' => esc_html__( 'Alignment', 'relish' ),
								'type' => 'radio',
								'subtype' => 'images',
								'value' => array(
									'left' => array(esc_html__( 'left', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-left.png'),
									'center' => array(esc_html__( 'Center', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/align-center.png'),
									'right' => array(esc_html__( 'right', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-right.png')
								),
							),
							'slider[autoplay]' => array(
								'title' => esc_html__( 'Autoplay', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[pagination]' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[navigation]' => array(
								'title' => esc_html__( 'Navigation', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[colums]' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
								'type' => 'select',
								'source' => array(
									'one' => array( 'One', false ),
									'two' => array( 'Two', false ),
									'three' => array( 'Three', true ),
									'four' => array( 'Four', false )
								),
								'addrowclasses' => 'disable',
							),
							'items' => array(
								'type' => 'group',
								'addrowclasses' => 'group testimonials sortable cloneable',
								'title' => esc_html__('Testimonials', 'relish' ),
								'button_title' => esc_html__('Add new item', 'relish' ),
								'layout' => array(
									'author' => array(
										'type' => 'text',
										'atts' => 'data-role="title"',
										'title' => esc_html__('Author', 'relish' ),
									),

									'iconimg' => array(
										'title' => esc_html__('Author', 'relish' ),
										'type' => 'media',
										'atts' => 'data-role="media"',
									),
									'img_border' => array(
										'title' => esc_html__( 'Draw image border', 'relish' ),
										'type' => 'checkbox',
									),
									'text_content' => array(
										'title' => esc_html__('Text', 'relish' ),
										'type' => 'textarea',
										'atts' => 'rows="5"',
										'value' => 'Sample text',
									),
									'url' => array(
										'title' => esc_html__( 'Url', 'relish' ),
										'type' => 'text',
										'value' => '#',
									),
									'new_window' => array(
										'title' => esc_html__( 'Open in new window', 'relish' ),
										'type' => 'checkbox',
									),
								), // layout
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'portfolio' => array(
				'callback' => 'portfolio_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Portfolio',
							),
							'portfolio_mode' => array(
								'title' => esc_html__( 'Mode', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'circle' => array( 'Circle', true,'e:effectsIhover' ),
									'square' => array( 'Square', false,'d:effectsIhover' ),
								),
							),
							'portfolio_columns' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
								'type' => 'select',
								'source' => array(
									'one' => array( 'One', false ),
									'two' => array( 'Two', false ),
									'three' => array( 'Three', true ),
									'four' => array( 'Four', false ),
								),
							),
							'items_per_page' => array(
								'title' => esc_html__( 'Items per page', 'relish' ),
								'type' => 'number',
								'value' => '1',
							),
							'portfolio_display' => array(
								'title' => esc_html__( 'Display as', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'grid' => array( 'Grid', true, 'd:slider[carousel];d:filter_categories;e:filter_by' ),
									'grid_with_filter' => array( 'Grid With Filter', false, 'd:slider[carousel];e:filter_categories;d:filter_by' ),
									'carousel' => array( 'Carousel', false, 'e:slider[carousel];d:filter_categories;e:filter_by' )
								),
							),
							'slider[carousel]' => array(
								'title' => esc_html__( 'Carousel', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:slider[autoplay];e:slider[delay];e:slider[colums];e:slider[pagination];e:slider[navigation]"',
								'addrowclasses' => 'disable',

							),
							'slider[autoplay]' => array(
								'title' => esc_html__( 'Autoplay', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[pagination]' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[navigation]' => array(
								'title' => esc_html__( 'Navigation', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'effectsIhover' => array(
								'title' => esc_html__( 'Select an effect', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'effect1' => array('Effect 1', true,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect2' => array('Effect 2', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect3' => array('Effect 3', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect4' => array('Effect 4', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect5' => array('Effect 5', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect6' => array('Effect 6', false,'d:direction;e:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect7' => array('Effect 7', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect8' => array('Effect 8', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect9' => array('Effect 9', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect10' => array('Effect 10', false,'d:direction;d:scale;e:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect11' => array('Effect 11', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect12' => array('Effect 12', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect13' => array('Effect 13', false,'d:direction;d:scale;d:directionUPDown;e:directionTwoChoice;d:directionThreeChoice'),
									'effect14' => array('Effect 14', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect15' => array('Effect 15', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect16' => array('Effect 16', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;e:directionThreeChoice'),
									'effect17' => array('Effect 17', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect18' => array('Effect 18', false,'e:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect19' => array('Effect 19', false,'d:direction;d:scale;d:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
									'effect20' => array('Effect 20', false,'d:direction;d:scale;e:directionUPDown;d:directionTwoChoice;d:directionThreeChoice'),
								),
							),
							'direction' => array(
								'title' => esc_html__( 'Select an direction', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'left_to_right' => array('Left to right', true),
									'right_to_left' => array('Right to left', false),
									'top_to_bottom' => array('Top to bottom', false),
									'bottom_to_top' => array('Bottom to top', false),
								),
							),

							'scale' => array(
								'title' => esc_html__( 'Select an Scale', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'scale_up' => array('Left to right', true),
									'scale_down' => array('Right to left', false),
									'scale_down_up' => array('Top to bottom', false),
								),
							),
							'directionUPDown' => array(
								'title' => esc_html__( 'Select an direction', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'top_to_bottom' => array('Top to bottom', true),
									'bottom_to_top' => array('Bottom to top', false),
								),
							),
							'directionTwoChoice' => array(
								'title' => esc_html__( 'Select an direction', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'from_left_and_right' => array('From left and right', true),
									'top_to_bottom' => array('Top to bottom', false),
									'bottom_to_top' => array('Bottom to top', false),
								),
							),
							'directionThreeChoice' => array(
								'title' => esc_html__( 'Select an direction', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'left_to_right' => array('Left to righ', true),
									'right_to_left' => array('Right to left', false),
								),
							),
							'filter_by' => array(
								'title' => esc_html__( 'Filter by', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array(esc_html__( 'None', 'relish' ), true, 'd:portfolio_categories' ),
									'filter_cat' => array( esc_html__( 'Categories', 'relish' ), false, 'e:portfolio_categories' ),
								),
							),

							'portfolio_categories' => array(
								'title' => esc_html__( 'Categories', 'relish' ),
								'type' => 'taxonomy',
								'atts' => 'multiple',
								'taxonomy' => 'cws_portfolio_cat'
							),
							'filter_categories' => array(
								'title' => esc_html__( 'Categories', 'relish' ),
								'type' => 'taxonomy',
								'atts' => 'multiple',
								'taxonomy' => 'cws_portfolio_cat',
								'addrowclasses' => 'disable',
							),
							'dis_pagination' => array(
								'title' => esc_html__( 'Use Pagination', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:user_pagination"',
							),
							'user_pagination' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'select',
								'addrowclasses' => 'disable',
								'source' => array(
									'standard' => array( 'Standard', true ),
									'load_more' => array( 'Load More', false ),
								)
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'milestone' => array(
				'callback' => 'milestone_sample',
				'title' => esc_html__( 'CWS Milestone', 'relish' ),
				'layout' => array(
					'title[text]' => array(
						'title' => esc_html__( 'Title', 'relish' ),
						'type' => 'text',
						'value' => 'Title',
					),
					'mode' => array(
						'title' => esc_html__( 'Mode', 'relish' ),
						'type' => 'select',
						'source' => array(
							'circle' => array( 'Circle', true ),
							'square' => array( 'Square', false ),
						),
					),
					'iconless' => array(
						'title' => esc_html__( 'Hide Icon', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="d:icon"',
					),
					'icon' => array(
						'title' => esc_html__( 'Icon', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai',
						'source' => 'fa',
					),
					'number' => array(
						'title' => esc_html__( 'Number', 'relish' ),
						'type' => 'number',
						'value' => '950'
					),
					'speed' => array(
						'title' => esc_html__( 'Speed', 'relish' ),
						'desc' => esc_html__( 'Should be integer', 'relish' ),
						'type' => 'number',
						'value' => '2000'
					),
					'custom_color_settings[font_color]' => array(
						'title' => esc_html__( 'Font Color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="#fff"',
						'value' => "#fff",
					),
					'borderless' => array(
						'title' => esc_html__( 'Hide border', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="d:custom_color_settings[border_color]"',
					),
					'custom_color_settings[border_color]' => array(
						'title' => esc_html__( 'Border color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
					),
					'bgcolor' => array(
						'title' => esc_html__( 'Background color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
						),
					'bgimage' => array(
						'title' => esc_html__( 'Background Image', 'relish' ),
						'type' => 'media',
						'atts' => 'data-role="media"',
						),
					'opacity' => array(
						'title' => esc_html__( 'Background Opacity (%)', 'relish' ),
						'type' => 'number',
						'atts' => 'min="0" max="100" step="1"',
						'value' => '90',
					),
				)
			),
			'our_team' => array(
				'callback' => 'our_team_sample',
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'Basic settings', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'title[text]' => array(
								'title' => esc_html__( 'Title', 'relish' ),
								'type' => 'text',
								'value' => 'Our Team',
							),
							'columns' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
								'type' => 'select',
								'source' => array(
									'one' => array( 'One', false ),
									'two' => array( 'Two', false ),
									'three' => array( 'Three', true ),
									'four' => array( 'Four', false ),
								),
							),
							'items_per_page' => array(
								'title' => esc_html__( 'Items per page', 'relish' ),
								'type' => 'number',
								'value' => '1',
							),
							'order_by' => array(
								'title' => esc_html__( 'Order By', 'relish' ),
								'type' => 'select',
								'source' => array(
									'desc' => array( 'Desc', true ),
									'asc' => array( 'ASC', false),
								),
							),
							'display' => array(
								'title' => esc_html__( 'Display as', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'grid' => array( 'Grid', true, 'd:slider[autoplay];d:slider[pagination];d:slider[navigation];e:dis_pagination' ),
									'grid_with_filter' => array( 'Grid With Filter', false, 'd:slider[autoplay];d:slider[pagination];d:slider[navigation];e:dis_pagination' ),
									'carousel' => array( 'Carousel', false, 'e:slider[autoplay];e:slider[pagination];e:slider[navigation];d:dis_pagination' )
								),
							),
							'slider[carousel]' => array(
								'title' => esc_html__( 'Carousel', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:slider[autoplay];e:slider[delay];e:slider[colums];e:slider[pagination];e:slider[navigation]"',
								'addrowclasses' => 'disable',

							),
							'slider[autoplay]' => array(
								'title' => esc_html__( 'Autoplay', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[pagination]' => array(
								'title' => esc_html__( 'Pagination', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'slider[navigation]' => array(
								'title' => esc_html__( 'Navigation', 'relish' ),
								'type' => 'checkbox',
								'addrowclasses' => 'disable',
							),
							'dis_pagination' => array(
								'title' => esc_html__( 'Use Pagination', 'relish' ),
								'type' => 'checkbox',
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
				),
			),
			'row' => array(
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'General', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'row_style' => array(
								'title' => esc_html__( 'Row type', 'relish' ),
								'type' => 'select',
								'source' => array(
									'def' => array('Default', true), // Title, isselected, data-options
									'fullwidth_item_no_padding' => array('Full-width content', false),
									'fullwidth_item' => array('Full-width content with paddings', false),
									'fullwidth_background' => array('Full-width background only', false)
								),
							),
							'content_position' => array(
								'title' => esc_html__( 'Vertical align', 'relish' ),
								'type' => 'select',
								'source' => array(
									'top' => array('Top', true),
									'middle' => array('Middle', false),
									'bottom' => array('Bottom', false)
								),
							),
						),
					),
					'tab1' => array(
						'title' => esc_html__( 'Spacings', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'margins' => array(
								'title' => esc_html__( 'Margins (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
							'paddings' => array(
								'title' => esc_html__( 'Paddings (px)', 'relish' ),
								'type' => 'input_group',
								'source' => array(
									'left' => array('number', 'Left'),
									'top' => array('number', 'Top'),
									'bottom' => array('number', 'Bottom'),
									'right' => array('number', 'Right'),
								),
							),
						),
					),
					'tab2' => array(
						'title' => esc_html__( 'Styling', 'relish' ),
						'type' => 'tab',
						'init' => 'closed',
						'layout' => array(
							'eclass' => array(
								'title' => esc_html__( 'Extra Class', 'relish' ),
								'description' => esc_html__( 'Space separated class names', 'relish' ),
								'type' => 'text',
							),
							'section_border' => array(
								'title' => esc_html__( 'Border:', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array( 'none', true, 'd:section_border_width;d:section_border_style;d:section_border_color;d:triangle_width'),
									'top-border' => array( 'top border', false, 'e:section_border_width;e:section_border_style;e:section_border_color;d:triangle_width;d:triangle_color' ),
									'bottom-border' => array( 'bottom border', false, 'e:section_border_width;e:section_border_style;e:section_border_color;d:triangle_color' ),
									'top-bottom' => array( 'top and bottom border', false, 'e:section_border_width;e:section_border_style;e:section_border_color;d:triangle_width;d:triangle_color' ),
									'triangle' => array( 'top and bottom triangle', false, 'd:section_border_width;d:section_border_style;d:section_border_color;e:triangle_width;e:triangle_color' ),
									'triangle_up' => array( 'top triangle', false, 'd:section_border_width;d:section_border_style;d:section_border_color;e:triangle_width;e:triangle_color' ),
									'triangle_down' => array( 'bottom triangle', false, 'd:section_border_width;d:section_border_style;d:section_border_color;e:triangle_width;e:triangle_color' ),
								),
							),
							'triangle_width' => array(
								'title' => esc_html__( 'Triangle width', 'relish' ),
								'type' => 'number',
								'atts' => ' min="1" step="1"',
								'value' => '35',
								'addrowclasses' => 'disable',
							),
							'triangle_color' => array(
								'title' => esc_html__( 'Triangle Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'disable',
							),
							'section_border_width' => array(
								'title' => esc_html__( 'Border width', 'relish' ),
								'type' => 'number',
								'atts' => ' min="1" step="1"',
								'value' => '1',
								'addrowclasses' => 'disable',
							),
							'section_border_style' => array(
								'title' => esc_html__( 'Border style:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'solid' => array( 'solid', true),
									'dashed' => array( 'dashed', false ),
									'dotted' => array( 'dotted', false )
								),
								'addrowclasses' => 'disable',
							),
							'section_border_color' => array(
								'title' => esc_html__( 'Border Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="#f2f2f2"',
								'value' => '#f2f2f2',
								'addrowclasses' => 'disable',
								),
							'customize_bg' => array(
								'title' => esc_html__( 'Customize', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:bg_media_type;e:bg_color_type;e:font_color"'
							),
							'bg_media_type' => array(
								'title' => esc_html__( 'Media type', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array( 'None', true, 'd:bg_img;d:is_bg_img_high_dpi;d:bg_video_type;d:bg_pattern;d:use_prlx;d:bg_possition;d:bg_repeat;d:bg_attach;' ),
									'img' => array( 'Image', false, 'e:bg_img;e:is_bg_img_high_dpi;e:bg_pattern;e:use_prlx;d:bg_video_type;e:bg_possition;e:bg_repeat;e:bg_attach;' ),
									),
								'addrowclasses' => 'disable',
								),
							'bg_img' => array(
								'title' => esc_html__( 'Image', 'relish' ),
								'type' => 'media',
								'atts' => 'data-role="media"',
								'addrowclasses' => 'disable',
								),
							'bg_attach' => array(
								'title' => esc_html__( 'Background Attachment:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'scroll' => array( 'Scroll', true),
									'fixed' => array( 'Fixed', false ),
									),
								'addrowclasses' => 'disable',
								),
							'bg_possition' => array(
								'title' => esc_html__( 'Background Position:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'left top' => array( 'left top', false),
									'left center' => array( 'left center', false ),
									'left bottom' => array( 'left bottom', false ),
									'right top' => array( 'right top', false ),
									'right center' => array( 'right center', false ),
									'right bottom' => array( 'right bottom', false ),
									'center top' => array( 'center top', false ),
									'center center' => array( 'center center', true ),
									'center bottom' => array( 'center bottom', false )
									),
								'addrowclasses' => 'disable',
								),
							'bg_repeat' => array(
								'title' => esc_html__( 'Background Repeat:', 'relish' ),
								'type' => 'select',
								'source' => array(
									'no-repeat' => array( 'No Repeat', false),
									'repeat' => array( 'Repeat', false ),
									'cover' => array( 'Cover', true ),
									'contain' => array( 'Contain', false )
									),
								'addrowclasses' => 'disable',
								),
							'bg_color_type' =>  array(
								'title' => esc_html__( 'Background Color', 'relish' ),
								'type' => 'select',
								'atts' => 'data-options="select:options"',
								'source' => array(
									'none' => array( 'None', true, 'd:bg_color;d:gradient_start_color;d:gradient_end_color;d:gradient_type;d:bg_color_opacity;' ),
									'color' => array( 'Color', false, 'e:bg_color;e:bg_color_opacity;d:gradient_start_color;d:gradient_end_color;d:gradient_type;' ),
									'gradient' => array( 'Gradient', false, 'e:gradient_start_color;e:gradient_end_color;e:gradient_type;e:bg_color_opacity;d:bg_color;' )
									),
								'addrowclasses' => 'disable',
								),
							'bg_color' => array(
								'title' => esc_html__( 'Color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable',
								),
							'gradient_start_color' => array(
								'title' => esc_html__( 'From', 'relish' ),
								'type' => 'text',
								'addrowclasses' => 'disable',
								'atts' => 'data-default-color="' . RELISH_COLOR . '"',
								'value' => RELISH_COLOR,
								'addrowclasses' => 'disable',
							),
							'gradient_end_color' => array(
								'title' => esc_html__( 'To', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'text',
								'atts' => 'data-default-color="#0eecbd"',
								'value' => '#0eecbd',
								'addrowclasses' => 'disable',
							),
							'gradient_type' => array(
								'title' => esc_html__( 'Type', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'radio',
								'value' => array(
									'linear' => array( 'Linear', true, 'e:gradient_linear_angle;d:gradient_radial_shape_settings_type;' ),
									'radial' => array( 'Radial', false, 'e:gradient_radial_shape_settings_type;d:gradient_linear_angle;' )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_linear_angle' => array(
								'title' => esc_html__( 'Angle', 'relish' ),
								'addrowclasses' => 'disable',
								'description' => esc_html__( 'Degrees: -360 to 360', 'relish' ),
								'type' => 'number',
								'atts' => ' min="-360" max="360" step="1"',
								'value' => '45',
								'addrowclasses' => 'disable',
							),
							'gradient_radial_shape_settings_type' => array(
								'addrowclasses' => 'disable',
								'title' => esc_html__( 'Shape variant', 'relish' ),
								'type' => 'radio',
								'value' => array(
									'simple' => array( 'Simple', true, 'e:gradient_radial_shape;d:gradient_radial_size_keyword;d:gradient_radial_size;' ),
									'extended' => array( 'Extended', false, 'e:gradient_radial_size_keyword;e:gradient_radial_size;d:gradient_radial_shape;' )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_shape' => array(
								'title' => esc_html__( 'Shape', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'radio',
								'value' => array(
									'ellipse' => array( 'Ellipse', true ),
									'circle' => array( 'Circle', false )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_size_keyword' => array(
								'title' => esc_html__( 'Size keyword', 'relish' ),
								'addrowclasses' => 'disable',
								'type' => 'select',
								'source' => array(
									'closest-side' => array( 'Closest side', false ),
									'farthest-side' => array( 'Farthest side', false ),
									'closest-corner' => array( 'Closest corner', false ),
									'farthest-corner' => array( 'Farthest corner', true )
								),
								'addrowclasses' => 'disable',
							),
							'gradient_radial_size' => array(
								'title' => esc_html__( 'Size', 'relish' ),
								'addrowclasses' => 'disable',
								'description' => esc_html__( 'Two space separated percent values, for example (60%% 55%%)', 'relish' ),
								'type' => 'text',
								'value' => '',
								'addrowclasses' => 'disable',
							),
							'use_prlx' => array(
								'title' => esc_html__( 'Apply Parallax', 'relish' ),
								'type' => 'checkbox',
								'atts' => 'data-options="e:prlx_speed"',
								'addrowclasses' => 'disable',
								),
							'prlx_speed' => array(
								'title' => esc_html__( 'Parallax speed', 'relish' ),
								'type' => 'number',
								'atts' => 'min="1" max="100" step="1"',
								'value' => '50',
								'addrowclasses' => 'disable',
							),
							'bg_color_opacity' => array(
								'title' => esc_html__( 'Opacity', 'relish' ),
								'description' => esc_html__( 'In percents', 'relish' ),
								'type' => 'number',
								'atts' => 'min="1" max="100" step="1"',
								'value' => '100',
								'addrowclasses' => 'disable',
							),
							'bg_pattern' => array(
								'title' => esc_html__( 'Pattern', 'relish' ),
								'type' => 'media',
								'addrowclasses' => 'disable',
							),
							'font_color' => array(
								'title' => esc_html__( 'Font color', 'relish' ),
								'type' => 'text',
								'atts' => 'data-default-color="' . $body_font_color . '"',
								'value' => $body_font_color,
								'addrowclasses' => 'disable',
							),
							'ani_module' => array(
								'type' => 'module',
								'name' => 'ani_add'
							),
						),
					),

				),
			),
			'grid' => array(
				'layout' => array(
					'tab0' => array(
						'title' => esc_html__( 'General', 'relish' ),
						'type' => 'tab',
						'layout' => array(
							'_cols' => array(
								'title' => esc_html__( 'Columns', 'relish' ),
						        'addrowclasses' => 'columns',
						        'type' => 'columns',
							),
						),
					),
				),
			),
			'msg_box' => array(
				'callback' => 'msg_box_sample',
				'title' => esc_html__( 'CWS Message Box', 'relish' ),
				'layout' => array(
					'type' => array(
						'title' => esc_html__( 'Type', 'relish' ),
						'type' => 'select',
						'source' => array(
							'info' => array(esc_html__( 'Informational', 'relish' ), true),
							'warning' => array(esc_html__( 'Warning', 'relish' ), false),
							'success' => array(esc_html__( 'Success', 'relish' ), false),
							'error' => array(esc_html__( 'Error', 'relish' ), false)
						),
					),
					'title' => array(
						'title' => esc_html__( 'Title', 'relish' ),
						'type' => 'text',
						'value' => 'INFORMATION BOX',
					),
					'text' => array(
						'title' => esc_html__( 'Text', 'relish' ),
						'type' => 'textarea',
						'value' => "Vestibulum sodales pellentesque nibh quis imperdiet",
					),
					'is_closable' => array(
						'title' => esc_html__( 'Add close button', 'relish' ),
						'type' => 'checkbox',
					),
					'customize' => array(
						'title' => esc_html__( 'Customize colors', 'relish' ),
						'type' => 'checkbox',
						'atts' => 'data-options="e:custom_options[color];e:custom_options[txt_color];e:custom_options[bg_color];e:custom_options[border_bg];e:custom_options[icon];e:custom_options[icon_bg];e:custom_options[icon_font_color]"',
					),
					'custom_options[color]' => array(
						'title' => esc_html__( 'Title & close icon color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="#ffffff"',
						'value' => '#ffffff',
						'addrowclasses' => 'disable',
					),
					'custom_options[txt_color]' => array(
						'title' => esc_html__( 'Text color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="#ffffff"',
						'value' => '#ffffff',
						'addrowclasses' => 'disable',
					),
					'custom_options[bg_color]' => array(
						'title' => esc_html__( 'Background color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
						'addrowclasses' => 'disable',
					),
					'custom_options[border_bg]' => array(
						'title' => esc_html__( 'Border Background', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="#ffffff"',
						'value' => '#ffffff',
						'addrowclasses' => 'disable',
					),
					'custom_options[icon]' => array(
						'title' => esc_html__( 'Icon', 'relish' ),
						'type' => 'select',
						'addrowclasses' => 'fai disable',
						'source' => 'fa',
					),
					'custom_options[icon_bg]' => array(
						'title' => esc_html__( 'Icon Background', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="#ffffff"',
						'value' => '#ffffff',
						'addrowclasses' => 'disable',
					),
					'custom_options[icon_font_color]' => array(
						'title' => esc_html__( 'Icon Color', 'relish' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.RELISH_COLOR.'"',
						'value' => RELISH_COLOR,
						'addrowclasses' => 'disable',
					),

				)
			),

		);/* END OFF */
	}
?>
