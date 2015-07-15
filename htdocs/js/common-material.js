// =============
// = VARIABLES =
// =============
var reloadAfterModal = false;

// =============
// = FUNCTIONS =
// =============
function loadLoginForm() {
	jQuery('#modal-login-content').load(siteUrl('/users/ajax_login'));
}
function matAlert(message) {
	if(typeof message == 'undefined') message = "Fusce dapibus, tellus ac cursus commodo.";
	jQuery('#modal-alert-text').html(message);
	jQuery('#modal-alert').openModal();
}

// ================
// = JQUERY STUFF =
// ================
(function($){
	$(function(){
		$('.button-collapse').sideNav();
		$('#link-login, #link-login-mobile').leanModal({
			dismissible: true,
			opacity: .5,
			ready: loadLoginForm,
			complete: function () {
				jQuery('#modal-login-loader').show();
				jQuery('#modal-login-content').html('');
				jQuery('#form-login-submit').css('visibility','hidden').attr('onclick',"return false;");
			}
		});
	}); // end of document ready
})(jQuery); // end of jQuery name space
