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
		case 'redirect-to':
			$redirect_to  = get_option( 'wcqb_redirect_to' );
			$setting_data = ( ! empty( $redirect_to ) && ! is_bool( $redirect_to ) ) ? $redirect_to : 'checkout';
			break;

		case 'position':
			$position     = get_option( 'wcqb_quick_buy_button_position' );
			$setting_data = ( ! empty( $position ) && ! is_bool( $position ) ) ? $position : 'after_add_to_cart';
			break;

		case 'button-text':
			$button_text  = get_option( 'wcqb_quick_buy_button_text' );
			$setting_data = ( ! empty( $button_text ) && ! is_bool( $button_text ) ) ? $button_text : __( 'Quick Buy', 'wc-quick-buy' );
			break;

		case 'archive-page-button-text':
			$button_text  = get_option( 'wcqb_archive_page_quick_buy_button_text' );
			$setting_data = ( ! empty( $button_text ) && ! is_bool( $button_text ) ) ? $button_text : __( 'Quick Buy', 'wc-quick-buy' );
			break;

		case 'button-class':
			$button_class = get_option( 'wcqb_quick_buy_button_extra_class' );
			$setting_data = ( ! empty( $button_class ) && ! is_bool( $button_class ) ) ? explode( ',', $button_class ) : array();
			break;

		case 'custom-css':
			$css          = get_option( 'wcqb_quick_buy_custom_css' );
			$setting_data = ( ! empty( $css ) && ! is_bool( $css ) ) ? $css : '';
			break;

		case 'popup-congratulations-text':
			$congrats_text = get_option( 'wcqb_quick_buy_popup_congratulations_text' );
			$setting_data  = ( ! empty( $congrats_text ) && ! is_bool( $congrats_text ) ) ? $congrats_text : '';
			break;

		default:
			$setting_data = -1;
	}

	return $setting_data;
}
