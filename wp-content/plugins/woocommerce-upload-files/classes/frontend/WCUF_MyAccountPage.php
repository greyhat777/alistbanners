<?php 
class WCUF_MyAccountPage
{
	public function __construct()
	{
		global $wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();
		$theme_version = wcuf_get_file_version( get_template_directory() . '/woocommerce/myaccount/my-account.php' );
		try{
			$wc_version = wcuf_get_woo_version_number();
		}catch(Exception $e){}
		
		if($all_options['display_last_order_upload_fields_in_my_account_page'] == 'yes')
			//add_action( $all_options['my_account_page_positioning'], array( &$this, 'my_account_page_positioning' ) );
			add_action( 'woocommerce_before_my_account', array( &$this, 'my_account_page_positioning' ) );
		
	}
	public function my_account_page_positioning($order = null)
	{
		/* $order_id = !is_numeric($order) ? $order->id : $order;
		$order = !is_numeric($order) ? $order : new WC_Order($order_id); */
		
		echo do_shortcode('[wcuf_upload_form_last_order]');
	}
}
?>