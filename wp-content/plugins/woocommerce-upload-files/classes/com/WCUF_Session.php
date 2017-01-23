<?php 
class WCUF_Session
{
	public function __construct()
	{
		//add_filter( 'wc_session_expiring', array( &$this, 'session_expiring' ), 10 ,1);
		add_action( 'init', array( &$this, 'manage_session' ));
		add_action('wp_logout', array( &$this, 'clear_session_data' ));
	}
	public function manage_session() 
	{
		global $wcuf_file_model;
		$time = $_SERVER['REQUEST_TIME'];
		$timeout_duration = 1200; //1200: 20 min
		//$timeout_duration = 10;
		
		if(!isset($_SESSION)) @session_start();
		/* wcuf_var_dump($_SESSION['LAST_ACTIVITY']);
		wcuf_var_dump(time());
		wcuf_var_dump("****************"); */
		if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
		  /* $this->remove_item_data();
		  $this->remove_item_data(null, false);
		  session_unset();     
		  session_destroy();
		  session_start();   */  
		  $this->clear_session_data();
		}
		
		$wcuf_file_model->delete_expired_sessions_files();
			
		$_SESSION['LAST_ACTIVITY'] = $time;
	}
	private function update_session()
	{
		//if(!isset($_SESSION)) session_start();
		$before = $_SESSION['LAST_ACTIVITY'];
		$_SESSION['LAST_ACTIVITY'] = time();
		/* wcuf_var_dump($before);
		wcuf_var_dump($_SESSION['LAST_ACTIVITY']);
		wcuf_var_dump($_SESSION['LAST_ACTIVITY'] - $before); */
	}
	public function clear_session_data( )
	{
		$this->remove_item_data();
		$this->remove_item_data(null, false);
		@session_unset();     
		@session_destroy();
		@session_start(); 
	}
	/*Format:
		array(2) {
	  ["wcufuploadedfile_3-59-60"]=>
	  array(5) {
		["name"]=>
		string(9) "test2.pdf"
		["type"]=>
		string(22) "application/x-download"
		["tmp_name"]=>
		string(113) "/var/.../wp-content/uploads/wcuf/tmp/34225430759"
		["error"]=>
		int(0)
		["size"]=>
		int(85996)
	  }
  */
	function set_item_data(  $key, $value, $file_already_moved = false, $is_order_details = false, $num_uploaded_files = 1, $ID3_info = null) 
	{
		global $wcuf_file_model;
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		
		//wcuf_var_dump(WC()->session);
		/* if(!WC()->session->_has_cookie)
			WC()->session->set_customer_session_cookie(true);  */
		//$data = (array)WC()->session->get( $session_key );
		
		$this->update_session();
		if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key]; 
		
		
		if ( empty( /* $data[$cart_item_key] */ $data[$key] ) ) 
		{
			$data[$key] = array();
		}
		else
		{
			$wcuf_file_model->delete_temp_file($data[$key]['tmp_name']);
		}
		if(!$file_already_moved)
			$value['tmp_name'] = $wcuf_file_model->move_temp_file($value['tmp_name']);
		 
		$value['title'] = $_POST['title'];
		$value['num_uploaded_files'] = $num_uploaded_files;
		$value['user_feedback'] = isset($_POST['user_feedback']) && $_POST['user_feedback'] != 'undefined' ? $_POST['user_feedback']:"";
		$value['ID3_info'] = isset($ID3_info) && !empty($ID3_info) ? $ID3_info: "none";
		$data[$key] = $value;
		//wcuf_var_dump($data); 
		//WC()->session->set( $session_key, $data );
		$_SESSION[$session_key] = $data;
	}
	
	function get_item_data( $key = null, $default = null, $is_order_details = false ) 
	{
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		//$data = (array)WC()->session->get( $session_key ); 
		if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key]; 
		
		if ( $key == null ) 
			return isset($data) && !empty($data) ? $data : $default;
		else
			return empty( $data[$key] ) ? $default : $data[$key];
	}
	function remove_data_by_product_ids($item)
	{
		$id = "-".$item['product_id'];
		if($item['variation_id'] !=0)
			$id .= "-".$item['variation_id'];
		
		$all_data = $this->get_item_data();
		if(isset($all_data))
		{
			foreach($all_data as $fieldname_id => $item)
			{
				if($this->endsWith($fieldname_id, $id) || $this->contains($fieldname_id, $id."-"))
					$this->remove_item_data($fieldname_id);
			}
		}
	}
	function remove_item_data( $key = null, $is_order_details = false ) 
	{
		global $wcuf_file_model;
		$session_key = !$is_order_details ? '_wcuf_temp_uploads' : '_wcuf_temp_uploads_on_order_details_page';
		//$data = (array)WC()->session->get( $session_key );
		if(!isset($_SESSION[$session_key]))
			$_SESSION[$session_key] = array();
		$data = $_SESSION[$session_key]; 
		
		// If no item is specified, delete *all* item data. This happens when we clear the cart (eg, completed checkout)
		if ( $key == null ) 
		{
			if(isset($data))
				foreach((array)$data as $temp_file_data)
					$wcuf_file_model->delete_temp_file($temp_file_data['tmp_name']);
			//WC()->session->set( $session_key, array() );
			 $_SESSION[$session_key] = array() ;
			return;
		}
		// If item is specified, but no data exists, just return
		if ( !isset( $data[$key] ) ) 
		{
			return;
		}
		else 
		{
			$wcuf_file_model->delete_temp_file($data[$key]['tmp_name']);
			unset( $data[$key] );
		}
		//WC()->session->set( $session_key, $data );
		$_SESSION[$session_key] = $data;
	} 
	function endsWith($haystack, $needle) 
	{
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
	function contains($haystack, $needle) 
	{
		return $needle === "" || (strpos($needle, $haystack) !== false);
	}
}
?>