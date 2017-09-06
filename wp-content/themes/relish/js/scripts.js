(function($) { 
"use strict";
 /*! Fades out the whole page when clicking links */
jQuery(document).ready(function() {

	jQuery('.header_wrapper_container .site_header a,.sticky_header .site_header a').click(function(event) {
		if(!jQuery(this).hasClass("fancy") && !jQuery(this).hasClass("cws_img_frame") && !jQuery('body').hasClass('no-loader')){
	    	if(jQuery(this).attr("href") != "#"  && !jQuery("body").hasClass("cwspb-active")){
		    	event.preventDefault();
		    	var newLocation = jQuery(this).attr("href");
		    	jQuery('body').fadeOut(1000, newpage(newLocation));	    		
	    	}
			
		}

  	});
  	function newpage(e) {
    	window.location = e;
  	}
});
cws_modules_state_init ();
is_visible_init ();
cws_milestone_init ();
cws_progress_bar_init ();
var directRTL;
if (jQuery("html").attr('dir') == 'rtl') {
	directRTL =  'rtl'
}else{
	directRTL =  ''
};
console.log(directRTL);
window.addEventListener( "load", function (){
	window.cws_modules_state.sync = true;
	cws_revslider_pause_init ();
	cws_header_bg_init ();
	cws_header_imgs_cover_init ();
	cws_header_parallax_init ();
	cws_mobile_menu_init();
	cws_wpml_vertical_switcher();
	jQuery( ".portfolio_carousel" ).cws_flex_carousel( ".cws_portfolio", ".cws_portfolio_header" );
	jQuery( ".portfolio_fw_carousel" ).cws_flex_carousel( ".cws_portfolio_fw", ".cws_portfolio_header" );
	jQuery( ".ourteam_carousel" ).cws_flex_carousel( ".cws_ourteam", ".cws_ourteam_header" );
	jQuery( ".news_carousel" ).cws_flex_carousel( ".news", ".cws_blog_header" );
	gallery_post_carousel_init();
	widget_carousel_init();
	cws_sc_carousel_init();
	cws_carousel_init_shortcode();
	twitter_carousel_init();
	isotope_init();
	cws_portfolio_pagination_init ();
	cws_portfolio_filter_init ();
	cws_portfolio_single_carousel_init ();
	cws_portfolio_fw_filter_init ();
	cws_ourteam_pagination_init ();
	cws_ourteam_filter_init ();
	cws_parallax_init();
	cws_blog_pagination_init();
	cws_sticky_sidebars_init();
	cws_prlx_init_waiter ();
	imageCircleWrap();
	circle_img_hover();

}, false );
jQuery(document).ready(function (){
	cws_sticky_light ();
	cws_responsive_custom_header_paddings_init ();
	cws_top_panel_mobile_init ();	/* async */
	cws_page_focus();
	cws_top_panel_search ();
	boxed_var_init ();
	cws_fs_video_bg_init ();
	wp_standard_processing ();
	cws_page_header_video_init ();
	cws_top_social_init ();
	custom_colors_init();
	cws_tabs_init ();
	cws_accordion_init ();
	cws_toggle_init ();
	select2_init();
	widget_archives_hierarchy_init();
	fancybox_init();
	wow_init();
	load_more_init();
	cws_revslider_class_add();
	cws_menu_bar ();
	cws_fullwidth_background_row ();
	cws_add_title_sep ();
	jQuery( ".cws_milestone" ).cws_milestone();
	jQuery( ".cws_progress_bar" ).cws_progress_bar();
	jQuery( ".cws_ce_content.ce_tabs" ).cws_tabs ();
	jQuery( ".cws_ce_content.ce_accordion" ).cws_accordion ();
	jQuery( ".cws_ce_content.ce_toggle" ).cws_toggle ( "accordion_section", "accordion_title", "accordion_content" );
	cws_message_box_init ();
	scroll_down_init ();
	scroll_top_init ();

	jQuery(window).resize( function (){
		cws_fullwidth_background_row ();
		cws_slider_video_height (jQuery( ".fs_video_slider" ));
		cws_slider_video_height (jQuery( ".fs_img_header" ));
	} );
});



function cws_fullwidth_background_row (){
	var main_width = jQuery('main').width();
	var row_bg_ofs, column_first_ofs, column_last_ofs;
	jQuery('.row_bg.fullwidth_background_bg').each(function(){

		row_bg_ofs = jQuery(this).offset();

		column_first_ofs = jQuery(this).find('.grid_col:first-child .cols_wrapper').offset();
		column_last_ofs = jQuery(this).find('.grid_col:last-child .cols_wrapper').offset();

		try{
			jQuery(this).find('.grid_col:first-child > .cols_wrapper > .row_bg_layer').css({'left':''+( row_bg_ofs.left - column_first_ofs.left )+'px','width':'auto','right':'0'});
			jQuery(this).find('.grid_col:first-child > .cols_wrapper > .row_bg_img_wrapper').css({'left':''+( row_bg_ofs.left - column_first_ofs.left )+'px','width':'auto','right':'0'});

			jQuery(this).find('.grid_col:last-child > .cols_wrapper > .row_bg_layer').css({'left':'0px','width':'auto','right':'-'+(jQuery(this).outerWidth() + row_bg_ofs.left - column_last_ofs.left - jQuery(this).find('.grid_col:last-child .cols_wrapper').outerWidth())+'px'});
			jQuery(this).find('.grid_col:last-child > .cols_wrapper > .row_bg_img_wrapper').css({'left':'0px','width':'auto','right':'-'+(jQuery(this).outerWidth() + row_bg_ofs.left - column_last_ofs.left - jQuery(this).find('.grid_col:last-child .cols_wrapper').outerWidth())+'px'});
			
		}
		catch (err){
			console.log("Unexpected" + err);
		}

	})
}

function cws_add_title_sep (){
	jQuery('.ce_title.und-title').each(function(){
		jQuery(this).append('<span class="title-separators"><span></span></span>');
	})
}

function cws_menu_bar () {
  jQuery(".menu-bar").on( 'click', function(){
    jQuery(".h-address-groups , .main-menu , .menu-bar").toggleClass("items-visible");
    return false;
  })
}

function cws_modules_state_init (){
	window.cws_modules_state = {
		"sync" : false,		
	}
}

function cws_revslider_class_add (){
	if (jQuery('.rev_slider_wrapper.fullwidthbanner-container').length) {
		jQuery('.rev_slider_wrapper.fullwidthbanner-container').next().addClass('benefits_after_slider');
		if (jQuery('.rev_slider_wrapper.fullwidthbanner-container').length && jQuery('.site-main main .benefits_cont:first-child').length) {
			if (jQuery('.site-main main .benefits_cont:first-child').css("margin-top").replace("px", "") < -90) {
				jQuery('.site-main main .benefits_cont:first-child').addClass('responsive-minus-margin');
			}
		}
	};
}

function cws_wpml_vertical_switcher(){
	
}

function cws_prlx_init_waiter (){
	var interval, layers, layer_ids, i, layer_id, layer_obj, layer_loaded;
	if ( window.cws_prlx == undefined ){
		return;
	}
	layers = cws_clone_obj( window.cws_prlx.layers );
	interval = setInterval( function (){
		layer_ids = Object.keys( layers );
		for ( i = 0; i < layer_ids.length; i++ ){
			layer_id = layer_ids[i];
			layer_obj = window.cws_prlx.layers[layer_id];
			layer_loaded = layer_obj.loaded;
			if ( layer_loaded ){
				delete layers[layer_id];
			}
		}
		if ( !Object.keys( layers ).length ){
			clearInterval ( interval );
		}
	}, 100);
}

function cws_is_rtl(){
	return jQuery("body").hasClass("rtl");
}

function cws_page_focus(){
	document.getElementsByTagName('html')[0].setAttribute('data-focus-chek', 'focused');
		window.addEventListener('focus', function() {
		document.getElementsByTagName('html')[0].setAttribute('data-focus-chek', 'focused');
	});
}

function boxed_var_init (){
	var body_el = document.body;
	var children = body_el.childNodes;
	var child_class = "";
	var match;
	window.boxed_layout = false;
	for ( var i=0; i<children.length; i++ ){
		child_class = children[i].className;
		if ( child_class != undefined ){
			match = /page_boxed/.test( child_class );
			if ( match ){
				window.boxed_layout = true;
				break;
			}
		}
	}
}

function reload_scripts(){
	wp_standard_processing();
	gallery_post_carousel_init();
	fancybox_init();
}

function is_visible_init (){
	jQuery.fn.is_visible = function (){
		return ( jQuery(this).offset().top >= jQuery(window).scrollTop() ) && ( jQuery(this).offset().top <= jQuery(window).scrollTop() + jQuery(window).height() );
	}
}

/* sticky */

function cws_sticky_light (){ 
	
if (jQuery('.sticky_header').length && !is_mobile() && !is_mobile_device ()) {
		jQuery('.sticky_header').removeClass('sticky_mobile_off');
		var lastScrollTop = 0;
		var percent = 100;
		var el_offset = jQuery('.header_wrapper_container .header_box').offset().top+10;
		var el_height = jQuery('.header_wrapper_container .header_box').innerHeight();
		var s_el_height = jQuery('.header_wrapper_container .header_box').innerHeight();

		var reset_height = el_height;
		jQuery(window).scroll(function(event){
		   var st = jQuery(this).scrollTop();
			if (sticky_menu_mode == 'smart') {
				if ( st > el_offset){
					if (st < lastScrollTop) {
					//TOP
					el_height = el_height + (st - lastScrollTop);
						if ( Math.abs(st - lastScrollTop) > 15){
							//FAST SCROLL
							jQuery('.sticky_header:not(.sticky_mobile_off)').removeAttr('style').addClass('sticky_active');
						}
						jQuery('.sticky_header:not(.sticky_mobile_off)').css({
						'-webkit-transform': 'translateY('+0+'px)',
						'-ms-transform': 'translateY('+0+'px)',
						'transform': 'translateY('+0+'px)',
						});
						jQuery('.sticky_header:not(.sticky_mobile_off)').removeClass('sticky_transition');
						jQuery('.sticky_header:not(.sticky_mobile_off)').addClass('sticky_active');
					} else {
						//BOTTOM
						el_height = reset_height;
							jQuery('.sticky_header:not(.sticky_mobile_off)').css({
							'-webkit-transform': 'translateY(-'+el_height+'px)',
							'-ms-transform': 'translateY(-'+el_height+'px)',
							'transform': 'translateY(-'+el_height+'px)',
							});
						jQuery('.sticky_header:not(.sticky_mobile_off)').addClass('sticky_transition');
						jQuery('.sticky_header:not(.sticky_mobile_off)').removeClass('sticky_active');
					}
					jQuery('.mobile_menu_wrapper').removeClass('active_mobile');
				} else {
					el_height = reset_height;
					jQuery('.sticky_header:not(.sticky_mobile_off)').css({
					'-webkit-transform': 'translateY(-'+el_height+'px)',
					'-ms-transform': 'translateY(-'+el_height+'px)',
					'transform': 'translateY(-'+el_height+'px)',
					});	
				}
			}

			if (sticky_menu_mode == 'simple'){
				if (st < el_offset) {
					jQuery('.sticky_header:not(.sticky_mobile_off)').removeClass('sticky_active');
					jQuery('.mobile_menu_wrapper').removeClass('active_mobile');
				} else {
					jQuery('.sticky_header:not(.sticky_mobile_off)').addClass('sticky_active');
					jQuery('.mobile_menu_wrapper').removeClass('active_mobile');
				}
			} 
			lastScrollTop = st;
		});

	}else{
		jQuery('.sticky_header').addClass('sticky_mobile_off');
	}

}

jQuery(window).resize( function (){
	cws_sticky_light ();
});


function get_logo_position(){
	if (jQuery(".site_header").length) {
		return /logo-\w+/.exec(jQuery(".site_header").attr("class"))[0];
	};
}


function is_mobile (){
	return window.innerWidth <= 768;
}

function is_mobile_device (){
	return jQuery("html").hasClass("touch");
}

/* sticky */

/* mobile menu */

function mobilecheck() {
	var check = false;
	(function(a) {
		if (/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) {
			check = true;
		}
	})(navigator.userAgent || navigator.vendor || window.opera);
	return check;
}
var clickevent = mobilecheck() ? ('ontouchstart' in document.documentElement ? "touchstart" : "click") : 'click';

var mobile_menu_controller_init_once = false;
function cws_mobile_menu_init(){

	if(is_mobile()){

		if(!mobile_menu_controller_init_once){
			if(jQuery('.header_wrapper_container header.site_header').hasClass('logo-in-menu')){
				jQuery('.header_wrapper_container header.site_header').find('li.header_logo_part a').clone().prependTo('.mobile_menu_header');
			}		

			mobile_menu_controller_init_once = true;
		}			
		jQuery(".header_wrapper_container .site_header").addClass("mobile_nav");
		jQuery(".body-cont").addClass("mobile_wrapper");
		jQuery('.header_wrapper_container .site_header .header_nav_part').addClass("mobile_nav");
		jQuery('.mobile_menu_header').css({'display':'flex'});
		mobile_nav_switcher_init();	
		mobile_nav_button_open();
	}
	else{
		jQuery(".header_wrapper_container .site_header").removeClass("mobile_nav opened");
		jQuery(".body-cont").removeClass("mobile_wrapper");
		jQuery('.header_wrapper_container .site_header .header_nav_part').removeClass("mobile_nav opened");
		reset_nav_mobile_menu();
	}	
}

function mobile_nav_switcher_init (){
	var nav_container = jQuery(".header_wrapper_container .site_header .header_nav_part"); 
	jQuery( ".header_wrapper_container .header_nav_part.mobile_nav .main-menu" ).css({'display':'none','margin-top':60});
	
	jQuery(".header_wrapper_container .header_nav_part.mobile_nav .mobile_menu_header .mobile_menu_switcher").on(clickevent,  function (){
		var open = "opened";
		var close = "close";
		var nav = jQuery( ".header_wrapper_container .header_nav_part .main-menu" );
		if ( nav_container.hasClass(open) ){	
			nav.stop(true).animate( {"margin-top": 60,"opacity" : 0}, 300,function(){
				nav_container.removeClass(open);
				nav.css({'display':'none'});
			});	
		}
		else{
			nav.show();
			nav.stop(true).animate( {"margin-top": 0,"opacity":1},300,function(){
				nav_container.addClass(open);
			});
			
		}
	});		
	if(nav_container.hasClass("opened")){
		jQuery( ".header_wrapper_container .header_nav_part .main-menu" ).css({'display':'inline-block','margin-top':0,'opacity':1}); 
	}
}

function mobile_nav_button_open(){
	jQuery(".header_wrapper_container .header_nav_part.mobile_nav .button_open").unbind(clickevent).on( clickevent, function (){
		var el = jQuery(this);
		el.siblings('.sub-menu').toggleClass('switch-menu-mobile');
		el.toggleClass('button_open_active');
	});	
}

function reset_nav_mobile_menu(){
	var nav = jQuery( ".header_wrapper_container .header_nav_part .main-menu" );
	jQuery( ".header_wrapper_container .header_nav_part .main-menu" ).css({'display':'inline-block'});
	nav.css({'margin-top':0,'opacity':1});
	jQuery('.mobile_menu_header').css({'display':'none'});
}

jQuery(window).resize(function(){
	cws_mobile_menu_init();
});

/* \mobile menu */

function cws_top_panel_search (){
		jQuery("#site_top_panel .search_icon,.header_nav_part .search_icon").click(function(){
			var el = jQuery(this);
			el.parents('#site_top_panel,.header_nav_part').find('.row_text_search .search-field').focus();
			el.parents('#site_top_panel,.header_nav_part').addClass( "show-search" );
		})	
		jQuery('#site_top_panel .search_back_button,.header_nav_part  .search_back_button').click(function(){
			var el = jQuery(this);
			el.parents('#site_top_panel,.header_nav_part').removeClass('show-search');
		})
}

/* carousel */

function count_carousel_items ( cont, layout_class_prefix, item_class, margin ){
	var re, matches, cols, cont_width, items, item_width, margins_count, cont_without_margins, items_count;
	if ( !cont ) return 1;
	layout_class_prefix = layout_class_prefix ? layout_class_prefix : 'grid-';
	item_class = item_class ? item_class : 'item';
	margin = margin ? margin : 30;
	re = new RegExp( layout_class_prefix + "(\\d+)" );
	matches = re.exec( cont.attr( "class" ) );
	cols = matches == null ? 1 : parseInt( matches[1] );
	cont_width = cont.outerWidth();


	items = cont.children( "." + item_class );
	item_width = items.eq(0).outerWidth();
	margins_count = cols - 1;

	cont_without_margins = cont_width - ( margins_count * margin ); /* margins = 30px */
	items_count = Math.floor( cont_without_margins / ( item_width -5 ) );	

	return items_count;
}


function gallery_post_carousel_init(){
	jQuery(".gallery_post_carousel").each(function (){
		var owl = jQuery(this);
		owl.owlCarousel({
			direction: directRTL,
			singleItem: true,
			slideSpeed: 300,
			navigation: false,
			pagination: false,
			afterUpdate : function() { setTimeout(function(){ jQuery('.isotope').length ? owl.closest('.isotope').isotope( 'layout' ) : '' }, 50) },
			afterInit : function() { setTimeout(function(){ jQuery('.isotope').length ? owl.closest('.isotope').isotope( 'layout' ) : '' }, 50) }
		});

		if (owl.attr('data-nav-init') == undefined) {
			owl.attr('data-nav-init',true);
		}else{
			owl.attr('data-nav-init',false);
		}

		if ( owl.attr('data-nav-init') == 'true' ){

			jQuery(this).parent().children(".carousel_nav.next").click(function (){
				owl.trigger('owl.next');
			});
			jQuery(this).parent().children(".carousel_nav.prev").click(function (){
				owl.trigger('owl.prev');
			});
		};			

	
	});
}
function widget_carousel_init(){
	jQuery( ".widget_carousel" ).each( function (){
		var cont = jQuery(this);
		cont.owlCarousel( {
			direction: directRTL,
			singleItem: true,
			slideSpeed: 300,
			navigation: true,
			pagination: false,
			items:1,
		});
		
	});
}

function cws_carousel_init_shortcode(){
	jQuery( ".cws_carousel_shortcode" ).each( function (){
		var cont = jQuery(this);
		if ( cont.find( ".gallery[class*='galleryid-']" ) ){
			cont.find( ".gallery[class*='galleryid-']" ).children( ":not(.gallery-item)" ).remove();
		}
		cont.owlCarousel( {
			direction: directRTL,
			singleItem: true,
			slideSpeed: 300,
			navigation: true,
			pagination: false,
			items:1,
		});
		
	});
}

jQuery.fn.cws_flex_carousel = function ( parent_sel, header_sel ){
	parent_sel = parent_sel != undefined ? parent_sel : '';
	header_sel = header_sel != undefined ? header_sel : '';
	jQuery( this ).each( function (){
		var owl = jQuery( this );
		var nav = jQuery(this).parents(parent_sel).find( ".carousel_nav_panel_container" );
		parent_sel = jQuery(this).parents(parent_sel);
		owl.cws_flex_carousel_controller( parent_sel, header_sel );
		if ( nav.length ){
			jQuery( ".next", nav ).click( function (){
				owl.trigger( "owl.next" );
			});
			jQuery( ".prev", nav ).click( function (){
				owl.trigger( "owl.prev" );
			});						
		}
		jQuery( window ).resize( function (){
			owl.cws_flex_carousel_controller( parent_sel, header_sel );
		});
	});
}

jQuery.fn.cws_flex_carousel_controller = function ( parent_sel, header_sel ){
	var owl = jQuery(this);
	var nav = jQuery( ".carousel_nav_panel_container", parent_sel );
	var show_hide_el = nav.siblings().length ? nav : nav.closest( header_sel );
	var show_pagination = false;
	if (show_hide_el.length) {
		var show_hide_el_display_prop = window.getComputedStyle( show_hide_el[0] ).display;
		show_pagination = false;
	}else{
		show_pagination = true;
	}



	var is_init = owl.hasClass( 'owl-carousel' );
	if ( is_init ){
		owl.data('owlCarousel').destroy();
		show_hide_el.css( 'display', 'none' );
	}
	var items_count = owl.children().length;
	var visible_items_count = count_carousel_items( owl );

	var args = {
		direction: directRTL,
		items: visible_items_count,
		slideSpeed: 300,
		navigation: false,
		pagination: show_pagination,
		responsive: true			
	}	

	if(owl.parent().parent().hasClass("news-pinterest")){
		if(owl.parent().parent().data( "columns" ) != 1){
			args.items = owl.parent().parent().data( "columns" );
			args.singleItem = false;
		}
		if(owl.parent().parent().data( "autoplay" ) == 1){
			args.autoPlay = true;
		}
		if(owl.parent().parent().data( "nav" ) ==1){
			args.navigation = true;
		}
		if(owl.parent().parent().hasClass("bullets_nav")){
			args.pagination = true;
		}
	}

	if(jQuery(this).data( "columns" ) != 1){
		args.items = jQuery(this).data( "columns" );
		args.singleItem = false;
	}
	if(jQuery(this).data( "autoplay" ) == 1){
		args.autoPlay = true;
	}
	if(jQuery(this).data( "nav" ) ==1){
		args.navigation = true;
	}
	if(owl.hasClass("bullets_nav")){
		args.pagination = true;
	}

	if ( items_count > visible_items_count ){
		if(owl.hasClass("portfolio_carousel")){
			args.addClassActive = true;
			args.center = true;	
			args.loop = true;
			switch (jQuery(this).data( "columns" )) {
			  case 4:
					args.itemsCustom = [
						[0, 1],
						[450, 1],
						[600, 1],
						[680, 2],
						[780, 2],
						[1000, 2],
						[1200, 4],
		        	];	
			    break;
			  case 3:
					args.itemsCustom = [
						[0, 1],
						[450, 1],
						[600, 1],
						[700, 1],
						[1000, 2],
						[1200, 2],
						[1210, 3],
		        	];
			    break;
			  case 2:
					args.itemsCustom = [
						[0, 1],
						[450, 1],
						[600, 1],
						[680, 2],
						[780, 2],
						[1000, 2],
						[1200, 2],
		        	];
			    break;
			  case 1:
					args.itemsCustom = [
						[0, 1],
						[450, 1],
						[600, 1],
						[700, 1],
						[1000, 1],
						[1200, 1],
		        	];
			    break;
			  default:
					args.itemsCustom = [
						[0, 1],
						[450, 1],
						[600, 1],
						[700, 2],
						[1000, 3],
						[1200, 3],
		        	];
			}
			owl.owlCarousel( args );
		}
		else{
			if(owl.parent().parent().hasClass("news-pinterest")){
				if(owl.parent().parent().data( "columns" ) != 1){
					args.items = owl.parent().parent().data( "columns" );
					args.singleItem = false;
				}
				
			}
			owl.owlCarousel( args );
			if (show_hide_el.length) {
				show_hide_el.css( 'display', show_hide_el_display_prop );
			}			
		}
	}	

}

function cws_sc_carousel_init (){
	jQuery( ".cws_sc_carousel" ).each( cws_sc_carousel_controller );
	window.addEventListener( 'resize', function (){
		jQuery( ".cws_sc_carousel" ).each( cws_sc_carousel_controller );		
	}, false);
}


var first = true;
function cws_sc_carousel_controller (){
	var el = jQuery( this );
	var bullets_nav = el.hasClass( "bullets_nav" );
	var content_wrapper = jQuery( ".cws_wrapper", el );
	var owl = content_wrapper;
	var content_top_level = content_wrapper.children();
	var nav = jQuery( ".carousel_nav_panel", el );
	var cols = el.data( "columns" );
	var items_count, grid_class, col_class, items, is_init, matches, args, page_content_section, sb_count;
	page_content_section = jQuery( ".page_content" );

	if ( page_content_section.hasClass( "double_sidebar" ) ){
		sb_count = 2;
	}
	else if ( page_content_section.hasClass( "single_sidebar" ) ){
		sb_count = 1;
	}
	else{
		sb_count = 0;
	}
	if ( content_top_level.is( ".gallery[class*='galleryid-']" ) ){		

		owl = content_top_level.filter( ".gallery[class*='galleryid-']" );
		is_init = owl.hasClass( "owl-carousel" );
		if ( is_init ) owl.data( "owlCarousel" ).destroy();
		if(content_top_level.parent().parent().parent().parent().attr('class') == 'cws-widget'){
			if(first){
				owl.children( ":not(.gallery-item)" ).remove();
				first = false;
			}
			var divs = owl.children( ".gallery-item" );

			for(var i = 0; i < divs.length; i+=2) {
				divs.slice(i, i+2).wrapAll("<div class='new-item-gallery-item'></div>");
			}
			owl.find(".new-item-gallery-item").children( ":not(.gallery-item)" ).remove();
			
			var matche_txt = owl.attr("class");
			var res_count = matche_txt.match(/gallery-columns-\d+/g);
			items_count = parseInt(res_count.toString().substr(-1));
		}
		else{
			owl.children( ":not(.gallery-item)" ).remove();
			items_count = count_carousel_items( owl, "gallery-columns-", "gallery-item" );
		}		
		
	}
	else if ( content_top_level.is( ".woocommerce" ) ){
		owl = content_top_level.children( ".products" );
		is_init = owl.hasClass( "owl-carousel" );
		if ( is_init ) owl.data( "owlCarousel" ).destroy();
		matches = /columns-\d+/.exec( content_top_level.attr( "class" ) );
		grid_class = matches != null && matches[0] != undefined ? matches[0] : '';
		owl.addClass( grid_class );
		items_count = count_carousel_items( owl, "columns-", "product" );
		owl.removeClass( grid_class );
	}
	else if ( content_top_level.is( "ul" ) ){
		owl = content_top_level;
		is_init = owl.hasClass( "owl-carousel" );
		if ( is_init ) owl.data( "owlCarousel" ).destroy();
		items = owl.children();
		grid_class = "crsl-grid-" + cols;
		col_class = "grid_col_" + Math.round( 12 / cols );
		owl.addClass( grid_class );
		if ( !items.hasClass( "item" ) ) items.addClass( "item" )
		items.addClass( col_class );
		items_count = count_carousel_items( owl, "crsl-grid-", "item" );
		owl.removeClass( grid_class );
		items.removeClass( col_class );
	}
	else {
		is_init = owl.hasClass( "owl-carousel" );
		if ( is_init ) owl.data( "owlCarousel" ).destroy();
		items = owl.children();
		grid_class = "crsl-grid-" + cols;
		col_class = "grid_col_" + Math.round( 12 / cols );
		owl.addClass( grid_class );
		if ( !items.hasClass( "item" ) ) items.addClass( "item" )
		items.addClass( col_class );
		items_count = count_carousel_items( owl, "crsl-grid-", "item" );
		owl.removeClass( grid_class );
		items.removeClass( col_class );
	}
	args = {
		direction: directRTL,
		slideSpeed: 300,
		navigation: true,
		pagination: bullets_nav
	}
	switch ( items_count ){
		case 5:
			if ( sb_count == 2 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 5]
				];
			}
			else if ( sb_count == 1 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 5]
				];
			}
			else{
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,5]
				];	
			}
			break;
		case 4:
			if ( sb_count == 2 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,1],
					[1170, 4]
				];
			}
			else if ( sb_count == 1 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,1],
					[1170, 4]
				];
			}
			else{
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,1],
					[1170, 4]
				];
			}
			break;
		case 3:
			if ( sb_count == 2 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 3]
				];
			}
			else if ( sb_count == 1 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 3]
				];
			}
			else{
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,3]
				];	
			}
			break;
		case 2:
			if ( sb_count == 2 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 2]
				];
			}
			else if ( sb_count == 1 ){
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 2]
				];
			}
			else{
				args.itemsCustom = [
					[0,1],
					[750,1],
					[980,2],
					[1170, 2]
				];	
			}
			break;
		
		default:
			args.singleItem = true;
	}
	if(jQuery(this).hasClass('wrapper-testimonials')){
		if(jQuery(this).data( "columns" ) != 1){
			args.items = jQuery(this).data( "columns" );
			args.singleItem = false;
		}
		else{
			args.singleItem = true;
		}
		if(jQuery(this).data( "autoplay" ) == 1){
			args.autoPlay = true;
		}
	}
	if(jQuery(this).hasClass('portfolio-section')){
		if(jQuery(this).data( "columns" ) != 1){
			args.items = jQuery(this).data( "columns" );
			args.singleItem = false;
		}
		if(jQuery(this).data( "autoplay" ) == 1){
			args.autoPlay = true;
		}
		args.itemsCustom = false;	
	}
	if(jQuery(this).data( "columns" ) != 1){
		args.items = jQuery(this).data( "columns" );
		args.singleItem = false;
		//args.itemsCustom = false;
	}
	if(jQuery(this).data( "autoplay" ) == 1){
		args.autoPlay = true;
	}
	if(jQuery(this).hasClass( "bullets_nav" )){
		args.navigation = false;
	}
	
	if(jQuery(this).data( "columns" ) == 1){
		jQuery(this).addClass("gallery-columns-data-1");
	}
	owl.owlCarousel(args);
	if ( nav.length ){
		jQuery( ".next", nav ).click( function (){
			owl.trigger( "owl.next" );
		});
		jQuery( ".prev", nav ).click( function (){
			owl.trigger( "owl.prev" );
		});
	}	
}

