<?php

function cws_mb_fillMbAttributes($meta, &$attr, $prefix = '') {
	$non_grouped = array('group', 'media', 'taxonomy');
	if ($meta && !empty($meta[0])) {
		$meta = $meta[0];
		foreach ($meta as $k => $v) {
			if (is_array($v) && isset($attr[$k]) && !in_array($attr[$k]['type'], $non_grouped) ) {
				// we don't fill grouped elements here
				$v_meta[0] = $v;
				cws_mb_fillMbAttributes($v_meta, $attr, $k);
			} else {
				$entry = !empty($prefix) ? $prefix . "[$k]" : $k;
				$attr_k = &cws_find_array_keys($attr, $entry);
				if ($attr_k) {
					switch ($attr_k['type']) {
						case 'text':
						case 'number':
						case 'textarea':
						case 'datetime':
						case 'media':
						case 'gallery':
							$attr_k['value'] = $v;
							break;
						case 'radio':
							if (is_array($attr_k['value'])) {
								foreach ($attr_k['value'] as $key => $val) {
									if ($key == $v) {
										$attr_k['value'][$key][1] = true;
									} else {
										$attr_k['value'][$key][1] = false;
									}
								}
							}
							break;
						case 'checkbox':
							$atts = '';
							if (isset($attr_k['atts'])) {
								$atts = $attr_k['atts'];
							}
							if ('on' === $v || '1' == $v) {
								$atts .= ' checked';
								$attr_k['atts'] = $atts;
							} else {
								$attr_k['atts'] = str_replace('checked', '', $atts);
							}
							break;
						case 'group':
							if (!empty($v)) {
								echo '<script type="text/javascript">';
								echo 'if(undefined===window[\'cws_groups\']){window[\'cws_groups\']={};}';
								echo 'window[\'cws_groups\'][\'' . $k .'\']=\'' . json_encode($v) . '\';';
								echo '</script>';
							}
							break;
						case 'select':
						case 'taxonomy':
							if (is_array($attr_k['source']) && !empty($v)) {
								foreach ($attr_k['source'] as $key => $value) {
									$attr_k['source'][$key][1] = false; // reset all
								}
								if (is_array($v)) {
									foreach ($v as $key => $value) {
										$attr_k['source'][$value][1] = true;
									}
								} else {
									$attr_k['source'][$v][1] = true;
								}
							} else {
								$attr_k['source'] .= ' ' . $v;
							}
							break;
					}
				}
			}
		}
	}
}

function &cws_find_array_keys(&$attr, $key) {
	$ret = null;
	if (isset($attr[$key]) && 'tab' !== $attr[$key]['type']) {
		$ret = &$attr[$key];
	} else {
		//$a = array_keys($attr);
		// this one's tricky as we need to return original array, not the copy
		foreach ($attr as $k=>&$value) {
			if ('tab' === $value['type'] && isset($value['layout'][$key])) {
				$ret = &$value['layout'][$key];
				break;
			}
		}
	}
	return $ret;
}

