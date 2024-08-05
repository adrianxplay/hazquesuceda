/**
 * Javascript Library for Studio
 * 
 * 
 * @author     Andon
 * @since      4.1
 * @package    Alpha Core Framework
 * @subpackage Core
 */
'use strict';

( function ( $ ) {
    themeCoreAdmin.selectBlock = function () {
        var $this = $( this ),
            category = $( this ).parent().data( 'category' );

        if ( parseInt( category ) ) {
            if ( category == 62 && $( '.alpha-new-template-form .template-type [value="header"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'header' );
            } else if ( category == 63 && $( '.alpha-new-template-form .template-type [value="footer"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'footer' );
            } else if ( category == 65 && $( '.alpha-new-template-form .template-type [value="popup"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'popup' );
            } else if ( category == 75 && $( '.alpha-new-template-form .template-type [value="single"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'single' );
            } else if ( category == 81 && $( '.alpha-new-template-form .template-type [value="archive"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'archive' );
            } else if ( category == 78 && $( '.alpha-new-template-form .template-type [value="shop"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'shop' );
            } else if ( category == 64 && $( '.alpha-new-template-form .template-type [value="product_layout"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'product_layout' );
            } else if ( category == 79 && $( '.alpha-new-template-form .template-type [value="cart"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'cart' );
            } else if ( category == 80 && $( '.alpha-new-template-form .template-type [value="checkout"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'checkout' );
            } else if ( category == 82 && $( '.alpha-new-template-form .template-type [value="type"]' ).length ) {
                $( '.alpha-new-template-form .template-type' ).val( 'type' );
            } else {
                $( '.alpha-new-template-form .template-type' ).val( 'block' );
            }
        } else {
            $( '.alpha-new-template-form .template-type' ).val( category );
        }

        $( '.blocks-wrapper .block.selected' ).removeClass( 'selected' );
        $( '#alpha-new-template-id' ).val( $this.parent().data( 'id' ) );
        if ( $( '.blocks-wrapper .block-category-my-templates.active' ).length )
            $( '#alpha-new-template-type' ).val( 'my' );
        else {
            if ( $( '#alpha-elementor-studio' ).is( ':checked' ) )
                $( '#alpha-new-template-type' ).val( 'e' );
            else if ( $( '#alpha-wpbakery-studio' ).is( ':checked' ) )
                $( '#alpha-new-template-type' ).val( 'w' );
            if ( $( '.alpha-new-template-form .template-type' ).val() == 'type' )
                $( '#alpha-new-template-type' ).val( 'g' );
        }
        $( '#alpha-new-template-name' ).val( $this.closest( '.block' ).addClass( 'selected' ).find( '.block-title' ).text() );
        $( '.blocks-wrapper, .blocks-overlay' ).addClass( 'closed' );
    }

} )( jQuery );