function twitter_carousel_init (){
	jQuery( ".tweets_carousel" ).each( function (){
		var el = jQuery( this );
		var owl = jQuery( ".cws_wrapper", el );
		owl.owlCarousel({
			direction: directRTL,
			singleItem: true,
			slideSpeed: 300,
			navigation: false,
			autoHeight:true,
			pagination: true
		});
	});
}

/* \carousel */

function wp_standard_processing (){
	var galls;
	jQuery( "img[class*='wp-image-']" ).each( function (){
		var canvas_id;
		var el = jQuery( this );
		var parent = el.parent( "a" );
		var align_class_matches = /align\w+/.exec( el.attr( "class" ) );
		var align_class = align_class_matches != null && align_class_matches[0] != undefined ? align_class_matches[0] : "";
		var added_class = "cws_img_frame";
		if ( align_class.length ){
			if ( parent.length ){
				el.removeClass( align_class );
			}
			added_class += " " + align_class;
		}
		if ( parent.length ){
			parent.addClass( added_class );
			parent.children().wrapAll( "<div class='cws_blur_wrapper' />" );
		}
	});
	galls = jQuery( ".gallery[class*='galleryid-']" );
	if ( galls.length ){
		galls.each( function (){
			var gall = jQuery( this );
			var gall_id = cws_unique_id ( "wp_gallery_" );
			jQuery( "a", gall ).attr( "data-fancybox-group", gall_id );
		});
	}
	jQuery( ".gallery-icon a[href*='.jpg'], .gallery-icon a[href*='.jpeg'], .gallery-icon a[href*='.png'], .gallery-icon a[href*='.gif'], .cws_img_frame[href*='.jpg'], .cws_img_frame[href*='.jpeg'], .cws_img_frame[href*='.png'], .cws_img_frame[href*='.gif']" ).fancybox();
}

