<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh/
 * @since      1.0.0
 *
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/public
 * @author     Adarsh Verma <adarsh.srmcem@gmail.com>
 */
class Wc_Quick_Buy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wcqb_wp_enqueue_scripts_callback() {
		// Public custom CSS.
		wp_enqueue_style(
			$this->plugin_name,
			WCQB_PLUGIN_URL . 'public/css/wc-quick-buy-public.css',
			array(),
			filemtime( WCQB_PLUGIN_PATH . 'public/css/wc-quick-buy-public.css' )
		);

		// Public custom JS.
		wp_enqueue_script(
			$this->plugin_name,
			WCQB_PLUGIN_URL . 'public/js/wc-quick-buy-public.js',
			array( 'jquery' ),
			filemtime( WCQB_PLUGIN_PATH . 'public/js/wc-quick-buy-public.js' ),
			true
		);

		// Public localization variables.
		wp_localize_script(
			$this->plugin_name,
			'WCQB_Public_JS_Obj',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Place the "Quick Buy" button before the "Add to Cart" button.
	 */
	public function wcqb_woocommerce_before_add_to_cart_button_callback() {
		global $product;

		// Get the product type.
		$product_type = $product->get_type();

		// Return, if the product type is external.
		if ( 'external' === $product_type ) {
			return;
		}

		// Should the button be displayed or not.
		$display_button = wpqb_get_plugin_setting( 'single-product-display-button' );

		// Return, if the button is not to be displayed.
		if ( ! empty( $display_button ) && 'no' === $display_button ) {
			return;
		}

		// Get the button position.
		$button_position = wpqb_get_plugin_setting( 'single-product-button-position' );

		// Return, if the button is to be displayed after the "Add to Cart" button.
		if ( ! empty( $button_position ) && 'after_add_to_cart' === $button_position ) {
			return;
		}

		// Display the button now.
		echo wcqb_display_quick_buy_button_html_single_product( $product->get_id() );
	}

	/**
	 * Place the "Quick Buy" button after the "Add to Cart" button.
	 */
	public function wcqb_woocommerce_after_add_to_cart_button_callback() {
		global $product;

		// Get the product type.
		$product_type = $product->get_type();

		// Return, if the product type is external.
		if ( 'external' === $product_type ) {
			return;
		}

		// Should the button be displayed or not.
		$display_button = wpqb_get_plugin_setting( 'single-product-display-button' );

		// Return, if the button is not to be displayed.
		if ( ! empty( $display_button ) && 'no' === $display_button ) {
			return;
		}

		// Get the button position.
		$button_position = wpqb_get_plugin_setting( 'single-product-button-position' );

		// Return, if the button is to be displayed after the "Add to Cart" button.
		if ( ! empty( $button_position ) && 'before_add_to_cart' === $button_position ) {
			return;
		}

		// Display the button now.
		echo wcqb_display_quick_buy_button_html_single_product( $product->get_id() );
	}

	/**
	 * Place the "Quick Buy" button on the shop and other archive pages.
	 */
	public function wcqb_woocommerce_after_shop_loop_item_callback() {
		global $product;

		// Get the product type.
		$product_type = $product->get_type();

		// Return, if the product type is not simple.
		if ( 'simple' !== $product_type ) {
			return;
		}

		// Should the button be displayed or not.
		$display_button = wpqb_get_plugin_setting( 'archive-page-display-button' );

		// Return, if the button is not to be displayed.
		if ( ! empty( $display_button ) && 'no' === $display_button ) {
			return;
		}

		// Display the button now.
		echo wcqb_display_quick_buy_button_html_archive_page( $product->get_id() );
	}

	/**
	 * AJAX served to save the items in cart session.
	 */
	public function wcqb_save_cart_session_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action doesn't match.
		if ( empty( $action ) || 'save_cart_session' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted items.
		$posted_array = filter_input_array( INPUT_POST );
		$items        = $posted_array['items'];

		// Exit, if the items array is empty or invalid.
		if ( empty( $items ) || ! is_array( $items ) ) {
			echo -1;
			wp_die();
		}

		// Set the session.
		$session_data = array(
			'items' => $items,
		);
		WC()->session->set( 'wcqb_data', $session_data );

		// Get the customer's address data.
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		}
	}

	/**
	 * Add quick buy popup HTML.
	 */
	public function wcqb_wp_footer_callback() {
		global $product;

		// Return if it's not the product single page.
		if ( ! is_product() ) {
			return;
		}

		// Include the modals.
	}
}


