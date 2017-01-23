<?php 
class WCUF_Option
{
	public function __construct()
	{
	}
	public function get_fields_meta_data()
	{
		$fields =  get_option( 'wcuf_files_fields_meta' );
		//WPML
		if (isset($fields)&& $fields != false && class_exists('SitePress'))
		{
			global $sitepress,$wcuf_wpml_helper;
			foreach($fields as $key => $extra_field)
			{
				//if($sitepress->get_default_language() !== ICL_LANGUAGE_CODE)
				{
					$fields[$key]['title'] =      			    apply_filters( 'wpml_translate_single_string', $fields[$key]['title'], 'woocommerce-files-upload',  'wcuf_'.$extra_field['id'].'_title', ICL_LANGUAGE_CODE  );
					$fields[$key]['description'] = 				apply_filters( 'wpml_translate_single_string', $fields[$key]['description'], 'woocommerce-files-upload',  'wcuf_'.$extra_field['id'].'_description', ICL_LANGUAGE_CODE  );
					$fields[$key]['message_already_uploaded'] =  apply_filters( 'wpml_translate_single_string', $fields[$key]['message_already_uploaded'], 'woocommerce-files-upload',  'wcuf_'.$extra_field['id'].'_already_uploaded', ICL_LANGUAGE_CODE  );
				}
				
			}
		}
		return  $fields;
	}
	public function get_order_uploaded_files_meta_data($order_id)
	{
		return get_post_meta($order_id, '_wcst_uploaded_files_meta');
	}
	public function get_option($option_name)
	{
		return get_option($option_name);
	}
	public function save_bulk_options($wcup_file_meta)
	{
		$file_metas = array();
		$last_id = $current_last_id = 0;
		$file_fields_meta = null;
		
		if(isset($wcup_file_meta))
		{
			$counter = 0; 
			$current_last_id = 0; 
			$last_id = $this->get_option('wcuf_last_file_id');
			$last_id = !isset($last_id) ? 0 :$last_id;
			$ids_deleted = array(); //used for WPML
			for($i = 0; $i <= $last_id; $i++)
				$ids_deleted[$i] = true;
			
			$old_file_meta = $this->get_fields_meta_data();
			$fields_to_delete = array();
			if($old_file_meta)
				foreach($old_file_meta as $old_meta)
					$fields_to_delete[$old_meta['id']] = true;
				
		
			foreach($wcup_file_meta as $file_meta)
			{
				if(isset($file_meta['id']))
				{
					if($old_file_meta)
						$fields_to_delete[$file_meta['id']] = false;
					
					$category_ids = array();
					$products_ids = array();
					$enable_for = isset($file_meta['enable_for']) ? $file_meta['enable_for'] : 'always';
					if($enable_for != 'always' && isset($file_meta['categories'] ))
					{
						
						if(isset($file_meta['categories'] ))
							$category_ids =  $file_meta['categories'];
					}
					if($enable_for != 'always' && isset($file_meta['products']))
						$products_ids =  $file_meta['products'];
					
					$file_meta['hide_upload_after_upload'] = !isset($file_meta['hide_upload_after_upload']) ?  false:true;
					$file_meta['user_can_delete'] = !isset($file_meta['user_can_delete']) ?  false:$file_meta['user_can_delete'];
					$file_meta['user_can_download_his_files'] = !isset($file_meta['user_can_download_his_files']) ?  false:$file_meta['user_can_download_his_files'];
					$file_meta['upload_fields_editable_for_completed_orders'] = !isset($file_meta['upload_fields_editable_for_completed_orders']) ?  false:true;
					$file_meta['sort_order'] = !isset($file_meta['sort_order']) ?  0:$file_meta['sort_order'];
					$file_meta['full_name_display'] = !isset($file_meta['full_name_display']) ?  false:$file_meta['full_name_display'];
					$file_meta['notify_admin'] = !isset($file_meta['notify_admin']) ?  false:$file_meta['notify_admin'];
					$file_meta['notify_attach_to_admin_email'] = !isset($file_meta['notify_attach_to_admin_email']) ?  false:$file_meta['notify_attach_to_admin_email'];
					$file_meta['display_on_checkout'] = !isset($file_meta['display_on_checkout']) ?  false:$file_meta['display_on_checkout'];
					$file_meta['display_on_cart'] = !isset($file_meta['display_on_cart']) ?  false:$file_meta['display_on_cart'];
					$file_meta['display_on_product'] = !isset($file_meta['display_on_product']) ?  false:$file_meta['display_on_product'];
					$file_meta['display_on_product_before_adding_to_cart'] = !isset($file_meta['display_on_product_before_adding_to_cart']) ?  false:$file_meta['display_on_product_before_adding_to_cart'];
					$file_meta['display_on_order_detail'] = !isset($file_meta['display_on_order_detail']) ?  false:$file_meta['display_on_order_detail'];
					$file_meta['hide_on_shortcode_form'] = !isset($file_meta['hide_on_shortcode_form']) ?  false:$file_meta['hide_on_shortcode_form'];
					$file_meta['required_on_checkout'] = !isset($file_meta['required_on_checkout']) ?  false:$file_meta['required_on_checkout'];
					//$file_meta['exact_image_size'] = !isset($file_meta['exact_image_size']) ?  false:true;
					$file_meta['disable_stacking'] = !isset($file_meta['disable_stacking']) ?  false:$file_meta['disable_stacking'];
					$file_meta['enable_multiple_uploads_per_field'] = !isset($file_meta['enable_multiple_uploads_per_field']) ?  false:$file_meta['enable_multiple_uploads_per_field'];
					$file_meta['multiple_uploads_max_files'] = !isset($file_meta['multiple_uploads_max_files']) ?  0:$file_meta['multiple_uploads_max_files'];
					$file_meta['multiple_uploads_minimum_required_files'] = !isset($file_meta['multiple_uploads_minimum_required_files']) ?  2:$file_meta['multiple_uploads_minimum_required_files'];
					$file_meta['multiple_uploads_max_files_depends_on_quantity'] = !isset($file_meta['multiple_uploads_max_files_depends_on_quantity']) ?  false:$file_meta['multiple_uploads_max_files_depends_on_quantity'];
					$file_meta['multiple_uploads_min_files_depends_on_quantity'] = !isset($file_meta['multiple_uploads_min_files_depends_on_quantity']) ?  false:$file_meta['multiple_uploads_min_files_depends_on_quantity'];
					$file_meta['disable_stacking_for_variation'] = !isset($file_meta['disable_stacking_for_variation']) ?  false:$file_meta['disable_stacking_for_variation'];
					$file_meta['notifications_recipients'] = !isset($file_meta['notifications_recipients']) ?  "":$file_meta['notifications_recipients'];
					$file_meta['text_field_label'] = !isset($file_meta['text_field_label']) ?  "":$file_meta['text_field_label'];
					$file_meta['text_field_on_order_details_page'] = !isset($file_meta['text_field_on_order_details_page']) ?  false:$file_meta['text_field_on_order_details_page'];
					$file_meta['text_field_max_input_chars'] = !isset($file_meta['text_field_max_input_chars']) ?  0:$file_meta['text_field_max_input_chars'];
					$file_meta['disclaimer_checkbox'] = !isset($file_meta['disclaimer_checkbox']) ?  false:$file_meta['disclaimer_checkbox'];
					$file_meta['disclaimer_text'] = !isset($file_meta['disclaimer_text']) ?  "":$file_meta['disclaimer_text'];
					$file_meta['is_text_field_on_order_details_page_required'] = !isset($file_meta['is_text_field_on_order_details_page_required']) ?  false:$file_meta['is_text_field_on_order_details_page_required'];
					$file_meta['disable_stacking'] = $file_meta['display_on_product'] ? true : $file_meta['disable_stacking'];
					$file_meta['enable_crop_editor'] = isset($file_meta['enable_crop_editor']) ? true : false;
					$file_meta['cropped_image_width'] = isset($file_meta['cropped_image_width']) ? $file_meta['cropped_image_width'] : 200;
					$file_meta['cropped_image_height'] = isset($file_meta['cropped_image_height']) ? $file_meta['cropped_image_height'] : 200;
					//extra costs
					$file_meta['extra_cost_enabled'] = !isset($file_meta['extra_cost_enabled']) ?  false:$file_meta['extra_cost_enabled'];
					$file_meta['extra_overcharge_type'] = !isset($file_meta['extra_overcharge_type']) ?  false:$file_meta['extra_overcharge_type'];
					$file_meta['extra_cost_value'] = !isset($file_meta['extra_cost_value']) ?  1:$file_meta['extra_cost_value'];
					$file_meta['extra_cost_overcharge_limit'] = !isset($file_meta['extra_cost_overcharge_limit']) || $file_meta['extra_cost_overcharge_limit'] == "" ?  0:$file_meta['extra_cost_overcharge_limit'];
					$file_meta['extra_cost_is_taxable'] = !isset($file_meta['extra_cost_is_taxable']) ?  false:$file_meta['extra_cost_is_taxable'];
					//
					$file_meta['extra_cost_media_enabled'] = !isset($file_meta['extra_cost_media_enabled']) ?  false:$file_meta['extra_cost_media_enabled'];
					$file_meta['extra_cost_per_second_value'] = !isset($file_meta['extra_cost_per_second_value']) ?  1:$file_meta['extra_cost_per_second_value'];
					$file_meta['extra_cost_overcharge_seconds_limit'] = !isset($file_meta['extra_cost_overcharge_seconds_limit']) || $file_meta['extra_cost_overcharge_seconds_limit'] == "" ?  0:$file_meta['extra_cost_overcharge_seconds_limit'];
					$file_meta['extra_cost_media_is_taxable'] = !isset($file_meta['extra_cost_media_is_taxable']) ?  false:$file_meta['extra_cost_media_is_taxable'];
					$file_meta['show_cost_per_second'] = !isset($file_meta['show_cost_per_second']) ?  false:$file_meta['show_cost_per_second'];
					
					array_push($file_metas, array( "id"=> $file_meta['id'], //$counter++,
												  "sort_order"=> $file_meta['sort_order'], 
												  "title"=> stripslashes ($file_meta['title']), 
												  "description"=>isset($file_meta['description']) ? stripslashes ($file_meta['description']):"",
												  "hide_upload_after_upload"=> $file_meta['hide_upload_after_upload'],
												  "message_already_uploaded"=>isset($file_meta['message_already_uploaded']) ? stripslashes ($file_meta['message_already_uploaded']):"",
												  "allow"=> 'allow',//$file_meta['allow'],
												  "types"=>isset($file_meta['types']) ? $file_meta['types']:null,
												  "size"=>$file_meta['size'],
												  "width_limit"=>$file_meta['width_limit'],
												  "height_limit"=>$file_meta['height_limit'],
												  "min_width_limit"=>$file_meta['min_width_limit'],
												  "min_height_limit"=>$file_meta['min_height_limit'],
												  "enable_crop_editor"=>$file_meta['enable_crop_editor'],
												  "cropped_image_width"=>$file_meta['cropped_image_width'],
												  "cropped_image_height"=>$file_meta['cropped_image_height'],
												  //"exact_image_size"=>$file_meta['exact_image_size'],
												  "user_can_delete" => $file_meta['user_can_delete'],
												  "user_can_download_his_files" => $file_meta['user_can_download_his_files'],
												  "upload_fields_editable_for_completed_orders" => $file_meta['upload_fields_editable_for_completed_orders'],
												  "full_name_display" => $file_meta['full_name_display'],
												  "notify_admin" => $file_meta['notify_admin'],
												  "notify_attach_to_admin_email" => $file_meta['notify_attach_to_admin_email'],
												  "disable_stacking" => $file_meta['disable_stacking'],
												  "enable_multiple_uploads_per_field" => $file_meta['enable_multiple_uploads_per_field'],
												  "multiple_uploads_max_files" => $file_meta['multiple_uploads_max_files'],
												  "multiple_uploads_minimum_required_files" => $file_meta['multiple_uploads_minimum_required_files'],
												  "multiple_uploads_max_files_depends_on_quantity" => $file_meta['multiple_uploads_max_files_depends_on_quantity'],
												  "multiple_uploads_min_files_depends_on_quantity" => $file_meta['multiple_uploads_min_files_depends_on_quantity'],
												  "disable_stacking_for_variation" => $file_meta['disable_stacking_for_variation'],
												  "display_on_checkout" => $file_meta['display_on_checkout'],
												  "display_on_cart" => $file_meta['display_on_cart'],
												  "display_on_product" => $file_meta['display_on_product'],
												  "display_on_product_before_adding_to_cart" => $file_meta['display_on_product_before_adding_to_cart'],
												  "display_on_order_detail" => $file_meta['display_on_order_detail'],
												  "hide_on_shortcode_form" => $file_meta['hide_on_shortcode_form'],
												  "required_on_checkout" => $file_meta['required_on_checkout'],
												  "notifications_recipients" => $file_meta['notifications_recipients'],
												  "text_field_on_order_details_page" => $file_meta['text_field_on_order_details_page'],
												  "text_field_max_input_chars" => $file_meta['text_field_max_input_chars'],
												  "text_field_label" => $file_meta['text_field_label'],
												  "disclaimer_checkbox" => $file_meta['disclaimer_checkbox'],
												  "disclaimer_text" => stripslashes($file_meta['disclaimer_text']),
												  "is_text_field_on_order_details_page_required" => $file_meta['is_text_field_on_order_details_page_required'],
												  "enable_for" => $enable_for,
												  "category_ids" => $category_ids,
												  "products_ids" => $products_ids,
												  //extra costs
												  "extra_cost_enabled" => $file_meta['extra_cost_enabled'],
												  "extra_overcharge_type" => $file_meta['extra_overcharge_type'],
												  "extra_cost_value" => $file_meta['extra_cost_value'],
												  "extra_cost_overcharge_limit" => $file_meta['extra_cost_overcharge_limit'],
												  "extra_cost_is_taxable" => $file_meta['extra_cost_is_taxable'],
												  //
												  "extra_cost_media_enabled" => $file_meta['extra_cost_media_enabled'],
												  "extra_cost_per_second_value" => $file_meta['extra_cost_per_second_value'],
												  "extra_cost_overcharge_seconds_limit" => $file_meta['extra_cost_overcharge_seconds_limit'],
												  "extra_cost_media_is_taxable" => $file_meta['extra_cost_media_is_taxable'],
												  "show_cost_per_second" => $file_meta['show_cost_per_second']
												  ));
					$current_last_id = isset($file_meta['id']) ? $file_meta['id'] : 0;
				}
			}
			$this->update_option( 'wcuf_files_fields_meta', $file_metas, $fields_to_delete );
			$file_fields_meta = $file_metas;
		}
		else
		{
			$this->delete_option( 'wcuf_files_fields_meta');
			$this->update_option( 'wcuf_last_file_id', 1 );
		}
		if($current_last_id > $last_id)
		  $this->update_option( 'wcuf_last_file_id', $current_last_id );
	  
	  return $file_fields_meta;
	}
	public function update_option($field_name, $field_data, $id_to_delete = null )
	{
		//WPML managment
		global $wcuf_wpml_helper;
		if($field_name == 'wcuf_files_fields_meta')
		{
			$wcuf_wpml_helper->register_strings($field_data);
			if(isset($id_to_delete))	
				$wcuf_wpml_helper->deregister_strings($id_to_delete, true);
		}
				
		return update_option( $field_name, $field_data );
	}
	public function delete_option($field_name)
	{
		global $wcuf_wpml_helper;
		if($field_name == 'wcuf_files_fields_meta')
		{
			$fields =  get_option( 'wcuf_files_fields_meta' );
			$wcuf_wpml_helper->deregister_strings($fields);
		}
		
		return delete_option( $field_name);
	}
	/* public function unregister_wpml_strings($ids)
	{
		$fields =  get_option( 'wcuf_files_fields_meta' );
		$rearranged_array = array();
		foreach($fields as $field)
		{
			$rearranged_array[$field['id']] = $field;
		}
		
		if(class_exists('SitePress') && function_exists ( 'icl_unregister_string' ))
			foreach($ids as $id => $id_to_delete)
			{
				if($id_to_delete && isset($rearranged_array[$id]))
				{
					icl_unregister_string ( 'woocommerce-files-upload', 'wcuf_'.$file_meta['id'].'_title' );
					icl_unregister_string ( 'woocommerce-files-upload', 'wcuf_'.$file_meta['id'].'_description' );
					icl_unregister_string ( 'woocommerce-files-upload', 'wcuf_'.$file_meta['id'].'_already_uploaded' );
				}
			}
	} */
	function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
	public function get_all_options($option_name = null)
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		
		$all_data = array();
		$all_data['bar_color'] = get_field('wcuf_bar_color', 'option'); 
		$all_data['bar_color'] = $all_data['bar_color'] != null ? $all_data['bar_color'] : "#808080"; 
		
