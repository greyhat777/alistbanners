<?php 
class WCUF_Cart
{
	var $already_add_to_cart_buttons_added;
	public function __construct()
	{
		add_action( 'woocommerce_cart_calculate_fees', array(&$this, 'add_extra_upload_costs') );
		//add_action('woocommerce_add_to_cart_validation', array(&$this, 'cart_add_to_validation'), 10, 5);
		//add_action('woocommerce_update_cart_validation', array(&$this, 'cart_update_validation'), 10, 4);
		
		add_action( 'woocommerce_remove_cart_item', array( &$this, 'cart_item_removed' ), 10 ,2);
		/* add_filter( 'woocommerce_add_to_cart_redirect', array(&$this, 'custom_add_to_cart_redirect'), 10,1);
		add_action( 'woocommerce_add_to_cart', array(&$this, 'after_add_to_cart_action'), 10, 6 ); */
		
		//Override add to cart button on shop page
		add_action('init', array(&$this, 'remove_loop_button'));
		add_action('woocommerce_after_shop_loop_item',array(&$this,'replace_add_to_cart')); 
		
		$this->already_add_to_cart_buttons_added = array();
	}
	function remove_loop_button()
	{
		/*  if(@is_product())
			return;  */
		global $wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();
		if($all_options['disable_view_button'] == 0)
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}		
	function replace_add_to_cart() 
	{
		/*  if(@is_product())
			return; */
		
		global $product, $wcuf_product_model,$wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();		
		
		//Check if "Add to cart" button has already been added
		/* wcuf_var_dump($this->already_add_to_cart_buttons_added); */
		if(isset($this->already_add_to_cart_buttons_added[$product->id]) || $all_options['disable_view_button'] == 1)
			return;
		$this->already_add_to_cart_buttons_added[$product->id] = true; 
		
		$link = $product->get_permalink();
		

		if($wcuf_product_model->has_a_required_upload_in_its_single_page($product))
			echo do_shortcode('<a href="'.$link.'" class="button addtocartbutton">'.__('View','woocommerce-files-upload').'</a>');
		else
			woocommerce_template_loop_add_to_cart();
	}
	function cart_item_removed($cart_item_key, $cart)
	{
		global $wcuf_session_model;
		$item = $cart->cart_contents[ $cart_item_key ];
		//wcuf_var_dump($item);
		$wcuf_session_model->remove_data_by_product_ids($item);
	}
	function after_add_to_cart_action( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) 
	{
		
		//wcuf_var_dump($product_id);
		//wcuf_var_dump(wcuf_product_in_cart_has_an_upload_field_in_its_single_page($product_id));
		/* if(wcuf_product_in_cart_has_an_upload_field_in_its_single_page($product_id))
		{
			 //wp_redirect(get_permalink($product_id )); 
			 ?>
			 <script>
				window.location.replace("<?php echo get_permalink($product_id ); ?>");
			 </script>
			 <?php
			 exit;
		} */
	}  
	function custom_add_to_cart_redirect($cart_get_cart_url) 
	{ 
		
		if ( isset( $_POST['add-to-cart'] ) ) 
		{
			$product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', $_POST['add-to-cart'] );
			//wcuf_var_dump($product_id);
			//wcuf_var_dump(wcuf_product_in_cart_has_an_upload_field_in_its_single_page($product_id));
			/* if(wcuf_product_in_cart_has_an_upload_field_in_its_single_page($product_id))
				return get_permalink($product_id ); */
				
		}
		return $cart_get_cart_url;
	}
	
