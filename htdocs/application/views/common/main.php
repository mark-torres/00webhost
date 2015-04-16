<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>My places<?php echo empty($page_title)?'':" :: ".$page_title ?></title>
	<meta name="author" content="Mark Torres">
	<link rel="stylesheet" href="/css/kickstart.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/css/popup.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/css/common.css" type="text/css" media="screen">
	<link href="http://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet" type="text/css">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="/js/jquery_1.9.1.min.js"></script>
</head>
<body>
	<div class="grid">
	<ul class="menu">
		<li id="main_menu-home">
			<a href="<?php echo site_url("/welcome") ?>">Home</a>
		</li>
		<li id="main_menu-search">
			<a href="<?php echo site_url("/places/search") ?>">Places</a>
		</li>
	</ul>
	<div id="user_links">
		<?php if (!empty($user['name'])): ?>
			<a id="link_log_in_out" href="/users/logout">
				<i class="icon-user"></i>
				<span>Logout <?php echo $user['name'] ?></span>
			</a>
		<?php else: ?>
			<a id="link_log_in_out" href="#" onclick="mainPopup.open('/users/ajax_login'); return false;">
				<i class="icon-user"></i>
				<span>Login</span>
			</a>
		<?php endif ?>
	</div>
	<!-- content starts -->
		<?php if(!empty($page_content)) echo $page_content ?>
	<!-- content ends -->
	<hr/>
	<p align="center">
		Powered by <a href="http://www.99lime.com/elements/" target="_blank">HTML KickStart</a> + HSoft&trade;
		<br>
		2015 All rights reserved
	</p>
	</div>
	<script src="/js/kickstart.js"></script>
	<script src="/js/jquery.popup.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/ajax.js"></script>
	<script src="/js/common.js"></script>
</body>
</html>