		$all_data['disable_view_button'] = get_field('wcuf_disable_view_button', 'option'); 
		$all_data['disable_view_button'] = $all_data['disable_view_button'] ? (int)$all_data['disable_view_button'] : 0; 
		
		$all_data['additional_button_class'] = get_field('wcuf_additional_button_class', 'option'); 
		$all_data['additional_button_class'] = $all_data['additional_button_class'] != null ? $all_data['additional_button_class'] : ''; 
		
		$all_data['browse_button_position'] = get_field('wcuf_browse_button_position', 'option'); 
		$all_data['browse_button_position'] = $all_data['browse_button_position'] != null ? $all_data['browse_button_position'] : 'woocommerce_before_add_to_cart_form'; 
		
		$all_data['cart_page_positioning'] = get_field('wcuf_cart_page_positioning', 'option') ; 
		$all_data['cart_page_positioning'] = $all_data['cart_page_positioning'] != null ? $all_data['cart_page_positioning'] : 'woocommerce_after_cart_table'; 
		
		$all_data['checkout_page_positioning'] = get_field('wcuf_checkout_page_positioning', 'option') ; 
		$all_data['checkout_page_positioning'] = $all_data['checkout_page_positioning'] != null ? $all_data['checkout_page_positioning'] : 'woocommerce_after_checkout_billing_form'; 
		