function cws_unique_id ( prefix ){
	var prefix = prefix != undefined && typeof prefix == 'string' ? prefix : "";
	var d = new Date();
	var t = d.getTime();
	var unique = Math.random() * t;	
	var unique_id = prefix + unique;
	return unique_id;	
}

/* fancybox */

function fancybox_init (){
	jQuery(".fancy").fancybox();
}

/* \fancybox */

/* wow */

function wow_init (){
	if (jQuery('.wow').length) {
		new WOW().init();	
	};	
}

/* wow */

/* isotope */

function isotope_init (){
	if (jQuery(".isotope").length) {
		jQuery(".isotope").isotope({
			itemSelector: ".item", 
		});	
	}
}

/* \isotope */

/* load more */
var wait_load = false;
function load_more_init (){
	jQuery( document ).on( "click", ".cws_load_more", function (e){
		e.preventDefault();
		if ( wait_load ) return;
		var el = jQuery(this);
		var url = el.attr( "href" );
		var paged = parseInt( el.data( "paged" ) );
		var max_paged = parseInt( el.data( "max-paged" ) );
		var template = el.data( "template" );
		var item_cont = el.siblings( ".grid" );
		var isotope = false;
		var args = { ajax : "true", paged : paged, template: template };
		if ( !item_cont.length ) return;
		el.closest('.cws_wrapper').find('.portfolio_loader_wraper').show();
		wait_load = true;
		jQuery.post( url, args, function ( data ){
			var new_items = jQuery(data).filter( '.item' );
			if ( !new_items.length ) return;
			new_items.css( 'display' , 'none' );
			jQuery(item_cont).append( new_items );
			el.closest('.cws_wrapper').find('.portfolio_loader_wraper').hide();
			el.closest('.cws_wrapper').find('.portfolio_loader_wraper').removeClass('btn_cws_load_more');
			wait_load = false;
			var img_loader = imagesLoaded( jQuery(item_cont) );
			img_loader.on ('always', function (){
				var load_owl = false;
				if(typeof owlCarousel != 'function'){					
					jQuery.getScript(custom.templateDir + "/js/owl.carousel.js").done(function( s, Status ) {
						reload_scripts();	
					});
					load_owl = true;
				}else{
					load_owl = true;
				}

				if(load_owl){
					new_items.css( 'display', 'block' );
					if ( jQuery(item_cont).isotope ){
						jQuery(item_cont).isotope( 'appended', new_items);
						jQuery(item_cont).isotope( 'layout' );
					}
					if (Retina.isRetina()) {
						jQuery(window.retina.root).trigger( "load" );
					}
					if ( paged == max_paged ){
						el.fadeOut( { duration : 300, complete : function (){
							el.remove();
						}})
					}
					else{
						el.data( "paged", String( paged + 1 ) );
					}					
				}

			});
		});
	});
}

/* \load more */


/* Image Circle Wrap */
function imageCircleWrap(){
	jQuery("img").each(function(){
		if(jQuery(this).hasClass("size-new-size") || jQuery(this).hasClass("new-size-medium") || jQuery(this).hasClass("new-size-small") || jQuery(this).hasClass("new-size") || jQuery(this).hasClass("size-new-size-medium") || jQuery(this).hasClass("size-new-size-small")){
			if(!jQuery(this).parent().hasClass('new-size-div')){
				jQuery(this).wrap("<div class='new-size-div'></div>");
			}
			jQuery(this).hasClass("aligncenter") ? jQuery(this).parent().parent().css({"text-align":"center"}) : "";
			jQuery(this).hasClass("alignright") ? jQuery(this).parent().parent().css({"text-align":"right"}) : "";
			jQuery(this).hasClass("alignleft") ? jQuery(this).parent().parent().css({"text-align":"left"}) : "";
		}
	});
	jQuery(".gallery .gallery-item").each(function(){
		if(!jQuery(this).find(".new-size-div")){
			jQuery(this).addClass("standard-gallery");
		}
	});
}

/* widget archives hierarchy */

function widget_archives_hierarchy_init (){
	widget_archives_hierarchy_controller ( ".cws-widget>ul li", "ul.children", "parent_archive", "widget_archive_opener" );
	widget_archives_hierarchy_controller ( ".cws-widget .menu>li", "ul.sub-menu", "has_children", "opener" );
}

function widget_archives_hierarchy_controller ( list_item_selector, sublist_item_selector, parent_class, opener_class ){
	jQuery( list_item_selector ).has( sublist_item_selector ).each( function (){
		jQuery( this ).addClass( parent_class );
		var sublist = jQuery( this ).children( sublist_item_selector ).first();
		var level_height = jQuery( this ).outerHeight() - sublist.outerHeight();
		jQuery(this).append( "<span class='fa fa-angle-right " + opener_class + "' style='line-height:" + (level_height - 1) + "px;'></span>" );
	});
	jQuery( list_item_selector + ">" + sublist_item_selector ).css( "display", "none" );
	jQuery( document ).on( "click", "." + opener_class, function (){
		var el = jQuery(this);
		var sublist = el.siblings( sublist_item_selector );
		if ( !sublist.length ) return;
		sublist = sublist.first();
		el.toggleClass( "active" );
		sublist.slideToggle( 300 );
	});
}

/* \widget archives hierarchy */

/* select 2 */

function select2_init (){
	if(!jQuery("body").hasClass("cwspb-active")){
		jQuery("select").select2();
	}
	
}

