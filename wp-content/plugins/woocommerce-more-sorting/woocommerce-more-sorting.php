<?php
/*
Plugin Name: More Sorting Options for WooCommerce
Plugin URI: http://coder.fm/item/woocommerce-more-sorting-plugin
Description: Add new custom, rearrange, remove or rename WooCommerce sorting options.
Version: 3.0.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Copyright: � 2016 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'alg_is_plugin_active' ) ) {
	/**
	 * alg_is_plugin_active - Check if plugin is active.
	 *
	 * @return  bool
	 * @version 3.0.0
	 * @since   2.1.0
	 */
	function alg_is_plugin_active( $plugin_file ) {
		$active_plugins = ( is_multisite() ) ?
			array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) :
			apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
		foreach ( $active_plugins as $active_plugin ) {
			$active_plugin = explode( '/', $active_plugin );
			if ( isset( $active_plugin[1] ) && $plugin_file === $active_plugin[1] ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! alg_is_plugin_active( 'woocommerce.php' ) ) {
	return;
}

if ( 'woocommerce-more-sorting.php' === basename( __FILE__ ) && alg_is_plugin_active( 'woocommerce-more-sorting-pro.php' ) ) {
	return;
}

if ( ! class_exists( 'Alg_Woocommerce_More_Sorting' ) ) :

/**
 * Main Alg_Woocommerce_More_Sorting Class
 *
 * @class   Alg_Woocommerce_More_Sorting
 * @version 3.0.0
 */

final class Alg_Woocommerce_More_Sorting {

	/**
	 * Plugin version
	 */
	public $version = '3.0.0';

	/**
	 * @var Alg_Woocommerce_More_Sorting The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_Woocommerce_More_Sorting Instance
	 *
	 * Ensures only one instance of Alg_Woocommerce_More_Sorting is loaded or can be loaded.
	 *
	 * @static
	 * @return Alg_Woocommerce_More_Sorting - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Alg_Woocommerce_More_Sorting Constructor.
	 *
	 * @access  public
	 * @version 3.0.0
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'woocommerce-more-sorting', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Settings
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param   mixed $links
	 * @return  array
	 * @version 3.0.0
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_more_sorting' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'woocommerce-more-sorting.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="http://coder.fm/item/woocommerce-more-sorting-plugin/">' . __( 'Unlock all', 'woocommerce-more-sorting' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 3.0.0
	 */
	private function includes() {

		require_once( 'includes/alg-wc-more-sorting-functions.php' );

		$settings = array();
		$settings[] = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-general.php' );
		if ( is_admin() && $this->version != get_option( 'alg_wc_more_sorting_version', '' ) ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
			$this->handle_deprecated_options();
			update_option( 'alg_wc_more_sorting_version', $this->version );
		}

		require_once( 'includes/class-alg-wc-more-sorting.php' );
	}

	/**
	 * handle_deprecated_options.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function handle_deprecated_options( $settings ) {
		$deprecated_settings = array(
			// v3.0.0
			'woocommerce_more_sorting_enabled'                         => 'alg_wc_more_sorting_enabled',
			'woocommerce_more_sorting_by_name_asc_text'                => 'alg_wc_more_sorting_by_title_asc_text',
			'woocommerce_more_sorting_by_name_desc_text'               => 'alg_wc_more_sorting_by_title_desc_text',
			'woocommerce_more_sorting_by_sku_asc_text'                 => 'alg_wc_more_sorting_by_sku_asc_text',
			'woocommerce_more_sorting_by_sku_desc_text'                => 'alg_wc_more_sorting_by_sku_desc_text',
			'woocommerce_more_sorting_by_sku_num_enabled'              => 'alg_wc_more_sorting_by_sku_num_enabled',
			'woocommerce_more_sorting_by_stock_quantity_asc_text'      => 'alg_wc_more_sorting_by_stock_quantity_asc_text',
			'woocommerce_more_sorting_by_stock_quantity_desc_text'     => 'alg_wc_more_sorting_by_stock_quantity_desc_text',
			'woocommerce_more_sorting_remove_all_enabled'              => 'alg_wc_more_sorting_remove_all_enabled',
			'woocommerce_more_sorting_pro_enabled'                     => 'alg_wc_more_sorting_enabled',
			'woocommerce_more_sorting_pro_by_name_asc_text'            => 'alg_wc_more_sorting_by_title_asc_text',
			'woocommerce_more_sorting_pro_by_name_desc_text'           => 'alg_wc_more_sorting_by_title_desc_text',
			'woocommerce_more_sorting_pro_by_sku_asc_text'             => 'alg_wc_more_sorting_by_sku_asc_text',
			'woocommerce_more_sorting_pro_by_sku_desc_text'            => 'alg_wc_more_sorting_by_sku_desc_text',
			'woocommerce_more_sorting_pro_by_sku_num_enabled'          => 'alg_wc_more_sorting_by_sku_num_enabled',
			'woocommerce_more_sorting_pro_by_stock_quantity_asc_text'  => 'alg_wc_more_sorting_by_stock_quantity_asc_text',
			'woocommerce_more_sorting_pro_by_stock_quantity_desc_text' => 'alg_wc_more_sorting_by_stock_quantity_desc_text',
			'woocommerce_more_sorting_pro_remove_all_enabled'          => 'alg_wc_more_sorting_remove_all_enabled',
		);
		foreach ( $deprecated_settings as $old => $new ) {
			if ( false !== ( $old_value = get_option( $old ) ) ) {
				update_option( $new, $old_value );
				delete_option( $old );
			}
		}
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 *
	 * @version 3.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-alg-wc-settings-more-sorting.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

if ( ! function_exists( 'alg_woocommerce_more_sorting' ) ) {
	/**
	 * Returns the main instance of Alg_Woocommerce_More_Sorting to prevent the need to use globals.
	 *
	 * @return  Alg_Woocommerce_More_Sorting
	 * @version 3.0.0
	 */
	function alg_woocommerce_more_sorting() {
		return Alg_Woocommerce_More_Sorting::instance();
	}
}

alg_woocommerce_more_sorting();
