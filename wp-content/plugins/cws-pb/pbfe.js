(function ($) {
"use strict";

var col_ctemplate = '<a title="pref column" class="pref" href="#"></a>';

var grid_ctemplate = 	'<a title="del grid" class="del" href="#"></a>' +
						'<a title="add grid" class="add" href="#"></a>' +
						'<a title="pref grid" class="pref" href="#"></a>' +
						'<a title="clone grid" class="clone" href="#"></a>' +
						'<a title="drag grid" class="drag" href="#"></a>';

var grid_ctemplate_nd = '<a title="add grid" class="add" href="#"></a>' +
						'<a title="pref grid" class="pref" href="#"></a>' +
						'<a title="clone grid" class="clone" href="#"></a>';

var row_ctemplate = '<a title="del row" class="del" href="#"></a>' +
						'<a title="pref row" class="pref" href="#"></a>' +
						'<a title="clone row" class="clone" href="#"></a>' +
						'<a title="drag row" class="drag" href="#"></a>' +
						'<span class="nbsp"></span>' +
						'<a title="mark row" class="add2t" href="#"></a>';

var widget_ctemplate = '<a title="del widget" class="del" href="#"></a>' +
						'<a title="pref widget" class="pref" href="#"></a>' +
						'<a title="clone widget" class="clone" href="#"></a>' +
						'<a title="drag widget" class="drag" href="#"></a>' +
						'<span class="nbsp"></span>' +
						'<a title="pref col" class="pref" href="#"></a>';

var widget_tabs = 	'<a title="drag widget" class="drag" href="#"></a>' +
						'<a title="clone widget" class="clone" href="#"></a>' +
						'<a title="pref widget" class="pref" href="#">*</a>' +
						'<a title="del widget" class="del" href="#">x</a>' +

						'<span class="nbsp"></span>' +
						'<a title="pref_tab" class="pref_tab" href="#">*</a>' +
						'<a title="add_tab" class="add_tab" href="#">+</a>' +
						'<a title="del_tab" class="del_tab" href="#">-</a>';

//var cols_lu = [12:'1', 6:'2', 4:'3', 3:'4'];

window.cwsfe.dlgs = [];
window.cwsfe.g_li = {}; // use it as a temporary params storage
window.processEvntInputOptionsLvl = 0;
var w_counter = 0;
window.g_cws_pb = [];
var w_local = 0;
var pid = getPageId();

function getPageId() {
	var a = document.getElementsByTagName('body')[0].className;
	var addendum = 0;
	var ind = a.indexOf('page-id');
	if ( ind != -1 ){
		addendum = 8;
	}	else {
		 ind = a.indexOf('postid');
		 addendum = 7;
	}
	return a.substr(ind+addendum, a.indexOf(' ', ind)-ind-addendum);
}

var unsaved = false;

function cws_confirmLeave(e) {
	var message = null;
	if (unsaved) {
		message = "There're possibly unsaved changes on this page.\nAre you sure you want to leave this page?";
	}
	console.log(unsaved);
	return message;
};

window.onbeforeunload = cws_confirmLeave;

function Atts2Params(atts) {
	var r_params = FlattenParams(JSON.parse(atts), '');
}

function FlattenParams(params, prefix) {
	var r_keys = Object.keys(params);
	var ret = {};
	for (var i=0;i<r_keys.length;i++){
		if ('object' === typeof params[r_keys[i]]) {
			var pref = prefix.length > 0 ? prefix + '[' + r_keys[i] + ']' : r_keys[i];
			ret = Object.assign(ret, FlattenParams(params[r_keys[i]], pref));
		} else if (prefix.length > 0) {
			ret[prefix + '[' + r_keys[i] + ']'] = params[r_keys[i]];
		} else {
			ret[r_keys[i]] = params[r_keys[i]];
		}
	}
	return ret;
}

	$(document).ready(function(e) {
		/*// Prevent jQuery UI dialog from blocking focusin
		$(document).on('focus focusout', function(e) {
			//e.stopImmediatePropagation();
			if ($(e.target).find(".media-modal").length) {
				e.stopImmediatePropagation();
			}
			console.log(e.type);
			console.log($(e.target));
		});

		$(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window, .mce-panel, .media-modal").length) {
				e.stopImmediatePropagation();
			} else {
				//console.log($(e.target));
			}
		});*/
		$('#cwspb_lower_panel a,#cwspb_lower_panel_left a').on('click', lowerPanelAdd);
		$('#cws_content_wrap .controls button').on('click', sidePanelApply);

		initDragModules($('#cws_content_wrap #bd .elements_panel ul.modules'), '.widget_wrapper');
		initDragModules($('#cws_content_wrap #bd .elements_panel ul.templates'), 'main');

		//initSort($('main'));

		var cont_height = $('#cws_content_wrap').height();
		$('#cws_content_wrap #bd').height(cont_height - 60);

		var pageid = document.body.className.match(/(?:page-id|postid)-(\d+)/);
		if (pageid) {
			getRawPageContent( pageid[1] ); // wp-preview-76
		} else {
			return;
		}

		for (var key in window.cwsfe.cols) {
			window.cwsfe.cols[key] = htmlDecode(window.cwsfe.cols[key]);
		}
		for (var key in window.cwsfe.icols) {
			window.cwsfe.icols[key] = htmlDecode(window.cwsfe.icols[key]);
		}
		for (var key in window.cwsfe.widgets) {
			window.cwsfe.widgets[key] = htmlDecode(window.cwsfe.widgets[key]);
		}

		for (var key in window.cwsfe.templates) {
			window.cwsfe.templates[key] = htmlDecode(window.cwsfe.templates[key]);
		}

		readyInit();

		$('#cws_content_wrap li>span').on('click', function(e){
			onSidePanelSpanClick(e);
		});

		$('#cws_content_wrap ul.parents').on('click', function(e){
			$('#cws_content_wrap ul.parents').addClass('minimized');
			$(this).removeClass('minimized');
		});

		/* group add */
		if (undefined !== window.cws_groups) {
			var i = 0;
			for (var key in window.cws_groups) {
				if (window.cws_groups.hasOwnProperty(key)) {
					group = JSON.parse(window.cws_groups[key]);
					window.cws_groups[key] = addGroup(group, key);
					i++;
				}
			}
		}

		function addGroup(group, key) {
			var parent = $('.row.group.' + key)[0];

			if (undefined === parent) return;
			var cloneable = $(parent).hasClass('cloneable') ? '<div class="clone"></div>' : '';
			var textarea = $(parent).find('script[data-templ="group_template"]');
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
					//var curr_title = (undefined !== group[gkey].title) ? group[gkey].title : 'Untitled';
					template = '<li><label class="disable"></label><div class="close"></div><div class="minimize"></div>' + cloneable + template0.replace(/%d/g, '' + k) + '</li>';
					if (last_li) {
						last_li.insertAdjacentHTML('afterend', template);
					} else {
						ul.insertAdjacentHTML('afterbegin', template);
					}
					last_li = ul.getElementsByTagName('li')[i];

					for (var item in group[gkey]) {
						if (group[gkey].hasOwnProperty(item)) {

							var group_prefix = mb_prefix + key + '[' + k + '][' + item + ']'; // index, because numbers can be deleted and saved as 0,3,4,6
							var input = $(parent).find('input[name="' + group_prefix +'"],select[name="' + group_prefix +'"]')[0];
							// value = group[gkey]
							switch (input.type) {
								case 'text':
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
					}
					k++;
					processGroupMinimize(last_li); // minimize them on start
					//initWidget(last_li);
				}
			}
			return k;
		}

		$('.row.group>div>button').on('click', function(e) {
			var parent = e.target.parentNode;
			addGroupItem(jQuery(parent).closest('.row_options'));
		});

		addAdminBarButton();

		var frame;
		var editor;

		jQuery('.row a.cwsfe_switch').on('click', function(e) {
			var mode = e.target.dataset['mode'];
			var parent = $(e.target).closest('.row').parent();
			var html;
			var textarea = parent.find('.wp-editor-area')[0]; // !!! we assume there wont be more than one tmce on the form
			var qttb = parent.find('.quicktags-toolbar');

			switch (mode) {
				case 'tmce':
					if (!qttb.length) {
						var qt = quicktags(window.tinyMCEPreInit.qtInit[textarea.id]);
						quicktags({
							id: textarea.id,
							buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
						});
						QTags._buttonsInit();
					} else {
						qttb.show();
					}
					var iframe = parent.find('iframe[id^="'+textarea.id+'"]')[0];
					var editorHeight = iframe ? parseInt(iframe.style.height, 10) : 0;
					textarea.style.height = editorHeight + 'px';
					html = window.switchEditors.pre_wpautop(tinymce.editors[textarea.id].getContent({format: 'html'}));
					parent.find('.cws-pb-tmce .mce-panel').hide();
					parent.find('.cws-pb-tmce .wp-editor-area').show();
					textarea.value = html;
					e.target.dataset['mode'] = 'html';
					e.target.innerHTML = 'Switch to Visual';
					break;
				case 'html':
					html = textarea.value;
					qttb.hide();
					tinymce.editors[textarea.id].setContent(html, {format: 'html'})
					parent.find('.cws-pb-tmce .mce-panel').show();
					parent.find('.cws-pb-tmce .wp-editor-area').hide();
					e.target.dataset['mode'] = 'tmce';
					e.target.innerHTML = 'Switch to Text';
					break;
			}
			e.stopPropagation();
			e.preventDefault();
		});

		jQuery('.row a.cwsfe_add_media').on('click', function(e) {
			window.g_cwspb_dialog.parentNode.style['display'] = 'none';

			wp.media.editor.open = function(id, options) {
				var workflow;

				options = options || {};

				id = this.id( id );
				this.activeEditor = id;

				workflow = this.get( id );

				// Redo workflow if state has changed
				if ( ! workflow || ( workflow.options && options.state !== workflow.options.state ) ) {
					workflow = this.add( id, options );
				}

				wp.media.frame = workflow;

				workflow.on('close', function(){
					window.g_cwspb_dialog.parentNode.style['display'] = 'block';
				});

				return workflow.open();
			}

			wp.media.editor.open( tinymce.activeEditor.id, {
				frame: 'post',
				state: 'insert',
				title: wp.media.view.l10n.addMedia,
				multiple: true,
			});
		});

/*		$(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window").length) {
				e.stopImmediatePropagation();
			}
		});*/
		console.log('dom ready');
/*		window.setTimeout(function(){
			$('#cws_content_wrap #bd textarea[id^="wp-editor-area"]').each(function(){
				initTmce(this);
			});
		}, 1000);*/
/*		window.setTimeout(function(){
			$('#cws_content_wrap #bd textarea[id^="wp-editor-area"]').each(function(){
				tinymce.remove('textarea#' + this.id);
			});
		}, 8000);*/
	}); // end of ready

	function readyInit() {
		initWidget($('#cws_content_wrap'), false);
/*		$('#cws_content_wrap #bd>div[id^="cwspbfe"]').each(function(k,i){
			var key = this.id.substr(8);
		});*/
	}

	function initDialog(key) {
		var iret = false;
		var that = $('#cws_content_wrap #bd>div[id="cwspbfe-'+key+'"]');
		if (that.length) {
			console.log('init dialog: ' + key);
			var buttons = {
					'Preview': onDialogPreview,
					'Apply': onDialogApply,
					Cancel: function() {
						/*
						var cloned = $('#cwspb_lower_panel .cloned_d>div');
						cloned.find('input[type="radio"][data-checked="1"]').each(function(){
							this.checked = true;
						});
						cloned.data('cancelled', true);
						sidePanelApply(cloned, false);
						*/
						if (window.cwsfe_def_dialog.previewed) {
							sidePanelApply(that, false, window.cwsfe_def_dialog);
						}
						window.cwsfe_def_dialog = null;
						that.dialog( 'close' );
					}
				};
			if ('grid' === key || 'igrid' === key) {
				delete buttons['Preview'];
			}
			var this_s = that[0];
			window.cwsfe.dlgs[key] = that.dialog({
				modal: true,
				autoOpen: false,
				height: 'auto',
				minWidth: document.body.clientWidth/3,
				title: key,
				show: { effect: 'slideDown', duration: 300 },
				buttons: buttons,
				close: function() {
					$('#cwspb_lower_panel .cloned_d').empty();
					var node = window.cwsfe.dlgs[this.id.substr(8)].data('node');
					$(node).removeClass('selected');
					in_drag = false;
					window.cwsfe_params = null;
				},
				open: function(ui) {
					if (window.jQuery && window.jQuery.ui.dialog) {
						$(document).unbind("focusin.dialog");
					}

					//var this_s = ui.target;
					/*$(this_s).find('textarea[id^="wp-editor-area"]').each(function(){
						initTmce(this);
					});*/
					// init some global data
					in_drag = true; // just make sure no widget over event go through
					window.processEvntInputOptionsLvl = 0;
					window.cwsfe.g_li = {};
					window.g_cwspb_dialog = this_s;

					//$(this).closest('.ui-dialog').css('z-index', '1001');
					var tmce_textarea = this_s.querySelector('textarea[id^="wp-editor-area"]');
					if (tmce_textarea) { // not every option has tmce inside
						var id = tmce_textarea.id;
						var content = window.cwsfe.dlgs[this_s.id.substr(8)].data('node')[0].dataset['cont'];
						if (undefined !== content) {
							tmce_textarea.value = content;
							var tmce_timeout = (undefined === tinyMCE.editors[id].getDoc()) ? 1000 : 0;

							setTimeout(function() {
								tinyMCE.editors[id].setContent(content, {format: 'raw'});
								//tinyMCE.editors[id].selection.select(tinyMCE.editors[id].getBody(), true);
								//tinyMCE.editors[id].selection.collapse(false);
								//tinyMCE.editors[id].show();
								//tinyMCE.editors[id].focus();
							}, tmce_timeout);
						}
					}
					// need to check for params and apply them respectively
					var template = this_s.id.substr(8);
					var node = window.cwsfe.dlgs[template].data('node');
					var params = {};
					if ('tabs_one' === template) {
						params = node.find(window.cwsfe.tabs.selectors.tabs + '.active').data('params');
					} else if ('accs_one' === template) {
						params = node.find(window.cwsfe.accs.selectors.tabs + '.active').data('params');
					} else {
						params = node.data('params');
					}

					window.cwsfe_params = params;

					var res = buildAtts(params);// normalized params are in ras.n_params
					var row_columns = that.find('.row.columns');
					if (row_columns.length) {
						// this is for grid dialog with columns only
						var col_name = row_columns.find('input').attr('name');
						if (undefined !== col_name) {
							var columns = row_columns.find('.columns');
							columns.find('ul').remove();
							var col_types = row_columns.find('.col_types');
							var colnums = params[col_name];
							for (var i=0; i<colnums.length;i++){
								var ul_class = '.span' + ( colnums.charAt(i) === '1' ? '12' : colnums.charAt(i) );
								var ul = col_types.find(ul_class);
								if (ul.length) {
									ul.clone().appendTo(columns);
								}
							}
						}
					}

					that.find('>.row.group,>.cws_form_tab>.row.group').each(function(i, group){ // > for only one level of groups
						jQuery(group).find('ul.groups>li').remove();
						var key = jQuery(group).find('.cwsfe_group').data('key'); //  this need to be extended if we want to have groups inside groups, using prefix for example
						if (undefined !== res.n_params[key]) {
							for (var i=0; i<Object.keys(res.n_params[key]).length;i++) {
								processGroupMinimize(addGroupItem(jQuery(group)));
							}
						}
					});

					var temp_params = jQuery.extend({}, params);
					assignParams( that.find('.row_options input,.row_options select, .row_options textarea'), temp_params);

					window.cwsfe_def_dialog = sidePanelApply(that, false, {shortcode: ''});
					window.cwsfe_def_dialog.previewed = false;

					/*
					// cloning destroys all the radio button checked states so we have to copy it back from the cloned version
					jQuery('#cwspb_lower_panel .cloned_d input[type="radio"]:checked').each(function(){
						that.find('input[type="radio"][value="'+this.value+'"]')[0].checked = true;
						this.setAttribute('data-checked', '1'); // is this some sort of jquery bug?
						//that.find('input[type="radio"][value="'+this.value+'"]').prop('checked', true);
					});

					// here we need to change ids in cloned textareas, otherwise quicktags wouldn't work
					jQuery('#cwspb_lower_panel .cloned_d #' + that.attr('id') + ' textarea[id^="cwsfe_ta"]').each(function(){
						var id = this.id;
						this.removeAttribute('id');
						this.setAttribute('data-oldid', id);
					});
					*/
				}
			});

			that.find('textarea[id^="wp-editor-area"]').each(function(){
				initTmce(this);
			});

			if (undefined == this_s.dataset['w']) {
				this_s.dataset['w'] = w_counter;
			} else {
				w_local = this_s.dataset['w'];
			}
			g_cws_pb[w_local] = [{'e':[],'d':[]}];
			w_counter++;

			that.find('div.row select,div.row input').on('change', function(el){
				emptyGcwspb();
				processMbInputOptions(el.target, null, true);
			});

			that.find('.row.fai select').each(function(){
				jQuery(this).select2({
					allowClear: true,
					placeholder: " ",
					formatResult: addIconToSelectFa,
					formatSelection: addIconToSelectFa,
					escapeMarkup: function(m) { return m; }
				});
			});

			iret = true;
		}
		return iret;
	}

	function initTmce(textarea, test) {
		var href = window.location.href;
		if ('#' === href.substr(href.length-1, 1)) {
			// there's a bug in tinymce when there's # at the end of the url
			window.history.pushState("tmce_bug", "tmce_bug", href.substr(0, href.length-1));
		}
		var tb3 = getButtons(textarea.dataset['buttons']);
		tinymce.init({
			cwsbuttons: textarea.dataset['buttons'],
			//selector: '#' + textarea.id,
			mode: 'exact',
			elements: textarea.id,
			theme: 'modern',
			content_css: tinyMCEPreInit.mceInit['cwsfe_dummy'].content_css,
			resize: true,
			content_editable: true,
			force_br_newlines: false,
			force_p_newlines: false,
			forced_root_block: false,
			directionality: tinyMCEPreInit.mceInit['cwsfe_dummy'].directionality,
			language: tinyMCEPreInit.mceInit['cwsfe_dummy'].language,
			convert_urls: false,
			ie7_compat: false,
			inline: false,
			block_formats: tinyMCEPreInit.mceInit['cwsfe_dummy'].block_formats,
			formats: tinyMCEPreInit.mceInit['cwsfe_dummy'].formats,
			style_formats: tinyMCEPreInit.mceInit['cwsfe_dummy'].style_formats,
			//external_plugins: tinyMCEPreInit.mceInit['cwsfe_dummy'].external_plugins,
			plugins: tinyMCEPreInit.mceInit['cwsfe_dummy'].plugins,
			menubar: false,
			toolbar1: tinyMCEPreInit.mceInit['cwsfe_dummy'].toolbar1,
			toolbar2: tinyMCEPreInit.mceInit['cwsfe_dummy'].toolbar2,
			toolbar3: tb3,
			setup: tmceButtons,
		});
	}

	function check4tmce(timeout) {
		if(flag) {

			 window.setTimeout(check4tmce, 500); /* this checks the flag every 100 milliseconds*/
		} else {
			/* do something*/
		}
	}

	function addAdminBarButton() {
		var wptoolbar = jQuery('#wp-toolbar');
		if (wptoolbar.length) {
			var real_link = jQuery('link[rel="canonical"]').attr('href');
			wptoolbar.find('>ul:first').append('<li id="wp-admin-bar-cwsfe-save"><a class="ab-item" href="#">'+cwsfe.l10n.update+'</a></li>');
			wptoolbar.find('>ul:first').append('<li id="wp-admin-bar-cwsfe-view"><a class="ab-item" href="'+real_link+'" target="_blank">View Page</a></li>');

			wptoolbar.find('>ul>li#wp-admin-bar-cwsfe-save>a').on('click', function(){
				var content = savePage( jQuery('main .grid_row_content .cwsfe_row') );
				var id = document.body.className.match(/page-id-(\d+)/)[1];
				jQuery.ajax({
					type: 'post',
					async: true,
					dataType: 'text',
					url: ajaxurl,
					data: {
						action: 'cwsfe_ajax_update_page',
						nonce: window.cwsfe.nonce,
						content: content,
						id: id,
					},
					error: function(resp){
					},
					success: function(resp){
						if (!resp.length) {
							alert('Updated successfully');
						} else {
							console.log(resp);
						}
					}
				});
			});
		}
	}

	function savePage(rows) {
		var out = '';
		rows.each(function(r, el){
			out += '[cws-row';
			out += printAtts( jQuery(el).data() );
			out += ']';
			jQuery(el).find('>.cwsfe_grid').each(function(r, el){
				out += '[cws-grid';
				var grid_data = jQuery(el).data();
				out += printAtts( grid_data );
				var _cols = grid_data.params['_cols'];
				out += ']';
				jQuery(el).find('>.cwsfe_col').each(function(c, el){
					out += '[col';
					var span = _cols.charAt(c);
					if ('1' === span && 1 === _cols.length) {
						span = '12';
					}
					out += ' span=' + span;
					out += printAtts( jQuery(el).data() );
					out += ']';
					jQuery(el).find('>div>div>.cwsfe_widget').each(function(w, el){ // need to be carefull with these >div, ideally to pass .cols_wrapper>.widget_wrapper somehow
						out += SaveWidget(el);
					});
					out += '[/col]';
				});
				out += '[/cws-grid]';
			});
			out += '[/cws-row]';
		});
		//console.log(out);
		unsaved = false;
		return out;
	}

	function SaveWidget(el) {
		var out = '';
		var data = jQuery(el).data();
		out += '[cws-widget type='+data.type;
		out += printAtts( jQuery(el).data() );
		//var content = (undefined !== data.cont) ? data.cont.replace('\'','&#39;') : '';
		var content = data.cont;
		switch (data.type) {
			case 'tabs':
				out += ']';
				content = undefined;
				jQuery(el).find(window.cwsfe.tabs.selectors.tabs).each(function(t, this_tab){
					var this_id = this_tab.getAttribute('tabindex');
					out += '[item type=' + data.type + '-one';
					var tab_data = jQuery(this_tab).data();
					tab_data['params']['active'] = jQuery(this_tab).hasClass('active') ? '1' : '0';
					out += printAtts( tab_data );
					out += ']';
					var content = jQuery(el).find(window.cwsfe.tabs.selectors.content + '[tabindex="'+ this_id +'"]');
					out += printInnerWidgets(content, 'cws-widget-t');
					out += '[/item]';
				});
				out += '[/cws-widget]';
				break;
			case 'accs':
				out += ']';
				content = undefined;
				jQuery(el).find(window.cwsfe.accs.selectors.tabs).each(function(t, this_tab){
					out += '[item type=' + data.type + '-one';
					var tab_data = jQuery(this_tab).data();
					tab_data['params']['active'] = jQuery(this_tab).hasClass('active') ? '1' : '0';
					out += printAtts( tab_data );
					out += ']';
					var content = jQuery(el).find(window.cwsfe.accs.selectors.content)[t];
					out += printInnerWidgets(jQuery(content), 'cws-widget-t');
					out += '[/item]';
				});
				out += '[/cws-widget]';
				break;
			case 'igrid':
				out += ']';
				var _cols = data.params['_cols'];
				content = undefined;
				jQuery(el).find(window.cwsfe.igrid.selectors.tabs).each(function(t, this_tab){
					var span = _cols.charAt(t);
					if ('1' === span && 1 === _cols.length) {
						span = '12';
					}
					out += '[icol span=' + span + ']';
					var content = jQuery(el).find(window.cwsfe.igrid.selectors.content)[t];
					out += printInnerWidgets(jQuery(content), 'cws-widget-t');
					out += '[/icol]';
				});
				out += '[/cws-widget]';
				break;
			default:
				if (undefined !== content) {
					out += ']' + content;
					out += '[/cws-widget]';
				} else {
					out += ' /]';
				}
				break;
		}
		return out;
	}

	function printInnerWidgets(content, scname) {
		var ret = '';
		content.find('.cwsfe_widget').each(function(w, tab_w){
			var tdata = jQuery(tab_w).data();
			ret += '['+scname+' type='+tdata.type; //shortcodes inside cws-widget should have a different name
			ret += printAtts( jQuery(tab_w).data() );
			var tcontent = tdata.cont;
			if (undefined !== tcontent) {
				ret += ']' + tcontent;
				ret += '[/'+scname+']';
			} else {
				ret += ' /]';
			}
		});
		return ret;
	}

	function printAtts(data){
		var out = '';
		if (undefined !== data.params) {
			var res = buildAtts(data.params);
			out += res.atts;
		}
		return out;
	}

	function addGroupItem(parent) {
		var cloneable = parent.hasClass('cloneable') ? '<div class="clone"></div>' : '';
		var ul = parent.find('ul.groups');
		var i = ul.find('>li').length;
		var last_li = i ? ul.find('>li:last')[0] : null;
		var textarea = parent.find('script[data-templ="group_template"]');
		var template = textarea.html();
		var group_key = textarea.data('key');
		var current_id = ul.find('>li').length;
		/*if (undefined !== window.cws_groups && undefined !== window.cws_groups[group_key]) {
			current_id = window.cws_groups[group_key];
		} else {
			window.cws_groups = [];
		}*/
		template = '<li><label class="disable"></label><div class="close"></div><div class="minimize"></div>' + cloneable + template.replace(/%d/g, '' + current_id) + '</li>';
		if (last_li) {
			last_li.insertAdjacentHTML('afterend', template);
		} else {
			ul[0].insertAdjacentHTML('afterbegin', template);
		}
		last_li = jQuery(ul).find('>li:last')[0];
		initWidget(last_li, true);
		ul.find('.row.fai select').each(function(){
			jQuery(this).select2({
				allowClear: true,
				placeholder: " ",
				formatResult: addIconToSelectFa,
				formatSelection: addIconToSelectFa,
				escapeMarkup: function(m) { return m; }
			});
		});
		//window.cws_groups[group_key] = ++current_id;
		return last_li;
	}

	function initWidget(parent, bdAdded) {
		jQuery(document).on('focusin', function(e) {
			if ($(e.target).closest(".mce-window, .mce-panel").length) {
				e.stopImmediatePropagation();
			}
		});

		jQuery(parent).find('.row_options .image_select .cws_img_select_wrap').on('click', function(el){
			cwsfe_processRadioImg(el);
		});

		jQuery(parent).find('.cws_pb_ftabs a').on('click', function() {
			var old_tab = jQuery(this).parent().find('a[class="active"]');
			if (jQuery(this)[0] != old_tab[0]) {
				var parent = jQuery(this).parent().parent();
				jQuery(this).toggleClass('active');
				old_tab.toggleClass('active');
				parent.find('.cws_form_tab[data-tabkey="' + old_tab.data('tab')+'"]').toggleClass('closed');
				parent.find('.cws_form_tab[data-tabkey="' + jQuery(this).data('tab')+'"]').toggleClass('closed');
			}
		});

		jQuery(parent).find('.row.sortable').each(function(k, el){
			jQuery(el).find('ul.groups').sortable({
				items: '>li',
				handle: 'label',
				update: function( ev, ui ) {
					jQuery(ui.item.find('[name]')[0]).trigger('change');
				},
			});
			jQuery(el).find('ul.groups').disableSelection();
		});

		jQuery(parent).find('.row_options textarea').each(function(){
			var id = 'cwsfe_ta_' + Date.now();
			this.id = id;
			var qt = quicktags({
				id: id,
				buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
			});
			QTags._buttonsInit();
		});

		jQuery(parent).find('input[data-default-color]').each(function(){
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

		jQuery(parent).find('.row_options a[id="pb-media-cws-pb"]').on('click', function() {
			var that = this;
			var media_editor_attachment_backup = wp.media.editor.send.attachment;
			wp.media.editor.send.attachment = function(props, attachment) {
				var row = that.parentNode.parentNode;
				var key = that.dataset['key'];
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

				$(row).find('img').attr('src', thumb);
				if ($(row).find('input#img-cws-pb').length) {
					$(row).find('input#img-cws-pb').attr('value', url);
				} else if (row.getElementsByTagName('input').length) {
					// assign this image to the sibling input
					row.querySelector('input[id="'+key+'[row]"]').value = url;
					row.querySelector('input[id="'+key+'[size]"]').value = props['size'];
					var params = window.cwsfe.g_li;
					if (undefined === params[key]) {
						params[key] = {};
					}
					params[key]['id'] = attachment.id;
					params[key]['w'] = attachment.width;
					params[key]['h'] = attachment.height;
					params[key]['row'] = url;
					window.cwsfe.g_li = params;

					row.querySelector('input[id="'+key+'[id]').value = attachment.id;
					row.querySelector('input[id="'+key+'[id]').setAttribute('data-dim', String(attachment.width + ':' + attachment.height) );
				}
				$(that).toggle(300);
				$(row).find('a#pb-remov-cws-pb').toggle(300);
				wp.media.editor.send.attachment = media_editor_attachment_backup;
				return;
			}
			wp.media.editor.remove(this); // this will reset workflow, otherwise first launch settings will be used all the time
			if (undefined !== this.dataset.media) {
				wp.media.editor.open(this, {library: {type:this.dataset.media}});
			} else {
				wp.media.editor.open(this);
			}
		});

		/* gallery support */
		var cws_frame;

		jQuery(parent).find('a.pb-gmedia-cws-pb').on('click', function(e) {
			e.preventDefault();
			window.g_cwspb_dialog.parentNode.style['display'] = 'none';
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
			cws_frame.on( 'close', function() {
				window.g_cwspb_dialog.parentNode.style['display'] = 'block';
			});
			cws_frame.on( 'update', function( selection ) {
				input[0].value = wp.media.gallery.shortcode( selection ).string();
				updateGalleryImages(selection.toArray(), parent);
			});
		});


		jQuery(document).on('wplink-open', function(){ window.g_cwspb_dialog.parentNode.style['display'] = 'none'; });
		jQuery(document).on('wplink-close', function(){
			window.g_cwspb_dialog.parentNode.style['display'] = 'block';
			if (window.cwsfeActiveEditor) {
				var wpLink_attrs = wpLink.getAttrs();
				if (undefined !== wpLink_attrs.href)
				document.getElementById(window.cwsfeActiveEditor).value = wpLink_attrs.href;
			}
			window.cwsfeActiveEditor = null;
		});

		/* /gallery support */

		jQuery(parent).find('.row_options fieldset.cwsfe_gradient input').on('change input propertychange', function(e){
			var parent = $(e.target).closest('.cwsfe_gradient');
			var preview = parent.find('.preview');
			var orientation = $(parent).find('input[name$="orientation]"]').val();
			var s_color = $(parent).find('input[name$="s_color]"]').val();
			var e_color = $(parent).find('input[name$="e_color]"]').val();
			preview.css('background', 'linear-gradient('+orientation+'deg, '+s_color+' 0%,'+e_color+' 100%)')
		});

		jQuery(parent).find('.row_options input.wplink').each(function(){
			if (undefined === this.id || !this.id.length) {
				var length = 8;
				this.id = 'cwsfe-wplink-' + (Math.round((Math.pow(36, length + 1) - Math.random() * Math.pow(36, length))).toString(36));
			}
		});

		jQuery(parent).find('.row_options input.wplink').on('dblclick', function(el) {
			if ( typeof wpLink !== 'undefined' ) {
				window.cwsfeActiveEditor = this.id;
				wpLink.open( this.id );
				return;
			}
		});

		jQuery(parent).find('.row_options a[id="pb-remov-cws-pb"]').on('click', function(el) {
			var key = el.target.dataset['key'];
			$(el.target).parent().find('input[id="'+key+'[row]"]').attr('value', '');
			$(el.target).parent().find('img').attr('src', '');
			$(this).hide(300);
			$(el.target).parent().find('a#pb-media-cws-pb').show(300);

			// clean up params data
			var params = window.cwsfe.g_li;
			if (undefined !== params[key]) {
				delete params[key];
			}
			window.cwsfe.g_li = params;
		});

		jQuery(parent).find('.close').on('click', function(e) {
			var li = e.target.parentNode;
			li.parentNode.removeChild(li);
		});

		jQuery(parent).find('.clone').on('click', function(e) {
			var parent = jQuery(e.target).closest('.row_options');
			var _data = jQuery(e.target).closest('li').find('.row_options:not([class*="disable"]) [name]').filter(function(id, val){
				return !jQuery(val).closest('.row_options').hasClass('disable');
			});
			var rObj = processForm2Param(_data);
			addGroupItem(parent);
			var last_li = parent.find('li[data-w]:last');
			var current_id = parent.find('li[data-w]').length - 1;
			// now we need to update ids in rObj to the new id
			// again, this will not work in multi-level groups
			var r_keys = Object.keys(rObj);
			for (var i=0;i<r_keys.length;i++){
				var new_name = r_keys[i].replace(/\[\d+\]/, '['+current_id+']');
				rObj[new_name] = rObj[r_keys[i]];
				delete rObj[r_keys[i]];
			}
			assignParams( last_li.find('.row_options input,.row_options select,.row_options textarea'), rObj);
			processGroupMinimize(last_li);
		});

		jQuery(parent).find('.minimize').on('click', function(e) {
			var li = e.target.parentNode;
			processGroupMinimize(li);
		});

		if (bdAdded) {
			var w_local = w_counter;
			if (undefined == parent.dataset['w']) {
				parent.dataset['w'] = w_counter;
			} else {
				w_local = parent.dataset['w'];
			}
			g_cws_pb[w_local] = [{'e':[],'d':[]}];
			w_counter++;

			jQuery(parent).find('.row_options input,.row_options select').on('change', function(el){
				emptyGcwspb();
				processMbInputOptions(el.target, null, true);
			});

			/*
			jQuery(parent).find('.row_options input,.row_options select').each(function(e, k){
				emptyGcwspb();
				processMbInputOptions(k, null, true);
			});
			*/
			//assignParams( jQuery(parent).find('.row_options input,.row_options select,.row_options textarea'), window.cwsfe_params);
		}

		if (jQuery(parent).find('.row.columns').length) {
			var sortableIn = 0;
			jQuery(parent).find('.row div.columns').sortable({
				items: 'ul',
				revert: true,
				update: function(ev, ui) {
					ui.item.attr('style', '');
				},
			}).droppable({drop: function ( event, ui ) { jQuery(this).closest('.cwsfe_grid').removeClass('empty_widget');}});

			jQuery(parent).find('.row.columns div.del').droppable({
					drop: function ( event, ui ) {
						ui.draggable.remove();
					}
			});

			jQuery(parent).find('div.col_types ul').draggable({
				revert: 'invalid',
				cursor: 'move',
				placeholder: 'ui-draggable-dragging',
				containment: 'document',
				opacity: 0.7,
				connectToSortable: jQuery(parent).find('div.columns'),
				helper: 'clone',
				start: function(ev, ui) {
					ui.helper[0].style.width = ui.helper.context.clientWidth + 'px';
				},
			});
		}

	} // end of initWidget

	function addIconToSelectFa(icon) {
		if ( icon.hasOwnProperty( 'id' ) && icon.id.length > 0 ) {
			return "<span><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text.toUpperCase() + "</span>";
		} else {
			return icon.text;
		}
	}

	function cwsfe_processRadioImg(el) {
		var ul_parent = $(el.target).closest('.cws_image_select');
		ul_parent.find('li.checked').toggleClass('checked');
		$(el.target).closest('li').toggleClass('checked');
		var t_input = el.target.parentNode.getElementsByTagName('input')[0];
		t_input.checked = true;
		$(t_input).trigger('change');
	}

	var getButtons = function(buttons) {
		var ret = '';
		if (undefined !== buttons) {
			buttons = JSON.parse(buttons);
			var button_keys = Object.keys(buttons);
			for (var i = 0; i < button_keys.length; i++) {
				ret += i ? ',' : '';
				ret += button_keys[i];
			}
		}
		return ret;
	}

	var tmceButtons = function(ed) {
		var buttons = ed.settings.cwsbuttons;
		if (undefined !== buttons) {
			buttons = JSON.parse(buttons);
			var button_keys = Object.keys(buttons);
			for (var i = 0; i < button_keys.length; i++) {
				var action = buttons[button_keys[i]].action;
				var func;
				switch (action[0]) {
					case 'insert':
						var text = action[1];
						var is_selection = text.indexOf('%selection%');
						if (-1 !== is_selection) {
							text = text.split('%selection%');
							func = function() {
								var selection = ed.selection.getContent();
								ed.insertContent(text[0] + selection + text[1]);
							}
						} else {
							func = function() {
								ed.insertContent(text);
							}
						}
						break;
				}

				ed.addButton(button_keys[i], {
					title: buttons[button_keys[i]].title,
					icon: 'cwsfe-'+button_keys[i],
					onclick: func,
				});
			}
		}
	}

	var htmlDecode = function(input) {
		var doc = new DOMParser().parseFromString(input, "text/html");
		return doc.documentElement.textContent;
	}

	var getRawPageContent = function(id) {
		jQuery.ajax({
			type: 'post',
			async: true,
			dataType: 'text',
			url: ajaxurl,
			data: {
				action: 'cwsfe_ajax_get_raw_page',
				nonce: window.cwsfe.nonce,
				id: id
			},
			error: function(resp){},
			success: function(resp){
				unsaved = false;
				var data = JSON.parse(resp);
				var lower_panel = $('main #cwspb_lower_panel');

				var row = $(data.render).insertBefore(lower_panel);

				InitRow(row);

				if ('function' === typeof window.jQuery.fn.cws_pageBuilder_loader) {
					window.jQuery.fn.cws_pageBuilder_loader();
				}
			}
		});
	}

	var getElParams = function(el) {
		var ret = {};
		var atts = undefined !== el.dataset['atts'] ? el.dataset['atts'] : '';
		if (atts.length > 0) {
			atts = atts.split('%22;').join('\\"');
			var params;
			try {
				params = JSON.parse(atts);
			} catch(e) {
				debugger
			}
			ret = FlattenParams(params, '');
		}
		return ret;
	}

	function InitRow(row) {
		restoreAtts(row);
		row.on('mouseenter', onRowOver);
		row.on('mouseleave', onRowLeave);
		row.find('.cwsfe_widget').on('mouseenter', onWidgetOverEvent);
		row.find('.cwsfe_widget').on('mouseleave', onRowLeave);
		initDrop(row.find('.widget_wrapper,'+window.cwsfe.tabs.selectors.content+','+window.cwsfe.accs.selectors.content+','+window.cwsfe.igrid.selectors.content));
		initSortModules(row.find('.widget_wrapper,'+window.cwsfe.tabs.selectors.content+','+window.cwsfe.accs.selectors.content+','+window.cwsfe.igrid.selectors.content));

		initSortModulesRow();
	}

	function restoreAtts(row) {
		row.each(function(r, el){
			if (!jQuery(el).hasClass('cwsfe_row')) {
				el = jQuery(el).find('.cwsfe_row')[0];
			}
			jQuery(el).data('params', getElParams(el));
			jQuery(el).find('>.cwsfe_grid').each(function(r, el){
				if(jQuery(el).find('.widget_wrapper').is(':empty')){
					jQuery(el).addClass('empty_widget');
				}
				jQuery(el).data('params', getElParams(el));
				jQuery(el).find('>.cwsfe_col').each(function(c, el){
					jQuery(el).data('params', getElParams(el));
					jQuery(el).find('>div>div>.cwsfe_widget').each(function(w, el){
						restoreWidget(el);
					});
				});
			});
		});
	}

	function restoreWidget(el) {
		var data = jQuery(el).data();
		var content = null;
		switch (data.type) {
			case 'tabs':
				jQuery(el).data('params', getElParams(el));
				jQuery(el)[window.cwsfe.tabs.init]();
				jQuery(el).find(window.cwsfe.tabs.selectors.tabs).each(function(t, this_tab){
					var this_id = this_tab.getAttribute('tabindex');
					jQuery(this_tab).data('params', getElParams(this_tab));
					content = jQuery(el).find(window.cwsfe.tabs.selectors.content + '[tabindex="'+ this_id +'"]');
					jQuery(content).find('.cwsfe_widget').each(function(w, tab_w){
						jQuery(tab_w).data('params', getElParams(tab_w));
					});
				});
				break;
			case 'accs':
				jQuery(el).data('params', getElParams(el));
				jQuery(el)[window.cwsfe.accs.init]();
				jQuery(el).find(window.cwsfe.accs.selectors.tabs).each(function(t, this_tab){
					jQuery(this_tab).data('params', getElParams(this_tab));
					content = jQuery(el).find(window.cwsfe.accs.selectors.content)[t];
					jQuery(content).find('.cwsfe_widget').each(function(w, tab_w){
						jQuery(tab_w).data('params', getElParams(tab_w));
					});
				});
				break;
			case 'igrid':
				jQuery(el).data('params', getElParams(el));
				//jQuery(el)[window.cwsfe.accs.init]();
				jQuery(el).find(window.cwsfe.igrid.selectors.tabs).each(function(t, this_tab){
					jQuery(this_tab).data('params', getElParams(this_tab));
					content = jQuery(el).find(window.cwsfe.igrid.selectors.content)[t];
					jQuery(content).find('.cwsfe_widget').each(function(w, tab_w){
						jQuery(tab_w).data('params', getElParams(tab_w));
					});
				});
				break;
			default:
				if (content) {
					content.find('.cwsfe_widget').each(function(w, tab_w){
						jQuery(tab_w).data('params', getElParams(tab_w));
					});
				} else {
					jQuery(el).data('params', getElParams(el));
				}
				break;
		}
	}

	var getTagEnd = function(str, needle) {
		var io = str.indexOf(needle);
		return str.indexOf('>', io+1)+1;
	}

	var initDragModules = function(parent, connectTo) {
		$('li', parent).draggable({
			revert: true,
			revertDuration: 0,
			cursor: 'move',
			placeholder: 'ui-draggable-dragging',
			containment: 'document',
			opacity: 0.7,
			cursorAt:{top:5,left:5},
			connectToSortable: connectTo,
			//stack:
			create: function (ev) {
				unsaved = true;
				if (undefined !== this.dataset['m'] && window.cwsfe.widgets[this.dataset['m']].length === 0) {
					jQuery(this).draggable('disable');
				}
			},
			stop: function(ev, ui) {
				ui.helper.each(function(){
					this.removeAttribute('style');
					jQuery(this).css({"opacity":"0"});
				});
				in_drag = false;
			},
			helper: function(ev, ui) {
				in_drag = true;
				var out = '';
				if (undefined !== this.dataset['m']) {
					var dt = window.cwsfe.widgets[this.dataset['m']].indexOf('data-type');
					out = window.cwsfe.widgets[this.dataset['m']].substr(0,dt) + ' data-cwsfetemp="" ' + window.cwsfe.widgets[this.dataset['m']].substr(dt);
				}
				if (undefined !== this.dataset['t']) {
					out = window.cwsfe.templates[this.dataset['t']];
					out = '<div class="cwsfetemplatedrag">' + out + '</div>';
					out = jQuery(out).clone();
				}
				return out;
			},
		});
	}

	var initDrop = function(ul) {
		ul.droppable({
			accept: '#cws_content_wrap #bd .elements_panel ul>li,main .cwsfe_widget, .tab_section, .accordion_content, .igrid_content, main',
			/*drop: function(ev, ui) {
				ui.draggable.empty();
				return ui.draggable.html($('<li><p>' + ui.draggable.data('m') + ' module</p></li>'));
			},*/
			drop: function(ev, ui) {
				jQuery(ui.helper).mouseup(function(eventObject){
					jQuery(this).closest('.cwsfe_grid').removeClass('empty_widget');
				});
			},
		});
	}

	var initSortModulesRow = function() {
		var row_parent = $('.grid_row_content:first-child').parent();
		row_parent.sortable({
			cursorAt: { left: 50, top: 45 },
			revert: true,
			items: '.grid_row_content,.cwsfetemplatedrag',
			appendTo: 'main',
			axis: 'y',
			delay: 150,
			//scroll: false,
			//containment: 'main',
			tolerance: 'intersect',
			helper: 'clone',
			//cursorAt: { top:1, left:350 },
			placeholder: 'ui-sortable-placeholder-row grid_row',
			handle: '.cwspb_controls a.drag',
			start: function(e, ui){
				//unsaved = true;
				ui.placeholder.height(ui.item.height());
				//ui.placeholder.width(ui.item.width());
				//ui.helper.css({left: 352});
			},
			stop: function(ev, ui) {
				// when received from connected draggable
				// sorting in connection with dragging creates unnecessary inline styles
				if (ui.item.hasClass('cwsfetemplatedrag')) {
					ui.item.find('.grid_row_content').each(function(){
						var styles = this.getAttribute('data-style');
						if ('null' !== styles) {
							this.setAttribute('style', styles);
						}
						this.removeAttribute('data-style');
						var $this = $(this);
						InitRow($this);
						$this.removeClass('cwsfetemplate');
					});
					ui.item.find('.grid_row_content').unwrap();
				}
				console.log('stop row');
			},
		});

		row_parent.children('.grid_row_content').sortable({
			revert: true,
			items: '.cwsfe_grid',
			axis: 'y',
			tolerance: 'intersect',
			connectWith: '.grid_row_content',
			placeholder: 'ui-sortable-placeholder',
			handle: '.cwspb_controls a.drag',
		});
	}

	var in_drag = false;

	var initSortModules = function(ul) {
		var is_cancel = false;
		ul.sortable({
			revert: true,
			items: '.cwsfe_widget',
			handle: '.cwspb_controls a.drag',
			tolerance: 'pointer',
			cursorAt:{top:5,left:5},
			//scroll: false,
			forcePlaceholderSize: true,
			connectWith: '.widget_wrapper, .tab_section, .accordion_content, .igrid_content', //, .tab_section
			placeholder: 'ui-sortable-placeholder',
			start: function(e, ui){
				unsaved = true;
				ui.helper.css('margin-top', $(window).scrollTop() );
				ui.item.find('.cwspb_controls').remove();
				ui.placeholder.height(ui.item.height());
				in_drag = true;
			},
			deactivate: function(ev, ui) {
				in_drag = false;
			},
			change: function(ev, ui) {
				var type = ui.item[0].dataset['type'];
				is_cancel = false;
				if ('tabs' === type || 'accs' === type || 'igrid' === type) {
					var parent = ui.placeholder.parent();
					if (parent.hasClass('tab_section') || parent.hasClass('accordion_content') || parent.hasClass('igrid_content') ) {
						is_cancel = true;
					}
				}
			},
			beforeStop: function(ev, ui) {
				var type = ui.item[0].dataset['type'];
				is_cancel = false;
				if ('tabs' === type || 'accs' === type || 'igrid' === type ) {
					var parent = ui.placeholder.parent();
					if (parent.hasClass('tab_section') || parent.hasClass('accordion_content') || parent.hasClass('igrid_content') ) {
						is_cancel = true;
					}
				}
			},
			stop: function(ev, ui) {
				// when received from connected draggable
				// sorting in connection with dragging creates unnecessary inline styles
				if (is_cancel) {
					$(this).sortable('cancel');
					ui.item.remove();
					is_cancel = false;
				} else {
					ui.item[0].removeAttribute('style');
					if (undefined !== ui.item[0].dataset['cwsfetemp']) {
						initModulesEvents(ui.item);
						ui.item[0].removeAttribute('data-cwsfetemp');
						$(this).sortable('refresh');
					}
				}
				in_drag = false;
			},
		});
	}

	var initModulesEvents = function(el) {
		var type = el[0].dataset['type'];
		if (undefined !== window.cwsfe.widget_atts[type]) {
			el.data('params', window.cwsfe.widget_atts[type]);
		}
		switch (type) {
			case 'igrid':
				var tabs = el.find(window.cwsfe.igrid.selectors.content).parent();
				tabs.find('.cwsfe_widget').on('mouseenter', onWidgetOverEvent);
				tabs.find('.cwsfe_widget').on('mouseleave', onRowLeave);
				initDrop(el.find(window.cwsfe.igrid.selectors.content));
				initSortModules(el.find(window.cwsfe.igrid.selectors.content));
				break;
			case 'tabs':
				el[window.cwsfe.tabs.init]();
				var tabs = el.find(window.cwsfe.tabs.selectors.content + ':first').parent();
				if (undefined !== window.cwsfe.widget_atts['tabs_one']) {
					el.find(window.cwsfe.tabs.selectors.tabs).data('params', window.cwsfe.widget_atts['tabs_one']);
				}
				tabs.find('.cwsfe_widget').on('mouseenter', onWidgetOverEvent);
				tabs.find('.cwsfe_widget').on('mouseleave', onRowLeave);
				initDrop(el.find(window.cwsfe.tabs.selectors.content));
				initSortModules(el.find(window.cwsfe.tabs.selectors.content));
				// assign initial tab attributes
				// make the tabs movable accross their place
				el.find(window.cwsfe.tabs.selectors.tabs).parent().sortable({
					items: window.cwsfe.tabs.selectors.tabs,
					start: function(e, ui){	in_drag = true; $('.cwspb_controls').remove(); },
					stop: function(e, ui){ in_drag = false },
				});
			break;
			case 'accs':
				el[window.cwsfe.accs.init]();
				var tabs = el.find(window.cwsfe.accs.selectors.content + ':first').closest('.cwsfe_widget');
				if (undefined !== window.cwsfe.widget_atts['accs_one']) {
					el.find(window.cwsfe.accs.selectors.tabs).data('params', window.cwsfe.widget_atts['accs_one']);
				}
				tabs.find('.cwsfe_widget').on('mouseenter', onWidgetOverEvent);
				tabs.find('.cwsfe_widget').on('mouseleave', onRowLeave);
				initDrop(el.find(window.cwsfe.accs.selectors.content));
				initSortModules(el.find(window.cwsfe.accs.selectors.content));
				// make the tabs movable accross their place
				el.find(window.cwsfe.accs.selectors.tabs).parent().sortable({
					items: window.cwsfe.accs.selectors.tabs,
					start: function(e, ui){	in_drag = true; $('.cwspb_controls').remove(); },
					stop: function(e, ui){ in_drag = false },
				});
			break;
		}
		el.on('mouseenter', onWidgetOverEvent);
		el.on('mouseleave', onRowLeave);
	}

	var onSidePanelSpanClick = function(e) {
		e.preventDefault();
		e.stopPropagation();
		var li = e.target.parentNode;
		var template = li.dataset['t'];
		var reason = null;
		var new_name = '';
		switch (e.target.className) {
			case 'edit':
				new_name = window.prompt(window.cwsfe.l10n.enter_template, template);
				if (new_name && new_name.length > 0 && new_name !== template) {
					li.dataset['t'] = new_name;
					jQuery(li).find('p').text(new_name);
					window.cwsfe.templates[new_name] = window.cwsfe.templates[template];
					delete window.cwsfe.templates[template];
					reason = 'rename';
				}
				break;
			case 'del':
				if (window.confirm(window.cwsfe.l10n.areusure)) {
					$(li).remove();
					delete window.cwsfe.templates[template];
					reason = 'del';
				}
				break;
		}
		if (reason) {
			jQuery.ajax({
				type: 'post',
				async: true,
				dataType: 'text',
				url: ajaxurl,
				data: {
					action: 'cwsfe_ajax_update_template',
					old: template,
					new: new_name,
					reason: reason,
					nonce: window.cwsfe.nonce,
				},
				success: function(resp){}
			});
		}
	}

	var lowerPanelAdd = function(e) {
			e.preventDefault();
			e.stopPropagation();
			var href = e.target.href.match('\/([a-z_0-9]+$)')[1];
			var lower_panel = $('main #cwspb_lower_panel');
			switch (href) {
				case 'add_row':
					//var li = Y.Node.create('');
					unsaved = true;
					var row = $(window.cwsfe.widgets.row).insertBefore(lower_panel);
					InitRow(row);
					$('html, body').animate({
        		scrollTop: row.offset().top - 64
					}, 1000);
					break;
				case 'add2templates':
					var marked = lower_panel.parent().find('.grid_row_content.templatize');
					if (marked.length > 0) {

						var template_name = window.prompt(window.cwsfe.l10n.enter_template, '');
						if (template_name && template_name.length > 0) {
							var content = savePage( jQuery('main .grid_row_content.templatize .cwsfe_row') );
							var id = document.body.className.match(/page-id-(\d+)/)[1];
							jQuery.ajax({
								type: 'post',
								async: true,
								dataType: 'text',
								url: ajaxurl,
								data: {
									action: 'cwsfe_ajax_add_template',
									name: template_name,
									nonce: window.cwsfe.nonce,
									content: content,
									id: id,
								},
								error: function(resp){
								},
								success: function(resp){
									// need to add to the panel
									window.cwsfe.templates[template_name] = resp;
									jQuery('#cws_content_wrap ul.templates').append('<li class="m_item" data-t="'+template_name+'"><p>'+ template_name +'</p><span class="del"></span><span class="edit"></span></li>');
									jQuery('#cws_content_wrap ul.templates>li:last-of-type span').on('click', function(e){
										onSidePanelSpanClick(e);
									});
									initDragModules($('#cws_content_wrap #bd .elements_panel ul.templates'), 'main');
									jQuery('main>.grid_row_content.templatize').toggleClass('templatize');
								}
							});

						}
					}
					break;
			}
		}

		//var in_control = false;
		var g_row;

		var onRowOver = function(e) {
			var row = $(this);
			g_row = row;
		}

		var timeout = null;
		var bt = false;

		$(document).on('mousemove', function(e) {
			if (in_drag) {return;}
			if (timeout !== null) {
				bt = false;
				clearTimeout(timeout);
			}

   		function get_row_control() {
				if (!g_row) {
					g_row = jQuery(e.target);
					if (!g_row.hasClass('grid_row_content')) {
						g_row = g_row.closest('.grid_row_content');
					}
					g_row = (g_row && 0 === g_row.length) ? null : g_row;
				} else {
					var widget = getElementUnderCursor(e.clientX, e.clientY, 'cwsfe_widget');
					if (widget.length) {
						g_row.find('>.cwspb_controls').remove();
						g_row = null;
						onWidgetOver(jQuery(widget));
						jQuery(widget[0]).closest(".grid_row_content").addClass('hidden-bg');
					}
				}
				if (g_row && g_row.find('>.cwspb_controls').length == 0 && g_row.find('.cwsfe_widget>.cwspb_controls').length === 0 && !in_drag) {
					g_row.prepend('<div data-type="row" class="cwspb_controls">'+ row_ctemplate +'</div>');
					assignControls(g_row.find('>.cwspb_controls'));
					jQuery(g_row).removeClass('hidden-bg');
				}
				if(g_row && g_row.find('.cwsfe_grid>.cwspb_controls').length === 0){
					var grids = g_row.find('.cwsfe_grid');
					if (grids.length === 1) {
						grids.prepend('<div data-type="grid" class="cwspb_controls">'+ grid_ctemplate_nd +'</div>');
					} else {
						grids.prepend('<div data-type="grid" class="cwspb_controls">'+ grid_ctemplate +'</div>');
					}
					assignControls(g_row.find('.cwsfe_grid>.cwspb_controls'));
				}

			}

			if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
				timeout = setTimeout(get_row_control, 300);
			}	else {
				get_row_control();
			}
			//}, 300);
		});

		var getElementUnderCursor = function(x,y, needle_class) {
			var stack = [],	elementMouseIsOver = document.elementFromPoint(x, y), iret = [], i = 0;

			stack.push(elementMouseIsOver);
			while (elementMouseIsOver && elementMouseIsOver.tagName !== 'HTML' && i < 5){
				elementMouseIsOver.style.pointerEvents = 'none';
				if (elementMouseIsOver.className.match(new RegExp('(\\s|^)' + needle_class + '(\\s|$)'))) {
					iret.push(elementMouseIsOver);
				}
				elementMouseIsOver = document.elementFromPoint(x, y);
				stack.push(elementMouseIsOver);
				i++;
			}
			i = 0;
			var il = stack.length;
			for (; i < il; i += 1) {
				if (stack[i]) {
					stack[i].style.pointerEvents = '';
				}
			}
			return iret;
		}

		var onRowLeave = function(e) {
			if (in_drag) {return;}
			var row = $(this);
			var main = getElementUnderCursor(e.clientX, e.clientY, 'page_content');
			var timeout = main.length ? 0 : 250;
			setTimeout(function() {
				row.find('.cwspb_controls').remove();
				g_row = null;
			}, timeout);
		}

		var onWidgetOverEvent = function(e) {
			var row = $(this);
			onWidgetOver(row);
		}

		var onWidgetOver = function(row) {
			if (row.length !== row.find('>.cwspb_controls').length && !in_drag) {
				row.each(function(k){
					var row = jQuery(this);
					if (!row.hasClass('cwsfe_widget')) {
						row = row.closest('.cwsfe_widget');
					}
					var template = widget_ctemplate;
					switch (row.data('type')) {
						case 'tabs':
						case 'accs':
							template = widget_tabs;
							break;
					}
					if (!k) {
						//row.closest('.grid_row_content').find('.cwspb_controls').remove();
					}
					row.prepend('<div data-type="widget" class="cwspb_controls">'+ template +'</div>');

					assignControls(row.find('>.cwspb_controls'));
				});
			}
		}

		var assignControls = function(node) {
			node.find('>a').each(function(i, k) {
/*				$(k).on('mouseover', onControlEnter);
				$(k).on('mouseout', onControlLeave);*/
				$(k).on('click', onControlClick);
			});
		}

		var initSort = function(a) {
			a.sortable({
				cursor: 'move',
				revert: true,
				distance: 5,
				items: '.grid-row',
				opacity: 0.5,
				handle: '.cwspb_controls a.drag',
			});
		}

		var onControlEnter = function(e) {
			//console.log('control:enter parent:' + e.target.parentNode.parentNode.className);
			in_control = true;
			e.target.parentNode.style.display = 'block';
			e.stopPropagation();
		}
		var onControlLeave = function(e) {
			//console.log('control:leave');
			in_control = false;
			e.target.parentNode.style.display = '';
		}

		var onControlClick = function(e) {
			e.stopPropagation();
			e.preventDefault();
			unsaved = true;
			var reason = e.target.className;
			var control = e.target.parentNode;
			var parent = $(control.parentNode);
			var control_type = control.dataset['type'];
			var parent_type = parent[0].dataset['type'];
			switch(reason) {
				case 'pref':
					parent.addClass('selected');
					addOptions(parent, control.dataset['type']);
					break;
				case 'pref_tab':
					parent.addClass('selected');
					addOptions(parent, parent.data('type') + '_one');
					break;
				case 'add':
					if ('grid' === control_type) {
						var row = parent.closest('.cwsfe_row');
						var last_grid = $(window.cwsfe.widgets.row).find('.cwsfe_grid').insertAfter(parent);
						last_grid.data('params', getElParams(last_grid[0]));
						initSortModules(last_grid);
						//initSortModulesRow();
						initDrop(last_grid.find('.widget_wrapper'));
						initSortModules(last_grid.find('.widget_wrapper'));

						row.parent().sortable('refresh');
					}
					break;
				case 'del_tab':
					if (confirm('Are you sure you want to delete this tab?')) {
						if ('accs' === parent_type) {
							var this_tab = parent.find(window.cwsfe.accs.selectors.tabs + '.active'); // !!!
							this_tab.remove();
							parent.find(window.cwsfe.accs.selectors.tabs + ':first').trigger('click');
						}	else if ('tabs' === parent_type) {
							var this_tab = parent.find(window.cwsfe.tabs.selectors.tabs + '.active'); // !!!
							var this_id = this_tab.attr('tabindex');
							var this_content = parent.find(window.cwsfe.tabs.selectors.content + '[tabindex="'+ this_id +'"]');
							this_tab.remove();
							this_content.remove();
							parent.find(window.cwsfe.tabs.selectors.tabs + ':first').trigger('click');
						}
					}
					break;
				case 'add_tab':
					// first we need to find out the latest index
					if ('accs' === parent_type) {
						var t_last_tab = $(cwsfe.widgets.accs).find(window.cwsfe.accs.selectors.tabs + ':last');
						//var t_last_content = $(cwsfe.widgets.accs).find(window.cwsfe.accs.selectors.content + ':last');
						var last_id = parent.find(window.cwsfe.accs.selectors.tabs).length;
						t_last_tab.data('params', window.cwsfe.widget_atts.accs_one);
						parent.find(window.cwsfe.accs.selectors.tabs + ':last').parent().append(t_last_tab);
						var tab_sections = parent.find(window.cwsfe.accs.selectors.content + ':last').parent();
						//tab_sections.append(t_last_content);
						window.jQuery.fn.tab_onClick(parent.find(window.cwsfe.accs.selectors.tabs + ':last')[0], tab_sections); // function from scripts.js
						var last_content = parent.find(window.cwsfe.accs.selectors.content + ':last');
					} else {
						var t_last_tab = $(cwsfe.widgets.tabs).find(window.cwsfe.tabs.selectors.tabs + ':last');
						var t_last_content = $(cwsfe.widgets.tabs).find(window.cwsfe.tabs.selectors.content + ':last');
						var last_id = parent.find(window.cwsfe.tabs.selectors.tabs).length;
						t_last_tab.attr('tabindex', last_id);
						t_last_content.attr('tabindex', last_id);
						t_last_tab.data('params', window.cwsfe.widget_atts.tabs_one);
						parent.find(window.cwsfe.tabs.selectors.tabs + ':last').parent().append(t_last_tab);
						var tab_sections = parent.find(window.cwsfe.tabs.selectors.content + ':last').parent();
						tab_sections.append(t_last_content);
						window.jQuery.fn.tab_onClick(parent.find(window.cwsfe.tabs.selectors.tabs + ':last')[0], tab_sections); // function from scripts.js
						var last_content = parent.find(window.cwsfe.tabs.selectors.content + ':last');
					}

					initDrop(last_content);
					initSortModules(last_content);
					var widget = last_content.find('.cwsfe_widget');
					widget.on('mouseenter', onWidgetOverEvent);
					widget.on('mouseleave', onRowLeave);
					break;
				case 'toggle':
					$(control).find('[data-state]').each( function(){
						var new_state = (this.dataset['state'] === 'disable') ? '' : 'disable';
						this.dataset['state'] = new_state;
					} );
					break;
				case 'del':
					if (window.confirm(window.cwsfe.l10n.areusure)) {
						if(parent.parent().children().length == 1){
							parent.closest('.cwsfe_grid').addClass('empty_widget');
						}
						parent.remove();
					}
					break;
				case 'clone':
					var new_w;
					if (
						parent_type && ('igrid' === parent_type || 'tabs' === parent_type || 'accs' === parent_type) ||
						'grid' === control_type || 'row' === control_type
					) {
						var ig_old_content = parent.find('.widget_wrapper,'+window.cwsfe.tabs.selectors.content+','+window.cwsfe.accs.selectors.content+','+window.cwsfe.igrid.selectors.content);
						ig_old_content.sortable('destroy');
						new_w = cloneWidget(parent);
						var wrapper = new_w.find('.widget_wrapper,'+window.cwsfe.tabs.selectors.content+','+window.cwsfe.accs.selectors.content+','+window.cwsfe.igrid.selectors.content);
						//initDrop(wrapper);
						var widgets = wrapper.find('.cwsfe_widget');
						initSortModules(ig_old_content);
						widgets.on('mouseenter', onWidgetOverEvent);
						widgets.on('mouseleave', onRowLeave);
						widgets.each(function(){
							var type = this.dataset.type || null;
							if (type && undefined !== window.cwsfe.widget_callbacks[type] && 'function' === typeof window.jQuery.fn[window.cwsfe.widget_callbacks[type]]) {
								window.jQuery.fn[window.cwsfe.widget_callbacks[type]](this);
							}
						});
						initSortModules(wrapper);
					} else {
						new_w = cloneWidget(parent);
						if (parent_type && undefined !== window.cwsfe.widget_callbacks[parent_type] && 'function' === typeof window.jQuery.fn[window.cwsfe.widget_callbacks[parent_type]]) {
							window.jQuery.fn[window.cwsfe.widget_callbacks[parent_type]](parent[0]);
						}
					}
					break;
				case 'add2t':
					parent.toggleClass('templatize');
					if(jQuery(".grid_row_content").hasClass('templatize')){
						jQuery('#cwspb_lower_panel_left a').addClass('active-panel');
					}
					else{
						jQuery('#cwspb_lower_panel_left a').removeClass('active-panel');
					}

					break;
			}
			//return false;
		}

		var cloneWidget = function(w) {
			// events are removed so we have to re-attach those later
			return w.clone(true).off().insertAfter(w);
		}

		var addOptions = function(parent, type) {
			var template = '';
			switch (type) {
				case 'row':
					template = 'row';
					break;
				case 'col':
					template = 'column';
					break;
				case 'grid':
					template = 'grid';
					var class_grid = getClassGrid(parent);
					// assign current value to radio
					$('#cws_content_wrap #cwspbfe-' + template + ' input[value='+ class_grid +']').attr('checked', true);
					if (undefined === window.cwsfe.dlgs[template]) {
						initDialog(template);
					}
					window.cwsfe.dlgs[template].data('initial', class_grid);
					break;
				case 'widget':
					template = parent.data('type');
					if ('igrid' === template) {
						var class_grid = getClassGrid( parent.children() );
						if (undefined === window.cwsfe.dlgs[template]) {
							initDialog(template);
						}
						window.cwsfe.dlgs[template].data('initial', class_grid);
					}
					break;
				default:
					template = type;
					break;
			}
			// here we need to init template as we just show and hide it
			/*
			$('#cws_content_wrap #cwspbfe-' + template).show(300);
			$('#cws_content_wrap .elements_panel').hide(300);
			*/
			if (undefined === window.cwsfe.dlgs[template]) {
				initDialog(template);
			}
			if (undefined !== window.cwsfe.dlgs[template]) {
				if ('row' === template && !parent.hasClass('cwsfe_row')) {
					parent = parent.find('.cwsfe_row');
				}
				window.cwsfe.dlgs[template].data('node', parent);
				window.cwsfe.dlgs[template].data('template', template);
				window.cwsfe.dlgs[template].dialog('open');
			}
		}

	var getClassGrid = function(parent) {
		var ret = ''
		parent.children('.cwsfe_col').each(function() {
			var col = parseInt(parent.children('.cwsfe_col').attr('class').match(/grid_col_(\d+)/)[1]); // !!! this needs to be unified
			if (12 === col) {
				col = 1;
			}
			ret += col;
		});
		return ret;
	}

	var onDialogApply = function(e) {
		sidePanelApply($(this), false);
		$(this).dialog( 'close' );
	}

	var onDialogPreview = function(e) {
		sidePanelApply($(this), true);
		window.cwsfe_def_dialog.previewed = true;
	}

	var sidePanelApply = function(th, isPreview, pShortcode) {
		var node = th.data('node');
		var template = th.data('template');
		var isCancelled = (undefined !== th.data('cancelled'));

		if (undefined === node) {return;}
/*
		var cloned = $('#cwspb_lower_panel .cloned_d>div');

		if (isPreview && undefined === cloned.data('node')) {
			// add this to our cloned copy
			copyParams(th, cloned, ['uiDialog']);
		}
*/
		if ('grid' === template || 'igrid' === template) {
			var row_columns = $(th).find('.row.columns');
			if (row_columns.length) {
				if (isCancelled) {
					var dialog = cloned.data('uiDialog')['uiDialog'];
					row_columns = dialog.find('.row.columns');
				}
				var col_name = row_columns.find('input').attr('name');
				var col_val = '';
				row_columns.find('.columns ul').each(function(){
					col_val += this.className.match(/span(\d)/)[1];
				});
				row_columns.find('input').val(col_val);
			}
			if (isCancelled) {
				// need to switch values to return to initial state
				th.find('.row.columns input').val(th.data('initial'));
				th.data('initial', col_val);
			}
			var node_tmp = node;
			var cols_html = window.cwsfe.cols;
			var sort_drop_class = '.widget_wrapper';
			if ('igrid' === template) {
				node_tmp = node.find('.cwsfe_igrid'); // .cwsfe_igrid
				cols_html = window.cwsfe.icols;
				sort_drop_class = window.cwsfe.igrid.selectors.content;
			}
			changeGridColumns(node_tmp, th, cols_html, sort_drop_class);
		}

		var groups = $(th).find('.groups');
		if (undefined !== groups && 0 < groups.length) {
			fixSortables($(th));
		}

		var _data = $(th).find('.row_options:not([class*="disable"]) [name]').filter(function(id, val){
			return !jQuery(val).closest('.row_options').hasClass('disable');
		});
		var rObj = processForm2Param(_data);
		if ('tabs_one' === template) {
			$.extend(rObj, window.cwsfe.g_li);
			node = node.find(window.cwsfe.tabs.selectors.tabs + '.active');
		} else if ('accs_one' === template) {
			$.extend(rObj, window.cwsfe.g_li);
			node = node.find(window.cwsfe.accs.selectors.tabs + '.active');
		}
		var old_params = $(node).data('params');
		old_params = (undefined !== old_params) ? old_params : {};
		var tmce_textarea = th.find('textarea[id^="wp-editor-area"]');
		var old_cont = '';
		var cont = '';
		if (tmce_textarea.length !== 0) {
			old_cont = window.cwsfe.dlgs[th[0].id.substr(8)].data('node')[0].dataset['cont'];
			if (isCancelled) {
				cont = old_cont;
			} else {
				if ('tmce' === th.find('a.cwsfe_switch')[0].dataset['mode'] ) {
					var id = tmce_textarea.attr('id');
					try {
						cont = tinyMCE.editors[id].getContent();
					} catch(e) {
						cont = tmce_textarea.val();
					}
				} else {
					cont = tmce_textarea.val();
				}
			}
			//tinyMCE.editors[id].hide();
			if (!isPreview) {
				window.cwsfe.dlgs[th[0].id.substr(8)].data('node')[0].dataset['cont'] = cont;
			}
		}
		var any_shortcodes = false;
		if (cont.length > 0) {
			any_shortcodes = cont.match(new RegExp(window.cwsfe.shortcodes));
		}
		if (any_shortcodes || !equalParams(old_params, rObj) || old_cont != cont || !isPreview) {
			// either params has changed or there're shortcodes in the content
			// now we need to ajax it up and replace the results in node
			//mergeParams(old_params, rObj); // merge with the old ones, in case of media for example, because g_li will be empty in this case
			var res = buildAtts(rObj);
			var res_sc = '';
			var shortcode_name = '';
			var shortcode_end = '';
			switch (template) {
				case 'grid':
					shortcode_name = '[cws-grid';
					shortcode_end = '[/cws-grid]';
					break;
				case 'row':
					shortcode_name = '[cws-row';
					shortcode_end = '[/cws-row]';
					break;
				case 'tabs_one':
				case 'accs_one':
					shortcode_name = '[pb_item type='+ template;
					shortcode_end = '[/pb_item]';
					break;
				case 'tabs':
				case 'accs':
					$(node).data('params', rObj);
					res_sc = SaveWidget($(node)[0]);
					$(node).data('params', old_params);
					break;
				default:
					shortcode_name = '[cws-widget type='+ template;
					shortcode_end = '[/cws-widget]';
					break;
			}
			if (res_sc.length === 0) { // because we build it for tabs and accs before
				if (cont.length > 0) {
					res_sc = shortcode_name + res.atts + ']' + cont + shortcode_end;
				}	else {
					res_sc = shortcode_name + res.atts + ' /]';
				}
			}
			if ('object' === typeof pShortcode && pShortcode.shortcode.length) {
				ajaxRenderPreview(pShortcode.shortcode, $(pShortcode.node), pShortcode.cont, isPreview, pShortcode.params);
			} else if ('object' === typeof pShortcode && !pShortcode.shortcode.length) {
				// if object is there, but shortcode length is not set
				return {shortcode: res_sc, node: node, cont: cont, params: rObj};
			} else /*if (undefined ==== pShortcode)*/ {
				ajaxRenderPreview(res_sc, $(node), cont, isPreview, rObj);
			}
		} else if (cont.length > 0) {
			// render it as is
			$(node).html(cont);
		}
	}

	var ajaxRenderPreview = function(strShortcode, node, context, isPreview, rObj) {
		console.log(strShortcode);
		jQuery.ajax({
			type: 'post',
			async: true,
			context: context,
			dataType: 'text',
			url: ajaxurl,
			data: {
				action: 'cwsfe_ajax_doshortcode',
				nonce: window.cwsfe.nonce,
				data: strShortcode,
				pid: pid,
			},
			error: function(resp){
			},
			success: function(resp){
				if (resp.length > 0) {
					// check for ||| as the limiter between node html and content part styles
					// applicable for tabs and accordions
					var parent = node.closest('.cwsfe_widget');
					var type = parent.data('type');
					if (-1 !== resp.indexOf('|||')) {
						var resp2 = resp.split('|||');
						resp = resp2[0];
						switch (type) {
							case 'accs':
								node.find(window.cwsfe.accs.selectors.content).attr('style', resp2[1]);
								var title = node.children()[0]
								$(title).html(resp); //!!! assume title is the very first child
								break;
							case 'tabs':
								var this_tab = node.find(window.cwsfe.tabs.selectors.tabs + '.active');
								var this_id = this_tab.attr('tabindex');
								var this_content = parent.find(window.cwsfe.tabs.selectors.content + '[tabindex="'+ this_id +'"]');
								this_content.attr('style', resp2[1]);
								var title = node.children()[0]
								$(title).html(resp); //!!! assume title is the very first child
								break;
						}
					} else if (parent.length > 0 && 'igrid' !== type) { // only widgets

						//node.html(resp);
						node.html($(resp).html());
						// now we need to re-init accs and tabs if any
						var needs_init = false;
						switch (type) {
							case 'accs':
								restoreWidget(parent[0]);
								needs_init = true;
								break;
							case 'tabs':
								restoreWidget(parent[0]);
								needs_init = true;
								break;
							default:
								if (undefined !== window.cwsfe.widget_callbacks[type] && 'function' === typeof window.jQuery.fn[window.cwsfe.widget_callbacks[type]]) {
									window.jQuery.fn[window.cwsfe.widget_callbacks[type]](parent[0]);
								}
								break;
						}
						if (needs_init) {
							var wrapper = parent.closest('.widget_wrapper');
							initModulesEvents(parent);
							/*wrapper.find('.cwsfe_widget').on('mouseenter', onWidgetOverEvent);
							wrapper.find('.cwsfe_widget').on('mouseleave', onRowLeave);
							initDrop(wrapper);
							initSortModules(wrapper);*/
						}

					} else if ( node.hasClass('cwsfe_row') ) {
						parent = node.parent();
						// check if response have full width classes
						var resp_container = $($(resp).children()[0]);
						var resp_children = $(resp).children().children();
						var row_children = parent.children();
						// first we remove any children from the current setting
						// except for cwsfe_row
						parent.children().each(function(){
							var _this = $(this);
							if ( !_this.hasClass('cwsfe_row') ) {
								_this.remove();
							}
						});

						// now insert from resp
						resp_children.each(function(){
							var _this = $(this);
							if ( !_this.hasClass('cwsfe_row') ) {
								_this.appendTo(parent);
							}
						});

						// now copy style and classes from parent and cwsfe_row
						var resp_row = $(resp).find('.cwsfe_row');
						node.attr('style', resp_row.attr('style') );
						node.attr('data-atts', resp_row.attr('data-atts') );
						node.attr('class', resp_row.attr('class') );

						parent.attr('style', resp_container.attr('style') );
						parent.attr('data-atts', resp_container.attr('data-atts') );
						parent.attr('class', resp_container.attr('class') );
					}
					if (!isPreview) {

						node.data('params', rObj);
						if (this.length > 0) { // this is cont, because of context
							node.data('cont', this);
						}
					}
				} else {
					//debugger
				}
			}
		});
	}

	var mergeParams = function (dataFrom, dataTo) {
		for (var key in dataFrom) {
			if (undefined === dataTo[key]) {
				dataTo[key] = dataFrom[key];
			}
		}
	}

	var equalParams = function(p1, p2) {
		var res = true;
		var p1_keys = Object.keys(p1);
		var p2_keys = Object.keys(p2);
		if (p1_keys.length === p2_keys.length) {
			for (var i = 0; i < p1_keys.length; i++) {
				if (undefined !== p2[p1_keys[i]]) {
					if (p2[p1_keys[i]] !== p1[p1_keys[i]]) {
						res = false;
						break;
					}
				} else {
					res = false;
					break;
				}
			}
		} else {
			res = false;
		}
		return res;
	}

	var buildAtts = function(params) {
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
			//atts = atts.split("'").join("\\'");
			atts = atts.split("'").join("&#39;");
			atts = atts.split("[").join("%5B;");
			atts = atts.split("]").join("%5D;");
			atts = atts.split('\\"').join("%22;");
			/*
			atts = atts.replace("'", "\\'");
			atts = atts.replace("[", "%5B;");
			atts = atts.replace("]", "%5D;");
			*/
			atts = ' atts=\'' + atts + '\'';
		}
		return {atts: atts, n_params: n_params};
	}

	var copyParams = function(_in, _out, aSkip) {
		var params = _out.data();
		var in_data = _in.data();
		for (var key in in_data) {
			if (in_data.hasOwnProperty(key) && ('undefined' === typeof aSkip || aSkip.indexOf(key) === -1) ) {
				params[key] = in_data[key];
			}
		}
		_out.data(params);
	}

	var changeGridColumns = function(grid, th, _cols, sort_drop_class) {
		// change columns
		var newvalue = th.find('input').val();
		var oldvalue = th.data('initial');

		if (newvalue === oldvalue) {return;}

		grid.children('.cwsfe_col').slice(0, Math.min(newvalue.length, oldvalue.length)).each(function(i, k) {
			var new_class = $(_cols[parseInt(newvalue.substr(i, 1))]).attr('class');
			$(k).attr('class', new_class);
		});

		if ( newvalue.length >= oldvalue.length ) {
			// new columns are added
			// first we update old columns
			var left = newvalue.substring(oldvalue.length);
			for (var i = 0; i < left.length; i++) {
				grid.append( _cols[parseInt(left.substr(i, 1))] );
				//grid.children(':last-child').find('.widget_wrapper').remove(); // !!! this should be unified, we add widget_wrapper during drag from empty widgets
				initSortModules(grid.children(':last-child').find(sort_drop_class));
				initDrop(grid.children(':last-child').find(sort_drop_class));
			}
		} else if (newvalue.length < oldvalue.length) {
			// removed one or more column
			// in this case widgets should be moved
			// first we move starting from oldvalue.length
			var cols = grid.children('.cwsfe_col');
			var dest_col = $(cols[newvalue.length-1]).find('.ui-sortable');
			for (var i = newvalue.length; i < cols.length; i++) {
				$(cols[i]).find('.cwsfe_widget').each(function(){
					$(this).detach().appendTo(dest_col);
				});
				cols[i].remove();
			}
		}
	}
})( window.jQuery );

