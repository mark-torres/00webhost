<div id="form-login-container" class="row" style="display: none;">
	<form id="form-login" class="col s12" onsubmit="return false;">
		<input name="login_salt" value="<?php echo $salt ?>" type="hidden">
		<input id="login-password" name="pass" value="" type="hidden">
		<div class="row">
			<div class="input-field col s12">
				<input placeholder="Username" id="login-user" name="user" type="text">
				<label for="login-user">Username</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<input placeholder="Password" id="login-pswd" type="password">
				<label for="login-password">Password</label>
			</div>
		</div>
		<div id="form-login-result" class=""></div>
	</form>
</div>
<script type="text/javascript">
var	formResult = jQuery('#form-login-result');
// evaluate the JSON response
function checkLogin (response)
{
	if(typeof response.success != 'undefined' && response.success) {
		// do something with the response
		location.reload();
	} else {
		formResult.html('').removeAttr('class').addClass('red-text text-darken-1');
		if(typeof response.message != 'undefined') {
			formResult.html(response.message);
		} else {
			formResult.html("Error logging in. Please check your connection.");
		}
	}
}
// process form
function validateForm() {
	var pass = document.getElementById('login-password');
	var pswd = document.getElementById('login-pswd');
	pass.value = CryptoJS.SHA256(pswd.value).toString(CryptoJS.enc.Hex);
	formResult.html('').removeAttr('class').addClass('blue-text text-darken-1');
	formResult.html('Logging in, please wait...');
	return true;
}
// ajax submit
jQuery("#form-login").ajaxFormSubmit({
	action: "/users/ajax_login",
	callback: checkLogin,
	validation: validateForm
});
// when loaded
jQuery(document).ready(function () {
	jQuery('#login-user').focus();
	jQuery('#modal-login-loader').hide();
	jQuery('#form-login-container').slideDown();
	jQuery('#form-login-submit').css('visibility','visible').attr('onclick',"jQuery('#form-login').submit();return false;");
});
</script>

