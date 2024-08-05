/**
 * Alpha Core Framework
 * Alpha Single Product Attributes Link
 * 
 * @package Alpha Core Framework
 * @since 4.3.0
 */

( function ( $ ) {

    /**
     * Active product addtional tab
     * 
     * @class ProductAttribute
     * @since 4.3
     * @param {string|jQuery} selector
     * @return {void}
     */
    theme.initProductAttribute = function () {
        function onClickActiveAttributes( e ) {
            $( '.additional_information_tab a' ).trigger( 'click' );
            e.preventDefault();
        }

        // More attributes link
        theme.$body.on( 'click', '.product-attributes + .more-attributes', onClickActiveAttributes );
    }

    $( window ).on( 'alpha_complete', function () {
        theme.initProductAttribute();                           // Initialize product attribute
    } );
} )( window.jQuery );