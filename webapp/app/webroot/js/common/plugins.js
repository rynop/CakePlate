/* ==========================================================
 * CakePlate jQuery plugin code
 * 
 * https://github.com/rynop/CakePlate
 * ==========================================================
 * Copyright 2011 pwebo.com, LLC.
 * 
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 * ========================================================== */

// remap jQuery to $
(function($){

})(window.jQuery);

$(document).ready(function(){
	placeholderHint();	//Add placeholder hints to all inputs that have class="placeholder", @see placeholderHint
	
	//Want to attach twipsy to all help images?
//	$("img[rel=twipsy]").twipsy({
//		live: true,
//		placement: 'right',
//		offset: 5
//	});
});

$.fn.exists = (function () {
    return $(this).length !== 0;
});

/**
 * Does the browser support placeholder HTML5? $.support.placeholder
 */
jQuery.support.placeholder = (function(){
    var i = document.createElement('input');
    return 'placeholder' in i;
})();

/**
 * To use set class of input to placeholder and it will use the title attribute for the placeholder hint
 * 
 * I know tihs is not a jQuery plugin..
 */
function placeholderHint(){
	if($.support.placeholder) return;	//if browser supports placeholder, dont need to do the rest 

	$('input.placeholder').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('title')) {
			input.val('');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('title')) {
			input.val(input.attr('title'));
		}
	})
	.blur()
	.parents('form').submit(function() {		
		$(this).find('.hint').each(function() {
			var input = $(this);
			if (input.val() == input.attr('title')) {
				input.val('');
			}
		});
	});
}
