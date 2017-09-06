var map;
var marker;
var api = wp.customize;
api.cwsfw = api.cwsfw || {};
api.cwsfw.SubSection = api.Control.extend({
	changed:{},

	ready: function () {
		var self = this, updateSubDebounced, subContent;
		subContent = this.container;

		updateSubDebounced = _.debounce( function(e) {
			self.updateSub(e);
		}, 250 );

		subContent.on( 'change input propertychange', ':input', function( e ) {
			if ('change' === e.type) {
				var groups = jQuery(e.target).closest('.groups');
				if (!groups.length) {
					groups = jQuery(e.target).closest('.cwsfw_font');
				}

				if (undefined !== e.target.dataset['key'] && 'img' === e.target.dataset['key']) {
					groups = jQuery(e.target).parent(); // media needs to send a few values too
				}
				if (undefined === groups || 0 === groups.length) {
					self.saveUpdatedParam(e.target, self.changed);
				} else {
					// we're dealing with groups here
					if (groups.closest('.row').hasClass('sortable')) {
						// update numbers to reflect changes
						fixSortables(groups.parent());
					}
					groups.find('[name]').each(function(k, el) {
						self.saveUpdatedParam(el, self.changed);
					});
				}
				updateSubDebounced(e);
			}
		});
	},

	saveUpdatedParam: function(elem, out) {
		var name_s = elem.name.split('[');
		var name = name_s;
		var curr_atts_lvl = out;
		if (name_s.length > 1) {
			for (var i=1;i<name_s.length;i++) {
				name_s[i] = name_s[i].substring(0,name_s[i].length - 1);
				if (undefined === curr_atts_lvl[name_s[i-1]]) {
					curr_atts_lvl[name_s[i-1]] = {};
				}
				curr_atts_lvl = curr_atts_lvl[name_s[i-1]]
			}
			name = name_s[name_s.length - 1];
			name = name.length ? name : 0;
		}

		var value;
		switch (elem.type) {
			case 'checkbox':
				value = elem.checked;
				break;
			case 'select-multiple':
				value = '';
				for (var i = 0, k=0;i<elem.options.length;i++) {
					if (elem[i].selected == true) {
						value += k>0 ? ',' : '';
						value += elem[i].value;
						k++;
					}
				}
				break;
			case 'radio':
				if (elem.checked) {
					value = elem.value;
				} else {
					return;
				}
				break;
			default:
				value = elem.value;
				break;
		}
		curr_atts_lvl[name] = value;
	},

	updateSub: function( args ) {
		var self = this;
		var parent_id = args.target.closest('.control-section').id.split('-'); // li in wp < 4.7, ul in 4.7
		if (undefined !== parent_id[3]) {
			// these are hacks cause self.setting need to change somehow
			// to make sure customizer sees differences
			var setting_name = 'cwsfw_settings';
			switch (parent_id[3]) {
				case 'cwsfw_mb':
					setting_name = 'cwsfw_mb_settings';
					break;
			}
			api._value[setting_name]._dirty = true;
			api._value[setting_name]._value = self.changed;
			self.setting.callbacks.fireWith(self.setting, [self.changed, null]);
		}
	}
});

jQuery.extend( api.controlConstructor, { cwsfw_section: api.cwsfw.SubSection });

window.initMap = function() {
	var coords_input = jQuery('.inside .row.coordinates input');
	var coords = {lat: -34.397, lng: 150.644};
	var zoom = 8;
	if (coords_input && coords_input[0].value.length > 0 ) {
		var a_coords = coords_input[0].value.split(';');
		coords = {lat: parseFloat(a_coords[0]), lng: parseFloat(a_coords[1])};
		zoom = parseInt(a_coords[2]);
	}
	map = new google.maps.Map(document.getElementById('map'), {
		center: coords,
		zoom: zoom
	});

	// add marker if we restore saved coords
	if (undefined !== a_coords) {
		addMarker(new google.maps.LatLng(parseFloat(a_coords[0]), parseFloat(a_coords[1])));
	}

	map.addListener('click', function(event) {
		addMarker(event.latLng);
	});

	function addMarker(location) {
		if (marker) {
			marker.setMap(null); // remove previous one
		}
		marker = new google.maps.Marker({
			position: location,
			map: map
		});
		var coords_input = jQuery('.inside .row.coordinates input');
		if (coords_input) {
			coords_input[0].value = location.lat() + ';' + location.lng() + ';' + map.zoom;
		}
		if (map.zoom > 13 ) {
			// let's asyncronousely ask google where we at
			request = new XMLHttpRequest();
			var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+location.lat()+','+location.lng()+'&key=AIzaSyD1RDKEdo4eaJ7wHuGC812E2QfWQd0Ojnc&result_type=street_address';
			if (request) {
				request.onreadystatechange = function() {
					if (request.readyState == 4) { //Numeric 4 means DONE
						var data = JSON.parse(request.responseText);
						if ('OK' === data.status) {
							console.log(data.results[0].formatted_address);
						}
					}
				}
			};
			request.open("GET", url, true);
			request.send();
		}
	}
}

function fixSortables(form) {
	jQuery(form).find('ul.groups').each(function(a, el){
		jQuery(el).find('li').each(function(b, el) {
			var that = b;
			jQuery(el).find('[name]').each(function(c, elm) {
				elm.name = elm.name.replace(/(\w+\[)(\d+)(\].*)/, '$1'+that+'$3');
			});
		});
	});
}

function fixCheckboxes(form) {
	jQuery(form).find('input[type="checkbox"]').each(function(a, el){
		jQuery(el).parent().find('input[name="'+el.name+'"][type="hidden"]').remove();
	});
}

var loc;