/* \select 2 */

/* tabs */

function cws_tabs_init (){
	jQuery.fn.cws_tabs = function (){
		jQuery(this).each(function (){
			var parent, tabs, tab_items_container, current_class, active_class;
			parent = jQuery(this);
			tabs = parent.find("[role='tab']");
			tab_items_container = parent.find("[role='tabpanel']").parent();
			current_class = "current";
			active_class = "active";

			tabs.each(function(index){
				if(!tabs.hasClass('active')){
					tabs.eq(0).addClass('active');
					tabs.parent().siblings('.tab_sections').find('.tab_section').css({'display':'none'}).eq(0).css({'display':'block'});

				}
				if(jQuery(this).hasClass('active')){
					jQuery(this).parent().siblings('.tab_sections').find('.tab_section').css({"display":'none'}).eq(index).css({'display':'block'});
				}


				jQuery(this).on("click", function (){
					var el, active_el, current_el, item, current_ind, current_item, active_el_id;
					el = jQuery(this);
					active_el = el.siblings( "." + active_class ).eq(0);
					current_el = jQuery(this);

					if ( !current_el.length ) return;
					active_el.removeClass( active_class );
					el.addClass( active_class );

					tab_items_container = el.closest("[role='tab']").parent().parent().find('[role="tabpanel"]').parent();
					item = tab_items_container.find("[tabindex='"+this.tabIndex+"']");
					current_ind = current_el.attr("tabindex");
					active_el_id = active_el.attr("tabindex");

					current_item = item.siblings("[tabindex='"+active_el_id+"']").eq(0);
					current_el.removeClass( current_class );
					el.addClass( current_class );		
					current_item.stop().fadeOut("25",'swing',function(){
						item.css({'display':'none'});
						if(el.hasClass('active')){
							item.stop().fadeIn("25",'swing',function(){				
							});
						}						
						
					});
				});
			});
		});
	}
}

function cws_accordion_init (){
	jQuery.fn.cws_accordion = function () {
		jQuery(this).each(function (){
			var sections = jQuery(this).find(".accordion_section");
			sections.each( function (index, value){
				if(jQuery(this).hasClass('active')){
					jQuery(this).find(".accordion_content").css({"display": "block","opacity":1});
				}
				if(!sections.hasClass('active')){
					sections.eq(0).addClass('active');
					sections.eq(0).find(".accordion_content").css({"display": "block","opacity":1});
				}
				var section_index = index;
				jQuery(this).on("click", function (){
					jQuery(this).find(".accordion_content").slideDown("300");
					sections.eq(section_index).addClass("active");
					sections.eq(section_index).siblings().removeClass("active").find(".accordion_content").slideUp("300");
				});
			});
		});
	}
}

function cws_toggle_init (){
	jQuery.fn.cws_toggle = function ( item_class, opener_class, toggle_section_class ){
		var i=0;
		jQuery(this).each( function (){
			i++;
			var sections = jQuery(this).find("."+item_class);
			var j=0;
			sections.each( function (index, value){
				j++;
				if(jQuery(this).hasClass('active')){
					jQuery(this).find('.accordion_content').css({'display':'block',"opacity":1});
				}
				var section_index = index;
				jQuery(this).find("."+opener_class).eq(0).on("click", function (){
					if (!sections.eq(section_index).hasClass("active")){
						sections.eq(section_index).addClass("active");
						sections.eq(section_index).find("."+toggle_section_class).eq(0).slideDown("300").css({'opacity':1});
					}
					else{
						sections.eq(section_index).removeClass("active");
						if(!jQuery('body').hasClass('cwspb-active')){
							sections.eq(section_index).find("."+toggle_section_class).eq(0).slideUp("300").css({'opacity':0});			
						}
					}
				});
			});
		});
	}
}

/* \tabs */


/* message box */

function cws_message_box_init (){
	jQuery( document ).on( 'click', '.cws_msg_box.closable .cls_btn', function (){
		var cls_btn = jQuery(this);
		var el = cls_btn.closest( ".cws_msg_box" );
		el.fadeOut( function (){
			el.remove();
		});
	});
}

/* \message box */

/* portfolio ajax */

function cws_portfolio_pagination_init (){
	var els = jQuery( ".cws_portfolio .pagination" );
	els.each( function (){
		var pagination = jQuery( this );
		cws_portfolio_pagination ( pagination );
	});

	jQuery('.cws_portfolio_fw .pagination').each( function (){
		var pagination = jQuery( this );
		cws_portfolio_pagination ( pagination , true );
	});
}

function cws_portfolio_pagination ( pagination , is_fw ){
	if ( pagination == undefined ) return;
	if (is_fw != undefined){ 
		is_fw == is_fw ;
	}else{
		is_fw == false ;
	}
	var old_page_links = pagination.find( ".page_links" );
	var items = old_page_links.find( ".page-numbers" ).not( ".current" );
	if (is_fw) {
		var parent = pagination.closest( ".cws_portfolio_fw" );
	}else{
		var parent = pagination.closest( ".cws_portfolio" );
	}
	
	if (is_fw) {
		var grid = parent.find( ".grid_fw" );
	}else{
		var grid = parent.find( ".cws_portfolio_items" );
	}

	if (is_fw) {
		var ajax_data_input = parent.find( "input.cws_portfolio_fw_ajax_data" );
	}else{
		var ajax_data_input = parent.find( "input.cws_portfolio_ajax_data" );
	}


	
	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		var action_func;
		ajax_data['url'] = url;		
		if (is_fw) {
			action_func = 'cws_portfolio_fw_pagination';
		}else{
			action_func = 'cws_portfolio_pagination';
		}
		item.on( "click", function ( e ){
			if (is_fw) {
				pagination.closest('.cws_portfolio_fw').find('.portfolio_loader_wraper').show().addClass('show_bottom');	
			}else{
				pagination.closest('.cws_portfolio').find('.portfolio_loader_wraper').show().addClass('show_bottom');	
			}
			e.preventDefault();
			jQuery.post( custom.ajaxurl, {
				"action" : action_func,
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var parent_offset = parent.offset().top;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_page_links = jQuery( ".pagination .page_links", jQuery( data ) );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );

				if (ajax_data['pagination_style'] != 'load_more') {
					grid.isotope( 'remove', old_items );
					if ( window.scrollY > parent_offset ){
						jQuery( 'html, body' ).stop().animate({
							scrollTop : parent_offset
						}, 300);
					}					
				}
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					if (is_fw) {
						pagination.closest('.cws_portfolio_fw').find('.portfolio_loader_wraper').removeClass('show_bottom').hide();	
					}else{
						pagination.closest('.cws_portfolio').find('.portfolio_loader_wraper').removeClass('show_bottom').hide();	
					}
					grid.isotope( 'layout' );
					old_page_links.fadeOut( function (){
						old_page_links.remove();
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							pagination.append( new_page_links );
							new_page_links.fadeIn();
							if (is_fw){
								cws_portfolio_pagination ( pagination , true );
							}else{
								cws_portfolio_pagination ( pagination );
							}
						}
						else{
							pagination.remove();
						}
					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }
						fancybox_init ();
					});
				});
			});
		});
	});
}

function cws_portfolio_filter_init (){
	var els = jQuery( ".cws_portfolio .cws_portfolio_filter" );
	els.each( function (){
		var el = jQuery( this );
		var parent = el.closest( ".cws_portfolio" );
		var grid = parent.find( ".cws_portfolio_items" );
		var ajax_data_input = parent.find( "input.cws_portfolio_ajax_data" );
		var event_select;
		
		if ( el.hasClass('fw_filter') ) {
			el = el.children("a");
			event_select = 'click';
		} else {
			el = jQuery( this );
			event_select = 'change';
		}
		
		var befores = false;

		el.on( event_select, function (e){
			var val;

			if ( event_select === "click" ) {
				e.preventDefault();
				jQuery( this ).addClass('active').siblings().removeClass('active');
				val = jQuery( this ).attr('data-filter');
			} else {
				val = el.val();
			}

			var ajax_data = JSON.parse( ajax_data_input.val() );
			ajax_data["filter"] = val;
			var old_pagination = parent.find( ".pagination" );
			var old_page_links = jQuery( ".page_links", old_pagination );
			jQuery.post( custom.ajaxurl, {
				"action" : "cws_portfolio_filter",
				"data" : ajax_data,
				beforeSend: function( xhr ) {
    				jQuery('.cws_wrapper').find('.portfolio_loader_wraper').show();
  				},
			}, function ( data, status ){


				var img_loader;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_pagination = jQuery( ".pagination", jQuery( data ) );
				var new_page_links = jQuery( ".page_links", new_pagination );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					jQuery('.cws_wrapper').find('.portfolio_loader_wraper').hide();
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
					if ( old_pagination.length ){
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							old_page_links.fadeOut( function (){
								old_page_links.remove();
								old_pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_portfolio_pagination ( old_pagination );
							});
						}
						else{
							old_pagination.fadeOut( function (){
								old_pagination.remove();
							});
						}
					}
					else{
						if ( new_page_links_exists ){
							new_pagination.css( "display", "none" );
							parent.append( new_pagination );
							new_pagination.fadeIn();
							cws_portfolio_pagination ( new_pagination );
						}
					}
				    if (Retina.isRetina()) {
			        	jQuery(window.retina.root).trigger( "load" );
				    }
					fancybox_init ();
				});
			});
		});
	});
}

function cws_portfolio_single_carousel_init (){
	jQuery( ".cws_portfolio.single.related" ).each( function (){
		var parent = jQuery(this);
		var grid = jQuery( ".cws_portfolio_items", parent );
		var ajax_data_input = jQuery( "#cws_portfolio_single_ajax_data", parent );
		var carousel_nav = jQuery( ".carousel_nav_panel", parent );
		if ( !carousel_nav.length ) return;
		jQuery( ".prev,.next", carousel_nav ).on( "click", function (){
			var el = jQuery( this );
			var action = el.hasClass( "prev" ) ? "prev" : "next";
			var ajax_data = JSON.parse( ajax_data_input.val() );
			var current = ajax_data['current'];
			var all = ajax_data['related_ids'];
			var next_ind;
			var next;
			for ( var i=0; i<all.length; i++ ){
				if ( all[i] == current ){
					if ( action == "prev" ){
						if ( i <= 0 ){
							next_ind = all.length-1;
						}
						else{
							next_ind = i-1;
						}
					}
					else{
						if ( i >= all.length-1 ){
							next_ind = 0;
						}
						else{
							next_ind = i+1
						}
					}
					break;
				}
			}
			if ( typeof next_ind != "number" || typeof all[next_ind] == undefined ) return;
			next = all[next_ind];
			jQuery.post( custom.ajaxurl, {
				'action' : 'cws_portfolio_single',
				'data' : {
					'initial_id' : ajax_data['initial'],
					'requested_id' : next
				}
			}, function ( data, status ){
				
				var animation_config, old_el, new_el, hiding_class, showing_class, delay, img_loader;
				ajax_data['current'] = next;
				ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
				animation_config = {
					'prev' : {
						'in' : 'fadeInLeft',
						'out' : 'fadeOutRight'
					},
					'next' : {
						'in' : 'fadeInRight',
						'out' : 'fadeOutLeft'
					},
					'delay' : 150
				};
				old_el = jQuery( ".cws_portfolio_items .item" , parent );
				new_el = jQuery( ".item", jQuery( data ) );
				hiding_class = "animated " + animation_config[action]['out'];
				showing_class = "animated " + animation_config[action]['in'];
				delay = animation_config['delay'];
				new_el.css( "display", "none" );
				grid.append( new_el );
				img_loader = imagesLoaded( grid );
				img_loader.on( 'always', function (){
					old_el.addClass( hiding_class );
					setTimeout( function (){
						old_el.remove();
						new_el.addClass( showing_class );
						new_el.css( "display", "block" );

					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }
					    fancybox_init();

					}, delay );
				});
			});
		});
	});
}

/* portfolio ajax */

/* full width portfolio ajax */