		$all_data['my_account_page_positioning'] = get_field('wcuf_my_account_page_positioning', 'option') ; 
		$all_data['my_account_page_positioning'] = $all_data['my_account_page_positioning']  != null ? $all_data['my_account_page_positioning']  : 'woocommerce_before_my_account'; 
		
		$all_data['show_warning_alert_on_configurator'] =  get_field('wcuf_show_warning_alert_on_configurator', 'option'); 
		$all_data['show_warning_alert_on_configurator'] = $all_data['show_warning_alert_on_configurator'] != null ? $all_data['show_warning_alert_on_configurator'] : 'yes'; 
		
		$all_data['image_preview_width'] = get_field('wcuf_image_preview_width', 'option'); 
		$all_data['image_preview_width'] = $all_data['image_preview_width'] != null ? $all_data['image_preview_width'] : 50; 
		
		$all_data['image_preview_height'] = get_field('wcuf_image_preview_height', 'option') ; 
		$all_data['image_preview_height'] = $all_data['image_preview_height']  != null ? $all_data['image_preview_height']  : 50; 
		
		$all_data['pages_in_which_standard_upload_fields_managment_is_disabled'] = get_field('wcuf_pages_in_which_standard_upload_fields_managment_is_disabled', 'option'); 
		$all_data['pages_in_which_standard_upload_fields_managment_is_disabled'] = $all_data['pages_in_which_standard_upload_fields_managment_is_disabled'] != null ? $all_data['pages_in_which_standard_upload_fields_managment_is_disabled'] : array(); 
		
		
		
