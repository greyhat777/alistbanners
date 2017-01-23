<?php
class WCUF_AdminMenu
{
	public static  $WCUF_current_lang;
	public function __construct()
	{
		//add_action( 'wp_enqueue_scripts', array(&$this, 'force_dequeue_scripts'),100 );
		//add_action('admin_enqueue_scripts', array(&$this, 'force_dequeue_scripts') );
		//add_action('wp_head',  array(&$this, 'enqueue_scripts'));
	}
	public static function force_dequeue_scripts($enqueue_styles)
	{
		if ( class_exists( 'woocommerce' ) && isset($_GET['page']) && $_GET['page'] == 'woocommerce-files-upload') 
		{
			global $wp_scripts;
			$wp_scripts->queue = array();
			WCUF_AdminMenu::enqueue_scripts();

		} 
	}
	public static function enqueue_scripts()
	{
		if ( class_exists( 'woocommerce' ) && isset($_GET['page']) && $_GET['page'] == 'woocommerce-files-upload') 
		{
			wp_dequeue_script( 'select2');
			wp_deregister_script('select2');
			
			 global /*$wp_scripts*/ $wcuf_option_model;
			//$wp_scripts->queue = array();	 
		
			$general_options = $wcuf_option_model->get_all_options(); 
			wp_enqueue_style( 'select2.css', wcuf_PLUGIN_PATH.'/css/select2.min.css' ); 
			wp_enqueue_style( 'wcuf-common', wcuf_PLUGIN_PATH.'/css/wcuf-common.css' ); 
			wp_enqueue_style( 'wcuf-backend.css', wcuf_PLUGIN_PATH.'/css/wcuf-backend.css' );
			wp_enqueue_style( 'wp-color-picker' );
			
			//wcuf_var_dump($wp_scripts);
			wp_enqueue_script( 'jquery' );		
			//wp_enqueue_script( 'select2-js', wcuf_PLUGIN_PATH.'/js/select2.min.js', array('jquery') );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_script( 'wcuf-autocomplete-product-and-categories', wcuf_PLUGIN_PATH.'/js/wcuf-admin-product_and_categories-autocomplete.js', array('jquery'),false,false );			
			wp_enqueue_script( 'wcuf-admin-menu', wcuf_PLUGIN_PATH.'/js/wcuf-admin-menu.js', array('jquery'),false,false );
			if($general_options['show_warning_alert_on_configurator'] == 'yes')
					wp_enqueue_script( 'wcuf-admin-menu-debug', wcuf_PLUGIN_PATH.'/js/wcuf-debug-alert.js', array('jquery'),false,false );
				
		}
	}
	public static function WCUF_switch_to_default_lang()
	{
		if(defined("ICL_LANGUAGE_CODE") && ICL_LANGUAGE_CODE != null)
		{
			global $sitepress;
			WCUF_AdminMenu::$WCUF_current_lang = ICL_LANGUAGE_CODE;
			$sitepress->switch_lang($sitepress->get_default_language());
		}
	}
	public static function WCUF_restore_current_lang()
	{
		if(defined("ICL_LANGUAGE_CODE") && ICL_LANGUAGE_CODE != null)
		{
			global $sitepress;
			$sitepress->switch_lang(WCUF_AdminMenu::$WCUF_current_lang);
		}
	}
	
	private function update_settings()
	{
		global $wcuf_option_model;
		if(isset($_REQUEST['wcup_file_meta']))
			return $wcuf_option_model->save_bulk_options($_REQUEST['wcup_file_meta']);
		
		return null;
	}
	/* private function reset_data()
	{
		delete_option( 'wcuf_last_file_id');
		delete_option( 'wcuf_files_fields_meta');
	}  */
	
	public function render_page()
	{
	/* 	global $sitepress_settings;
		wcuf_var_dump( $sitepress_settings['admin_default_language']); */
		
		global $wcuf_option_model, $wcuf_product_model;
		if (isset($_REQUEST['wcup_file_meta']) || isset($_REQUEST['wcuf_is_submit']))//$_SERVER['REQUEST_METHOD'] == 'POST')
			$file_fields_meta = $this->update_settings();
		else
			$file_fields_meta = $wcuf_option_model->get_fields_meta_data();
		//wcuf_var_dump($file_fields_meta );
		$last_id = $wcuf_option_model->get_option( 'wcuf_last_file_id');
		
		//text
		$already_uploaded_default_message = "[file_name_with_image_preview]";
		$upload_per_product_instruction = __("If you have choosen to display the upload field only on Cart and/or Checkout and/or Order details pages (thus excluding Products pages. If it is has been selected this option is required and cannot be disabled) by disabling the following option only one Upload Field will be displayed if at least one of the item in cart/order matches the filtering criteria (otherwise by default it is displayed one Upload Field for each matching product)",'woocommerce-files-upload');
		$upload_product_page_before_instruction = __('<strong>NOTE:</strong> By default, to offer all the feaures, the upload field is showed only <strong>AFTER</strong> the product has been added to the cart. Enabling this option the following feature will not work: <ol><li><strong>Max number of uploadable files depens on product quantity</strong></li><li><strong>Enable one upload field for every single product variation</strong></li><li><strong>Filtering by specific variation:</strong> if in the Filtering section has been selected one or more variationd they will be ignored and will be added <strong>ONLY ONE</strong> upload field for the main product.</li>', 'woocommerce-files-upload');
		$product_filtering_instruction = __('Select Product(s) (search typing product name, id or sku code)', 'woocommerce-files-upload');
		
		if(!$last_id)
			$last_id = 0;
		else
			$last_id ++;
		$counter  = 0;
		?>
		<script>
			jQuery.fn.select2=null;
		</script>
		<script type='text/javascript' src='<?php echo wcuf_PLUGIN_PATH.'/js/select2.min.js'; ?>'></script>
		<div id="icon-themes" class="icon32"><br></div> 
		<h2><?php _e('Uploads options', 'woocommerce-files-upload');?></h2>
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') 
				echo '<div id="message" class="updated"><p>' . __('Saved successfully.', 'woocommerce-files-upload') . '</p></div>'; ?>
		<div class="wrap">
		<!-- <div id="wcuf_error_box">
		
		</div>-->
			<form action="" method="post"  style="padding-left:20px">
			<input type="hidden" name="wcuf_is_submit" value="true"></input>
			<?php //settings_fields('wcuf_files_fields_meta_groups'); ?> 
				<button class="add_field_button button-primary"><?php _e('Add one more Upload Field', 'woocommerce-files-upload');?></button>
				<ul class="input_fields_wrap wcuf_sortable">
				<?php if($file_fields_meta):
						foreach($file_fields_meta as $file_meta): 
						
						$file_meta['enable_for'] = !isset($file_meta['enable_for']) ?  'always':$file_meta['enable_for'];
						$file_meta['text_field_on_order_details_page'] = !isset($file_meta['text_field_on_order_details_page']) ?  false:$file_meta['text_field_on_order_details_page'];
						$file_meta['is_text_field_on_order_details_page_required'] = !isset($file_meta['is_text_field_on_order_details_page_required']) ?  false:$file_meta['is_text_field_on_order_details_page_required'];
						$file_meta['sort_order'] = !isset($file_meta['sort_order']) ?  0:$file_meta['sort_order'];
						$file_meta['notify_admin'] = !isset($file_meta['notify_admin']) ?  false:$file_meta['notify_admin'];
						$file_meta['notify_attach_to_admin_email'] = !isset($file_meta['notify_attach_to_admin_email']) ?  false:$file_meta['notify_attach_to_admin_email'];
						$file_meta['message_already_uploaded'] = !isset($file_meta['message_already_uploaded']) ?  $already_uploaded_default_message:$file_meta['message_already_uploaded'];
						$file_meta['disclaimer_checkbox'] = !isset($file_meta['disclaimer_checkbox']) ?  false:$file_meta['disclaimer_checkbox'];
						$file_meta['disclaimer_text'] = !isset($file_meta['disclaimer_text']) ?  "":$file_meta['disclaimer_text'];
						$selected_categories = !isset($file_meta['category_ids']) ? array():$file_meta['category_ids'];
						$selected_products = !isset($file_meta['products_ids']) ? array():$file_meta['products_ids'];
						$notifications_recipients = !isset($file_meta['notifications_recipients']) ? '':$file_meta['notifications_recipients'];
						$file_meta['width_limit'] = isset($file_meta['width_limit']) ? $file_meta['width_limit'] : 0;
						$file_meta['height_limit'] = isset($file_meta['height_limit']) ? $file_meta['height_limit'] : 0;
						$file_meta['min_width_limit'] = isset($file_meta['min_width_limit']) ? $file_meta['min_width_limit'] : 0;
						$file_meta['min_height_limit'] = isset($file_meta['min_height_limit']) ? $file_meta['min_height_limit'] : 0;
						$file_meta['upload_fields_editable_for_completed_orders'] = isset($file_meta['upload_fields_editable_for_completed_orders']) ? $file_meta['upload_fields_editable_for_completed_orders'] : false;
						$file_meta['enable_crop_editor'] = isset($file_meta['enable_crop_editor']) ? $file_meta['enable_crop_editor'] : false;
						$file_meta['cropped_image_width'] = isset($file_meta['cropped_image_width']) ? $file_meta['cropped_image_width'] : 200;
						$file_meta['cropped_image_height'] = isset($file_meta['cropped_image_height']) ? $file_meta['cropped_image_height'] : 200;
						
						?>
						<li class="input_box">
							<label class="wcuf_sort_button"><span class="dashicons dashicons-sort"></span><?php _e('Drag to sort', 'woocommerce-files-upload');?></label>
							<label class="wcuf_required"><?php _e('Title (NO Html code)', 'woocommerce-files-upload');?></label>
							<input type ="hidden" class="wcup_file_meta_id" name= "wcup_file_meta[<?php echo $counter ?>][id]" value="<?php echo $file_meta['id'] ?>" ></input>
							<input type ="hidden" class="wcup_file_meta_sort_order" name= "wcup_file_meta[<?php echo $counter ?>][sort_order]" value="<?php echo $file_meta['sort_order'] ?>" ></input>
							<input type="text" value="<?php echo $file_meta['title']; ?>" name="wcup_file_meta[<?php echo $counter ?>][title]"  placeholder=" "  size="80" required></textarea >
							
							<button data-id="<?php echo $counter ?>" class="button wcuf_collapse_options"><?php _e('Collapse/Expand Options Box', 'woocommerce-files-upload');?></button>
							<div id="wcuf_collapsable_box_<?php echo $counter ?>" class="wcuf_collapsable_box wcuf_box_hidden">
								<label><?php _e('Description (HTML code permitted)', 'woocommerce-files-upload');?></label>
								<textarea  class="upload_description"  rows="5" cols="80" name="wcup_file_meta[<?php echo $counter ?>][description]" placeholder="<?php _e('Description (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php echo $file_meta['description']; ?></textarea>
								
								<label class="option_label"><?php _e('Hide description after the upload has been completed?', 'woocommerce-files-upload');?></label>
								<input class="variant_option_input" type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][hide_upload_after_upload]" value="true" <?php if(isset($file_meta['hide_upload_after_upload']) && $file_meta['hide_upload_after_upload']) echo 'checked="checked"'?> ></input>
								
