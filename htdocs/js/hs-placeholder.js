var hsPlaceholders = {};
var phPatt = /^([1-9]\d+)x([1-9]\d+)$/;
var phBgColor = "#e1e1e1";
var phFgColor = "#8e8e8e";
var phFontSize = "15";

// Create the canvas element
var phCanvas = document.createElement('canvas');
phCanvas.setAttribute('style','display:none');
phCanvas.id = 'phCanvas';
var ctx = phCanvas.getContext("2d");
document.body.appendChild(phCanvas);

function hsPlaceholderProcess()
{
	// Get all IMG tags to process and list all required sizes
	var _imgTags = document.querySelectorAll('img.hs-placeholder[dyn-src]');
	for(var i = 0; i < _imgTags.length; i++)
	{
		var dataSrc = _imgTags[i].getAttribute('dyn-src');
		var imgSrc = _imgTags[i].getAttribute('src');
		if(phPatt.test(dataSrc) && imgSrc.length == 0)
		{
			var parts = phPatt.exec(dataSrc);
			var w = parts[1];
			var h = parts[2];
			hsPlaceholders[dataSrc] = {'w':w,'h':h};
			_imgTags[i].setAttribute('width',w);
			_imgTags[i].setAttribute('height',h);
		}
	}
	// Process the IMG tags
	for(var pHolder in hsPlaceholders)
	{
		var w = hsPlaceholders[pHolder].w;
		var h = hsPlaceholders[pHolder].h;
		phCanvas.setAttribute('width',w);
		phCanvas.setAttribute('height',h);
		ctx.fillStyle = phBgColor;
		ctx.fillRect(0,0,w,h);
		ctx.moveTo(0,0);
		ctx.fillStyle = phFgColor;
		ctx.textAlign="center";
		ctx.font = phFontSize + "px sans-serif";
		ctx.fillText(w+" x "+h,(w/2),(h/2)+(phFontSize/3));
		var imgData = phCanvas.toDataURL();
		for(var i = 0; i < _imgTags.length; i++)
		{
			if(_imgTags[i].getAttribute('dyn-src') == pHolder)
			{
				_imgTags[i].setAttribute('src', imgData);
			}
		}
	}
} // - - - - - - end of function hsPlaceholderInit - - - - - -
