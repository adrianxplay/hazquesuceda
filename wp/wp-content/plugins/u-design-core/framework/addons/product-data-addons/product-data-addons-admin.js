/**
 * Alpha Product Data Addons Admin Library
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
( function ( wp, $ ) {
    'use strict';

    window.themeAdmin = themeAdmin || {};

    var productDataAddons = {
        init: function () {
            var self = this;

            $( '.alpha_custom_labels .color-picker' ).wpColorPicker();

            $( '.save_alpha_product_options' ).on( 'click', self.onExtraSave );
            $( '#alpha_add_custom_label' ).on( 'click', self.addLabel );
            $( '.alpha_custom_labels' ).on( 'click', '.delete', self.removeLabel );
            $( '.alpha_custom_labels' ).on( 'change', '.custom_label_type', self.typeSelected );
            $( document ).off( 'click', '.btn_upload_img' ).on( 'click', '.btn_upload_img', self.uploadFile );
            self.sortableLabels();
        },

        /**
         * Add a custom label
         */
        addLabel: function () {
            var form = $( this ).closest( '.form-field' );
            form.siblings( '.wc-metabox-template' ).clone().show().appendTo( form.find( '.wc-metaboxes' ) );

            if ( $.fn.wpColorPicker ) {
                form.find( '.color-picker' ).addClass( 'alpha-color-picker' );
                form.find( 'input.alpha-color-picker' ).wpColorPicker();
            }
        },

        /**
         * Remove a custom label
         */
        removeLabel: function ( e ) {
            e.preventDefault();
            $( this ).closest( '.wc-metabox' ).remove();
        },

        /**
         * Selected Label Type
         */
        typeSelected: function () {
            if ( $( this ).val() ) {
                $( this ).siblings( '.text-controls' ).hide();
                $( this ).siblings( '.image-controls' ).show();
            } else {
                $( this ).siblings( '.image-controls' ).hide();
                $( this ).siblings( '.text-controls' ).show();
            }
        },

        /**
         * Upload Label Image
         */
        uploadFile: function ( e ) {

            var file_frame;
            var $this = $( this ),
                $prev = $this.prev(),
                $next = $this.next();

            // If the media frame already exists, reopen it.
            if ( !file_frame ) {
                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media(
                    {
                        title: 'Choose an image',
                        button: {
                            text: 'Use image'
                        },
                        multiple: false
                    }
                );
            }

            file_frame.open();

            // When an image is selected, run a callback.
            file_frame.on(
                'select',
                function () {
                    var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                    $prev.val( attachment.url );
                    file_frame.close();
                    $next.val( attachment.id );
                }
            );
            e.preventDefault();
        },

        /**
         * Sortable Custom Labels
         */
        sortableLabels: function () {
            // Attribute ordering.
            $( '.alpha_custom_labels .wc-metaboxes' ).sortable(
                {
                    items: '.wc-metabox',
                    cursor: 'move',
                    axis: 'y',
                    handle: 'h3',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function ( event, ui ) {
                        ui.item.css( 'background-color', '#f6f6f6' );
                    },
                    stop: function ( event, ui ) {
                        ui.item.removeAttr( 'style' );
                        // attribute_row_indexes();
                    }
                }
            );
        },

        /**
         * Event handler on save
         */
        onExtraSave: function ( e ) {
            e.preventDefault();

            var extra = [],
                $wrapper = $( '#alpha_data_addons' );

            extra[ 'alpha_custom_labels' ] = [];
            $( '.alpha_custom_labels' ).find( '.wc-metabox' ).each(
                function () {
                    var each = {};
                    each.type = $( this ).find( '.custom_label_type' ).val();
                    if ( each.type ) {
                        each.img_url = $( this ).find( '.label_image' ).val();
                        each.img_id = $( this ).find( '.label_image_id' ).val();
                    } else {
                        each.label = $( this ).find( '.label_text' ).val();
                        each.color = $( this ).find( '[name="label_color"]' ).val();
                        each.bgColor = $( this ).find( '[name="label_bgcolor"]' ).val();
                    }
                    if ( ( !each.type && each.label ) || ( each.type && each.img_url ) ) {
                        extra[ 'alpha_custom_labels' ].push( each );
                    }
                }
            )

            extra[ 'alpha_extra_info' ] = $( '#alpha_extra_info' ).val();

            $wrapper.block(
                {
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                }
            );

            $.ajax( {
                type: 'POST',
                dataType: 'json',
                url: alpha_product_data_addon_vars.ajax_url,
                data: {
                    action: "alpha_save_product_extra_options",
                    nonce: alpha_product_data_addon_vars.nonce,
                    post_id: alpha_product_data_addon_vars.post_id,
                    alpha_custom_labels: extra[ 'alpha_custom_labels' ],
                    alpha_extra_info: extra[ 'alpha_extra_info' ],
                },
                success: function () {
                    $wrapper.unblock();
                }
            } );
        },

        /**
         * Event handler on save
         */
        onCancel: function ( e ) {
            confirm( "Changes are cancelled. Do you want to reload this page?" ) && window.location.reload();
        }
    }
    /**
     * Product Image Admin Swatch Initializer
     */
    themeAdmin.productDataAddons = productDataAddons;

    $( document ).ready( function () {
        themeAdmin.productDataAddons.init();
    } );
} )( wp, jQuery );
