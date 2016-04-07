<?php
if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";}

$id = empty($place['id'])?'':$place['id'];
$name = empty($place['name'])?'':$place['name'];
$info = empty($place['info'])?'':$place['info'];
$address = empty($place['address'])?'':$place['address'];
$tags = empty($place['tags'])?array():$place['tags'];
$latitude = empty($place['latitude'])?'':$place['latitude'];
$longitude = empty($place['longitude'])?'':$place['longitude'];

$submit_text = empty($id)?'Add':'Save';
?>
<!-- Open Layers -->
<link rel="stylesheet" href="<?php echo site_url("/css/ol.css") ?>" type="text/css">
<style type="text/css" media="screen">
.map {
	height: 400px;
	width: 100%;
	border: 1px solid silver;
}
</style>
<div class="container">
	<!-- form + map-->
	<div class="section">
		<div class="row">
			<?php if (empty($user)): ?>
				<p class="red-text center-align">
					Warning: You are not authorized to add/edit places.
				</p>
			<?php endif ?>
			<h4>
				<?php echo empty($title) ? '' : $title ?>
			</h4>
			<form action="<?php echo site_url("/places/save") ?>" method="post">
				<div class="col s12 m6">
					<input type="hidden" name="place[id]" value="<?php echo $id ?>" id="place_id">
					<div class="input-field col s12">
						<label for="place_name">
							Name *
						</label>
						<input type="text" name="place[name]" value="<?php echo htmlspecialchars($name) ?>" id="place_name">
					</div>
					<div class="input-field col s12">
						<textarea class="materialize-textarea" name="place[info]" 
							id="place_info"><?php echo htmlspecialchars($info) ?></textarea>
						<label for="place_info">
							Info
						</label>
					</div>
					<div class="input-field col s12">
						<label for="place_info">
							Address
						</label>
						<textarea class="materialize-textarea" name="place[address]" 
							id="place_address"><?php echo htmlspecialchars($address) ?></textarea>
					</div>
					<fieldset class="input-field">
						<legend class="grey-text">Location</legend>
						<div class="input-field col s6">
							<label for="place_latitude" id="place_latitude_label">
								Latitude
							</label>
							<input type="text" readonly name="place[latitude]" value="<?php echo $latitude ?>" id="place_latitude">
						</div>
						<div class="input-field col s6">
							<label for="place_longitude" id="place_longitude_label"> 
								Longitude
							</label>
							<input type="text" readonly name="place[longitude]" value="<?php echo $longitude ?>" id="place_longitude">
						</div>
						<div class="input-field col s12 m6">
							<button onclick="getCurrentLocation();" class="blue btn-floating white-text lighten-2" 
								type="button" id="btn_locate_me">
								<i class="material-icons left">my_location</i>
							</button>
							&nbsp;&nbsp;
							<button onclick="setLocationFromMarker();" class="blue btn-floating white-text lighten-2" 
								type="button" id="btn_locate_me">
								<i class="material-icons left">location_on</i>
							</button>
						</div>
					</fieldset>
				</div>
				<div class="col s12 m6">
					<div id="map" class="input-field map"></div>
					<p>
						<button onclick="locateMe();" class="blue btn-flat white-text lighten-2" 
							type="button" id="btn_locate_me">
							<i class="material-icons left">my_location</i>
							Locate me
						</button>
					</p>
				</div>
				<div class="col s12">
					<fieldset class="input-field">
						<legend class="grey-text">Tags</legend>
						<?php foreach ($tag_list as $i => $tag): ?>
							<p class="col s6 m4 l2">
								<input <?php echo array_key_exists($tag['id'], $tags)?'checked':'' ?> 
									name="place[tags][<?php echo $tag['id'] ?>]" type="checkbox" 
									id="<?php echo "tag_{$tag['id']}" ?>" />
								<label title="<?php echo htmlspecialchars($tag['display_name']) ?>" for="<?php echo "tag_{$tag['id']}" ?>">
									<?php echo $tag['display_name'] ?>
								</label>
							</p>
						<?php endforeach ?>
				
					</fieldset>
				</div>
				<div class="input-field col s12">
					<input type="submit" class="btn" value="<?php echo $submit_text ?>" id="btn_submit">
				</div>
			</form>
		</div>
	</div>
	<!-- photos -->	
	<?php if (!empty($place['photos'])): ?>
	<div class="section">
		<h5>
			Photos
		</h5>
		<form method="post" id="form-place-photos" action="<?php echo site_url("/places/delete_photos") ?>">
			<input type="hidden" name="place[id]" value="<?php echo $id ?>" id="place_id">
			<input type="hidden" name="place[photos_to_delete]" value="" id="photos_to_delete">
			<div class="row">
				<?php foreach ($place['photos'] as $photo_id => $photo): ?>
					<div class="col s4 m2" style="position: relative;">
						<img class="circle responsive-img" src="<?php echo $photo['thumb'] ?>" 
							onclick="jQuery('<?php echo "#photo_chk_{$photo_id}" ?>').click();"
							height="<?php echo PHOTO_THUMB_HEIGHT ?>"
							width="<?php echo PHOTO_THUMB_WIDTH ?>"/>
						<p style="position: absolute; top: 0px; left: 0px; margin-top: 0px;">
							<input class="check-del_photo" type="checkbox" value="<?php echo $photo_id ?>"
								id="<?php echo "photo_chk_{$photo_id}" ?>"/>
							<label for="<?php echo "photo_chk_{$photo_id}" ?>">
								&nbsp;
							</label>
						</p>
					</div>
				<?php endforeach ?>
			</div>
			<div class="row">
				<button id="form-place-photos-submit" type="submit" class="btn red lighten-2" style="display: none;">
					Delete selected photos
				</button>
			</div>
		</form>
	</div>
	<?php endif ?>
</div>
<div style="display: none;">
	<img id="geolocation_marker" width="48" height="48" 
		src="<?php echo site_url("/img/48/Map-Marker-Marker-Outside-Azure-icon.png") ?>">
</div>
<script type="text/javascript">
document.getElementById('place_name').focus();
<?php if (!preg_match("/^0[.]0+$/",$latitude) && !preg_match("/^0[.]0+$/",$longitude)): ?>
var placeLocation = {
	latitude: <?php echo json_encode((float)$latitude) ?>,
	longitude: <?php echo json_encode((float)$longitude) ?> 
};
<?php else: ?>
var placeLocation = false;
<?php endif ?>
</script>
