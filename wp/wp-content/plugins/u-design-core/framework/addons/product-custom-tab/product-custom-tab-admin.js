/**
 * Alpha Custom Tab Admin Library
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
( function ( wp, $ ) {
    'use strict';

    window.themeAdmin = window.themeAdmin || {};


    var ProductCustomTab = {
        init: function () {
            var self = this;

            $( '.save_alpha_product_desc' ).on( 'click', self.onSave );
        },

		/**
		 * Event handler on save
		 */
        onSave: function ( e ) {
            e.preventDefault();

            var tabs = [];
            var keys = [ '1st', '2nd' ];

            var $wrapper = $( '#alpha_custom_tab_options' );
            $wrapper.block(
                {
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                }
            );

            keys.forEach( function ( item ) {
                var title = $( '#alpha_custom_tab_options' ).find( '.alpha_custom_tab_title_' + item + '_field input' ).val();
                if ( title && tinymce.editors[ 'alpha_custom_tab_content_' + item ] ) {
                    var content = tinymce.editors[ 'alpha_custom_tab_content_' + item ].getContent();
                    if ( content ) {
                        tabs[ item ] = [];
                        tabs[ item ][ 0 ] = title;
                        tabs[ item ][ 1 ] = content;
                    }
                }
            } )

            var data = {
                action: "alpha_save_product_tabs",
                nonce: alpha_product_custom_tab_vars.nonce,
                post_id: alpha_product_custom_tab_vars.post_id,
                alpha_custom_tabs: tabs,
            };
            if ( tabs[ '1st' ] ) {
                data.alpha_custom_tab_1st = tabs[ '1st' ];
            }
            if ( tabs[ '2nd' ] ) {
                data.alpha_custom_tab_2nd = tabs[ '2nd' ];
            }

            $.ajax( {
                type: 'POST',
                dataType: 'json',
                url: alpha_product_custom_tab_vars.ajax_url,
                data: data,
                success: function () {
                    $wrapper.unblock();
                }
            } );
        },
    }
	/**
	 * Product Image Admin Swatch Initializer
	 */
    themeAdmin.productCustomTab = ProductCustomTab;

    $( document ).ready( function () {
        themeAdmin.productCustomTab.init();
    } );
} )( wp, jQuery );
