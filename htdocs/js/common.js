// main popup variable
var mainPopup = false;

// common stuff
jQuery(document).ready(function () {
	mainPopup = new jQuery.Popup();
	// buttons as links
	jQuery('button.link').on('click', function () {
		var href = jQuery(this).attr('href');
		window.location.href = href;
	});
});
