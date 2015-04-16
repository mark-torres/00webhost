var userPlaceScore = 0;
var placeScore = {
	likes: 0,
	dislikes: 0
};

function likePlace(placeId)
{
	var likePromise = jQuery.ajax({
		url: '/users/like_place/'+placeId,
		dataType: 'json'
	});
	
	likePromise.done(function (response) {
		console.log(response);
		if(response.liked.success)
		{
			if(typeof response.liked.changed != 'undefined' && response.liked.changed)
			{
				// changed from dislike to like: adjust numbers
				placeScore.likes++;
				placeScore.dislikes--;
			}
			else if(response.liked.new)
			{
				placeScore.likes++;
			}
			updatePlacePopularity();
		}
		else
		{
			var msg = "Error saving data";
			if(typeof response.message != 'undefined')
			{
				msg = response.message;
			}
			alert(msg);
		}
	});
	likePromise.fail(function () {
		alert('Error posting data, please try again later');
	});
} // - - - - - - end of function likePlace - - - - - -

function dislikePlace(placeId)
{
	var dislikePromise = jQuery.ajax({
		url: '/users/dislike_place/'+placeId,
		dataType: 'json'
	});
	
	dislikePromise.done(function (response) {
		console.log(response);
		if(response.disliked.success)
		{
			if(typeof response.disliked.changed != 'undefined' && response.disliked.changed)
			{
				// changed from dislike to like: adjust numbers
				placeScore.dislikes++;
				placeScore.likes--;
			}
			else if(response.disliked.new)
			{
				placeScore.dislikes++;
			}
			updatePlacePopularity();
		}
		else
		{
			var msg = "Error saving data";
			if(typeof response.message != 'undefined')
			{
				msg = response.message;
			}
			alert(msg);
		}
	});
	dislikePromise.fail(function () {
		alert('Error saving data, please try again later');
	});
} // - - - - - - end of function likePlace - - - - - -

function updatePlacePopularity()
{
	jQuery('#place_likes').html(placeScore.likes);
	jQuery('#place_dislikes').html(placeScore.dislikes);
} // - - - - - - end of function updatePlacePopularity - - - - - -