		$all_data['css_notice_text_margin_top'] = get_field('wcuf_css_notice_text_margin_top', 'option'); 
		$all_data['css_notice_text_margin_top'] = $all_data['css_notice_text_margin_top'] != null ? $all_data['css_notice_text_margin_top'] : "5"; 
		
		$all_data['css_notice_text_margin_bottom'] = get_field('wcuf_css_notice_text_margin_bottom', 'option') ; 
		$all_data['css_notice_text_margin_bottom'] = $all_data['css_notice_text_margin_bottom'] != null ? $all_data['css_notice_text_margin_bottom'] : "0"; 
		
		$all_data['css_feedback_text_area_height'] = get_field('wcuf_css_feedback_text_area_height', 'option'); 
		$all_data['css_feedback_text_area_height'] = $all_data['css_feedback_text_area_height'] != null ? $all_data['css_feedback_text_area_height'] : "100"; 
		
		$all_data['css_feedback_text_area_margin_top'] = get_field('wcuf_css_feedback_text_area_margin_top', 'option'); 
		$all_data['css_feedback_text_area_margin_top'] = $all_data['css_feedback_text_area_margin_top'] != null ? $all_data['css_feedback_text_area_margin_top'] : "0"; 
		
		$all_data['css_feedback_text_area_margin_bottom'] = get_field('wcuf_css_feedback_text_area_margin_bottom', 'option'); 
		$all_data['css_feedback_text_area_margin_bottom'] = $all_data['css_feedback_text_area_margin_bottom'] != null ? $all_data['css_feedback_text_area_margin_bottom'] : "5"; 
		