								<label class="wcuf_already_uploaded_message_label"><?php _e('Text to show after the upload has been completed (HTML code permitted)', 'woocommerce-files-upload'); ?></label>
								<p><?php _e('Permitted shortcodes:<br/><strong>[file_name]</strong> to display the file(s) name list. For every file is also reported the additional cost (only if any of the extra costs option have been enabled)<br/><strong>[file_name_no_cost]</strong> like previous but without costs display<br/><strong>[file_name_with_image_preview]</strong> like [file_name] shotcode with image preview (if the file(s) is a jpg/png)<br/><strong>[file_name_with_image_preview_no_cost]</strong> like previous shotcode without costs display<br/><strong>[image_preview_list]</strong> to display image preview (if the file(s) is a jpg/png)<br/><strong>[uploaded_files_num]</strong> to display total number of the uploaded files (useful if the "Multiple files upload" option has been enabled)<br/><strong>[additional_costs]</strong> (tax excluded) to display the sum of the additional costs of all the uploaded files', 'woocommerce-files-upload');?></p>
								<textarea  class="upload_description"  rows="5" cols="80" name="wcup_file_meta[<?php echo $counter ?>][message_already_uploaded]" placeholder="<?php _e('This message is displayed after file description only if a file have been uploaded (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php echo $file_meta['message_already_uploaded']; ?></textarea>
								
