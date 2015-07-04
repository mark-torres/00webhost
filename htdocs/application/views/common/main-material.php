<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>My places<?php echo empty($page_title)?'':" :: ".$page_title ?></title>
	<!-- CSS	-->
	<link href="/css/material-icons.css" rel="stylesheet"/>
	<link href="/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="/css/common-material.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
	<!-- LOGIN MODAL -->
	<div id="modal-login" class="modal">
		<div id="modal-login-content" class="modal-content">
		</div>
		<div id="modal-login-loader" class="progress collapsible">
			<div class="indeterminate"></div>
		</div>
		<div is="modal-login-footer" class="modal-footer">
			<a href="#!" onclick="return false;" class="modal-action modal-close btn-flat">Cancel</a>
			<a href="#!" onclick="return false;" class="modal-action btn-flat" id="form-login-submit" style="visibility: hidden;">Login</a>
		</div>
	</div>
	<!-- CONTENT -->
	<nav class="light-blue lighten-1" role="navigation">
		<div class="nav-wrapper container">

			<a id="logo-container" href="<?php echo site_url("/welcome") ?>" class="brand-logo">
				my places
			</a>
			<!-- ================ -->
			<!-- = REGULAR MENU = -->
			<!-- ================ -->
			<ul class="right hide-on-med-and-down">
				<li>
					<a href="<?php echo site_url("/places/search") ?>">
						<i class="material-icons left">view_module</i>
						Places
					</a>
				</li>
				<li>
				<?php if (!empty($user['name'])): ?>
					<a href="<?php echo site_url("/users/logout") ?>">
						<i class="material-icons left">perm_identity</i>
						Log Out <?php echo $user['name'] ?>
					</a>
				<?php else: ?>
					<a id="link-login" href="#modal-login" onclick="return false;">
						<i class="material-icons left">perm_identity</i>
						Log In
					</a>
				<?php endif ?>
				</li>
			</ul>
			<!-- =============== -->
			<!-- = MOBILE MENU = -->
			<!-- =============== -->
			<ul id="nav-mobile" class="side-nav">
				<li>
					<a href="<?php echo site_url("/places/search") ?>">
						Places
					</a>
				</li>
				<li>
				<?php if (!empty($user['name'])): ?>
					<a href="<?php echo site_url("/users/logout") ?>">
						Logout <?php echo $user['name'] ?>
					</a>
				<?php else: ?>
					<a id="link-login-mobile" href="#modal-login">
						Log In
					</a>
				<?php endif ?>
				</li>
			</ul>
			<a href="#" data-activates="nav-mobile" class="button-collapse">
				<i class="material-icons">menu</i>
			</a>
		</div>
	</nav>

	<?php if(!empty($page_content)) echo $page_content ?>

	<footer class="page-footer orange lighten-1">
		<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">About this project</h5>
					<p class="grey-text text-lighten-4">
						Etiam porta sem malesuada magna mollis euismod. Vivamus sagittis lacus vel augue 
						laoreet rutrum faucibus dolor auctor. Maecenas faucibus mollis interdum. Donec sed 
						odio dui. Maecenas sed diam eget risus varius blandit sit amet non magna. Cum sociis 
						natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas 
						faucibus mollis interdum.
					</p>
				</div>
				<!--
				<div class="col l3 s12">
					<h5 class="white-text">Settings</h5>
					<ul>
						<li><a class="white-text" href="#!">Link 1</a></li>
						<li><a class="white-text" href="#!">Link 2</a></li>
						<li><a class="white-text" href="#!">Link 3</a></li>
						<li><a class="white-text" href="#!">Link 4</a></li>
					</ul>
				</div>
				<div class="col l3 s12">
					<h5 class="white-text">Connect</h5>
					<ul>
						<li><a class="white-text" href="#!">Link 1</a></li>
						<li><a class="white-text" href="#!">Link 2</a></li>
						<li><a class="white-text" href="#!">Link 3</a></li>
						<li><a class="white-text" href="#!">Link 4</a></li>
					</ul>
				</div>
				-->
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				Made with <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
				by HSoft&trade;
			</div>
		</div>
	</footer>
	<!--	Scripts-->
	<script src="/js/jquery_1.9.1.min.js"></script>
	<script src="/js/materialize.min.js"></script>
	<script src="/js/ajax.js"></script>
	<script src="/js/common-material.js"></script>
	<?php if (empty($user['name'])): ?>
		<script src="/js/sha256.js"></script>
	<?php endif ?>
	<?php if (!empty($page_scripts) && is_array($page_scripts)): ?>
		<?php foreach ($page_scripts as $page_script): ?>
		<script src="<?php echo $page_script ?>"></script>
		<?php endforeach ?>
	<?php endif ?>
	</body>
</html>
