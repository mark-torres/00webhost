<?php if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";} ?>

<?php if (!empty($place['latitude']) && !empty($place['longitude'])): ?>
	<link rel="stylesheet" href="/css/ol.css" type="text/css">
	<style type="text/css" media="screen">
	.ol-map {
		height: 400px;
		width: 100%;
	}
	</style>
<?php endif ?>

<div class="container">
	<?php if (empty($place)): ?>
		<div class="section">
			<h4>Ooops!</h4>
			<p>Especified place does not exist.</p>
		</div>
	<?php else: ?>		
		<div class="section">
			<div class="row">
				<div class="col s12 m8">
					<h3>
						<?php echo $place['name'] ?>
					</h3>
					<?php if (!empty($place['tags']) && is_array($place['tags'])): ?>
					<i class="tiny material-icons">label</i>
					<?php
					$last_tag = array_pop($place['tags']);
					?>
						<?php foreach ($place['tags'] as $tag): ?>
							<a href="/places/search/type/tag/keyword/<?php echo $tag['name'] ?>">
								<?php echo $tag['display_name'] ?>
							</a>,
						<?php endforeach ?>
							<a href="/places/search/type/tag/keyword/<?php echo $last_tag['name'] ?>">
								<?php echo $last_tag['display_name'] ?>
							</a>
					<?php endif ?>
				</div>
				<?php if (!empty($user)): ?>
					<div class="col s12 m4">
						<a class="btn-floating red lighten-2" href="#" onclick="likePlace(<?php echo (int)$place['id'] ?>);return false;">
							<i class="material-icons">thumb_down</i>
						</a>
						&nbsp;&nbsp;
						<a class="btn-floating green lighten-2" href="#" onclick="dislikePlace(<?php echo (int)$place['id'] ?>);return false;">
							<i class="material-icons">thumb_up</i>
						</a>
						&nbsp;&nbsp;
						<a class="btn-floating blue lighten-2" href="<?php echo site_url("/places/edit/{$place['id']}") ?>">
							<i class="material-icons">mode_edit</i>
						</a>
					</div>
				<?php endif ?>
			</div>
			<div class="row">
				<div class="col s12 m6">
					<strong>Info:</strong>
					<br>
					<?php echo nl2br($place['info']) ?>
				</div>
				<div class="col s12 m4">
					<strong>Address:</strong>
					<br>
					<?php echo nl2br($place['address']) ?>
				</div>
				<div class="col s12 m2">
					<strong>Popularity:</strong>
					<br>
					<i class="tiny material-icons">thumb_up</i>
					<span id="place_likes"><?php echo $place['likes'] ?></span>
					&nbsp;&nbsp;
					<i class="tiny material-icons">thumb_down</i>
					<span id="place_dislikes"><?php echo $place['dislikes'] ?></span>
				</div>
			</div>
		</div>
		<?php if (!preg_match("/^0[.]0+$/",$place['latitude']) && !preg_match("/^0[.]0+$/",$place['longitude'])): ?>
			<div class="section">
				<div class="row">
					<div id="ol-map" class="ol-map"></div>
					<a class="btn-flat" href="#" onclick="resetMapView(); return false;">
						Reset map position
					</a>
				</div>
			</div>
		<?php endif ?>
		
		<div class="section">
			<div class="row">
				<div class="col s12 m3">
					<h4>
						Photo gallery
					</h4>
				</div>
				<?php if (!empty($user)): ?>
					<div class="col s12 m2">
						<a class="btn-floating red lighten-2" href="#" onclick="return false;" id="btn-add-photo">
							<i class="material-icons">add</i>
						</a>
					</div>
				<?php endif ?>
			</div>
			<?php if (!empty($user)): ?>
				<div id="form-upload-container" class="row" style="display: none;">
					<form id="file-form" method="POST" onsubmit="return false;">
						<input type="hidden" name="place_id" value="<?php echo $place['id'] ?>" id="place_id">
						<div class="file-field input-field">
							<div class="btn blue lighten-2">
								<span>Choose File</span>
								<input id="file-select" type="file">
							</div>
							<div class="file-path-wrapper">
								<input class="file-path" type="text">
							</div>
						</div>
						<div class="input-field">
							<input type="text" name="photo_descr" value=""
								placeholder="Short description" id="photo_descr" maxlength="30">
						</div>
						<div class="input-field">
							<button type="submit" class="btn" id="form-btn-upload">
								Upload
								<i class="material-icons right">send</i>
							</button>
							<button type="reset" class="btn" id="form-btn-cancel">
								Cancel
							</button>
						</div>
					</form>
					<div class="progress" id="upload-progress-container" style="display: none;">
						<div id="upload-progress-bar" class="determinate" style="width: 0%"></div>
					</div>
				</div>
			<?php endif ?>
			<div class="row">
				<?php if (!empty($place['photos']) && is_array($place['photos'])): ?>
					<?php foreach ($place['photos'] as $photo_id => $photo): ?>
						<div id="place_photo_gallery" class="col s12 m2">
								<img class="responsive-img" src="<?php echo $photo['thumb'] ?>" 
									height="<?php echo PHOTO_THUMB_HEIGHT ?>"
									width="<?php echo PHOTO_THUMB_WIDTH ?>"/>
						</div>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>
		
	<?php endif ?>
</div>
<script type="text/javascript">
var placeLocation = [<?php echo $place['longitude'] ?>, <?php echo $place['latitude'] ?>];
var placeScore = <?php echo json_encode(array('likes'=>$place['likes'], 'dislikes'=>$place['dislikes'])) ?>;

var btnAddPhoto = document.getElementById('btn-add-photo');
var btnCancel = document.getElementById('form-btn-cancel');
var btnUpload = document.getElementById('form-btn-upload');
var progContainer = document.getElementById('upload-progress-container');
var progressBar = document.getElementById('upload-progress-bar');
var formContainer = document.getElementById('form-upload-container');
var frmUploadPhoto = document.getElementById('file-form');
</script>
