var wcuf_multiple_files_queues = new Array();
jQuery(document).on('click', '.wcuf_delete_single_file_in_multiple_list', wcuf_delete_single_file_in_multiple_list);
jQuery(document).on('change', '.wcuf_quantity_per_file_input', wcuf_set_quantity_per_file);

function wcuf_manage_multiple_file_browse(evt)
{
	var id =  jQuery(evt.currentTarget).data('id'); 
	var files = evt.target.files;
	
	if(typeof wcuf_multiple_files_queues[id] === 'undefined')
		wcuf_multiple_files_queues[id] = new Array();
	
	jQuery('button.button#wcuf_upload_multiple_files_button_'+id).fadeIn();
	
	for( var i = 0; i < files.length; i++)
	{
		files[i].quantity = 1;
		wcuf_multiple_files_queues[id].push(files[i]);
		wcuf_append_new_file_ui(id,files[i]);
	}
	
	//console.log(wcuf_multiple_files_queues);
}
//the id is not relative to the file but to the upload field unique id
function wcuf_append_new_file_ui(id, file)
{
	var is_quantity_per_file_box_visible = !wcuf_enable_select_quantity_per_file ? 'style="display:none"' : '';
	var template = '<div class="wcuf_single_file_in_multiple_list" >';
		template +=  '<div class="wcuf_single_file_name_container" >';
		template +=    '<span class="wcuf_single_file_name_in_multiple_list">'+file.name+'</span>';
		template +=    '<i data-id="'+id+'" class="wcuf_delete_single_file_in_multiple_list wcuf_delete_file_icon"></i>';
		template +=   '</div>';
		template +=   '<div class="wcuf_quantity_per_file_container" '+is_quantity_per_file_box_visible+'>';
		template +=     '<span class="wcuf_quantity_per_file_label">'+wcuf_quantity_per_file_label+'</span>';
		template +=     '<input type="number" min="1" data-id="'+id+'" class="wcuf_quantity_per_file_input" value="1"></input>';
		template +=   '</div>';
	template += '</div>';
	
	jQuery('#wcuf_file_name_'+id).append(template);
}
function wcuf_get_field_index(elem)
{
	return elem.parent().parent().index(); 
}
function wcuf_delete_single_file_in_multiple_list(evt)
{
	//Files have not an unique id. To remove the html list index is found and then is used to splice the array
	var id =  jQuery(evt.currentTarget).data('id'); 
	//var index =  jQuery(evt.currentTarget).parent().parent().index(); 
	var index =  wcuf_get_field_index(jQuery(evt.currentTarget)); 
	jQuery('.wcuf_single_file_in_multiple_list:nth-child('+(index+1)+')').remove();
	wcuf_multiple_files_queues[id].splice(index, 1);
	
	if(wcuf_multiple_files_queues[id].length == 0)
		jQuery('button.button#wcuf_upload_multiple_files_button_'+id).fadeOut();
}
function wcuf_set_quantity_per_file(evt)
{
	var index =  wcuf_get_field_index(jQuery(evt.currentTarget)); 
	var value = jQuery(evt.currentTarget).val();
	var id = jQuery(evt.currentTarget).data('id'); 
	
	value = value < 1 ? 1 : value;
	jQuery(evt.currentTarget).val(value);
	wcuf_multiple_files_queues[id][index].quantity = value;
}