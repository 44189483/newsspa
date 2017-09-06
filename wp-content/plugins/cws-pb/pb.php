<?php global $pb_options;

$options = function_exists(PB_THEME_SLUG . '_get_pb_options') ? call_user_func(PB_THEME_SLUG . '_get_pb_options') : null;

echo '<input type="hidden" name="cws-pb-out" id="cws-pb-out" value="">';

?>
<!-- Text -->
<?php if (!$options || in_array('text', $options['modules']) ): ?>
<div id="cws-pb-text" style="display:none">
	<?php
	if ( isset($options['text']['layout']) ) :
		echo cws_pb_print_layout($options['text']['layout'], $options);
	else:
	?>
	<div class="row row_options">
		<label for="title">Widget Title:</label>
		<input type="text" name="title">
	</div>
	<div class="row">
		<div class="cws_tmce_buttons">
			<a href="#" id="insert-media-button" class="button insert-media add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
			<div class="cws_tmce_controls">
				<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>
			</div>
		</div>
		<div class="cws-pb-tmce">
			<textarea class="wp-editor-area" name="cws-pb-content" id="cws-pb-content"></textarea>
		</div>
	</div>
<?php endif; ?>
</div>
<?php endif; ?>
<!-- Tabs -->
<?php if (!$options || in_array('tabs', $options['modules']) ): ?>
<div id="cws-pb-tabs" style="display:none">
	<?php
	if (isset($options['tabs']['options']['icon_selection']) && $options['tabs']['options']['icon_selection'] == true) {
		echo '<div class="row">';
			cws_pb_icon_selection();
		echo '</div>';
	}
	?>
	<div class="row row_options">
		<label for="title">Tab Title:</label>
		<input type="text" name="title">
	</div>
	<div class="row">
		<div class="cws_tmce_buttons">
			<a href="#" id="insert-media-button" class="button insert-media add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
			<div class="cws_tmce_controls">
				<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>
			</div>
		</div>
		<div class="cws-pb-tmce">
			<textarea class="wp-editor-area" name="cws-pb-content" id="cws-pb-content"></textarea>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- tweet -->
<?php if (!$options || in_array('tweet', $options['modules']) ): ?>
<div id="cws-pb-tweet" style="display:none">
	<?php
	if (isset($options['tweet']['atts']) && true === $options['tweet']['atts']) {
		echo '<div data-options="atts:1"></div>';
	}
	if (isset($options['tweet']['layout']) ) :
		echo cws_pb_print_layout($options['tweet']['layout'], $options);
	else : ?>
	<div class="row row_options">
		<label for="title">Title:</label><input type="text" name="title"><br/>
	</div>
	<div class="row row_options">
		<label for="p_visible">Tweets to show:</label><input type="text" name="p_visible" value="4"><br/>
	</div>
	<div class="row row_options">
		<label for="p_items">Tweets to extract:</label><input type="text" name="p_items" value="4"><br/>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>
<!-- blog -->
<?php if ($options && in_array('blog', $options['modules']) ): ?>
<div id="cws-pb-blog" style="display:none">
	<div class="row row_options">
		<label for="title">Title:</label><input type="text" name="title">
	</div>
	<div class="row row_options">
		<label for="title">Select columns type:</label>
		<select placeholder="Select number of columns" data-placeholder="Select number of columns" name="p_cols">
			<option value="2">Two</option>
			<option value="3">Three</option>
			<option value="4">Four</option>
		</select>
	</div>
	<div class="row row_options">
		<label for="title">Select categories:</label>
		<select multiple placeholder="Select categories" data-placeholder="Select categories" name="p_cats">
		<?php echo cws_pb_print_taxonomy('category'); ?>
		</select>
	</div>
	<div class="row row_options">
		<label for="p_items">Items per page:</label><input type="text" name="p_items" value="8"><br/>
	</div>
</div>
<?php endif; ?>

