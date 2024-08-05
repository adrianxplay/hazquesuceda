/**
 * WP Alpha Core Framework
 * Alpha Shop Show Type: Shop Show Type
 *
 * @package WP Alpha Core Framework
 * @since   1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {

    /**
     * Initialize ajax load products when show type changes
     * 
     * @since 1.2.0
     * 
     * @param {String} selector
     */
    theme.shopShowType = function ( selector ) {

        /**
         * Event handler to change show type
         * 
         * @since 1.2.0
         * @param {Event} e 
         */
        function changeShowType( e ) {
            e.preventDefault();
            var $link = $( this );
            $( '.product-archive .products, .archive-products .products' ).data( 'loading_show_type', true )	// For skeleton screen
            if ( !$link.hasClass( 'active' ) ) {
                $link.parent().children().toggleClass( 'active' );
                theme.setCookie( alpha_vars.theme + '_gridcookie', $link.hasClass( 'mode-list' ) ? 'list' : 'grid', 7 );

                if ( theme.AjaxLoadPost && theme.AjaxLoadPost.isAjaxShop ) {
                    theme.AjaxLoadPost.loadPage(
                        location.href,
                        { showtype: true }
                    );
                } else {
                    location.reload();
                }
            }
        }

        theme.$body.on( 'click', selector, changeShowType );
    }

    $( window ).on( 'alpha_load', function () {
        theme.shopShowType( '.toolbox-item .btn-showtype' );
    } );

} )( window.jQuery );