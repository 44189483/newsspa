<?php

function cwsfw_get_sections() {
	$settings = array(
		'general_setting' => array(
			'type' => 'section',
			'title' => esc_html__( 'Header', 'relish' ),
			'icon' => array('fa', 'header'),
			// 'active' => true // true by default
			'layout' => array(
				'logo_cont' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Logotype', 'relish' ),
					'layout' => array(
						'logo' => array(
							'title' => esc_html__( 'Logo', 'relish' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-12',
							'layout' => array(
								'logo_is_high_dpi' => array(
									'title' => esc_html__( 'High-Resolution sticky logo', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
								),
							),
						),
						'logo-dimensions' => array(
							'title' => esc_html__( 'Dimensions', 'relish' ),
							'type' => 'dimensions',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'width' => array('placeholder' => esc_html__( 'Width', 'relish' ), 'value' => ''),
								'height' => array('placeholder' => esc_html__( 'Height', 'relish' ), 'value' => '47px'),
								),
						),
						'logo-position' => array(
							'title' => esc_html__( 'Position', 'relish' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'left' => array( esc_html__( 'Left', 'relish' ), 	false, 'd:logo-position-inner;d:logo-margin-in-menu;e:logo-margin', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
								'center' =>array( esc_html__( 'Center', 'relish' ), false, 'd:logo-position-inner;d:logo-margin-in-menu;e:logo-margin', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
								'right' =>array( esc_html__( 'Right', 'relish' ), false, 'd:logo-position-inner;d:logo-margin-in-menu;e:logo-margin', get_template_directory_uri() . '/img/fw_img/align-right.png' ),
								'in-menu' =>array( esc_html__( 'Inside', 'relish' ), true, 'e:logo-position-inner;e:logo-margin-in-menu;d:logo-margin', get_template_directory_uri() . '/img/fw_img/align-inner.png' ),
							),
						),
						'logo-position-inner' => array(
							'type' => 'number',
							'addrowclasses' => 'grid-col-4',
							'title' => esc_html__( 'Inner Position', 'relish' ),
							'placeholder' => esc_html__( 'In percents', 'relish' ),
							'value' => '4'
						),
						'logo-margin' => array(
							'title' => esc_html__( 'Margin', 'relish' ),
							'type' => 'margins',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'margin-top' => array('placeholder' => esc_html__( 'Top', 'relish' ), 'value' => '12'),
								'margin-left' => array('placeholder' => esc_html__( 'left', 'relish' ), 'value' => '0'),
								'margin-right' => array('placeholder' => esc_html__( 'Right', 'relish' ), 'value' => '0'),
								'margin-bottom' => array('placeholder' => esc_html__( 'Bottom', 'relish' ), 'value' => '12'),
								),
						),
						'logo-margin-in-menu' => array(
							'title' => esc_html__( 'Margin', 'relish' ),
							'type' => 'margins',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'margin-top' => array('placeholder' => esc_html__( 'Top', 'relish' ), 'value' => '0'),
								'margin-left' => array('placeholder' => esc_html__( 'left', 'relish' ), 'value' => '48'),
								'margin-right' => array('placeholder' => esc_html__( 'Right', 'relish' ), 'value' => '48'),
								'margin-bottom' => array('placeholder' => esc_html__( 'Bottom', 'relish' ), 'value' => '0'),
								),
						),
						'logo_sticky' => array(
							'title' => esc_html__( 'Sticky logo', 'relish' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-6',
							'layout' => array(
								'logo_sticky_is_high_dpi' => array(
									'title' => esc_html__( 'High-Resolution sticky logo', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
								),
							),
						),

						'logo_mobile' => array(
							'title' => esc_html__( 'Mobile logo', 'relish' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-6',
							'layout' => array(
								'logo_mobile_is_high_dpi' => array(
									'title' => esc_html__( 'High-Resolution logo', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
								),
							),
						),

					)
				),
				'menu_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Menu', 'relish' ),
					'layout' => array(
						'menu-position' => array(
							'title' => esc_html__( 'Position', 'relish' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'left' => array( esc_html__( 'Left', 'relish' ), 	true, '', get_template_directory_uri() . '/img/fw_img/align-left.png' ),
								'center' =>array( esc_html__( 'Center', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-center.png' ),
								'right' =>array( esc_html__( 'Right', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/align-right.png' ),
							),
						),
						'header_margin' => array(
							'title' => esc_html__( 'Header margin', 'relish' ),
							'type' => 'margins',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'margin-top' => array('placeholder' => esc_html__( 'Top', 'relish' ), 'value' => '13'),
								'margin-bottom' => array('placeholder' => esc_html__( 'Bottom', 'relish' ), 'value' => '13'),
							),
						),
						'show_header_outside_slider' => array(
							'title' => esc_html__( 'Header hovers slider', 'relish' ),
							'addrowclasses' => 'checkbox grid-col-4 alt',
							'type' => 'checkbox',
							'atts' => 'data-options="e:header_outside_slider_bg_opacity;e:header_outside_slider_font_color"',
						),
						'show_sandwich_menu' => array(
							'title' => esc_html__( 'Show Sandwich Menu', 'relish' ),
							'addrowclasses' => 'checkbox grid-col-4 alt',
							'type' => 'checkbox',
						),
						'header_outside_slider_bg_opacity' => array(
							'type' => 'number',
							'title' => esc_html__( 'Opacity', 'relish' ),
							'placeholder' => esc_html__( 'In percents', 'relish' ),
							'addrowclasses' => 'disable grid-col-4',
							'value' => '30',

						),
						'header_outside_slider_font_color' => array(
							'title' => esc_html__( 'Header Font color', 'relish' ),
							'atts' => 'data-default-color="#595959"',
							'addrowclasses' => 'disable grid-col-4',
							'type' => 'text',
						),
						'menu-stick' => array(
							'title' => esc_html__( 'Apply sticky menu', 'relish' ),
							'addrowclasses' => 'checkbox grid-col-4 alt',
							'atts' => 'data-options="e:stick-mode"',
							'type' => 'checkbox',
						),
						'stick-mode'	=> array(
							'title'		=> esc_html__( 'Sticky menu mode', 'relish' ),
							'type'	=> 'select',
							'addrowclasses' => 'disable grid-col-12',
							'source'	=> array(
								'smart' => array( esc_html__( 'Smart', 'relish' ),  true ),
								'simple' => array( esc_html__( 'Simple', 'relish' ), false ),
							),
						),



						'enable_mob_menu' => array(
							'title' => esc_html__( 'Enable mobile menu', 'relish' ),
							'addrowclasses' => 'checkbox grid-col-4 alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),

					)
				),
				'top_bar_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Top Panel', 'relish' ),
					'layout' => array(
						'top_panel_switcher' => array(
							'title' => esc_html__( 'Switch On/Off the Top Panel', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked data-options="e:top_panel_text"',
							'type' => 'checkbox',
						),
						'top_panel_text' => array(
							'title' => esc_html__( 'Content', 'relish' ),
							'type' => 'textarea',
							'atts' => 'rows="6"',
						),
						'toggle-share-icon' => array(
							'title' => esc_html__( 'Toogle Social Icons', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'search_place' => array(
							'title' => esc_html__( 'Search icon position', 'relish' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-4',
							'value' => array(
								'none' => array( esc_html__( 'None', 'relish' ), 	false, '', get_template_directory_uri() . '/img/fw_img/no_layout.png' ),
								'left' =>array( esc_html__( 'Left', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/search-menu-left.png' ),
								'right' =>array( esc_html__( 'Right', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/search-menu-right.png' ),
							),
						),
						'theme_top_bar_color'	=> array(
							'title'	=> esc_html__( 'Top Panel Background Color', 'relish' ),
							'atts'	=> 'data-default-color="#f0f7f2"',
							'addrowclasses' => 'grid-col-4',
							'type'	=> 'text'
						),
						'theme_top_bar_font_color' => array(
							'title' => esc_html__( 'Top Panel Font color', 'relish' ),
							'atts' => 'data-default-color="#777777"',
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),

					)
				),
				'styling_options_headers' => array(
					'type' => 'tab',					
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Header', 'relish' ),
					'layout' => array(
						'customize_headers' => array(
							'title' => esc_html__( 'Customize headers', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'type' => 'checkbox',
							'atts' => 'data-options="e:header_bg_image;e:bg_header_color_overlay_type;"',
						),
						'header_bg_image' => array(
							'title' => esc_html__( 'Header Image', 'relish' ),
							'type' => 'media',
						),
						'bg_header_color_overlay_type'	=> array(
							'title'		=> esc_html__( 'Color overlay type', 'relish' ),
							'type'	=> 'select',
							'source'	=> array(
								'color' => array( esc_html__( 'Color', 'relish' ),  true, 'e:bg_header_overlay_color;d:bg_header_settings;' ),
								'gradient' => array( esc_html__( 'Gradient', 'relish' ), false, 'd:bg_header_overlay_color;e:bg_header_settings;' )
							),
						),
						'bg_header_overlay_color'	=> array(
							'title'	=> esc_html__( 'Overlay color', 'relish' ),
							'atts' => 'data-default-color="' . RELISH_COLOR . '"',
							'type'	=> 'text',
						),
						'bg_header_settings' => array(
							'title' => esc_html__( 'Gradient Settings', 'relish' ),
							'type' => 'fields',
							'addrowclasses' => 'disable groups',
							'layout' => array(
								'first_color' => array(
									'type' => 'text',
									'title' => esc_html__( 'From', 'relish' ),
									'atts' => 'data-default-color=""',
								),
								'second_color' => array(
									'type' => 'text',
									'title' => esc_html__( 'To', 'relish' ),
									'atts' => 'data-default-color=""',
								),
								'type' => array(
									'title' => esc_html__( 'Gradient type', 'relish' ),
									'type' => 'radio',
									'value' => array(
										'linear' => array( esc_html__( 'Linear', 'relish' ),  true, 'e:linear_settings;d:radial_settings' ),
										'radial' =>array( esc_html__( 'Radial', 'relish' ), false,  'd:linear_settings;e:radial_settings' ),
									),
								),
								'linear_settings' => array(
									'title' => esc_html__( 'Linear settings', 'relish'  ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'angle' => array(
											'type' => 'number',
											'title' => esc_html__( 'Angle', 'relish' ),
											'value' => '45',
										),
									)
								),
								'radial_settings' => array(
									'title' => esc_html__( 'Radial settings', 'relish'  ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'shape_settings' => array(
											'title' => esc_html__( 'Shape', 'relish' ),
											'type' => 'radio',
											/*'addrowclasses' => 'disable',*/
											'value' => array(
												'simple' => array( esc_html__( 'Simple', 'relish' ),  true, 'e:shape;d:size;d:size_keyword;' ),
												'extended' =>array( esc_html__( 'Extended', 'relish' ), false, 'd:shape;e:size;e:size_keyword;' ),
											),
										),
										'shape' => array(
											'title' => esc_html__( 'Gradient type', 'relish' ),
											'type' => 'radio',
											/*'addrowclasses' => 'disable',*/
											'value' => array(
												'ellipse' => array( esc_html__( 'Ellipse', 'relish' ),  true ),
												'circle' =>array( esc_html__( 'Circle', 'relish' ), false ),
											),
										),
										'size_keyword' => array(
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
										'size' => array(
											'type' => 'text',
											'addrowclasses' => 'disable',
											'title' => esc_html__( 'Size', 'relish' ),
											'atts' => 'placeholder="'.esc_html__( 'Two space separated percent values, for example (60% 55%)', 'relish' ).'"',
										),
									)
								)
							)
						),
						'bg_header_color_overlay_opacity' => array(
							'type' => 'number',
							'title' => esc_html__( 'Opacity', 'relish' ),
							'placeholder' => esc_html__( 'In percents', 'relish' ),
							'value' => '40'
						),
						'bg_header_use_pattern' => array(
							'title' => esc_html__( 'Add pattern', 'relish' ),
							'addrowclasses' => 'checkbox',
							'type' => 'checkbox',
							'atts' => 'data-options="e:bg_header_pattern_image"',
						),
						'bg_header_pattern_image' => array(
							'title' => esc_html__( 'Pattern image', 'relish' ),
							'type' => 'media',
							'addrowclasses' => 'disable',
							'url-atts' => 'readonly',

						),
						'parallaxify' => array(
							'title' => esc_html__( 'Parallaxify image', 'relish' ),
							'addrowclasses' => 'checkbox',
							'type' => 'checkbox',
							'atts' => 'data-options="e:parallax_options;"',
						),
						'parallax_options' => array(
							'title' => esc_html__( 'Parallax options', 'relish' ),
							'type' => 'fields',
							'addrowclasses' => 'groups',
							'layout' => array(
								'scalar_x' => array(
									'type' => 'number',
									'title' => esc_html__( 'x-axis parallax intensity', 'relish' ),
									'placeholder' => esc_html__( 'Integer', 'relish' ),
									'value' => '2',
								),
								'scalar_y' => array(
									'type' => 'number',
									'title' => esc_html__( 'y-axis parallax intensity', 'relish' ),
									'placeholder' => esc_html__( 'Integer', 'relish' ),
									'value' => '2'
								),
								'limit_x' => array(
									'type' => 'number',
									'title' => esc_html__( 'Maximum x-axis shift', 'relish' ),
									'placeholder' => esc_html__( 'Integer', 'relish' ),
									'value' => '15'
								),
								'limit_y' => array(
									'type' => 'number',
									'title' => esc_html__( 'Maximum y-axis shift', 'relish' ),
									'placeholder' => esc_html__( 'Integer', 'relish' ),
									'value' => '15'
								),
							),
						),
						'font_color' => array(
							'title'	=> esc_html__( 'Font Color', 'relish' ),
							'atts' => 'data-default-color="#ffffff"',
							'type'	=> 'text',
						),
						'spacings' => array(
							'title' => esc_html__( 'Spacings', 'relish' ),
							'type' => 'margins',
							'value' => array(
								'top' => array('placeholder' => esc_html__( 'Top', 'relish' ), 'value' => '12'),
								'bottom' => array('placeholder' => esc_html__( 'Bottom', 'relish' ), 'value' => '12'),
							),
						),
					)
				),	


			)
		),	// end of sections
		'styling_options' => array(
			'type' => 'section',
			'title' => esc_html__('Styling Option', 'relish' ),
			'icon' => array('fa', 'paint-brush'),
			//'active' => true // true by default
			'layout' => array(
				'styling_options_color' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Colors', 'relish' ),
					'layout' => array(
						'theme-custom-color' => array(
							'title' => esc_html__( 'Theme color', 'relish' ),
							'atts' => 'data-default-color="' . RELISH_COLOR . '"',
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),
					)
				),
				'styling_options_costomizations' => array(
					'type' => 'tab',
					/*'init' => 'open',*/
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Layout', 'relish' ),
					'layout' => array(
						'boxed-layout' => array(
							'title' => esc_html__( 'Boxed Layout', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'type' => 'checkbox',
							'atts' => 'data-options="e:url_background;"',
						),
						'url_background' => array(
					       'title' => esc_html__( 'Background Settings', 'relish' ),
					       'type' => 'info',
					       'addrowclasses' => 'disable',
					       'icon' => array('fa', 'calendar-plus-o'),
					       'value' => '<a href="themes.php?page=custom-background" target="_blank">'.esc_html__('Click this link to customize your background settings','relish').'</a>',
					    ),
					)
				)
			)
		),	// end of sections
		'footer_options' => array(
			'type' => 'section',
			'title' => esc_html__('Footer', 'relish' ),
			'icon' => array('fa', 'list-alt'),
			//'active' => true // true by default
			'layout' => array(
				'styling_options_color' => array(
					'type' => 'tab',
					'init' => 'open',	
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Footer', 'relish' ),
					'layout' => array(
						'add_footer_bg_img' => array(
							'title' => esc_html__( 'Add Footer Image', 'relish' ),
							'addrowclasses' => 'checkbox  grid-col-12',
							'type' => 'checkbox',
							'atts' => 'data-options="e:footer_img_settings;"',
						),
						'footer_img_settings' => array(
							'title' => esc_html__( 'Footer Image Settings', 'relish' ),
							'type' => 'fields',
							'addrowclasses' => 'disable grid-col-12',
							'layout' => array(
								'footer_bg_im' => array(
									'title' => esc_html__( 'Footer Bg Image', 'relish' ),
									'type' => 'media',
									'url-atts' => 'readonly',
							),
						'footer_img_pos_x'	=> array(
							'title'		=> esc_html__( 'Footer Img Position x', 'relish' ),
							'type'	=> 'select',
								'source'	=> array(
									'left' => array( esc_html__( 'left', 'relish' ),  true ),
									'right' => array( esc_html__( 'right', 'relish' ), false ),
									'center' => array( esc_html__( 'center', 'relish' ), false ),
								),
						),
						'footer_img_pos_y'	=> array(
									'title'		=> esc_html__( 'Footer Img Position y', 'relish' ),
									'type'	=> 'select',
									'source'	=> array(
										'top' => array( esc_html__( 'top', 'relish' ),  true ),
										'center' => array( esc_html__( 'center', 'relish' ), false ),
										'bottom' => array( esc_html__( 'bottom', 'relish' ), false ),
									),
								),
						'footer_img_repeat'	=> array(
									'title'		=> esc_html__( 'Footer Img Repeat', 'relish' ),
									'type'	=> 'select',
									'source'	=> array(
										'no-repeat' => array( esc_html__('no repeat', 'relish' ), true ),
										'repeat' => array( esc_html__('repeat', 'relish' ), false ),
										'repeat-x' => array( esc_html__('repeat x', 'relish' ), false ),
										'repeat-y' => array( esc_html__('repeat y', 'relish' ), false ),
									),
								),
						'footer_img_size'	=> array(
									'title'		=> esc_html__( 'Footer Img Size', 'relish' ),
									'type'	=> 'select',
									'addrowclasses' => 'grid-col-12',
									'source'	=> array(
										'auto' => array( esc_html__('auto', 'relish' ),  true ),
										'cover' => array( esc_html__('cover', 'relish' ), false ),
										'container' => array( esc_html__('container', 'relish' ), false ),
									),
								),
						'footer_img_attachment'	=> array(
									'title'		=> esc_html__( 'Footer Img Attachment', 'relish' ),
									'type'	=> 'select',
									'source'	=> array(
										'fixed' => array( esc_html__('fixed', 'relish' ), false ),
										'scroll' => array( esc_html__('scroll', 'relish' ),  true ),
										'local' => array( esc_html__('local', 'relish' ), false )
									),
								),
							),
						),
						'footer_pattern' => array(
							'title' => esc_html__( 'Footer Pattern', 'relish' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-12',
						),
						'theme-footer-color'	=> array(
							'title'	=> esc_html__( 'Footer Background Color', 'relish' ),
							'atts'	=> 'data-default-color="'. RELISH_FOOTER_COLOR .'"',
							'addrowclasses' => 'grid-col-4',
							'value' => RELISH_FOOTER_COLOR,
							'type'	=> 'text'
						),
						'theme-footer-font-color' => array(
							'title' => esc_html__( 'Footer font color', 'relish' ),
							'atts' => 'data-default-color="#b0b0b0"',
							'value' => "#ffffff",
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),
						'theme_footer_copy_color'	=> array(
							'title'	=> esc_html__( 'Footer (Copyrights) Background Color', 'relish' ),
							'atts'	=> 'data-default-color="#404b43"',
							'addrowclasses' => 'grid-col-4',
							'type'	=> 'text'
						),
						'theme_footer_copy_font_color' => array(
							'title' => esc_html__( 'Footer (Copyrights) Font Color', 'relish' ),
							'atts' => 'data-default-color="#e1e1e1"',
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),
					)
				),
				'layout_options_footer' => array(
					'type' => 'tab',
					'customizer' => array('show' => false),
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Footer Sidebar', 'relish' ),
					'layout' => array(
						'footer-sidebar-top' => array(
							'title' 		=> esc_html__('Footer sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'grid-col-6',
							'source' 		=> 'sidebars',
							'value'			=> 'Footer'
						),
						'footer_layouts_columns'	=> array(
							'title'		=> esc_html__( 'Footer Layout', 'relish' ),
							'type'	=> 'select',
							'addrowclasses' => 'grid-col-6',

							'source'	=> array(
								'one' => array( esc_html__( 'One', 'relish' ),  false ),
								'two' => array( esc_html__( 'Two', 'relish' ), false ),
								'three' => array( esc_html__( 'Three', 'relish' ), true ),
								'four' => array( esc_html__( 'Four', 'relish' ), false ),
							),
						),
						'footer_copyrights_text' => array(
							'title' => esc_html__( 'Footer Copyrights content', 'relish' ),
							'addrowclasses' => 'grid-col-12',
							'type' => 'textarea',
							'atts' => 'rows="6"',
							'value' => 'Copyright &#169; 2017 relish.com. All rights reserved.'
						),
					)
				),
			)
		),	// end of sections
		'layout_options' => array(
			'type' => 'section',
			'title' => esc_html__('Page Layouts', 'relish' ),
			'icon' => array('fa', 'columns'),
			//'active' => true // true by default
			'layout'	=> array(
				'homepage_options'	=> array(
					'type' => 'tab',
					'init'	=> 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Home', 'relish' ),
					'layout' => array(
						'home-slider-type' => array(
							'title' => esc_html__('Slider', 'relish' ),
							'type' => 'radio',
							'value' => array(
								'none' => 	array( esc_html__('None', 'relish' ), true, 'd:home-header-slider-options;d:slidersection-start;d:static_img_section' ),
								'img-slider'=>	array( esc_html__('Image Slider', 'relish' ), false, 'e:home-header-slider-options;d:slidersection-start;d:static_img_section' ),
								'video-slider' => 	array( esc_html__('Video Slider', 'relish' ), false, 'd:home-header-slider-options;e:slidersection-start;d:static_img_section' ),
								'stat-img-slider' => 	array( esc_html__('Static image', 'relish' ), false, 'd:home-header-slider-options;d:slidersection-start;e:static_img_section' ),
							),
						),
						'home-header-slider-options' => array(
							'title' => esc_html__( 'Slider shortcode', 'relish' ),
							'addrowclasses' => 'disable',
							'type' => 'text',
							'value' => '[rev_slider homepage]',
						),
						'slidersection-start' => array(
							'title' => esc_html__( 'Video Slider Setting', 'relish' ),
							'type' => 'fields',
							'addrowclasses' => 'disable groups',
							'layout' => array(
								'slider_switch' => array(
									'type' => 'checkbox',
									'title' => esc_html__( 'Slider', 'relish' ),
									'atts' => 'data-options="e:slider_shortcode;d:set_video_header_height"',
								),
								'slider_shortcode' => array(
									'title' => esc_html__( 'Slider shortcode', 'relish' ),
									'addrowclasses' => 'disable',
									'type' => 'text',
								),
								'set_video_header_height' => array(
									'type' => 'checkbox',
									'title' => esc_html__( 'Set Video height', 'relish' ),
									'atts' => 'data-options="e:video_header_height"',
								),
								'video_header_height' => array(
									'title' => esc_html__( 'Video height', 'relish' ),
									'addrowclasses' => 'disable',
									'type' => 'number',
									'value' => '600',
								),
								'video_type' => array(
									'title' => esc_html__('Video type', 'relish' ),
									'type' => 'radio',
									'value' => array(
										'self_hosted' => 	array( esc_html__('Self-hosted', 'relish' ), true, 'e:sh_source;d:youtube_source;d:vimeo_source' ),
										'youtube'=>	array( esc_html__('Youtube clip', 'relish' ), false, 'd:sh_source;e:youtube_source;d:vimeo_source' ),
										'vimeo' => 	array( esc_html__('Vimeo clip', 'relish' ), false, 'd:sh_source;d:youtube_source;e:vimeo_source' ),
									),
								),
								'sh_source' => array(
									'title' => esc_html__( 'Add video', 'relish' ),
									'type' => 'media',
								),
								'youtube_source' => array(
									'title' => esc_html__( 'Youtube video code', 'relish' ),
									'addrowclasses' => 'disable',
									'type' => 'text',
								),
								'vimeo_source' => array(
									'title' => esc_html__( 'Vimeo embed url', 'relish' ),
									'addrowclasses' => 'disable',
									'type' => 'text',
								),
								'color_overlay_type' => array(
									'title' => esc_html__( 'Overlay type', 'relish' ),
									'type' => 'select',
									'source' => array(
										'none' => array( esc_html__( 'None', 'relish' ), 	true, 'd:overlay_color;d:slider_gradient_settings;d:color_overlay_opacity;'),
										'color' => array( esc_html__( 'Color', 'relish' ), 	false, 'e:overlay_color;d:slider_gradient_settings;e:color_overlay_opacity;'),
										'gradient' =>array( esc_html__( 'Gradient', 'relish' ), false, 'd:overlay_color;e:slider_gradient_settings;e:color_overlay_opacity;'),
									),
								),
								'overlay_color' => array(
									'title' => esc_html__( 'Overlay Color', 'relish' ),
									'atts' => 'data-default-color=""',
									'type' => 'text',
								),
								'color_overlay_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity', 'relish' ),
									'placeholder' => esc_html__( 'In percents', 'relish' ),
									'value' => '40'
								),
								'slider_gradient_settings' => array(
									'title' => esc_html__( 'Gradient settings', 'relish' ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'From', 'relish' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'To', 'relish' ),
											'atts' => 'data-default-color=""',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'relish' ),
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'relish' ),  true, 'e:linear_settings;d:radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'relish' ), false,  'd:linear_settings;e:radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'relish'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'relish' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'relish'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'relish' ),
													'type' => 'radio',
													/*'addrowclasses' => 'disable',*/
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'relish' ),  true, 'e:shape;d:size;d:size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'relish' ), false, 'd:shape;e:size;e:size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'relish' ),
													'type' => 'radio',
													/*'addrowclasses' => 'disable',*/
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'relish' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'relish' ), false ),
													),
												),
												'size_keyword' => array(
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
												'size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'relish' ),
													'atts' => 'placeholder="'.esc_html__( 'Two space separated percent values, for example (60% 55%)', 'relish' ).'"',
												),
											)
										)
										
									),
								),
								'use_pattern' => array(
									'type' => 'checkbox',
									'title' => esc_html__( 'Use pattern image', 'relish' ),
									'atts' => 'data-options="e:pattern_image"',
								),
								'pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'relish' ),
									/*'addrowclasses' => 'disable',*/
									'type' => 'media',
								),
							),
						),// end of video-section
						'static_img_section' => array(
							'title' => esc_html__( 'Static image Slider Setting', 'relish' ),
							'type' => 'fields',
							'addrowclasses' => 'groups',
							/*'addrowclasses' => 'disable',*/
							'layout' => array(
								'home-header-image-options' => array(
									'title' => esc_html__( 'Static image', 'relish' ),
									// 'addrowclasses' => 'disable',
									'type' => 'media',
									'url-atts' => 'readonly',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'Is it hi-dpi?', 'relish' ),
											'type' => 'checkbox',
											'addrowclasses' => 'checkbox',
										),
									),
								),
								'set_static_image_height' => array(
									'type' => 'checkbox',
									'title' => esc_html__( 'Set Image height', 'relish' ),
									'atts' => 'data-options="e:static_image_height"',
								),
								'static_image_height' => array(
									'title' => esc_html__( 'Static Image Height', 'relish' ),
									'addrowclasses' => 'disable',
									'type' => 'number',
									'default' => '600',
								),

								/*addd*/
								'img_header_color_overlay_type'	=> array(
									'title'		=> esc_html__( 'Color overlay type', 'relish' ),
									'type'	=> 'select',
									'source'	=> array(
										'color' => array( esc_html__( 'Color', 'relish' ),  true, 'e:img_header_overlay_color;d:img_header_gradient_settings;' ),
										'gradient' => array( esc_html__( 'Gradient', 'relish' ), false, 'd:img_header_overlay_color;e:img_header_gradient_settings;' )
									),
								),
								'img_header_overlay_color'	=> array(
									'title'	=> esc_html__( 'Overlay color', 'relish' ),
									'atts' => 'data-default-color="' . RELISH_COLOR . '"',
									'type'	=> 'text'
								),
								'img_header_gradient_settings' => array(
									'title' => esc_html__( 'Gradient Settings', 'relish' ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'From', 'relish' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'To', 'relish' ),
											'atts' => 'data-default-color=""',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'relish' ),
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'relish' ),  true, 'e:img_header_gradient_linear_settings;d:img_header_gradient_radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'relish' ), false,  'd:img_header_gradient_linear_settings;e:img_header_gradient_radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'relish'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'relish' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'relish'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'relish' ),
													'type' => 'radio',
													/*'addrowclasses' => 'disable',*/
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'relish' ),  true, 'e:img_header_gradient_shape;d:img_header_gradient_size;d:img_header_gradient_size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'relish' ), false, 'd:img_header_gradient_shape;e:img_header_gradient_size;e:img_header_gradient_size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'relish' ),
													'type' => 'radio',
													/*'addrowclasses' => 'disable',*/
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'relish' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'relish' ), false ),
													),
												),
												'img_header_gradient_size_keyword' => array(
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
												'img_header_gradient_size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'relish' ),
													'atts' => 'placeholder="'.esc_html__( 'Two space separated percent values, for example (60% 55%)', 'relish' ).'"',
												),
											)
										)
									)
								),
								'img_header_color_overlay_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity', 'relish' ),
									'placeholder' => esc_html__( 'In percents', 'relish' ),
									'value' => '40'
								),
								'img_header_use_pattern' => array(
									'title' => esc_html__( 'Add pattern', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_pattern_image;"',
								),
								'img_header_pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'relish' ),
									'type' => 'media',
									'url-atts' => 'readonly',
								),
								/*'bg_header_use_blur' => array(
									'title' => esc_html__( 'Apply blur', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:bg_header_blur_intensity;"',
								),
								'bg_header_blur_intensity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Intensity', 'relish' ),
									'placeholder' => esc_html__( 'In percents', 'relish' ),
									'value' => '8'
								),*/
								'img_header_parallaxify' => array(
									'title' => esc_html__( 'Parallaxify image', 'relish' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_parallax_options;"',
								),
								'img_header_parallax_options' => array(
									'title' => esc_html__( 'Parallax options', 'relish' ),
									'type' => 'fields',
									'layout' => array(
										'img_header_scalar_x' => array(
											'type' => 'number',
											'title' => esc_html__( 'x-axis parallax intensity', 'relish' ),
											'placeholder' => esc_html__( 'Integer', 'relish' ),
											'value' => '2'
										),
										'img_header_scalar_y' => array(
											'type' => 'number',
											'title' => esc_html__( 'y-axis parallax intensity', 'relish' ),
											'placeholder' => esc_html__( 'Integer', 'relish' ),
											'value' => '2'
										),
										'img_header_limit_x' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum x-axis shift', 'relish' ),
											'placeholder' => esc_html__( 'Integer', 'relish' ),
											'value' => '15'
										),
										'limg_header_limit_y' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum y-axis shift', 'relish' ),
											'placeholder' => esc_html__( 'Integer', 'relish' ),
											'value' => '15'
										),
									),
								),
							),
						),// end of static img slider-section
						'def-home-layout' => array(
							'title' 			=> esc_html__('Home Page Sidebar Layout', 'relish' ),
							'type' 				=> 'radio',
							'subtype' 			=> 'images',
							'value' 			=> array(
								'left' 				=> 	array( esc_html__('Left', 'relish' ), false, 'e:def-home-sidebar1;d:def-home-sidebar2;',	get_template_directory_uri() . '/core/images/left.png' ),
								'right' 			=> 	array( esc_html__('Right', 'relish' ), false, 'e:def-home-sidebar1;d:def-home-sidebar2;', get_template_directory_uri() . '/core/images/right.png' ),
								'both' 				=> 	array( esc_html__('Both', 'relish' ), false, 'e:def-home-sidebar1;e:def-home-sidebar2;', get_template_directory_uri() . '/core/images/both.png' ),
								'none' 				=> 	array( esc_html__('None', 'relish' ), true, 'd:def-home-sidebar1;d:def-home-sidebar2;', get_template_directory_uri() . '/core/images/none.png' )
							),
						),
						'def-home-sidebar1' => array(
							'title' 		=> esc_html__('Sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
						'def-home-sidebar2' => array(
							'title' 		=> esc_html__('Right sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
					)
				),
				'layout_page' => array(
					'type' => 'tab',
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Page', 'relish' ),
					'layout' => array(
						'def-page-layout' => array(
							'title' 			=> esc_html__('Page Sidebar Layout', 'relish' ),
							'type' 				=> 'radio',
							'subtype' 			=> 'images',
							'value' 			=> array(
								'left' 				=> 	array( esc_html__('Left', 'relish' ), false, 'e:def-page-sidebar1;d:def-page-sidebar2;',	get_template_directory_uri() . '/core/images/left.png' ),
								'right' 			=> 	array( esc_html__('Right', 'relish' ), true, 'e:def-page-sidebar1;d:def-page-sidebar2;', get_template_directory_uri() . '/core/images/right.png' ),
								'both' 				=> 	array( esc_html__('Both', 'relish' ), false, 'e:def-page-sidebar1;e:def-page-sidebar2;', get_template_directory_uri() . '/core/images/both.png' ),
								'none' 				=> 	array( esc_html__('None', 'relish' ), false, 'd:def-page-sidebar1;d:def-page-sidebar2;', get_template_directory_uri() . '/core/images/none.png' )
							),
						),
						'spacings_blog' => array(
							'title' => esc_html__( 'Page Spacings', 'relish' ),
							'type' => 'margins',
							'value' => array(
								'top' => array('placeholder' => esc_html__( 'Top', 'relish' ), 'value' => '90'),
								'bottom' => array('placeholder' => esc_html__( 'Bottom', 'relish' ), 'value' => '40'),
							),
						),
						
					)
				),
				'layout_options_page' => array(
					'type' => 'tab',
					'icon' => array( 'fa', 'fa-book' ),
					'title' => esc_html__( 'Blog', 'relish' ),
					'layout' => array(
						'def-page-sidebar1' => array(
							'title' 		=> esc_html__('Sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
						'def-page-sidebar2' => array(
							'title' 		=> esc_html__('Right sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
						'def-blog-layout' => array(
							'title' 			=> esc_html__('Blog Sidebar Layout', 'relish' ),
							'type' 				=> 'radio',
							'subtype' 			=> 'images',
							'value' 			=> array(
								'left' 				=> 	array( esc_html__('Left', 'relish' ), false, 'e:def-blog-sidebar1;d:def-blog-sidebar2;',	get_template_directory_uri() . '/core/images/left.png' ),
								'right' 			=> 	array( esc_html__('Right', 'relish' ), false, 'e:def-blog-sidebar1;d:def-blog-sidebar2;', get_template_directory_uri() . '/core/images/right.png' ),
								'both' 				=> 	array( esc_html__('Both', 'relish' ), false, 'e:def-blog-sidebar1;e:def-blog-sidebar2;', get_template_directory_uri() . '/core/images/both.png' ),
								'none' 				=> 	array( esc_html__('None', 'relish' ), true, 'd:def-blog-sidebar1;d:def-blog-sidebar2;', get_template_directory_uri() . '/core/images/none.png' )
							),
						),
						'def-blog-sidebar1' => array(
							'title' 		=> esc_html__('Sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
						'def-blog-sidebar2' => array(
							'title' 		=> esc_html__('Right sidebar', 'relish' ),
							'type' 			=> 'select',
							'addrowclasses' => 'disable',
							'source' 		=> 'sidebars',
						),
						'def_blogtype' => array(
							'title'		=> esc_html__( 'Blog Layout', 'relish' ),
							'desc'		=> esc_html__( 'Default Blog Layout', 'relish' ),
							'type'		=> 'radio',
							'subtype' => 'images',
							'value' => array(
								'1' => array( esc_html__('Large', 'relish' ), true, '', get_template_directory_uri() . '/img/fw_img/large.png'),
								'medium' => array( esc_html__('Medium', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/medium.png'),
								'small' => array( esc_html__('Small', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/small.png'),
								'2' => array( esc_html__('Two', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/pinterest_2_columns.png'),
								'3' => array( esc_html__('Three', 'relish' ), false, '', get_template_directory_uri() . '/img/fw_img/pinterest_3_columns.png'),
							),
						),
					)
				),
				'layout_options_sidebar_generator' => array(
					'type' => 'tab',
					'customizer' => array('show' => false),
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Sidebars', 'relish' ),
					'layout' => array(
						'sidebars' => array(
							'type' => 'group',
							'addrowclasses' => 'group',
							'title' => esc_html__('Sidebar generator', 'relish' ),
							'button_title' => esc_html__('Add new sidebar', 'relish' ),
							'layout' => array(
								'title' => array(
									'type' => 'text',
									'atts' => 'data-role="title"',
									'title' => esc_html__('Sidebar', 'relish' ),
								)
							),
							'value' => array(
						        array('title' => 'Footer',),
						        array('title' => 'Blog Right',),
						        array('title' => 'Page Right',),
						    )
						),
						'sticky_sidebars' => array(
       						'title' => esc_html__( 'Sticky sidebars', 'relish' ),
       						'addrowclasses' => 'checkbox alt',
      						'atts' => 'checked',
       						'type' => 'checkbox',
      					),
					)
				),
			)
		),	// end of sections
		'typography_options' => array(
			'type' => 'section',
			'title' => esc_html__('Typography', 'relish' ),
			'icon' => array('fa', 'font'),
			'layout' => array(
				'menu_font' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Menu', 'relish' ),
					'layout' => array(
						'menu-font' => array(
							'title' => esc_html__('Menu Font', 'relish' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '17px',
								'line-height' => '45px',
								'color' => '#777777',
								'font-family' => "Work Sans",
								'font-weight' => array( '400' ),
								'font-sub' => array('latin'),
							)
						)
					)
				),
				'header_font' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Header', 'relish' ),
					'layout' => array(
						'header-font' => array(
							'title' => esc_html__('Header\'s Font', 'relish' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '48px',
								'line-height' => '35px',
								'color' => '#76c08a',
								'font-family' => 'Philosopher',
								'font-weight' => array( '400', '300', '700' ),
								'font-sub' => array('latin'),
							),
						)
					)
				),
				'body_font_options' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Body', 'relish' ),
					'layout' => array(
						'body-font' => array(
							'title' => esc_html__('Body Font', 'relish' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '16px',
								'line-height' => '25px',
								'color' => '#777777',
								'font-family' => 'Work Sans',
								'font-weight' => array('300' ),
								'font-sub' => array('latin'),
							)
						)
					)
				)
			)
		), // end of sections
		'help_options' => array(
			'type' => 'section',
			'title' => esc_html__('Help & Maintenance', 'relish' ),
			'icon' => array('fa', 'life-ring'),
			'layout' => array(
				'meta_slugs' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Maintenance', 'relish' ),
					'layout' => array(
						'show_loader' => array(
							'title' => esc_html__( 'Show Loader', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'breadcrumbs' => array(
							'title' => esc_html__( 'Show breadcrumbs', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'blog_author' => array(
							'title' => esc_html__( 'Show post author', 'relish' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'portfolio_slug' => array(
							'title' => esc_html__( 'Portfolio slug', 'relish' ),
							'type' 	=> 'text',
							'value'	=> 'Portfolio'
						),
						'staff_slug' => array(
							'title' => esc_html__( 'Staff Slug', 'relish' ),
							'type' 	=> 'text',
							'value'	=> 'Our Team'
						),
						'blog_title' => array(
							'title' => esc_html__( 'Blog Slug', 'relish' ),
							'type' 	=> 'text',
							'value'	=> 'Blog'
						),
						'_theme_purchase_code' => array(
							'title' => esc_html__( 'Theme purchase code', 'relish' ),
							'type' 	=> 'text',
							'value'	=> '',
							'customizer' 	=> array( 'show' => false )
						),
					)
				),
				'help_slugs' => array(
					'type' => 'tab',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Help', 'relish' ),
					'layout' => array(
						'help' => array(
					       'title' 			=> esc_html__( 'Help', 'eight' ),
					       'addrowclasses' => 'grid-col-12',
					       'type' 			=> 'info',
					       'subtype'		=> 'custom',
					       'value' 			=> '<a class="cwsfw_info_button" href="http://relish.creaws.com/manual" target="_blank"><i class="fa fa-life-ring"></i>&nbsp;&nbsp;' . esc_html__( 'Online Tutorial', 'eight' ) . '</a>&nbsp;&nbsp;<a class="cwsfw_info_button" href="https://www.youtube.com/user/cwsvideotuts/playlists" target="_blank"><i class="fa fa-video-camera"></i>&nbsp;&nbsp;' . esc_html__( 'Video Tutorial', 'eight' ) . '</a>',
					    ),						
					)
				),


			)
		),
		'social_options' => array(
			'type' => 'section',
			'title' => esc_html__('Social Networks', 'relish' ),
			'icon' => array('fa', 'share-alt'),
			'layout' => array(
				'social_option'	=> array(
					'type' => 'tab',
					'init'	=> 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Social Options', 'relish' ),
					'layout' => array(
						'social_group' => array(
							'type' => 'group',
							'addrowclasses' => 'group sortable',
							'title' => esc_html__('Social Networks', 'relish' ),
							'button_title' => esc_html__('Add new social network', 'relish' ),
							'layout' => array(
								'title' => array(
									'type' => 'text',
									'atts' => 'data-role="title"',
									'title' => esc_html__('Social account title', 'relish' ),
								),
								'icon' => array(
									'type' => 'select',
									'addrowclasses' => 'fai',
									'source' => 'fa',
									'title' => esc_html__('Select the icon for this social contact', 'relish' )
								),
								'url' => array(
									'type' => 'text',
									'title' => esc_html__('Url to your account', 'relish' ),
								)
							)
						),
						'social_links_location' => array(
							'type' => 'select',
							'title' => esc_html__( 'Social Links Location', 'relish' ),
							'source' => array(
								'none' => array( esc_html__( 'None', 'relish' ), false),
								'top' => array( esc_html__( 'Top Panel', 'relish' ), true),
								'bottom' => array( esc_html__( 'Copyrights area', 'relish' ), false),
								'top_bottom' => array( esc_html__( 'Top and Copyrights', 'relish' ), false)
							)
						),
					)
				),
			)
		), // end of sections
	);
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {
		$settings['woo_options'] = array(
			'type'		=> 'section',
			'title'		=> esc_html__( 'WooCommerce', 'relish' ),
			'icon'		=> array('fa', 'shopping-cart'),
			'layout'	=> array(
				'woo_options' => array(
					'type' 	=> 'tab',
					'init'	=> 'open',
					'icon' 	=> array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Woocommerce', 'relish' ),
					'layout' => array(
						'woo_cart_enable'	=> array(
							'title'			=> esc_html__( 'Show WooCommerce Cart', 'relish' ),
							'type'			=> 'checkbox',
							'addrowclasses'	=> 'checkbox alt'
						),
						'woo_sb_layout' => array(
							'title' => esc_html__('Sidebar Position', 'relish' ),
							'type' => 'radio',
							'subtype' => 'images',
							'value' => array(
								'left' => 	array( esc_html__('Left', 'relish' ), false, 'e:woo_sidebar;',	get_template_directory_uri() . '/core/images/left.png' ),
								'right' => 	array( esc_html__('Right', 'relish' ), true, 'e:woo_sidebar;', get_template_directory_uri() . '/core/images/right.png' ),
								'none' => 	array( esc_html__('None', 'relish' ), false, 'd:woo_sidebar;', get_template_directory_uri() . '/core/images/none.png' )
							),
						),
						'woo_sidebar' => array(
							'title' => esc_html__('Select a sidebar', 'relish' ),
							'type' => 'select',
							'addrowclasses' => 'disable',
							'source' => 'sidebars',
						),
						'woo_num_products'	=> array(
							'title'			=> esc_html__( 'Products per page', 'relish' ),
							'type'			=> 'number',
							'value'			=> get_option( 'posts_per_page' )
						),
						'woo_related_num_products'	=> array(
							'title'			=> esc_html__( 'Related products', 'relish' ),
							'type'			=> 'number',
							'value'			=> get_option( 'posts_per_page' )
						)
					)
				)
			)
		);
	}
	return $settings;
}

?>