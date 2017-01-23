<?php
/**
 * WooCommerce More Sorting - Settings
 *
 * @version 3.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_More_Sorting' ) ) :

class Alg_WC_Settings_More_Sorting extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 3.0.0
	 */
	function __construct() {
		$this->id    = 'alg_more_sorting';
		$this->label = __( 'More Sorting', 'woocommerce-more-sorting' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.0
	 */
	function get_settings() {
		global $current_section;
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
	}
}

endif;

return new Alg_WC_Settings_More_Sorting();
