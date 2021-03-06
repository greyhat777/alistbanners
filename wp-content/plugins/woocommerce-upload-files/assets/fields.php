<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5681280d33aa8',
	'title' => 'WooCommerce Upload Files - General options',
	'fields' => array (
		array (
			'key' => 'field_577fd3b94729a',
			'label' => 'Enable quantity selection',
			'name' => 'wcuf_enable_quantity_selection',
			'type' => 'select',
			'instructions' => 'in case of <strong>multiple files upload</strong> you can allow your customers to specify for each upload a quantity. This could be useful, for example, if a customer of yours woulk like to have multiple prints of a file.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_5697af32ebd65',
			'label' => 'Disable "View" button feature',
			'name' => 'wcuf_disable_view_button',
			'type' => 'select',
			'instructions' => 'If a product has at least a required upload field, the "add to cart" button (on shop page and on widgets) is replaced by a "View" button that forces the customer to enter the product page and upload a file after adding it to the basket.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				0 => 'False',
				1 => 'True',
			),
			'default_value' => array (
				0 => 0,
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_569cb2e2da65d',
			'label' => 'Show warning alert on Upload files configurator page',
			'name' => 'wcuf_show_warning_alert_on_configurator',
			'type' => 'select',
			'instructions' => 'In case of javascript error due to 3rd party plugins/theme that may prevent the configurator to work properly, an alert is show on upload fields configurator page.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'yes' => 'Yes',
				'no' => 'No',
			),
			'default_value' => array (
				'yes' => 'yes',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56c6dff1eeed5',
			'label' => 'Summary box',
			'name' => 'wcuf_display_summary_box_strategy',
			'type' => 'checkbox',
			'instructions' => 'Optionally can be displayed a summary box containing the names of all the uploaded files. Click on which pages it has to be displayed',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'cart' => 'Cart page',
				'checkout' => 'Checkout page',
				'order_details' => 'Order details page',
			),
			'default_value' => array (
			),
			'layout' => 'horizontal',
			'toggle' => 1,
		),
		array (
			'key' => 'field_56d418494750f',
			'label' => 'Summary box info to display',
			'name' => 'wcuf_summary_box_info_to_display',
			'type' => 'select',
			'instructions' => 'Select which info display in summary box',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'file_name_and_preview_image' => 'File name(s) and preview image(s)',
				'file_name' => 'Only file name(s)',
				'preview_image' => 'Only preview image(s)',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_5736dcc84eeab',
			'label' => 'Display last order upload fields in My Account page',
			'name' => 'wcuf_display_last_order_upload_fields_in_my_account_page',
			'type' => 'select',
			'instructions' => 'Show the upload fields form for the last order in my account page',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56daf36373525',
			'label' => 'Remove random number prefix to uploaded file name',
			'name' => 'wcuf_remove_random_number_prefix',
			'type' => 'select',
			'instructions' => 'By default, for security reasons, WCUF adds a random number as prefix to the uploaded file. Optionally you can disable this feature. This option is only for single file upload fields.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56f537fb95820',
			'label' => 'Disable upload field standard managment',
			'name' => 'wcuf_pages_in_which_standard_upload_fields_managment_is_disabled',
			'type' => 'checkbox',
			'instructions' => 'Select in which page you want to complete disable upload fields standard managment. You have to disable the standard managment if you want to use the shortcodes in one of following pages:',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'cart' => 'Cart page',
				'product' => 'Product pages',
				'checkout' => 'Checkout',
			),
			'default_value' => array (
			),
			'layout' => 'horizontal',
			'toggle' => 1,
		),
		array (
			'key' => 'field_577cf16e875c8',
			'label' => 'Force upload requirement check in Product page before adding item to the cart',
			'name' => 'wcuf_force_require_check_befor_adding_item_to_car',
			'type' => 'select',
			'instructions' => '<p>By default the upload requirement check is performed after an item is added to the cart. This avoid the users who are simple reading the product page to be not able to leave it if they don\'t want to purchase the product.</p>
<p>Enabling this option, in case an upload field has been configured to be showed before an item is added to the cart, the requierment check is performed before the item is added to the cart.</p>',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_57924b6d8fa6e',
			'label' => 'Allow user to leave page in case of required field',
			'name' => 'wcuf_allow_user_to_leave_page_in_case_of_required_field',
			'type' => 'select',
			'instructions' => 'By default the user won\'t be able to leave the page until all required uploads have not been completed. Selecting <strong>Yes</strong> option, the user will be prompted only at the first try to leave the page with a warning message, then he will be able to leave the page.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'no' => 'No',
				'yes' => 'Yes',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_575997c343ba1',
	'title' => 'WooCommerce Upload Files – Texts',
	'fields' => array (
		array (
			'key' => 'field_575997d817d41',
			'label' => 'Browse file button',
			'name' => 'wcuf_browse_button',
			'type' => 'text',
			'instructions' => '<p><strong>NOTE:</strong> If you are using WPML you can translate all button texts by switching language using the WPML language menu.</p>
<p>&nbsp;</p>
<p>This is the text used for the <strong>Browse</strong> button used to upload single files.</p>',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Browse...',
			'placeholder' => 'Default text: Browse...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5759984b17d42',
			'label' => 'Add files button',
			'name' => 'wcuf_add_files_button',
			'type' => 'text',
			'instructions' => '<p><strong>NOTE:</strong> If you are using WPML you can translate all button texts by switching language using the WPML language menu.</p>
<p>&nbsp;</p>
<p>This is the text used for the <strong>Add files</strong> button used to select multiple files.</p>',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Add files',
			'placeholder' => 'Default text: Add files',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5759987217d43',
			'label' => 'Upload selected files button',
			'name' => 'wcuf_upload_selected_files_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Upload selected files</strong> button used to upload multiple files.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Upload selected files',
			'placeholder' => 'Default text: Upload selected files',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_575998af17d44',
			'label' => 'Delete files button',
			'name' => 'wcuf_delete_file_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Delete file</strong> button used to delete files.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Delete file(s)',
			'placeholder' => 'Default text: Delete file(s)',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5759997617d45',
			'label' => 'Crop & Upload button',
			'name' => 'wcuf_crop_and_upload_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Crop & Upload</strong> button used crop image files and upload them.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Crop & Upload',
			'placeholder' => 'Default value: Crop & Upload button',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_575999a917d46',
			'label' => 'Zoom In button (Crop feature)',
			'name' => 'wcuf_zoom_in_crop_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Zoom In</strong> button used to zoom an image that has to be cropped.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => '+',
			'placeholder' => 'Default value: +',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_575999f617d47',
			'label' => 'Zoom Out button (Crop feature)',
			'name' => 'wcuf_zoom_out_crop_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Zoom Out</strong> button used to zoom an image that has to be cropped.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => '-',
			'placeholder' => 'Default value: -',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5759a3fd20728',
			'label' => 'Save uploads button',
			'name' => 'wcuf_save_uploads_button',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Save uploads</strong> button used to save uploaded files in Order details page.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Save upload(s)',
			'placeholder' => 'Default text: Save upload(s)',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_577fdd7416205',
			'label' => 'Select quantity label',
			'name' => 'wcuf_select_quantity_label',
			'type' => 'text',
			'instructions' => 'This is the text used for the <strong>Select quantity</strong> label showed in case of multiple file upload and if the special option has been enabled in the <strong>Option</strong> menu.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Select quantity:',
			'placeholder' => 'Default text: Select quantity:',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-texts',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_568132c46c415',
	'title' => 'WooCommerce Upload Files - Fields positioning',
	'fields' => array (
		array (
			'key' => 'field_568132e576737',
			'label' => 'Product page',
			'name' => 'wcuf_browse_button_position',
			'type' => 'select',
			'instructions' => 'Chose where the Upload field has to be displayed. For "simple" products, "After variation options dropdown(s)" and "Before variation options dropdown(s)" will have same effects, will render upload fields before the "Add to cart" button.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'woocommerce_before_add_to_cart_button' => 'After variable options dropdown(s) and before add to cart button',
				'woocommerce_before_add_to_cart_form' => 'Before both variable options dropdown(s)	and add to cart button',
				'woocommerce_after_add_to_cart_button' => 'After add to cart button',
				'woocommerce_product_thumbnails' => 'After product images',
				'woocommerce_before_single_product_summary' => 'Before product Images',
				'woocommerce_single_product_summary' => 'Before short description',
				'woocommerce_after_single_product_summary' => 'After product description',
			),
			'default_value' => array (
				0 => 'woocommerce_before_add_to_cart_form',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_569cc4e85cba5',
			'label' => 'Cart page',
			'name' => 'wcuf_cart_page_positioning',
			'type' => 'select',
			'instructions' => 'Show where the uploads fields has to be displayed in cart page.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'woocommerce_before_cart_table' => 'Before cart table',
				'woocommerce_after_cart_table' => 'After cart table',
				'woocommerce_before_cart_contents' => 'Before cart contents',
				'woocommerce_after_cart_contents' => 'After cart contents',
				'woocommerce_before_cart_totals' => 'Before cart totals',
				'woocommerce_after_cart_totals' => 'After cart totals',
			),
			'default_value' => array (
				0 => 'woocommerce_before_cart_table',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_56a9c70ed4c14',
			'label' => 'Checkout page',
			'name' => 'wcuf_checkout_page_positioning',
			'type' => 'select',
			'instructions' => 'Show where the uploads fields has to be displayed in cart page.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'woocommerce_before_checkout_billing_form' => 'Before billing form',
				'woocommerce_after_checkout_billing_form' => 'After billing form',
				'woocommerce_before_checkout_shipping_form' => 'Before shipping form (Note: shipping form must be visible)',
				'woocommerce_after_checkout_shipping_form' => 'After shipping form (Note: shipping form must be visible)',
				'woocommerce_before_order_notes' => 'Before order notes',
				'woocommerce_after_order_notes' => 'After order notes',
				'woocommerce_checkout_before_order_review' => 'Before order & payment review',
				'woocommerce_checkout_after_order_review' => 'After order & payment review',
				'woocommerce_review_order_before_submit' => 'Before "Place order" button',
				'woocommerce_review_order_after_submit' => 'After "Place order" submit button',
			),
			'default_value' => array (
				0 => 'woocommerce_after_checkout_billing_form',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-options',
			),
		),
	),
	'menu_order' => 1,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_56c6d6b17ba57',
	'title' => 'WooCommerce Upload Files – Style',
	'fields' => array (
		array (
			'key' => 'field_56812878efd11',
			'label' => 'Bar color',
			'name' => 'wcuf_bar_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#808080',
		),
		array (
			'key' => 'field_56bc805020a0b',
			'label' => 'Image preview height',
			'name' => 'wcuf_image_preview_height',
			'type' => 'number',
			'instructions' => 'Image preview height used by the [file_name_with_image_preview] shortcode during the uploaded file list generation.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 50,
			'placeholder' => 'Default is 50',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56bc7fc820a0a',
			'label' => 'Image preview width',
			'name' => 'wcuf_image_preview_width',
			'type' => 'number',
			'instructions' => 'Image preview width used by the [file_name_with_image_preview] shortcode during the uploaded file list generation.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 50,
			'placeholder' => 'Default is 50',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c72067cf478',
			'label' => 'Upload field title color',
			'name' => 'wcuf_css_upload_field_title_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
		),
		array (
			'key' => 'field_56c8235b3e228',
			'label' => 'Upload field title font size',
			'name' => 'wcuf_css_upload_field_title_font_size',
			'type' => 'number',
			'instructions' => 'Leave empty to inherit from theme.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c6d7467f3c8',
			'label' => 'Notice text margin top',
			'name' => 'wcuf_css_notice_text_margin_top',
			'type' => 'number',
			'instructions' => 'Margin top (px) of the notice text: (Max size: XXMB. Max file: X )',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 5,
			'placeholder' => 'Default value is 5',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c6d80b1ccfc',
			'label' => 'Notice text margin bottom',
			'name' => 'wcuf_css_notice_text_margin_bottom',
			'type' => 'number',
			'instructions' => 'Margin bottom (px) of the notice text: (Max size: XXMB. Max file: X )',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => 'Default value is 0',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c6d8201ccfd',
			'label' => 'Feedback text area height',
			'name' => 'wcuf_css_feedback_text_area_height',
			'type' => 'number',
			'instructions' => 'Feedback text area height (px)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => 100,
			'placeholder' => 'Default value is 100',
			'prepend' => '',
			'append' => '',
			'min' => 50,
			'max' => '',
			'step' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c6d89d1ccfe',
			'label' => 'Feedback text area margin top',
			'name' => 'wcuf_css_feedback_text_area_margin_top',
			'type' => 'number',
			'instructions' => 'Feedback text area margin top (px)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => 'Default value is 0',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_56c6d9171ccff',
			'label' => 'Feedback text area margin bottom',
			'name' => 'wcuf_css_feedback_text_area_margin_bottom',
			'type' => 'number',
			'instructions' => 'Feedback text area margin bottom (px)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 33,
				'class' => '',
				'id' => '',
			),
			'default_value' => 5,
			'placeholder' => 'Default value is 5',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_568131f8bf16f',
			'label' => 'Additional "Browse" button class',
			'name' => 'wcuf_additional_button_class',
			'type' => 'text',
			'instructions' => 'You can add a class to the "Browse" button used in the product, checkout and order details pages.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'class_name',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_57284c445b6ce',
			'label' => 'Distance between upload buttons',
			'name' => 'wcuf_css_distance_between_upload_buttons',
			'type' => 'number',
			'instructions' => 'Distance between "Add files" and "Upload selected files" buttons',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 2,
			'placeholder' => 'Default value is 2',
			'prepend' => '',
			'append' => '',
			'min' => 0,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-options',
			),
		),
	),
	'menu_order' => 2,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_5714a3a2de46d',
	'title' => 'WooCommerce Upload Files – Crop controller',
	'fields' => array (
		array (
			'key' => 'field_5714a3c24a8df',
			'label' => 'Crop controller width',
			'name' => 'wcuf_crop_area_width',
			'type' => 'number',
			'instructions' => 'The value rapresent the width (in pixels) of the crop controller used to edit the image.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 300,
			'placeholder' => 'Default value: 300',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5714a4964a8e0',
			'label' => 'Crop controller height',
			'name' => 'wcuf_crop_area_height',
			'type' => 'number',
			'instructions' => 'The value rapresent the height (in pixels) of the crop controller used to edit the image.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => 50,
				'class' => '',
				'id' => '',
			),
			'default_value' => 300,
			'placeholder' => 'Default value: 300',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => 1,
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-options',
			),
		),
	),
	'menu_order' => 3,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>