		$all_data['css_upload_field_title_color'] = get_field('wcuf_css_upload_field_title_color', 'option') ; 
		$all_data['css_upload_field_title_color'] = $all_data['css_upload_field_title_color'] != null ? $all_data['css_upload_field_title_color'] : "inherit"; 
		
		$all_data['css_upload_field_title_font_size'] = get_field('wcuf_css_upload_field_title_font_size', 'option') ; 
		$all_data['css_upload_field_title_font_size'] =  $all_data['css_upload_field_title_font_size'] != null ? $all_data['css_upload_field_title_font_size'] : "inherit"; 
		
		$all_data['css_distance_between_upload_buttons'] = get_field('wcuf_css_distance_between_upload_buttons', 'option') ;
		$all_data['css_distance_between_upload_buttons'] = $all_data['css_distance_between_upload_buttons'] != null ? $all_data['css_distance_between_upload_buttons'] : '2';
		
		$all_data['crop_area_width'] = get_field('wcuf_crop_area_width', 'option'); 		
		$all_data['crop_area_width'] = $all_data['crop_area_width'] != null ? $all_data['crop_area_width'] : 300; 		
		
		$all_data['crop_area_height'] = get_field('wcuf_crop_area_height', 'option');
		$all_data['crop_area_height'] = $all_data['crop_area_height'] != null ? $all_data['crop_area_height'] : 300;
		/* Format: display_summary_box_strategy
		array(3) {
			  [0]=>
			  string(4) "cart"
			  [1]=>
			  string(8) "checkout"
			  [2]=>
			  string(13) "order_details"
			}
		*/
		$all_data['display_summary_box_strategy'] = get_field('wcuf_display_summary_box_strategy', 'option'); 
		$all_data['display_summary_box_strategy'] = $all_data['display_summary_box_strategy'] != null && !empty($all_data['display_summary_box_strategy']) ? $all_data['display_summary_box_strategy'] : "no"; 
		
