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
.wcuf_file_name
{
	font-weight:bold;
	margin-top: 5px;
	margin-bottom: 5px;
}
.wcuf_spacer
{
	display:block; height:10px;
	width:100%;
	clear: both;
}
.wcuf_spacer2
{
	display:block; 
	height:25px;
	width:100%;
	clear: both;
}
.wcuf_spacer3,.wcuf_spacer4
{
	display:block; height:2px;
	width:100%;
	clear: both;
}
#wcuf_file_uploads_container strong {
   display: block;
  clear:both;
}
#wcuf_deleting_message
{
	display:none;
}
input[type="file"].wcuf_file_input
{
	display:none;
}
button.button.wcuf_upload_multiple_files_button,.button.wcuf_upload_multiple_files_button, .woocommerce.single.single-product .entry-summary form button.button.wcuf_upload_multiple_files_button, #wcuf_file_uploads_container .button.wcuf_upload_multiple_files_button
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
.wcuf_file_preview_list_item
{
	margin-bottom: 5px;
}
h4.wcuf_upload_field_title.wcuf_summary_uploaded_files_title
{
	margin-top:15px;
}
.wcuf_summary_uploaded_files_list_spacer
{
	display:block;
	clear:both;
	height:10px;
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