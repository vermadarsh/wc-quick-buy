<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/vermadarsh/
 * @since             1.0.0
 * @package           Wc_Quick_Buy
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Quick Buy
 * Plugin URI:        https://github.com/vermadarsh/
 * Description:       This plugin allows to add quick buy functionality for WooCommerce products.
 * Version:           1.0.0
 * Author:            Adarsh Verma
 * Author URI:        https://github.com/vermadarsh/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-quick-buy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCQB_PLUGIN_VERSION', '1.0.0' );

// Set the current plugin path.
if ( ! defined( 'WCQB_PLUGIN_PATH' ) ) {
	define( 'WCQB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Set the current plugin URL.
if ( ! defined( 'WCQB_PLUGIN_URL' ) ) {
	define( 'WCQB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-quick-buy-activator.php
 */
function activate_wc_quick_buy() {
	require_once WCQB_PLUGIN_PATH . 'includes/class-wc-quick-buy-activator.php';
	Wc_Quick_Buy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-quick-buy-deactivator.php
 */
function deactivate_wc_quick_buy() {
	require_once WCQB_PLUGIN_PATH . 'includes/class-wc-quick-buy-deactivator.php';
	Wc_Quick_Buy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_quick_buy' );
register_deactivation_hook( __FILE__, 'deactivate_wc_quick_buy' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_quick_buy() {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require WCQB_PLUGIN_PATH . 'includes/class-wc-quick-buy.php';
	$plugin = new Wc_Quick_Buy();
	$plugin->run();
}

/**
 * This initiates the plugin.
 * Checks for the required plugins to be installed and active.
 */
function wcqb_plugins_loaded_callback() {
	$active_plugins = get_option( 'active_plugins' );
	$is_wc_active   = in_array( 'woocommerce/woocommerce.php', $active_plugins, true );

	if ( current_user_can( 'activate_plugins' ) && false === $is_wc_active ) {
		add_action( 'admin_notices', 'wcqb_admin_notices_callback' );
	} else {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcqb_plugin_actions_callback' );
		run_wc_quick_buy();
	}
}

add_action( 'plugins_loaded', 'wcqb_plugins_loaded_callback' );

/**
 * This function is called to show admin notices for any required plugin not active || installed.
 */
function wcqb_admin_notices_callback() {
	$this_plugin_data = get_plugin_data( __FILE__ );
	$this_plugin      = $this_plugin_data['Name'];
	$wc_plugin        = 'WooCommerce';
	?>
	<div class="error">
		<p>
			<?php
			/* translators: 1: %s: string tag open, 2: %s: strong tag close, 3: %s: this plugin, 4: %s: woocommerce plugin */
			echo wp_kses_post( sprintf( __( '%1$s%3$s%2$s is ineffective as it requires %1$s%4$s%2$s to be installed and active. Click %5$shere%6$s to install or activate it.', 'wc-quick-buy' ), '<strong>', '</strong>', esc_html( $this_plugin ), esc_html( $wc_plugin ), '<a target="_blank" href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">', '</a>' ) );
			?>
		</p>
	</div>
	<?php
}

/**
 * This function adds custom plugin actions.
 *
 * @param array $links Links array.
 * @return array
 */
function wcqb_plugin_actions_callback( $links ) {
	$this_plugin_links = array(
		'<a title="' . __( 'Settings', 'wc-quick-buy' ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products&section=wc-quick-buy' ) ) . '">' . __( 'Settings', 'wc-quick-buy' ) . '</a>',
	);

	return array_merge( $this_plugin_links, $links );
}
