<?php
class WCUF_Email  
{
	public function __construct() 
	{
	}
	public function trigger( $links_to_notify_via_mail, $order , $attachment = array()) 
	{
		//$recipient = get_option( 'admin_email' );
		foreach($links_to_notify_via_mail as $recipients => $links)
		{
			$recipient = $recipients;
			$subject = __('User submitted new upload for order #', 'woocommerce-files-upload').$order->id;
			$content = __('User submitted new upload for order #', 'woocommerce-files-upload').'<a href="'.admin_url('post.php?post='.$order->id.'&action=edit').'">'.$order->id.'</a>';
			$content .= "<br /> <br />";
			$content .= "<strong>".__('Customer personal data')."</strong><br/>".$order->get_formatted_billing_full_name()." (". $order->billing_email.") <br/>".$order->get_formatted_billing_address();
			$content .= "<br /> <br />";
			$content .= "<strong>".__('Uploaded file(s)', 'woocommerce-files-upload')."</strong> <br />";
			$content .= __('You can directly download by clicking on following link(s): ', 'woocommerce-files-upload');
			$content .= "<br /> ";
			$content .= '<table>';
			foreach($links as $download)
			{
				$content .='<tr><a href="'.$download['url'].'">'.$download['title'].'</a></tr>';
				if(isset($download['feedback']) && $download['feedback'] != '')
				{
					$content .= '<tr><strong>'.__('User feedback: ', 'woocommerce-files-upload').'</strong>';
					$content .= "<br /> ";
					$content .= $download['feedback'];
					$content .= "</tr>";
					$content .= "<tr><br/></tr>";
				}
			
			}
			$content .= '</table>';
			
		
			$mail = WC()->mailer();
			$email_heading = get_bloginfo('name');
			
			ob_start();
			$mail->email_header($email_heading );
			echo $content;
			$mail->email_footer();
			$message =  ob_get_contents();
			ob_end_clean(); 
			
			$attachments = isset($attachment[$recipients]) ? $attachment[$recipients] : array();
			
			$mail->send( $recipient, $subject, $message, "Content-Type: text/html\r\n", $attachments);
		}
	}
} 