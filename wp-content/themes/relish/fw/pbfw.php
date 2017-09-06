<?php

require_once( ABSPATH . 'wp-admin/includes/file.php' );
global $gf;
global $wp_filesystem;
WP_Filesystem();
$gf = json_decode($wp_filesystem->get_contents(CWSFW_PATH . '/gf.json'));

function cwsfw_get_grouped_types() {
	return array('group', 'media', 'taxonomy', 'fields');
}

function cwsfw_fillMbAttributes($meta, &$attr, $prefix = '') {
	foreach ($meta as $k => $v) {
		$entry = !empty($prefix) ? $prefix . "[$k]" : $k;
		$attr_k = &$attr[$k];
			if ($attr_k) {
				switch ($attr_k['type']) {
					case 'text':
					case 'number':
					case 'textarea':
					case 'datetime':
					case 'gallery':
						$attr_k['value'] = htmlentities(stripslashes($v));
						break;
					case 'media':
						if (isset($attr_k['layout'])) {
							cwsfw_fillMbAttributes($v, $attr_k['layout'], $k);
						}
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
						if ('on' === $v || '1' === $v) {
							$atts .= ' checked';
							$attr_k['atts'] = $atts;
						} else {
							$attr_k['atts'] = str_replace('checked', '', $atts);
						}
						break;
					case 'group':
						if (!empty($v)) {
							$attr_k['value'] = $v;
						}
						break;
					case 'dimensions':
					case 'margins':
						foreach ($v as $key => $value) {
							if (isset($attr_k['value'][$key])) {
								$attr_k['value'][$key]['value'] = $value;
							}
						}
						break;
					case 'font':
						foreach ($v as $key => $value) {
							$attr_k['value'][$key] = $value;
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
					case 'fields':
						cwsfw_fillMbAttributes($v, $attr_k['layout'], $prefix);
						break;
					default:
						break;
				}
			}
		}
	}

function &cwsfw_find_array_keys(&$attr, $key) {
	$ret = null;
	$non_grouped = cwsfw_get_grouped_types();
	if (isset($attr[$key]) && !in_array($attr[$key]['type'], $non_grouped)) {
		$ret = &$attr[$key];
	} else {
		foreach ($attr as $k=>&$value) {
			if (isset($value['layout'][$key])) {
				$ret = &$value['layout'][$key];
				break;
			}
		}
	}
	return $ret;
}

/* straighten up our array, filling the references */
function cwsfw_build_array_keys(&$attr) {
	$ret = array();
	foreach ($attr as $section => &$value) {
		$first_element = reset($value['layout']);
		if ('tab' === $first_element['type']) {
			foreach ($value['layout'] as $tabs => &$val) {
				foreach ($val['layout'] as $k => &$v) {
					$ret[$k] = &$v;
				}
			}
		} else {
			foreach ($value['layout'] as $k => &$v) {
				$ret[$k] = &$v;
			}
		}
	}
	return $ret;
}

function cwsfw_print_layout ($layout, $prefix, &$values = null) {
	$out = '';
	$isTabs = false;
	$isCustomizer = is_customize_preview();
	$tabs = array();
	$bIsWidget = '[' === substr($prefix, -1);
	$tabs_idx = 0;

	foreach ($layout as $key => $v) {
		if (isset($v['customizer']) && !$v['customizer']['show'] && $isCustomizer) continue;
		if ($bIsWidget && empty($v)) continue;
		$row_classes = isset($v['rowclasses']) ? $v['rowclasses'] : 'row row_options ' . $key;
		$row_classes = isset($v['addrowclasses']) ? $row_classes . ' ' . $v['addrowclasses'] : $row_classes;
		if ('label' === $v['type']) {
		}

		$row_atts = isset($v['row_atts']) ? ' ' . $v['row_atts'] : '';

		$row_atts = $v['type'] === 'media' ? $row_atts . ' data-role="media"' : $row_atts;

		if ($values && !empty($v['value']) ) {
			$values[$key] = $v['value'];
		}

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
				$out .= '<label for="' . $prefix . $name . '">' . $v['title'] . '</label>';
				if (isset($v['tooltip']) && is_array($v['tooltip']) ) {
					$out .= '<div class="cwsfw-qtip dashicons-before" title="' . $v['tooltip']['title'] . '" qt-content="'.$v['tooltip']['content'].'">';
					$out .= '</div>';
				}
			}
			$out .= "<div>";
		}

		$value = isset($v['value']) && !is_array($v['value']) ? ' value="' . $v['value'] . '"' : '';
		$atts = isset($v['atts']) ? ' ' . $v['atts'] : '';
		switch ($v['type']) {
			case 'text':
			case 'number':
				$ph = isset($value['placeholder']) ? ' placeholder="' . $value['placeholder'] . '"' : '';
				if (isset($v['verification'])) {
					$atts .= ' data-verification="' . esc_attr( str_replace('"', '\'', json_encode($v['verification'])) ) . '"';
				}
				$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name .'"' . $value . $atts . $ph . '>';
				break;
			case 'info':
				$subtype = isset($v['subtype']) ? $v['subtype'] : 'info';
				$out .= '<div class="'. $subtype .'">';
				if (isset($v['icon']) && is_array($v['icon'])) {
					$out .= '<div class="info_icon">';
					switch ($v['icon'][0]) {
						case 'fa':
							$out .= "<i class='fa fa-2x fa-{$v['icon'][1]}'></i>";
							break;
					}
					$out .= '</div>';
				}
				$out .= '<div class="info_desc">';
				$out .= $v['value'];
				$out .= '</div>';
				$out .= '<div class="clear"></div>';
				$out .= '</div>';
				break;
			case 'checkbox':
				$value = ' value="1"';
				if (!empty($atts) && false !== strpos($atts, 'checked')) {
					$values[$key] = '1';
				} else {
					$values[$key] = '0';
				}
				$out .= '<input type="hidden" name="'. $prefix . $name .'" value="0">';
				$salt = time()*rand(1,1024);
				$out .= '<input type="'. $v['type'] .'" name="'. $prefix . $name .'" id="' . $prefix . $name . '_'.$salt.'"' . $value . $atts . '>';
				$out .= '<label for="' . $prefix . $name . '_'.$salt.'"></label>';
				break;
			case 'radio':
				if (isset($v['subtype']) && 'images' === $v['subtype']) {
					$out .= '<ul class="cws_image_select">';
					foreach ($v['value'] as $k => $value) {
						$selected = '';
						if (isset($value[1]) && true === $value[1]) {
							$selected = ' checked';
							$values[$key] = $k;
						}
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
						$selected = '';
						if (isset($value[1]) && true === $value[1]) {
							$selected = ' checked';
							$values[$key] = $k;
						}
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
			case 'fields':
				$out .= '<div class="cwsfw_fields">';
				$values[$key] = array();
				$out .= cwsfw_print_layout( $v['layout'], $prefix . $name . '[', $values[$key] ); // here would be a template stored
				$out .= '</div>';
				break;
			case 'group':
				case 'group':
				if (isset($v['value'])) {
					$out .= '<script type="text/javascript">';
					$out .= 'if(undefined===window[\'cws_groups\']){window[\'cws_groups\']={};}';
					$out .= 'window[\'cws_groups\'][\'' . $key .'\']=\'' . json_encode($v['value']) . '\';';
					$out .= '</script>';
				}
				$out .= '<script class="cwsfe_group" style="display:none" data-key="'.$key.'" data-templ="group_template" type="text/html">';
				$out .= cwsfw_print_layout( $v['layout'], $prefix . $name . '[%d][', $values ); //would be a template stored
				$out .= '</script>';
				$out .= '<ul class="groups"></ul>';
				if (isset($v['button_title'])) {
					$out .= '<button type="button" class="'.(isset($v['button_class']) ? $v['button_class'] : '').'" name="'.$key.'">'.(isset($v['button_icon']) ? '<i class="'. $v['button_icon'] .'"></i> ' : ''). $v['button_title'] .'</button>';
				}
				break;
			case 'font':
				global $gf;
				$out .= '<fieldset class="cwsfw_'. $v['type'] .'" id="'. $prefix . $name . '">';
				$out .= '<div class="cwsfw_gf_filters">';
				$out .= '<div class="cwsfw_filter_item">';
				$out .= '<label for="font-catalog">'. esc_html__('Font Catalog', 'relish') .'</label>';
				$out .= '<select class="font-catalog">';
				$out .= cwsfw_print_gf_cat();
				$out .= '</select>';
				$out .= '</div>';
				$out .= '<div class="cwsfw_filter_item">';
				$out .= '<label for="font-subs">'. esc_html__('Font Subsets', 'relish') .'</label>';
				$out .= '<select class="font-subs">';
				$out .= cwsfw_print_gf_subs();
				$out .= '</select>';
				$out .= '</div>';
				$out .= '<div class="clear"></div>';
				$out .= '</div>';

				$out .= '<div class="cwsfw_gf_props">';

				$out .= '<div class="props_item">';
				$out .= '<label for="font-family">'. esc_html__('Font Family', 'relish') .'</label>';
				$out .= '<select name="'. $prefix . $name .'[font-family]" class="font-family">';
				$out .= cwsfw_print_gf($v['value']['font-family']);
				$out .= '</select>';
				$out .= '</div>';

				$out .= '<div class="props_item">';
				$out .= '<label for="font-weight">'. esc_html__('Font Weight', 'relish') .'</label>';
				$out .= '<select multiple name="'. $prefix . $name .'[font-weight][]" class="font-weight">';
				if (isset($v['value']['font-weight'])) {
					$font = $v['value']['font-family'];
					$var = $gf->{$font}->var;
					$var_a = explode(';', $var);
					$out .= cwsfw_print_gf_weight($v['value']['font-weight'], $var_a);
				}
				$out .= '</select>';
				$out .= '</div>';

				$out .= '<div class="props_item">';
				$out .= '<label for="font-sub">'. esc_html__('Font Scripts', 'relish') .'</label>';
				$out .= '<select multiple name="'. $prefix . $name .'[font-sub][]" class="font-sub">';
				if (isset($v['value']['font-sub'])) {
					$font = $v['value']['font-family'];
					$var = $gf->{$font}->sub;
					$var_a = explode(';', $var);
					$out .= cwsfw_print_gf_sub($v['value']['font-sub'], $var_a);
				}

				$out .= '</select>';
				$font_type = isset($v['value']['font-type']) ? $v['value']['font-type'] : '';
				$out .= '<input type=hidden name="'. $prefix . $name .'[font-type]" value="'.$font_type.'">';
				$out .= '</div>';
				$out .= '<div class="clear"></div>';

				if ($v['font-color']) {
					$out .= '<div class="props_item">';
					$out .= '<label for="color">'. esc_html__('Font Color', 'relish') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[color]" class="color" data-default-color="'.$v['value']['color'].'">';
					$out .= '</div>';
				}
				if ($v['font-size']) {
					$out .= '<div class="props_item">';
					$out .= '<label for="font-size">'. esc_html__('Font Size', 'relish') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[font-size]" class="font-size" value="'.$v['value']['font-size'].'">';
					$out .= '</div>';
				}
				if ($v['line-height']) {
					$out .= '<div class="props_item">';
					$out .= '<label for="line-height">'. esc_html__('Line height', 'relish') .'</label>';
					$out .= '<input type=text name="'. $prefix . $name .'[line-height]" class="line-height" value="'.$v['value']['line-height'].'">';
					$out .= '</div>';
				}
				$out .= '<div class="clear"></div>';
				$out .= '</div>'; // cwsfw_gf_props
				if (!$isCustomizer) {
					/* now preview */
					$out .= '<div class="preview">';
					$out .= '<div class="preview_text">';
					$preview_text = esc_html__('Quick brown fox jumps over the lazy dog', 'relish');
					$out .= '<p>' . $preview_text . '</p>';
					$out .= '</div>';
					$out .= '</div>';
				}
				$out .= '</fieldset>';
				break;
			case 'dimensions':
			case 'margins':
				$out .= '<fieldset class="cwsfw_'. $v['type'] .'">';
				foreach ($v['value'] as $k => $value) {
					$out .= '<input type="text" name="'. $prefix . $name .'['.$k.']" value="' . $value['value'] .'" placeholder="' . $value['placeholder'] . '"' . $atts . '>';
					$values[$key][$k] = $value['value'];
				}
				$out .= '</fieldset>';
				break;
			case 'tab':
				$isTabs = true;
				$tabs[$tabs_idx] = array(
					'tab' => $key,
					'title' => $v['title'],
					'active' => (isset($v['init']) && $v['init'] === 'open'),
					'icon' => $v['icon']);
				$tabs_idx++;
				$out .= '<div class="cws_form_tab' . (isset($v['init']) ?  ' ' . $v['init'] : ' closed' ). '" data-tabkey="'.$key.'">';
				$out .= cwsfw_print_layout( $v['layout'], $prefix, $values );
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
				$out .= cwsfw_print_taxonomy($taxonomy, $v['source']);
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
				if (false !== strpos($atts, 'multiple') ) {
					$name .= '[]';
				}
				$out .= '<select name="'. $prefix . $name .'"' . $atts . ' data-options="select:options">';
				if (!empty($v['source'])) {
					$source = $v['source'];
					if ( is_string($source) ) {
						if (strpos($source, ' ') !== false) {
							list($func, $arg0) = explode(' ', $source);
						} else {
							$arg0 = '';
							$func = $source;
						}
						$out .= call_user_func_array('cwsfw_print_' . $func, array($arg0) );
					}
					else {
						foreach ($source as $k => $value) {
							$selected = '';
							if (isset($value[1]) && true === $value[1]) {
								$selected = ' selected';
								$values[$key] = $k;
							}
							$data_options = !empty($value[2]) ? ' data-options="' . $value[2] . '"' : '';
							$out .= '<option value="' . $k . '"' . $data_options . $selected .'>' . $value[0] . '</option>';
						}
					}
				}
				$out .= '</select>';
				break;
			case 'media':
				$isValueSet = !empty($v['value']['src']);
				$display_none = ' style="display:none"';
				$out .= '<div class="img-wrapper">';
				$out .= '<img src'. ($isValueSet ? '="'.$v['value']['src'] . '"' : '') .'/>';
				$url_atts = !empty($v['url-atts']) ? ' ' . $v['url-atts'] : ' readonly type="hidden"';
				$out .= '<input class="widefat" data-key="img"' . $url_atts . ' id="' . $prefix . $name . '" name="' . $prefix . $name . '[src]" value="' . ($isValueSet ? $v['value']['src']:'') . '" />';
				$out .= '<a class="pb-media-cws-pb"'. ($isValueSet ? $display_none : '') .'><i class="fa fa-picture-o"></i> '. esc_html__('Select', 'relish') . '</a>';
				$out .= '<a class="pb-remov-cws-pb"'. ($isValueSet ? '' : $display_none) .'><i class="fa fa-trash-o"></i> ' . esc_html__('Remove', 'relish') . '</a>';
				$out .= '<input class="widefat" data-key="img-id" readonly id="' . $prefix . $name . '[id]" name="' . $prefix . $name . '[id]" type="hidden" value="'.($isValueSet ? $v['value']['id']:'').'" />';
				if (isset($v['layout'])) {
					$out .= '<div class="media_supplements">';
					$out .=	cwsfw_print_layout( $v['layout'], $prefix . $name . '[' );
					$out .= '</div>';
				}
				$out .= '</div>';
				break;
			case 'gallery':
				$isValueSet = !empty($v['value']);
				$out .= '<div class="img-wrapper">';
				$out .= '<a class="pb-gmedia-cws-pb">'. esc_html__('Select', 'relish') . '</a>';
				$out .= '<input class="widefat" data-key="gallery" readonly id="' . $prefix . $name . '" name="' . $prefix . $name . '" type="hidden" value="' . ($isValueSet ? esc_attr($v['value']):'') . '" />';
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
			if (is_array($v['icon'])) {
				$icon = sprintf('<i class="%s %s-%s"></i>', $v['icon'][0], $v['icon'][0], $v['icon'][1]);
			} else {
				// direct link
				$icon = '<span></span>';
			}
			$tabs_out .= '<a href=# data-tab="'. $v['tab'] .'" class="' . ($v['active'] ? 'active' : '') .'">' . $icon . $v['title'] . '</a>';
		}
		$tabs_out .= '<div class="clear"></div></div>';
		$out = $tabs_out . $out;
	}
	return $out;
}

function cwsfw_print_gf($sel) {
	global $gf;
	$output = '';
	foreach ( $gf as $k => $v) {
		$type = '';
		if (!empty($v->type)) {
			$type = ' data-type="' . $v->type . '"';
		}
		$selected = (!empty($sel) && $k === $sel) ? ' selected' : '';
		$output .= '<option value="' . esc_attr($k) . '"' . $selected . $type . ' data-cat="'.$v->cat.'" data-weight="'.$v->var.'" data-sub="'.$v->sub.'">' . $k . '</option>';
	}
	return $output;
}

function cwsfw_print_gf_weight($sel, $font_arr) {
	$output = '';
	$weights = array(
		'100' => esc_html__('Ultra-Light 100', 'relish'),
		'100italic' => esc_html__('Ultra-Light Italic 100', 'relish'),
		'200' => esc_html__('Light 200', 'relish'),
		'200italic' => esc_html__('Light Italic 200', 'relish'),
		'300' => esc_html__('Book 300', 'relish'),
		'300italic' => esc_html__('Book Italic 300', 'relish'),
		'regular' => esc_html__('Regular 400', 'relish'),
		'italic' => esc_html__('Italic 400', 'relish'),
		'500' => esc_html__('Medium 500', 'relish'),
		'500italic' => esc_html__('Medium Italic 500', 'relish'),
		'600' => esc_html__('Semi-Bold 600', 'relish'),
		'600italic' => esc_html__('Semi-Bols Italic 600', 'relish'),
		'700' => esc_html__('Bold 700', 'relish'),
		'700italic' => esc_html__('Bold Italic 700', 'relish'),
		'800' => esc_html__('Extra-Bold 800', 'relish'),
		'800italic' => esc_html__('Extra-Bold Italic 800', 'relish'),
		'900' => esc_html__('Ultra-Bold 900', 'relish'),
		'900italic' => esc_html__('Ultra-Bold Italic 900', 'relish'),
		);
	foreach ( $weights as $k => $v) {
		$selected = (!empty($sel) && in_array((String)$k, $sel) ) ? ' selected' : '';
		$disabled = !in_array($k, $font_arr) ? $selected . ' disabled' : $selected;
		$output .= '<option value="' . $k . '"' . $disabled . '>' . $v . '</option>';
	}
	return $output;
}

function cwsfw_getGFSubs() {
	return array(
		'latin' => esc_html__('Latin', 'relish'),
		'latin-ext' => esc_html__('Latin Extended', 'relish'),
		'greek' => esc_html__('Greek', 'relish'),
		'greek-ext' => esc_html__('Greek Extended', 'relish'),
		'vietnamese' => esc_html__('Vietnamese', 'relish'),
		'hebrew' => esc_html__('Hebrew', 'relish'),
		'arabic' => esc_html__('Arabic', 'relish'),
		'devanagari' => esc_html__('Devanagari', 'relish'),
		'cyrillic' => esc_html__('Cyrillic', 'relish'),
		'cyrillic-ext' => esc_html__('Cyrillic Extended', 'relish'),
		'khmer' => esc_html__('Khmer', 'relish'),
		'tamil' => esc_html__('Tamil', 'relish'),
		'thai' => esc_html__('Thai', 'relish'),
		'telugu' => esc_html__('Telugu', 'relish'),
		'bengali' => esc_html__('Bengali', 'relish'),
		'gujarati' => esc_html__('Gujarati', 'relish'),
	);
}

function cwsfw_print_gf_subs() {
	$output = '<option value="all" selected>' . esc_html('All', 'relish') . '</option>';
	$subs = cwsfw_getGFSubs();
	foreach ( $subs as $k => $v) {
		$output .= '<option value="' . $k . '">' . $v . '</option>';
	}
	return $output;
}

function cwsfw_print_gf_sub($sel, $font_arr) {
	$output = '';
	$subs = cwsfw_getGFSubs();
	foreach ( $subs as $k => $v) {
		$selected = (!empty($sel) && in_array($k, $sel) ) ? ' selected' : '';
		$disabled = !in_array($k, $font_arr) ? $selected . ' disabled' : $selected;
		$output .= '<option value="' . $k . '"' . $disabled . '>' . $v . '</option>';
	}
	return $output;
}

function cwsfw_print_gf_cat() {
	global $gf;
	$output = '<option value="all" selected>' . esc_html('All', 'relish') . '</option>';
	$cats = array();
	foreach ( $gf as $k => $v) {
		if (!in_array($v->cat, $cats)) {
			$output .= '<option value="' . $v->cat . '">' . $v->cat . '</option>';
			$cats[] = $v->cat;
		}
	}
	return $output;
}

function cwsfw_print_sidebars($sel) {
	global $wp_registered_sidebars;
	$output = '<option value=""></option>';
	foreach ( (array) $wp_registered_sidebars as $k=>$v) {
		$selected = (!empty($sel) && $k === $sel) ? ' selected' : '';
		$output .= '<option value="' . $k . '"' . $selected . '>' . $v['name'] . '</option>';
	}
	return $output;
}

function cwsfw_print_taxonomy($name, $src) {
	$source = cwsfw_get_taxonomy_array($name);
	$output = '<option value=""></option>';
	foreach($source as $k=>$v) {
		$selected = (!empty($src[$k]) && true === $src[$k][1]) ? ' selected' : '';
		$output .= '<option value="' . $k . '"'.$selected.'>' . $v . '</option>';
	}
	return $output;
}

function cwsfw_get_taxonomy_array($tax, $args = '') {
	$terms = get_terms($tax, $args);
	$ret = array();
	if (!is_wp_error($terms)) {
		foreach ($terms as $k=>$v) {
			$slug = str_replace('%', '|', $v->slug);
			$ret[$slug] = $v->name;
		}
	}
	return $ret;
}

function cwsfw_print_fa ($sel) {
	$cwsfi = get_option('cwsfi');
	$isFlatIcons = false;
	$fIcons = null;
	if (function_exists( 'relish'. '_get_all_flaticon_icons')) {
		$fIcons = call_user_func( 'relish'. '_get_all_flaticon_icons');
		$isFlatIcons = !empty($fIcons) && is_array($fIcons);
	}
	$output = '<option value=""></option>';
	if (function_exists( 'relish'. '_get_all_fa_icons')) {
		if ($isFlatIcons) {
			$output .= '<optgroup label="Font Awesome">';
		}
		$icons = call_user_func( 'relish'. '_get_all_fa_icons');
		foreach ($icons as $icon) {
			$selected = ($sel === 'fa fa-' . $icon) ? ' selected' : '';
			$output .= '<option value="fa fa-' . $icon . '" '.$selected.'>' . $icon . '</option>';
		}
		if ($isFlatIcons) {
			$output .= '</optgroup>';
		}
	}
	if ($isFlatIcons) {
		$output .= '<optgroup label="Flaticon">';
		foreach ($fIcons as $icon) {
			$selected = ($sel === 'flaticon-' . $icon) ? ' selected' : '';
			$output .= '<option value="flaticon-' . $icon . '" '.$selected.'>' . $icon . '</option>';
		}
		$output .= '</optgroup>';
	}
	return $output;
}

function cwsfw_print_titles ( $ptype ) {
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
