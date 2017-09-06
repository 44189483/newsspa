(function($) {
/* portfolio ajax */
cws_blog_pagination_init();
cws_blog_pagination();

function cws_blog_pagination_init (){
	var els = jQuery( ".shortcode-blog .pagination" );
	cws_blog_pagination ( els );
}

function cws_blog_pagination ( pagination , is_fw ){
	var old_page_links = jQuery(pagination).find( ".page_links" );
	var items = jQuery(old_page_links).find( ".page-numbers" ).not( ".current" );
	if (is_fw) {
		var parent = jQuery(pagination).closest( ".cws_portfolio_fw" );
	}else{
		var parent = jQuery(pagination).closest( ".cws_portfolio" );
	}
	
	console.log(items);
	var grid = parent.find( ".grid .item" );
	
	var ajax_data_input = parent.find( "input.cws_blog_ajax_data" );

	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		var action_func;
		ajax_data['url'] = url;		
	
		action_func = 'plugin_get_post';

		item.on( "click", function ( e ){
			e.preventDefault();
			jQuery.post( ajaxurl, {
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
						pagination.closest('.cws_portfolio_fw').find('.portfolio_loader_wraper').hide();	
					}else{
						pagination.closest('.cws_portfolio').find('.portfolio_loader_wraper').hide();	
					}
					grid.isotope( 'layout' );
					old_page_links.fadeOut( function (){
						old_page_links.remove();
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							pagination.append( new_page_links );
							new_page_links.fadeIn();
							if (is_fw){
								cws_blog_pagination ( pagination , true );
							}else{
								cws_blog_pagination ( pagination );
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

})(jQuery);