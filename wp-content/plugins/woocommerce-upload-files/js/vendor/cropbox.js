/**
 * Created by ezgoing on 14/9/2014.
 */
'use strict';
var cropbox = function(options){
    var el = document.querySelector(options.imageBox),
    obj =
    {
        state : {},
        ratio : 1,
        options : options,
        imageBox : el,
        thumbBox : el.querySelector(options.thumbBox),
        spinner : el.querySelector(options.spinner),
        image : new Image(),
        getDataURL: function ()
        {
            var position_offset_width = options.controller_real_width/el.clientWidth,
				position_offset_height = options.controller_real_height/el.clientHeight,
				width = options.cropped_real_image_width,//this.thumbBox.clientWidth, //edit this for clipped image width
                height = options.cropped_real_image_height,//this.thumbBox.clientHeight, //edit this for clipped image height
                canvas = document.createElement("canvas"),
                dim = el.style.backgroundPosition.split(' '),
                size = el.style.backgroundSize.split(' '),
                dx = parseInt(dim[0])*position_offset_width - options.controller_real_width/2 + width/2,//parseInt(dim[0]) - el.clientWidth/2 + width/2,
                dy = parseInt(dim[1])*position_offset_height - options.controller_real_height/2 + height/2, //parseInt(dim[1]) - el.clientHeight/2 + height/2,
                dw = /* width, */parseFloat(size[0])*(position_offset_width),//parseInt(size[0]), //clip area width
                dh = /* height, */parseFloat(size[1])*(position_offset_height),//parseInt(size[1]), //clip area height
                sh = parseInt(this.image.height),
                sw = parseInt(this.image.width);
				
			/* console.log(position_offset_width);	
			console.log(position_offset_height);		
			console.log(parseInt(size[0])*position_offset_width);	
			console.log(parseInt(size[1])*position_offset_height);	
			console.log(dx);	
			console.log(dy);	
			console.log(dw);	
			console.log(dh);	 */
            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext("2d");
            context.drawImage(this.image, 0, 0, sw, sh, dx, dy, dw, dh);
            var imageData = canvas.toDataURL('image/jpeg', 0.85);
            return imageData;
        },
        getBlob: function()
        {
            var imageData = this.getDataURL();
            var b64 = imageData.replace('data:image/jpeg;base64,','');
            var binary = atob(b64);
            var array = [];
            for (var i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            return  new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
        },
        zoomIn: function ()
        {
            this.ratio*=1.1;
            setBackground();
        },
        zoomOut: function ()
        {
            this.ratio*=0.9;
            setBackground();
        }
    },
    attachEvent = function(node, event, cb)
    {
        if (node.attachEvent)
            node.attachEvent('on'+event, cb);
        else if (node.addEventListener)
            node.addEventListener(event, cb);
    },
    detachEvent = function(node, event, cb)
    {
        if(node.detachEvent) {
            node.detachEvent('on'+event, cb);
        }
        else if(node.removeEventListener) {
            node.removeEventListener(event, render);
        }
    },
    stopEvent = function (e) {
		e.preventDefault();
        if(window.event) e.cancelBubble = true;
        else e.stopImmediatePropagation();
    },
    setBackground = function()
    {
        var w =  parseInt(obj.image.width)*obj.ratio;
        var h =  parseInt(obj.image.height)*obj.ratio;
      
		//console.log(el.style.backgroundPosition == "");
		/* if(el.style.backgroundPosition == "") */
		{
			var pw = (el.clientWidth - w) / 2;
			var ph = (el.clientHeight - h) / 2; 
		}
		/* else
		{
			var position = el.style.backgroundPosition.split(' ');
			var size = el.style.backgroundSize.split(' ');
			var pw = parseInt(position[0]);
			var ph = parseInt(position[1]);
		} */

        el.setAttribute('style',
                'background-image: url(' + obj.image.src + '); ' +
                'background-size: ' + w +'px ' + h + 'px; ' +
                'background-position: ' + pw + 'px ' + ph + 'px; ' +
                'background-repeat: no-repeat');
    },
    imgMouseDown = function(e)
    {
        stopEvent(e);

        obj.state.dragable = true;
        obj.state.mouseX = e.clientX;
        obj.state.mouseY = e.clientY;
    },
	imgTouchDown = function(e)
    {
       stopEvent(e);

        obj.state.dragable = true;
        obj.state.mouseX = e.touches[0].clientX;
        obj.state.mouseY = e.touches[0].clientY;
    },
    imgMouseMove = function(e)
    {
        stopEvent(e);

        if (obj.state.dragable)
        {
            var x = e.clientX - obj.state.mouseX;
            var y = e.clientY - obj.state.mouseY;

            var bg = el.style.backgroundPosition.split(' ');

            var bgX = x + parseInt(bg[0]);
            var bgY = y + parseInt(bg[1]);

            el.style.backgroundPosition = bgX +'px ' + bgY + 'px';

            obj.state.mouseX = e.clientX;
            obj.state.mouseY = e.clientY;
        }
    },
	 imgTouchMove = function(e)
    {
        stopEvent(e);

        if (obj.state.dragable)
        {
            var x = e.touches[0].clientX - obj.state.mouseX;
            var y = e.touches[0].clientY - obj.state.mouseY;

            var bg = el.style.backgroundPosition.split(' ');

            var bgX = x + parseInt(bg[0]);
            var bgY = y + parseInt(bg[1]);

            el.style.backgroundPosition = bgX +'px ' + bgY + 'px';

            obj.state.mouseX = e.touches[0].clientX;
            obj.state.mouseY = e.touches[0].clientY;
        }
    },
    imgMouseUp = function(e)
    {
        stopEvent(e);
        obj.state.dragable = false;
    },
    zoomImage = function(e)
    {
        var evt=window.event || e;
        var delta=evt.detail? evt.detail*(-120) : evt.wheelDelta;
        delta > -120 ? obj.ratio*=1.1 : obj.ratio*=0.9;
        setBackground();
    },
	setInitialZoomRatio = function()
	{
		obj.ratio = options.pixel_ratio;
		//console.log(ratio);
		/* if(cropped_image_height > cropped_image_width)
		{
			ratio = cropped_image_width/cropped_image_height;
			cropped_image_height = Math.round(controller_height*2/3);
			cropped_image_width = Math.round(cropped_image_height*ratio);
		} 
		else
		{
			ratio = cropped_image_heightcropped_image_width;
			cropped_image_width =  Math.round(controller_width*2/3);
			cropped_image_height =  Math.round(controller_height*ratio);
		}  */
	};
	
    obj.spinner.style.display = 'block';
    obj.image.onload = function() {
        obj.spinner.style.display = 'none';
        attachEvent(el, 'mousedown', imgMouseDown);
        attachEvent(el, 'touchstart', imgTouchDown);
        attachEvent(el, 'mousemove', imgMouseMove);
        attachEvent(el, 'touchmove', imgTouchMove);
		
        attachEvent(document.body, 'mouseup', imgMouseUp);
        attachEvent(document.body, 'mouseover', imgMouseUp);
        attachEvent(document.body, 'mouseout', imgMouseUp);
       
		attachEvent(el, 'mouseover', imgMouseUp);
        attachEvent(el, 'mouseout', imgMouseUp);
        attachEvent(el, 'mouseup', imgMouseUp);
		attachEvent(el, 'touchend', imgMouseUp);
		
		setInitialZoomRatio();
        setBackground();
       /*  var mousewheel = (/Firefox/i.test(navigator.userAgent))? 'DOMMouseScroll' : 'mousewheel';
        attachEvent(el, mousewheel, zoomImage); */
    };
    obj.image.src = options.imgSrc;
    attachEvent(el, 'DOMNodeRemoved', function(){detachEvent(document.body, 'DOMNodeRemoved', imgMouseUp)});

    return obj;
};