	public function add_extra_upload_costs()
	{
		global $woocommerce, $wcuf_session_model, $wcuf_option_model, $wcuf_file_model;
		$all_temp_uploads = $wcuf_session_model->get_item_data();
		$file_fields_groups =  $wcuf_option_model->get_fields_meta_data();
		
		foreach((array)$all_temp_uploads as $temp_upload_id => $temp_upload)
		{
			$ids = $wcuf_file_model->get_product_ids_and_field_id_by_file_id($temp_upload_id);			
			$is_in_still_in_cart = true;
			if(isset($ids['product_id']))
				if(!$this->item_is_in_cart($ids['product_id'], $ids['variant_id']))
				{
					$is_in_still_in_cart = false;
					$wcuf_session_model->remove_item_data($temp_upload_id);
				}
				
			if($is_in_still_in_cart)
				foreach($file_fields_groups as $upload_field_meta)
				{
					if($upload_field_meta["id"] == $ids['field_id']) //0 => $field_id
					{
						if(isset($upload_field_meta['extra_cost_enabled']) && $upload_field_meta['extra_cost_enabled'])
						{
							if(isset($temp_upload['quantity']))
								$temp_upload['quantity'] = is_array($temp_upload['quantity']) ? array_sum($temp_upload['quantity']) : $temp_upload['quantity'];
							$quantity = isset($temp_upload['quantity']) ? $temp_upload['quantity'] : $temp_upload['num_uploaded_files'];
							$upload_field_meta['extra_cost_overcharge_limit'] = isset($upload_field_meta['extra_cost_overcharge_limit']) ? $upload_field_meta['extra_cost_overcharge_limit'] : null;
							//wcuf_var_dump($upload_field_meta['extra_overcharge_type']);
							$price_and_num = $this->get_additional_costs($quantity, $upload_field_meta['extra_cost_overcharge_limit'], $upload_field_meta['extra_cost_value'], $upload_field_meta['extra_overcharge_type'], $ids);
							
							if(isset($ids['product_id']))
								$id_to_print = $ids['variant_id'] != "" ? $ids['product_id']."_".$ids['variant_id']: $ids['product_id'];
							else
								$id_to_print = "";
							//wcuf_var_dump($price_and_num);
							$upload_field_meta['extra_cost_is_taxable'] = isset($upload_field_meta['extra_cost_is_taxable']) ? $upload_field_meta['extra_cost_is_taxable'] : false;
							
							$woocommerce->cart->add_fee("#".$id_to_print.": ".$temp_upload['title']." - ".$price_and_num['num'].__('X Upload(s)', 'woocommerce-files-upload'), $price_and_num['price'], $upload_field_meta['extra_cost_is_taxable']); //( string $name, float $amount, boolean $taxable = false, string $tax_class = ''  )
							//wcuf_var_dump($result);
							//wcuf_var_dump(WC()->cart->cart_contents);
						}
						//Extra cost per duration
						if(isset($temp_upload['ID3_info']) && isset($upload_field_meta['extra_cost_media_enabled']) && $upload_field_meta['extra_cost_media_enabled'])
						{
							//$temp_upload['name']
							//$temp_upload['ID3_info']
							$id3_counter = 1;
							$upload_field_meta['extra_cost_overcharge_seconds_limit'] = isset($upload_field_meta['extra_cost_overcharge_seconds_limit']) ? $upload_field_meta['extra_cost_overcharge_seconds_limit'] : null;
							$upload_field_meta['extra_cost_media_is_taxable'] = isset($upload_field_meta['extra_cost_media_is_taxable']) ? $upload_field_meta['extra_cost_media_is_taxable'] : false;
								
							if(is_array($temp_upload['ID3_info']))
								foreach((array)$temp_upload['ID3_info'] as $media_file_info)
								{
									if(isset($media_file_info['quantity']))
										$media_file_info['quantity'] = is_array($media_file_info['quantity']) ? array_sum($media_file_info['quantity']) : $media_file_info['quantity'];
									$quantity = isset($media_file_info['quantity']) ? $media_file_info['quantity'] : 1;
									$price_and_num = $this->get_additional_costs($media_file_info['playtime_seconds']*$quantity, $upload_field_meta['extra_cost_overcharge_seconds_limit'], $upload_field_meta['extra_cost_per_second_value']);
									$formatted_price = sprintf(get_woocommerce_price_format(), get_woocommerce_currency_symbol(),$upload_field_meta['extra_cost_per_second_value']);
									if(isset($ids['product_id']))
										$id_to_print = $ids['variant_id'] != "" ? $ids['product_id']."_".$ids['variant_id']: $ids['product_id'];
									else
										$id_to_print = "";
									
									$quantity_string = $quantity > 1 ? " - ".$quantity.__('X Upload(s)', 'woocommerce-files-upload'):"";
									$cost_per_second = isset($upload_field_meta['show_cost_per_second']) && $upload_field_meta['show_cost_per_second'] ? " (".$formatted_price." ".__(' per second ','woocommerce-files-upload')." )" : "";
									$woocommerce->cart->add_fee("#".$id_to_print."-".$id3_counter.": ".$media_file_info['file_name']." - ".$media_file_info['playtime_string'].$cost_per_second.$quantity_string, $price_and_num['price'], $upload_field_meta['extra_cost_media_is_taxable']);
									$id3_counter++;
								}
							}
					}
				} 
		}
	}
	public function get_sum_of_all_additional_costs($file_fields_groups,$temp_upload, $field_id, $product)
	{
		$extra_cost = 0;
		$product['field_id'] = $field_id;
		foreach($file_fields_groups as $upload_field_meta)
		{
			if($upload_field_meta["id"] == $field_id) 
			{
				if(isset($upload_field_meta['extra_cost_enabled']) && $upload_field_meta['extra_cost_enabled'])
				{
					if(isset($temp_upload['quantity']))
						$temp_upload['quantity'] = is_array($temp_upload['quantity']) ? array_sum($temp_upload['quantity']) : $temp_upload['quantity'];
					$quantity = isset($temp_upload['quantity']) ? $temp_upload['quantity'] : $temp_upload['num_uploaded_files'];
			
					$upload_field_meta['extra_cost_overcharge_limit'] = isset($upload_field_meta['extra_cost_overcharge_limit']) ? $upload_field_meta['extra_cost_overcharge_limit'] : null;
					$price_and_num = $this->get_additional_costs($quantity/* $temp_upload['num_uploaded_files'] */, $upload_field_meta['extra_cost_overcharge_limit'], $upload_field_meta['extra_cost_value'], $upload_field_meta['extra_overcharge_type'], $product);
					
					$upload_field_meta['extra_cost_is_taxable'] = isset($upload_field_meta['extra_cost_is_taxable']) ? $upload_field_meta['extra_cost_is_taxable'] : false;
					$extra_cost += $price_and_num['price'];
				}
				//Extra cost per duration
				if(isset($temp_upload['ID3_info']) && $temp_upload['ID3_info'] != 'none' && isset($upload_field_meta['extra_cost_media_enabled']) && $upload_field_meta['extra_cost_media_enabled'])
				{
					//$temp_upload['name']
					//$temp_upload['ID3_info']
					foreach($temp_upload['ID3_info'] as $media_file_info)
					{
						if(isset($media_file_info['quantity']))
								$media_file_info['quantity'] = is_array($media_file_info['quantity']) ? array_sum($media_file_info['quantity']) : $media_file_info['quantity'];
						$quantity = isset($media_file_info['quantity']) ? $media_file_info['quantity'] : 1;
						$upload_field_meta['extra_cost_overcharge_seconds_limit'] = isset($upload_field_meta['extra_cost_overcharge_seconds_limit']) ? $upload_field_meta['extra_cost_overcharge_seconds_limit'] : null;
						$price_and_num = $this->get_additional_costs($media_file_info['playtime_seconds']*$quantity, $upload_field_meta['extra_cost_overcharge_seconds_limit'], $upload_field_meta['extra_cost_per_second_value']);
						
						$upload_field_meta['extra_cost_media_is_taxable'] = isset($upload_field_meta['extra_cost_media_is_taxable']) ? $upload_field_meta['extra_cost_media_is_taxable'] : false;
						$extra_cost += $price_and_num['price'];
					}
				}
			}
		}
		return sprintf(get_woocommerce_price_format(), get_woocommerce_currency_symbol(),$extra_cost);		
	}
	
