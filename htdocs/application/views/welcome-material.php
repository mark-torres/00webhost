<?php if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";} ?>

<?php
// shorten texts
foreach ($popular as $key => $place)
{
	if(strlen($place['info']) > PREVIEW_TEXT_LEN)
	{
		$popular[$key]['info'] = substr($place['info'], 0, PREVIEW_TEXT_LEN)."...";
	}
}
?>
<div class="section no-pad-bot" id="index-banner">
	<div class="container">
		<br><br>
		<h1 class="header center orange-text">Welcome!</h1>
		<div class="row center">
			<h5 class="header col s12 light">Need a bar or restaurant? Find it here!</h5>
		</div>
		<div class="row center">
			<a href="<?php echo site_url("/places/search") ?>" class="btn-large waves-effect waves-light orange">Find a place</a>
		</div>
		<br><br>

	</div>
</div>
<div class="container">
	<div class="section">

		<!--	 Icon Section	 -->
		<div class="row">
			<div class="col s12 m4">
				<div class="icon-block">
					<h2 class="center light-blue-text"><i class="material-icons">thumb_up</i></h2>
					<h5 class="center">Popular places</h5>
					
					<?php if (empty($popular)): ?>
						<p class="light">There are no popular places.</p>
					<?php else: ?>
						
						<?php foreach ($popular as $pop_place): ?>
							
							<p>
								<strong>
									<?php echo $pop_place['name'] ?>
								</strong>
								<br>
								<i class="material-icons">thumb_up</i>
								<?php echo sprintf("%.1f %%", $pop_place['popularity']) ?>
							</p>
							<p class="light">
								<?php echo $pop_place['info'] ?>
								<br>
								<a href="<?php echo site_url("/places/details/".$pop_place['id']) ?>">
									view
								</a>
							</p>
							<p>&nbsp;</p>
							
						<?php endforeach ?>

					<?php endif ?>
					
				</div>
			</div>

			<div class="col s12 m4">
				<div class="icon-block">
					<h2 class="center light-blue-text"><i class="material-icons">search</i></h2>
					<h5 class="center">Find by tags</h5>

					<?php if (empty($tags)): ?>
						<p class="light">
							No tags found
						</p>
					<?php else: ?>
						
						<p class="light center-align">
							<?php
							$last_tag = array_pop($tags);
							?>
							<?php foreach ($tags as $tag): ?>
								<a href="/places/search/type/tag/keyword/<?php echo $tag['name'] ?>">
									<?php echo $tag['display_name'] ?>
								</a>, 
							<?php endforeach ?>
						
								<a href="/places/search/type/tag/keyword/<?php echo $last_tag['name'] ?>">
									<?php echo $last_tag['display_name'] ?>
								</a>
						</p>
						
					<?php endif ?>
				</div>
			</div>

			<div class="col s12 m4">
				<div class="icon-block">
					<h2 class="center light-blue-text"><i class="material-icons">perm_phone_msg</i></h2>
					<h5 class="center">Order</h5>

					<p class="light">
						Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean lacinia bibendum nulla sed consectetur.
					</p>
				</div>
			</div>
		</div>

	</div>
	<br><br>

	<div class="section">

	</div>
</div>

