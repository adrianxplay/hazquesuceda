/**
 * WP Alpha Core Framework
 * Alpha Cart Coupons
 * 
 * @package WP Alpha Core Framework
 * @since 1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {

	/**
	 * Initialize coupon code form
	 * 
	 * @since 1.2.0
	 * @param {string|jQuery} selector
	 * @return {void}
	 */
    theme.initCartCoupons = function () {
        function onClickApplyCoupons( e ) {
            var $this = $( this );
            e.preventDefault();

            if ( $this.siblings( '.alpha_coupon_code' ).length ) {
                var code = $this.siblings( '.alpha_coupon_code' ).val();
                if ( $( '.form-coupon #coupon_code' ).length ) {
                    $( '.form-coupon #coupon_code' ).val( code ).siblings( 'button' ).trigger( 'click' );
                }
            }
        }

        // Apply Coupon Button
        theme.$body.on( 'click', '.alpha-apply-coupon', onClickApplyCoupons );
    }


    $( window ).on( 'alpha_load', function () {
        theme.initCartCoupons();                           // Initialize coupon form
    } );
} )( window.jQuery );