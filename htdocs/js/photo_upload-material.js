// REFERENCE
// Uploading Files with AJAX: http://blog.teamtreehouse.com/uploading-files-ajax
// JS AJAX File Upload with Progress: http://codular.com/javascript-ajax-file-upload-with-progress
// Simple File Uploads Using jQuery & AJAX: http://abandon.ie/notebook/simple-file-uploads-using-jQuery-ajax

var photoForm = document.getElementById('file-form');
var fileSelect = document.getElementById('file-select');
// var uploadButton = document.getElementById('upload-button');
var placeId = document.getElementById('place_id').value;

if(typeof thumbHeight == 'undefined')
	var thumbHeight = 200;

if(typeof thumbWidth == 'undefined')
	var thumbWidth = 200;

// progress on transfers from the server to the client (downloads)
function updateProgress (oEvent) {
	if (oEvent.lengthComputable) {
		console.log(oEvent.loaded / oEvent.total);
		var percentComplete = Math.ceil(oEvent.loaded / oEvent.total * 100) + '%';
		jQuery(progressBar).attr('class','determinate');
		jQuery(progressBar).css('width',percentComplete);
		// uploadButton.value = 'Uploading... ' + percentComplete;
		// ...
	} else {
		// Unable to compute progress information since the total size is unknown
		jQuery(progressBar).removeAttr('style');
		jQuery(progressBar).attr('class','indeterminate');
		// uploadButton.value = 'Uploading, please wait...';
	}
}

photoForm.onsubmit = function(event)
{
	event.preventDefault();

	// Hide form and show progress bar
	jQuery(formContainer).hide();
	jQuery(progressBar).css('width','1%');
	jQuery(progContainer).show();

	// Get the selected files from the input.
	var files = fileSelect.files;
	
	// Create a new FormData object.
	var formData = false;
	if(typeof FormData == 'function')
	{
		formData = new FormData();
	}
	else
	{
		alert('AJAX upload not supported.');
		return false;
	}
	
	// add place id
	formData.append('place_id', placeId);
	// add description
	var photoDescr = document.getElementById('photo_descr').value;
	formData.append('photo_descr', photoDescr);
	
	// Blobs
	// formData.append(name, blob, filename);
	// Strings
	// formData.append(name, value);
	
	// Loop through each of the selected files.
	for (var i = 0; i < files.length; i++) {
		var file = files[i];

		// Check the file type.
		if (!file.type.match('image.*')) {
			continue;
		}

		// Add the file to the request.
		formData.append('photos[]', file, file.name);
	}
	
	// Set up the request.
	var xhr = false;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xhr = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// add pregress event listener BEFORE open()
	if(typeof xhr.onprogress != 'undefined')
		xhr.onprogress = updateProgress;
	if(typeof xhr.upload.onprogress != 'undefined')
		xhr.upload.onprogress = updateProgress;
	
	// Open the connection.
	xhr.open('POST', '/places/add_photo', true);
	
	// Set up a handler for when the request finishes.
	xhr.onload = function () {
		// uploadButton.value = 'Upload';
		if (xhr.status === 200) {
			// File(s) uploaded.
			var response = jQuery.parseJSON(xhr.response);
			if(response.success) {
				var div = jQuery('<div/>');
				var caption = jQuery('<span/>');
				caption.html(response.photo.description)
				div.addClass('caption');
				var photo = jQuery("<img/>");
				photo.attr('src', response.photo.thumb_src);
				photo.attr('height', thumbHeight).attr('width', thumbWidth);
				var link = jQuery("<a/>");
				link.attr('rel','gallery0');
				link.attr('target', '_blank').attr('href', response.photo.src).addClass('place_gallery');
				link.append(photo);
				link.append(caption);
				div.append(link);
				jQuery('#place_photo_gallery').append(div);
				photoForm.reset();
			} else {
				matAlert(response.message);
			}
		} else {
			matAlert('Error posting files. Please try again later.');
		}
	};
	
	// Send the Data.
	xhr.send(formData);
}
