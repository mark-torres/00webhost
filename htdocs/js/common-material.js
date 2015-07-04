// =============
// = VARIABLES =
// =============
var formLoginModal = document.getElementById('modal-login');
// =============
// = FUNCTIONS =
// =============
function loadLoginForm() {
	jQuery('#modal-login-content').load('/users/ajax_login');
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
