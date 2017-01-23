<?php 
class WCUF_Text
{
	public function __construct()
	{
	}
	public function get_button_texts()
	{
		$all_data['browse_button'] = get_field('wcuf_browse_button', 'option') != null ? get_field('wcuf_browse_button', 'option') : __("Browse...","woocommerce-files-upload"); 		
		$all_data['add_files_button'] = get_field('wcuf_add_files_button', 'option') != null ? get_field('wcuf_add_files_button', 'option') : __("Add files","woocommerce-files-upload"); 
		$all_data['upload_selected_files_button'] = get_field('wcuf_upload_selected_files_button', 'option') != null ? get_field('wcuf_upload_selected_files_button', 'option') : __("Upload selected files","woocommerce-files-upload"); 
		$all_data['delete_file_button'] = get_field('wcuf_delete_file_button', 'option') != null ? get_field('wcuf_delete_file_button', 'option') : __("Delete file","woocommerce-files-upload"); 
		$all_data['crop_and_upload_button'] = get_field('wcuf_crop_and_upload_button', 'option') != null ? get_field('wcuf_crop_and_upload_button', 'option') : __("Crop & Upload","woocommerce-files-upload"); 
		$all_data['zoom_in_crop_button'] = get_field('wcuf_zoom_in_crop_button', 'option') != null ? get_field('wcuf_zoom_in_crop_button', 'option') : "+"; 
		$all_data['zoom_out_crop_button'] = get_field('wcuf_zoom_out_crop_button', 'option') != null ? get_field('wcuf_zoom_out_crop_button', 'option') : "-"; 
		$all_data['save_uploads_button'] = get_field('wcuf_save_uploads_button', 'option') != null ? get_field('wcuf_save_uploads_button', 'option') : __('Save upload(s)', 'woocommerce-files-upload'); 
		$all_data['select_quantity_label'] = get_field('wcuf_select_quantity_label', 'option') != null ? get_field('wcuf_select_quantity_label', 'option') : __('Select quantity:', 'woocommerce-files-upload'); 
		
		return $all_data;
	}
}
?>