function cws_portfolio_fw_filter_init (){
var els = jQuery( ".cws_portfolio_fw .cws_portfolio_filter" );
	els.each( function (){
		var el = jQuery( this );
		var parent = el.closest( ".cws_portfolio_fw" );
		var grid = parent.find( ".grid_fw" );
		var ajax_data_input = parent.find( "input.cws_portfolio_fw_ajax_data" );
		var event_select;
		
		if ( el.hasClass('fw_filter') ) {
			el = el.children("a");
			event_select = 'click';
		} else {
			el = jQuery( this );
			event_select = 'change';
		}
		

		el.on( event_select, function (e){
			var val;

			if ( event_select === "click" ) {
				e.preventDefault();
				jQuery( this ).addClass('active').siblings().removeClass('active');
				val = jQuery( this ).attr('data-filter');
			} else {
				val = el.val();
			}
			var ajax_data = JSON.parse( ajax_data_input.val() );
			ajax_data["filter"] = val;
			var old_pagination = parent.find( ".pagination" );
			var old_page_links = jQuery( ".page_links", old_pagination );
			jQuery.post( custom.ajaxurl, {
				"action" : "cws_portfolio_fw_filter",
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_pagination = jQuery( ".pagination", jQuery( data ) );
				var new_page_links = jQuery( ".page_links", new_pagination );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
					if ( old_pagination.length ){
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							old_page_links.fadeOut( function (){
								old_page_links.remove();
								old_pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_portfolio_pagination ( old_pagination , true );
							});
						}
						else{
							old_pagination.fadeOut( function (){
								old_pagination.remove();
							});
						}
					}
					else{
						if ( new_page_links_exists ){
							new_pagination.css( "display", "none" );
							parent.append( new_pagination );
							new_pagination.fadeIn();
							cws_portfolio_pagination ( new_pagination , true );
						}
					}
				    if (Retina.isRetina()) {
			        	jQuery(window.retina.root).trigger( "load" );
				    }
					fancybox_init ();
				});
			});
		});
	});
}

/* \full width portfolio ajax */

/* portfolio ajax */

function cws_blog_pagination_init (){
	var els = jQuery( ".shortcode-blog-wrapper .pagination" );
	els.each( function (){
		var pagination = jQuery( this );
		cws_blog_pagination ( pagination );
	});
}

function cws_blog_pagination ( pagination , is_fw ){
	if ( pagination == undefined ) return;
	if (is_fw != undefined){ 
		is_fw == is_fw ;
	}else{
		is_fw == false ;
	}
	var old_page_links = pagination.find( ".page_links" );
	var items = old_page_links.find( ".page-numbers" ).not( ".current" );

	var parent = pagination.closest( ".shortcode-blog-wrapper" );

	var grid = parent.find( ".shortcode-blog .grid" );

	var ajax_data_input = parent.find( "input.cws_blog_ajax_data" );

	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		var action_func;
		ajax_data['url'] = url;		

		action_func = 'plugin_get_post';

		item.on( "click", function ( e ){
			parent.find('.portfolio_loader_wraper').show();
			e.preventDefault();
			jQuery.post( custom.ajaxurl, {
				"action" : action_func,
				"data" : ajax_data
			}, function ( data, status ){

				var load_owl = false;
				if(typeof owlCarousel != 'function'){					
					jQuery.getScript(custom.templateDir + "/js/owl.carousel.js").done(function( s, Status ) {
						reload_scripts();		
					});
					load_owl = true;	
				}else{
					load_owl = true;
				}

				if(load_owl){
					var img_loader;
					var parent_offset = parent.offset().top;
					var old_items = jQuery( ".item", grid );
					var new_items = jQuery( ".item", jQuery( data ) );
						
					var new_page_links = jQuery( ".pagination .page_links", jQuery( data ) ).context.last();
					new_page_links = jQuery(".page_links",new_page_links);
					var new_page_links_exists = Boolean( new_page_links.children().length );
					new_items.css( "display", "none" );
					
					if (ajax_data['pagination_style'] != 'load_more') {
					
						grid.isotope( 'remove', old_items );
						jQuery( ".portfolio_loader_wraper", grid ).remove();
						if ( window.scrollY > parent_offset ){
							jQuery( 'html, body' ).stop().animate({
								scrollTop : parent_offset
							}, 300);
						}									
								
					}
					grid.append( new_items );

					img_loader = imagesLoaded( grid );
					img_loader.on( "always", function (){
						parent.find('.portfolio_loader_wraper').hide();	
						grid.isotope( 'appended', new_items );
						grid.isotope( 'layout' );
						old_page_links.fadeOut( function (){
							old_page_links.remove();
							if ( new_page_links_exists ){
								new_page_links.css( "display", "none" );
								pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_blog_pagination ( pagination );
								
							}
							else{
								pagination.remove();
							}
						    if (Retina.isRetina()) {
					        	jQuery(window.retina.root).trigger( "load" );
						    }
							fancybox_init ();
						});
					});
				}


			});
		});
	});
}








/* ourteam ajax */

function cws_ourteam_pagination_init (){
	var els = jQuery( ".cws_ourteam .pagination" );
	els.each( function (){
		var pagination = jQuery( this );
		cws_ourteam_pagination( pagination );
	});	
}

function cws_ourteam_pagination ( pagination ){
	if ( pagination == undefined ) return;
	var old_page_links = pagination.find( ".page_links" );
	var items = old_page_links.find( ".page-numbers" ).not( ".current" );
	var parent = pagination.closest( ".cws_ourteam" );
	var grid = parent.find( ".cws_ourteam_items" );
	var ajax_data_input = parent.find( "input.cws_ourteam_ajax_data" );
	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		ajax_data['url'] = url;
		item.on( "click", function ( e ){
			e.preventDefault();
			jQuery.post( custom.ajaxurl, {
				"action" : "cws_ourteam_pagination",
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var parent_offset = parent.offset().top;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_page_links = jQuery( ".pagination .page_links", jQuery( data ) );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				if ( window.scrollY > parent_offset ){
					jQuery( 'html, body' ).stop().animate({
						scrollTop : parent_offset
					}, 300);
				}
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					old_page_links.fadeOut( function (){
						old_page_links.remove();
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							pagination.append( new_page_links );
							new_page_links.fadeIn();
							cws_ourteam_pagination ( pagination );
						}
						else{
							pagination.remove();
						}
					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }
						fancybox_init ();
					});
				});

			});
		});
	});
}

function cws_ourteam_filter_init (){
	var els = jQuery( ".cws_ourteam select.cws_ourteam_filter" );
	els.each( function (){
		var el = jQuery( this );
		var parent = el.closest( ".cws_ourteam" );
		var grid = parent.find( ".cws_ourteam_items" );
		var ajax_data_input = parent.find( "input.cws_ourteam_ajax_data" );
		el.on( "change", function (){
			var val = el.val();
			var ajax_data = JSON.parse( ajax_data_input.val() );
			ajax_data["filter"] = val;
			var old_pagination = parent.find( ".pagination" );
			var old_page_links = jQuery( ".page_links", old_pagination );
			jQuery.post( custom.ajaxurl, {
				"action" : "cws_ourteam_filter",
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_pagination = jQuery( ".pagination", jQuery( data ) );
				var new_page_links = jQuery( ".page_links", new_pagination );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
					if ( old_pagination.length ){
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							old_page_links.fadeOut( function (){
								old_page_links.remove();
								old_pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_ourteam_pagination ( old_pagination );
							});
						}
						else{
							old_pagination.fadeOut( function (){
								old_pagination.remove();
							});
						}
					}
					else{
						if ( new_page_links_exists ){
							new_pagination.css( "display", "none" );
							parent.append( new_pagination );
							new_pagination.fadeIn();
							cws_ourteam_pagination ( new_pagination );
						}
					}
				    if (Retina.isRetina()) {
			        	jQuery(window.retina.root).trigger( "load" );
				    }
					fancybox_init ();
				});
			});
		});
	});
}

/* \ourteam ajax */

/* parallax */

function cws_parallax_init(){
	if (jQuery( ".cws_prlx_section" ).length) {
		jQuery( ".cws_prlx_section" ).cws_prlx();
	};
}

/* \parallax */

/* milestone */

function cws_milestone_init (){
	jQuery.fn.cws_milestone = function (){
		jQuery(this).each( function (){		
			var el = jQuery(this);
			var number_container = el.find(".milestone_number");
			var done = false;
			if (number_container.length){
				if ( !done ) done = milestone_controller (el, number_container);
				jQuery(window).scroll(function (){
					if ( !done ) done = milestone_controller (el, number_container);
				});
			}
		});
	}
}

function milestone_controller (el, number_container){
	var od, args;
	var speed = number_container.data( 'speed' );
	var number = number_container.text();
	if (el.is_visible()){
		args= {
			el: number_container[0],
			format: 'd',
		};
		if ( speed ) args['duration'] = speed;
		od = new Odometer( args );
		od.update( number );
		return true;
	}
	return false;
}

function get_digit (number, digit){
	var exp = Math.pow(10, digit);
	return Math.round(number/exp%1*10);
}

/* \milestone */

/* progress bar */

function cws_progress_bar_init (){
	jQuery.fn.cws_progress_bar = function (){
		jQuery(this).each( function (){
			var el = jQuery(this);
			var done = false;
			if (!done) done = progress_bar_controller(el);
			jQuery(window).scroll(function (){
				if (!done) done = progress_bar_controller(el);
			});
		});
	}
}

function progress_bar_controller (el){
	if (el.is_visible()){
		var progress = el.find(".progress");
		var value = parseInt( progress.attr("data-value") );
		var width = parseInt(progress.css('width').replace(/%|(px)|(pt)/,""));
		var ind = el.find(".indicator");
		if ( width < value ){
			var progress_interval = setInterval( function(){
				width ++;
				progress.css("width", width+"%");
				ind.text(width+'%');
				if (width == value){
					clearInterval(progress_interval);
				}
			}, 5);
		}
		return true;
	}
	return false;
}

/* \progress bar */

