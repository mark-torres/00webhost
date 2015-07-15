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
	target: 'ol-map',
	layers: [osmLayer, markerLayer],
	view: mapView
});

function resetMapView()
{
	mapView.setZoom(viewZoom);
	mapView.setCenter(markerPosition);
} // - - - - - - end of function resetMapView - - - - - -

function showUploadPhotoForm() {
	jQuery(btnAddPhoto).fadeOut();
	jQuery(progContainer).hide();
	jQuery(formContainer).slideDown();
}
function hideUploadPhotoForm() {
	jQuery(formContainer).slideUp();
	jQuery(btnAddPhoto).fadeIn();
	jQuery(progContainer).hide();
}

function viewPlaceProducts() {
	jQuery('#modal-product-content').load(siteUrl('/places/ajax_product_list/'+placeId));
}

jQuery(document).ready(function() {
	jQuery(btnAddPhoto).on('click',function () {
		showUploadPhotoForm();
	});
	jQuery(frmUploadPhoto).on('reset',function () {
		hideUploadPhotoForm();
	});
	jQuery('#lnk-products').leanModal({
			dismissible: true,
			opacity: .5,
			ready: viewPlaceProducts,
			complete: function () {
				jQuery('#modal-product-loader').show();
				jQuery('#modal-product-content').html('');
				if (reloadAfterModal) {
					location.reload();
				}
			}
		});
});

