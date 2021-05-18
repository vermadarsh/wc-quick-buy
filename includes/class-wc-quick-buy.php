<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/vermadarsh/
 * @since      1.0.0
 *
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Quick_Buy
 * @subpackage Wc_Quick_Buy/includes
 * @author     Adarsh Verma <adarsh.srmcem@gmail.com>
 */
class Wc_Quick_Buy {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Quick_Buy_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = ( defined( 'WCQB_PLUGIN_VERSION' ) ) ? WCQB_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'wc-quick-buy';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Quick_Buy_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Quick_Buy_i18n. Defines internationalization functionality.
	 * - Wc_Quick_Buy_Admin. Defines all hooks for the admin area.
	 * - Wc_Quick_Buy_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once WCQB_PLUGIN_PATH . 'includes/class-wc-quick-buy-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once WCQB_PLUGIN_PATH . 'includes/class-wc-quick-buy-i18n.php';

		// The class responsible for defining all custom functions used throughout the plugin.
		require_once WCQB_PLUGIN_PATH . 'includes/wc-quick-buy-functions.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once WCQB_PLUGIN_PATH . 'admin/class-wc-quick-buy-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once WCQB_PLUGIN_PATH . 'public/class-wc-quick-buy-public.php';

		$this->loader = new Wc_Quick_Buy_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Quick_Buy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wc_Quick_Buy_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wc_Quick_Buy_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wcqb_admin_enqueue_scripts_callback' );
		$this->loader->add_filter( 'woocommerce_get_sections_products', $plugin_admin, 'wcqb_woocommerce_get_sections_products_callback' );
		$this->loader->add_filter( 'woocommerce_get_settings_products', $plugin_admin, 'wcqb_woocommerce_get_settings_products_callback', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public   = new Wc_Quick_Buy_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wcqb_wp_enqueue_scripts_callback' );
		$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $plugin_public, 'wcqb_woocommerce_before_add_to_cart_button_callback' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_public, 'wcqb_woocommerce_after_add_to_cart_button_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Quick_Buy_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