function custom_colors_init (){

	jQuery('.cws_sc_carousel.custom-control-color').each(function(){
		var control_color = jQuery(this).attr("data-customcontrol");
		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .prev').css(
				   {"background-color":'transparent',
					"color":control_color,
					"-webkit-box-shadow":"0px 0px 0px 1px "+control_color,
					"-moz-box-shadow":"0px 0px 0px 1px "+control_color,
					"-ms-box-shadow":"0px 0px 0px 1px "+control_color,
					"box-shadow":"0px 0px 0px 1px "+control_color
				});

		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .next').css(
				   {"background-color":'transparent',
					"color":control_color,
					"-webkit-box-shadow":"0px 0px 0px 1px "+control_color,
					"-moz-box-shadow":"0px 0px 0px 1px "+control_color,
					"-ms-box-shadow":"0px 0px 0px 1px "+control_color,
					"box-shadow":"0px 0px 0px 1px "+control_color
				});

		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .prev').on("mouseenter", function (){
			jQuery(this).css({"background-color":'rgba('+cws_Hex2RGB(control_color)+',0.25)'});
		});
		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .prev').on("mouseleave", function (){
			jQuery(this).css({"background-color":'transparent'});
		});
		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .next').on("mouseenter", function (){
			jQuery(this).css({"background-color":'rgba('+cws_Hex2RGB(control_color)+',0.25)'});
		});
		jQuery(this).find('.cws_sc_carousel_header .carousel_nav_panel .next').on("mouseleave", function (){
			jQuery(this).css({"background-color":'transparent'});
		});


		});

	jQuery('.pricing_table_column:not(.active_table_column) .price_section').each(function(){
		if (jQuery(this).attr('data-bg-color') !== undefined) {
			var bg_color = jQuery(this).attr("data-bg-color");
			jQuery(this).parents('.pricing_table_column').on("mouseenter", function (){
				jQuery(this).find(".price_section").css({"color":bg_color});
			});
			jQuery(this).parents('.pricing_table_column').on("mouseleave", function (){
				jQuery(this).find(".price_section").removeAttr('style');
			});
		}
	})

	jQuery('.cws_callout').each(function(){
		if (jQuery(this).attr('data-atts-color') !== undefined) {
			var f_color = jQuery(this).attr("data-atts-color");
			var bg_color = jQuery(this).attr("data-atts-bg");
			jQuery(this).find('.button_section a').on("mouseenter", function (){
				jQuery(this).css({"color":f_color});
			});
			jQuery(this).find('.button_section a').on("mouseleave", function (){
				jQuery(this).css({"color":bg_color});
			});
		}
	})


	jQuery(".cws_button.custom_colors").each(function (){
		var bg_color = jQuery(this).attr("data-bg-color");
		var font_color = jQuery(this).attr("data-font-color");
		var alt = jQuery(this).hasClass("alt");
		var btn = jQuery(this);
		if ( alt ){
			if (jQuery(this).parents('.pricing_table_column').length) {
				jQuery(this).css({"background-color":bg_color,"color":font_color});

				btn.on("mouseenter", function (){
					jQuery(this).css({"background-color":font_color,"color":bg_color});
				});
				btn.on("mouseleave", function (){
					btn.css({"background-color":bg_color,"color":font_color});
				});

			}else{
				jQuery(this).css({"background-color":bg_color,"color":font_color});

				jQuery(this).on("mouseover", function (){
					if (bg_color == 'transparent') {
						jQuery(this).css({"background-color":'rgba('+cws_Hex2RGB(font_color)+',0.25)',"color":font_color});
					}else{
						jQuery(this).css({"background-color":font_color,"color":bg_color});
					}
				});
				jQuery(this).on("mouseout", function (){
					jQuery(this).css({"background-color":bg_color,"color":font_color});
				});
			}
		}
		else{
			jQuery(this).css({"background-color":bg_color,"color":font_color});
			jQuery(this).on("mouseover", function (){
				jQuery(this).css({"background-color":font_color,"color":bg_color});
			});
			jQuery(this).on("mouseout", function (){
				jQuery(this).css({"background-color":bg_color,"color":font_color});
			});
		}
	});
	jQuery(".cws_fa.custom_colors").each(function (){
		var bg_color = jQuery(this).attr("data-bg-color");
		var font_color = jQuery(this).attr("data-font-color");
		var alt = jQuery(this).hasClass("alt");
		if ( alt ){
			if (jQuery(this).is('.bordered_icon.simple_icon')) {
				jQuery(this).css({"background-color":bg_color,
					"color":font_color,
					"-webkit-box-shadow":"0px 0px 0px 1px "+bg_color,
					"-moz-box-shadow":"0px 0px 0px 1px "+bg_color,
					"-ms-box-shadow":"0px 0px 0px 1px "+bg_color,
					"box-shadow":"0px 0px 0px 1px "+bg_color});
			}else if(jQuery(this).is('.simple_icon')){
				jQuery(this).css({"color":bg_color});
			}else{
				jQuery(this).css({"color":font_color,"border-color":font_color});
			}

			if (jQuery(this).parent('.cws_fa_wrapper').length) {
				jQuery(this).parent('.cws_fa_wrapper').on("mouseover", function (){
					jQuery(this).find('.cws_fa').css({"background-color":font_color,"color":bg_color, 'border-color':font_color});
					jQuery(this).find('.ring').css({
						"-webkit-box-shadow":"0px 0px 0px 1px "+font_color,
						"-moz-box-shadow":"0px 0px 0px 1px "+font_color,
						"-ms-box-shadow":"0px 0px 0px 1px "+font_color,
						"box-shadow":"0px 0px 0px 1px "+font_color
					});
				});
				jQuery(this).parent('.cws_fa_wrapper').on("mouseout", function (){
					jQuery(this).find('.cws_fa').css({"background-color":'transparent',"color":font_color,"border-color":'#f2f2f2'});
					jQuery(this).find('.ring').css({
						"-webkit-box-shadow":"0px 0px 0px 1px #fafafa",
						"-moz-box-shadow":"0px 0px 0px 1px #fafafa",
						"-ms-box-shadow":"0px 0px 0px 1px #fafafa",
						"box-shadow":"0px 0px 0px 1px #fafafa"
					});
				});
			}else{
				if (jQuery(this).is('.bordered_icon.simple_icon')) {
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":bg_color,'background-color':'transparent'});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"background-color":bg_color,
							"color":font_color,
							"-webkit-box-shadow":"0px 0px 0px 1px "+bg_color,
							"-moz-box-shadow":"0px 0px 0px 1px "+bg_color,
							"-ms-box-shadow":"0px 0px 0px 1px "+bg_color,
							"box-shadow":"0px 0px 0px 1px "+bg_color});
					});	
				}else if(jQuery(this).is('.simple_icon')){
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":font_color});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"color":bg_color});
					});
				}else{
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":bg_color,"border-color":bg_color});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"color":font_color,"border-color":font_color});
					});
				}
				
			}

		}
		else{
			if (jQuery(this).is('.bordered_icon.simple_icon')) {
				jQuery(this).css({"background-color":'transparent',
					"color":font_color,
					"-webkit-box-shadow":"0px 0px 0px 1px "+font_color,
					"-moz-box-shadow":"0px 0px 0px 1px "+font_color,
					"-ms-box-shadow":"0px 0px 0px 1px "+font_color,
					"box-shadow":"0px 0px 0px 1px "+font_color});		
			}else if(jQuery(this).is('.simple_icon')){
				jQuery(this).css({"background-color":'transparent',
					"color":font_color});
			}else{
				jQuery(this).css({"color":bg_color,"border-color":bg_color});
			}
			if (jQuery(this).parent('.cws_fa_wrapper').length) {
				jQuery(this).next('.ring').css({
					"-webkit-box-shadow":"0px 0px 0px 1px "+bg_color,
					"-moz-box-shadow":"0px 0px 0px 1px "+bg_color,
					"-ms-box-shadow":"0px 0px 0px 1px "+bg_color,
					"box-shadow":"0px 0px 0px 1px "+bg_color
				})
				jQuery(this).parent('.cws_fa_wrapper').on("mouseover", function (){
					jQuery(this).find('.cws_fa').css({"border-color":font_color,"color":font_color});
				});
				jQuery(this).parent('.cws_fa_wrapper').on("mouseout", function (){
					jQuery(this).find('.cws_fa').css({"color":bg_color,"border-color":bg_color});
				});
			}else{
				if (jQuery(this).is('.bordered_icon.simple_icon')) {
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":bg_color,'background-color':font_color});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"background-color":'transparent',
							"color":font_color,
							"-webkit-box-shadow":"0px 0px 0px 1px "+font_color,
							"-moz-box-shadow":"0px 0px 0px 1px "+font_color,
							"-ms-box-shadow":"0px 0px 0px 1px "+font_color,
							"box-shadow":"0px 0px 0px 1px "+font_color});
					});	
				}else if(jQuery(this).is('.simple_icon')){
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":font_color});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"color":bg_color});
					});
				}else{
					jQuery(this).on("mouseover", function (){
						jQuery(this).css({"color":font_color,"border-color": font_color});
					});
					jQuery(this).on("mouseout", function (){
						jQuery(this).css({"color":bg_color,"border-color":bg_color});
					});
				}
			}
			
		}
	});
}

function cws_Hex2RGB(hex) {
	var hex = hex.replace("#", "");
	var color = '';
	if (hex.length == 3) {
		color = hexdec(hex.substr(0,1))+',';
		color = color + hexdec(hex.substr(1,1))+',';
		color = color + hexdec(hex.substr(2,1));
	}else if(hex.length == 6){
		color = hexdec(hex.substr(0,2))+',';
		color = color + hexdec(hex.substr(2,2))+',';
		color = color + hexdec(hex.substr(4,2));
	}
	return color;
}
function hexdec(hex_string) {
	hex_string = (hex_string + '')
	.replace(/[^a-f0-9]/gi, '');
	return parseInt(hex_string, 16);
}

/* header parallax */


function cws_header_imgs_cover_init (){
	cws_header_imgs_cover_controller ();
	window.addEventListener( "resize", cws_header_imgs_cover_controller, false );
}

function cws_header_imgs_cover_controller (){
	var prlx_sections, prlx_section, section_imgs, section_img, i, j;
	var prlx_sections = jQuery( '.cws_parallax_scene_container > .cws_parallax_scene, .header_bg_img > .cws_parallax_section');	
	for ( i = 0; i < prlx_sections.length; i++ ){
		prlx_section = prlx_sections[i];
		section_imgs = jQuery( "img", jQuery( prlx_section ) );
		for ( j = 0; j < section_imgs.length; j++ ){
			section_img = section_imgs[j];
			cws_cover_image( section_img, prlx_section );
		}
	}
}

function cws_cover_image ( img, section ){
	var section_w, section_h, img_nat_w, img_nat_h, img_ar, img_w, img_h, canvas;
	if ( img == undefined || section == undefined ) return;
	section_w = section.offsetWidth;
	section_h = section.offsetHeight;	
	img_nat_w = img.naturalWidth;
	img_nat_h = img.naturalHeight;
	img_ar = img_nat_w / img_nat_h;
	if ( img_ar > 1 ){
		img_h = section_h;
		img_w = section_h * img_ar;
	}
	else{
		img_w = section_w;
		img_h = section_w / img_ar;
	}
	img.width = img_w;
	img.height = img_h;
}


function cws_header_bg_init(){
	var bg_sections = jQuery('.header_bg_img, .cws_parallax_scene_container');
	bg_sections.each( function (){
		var bg_section = jQuery( this );
		cws_header_bg_controller( bg_section );
	});
	window.addEventListener( 'resize', function (){
		var bg_sections = jQuery('.header_bg_img, .cws_parallax_scene_container');
		bg_sections.each( function (){
			var bg_section = jQuery( this );
			cws_header_bg_controller( bg_section );
		});
	}, false );
}

function cws_header_bg_controller ( bg_section ){
	var benefits_area = jQuery( ".benefits_area" ).eq( 0 );
	var page_content_section = jQuery( ".page_content" ).eq( 0 );
	var top_curtain_hidden_class = "hidden";
	var top_panel = jQuery( "#site_top_panel" );
	var top_curtain = jQuery( "#top_panel_curtain" );
	var consider_top_panel = top_panel.length && top_curtain.length && top_curtain.hasClass( top_curtain_hidden_class );
		if ( benefits_area.length ){
			if ( consider_top_panel ){
				bg_section.css( {
					'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
					'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
				});
			}
			else{
				bg_section.css( {
					'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + "px",
					'margin-top' : "-" + bg_section.parent().offset().top + "px"
				});
			}
			bg_section.addClass( 'height_assigned' );
		}
		else if ( page_content_section.length ){
			if ( page_content_section.hasClass( "single_sidebar" ) || page_content_section.hasClass( "double_sidebar" ) ){
				if ( consider_top_panel ){
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
						'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
					});
				}
				else{
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + bg_section.parent().offset().top + "px",
						'margin-top' : "-" + bg_section.parent().offset().top + "px"
					});
				}
				bg_section.addClass( 'height_assigned' );				
			}
			else{
				if ( consider_top_panel ){
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
						'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
					});
				}
				else{
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + "px",
						'margin-top' : "-" + bg_section.parent().offset().top + "px"
					});
				}
				bg_section.addClass( 'height_assigned' );				
			}
		}
}

function cws_header_parallax_init (){
	var scenes = jQuery( ".cws_parallax_section, .cws_parallax_scene" );
	scenes.each( function (){
		var scene = this;
		var prlx_scene = new Parallax ( scene );
	});
}

function cws_carousels_init_waiter ( els, callback ){
	for ( var i = 0; i < els.length; i++ ){
		if ( jQuery( els[i] ).hasClass( 'owl-carousel' ) ){
			els.splice( i, 1 );
		}
	}
	if ( els.length ){
		setTimeout( function (){
			cws_carousels_init_waiter ( els, callback );
		}, 10 );
	}
	else{
		callback ();
		return true;
	}
}

function cws_wait_for_header_bg_height_assigned ( callback ){
	var header_bg_sections = jQuery( '.header_bg_img, .cws_parallax_scene_container' );
	if ( callback == undefined || typeof callback != 'function' ) return;
	cws_header_bg_height_assigned_waiter ( header_bg_sections, callback );
}

function cws_header_bg_height_assigned_waiter ( els, callback ){
	var i;
	for ( i = 0; i < els.length; i++ ){
		if ( jQuery( els[i] ).hasClass( 'height_assigned' ) ){
			els.splice( i, 1 );
		}
	}
	if ( els.length ){
		setTimeout( function (){
			cws_header_bg_height_assigned_waiter ( els, callback );
		}, 10 );
	}
	else{
		callback ();
		return true;
	}
}

/* \header parallax */

/* full screen video */

function cws_page_header_video_init (){
	cws_set_header_video_wrapper_height();
	window.addEventListener( 'resize', cws_set_header_video_wrapper_height, false )
}

