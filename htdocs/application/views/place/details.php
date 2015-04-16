<?php if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";} ?>

<?php if (!empty($place['latitude']) && !empty($place['longitude'])): ?>
	<link rel="stylesheet" href="/css/ol.css" type="text/css">
	<script src="/js/ol.js" type="text/javascript"></script>
	<style type="text/css" media="screen">
	.map {
	  height: 400px;
	  width: 100%;
	  border: 1px solid silver;
	}
	a.place_gallery {
		display: inline-block;
		position: relative;
		text-decoration: none;
/*		border: 1px solid #DDD;*/
/*		background: none repeat scroll 0% 0% #FFF;*/
/*		padding: 3px;*/
/*		margin: 5px;*/
/*		border-radius: 5px;*/
	}
	a.place_gallery img {
		border: 0px none;
		display: block;
		position: relative;
		margin: 0px;
		padding: 0px;
	}
	div.photo_thumbnail {
		background-repeat: no-repeat;
		width: 200px;
		height: 200px;
		background-size: 100% 100%;
		width: <?php echo PHOTO_THUMB_WIDTH ?>px;
		height: <?php echo PHOTO_THUMB_HEIGHT ?>px;
	}
	</style>
<?php endif ?>

<?php if (empty($place)): ?>
	<h4>Ooops!</h4>
	<p>Especified place does not exist.</p>
<?php else: ?>
	<h4>
		<?php echo $place['name'] ?>
	</h4>
	<div class="col_6">
		<?php if (!empty($user)): ?>
			<ul class="button-bar">
				<li>
					<a href="/places/edit/<?php echo $place['id'] ?>"><i class="icon-pencil"></i> Edit</a>
				</li>
				<li>
					<a href="#" onclick="likePlace(<?php echo (int)$place['id'] ?>);return false;"
						id="btn_place-like">
						<i class="icon-thumbs-up"></i> Like
					</a>
				</li>
				<li>
					<a href="#" onclick="dislikePlace(<?php echo (int)$place['id'] ?>);return false;"
						id="btn_place-dislike">
						<i class="icon-thumbs-down"></i> Dislike
					</a>
				</li>
			</ul>
		<?php endif ?>
		<p>
			<strong>Info:</strong>
			<br>
			<?php echo nl2br($place['info']) ?>
		</p>
		<p>
			<strong>Address:</strong>
			<br>
			<?php echo nl2br($place['address']) ?>
		</p>
		<p>
			<strong>Popularity:</strong>
			&nbsp;
			<i class="icon-thumbs-up"></i>
			<span id="place_likes"><?php echo $place['likes'] ?></span>
			&nbsp;&nbsp;
			<i class="icon-thumbs-down"></i>
			<span id="place_dislikes"><?php echo $place['dislikes'] ?></span>
		</p>
		<?php if (!empty($place['tags'])): ?>
			<p>
				<strong>Tags:</strong>
				<?php foreach ($place['tags'] as $tag): ?>
					<button href="/places/search/type/tag/keyword/<?php echo $tag['name'] ?>"
						class="link small pill">
						<i class="icon-tag"></i>
						<?php echo $tag['display_name'] ?>
					</button>
				<?php endforeach ?>
			</p>
		<?php endif ?>
	</div>
	<?php if (!preg_match("/^0[.]0+$/",$place['latitude']) && !preg_match("/^0[.]0+$/",$place['longitude'])): ?>
		<div class="col_6">
			<div id="map" class="map"></div>
			<a href="#" onclick="resetMapView(); return false;">
				Reset map position
			</a>
		</div>
		<script type="text/javascript">
		var placeLocation = [<?php echo $place['longitude'] ?>, <?php echo $place['latitude'] ?>];
		var viewZoom = 16;
		var markerIcon = new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
			anchor: [24, 47],
			anchorXUnits: 'pixels',
			anchorYUnits: 'pixels',
			opacity: 0.95,
			src: '/img/48/Map-Marker-Marker-Outside-Azure-icon.png'
		}));
		var markerPosition = ol.proj.transform(placeLocation, 'EPSG:4326', 'EPSG:3857');
		var markerPoint = new ol.geom.Point(markerPosition);
		var markerFeature = new ol.Feature(markerPoint);
		var markerLayer = new ol.layer.Vector({
			source: new ol.source.Vector({
				features: [markerFeature]
			}),
			style: new ol.style.Style({
				image: markerIcon
			})
		});

		var mapView = new ol.View({
			center: ol.proj.transform(placeLocation, 'EPSG:4326', 'EPSG:3857'),
			zoom: viewZoom
		});

		var osmLayer = new ol.layer.Tile({
			source: new ol.source.OSM()
		});

		var map = new ol.Map({
			target: 'map',
			layers: [osmLayer, markerLayer],
			view: mapView
		});
		
		function resetMapView()
		{
			mapView.setZoom(viewZoom);
			mapView.setCenter(markerPosition);
		} // - - - - - - end of function resetMapView - - - - - -
		</script>
	<?php endif ?>
	
	<?php if (!empty($user)): ?>
	<div class="col_6">	
		<form id="file-form" method="POST" onsubmit="return false;">
			<fieldset>
				<legend>
					Upload a photo
				</legend>
				<input type="hidden" name="place_id" value="<?php echo $place['id'] ?>" id="place_id">
				<input type="text" name="photo_descr" value=""
					placeholder="Short description" id="photo_descr" maxlength="30">
				<input style="background-color: transparent;" type="file" id="file-select"/><br><br>
				<input type="submit" class="small blue" name="upload" value="Upload" id="upload-button">
			</fieldset>
		</form>
		<script src="/js/photo_upload.js" type="text/javascript" charset="utf-8"></script>
	</div>
	<?php endif ?>
	<div id="place_photo_gallery" class="col_12">
		<?php foreach ($place['photos'] as $photo_id => $photo): ?>
			<div class="caption">
				<a rel="gallery0" class="place_gallery" href="<?php echo $photo['src'] ?>" target="_blank">
					<img src="<?php echo $photo['thumb'] ?>" 
						height="<?php echo PHOTO_THUMB_HEIGHT ?>"
						width="<?php echo PHOTO_THUMB_WIDTH ?>"/>
					<span>
						<?php echo $photo['descr'] ?>
					</span>
				</a>
			</div>
		<?php endforeach ?>
	</div>
	
<?php endif ?>
<script src="/js/user.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
// rewrite placeScore
var placeScore = <?php echo json_encode(array('likes'=>$place['likes'], 'dislikes'=>$place['dislikes'])) ?>;
jQuery(document).ready(function () {
	jQuery('a.place_gallery').fancybox();
});
</script>
