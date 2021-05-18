<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh/
 * @since      1.0.0
 *
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/admin
 * @author     Adarsh Verma <adarsh.srmcem@gmail.com>
 */
class Wc_Quick_Buy_Admin {

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
	 * @since    1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wcqb_admin_enqueue_scripts_callback() {
		// Admin custom CSS.
		wp_enqueue_style(
			$this->plugin_name,
			WCQB_PLUGIN_URL . 'admin/css/wc-quick-buy-admin.css',
			array(),
			filemtime( WCQB_PLUGIN_PATH . 'admin/css/wc-quick-buy-admin.css' )
		);

		// Admin custom JS.
		wp_enqueue_script(
			$this->plugin_name,
			WCQB_PLUGIN_URL . 'admin/js/wc-quick-buy-admin.js',
			array( 'jquery' ),
			filemtime( WCQB_PLUGIN_PATH . 'admin/js/wc-quick-buy-admin.js' ),
			true
		);
	}

	/**
	 * Custom section for admin settings for quick buy.
	 *
	 * @param array $sections Array of WC products tab sections.
	 */
	public function wcqb_woocommerce_get_sections_products_callback( $sections ) {
		$sections['wc-quick-buy'] = __( 'Quick buy', 'wc-quick-buy' );

		return $sections;
	}

	/**
	 * Add custom section to WooCommerce settings products tab.
	 *
	 * @param array $settings Holds the woocommerce settings fields array.
	 * @param array $current_section Holds the wcbogo settings fields array.
	 * @return array
	 */
	public function wcqb_woocommerce_get_settings_products_callback( $settings, $current_section ) {
		// Check the current section is what we want.
		if ( 'wc-quick-buy' === $current_section ) {
			return $this->wcqb_plugin_settings_fields();
		} else {
			return $settings;
		}
	}

	/**
	 * Return the fields for plugin settings.
	 *
	 * @return array
	 */
	private function wcqb_plugin_settings_fields() {
		return apply_filters(
			'woocommerce_wcqb_plugin_settings',
			array(
				array(
					'title' => __( 'Single Product Page', 'wc-quick-buy' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'wcqb_single_product_page_plugin_settings_title',
				),
				array(
					'name'     => __( 'Display Button', 'wc-quick-buy' ),
					'type'     => 'checkbox',
					'desc'     => __( 'Should the "Quick Buy" button be displayed?', 'wc-quick-buy' ),
					'id'       => 'wcqb_show_button_on_single_product',
				),
				array(
					'name'     => __( 'Position', 'wc-quick-buy' ),
					'type'     => 'select',
					'options'  => array(
						'before_add_to_cart' => __( 'Before Add to Cart', 'wc-quick-buy' ),
						'after_add_to_cart'  => __( 'After Add to Cart', 'wc-quick-buy' ),
					),
					'class'    => 'wc-enhanced-select',
					'desc'     => __( 'This sets the position of the "Quick Buy" button. Default: After add to cart.', 'wc-quick-buy' ),
					'desc_tip' => true,
					'default'  => '',
					'id'       => 'wcqb_quick_buy_button_position_single_product',
				),
				array(
					'name'        => __( 'Button Text', 'wc-quick-buy' ),
					'type'        => 'text',
					'class'       => 'wcqb-quick-buy-button-text',
					'desc'        => __( 'This holds the "Quick Buy" button text.', 'wc-quick-buy' ),
					'desc_tip'    => true,
					'id'          => 'wcqb_quick_buy_button_text_single_product',
					'placeholder' => __( 'Default: Quick Buy', 'wc-quick-buy' ),
				),
				array(
					'name'        => __( 'Button Extra Classes', 'wc-quick-buy' ),
					'type'        => 'textarea',
					'class'       => 'wcqb-quick-buy-button-extra-class',
					'desc'        => __( 'This holds the "Quick Buy" button extra class attribute.', 'wc-quick-buy' ),
					'desc_tip'    => true,
					'id'          => 'wcqb_quick_buy_button_extra_class_single_product',
					'placeholder' => __( 'Custom classes separated by space', 'wc-quick-buy' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'wcqb_single_product_page_plugin_settings_title',
				),
				array(
					'title' => __( 'Shop & Category Pages', 'wc-quick-buy' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'wcqb_archive_page_plugin_settings_title',
				),
				array(
					'name'     => __( 'Display Button', 'wc-quick-buy' ),
					'type'     => 'checkbox',
					'desc'     => __( 'Should the "Quick Buy" button be displayed?', 'wc-quick-buy' ),
					'id'       => 'wcqb_show_button_on_archive_pages',
				),
				array(
					'name'     => __( 'Position', 'wc-quick-buy' ),
					'type'     => 'select',
					'options'  => array(
						'before_add_to_cart' => __( 'Before Add to Cart', 'wc-quick-buy' ),
						'after_add_to_cart'  => __( 'After Add to Cart', 'wc-quick-buy' ),
					),
					'class'    => 'wc-enhanced-select',
					'desc'     => __( 'This sets the position of the "Quick Buy" button. Default: After add to cart.', 'wc-quick-buy' ),
					'desc_tip' => true,
					'default'  => '',
					'id'       => 'wcqb_quick_buy_button_position_archive_page',
				),
				array(
					'name'        => __( 'Button Text', 'wc-quick-buy' ),
					'type'        => 'text',
					'class'       => 'wcqb-archive-page-quick-buy-button-text',
					'desc'        => __( 'This holds the "Quick Buy" button text.', 'wc-quick-buy' ),
					'desc_tip'    => true,
					'id'          => 'wcqb_archive_page_quick_buy_button_text',
					'placeholder' => __( 'Default: Quick Buy', 'wc-quick-buy' ),
				),
				array(
					'name'        => __( 'Button Extra Classes', 'wc-quick-buy' ),
					'type'        => 'textarea',
					'class'       => 'wcqb-quick-buy-button-extra-class',
					'desc'        => __( 'This holds the "Quick Buy" button extra class attribute.', 'wc-quick-buy' ),
					'desc_tip'    => true,
					'id'          => 'wcqb_quick_buy_button_extra_class_archive_page',
					'placeholder' => __( 'Custom classes separated by space', 'wc-quick-buy' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'wcqb_archive_page_plugin_settings_title',
				),
			)
		);
	}
}