function cws_set_header_video_wrapper_height (){
	var containers = document.getElementsByClassName( 'page_header_video_wrapper' );
	for ( var i=0; i<containers.length; i++ ){
		cws_set_window_height( containers[i] );
	}			
}

function scroll_down_init (){
	jQuery( ".fs_video_slider" ).on( "click", ".scroll_down", function ( e ){
		var anchor, matches, id, el, el_offset;
		e.preventDefault();
		anchor = jQuery( this ).attr( "href" );
		matches = /#(\w+)/.exec( anchor );
		if ( matches == null ) return;
		id = matches[1];
		el = document.getElementById( id );
		if ( el == null ) return;
		el_offset = jQuery( el ).offset().top;
		jQuery( "html, body" ).animate({
			scrollTop : el_offset
		}, 300);
	});	
}

/* \full screen video */

/* BLUR */

function cws_wait_for_image ( img, callback ){
	var complete = false;
	if ( img == undefined || img.complete == undefined || callback == undefined || typeof callback != 'function' ) return;
	if ( !img.complete ){
		setTimeout( function (){
			cws_wait_for_image ( img, callback );
		}, 10 );
	}
	else{
		callback ();
		return true;
	} 
}

function cws_wait_for_canvas ( canvas, callback ){
	var drawn = false;
	if ( canvas == undefined || typeof canvas != 'object' || callback == undefined || typeof callback != 'function' ) return;
	if ( !jQuery( canvas ).hasClass( 'drawn' ) ){
		setTimeout( function (){
			cws_wait_for_canvas ( canvas, callback );
		}, 10);
	}
	else{
		callback ();
		return true;
	}
}

function circle_img_hover(){
	if(clickevent){
		jQuery('.shortcode.ih-item.circle').on(clickevent,  function (){
			jQuery('.shortcode.ih-item.circle').find('.item_content').css({'opacity':0});
			jQuery(this).find('.item_content').css({"opacity":1}).fadeIn();
		});
	}
}
window.addEventListener( 'resize', function (){
	circle_img_hover();
});
/* \BLUR */

/* SCROLL TO TOP */
function scroll_top_vars_init (){
	window.scroll_top = {
		el : jQuery( ".scroll_top" ),
		anim_in_class : "fadeIn",
		anim_out_class : "fadeOut"
	};
}
function scroll_top_init (){
	scroll_top_vars_init ();
	scroll_top_controller ();
	window.addEventListener( 'scroll', scroll_top_controller, false);
	window.scroll_top.el.on( 'click', function (){
		jQuery( "html, body" ).animate( {scrollTop : 0}, '300', function (){
			window.scroll_top.el.css({
				"pointer-events" : "none"
			});
			window.scroll_top.el.addClass( window.scroll_top.anim_out_class );
		});
	});
}
function scroll_top_controller (){
	var scroll_pos = window.pageYOffset;
	if ( window.scroll_top == undefined ) return;
	if ( scroll_pos < 1 && window.scroll_top.el.hasClass( window.scroll_top.anim_in_class ) ){
		window.scroll_top.el.css({
			"pointer-events" : "none"
		});
		window.scroll_top.el.removeClass( window.scroll_top.anim_in_class );
		window.scroll_top.el.addClass( window.scroll_top.anim_out_class );
	}
	else if( scroll_pos >= 1 && !window.scroll_top.el.hasClass( window.scroll_top.anim_in_class ) ){
		window.scroll_top.el.css({
			"pointer-events" : "auto"
		});
		window.scroll_top.el.removeClass( window.scroll_top.anim_out_class );
		window.scroll_top.el.addClass( window.scroll_top.anim_in_class );
	}
}
/* \SCROLL TO TOP */

function cws_set_window_width ( el ){
	var window_w;
	if ( el != undefined ){
		window_w = document.body.clientWidth;
		el.style.width = window_w + 'px';
	}
}
function cws_set_window_height ( el ){
	var window_h;
	if ( el != undefined ){
		window_h = window.innerHeight;
		el.style.height = window_h + 'px';
	}
}

function cws_top_social_init (){
	if (jQuery("#top_social_links_wrapper").hasClass('toggle-on')) {
		var el = jQuery( "#top_social_links_wrapper" );
		var toggle_class = "expanded";
		var parent_toggle_class = "active_social";
		if ( !el.length ) return;
		el.on( 'click', function (){
			var el = jQuery( this ).children('.cws_social_links');
			if ( el.hasClass( toggle_class ) ){
				el.removeClass( toggle_class );
				setTimeout( function (){
					el.closest( "#site_top_panel" ).removeClass( parent_toggle_class );
				}, 300);
			}
			else{
				el.addClass( toggle_class );
				el.closest( "#site_top_panel" ).addClass( parent_toggle_class );			
			}
		});
	};
}

function cws_fs_video_bg_init (){
	var slider_wrappers, header_height_is_set;
	header_height_is_set = document.getElementsByClassName( 'header_video_fs_view' );


	if ( !header_height_is_set.length) return;
		cws_fs_video_slider_controller( header_height_is_set[0] );
	window.addEventListener( 'resize', function (){
		cws_fs_video_slider_controller( header_height_is_set[0] );
	});
}
function cws_fs_video_slider_controller ( el ){
	cws_set_window_width( el );
	cws_set_window_height( el );
}

function cws_slider_video_height (element){
	var height_coef = element.attr('data-wrapper-height')
	if (height_coef) {
		if (window.innerWidth<960) {
			element.height(window.innerWidth/height_coef)
		}else{
			element.height(960/height_coef)
		}
	}	
}

/* SLIDER SCROLL CONTROLLER */

function cws_revslider_pause_init (){
	var slider_els, slider_el, slider_id, id_parts, revapi_ind, revapi_id, i;
	var slider_els = document.getElementsByClassName( "rev_slider" );
	window.cws_revsliders = {};
	if ( !slider_els.length ) return;
	for ( i = 0; i < slider_els.length; i++ ){
		slider_el = slider_els[i];
		slider_id = slider_el.id;
		id_parts = /rev_slider_(\d+)(_\d+)?/.exec( slider_id );
		if ( id_parts == null ) continue;
		if ( id_parts[1] == undefined ) continue;
		revapi_ind = id_parts[1];
		revapi_id = "revapi" + revapi_ind;
		window.cws_revsliders[slider_id] = {
			'el' : slider_el,
			'api_id' : revapi_id,
			'stopped' : false
		}
		window[revapi_id].bind( 'revolution.slide.onloaded', function (){
			cws_revslider_scroll_controller ( slider_id );
		});
		window.addEventListener( 'scroll', function (){
			cws_revslider_scroll_controller ( slider_id );
		});	
	}	
}
function cws_revslider_scroll_controller ( slider_id ){
	var slider_obj, is_visible;
	if ( slider_id == undefined ) return;
	slider_obj = window.cws_revsliders[slider_id];
	is_visible = jQuery( slider_obj.el ).is_visible();
	if ( is_visible && slider_obj.stopped ){
		window[slider_obj.api_id].revresume();
		slider_obj.stopped = false;
	}
	else if ( !is_visible && !slider_obj.stopped ){
		window[slider_obj.api_id].revpause();	
		slider_obj.stopped = true;		
	}
}

/* \SLIDER SCROLL CONTROLLER */

/* CUSTOM HEADER SPASINGS RESPONSIVE */

function cws_responsive_custom_header_paddings_init (){
	cws_responsive_custom_header_paddings ();
	window.addEventListener( "resize", cws_responsive_custom_header_paddings, false );
}

function cws_responsive_custom_header_paddings (){
	var sections, section, i, initial_viewport, current_viewport, viewport_coef;
	var sections = document.getElementsByClassName( "page_title customized" );
	if ( !sections.length ) return;
	initial_viewport = 1920;
	current_viewport = window.innerWidth;
	viewport_coef = current_viewport / initial_viewport;
	for ( i = 0; i < sections.length; i++  ){
		section = sections[i];
		cws_responsive_custom_header_paddings_controller ( section, viewport_coef );
	}
}

function cws_responsive_custom_header_paddings_controller ( section, coef ){
	var section_cont, section_atts, matches, attr, prop, init_val, proc_val, i;
	if ( section == undefined || coef == undefined ) return;
	section_cont = jQuery( ".container", section );
	if ( !section_cont.length ) return;
	if ( section == undefined || !section.hasAttributes() || section.attributes == undefined ) return;
	section_atts = section.attributes;
	for ( i = 0; i < section_atts.length; i++ ){
		attr = section_atts[i];
		matches = /^data-init-(padding-\w+)$/.exec( attr.name );
		if ( matches == null ) continue;
		prop = matches[1];
		init_val = attr.value;
		proc_val = Math.round( init_val * coef );
		section_cont.css( prop, proc_val + "px" );
	}
}

/* \CUSTOM HEADER SPASINGS RESPONSIVE */

/* TOP PANEL MOBILE */

function cws_top_panel_mobile_init (){
	top_panel_curtain_init ();
	cws_top_panel_mobile_controller ();
	window.addEventListener( "resize", cws_top_panel_mobile_controller, false );
}

function cws_top_panel_mobile_controller (){
	var top_panel, curtain, _is_mobile, mobile_init, is_curtain_hidden, hidden_class;
	hidden_class = "hidden";
	top_panel = jQuery( "#site_top_panel" );
	curtain = jQuery( "#top_panel_curtain" );
	if ( !top_panel.length || !curtain.length ) return;
	_is_mobile = is_mobile();
	mobile_init = top_panel.hasClass( "mobile" );
	if ( _is_mobile ){
		if ( mobile_init ){
			is_curtain_hidden = curtain.hasClass( hidden_class );
			if ( is_curtain_hidden ){
				top_panel.css({
					"margin-top" : "-" + top_panel.outerHeight() + "px"
				})
			}
		}
		else{
			top_panel.addClass( "mobile" );
			cws_wait_for_header_bg_height_assigned( function (){
				pick_up_curtain ();
			});
		}
	}
	else if ( !_is_mobile && mobile_init ){
		if ( mobile_init ){
			top_panel.removeClass( "mobile" );
			put_down_curtain ();
		}
	}
	else{
	}
}

function top_panel_curtain_init (){
	var curtain = document.getElementById( "top_panel_curtain" );
	if ( curtain != null ){
		curtain.addEventListener( "click", top_panel_curtain_click_controller, false );
	}
}
function top_panel_curtain_click_controller (){
	var curtain_obj, hidden_class;
	curtain_obj = jQuery( "#top_panel_curtain" );
	hidden_class = "hidden";
	if ( curtain_obj.hasClass( hidden_class ) ){
		put_down_curtain ( true );
	}
	else{
		pick_up_curtain ( true );
	}	
}
function pick_up_curtain ( animated ){
	var curtain_obj, top_panel, top_panel_obj, top_panel_height, anim_speed, hidden_class;
	if ( animated == undefined ) animated = false;
	curtain_obj = jQuery( "#top_panel_curtain" );
	top_panel = document.getElementById( "site_top_panel" );
	top_panel_obj = jQuery( top_panel );
	top_panel_height = top_panel.offsetHeight;
	anim_speed = 300;
	hidden_class = "hidden";
	if ( animated ){
		top_panel_obj.removeClass("visible_cont");
		top_panel_obj.stop().animate({
			"margin-top" : "-" + top_panel_height + "px"
		}, anim_speed, function (){
			curtain_obj.addClass( hidden_class );
		});
	}
	else{
		top_panel_obj.removeClass("visible_cont");
		top_panel.style.marginTop = "-" + top_panel_height + "px";
		curtain_obj.addClass( hidden_class );
	}
}
function put_down_curtain ( animated ){
	var curtain_obj, top_panel, top_panel_obj, top_panel_height, anim_speed, hidden_class;	
	if ( animated == undefined ) animated = false;
	curtain_obj = jQuery( "#top_panel_curtain" );
	top_panel = document.getElementById( "site_top_panel" );
	top_panel_obj = jQuery( top_panel );
	anim_speed = 300;
	hidden_class = "hidden";
	if ( animated ){
		top_panel_obj.addClass("visible_cont");
		top_panel_obj.stop().animate({
			"margin-top" : "0px"
		}, anim_speed, function (){
			curtain_obj.removeClass( hidden_class );
		});
	}
	else{
		top_panel_obj.addClass("visible_cont");
		top_panel.style.marginTop = "0px";
		curtain_obj.removeClass( hidden_class );
	}
}

