jQuery( document ).ready( function( $ ) {
	'use strict';

	/**
	 * Localized variables.
	 */
	var ajaxurl = WCQB_Public_JS_Obj.ajaxurl;

	var single_product_quick_buy_button_text = 'hello';

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
	 * Check if a string is valid.
	 *
	 * @param {string} $data
	 */
	 function is_valid_string( data ) {
		if ( '' === data || undefined === data || ! isNaN( data ) || 0 === data ) {
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
