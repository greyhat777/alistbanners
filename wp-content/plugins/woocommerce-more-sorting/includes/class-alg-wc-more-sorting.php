<?php
/**
 * WooCommerce More Sorting
 *
 * @version 3.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Alg_More_Sorting' ) ) :

class WC_Alg_More_Sorting {

	/**
	 * Constructor.
	 *
	 * @version 3.0.0
	 */
	function __construct() {

		if ( 'yes' === get_option( 'alg_wc_more_sorting_enabled', 'yes' ) ) {

			// Remove All Sorting
			if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'remove_all' ) ) {
				add_action( 'wp_loaded', array( $this, 'remove_sorting' ), PHP_INT_MAX );
				add_filter( 'wc_get_template', array( $this, 'remove_sorting_template' ), PHP_INT_MAX, 5 );
			} else {

				// Add Custom Sorting
				if ( 'yes' === get_option( 'alg_wc_more_sorting_custom_sorting_enabled', 'yes' ) ) {
					add_filter( 'woocommerce_get_catalog_ordering_args',       array( $this, 'get_catalog_ordering_args' ), PHP_INT_MAX );
					add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'add_custom_sorting' ),        PHP_INT_MAX );
					add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'add_custom_sorting' ),        PHP_INT_MAX );
				}

				// Remove or Rename Default Sorting
				if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'default_sorting' ) ) {
					add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'remove_default_sortings' ), PHP_INT_MAX );
					add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'rename_default_sortings' ), PHP_INT_MAX );
					add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'remove_default_sortings' ), PHP_INT_MAX );
				}

				// Rearrange All Sorting
				if ( 'yes' === get_option( 'alg_wc_more_sorting_rearrange_enabled', 'no' ) ) {
					add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'rearrange_sorting' ), PHP_INT_MAX );
					add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'rearrange_sorting' ), PHP_INT_MAX );
				}

			}
		}
	}

	/**
	 * remove_sorting_template.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function remove_sorting_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'loop/orderby.php' === $template_name ) {
			$located = untrailingslashit( realpath( plugin_dir_path( __FILE__ ) . '/..' ) ) . '/templates/alg-loop-orderby.php';
		}
		return $located;
	}

	/**
	 * remove_sorting.
	 *
	 * @version 3.0.0
	 */
	function remove_sorting() {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'mpcth_before_shop_loop',       'woocommerce_catalog_ordering', 40 ); // Blaszok theme
		remove_action( 'woocommerce_after_shop_loop',  'woocommerce_catalog_ordering', 10 ); // Storefront
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 ); // Storefront
	}

	/*
	 * maybe_add_sorting.
	 *
	 * @version 3.0.0
	 */
	private function maybe_add_sorting( $sortby, $key, $default ) {
		$option_name = 'alg_wc_more_sorting_by_' . $key . '_text';
		if ( '' != get_option( $option_name, $default ) ) {
			$sortby[ $key ] = get_option( $option_name, $default );
		}
		return $sortby;
	}

	/*
	 * rearrange_sorting.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function rearrange_sorting( $sortby ) {
		$rearranged_sorting = get_option( 'alg_wc_more_sorting_rearrange', false );
		if ( false === $rearranged_sorting ) {
			$rearranged_sorting = alg_get_woocommerce_sortings_order();
		} else {
			$rearranged_sorting = explode( PHP_EOL, $rearranged_sorting );
		}
		$rearranged_sortby = array();
		foreach ( $rearranged_sorting as $sorting ) {
			$sorting = str_replace( "\n", '', $sorting );
			$sorting = str_replace( "\r", '', $sorting );
			if ( isset( $sortby[ $sorting ] ) ) {
				$rearranged_sortby[ $sorting ] = $sortby[ $sorting ];
				unset( $sortby[ $sorting ] );
			}
		}
		return array_merge( $rearranged_sortby, $sortby );
	}

	/*
	 * remove_default_sortings.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function remove_default_sortings( $sortby ) {
		$default_sortings = alg_get_woocommerce_default_sortings();
		foreach ( $default_sortings as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'default_sorting_disable', $option_key ) ) {
				unset( $sortby[ $sorting_key ] );
			}
		}
		return $sortby;
	}

	/*
	 * rename_default_sortings.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function rename_default_sortings( $sortby ) {
		$default_sortings = alg_get_woocommerce_default_sortings();
		foreach ( $default_sortings as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			if ( isset( $sortby[ $sorting_key ] ) ) {
				$sortby[ $sorting_key ] = apply_filters( 'alg_wc_more_sorting', $sorting_desc, 'default_sorting_text', $option_key );
			}
		}
		return $sortby;
	}

	/*
	 * Add new sorting options to Front End and to Back End (in WooCommerce > Settings > Products > Default Product Sorting).
	 *
	 * @version 3.0.0
	 */
	function add_custom_sorting( $sortby ) {
		$sortby = $this->maybe_add_sorting( $sortby, 'title_asc',           __( 'Sort by title: A to Z', 'woocommerce-more-sorting' ) );
		$sortby = $this->maybe_add_sorting( $sortby, 'title_desc',          __( 'Sort by title: Z to A', 'woocommerce-more-sorting' ) );
		$sortby = $this->maybe_add_sorting( $sortby, 'sku_asc',             __( 'Sort by SKU: low to high', 'woocommerce-more-sorting' ) );
		$sortby = $this->maybe_add_sorting( $sortby, 'sku_desc',            __( 'Sort by SKU: high to low', 'woocommerce-more-sorting' ) );
		$sortby = $this->maybe_add_sorting( $sortby, 'stock_quantity_asc',  __( 'Sort by stock quantity: low to high', 'woocommerce-more-sorting' ) );
		$sortby = $this->maybe_add_sorting( $sortby, 'stock_quantity_desc', __( 'Sort by stock quantity: high to low', 'woocommerce-more-sorting' ) );
		return $sortby;
	}

	/*
	 * Add new sorting options to WooCommerce sorting.
	 *
	 * @version 3.0.0
	 */
	function get_catalog_ordering_args( $args ) {

		global $woocommerce;
		// Get ordering from query string unless defined
		$orderby_value = isset( $_GET['orderby'] ) ?
			woocommerce_clean( $_GET['orderby'] ) :
			apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		// Get order + orderby args from string
		$orderby_value = explode( '-', $orderby_value );
		$orderby       = esc_attr( $orderby_value[0] );

		switch ( $orderby ) :
			case 'title_asc':
				$args['orderby'] = 'title';
				$args['order'] = 'asc';
				$args['meta_key'] = '';
			break;
			case 'title_desc':
				$args['orderby'] = 'title';
				$args['order'] = 'desc';
				$args['meta_key'] = '';
			break;
			case 'sku_asc':
				$args['orderby'] = ( 'no' === apply_filters( 'alg_wc_more_sorting', 'no', 'by_sku_num' ) ) ? 'meta_value' : 'meta_value_num';
				$args['order'] = 'asc';
				$args['meta_key'] = '_sku';
			break;
			case 'sku_desc':
				$args['orderby'] = ( 'no' === apply_filters( 'alg_wc_more_sorting', 'no', 'by_sku_num' ) ) ? 'meta_value' : 'meta_value_num';
				$args['order'] = 'desc';
				$args['meta_key'] = '_sku';
			break;
			case 'stock_quantity_asc':
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'asc';
				$args['meta_key'] = '_stock';
			break;
			case 'stock_quantity_desc':
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'desc';
				$args['meta_key'] = '_stock';
			break;
		endswitch;

		return $args;
	}
}

endif;

return new WC_Alg_More_Sorting();
