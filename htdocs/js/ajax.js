// Form submit with AJAX
jQuery.fn.ajaxFormSubmit = function (options) {
	// Validation: action and callback are required
	if(typeof options == "undefined")
	{
		if (typeof console != "undefined") console.log("Missing options");
		return -1;
	}
	if(typeof options.action == "undefined")
	{
		if (typeof console != "undefined") console.log("Missing action URL");
		return -1;
	}
	if(typeof options.callback == "undefined")
	{
		if (typeof console != "undefined") console.log("Missing callback function");
		return -1;
	}

	// bind submit event
	jQuery(this).submit(function (event) {
		event.preventDefault();
		if(typeof options.validation == 'function')
		{
			if(!options.validation())
				return false;
		}
		var formData = jQuery(this).serialize();
		jQuery.post(options.action, formData, options.callback, "json");
	});
}
