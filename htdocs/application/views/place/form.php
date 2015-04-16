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
<link rel="stylesheet" href="http://openlayers.org/en/v3.1.0/css/ol.css" type="text/css">
<script src="http://openlayers.org/en/v3.1.0/build/ol.js" type="text/javascript"></script>
<style type="text/css" media="screen">

.map {
	height: 400px;
	width: 100%;
	border: 1px solid silver;
}
	
</style>
<h4>
	<?php echo empty($title) ? '' : $title ?>
</h4>
<?php if (empty($user)): ?>
	<div class="notice warning">
		<i class="icon-warning-sign icon-large"></i>
		Warning: You are not authorized to add/edit places.
		<a href="#close" class="icon-remove"></a>
	</div>
<?php endif ?>
<div class="col_6">
	<form class="vertical" action="/places/save" method="post">
		
		<input type="hidden" name="place[id]" value="<?php echo $id ?>" id="place_id">
		
		<label>
			Name <span>(required)</span>
			<input type="text" name="place[name]" value="<?php echo $name ?>" id="place_name">
		</label>
		<?php if (!empty($error['name'])): ?>
		<div class="notice error">
			<i class="icon-remove-sign icon-large"></i>
			<?php echo $error['name'] ?>
			<a href="#close" class="icon-remove"></a>
		</div>
		<?php endif ?>
		
		<label>
			Info
			<textarea name="place[info]" id="place_info"><?php echo $info ?></textarea>
		</label>
		<?php if (!empty($error['info'])): ?>
		<div class="notice error">
			<i class="icon-remove-sign icon-large"></i>
			<?php echo $error['info'] ?>
			<a href="#close" class="icon-remove"></a>
		</div>
		<?php endif ?>
		
		<label>
			Address
			<textarea name="place[address]" id="place_address"><?php echo $address ?></textarea>
		</label>
		<?php if (!empty($error['address'])): ?>
		<div class="notice error">
			<i class="icon-remove-sign icon-large"></i>
			<?php echo $error['address'] ?>
			<a href="#close" class="icon-remove"></a>
		</div>
		<?php endif ?>
		
		<fieldset>
			<legend>Tags</legend>
			
			<?php foreach ($tag_list as $tag): ?>
				<label for="<?php echo "tag_{$tag['id']}" ?>" class="inline">
					<input <?php echo array_key_exists($tag['id'], $tags)?'checked':'' ?> 
						name="place[tags][<?php echo $tag['id'] ?>]" type="checkbox" 
						id="<?php echo "tag_{$tag['id']}" ?>" />
					<?php echo $tag['display_name'] ?>
				</label>
				&nbsp;&nbsp;
			<?php endforeach ?>
						
		</fieldset>
		
		<fieldset>
			<legend>Location</legend>
			<div class="col_6">
				<label>
					Latitude
				</label>
				<input type="text" readonly name="place[latitude]" value="<?php echo $latitude ?>" id="place_latitude">
			</div>
			<div class="col_6">
				<label>
					Longitude
				</label>
				<input type="text" readonly name="place[longitude]" value="<?php echo $longitude ?>" id="place_longitude">
			</div>
			<?php if (!empty($error['location'])): ?>
			<div class="notice error">
				<i class="icon-remove-sign icon-large"></i>
				<?php echo $error['location'] ?>
				<a href="#close" class="icon-remove"></a>
			</div>
			<?php endif ?>
			<div class="col_6">
				<input class="orange" onclick="getCurrentLocation();" type="button"
					name="btn_current_location" value="Get current location" id="btn_current_location">
			</div>
			<div class="col_6">
				<input onclick="setLocationFromMarker();" class="orange" type="button"
					name="btn_map_location" value="Get marker location" id="btn_map_location">
			</div>

		</fieldset>
		<input type="hidden" name="photos_to_delete" value="" id="photos_to_delete">
		<input type="submit" class="blue" name="btn_submit" value="<?php echo $submit_text ?>" id="btn_submit">
	</form>
</div>
<div class="col_6">
	
	<div id="map" class="map"></div>
	<p>
		<input onclick="locateMe();" class="orange" type="button" name="btn_locate_me" value="Locate me!" id="btn_locate_me">
	</p>
</div>
<?php if (!empty($place['photos'])): ?>
<div class="col_12">
	<?php foreach ($place['photos'] as $photo_id => $photo): ?>
		<div class="caption">
			<img src="<?php echo $photo['thumb'] ?>" 
				height="<?php echo PHOTO_THUMB_HEIGHT ?>"
				width="<?php echo PHOTO_THUMB_WIDTH ?>"/>
			<span>
				<label for="<?php echo "photo_{$photo_id}" ?>">
					<input class="check-del_photo" type="checkbox" name="<?php echo "photo_{$photo_id}" ?>"
						value="<?php echo $photo_id ?>" id="<?php echo "photo_{$photo_id}" ?>">
					Delete this photo
				</label>
			</span>
		</div>
	<?php endforeach ?>
</div>
<?php endif ?>
<div style="display: none;">
	<img id="geolocation_marker" src="/img/48/Map-Marker-Marker-Outside-Azure-icon.png"
		width="48" height="48">
</div>
<script src="/js/location.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/ol-drag.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/place_form.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
jQuery('#place_name').focus();

<?php if (!preg_match("/^0[.]0+$/",$place['latitude']) && !preg_match("/^0[.]0+$/",$place['longitude'])): ?>
// If place already has location
var placeLocation = ol.proj.transform(
	[<?php echo $place['longitude'] ?>,<?php echo $place['latitude'] ?>],
	'EPSG:4326',
	'EPSG:3857'
);
mapView.setZoom(15);
mapView.setCenter(placeLocation);
markerPoint = new ol.geom.Point(placeLocation);
markerFeature.setGeometry(markerPoint);
markerAdded = true;
map.addLayer(markerLayer);
<?php endif ?>

function addToDeletionList(photoId)
{
	var photos = jQuery('#photos_to_delete').val();
	var list = [];
	if(photos.length > 0)
	{
		list = photos.split(',');
	}
	var pos = list.indexOf(photoId);
	if(pos < 0)
	{
		list.push(photoId);
		list.sort();
		jQuery('#photos_to_delete').val(list.join(','));
		console.log(list);
	}
} // - - - - - - end of function addToDeletionList - - - - - -

function removeFromDeletionList(photoId)
{
	var photos = jQuery('#photos_to_delete').val();
	var list = [];
	if(photos.length > 0)
	{
		list = photos.split(',');
	}
	var pos = list.indexOf(photoId);
	if(pos >= 0)
	{
		list.splice(pos, 1);
		list.sort();
		jQuery('#photos_to_delete').val(list.join(','));
		console.log(list);
	}
} // - - - - - - end of function removeFromDeletionList - - - - - -

jQuery(document).ready(function () {
	// delete photo switch
	jQuery('input.check-del_photo').on('change', function () {
		var photoId = jQuery(this).val();
		if(this.checked)
		{
			addToDeletionList(photoId);
		}
		else
		{
			removeFromDeletionList(photoId);
		}
	});
});

</script>
