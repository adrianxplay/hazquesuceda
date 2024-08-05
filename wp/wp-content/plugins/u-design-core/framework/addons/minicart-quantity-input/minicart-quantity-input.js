/**
 * Alpha Dependent Plugin - Quantity Input in MiniCart
 * 
 * @since 1.0
 */

'use script';

window.theme || ( window.theme = {} );

( function ( $ ) {
    var MiniCartQuantityInput = {
        /**
         * Initialize
         *
         * @since 1.2
         */
        init: function () {
            // Register events.
            $( 'body' )
                .on( 'change', '.cart_list.mini-list .qty', this.qtyChange )
        },

        /**
         * Change qty of cart item
         *
         * @since 1.2
         */
        qtyChange: function () {
            var $cartItem = $( this ).closest( '.mini_cart_item' );

            if ( $cartItem.find( '.w-loading' ).length ) {
                return;
            }

            theme.doLoading( $cartItem, 'small' );

            $.post( alpha_vars.ajax_url, {
                action: 'alpha_update_cart_item',
                nonce: alpha_vars.nonce,
                cart_item_key: $( this ).attr( 'name' ),
                quantity: $( this ).val()
            }, function () {
                theme.$body.trigger( 'wc_fragment_refresh' );
            } );
        }
    }

    theme.MiniCartQuantityInput = MiniCartQuantityInput;

    $( window ).on( 'alpha_complete', function () {
        theme.MiniCartQuantityInput.init();
    } );
} )( jQuery );