jQuery(document).on('click', '.wcuf_crop_button', wcuf_prevent_default);

function wcuf_image_crop(evt, myId, callback)
{
	this.evt = evt;
	this.id = myId;
	this.callback = callback;
	this.reader = new FileReader();
	var mySelf = this;
	this.reader.onload = function(e)
	{
		wcuf_on_image_to_crop_loaded(e, evt,myId,callback);
	}
	this.reader.readAsDataURL(evt.target.files[0]);
}
function wcuf_on_image_to_crop_loaded(e,evt,id,callback)
{
	var result = wcuf_dataURItoBlob(e.target.result);
	var cropper, ratio;
	var cropped_image_width = jQuery("#wcuf_upload_field_"+id).data('cropped-width');
	var cropped_image_height = jQuery("#wcuf_upload_field_"+id).data('cropped-height');
	var controller_width = jQuery("#wcuf_crop_image_box_"+id).width();
	var controller_height = jQuery("#wcuf_crop_image_box_"+id).height();
	var controller_real_width,controller_real_height = 0;
	var ratio = 1;
	//Clear previous event listeners
	wcuf_clear_all_event_listener('btnCrop_'+id);
	wcuf_clear_all_event_listener('btnZoomIn_'+id);
	wcuf_clear_all_event_listener('btnZoomOut_'+id);
	
	//Set size
	if(cropped_image_height > cropped_image_width)
	{
		ratio = cropped_image_width/cropped_image_height;		
		controller_real_height = cropped_image_height * 1.3;
		cropped_image_height = Math.round((cropped_image_height/controller_real_height)*controller_height) + 2;//Math.round(controller_height*2/3);
		cropped_image_width = Math.round(cropped_image_height*ratio) + 2 ; //2: border thick
		ratio = controller_height/controller_real_height; 
		controller_real_width =  jQuery('#wcuf_crop_image_box_'+id).width() / ratio ;
		
	} 
	else if(cropped_image_height < cropped_image_width)
	{
		ratio = cropped_image_height/cropped_image_width;
		controller_real_width = cropped_image_width * 1.3;
		//2: border thick
		cropped_image_width =  Math.round((cropped_image_width/controller_real_width)*controller_width) + 2;//Math.round(controller_width*2/3);
		cropped_image_height =  Math.round(cropped_image_width*ratio) + 2;
		ratio = controller_width/controller_real_width;
		controller_real_height = jQuery('#wcuf_crop_image_box_'+id).height() / ratio;
	} 
	else
	{
		if(controller_height < controller_width)
		{
			controller_real_height = cropped_image_height * 1.3;
			controller_real_width = controller_real_height * (controller_width/controller_height);
			ratio = controller_height/controller_real_height;
		}
		else
		{
			controller_real_width = cropped_image_width * 1.3;
			controller_real_height = controller_real_width * (controller_height/controller_width); 
			ratio = controller_width/controller_real_width;
		}
		
		//2: border thick
		cropped_image_width =  Math.round((cropped_image_width/controller_real_width)*controller_width) + 2;//Math.round(controller_width*2/3);
		cropped_image_height =  Math.round((cropped_image_height/controller_real_height)*controller_height) + 2;//Math.round(controller_height*2/3);
		ratio = controller_height/controller_real_height;
	}
	var options =
    {
        imageBox: '#wcuf_crop_image_box_'+id,
        thumbBox: '#wcuf_crop_thumb_box_'+id,
        spinner: '#wcuf_crop_thumb_spinner_'+id,
        cropped_image_width: cropped_image_width,
        cropped_image_height: cropped_image_height,
        controller_real_width: controller_real_width,
        controller_real_height: controller_real_height,
        cropped_real_image_width: jQuery("#wcuf_upload_field_"+id).data('cropped-width'),
        cropped_real_image_height:  jQuery("#wcuf_upload_field_"+id).data('cropped-height'),
		pixel_ratio: ratio
    }
	
	
	
	jQuery('#wcuf_crop_thumb_box_'+id).css({'width': cropped_image_width+'px',
											'height': cropped_image_height+'px',
											'margin-top': "-"+(cropped_image_height/2)+'px',
											'margin-left': "-"+(cropped_image_width/2)+'px'});
		
	if(result.type == "image/jpeg" || result.type == "image/png")
	{
		jQuery('#wcuf_crop_container_'+id).fadeIn();
		jQuery('#wcuf_crop_container_'+id).removeClass('wcuf_not_to_be_showed');
		options.imgSrc = e.target.result;
		cropper = new cropbox(options);	
	}	
	else
	{
		jQuery('#btnCrop_'+id).on('click', '.wcuf_crop_button', wcuf_prevent_default);
		jQuery('#btnZoomIn_'+id).on('click', '.wcuf_crop_button', wcuf_prevent_default);
		jQuery('#btnZoomOut_'+id).on('click', '.wcuf_crop_button', wcuf_prevent_default);
		jQuery('#wcuf_crop_container_'+id).fadeOut();
		jQuery('#wcuf_crop_container_'+id).addClass('wcuf_not_to_be_showed');
		alert(wcuf_image_file_error);
		return false;
	}
	document.querySelector('#btnCrop_'+id).addEventListener('click', wcuf_crop_and_upload);
	document.querySelector('#btnZoomIn_'+id).addEventListener('click', wcuf_crop_zoom_in);
	document.querySelector('#btnZoomOut_'+id).addEventListener('click',wcuf_crop_zoom_out);

	function wcuf_crop_zoom_in(event)
	{
		event.preventDefault();
		event.stopImmediatePropagation();
		
		cropper.zoomIn();
		return false;
	}
	function wcuf_crop_zoom_out(event)
	{
		event.preventDefault();
			event.stopImmediatePropagation();
			cropper.zoomOut();
			return false;
	}
	function wcuf_crop_and_upload(event)
	{
		event.preventDefault();
		event.stopImmediatePropagation();
		var img = cropper.getDataURL()
		//document.querySelector('.cropped').innerHTML += '<img src="'+img+'">';
		
		var blob = wcuf_dataURItoBlob(img);
		blob.name = evt.target.files[0].name;
		//evt.target.files[0] = blob;
		
		evt.blob = blob;
		callback(evt);
		return false;
	}
}
function wcuf_clear_all_event_listener(element_id)
{
	var old_element = document.getElementById(element_id);
	var new_element = old_element.cloneNode(true);
	old_element.parentNode.replaceChild(new_element, old_element);
}
function wcuf_prevent_default(event)
{
	event.preventDefault();
	event.stopImmediatePropagation();
	return false;
}
function wcuf_dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}