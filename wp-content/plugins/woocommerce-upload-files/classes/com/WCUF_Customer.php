<?php 
class WCUF_Customer
{
 public function __construct(){} 	
 public function get_current_customer_last_order_id()
 {
	 $current_customer_id = get_current_user_id();
	 
	 if($current_customer_id == 0)
		 return 0;
	 
	$last_customer_order = get_posts( array(
        'numberposts' => 1,
        'meta_value'  => $current_customer_id,
		'meta_key'  => "_customer_user",
        'post_type'   => 'shop_order',
		'post_status' => array_keys( wc_get_order_statuses() ),
    ) ); 
	
	return isset($last_customer_order) && !empty($last_customer_order) ? $last_customer_order[0]->ID : 0;
 }
}
?>