<!-- portfolio -->
<?php if ($options && in_array('portfolio', $options['modules']) ): ?>
<div id="cws-pb-portfolio" style="display:none">
	<div class="row row_options">
		<label for="title">Title:</label><input type="text" name="title"><br/>
	</div>
	<div class="row row_options">
		<label for="p_cols">Select number of columns:</label>
		<select placeholder="Select number of columns" data-placeholder="Select number of columns" name="p_cols">
			<option value="1">One</option>
			<option value="2" selected>Two</option>
			<option value="3">Three</option>
			<option value="4">Four</option>
		</select>
	</div>
	<div class="row row_options">
		<label for="p_cats">Select categories:</label>
		<select multiple placeholder="Select categories" data-placeholder="Select categories" name="p_cats">
		<?php
			$taxonomy = isset( $options['portfolio']['options'] ) ? $options['portfolio']['options']['taxonomy'] : 'cws-portfolio-type';
			echo cws_pb_print_taxonomy( $taxonomy );
		?>
		</select>
	</div>
	<div class="row row_options">
		<label for="p_filter">Use filter:</label><input name="p_filter" type="checkbox" checked><br/>
	</div>
	<div class="row row_options">
		<label for="p_carousel">Carousel:</label><input name="p_carousel" type="checkbox" data-options="toggle:p_items">
	</div>
	<div class="row row_options p_items">
		<label for="p_items">Items per page:</label><input type="text" name="p_items"><br/>
	</div>
</div>
<?php endif; ?>

<!-- portfolio row fullwidth -->
<?php if ($options && in_array('row_portfolio_fw', $options['modules']) ): ?>
<div id="cws-pb-port1" style="display:none">
	<?php
		echo cws_pb_print_layout($options['row_portfolio_fw']['layout'], $options);
	?>
</div>
<?php endif; ?>

<!-- portfolio row -->
<?php if ($options && in_array('row_portfolio', $options['modules']) ): ?>
<div id="cws-pb-port2" style="display:none">
	<?php
	if (isset($options['row_portfolio']['layout'])) {
		echo cws_pb_print_layout($options['row_portfolio']['layout'], $options);
	}
	?>
</div>
<?php endif; ?>

<!-- ourteam row -->
<?php if ($options && in_array('row_ourteam', $options['modules']) ): ?>
<div id="cws-pb-ourt1" style="display:none">
	<?php
	if (isset($options['row_ourteam']['layout'])) {
		echo cws_pb_print_layout($options['row_ourteam']['layout'], $options);
	}
	?>
</div>
<?php endif; ?>

<!-- blog row -->
<?php if ($options && in_array('row_blog', $options['modules']) ): ?>
<div id="cws-pb-blog1" style="display:none">
	<?php
	if (isset($options['row_blog']['layout'])) {
		echo cws_pb_print_layout($options['row_blog']['layout'], $options);
	}
	?>
</div>
<?php endif; ?>

<!-- Ourteam -->
<?php if ( $options && in_array('ourteam', $options['modules']) ): ?>
<div id="cws-pb-ourteam" style="display:none">
	<?php
	if ( isset($options['ourteam']['layout']) ) :
		echo cws_pb_print_layout($options['ourteam']['layout'], $options);
	else:
	?>
	<div class="row row_options">
		<label for="title">Title:</label><input type="text" name="title"><br/>
	</div>
	<div class="row row_options">
		<label for="p_render_o">Grid:</label><input name="p_render_o" type="radio" value="grid" checked />
		<label for="p_render_o">Carousel:</label><input name="p_render_o" type="radio" value="carousel"><br/>
	</div>
	<div class="row row_options">
		<label for="p_cats">Select categories</label>
		<select multiple placeholder="Select categories" data-placeholder="Select categories" name="p_cats">
		<?php echo cws_pb_print_taxonomy('cws-staff-dept'); ?>
		</select>
	</div>
<?php endif; ?>
</div>
<?php endif; ?>

<?php
function cws_pb_print_taxonomy($name) {
	$source = cws_pb_get_taxonomy_array($name);
	$output = '<option value=""></option>';
	foreach($source as $k=>$v) {
		$output .= '<option value="' . $k . '">' . $v . '</option>';
	}
	return $output;
}

function cws_pb_get_taxonomy_array($tax, $args = '') {
/*	if (!empty($args)) {
		$args .= '&';
	}
	$args .= 'hide_empty=0';*/
	$terms = get_terms($tax, $args);
	$ret = array();
	if (!is_wp_error($terms)) {
		foreach ($terms as $k=>$v) {
			$slug = str_replace('%', '|', $v->slug);
			$ret[$slug] = $v->name;
		}
	} else {
		//$ret[''] = $terms->get_error_message();
	}
	return $ret;
}

