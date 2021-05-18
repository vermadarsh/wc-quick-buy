<?php
/**
 * This file is used for writing all the re-usable custom functions.
 *
 * @since 1.0.0
 * @package Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Get plugin setting by setting index.
 *
 * @param string $setting Holds the setting index.
 * @return boolean|string|array|int
 */
function wpqb_get_plugin_setting( $setting ) {
	switch ( $setting ) {
		case 'single-product-display-button':
			$setting_data = get_option( 'wcqb_show_button_on_single_product' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : 'no';
			break;

		case 'single-product-button-position':
			$setting_data = get_option( 'wcqb_quick_buy_button_position_single_product' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : 'after_add_to_cart';
			break;

		case 'single-product-button-text':
			$setting_data = get_option( 'wcqb_quick_buy_button_text_single_product' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : __( 'Quick Buy', 'wc-quick-buy' );
			break;

		case 'single-product-button-classes':
			$setting_data = get_option( 'wcqb_quick_buy_button_extra_class_single_product' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : '';
			break;

		case 'archive-page-display-button':
			$setting_data = get_option( 'wcqb_show_button_on_archive_pages' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : 'no';
			break;

		case 'archive-page-button-position':
			$setting_data = get_option( 'wcqb_quick_buy_button_position_archive_page' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : 'after_add_to_cart';
			break;

		case 'archive-page-button-text':
			$setting_data = get_option( 'wcqb_archive_page_quick_buy_button_text' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : __( 'Quick Buy', 'wc-quick-buy' );
			break;

		case 'archive-page-button-classes':
			$setting_data = get_option( 'wcqb_quick_buy_button_extra_class_archive_page' );
			$setting_data = ( ! empty( $setting_data ) && ! is_bool( $setting_data ) ) ? $setting_data : '';
			break;

		default:
			$setting_data = -1;
	}

	return $setting_data;
}

/**
 * Return the "Quick Buy" button html.
 *
 * @param int     $product_id Holds the product ID.
 * @return string
 */
function wcqb_display_quick_buy_button_html_single_product( $product_id ) {
	// Get the button text.
	$button_text = wpqb_get_plugin_setting( 'single-product-button-text' );

	// Button extra classes attribute.
	$button_classes = wpqb_get_plugin_setting( 'single-product-button-classes' );
	ob_start();
	?>
	<div class="wcqb-quick-buy-button-wrapper single-product">
		<button type="button" data-product_id="<?php echo esc_attr( $product_id ); ?>" class="single_add_to_cart_button button alt <?php echo esc_attr( $button_classes );?>"><?php echo esc_html( $button_text ); ?></button>
	</div>
	<?php

	return ob_get_clean();
}
