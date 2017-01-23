<?php 
class WCUF_CheckoutPage
{
	var $upload_form_is_active = false;
	public function __construct()
	{
		global $wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();
		
		//Upload form
		add_action( $all_options['checkout_page_positioning'], array( &$this, 'add_uploads_checkout_page' ), 10, 1 ); //Checkout page
	
		
		//Ajax upload -> moved to File model
		//add_action( 'wp_ajax_upload_file_during_checkout', array( &$this, 'ajax_upload_file_during_checkout' ));
		
		//After Checkout
		//add_action('woocommerce_checkout_update_order_meta', array( &$this, 'save_uploads_after_checkout' )); //After checkout
		add_action('woocommerce_checkout_order_processed', array( &$this, 'save_uploads_after_checkout' )); //After checkout
		//add_action('save_post', array( &$this, 'save_uploads_after_checkout' )); //After checkout
		add_action('woocommerce_checkout_process', array( &$this, 'check_required_uploads_before_checkout_is_complete' )); 
		
		add_action( 'wp_ajax_reload_upload_fields_on_checkout', array( &$this, 'ajax_add_uploads_checkout_page' ));
		add_action( 'wp_ajax_nopriv_reload_upload_fields_on_checkout', array( &$this, 'ajax_add_uploads_checkout_page' ));
		
		add_action('wp', array( &$this,'add_headers_meta'));
		add_action('wp_head', array( &$this,'add_meta'));
		//add_action('send_headers', array( &$this,'add_headers_meta'));
	}
	function ajax_add_uploads_checkout_page() 
	{
		$this->add_uploads_checkout_page("",true);
	}
	function add_uploads_checkout_page($checkout,$is_ajax_request=false, $used_by_shortcode = false) 
	{
		global $wcuf_option_model, $wcuf_wpml_helper, $wcuf_session_model, $wcuf_cart_model, $wcuf_shortcodes,$wcuf_product_model,$wcuf_text_model;
		$button_texts  = $wcuf_text_model->get_button_texts();
		$item_to_show_upload_fields = WC()->cart->cart_contents;
		$file_order_metadata = array();
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		$style_options = $wcuf_option_model->get_style_options();
		$crop_area_options = $wcuf_option_model->get_crop_area_options();
		$display_summary_box = $wcuf_option_model->get_all_options('display_summary_box_strategy');
		$summary_box_info_to_display = $wcuf_option_model->get_all_options('summary_box_info_to_display');
		$all_options = $wcuf_option_model->get_all_options();
		$additional_button_class = $all_options['additional_button_class'];
		$check_if_standard_managment_is_disabled = $all_options['pages_in_which_standard_upload_fields_managment_is_disabled'];
		$current_page = 'checkout';
		//$wcuf_session_model->remove_item_data();
		
		//wcuf_var_dump($wcuf_session_model->get_item_data());
		if($this->upload_form_is_active || (in_array($current_page,$check_if_standard_managment_is_disabled) && !$is_ajax_request && !$used_by_shortcode) )
			return;
		else
			$this->upload_form_is_active = true;
		
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
				
				wp_enqueue_style('wcuf-magnific-popup', wcuf_PLUGIN_PATH.'/css/vendor/magnific-popup.css');	
				wp_enqueue_style('wcuf-frontend-common', wcuf_PLUGIN_PATH.'/css/wcuf-frontend-common.css');			
				wp_enqueue_style('wcuf-cropbox', wcuf_PLUGIN_PATH.'/css/vendor/cropbox.php?'.http_build_query($crop_area_options) );
				wp_enqueue_style('wcuf-checkout', wcuf_PLUGIN_PATH. '/css/wcuf-frontend-cart-checkout.css.php?'.http_build_query($style_options) );  
				
				include WCUF_PLUGIN_ABS_PATH.'/template/alert_popup.php';
				echo '<div id="wcuf_checkout_ajax_container_loading_container"></div>';
				echo '<div id="wcuf_checkout_ajax_container">';
			}
		include WCUF_PLUGIN_ABS_PATH.'/template/checkout_cart_product_page_template.php';
		if(!$is_ajax_request)		
			echo '</div>';
		else
		{
			wp_die();
		}
	}
	function check_required_uploads_before_checkout_is_complete()
	{
		global $wcuf_product_model,$woocommerce;
		$cart = $woocommerce->cart->cart_contents;
		foreach((array)$cart as $cart_item)
		{
			$product = $cart_item['data'];
			$upload_fields_to_perform_upload = $wcuf_product_model->has_a_required_upload_in_its_single_page($product, true);
			if(!empty($upload_fields_to_perform_upload))
				foreach((array)$upload_fields_to_perform_upload as $item_message)
					wc_add_notice( sprintf(__('Upload <strong>%s</strong> for product <strong>%s</strong> has not been performed.','woocommerce-files-upload'),$item_message['upload_field_name'],$item_message['product_name']) ,'error'); 
					
		}
		//wc_add_notice( __('Stop test','woocommerce-files-upload') ,'error');
	}
	function save_uploads_after_checkout( $order_id)
	{
		global $wcuf_file_model, $wcuf_option_model, $wcuf_session_model;
		/* if(!wp_verify_nonce($_POST['wcuf_attachment_nonce'], 'wcuf_checkout_upload')) 
		  return $order_id; */
		

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return $order_id;
		 
		$temp_uploads = $wcuf_session_model->get_item_data();
		//wcuf_var_dump("checkout");
		//wcuf_var_dump($temp_uploads);
		
		if(!empty($temp_uploads))
		{
			$order = new WC_Order($order_id);
			$file_fields_groups =  $wcuf_option_model->get_fields_meta_data();
			$file_order_metadata = $wcuf_option_model->get_order_uploaded_files_meta_data($order_id);
			$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0];
			$file_order_metadata = $wcuf_file_model->upload_files($order, $file_order_metadata, $file_fields_groups, $temp_uploads);
			//$file_order_metadata = $wcuf_file_model->upload_and_decode_files($order, $file_order_metadata, $file_fields_groups);
		}
		$wcuf_session_model->remove_item_data();
	}
	function add_meta()
	{
		if(@is_checkout())
		{
			
			 echo '<meta http-equiv="Cache-control" content="no-cache">';
			echo '<meta http-equiv="Expires" content="-1">';
		}
	}
	function add_headers_meta()
	{
		if(@is_checkout())
		{
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache');
		}
	}
}
?>