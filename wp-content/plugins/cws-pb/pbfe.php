<?php
cwsfe_addFilters();

$options = function_exists(PB_THEME_SLUG . '_get_pb_options') ? call_user_func(PB_THEME_SLUG . '_get_pb_options') : null;

//if (!$options || in_array('text', $options['modules']) ):
//<div id="cws-pb-text" style="display:none">

//if (isset($options['col']['layout'])) {
// style="display:none"
// echo cwspbfe_print_layout($options['col']['layout'], $options);
global $ta_counter;
$ta_counter = 0;

	function cwspbfe_print_layout ($layout, $options = null, &$values, $prefix = '' ) {
		global $ta_counter;
		$out = '';
		$isTabs = false;
		$tabs = array();
		$tabs_idx = 0;
		$bIsWidget = '[' === substr($prefix, -1);

		foreach ($layout as $key => $v) {
			$row_classes = isset($v['rowclasses']) ? $v['rowclasses'] : 'row row_options ' . $key;
			$row_classes = isset($v['addrowclasses']) ? $row_classes . ' ' . $v['addrowclasses'] : $row_classes;

			$row_atts = isset($v['row_atts']) ? ' ' . $v['row_atts'] : '';

			$row_atts = $v['type'] === 'media' ? $row_atts . ' data-role="media"' : $row_atts;

			if ($bIsWidget) {
				$a = strpos($key, '[');
				if (false !== $a) {
					$name = substr($key, 0, $a) . ']' . substr($key, $a, -1) . ']';
				} else {
					$name = $key . ']';
				}
			} else {
				$name = $key;
			}

			if ('module' !== $v['type'] && 'tab' !== $v['type']) {
				$out .= '<div class="' . $row_classes . '"' . $row_atts . '>';
				if (isset($v['title'])) {
					$out .= '<label for="'. $prefix . $name .'">' . $v['title'] . '</label>';
				}
				if (isset($v['p_title'])) {
					$out .= '<label for="'. $prefix . $name .'">' . $v['p_title'] . '</label>';
				}
				$out .= "<div>";
			}
			if (!empty($v['value']) && !is_array($v['value']) && (!isset($v['addrowclasses']) || false === strpos($v['addrowclasses'], 'disable') ) ) {
				$values[$prefix . $name] = str_replace(array('"'), array('\"'), $v['value']);
			}
			$value = isset($v['value']) && !is_array($v['value']) ? ' value="' . $v['value'] . '"' : '';
			$atts = isset($v['atts']) ? ' ' . $v['atts'] : '';
			switch ($v['type']) {
				case 'text':
				case 'number':
				case 'checkbox':
					$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name .'"' . $value . $atts . '>';
					break;
				case 'radio':
					if (isset($v['subtype']) && 'images' === $v['subtype']) {
						$out .= '<ul class="cws_image_select">';
						foreach ($v['value'] as $k => $value) {
							$selected = '';
							if (isset($value[1]) && true === $value[1]) {
								$selected = ' checked';
								if (!isset($v['addrowclasses']) || false === strpos($v['addrowclasses'], 'disable') ) {
									$values[$prefix . $name] = $k;
								}
							}
							$out .= '<li class="image_select' . $selected . '">';
							$out .= '<div class="cws_img_select_wrap">';
							$out .= '<img src="' . $value[3] . '" alt="image"/>';
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name . '" value="' . $k . '" title="' . $k . '"' .  $data_options . $selected . '>' . $value[0] . '<br/>';
							$out .= '</div>';
							$out .= '</li>';
						}
						$out .= '<div class="clear"></div>';
						$out .= '</ul>';
					} else {
						foreach ($v['value'] as $k => $value) {
							$selected = '';
							if (isset($value[1]) && true === $value[1]) {
								$selected = ' checked';
								if (!isset($v['addrowclasses']) || false === strpos($v['addrowclasses'], 'disable') ) {
									$values[$prefix . $name] = $k;
								}
							}
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name . '" value="' . $k . '" title="' . $k . '"' .  $data_options . $selected . '>' . $value[0] . '<br/>';
						}
					}
					break;
				case 'insertmedia':
					$out .= '<div class="cws_tmce_buttons">';
					$out .= 	'<a href="#" class="button cwsfe_add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>';
					$out .= 	'<div class="cws_tmce_controls">';
					$out .= 	'<a href="#" class="button cwsfe_switch" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>';
					$out .= '</div></div>';
					break;
				case 'tab':
					$isTabs = true;
					$tabs[$tabs_idx] = array('tab' => $prefix . $name, 'title' => $v['title'], 'active' => !isset($v['init']) || $v['init'] !== 'closed');
					$tabs_idx++;
					$out .= '<div class="cws_form_tab' . (isset($v['init']) ?  ' ' . $v['init'] : '' ). '" data-tabkey="'.$prefix . $name.'">';
					//$out .= '<span>' . $v['title'] . '</span>';
					$out .= cwspbfe_print_layout( $v['layout'], $options, $values, $prefix );
					$out .= '</div>';
					break;
				case 'textarea':
					$d = strpos($atts, '%d');
					if ($d) {
						$ta_counter++;
						$atts = sprintf($atts, $ta_counter);
					}
					if (isset($v['value'])) {
						$values[$prefix . $name] = str_replace(array("\n", '"'), array('\n', '\"'), $v['value']);
					}
					if (isset($v['buttons'])) {
						$atts .= ' data-buttons="' . esc_attr(json_encode($v['buttons'])) . '"';
					}
					$out .= '<textarea name="'. $prefix . $name .'"' . $atts . '>' . (isset($v['value']) ? $v['value'] : '') . '</textarea>';
					break;
				case 'taxonomy':
					$taxonomy = isset($v['taxonomy']) ? $v['taxonomy'] : '';
					$out .= '<select name="'. $prefix . $name .'"' . $atts . '>';
					$out .= cws_pb_print_taxonomy($taxonomy);
					$out .= '</select>';
					break;
				case 'gradient':
					$out .= '<fieldset class="cwsfe_'. $v['type'] .'" id="'. $prefix . $name . '">';
					$value = $v['value'];
					if (!isset($v['addrowclasses']) || false === strpos($v['addrowclasses'], 'disable') ) {
						foreach ($value as $g_k => $g_v) {
							$values[$prefix . $name . '[' . $g_k . ']'] = $g_v;
						}
					}
					$gr_style = '';
					if (!empty($value)) {
						$gr_style = sprintf(' style="background: linear-gradient(%sdeg, %s 0%%,%s 100%%)"', $value['orientation'], $value['s_color'], $value['e_color']);
					}
					$out .= '<div class="preview"'.$gr_style.'></div>';
					$out .= '<label for="orientation">'. esc_html__('Gradient orientation (in degrees)', 'the8') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[orientation]" value="'.$value['orientation'].'">';
					$out .= '<label for="s_color">'. esc_html__('Start color', 'the8') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[s_color]" value="'.$value['s_color'].'" data-default-color="'.$value['s_color'].'">';
					$out .= '<label for="e_color">'. esc_html__('End color', 'the8') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[e_color]" value="'.$value['e_color'].'" data-default-color="'.$value['e_color'].'">';
					$out .= '</fieldset>';
					break;
				case 'columns':
					$out .= '<div class="del">Drag here to remove</div>';
					$out .= '<input type="hidden" name="'. $prefix . $name .'"' . $value . $atts . '>';
					$out .= '<div class="columns"></div>';
					$out .= '<div class="col_types">';
					$out .= '<ul class="span12"><li>1/1</li></ul>';
					$out .= '<ul class="span9"><li>3/4</li></ul>';
					$out .= '<ul class="span8"><li>2/3</li></ul>';
					$out .= '<ul class="span6"><li>1/2</li></ul>';
					$out .= '<ul class="span4"><li>1/3</li></ul>';
					$out .= '<ul class="span3"><li>1/4</li></ul>';
					$out .= '</div>';
					break;
				case 'input_group':
					$field_class = ('p_' === substr($prefix . $name,0, 2)) ? substr($prefix . $name, 2) : $prefix . $name;
					$out .= '<fieldset class="' . $field_class . '">';
					$source = $v['source'];
					foreach ($source as $k => $value) {
						$i_name = $prefix . $name . '[' . $k . ']';
						$out .= sprintf('<input type="%s" id="%s" name="%s" placeholder="%s">', $value[0], $i_name, $i_name, $value[1]);
					}
					$out .= '</fieldset>';
					break;
				case 'group':
					if (isset($v['value'])) {
						$out .= '<script type="text/javascript">';
						$out .= 'if(undefined===window[\'cws_groups\']){window[\'cws_groups\']={};}';
						$out .= 'window[\'cws_groups\'][\'' . $key .'\']=\'' . json_encode($v['value']) . '\';';
						$out .= '</script>';
					}
					$out .= '<script class="cwsfe_group" style="display:none" data-key="'.$key.'" data-templ="group_template" type="text/html">';
					$dummy = array();
					$out .= cwspbfe_print_layout( $v['layout'], $options, $dummy, $prefix . $name . '[%d][' );// here would be a template stored
					$out .= '</script>';
					$out .= '<ul class="groups"></ul>';
					if (isset($v['button_title'])) {
						$out .= '<button type="button" name="'.$key.'">'. $v['button_title'] .'</button>';
					}
					break;
				case 'select':
					$ismul = (false !== strpos($atts, 'multiple')) ? '[]' : '';
					$select_tag = '<select name="'. $prefix . $name . $ismul .'"' . $atts;
					$source = $v['source'];
					if ( is_string($source) ) {
						if (strpos($source, ' ') !== false) {
							preg_match('/(\w+)\s(.*)$/', $source, $matches);
							$func = $matches[1];
							$arg0 = $matches[2];
						} else {
							$arg0 = '';
							$func = $source;
						}
						//$out .= $select_tag . ' data-options="select:options">';
						$out .= $select_tag . '>';
						$out .= call_user_func_array('cws_pb_print_' . $func, array($arg0) );
					}	else {
						$selected_index = array();
						$si = 0;
						$sel_options = '';
						foreach ($source as $k => $value) {
							$selected = '';
							if (isset($value[1]) && true === $value[1]) {
								$selected = ' selected';
								array_push($selected_index, $si);
								if (!isset($v['addrowclasses']) || false === strpos($v['addrowclasses'], 'disable') ) {
									$values[$prefix . $name] = $k;
								}
							}
							$si++;
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$sel_options .= '<option value="' . $k . '"' . $data_options . $selected .'>' . $value[0] . '</option>';
						}
						if (!empty($selected_index)) {
							$select_tag .= ' data-defaultvalue="'	. implode(',', $selected_index) . '"';
						}
						//$out .= $select_tag . ' data-options="select:options">';
						$out .= $select_tag . '>';
						$out .= $sel_options;
					}
					$out .= '</select>';
					break;
				case 'module':
					if ( $options && isset($options[$v['name']]['layout']) ) {
						//$out .= cwspbfe_print_layout( $options[$v['name']]['layout'], null );
						$out .= cwspbfe_print_layout( $options[$v['name']]['layout'], null, $values, $prefix );
					}
					break;
				case 'media':
					$out .= '<div class="img-wrapper">';
					$out .= '<a id="pb-media-cws-pb" data-key="'.$prefix . $name.'">'. __('Select', PB_THEME_SLUG) . '</a>';
					$out .= '<a id="pb-remov-cws-pb" data-key="'.$prefix . $name.'" style="display:none">' . __('Remove', PB_THEME_SLUG) . '</a>';
					$out .= '<input class="widefat" data-role="media" readonly id="'.$prefix . $name.'[row]" name="'.$prefix . $name.'[row]" type="hidden" value="" />';
					$out .= '<input class="widefat" readonly id="'.$prefix . $name.'[id]" name="'.$prefix . $name.'[id]" type="hidden" value="" />';
					$out .= '<input class="widefat" readonly id="'.$prefix . $name.'[size]" name="'.$prefix . $name.'[size]" type="hidden" value="" />';
					$out .= '<img data-id="'.$prefix . $name.'" src />';
					$out .= '</div>';
					break;
				case 'gallery':
					$isValueSet = !empty($v['value']);
					$out .= '<div class="img-wrapper">';
					$out .= '<a class="pb-gmedia-cws-pb">'. __('Select', 'the8') . '</a>';
					$out .= '<input class="widefat" data-role="gallery" readonly id="' . $prefix . $name . '" name="' . $prefix . $name . '" type="hidden" value="' . ($isValueSet ? esc_attr($v['value']):'') . '" />';
					if ($isValueSet) {
						$g_value = htmlspecialchars_decode($v['value']); // shortcodes should be un-escaped
						$ids = shortcode_parse_atts($g_value);
						if (strpos($ids[1], 'ids=') === 0) {
							preg_match_all('/\d+/', $ids[1], $match);
							if (!empty($match)) {
								$out .= '<div class="cws_gallery">';
								foreach ($match[0] as $k => $val) {
									$out .= '<img src="' . wp_get_attachment_url($val) . '">';
								}
								$out .= '<div class="clear"></div></div>';
							}
						}
					}
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

	function cws_pb_print_taxonomy($name) {
		$source = cws_pb_get_taxonomy_array($name);
		$output = '<option value=""></option>';
		foreach($source as $k=>$v) {
			$output .= '<option value="' . $k . '">' . $v . '</option>';
		}
		return $output;
	}

	function cws_pb_get_taxonomy_array($tax, $args = '') {
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

	function cws_pb_print_all_titles () {
		global $post;
		$output = '';
		$a = get_post_types(array('public'=>true), 'objects');

		$post_bc = $post;
		foreach ($a as $k) {
			$output .= '<optgroup label="'.$k->labels->name.'">';
			$r = new WP_Query( array( 'posts_per_page' => '-1', 'post_type' => $k->name, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
			while ( $r->have_posts() ) {
				$r->the_post();
				$output .= '<option value="' . get_permalink($r->post->ID) . '">' . esc_attr( get_the_title() ) . "</option>\n";
			}
			wp_reset_query();
			$output .= '</optgroup>';
		}
		$post = $post_bc;
		return $output;
	}

	function cws_pb_print_fa ($sel) {
		$cwsfi = get_option('cwsfi');
		$isFlatIcons = !empty($cwsfi) && !empty($cwsfi['entries']);
		$output = '<option value=""></option>';
		if (function_exists(PB_THEME_SLUG . '_get_all_fa_icons')) {
			if ($isFlatIcons) {
				$output .= '<optgroup label="Font Awesome">';
			}
			$icons = call_user_func(PB_THEME_SLUG . '_get_all_fa_icons');
			foreach ($icons as $icon) {
				$selected = ($sel === 'fa fa-' . $icon) ? ' selected' : '';
				$output .= '<option value="fa fa-' . $icon . '" '.$selected.'>' . $icon . '</option>';
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
					$selected = ($sel === 'flaticon-' . $icon) ? ' selected' : '';
					$output .= '<option value="flaticon-' . $icon . '" '.$selected.'>' . $icon . '</option>';
				}
				$output .= '</optgroup>';
			}
		}
		return $output;
	}

?>
<div id="pb_overlay" style="display:none"></div>
<div id="cws_content_wrap" data-cws-ajurl="<?php echo CWS_PB_PLUGIN_URL ?>" class="wp-editor-container">
	<div id="bd">
		<div class="elements_panel">
			<div class=""></div>
			<?php
				echo '<h4>' . esc_html__('CWS Page Builder', PB_THEME_SLUG) . '</h4>';
				echo printModules($options['fmodules']);

				echo '<h4>' . esc_html__('Saved Templates', PB_THEME_SLUG) . '</h4>';
				echo printTemplates();
			?>
		</div>
		<?php
			$values = array();
			foreach ($options as $k=>$v) {
				if ( isset($options[$k]['layout']) && (!isset($options[$k]['type']) || 'module' !== $options[$k]['type']) ) {
					echo '<div id="cwspbfe-'. $k . '" style="display:none">';
					//echo '<div id="cwspbfe-'. $k . '">';
					$values[$k] = array();
					echo cwspbfe_print_layout($options[$k]['layout'], $options, $values[$k], '');
					echo '</div>';
				}
			}
			/*if (!$is_grid) {
				echo '<div id="cwspbfe-grid" style="display:none">';
				echo cwspbfe_print_layout($options['grid']['layout'], null, $values, '');
				echo '</div>';
			}*/
		?>
	</div>
	<div class="controls" style="display:none">
		<button type="button" name="apply">Apply</button>
		<button type="button" name="cancel">Cancel</button>
	</div>
</div>
<script id="cwsfe">
<?php
global $shortcode_tags;
$shortcodes = '';
foreach ($shortcode_tags as $k => $v) {
	if ($v !== reset($shortcode_tags)) {
		$shortcodes .= '|';
	}
	$k = str_replace('-', '\\\\\\\\-', $k);
	$shortcodes .= $k;
}
$shortcodes = '\\\\[(' . $shortcodes . ')\\\\s.*?\\\\]';

?>
window.cwsfe = {
	cols:{
		3:'<?php echo esc_js(do_shortcode('[col span=3][/col]')); ?>',
		4:'<?php echo esc_js(do_shortcode('[col span=4][/col]')); ?>',
		6:'<?php echo esc_js(do_shortcode('[col span=6][/col]')); ?>',
		8:'<?php echo esc_js(do_shortcode('[col span=8][/col]')); ?>',
		9:'<?php echo esc_js(do_shortcode('[col span=9][/col]')); ?>',
		1:'<?php echo esc_js(do_shortcode('[col span=12][/col]')); ?>',
	},
	icols:{
		3:'<?php echo esc_js(do_shortcode('[icol span=3][/icol]')); ?>',
		4:'<?php echo esc_js(do_shortcode('[icol span=4][/icol]')); ?>',
		6:'<?php echo esc_js(do_shortcode('[icol span=6][/icol]')); ?>',
		8:'<?php echo esc_js(do_shortcode('[icol span=8][/icol]')); ?>',
		9:'<?php echo esc_js(do_shortcode('[icol span=9][/icol]')); ?>',
		1:'<?php echo esc_js(do_shortcode('[icol span=12][/icol]')); ?>',
	},
widgets:{
<?php
	$w_callbacks = array();
	remove_filter('the_content','cwspb_content');
	foreach ($options['fmodules'] as $k => $v) {
		foreach ($v['modules'] as $k => $v) {
			$w_content = '';
			if (isset($options[$k]) && isset($options[$k]['content'])) {
				$w_content = $options[$k]['content'];
			}
			if (isset($options[$k]) && isset($options[$k]['callback'])) {
				$w_callbacks[$k] = $options[$k]['callback'];
			}
			echo $k . ':\'' . esc_js(do_shortcode('[cws-widget type=' . $k . ']'.$w_content.'[/cws-widget]')) . '\',' . "\n";
		}
	}
	echo 'row:\'' . esc_js(do_shortcode('[cws-row][cws-grid atts=\'{"_cols":"1"}\'][col span=12][/col][/cws-grid][/cws-row]')) .'\',' . "\n";
?>
},
widget_callbacks:{
<?php
	foreach ($w_callbacks as $k => $v) {
		if (!empty($v) ) {
			echo $k . ':\'' . $v . '\',' . "\n";
		}
	}
?>
},
widget_atts:{
<?php
	foreach ($values as $k => $v) {
		if (!empty($v) && is_array($v)) {
			echo $k . ':{';
			foreach ($v as $name => $value) {
				echo sprintf('"%s":"%s",', $name, $value);
			}
			echo '},' . "\n";
		}
	}
?>
},
templates:{
<?php
	$templates = get_option('cwsfe_t');
	if (!empty($templates) && !empty($templates[PB_THEME_SLUG])) {
		foreach ($templates[PB_THEME_SLUG] as $k => $v) {
			if (!empty($v) && !empty($v['content'])) {
				echo '\'' . $k . '\':\'';
				$v_content = $v['content'];
				$v_rendered = do_shortcode($v_content);
				$v_rendered = str_replace(
					array('\"'),
					array('%22;'),
					$v_rendered);
				echo esc_js($v_rendered);
				echo '\',' . "\n";
			}
		}
	}
?>
},
l10n:{
	update:'<?php _e('Update'); ?>',
	areusure:'<?php _e('Are you sure you want to do this?'); ?>',
	enter_template:'<?php esc_html_e('Enter the name of the template.', 'cws_pb'); ?>',
},
shortcodes:'<?php echo $shortcodes ?>',
nonce:'<?php echo wp_create_nonce('cwsfe_ajax'); ?>',
row_width:'<?php echo isset($options["row_width"]) ? $options["row_width"] : "1170px"; ?>',
tabs:{
	<?php if (isset($options['tabs'])): ?>
	selectors:{<?php printJsObjects($options['tabs']['selectors']); ?>},
	<?php endif ?>
	<?php if (isset($options['tabs']) && isset($options['tabs']['init'])): ?>
	init: <?php echo '"' . $options['tabs']['init'] . '"'; ?>,
	<?php endif ?>
},
accs:{
	<?php if (isset($options['accs'])): ?>
	selectors:{<?php printJsObjects($options['accs']['selectors']); ?>},
	<?php endif ?>
	<?php if (isset($options['accs']) && isset($options['accs']['init'])): ?>
	init: <?php echo '"' . $options['accs']['init'] . '"'; ?>,
	<?php endif ?>
},
igrid:{
	<?php if (isset($options['igrid'])): ?>
	selectors:{<?php printJsObjects($options['igrid']['selectors']); ?>},
	<?php endif ?>
	<?php if (isset($options['igrid']) && isset($options['igrid']['init'])): ?>
	init: <?php echo '"' . $options['igrid']['init'] . '"'; ?>,
	<?php endif ?>
},
};

</script>

<?php

	echo '<div id="div_cwsfe_dummy" style="display:none"><textarea id="cwsfe_dummy"></textarea>';
	wp_editor('', 'cwsfe_dummy');
	echo '</div>';

	function printJsObjects($objects) {
		if (!empty($objects)) {
			foreach ($objects as $name => $value) {
				echo sprintf('%s:"%s",', $name, addslashes($value));
			}
		}
	}

	function printModules($items) {
		$out = '';
		if (!empty($items)) {
			$out = '<ul class="modules">';
			$i = 0;
			foreach ($items as $k => $v) {
				$minimized = $i ? ' minimized' : '';
				$out .= '<ul class="parents'.$minimized.'">';
				$out .= '<h6 class="title">' . $v['title'] . '</h6>';
				foreach ($v['modules'] as $k => $v) {
					$out .= '<li class="m_item" data-m="'.$k.'">'. $v .'</li>';
				}
				$i++;
				$out .= '</ul>';
			}
			$out .= '</ul>';
		}
		return $out;
	}

	function printTemplates() {
		$templates = get_option('cwsfe_t');
		$out = '<ul class="templates">';
		if (isset($_GET['prev']) && 'true' === $_GET['prev']) {
			$out .= '<li id="cwspb_lower_panel_left"><a href="add2templates">Save</a><div class="clearfix"></div><div class="cloned_d" style="display:none"></div></li>';
		}
		if (!empty($templates) && !empty($templates[PB_THEME_SLUG])) {
			foreach ($templates[PB_THEME_SLUG] as $k => $v) {
				$out .= '<li class="m_item" data-t="'.$k.'"><p>'. $k .'</p><span class="del"></span><span class="edit"></span></li>';
			}
		}
		$out .= '</ul>';
		return $out;
	}
?>