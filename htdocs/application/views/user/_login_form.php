<form onsubmit="return false;" id="frm_login">
	<input type="hidden" name="login_salt" value="<?php echo $salt ?>">
	<table>
		<tr>
			<th colspan="2">
				Login to MyPlaces
			</th>
		</tr>
		<tr>
			<td>
				Username:
			</td>
			<td>
				<input size="30" type="text" name="user" value="" id="login_user">
			</td>
		</tr>
		<tr>
			<td>
				Password:
			</td>
			<td>
				<input size="30" type="password" value="" id="pass">
				<input type="hidden" name="pass" value="" id="login_pass">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input class="small blue" type="submit" name="btn_login" value="login" id="btn_login">
			</td>
		</tr>
	</table>
	<div style="display: none;" class="notice error" id="login_notice">
		<i class="icon-remove-sign icon-large"></i>
		<span id="notice_text">&nbsp;</span>
		<a href="#close" class="icon-remove"></a>
	</div>
</form>
<script src="/js/sha256.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	jQuery('#login_user').focus();
	
	// evaluate the JSON response
	function checkLogin (response)
	{
		jQuery('#login_notice').fadeOut();
		jQuery('#notice_text').html('');
		if(response.success)
		{
			// do something with the response
			jQuery('#link_log_in_out').removeAttr('onclick').attr('href','/users/logout');
			jQuery('#link_log_in_out span').html('Logout '+response.data.username);
			mainPopup.close();
		}
		else
		{
			// show an error message
			jQuery('#notice_text').html(response.message);
			jQuery('#login_notice').fadeIn();
		}
	}
	
	function validateForm() {
		var pass = document.getElementById('login_pass');
		var pswd = document.getElementById('pass');
		pass.value = CryptoJS.SHA256(pswd.value).toString(CryptoJS.enc.Hex);
		return true;
	}
 
	jQuery("#frm_login").ajaxFormSubmit({
		action: "/users/ajax_login",
		callback: checkLogin,
		validation: validateForm
	});
</script>

