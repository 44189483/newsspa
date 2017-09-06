<?php
new relish_Metaboxes();

class relish_Metaboxes {
	public $mb_page_layout = array();
	public function __construct() {
		$this->mb_page_layout = array(
			'sb_layout' => array(
				'title' => esc_html__('Sidebar Position', 'relish' ),
				'type' => 'radio',
				'subtype' => 'images',
				'value' => array(
					'default'=>	array( esc_html__('Default', 'relish' ), true, 'd:sidebar1;d:sidebar2', get_template_directory_uri() . '/core/images/default.png' ),
					'left' => 	array( esc_html__('Left', 'relish' ), false, 'e:sidebar1;d:sidebar2',	get_template_directory_uri() . '/core/images/left.png' ),
					'right' => 	array( esc_html__('Right', 'relish' ), false, 'e:sidebar1;d:sidebar2', get_template_directory_uri() . '/core/images/right.png' ),
					'both' => 	array( esc_html__('Double', 'relish' ), false, 'e:sidebar1;e:sidebar2', get_template_directory_uri() . '/core/images/both.png' ),
					'none' => 	array( esc_html__('None', 'relish' ), false, 'd:sidebar1;d:sidebar2', get_template_directory_uri() . '/core/images/none.png' )
				),
			),
			'sidebar1' => array(
				'title' => esc_html__('Select a sidebar', 'relish' ),
				'type' => 'select',
				'addrowclasses' => 'disable',
				'source' => 'sidebars',
			),
			'sidebar2' => array(
				'required' => array( 'sb_layout', '=', 'both' ),
				'title' => esc_html__('Select right sidebar', 'relish' ),
				'type' => 'select',
				'addrowclasses' => 'disable',
				'source' => 'sidebars',
			),
			'is_blog' => array(
				'type' => 'checkbox',
				'title' => esc_html__('Show Blog posts', 'relish' ),
				'atts' => 'data-options="e:blogtype;e:category"',
			),
			'blogtype' => array(
				'type' => 'radio',
				'subtype' => 'images',
				'title' => esc_html__('Blog Layout', 'relish' ),
				'addrowclasses' => 'disable',
				'value' => array(
					'default'=>	array( esc_html__('Default', 'relish' ), false, '', get_template_directory_uri() . '/core/images/default.png' ),
					'small' => array( esc_html__('Small', 'relish' ), false, '', get_template_directory_uri() . '/core/images/small.png' ),
					'medium' => array( esc_html__('Medium', 'relish' ), true, '', get_template_directory_uri() . '/core/images/medium.png' ),
					'large' => array( esc_html__('Large', 'relish' ), false, '', get_template_directory_uri() . '/core/images/large.png' ),
					'2' => array(  esc_html__('Two', 'relish' ), false, '', get_template_directory_uri() . '/core/images/pinterest_2_columns.png'),
					'3' => array( esc_html__('Three', 'relish' ), false, '', get_template_directory_uri() . '/core/images/pinterest_3_columns.png'),
				),
			),
			'category' => array(
				'title' => esc_html__('Category', 'relish' ),
				'type' => 'taxonomy',
				'addrowclasses' => 'disable',
				'atts' => 'multiple',
				'taxonomy' => 'category',
				'source' => array(),
			),
			'sb_foot_override' => array(
				'type' => 'checkbox',
				'title' => esc_html__( 'Customize footer', 'relish' ),
				'atts' => 'data-options="e:footer-sidebar-top"',
			),
			'footer-sidebar-top' => array(
				'title' => esc_html__('Sidebar area', 'relish' ),
				'type' => 'select',
				'addrowclasses' => 'disable',
				'source' => 'sidebars',
			),
			'sb_slider_override' => array(
				'type' => 'checkbox',
				'title' => esc_html__( 'Add Image Slider', 'relish' ),
				'atts' => 'data-options="e:slider_shortcode"',
			),
			'slider_shortcode' => array(
				'title' => esc_html__( 'Slider shortcode', 'relish' ),
				'addrowclasses' => 'disable',
				'type' => 'text',
				'default' => ''
			),
		);
		$this->init();
	}

	private function init() {
		load_template( trailingslashit( get_template_directory() ) . '/core/pb.php');
		add_action( 'add_meta_boxes', array($this, 'post_addmb') );
		add_action( 'add_meta_boxes_cws_portfolio', array($this, 'portfolio_addmb') );
		add_action( 'add_meta_boxes_cws_staff', array($this, 'staff_addmb') );

		add_action( 'admin_enqueue_scripts', array($this, 'mb_script_enqueue') );
		add_action( 'save_post', array($this, 'post_metabox_save'), 11, 2 );
	}