/* \TOP PANEL MOBILE */

function cws_clone_obj ( src_obj ){
	var new_obj, keys, i, key, val;
	if ( src_obj == undefined || typeof src_obj != 'object' ) return false;
	new_obj = {};
	keys = Object.keys( src_obj );
	for ( i = 0; i < keys.length; i++ ){
		key = keys[i];
		val = src_obj[key];
		new_obj[key] = val;
	}
	return new_obj;
}

//Accordions
jQuery.fn.tab_onClick = function ( e,f ){
	cws_accordion_init();
	jQuery( ".cws_ce_content.ce_accordion" ).cws_accordion();
	jQuery( ".cws_ce_content.ce_toggle" ).cws_accordion();
	cws_tabs_init();
	jQuery( ".cws_ce_content.ce_tabs" ).cws_tabs ();
}

function cws_sticky_sidebars_init(){
 if (sticky_sidebars == 1){
  jQuery('.sb_left, .sb_right').theiaStickySidebar({
        additionalMarginTop: 60,
        additionalMarginBottom: 60
  });   
 }
}

// CWS Builder Scripts
jQuery.fn.carousel_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.blog_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.igrid_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.banners_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.products_gallery_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.pricing_lists_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.pricing_lists_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.progress_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.divider_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.button_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.pricing_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.callout_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.flaticon_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.twitter_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.embed_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.testimonials_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.portfolio_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}

jQuery.fn.milestone_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.our_team_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.msg_box_sample = function ( parent ){
	jQuery.fn.cws_pageBuilder_loader();
}
jQuery.fn.cws_pageBuilder_loader = function(){
	cws_revslider_pause_init ();
	cws_header_bg_init ();
	cws_header_imgs_cover_init ();
	cws_header_parallax_init ();
	cws_mobile_menu_init();
	jQuery( ".portfolio_carousel" ).cws_flex_carousel( ".cws_portfolio", ".cws_portfolio_header" );
	jQuery( ".portfolio_fw_carousel" ).cws_flex_carousel( ".cws_portfolio_fw", ".cws_portfolio_header" );
	jQuery( ".ourteam_carousel" ).cws_flex_carousel( ".cws_ourteam", ".cws_ourteam_header" );
	jQuery( ".news_carousel" ).cws_flex_carousel( ".news", ".cws_blog_header" );
	gallery_post_carousel_init();
	widget_carousel_init();
	cws_sc_carousel_init();
	cws_carousel_init_shortcode();
	twitter_carousel_init();
	jQuery( window ).load(function() {
  		// Run code
  		select2_init();
	});
	if(jQuery('body').hasClass("cwspb-active")){
		setTimeout(imageCircleWrap,250);
		setTimeout(isotope_init,250);		
	}

	builder_widget_wrap();
	row_options_cws_builder();
	cws_portfolio_pagination_init ();
	cws_blog_pagination_init();
	cws_portfolio_filter_init ();
	cws_portfolio_single_carousel_init ();
	cws_portfolio_fw_filter_init ();
	cws_ourteam_pagination_init ();
	cws_ourteam_filter_init ();
	cws_parallax_init();
	cws_prlx_init_waiter ();
	cws_sticky_light ();
	cws_responsive_custom_header_paddings_init ();
	cws_top_panel_mobile_init ();
	cws_page_focus();
	cws_top_panel_search ();
	boxed_var_init ();
	cws_fs_video_bg_init ();
	wp_standard_processing ();
	cws_page_header_video_init ();
	cws_top_social_init ();
	custom_colors_init();
	cws_tabs_init ();
	cws_accordion_init ();
	cws_toggle_init ();
	select2_close();
	widget_archives_hierarchy_init();
	fancybox_init();
	wow_init();
	load_more_init();
	cws_revslider_class_add();
	cws_menu_bar ();
	cws_fullwidth_background_row ();
	cws_add_title_sep ();
	jQuery( ".cws_milestone" ).cws_milestone();
	jQuery( ".cws_progress_bar" ).cws_progress_bar();
	jQuery( ".cws_ce_content.ce_tabs" ).cws_tabs ();
	jQuery( ".cws_ce_content.ce_accordion" ).cws_accordion ();
	jQuery( ".cws_ce_content.ce_toggle" ).cws_toggle ( "accordion_section", "accordion_title", "accordion_content" );
	cws_message_box_init ();
	scroll_down_init ();
	scroll_top_init ();

}


var builder_module = (function () {
jQuery("#cws_content_wrap ul.modules ul.parents h6").each(function(){
	var open = false;
	jQuery("#cws_content_wrap ul.modules ul.parents").eq(0).find('h6').addClass('active');
	jQuery(this).click(function(){
		jQuery("#cws_content_wrap ul.modules ul.parents h6").not(this).removeClass("active");	
					
		if(!jQuery(this).hasClass('active')){
			open = false;
			jQuery(this).addClass('active');
			jQuery("#cws_content_wrap ul.modules ul.parents").find('li').css({"display":"none"});
		}
		if(jQuery(this).hasClass('active') && !open){
			open = true;
			for(var i = 0 ; i < jQuery(this).parent().find('li').length; i++){
				jQuery(this).parent().find('li').hide().css({"opacity":"0","display":"block"}).eq(i).stop().delay(50 * i).animate({opacity:1},250);	
			}		
		}				
	})
});

jQuery(".btn-container .cws_button").each(function(){
	var data_side = jQuery(this).data( "side" );
	if(data_side){
		jQuery(this).parent().parent().addClass('elemfloat');
		jQuery(this).parent().parent().addClass(data_side);

	}
	jQuery(this).hover(
      function () {
      	var color = jQuery(this).data( "attrColor" ),
      	bgColor = jQuery(this).data( "attrBgcolor" ),
      	borderColor = jQuery(this).data( "attrBorderColor" );
      	if(jQuery(this).hasClass('add_hover')){
      		jQuery(this).css({'color':color});
      		if(jQuery(this).hasClass('alt-style')){
      			jQuery(this).css({'background-color':bgColor,'border-color':borderColor});
      		}
      	}
      },
      function () {
      	var color = jQuery(this).data( "attrColor" ),
      	standartColor = jQuery(this).data( "standartColor" ),
      	basedColor = jQuery(this).data( "basedColor" ),
      	bgColor = jQuery(this).data( "attrBgcolor" ),
      	borderColor = jQuery(this).data( "attrBorderColor" );
      	if(jQuery(this).hasClass('add_hover')){
      		jQuery(this).css({'color':standartColor});
      		if(jQuery(this).hasClass('alt-style')){
        		jQuery(this).css({"background-color": "transparent",'color': basedColor,'border-color':borderColor});
        	} 
    	}
      }
    );
});

jQuery(".cws_fa_wrapper.cws_icons_fa").each(function(){

	var data_side = jQuery(this).data( "side" );
	if(data_side){
		jQuery(this).parent().parent().addClass('elemfloat');
		jQuery(this).parent().parent().addClass(data_side);
	}

	
	jQuery(this).hover(
      function () {
      	var color = jQuery(this).data( "attrColor" );
      	var standartColor = jQuery(this).data( "standartColor" );
      	var BorderWidth = jQuery(this).data( "borderWidth" );
      	if(jQuery(this).hasClass('add_hover')){
      		if(!jQuery(this).hasClass('alt')){
      			if(jQuery("i",this).hasClass('fa-5x')){
      				jQuery("i",this).css({'color':color,"boxShadow":"0 0 0 66px #fff inset,0 0 0 5px " + color + ""});
      			}
      			else{
      				jQuery("i",this).css({'color':color,"boxShadow":"0 0 0 55px #fff inset,0 0 0 5px " + color + ""});
      			}
      			
      		}
      		if(jQuery(this).hasClass('alt') && jQuery("i",this).hasClass('borderless')){
      			jQuery("i",this).css({'color':color});
      			     			
      			if(jQuery("i",this).hasClass('fa-5x')){
      				jQuery("i",this).css({"boxShadow":"inset 0 0 0 66px " + standartColor + ""});
      			}
      			else if(jQuery("i",this).hasClass('fa-4x')){
      				jQuery("i",this).css({"boxShadow":"inset 0 0 0 55px " + standartColor + ""});
      			}
      			else if(jQuery("i",this).hasClass('fa-3x')){
      				jQuery("i",this).css({"boxShadow":"inset 0 0 0 40px " + standartColor + ""});
      			}
      			else{
      				jQuery("i",this).css({"boxShadow":"inset 0 0 0 22px " + standartColor + ""});
      			}
      		}
      		if(jQuery(this).hasClass('alt') && !jQuery("i",this).hasClass('borderless')){
      			jQuery("i",this).css({"color":color});
      		}
      	}
      },
      function () {
      	var boxShadowColor = jQuery(this).data( "attrCustomize" );
      	var color = jQuery(this).data( "attrColor" );
      	var BorderWidth = jQuery(this).data( "borderWidth" );
      	var standartColor = jQuery(this).data( "standartColor" );
      	var standartBgColor = jQuery(this).data( "standartBgcolor" );
      	if(jQuery(this).hasClass('add_hover')){
      		if(!jQuery(this).hasClass('alt')){
      			jQuery("i",this).css({"color":standartColor, "boxShadow":"0 0 0 2px #fff inset,0 0 0 5px " + standartBgColor + ""});
      		}
      		if(jQuery(this).hasClass('alt')  && jQuery("i",this).hasClass('borderless')){
      			
      			jQuery("i",this).css({'color':standartColor});
      			jQuery("i",this).css('background-color: transparent !important');
      			if(!jQuery("i",this).hasClass('borderless')){
      				jQuery("i",this).css({"boxShadow" : "0 0 0 2px #fff inset,0 0 0 5px " + standartBgColor + ""});
      			}
      			else{
      				jQuery("i",this).css({"boxShadow" : "none"});
      			}
      			
      			
      		}
      		if(jQuery(this).hasClass('alt') && !jQuery("i",this).hasClass('borderless')){
      			if(jQuery("i",this).hasClass('no-customize')){
      				jQuery("i",this).css({"color":standartBgColor});
      			}
      			else{
      				jQuery("i",this).css({"color":standartColor});
      			}
      			
      		}
      	}
      }
    );
});
}());

function row_options_cws_builder(){
var globalIndex = 0;
jQuery('.row.row_options').each(function(index, value){
	if(!jQuery(this).hasClass('disable')){
		globalIndex++;
		jQuery( this ).addClass(globalIndex % 2 ? 'bg-fff' : 'bg-ccc');
	}
	else{
		jQuery( this ).addClass("sub-options");
	}
});	
}


function select2_close(){
	jQuery(document).on('click',function(e){
		if(jQuery(e.target).closest("select").length==0){
			jQuery('select').select2("close");
    	}
	});
}


function FindPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
	  posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}

jQuery('.wrapper-circle .style-offers-two').mouseenter(function( e ) {
  var PosX = 0;
  var PosY = 0;
  var ImgPos;
  var posXelement = 0;
  var posYelement = 0;

  var offset = jQuery(this).offset();
  posXelement += offset.left;
  posYelement += offset.top;
  var ImgPos = 	[posXelement, posYelement];

  if (!e) var e = window.event;
  if (e.pageX || e.pageY)
  {
    PosX = e.pageX;
    PosY = e.pageY;
  }
  else if (e.clientX || e.clientY)
    {
      PosX = e.clientX + document.body.scrollLeft
        + document.documentElement.scrollLeft;
      PosY = e.clientY + document.body.scrollTop
        + document.documentElement.scrollTop;
    }
  PosX = PosX - ImgPos[0];
  PosY = PosY - ImgPos[1];
  if(PosY < 147){
  	jQuery(this).find('.text-information').css({"height": 0});
  }
  else{
  	jQuery(this).find('.text-information').css({"height": 100 + "%"});
  }
}).mouseleave(function(){
    jQuery(this).find('.text-information').css({"height": 45 + "%"});
});


function builder_widget_wrap(){
	jQuery(".cwspb-active .grid_row_content .grid_row > .cwsfe_grid > *:not(.cwspb_controls)").hover(function(){
		if(jQuery(this).length != 0){
		}
	},
	function(){
		if(jQuery(this).hasClass('border-transparent')){
		}
	});
}

})(jQuery);