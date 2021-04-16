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

		// Add this JS for mobile devices and on product pages only.
		if ( is_product() ) {
			// Confetti script.
			wp_enqueue_script(
				'wcqb-confetti-js',
				WCQB_PLUGIN_URL . 'public/js/confetti.js',
				array( 'jquery' ),
				time(),
				true
			);
		}

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
				'single_page_quick_buy_button_text'  => wpqb_get_plugin_setting( 'button-text' ),
				'archive_page_quick_buy_button_text' => wpqb_get_plugin_setting( 'archive-page-button-text' ),
			)
		);
	}

	/**
	 * Place the quick buy button before/after the add to cart button.
	 */
	public function wcqb_woocommerce_product_add_to_cart_button_callback() {
		global $product;
		$product_type  = $product->get_type();

		if ( 'simple' === $product_type ) {
			echo $this->wcqb_render_quick_buy_simple_product( $product );
		} elseif ( 'variable' === $product_type ) {
			echo $this->wcqb_render_quick_buy_variable_product( $product );
		}
	}

	/**
	 * Render the quick buy button html for simple products.
	 *
	 * @param object $product Holds the WooCommerce product object.
	 */
	private function wcqb_render_quick_buy_simple_product( $product ) {
		$regular_price = $product->get_regular_price();
		$sale_price    = $product->get_sale_price();
		$price         = ( ! empty( $sale_price ) ) ? wc_price( $sale_price ) : wc_price( $regular_price );

		// Set the button extra class.
		$button_class = wpqb_get_plugin_setting( 'button-class' );

		if ( ! empty( $button_class ) ) {
			$button_class = implode( ' ', $button_class );
		} else {
			$button_class = '';
		}

		// Button text.
		$button_text = wpqb_get_plugin_setting( 'button-text' );
		$button_text = str_replace( '[price]', $price, $button_text );

		ob_start();
		if ( ! wp_is_mobile() ) {
			// Web view.
			?>
			<a
				class="wcqb_quick_buy_button open_popup single_add_to_cart_button button alt <?php echo esc_html( $button_class ); ?>"
				href="#"
				data-redirectto="<?php echo esc_url( home_url( '?action=wcqb-quick-buy&pid=' . $product->get_id() ) ); ?>"
				title="<?php esc_html_e( 'Buy Now', 'wc-quick-buy' ); ?>"
			><?php echo wp_kses_post( $button_text ); ?></a>
			<?php
		} else {
			// Mobile view.
			?>
			<a
				class="wcqb_quick_buy_button open_popup single_add_to_cart_button button alt <?php echo esc_html( $button_class ); ?>"
				href="#"
				title="<?php esc_html_e( 'Buy Now', 'wc-quick-buy' ); ?>"
			><?php echo wp_kses_post( $button_text ); ?></a>
			<?php
		}
		echo wp_kses(
			ob_get_clean(),
			array(
				'a' => array(
					'href'            => array(),
					'class'           => array(),
					'data-redirectto' => array(),
				),
				'span'   => array(
					'class'  => array(),
				),
				'i'      => array(
					'class'       => array(),
					'aria-hidden' => array(),
				),
			)
		);
	}

	/**
	 * Render the quick buy button html for variable products.
	 *
	 * @param object $product Holds the WooCommerce product object.
	 */
	private function wcqb_render_quick_buy_variable_product( $product ) {
		// Button class.
		$button_class   = wpqb_get_plugin_setting( 'button-class' );
		$button_class[] = 'non-clickable'; // Adding this clss so the button is not clickable on page load, until any variation is selected.

		if ( ! empty( $button_class ) ) {
			$button_class = implode( ' ', $button_class );
		} else {
			$button_class = '';
		}

		// Button text.
		$button_text = wpqb_get_plugin_setting( 'archive-page-button-text' );

		ob_start();
		if ( ! wp_is_mobile() ) {
			// Web view.
			?>
			<a
				class="wcqb_quick_buy_button dont_open_popup single_add_to_cart_button button alt <?php echo esc_html( $button_class ); ?>"
				href="#"
				data-redirectto="<?php echo esc_url( home_url( '?action=wcqb-quick-buy&pid=' . $product->get_id() ) ); ?>"
				title="<?php esc_html_e( 'Buy Now', 'wc-quick-buy' ); ?>"
			><?php echo wp_kses_post( $button_text ); ?></a>
			<?php
		} else {
			// Mobile view.
			?>
			<a
				class="wcqb_quick_buy_button open_popup single_add_to_cart_button button alt <?php echo esc_html( $button_class ); ?>"
				href="#"
				title="<?php esc_html_e( 'Buy Now', 'wc-quick-buy' ); ?>"
			><?php echo wp_kses_post( $button_text ); ?></a>
			<?php
		}
		echo wp_kses(
			ob_get_clean(),
			array(
				'a' => array(
					'href'            => array(),
					'class'           => array(),
					'data-redirectto' => array(),
				),
				'span'   => array(
					'class'  => array(),
				),
				'i'      => array(
					'class'       => array(),
					'aria-hidden' => array(),
				),
			)
		);
	}

	/**
	 * Add custom CSS provided at the admin end.
	 */
	public function wcqb_wp_head_callback() {
		$custom_css = wpqb_get_plugin_setting( 'custom-css' );
		
		if ( empty( $custom_css ) ) {
			return;
		}

		echo "<style id='wcqb_custom_css'>{$custom_css}</style>";
	}

	/**
	 * Do something on WordPress initialization.
	 */
	public function wcqb_wp_callback() {
		// Execute the same on web view only.
		if ( ! wp_is_mobile() ) {
			$this->quick_buy_web_view();
		}

		// Set customer session based on buy now popup data.
		$popup_submission = filter_input( INPUT_POST, 'wcqb_submit_form', FILTER_SANITIZE_STRING );

		// Check if the popup is submitted.
		if ( isset( $popup_submission ) && 'yes' === $popup_submission ) {
			// Add the product to the cart.
			$this->quick_buy_mobile_view();

			// Set customer session data now.
			$customer_data = WC()->session->get( 'customer' );

			// Check if we received phone number or email.
			$billing_phone = filter_input( INPUT_POST, 'quickbuy-customer-phone', FILTER_SANITIZE_STRING );
			$billing_email = filter_input( INPUT_POST, 'quickbuy-customer-email', FILTER_SANITIZE_STRING );

			// If billing phone is provided.
			if ( ! empty( $billing_phone ) && null !== $billing_phone ) {
				$customer_data['phone'] = $billing_phone;
			} elseif ( ! empty( $billing_email ) && null !== $billing_email ) {
				$customer_data['email'] = $billing_email;
			}

			// Set the customer session.
			WC()->session->set( 'customer', $customer_data );
		}
	}

	/**
	 *
	 */
	private function quick_buy_web_view() {
		// Check for the action.
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		if ( 'wcqb-quick-buy' !== $action ) {
			return;
		}

		// Process the quick buy now.
		$product_id = (int) filter_input( INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT );
		$quantity   = (int) filter_input( INPUT_GET, 'quantity', FILTER_SANITIZE_NUMBER_INT );

		// Exit, if the product ID is invalid.
		if ( empty( $product_id ) || ! is_int( $product_id ) || 0 === $product_id ) {
			echo 0;
			wp_die();
		}

		/**
		 * Before clearing cart for quick buy.
		 *
		 * This hook fires right before clearing cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_before_quick_buy_before_clear_cart', $product_id );

		// Clear the cart before adding any product to cart.
		WC()->cart->empty_cart();

		/**
		 * After clearing cart for quick buy.
		 *
		 * This hook fires right after clearing cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_before_quick_buy_after_clear_cart', $product_id );

		// Add the product to cart.
		WC()->cart->add_to_cart( $product_id, $quantity );

		/**
		 * After adding product to cart quick buy.
		 *
		 * This hook fires right after adding product to cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_after_quick_buy', $product_id );

		// Get the redirect to.
		$redirect_to = wpqb_get_plugin_setting( 'redirect-to' );
		$redirect_to = ( 'checkout' === $redirect_to ) ? esc_url( wc_get_checkout_url() ) : esc_url( wc_get_cart_url() );

		// Do the redirect now.
		wp_safe_redirect( $redirect_to );
		exit( 0 );
	}

	private function quick_buy_mobile_view() {
		// Process the add to cart functionality.
		$quantity   = 1; //(int) filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_STRING );
		$product_id = (int) filter_input( INPUT_POST, 'wcqb_product_id', FILTER_SANITIZE_STRING );

		// Exit, if the product ID is invalid.
		if ( empty( $product_id ) || ! is_int( $product_id ) || 0 === $product_id ) {
			wp_die( __( 'Invalid product ID. Cannot proceed.', 'wc-quick-buy' ) );
		}

		/**
		 * Before clearing cart for quick buy.
		 *
		 * This hook fires right before clearing cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_before_quick_buy_before_clear_cart_popup', $product_id );

		// Clear the cart before adding any product to cart.
		WC()->cart->empty_cart();

		/**
		 * After clearing cart for quick buy.
		 *
		 * This hook fires right after clearing cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_before_quick_buy_after_clear_cart_popup', $product_id );

		// Add the product to cart.
		WC()->cart->add_to_cart( $product_id, $quantity );

		/**
		 * After adding product to cart quick buy.
		 *
		 * This hook fires right after adding product to cart for quick buy.
		 *
		 * @param int $product_id Holds the product ID.
		 */
		do_action( 'wcqb_after_quick_buy_popup', $product_id );

		/**
		 * Save the customer data in the database.
		 * Email or Phone Number
		 */
		global $wpdb;
		$billing_phone = filter_input( INPUT_POST, 'quickbuy-customer-phone', FILTER_SANITIZE_STRING );
		$billing_email = filter_input( INPUT_POST, 'quickbuy-customer-email', FILTER_SANITIZE_STRING );
		$table_name = $wpdb->prefix . 'flicap_checkout_data';

		// If billing phone is provided.
		if ( ! empty( $billing_phone ) && null !== $billing_phone ) {
			// Check if entry exists by the phone.
			$query = "SELECT `id` FROM `$table_name` WHERE `phone` = '{$billing_phone}'";
			$row   = $wpdb->get_row( $query, ARRAY_A );

			// Check for existing data.
			if ( ! empty( $row['id'] ) ) {
				// Update the rows.
				$id = $row['id'];
				$wpdb->update(
					$table_name,
					array(
						'phone'      => $billing_phone,
						'datetime'   => time(),
						'cart_items' => $this->wcqb_get_cart_string(),
					),
					array(
						'id' => $id
					)
				);
			} else {
				// Insert the row.
				$wpdb->insert(
					$table_name,
					array(
						'name'       => '',
						'phone'      => $billing_phone,
						'email'      => '',
						'address'    => '',
						'postcode'   => '',
						'datetime'   => time(),
						'cart_items' => $this->wcqb_get_cart_string(),
					)
				);
			}

			// Send email to admin about this entry.
			$to      = array(
				'hiren@flicap.com',
				'mansib.sky@gmail.com',
			);
			$subject = __( 'New Customer On Checkout', 'save-checkout-data' );
			ob_start();
			?>
			<div>
				<p>Hello Admin User,</p>
				<p>New customer has arrived on checkout page. Following are the details:</p>
				<p>Phone: <?php echo $billing_phone; ?></p>
				<p>Cart items: <?php echo $this->wcqb_get_cart_string(); ?></p>
				<p>Thank You!</p>
			</div>
			<?php
			$mail_content = ob_get_clean();
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail( $to, $subject, $mail_content, $headers );
		}

		// If billing email is provided.
		if ( ! empty( $billing_email ) && null !== $billing_email ) {
			// Check if entry exists by the email.
			$query = "SELECT `id` FROM `$table_name` WHERE `email` = '{$billing_email}'";
			$row   = $wpdb->get_row( $query, ARRAY_A );

			// Check for existing data.
			if ( ! empty( $row['id'] ) ) {
				// Update the rows.
				$id = $row['id'];
				$wpdb->update(
					$table_name,
					array(
						'email'      => $billing_email,
						'datetime'   => time(),
						'cart_items' => $this->wcqb_get_cart_string(),
					),
					array(
						'id' => $id
					)
				);
			} else {
				// Insert the row.
				$wpdb->insert(
					$table_name,
					array(
						'name'       => '',
						'phone'      => '',
						'email'      => $billing_email,
						'address'    => '',
						'postcode'   => '',
						'datetime'   => time(),
						'cart_items' => $this->wcqb_get_cart_string(),
					)
				);
			}
		}
	}

	/**
	 * Get the cart string.
	 *
	 * @return string
	 */
	function wcqb_get_cart_string() {
		$cart_items     = WC()->cart->get_cart();
		$cart_items_arr = array();

		if ( ! empty( $cart_items ) && is_array( $cart_items ) ) {
			foreach ( $cart_items as $cart_item ) {
				$product_id = ( 0 === $cart_item['variation_id'] ) ? $cart_item['product_id'] : $cart_item['variation_id'];
				$wc_product = wc_get_product( $product_id );
				$product_sku = $wc_product->get_sku();
				$cart_items_arr[] = "(ID: {$product_id} | SKU: {$product_sku})";
			}
		}

		return implode( ', ', $cart_items_arr );
	}

	/**
	 * Add quick buy popup HTML.
	 */
	function wcqb_wp_footer_callback() {
		global $product;

		// Return if it's not the product single page.
		if ( ! is_product() ) {
			return;
		}

		// Get the product prices.
		$regular_price = $product->get_regular_price();
		$sale_price    = $product->get_sale_price();

		if ( empty( $sale_price ) ) {
			$congratulations_text = __( 'Congratulations! You\'re just a step away..', 'wc-quick-buy' );
		} else {
			// Calculate the difference now.
			$difference = (int) ( $regular_price - $sale_price );

			if ( 0 === $difference ) {
				$congratulations_text = __( 'Congratulations! You\'re just a step away..', 'wc-quick-buy' );
			} else {
				$congratulations_text = wpqb_get_plugin_setting( 'popup-congratulations-text' );
				$congratulations_text = str_replace( '[saved_amount]', wc_price( $difference ), $congratulations_text );
			}
		}

		// Product image.
		$product_image_id  = $product->get_image_id();
		$product_image_url = wc_placeholder_img_src();

		if ( ! empty( $product_image_id ) ) {
			$product_image_url = wp_get_attachment_url( $product_image_id );
		}

		// Set the popup HTML now.
		ob_start();
		?>
		<div id="buynow_popup" class="down_modal">
			<div class="down_modal_content">    
				<div class="direct_buynow_section">
					<label for=""><?php echo wp_kses_post( $congratulations_text ); ?></label>
					<img src="<?php echo esc_url( $product_image_url );  ?>" alt="" class="pop_image_small">
					<p><?php esc_html_e( 'Enter details below to continue...', 'wc-quick-buy' ); ?></p>
					<form action="/checkout/" method="POST">
						<div class="pop_field customer-phone-active">
							<label for="quickbuy-customer-phone"><?php esc_html_e( 'Mobile number', 'wc-quick-buy' ); ?></label>
							<input required type="tel" name="quickbuy-customer-phone" id="quickbuy-customer-phone" placeholder="+91-987654321">							
						</div>
						<a href="javascript:void(0);" id="wcqb-quick-buy-popup-use-email"><?php esc_html_e( 'Use Email ID', 'wc-quick-buy' ); ?></a>
						<a href="" class="enquiry_msg"><i class="fab fa-whatsapp"></i></a>
						<div class="btn-pading-left"><button name="wcqb_quickbuy_popup_submit" type="submit" class="button single_add_to_cart_button"><?php echo sprintf( __( '%1$s Checkout', 'wc-quick-buy' ), '<i class="fas fa-money-bill"></i>' ); ?></button></div>
						<input type="hidden" name="wcqb-buynow-popup-nonce" value="<?php echo wp_create_nonce( 'wcqb-popup-nonce' ); ?>" />
						<input type="hidden" name="wcqb_product_id" value="<?php echo esc_html( $product->get_id() ); ?>" />
						<input type="hidden" name="wcqb_submit_form" value="yes" />
					</form>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
}
