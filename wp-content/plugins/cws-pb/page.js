(function ($) {
"use strict";

	$(document).ready(function(e) {
		var preview_button = $('#post-preview');
		var href = preview_button.attr('href');
		href = href.replace('preview=true','prev=true');
		var our_button = '<a class="button button-primary button-large" href="'+href+'" id="cwspbfe_edit">Edit with CWS Page Builder</a>';
		$('#titlediv').append(our_button);
	}); // end of ready

})( window.jQuery );
