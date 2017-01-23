<?php
/**
 * WooCommerce More Sorting - General Section Settings
 *
 * @version 3.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_More_Sorting_Settings_General' ) ) :

class Alg_WC_More_Sorting_Settings_General {

	/**
	 * Constructor.
	 *
	 * @version 3.0.0
	 */
	function __construct() {

		$this->id   = '';
		$this->desc = __( 'General', 'woocommerce-more-sorting' );

		$this->additional_desc_tip = sprintf( __( 'You will need <a href="%s">More Sorting Options for WooCommerce Pro</a> plugin to change this value.', 'woocommerce-more-sorting' ), 'http://coder.fm/item/woocommerce-more-sorting-plugin/' );

		add_filter( 'woocommerce_get_sections_alg_more_sorting',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_more_sorting_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );

		// Add 'Remove All Sorting' checkbox to WooCommerce > Settings > Products
		if ( 'yes' === get_option( 'alg_wc_more_sorting_enabled', 'yes' ) ) {
			add_filter( 'woocommerce_product_settings', array( $this, 'add_remove_sorting_checkbox' ), 100 );
		}
	}

	/*
	 * Add Remove All Sorting checkbox to WooCommerce > Settings > Products.
	 *
	 * @version 3.0.0
	 */
	function add_remove_sorting_checkbox( $settings ) {
		$updated_settings = array();
		foreach ( $settings as $section ) {
			if ( isset( $section['id'] ) && 'woocommerce_cart_redirect_after_add' == $section['id'] ) {
				$updated_settings[] = array(
					'title'     => __( 'More Sorting: Remove All Sorting', 'woocommerce-more-sorting' ),
					'desc'      => __( 'Completely remove sorting from the shop front end', 'woocommerce-more-sorting' ),
					'id'        => 'alg_wc_more_sorting_remove_all_enabled',
					'type'      => 'checkbox',
					'default'   => 'no',
					'custom_attributes' => apply_filters( 'alg_wc_more_sorting', array( 'disabled' => 'disabled' ), 'settings' ),
					'desc_tip'  => apply_filters( 'alg_wc_more_sorting', $this->additional_desc_tip, 'settings' ),
				);
			}
			$updated_settings[] = $section;
		}
		return $updated_settings;
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_settings.
	 *
	 * @version 3.0.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'More Sorting Options', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_options',
			),
			array(
				'title'     => __( 'More Sorting for WooCommerce', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Enable', 'woocommerce-more-sorting' ) . '</strong>',
				'desc_tip'  => __( 'Add new custom, rearrange, remove or rename WooCommerce sorting options.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_options',
			),
			array(
				'title'     => __( 'Custom Sorting', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_custom_sorting_options',
			),
			array(
				'title'     => __( 'Custom Sorting', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Enable Section', 'woocommerce-more-sorting' ) . '</strong>',
				'id'        => 'alg_wc_more_sorting_custom_sorting_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Sort by Name', 'woocommerce-more-sorting' ),
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by title: A to Z', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_title_asc_text',
				'default'   => __( 'Sort by title: A to Z', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'title'     => '',
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by title: Z to A', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_title_desc_text',
				'default'   => __( 'Sort by title: Z to A', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'title'     => __( 'Sort by SKU', 'woocommerce-more-sorting' ),
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by SKU: low to high', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_sku_asc_text',
				'default'   => __( 'Sort by SKU: low to high', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'title'     => '',
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by SKU: high to low', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_sku_desc_text',
				'default'   => __( 'Sort by SKU: high to low', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'title'     => '',
				'desc'      => __( 'Sort SKUs as numbers instead of as texts', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_sku_num_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_more_sorting', array( 'disabled' => 'disabled' ), 'settings' ),
				'desc_tip'  => apply_filters( 'alg_wc_more_sorting', $this->additional_desc_tip, 'settings' ),
			),
			array(
				'title'     => __( 'Sort by stock quantity', 'woocommerce-more-sorting' ),
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by stock quantity: low to high', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_stock_quantity_asc_text',
				'default'   => __( 'Sort by stock quantity: low to high', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'title'     => '',
				'desc'      => __( 'Default: ', 'woocommerce-more-sorting' ) . __( 'Sort by stock quantity: high to low', 'woocommerce-more-sorting' ),
				'desc_tip'  => __( 'Text to show on frontend. Set blank to disable.', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_by_stock_quantity_desc_text',
				'default'   => __( 'Sort by stock quantity: high to low', 'woocommerce-more-sorting' ),
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_custom_sorting_options',
			),
			array(
				'title'     => __( 'Rearrange Sorting', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_rearrange_options',
			),
			array(
				'title'     => __( 'Rearrange Sorting', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Enable Section', 'woocommerce-more-sorting' ) . '</strong>',
				'id'        => 'alg_wc_more_sorting_rearrange_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Rearrange Sorting', 'woocommerce-more-sorting' ),
				'id'        => 'alg_wc_more_sorting_rearrange',
				'desc_tip'  => __( 'Default:', 'woocommerce-more-sorting' ) . '<br>' . implode( '<br>', alg_get_woocommerce_sortings_order() ),
				'default'   => implode( PHP_EOL, alg_get_woocommerce_sortings_order() ),
				'type'      => 'textarea',
				'css'       => 'min-height:300px;',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_rearrange_options',
			),
			array(
				'title'     => __( 'Default WooCommerce Sorting', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_default_sorting_options',
			),
			array(
				'title'     => __( 'Default Sorting Options', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Enable Section', 'woocommerce-more-sorting' ) . '</strong>',
				'id'        => 'alg_wc_more_sorting_default_sorting_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_more_sorting', array( 'disabled' => 'disabled' ), 'settings' ),
				'desc_tip'  => apply_filters( 'alg_wc_more_sorting', $this->additional_desc_tip, 'settings' ),
			),
		);
		foreach ( alg_get_woocommerce_default_sortings() as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			$settings[] = array(
				'title'     => $sorting_desc,
				'id'        => 'alg_wc_more_sorting_default_sorting_' . $option_key,
				'default'   => $sorting_desc,
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			);
			if ( 'menu_order' === $sorting_key ) {
				continue;
			}
			$settings[] = array(
				'desc'      => __( 'Remove', 'woocommerce-more-sorting' ) . ' "' . $sorting_desc . '"',
				'id'        => 'alg_wc_more_sorting_default_sorting_' . $option_key . '_disable',
				'default'   => 'no',
				'type'      => 'checkbox',
			);
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_default_sorting_options',
			),
			array(
				'title'     => __( 'Remove Sorting', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_remove_options',
			),
			array(
				'title'     => __( 'Remove All Sorting', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Remove all sorting (including WooCommerce default)', 'woocommerce-more-sorting' ) . '</strong>',
				'id'        => 'alg_wc_more_sorting_remove_all_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_more_sorting', array( 'disabled' => 'disabled' ), 'settings' ),
				'desc_tip'  => apply_filters( 'alg_wc_more_sorting', $this->additional_desc_tip, 'settings' ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_remove_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_More_Sorting_Settings_General();
