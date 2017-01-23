<?php
/*** set the content type header ***/
header("Content-type: text/css");
?>
.wcuf_bar
{
	background-color: grey;
    display: block;
    height: 10px;
    width: 100%;
}
.wcuf_upload_status_box
{
	display:none;
}
.wcuf_required_label:after { content:" *";  color:red;}
.woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_field_button::before, 
.woocommerce.single.single-product .entry-summary form button.button.delete_button::before,
.woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_multiple_files_button::before,
.woocommerce.single.single-product .entry-summary form button.button.wcuf_remove_button_extra_content::before {
  content:none
}
.woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_multiple_files_button
{
	width: auto;
}
/* .woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_multiple_files_button  {
  float: left;
  margin-left: 2px;
  position: absolute;
  width: auto;
}
 
 #wcuf_ajax_container
 {
	 min-width:300px;
 }*/
#wcuf_deleting_message
{
	display:none;
}
.wcuf_spacer
{
	display:block; height:2px;
	width:100%;
	clear: both;
}
.wcuf_spacer2
{
	display:block; 
	height:5px;
	width:100%;
	clear: both;
}
.wcuf_spacer3
{
	display:block; height:0px;
	width:100%;
	clear: both;
}
.wcuf_spacer4
{
	display:block; height:50px;
	width:100%;
	clear: both;
}

.wcuf_field_hidden
{
	display:none;
}
input[type="file"].wcuf_file_input
{
	/* display:none; */
	opacity:0;
	position:absolute;
}
.button.wcuf_upload_field_button {
	  width: auto !important;
}
	
#wcuf_file_uploads_container{ min-width:300px }
	
#wcuf_file_uploads_container strong {
  display: block;
  clear:both;
}
button.button.wcuf_upload_multiple_files_button,.woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_multiple_files_button, #wcuf_file_uploads_container .button.wcuf_upload_multiple_files_button
{
	display:none;
}
.wcuf_multiple_files_list
{
	margin-top: 3px;
	display:block;
}
.wcuf_single_file_name_in_multiple_list
{
	font-weight:bold;
	font-style: italic;
}
.wcuf_file_name
{
	display:block;
	clear:left;
	margin-top: 5px;
	margin-bottom: 5px;
}
.wcuf_feedback_textarea:required
{
	border: 1px solid red;
}
.wcuf_file_preview_list_item
{
	margin-bottom: 5px;
}
.wcuf_summary_uploaded_files_list_spacer
{
	display:block;
	clear:both;
	height:20px;
}
#wcuf_summary_uploaded_files
{
	margin-bottom:40px;
}
.wcuf_disclaimer_checkbox
{
	 margin-right: 5px;
    top: 3px;
    vertical-align: bottom;
	position: relative;
}
.wcuf_disclaimer_label
{
	display:block;
	clear:both;
	margin: 10px 0px 10px 0px;
}
h4.wcuf_upload_field_title
{
	color: <?php echo urldecode($_GET['css_upload_field_title_color']);?>;
	font-size: <?php echo urldecode($_GET['css_upload_field_title_font_size']);?>px;
}
.wcuf_feedback_textarea
{
	height: <?php echo $_GET['css_feedback_text_area_height'];?>px;
	margin-top: <?php echo $_GET['css_feedback_text_area_margin_top'];?>px;
	margin-bottom: <?php echo $_GET['css_feedback_text_area_margin_bottom'];?>px;
}
.wcuf_max_size_notice
{
	margin-top: <?php echo $_GET['css_notice_text_margin_top'];?>px;
	margin-bottom: <?php echo $_GET['css_notice_text_margin_bottom'];?>px;
}