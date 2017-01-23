function wcuf_check_image_file_width_and_height(files, evt, callback, max_image_width, max_image_height,min_image_width, min_image_height)
{
	var current_file_loaded = 0;
	var error = false;
	for(var i = 0; i< files.length; i++)
	{
		var loadingImage = loadImage(
        files[i],
		function (img) 
			{
				if(img.type === "error") 
				{
					if(error)
						return false;
					
					current_file_loaded++;					
					if(max_image_width != 0 || max_image_height != 0)
						error = true;
					if(error == true || current_file_loaded == files.length )
					{
						callback(evt,error,this, {'max_image_width': max_image_width, 'max_image_height':max_image_height, 'min_image_height':min_image_height, 'min_image_width': min_image_width});
					}
				} 
				else 
				{
					if(error)
						return false;
					
					current_file_loaded++;
					if( ((max_image_width != 0 && img.width > max_image_width) || (max_image_height != 0 && img.height > max_image_height)) ||
						//(exact_image_size && ((max_image_width != 0 && img.width != max_image_width) || (max_image_height != 0 && img.height != max_image_height))))
						((min_image_width != 0 && img.width < min_image_width) || (min_image_height != 0 && img.height < min_image_height)) )
						error = true;
					if(error == true || current_file_loaded == files.length)
					{
						callback(evt,error,this, {'max_image_width': max_image_width, 'max_image_height':max_image_height, 'min_image_height':min_image_height, 'min_image_width': min_image_width});
					}
				}
			}
        );
		//loadingImage.filename = files[i].name;
	}
}