		$all_data['display_last_order_upload_fields_in_my_account_page'] = get_field('wcuf_display_last_order_upload_fields_in_my_account_page', 'option') ; 
		$all_data['display_last_order_upload_fields_in_my_account_page'] = $all_data['display_last_order_upload_fields_in_my_account_page'] != null && !empty($all_data['display_last_order_upload_fields_in_my_account_page']) ? $all_data['display_last_order_upload_fields_in_my_account_page'] : "no"; 
		
		//file_name_and_preview_image - file_name - preview_image
		$all_data['summary_box_info_to_display'] = get_field('wcuf_summary_box_info_to_display', 'option'); 
		$all_data['summary_box_info_to_display'] = $all_data['summary_box_info_to_display'] != null && !empty($all_data['summary_box_info_to_display']) ? $all_data['summary_box_info_to_display'] : "file_name_and_preview_image"; 
		
		$all_data['remove_random_number_prefix'] = get_field('wcuf_remove_random_number_prefix', 'option') ; 
		$all_data['remove_random_number_prefix'] = $all_data['remove_random_number_prefix'] != null && !empty($all_data['remove_random_number_prefix']) ? $all_data['remove_random_number_prefix'] : "no"; 
		
		$all_data['force_require_check_befor_adding_item_to_car'] = get_field('wcuf_force_require_check_befor_adding_item_to_car', 'option') ; 
		$all_data['force_require_check_befor_adding_item_to_car'] = $all_data['force_require_check_befor_adding_item_to_car'] ? $all_data['force_require_check_befor_adding_item_to_car'] : "no"; 
		
