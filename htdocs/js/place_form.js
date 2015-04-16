var posMorelia = [-101.1922, 19.7030];

// ====================
// = OPEN LAYER STUFF =
// ====================
var markerAdded = false;
var markerIcon = new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
	anchor: [24, 47],
	anchorXUnits: 'pixels',
	anchorYUnits: 'pixels',
	opacity: 0.95,
	src: '/img/48/Map-Marker-Marker-Outside-Azure-icon.png'
}));
var markerPosition = ol.proj.transform(posMorelia, 'EPSG:4326', 'EPSG:3857');
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
	center: ol.proj.transform(posMorelia, 'EPSG:4326', 'EPSG:3857'),
	zoom: 10
});

var osmLayer = new ol.layer.Tile({
	source: new ol.source.OSM()
});

var map = new ol.Map({
	target: 'map',
	interactions: ol.interaction.defaults().extend([new app.Drag()]),
	layers: [osmLayer],
	view: mapView
});

// ============
// = MY STUFF =
// ============
function setMapLocation(position)
{
	var currentPosition = ol.proj.transform([position.coords.longitude, position.coords.latitude], 'EPSG:4326', 'EPSG:3857');
	mapView.setZoom(17);
	mapView.setCenter(currentPosition);
	markerPoint = new ol.geom.Point(currentPosition);
	markerFeature.setGeometry(markerPoint);
	if(!markerAdded)
	{
		markerAdded = true;
		map.addLayer(markerLayer);
	}
} // - - - - - - end of function setMapLocation - - - - - -

function locateMe()
{
	if(geolocationSupport())
	{
		// jQuery('#map').html('Loading map, please wait...');
		navigator.geolocation.getCurrentPosition(setMapLocation);
	}
	else
	{
		alert("This browser does not support geolocation");
	}
} // - - - - - - end of function startMap - - - - - -

function getMarkerLocation()
{
	var g = markerFeature.getGeometry();
	var c = g.getCoordinates();
	var p = ol.proj.transform(c,'EPSG:3857','EPSG:4326');
	var l = {lon: p[0], lat: p[1]};
	return l;
} // - - - - - - end of function getMapLocation - - - - - -

function setLocationFromMarker()
{
	var mPos = getMarkerLocation();
	jQuery('#place_latitude').val(mPos.lat);
	jQuery('#place_longitude').val(mPos.lon);
} // - - - - - - end of function setLocationFromMarker - - - - - -

function getCurrentLocation()
{
	if(geolocationSupport())
	{
		jQuery('#place_latitude').val('Please wait...');
		jQuery('#place_longitude').val('Please wait...');
		navigator.geolocation.getCurrentPosition(setCurrentLocation);
	}
	else
	{
		alert("This browser does not support geolocation");
	}
} // - - - - - - end of function getCurrentLocation - - - - - -

function setCurrentLocation(position)
{
	jQuery('#place_latitude').val(position.coords.latitude);
	jQuery('#place_longitude').val(position.coords.longitude);
} // - - - - - - end of function setCurrentLocation - - - - - -
