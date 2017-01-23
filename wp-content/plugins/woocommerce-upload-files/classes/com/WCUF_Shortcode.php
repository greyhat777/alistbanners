<?php 
class WCUF_Shortcode
{
	public function __construct()
	{
		add_shortcode( 'wcuf_upload_form', array(&$this, 'wcuf_upload_form' ));
		add_shortcode( 'wcuf_product_page_upload_form', array(&$this, 'wcuf_product_page_upload_form' ));
		add_shortcode( 'wcuf_cart_page_upload_form', array(&$this, 'wcuf_cart_page_upload_form' ));
		add_shortcode( 'wcuf_checkout_page_upload_form', array(&$this, 'wcuf_checkout_page_upload_form' ));
		add_shortcode( 'wcuf_upload_form_last_order', array(&$this, 'wcuf_upload_form_last_order' ));
		
		add_action( 'wp_ajax_reload_shortcode_upload_fields', array( &$this, 'ajax_reload_uploads_shortcode_page' ));
		add_action( 'wp_ajax_nopriv_reload_shortcode_upload_fields', array( &$this, 'ajax_reload_uploads_shortcode_page' ));
	}
	
	public function ajax_reload_uploads_shortcode_page() 
	{
		$this->wcuf_upload_form(null,true);
	}
	public function wcuf_product_page_upload_form()
	{
		global $wcuf_product_page_addon;
		if(@is_product())
		{
			ob_start();
			if($wcuf_product_page_addon->upload_form_is_active)
			{
				echo "<strong>".__('To use this you have to disable the standard upload field managment for Product page. Go to the Plugin options page and disable it.','woocommerce-files-upload')."</strong>";
			}
			else
				$wcuf_product_page_addon->add_uploads_on_product_page(false, 0, true);
			return ob_get_clean();
		}
	}
	public function wcuf_cart_page_upload_form()
	{
		global $wcuf_cart_addon;
		if(@is_cart())
		{
			ob_start();
			if($wcuf_cart_addon->upload_form_is_active)
			{
				echo "<strong>".__('To use this you have to disable the standard upload field managment for Cart page. Go to the Plugin options page and disable it.','woocommerce-files-upload')."</strong>";
			}
			else
				$wcuf_cart_addon->add_uploads_cart_page(null, true);
			return ob_get_clean();
		}
	}
	public function wcuf_checkout_page_upload_form()
	{
		global $wcuf_checkout_addon;
		if(@is_checkout())
		{
			ob_start();
			if($wcuf_checkout_addon->upload_form_is_active)
			{
				echo "<strong>".__('To use this you have to disable the standard upload field managment for Checkout page. Go to the Plugin options page and disable it.','woocommerce-files-upload')."</strong>";
			}
			else
				$wcuf_checkout_addon->add_uploads_checkout_page(null, false, true);
			return ob_get_clean();
		}
	}
	public function wcuf_upload_form($atts, $is_ajax_request = false)
	{
		/* $a = shortcode_atts( array(
        'id' => get_the_ID(),
			), $atts );
			
		if(!isset($a['id']))
			return "";
		 */
		 
		if(@is_product() || @is_cart() || @is_checkout())
			return;
		
		global $wcuf_option_model, $wcuf_wpml_helper, $wcuf_session_model, $wcuf_cart_model, $wcuf_shortcodes,$wcuf_product_model,$wcuf_text_model;
		$button_texts  = $wcuf_text_model->get_button_texts();
		$item_to_show_upload_fields = WC()->cart->cart_contents;
		$file_order_metadata = array();
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		$style_options = $wcuf_option_model->get_style_options();
		$all_options = $wcuf_option_model->get_all_options();
		$crop_area_options = $wcuf_option_model->get_crop_area_options();
		$additional_button_class = $all_options['additional_button_class'];
		$display_summary_box = 'no';
		$current_page = 'shortcode';
		
		if(!$is_ajax_request)
		{
			wp_enqueue_script('wcuf-load-image', wcuf_PLUGIN_PATH. '/js/load-image.all.min.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-ajax-upload-file', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cart-checkout-product-page.js' ,array('jquery'));   
			wp_enqueue_script('wcuf-multiple-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-multiple-file-manager.js' ,array('jquery'));  
			wp_enqueue_script('wcuf-audio-video-file-manager', wcuf_PLUGIN_PATH. '/js/wcuf-audio-video-file-manager.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-image-size-checker', wcuf_PLUGIN_PATH. '/js/wcuf-image-size-checker.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-cropbox', wcuf_PLUGIN_PATH. '/js/vendor/cropbox.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-image-cropper', wcuf_PLUGIN_PATH. '/js/wcuf-frontend-cropper.js' ,array('jquery')); 
			wp_enqueue_script('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/js/vendor/jquery.magnific-popup.js', array('jquery'));
			
			wp_enqueue_style('wcuf-frontend-common', wcuf_PLUGIN_PATH.'/css/wcuf-frontend-common.css');
			wp_enqueue_style('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/css/vendor/magnific-popup.css');
			wp_enqueue_style('wcuf-checkout', wcuf_PLUGIN_PATH. '/css/wcuf-frontend-shortcode.css.php?'.http_build_query($style_options)  );  
			wp_enqueue_style('wcuf-cropbox', wcuf_PLUGIN_PATH.'/css/vendor/cropbox.php?'.http_build_query($crop_area_options) );
			
			include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
			echo '<div id="wcuf_shortcode_ajax_container_loading_container"></div>';
			echo '<div id="wcuf_shortcode_ajax_container">';
			ob_start();
		}
		
		include WCUF_PLUGIN_ABS_PATH.'/template/checkout_cart_product_page_template.php';
		if(!$is_ajax_request)
		{			
			echo '</div>';
			return ob_get_clean();
		}
		else
		{
			wp_die();
		}		
	}
	public function wcuf_upload_form_last_order($atts)
	{
		 
		if(@is_product() || @is_cart() || @is_checkout())
			return;
		
		global $wcuf_order_details_page_addon, $wcuf_customer_model;
		
		$last_order_id = $wcuf_customer_model->get_current_customer_last_order_id();
		ob_start();
		if($last_order_id > 0)
			$wcuf_order_details_page_addon->front_end_order_page_addon(new WC_Order($last_order_id), true);
		else
			_e('You have no orders or you are not logged','woocommerce-files-upload');
		return ob_get_clean();
	}
	
	//Used only in product, cart, shortcode & checkout template
	public function get_file_names_with_additional_info($shortcode, $already_uploaded_message, $file_fields, $item_in_cart_temp, $product, $show_image_preview = false, $order_id = 0, $show_costs = true, $show_file_name = true)
	{
		global $wcuf_cart_model, $wcuf_file_model;
		$files_name = "";
		$ids = null;
		
		if(!isset($item_in_cart_temp['name']) && isset($item_in_cart_temp['original_filename']))
					$item_in_cart_temp['name'] = $item_in_cart_temp['original_filename'];
		if(isset($item_in_cart_temp['name']))
		{
			$files_name = "";
			if(!is_array($item_in_cart_temp['name']))
				$item_in_cart_temp['name'] = array($item_in_cart_temp['name']);
			//else 
			{	
				//$wcuf_file_model->file_zip_name 
				$normal_uploads_counter = 1 ;
				if(isset($product))
					$ids = array('field_id' => $file_fields['id'], 'product_id' => $product['product_id'], 'variation_id' => $product['variation_id'] != "" ? $product['variation_id'] : null);
				$files_name .= '<ol class="wcuf_file_preview_list">';
				$already_processed = array();
				
				//Media file costs managment
				if(is_array($item_in_cart_temp['ID3_info']))
					foreach((array)$item_in_cart_temp['ID3_info'] as $temp_file_name)
					{
						if(isset($temp_file_name['quantity']))
							$temp_file_name['quantity'] = is_array($temp_file_name['quantity']) ? array_sum($temp_file_name['quantity']) : $temp_file_name['quantity'];
						$quantity = isset($temp_file_name['quantity']) ? $temp_file_name['quantity'] : 1;
						$price_and_max_overcharge_seconds = array('price' =>"");
						//Additional costs per secods
						if($show_costs && isset($file_fields['extra_cost_media_enabled']) && $file_fields['extra_cost_media_enabled'])
						{
							$price_and_max_overcharge_seconds = $wcuf_cart_model->get_additional_costs($temp_file_name['playtime_seconds']*$quantity, $file_fields['extra_cost_overcharge_seconds_limit'], $file_fields['extra_cost_per_second_value'], 'fixed' , null,true);
							$price_and_max_overcharge_seconds['price'] = "+".$price_and_max_overcharge_seconds['price'].__(' (seconds cost)', 'woocommerce-files-upload');
						}
						//Additional cost per upload (sum seconds and cost per upload)
						if($show_costs && isset($ids) && isset($file_fields['extra_cost_enabled']) && $file_fields['extra_cost_enabled'] && ($file_fields['extra_cost_overcharge_limit'] != 0 || $file_fields['extra_cost_overcharge_limit'] <= $normal_uploads_counter))
						{
							$price = $wcuf_cart_model->get_additional_costs($quantity /* 1 */, $file_fields['extra_cost_overcharge_limit'], $file_fields['extra_cost_value'], $file_fields['extra_overcharge_type'], $ids, true);
							$price_and_max_overcharge_seconds['price'] .= " +".$price['price'].__(' (cost per upload)', 'woocommerce-files-upload');
						}
						
						$normal_uploads_counter++;
						$file_name = $show_file_name ? $temp_file_name['file_name']." (".$temp_file_name['playtime_string'].") " : "";
						$quantity_string = $quantity > 1 ? " (".__('quantity: ', 'woocommerce-files-upload').$quantity.") ":"";
						$files_name .= '<li class="wcuf_file_preview_list_item"> '.$file_name.$quantity_string.$price_and_max_overcharge_seconds['price'].'</li>';
						$already_processed[$temp_file_name['file_name']] = true;
					}
					
				//Remaining file types managment	
				$normal_uploads_counter = 1 ;
				if(isset($item_in_cart_temp['name']))
					$is_zip = is_array($item_in_cart_temp['name']) && count($item_in_cart_temp['name']) > 1 ? true : false;
				else
					$is_zip = is_array($item_in_cart_temp['original_filename']) && count($item_in_cart_temp['original_filename']) > 1 ? true : false;
				
				$counter = 0;
				foreach((array)$item_in_cart_temp['name'] as $temp_file_name)
				{
					$price = array('price' =>"");
					if(isset($item_in_cart_temp['quantity'][$counter]))
						$item_in_cart_temp['quantity'][$counter] = is_array($item_in_cart_temp['quantity'][$counter]) ? array_sum($item_in_cart_temp['quantity'][$counter]) : $item_in_cart_temp['quantity'][$counter];
					$quantity = isset($item_in_cart_temp['quantity'][$counter]) ? $item_in_cart_temp['quantity'][$counter] : 1;
					$counter++;
					if(!isset($already_processed[$temp_file_name])) //If file has not already been processed
					{
						$price_per_item = "";
						if($show_costs && isset($ids) && isset($file_fields['extra_cost_enabled']) && $file_fields['extra_cost_enabled'] && ($file_fields['extra_cost_overcharge_limit'] != 0 || $file_fields['extra_cost_overcharge_limit'] <= $normal_uploads_counter))
						{
							$price = $wcuf_cart_model->get_additional_costs($quantity /* 1 */, $file_fields['extra_cost_overcharge_limit'], $file_fields['extra_cost_value'], $file_fields['extra_overcharge_type'], $ids, true);
							$normal_uploads_counter++;
						}
						if(!isset($item_in_cart_temp['tmp_name']))
							$item_in_cart_temp['tmp_name'] = $item_in_cart_temp['absolute_path'];
						$image_preview_html = $show_image_preview ? $wcuf_file_model->get_preview_image_html($item_in_cart_temp['tmp_name'],$temp_file_name, $is_zip, $order_id) : "";
						$file_name = $show_file_name ? $temp_file_name : "";
						$quantity_string = $quantity > 1 ? " (".__('quantity: ', 'woocommerce-files-upload').$quantity.") ":"";
						if($file_name != "" || $image_preview_html != "")
							$files_name .= '<li class="wcuf_file_preview_list_item"> '.$image_preview_html." ".$file_name.$quantity_string." ".$price['price'].'</li>';
					}
					
				}
				$files_name .= "</ol>";
			}
		}
		/* $result = $show_image_preview ? str_replace('[file_name_with_image_preview]',  $files_name, $already_uploaded_message) : str_replace('[file_name]',  $files_name, $already_uploaded_message);
		$result =  str_replace('[file_name_with_image_preview_no_cost]',  $files_name, $result); */
		$result =  str_replace($shortcode,  $files_name, $already_uploaded_message);
		
		return $result;
	}
	
	//used only order details page
	function get_file_names($shortcode, $already_uploaded_message, $file_fields, $uploaded_file_data, $show_image_preview = false, $order_id = 0)
	{
		global $wcuf_file_model;
		$files_name = "";
		
		if(isset($uploaded_file_data['original_filename']))
		{
			$files_name = "";
			
			if(!is_array($uploaded_file_data['original_filename']))
				$uploaded_file_data['original_filename'] = array($uploaded_file_data['original_filename']);
			//else 
			{	
				$files_name .= '<ol class="wcuf_file_preview_list">';
				$already_processed = array();
				if(is_array($uploaded_file_data['ID3_info']))
					foreach((array)$uploaded_file_data['ID3_info'] as $temp_file_name)
					{
						if(isset($temp_file_name['quantity']))
							$temp_file_name['quantity'] = is_array($temp_file_name['quantity']) ? array_sum($temp_file_name['quantity']) : $temp_file_name['quantity'];
						$quantity = isset($temp_file_name['quantity']) ? $temp_file_name['quantity'] : 1;
						$quantity_string = $quantity > 1 ? " (".__('quantity: ', 'woocommerce-files-upload').$quantity.") ":"";
						$files_name .= '<li class="wcuf_file_preview_list_item">'.$temp_file_name['file_name']." (".$temp_file_name['playtime_string'].") ".$quantity_string." </li>";
						$already_processed[$temp_file_name['file_name']] = true;
					}
				if(isset($uploaded_file_data['name']))	
					$is_zip = is_array($uploaded_file_data['name']) && count($uploaded_file_data['name']) > 1 ? true : false;
				else
					$is_zip = is_array($uploaded_file_data['original_filename']) && count($uploaded_file_data['original_filename']) > 1 ? true : false;
				
				$counter = 0;
				foreach((array)$uploaded_file_data['original_filename'] as $temp_file_name)
				{
					if(isset($uploaded_file_data['quantity'][$counter]))
						$uploaded_file_data['quantity'][$counter] = is_array($uploaded_file_data['quantity'][$counter]) ? array_sum($uploaded_file_data['quantity'][$counter]) : $uploaded_file_data['quantity'][$counter];
					$quantity = isset($uploaded_file_data['quantity'][$counter]) ? $uploaded_file_data['quantity'][$counter] : 1;
					$quantity_string = $quantity > 1 ? " (".__('quantity: ', 'woocommerce-files-upload').$quantity.") ":"";
					if(!isset($uploaded_file_data['tmp_name']))
						$uploaded_file_data['tmp_name'] = $uploaded_file_data['absolute_path'];
					$image_preview_html = $show_image_preview ? $wcuf_file_model->get_preview_image_html($uploaded_file_data['tmp_name'],$temp_file_name, $is_zip, $order_id) : "";
					if(!isset($already_processed[$temp_file_name]))
						$files_name .= '<li class="wcuf_file_preview_list_item">'.$image_preview_html." ".$temp_file_name.$quantity_string."</li>";
					$counter++;
				}
				$files_name .= "</ol>";
			}
		}
		/* $result = $show_image_preview ? str_replace('[file_name_with_image_preview]',  $files_name, $already_uploaded_message) : str_replace('[file_name]',  $files_name, $already_uploaded_message);
		$result = str_replace('[file_name_no_cost]',  $files_name, $result); */
		$result = str_replace($shortcode,  $files_name, $already_uploaded_message);
		return $result;
	}
	
	public function uploaded_files_num($already_uploaded_message, $file_fields, $item_in_cart_temp)
	{
		$num = isset($item_in_cart_temp['num_uploaded_files']) ? $item_in_cart_temp['num_uploaded_files'] : "";
		return str_replace('[uploaded_files_num]', $num, $already_uploaded_message);
	}
	public function additional_costs($already_uploaded_message, $file_fields_groups, $item_in_cart_temp,$file_fields,$product)
	{
		global $wcuf_cart_model;
		$extra_costs = $wcuf_cart_model->get_sum_of_all_additional_costs($file_fields_groups, $item_in_cart_temp, $file_fields['id'], $product);
		return str_replace('[additional_costs]', $extra_costs, $already_uploaded_message);			
	}
	
}
?>