function cws_mb_print_layout ($layout, $prefix) {
	$out = '';
	$isTabs = false;
	$tabs = array();
	$bIsWidget = '[' === substr($prefix, -1);
	$tabs_idx = 0;

	foreach ($layout as $key => $v) {
		$row_classes = isset($v['rowclasses']) ? $v['rowclasses'] : 'row row_options ' . $key;
		$row_classes = isset($v['addrowclasses']) ? $row_classes . ' ' . $v['addrowclasses'] : $row_classes;
		if ('label' === $v['type']) {
			//$row_classes .= ' label';
		}

		$row_atts = isset($v['row_atts']) ? ' ' . $v['row_atts'] : '';

		$row_atts = $v['type'] === 'media' ? $row_atts . ' data-role="media"' : $row_atts;

		if ('module' !== $v['type'] && 'tab' !== $v['type']) {
			$out .= '<div class="' . $row_classes . '"' . $row_atts . '>';
			if (isset($v['title'])) {
				$out .= '<label for="'. $key .'">' . $v['title'] . '</label>';
			}
			$out .= "<div>";
		}
		$name = $bIsWidget ? $key . ']' : $key;

		$value = isset($v['value']) && !is_array($v['value']) ? ' value="' . $v['value'] . '"' : '';
		$atts = isset($v['atts']) ? ' ' . $v['atts'] : '';
		switch ($v['type']) {
			case 'text':
			case 'number':
				$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name .'"' . $value . $atts . '>';
				break;
			case 'checkbox':
				if (!empty($atts) && false !== strpos($atts, 'checked')) {
					$value = ' value="1"';
				}
				$out .= '<input type="hidden" name="'. $prefix . $name .'" value="0">';
				$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name .'"' . $value . $atts . '>';
				break;
			case 'radio':
				if (isset($v['subtype']) && 'images' === $v['subtype']) {
					$out .= '<ul class="cws_image_select">';
					foreach ($v['value'] as $k => $value) {
						$selected = isset($value[1]) && true === $value[1] ? ' checked' : '';
						$out .= '<li class="image_select' . $selected . '">';
						$out .= '<div class="cws_img_select_wrap">';
						$out .= '<img src="' . $value[3] . '" alt="image"/>';
						$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
						$out .= '<input type="'. $v['type'] .'" name="'. $prefix. $name . '" value="' . $k . '" title="' . $k . '"' .  $data_options . $selected . '>' . $value[0] . '<br/>';
						$out .= '</div>';
						$out .= '</li>';
					}
					$out .= '<div class="clear"></div>';
					$out .= '</ul>';
				} else {
					foreach ($v['value'] as $k => $value) {
						$selected = isset($value[1]) && true === $value[1] ? ' checked' : '';
						$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
						$out .= '<input type="'. $v['type'] .'" name="'. $prefix. $name . '" value="' . $k . '" title="' . $k . '"' .  $data_options . $selected . '>' . $value[0] . '<br/>';
					}
				}
				break;
			case 'insertmedia':
				$out .= '<div class="cws_tmce_buttons">';
				$out .= 	'<a href="#" id="insert-media-button" class="button insert-media add_media" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>';
				$out .= 	'<div class="cws_tmce_controls">';
				$out .= 	'<a href="#" id="cws-switch-text" class="button" data-editor="content" data-mode="tmce" title="Switch to Text">Switch to Text</a>';
				$out .= '</div></div>';
				break;
			case 'group':
				$out .= '<textarea class="cws_mb_group" style="display:none" data-key="'.$key.'" data-templ="group_template">';
				$out .= cws_mb_print_layout( $v['layout'], $prefix . $name . '[%d][' ); // here would be a template stored
				$out .= '</textarea>';
				$out .= '<ul class="groups"></ul>';
				$out .= '<button type="button" name="'.$key.'">'. $v['button_title'] .'</button>';
				break;
			case 'tab':
				$isTabs = true;
				$tabs[$tabs_idx] = array('tab' => $key, 'title' => $v['title'], 'active' => !isset($v['init']) || $v['init'] !== 'closed');
				$tabs_idx++;
				$out .= '<div class="cws_form_tab' . (isset($v['init']) ?  ' ' . $v['init'] : '' ). '" data-tabkey="'.$key.'">';
				$out .= cws_mb_print_layout( $v['layout'], $prefix );
				$out .= '</div>';
				break;
			case 'textarea':
				$out .= '<textarea name="'. $prefix . $name .'"' . $atts . '>' . (isset($v['value']) ? $v['value'] : '') . '</textarea>';
				break;
			case 'button':
				$out .= '<button type="button" name="'. $prefix . $name .'"' . $atts . '>' . (isset($v['btitle']) ? $v['btitle'] : '') . '</button>';
				break;
			case 'datetime_add':
				$out .= '<ul class="recurring_events" data-pattern="'. $prefix . $name .'" data-lang="'. esc_html__('From', 'relish') . '|' . esc_html__('till', 'relish') .'">';
				if (!empty($v['source'])) {
					$i = 0;
					foreach ($v['source'] as $dstart => $dend) {
						$out .= '<li class="recdate">'. esc_html__('From', 'relish') .' <span>'.$dstart.'</span> '.esc_html__('till', 'relish').' <span>'. $dend .'</span><div class="close"></div>';
						$out .= '<input type="hidden" name="'.$prefix.$key.'['.$i.'][s]" value="'.$dstart.'" />';
						$out .= '<input type="hidden" name="'.$prefix.$key.'['.$i.'][e]" value="'.$dend.'" />';
						$out .= '</li>';
						$i++;
					}
				}
				$datatype = 'datepicker;periodpicker;'.$key.'-end';
				$out .= '<input type="text" data-cws-type="'. $datatype .'" name="'. $key .'"' . $value . $atts . '>';
				$out .= '<div class="row '. $key .'-end">';
				$out .= '<input type="text" name="'. $key .'-end">';
				$out .= '<button type="button" name="'.$key.'">Add '. $v['title'] .'</button>';
				$out .= '</ul>';
				break;
			case 'datetime':
				if (isset($v['dtype'])) {
					list($dtype, $end) = $v['dtype'];
					$datatype = 'datepicker;' . $dtype .';'. $end;
					$out .= '<input type="text" data-cws-type="'. $datatype .'" name="'. $prefix . $name .'"' . $value . $atts . '>';
				}
				break;
			case 'map':
				$out .= '<div class="cws_maps" id="' . $key . '"></div>';
				break;
			case 'taxonomy':
				$taxonomy = isset($v['taxonomy']) ? $v['taxonomy'] : '';
				$ismul = (false !== strpos($atts, 'multiple'));
				$out .= '<select name="'. $prefix . $name . ($ismul ? '[]':''). '"' . $atts . '>';
				$out .= cws_mb_print_taxonomy($taxonomy, $v['source']);
				$out .= '</select>';
				break;
			case 'input_group':
				$out .= '<fieldset class="' . substr($key, 2) . '">';
				$source = $v['source'];
				foreach ($source as $key => $value) {
					$out .= sprintf('<input type="%s" id="%s" name="%s" placeholder="%s">', $value[0], $key, $prefix.$key, $value[1]);
				}
				$out .= '</fieldset>';
				break;
			case 'select':
				$out .= '<select name="'. $prefix . $name .'"' . $atts . ' data-options="select:options">';
				if (!empty($v['source'])) {
					$source = $v['source'];
					if ( is_string($source) ) {
						if (strpos($source, ' ') !== false) {
							//list($func, $arg0) = explode(' ', $source);
							preg_match('/(\w+)\s(.*)$/', $source, $matches);
							$func = $matches[1];
							$arg0 = $matches[2];
						} else {
							$arg0 = '';
							$func = $source;
						}
						$out .= call_user_func_array('cws_mb_print_' . $func, array($arg0) );
					}
					else {
						foreach ($source as $key => $value) {
							$selected = isset($value[1]) && true === $value[1] ? ' selected' : '';
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$out .= '<option value="' . $key . '"' . $data_options . $selected .'>' . $value[0] . '</option>';
						}
					}
				}
				$out .= '</select>';
				break;
			case 'media':
				//$out .= '<label for="cws-pb-row-img">' . __('Add background image', 'relish') . '</label>';
				//$media = isset($v['media']) ? ' data-media="' . $v['media'] . '"' : '';
				$isValueSet = !empty($v['value']['src']);
				$display_none = ' style="display:none"';
				$out .= '<div class="img-wrapper">';
				$out .= '<a class="pb-media-cws-pb"'. ($isValueSet ? $display_none : '') . $atts . '>'. __('Select', 'relish') . '</a>';
				$out .= '<a class="pb-remov-cws-pb"'. ($isValueSet ? '' : $display_none) .'>' . __('Remove', 'relish') . '</a>';
				$out .= '<input class="widefat" data-key="img" readonly id="' . $prefix . $name . '" name="' . $prefix . $name . '[src]" type="hidden" value="' . ($isValueSet ? $v['value']['src']:'') . '" />';
				$out .= '<input class="widefat" data-key="img-id" readonly id="' . $prefix . $name . '[id]" name="' . $prefix . $name . '[id]" type="hidden" value="'.($isValueSet ? $v['value']['id']:'').'" />';
				$out .= '<img src'. ($isValueSet ? '="'.$v['value']['src'] . '"' : '') .'/>';
				$out .= '</div>';
				break;
			case 'gallery':
				$isValueSet = !empty($v['value']);
				$out .= '<div class="img-wrapper">';
				$out .= '<a class="pb-gmedia-cws-pb">'. __('Select', 'relish') . '</a>';
				$out .= '<input class="widefat" data-key="gallery" readonly id="' . $prefix . $name . '" name="' . $prefix . $name . '" type="hidden" value="' . ($isValueSet ? esc_attr($v['value']):'') . '" />';
								//$out .= '<img src'. ($isValueSet ? '="'.$v['value']['src'] . '"' : '') .' style="width:100px;height:100px"/>';
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
			$tabs_out .= '<a href=# data-tab="'. $v['tab'] .'" class="' . ($v['active'] ? 'active' : '') .'">' . $v['title'] . '</a>';
		}
		$tabs_out .= '<div class="clear"></div></div>';
		$out = $tabs_out . $out;
	}
	return $out;
}

