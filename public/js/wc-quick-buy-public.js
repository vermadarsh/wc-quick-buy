jQuery( document ).ready( function( $ ) {
	'use strict';

	/**
	 * Localized variables.
	 */
	var {
		single_page_quick_buy_button_text,
		archive_page_quick_buy_button_text,
	} = WCQB_Public_JS_Obj;

	var single_product_quick_buy_button_text = single_page_quick_buy_button_text;

	/**
	 * Enable/disable the quick buy button on variable product page.
	 */
	$( document ).on( 'change', 'table.variations select', function() {
		var add_to_cart_div_class = $( '.woocommerce-variation-add-to-cart' ).attr( 'class' );
		var is_add_to_cart_enabled = add_to_cart_div_class.indexOf( 'woocommerce-variation-add-to-cart-enabled' );
		
		if ( -1 === is_add_to_cart_enabled ) {
			block_element( $( '.wcqb_quick_buy_button' ) );
			$( '.wcqb_quick_buy_button' ).html( archive_page_quick_buy_button_text );
		} else {
			var variation_id = $( 'input[name="variation_id"]' ).val();
			unblock_element( $( '.wcqb_quick_buy_button' ) );

			// Set the variation ID to be added to cart.
			$( '.wcqb_quick_buy_button' ).data( 'pid', variation_id );

			// Set the button text.
			single_page_quick_buy_button_text = single_product_quick_buy_button_text;
			var variation_price = $( '.woocommerce-variation-price' ).html();
			single_page_quick_buy_button_text = single_page_quick_buy_button_text.replace( '[price]', variation_price );
			$( '.wcqb_quick_buy_button' ).html( single_page_quick_buy_button_text );
		}
	} );

	/**
	 * Do buy now.
	 * Web view.
	 */
	$( document ).on( 'click', '.wcqb_quick_buy_button.dont_open_popup', function( e ) {
		e.preventDefault();

		var redirect_to = $( this ).data( 'redirectto' );
		var quantity = $( '.quantity input.qty[type="number"]' ).val();

		// Exit, if the quantity is invalid.
		if ( -1 === is_valid_number( quantity ) ) {
			console.log( 'quantity is invalid: ', quantity );
			return false;
		}

		// Redirect if everything works fine.
		window.location.href = redirect_to + '&quantity=' + quantity;
	} );

	/**
	 * Do buy now.
	 * Mobile view.
	 */
	$( document ).on( 'click', '.wcqb_quick_buy_button.open_popup', function( e ) {
		e.preventDefault();
		$( '#buynow_popup' ).show();
		setTimeout( function() {
			console.log( 'heelllooooo' );
			startConfetti();
		}, 500 );
	
		setTimeout( function() {
			stopConfetti();
		}, 4500 );
	} );

	/**
	 * Close the buy now modal.
	 */
	var buynow_popup = document.getElementById( 'buynow_popup' );
	window.onclick = function( evt ) {
		if ( evt.target == buynow_popup ) {
			buynow_popup.style.display = 'none';
		}
	}

	/**
	 * Hide the modal when Esc. key is pressed.
	 */
	$( document ).on( 'keyup', function ( evt ) {
		if ( 27 === evt.keyCode ) {
			$( '#buynow_popup' ).hide();
		}
	} );

	/**
	 * Use email for quick buy popup.
	 */
	$( document ).on( 'click', '#wcqb-quick-buy-popup-use-email', function() {
		// Change input field attributes.
		$( '#quickbuy-customer-phone' ).attr( 'type', 'email' );
		$( '#quickbuy-customer-phone' ).attr( 'name', 'quickbuy-customer-email' );
		$( '#quickbuy-customer-phone' ).attr( 'placeholder', 'john.doe@example.com' );
		
		// Change label attirbutes.
		$( '#quickbuy-customer-phone' ).prev( 'label' ).attr( 'quickbuy-customer-email' );
		$( '#quickbuy-customer-phone' ).prev( 'label' ).text( 'Email ID' );
		
		// Finally change the ID attribute.
		$( '#quickbuy-customer-phone' ).attr( 'id', 'quickbuy-customer-email' );

		// Change the anchor tag text for email.
		$( '#wcqb-quick-buy-popup-use-email' ).text( 'Use Mobile Number' );
		$( '#wcqb-quick-buy-popup-use-email' ).attr( 'id', 'wcqb-quick-buy-popup-use-phone' );

		// For icon.
		$( '.pop_field' ).addClass( 'customer-email-active' ).removeClass( 'customer-phone-active' );
	} );

	/**
	 * Use phone for quick buy popup.
	 */
	$( document ).on( 'click', '#wcqb-quick-buy-popup-use-phone', function() {
		// Change input field attributes.
		$( '#quickbuy-customer-email' ).attr( 'type', 'tel' );
		$( '#quickbuy-customer-email' ).attr( 'name', 'quickbuy-customer-phone' );
		$( '#quickbuy-customer-email' ).attr( 'placeholder', '+91-987654321' );

		// Change label attirbutes.
		$( '#quickbuy-customer-email' ).prev( 'label' ).attr( 'quickbuy-customer-phone' );
		$( '#quickbuy-customer-email' ).prev( 'label' ).text( 'Mobile Number' );
		
		// Finally change the ID attribute.
		$( '#quickbuy-customer-email' ).attr( 'id', 'quickbuy-customer-phone' );

		// Change the anchor tag text for phone.
		$( '#wcqb-quick-buy-popup-use-phone' ).text( 'Use Email ID' );
		$( '#wcqb-quick-buy-popup-use-phone' ).attr( 'id', 'wcqb-quick-buy-popup-use-email' );

		// For icon.
		$( '.pop_field' ).addClass( 'customer-phone-active' ).removeClass( 'customer-email-active' );
	} );

	

	/**
	 * Check if a number is valid.
	 * 
	 * @param {number} data 
	 */
	function is_valid_number( data ) {
		if ( '' === data || undefined === data || isNaN( data ) || 0 === data ) {
			return -1;
		} else {
			return 1;
		}
	}

	/**
	 * Block element.
	 *
	 * @param {string} element 
	 */
	function block_element( element ) {
		element.addClass( 'non-clickable' );
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element 
	 */
	function unblock_element( element ) {
		element.removeClass( 'non-clickable' );
	}
} );