	public function portfolio_addmb() {
		add_meta_box( 'cws-post-metabox-id', 'CWS Portfolio Options', array($this, 'mb_portfolio_callback'), 'cws_portfolio', 'normal', 'high' );
	}

	public function staff_addmb() {
		add_meta_box( 'cws-post-metabox-id', 'CWS Staff Options', array($this, 'mb_staff_callback'), 'cws_staff', 'normal', 'high' );
	}

	public function post_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Post Options', array($this, 'mb_post_callback'), 'post', 'normal', 'high' );
		add_meta_box( 'cws-post-metabox-id-2', 'CWS Page Options', array($this, 'mb_page_callback'), 'page', 'normal', 'high' );
	}

	public function mb_staff_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'is_clickable' => array(
				'type' => 'checkbox',
				'title' => esc_html__('Show details page', 'relish' ),
			),
			'social_group' => array(
				'type' => 'group',
				'addrowclasses' => 'group',
				'title' => esc_html__('Social networks', 'relish' ),
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
					),
				),
			),
		);

		$cws_stored_meta = get_post_meta( $post->ID, 'cws_mb_post' );
		cws_mb_fillMbAttributes($cws_stored_meta, $mb_attr);
		echo cws_mb_print_layout($mb_attr, 'cws_mb_');
	}

	public function mb_page_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = $this->mb_page_layout;

		$cws_stored_meta = get_post_meta( $post->ID, 'cws_mb_post' );
		cws_mb_fillMbAttributes($cws_stored_meta, $mb_attr);
		echo cws_mb_print_layout($mb_attr, 'cws_mb_');
	}

	public function mb_portfolio_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'show_related' => array(
				'title' => esc_html__( 'Show related items', 'relish' ),
				'type' => 'checkbox',
				'atts' => 'checked data-options="e:related_projects_options;e:rpo_title;e:rpo_cols;e:rpo_items_count"',
			),
			'related_projects_options' => array(
				'type' => 'label',
				'title' => esc_html__( 'Related items options', 'relish' ),
			),
			'rpo_title' => array(
				'type' => 'text',
				'title' => esc_html__( 'Title', 'relish' ),
				'value' => esc_html__( 'Related items', 'relish' )
				),
			'rpo_cols' => array(
				'type' => 'select',
				'title' => esc_html__( 'Columns', 'relish' ),
				'source' => array(
					'1' => array(esc_html__( 'one', 'relish' ), false),
					'2' => array(esc_html__( 'two', 'relish' ), false),
					'3' => array(esc_html__( 'three', 'relish' ), false),
					'4' => array(esc_html__( 'four', 'relish' ), true),
					),
				),
			'rpo_categories' => array(
				'title' => esc_html__( 'Categories', 'relish' ),
				'type' => 'taxonomy',
				'atts' => 'multiple',
				'taxonomy' => 'cws_portfolio_cat',
				'source' => array(),
			),
			'rpo_items_count' => array(
				'type' => 'number',
				'title' => esc_html__( 'Number of items to show', 'relish' ),
				'value' => '4'
			),
			'enable_hover' => array(
				'title' => esc_html__( 'Enable hover effect (FancyBox)', 'relish' ),
				'type' => 'checkbox',
				'atts' => 'checked data-options="e:link_options;e:link_options_url;e:link_options_fancybox"',
			),
			'link_options' => array(
				'type' => 'label',
				'title' => esc_html__( 'Add custom URL', 'relish' ),
			),
			'link_options_url' => array(
				'type' => 'text',
				'default' => ''
			),
			'link_options_fancybox' => array(
				'type' => 'checkbox',
				'title' => esc_html__( 'Open in a popup window (FancyBox)', 'relish' ),
				'atts' => 'checked'
			)
		);

		$cws_stored_meta = get_post_meta( $post->ID, 'cws_mb_post' );
		cws_mb_fillMbAttributes($cws_stored_meta, $mb_attr);
		echo cws_mb_print_layout($mb_attr, 'cws_mb_');
	}

	public function mb_post_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'gallery' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Gallery', 'relish' ),
				'layout' => array(
					'gallery' => array(
						'type' => 'gallery'
					)
				)
			),
			'video' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Video', 'relish' ),
				'layout' => array(
					'video' => array(
						'title' => esc_html__( 'Direct URL path of a video file', 'relish' ),
						'type' => 'text'
					)
				)
			),
			'audio' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Audio', 'relish' ),
				'layout' => array(
					'audio' => array(
						'title' => esc_html__( 'A self-hosted or SoundClod audio file URL', 'relish' ),
						'subtitle' => esc_html__( 'Ex.: /wp-content/uploads/audio.mp3 or http://soundcloud.com/...', 'relish' ),
						'type' => 'text'
					)
				)
			),
			'link' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Url', 'relish' ),
				'layout' => array(
					'link' => array(
						'type' => 'text'
					)
				)
			),
			'quote' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Quote', 'relish' ),
				'layout' => array(
					'quote[quote]' => array(
						'subtitle' => esc_html__( 'Enter the quote', 'relish' ),
						'atts' => 'rows="5"',
						'type' => 'textarea'
					),
					'quote[author]' => array(
						'title' => esc_html__( 'Author', 'relish' ),
						'type' => 'text'
					),
					'quote[avatar]' => array(
						'title' => esc_html__( 'Photo', 'relish' ),
						'type' => 'media',
						'atts' => 'data-type="image"',
					)
				)
			)
		);

		$cws_stored_meta = get_post_meta( $post->ID, 'cws_mb_post' );
		cws_mb_fillMbAttributes($cws_stored_meta, $mb_attr);
		echo cws_mb_print_layout($mb_attr, 'cws_mb_');

		$mb_attr_all = array(
			'enable_lightbox' => array(
				'title' => esc_html__( 'Enable lightbox', 'relish' ),
				'type' => 'checkbox',
				'atts' => 'checked',
			),
		);
		cws_mb_fillMbAttributes($cws_stored_meta, $mb_attr_all);
		echo cws_mb_print_layout($mb_attr_all, 'cws_mb_');
	}

	public function mb_script_enqueue($a) {
		global $pagenow;
		global $typenow;
		if( ($a == 'widgets.php' || $a == 'post-new.php' || $a == 'post.php' || $a == 'edit-tags.php') && ('customize.php' !== $pagenow) ) {
			wp_enqueue_script('qtip-js', get_template_directory_uri() . '/fw/js/jquery.qtip.js', array('jquery'), false );
			wp_enqueue_style('qtip-css', get_template_directory_uri() . '/fw/css/jquery.qtip.css', false, '2.0.0' );
			if($typenow != 'product'){
				wp_enqueue_script('select2-js', get_template_directory_uri() . '/core/js/select2/select2.js', array('jquery') );
				wp_enqueue_style('select2-css', get_template_directory_uri() . '/core/js/select2/select2.css', false, '2.0.0' );
			}
			wp_enqueue_script('relish-metaboxes-js', get_template_directory_uri() . '/core/js/metaboxes.js', array('jquery') );
			wp_enqueue_style('relish-metaboxes-css', get_template_directory_uri() . '/core/css/metaboxes.css', false, '2.0.0' );
			wp_enqueue_script('custom-user-js', get_template_directory_uri() . '/core/js/user.js', array('jquery') );
			wp_enqueue_media();

			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_style( 'mb_post_css' );
		} elseif ($a == 'user-edit.php' || $a == 'profile.php') {
			wp_enqueue_media();
			wp_enqueue_script('select2-js', get_template_directory_uri() . '/core/js/select2/select2.js', array('jquery') );
			wp_enqueue_style('select2-css', get_template_directory_uri() . '/core/js/select2/select2.css', false, '2.0.0' );
			wp_enqueue_script('relish-metaboxes-js', get_template_directory_uri() . '/core/js/metaboxes.js', array('jquery') );
			wp_enqueue_style('relish-metaboxes-css', get_template_directory_uri() . '/core/css/metaboxes.css', false, '2.0.0' );
			wp_enqueue_script('custom-user-js', get_template_directory_uri() . '/core/js/user.js', array('jquery') );
		}
	}

	public function post_metabox_save( $post_id, $post )
	{
		if ( in_array($post->post_type, array('post', 'page', 'cws_portfolio', 'cws_staff')) ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;

			if ( !isset( $_POST['mb_nonce']) || !wp_verify_nonce($_POST['mb_nonce'], 'cws_mb_nonce') )
				return;

			if ( !current_user_can( 'edit_post', $post->ID ) )
				return;

			$save_array = array();

			foreach($_POST as $key => $value) {
				if (0 === strpos($key, 'cws_mb_')) {
					if ('on' === $value) {
						$value = '1';
					}
					if (is_array($value)) {
						foreach ($value as $k => $val) {
							if (is_array($val)) {
								$save_array[substr($key, 7)][$k] = $val;
							} else {
								$save_array[substr($key, 7)][$k] = esc_html($val);
							}
						}
					} else {
						$save_array[substr($key, 7)] = esc_html($value);
					}
				}
			}
			if (!empty($save_array)) {
				update_post_meta($post_id, 'cws_mb_post', $save_array);
			}
		}
	}
}
?>