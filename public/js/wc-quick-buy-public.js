jQuery( document ).ready( function( $ ) {
	'use strict';

	/**
	 * Localized variables.
	 */
	var ajaxurl = WCQB_Public_JS_Obj.ajaxurl;

	/**
	 * Enable/disable the quick buy button on variable product page.
	 */
	$( document ).on( 'change', 'table.variations select', function() {
		var add_to_cart_div_class = $( '.woocommerce-variation-add-to-cart' ).attr( 'class' );
		var is_add_to_cart_enabled = add_to_cart_div_class.indexOf( 'woocommerce-variation-add-to-cart-enabled' );
		
		if ( -1 === is_add_to_cart_enabled ) {
			block_element( $( '.wcqb-quick-buy-button-wrapper button' ) );
			return false;
		}

		// Set the variation ID as the product ID for quick buy button.
		var variation_id = parseInt( $( 'input[name="variation_id"]' ).val() );
		unblock_element( $( '.wcqb-quick-buy-button-wrapper button' ) );

		// Set the variation ID to be added to cart.
		$( '.wcqb-quick-buy-button-wrapper button' ).data( 'product_id', variation_id );
	} );

	/**
	 * Quick buy from single product page.
	 */
	$( document ).on( 'click', '.wcqb-quick-buy-button-wrapper button', function() {
		var this_button     = $( this );
		var quantity        = parseInt( $( 'input[name="quantity"]' ).val() );
		quantity            = ( -1 === is_valid_number( quantity ) ) ? 1 : quantity;
		var product_type    = this_button.parent( 'div' ).data( 'product_type' );
		var product_id      = this_button.data( 'product_id' );
		var quick_buy_items = [];

		// Check for grouped product type.
		if ( 1 === is_valid_string( product_type ) && 'grouped' === product_type ) {
			// Get the products that need to be added to custom cart session.
			$( 'table.woocommerce-grouped-product-list tbody tr' ).each( function() {
				var this_tr    = $( this );

				// Get the quantity now.
				var quantity = parseInt( this_tr.find( 'td.woocommerce-grouped-product-list-item__quantity div.quantity input[type="number"]' ).val() );

				// Check for it's validity.
				if ( 1 === is_valid_number( quantity ) ) {
					// Get the product ID.
					var tr_id_attr = this_tr.attr( 'id' );
					var product_id = parseInt( tr_id_attr.replace( 'product-', '' ) );
					
					// Push the items in array.
					quick_buy_items.push(
						{
							'product_id': product_id,
							'quantity': quantity,
						}
					);
				}
			} );
		} else {
			// Push the items in array.
			quick_buy_items.push(
				{
					'product_id': product_id,
					'quantity': quantity,
				}
			);
		}

		// Return, if the items array is empty.
		if ( 0 === quick_buy_items.length ) {
			return false;
		}

		// Set this items array to cart session and open address modal.
		wcqb_save_quick_buy_items_session( quick_buy_items, this_button );
	} );

	/**
	 * Set the items in cart session.
	 *
	 * @param {array} quick_buy_items Holds the items array.
	 * @param {object} this_button Holds the button element object.
	 */
	function wcqb_save_quick_buy_items_session( quick_buy_items, this_button ) {
		// Block the element.
		block_element( this_button );

		// Send the AJAX for clearing the log.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'save_cart_session',
				items: quick_buy_items,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 'log-cleared' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( this_button );

				// Change the button text.
				this_button.text( this_button_text );

				// Erase the text from the textarea.
				$( '.rothco-log-holder' ).val( '' );
			},
		} );
	}

	jQuery(".popup-btn").click(function(){
		jQuery(".cms-popup").addClass("open");
		jQuery("body").addClass("pop-open");
	});

	jQuery(".close").click(function(){
		jQuery(".cms-popup").removeClass("open");
		jQuery("body").removeClass("pop-open");
	});

	jQuery(".address-box").click(function(){
		jQuery(".address-popup").addClass("open");
		jQuery("body").addClass("pop-open-2");
	});

	jQuery(".back").click(function(){
		jQuery(".address-popup").removeClass("open");
		jQuery("body").removeClass("pop-open-2");
	});

	jQuery(".close-2").click(function(){
		jQuery(".address-popup").removeClass("open");
		jQuery("body").removeClass("pop-open-2");
	});

	jQuery(".location").click(function(){
		jQuery(".loaction").addClass("open");
		jQuery("body").addClass("pop-open-2");
	});

	jQuery(".back").click(function(){
		jQuery(".loaction").removeClass("open");
		jQuery("body").removeClass("pop-open-2");
	});

	jQuery(".close-2").click(function(){
		jQuery(".loaction").removeClass("open");
		jQuery("body").removeClass("pop-open-2");
	});

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