jQuery(document).ready(function() {
	function waitfor(test, count, callback) {
		// Check if condition met. If not, re-check later (msec).
		while (test()) {
			count++;
			setTimeout(function() {
				waitfor(test, count, callback);
			}, 50);
			return;
		}
		// Condition finally met. callback() can be executed.
		callback();
	}

	function islocset() {
		return (undefined === loc);
	}

	/* add sortable support */
	jQuery('.row.sortable').each(function(k, el){
		jQuery(el).find('ul.groups').sortable({
			items: 'li',
			handle: '>label',
			cancel: 'li.drop-disable',
			start: function(){
			    jQuery('.drop-disable label').css("background-color",'red');
			},
			over: function(){
			    jQuery('.drop-disable label').css("background-color", 'rgb(186, 0, 0)');
			},
			stop: function(){
			    jQuery('.drop-disable label').css("background-color", 'rgb(39, 182, 175)');
			},
			update: function( event, ui ) {
				jQuery(ui.item.find('[name]')[0]).trigger('change');
			},
		});
		jQuery(el).find('ul.groups').disableSelection();
	});

	/* detect unsaved changes */
	jQuery('.cwsfw_controls').on('change', 'input, select, textarea, ul.groups li', function(e) {
		jQuery('.cwsfw_notices .cwsfw_unsaved').show(400);
		jQuery('.cwsfw_top_buttons').addClass('unsaved');
		jQuery('#cwsfw_save.button, #cwsfw_save-1.button').addClass('notify');
		return;
	});

	jQuery('.accordion-subsection h3').on('click', function() {
		jQuery(this).toggleClass('open');
		jQuery(this).parent().find('ul.accordion-subsection-content').slideToggle(300);
	});

	/* impexp */
	jQuery('#cws_impexp_import').on('change', function(e) {
		var reader = new FileReader();
		var file = e.target.files[0];
		reader.addEventListener('load', function () {
			var impexp_ta = document.getElementById('cwsfw_impexp_ta');
			impexp_ta.value = reader.result;

			jQuery('#cwsfw_import').removeClass('disabled');
		}, false);
		if (undefined !== file) {
			reader.readAsText(file);
		}
		return false;
	});

	jQuery('#cwsfw_export').on('click', function(e) {
		var form = jQuery('form#cwsfw');
		fixSortables(form);
		//fixCheckboxes(form);
		var data = form.serializeArray();
		var _data = {};
		for (var i=0;i<data.length;i++){
			var val = data[i].value;
			if (undefined !== _data[data[i].name]) {
				val = _data[data[i].name] + ',' + val;
			}
			_data[data[i].name] = val;
		}
		var data = buildAtts(_data);
		e.target.href = 'data:application/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data['n_params']));
	});

	function buildAtts(params) {
		// first we need to convert string names like 'name[key]' to name[key]
		// and remove p_name if any
		var n_params = {};
		if (undefined !== params) {
			var keys = Object.keys(params);
			for (var k = 0; k < keys.length; k++) {
				var prop = keys[k];
				prop = prop.indexOf('p_') === 0 ? prop.substr(2) : prop;
				var name_s = prop.split('[');
				var name = name_s;
				var curr_atts_lvl = n_params;
				if (name_s.length > 1) {
					for (var i=1;i<name_s.length;i++) {
						name_s[i] = name_s[i].substring(0,name_s[i].length - 1);
						if (undefined === curr_atts_lvl[name_s[i-1]]) {
							curr_atts_lvl[name_s[i-1]] = {};
						}
						curr_atts_lvl = curr_atts_lvl[name_s[i-1]]
					}
					name = name_s[name_s.length - 1];
					name = name.length ? name : 0;
				}
				// remove empty values
				if (undefined !== params['p_'+prop] && params['p_'+prop].length) {
					curr_atts_lvl[name] = params['p_'+prop];
				} else {
					curr_atts_lvl[name] = params[prop];
				}
			}
		}
		// remove empty objects, only one level deep!
		var keys = Object.keys(n_params);
		var atts = '';
		if (keys.length > 0) {
			atts = JSON.stringify(n_params);
			atts = atts.split("'").join("\\'");
			atts = atts.split("[").join("%5B;");
			atts = atts.split("]").join("%5D;");
			/*
			atts = atts.replace("'", "\\'");
			atts = atts.replace("[", "%5B;");
			atts = atts.replace("]", "%5D;");
			*/
			atts = ' atts=\'' + atts + '\'';
		}
		return {atts: atts, n_params: n_params};
	}

	/* qtip */
	jQuery('.cwsfw-qtip').each(function(k, el){
		var tt = el.attributes['title'].value;
		jQuery(el).qtip({
			content: {
				text: el.attributes['qt-content'].value,
				title: tt,
			},
		});
	});


	/* google fonts */

	/* on change sub and cat filters */
	jQuery('.cwsfw_controls fieldset.cwsfw_font select.font-subs,.cwsfw_controls fieldset.cwsfw_font select.font-catalog').on('change', function(e) {
		var parent = jQuery(e.target).closest('fieldset');
		var cat = jQuery(parent).find('select.font-catalog').val();
		var sub = jQuery(parent).find('select.font-subs').val();
		var font = jQuery(parent).find('select.font-family')[0];
		var val = '';
		for (var i=0;i<font.options.length;i++) {
			var subs = font.options[i].dataset['sub'].split(/;/g)
			if (sub === 'all' && cat === 'all') {
				font.options[i].disabled = false;
				if ( val.length === 0 ) {
					val = font.options[i].value;
				}
			} else if ( (-1 !== subs.indexOf(sub) && cat === font.options[i].dataset['cat']) ||
				('all' === sub && cat === font.options[i].dataset['cat']) ||
				(-1 !== subs.indexOf(sub) && 'all' === cat)
				) {
				font.options[i].disabled = false;
				if ( val.length === 0 ) {
					val = font.options[i].value;
				}
			} else {
				font.options[i].disabled = true;
			}
		}
		font.selectedIndex = 0;
		jQuery(font).select2().select2('val', val);
		jQuery(font).trigger('change');
	});

	var currentFont = {};

	/* on preview button click */
	jQuery('.cwsfw_controls fieldset.cwsfw_font').each(function() {
		gf_preview(jQuery(this));
	});

	function gf_preview(parent) {
		var font = jQuery(parent).find('select.font-family').val();
		var lineheight = jQuery(parent).find('input.line-height').val();
		var fontsize = jQuery(parent).find('input.font-size').val();
		var fontweight = jQuery(parent).find('select.font-weight').val();
		fontweight = fontweight ? fontweight.join(',') : 'regular';
		var fontsub = jQuery(parent).find('select.font-sub').val();
		fontsub = fontsub ? fontsub.join(',') : 'latin';
		var fontcolor = jQuery(parent).find('input.color').val();
		var preview_text = jQuery(parent).find('.preview_text');
		var fname = jQuery(parent).attr('id');

		if (undefined === currentFont[parent.id] || currentFont[parent.id]['font'] != font || currentFont[parent.id]['fontweight'] != fontweight || currentFont[parent.id]['fontsub'] != fontsub) {
			currentFont[parent.id] = {font:font,fontweight:fontweight,fontsub:fontsub};

			var selIdx = jQuery(parent).find('select.font-family')[0].selectedIndex;
			if (-1 !== selIdx) {
				var type = jQuery(parent).find('select.font-family')[0][selIdx].dataset['type']
			}			
			if (undefined === type || 'std' !== type) {
				WebFont.load({google: {families: [ font+':'+fontweight+':'+fontsub]}});
			}
		}
		if (preview_text) {
			preview_text.css({fontSize:fontsize, fontFamily:font, lineHeight:lineheight, color: fontcolor});
		}
		return false;
	}

	/* on change font */
	jQuery('fieldset.cwsfw_font select,fieldset.cwsfw_font input').on('change', function(e) {
		var parent = jQuery(e.target).closest('fieldset');
		gf_preview(parent[0]);
	});

	jQuery('fieldset.cwsfw_font select.font-family').on('change', function(e) {
		var parent = jQuery(e.target).closest('fieldset');
		var font_weight = jQuery(parent).find('select.font-weight')[0];
		var weights = (-1 !== e.target.selectedIndex) ? e.target.options[e.target.selectedIndex].dataset['weight'].split(/;/g) : [];
		var val = null;
		for (var i=0;i<font_weight.options.length;i++) {
			if ( -1 === weights.indexOf(font_weight[i].value) ) {
				font_weight.options[i].disabled = true;
			} else {
				font_weight.options[i].disabled = false;
				if (!val) {
					val = font_weight.options[i].value;
				}
			}
		}
		jQuery(font_weight).select2().select2('val', val);
		var font_sub = jQuery(parent).find('select.font-sub')[0];
		var subs = [];
		if (-1 !== e.target.selectedIndex) {
			subs = e.target.options[e.target.selectedIndex].dataset['sub'].split(/;/g);
			var font_type = '';
			if (undefined !== e.target.options[e.target.selectedIndex].dataset['type']) {
				font_type = e.target.options[e.target.selectedIndex].dataset['type'];
			}
			jQuery(parent).find('input[name="'+parent[0].id+'[font-type]"]')[0].value = font_type;
		}
		val = null;
		for (var i=0;i<font_sub.options.length;i++) {
			if ( -1 === subs.indexOf(font_sub[i].value) ) {
				font_sub.options[i].disabled = true;
			} else {
				font_sub.options[i].disabled = false;
				if (!val) {
					val = font_sub.options[i].value;
				}
			}
		}
		jQuery(font_sub).select2().select2('val', val);
	});

	/* /google fonts */

	/* saving, resetting data */
	jQuery('#cwsfw_save, #cwsfw_save-1').on('click', function(e) {
		var form = jQuery('form#cwsfw');

		// check requirement fields
		var requirement = false;
		jQuery.each( form[0], function( key, value ) {
			var el = jQuery(value);
			if (el.closest('.row.row_options').hasClass('requirement')) {
				var title = el.closest('.row.row_options').find('label').text();
				var section = jQuery('[data-key="'+el.closest('div.section').data('section')+'"]').text();
				console.log(section);
				if (el.attr('type') == 'text'){
					if (el.val() == ''){
						requirement = true;
						el.css('border', '1px solid red');
						jQuery('.requirement-fields').remove();
						jQuery('div.section:not(.disable)').append('<div class="requirement-fields notice notice-error is-dismissible"><p><b>Requirement fields:</b></p><p><code>'+title+' ('+section+')</code></p></div>');
					} else {
						requirement = false;
						jQuery('.requirement-fields').remove();
						el.removeAttr('style');
					}
				}
			}
		});
		if (requirement) return false;
	jQuery('.cwsfw_top_buttons .spinner').fadeIn(300).delay(1000).fadeOut(300);
		jQuery('#cwsfw_save.button, #cwsfw_save-1.button').html('<i class="fa fa-floppy-o"></i> Saved');
		// check requirement fields

		fixSortables(form);
		var theme_slug = form.data('theme');
		var _data = form.serialize();
		var _nonce = form.data('nonce');

		// check requirement button styles
		jQuery('#cwsfw_save.button, #cwsfw_save-1.button').removeClass('notify');
		setTimeout(function(){ jQuery('#cwsfw_save.button, #cwsfw_save-1.button').html('<i class="fa fa-floppy-o"></i> Save Changes');} , 2000)
		jQuery('.cwsfw_top_buttons').removeClass('unsaved');
		// check requirement button styles

		jQuery.ajax({
			type: 'post',
			dataType: 'json',
			url: ajaxurl,
			data: {
				action: 'cwsfw_'+theme_slug+'_ajax_save',
				nonce: _nonce,
				data: _data
			},
			error: function(resp){
			},
			success: function(resp){
				jQuery('.cwsfw_notices .cwsfw_unsaved').hide();
			}
		});
		return false;
	});

	jQuery('#cwsfw_reset_all').on('click', function(e) {
		var form = jQuery('form#cwsfw');
		var _nonce = form.data('nonce');
		var theme_slug = form.data('theme');
		jQuery.ajax({
			type: 'post',
			dataType: 'json',
			url: ajaxurl,
			data: {
				action: 'cwsfw_'+theme_slug+'_ajax_read_def',
				nonce: _nonce,
				data: null,
			},
			error: function(resp){
			},
			success: function(resp){
				document.location.reload(true);
			}
		});
		e.preventDefault;
		e.stopPropagation;
		return false;
	});

	jQuery('#cwsfw_reset_sec').on('click', function(e) {
		e.preventDefault;
		e.stopPropagation;
		var form = jQuery('form#cwsfw');
		var li_sec_key = form.find('ul.cwsfw_section_items li.active').data('key');
		var tabs = (1 === form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] .cws_pb_ftabs').length) ? form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] .cws_pb_ftabs') : null;
		var defaults = document.getElementById('cwsfw_defaults');
		defaults = JSON.parse(defaults.innerText);
		if (tabs) {
			var groups = form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] .cws_form_tab.open ul.groups');
		} else {
			var groups = form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] ul.groups');
		}
		if (groups.length > 0) {
			var defaults_a = document.getElementById('cwsfw_defaults_a');
			defaults_a = JSON.parse(defaults_a.innerText);
			defaults_a = JSON.parse(defaults_a);
			// now we need to add groups
			groups.each(function(){
				var key = this.parentNode.getElementsByTagName('textarea')[0].dataset['key'];
				var times = Object.keys(defaults_a[key]).length;
				for (var i=0;i<times;i++) {
					addGroup(jQuery(this).closest('.row_option'));
				}
			});
		}
		if (tabs) {
			var li_names = form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] .cws_form_tab.open [name]');
		} else {
			var li_names = form.find('.cwsfw_controls>[data-section="'+li_sec_key+'"] [name]');
		}
		var val;
		for (var i=0;i<li_names.length;i++){
			val = defaults[li_names[i].name];
			var shouldTrigger = true;
			switch(li_names[i].type) {
				case 'text':
				case 'number':
				case 'select-one':
				case 'textarea':
					var key = li_names[i].dataset['key'];
					li_names[i].value = val;
					if (undefined !== key) {
						switch (key) {
							case 'img':
								// media
								jQuery(li_names[i]).siblings('img')[0].src = val;
								if (0 === val.length) {
									jQuery(li_names[i]).siblings('a.pb-remov-cws-pb').hide();
									jQuery(li_names[i]).siblings('a.pb-media-cws-pb').show();
								}
								break;
						}
					}
					break;
				case 'checkbox':
					li_names[i].checked = (val === '1');
					break;
				case 'radio':
					if (li_names[i].value === val) {
						li_names[i].checked = true;
						if ('cws_img_select_wrap' === li_names[i].parentNode.className) {
							jQuery(li_names[i]).closest('li.image_select').addClass('checked');
						}
					} else {
						shouldTrigger = false;
						li_names[i].checked = false;
						if ('cws_img_select_wrap' === li_names[i].parentNode.className) {
							jQuery(li_names[i]).closest('li.image_select').removeClass('checked');
						}
					}
					break;
				case 'select-multiple':
					jQuery(li_names[i]).val(val.split(','));
					break;
			}
			if (shouldTrigger) {
				jQuery(li_names[i]).trigger('change');
			}
		}
		return false;
	});

	/* saving data */
	jQuery('#cwsfw_import').on('click', function(e) {
		if (!jQuery(e.target).hasClass('disabled')) {
			var form = jQuery('form#cwsfw');
			var _nonce = form.data('nonce');
			var theme_slug = form.data('theme');
			var _data = document.getElementById('cwsfw_impexp_ta').value;
			var data = JSON.parse(_data);
			var _res = norm_array(data, '');

			jQuery.ajax({
				type: 'post',
				dataType: 'json',
				url: ajaxurl,
				data: {
					action: 'cwsfw_'+theme_slug+'_ajax_save',
					nonce: _nonce,
					data: _res,
				},
				error: function(resp){
				},
				success: function(resp){
					document.location.reload(true);
				}
			});

			jQuery('#cwsfw_import').addClass('disabled');
		}
		e.stopPropagation();
		e.preventDefault();
	});

	function norm_array(input, prefix) {
		var ret = '';
		var bracket;
		for (var key in input) {
			if ('object' == typeof input[key]) {
				bracket = prefix.length > 0 ? ']' : '';
				ret += norm_array(input[key], prefix + key + bracket + '[');
			} else {
				bracket = prefix.length > 0 ? ']' : '';
				ret += prefix + key + bracket + '=' + input[key] + '&';
			}
		}
		return ret;
	}

	/**/

	jQuery('.cwsfw_sections li').on('click', function(e) {
		var old_sec = jQuery(this).parent().find('li.active');
		if (jQuery(this)[0] != old_sec[0]) {
			var parent = jQuery(this).closest('#wpbody-content');
			jQuery(this).toggleClass('active');
			old_sec.toggleClass('active');
			var old_key = old_sec[0].dataset['key'];
			var new_key = this.dataset['key'];
			parent.find('.cwsfw_controls .section[data-section="' + old_key + '"]').toggleClass('disable');
			parent.find('.cwsfw_controls .section[data-section="' + new_key + '"]').toggleClass('disable');
		}
		e.stopPropagation();
		e.preventDefault();
	});

	jQuery('.inside .row.country').each(function(el, k) {
		if (jQuery(k).find('select')[0].selectedIndex === 0) {
			jQuery.getJSON("http://api.wipmania.com/jsonp?callback=?",
				function(data) {
					loc = data;
			});
			var that = k;
			waitfor(islocset, 10, function() {
				var el = jQuery(that).find('select')[0];
				var country_id = loc.address.country_code;
				for (var i=0;i<el.options.length;i++){
					if (country_id === el.options[i].value) {
						el.selectedIndex = i;
						break;
					}
				}
			});
		}
	});

	// tabs
	jQuery('.cwsfw_controls .cws_pb_ftabs a').on('click', function(e) {
		var old_tab = jQuery(this).parent().find('a[class="active"]');
		if (jQuery(this)[0] != old_tab[0]) {
			var parent = jQuery(this).parent().parent();
			jQuery(this).toggleClass('active');
			old_tab.toggleClass('active');
			parent.find('.cws_form_tab[data-tabkey]').addClass('closed').removeClass('open'); // hide all
			parent.find('.cws_form_tab[data-tabkey="' + jQuery(this).data('tab')+'"]').toggleClass('closed').toggleClass('open');
		}
		e.stopPropagation();
		e.preventDefault();
	});

	// date-time
	var tp;

	jQuery('.inside .row input[data-cws-type^="datepicker"]').each(function(el, k) {
		var typear = k.dataset.cwsType.split(';');
		switch (typear[1]) {
			case 'periodpicker':
				tp = jQuery(k).periodpicker({
					end: '.inside .row.'+ typear[2] +' input',
					timepicker: true,
					lang: document.body.className.substr(document.body.className.indexOf('locale-')+7, 2),
					timepickerOptions: {
						inputFormat: 'hh:mm'
					},
					cells: [1,3]
				});
			break;
			default:
				jQuery(k).datetimepicker();
			break;
		}
	});

	jQuery('.cwsfw_controls input[name="cws_mb_is_allday"]').on('change', function(e) {
		tp.periodpicker('setOption', 'timepicker', !e.target.checked);
	});

	function addCloseProcessing(node) {
		node.addEventListener('click', function() {
			node.parentNode.removeChild(node);
		}, false);
	}

	jQuery('.cwsfw_controls .recurring_events li .close').each(function(e, k) {
		addCloseProcessing(k);
	});

	var mb_prefix = '';

	jQuery('.cwsfw_controls .recurring_events>div>button').on('click', function() {
		var ul = this.parentNode.parentNode;
		var i = ul.getElementsByTagName('li').length;
		var last_li = i ? ul.getElementsByTagName('li')[i-1] : null;
		var start_t = jQuery(ul).find('input[type="text"]')[0].value;
		var end_t = jQuery(ul).find('input[type="text"]')[1].value;
		var key = mb_prefix + ul.dataset['pattern'].substring( mb_prefix.length );
		var lang = ['From', 'till'];
		if (undefined !== ul.dataset['lang']) {
			lang = ul.dataset['lang'].split('|');
		}

		var node_str = '<li class="recdate">'+ lang[0] +' <span>'+ start_t +'</span> '+lang[1]+' <span>'+ end_t +'</span><div class="close"></div>';
		node_str  += '<input type="hidden" name="'+key+'['+i+'][s]" value="'+start_t+'" />';
		node_str  += '<input type="hidden" name="'+key+'['+i+'][e]" value="'+end_t+'" />';
		node_str  += '</li>';
		if (last_li) {
			last_li.insertAdjacentHTML('afterend', node_str);
		} else {
			ul.insertAdjacentHTML('afterbegin', node_str);
		}
		last_li = ul.getElementsByTagName('li')[i];
		addCloseProcessing(last_li.getElementsByClassName('close')[0]); // add close button processing
	});

	/* group add */
	if (undefined !== window.cws_groups) {
		var i = 0;
		for (var key in window.cws_groups) {
			if (window.cws_groups.hasOwnProperty(key)) {
				group = JSON.parse(window.cws_groups[key]);
				window.cws_groups[key] = addGroups(group, key);
				i++;
			}
		}
	}

	function addGroups(group, key) {

		var parent = jQuery('.row.group.'+key)[0];
		if (undefined === parent) return;
		var textarea = jQuery(parent).find('script[data-templ="group_template"]');
		var template0 = textarea.html();
		var group_key = textarea.data('key');
		var current_id = 0;

		// now we need to assign new values here
		var k = 0;
		for (var gkey in group) {
			if ( group.hasOwnProperty(gkey) ) {
				// since gkey is just a number, we need to get to the items
				var ul = parent.getElementsByTagName('ul')[0];
				var i = ul.getElementsByTagName('li').length;
				var last_li = i ? ul.getElementsByTagName('li')[i-1] : null;
				template = '<li'+(group[gkey].val == 'drop_zone_start' || group[gkey].val == 'drop_zone_end' ? ' class="drop-disable"' : '')+'><label class="disable"></label><div class="close"></div><div class="minimize"></div>' + template0.replace(/%d/g, '' + k) + '</li>';

				if (last_li) {
					last_li.insertAdjacentHTML('afterend', template);
				} else {
					ul.insertAdjacentHTML('afterbegin', template);
				}
				last_li = ul.getElementsByTagName('li')[i];

				for (var item in group[gkey]) {
					if (group[gkey].hasOwnProperty(item)) {

						var group_prefix = mb_prefix + key + '[' + k + '][' + item + ']'; // index, because numbers can be deleted and saved as 0,3,4,6
						var input = jQuery(parent).find('input[name="' + group_prefix +'"],select[name="' + group_prefix +'"],textarea[name="' + group_prefix +'"]');
						if (input.length) {
							input = input[0];
							switch (input.type) {
								case 'text':
								case 'number':
								case 'textarea':
									input.value = group[gkey][item];
									break;
								case 'radio':
									break;
								case 'select-one':
									for (var i=0;i<input.options.length;i++){
										if (group[gkey][item] === input.options[i].value) {
											input.selectedIndex = i;
											break;
										}
									}
									break;
							}

						}
						// value = group[gkey]
					}
				}
				k++;
				processGroupMinimize(last_li); // minimize them on start
				//initWidget(last_li);
			}
		}

		return k;
	}


	jQuery('.row.group>div>button').on('click', function(e) {
		var parent = e.target.parentNode;
		addGroupItem(jQuery(parent).closest('.row_options'));
	});

	function addGroupItem(parent) {
		fixSortables(parent); // in case there were movements or deletings
		var ul = parent.find('ul.groups');
		var i = ul.find('>li').length;
		var last_li = i ? ul.find('>li:last')[0] : null;
		var textarea = parent.find('script[data-templ="group_template"]');
		var template = textarea.html();
		var group_key = textarea.data('key');
		var current_id = ul.find('>li').length;

		template = '<li><label class="disable"></label><div class="close"></div><div class="minimize"></div>' + template.replace(/%d/g, '' + current_id) + '</li>';
		if (last_li) {
			last_li.insertAdjacentHTML('afterend', template);
		} else {
			ul[0].insertAdjacentHTML('afterbegin', template);
		}
		last_li = jQuery(ul).find('>li:last')[0];
		initWidget(last_li, true);
	}

	/* control processing */
	window.processEvntInputOptionsLvl = 0;
	var w_counter = 0;
	var g_cws_pb = [];

	jQuery('div[id^=cws-post-metabox-id-] .row_options .image_select .cws_img_select_wrap,\
.cwsfw-subsections .row_options .image_select .cws_img_select_wrap,\
.widget-liquid-right .widget-content .row_options .image_select .cws_img_select_wrap').on('click', function(el){
		// this one is for clicking on radio images
		processRadioImg(el);
	});

	function processRadioImg(el) {
		var ul_parent = jQuery(el.target).closest('.cws_image_select');
		ul_parent.find('li.checked').toggleClass('checked');
		jQuery(el.target).closest('li').toggleClass('checked');
		var t_input = el.target.getElementsByTagName('input')[0];
		t_input.checked = true;
		jQuery(t_input).trigger('change');
	}

	function getWidgetId (el) {
		return jQuery(el).closest('.row').length ? jQuery(el).closest('*[data-w]').data('w') : 0;
	}

	jQuery('.cwsfw_controls,#customize-theme-controls').each(function(e, k){
		initWidget(this, true);
		jQuery(this).find('.cwsfw_fields').each(function(e, k){
			initWidget(this, true);
		});
	});

	jQuery('.widget-liquid-right .widget-content').each(function(e, k){
		if (!/__i__/.test(k.name))	{ // skip inactive widgets
			initWidget(this, true);
		}
	});

	function processSelects(k) {
		var parent = jQuery(k).closest('.row')[0];
		if (/\sfai/.test(parent.className)) {
			jQuery(k).select2({
				allowClear: true,
				placeholder: " ",
				formatResult: addIconToSelectFa,
				formatSelection: addIconToSelectFa,
				escapeMarkup: function(m) { return m; }
			});
		}
		else {
			jQuery(k).select2({
				allowClear: true,
				placeholder: " ",
			});
		}
	}

	function addIconToSelectFa(icon) {
		if ( icon.hasOwnProperty( 'id' ) && icon.id.length > 0 ) {
			return "<span><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text.toUpperCase() + "</span>";
		} else {
			return icon.text;
		}
	}

	function getInputValue(name, parent) {
		var el = parent.querySelector('*[id="'+name+'"]');
		var ret = null;
		if (el) {
			switch (el.type) {
				case 'checkbox':
					ret = el.checked ? '1' : '0';
					break;
				default:
					ret = el.value;
					break;
			}
		}
		return ret;
	}

	function processMbInputOptions (el, params, bIsAssign, bToggleHide) {
		var row = jQuery(el).closest('.row_options')[0]; // this one should be the only one
		if (undefined === row) {
			return;
		}
		/*if ('top_panel_switcher' === el.name) {
			debugger
		}*/
		var bToggleHide = undefined === bToggleHide ? false : bToggleHide;
		var bDisabled = /(\W|^)disable(\W|$)/.test(row.className);
		//if (undefined !== el.getAttribute('data-options') && el.getAttribute('data-options') && ( (!bIsAssign && !bDisabled && !bToggleHide) || (!bIsAssign && bDisabled && bToggleHide) || (bIsAssign && !bDisabled && !bToggleHide) )) {
		if ( undefined !== el.getAttribute('data-options') && el.getAttribute('data-options') &&  ( ( !bIsAssign && !bDisabled ) || (bIsAssign && !bToggleHide && !bDisabled) || ( bIsAssign && !bDisabled ) ) ) {
			if (bIsAssign && ( ('radio' === el.type && !el.checked) ) ) {
				// by default unchecked checkbox/radio means to ignore any data-options
				return;
			}
			var parent = row.parentNode;
			var options_pairs = el.getAttribute('data-options').split(';');
			for (var i=0; i<options_pairs.length; i++) {
				var pair = options_pairs[i].split(':');
				if ('checkbox' === el.type && !el.checked) {
					if ('e' == pair[0]) {
						pair[0] = 'd';
					} else if ('d' == pair[0]) {
						pair[0] = 'e';
					}  else if ('ei' == pair[0]) {
						pair[0] = 'di';
					} else if ('di' == pair[0]) {
						pair[0] = 'ei';
					}
				}
				switch (pair[0]) {
					case 'ei':
					case 'di':
						var cond_pairs = pair[1].split('|');
						var should = true;
						for (var k=0;k<cond_pairs.length;k++) {
							var cond = cond_pairs[k].split(/([=><])/); // ['op0','=', '0']
							var cond_value = getInputValue(cond[0], parent);
							switch (cond[1]) {
								case '=':
									should = should && (cond_value == cond[2]);
									break;
								case '>':
									should = should && (cond_value > cond[2]);
									break;
								case '<':
									should = should && (cond_value < cond[2]);
									break;
							}
						}
						if (should) {
							// if all true - enable
							if (pair[0] == 'ei') {
								elProcessEnable(pair[2], params, bToggleHide, false, parent);
							} else {
								elDisable(pair[2], parent, params);
							}
						}
						break;
					case 'toggle':
					case 't':
						var bElDisabled;
						if (bToggleHide) {
							bElDisabled = false;
						} else {
							bElDisabled = /(\W|^)disable(\W|$)/.test(parent.getElementsByClassName('row '+ pair[1])[0].className);
							if (!el.checked && bElDisabled) {
								bElDisabled = false;
							}
						}
						parent.getElementsByClassName('row '+pair[1])[0].className = parent.getElementsByClassName('row '+pair[1])[0].className.replace(/\s+disable/gm,'') + (bElDisabled ? '' : ' disable');
						if (!bElDisabled) {
							addInputArray(window.processEvntInputOptionsLvl, 'd', pair[1], parent);
							if (params) {
								delete params[pair[1]];
							}
						} else {
							addInputArray(window.processEvntInputOptionsLvl, 'e', pair[1], parent);
						}
						jQuery(parent).find('div.row.'+pair[1]+' select[data-options],div.row.'+pair[1]+' input[data-options]').each( function(el) {
							var bSkipProcess = false;
							switch (this.type) {
								case 'select-one':
									var el = this;
									while ((el = el.parentElement) && !el.classList.contains('row'));

									window.cws_evnt_param_key = el.className.split(' ')[2]; // !!! get p_something from class
									break;
								case 'radio':
									if (!this.checked) {
										bSkipProcess = true;
									}
									break;
							}
							if (!bSkipProcess) {
								window.processEvntInputOptionsLvl++;
								processMbInputOptions(this, params, bIsAssign, !bElDisabled);
								window.processEvntInputOptionsLvl--;
							}
						});
					break;
					case 'enable':
					case 'e':
						elProcessEnable(pair[1], params, bToggleHide, false, parent);
						break;
					case 'disable':
					case 'd':
						addInputArray(window.processEvntInputOptionsLvl, 'd', pair[1], parent);
						if (!getStatusInputArray(window.processEvntInputOptionsLvl, pair[1], parent)) {
							if (params && !bIsAssign) {
								delete params[pair[1]];
							}
							elDisable(pair[1], parent, params);
						}
						break;
					case 'select':
						if (bIsAssign) {
							var sel_index = 0;
							if (params && undefined !== window.cws_evnt_param_key) {
								sel_index = undefined !== params[window.cws_evnt_param_key] ? params[window.cws_evnt_param_key] : 0;
							}
							//if ( isNaN(parseInt(sel_index)) ) {
							// most likely string value is here
							// need to assign selectedIndex if they don't match
							// i.e. when this control hasn't been processed yet
							for (var i=0;i<el.options.length;i++){
								if (sel_index === el.options[i].value) {
									el.selectedIndex = i;
									break;
								}
							}
						}
						var op_options = (undefined !== el.options[el.selectedIndex] && undefined !== el.options[el.selectedIndex].dataset.options) ? el.options[el.selectedIndex].dataset.options : '';
						bToggleHide = typeof bToggleHide !== 'undefined' ? bToggleHide : false;
						if (op_options.length) {
							options_pairs = op_options.split(';');
							for (var i=0; i<options_pairs.length; i++) {
								pair = options_pairs[i].split(':');
								switch (pair[0]) {
									case 'enable':
									case 'e':
										elProcessEnable(pair[1], params, bToggleHide, false, parent);
										break;
									case 'disable':
									case 'd':
									//parent.querySelectorAll('select[name^="p_'+pair[1]+'"]')[0].value = [];
										addInputArray(window.processEvntInputOptionsLvl, 'd', pair[1], parent);
										if (!getStatusInputArray(window.processEvntInputOptionsLvl, pair[1], parent)) {
											if (params) {
												delete params[pair[1]];
											}
											elDisable(pair[1], parent, params);
										}
										break;
								}
							}
						}
					break;
				}
			}
		}
	}

	function elProcessEnable (pair_1, params, bToggleHide, bIsAssign, par) {
		var parent = par.parentNode;
		if (!bToggleHide && parent.getElementsByClassName('row '+ pair_1).length == 1) {
			if (getStatusInputArray(window.processEvntInputOptionsLvl, pair_1, par)) {
				addInputArray(window.processEvntInputOptionsLvl, 'e', pair_1, par);
				parent.getElementsByClassName('row '+ pair_1)[0].className = parent.getElementsByClassName('row '+pair_1)[0].className.replace(/\s+disable/gm,'');
				if (!bIsAssign) {
					// need to process data-options if any in case we're just clicking thru form,
					// i.e. not comming from assign
					pair_1 = pair_1.replace( /(:|\.|\[|\]|,)/g, "\\$1" ); // jQuery doesn't like ":.[]," in classes or ids
					jQuery(parent).find('div.row.'+pair_1+' select[data-options],div.row.'+pair_1+' input').each( function(el) {
						switch (this.type) {
							case 'select-one':
								var el = this;
								while ((el = el.parentElement) && !el.classList.contains('row'));

								window.cws_evnt_param_key = el.className.match(/\w+/i)[0]; // get p_something from class
								break;
							case 'radio':
								this.checked = (null !== this.getAttribute('checked')) ? true : false;
								break;
							case 'text':
								if (params && undefined !== params[this.name]) {
									this.value = params[this.name];
									if (undefined !== this.dataset['defaultColor']) {
										this.dataset['defaultColor'] = params[this.name];
									}
								}
								break;
						}
						window.processEvntInputOptionsLvl++;
						processMbInputOptions(this, params, true, false);
						window.processEvntInputOptionsLvl--;
					});
				}
			}
		} else {
			addInputArray(window.processEvntInputOptionsLvl, 'd', pair_1, par);
			if (params) {
				delete params[pair_1];
			}
			elDisable(pair_1, parent, params);
		}
	}

	function getStatusInputArray(lvl, value, parent) {
		var i = 0;
		var w_id = getWidgetId(parent);
		g_cws_pb[w_id].filter(function(el, k) {
			if (k<=lvl) {
				i += (-1 !== el.e.indexOf(value)) ? 1 : 0;
				i -= (-1 !== el.d.indexOf(value)) ? 1 : 0;
			}
		});
		return i>=0;
	}

	function addInputArray (lvl, op, value, parent) {
		var w_id = getWidgetId(parent);
		if (undefined === g_cws_pb[w_id][lvl]) {
			g_cws_pb[w_id][lvl] = {'e':[],'d':[]};
		}
		if (-1 === g_cws_pb[w_id][lvl][op].indexOf(value)) {
			g_cws_pb[w_id][lvl][op][g_cws_pb[w_id][lvl][op].length] = value;
		}
	}

	function elDisable (el, parent, params) {
		var el_j = el.replace( /(:|\.|\[|\]|,)/g, "\\$1" ); // jQuery doesn't like ":.[]," in classes or ids
		jQuery(parent).find('div.row.'+ el_j +' select,div.row.'+ el_j +' input,div.row.'+ el_j +' .img-wrapper img').filter(function(k, el){
				if ('s2id' === el.id.substr(0,4) || 'select2-input' === el.className)
					return false;
				return true;
			}).each( function() {
			if ('text' === this.type || 'hidden' === this.type) {
				jQuery(this).val(this.value);
			} else if ('checkbox' === this.type || 'radio' === this.type) {
				if (undefined !== this.getAttribute('data-options')) {
					window.processEvntInputOptionsLvl++;
					processMbInputOptions(this, params, false, true);
					window.processEvntInputOptionsLvl--;
				}
			} else if ('select-one' === this.type) {
				jQuery(this).select2('val', this.value);
				if (undefined !== this.getAttribute('data-options')) {
					window.processEvntInputOptionsLvl++;
					processMbInputOptions(this, params, false, true);
					window.processEvntInputOptionsLvl--;
				}
			} else if (undefined === this.type) {
				jQuery(this).attr("src", this.value);
			}
		});
		if (undefined !== parent && parent.getElementsByClassName('row '+el).length > 0) {
			parent.getElementsByClassName('row '+el)[0].className = parent.getElementsByClassName('row '+el)[0].className.replace(/\s+disable/gm,'') + ' disable';
		}
	}

	jQuery( document ).on( 'widget-added', function( event, widget ){
		if ( /widget-\d+_cws-\w+/.test(widget[0].id) ) {
			initWidget(jQuery(widget).find('.widget-content')[0]);
		}
	});

	function initWidget(parent, forced) {
		var w_local = w_counter;
		if (undefined == parent.dataset['w']) {
			parent.dataset['w'] = w_counter;
		} else {
			w_local = parent.dataset['w'];
		}
		g_cws_pb[w_local] = [{'e':[],'d':[]}];
		w_counter++;

		jQuery(parent).find('div.row select,div.row input[type!="hidden"]').on('change', function(el){
			g_cws_pb[w_local].length = 0;
			processMbInputOptions(el.target, null, false);
			g_cws_pb[w_local].length = 0;
		});
		jQuery(parent).find('#accordion-panel-cwsfw input[data-default-color],.row input[data-default-color]').each(function(){
			var color = this.value.length ? this.value : this.dataset['defaultColor'];
			this.value = color;
			jQuery(this).wpColorPicker({
				'color': color,
				change: function(event, ui){
					event.target.value = ui.color.toString();
					jQuery(event.target).trigger('change');
				}
			});
		});
		jQuery(parent).find('.row_options .image_select .cws_img_select_wrap').on('click', function(el){
			processRadioImg(el);
		});
		jQuery(parent).find('.row_options input,.row_options select').each(function(e, k){
			var bIsAssign = (undefined !== forced) ? !forced : true;
			processMbInputOptions(k, null, true);
		});
		jQuery(parent).find('.row_options select').each(function(e, k){
			processSelects(k);
		});
		initSelectImage(parent);

		jQuery(parent).find('.close').on('click', function(e) {
			var li = e.target.parentNode;
			li.parentNode.removeChild(li);
		});

		//Toogle on label click
		jQuery(parent).find('.groups>li>label').on('click', function(e) {
			var li = e.target.parentNode;
			processGroupMinimize(li);
		});

		jQuery(parent).find('.minimize').on('click', function(e) {
			var li = e.target.parentNode;
			processGroupMinimize(li);
		});
	}

	function processGroupMinimize(li) {
		var label = jQuery(li).find('>label');
		if ('disable' == label[0].className) {
			var ititle = jQuery(li).find('input[data-role="title"]');
			var title = 'Some social website';
			if (ititle) {
				title = ititle.val();
			}
			label[0].innerText = title;
			jQuery(li).find('>.row').each(function(){
				jQuery(this).slideUp(500);
			});
		} else {
			jQuery(li).find('>.row').each(function(){
				jQuery(this).slideDown(500);
			});
		}
		jQuery(label[0]).toggleClass('disable');
	}

	jQuery(document).ajaxSuccess(function(e, xhr, settings) {
		if (undefined !== settings.data) {
			var action = settings.data.match(/action=(.+?)($|&)/);
			var isdel = settings.data.match(/delete_widget=(.+?)($|&)/);
			var addnew = settings.data.match(/add_new=(|.+?)($|&)/);
			var wid = settings.data.match(/widget-id=(.+?)($|&)/);
			if (action && 'save-widget' === action[1] && (!isdel || '1' !== isdel[1]) && '' === addnew[1]) {
				initWidget(jQuery('div[id*="'+wid[1]+'"]').find('.widget-content')[0], true); // true, because there might be assigned values
			}
			if (action && 'cws_ajax_sc_settings' === action[1]) {
				var parent = document.getElementsByClassName('cws_tb_modal_window')[0];
				initWidget(jQuery(parent).find('.row').parent()[0]);
			}
		}
	});

	jQuery('#post-formats-select input').change(function(e){
		var sel = e.target.value;
		var tab = jQuery('.cwsfw_controls [data-tabkey="'+sel+'"]');
		jQuery('.cwsfw_controls [data-tabkey]').addClass('closed')
		jQuery('.cwsfw_controls a').removeClass('active');
		if (tab.length) {
			tab.toggleClass('closed');
			jQuery('.cwsfw_controls a[data-tab="'+sel+'"]').addClass('active');
		}
	});

	jQuery('#post-formats-select input:checked').change();

	function getGSelection(sc_str) {
		var shortcode = wp.shortcode.next( 'gallery', sc_str );

		var defaultPostId = wp.media.gallery.defaults.id,
			attachments, selection;

		var selection = null;

		if ( shortcode) {
			shortcode = shortcode.shortcode;

			if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
				shortcode.set( 'id', defaultPostId );

			attachments = wp.media.gallery.attachments( shortcode );

			selection = new wp.media.model.Selection( attachments.models, {
				props: attachments.props.toJSON(),
				multiple: true
			});

			selection.gallery = attachments.gallery;

			selection.more().done(function () {
				// Break ties with the query.
				selection.props.set({ query: false });
				selection.unmirror();
				selection.props.unset('orderby');
			});
		}
		return selection;
	}

	var cws_frame;

	function initSelectImage(parent) {
		jQuery(parent).find('a.pb-gmedia-cws-pb').on('click', function(e) {
			e.preventDefault();
			var parent = e.target.parentNode;
			var input = parent.getElementsByTagName('input');

			var selection = getGSelection(input[0].value);

			var state = selection ? 'gallery-edit' : 'gallery-library';

			if (!cws_frame) {
				cws_frame = wp.media({
					// Set the title of the modal.
					id:				'cws-frame',
					frame:		'post',
					state:		state,
					title:		wp.media.view.l10n.editGalleryTitle,
					editing:	true,
					multiple:	true,
					selection: selection,

					// Tell the modal to show only images.
					library: { type: 'image' },

					// Customize the submit button.
					button: {	text: 'update',
						close: true
					}
				});
			} else {
				cws_frame.setState(state); // !!! options.state is not an option
				cws_frame.options.selection = selection;
			}
			cws_frame.open();
			cws_frame.on( 'update', function( selection ) {
				input[0].value = wp.media.gallery.shortcode( selection ).string();
				updateGalleryImages(selection.toArray(), parent);
			});
		});

		function updateGalleryImages(sel_arr, parent) {
			var cws_gallery = parent.parentNode.getElementsByClassName('cws_gallery')[0];
			if (cws_gallery) {
				cws_gallery.parentNode.removeChild(cws_gallery);
			}
			var images_html = '<div class="cws_gallery">';
			for (var i = 0; i < sel_arr.length; i++) {
				images_html += '<img src="' + sel_arr[i].attributes.url + '">'
			};
			images_html += '<div class="clear"></div></div>';
			parent.insertAdjacentHTML('afterend',images_html);
		}

		jQuery(parent).find('a.pb-media-cws-pb').on('click', function() {
			var that = this;
			var media_editor_attachment_backup = wp.media.editor.send.attachment;
			wp.media.editor.send.attachment = function(props, attachment) {
				var row = that.parentNode.parentNode;
				var url, thumb;
				switch (attachment.type) {
					case 'image':
						url = attachment.sizes.full.url;
						thumb = (attachment.sizes[props['size']].url || url);
						break;
					case 'video':
						url = attachment.url;
						thumb = attachment.image.src;
						break;
				}
				row.querySelector('input[data-key="img"]').value = url;
				row.getElementsByTagName('img')[0].src = thumb;
				row.querySelector('input[data-key="img-id"]').value = attachment.id;

				jQuery(row.querySelector('input[data-key="img"]')).trigger('change'); // for customizer
				jQuery(that).toggle(300);
				jQuery(row).find('a.pb-remov-cws-pb').toggle(300);
				wp.media.editor.send.attachment = media_editor_attachment_backup;
				return;
			}
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				console.log(html);
			}
			if (undefined !== this.dataset.media) {
				wp.media.editor.open(this, {library: {type:this.dataset.media}});
			} else {
				wp.media.editor.open(this);
			}
			return false;
		});

		jQuery(parent).find('a.pb-remov-cws-pb').on('click', function(el) {
			var parent = jQuery(el.target).parent();
			parent.find('input[data-key="img"]').attr('value', '');
			jQuery(parent.find('input[data-key="img"]')).trigger('change'); // for customizer
			parent.find('input[data-key="img-id"]').attr('value', '');
			parent.find('img').attr('src', '');
			jQuery(this).hide(300);
			parent.find('a.pb-media-cws-pb').show(300);
		});

	}

});
