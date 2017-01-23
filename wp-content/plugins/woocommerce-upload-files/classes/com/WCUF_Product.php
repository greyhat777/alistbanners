<?php 
class WCUF_Product
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcuf_get_products_list', array(&$this, 'ajax_get_products_partial_list'));
			add_action('wp_ajax_wcuf_get_product_categories_list', array(&$this, 'ajax_get_product_categories_partial_list'));
		}
	}	
	public function wc_price_calculator_is_active_on_product($product)
	{
		if(!class_exists('WC_Price_Calculator_Product'))
			return false;
		return  WC_Price_Calculator_Product::calculator_enabled( $product );
	}
	public function wc_price_calulator_get_unique_product_name_hash($product_name)
	{
		return $product_name != "" ? md5($product_name) : "0";
	}
	public function wc_price_calulator_get_cart_item_name($cart_item)
	{
		if(!class_exists('WC_Price_Calculator_Cart'))
			return "";
		 $calculator = new WC_Price_Calculator_Cart();
		 $measurements = $calculator->display_product_data_in_cart( array(), $cart_item );
		 $result = " ";
		 foreach((array)$measurements as $measurement)
		 {
			if(!$measurement['hidden'])
			 {
				 if($result != " ")
					 $result.= " - ";
				$result .= $measurement['name'].": ".$measurement['display'];
			 }
		 }
		return  $result;
	}
	public function wc_price_calulator_get_order_item_name( $order_item  )
	{
		if(!class_exists('WC_Price_Calculator_Cart') || !isset($order_item["measurement_data"]))
			return "";
		 $calculator = new WC_Price_Calculator_Cart();
		
		 $order_item['pricing_item_meta_data'] = unserialize($order_item["measurement_data"]);
		 $order_item['data'] =  wc_get_product( $order_item['product_id'] );
		// wcuf_var_dump($order_item);
		 $measurements = $calculator->display_product_data_in_cart( array(), $order_item);
		 // wcuf_var_dump($measurements);
		 $result = " ";
		 foreach((array)$measurements as $measurement)
		 {
			if(!$measurement['hidden'])
			 {
				 if($result != " ")
					 $result.= " - ";
				 $display = is_array($measurement['display']) ? $measurement['display']['value'] : $measurement['display'];
				$result .= " - ".$measurement['name'].": ". $display;
			 }
		 } 
		return  $result;
	}
	public function get_product_category_name($category_id, $default = false)
	{
		global $wcuf_wpml_helper;
		$category_id = $wcuf_wpml_helper->get_main_language_id($category_id, 'product_cat');
		$category = get_term( $category_id, 'product_cat' );
		return isset($category) ? $category->name : $default;
	}
	public function get_product_name($product_id, $default = false)
	{
		global $wcuf_wpml_helper;
		$product_id = $wcuf_wpml_helper->get_main_language_id($product_id, 'product');
		$readable_name  = $default;
		
		if($this->is_variation($product_id))
		{
			$readable_name = $this->get_variation_complete_name($product_id);
			$readable_name = isset($readable_name) && $readable_name != "" && $readable_name!= " " ? "#".$product_id." - ".$readable_name  : $default;
		}
		else
		{
			$product = new WC_Product($product_id);
			$readable_name = isset($product) ? $product->get_formatted_name() : $default;
		}
		return $readable_name; //isset($product) ? $product->get_formatted_name() : $default;
	}
	 public function ajax_get_products_partial_list()
	 {
		 $products = $this->get_product_list($_GET['product']);
		 echo json_encode( $products);
		 wp_die();
	 }
	  public function ajax_get_product_categories_partial_list()
	 {
		  $product_categories = $this->get_product_category_list($_GET['product_category']);
		 echo json_encode( $product_categories);
		 wp_die();
	 }
	 
	 public function get_product_list($search_string = null)
	 {
		global $wpdb, $wcuf_wpml_helper;
		 $query_string = "SELECT products.ID as id, products.post_parent as product_parent, products.post_title as product_name, product_meta.meta_value as product_sku
							 FROM {$wpdb->posts} AS products
							 LEFT JOIN {$wpdb->postmeta} AS product_meta ON product_meta.post_id = products.ID AND product_meta.meta_key = '_sku'
							 WHERE  (products.post_type = 'product' OR products.post_type = 'product_variation')
							";
		if($search_string)
				$query_string .=  " AND ( products.post_title LIKE '%{$search_string}%' OR product_meta.meta_value LIKE '%{$search_string}%' OR products.ID LIKE '%{$search_string}%' ) 
								   AND (products.post_type = 'product' OR products.post_type = 'product_variation') ";
		
		$query_string .=  " GROUP BY products.ID ";
		$result = $wpdb->get_results($query_string ) ;
		
		if(isset($result) && !empty($result))
			foreach($result as $index => $product)
				{
					if($product->product_parent != 0 )
					{
						$readable_name = $this->get_variation_complete_name($product->id);
						$result[$index]->product_name = $readable_name != false ? "<i>".__('Variation','woocommerce-files-upload')."</i> ".$readable_name : $result[$index]->product_name;
					}
				}
		
		
		//WPML
		if($wcuf_wpml_helper->wpml_is_active())
		{
			$product_ids = $variation_ids = array();
			foreach($result as $product)
			{
				if($product->product_parent == 0 )
					$product_ids[] = $product;
				else
					$variation_ids[] = $product;
			}
			//$result = $wcuf_wpml_helper->remove_translated_id($result, 'product', true);
			
			//Filter products
			if(!empty($product_ids))
				$product_ids = $wcuf_wpml_helper->remove_translated_id($product_ids, 'product', true);
			
			//Filter variations
			if(!empty($variation_ids))
				$variation_ids = $wcuf_wpml_helper->remove_translated_id($variation_ids, 'product', true);
			
			$result = array_merge($product_ids, $variation_ids);
		}
		
		return $result;
	 }
	 public function get_variation_complete_name($variation_id)
	 {
		$error = false;
		$variation = null;
		try
		{
			$variation = new WC_Product_Variation($variation_id);
		}
		catch(Exception $e){$error = true;}
		if($error) 
			try
			{
				$error = false;
				$variation = new WC_Product($variation_id);
				return $variation->get_title();
			}catch(Exception $e){$error = true;}
		
		if($error)
			return "";
		
		$product_name = $variation->get_title()." - ";	
		if($product_name == " - ")
			return false;
		$attributes_counter = 0;
		foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
		{
			
			if($attributes_counter > 0)
				$product_name .= ", ";
			$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
			
			$product_name .= " ".wc_attribute_label($meta_key).": ".$value;
			$attributes_counter++;
		}
		return $product_name;
	 }
	 public function get_variations($product_id)
	 {
		global $wpdb, $wcuf_wpml_helper;
		
		if($wcuf_wpml_helper->wpml_is_active())
			$product_id = $wcuf_wpml_helper->get_main_language_id($product_id);
		
		 $query = "SELECT products.ID, product_price.meta_value as price
		           FROM {$wpdb->posts} AS products 
		           INNER JOIN {$wpdb->postmeta} AS product_price ON product_price.post_id = products.ID
				   WHERE product_price.meta_key = '_price' 
				   AND	 products.post_parent = {$product_id} "; //_regular_price
		 $result =  $wpdb->get_results($query); 
		 return isset($result) ? $result : null;		 
	 }
	 public function is_variation($product_id)
	 {
		 global $wpdb, $wcuf_wpml_helper;
		
		 if($wcuf_wpml_helper->wpml_is_active())
			$product_id = $wcuf_wpml_helper->get_main_language_id($product_id, 'product_variation');
		
		$query = "SELECT products.post_parent as product_parent 
				  FROM {$wpdb->posts} AS products 
				  WHERE  products.ID = {$product_id} ";
				  
		 $result =  $wpdb->get_results($query); 
		 //wcuf_var_dump($result);
		 return isset($result) && isset($result[0]) && $result[0] != "" ? $result[0]->product_parent : 0;	
	 }
	 public function get_product_category_list($search_string = null)
	 {
		 global $wpdb, $wcuf_wpml_helper;
		  $query_string = "SELECT product_categories.term_id as id, product_categories.name as category_name
							 FROM {$wpdb->terms} AS product_categories
							 LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id = product_categories.term_id 							 						 	 
							 WHERE tax.taxonomy = 'product_cat' 
							 AND product_categories.slug <> 'uncategorized' 
							";
		 if($search_string)
					$query_string .=  " AND ( product_categories.name LIKE '%{$search_string}%' )";
			
		$query_string .=  " GROUP BY product_categories.term_id ";
		$result = $wpdb->get_results($query_string ) ;
		
		//WPML
		if($wcuf_wpml_helper->wpml_is_active())
		{
			$result = $wcuf_wpml_helper->remove_translated_id($result, 'product_cat', true);
		} 
		
		return $result;
	 }
	function has_a_required_upload_in_its_single_page($current_product, $check_if_upload_has_been_performed = false)
	{
		global $wcuf_option_model, $wcuf_wpml_helper,$sitepress, $wcuf_session_model;
		$fields_for_which_upload_has_not_been_performed = array();
		$product = array('product_id' => $current_product->id, 'variation_id' => empty($current_product->variation_id) ? 0 : $current_product->variation_id);		
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
				
		if(is_array($file_fields_groups))
		foreach($file_fields_groups as $file_fields)
		{ 
			$enable_for = isset($file_fields['enable_for']) ? $file_fields['enable_for']:'always';
			$display_on_checkout = isset($file_fields['display_on_checkout']) ? $file_fields['display_on_checkout']:false;
			$display_on_product = isset($file_fields['display_on_product']) ? $file_fields['display_on_product']:false;
			$display_on_cart = isset($file_fields['display_on_cart']) ? $file_fields['display_on_cart']:false;
			$disable_stacking = isset($file_fields['disable_stacking']) ? (bool)$file_fields['disable_stacking']:false;
			$display_on_product_before_adding_to_cart = isset($file_fields['display_on_product_before_adding_to_cart']) ? $file_fields['display_on_product_before_adding_to_cart']:false;
			$disable_stacking_for_variation = isset($file_fields['disable_stacking_for_variation']) && !$display_on_product_before_adding_to_cart  ? (bool)$file_fields['disable_stacking_for_variation']:false;
			$selected_categories = isset($file_fields['category_ids']) ? $file_fields['category_ids']:array();
			$display_product_fullname = isset($file_fields['full_name_display']) ? $file_fields['full_name_display']:true; //Usefull only for variable products
			$all_products_cats_ids = array();
			$products_for_which_stacking_is_disabled = array();
			$selected_products = isset($file_fields['products_ids']) ? $file_fields['products_ids']:array();
			$enable_upload_per_file = false;
			$required = isset($file_fields['required_on_checkout']) ? $file_fields['required_on_checkout']:false;
			$has_required_field = $enable_for == 'always' && $display_on_product && $required ? true:false;
			
			if(( ($display_on_product || $display_on_cart || $display_on_checkout) && $required) && (($enable_for === 'always' && $disable_stacking) || $enable_for !== 'always' && (count($selected_categories) > 0 || count($selected_products) > 0 )))
			{
				//WPML
				if($wcuf_wpml_helper->wpml_is_active())
				{
					$product['product_id'] = $wcuf_wpml_helper->get_main_language_id($product['product_id']);
					if($product['variation_id'] != 0)
							$product['variation_id'] = $wcuf_wpml_helper->get_main_language_id($product['variation_id'], 'product_variation');
				}
				
				//products
				$discard_field = false;
				if(!empty($selected_products) )
				{
					$variation_id = $is_variation = 0;
					
					foreach($selected_products as $product_id)
					{	
						$discard_field = false;
						$is_variation = $this->is_variation($product_id);
						$variation_id = $is_variation > 0 ? $product_id : 0 ;
						$product_id = $is_variation > 0 ? $is_variation : $product_id ;
						if( ($product_id == $product['product_id'] && ( $product['variation_id'] == 0 || $variation_id == 0 || $variation_id == $product['variation_id']) && ($enable_for === 'categories' || $enable_for === 'categories_children'))
							|| ( ($product_id != $product['product_id'] || ($is_variation > 0 && $product_id == $product['product_id'] && $variation_id != $product['variation_id'])) && ($enable_for === 'disable_categories' || $enable_for === 'disable_categories_children')) )
							{
								
								if($disable_stacking)
									$enable_upload_per_file = true;
								$has_required_field = true;
							}
							elseif( $enable_for !== 'always') 
									$discard_field = true;
						
					}
				}
				else if($enable_for === 'always' && $disable_stacking)
				{
					$enable_upload_per_file = true;
					$has_required_field = true;
				}
					
		
				//product categories
				$product_cats = wp_get_post_terms( $product["product_id"], 'product_cat' );
				$current_product_categories_ids = array();
				foreach($product_cats as $category)
				{
					$category_id = $category->term_id;
					
					if(!$disable_stacking)
						array_push($all_products_cats_ids, (string)$category_id);
					else
						array_push($current_product_categories_ids, (string)$category_id);
					
					//parent categories
					if($enable_for == "categories_children" || $enable_for == "disable_categories_children")
					{
						$parents =  get_ancestors( $category->term_id, 'product_cat' ); 
						foreach($parents as $parent_id)
						{
							$temp_category = $parent_id;
							if(!$disable_stacking)
								array_push($all_products_cats_ids, (string)$temp_category);
							else
								array_push($current_product_categories_ids, (string)$temp_category);
						}
					}
				}
				
				//Can enable upload for this product? (if stacking uploads are disabled)
				if( $disable_stacking && count($selected_categories) > 0)
				{
					if($enable_for === 'categories' || $enable_for === 'categories_children')
					{
						if(array_intersect($selected_categories, $current_product_categories_ids))
						{
							$has_required_field = true;
						}
					}
					elseif(!$discard_field)
					{
						if(!array_intersect($selected_categories, $current_product_categories_ids))
						{
							$has_required_field = true;
						}
						else $has_required_field = false;
					}	
				}
			
				//Cumulative ORDER catagories. If exists at least one product with an "enabled"/"disabled" category, upload field can be rendered
				if( !$disable_stacking && count($selected_categories) > 0)
					if($enable_for === 'categories' || $enable_for === 'categories_children')
					{  
						if(array_intersect($selected_categories, $all_products_cats_ids))
							$has_required_field = true;
					}
					elseif(!$discard_field)
					{ 
						if(!array_intersect($selected_categories, $all_products_cats_ids))
							$has_required_field = true;
						else $has_required_field = false;
					}						
			}
			
			if($has_required_field && $check_if_upload_has_been_performed)
			{
				//wc_measuere id?
				$uploaded_performed = $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']) == null && 
									  $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']."-".$product['variation_id']) == null  && 
									  $wcuf_session_model->get_item_data("wcufuploadedfile_".$file_fields['id']."-".$product['product_id']) == null 
									  ? false : true;
				if(!$uploaded_performed)
					$fields_for_which_upload_has_not_been_performed[] = array('upload_field_name'=>$file_fields['title'], 'product_name' => $this->get_product_name($product['variation_id'] != 0 ? $product['variation_id'] : $product['product_id']));	
			}
			else if(!$check_if_upload_has_been_performed && $has_required_field)
				return true;
		}
		
		if($check_if_upload_has_been_performed)
			return $fields_for_which_upload_has_not_been_performed;
		
		return false;
	}
}
?>