								<label class="option_label"><?php _e('In case of Variable Product, display full product name (product name and variant details)? If unchecked will be displayed only product name.', 'woocommerce-files-upload');?></label>
								<input class="variant_option_input" type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][full_name_display]" value="true" <?php if(!isset($file_meta['full_name_display']) || $file_meta['full_name_display']) echo 'checked="checked"'?> ></input>
								
								<label class="option_label"><?php _e('Allowed file type(s)', 'woocommerce-files-upload');?></label>
								<input type="text" name="wcup_file_meta[<?php echo $counter ?>][types]" placeholder="<?php _e('File type(s), ex: .jpg,.bmp,.png leave empty to accept all file types. ', 'woocommerce-files-upload'); ?>" value="<?php echo $file_meta['types']; ?>" size="80"></input>
								
								<label class="wcuf_required"><?php _e('File size (MB)', 'woocommerce-files-upload');?></label>
								<input type="number" min="1" name="wcup_file_meta[<?php echo $counter ?>][size]" value="<?php echo $file_meta['size']; ?>" required></input>
								
								<label><?php _e('Can user delete file(s)?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][user_can_delete]" value="true" <?php if($file_meta['user_can_delete']) echo 'checked="checked"'?> ></input>
								<label><?php _e('Can user download uploaded file?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][user_can_download_his_files]" value="true" <?php if($file_meta['user_can_download_his_files']) echo 'checked="checked"'?> ></input>
								
								<label><?php _e('Upload fields are visible also for Orders marked as <i>completed</i> (Valid only for Order details page)?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][upload_fields_editable_for_completed_orders]" value="true" <?php if($file_meta['upload_fields_editable_for_completed_orders']) echo 'checked="checked"'?> ></input>
								
								<h3><?php _e('Visibility', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Display field on Checkout page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][display_on_checkout]" value="true" <?php if(isset($file_meta['display_on_checkout']) && $file_meta['display_on_checkout']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Display field on Cart page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" data-id="<?php echo $counter ?>" class="wcuf_display_on_cart_checkbox" name="wcup_file_meta[<?php echo $counter ?>][display_on_cart]" value="true" <?php if(isset($file_meta['display_on_cart']) && $file_meta['display_on_cart']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Display field on Product page?', 'woocommerce-files-upload');?></label>
								<!-- <p><?php  _e('This will enable the "Upload per product" option.', 'woocommerce-files-upload') ?></p> -->
								<input type="checkbox" data-id="<?php echo $counter ?>" class="wcuf_display_on_product_checkbox" name="wcup_file_meta[<?php echo $counter ?>][display_on_product]" value="true" <?php if(isset($file_meta['display_on_product']) && $file_meta['display_on_product']) echo 'checked="checked"'?> ></input>
								 
								<div class="wcuf_product_page_visibility_sub_option" id="wcuf_display_on_product_before_adding_to_cart_container_<?php echo $counter ?>">
									<label style="margin-top:20px;"><?php _e('on Product page, display the field BEFORE adding an item to the cart?', 'woocommerce-files-upload');?></label>
									<input type="checkbox" data-id="<?php echo $counter ?>" id="wcuf_display_on_product_before_adding_to_cart_<?php echo $counter ?>" class="" name="wcup_file_meta[<?php echo $counter ?>][display_on_product_before_adding_to_cart]" value="true" <?php if(isset($file_meta['display_on_product_before_adding_to_cart']) && $file_meta['display_on_product_before_adding_to_cart']) echo 'checked="checked"'?> ></input>
									<p><?php  echo $upload_product_page_before_instruction; ?></p>
								</div>
								
								<label style="margin-top:20px;"><?php _e('Display field on Order detail page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][display_on_order_detail]" value="true" <?php if(!isset($file_meta['display_on_order_detail']) || $file_meta['display_on_order_detail']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Hide on shortcode upload form?', 'woocommerce-files-upload');?></label>
								<p><?php _e('By default using the <strong>[wcuf_upload_form]</strong> shortcode all the upload fields that  match products in the cart are visible. Enabling this option this field will be hidden.', 'woocommerce-files-upload');?></p>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][hide_on_shortcode_form]" value="true" <?php if(isset($file_meta['hide_on_shortcode_form']) && $file_meta['hide_on_shortcode_form']) echo 'checked="checked"'?> ></input>
								
								<h3><?php _e('Requirement', 'woocommerce-files-upload');?></h3>
								<p><?php _e('The plugin will <strong>try to deny the page leaving</strong> until all the required files have not been uploaded <strong>propting a warning dialog</strong> (some browsers, for security reasons, may not permit this denial).','woocommerce-files-upload'); ?><br/>
								<?php _e('In case you want to <strong>give the possibility to leave the page</strong>, go to the <strong>Options</strong> menu and under <strong>Allow user to leave page in case of required field</strong> section select <strong>Yes</strong> option.','woocommerce-files-upload'); ?></p>
								<p><strong><?php _e('NOTE','woocommerce-files-upload');?>:</strong> <?php _e('if enabling this option your are experiencing multiple "Add to cart" buttons issues on your shop page, go to the Option menu and set False for the Disable View Button option', 'woocommerce-files-upload'); ?></p>
								<label style="margin-top:20px;"><?php _e('Upload is required', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][required_on_checkout]" value="true" <?php if(isset($file_meta['required_on_checkout']) && $file_meta['required_on_checkout']) echo 'checked="checked"'?> ></input>
								
								<h3><?php _e('Image media file (only for jpg/png media files)', 'woocommerce-files-upload');?></h3>
								<label><?php _e('Enable crop editor (this option will be ignored in case the multiple file option has been enabled)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" min="0" name="wcup_file_meta[<?php echo $counter ?>][enable_crop_editor]" value="true" <?php if($file_meta['enable_crop_editor']) echo 'checked="checked"'; ?>></input>
								
								<label class="wcuf_required"><?php _e('Cropped image width', 'woocommerce-files-upload');?></label>
								<input type="number" min="1" step="1" name="wcup_file_meta[<?php echo $counter ?>][cropped_image_width]" value="<?php echo $file_meta['cropped_image_width']; ?>" required></input>
								<label class="wcuf_required"><?php _e('Cropped image height', 'woocommerce-files-upload');?></label>
								<input type="number" min="1"  step="1" name="wcup_file_meta[<?php echo $counter ?>][cropped_image_height]" value="<?php echo $file_meta['cropped_image_height']; ?>" required></input>
								
								<label class="wcuf_required"><?php _e('Input image min width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[<?php echo $counter ?>][min_width_limit]" value="<?php echo $file_meta['min_width_limit']; ?>" required></input>
								<label class="wcuf_required"><?php _e('Input image min height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[<?php echo $counter ?>][min_height_limit]" value="<?php echo $file_meta['min_height_limit']; ?>" required></input>
								<label class="wcuf_required"><?php _e('Input image max width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[<?php echo $counter ?>][width_limit]" value="<?php echo $file_meta['width_limit']; ?>" required></input>
								<label class="wcuf_required"><?php _e('Input image max height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[<?php echo $counter ?>][height_limit]" value="<?php echo $file_meta['height_limit']; ?>" required></input>
								
								
								<h3><?php _e('Extra costs', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Enable extra cost per upload?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_enabled]" value="true" <?php if(isset($file_meta['extra_cost_enabled']) && $file_meta['extra_cost_enabled']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_is_taxable]" value="true" <?php if(isset($file_meta['extra_cost_is_taxable']) && $file_meta['extra_cost_is_taxable']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Select overcharge type (Percentace type will not work if "Upload per product" option has not been enabled)', 'woocommerce-files-upload');?></label>							
								<select  name="wcup_file_meta[<?php echo $counter ?>][extra_overcharge_type]">
								  <option value="fixed" <?php if($file_meta['extra_overcharge_type'] == 'fixed') echo 'selected'; ?>><?php _e('Fixed value', 'woocommerce-files-upload');?></option>
								  <option value="percentage" <?php if($file_meta['extra_overcharge_type'] == 'percentage') echo 'selected'; ?>><?php _e('Percentage of item price', 'woocommerce-files-upload');?></option>
								</select>
								
								<label style="margin-top:20px; "><?php _e('Value (this will be the percentage or the fixed value added to the original item price)', 'woocommerce-files-upload');?></label>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_value]"  step="0.01" value="<?php if(isset($file_meta['extra_cost_value'])) echo $file_meta['extra_cost_value']; else echo '1';?>" ></input>
								
								<label style="margin-top:20px;"><?php _e('Overcharge uploads limit', 'woocommerce-files-upload');?></label>
								<p><?php _e('Applies only if "Multiple files upload per single field" option has been enabled. If the number of uploaded files will pass this value will not be added extra overcharge for exceding uploads. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_overcharge_limit]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_overcharge_limit'])) echo $file_meta['extra_cost_overcharge_limit']; else echo '1';?>" ></input>
								
								<!-- <label ><?php _e('Fee', 'woocommerce-files-upload');?></label>
								<input type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_initial_fee]" min="0" value="<?php if(isset($file_meta['extra_cost_initial_fee'])) echo $file_meta['extra_cost_initial_fee']?>" ></input>
								
								<label><?php _e('Quantity limit', 'woocommerce-files-upload');?></label>
								<input type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_qty_limit]" min="0" step="1" value="<?php if(isset($file_meta['extra_cost_qty_limit'])) echo $file_meta['extra_cost_qty_limit']?>" ></input>
								-->
								
								<h3><?php _e('Extra costs per second (ONLY APPLICABLE IF UPLOADED FILE IS AN AUDIO/VIDEO)', 'woocommerce-files-upload');?></h3>
								<p><?php _e('WCUF will try do detect media file the duration (in seconds) extracting the info from its ID3 data (if any and well encoded). An extra cost will be added to the products for the seconds detected.', 'woocommerce-files-upload');?></p>
								<label style="margin-top:20px;"><?php _e('Enable extra cost per second?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_media_enabled]" value="true" <?php if(isset($file_meta['extra_cost_media_enabled']) && $file_meta['extra_cost_media_enabled']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_media_is_taxable]" value="true" <?php if(isset($file_meta['extra_cost_media_is_taxable']) && $file_meta['extra_cost_media_is_taxable']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Display the "Cost per second" on cart? (Will be added an extra text reporting how much cost a second)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][show_cost_per_second]" value="true" <?php if(isset($file_meta['show_cost_per_second']) && $file_meta['show_cost_per_second']) echo 'checked="checked"'?> ></input>
								
								
								<label style="margin-top:20px; "><?php _e('Additional cost per second', 'woocommerce-files-upload');?></label>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_per_second_value]" step="0.01" value="<?php if(isset($file_meta['extra_cost_per_second_value'])) echo $file_meta['extra_cost_per_second_value']; else echo '1';?>" ></input>
								
								<label style="margin-top:20px;"><?php _e('Maximun seconds overcharge limit', 'woocommerce-files-upload');?></label>
								<p><?php _e('If the number of seconds will pass this value will not be added extra overcharge for exceding seconds. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[<?php echo $counter ?>][extra_cost_overcharge_seconds_limit]" step="1" min="0" value="<?php if(isset($file_meta['extra_cost_overcharge_seconds_limit'])) echo $file_meta['extra_cost_overcharge_seconds_limit']; else echo '0';?>" ></input>
								
								
								<h3><?php _e('Text feedback', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Add a text field where the customer can input text? (Note: text must be inserted before files are uploaded)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][text_field_on_order_details_page]" value="true"  <?php if($file_meta['text_field_on_order_details_page']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Label (could be left empty)', 'woocommerce-files-upload');?></label>
								<input type="text" name="wcup_file_meta[<?php echo $counter ?>][text_field_label]" value="<?php if(isset($file_meta['text_field_label'])) echo $file_meta['text_field_label']; ?>"   ></input>
								
								<label style=""><?php _e('Max input characters (leave 0 for no limits)', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[<?php echo $counter ?>][text_field_max_input_chars]" value="<?php if(isset($file_meta['text_field_max_input_chars'])) echo $file_meta['text_field_max_input_chars']; else echo 0; ?>"   ></input>
								
								<label style=""><?php _e('Is required?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][is_text_field_on_order_details_page_required]" value="true"  <?php if($file_meta['is_text_field_on_order_details_page_required']) echo 'checked="checked"'?> ></input>
								
								<h3><?php _e('Disclaimer', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Add a disclaimer checkbox?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][disclaimer_checkbox]" value="true"  <?php if($file_meta['disclaimer_checkbox']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Disclameir checkbox label (HTML accepted. Ex: "I have read and accepted the &lt;a href="www.link.to/disclaimer"&gt; Disclaimer &lt;/a&gt;")', 'woocommerce-files-upload');?></label>
								<textarea type="text" class="wcuf_disclaimer_text" name="wcup_file_meta[<?php echo $counter ?>][disclaimer_text]" cols="80" rows="5"><?php echo $file_meta['disclaimer_text']; ?></textarea>
								
								<h3><?php _e('Notifications', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Notify admin via email when customer completed the upload?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][notify_admin]" value="true" <?php if($file_meta['notify_admin']) echo 'checked="checked"'?> ></input>
								
								<label style="margin-top:20px;"><?php _e('Attach uploaded file to admin notification email? (<strong>NOTE:</strong> this option works only if admin notification email option has been enabled</i>)', 'woocommerce-files-upload');?></label>
								<p><small><?php _e('Remember that some some server email provider will not receive emails with attachments bigger than 10MB (<a target="_blank" href="https://www.outlook-apps.com/maximum-email-size/">Gmail: 25MB, Outlook and Hotmail 10MB,...</a>)', 'woocommerce-files-upload'); ?></small></p>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][notify_attach_to_admin_email]" value="true" <?php if($file_meta['notify_attach_to_admin_email']) echo 'checked="checked"'?> ></input>
								
								<label class="option_label"><?php _e('Recipient(s)', 'woocommerce-files-upload');?></label>
								<p><small><?php  _e('Leave empty to send notifications to site admin email address.', 'woocommerce-files-upload'); ?></small></p>
								<input type="text" name="wcup_file_meta[<?php echo $counter ?>][notifications_recipients]" placeholder="<?php _e("You can insert multiple email addresses comma separated, ex.: 'admin@site.com, managment@site.com'", "woocommerce-files-upload"); ?>" value="<?php echo $notifications_recipients; ?>" size="100"></input>
								
								
								<h3><?php _e('Upload field type', 'woocommerce-files-upload');?></h3>
								<p><?php echo $upload_per_product_instruction; ?>
								</p>
								<label style="margin-top:20px;"  ><?php _e('Upload per product', 'woocommerce-files-upload');?></label>
								<input type="checkbox" id="wcuf_multiple_uploads_checkbox_<?php echo $counter ?>" name="wcup_file_meta[<?php echo $counter ?>][disable_stacking]" value="true" <?php if(isset($file_meta['disable_stacking']) && $file_meta['disable_stacking']) echo 'checked="checked"' ?> ></input>
								
								<label style="margin-top:20px;"  ><?php _e('Enable one upload field for every single product variation? (Works only with variable products,  if the "Upload per product" option has been enabled and all variations have been created. In case of "Any" variations, upload field will not work)', 'woocommerce-files-upload');?></label>
								<input type="checkbox"  name="wcup_file_meta[<?php echo $counter ?>][disable_stacking_for_variation]" value="true" <?php if(isset($file_meta['disable_stacking_for_variation']) && $file_meta['disable_stacking_for_variation']) echo 'checked="checked"'?> ></input>
								
								
								<h3><?php _e('Multiple files upload per single upload field', 'woocommerce-files-upload');?></h3>
								<?php if(!class_exists('ZipArchive')): ?>
									<strong><?php _e('This feature is not available because your server has not the "ZipArchive" php extension installed.', 'woocommerce-files-upload');?></strong>
								<?php else: ?>
									<p><strong><?php _e('NOTE:', 'woocommerce-files-upload');?></strong> <?php _e('Using the <i>Upload files Configurator -> Options menu</i> you can also enable the special <strong>Enable quantity selection</strong> option that will allow your customers to specify a quantity value for each upload.', 'woocommerce-files-upload');?></p>
									<label style="margin-top:20px;"  ><?php _e('Enable multiple files upload per single field?', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[<?php echo $counter ?>][enable_multiple_uploads_per_field]" value="true" <?php if(isset($file_meta['enable_multiple_uploads_per_field']) && $file_meta['enable_multiple_uploads_per_field']) echo 'checked="checked"'?> ></input>
									
									<label style="margin-top:20px;"  ><?php _e('Select max number of files that can be uploaded. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" has been enabled)', 'woocommerce-files-upload');?></label>
									<input type="number"  min="0" name="wcup_file_meta[<?php echo $counter ?>][multiple_uploads_max_files]" value="<?php if(isset($file_meta['multiple_uploads_max_files']) && $file_meta['multiple_uploads_max_files']) echo $file_meta['multiple_uploads_max_files']; else echo 0; ?>"   ></input>
									
									<label ><?php _e('Minimum number of required uploads. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" option has been enabled)', 'woocommerce-files-upload');?></label>
									<input type="number"  min="0" name="wcup_file_meta[<?php echo $counter ?>][multiple_uploads_minimum_required_files]" value="<?php if(isset($file_meta['multiple_uploads_minimum_required_files']) && $file_meta['multiple_uploads_minimum_required_files']) echo $file_meta['multiple_uploads_minimum_required_files']; else echo 0; ?>"></input>
									
									<label style=""  ><?php _e('Max number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[<?php echo $counter ?>][multiple_uploads_max_files_depends_on_quantity]" value="true" <?php if(isset($file_meta['multiple_uploads_max_files_depends_on_quantity']) && $file_meta['multiple_uploads_max_files_depends_on_quantity']) echo 'checked="checked"'?> ></input>
									
									<label style=""  ><?php _e('Minimum number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[<?php echo $counter ?>][multiple_uploads_min_files_depends_on_quantity]" value="true" <?php if(isset($file_meta['multiple_uploads_min_files_depends_on_quantity']) && $file_meta['multiple_uploads_min_files_depends_on_quantity']) echo 'checked="checked"'?> ></input>
									
								<?php endif; ?>
								
								<h3><?php _e('Filtering', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Filtering criteria: This upload field will be', 'woocommerce-files-upload');?></label>							
								<select  class="upload_type" data-id="<?php echo $counter ?>" name="wcup_file_meta[<?php echo $counter ?>][enable_for]">
								  <option value="always" <?php if($file_meta['enable_for'] == 'always') echo 'selected'; ?>><?php _e('Enabled for every product (or order, according to "Upload per product" option)', 'woocommerce-files-upload');?></option>
								  <option value="categories" <?php if($file_meta['enable_for'] == 'categories') echo 'selected'; ?>><?php _e('Enabled for selected categories and products', 'woocommerce-files-upload');?></option>
								  <option value="categories_children" <?php if($file_meta['enable_for'] == 'categories_children') echo 'selected'; ?>><?php _e('Enabled for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>
								  <option value="disable_categories"  <?php if($file_meta['enable_for'] == 'disable_categories') echo 'selected'; ?>><?php _e('Disabled for selected categories and products', 'woocommerce-files-upload');?></option>
								  <option value="disable_categories_children"  <?php if($file_meta['enable_for'] == 'disable_categories_children') echo 'selected'; ?>><?php _e('Disable for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>
								</select>
								<div class="spacer" ></div>
								<div class="upload_categories_box" id='upload_categories_box<?php echo $counter ?>'>
								<label><?php _e('Select category(ies) (search typing category name)', 'woocommerce-files-upload');?></label>
								<?php  
									/* WCUF_AdminMenu::WCUF_switch_to_default_lang();
									$select_cats = wp_dropdown_categories( array( 'echo' => 0, 'hide_empty' => 0, 'taxonomy' => 'product_cat', 'hierarchical' => 1) );
									WCUF_AdminMenu::WCUF_restore_current_lang();
									
									if(count($selected_categories) > 0)
									{
										//set selected (if exists)
										foreach($selected_categories as $category_id)
											$select_cats = str_replace('value="'.$category_id.'"', 'value="'.$category_id.'" selected', $select_cats);
											
									}
									
									$select_cats = str_replace( "name='cat' id='cat' class='postform'", "style='width:200px;' id='upload_type_id".$counter."' name='wcup_file_meta[".$counter."][categories][]' class='js-multiple' multiple='multiple' ", $select_cats ); 
									 echo $select_cats;  */
									 ?>
									 <select class="js-data-product-categories-ajax wcuf_select2"  id='upload_type_id<?php echo $counter; ?>' name='wcup_file_meta[<?php echo $counter; ?>][categories][]'  multiple='multiple'> 
											<?php 
												foreach( $selected_categories as $category_id)
													{
														echo '<option value="'.$category_id.'" selected="selected" >'.$wcuf_product_model->get_product_category_name($category_id).'</option>';
													}
												?>
									</select>
									<div class="spacer" ></div>
									<label><?php echo $product_filtering_instruction;?></label>
									<select class="js-data-products-ajax wcuf_select2" id="product_select_box0"  name='wcup_file_meta[<?php echo $counter; ?>][products][]' multiple='multiple'> 
									<?php 
										foreach( $selected_products as $product_id)
											{
												echo '<option value="'.$product_id.'" selected="selected" >'.$wcuf_product_model->get_product_name($product_id).'</option>';
											}
										?>
									</select>
								</div>
								<div class="spacer" ></div>
								<button class="remove_field button-secondary"><?php _e('Remove upload', 'woocommerce-files-upload');?></button>
							</div>
						</li>
				<?php $counter++; endforeach; else: ;?>
					<li class="input_box">
						<label class="wcuf_sort_button"><span class="dashicons dashicons-sort"></span><?php _e('Drag to sort', 'woocommerce-files-upload');?></label>
						<input type="text" name="wcup_file_meta[0][title]" placeholder=" "  size="80" required></input>
						<label class="wcuf_required"><?php _e('Title (NO Html code)', 'woocommerce-files-upload');?></label>
						
						<button data-id="0" class="button wcuf_collapse_options"><?php _e('Collapse/Expand Options Box', 'woocommerce-files-upload');?></button>
						<div id="wcuf_collapsable_box_0" class="wcuf_collapsable_box" >
							
								<input type ="hidden" class="wcup_file_meta_id" name= "wcup_file_meta[0][id]" value="<?php echo ($last_id+1); ?>" ></input>
								<input type ="hidden" class="wcup_file_meta_sort_order" name= "wcup_file_meta[0][sort_order]" value="0" ></input>
								<label><?php _e('Description (HTML code permitted)', 'woocommerce-files-upload');?></label>
								<textarea class="upload_description" name="wcup_file_meta[0][description]" rows="5" cols="80" placeholder="<?php _e('Description (you can use HTML code)', 'woocommerce-files-upload'); ?>"></textarea >
								
								<label class="option_label"><?php _e('Hide description after the upload has been completed?', 'woocommerce-files-upload');?></label>
								<input class="variant_option_input" type="checkbox" name="wcup_file_meta[0][hide_upload_after_upload]" value="true" ></input>
								
								<label class="wcuf_already_uploaded_message_label"><?php _e('Text to show after the upload has been completed (HTML code permitted)', 'woocommerce-files-upload'); ?></label>
								<p><?php _e('Permitted shortcodes:<br/><strong>[file_name]</strong> to display the file(s) name list. For every file is also reported the additional cost (only if any of the extra costs option have been enabled)<br/><strong>[file_name_no_cost]</strong> like previous but without costs display<br/><strong>[file_name_with_image_preview]</strong> like [file_name] shotcode with image preview (if the file(s) is a jpg/png)<br/><strong>[file_name_with_image_preview_no_cost]</strong> like previous shotcode without costs display<br/><strong>[image_preview_list]</strong> to display image preview (if the file(s) is a jpg/png)<br/><strong>[uploaded_files_num]</strong> to display total number of the uploaded files (useful if the "Multiple files upload" option has been enabled)<br/><strong>[additional_costs]</strong> (tax excluded) to display the sum of the additional costs of all the uploaded files', 'woocommerce-files-upload');?></p>
								<textarea  class="upload_description"  rows="5" cols="80" name="wcup_file_meta[0][message_already_uploaded]" placeholder="<?php _e('This message is displayed after file description only if a file have been uploaded (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php echo $already_uploaded_default_message; ?></textarea>
								
								<label class="option_label"><?php _e('In case of Variable Product, display full product name (product name and variant details)? If unchecked will be displayed only product name.', 'woocommerce-files-upload');?></label>
								<input class="variant_option_input" type="checkbox" name="wcup_file_meta[0][full_name_display]" value="true" checked="checked"></input>
								
								<label class="option_label"><?php _e('Allowed file type(s)', 'woocommerce-files-upload');?></label>
								<input type="text" name="wcup_file_meta[0][types]"  placeholder="<?php _e('File type(s), ex: .jpg,.bmp,.png leave empty to accept all file types. ', 'woocommerce-files-upload'); ?>" size="80" ></input>
								
								<label class="wcuf_required"><?php _e('File size (MB)', 'woocommerce-files-upload');?></label>
								<input type="number" min="1" name="wcup_file_meta[0][size]" value="20" required></input>
								
								<label><?php _e('Can user delete file(s)?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][user_can_delete]" value="true" checked="checked"></input>
								<label><?php _e('Can user download uploaded file?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][user_can_download_his_files]" value="true" checked="checked" ></input>
								
								<label><?php _e('Upload fields are visible also for Orders marked as <i>completed</i> (Valid only for Order details page)?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][upload_fields_editable_for_completed_orders]" value="true"></input>
								
								<h3><?php _e('Visibility', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Display field on Checkout page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][display_on_checkout]" value="true"></input>
								
								<label style="margin-top:20px;"><?php _e('Display field on Cart page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" data-id="0" class="wcuf_display_on_cart_checkbox" name="wcup_file_meta[0][display_on_cart]" value="true" ></input>
								
								<label style="margin-top:20px;"><?php _e('Display field on Product page?', 'woocommerce-files-upload');?></label>
								<!--<p><?php  _e('This will enable the "Upload per product" option.', 'woocommerce-files-upload') ?></p>-->
								<input type="checkbox" data-id="0" class="wcuf_display_on_product_checkbox" name="wcup_file_meta[0][display_on_product]" value="true"  ></input>
								
								<div class="wcuf_product_page_visibility_sub_option" id="wcuf_display_on_product_before_adding_to_cart_container_0">
									<label style="margin-top:20px;"><?php _e('on Product page, display the field BEFORE adding an item to the cart?', 'woocommerce-files-upload');?></label>
									<input type="checkbox" data-id="0" class="" id="wcuf_display_on_product_before_adding_to_cart_0" name="wcup_file_meta[0][display_on_product_before_adding_to_cart]" value="true"  ></input>
									<p><?php  echo $upload_product_page_before_instruction; ?></p>
								</div>
								<label style="margin-top:20px;"><?php _e('Display field on Order detail page?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][display_on_order_detail]" value="true" checked="checked"></input>
								
								<label style="margin-top:20px;"><?php _e('Hide on shortcode upload form?', 'woocommerce-files-upload');?></label>
								<p><?php _e('By default using the <strong>[wcuf_upload_form]</strong> shortcode all the upload fields that  match products in the cart are visible. Enabling this option this field will be hidden.', 'woocommerce-files-upload');?></p>
								<input type="checkbox" name="wcup_file_meta[0][hide_on_shortcode_form]" value="true" ></input>
								
								
								<h3><?php _e('Requirement', 'woocommerce-files-upload');?></h3>
								<p><?php _e('The plugin will <strong>try to deny the page leaving</strong> until all the required files have not been uploaded <strong>propting a warning dialog</strong> (some browsers, for security reasons, may not permit this denial).','woocommerce-files-upload'); ?><br/>
								<?php _e('In case you want to <strong>give the possibility to leave the page</strong>, go to the <strong>Options</strong> menu and under <strong>Allow user to leave page in case of required field</strong> section select <strong>Yes</strong> option.','woocommerce-files-upload'); ?></p>
								<p><strong><?php _e('NOTE','woocommerce-files-upload');?>:</strong> <?php _e('if enabling this option your are experiencing multiple "Add to cart" buttons issues on your shop page, go to the Option menu and set False for the Disable View Button option', 'woocommerce-files-upload'); ?></p>
								<label style="margin-top:20px;"><?php _e('Upload is required', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][required_on_checkout]" value="true" ></input>
								
								<h3><?php _e('Image media file (only for jpg/png media files)', 'woocommerce-files-upload');?></h3>
								<label><?php _e('Enable crop editor (this option will be ignored in case the multiple file option has been enabled)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" min="0" name="wcup_file_meta[0][enable_crop_editor]" value="true" ></input>
								
								<label class="wcuf_required"><?php _e('Cropped image width', 'woocommerce-files-upload');?></label>
								<input type="number" min="1" step="1" name="wcup_file_meta[0][cropped_image_width]" value="200" required></input>
								<label class="wcuf_required"><?php _e('Cropped image height', 'woocommerce-files-upload');?></label>
								<input type="number" min="1"  step="1" name="wcup_file_meta[0][cropped_image_height]" value="200" required></input>
								
								<label class="wcuf_required"><?php _e('Input image min width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[0][min_width_limit]" value="0" required></input>
								<label class="wcuf_required"><?php _e('Input image min height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[0][min_height_limit]" value="0" required></input>
								<label class="wcuf_required"><?php _e('Input image max width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[0][width_limit]" value="0" required></input>
								<label class="wcuf_required"><?php _e('Input image max height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[0][height_limit]" value="0" required></input>
								
								
								<h3><?php _e('Extra costs', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Enable extra cost per upload?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][extra_cost_enabled]" value="true" ></input>
								
								<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][extra_cost_is_taxable]" value="true" ></input>
								
								<label style="margin-top:20px;"><?php _e('Select overcharge type (Percentace type will not work if "Upload per product" option has not been enabled)', 'woocommerce-files-upload');?></label>							
								<select  name="wcup_file_meta[0][extra_overcharge_type]">
								  <option value="fixed"><?php _e('Fixed value', 'woocommerce-files-upload');?></option>
								  <option value="percentage"><?php _e('Percentage of item price', 'woocommerce-files-upload');?></option>
								</select>
								
								<label style="margin-top:20px; "><?php _e('Value (this will be the percentage or the fixed value added to the original item price)', 'woocommerce-files-upload');?></label>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[0][extra_cost_value]"  step="0.01" value="0" ></input>
								
								<label style="margin-top:20px;"><?php _e('Overcharge uploads limit', 'woocommerce-files-upload');?></label>
								<p><?php _e('Applies only if "Multiple files upload per single field" option has been enabled. If the number of uploaded files will pass this value will not be added extra overcharge for exceding uploads. Leave 0 for no limits', 'woocommerce-files-upload');?></p>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[0][extra_cost_overcharge_limit]" min="0" step="1" value="1" ></input>
								
								<h3><?php _e('Extra costs per second (ONLY APPLICABLE IF UPLOADED FILE IS AN AUDIO/VIDEO)', 'woocommerce-files-upload');?></h3>
								<p><?php _e('WCUF will try do detect media file the duration (in seconds) extracting the info from its ID3 data (if any and well encoded). An extra cost will be added to the products for the seconds detected.', 'woocommerce-files-upload');?></p>
								<label style="margin-top:20px;"><?php _e('Enable extra cost per second?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][extra_cost_media_enabled]" value="true"></input>
								
								<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][extra_cost_media_is_taxable]" value="true"  ></input>
								
								<label style="margin-top:20px;"><?php _e('Display the "Cost per second" on cart? (Will be added an extra text reporting how much cost a second)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[<?php echo $counter ?>][show_cost_per_second]" value="true" ></input>
								
								<label style="margin-top:20px; "><?php _e('Additional cost per second', 'woocommerce-files-upload');?></label>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[0][extra_cost_per_second_value]" step="0.01" value="1" ></input>
								
								<label style="margin-top:20px;"><?php _e('Maximun seconds overcharge limit', 'woocommerce-files-upload');?></label>
								<p><?php _e('If the number of seconds will pass this value will not be added extra overcharge for exceding seconds. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>
								<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta[0][extra_cost_overcharge_seconds_limit]" step="1" min="0" value="0" ></input>
								
								
								<h3><?php _e('Text feedback', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Add a text field where the customer can input text? (Note: text must be inserted before files are uploaded)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][text_field_on_order_details_page]" value="true"  ></input>
								
								<label style="margin-top:20px;"><?php _e('Label (could be left empty)', 'woocommerce-files-upload');?></label>
								<input type="text" name="wcup_file_meta[0][text_field_label]" value=""   ></input>
								
								<label style=""><?php _e('Max input characters (leave 0 for no limits)', 'woocommerce-files-upload');?></label>
								<input type="number" min="0" name="wcup_file_meta[0][text_field_max_input_chars]" value="0"   ></input>
								
								<label style=""><?php _e('Is required?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][is_text_field_on_order_details_page_required]" value="true" ></input>
								
								<h3><?php _e('Disclaimer', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Add a disclaimer checkbox?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][disclaimer_checkbox]" value="true" ></input>
								
								<label style="margin-top:20px;"><?php _e('Disclameir checkbox label (HTML accepted. Ex: "I have read and accepted the &lt;a href="www.link.to/disclaimer"&gt; Disclaimer &lt;/a&gt;")', 'woocommerce-files-upload');?></label>
								<textarea type="text" class="wcuf_disclaimer_text" name="wcup_file_meta[0][disclaimer_text]" cols="80" rows="5"></textarea>
								
								<h3><?php _e('Notifications', 'woocommerce-files-upload');?></h3>
								<label style="margin-top:20px;"><?php _e('Notify admin via mail when customer completed the upload?', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][notify_admin]" value="true"></input>
								
								<label style="margin-top:20px;"><?php _e('Attach uploaded file to admin notification email? (<strong>NOTE:</strong> this option works only if admin notification email option has been enabled)', 'woocommerce-files-upload');?></label>
								<p><small><?php _e('Remember that some some server email provider will not receive emails with attachments bigger than 10MB (<a target="_blank" href="https://www.outlook-apps.com/maximum-email-size/">Gmail: 25MB, Outlook and Hotmail 10MB,...</a>)', 'woocommerce-files-upload'); ?></small></p>
								<input type="checkbox" name="wcup_file_meta[0][notify_attach_to_admin_email]" value="true" ></input>
								
								<label class="option_label"><?php _e('Recipient(s)', 'woocommerce-files-upload');?></label>
								<p><small><?php  _e('Leave empty to send notifications to site admin email address.', 'woocommerce-files-upload'); ?></small></p>
								<input type="text" name="wcup_file_meta[0][notifications_recipients]" placeholder="<?php _e("You can insert multiple email addresses comma separated, ex.: 'admin@site.com, managment@site.com'", "woocommerce-files-upload"); ?>" value="" size="100"></input>
								
								
								<h3><?php _e('Upload field type', 'woocommerce-files-upload');?></h3>
								<p><?php echo $upload_per_product_instruction; ?>
								</p>
								<label style="margin-top:20px;"  ><?php _e('Upload per product', 'woocommerce-files-upload');?></label>
								<input type="checkbox" id="wcuf_multiple_uploads_checkbox_0" name="wcup_file_meta[0][disable_stacking]" value="true" checked="checked"></input>
								
								<label style="margin-top:20px;"  ><?php _e('Enable one upload field for every single product variation? (Works only with variable products,  if the "Upload per product" option has been enabled and all variations have been created. In case of "Any" variations, upload field will not work)', 'woocommerce-files-upload');?></label>
								<input type="checkbox" name="wcup_file_meta[0][disable_stacking_for_variation]" value="true"  ></input>
								
								<h3><?php _e('Multiple files upload per single upload field', 'woocommerce-files-upload');?></h3>
								<?php if(!class_exists('ZipArchive')): ?>
									<strong><?php _e('This feature is not available because your server has not the "ZipArchive" php extension installed.', 'woocommerce-files-upload');?></strong>
								<?php else: ?>
									<p><strong><?php _e('NOTE:', 'woocommerce-files-upload');?></strong> <?php _e('Using the <i>Upload files Configurator -> Options menu</i> you can also enable the special <strong>Enable quantity selection</strong> option that will allow your customers to specify a quantity value for each upload.', 'woocommerce-files-upload');?></p>
									<label style="margin-top:20px;"  ><?php _e('Enable multiple files upload per single field?', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[0][enable_multiple_uploads_per_field]" value="true" ></input>
									
									<label style="margin-top:20px;"  ><?php _e('Select max number of files that can be uploaded. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" has been enabled)', 'woocommerce-files-upload');?></label>
									<input type="number"  min="0" name="wcup_file_meta[0][multiple_uploads_max_files]" value="0"   ></input>
									
									<label  ><?php _e('Minimum number of required uploads. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" option has been enabled)', 'woocommerce-files-upload');?></label>
									<input type="number"  min="0" name="wcup_file_meta[0][multiple_uploads_minimum_required_files]" value="0"></input>
									
									
									<label style=""  ><?php _e('Max number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[0][multiple_uploads_max_files_depends_on_quantity]" value="true" ></input> 
									
									<label style=""  ><?php _e('Minimum number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>
									<input type="checkbox"  name="wcup_file_meta[0][multiple_uploads_min_files_depends_on_quantity]" value="true" ></input>
									
								<?php endif; ?>
								
								<h3><?php _e('Filtering', 'woocommerce-files-upload');?></h3>								
								<label style="margin-top:20px;"><?php _e('Filtering criteria: This upload field will be', 'woocommerce-files-upload');?></label>
								<select  class="upload_type" data-id="0" name="wcup_file_meta[0][enable_for]">
								  <option value="always" selected><?php _e('Enabled for every product (or order, according to "Upload per product" option)', 'woocommerce-files-upload');?></option>
								  <option value="categories" ><?php _e('Enabled for selected categories and products', 'woocommerce-files-upload');?></option>
								  <option value="categories_children" ><?php _e('Enabled for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>
								  <option value="disable_categories" ><?php _e('Disabled for selected categories and products', 'woocommerce-files-upload');?></option>
								  <option value="disable_categories_children" ><?php _e('Disable for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>
								</select>
								
								<div class="spacer" ></div>
								<div class="upload_categories_box" id='upload_categories_box0'>
								<?php 
									 /*  WCUF_AdminMenu::WCUF_switch_to_default_lang();
									  $select_cats = wp_dropdown_categories( array( 'echo' => 0, 'hide_empty' => 0, 'taxonomy' => 'product_cat', 'hierarchical' => 1) );
									  WCUF_AdminMenu::WCUF_restore_current_lang();
									  $select_cats = str_replace( "name='cat' id='cat' class='postform'", "style='width:200px;' id='upload_type_id0' name='wcup_file_meta[0][categories][]' class='js-multiple' multiple='multiple' ", $select_cats ); 
									 echo $select_cats;  */
									 
									 ?>
									  <label><?php _e('Select category(ies) (search typing category name)', 'woocommerce-files-upload');?></label>
									  <select class="js-data-product-categories-ajax wcuf_select2"  id='upload_type_id0' name='wcup_file_meta[0][categories][]'  multiple='multiple'> </select>
									  <div class="spacer" ></div>
									  <label><?php echo $product_filtering_instruction;?></label>
									  <select class="js-data-products-ajax wcuf_select2" id="product_select_box0"  name='wcup_file_meta[0][products][]' multiple='multiple'> </select>
								</div>
								<div class="spacer" ></div>
								<button class="remove_field button-secondary"><?php _e('Remove upload', 'woocommerce-files-upload');?></button>
						</div>
					</li>
				<?php endif ?>
				</ul>
				<script>
				jQuery(document).ready(function() 
				{
						//var max_fields      = 999999; //maximum input boxes allowed
						var wrapper         = jQuery(".input_fields_wrap"); //Fields wrapper
						var add_button      = jQuery(".add_field_button"); //Add button ID
						var x = <?php echo ($last_id+1); ?>; //initlal text box count
						
						//jQuery(".js-multiple").select2({'width':300});
						jQuery(".upload_type").on('change', setSelectBoxVisibility);
						jQuery(".upload_type").trigger('change');
						
						
						function setSelectBoxVisibility(event)
						{
							if(jQuery(event.target).val() != 'always')
							   {
								   jQuery("#upload_categories_box"+jQuery(event.target).data('id')).show();
							   }
							   else
								  jQuery("#upload_categories_box"+jQuery(event.target).data('id')).hide();
						}

						jQuery(add_button).click(function(e)
						{ //on add input button click
							e.preventDefault();
							e.stopImmediatePropagation();
							//if(x < max_fields)
							{
								x++; 
								jQuery(wrapper).append(getHtmlTemplate(x)); //add input box
								wcuf_activate_new_category_select_box("#upload_type_id"+x);
								wcuf_activate_new_product_select_box("#product_select_box"+x);
							}
							//jQuery(".js-multiple").select2({'width':300});
							jQuery(".upload_type").on('change', setSelectBoxVisibility);
							jQuery('.wcuf_collapse_options').on('click', wcuf_onCollassableButtonClick);
							wcuf_checkMultipleUpoloadsCheckbox();
							return false;
						});
					   
						jQuery(wrapper).on("click",".remove_field", function(e)
						{ //user click on remove text
							e.preventDefault(); 
							
							//smooth scroll
							 jQuery('html, body').animate({
								scrollTop: jQuery(this).parent().parent('.input_box').offset().top-100
							}, 500);
	
							jQuery(this).parent().parent('.input_box').delay(500).fadeOut(500, function()
							{
								//jQuery(this).parent('.input_box').remove(); 
								jQuery(this).remove(); 
								x--;
							}); 
							
						})
				});
				function getHtmlTemplate(index)
				{
					var categories = '<?php
								  WCUF_AdminMenu::WCUF_switch_to_default_lang();
								  $select_cats = wp_dropdown_categories( array( 'echo' => 0,'hide_empty' => 0, 'taxonomy' => 'product_cat', 'hierarchical' => 1) );
								  WCUF_AdminMenu::WCUF_restore_current_lang();
								  $select_cats = str_replace( "name='cat' id='cat' class='postform'", 'style="width:200px;" id="upload_type_id_index_to_replace" name="wcup_file_meta[_index_to_replace][categories][]" class="js-multiple" multiple="multiple" ', $select_cats ); 
								 $select_cats = str_replace("'", '', $select_cats);
								echo str_replace("\n", '', $select_cats); ?>'; 
								 
					//categories = categories.replace("_index_to_replace", index);
					categories = categories.replace(/_index_to_replace/g, index);
					var template = '<li class="input_box">';
							template += '<label class="wcuf_sort_button"><span class="dashicons dashicons-sort"></span><?php _e('Drag to sort', 'woocommerce-files-upload');?></label>';
							template += '<label class="wcuf_required"><?php _e('Title (NO Html code)', 'woocommerce-files-upload');?> </label>';
							template += '<input type ="hidden" class="wcup_file_meta_id" name="wcup_file_meta['+index+'][id]" value="'+index+'" ></input>';
							template += '<input type ="hidden" class="wcup_file_meta_sort_order" name= "wcup_file_meta['+index+'][sort_order]" value="'+index+'" ></input>';
							template += '<input type="text"  name="wcup_file_meta['+index+'][title]"  placeholder=" "  size="80" required></textarea >';
							template += '<button data-id="'+index+'" class="button wcuf_collapse_options"><?php _e('Collapse/Expand Options Box', 'woocommerce-files-upload');?></button>';
							template += '<div id="wcuf_collapsable_box_'+index+'" class="wcuf_collapsable_box">';
								template += '<label><?php _e('Description (HTML code permitted)', 'woocommerce-files-upload');?> </label>';
								template += '<textarea  class="upload_description"  rows="5" cols="80" name="wcup_file_meta['+index+'][description]" placeholder="<?php _e('Description (you can use HTML code)', 'woocommerce-files-upload'); ?>"></textarea>';
								
								template += '<label class="option_label"><?php _e('Hide description after the upload has been completed?', 'woocommerce-files-upload');?></label>';
								template += '<input class="variant_option_input" type="checkbox" name="wcup_file_meta['+index+'][hide_upload_after_upload]" value="true" ></input>';
								
								template += '<label class="wcuf_already_uploaded_message_label"><?php _e('Text to show after the upload has been completed (HTML code permitted)', 'woocommerce-files-upload'); ?></label>';
								template += '<p><?php _e('Permitted shortcodes:<br/><strong>[file_name]</strong> to display the file(s) name list. For every file is also reported the additional cost (only if any of the extra costs option have been enabled)<br/><strong>[file_name_no_cost]</strong> like previous but without costs display<br/><strong>[file_name_with_image_preview]</strong> like [file_name] shotcode with image preview (if the file(s) is a jpg/png)<br/><strong>[file_name_with_image_preview_no_cost]</strong> like previous shotcode without costs display<br/><strong>[image_preview_list]</strong> to display image preview (if the file(s) is a jpg/png)<br/><strong>[uploaded_files_num]</strong> to display total number of the uploaded files (useful if the "Multiple files upload" option has been enabled)<br/><strong>[additional_costs]</strong> (tax excluded) to display the sum of the additional costs of all the uploaded files', 'woocommerce-files-upload');?></p>';
								template += '<textarea  class="upload_description"  rows="5" cols="80" name="wcup_file_meta['+index+'][message_already_uploaded]" placeholder="<?php _e('This message is displayed after file description only if a file have been uploaded (you can use HTML code)', 'woocommerce-files-upload'); ?>"><?php echo $already_uploaded_default_message; ?></textarea>';
								
								template += '<label class="option_label"><?php _e('In case of Variable Product, display full product name (product name and variant details)? If unchecked will be displayed only product name.', 'woocommerce-files-upload');?></label>';
								template += '<input class="variant_option_input" type="checkbox" name="wcup_file_meta['+index+'][full_name_display]" value="true" checked="checked"></input>';
								
								template += '<label class="option_label"><?php _e('Allowed file type(s)', 'woocommerce-files-upload');?></label>';
								//template += '<select  name="wcup_file_meta['+index+'][allow]">';
								//template += '  <option value="allow" >Allow</option>';
								//template += '  <option value="disallow" >Disallow</option>';
								//template += '</select>';
								template += '<input type="text" name="wcup_file_meta['+index+'][types]" placeholder="<?php _e('File type(s), ex: .jpg,.bmp,.png leave empty to accept all file types. ', 'woocommerce-files-upload'); ?>" size="80" ></input>';
								
								template += '<label class="wcuf_required"><?php _e('File size (MB)', 'woocommerce-files-upload');?></label>';								
								template += '<input type="number" min="1" name="wcup_file_meta['+index+'][size]" value="20" required></input>';
								
								template += '<label><?php _e('Can user delete file(s)?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][user_can_delete]" value="true" checked="checked"></input>';
								template += '<label><?php _e('Can user download uploaded file?  (Valid only for Order details page)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][user_can_download_his_files]" value="true" checked="checked" ></input>';
								
								template += '<label><?php _e('Upload fields are visible also for Orders marked as <i>completed</i> (Valid only for Order details page)?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][upload_fields_editable_for_completed_orders]" value="true"></input>';
								
								template += '<h3><?php _e('Visibility', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Display field on Checkout page?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][display_on_checkout]" value="true"></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Display field on Cart page?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" data-id="'+index+'" class="wcuf_display_on_cart_checkbox" name="wcup_file_meta['+index+'][display_on_cart]" value="true"></input>';
								
								
								template += '<label style="margin-top:20px;"><?php _e('Display field on Product page?', 'woocommerce-files-upload');?></label>';
								//template += '<p><?php  _e('This will enable the "Upload per product" option.', 'woocommerce-files-upload') ?></p>';
								template += '<input type="checkbox" data-id="'+index+'" class="wcuf_display_on_product_checkbox" name="wcup_file_meta['+index+'][display_on_product]" value="true" ></input>';
								
								template += '<div class="wcuf_product_page_visibility_sub_option" id="wcuf_display_on_product_before_adding_to_cart_container_'+index+'">';
								template +=	'<label style="margin-top:20px;"><?php _e('on Product page, display the field BEFORE adding an item to the cart?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" data-id="'+index+'" class="" id="wcuf_display_on_product_before_adding_to_cart_'+index+'" name="wcup_file_meta['+index+'][display_on_product_before_adding_to_cart]" value="true" ></input>';
								template +=	'<p><?php  echo $upload_product_page_before_instruction; ?></p>';
								template += '</div>';
								
								template += '<label style="margin-top:20px;"><?php _e('Display field on Order detail page?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][display_on_order_detail]" value="true" checked="checked"></input>';
										
								template += '<label style="margin-top:20px;"><?php _e('Hide on shortcode upload form?', 'woocommerce-files-upload');?></label>';
								template += '<p><?php _e('By default using the <strong>[wcuf_upload_form]</strong> shortcode all the upload fields that  match products in the cart are visible. Enabling this option this field will be hidden.', 'woocommerce-files-upload');?></p>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][hide_on_shortcode_form]" value="true" ></input>';
								
								template += '<h3><?php _e('Requirement', 'woocommerce-files-upload');?></h3>';
								template += '<p><?php _e('The plugin will <strong>try to deny the page leaving</strong> until all the required files have not been uploaded <strong>propting a warning dialog</strong> (some browsers, for security reasons, may not permit this denial).','woocommerce-files-upload'); ?><br/>';
								template += '<?php _e('In case you want to <strong>give the possibility to leave the page</strong>, go to the <strong>Options</strong> menu and under <strong>Allow user to leave page in case of required field</strong> section select <strong>Yes</strong> option.','woocommerce-files-upload'); ?></p>';
								template += '<p><strong><?php _e('NOTE','woocommerce-files-upload');?>:</strong> <?php _e('if enabling this option your are experiencing multiple "Add to cart" buttons issues on your shop page, go to the Option menu and set False for the Disable View Button option', 'woocommerce-files-upload'); ?></p>';
								template += '<label style="margin-top:20px;"><?php _e('Upload is required', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][required_on_checkout]" value="true" ></input>';
								
								template += '<h3><?php _e('Image media file (only for jpg/png media files)', 'woocommerce-files-upload');?></h3>';
								template += '<label><?php _e('Enable crop editor (this option will be ignored in case the multiple file option has been enabled)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" min="0" name="wcup_file_meta['+index+'][enable_crop_editor]" value="true" ></input>';
								
								template += '<label class="wcuf_required"><?php _e('Cropped image width', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="1" step="1" name="wcup_file_meta['+index+'][cropped_image_width]" value="200" required></input>';
								template += '<label class="wcuf_required"><?php _e('Cropped image height', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="1"  step="1" name="wcup_file_meta['+index+'][cropped_image_height]" value="200" required></input>';
								
								template += '<label class="wcuf_required"><?php _e('Input image min width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="0" name="wcup_file_meta['+index+'][min_width_limit]" value="0" required></input>';
								template += '<label class="wcuf_required"><?php _e('Input image min height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="0" name="wcup_file_meta['+index+'][min_height_limit]" value="0" required></input>';
								template += '<label class="wcuf_required"><?php _e('Input image max width in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="0" name="wcup_file_meta['+index+'][width_limit]" value="0" required></input>';
								template += '<label class="wcuf_required"><?php _e('Input image max height in px. Leave 0 for no limits', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="0" name="wcup_file_meta['+index+'][height_limit]" value="0" required></input>';
								
								template += '<h3><?php _e('Extra costs', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Enable extra cost per upload?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][extra_cost_enabled]" value="true" ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][extra_cost_is_taxable]" value="true" ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Select overcharge type (Percentace type will not work if "Upload per product" option has not been enabled)', 'woocommerce-files-upload');?></label>';							
								template += '<select  name="wcup_file_meta['+index+'][extra_overcharge_type]">';
								template += '  <option value="fixed" ><?php _e('Fixed value', 'woocommerce-files-upload');?></option>';
								template += '  <option value="percentage"><?php _e('Percentage of item price', 'woocommerce-files-upload');?></option>';
								template += '</select>';
								
								template += '<label style="margin-top:20px; "><?php _e('Value (this will be the percentage or the fixed value added to the original item price)', 'woocommerce-files-upload');?></label>';
								template += '<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta['+index+'][extra_cost_value]" step="0.01" min="0" value="0" ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Overcharge uploads limit', 'woocommerce-files-upload');?></label>';
								template += '<p><?php _e('Applies only if "Multiple files upload per single field" option has been enabled. If the number of uploaded files will pass this value will not be added extra overcharge for exceding uploads. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>';
								template += '<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta['+index+'][extra_cost_overcharge_limit]" min="0" step="1" value="0" ></input>';
								
								template += '<h3><?php _e('Extra costs per second (ONLY APPLICABLE IF UPLOADED FILE IS AN AUDIO/VIDEO)', 'woocommerce-files-upload');?></h3>';
								template += '<p><?php _e('WCUF will try do detect media file the duration (in seconds) extracting the info from its ID3 data (if any and well encoded). An extra cost will be added to the products for the seconds detected.', 'woocommerce-files-upload');?></p>';
								template += '<label style="margin-top:20px;"><?php _e('Enable extra cost per second?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][extra_cost_media_enabled]" value="true"></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Is taxable?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][extra_cost_media_is_taxable]" value="true"  ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Display the "Cost per second" on cart? (Will be added an extra text reporting how much cost a second)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][show_cost_per_second]" value="true" ></input>';
								
								template += '<label style="margin-top:20px; "><?php _e('Additional cost per second', 'woocommerce-files-upload');?></label>';
								template += '<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta['+index+'][extra_cost_per_second_value]" step="0.01" value="1" ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Maximun seconds overcharge limit', 'woocommerce-files-upload');?></label>';
								template += '<p><?php _e('If the number of seconds will pass this value will not be added extra overcharge for exceding seconds. Leave 0 for no limits.', 'woocommerce-files-upload');?></p>';
								template += '<input class="wcuf_no_margin_bottom" type="number" name="wcup_file_meta['+index+'][extra_cost_overcharge_seconds_limit]" step="1" min="0" value="0" ></input>';
								
								template += '<h3><?php _e('Text feedback', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Add a text field where the customer can input text? (Note: text must be inserted before files are uploaded)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][text_field_on_order_details_page]" value="true"  ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Label (could be left empty)', 'woocommerce-files-upload');?></label>';
								template += '<input type="text" name="wcup_file_meta['+index+'][text_field_label]" value=""></input>';
								
								template += '<label style=""><?php _e('Max input characters (leave 0 for no limits)', 'woocommerce-files-upload');?></label>';
								template += '<input type="number" min="0" name="wcup_file_meta['+index+'][text_field_max_input_chars]" value="0"   ></input>';
								
								template += '<label style=""><?php _e('Is required?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][is_text_field_on_order_details_page_required]" value="true" ></input>';
								
								template += '<h3><?php _e('Disclaimer', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Add a disclaimer checkbox?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][disclaimer_checkbox]" value="true" ></input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Disclameir checkbox label (HTML accepted. Ex: "I have read and accepted the &lt;a href="www.link.to/disclaimer"&gt; Disclaimer &lt;/a&gt;")', 'woocommerce-files-upload');?></label>';
								template += '<textarea type="text" class="wcuf_disclaimer_text" name="wcup_file_meta['+index+'][disclaimer_text]" cols="80" rows="5"></textarea>';
								
								template += '<h3><?php _e('Notifications', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Notify admin via mail when customer completed the upload?', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][notify_admin]" value="true" > </input>';
								
								template += '<label style="margin-top:20px;"><?php _e('Attach uploaded file to admin notification email? (<strong>NOTE:</strong> this option works only if admin notification email option has been enabled)', 'woocommerce-files-upload');?></label>';
								template += '<p><small><?php _e('Remember that some some server email provider will not receive emails with attachments bigger than 10MB (<a target="_blank" href="https://www.outlook-apps.com/maximum-email-size/">Gmail: 25MB, Outlook and Hotmail 10MB,...</a>)', 'woocommerce-files-upload'); ?></small></p>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][notify_attach_to_admin_email]" value="true" ></input>';
								
								template += '<label class="option_label"><?php _e('Recipient(s)', 'woocommerce-files-upload');?></label>';
								template += '<p><small><?php  _e('Leave empty to send notifications to site admin email address.', 'woocommerce-files-upload'); ?></small></p>';
								template += '<input type="text" name="wcup_file_meta['+index+'][notifications_recipients]" placeholder="<?php _e("You can insert multiple email addresses comma separated, ex.: \'admin@site.com, managment@site.com\'", "woocommerce-files-upload"); ?>" value="" size="100"></input>';
								
								
								template += '<h3><?php _e('Upload field type', 'woocommerce-files-upload');?></h3>';
								template += '<p><?php echo $upload_per_product_instruction; ?>';
								template += '</p>';
								template += '<label style="margin-top:20px;"><?php _e('Upload per product', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" id="wcuf_multiple_uploads_checkbox_'+index+'" name="wcup_file_meta['+index+'][disable_stacking]" value="true" checked="checked"></input>'; 
								
								template += '<label style="margin-top:20px;"  ><?php _e('Enable one upload field for every single product variation? (Works only with variable products,  if the "Upload per product" option has been enabled and all variations have been created. In case of "Any" variations, upload field will not work)', 'woocommerce-files-upload');?></label>';
								template += '<input type="checkbox" name="wcup_file_meta['+index+'][disable_stacking_for_variation]" value="true" ></input>';
								
								template += '<h3><?php _e('Multiple files upload per single upload field', 'woocommerce-files-upload');?></h3>';
								<?php if(!class_exists('ZipArchive')): ?>
									template += '<strong><?php _e('This feature is not available because your server has not the "ZipArchive" php extension installed.', 'woocommerce-files-upload');?></strong>';
								<?php else: ?>
									template += '<p><strong><?php _e('NOTE:', 'woocommerce-files-upload');?></strong> <?php _e('Using the <i>Upload files Configurator -> Options menu</i> you can also enable the special <strong>Enable quantity selection</strong> option that will allow your customers to specify a quantity value for each upload.', 'woocommerce-files-upload');?></p>';
									template += '<label style="margin-top:20px;"  ><?php _e('Enable multiple files upload per single field?', 'woocommerce-files-upload');?></label>';
									template += '<input type="checkbox"  name="wcup_file_meta['+index+'][enable_multiple_uploads_per_field]" value="true" ></input>';
									
									template += '<label style="margin-top:20px;"  ><?php _e('Select max number of files that can be uploaded. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" has been enabled)', 'woocommerce-files-upload');?></label>';
									template += '<input type="number"  min="0" name="wcup_file_meta['+index+'][multiple_uploads_max_files]" value="0"></input>';
									
									template += '<label ><?php _e('Minimum number of required uploads. Leave 0 for no limits. (works only if "Enable multiple files upload per single field" option has been enabled)', 'woocommerce-files-upload');?></label>';
									template += '<input type="number"  min="0" name="wcup_file_meta['+index+'][multiple_uploads_minimum_required_files]" value="0"></input>';
								
									template += '<label style=""  ><?php _e('Max number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>';
									template += '<input type="checkbox"  name="wcup_file_meta['+index+'][multiple_uploads_max_files_depends_on_quantity]" value="true" ></input>';
									
									template += '<label style=""  ><?php _e('Minimum number of uploadable files  depends on product quantity? ( Works only if "Upload per product" and "Enable multiple files upload per single field" options have been enabled and Field is not displayed BEFORE adding items to the cart on product page)', 'woocommerce-files-upload');?></label>';
									template += '<input type="checkbox"  name="wcup_file_meta['+index+'][multiple_uploads_min_files_depends_on_quantity]" value="true" ></input>';
								<?php endif; ?>
								
								template += '<h3><?php _e('Filtering', 'woocommerce-files-upload');?></h3>';
								template += '<label style="margin-top:20px;"><?php _e('Filtering criteria: This upload field will be', 'woocommerce-files-upload');?></label>';
								template += '<select  class="upload_type" data-id="'+index+'" name="wcup_file_meta['+index+'][enable_for]">';
								template += '  <option value="always" selected><?php _e('Enabled for every product (or order, according to "Upload per product" option)', 'woocommerce-files-upload');?></option>';
								template += '  <option value="categories" ><?php _e('Enabled for selected categories and products', 'woocommerce-files-upload');?></option>';
								template += '  <option value="categories_children" ><?php _e('Enabled for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>';
								template += '  <option value="disable_categories"  ><?php _e('Disabled for selected categories and products', 'woocommerce-files-upload');?></option>';
								template += '  <option value="disable_categories_children" ><?php _e('Disable for selected categories (and all its children) and products', 'woocommerce-files-upload');?></option>';
								template += '</select>';
								template += '<div class="spacer" ></div>';
								template += '<div class="upload_categories_box" id="upload_categories_box'+index+'">';
								//template += categories;
									template += '<label><?php _e('Select category(ies) (search typing category name)', 'woocommerce-files-upload');?></label>';
									template += '<select class="js-data-product-categories-ajax wcuf_select2" id="upload_type_id'+index+'" name="wcup_file_meta['+index+'][categories][]"  multiple="multiple"> </select>';
									template += '<div class="spacer" ></div>';
									template += '<label><?php echo $product_filtering_instruction;?></label>';
									template += '<select class="js-data-products-ajax wcuf_select2" id="product_select_box'+index+'"  name="wcup_file_meta['+index+'][products][]" multiple="multiple"> </select>';
								
								template += '</div>';
								template += '<div class="spacer"></div>';
								template += '<button class="remove_field button-secondary"><?php _e('Remove upload', 'woocommerce-files-upload');?></button>';
						template += '</div>';
						template += '</li>';
					return template;
				}
				</script>
				<button class="add_field_button button-primary"><?php _e('Add one more Upload Field', 'woocommerce-files-upload');?></button>
				<div class="spacer"></div><div class="spacer"></div>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'wshipinfo-patsatech'); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}
?>