		$all_data['allow_user_to_leave_page_in_case_of_required_field'] = get_field('wcuf_allow_user_to_leave_page_in_case_of_required_field', 'option'); 
		$all_data['allow_user_to_leave_page_in_case_of_required_field'] = $all_data['allow_user_to_leave_page_in_case_of_required_field'] ? $all_data['allow_user_to_leave_page_in_case_of_required_field'] : "no"; 
		
		$all_data['enable_quantity_selection'] = get_field('wcuf_enable_quantity_selection', 'option'); 
		$all_data['enable_quantity_selection'] = get_field('wcuf_enable_quantity_selection', 'option') && $all_data['enable_quantity_selection'] == 'yes' ? true : false; 
		
		
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		
		if(isset($option_name) && isset($all_data[$option_name]))
			return $all_data[$option_name];
		
		return $all_data;
	}
	public function remove_file_name_prefix()
	{
		return get_field('wcuf_remove_random_number_prefix', 'option') != null && !empty(get_field('wcuf_remove_random_number_prefix', 'option')) ? get_field('wcuf_remove_random_number_prefix', 'option') : "no";
	}
	public function get_style_options()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		
		/* $all_data['css_notice_text_margin_top'] = get_field('wcuf_css_notice_text_margin_top', 'option') != null ? get_field('wcuf_css_notice_text_margin_top', 'option') : "5"; 
		$all_data['css_notice_text_margin_bottom'] = get_field('wcuf_css_notice_text_margin_bottom', 'option') != null ? get_field('wcuf_css_notice_text_margin_bottom', 'option') : "0"; 
		$all_data['css_feedback_text_area_height'] = get_field('wcuf_css_feedback_text_area_height', 'option') != null ? get_field('wcuf_css_feedback_text_area_height', 'option') : "100"; 
		$all_data['css_feedback_text_area_margin_top'] = get_field('wcuf_css_feedback_text_area_margin_top', 'option') != null ? get_field('wcuf_css_feedback_text_area_margin_top', 'option') : "0"; 
		$all_data['css_feedback_text_area_margin_bottom'] = get_field('wcuf_css_feedback_text_area_margin_bottom', 'option') != null ? get_field('wcuf_css_feedback_text_area_margin_bottom', 'option') : "5"; 
		$all_data['css_upload_field_title_color'] = get_field('wcuf_css_upload_field_title_color', 'option') != null ? get_field('wcuf_css_upload_field_title_color', 'option') : "inherit"; 
		$all_data['css_upload_field_title_font_size'] = get_field('wcuf_css_upload_field_title_font_size', 'option') != null ? get_field('wcuf_css_upload_field_title_font_size', 'option') : "inherit"; 
		$all_data['css_distance_between_upload_buttons'] = get_field('wcuf_css_distance_between_upload_buttons', 'option') != null ? get_field('wcuf_css_distance_between_upload_buttons', 'option') : '2';
		
		$all_data['crop_area_width'] = get_field('wcuf_crop_area_width', 'option') != null ? get_field('wcuf_crop_area_width', 'option') : 300; 		
		$all_data['crop_area_height'] = get_field('wcuf_crop_area_height', 'option') != null ? get_field('wcuf_crop_area_height', 'option') : 300;  */
		$all_data['css_notice_text_margin_top'] = get_field('wcuf_css_notice_text_margin_top', 'option'); 
		$all_data['css_notice_text_margin_top'] = $all_data['css_notice_text_margin_top'] != null ? $all_data['css_notice_text_margin_top'] : "5"; 
		
		$all_data['css_notice_text_margin_bottom'] = get_field('wcuf_css_notice_text_margin_bottom', 'option') ; 
		$all_data['css_notice_text_margin_bottom'] = $all_data['css_notice_text_margin_bottom'] != null ? $all_data['css_notice_text_margin_bottom'] : "0"; 
		
		$all_data['css_feedback_text_area_height'] = get_field('wcuf_css_feedback_text_area_height', 'option'); 
		$all_data['css_feedback_text_area_height'] = $all_data['css_feedback_text_area_height'] != null ? $all_data['css_feedback_text_area_height'] : "100"; 
		
		$all_data['css_feedback_text_area_margin_top'] = get_field('wcuf_css_feedback_text_area_margin_top', 'option'); 
		$all_data['css_feedback_text_area_margin_top'] = $all_data['css_feedback_text_area_margin_top'] != null ? $all_data['css_feedback_text_area_margin_top'] : "0"; 
		
		$all_data['css_feedback_text_area_margin_bottom'] = get_field('wcuf_css_feedback_text_area_margin_bottom', 'option'); 
		$all_data['css_feedback_text_area_margin_bottom'] = $all_data['css_feedback_text_area_margin_bottom'] != null ? $all_data['css_feedback_text_area_margin_bottom'] : "5"; 
		
		$all_data['css_upload_field_title_color'] = get_field('wcuf_css_upload_field_title_color', 'option') ; 
		$all_data['css_upload_field_title_color'] = $all_data['css_upload_field_title_color'] != null ? $all_data['css_upload_field_title_color'] : "inherit"; 
		
		$all_data['css_upload_field_title_font_size'] = get_field('wcuf_css_upload_field_title_font_size', 'option') ; 
		$all_data['css_upload_field_title_font_size'] =  $all_data['css_upload_field_title_font_size'] != null ? $all_data['css_upload_field_title_font_size'] : "inherit"; 
		
		$all_data['css_distance_between_upload_buttons'] = get_field('wcuf_css_distance_between_upload_buttons', 'option') ;
		$all_data['css_distance_between_upload_buttons'] = $all_data['css_distance_between_upload_buttons'] != null ? $all_data['css_distance_between_upload_buttons'] : '2';
		
		$all_data['crop_area_width'] = get_field('wcuf_crop_area_width', 'option'); 		
		$all_data['crop_area_width'] = $all_data['crop_area_width'] != null ? $all_data['crop_area_width'] : 300; 		
		
		$all_data['crop_area_height'] = get_field('wcuf_crop_area_height', 'option');
		$all_data['crop_area_height'] = $all_data['crop_area_height'] != null ? $all_data['crop_area_height'] : 300;
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return $all_data;
	}
	public function get_crop_area_options()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		/* $all_data['crop_area_width'] = get_field('wcuf_crop_area_width', 'option') != null ? get_field('wcuf_crop_area_width', 'option') : 300; 		
		$all_data['crop_area_height'] = get_field('wcuf_crop_area_height', 'option') != null ? get_field('wcuf_crop_area_height', 'option') : 300;  */
		$all_data['crop_area_width'] = get_field('wcuf_crop_area_width', 'option'); 		
		$all_data['crop_area_width'] = $all_data['crop_area_width'] != null ? $all_data['crop_area_width'] : 300; 		
		
		$all_data['crop_area_height'] = get_field('wcuf_crop_area_height', 'option');
		$all_data['crop_area_height'] = $all_data['crop_area_height'] != null ? $all_data['crop_area_height'] : 300;
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return $all_data;
	}
}
?>