var content_content = '';
var needs_update = false;

	function processMbInputOptions (el, params, bIsAssign, bToggleHide) {
		var row = jQuery(el).closest('.row_options')[0]; // this one should be the only one
		if (undefined === row) {
			return;
		}
		var bToggleHide = undefined === bToggleHide ? false : bToggleHide;
		var bDisabled = /(\W|^)disable(\W|$)/.test(row.className);
		//if (undefined !== el.getAttribute('data-options') && el.getAttribute('data-options') && ( (!bIsAssign && !bDisabled && !bToggleHide) || (!bIsAssign && bDisabled && bToggleHide) || (bIsAssign && !bDisabled && !bToggleHide) )) {
		if ( undefined !== el.getAttribute('data-options') && el.getAttribute('data-options') /*&&  ( ( !bIsAssign && !bDisabled ) || (bIsAssign && !bToggleHide && !bDisabled) )*/ ) {
			if (!bIsAssign && ( ('checkbox' === el.type || 'radio' === el.type) && !el.checked) ) {
				// by default unchecked checkbox/radio means to ignore any data-options
				return;
				/*var init = /checked/.test(el.outerHTML); //Boolean(el.dataset['init']); // so far only 0 or 1
				if (init == el.checked ) {
					return;
				}*/
			}
			var parent = row.parentNode;
			var options_pairs = el.getAttribute('data-options').split(';');
			for (var i=0; i<options_pairs.length; i++) {
				var pair = options_pairs[i].split(':');
				if ( 'checkbox' === el.type && !el.checked) {
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
				if (bToggleHide && pair[0] === 'e') {
					// in this case hide it, since togglehide ca be invoked only from elDisable
					pair[0] = 'd';
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
						//parent.getElementsByClassName('row '+pair[1])[0].style.display = el.checked ? (bElDisabled ? 'none' : '') : (bElDisabled ? 'none': '');
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
									//while ((el = el.parentElement) && !el.classList.contains('row'));
									window.cws_evnt_param_key = this.name; // !!! get p_something from class
									break;
								case 'radio':
									if (!this.checked) {
										bSkipProcess = true;
									}
									break;
								//window.cws_evnt_param_key = el.classList.item(el.classList.length-1);
							}
							if (!bSkipProcess) {
								window.processEvntInputOptionsLvl++;
								processMbInputOptions(this, params, bIsAssign, !bElDisabled);
								window.processEvntInputOptionsLvl--;
							}
						});
						//jQuery(el).closest('.cwsfw_controls').find('div.row.'+pair[1]).toggle(300);
					break;
					case 'enable':
					case 'e':
						elProcessEnable(pair[1], params, bToggleHide, false, parent);
						break;
					case 'disable':
					case 'd':
						addInputArray(window.processEvntInputOptionsLvl, 'd', pair[1], parent);
						if (!getStatusInputArray(window.processEvntInputOptionsLvl, pair[1], parent)) {
							/*if (params && !bIsAssign) {
								delete params[pair[1]];
							}*/
							elDisable(pair[1], parent, params);
						}
						break;
					case 'select':
						//if (bIsAssign) {
							var sel_index = 0;
							if (params && undefined !== window.cws_evnt_param_key && el.name === window.cws_evnt_param_key) {
								if ('select2-offscreen' === el.className) {
										jQuery(el).select2('val', params[window.cws_evnt_param_key]);
								} else {
									sel_index = undefined !== params[window.cws_evnt_param_key] ? params[window.cws_evnt_param_key] : 0;
									for (var i=0;i<el.options.length;i++){
										if (sel_index === el.options[i].value) {
											el.selectedIndex = i;
											break;
										}
									}
								}
							}
						//}
						/*} else if (sel_index != el.selectedIndex) {
							// means, this control has not been assigned yet
							// but we still have to consider its real value
							el.selectedIndex = sel_index;
						}*/
						var op_options = (undefined !== el.options[el.selectedIndex] && undefined !== el.options[el.selectedIndex].dataset.options) ? el.options[el.selectedIndex].dataset.options : el.options[0].dataset.options;
						bToggleHide = typeof bToggleHide !== 'undefined' ? bToggleHide : false;
						if (op_options && op_options.length) {
							options_pairs = op_options.split(';');
							for (var i=0; i<options_pairs.length; i++) {
								pair = options_pairs[i].split(':');
								if (bToggleHide && pair[0] === 'e') {
									// in this case hide it, since togglehide ca be invoked only from elDisable
									pair[0] = 'd';
								}
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
											/*if (params) {
												delete params[pair[1]];
											}*/
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
		var parent = par;
		if (!bToggleHide && parent.getElementsByClassName('row '+ pair_1).length == 1) {
			if (getStatusInputArray(window.processEvntInputOptionsLvl, pair_1, par)) {
				addInputArray(window.processEvntInputOptionsLvl, 'e', pair_1, par);
				parent.getElementsByClassName('row '+ pair_1)[0].className = parent.getElementsByClassName('row '+pair_1)[0].className.replace(/\s+disable/gm,'');
				if (!bIsAssign) {
					// need to process data-options if any in case we're just clicking thru form,
					// i.e. not coming from assign
					pair_1 = pair_1.replace( /(:|\.|\[|\]|,)/g, "\\$1" ); // jQuery doesn't like ":.[]," in classes or ids
					jQuery(parent).find('div.row.'+pair_1+' select[data-options],div.row.'+pair_1+' input:not([id^="s2id_"])').each( function(el) {
						switch (this.type) {
							case 'select-one':
								window.cws_evnt_param_key = this.name;
								break;
						}
						AssignElement(this, params, true);
						window.processEvntInputOptionsLvl++;
						processMbInputOptions(this, params, true, false);
						window.processEvntInputOptionsLvl--;
					});
				}
			}
		} else { //if (!getStatusInputArray(window.processEvntInputOptionsLvl, pair_1)) {
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
		console.log(w_id);
		// basically we check if it was enabled (true) or disabled (false) on some higher level
		if (undefined !== g_cws_pb[w_id]) {
			g_cws_pb[w_id].filter(function(el, k) {
				if (k<=lvl) {
					i += (-1 !== el.e.indexOf(value)) ? 1 : 0;
					i -= (-1 !== el.d.indexOf(value)) ? 1 : 0;
				}
			});
		}
		//console.log(value + ': '+ i);
		return i>=0;
	}

	function addInputArray (lvl, op, value, parent) {
		var w_id = getWidgetId(parent);
		if (undefined === g_cws_pb[w_id]) {
			g_cws_pb[w_id] = [];
		}
		if (undefined === g_cws_pb[w_id][lvl]) {
			g_cws_pb[w_id][lvl] = {'e':[],'d':[]};
		}
		if (-1 === g_cws_pb[w_id][lvl][op].indexOf(value)) {
			g_cws_pb[w_id][lvl][op][g_cws_pb[w_id][lvl][op].length] = value;
		}
	}

	function elDisable (el, parent, params) {
		var el_j = el.replace( /(:|\.|\[|\]|,)/g, "\\$1" ); // jQuery don't like ":.[]," in classes or ids
		jQuery(parent).find('div.row.'+ el_j +' select[data-options],div.row.'+ el_j +' input:not([id^="s2id_"])').each( function() {
			if (this.name.length) {
				var dummy = {};
				dummy[this.name] = buildDummyValue(this);
				AssignElement(this, dummy, false);
				window.processEvntInputOptionsLvl++;
				processMbInputOptions(this, params, false, true);
				window.processEvntInputOptionsLvl--;
			}
		});
		if (undefined !== parent && parent.getElementsByClassName('row '+el).length > 0) {
			parent.getElementsByClassName('row '+el)[0].className = parent.getElementsByClassName('row '+el)[0].className.replace(/\s+disable/gm,'') + ' disable';
		}
	}

	function buildDummyValue(t) {
		var d_value = '';
		switch (t.type) {
			case 'checkbox':
				d_value = false;
				break;
		}
		return d_value;
	}

	function getWidgetId (el) {
		return jQuery(el).closest('*[data-w]').length ? jQuery(el).closest('*[data-w]').data('w') : 0;
	}

	function processGroupMinimize(li) {
		var label = jQuery(li).find('>label');
		if (jQuery(label).hasClass('disable')) {
			var title = jQuery(li).find('input[data-role="title"]').val();
			label[0].innerText = title;
		}
		jQuery(li).find('>.row,>.cws_pb_ftabs,>.cws_form_tab').toggleClass('g_hidden');
		jQuery(label).toggleClass('disable');
	}

	function assignParams(elements, params) {
		emptyGcwspb();
		elements.each(function(e, k){
			var bSkip = false;
			if ('alternative_style' === k.name) { debugger }
			if (undefined !== params && undefined !== params[k.name]) {
				// assign
				bSkip = AssignElement(k, params, false);
			} else if (k.name.length > 0 && undefined === params[k.name]) {
				// empty it in this case
				var dummy = {};
				dummy[k.name] = buildDummyValue(this);
				params[k.name] = dummy[k.name];
				AssignElement(k, dummy, false);
			}
			/*if (k.dataset['defaultColor'] ) {
				k.value = k.dataset['defaultColor'];
				if (params && undefined !== params[k.name]) {
					k.value = params[k.name];
				}
				jQuery(k).wpColorPicker('color', k.value);
			}*/
			if (!bSkip && undefined !== k.dataset['options'] && undefined !== params[k.name] && params[k.name] !== 'baadfood') {
				processMbInputOptions(k, params, true);
				// remove processed params
				var group = jQuery(k).closest('.row.group');
				var prefix = '';
				if (group.length) {
					prefix = jQuery(k).closest('.row.group').find('script.cwsfe_group').data('key');
					prefix += '[' + jQuery(k).closest('ul.groups li').index() + ']';
				}
				var w_id = getWidgetId(k);
				cleanParams(g_cws_pb[w_id], params, prefix);
				params[k.name] = 'baadfood';
			}
		});
	}

	function cleanParams(gcwspb, params, prefix) {
		if (gcwspb) {
			for(var i=0;i<gcwspb.length;i++) {
				if ('object' === typeof gcwspb[i]) {
					var keys = Object.keys(gcwspb[i]);
					for (var j=0;j<keys.length;j++) {
						cleanParams(gcwspb[i][keys[j]], params, prefix);
					}
				} else /*if ('array' === typeof gcwspb[i])*/ {
					// enabled or disabled elements
					// we suppose to clean
					for (var j=0;j<gcwspb.length;j++) {
						// only "normal" and "normal[normal]" names are valid in groups
						var normalized_name = '';
						if (-1 !== gcwspb[j].indexOf('[')) {
							normalized_name = gcwspb[j].replace(/(.+?)\[(.+?)\]/, "[$1][$2]");
						} else if (prefix.length) {
							normalized_name = '[' + gcwspb[j] + ']'; // for group names
						} else {
							normalized_name = gcwspb[j];
						}
						params[prefix + normalized_name] = 'baadfood';
					}
				}
			}
		}
	}

	function emptyGcwspb() {
		g_cws_pb = [];
		return;
	}

	function AssignElement(cur_input, item_params, bUseDefault) {
		var input_type = cur_input.type;
		var key = cur_input.name;
		var bSkip = false;
		if (item_params && item_params[key] && 'baadfood' === item_params[key] ) {
			return bSkip;
		}
		if (key.length) {
			switch (input_type) {
				case 'radio':
					if (item_params && item_params[key]) {
						cur_input.checked = cur_input.value === item_params[key];
					} else {
						cur_input.checked = (null !== cur_input.getAttribute('checked'));
					}
					bSkip = !cur_input.checked;
					if (jQuery(cur_input).parent().hasClass('cws_img_select_wrap')) {
						var li_img = jQuery(cur_input).closest('li.image_select');
						if (cur_input.checked) {
							li_img.addClass('checked');
						} else {
							li_img.removeClass('checked');
						}
					}
					break;
				case 'checkbox':
					if (item_params) {
						if (item_params[key] === '' || item_params[key] === '0') {
							// check for dummy
							item_params[key] = false;
						}
						cur_input.checked = item_params[key];
					}
					break;
				case 'select-multiple':
					if (item_params) {
						var sel_options = item_params[key].split(',');
						for (var i=0; i<sel_options.length;i++) {
							sel_options[i].value = (-1 !== sel_options.indexOf(sel_options[i].value));
						}
					} else if (undefined !== cur_input.dataset['defaultvalue']) {
						debugger
						var sel_options = cur_input.dataset['defaultvalue'].split(',');
						for (var i=0; i<sel_options.length;i++) {
							sel_options[i].value = (-1 !== sel_options.indexOf(sel_options[i].value));
						}
					}
					break;
				default:
					// hidden
					var item_val = '';
					if (item_params && undefined !== item_params[key]) {
						item_val = item_params[key];
						cur_input.value = item_val;
						if ('select-one' === input_type) {
							jQuery(cur_input).select2('val', item_params[key]);
						}
					} else if (bUseDefault) {
						switch (input_type) {
							case 'select-one':
								if (undefined !== cur_input.dataset['defaultvalue']) {
									cur_input.selectedIndex = cur_input.dataset['defaultvalue'];
								}
								break;
							default:
								item_val = cur_input.defaultValue;
								cur_input.value = item_val;
								break;
						}
					}
					var role = (undefined !== cur_input.dataset['role']) ? cur_input.dataset['role'] : null;
					if (!role && cur_input.dataset['defaultColor']) {
						role = 'color';
					}
					if (role) {
						switch (role) {
							case 'media':
								processMediaAtts( jQuery(cur_input).parent(), item_val );
								break;
							case 'gallery':
								var selection = getGSelection(item_val);
								if (selection) {
									if (!selection.length) {
										setTimeout(function() {
											selection = getGSelection(item_val);
											console.log(selection.length);
											updateGalleryImages(selection.toArray(), jQuery(cur_input).parent()[0]);
										}, 1000); // !!!
									} else {
										updateGalleryImages(selection.toArray(), jQuery(cur_input).parent()[0]);
									}
								}
								break;
							case 'color':
								cur_input.value = item_val;
								jQuery(cur_input).wpColorPicker('color', item_val);
								break;
							case 'title':
								// add label to group items
								jQuery(cur_input).closest('li[data-w]').find('>label')[0].innerText = item_val;
								break;
						}
					}

					break;
			}
		}
		return bSkip;
	}

	function addIconToSelectFa(icon) {
		if ( icon.hasOwnProperty( 'id' ) && icon.id.length > 0 ) {
			return "<span><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text.toUpperCase() + "</span>";
		} else {
			return icon.text;
		}
	}

	/* gallery support routines */
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
	/* /gallery support routines */

	function processMediaAtts(wrapper, src) {
		src = src || '';
		wrapper.find('img').attr('src', src);
		if (src.length > 0) {
			wrapper.find('a#pb-media-cws-pb').hide();
			wrapper.find('a#pb-remov-cws-pb').show();
		} else {
			wrapper.find('a#pb-media-cws-pb').show();
			wrapper.find('a#pb-remov-cws-pb').hide();
		}
	}

	function processForm2Param(_data) {
		var rObj = {};
		for (var k = 0; k < _data.length; k++) {
			var value = null;
			switch(_data[k].type) {
				case 'text':
				case 'number':
				case 'hidden':
				case 'textarea':
				case 'select-one':
					value = _data[k].value;
					break;
				case 'radio':
					value = _data[k].checked ? _data[k].value : null;
					break;
				case 'checkbox':
					value = _data[k].checked ? '1' : '0';
					break;
				case 'select-multiple':
					// need to check this
					var y=0;
					for (var i=0; i<_data[k].options.length; i++) {
						if (_data[k].options[i].selected == true) {
							value += y>0 ? ',' : '';
							value += _data[k].options[i].value;
							y++;
						}
					}
					break;
			}
			if (value !== null) {
				rObj[_data[k].name] = value;
			}
		}
		return rObj;
	}

	function fixSortables(form) {
		jQuery(form).find('ul.groups').each(function(a, el) {
			jQuery(el).find('>li').each(function(b, el0) {
				var that = b;
				jQuery(el0).find('[name]').each(function(c, el1) {
					var el_name = el1.name.replace(/(\w+\[)(\d+)(\].*)/, '$1'+that+'$3');
						jQuery(el1).attr('name', 'temp-' + el_name);
				});
			});
		});
		jQuery(form).find('ul.groups>li [name]').each(function(a, el) {
			var el_name = el.name.substr(5); // remove temp-, otherwise radio buttons loose their checked value
			jQuery(el).attr('name', el_name);
		});
	}