function cws_mb_print_sidebars($sel) {
	global $wp_registered_sidebars;
	$output = '';
	foreach ( (array) $wp_registered_sidebars as $k=>$v) {
		$selected = (!empty($sel) && $k === $sel) ? ' selected' : '';
		$output .= '<option value="' . $k . '"' . $selected . '>' . $v['name'] . '</option>';
	}
	return $output;
}

function cws_mb_print_taxonomy($name, $src) {
	$source = cws_mb_get_taxonomy_array($name);
	$output = '<option value=""></option>';
	foreach($source as $k=>$v) {
		$selected = (!empty($src[$k]) && true === $src[$k][1]) ? ' selected' : '';
		$output .= '<option value="' . $k . '"'.$selected.'>' . $v . '</option>';
	}
	return $output;
}

function cws_mb_get_taxonomy_array($tax, $args = '') {
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

function cws_mb_print_fa ($sel) {
	$cwsfi = get_option('cwsfi');
	$isFlatIcons = !empty($cwsfi) && !empty($cwsfi['entries']);
	$output = '<option value=""></option>';
	if (function_exists('relish' . '_get_all_fa_icons')) {
		if (!$isFlatIcons) {
			$output .= '<optgroup label="Font Awesome">';
		}
		$icons = call_user_func('relish' . '_get_all_fa_icons');
		foreach ($icons as $icon) {
			$selected = ($sel === 'fa fa-' . $icon) ? ' selected' : '';
			$output .= '<option value="fa fa-' . $icon . '" '.$selected.'>' . $icon . '</option>';
		}
		if (!$isFlatIcons) {
			$output .= '</optgroup>';
		}
	}
	if (!$isFlatIcons) {
		if (function_exists('relish' . '_get_all_flaticon_icons')) {
			$output .= '<optgroup label="Flaticon">';
			$icons = call_user_func('relish' . '_get_all_flaticon_icons');
			foreach ($icons as $icon) {
				$selected = ($sel === 'flaticon-' . $icon) ? ' selected' : '';
				$output .= '<option value="flaticon-' . $icon . '" '.$selected.'>' . $icon . '</option>';
			}
			$output .= '</optgroup>';
		}
	}
	return $output;
}

function cws_mb_print_titles ( $ptype ) {
	global $post;
	$output = '';
	$post_bc = $post;
	$r = new WP_Query( array( 'posts_per_page' => '-1', 'post_type' => $ptype, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
	while ( $r->have_posts() ) {
		$r->the_post();
		$output .= '<option value="' . $r->post->ID . '">' . esc_attr( get_the_title() ) . "</option>\n";
	}
	wp_reset_postdata();
	$post = $post_bc;
	return $output;
}
?>
