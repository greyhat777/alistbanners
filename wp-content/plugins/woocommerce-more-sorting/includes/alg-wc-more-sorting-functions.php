<?php
/**
 * WooCommerce More Sorting Functions
 *
 * @version 3.0.0
 * @since   3.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_get_woocommerce_default_sortings' ) ) {
	/**
	 * alg_get_woocommerce_default_sortings.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function alg_get_woocommerce_default_sortings() {
		return array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sort by popularity', 'woocommerce' ),
			'rating'     => __( 'Sort by average rating', 'woocommerce' ),
			'date'       => __( 'Sort by newness', 'woocommerce' ),
			'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
		);
	}
}

if ( ! function_exists( 'alg_get_woocommerce_sortings_order' ) ) {
	/**
	 * alg_get_woocommerce_sortings_order.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function alg_get_woocommerce_sortings_order() {
		return array(
			'menu_order',
			'popularity',
			'rating',
			'date',
			'price',
			'price-desc',
			'title_asc',
			'title_desc',
			'sku_asc',
			'sku_desc',
			'stock_quantity_asc',
			'stock_quantity_desc',
		);
	}
}