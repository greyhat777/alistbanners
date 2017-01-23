<?php
class WCUF_File
{
	var $current_order;
	var $email_sender;
	var $file_zip_name = 'multiple_files.zip';
	public function __construct()
	{
		add_action( 'before_delete_post', array( &$this, 'delete_all_order_uploads' ), 10 );
		//Ajax
		add_action( 'wp_ajax_upload_file_during_checkout_or_product_page', array( &$this, 'ajax_save_file_on_session' ));
		add_action( 'wp_ajax_nopriv_upload_file_during_checkout_or_product_page', array( &$this, 'ajax_save_file_on_session' ));
		
		add_action( 'wp_ajax_delete_file_during_checkout_or_product_page', array( &$this, 'ajax_delete_file_from_session' ));
		add_action( 'wp_ajax_nopriv_delete_file_during_checkout_or_product_page', array( &$this, 'ajax_delete_file_from_session' ));
		
		add_action( 'wp_ajax_save_uploaded_files_on_order_detail_page', array( &$this, 'ajax_save_file_uploaded_from_order_detail_page' ));
		add_action( 'wp_ajax_nopriv_save_uploaded_files_on_order_detail_page', array( &$this, 'ajax_save_file_uploaded_from_order_detail_page' ));
		
		add_action( 'wp_ajax_upload_file_on_order_detail_page', array( &$this, 'ajax_upload_file_on_order_detail_page' ));
		add_action( 'wp_ajax_nopriv_upload_file_on_order_detail_page', array( &$this, 'ajax_upload_file_on_order_detail_page' ));
		
		add_action( 'wp_ajax_delete_file_on_order_detail_page', array( &$this, 'ajax_delete_file_on_order_detail_page' ));
		add_action( 'wp_ajax_nopriv_delete_file_on_order_detail_page', array( &$this, 'ajax_delete_file_on_order_detail_page' ));
		
		add_action('init', array( &$this, 'get_image' ));
		add_action('init', array( &$this, 'get_file_in_zip' ));
		
		
	}
	function ajax_delete_file_on_order_detail_page()
	{
		if(!isset($_POST) || !isset($_POST['is_temp']) || !isset($_POST['order_id']) || $_POST['order_id'] == 0)
			return;
		
		if($_POST['is_temp'] == 'no')
		{
			global $wcuf_option_model;
			$file_order_metadata =$wcuf_option_model->get_order_uploaded_files_meta_data($_POST['order_id']);
			$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0];
			
			$this->delete_file($_POST['id'], $file_order_metadata, $_POST['order_id']);
		}
		else
		{
			$this->ajax_delete_file_from_session(true);
		}
		wp_die();
	}
	function ajax_delete_file_from_session($is_order_detail_page = false)
	{
		global $wcuf_session_model,$wcuf_cart_model ;
		$wcuf_upload_unique_name = isset($_POST['id']) ? $_POST['id']:null;
		if(isset($wcuf_upload_unique_name))
		{
			//wcuf_var_dump($wcuf_upload_unique_name);
			$wcuf_session_model->remove_item_data($wcuf_upload_unique_name, $is_order_detail_page);
		}
		wp_die();
	}
	function ajax_save_file_uploaded_from_order_detail_page()
	{
		global $wcuf_option_model, $wcuf_file_model, $wcuf_session_model;
		$temp_uploads = $wcuf_session_model->get_item_data(null,null,true);
		if(!isset($_POST) || $_POST['order_id'] == 0)
			return;
		
		$order_id = $_POST['order_id'];
		
		if(!empty($temp_uploads))
		{
			$order = new WC_Order($order_id);
			$file_fields_groups =  $wcuf_option_model->get_fields_meta_data();
			$file_order_metadata = $wcuf_option_model->get_order_uploaded_files_meta_data($order_id);
			$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0];
			$file_order_metadata = $wcuf_file_model->upload_files($order, $file_order_metadata, $file_fields_groups, $temp_uploads);
		}
		$wcuf_session_model->remove_item_data();
		wp_die();
	}
	function ajax_upload_file_on_order_detail_page()
	{
		$this->ajax_save_file_on_session(true);
		/* if(!isset($_POST) || !isset($_POST['order_id']))
			return;
		
		global $wcuf_option_model;
		$tmp_files = array();
		if(count($_FILES) > 1 && class_exists('ZipArchive'))
		{
			$zip = new ZipArchive();
			$filename = $this->create_temp_file_name();
			$unique_key = "";
			
			if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
				wp_die();
			}
			foreach($_FILES as $wcuf_upload_unique_name => $data)
			{
				if($unique_key == "")
					$unique_key = $wcuf_upload_unique_name;
				$zip->addFile($data['tmp_name'], $data['name']);
			}
			$tmp_files[$unique_key] = array('tmp_name' => $filename, 'name'=>'multiple_files.zip', 'title' => $_POST['title']);
			$zip->close();
		}
		else
			foreach($_FILES as $wcuf_upload_unique_name => $data)
			{
				$data['title'] = $_POST['title'];
				$tmp_files[$wcuf_upload_unique_name] = $data;
				break;
			}
		
		$_FILES = null;
		$order = new WC_Order($_POST['order_id']);
		$file_order_metadata =$wcuf_option_model->get_order_uploaded_files_meta_data($_POST['order_id']);
		$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0];
		$file_fields_groups = $wcuf_option_model->get_fields_meta_data();
		$file_order_metadata = $this->upload_files($order, $file_order_metadata, $file_fields_groups, $tmp_files);
		wp_die(); */
	}
	function ajax_save_file_on_session($is_order_detail_page = false)
	{
		if(!isset($_POST))
			return;
		
		global $wcuf_session_model,$wcuf_cart_model;
		/*Format 
		//$_POST
		array(1) {
			  ["action"]=>
			  string(27) "upload_file_during_checkout"
			}
		//$_FILES
		array(1) {
		  ["wcufuploadedfile_58"]=>
		  array(5) {
			["name"]=>
			string(15) "Snake_River.jpg"
			["type"]=>
			string(10) "image/jpeg"
			["tmp_name"]=>
			string(26) "/var/zpanel/temp/php7XJBgQ"
			["error"]=>
			int(0)
			["size"]=>
			int(5245329)
		  }
			}
		*/
		$unique_key = "";
		$num_files = 0;
		$upload_field_name = $_POST['title'];
		$getID3 = new getID3();
		$ID3_info = array();
		if(count($_FILES) > 1 && class_exists('ZipArchive'))
		{
			$zip = new ZipArchive();
			$filename = $this->create_temp_file_name();
			$file_names = array();
			$file_quantity = array();
			if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
				wp_die();
			}
			foreach($_FILES as $wcuf_upload_unique_name => $data)
			{
				if($unique_key == "")
					$unique_key = $wcuf_upload_unique_name;
				$zip->addFile($data['tmp_name'], $data['name']);
				$file_names[] =  $data['name'];
				$file_quantity[] =  $_POST['quantity_'.$num_files];
				//ID3 Info
				try{
					$file_id3 = $getID3->analyze($data['tmp_name']);
					//playtime_seconds
					//playtime_string
					
					if( (isset($file_id3['video']) || isset($file_id3['audio'])) && isset($file_id3['playtime_string']) )
						$ID3_info[] = array( 'file_name' => $data['name'],
											'quantity' => $file_quantity[$num_files] ,
											'playtime_seconds' => isset($file_id3['playtime_seconds']) ? $file_id3['playtime_seconds'] : 'none',
											'playtime_string' => isset($file_id3['playtime_string']) ? $file_id3['playtime_string'] : 'none');	
					$num_files++;
				}catch(Exception $e){}
			}
			$data = array('tmp_name' => $filename, 'name'=>$file_names, 'quantity' => $file_quantity/* 'multiple_files.zip' */);
			$zip->close();
			
			$wcuf_session_model->set_item_data($unique_key, $data, true, $is_order_detail_page, $num_files, $ID3_info);
		}
		else
		{
			foreach($_FILES as $wcuf_upload_unique_name => $data)
			{
				/* if($unique_key == "")
					$unique_key = $wcuf_upload_unique_name; */
				if($num_files > 0) //Force multiple file to not be processed in case of error
					break;
					
				$data['quantity'] =  $_POST['quantity_0'];
				//ID3 info
				try{
					$file_id3 = $getID3->analyze($data['tmp_name']);
					if( (isset($file_id3['video']) || isset($file_id3['audio'])) && isset($file_id3['playtime_string']))
						$ID3_info[] = array('file_name' => $data['name'],
											'quantity' => $data['quantity'],
											'playtime_seconds' => isset($file_id3['playtime_seconds']) ? $file_id3['playtime_seconds'] : 'none',
											'playtime_string' => isset($file_id3['playtime_string']) ? $file_id3['playtime_string'] : 'none');
											
					
				}catch(Exception $e){}
				$wcuf_session_model->set_item_data($wcuf_upload_unique_name, $data, false, $is_order_detail_page, 1, $ID3_info);
				$num_files++;
			}
		}
		
		wp_die();
	}
	public function get_product_ids_and_field_id_by_file_id($temp_upload_id)
	{
		list($fieldname, $field_id_and_product_id) = explode("_", $temp_upload_id );
		$ids = explode("-", $field_id_and_product_id ); //0 => $field_id, 1 => $product_id, 2 => $variation_id 3=> file title hash (only if 2 exists)
		$variant_id = isset($ids[3]) || (isset($ids[2]) && is_numeric($ids[2])) ? $ids[2] : 0;
		$unique_product_name_hash = isset($ids[3]) ? $ids[3] : "";
		if(isset($ids[2]) && !is_numeric($ids[2]))
			$unique_product_name_hash = $ids[2];
		
		return array('field_id' => $ids[0], 'product_id' => isset($ids[1]) ? $ids[1] : null, 'variant_id'=>$variant_id, 'unique_product_name_hash'=>$unique_product_name_hash, 'fieldname'=>$fieldname);
	}
	
	public function get_preview_image_html($file_full_path, $file_name, $is_zip, $order_id)
	{
		global $wcuf_option_model;
		$all_options = $wcuf_option_model->get_all_options();
		$image_preview_width = $all_options['image_preview_width'];
		$image_preview_height = $all_options['image_preview_height'];
		$image = false;
		$file_name_real_name = basename($file_full_path);
		if($is_zip)
		{
			if(class_exists('ZipArchive'))
			{
				//$file_name_real_name = $this->file_zip_name;
				$z = new ZipArchive();
				if ($z->open($file_full_path)) 
				{
					$im_string = $z->getFromName($file_name);
					$image = @imagecreatefromstring($im_string);
					$z->close();
				}
			}
			else return "";
			
			if($image === false)
				return "";
		}
		else		
			$image = @is_array(getimagesize($file_full_path)) ? true : false;
		
		//wcuf_var_dump($order_id);
		$is_zip = $is_zip ? "true": "false";
		return $image !== false ? '<img class="wcuf_file_preview_list_item_image" width="'.$image_preview_width.'" height="'.$image_preview_height.'" src="'.get_site_url().'?wcuf_file_name='.$file_name_real_name.'&wcuf_image_name='.$file_name.'&wcuf_is_zip='.$is_zip.'&wcuf_order_id='.$order_id.'"></img>' : "";
	}
	
	private function get_temp_dir_path($order_id)
	{
		$upload_dir = wp_upload_dir();
		$temp_dir = $upload_dir['basedir']. '/wcuf/';
		$temp_dir .= isset($order_id) && $order_id !=0 ? $order_id.'/': 'tmp/';
		
		return $temp_dir;
	}
	public function get_file_in_zip()
	{
		if(!isset($_GET['wcuf_zip_name']) || !isset($_GET['wcuf_single_file_name']) || !isset($_GET['wcuf_order_id']))
			return;
		
		if(!current_user_can( 'manage_options' ) || !current_user_can( 'manage_woocommerce' ))
		{
			_e('You are not authorized', 'woocommerce-files-upload');
			return;
		}
		
		$path = $_GET['wcuf_zip_name'];
		$single_file_name = $_GET['wcuf_single_file_name'];
		$temp_dir = $this->get_temp_dir_path($_GET['wcuf_order_id']);
		
		$z = new ZipArchive();
		if ($z->open($temp_dir.$path)) {
			$file_string = $z->getFromName($single_file_name);			
			$z->close();	
			header("Content-length: ".strlen($file_string));
			//header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename=".$single_file_name.";" );
			header('Content-Transfer-Encoding: chunked');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Description: File Transfer');
			header('Content-Type: application/force-download');
			echo $file_string;
		}
		else
		{
			_e('Error opening the file', 'woocommerce-files-upload');
			return;
		}
		wp_die();
	}
	public function get_image()
	{
		if(!isset($_GET['wcuf_file_name']) || !isset($_GET['wcuf_image_name']) || !isset($_GET['wcuf_is_zip']))
			return;
		
		/* $upload_dir = wp_upload_dir();
		$temp_dir = $upload_dir['basedir']. '/wcuf/';
		$temp_dir .= isset($_GET['wcuf_order_id']) && $_GET['wcuf_order_id'] !=0 ? $_GET['wcuf_order_id'].'/': 'tmp/'; */
		$temp_dir = $this->get_temp_dir_path(isset($_GET['wcuf_order_id']) ? $_GET['wcuf_order_id'] : null);
		
		if($_GET['wcuf_is_zip'] === "true")
		{
			if(class_exists('ZipArchive'))
			{
				$z = new ZipArchive();
				if ($z->open($temp_dir.$_GET['wcuf_file_name'])) 
				{
					$im_string = $z->getFromName($_GET['wcuf_image_name']);
					$im = imagecreatefromstring($im_string);
					header('Content-Type: image/png');
					$image_result = imagepng($im);
					imagedestroy($im);
					$z->close();
				
					/* $im_string = $z->getFromName($_GET['wcuf_image_name']);
					$im = imagecreatefromstring($im_string);
					$mime_type = getimagesizefromstring ($im_string);
					$mime_type = $mime_type['mime'];
					switch($mime_type)
					{
						case "image/jpeg":
								header('Content-Type: image/jpeg');
								$im = imagecreatefromjpeg($im_string);
								$image_result = imagejpeg($im);
								imagedestroy($im);
								break;
							case "image/gif":
								header('Content-Type: image/gif');
								$im = @imagecreatefromgif($im_string);
								$image_result = imagegif($im);
								imagedestroy($im);
								break;
							case "image/png":
								header('Content-Type: image/png');
								$im = @imagecreatefrompng($im_string);
								$image_result = imagepng($im);
								imagedestroy($im);
								break;
							 //case "image/x-ms-bmp":
								//$im = imagecreatefromwbmp($path); //png file
								//break; 
							default: 
								$im=false;
								break; 
					}
					$z->close();*/
				}
			}
		}
		else
		{
			
			$path = $temp_dir.$_GET['wcuf_file_name'];
			$fileName = basename($path);
			//New
			$size = getimagesize($path);
			//wcuf_var_dump($size["mime"]);
			switch($size["mime"])
			{
					case "image/jpeg":
						header('Content-Type: image/jpeg');
						$im = imagecreatefromjpeg($path); //jpeg file
						imagejpeg($im);
						imagedestroy($im);
						break;
					case "image/gif":
						header('Content-Type: image/gif');
						$im = imagecreatefromgif($path); //gif file
						imagegif($im);
						imagedestroy($im);
						break;
					case "image/png":
						header('Content-Type: image/png');
						$im = imagecreatefrompng($path); //png file
						imagepng($im);
						imagedestroy($im);
						break;
					 //case "image/x-ms-bmp":
						//$im = imagecreatefromwbmp($path); //png file
						//break; 
					default: 
						$im=false;
						break;
			} 
			
			//Old
			/* $size = filesize($path);
			$metadata = getimagesize($path);
			$file_type = $metadata["mime"];
			header("Content-length: ".$size);
			//header("Content-type: application/octet-stream");
			header("Content-type: ".$file_type);
			header("Content-disposition: attachment; filename=".$fileName.";" );
			
			//header('Content-Transfer-Encoding: binary');
			header('Content-Transfer-Encoding: chunked');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			//header("Content-Type: application/download");
			header('Content-Description: File Transfer');
			//header('Content-Type: application/force-download');
			//echo $content;
			if ($fd = fopen ($path, "r")) 
			{

					set_time_limit(0);
					ini_set('memory_limit', '1024M');
				
				if (ob_get_contents()) ob_clean();
				while(!feof($fd)) {
					echo fread($fd, 4096);
				}   
				flush();
				ob_end_flush();
				try{
					fclose($fd);
				}catch(Exception $e){}
			} */
		}
		die();
	}
	
	public function wcup_ovveride_upload_directory( $dir ) 
	{ 
		return array(
			'path'   => $dir['basedir'] . '/wcuf/'.$this->current_order->id,//get_current_user_id(),
			'url'    => $dir['baseurl'] . '/wcuf/'.$this->current_order->id,//get_current_user_id(),
			'subdir' => '/wcuf/'.$this->current_order->id,//get_current_user_id(),
		) + $dir;
	}
	public function generate_unique_file_name($dir, $name, $ext = "")
	{
		global $wcuf_option_model;
		return  $wcuf_option_model->remove_file_name_prefix() == 'no' || $name == $this->file_zip_name ? rand(0,100000)."_".$name.$ext : $name.$ext;
	}
	public function upload_files($order,$file_order_metadata, $options, $temp_uploaded = null)
	{
		$order_id = $order->id ;	
		//var_dump($_FILES);
		 //var_dump($_POST);
		 
		 //$_POST['wcuf']
		 //array(1) { ["wcuf"]=> array(3) { [0]=> array(2) { ["title"]=> string(7) "Title 1" ["id"]=> string(1) "0" } [1]=> array(2) { ["title"]=> string(7) "Title 2" ["id"]=> string(1) "1" } [4]=> array(2) { ["title"]=> string(7) "Ttile 4" ["id"]=> string(1) "4" } } } 
		 
		 //foreach ogni file, si recupera lo id, e si salva nei meta titolo e path del file
		 //wp_handle_upload
		 
		 //$mail_sent = false;
		 if(isset($_FILES) && isset($temp_uploaded))
			$files_array = array_merge($_FILES, $temp_uploaded );
		 else
		  $files_array = isset($temp_uploaded) ? $temp_uploaded : $_FILES;
	  
		 $upload_dir = wp_upload_dir();
		if (!file_exists($upload_dir['basedir']."/wcuf")) 
				mkdir($upload_dir['basedir']."/wcuf", 0775, true);
			
		 $links_to_notify_via_mail = array();
		 $links_to_attach_to_mail = array();
		 foreach($files_array as $fieldname_id => $file_data)
		 {
			 list($fieldname, $id) = explode("_", $fieldname_id );
			 if($file_data["name"] != '' && file_exists($file_data['tmp_name']))
			 {
				$this->current_order = $order;
				
				if(!isset($temp_uploaded))
				{
					$upload_overrides = array( 'test_form' => false, 'unique_filename_callback' => array( $this , 'generate_unique_file_name') );
					add_filter( 'upload_dir', array( &$this,'wcup_ovveride_upload_directory' ));
					$movefile = wp_handle_upload( $file_data, $upload_overrides );
					remove_filter( 'upload_dir', array( &$this,'wcup_ovveride_upload_directory' ));
				}
				else
				{
					$movefile = array();
					$file_name = $this->generate_unique_file_name('none', is_array($file_data['name']) ? $this->file_zip_name : $file_data['name']);
					if (!file_exists($upload_dir['basedir']."/wcuf/".$order_id)) 
						mkdir($upload_dir['basedir']."/wcuf/".$order_id, 0775, true);
			
					@rename($file_data['tmp_name'], $upload_dir['basedir'] . '/wcuf/'.$order_id.'/'.$file_name);
					$movefile['file'] = $upload_dir['basedir'] . '/wcuf/'.$order_id.'/'.$file_name;
					$movefile['url'] = $upload_dir['baseurl'] . '/wcuf/'.$order_id.'/'.$file_name;
					$movefile['name'] = $file_data['name'];
				}
				
				if( !file_exists ($upload_dir['basedir'].'/wcuf/index.html'))
					touch ($upload_dir['basedir'].'/wcuf/index.html');
				/* wcuf_var_dump($file_data);	
				wcuf_var_dump($movefile);	 */
				if ( $movefile && !isset( $movefile['error'] ) ) 
				{
					//echo "File is valid, and was successfully uploaded.\n";
					//var_dump( $movefile); //['url'], ['file']
					/* if(!isset($file_order_metadata[$id]))
							$file_order_metadata[$id] = array(); */
					
					if( !file_exists ($upload_dir['basedir'].'/wcuf/'.$order_id.'/index.html'))
						touch ($upload_dir['basedir'].'/wcuf/'.$order_id.'/index.html');
					
					$posted_user_feedback = isset($_POST['wcuf'][$id]['user_feedback']) ? $_POST['wcuf'][$id]['user_feedback'] : "";
					$file_order_metadata[$id]['absolute_path'] = $movefile['file'];
					$file_order_metadata[$id]['url'] = $movefile['url'];
					$file_order_metadata[$id]['title'] = !isset($_POST['wcuf'][$id]['title']) ? $file_data['title'] : $_POST['wcuf'][$id]['title'];
					$file_order_metadata[$id]['num_uploaded_files'] = $file_data['num_uploaded_files'];
					$file_order_metadata[$id]['id'] = $id;
					$file_order_metadata[$id]['user_feedback'] =  !isset($_POST['wcuf'][$id]['user_feedback']) ? $file_data['user_feedback']: $posted_user_feedback;
					$file_order_metadata[$id]['original_filename'] = $movefile['name'];
					$file_order_metadata[$id]['ID3_info'] = $file_data['ID3_info'];
					$file_order_metadata[$id]['quantity'] = $file_data['quantity'];
					
					$original_option_id = $id;
					$needle = strpos($original_option_id , "-");
					if($needle !== false)
						$original_option_id = substr($original_option_id, 0, $needle);
					foreach($options as $option)
					{
						if(/* !$mail_sent &&  */$option['id'] == $original_option_id && $option['notify_admin'] )
						{
							$recipients = $option['notifications_recipients'] != "" ? $option['notifications_recipients'] : get_option( 'admin_email' );
							if(!isset($links_to_notify_via_mail[$recipients]))
								$links_to_notify_via_mail[$recipients] = array();
							array_push($links_to_notify_via_mail[$recipients], array('title' => $file_order_metadata[$id]['title'], 'url'=> $file_order_metadata[$id]['url'], 'feedback' => $file_order_metadata[$id]['user_feedback']));
						
							if($option['notify_attach_to_admin_email'])
							{
								if(!isset($links_to_attach_to_mail[$recipients]))
									$links_to_attach_to_mail[$recipients] = array();
								
								array_push($links_to_attach_to_mail[$recipients], $file_order_metadata[$id]['absolute_path'] );
							}						
						}
					}
					 
				}
				else
				{
					
					//var_dump($movefile['error']);
				}

			 }
		 }
		 //Notification via mail
		if(count($links_to_notify_via_mail) > 0)
		{
			global $wcuf_wpml_helper;
			$wcuf_wpml_helper->switch_to_admin_default_lang();
			$this->email_sender = new WCUF_Email();
			$this->email_sender->trigger($links_to_notify_via_mail, $order, $links_to_attach_to_mail );	
			$wcuf_wpml_helper->restore_from_admin_default_lang();
		}
		//Save upload fields data
		update_post_meta( $order_id, '_wcst_uploaded_files_meta', $file_order_metadata);
		return $file_order_metadata;
	}
	//NO
	public function upload_and_decode_files($order,$file_order_metadata, $options)
	{
		$order_id = $order->id ;	
		 $links_to_notify_via_mail = array();
		 $links_to_attach_to_mail = array();
		 foreach($_POST['wcuf-encoded-file'] as $id => $file_data)
		 {
			$this->current_order = $order;
			
			//decode data
			$upload_dir = wp_upload_dir();
			$upload_complete_dir = $upload_dir['basedir']. '/wcuf/'.$order->id.'/';
			if (!file_exists($upload_complete_dir)) 
					mkdir($upload_complete_dir, 0775, true);
				
			$unique_file_name = $this->generate_unique_file_name(null, $_POST['wcuf'][$id]['file_name']);
			$ifp = fopen($upload_complete_dir.$unique_file_name, "w"); 
			fwrite($ifp, base64_decode($file_data)); 
			fclose($ifp); 
		
			if( !file_exists ($upload_dir['basedir'].'/wcuf/index.html'))
				touch ($upload_dir['basedir'].'/wcuf/index.html');
				
			
			if( !file_exists ($upload_dir['basedir'].'/wcuf/'.$order_id.'/index.html'))
				touch ($upload_dir['basedir'].'/wcuf/'.$order_id.'/index.html');
			
			$file_order_metadata[$id]['absolute_path'] = $upload_complete_dir.$unique_file_name;
			$file_order_metadata[$id]['url'] = $upload_dir['baseurl'].'/wcuf/'.$order->id.'/'.$unique_file_name;
			$file_order_metadata[$id]['title'] = $_POST['wcuf'][$id]['title'];
			$file_order_metadata[$id]['id'] = $id;
			$original_option_id = $id;
			$needle = strpos($original_option_id , "-");
			if($needle !== false)
				$original_option_id = substr($original_option_id, 0, $needle);
			foreach($options as $option)
			{
				if(/* !$mail_sent &&  */$option['id'] == $original_option_id && $option['notify_admin'] )
				{
					$recipients = $option['notifications_recipients'] != "" ? $option['notifications_recipients'] : get_option( 'admin_email' );
					if(!isset($links_to_notify_via_mail[$recipients]))
						$links_to_notify_via_mail[$recipients] = array();
					
					array_push($links_to_notify_via_mail[$recipients], array('title' => $file_order_metadata[$id]['title'], 'url'=> $file_order_metadata[$id]['url']));
				
					if($option['notify_attach_to_admin_email'])
					{
						if(!isset($links_to_attach_to_mail[$recipients]))
							$links_to_attach_to_mail[$recipients] = array();
						array_push($links_to_attach_to_mail[$recipients], $file_order_metadata[$id]['absolute_path'] );
					}
				}
			}
				 
			
		 }
		 //Notification via mail
		if(count($links_to_notify_via_mail) > 0)
		{
			$this->email_sender = new WCUF_Email();
			$this->email_sender->trigger($links_to_notify_via_mail, $order, $links_to_attach_to_mail );	
		}
		update_post_meta( $order_id, '_wcst_uploaded_files_meta', $file_order_metadata);
		return $file_order_metadata;
	}
	public function move_temp_file($file_tmp_name)
	{
		/* $upload_dir = wp_upload_dir();
		$temp_dir = $upload_dir['basedir']. '/wcuf/tmp/';
		if (!file_exists($temp_dir)) 
				mkdir($temp_dir, 0775, true);
		if( !file_exists ($temp_dir.'index.html'))
			touch ($temp_dir.'index.html');
		
		$absolute_path = $temp_dir.rand(0,100000); */		
		$absolute_path = $this->create_temp_file_name(); 	
		move_uploaded_file($file_tmp_name, $absolute_path);
		return $absolute_path;
	}
	public function create_temp_file_name()
	{
		$upload_dir = wp_upload_dir();
		$temp_dir = $upload_dir['basedir']. '/wcuf/tmp/';
		if (!file_exists($temp_dir)) 
				mkdir($temp_dir, 0775, true);
		if( !file_exists ($temp_dir.'index.html'))
			touch ($temp_dir.'index.html');
		
		$absolute_path = $temp_dir.rand(0,100000);	
		return $absolute_path;
	}
	public function delete_temp_file($path)
	{
		try{
			@unlink($path);
		}catch(Exception $e){};
	}
	public function delete_file($id, $file_order_metadata, $order_id)
	{
		/* var_dump("delete ".$file_order_metadata[$id]['absolute_path']);*/
		try{
			@unlink($file_order_metadata[$id]['absolute_path']);
		}catch(Exception $e){};
		unset($file_order_metadata[$id]);
		update_post_meta( $order_id, '_wcst_uploaded_files_meta', $file_order_metadata);
		return $file_order_metadata; 
	}	
	public function delete_expired_sessions_files()
	{
		$upload_dir = wp_upload_dir();
		$temp_dir = $upload_dir['basedir']. '/wcuf/tmp/';

		//glob($temp_dir."*.jpg")
		//glob($temp_dir."*.txt")
		//glob($temp_dir."*.{jpg,JPG,jpeg,JPEG,png,PNG}")
		$files = glob($temp_dir."*");
		if(is_array($files) && count($files) > 0)
			foreach ($files as $file) //glob($temp_dir."*") 
			{
				if (basename($file) != "index.html" && @filemtime($file) < time() - 86400) //86400:24h; 10800:3h, 1800: 30 min 
				{
					try{
						@unlink($file);
					}catch(Exception $e){};
				}
			}
	}
	public function delete_all_order_uploads($order_id)
	{
		$post = get_post($order_id);
		if ($post->post_type == 'shop_order')
		{
			$file_order_metadata = get_post_meta($order_id, '_wcst_uploaded_files_meta');
			$file_order_metadata = !$file_order_metadata ? array():$file_order_metadata[0];
			
			foreach($file_order_metadata as $file_to_delete)
			{
				try{
					@unlink($file_to_delete['absolute_path']);
				}catch(Exception $e){};
			}
			delete_post_meta( $order_id, '_wcst_uploaded_files_meta');
		}
	}
}	
?>