function cws_pb_print_titles ( $ptype ) {
	global $post;
	$output = '';
	$post_bc = $post;
	$r = new WP_Query( array( 'posts_per_page' => '-1', 'post_type' => $ptype, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
	while ( $r->have_posts() ) {
		$r->the_post();
		$output .= '<option value="' . $r->post->ID . '">' . esc_attr( get_the_title() ) . "</option>\n";
	}
	wp_reset_query();
	$post = $post_bc;
	return $output;
}

?>

<!-- Callout -->
<?php if ($options && in_array('callout', $options['modules']) ): ?>
<div id="cws-pb-callout" style="display:none">
		<?php
			if (isset($options['callout']['options']['icon_selection']) && $options['callout']['options']['icon_selection'] == true) {
			echo '<div class="row">';
				cws_pb_icon_selection();
			echo '</div>';
			}
		if (isset($options['callout']['layout'])) :
			echo cws_pb_print_layout($options['callout']['layout'], $options);
		else:
		?>
	<div class="row row_options">
		<label for="title">Title:</label><input type="text" name="title">
	</div>
	<div class="row row_options">
		<label for="c_btn_text">Button text:</label><input type="text" name="c_btn_text">
	</div>
	<div class="row row_options">
		<label for="c_btn_href">Button Url:</label><input type="text" name="c_btn_href">
	</div>
	<br/>
	<div class="cws-pb-tmce">
		<textarea class="wp-editor-area" name="cws-pb-content" id="cws-pb-content"></textarea>
	</div>
<?php endif ?>
</div>
<?php endif; ?>

<?php
	function cws_pb_icon_selection () {
		if (function_exists('cws_get_option')) {
			$font_array = cws_get_option( 'body-font' );
			$icon_img_size = $font_array['font-size'] * 1.14 * 1.5;
		} else {
			$icon_img_size = 48;
		}
		ob_start(); ?>
		<section class="icon-options">
			<div class="row">
			<ul class="redux-image-select">
			<li class="redux-image-select selected">
				<input name="fa" type="radio" value="fa"><i class="fa fa-flag fa-2x"></i>
			</li>
			<li class="redux-image-select">
				<input name="img" type="radio" value="img"><i class="fa fa-picture-o fa-2x"></i>
				</li>
			</ul>
			</div>
			<div class="row p_iconimg">
			<div class='image-part'>
				<div class="img-wrapper">
					<label for="cws-pb-icons"><?php  _e('Tab icon:', PB_THEME_SLUG); ?></label>
					<select id="cws-pb-icons" placeholder="Pick an icon for this module" data-placeholder="Pick an icon for this module" name="">
						<?php
							echo cws_pb_print_fa_select(true);
						?>
					</select>
				</div>
				<div class="img-wrapper" style="display:none">
					<a id="pb-media-cws-pb" data-key="p_iconimg"><?php _e('Select', PB_THEME_SLUG); ?></a>
					<a id="pb-remov-cws-pb" data-key="p_iconimg" style="display:none"><?php _e('Remove', PB_THEME_SLUG); ?></a>
					<input class="widefat" readonly id="p_iconimg[row]" name="p_iconimg[row]" type="hidden" value="" />
					<input class="widefat" readonly id="p_iconimg[id]" name="p_iconimg[row]" type="hidden" value="" />
					<img width="<?php echo $icon_img_size; ?>" height="<?php echo $icon_img_size; ?>" id="img-cws-pb" src alt />
				</div>
			</div>
			</div>
		</section>
		<?php ob_end_flush();
	}
	function cws_pb_print_fa_select($all = false) {
		$cwsfi = get_option('cwsfi');
		$isFlatIcons = !empty($cwsfi) && !empty($cwsfi['entries']);
		$output = '<option value=""></option>';
		if (function_exists(PB_THEME_SLUG . '_get_all_fa_icons')) {
			if ($isFlatIcons) {
				$output .= '<optgroup label="Font Awesome">';
			}
			$icons = call_user_func(PB_THEME_SLUG . '_get_all_fa_icons');
			foreach ($icons as $icon) {
				$output .= '<option value="fa fa-' . $icon . '">' . $icon . '</option>';
			}
			if ($isFlatIcons) {
				$output .= '</optgroup>';
			}
		}
		if ($isFlatIcons) {
			if (function_exists(PB_THEME_SLUG . '_get_all_flaticon_icons')) {
				$output .= '<optgroup label="Flaticon">';
				$icons = call_user_func(PB_THEME_SLUG . '_get_all_flaticon_icons');
			foreach ($icons as $icon) {
					$output .= '<option value="flaticon-' . $icon . '">' . $icon . '</option>';
				}
				$output .= '</optgroup>';
			}
		}
		return $output;
	}

	function cws_pb_print_layout ($layout, $options = null) {
		$out = '';
		$isTabs = false;
		$tabs = array();
		$tabs_idx = 0;
		foreach ($layout as $key => $v) {
			$row_classes = isset($v['rowclasses']) ? $v['rowclasses'] : 'row row_options ' . $key;
			$row_classes = isset($v['addrowclasses']) ? $row_classes . ' ' . $v['addrowclasses'] : $row_classes;

			$row_atts = isset($v['row_atts']) ? ' ' . $v['row_atts'] : '';

			$row_atts = $v['type'] === 'media' ? $row_atts . ' data-role="media"' : $row_atts;

			if ('module' !== $v['type'] && 'tab' !== $v['type']) {
				$out .= '<div class="' . $row_classes . '"' . $row_atts . '>';
				if (isset($v['title'])) {
					$out .= '<label for="'. $key .'">' . $v['title'] . '</label>';
				}
				if (isset($v['p_title'])) {
					$out .= '<label for="'. $key .'">' . $v['p_title'] . '</label>';
				}
				$out .= "<div>";
			}
			$value = isset($v['value']) && !is_array($v['value']) ? ' value="' . $v['value'] . '"' : '';
			$atts = isset($v['atts']) ? ' ' . $v['atts'] : '';
			switch ($v['type']) {
				case 'text':
				case 'number':
				case 'checkbox':
					$out .= '<input type="'. $v['type'] .'" name="'. $key .'"' . $value . $atts . '>';
					break;
				case 'radio':
					foreach ($v['value'] as $k => $value) {
						$selected = isset($value[1]) && true === $value[1] ? ' checked' : '';
						$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
						$out .= '<input type="'. $v['type'] .'" name="'. $key . '" value="' . $k . '"' . '" title="' . $k . '"' .  $data_options . $selected . '>' . $value[0] . '<br/>';
						//$out .= '</div>';
					}
					break;
				case 'insertmedia':
					$out .= '<div class="cws_tmce_buttons">';
					$out .= 	'<a href="#" id="insert-media-button" class="button insert-media add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>';
					$out .= 	'<div class="cws_tmce_controls">';
					$out .= 	'<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>';
					$out .= '</div></div>';
					break;
				case 'tab':
					$isTabs = true;
					$tabs[$tabs_idx] = array('tab' => $key, 'title' => $v['title'], 'active' => !isset($v['init']) || $v['init'] !== 'closed');
					$tabs_idx++;
					$out .= '<div class="cws_form_tab' . (isset($v['init']) ?  ' ' . $v['init'] : '' ). '" data-tabkey="'.$key.'">';
					//$out .= '<span>' . $v['title'] . '</span>';
					$out .= cws_pb_print_layout( $v['layout'], $options );
					$out .= '</div>';
					break;
				case 'textarea':
					$out .= '<textarea name="'. $key .'" rows="3"' . $atts . '>' . (isset($v['value']) ? $v['value'] : '') . '</textarea>';
					break;
				case 'taxonomy':
					$taxonomy = isset($v['taxonomy']) ? $v['taxonomy'] : '';
					$out .= '<select name="'. $key .'"' . $atts . '>';
					$out .= cws_pb_print_taxonomy($taxonomy);
					$out .= '</select>';
					break;
				case 'input_group':
					$out .= '<fieldset class="' . substr($key, 2) . '">';
					$source = $v['source'];
					foreach ($source as $k => $value) {
						$name = $key . '[' . $k . ']';
						$out .= sprintf('<input type="%s" id="%s" name="%s" placeholder="%s">', $value[0], $name, $name, $value[1]);
					}
					$out .= '</fieldset>';
					break;
				case 'select':
					$out .= '<select name="'. $key .'"' . $atts . ' data-options="select:options">';
					$source = $v['source'];
					if ( is_string($source) ) {
						list($func, $arg0) = explode(' ', $source);
						$out .= call_user_func_array('cws_pb_print_' . $func, array($arg0) );
					}
					else {
						foreach ($source as $key => $value) {
							$selected = isset($value[1]) && true === $value[1] ? ' selected' : '';
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$out .= '<option value="' . $key . '"' . $data_options . $selected .'>' . $value[0] . '</option>';
						}
					}
					$out .= '</select>';
					break;
				case 'module':
					if ( $options && isset($options[$v['name']]['layout']) ) {
						//$out .= cws_pb_print_layout( $options[$v['name']]['layout'], null );
						$out .= cws_pb_print_layout( $options[$v['name']]['layout'], null );
					}
					break;
				case 'media':
					//$out .= '<label for="cws-pb-row-img">' . __('Add background image', PB_THEME_SLUG) . '</label>';
					//$media = isset($v['media']) ? ' data-media="' . $v['media'] . '"' : '';
					$out .= '<div class="img-wrapper">';
					$out .= '<a id="pb-media-cws-pb" data-key="'.$key.'">'. __('Select', PB_THEME_SLUG) . '</a>';
					$out .= '<a id="pb-remov-cws-pb" data-key="'.$key.'" style="display:none">' . __('Remove', PB_THEME_SLUG) . '</a>';
					$out .= '<input class="widefat" data-role="media" readonly id="'.$key.'[row]" name="'.$key.'[row]" type="hidden" value="" />';
					$out .= '<input class="widefat" readonly id="'.$key.'[id]" name="'.$key.'[id]" type="hidden" value="" />';
					$out .= '<img data-id="'.$key.'" src />';
					$out .= '</div>';
					break;
			}
			if (isset($v['description'])) {
				$out .= '<div class="description">' . $v['description'] . '</div>';
			}
			if ('module' !== $v['type'] && 'tab' !== $v['type'] ) {
				$out .= "</div>";
				$out .= '</div>';
			}
		}
		if ($isTabs) {
			$out .= '<div class="clear"></div>';
			$tabs_out = '<div class="cws_pb_ftabs">';
			foreach ($tabs as $key => $v) {
				$tabs_out .= '<a data-tab="'. $v['tab'] .'" class="' . ($v['active'] ? 'active' : '') .'">' . $v['title'] . '</a>';
			}
			$tabs_out .= '<div class="clear"></div></div>';
			$out = $tabs_out . $out;
		}
		return $out;
	}
?>
<!-- Table columns -->
<?php if (!$options || in_array('tcol', $options['modules']) ): ?>
<div id="cws-pb-tcol" style="display:none">
	<?php	if (isset($options['tcol']['layout'])):
		echo cws_pb_print_layout($options['tcol']['layout'], $options);
	else:
	?>
	<div class="row row_options">
		<label for="title">Column Title:</label><input type="text" name="title">
	</div>
	<div class="row row_options">
		<label for="currency">Currency:</label><input type="text" name="currency">
	</div>
	<div class="row row_options">
		<label for="price">Price:</label><input type="text" name="price">
	</div>
	<div class="row row_options">
		<label for="price_description">Price description:</label><input type="text" name="price_description">
	</div>
	<div class="row row_options">
		<label for="encouragement">Encouragement:</label><input type="text" name="encouragement">
	</div>
	<div class="row row_options">
		<label for="order_url">Order url:</label><input type="text" name="order_url">
	</div>
	<div class="row row_options">
		<label for="button_text">Button text:</label><input type="text" name="button_text">
	</div>
	<div class="row row_options">
		<label for="ishilited">Highlighted:</label><input type="checkbox" name="ishilited">
	</div>
	<div class="row">
		<div class="cws_tmce_buttons">
			<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="content" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
			<div class="cws_tmce_controls">
				<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>
			</div>
		</div>
		<div class="cws-pb-tmce">
			<textarea class="wp-editor-area" name="cws-pb-content" id="cws-pb-content"></textarea>
		</div>
	</div>
<?php endif; ?>
</div>
<?php endif; ?>
<!-- Accordions -->
<?php if (!$options || in_array('accs', $options['modules']) ): ?>
<div id="cws-pb-accs" style="display:none">
	<?php
	if (isset($options['accs']['options']['icon_selection']) && $options['accs']['options']['icon_selection'] == true || !isset($options['accs']['options']['icon_selection'])) {
		echo '<div class="row">';
		cws_pb_icon_selection();
		echo '</div>';
	}
	?>
	<div class="row row_options">
		<label for="title">Accordion Title:</label><input type="text" name="title">
	</div>
	<div class="row">
		<div class="cws_tmce_buttons">
			<a href="#" id="insert-media-button" class="button insert-media add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
			<div class="cws_tmce_controls">
				<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>
			</div>
		</div>
			<div class="cws-pb-tmce">
				<textarea class="wp-editor-area" name="cws-pb-content" id="cws-pb-content"></textarea>
			</div>
	</div>
</div>
<?php endif; ?>

<!-- Row settings -->
<div id="cws-pb-accs-title" style="display:none">
<?php if (isset($options['accs-title']['layout'])):
		echo cws_pb_print_layout($options['accs-title']['layout'], $options);
	else:
	?>
	<div class="row row_options">
		<label for="title">Accordion Title:</label>
		<input type="text" name="title" />
	</div>
	<div class="row row_options">
		<label for="extra_style">Alternative styles:</label>
		<input type="checkbox" name="alt_style" />
	</div>
	<div class="row row_options">
		<label for="title">Use it as toggle?:</label>
		<input type="checkbox" name="istoggle" />
	</div>
<?php endif; ?>
</div>

<div id="cws-pb-col" style="display:none">
<?php if (isset($options['col']['layout'])):
		echo cws_pb_print_layout($options['col']['layout'], $options);
	else:
	?>
	<div class='row row_options'>
		<label for="margins"><?php echo isset($options['margins_label']) ? $options['margins_label'] : 'Margins:' ?></label>
		<fieldset class='margins'>
			<input type="number" id="margin_left" name="margin_left" placeholder="Left (px)">
			<input type="number" id="margin_top" name="margin_top" placeholder="Top (px)">
			<input type="number" id="margin_bottom" name="margin_bottom" placeholder="Bottom (px)">
			<input type="number" id="margin_right" name="margin_right" placeholder="Right (px)">
		</fieldset>
	</div>
	<div class="row row_options">
		<label for="extra_style">Extra style name:</label>
		<select name="extra_style" class="sel2">
		</select>
	</div>
<?php endif; ?>
</div>

<?php
// grid if any
if (isset($options['col']['grid_layout'])) {
	echo '<div id="cws-pb-col-grid" style="display:none">';
	echo cws_pb_print_layout($options['col']['grid_layout'], $options);
	echo '</div>';
}
?>

<div id="cws-pb-col-title" style="display:none">
	<?php if (isset($options['col-title']['layout'])):
		echo cws_pb_print_layout($options['col-title']['layout'], $options);
	else:
	?>
	<div class="row row_options">
		<label for="title">Widget Title:</label>
		<input type="text" name="title">
	</div>
	<div class="row row_options">
		<label for="extra_style">Extra style name:</label>
		<select name="extra_style_col">
			<option value="" selected>Default</option>
			<option value="type-2">Alternative</option>
			<option value="type-vertical">Vertical</option>
		</select>
	</div>
<?php endif; ?>
</div>

<div id="pb_overlay" style="display:none"></div>
<div id="cws_content_wrap" data-cws-ajurl="<?php echo CWS_PB_PLUGIN_URL ?>" class="wp-editor-container" style="display:none">
	<div id="bd">
		<div class="yui-b elements_panel">
			<div id="feeds">
				<div class='tabs clearfix'>
					<a class='active' href="cols">Rows</a>
					<a href="modules">Modules</a>
					<a href="templates">Templates</a>
				</div>
				<div class='tabs_content'>
					<ul id="feeds-cols" class='tab_section clearfix'></ul>
					<ul id="feeds-modules" class='tab_section clearfix' style="display:none"></ul>
					<ul id="feeds-templates" class='tab_section clearfix' style="display:none">
						<li class="button_add">Add To Templates</li>
						<li class="button_save">Save Templates</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="yui-main">
			<div class="yui-b">
				<div class="yui-g">
					<ul id="cws_row"></ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script name="cws_pb_templates" type="text/javascript" charset="utf-8">
<?php
	$templates = get_option('cws-' . PB_THEME_SLUG . '-pbtempl');
	if (!empty($templates)) {
		$templ_out = 'var templates = {';
		foreach ($templates as $k => $v) {
			$safe_name = preg_replace(array('/[^A-Za-z0-9]/'), '_', $k);
			$templ_out .= '\'' . $safe_name . '\':{id:\''.$safe_name.'\',type:\'template\',dtitle:\'' . $k . '\',value:\''. $v .'\'},';
		}
		$templ_out = substr($templ_out, 0, -1) . '};';
		echo $templ_out;
	} else {
		echo 'var templates={};';
	}
?>
</script>