	public function get_additional_costs($num, $limit, $value, $type = 'fixed', $product_ids = null, $use_currecy_symbol = false)
	{
		$price = 0;
		//wcuf_var_dump($product_ids);
		//$product_ids = !is_array($product_ids) && isset($product_ids) ? $product_ids = array('product_id' => $product_ids['product_id']) : $product_ids;
		if($type == 'fixed')
		{
			$num = isset($limit) && ($limit == 0 || round($num) <= $limit) ? round($num) : $limit ;
			$price = $num * $value;
		}
		else if(isset($product_ids) && isset($product_ids['product_id']))
		{
			$product_ids['variant_id'] = !isset($product_ids['variant_id']) || $product_ids['variant_id'] == "" ? 0 : $product_ids['variant_id'];
			$product = /* !isset($product_ids['variant_id']) || $product_ids['variant_id'] == "" || */ $product_ids['variant_id']== 0 ? new WC_Product_Simple($product_ids['product_id']) : new WC_Product_Variation($product_ids['variant_id']);
			/* if(is_a($product, 'WC_Product_Variation'))
			{
				wcuf_var_dump($product_ids);
				wcuf_var_dump($product->get_price());
			} */
			//Price adjust
			$price =  $num * $product->get_price( ) * ($value/100);
			//$price =  $num * $product->price * ($value/100);
			//$price =  $num * $this->get_cart_item_price($product_ids['product_id'], $product_ids['variant_id']) * ($value/100);
		}
		$price = $use_currecy_symbol ? sprintf(get_woocommerce_price_format(), get_woocommerce_currency_symbol(),$price) : $price;
		//wcuf_var_dump($price);
		return array('price'=>$price, 'num'=>$num);
	}
	public function get_cart_item_price($item_id, $variation_id = 0)
	{
		$cart_items = WC()->cart->cart_contents;
		if(!isset($item_id) || empty($cart_items))
			return false;
		global $wcuf_wpml_helper;
		//wcuf_var_dump($cart_items);
		foreach((array)$cart_items as $item)
		{
			if($wcuf_wpml_helper->wpml_is_active())
			{
				$item['product_id'] = $wcuf_wpml_helper->get_main_language_id($item['product_id']);
				$item['variation_id'] = $wcuf_wpml_helper->get_main_language_id($item['variation_id']);
			}
			if($item['product_id'] == $item_id && ($variation_id == 0 || $item['variation_id'] == $variation_id ))
			{
				//wcuf_var_dump($item);
				return $item["data"]['price'];
			}
		}
		return false;
	}
	public function item_is_in_cart($item_id, $variation_id = 0)
	{
		$cart_items = WC()->cart->cart_contents;
		if(!isset($item_id) || empty($cart_items))
			return false;
		global $wcuf_wpml_helper;
		//wcuf_var_dump($cart_items);
		foreach((array)$cart_items as $item)
		{
			if($wcuf_wpml_helper->wpml_is_active())
			{
				$item['product_id'] = $wcuf_wpml_helper->get_main_language_id($item['product_id']);
				$item['variation_id'] = $wcuf_wpml_helper->get_main_language_id($item['variation_id']);
			}
			if($item['product_id'] == $item_id && ($variation_id == 0 || $item['variation_id'] == $variation_id ))
				return true;
		}
		return false;
	}
	//Add to cart
	/* public function cart_add_to_validation( $original_result, $product_id, $quantity , $variation_id = 0, $variations = null )
	{
		global $woocommerce,$wcps_product_model;

		//wcps_var_dump($product_id." ".$quantity);
		//wcps_var_dump(WC()->cart);
		//WC()->cart
		//$woocommerce->add_error( sprintf( "You must add a minimum of %s %s's to your cart to proceed." , $minimum, $product_title ) );
		$result = $wcps_product_model->customer_can_purchase_product($product_id,  $variation_id,  $quantity, false);
		if(!$result['result'])
			foreach($result['messages'] as $message)
				wc_add_notice( $message ,'error');
		
		if($result['result'] == true)
			$result['result'] = $original_result;
		
		return $result['result'];
	} */
	//Update cart -> Not used
	public function cart_update_validation($original_result, $cart_item_key, $values, $quantity )
	{
		global $woocommerce;
		$items = WC()->cart->cart_contents;
		$original_result = false;
		
		//wcuf_var_dump($original_result);
		if(isset($items[$cart_item_key]))
		{
			//$items[$cart_item_key]['product_id'], $items[$cart_item_key]['variation_id'], $quantity;
			
		}
		wc_add_notice( "aaa" ,'error');
		return $original_result;
	}
}
?>