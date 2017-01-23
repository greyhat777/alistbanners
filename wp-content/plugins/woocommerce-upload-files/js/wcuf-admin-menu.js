jQuery(document).ready(function()
{
	jQuery('.wcuf_collapse_options').on('click', wcuf_onCollassableButtonClick);
	jQuery(document).live('click','.wcuf_display_on_product_checkbox', wcuf_onDisplayOnProductCheckboxClick);
	
	 jQuery(".wcuf_sortable").sortable({
        containment: "parent",
		handle: ".wcuf_sort_button",
        placeholder: "ui-state-highlight",
		update: wcuf_uploaded_field_sorted,
		start: wcuf_on_start_field_sorting
    });
	
	wcuf_checkMultipleUpoloadsCheckbox();	
});
function wcuf_on_start_field_sorting( event, ui ) 
{
	/* console.log(jQuery(event.target));
	console.log(ui);
	if(!jQuery(event.currentTarget).hasClass('dashicons-sort'))
	{
		event.preventDefault();
		event.stopImmediatePropagation();
		return false;
	} */
}
function wcuf_uploaded_field_sorted( event, ui ) 
{
	//console.log(event.currentTarget);
	jQuery(".input_box").each(function(index, element)
	{
		var id = jQuery(this).find('.wcup_file_meta_id').val();
		jQuery(this).find('.wcup_file_meta_sort_order').val(index);
		//console.log( id+", new index: "+index );
	});
}
function wcuf_onCollassableButtonClick(event)
{
	//console.log(jQuery(event.currentTarget).data('id'));
	event.preventDefault();
	event.stopImmediatePropagation();
	var id = jQuery(event.currentTarget).data('id');
	jQuery('#wcuf_collapsable_box_'+id).toggleClass('wcuf_box_hidden');
	
	return false;
}
function wcuf_checkMultipleUpoloadsCheckbox()
{
	jQuery('.wcuf_display_on_product_checkbox').each(function(index,value)
	{
		var id = jQuery(this).data('id');
		wcuf_setMultipleUploadCheckboux(id, this);
	});
}
function wcuf_onDisplayOnProductCheckboxClick(event)
{
	var id = jQuery(event.target).data('id');
	//console.log(jQuery(event.target));
	//wcuf_setMultipleUploadCheckboux(id, event.target);
	wcuf_checkMultipleUpoloadsCheckbox();
	
}
function wcuf_setMultipleUploadCheckboux(id, elem)
{
	if(jQuery(elem).prop('checked'))
	{
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).prop('checked',true);
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).attr('disabled',true);
		
		//jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).prop('checked',true);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).removeAttr('disabled');
		jQuery('#wcuf_display_on_product_before_adding_to_cart_container_'+id).fadeIn();
	}
	else
	{
		//jQuery('#wcuf_multiple_uploads_checkbox_'+id).prop('checked',false);
		jQuery('#wcuf_multiple_uploads_checkbox_'+id).removeAttr('disabled');
		
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).attr('disabled',true);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_'+id).attr('checked',false);
		jQuery('#wcuf_display_on_product_before_adding_to_cart_container_'+id).fadeOut();
	}
}