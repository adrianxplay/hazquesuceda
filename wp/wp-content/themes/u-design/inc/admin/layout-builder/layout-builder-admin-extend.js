/**
 * Javascript Library for Layout Builder Admin Extend
 * 
 * 
 * @author     Andon
 * @since      4.1
 * @package    Alpha Framework
 * @subpackage Theme
 */
'use strict';

( function ( $ ) {
    $( document ).ready( function () {

        themeAdmin.LayoutBuilder.view.editPart = function ( e ) {
            var $part = $( e.currentTarget );

            // active part
            if ( $part.hasClass( 'disabled' ) ) {
                return;
            }

            var part = $part.data( 'part' );
            var $layout = $part.closest( '.alpha-layout' );
            var $options = $layout.next( '.alpha-layout-options' ).children();
            var controls_html = ''; // '<h4 class="alpha-layout-control">' + $part.text() + '</h4>';
            var $item = $part.closest( '.alpha-layout-item' );
            var currentCategory = $item.data( 'category' );
            var conditionNo = $item.data( 'condition-no' );

            // show layout options for selected layout part.
            var optionControls = themeAdmin.LayoutBuilder.model.getOptionControls( part );
            var optionValues = themeAdmin.LayoutBuilder.model.getOptionValues( currentCategory, conditionNo );

            if ( optionControls ) {
                var randomList = [];
                for ( var optionName in optionControls ) {

                    // get random number.
                    var random;
                    do {
                        random = Math.floor( Math.random() * 65535 );
                    } while ( randomList.indexOf( random ) >= 0 );
                    randomList.push( random );

                    var name = '_alpha_' + part + '_' + optionName + random;
                    var control = optionControls[ optionName ];
                    var control_html = '';
                    var optionValue = optionValues && 'undefined' != typeof optionValues[ optionName ] ? optionValues[ optionName ] : '';

                    // show label, description
                    if ( control.description ) {
                        control_html += '<div class="alpha-layout-desc"><label>' + control.label + '</label><p>' + control.description + '</p></div>';
                    } else {
                        control_html += '<label for="' + name + '" class="alpha-layout-desc">' + control.label + '</label>';
                    }

                    // show control
                    if ( 'buttonset' == control.type ) {

                        var choice = '';

                        control_html += '<input type="radio" id="' + name + '_' + choice + '" name="' + name + '" value=""' + ( '' == optionValue ? ' checked' : '' ) + ' class="radio-default">';
                        control_html += '<label for="' + name + '_' + choice + '" class="label-default fas fa-redo"' + ( optionValue ? '' : ' checked="true"' ) + '></label>';
                        control_html += '<div class="alpha-radio-button-set">';
                        for ( var choice in control.options ) {
                            control_html += '<input type="radio" id="' + name + '_' + choice + '" name="' + name + '" value="' + choice + '"' + ( choice == optionValue ? ' checked' : '' ) + '>'; // check checked
                            control_html += '<label for="' + name + '_' + choice + '" class="alpha_' + part + '_' + optionName + '_' + choice + '">' + control.options[ choice ] + '</label>';
                            // control_html += '<img src="' + alpha_layout_vars.layout_images_url + control.options[choice].image + '" title="' + control.options[choice].title + '">';
                        }
                        control_html += '</div>';

                    } else if ( 'image' == control.type ) {
                        var choice = '';
                        control_html += '<input type="radio" id="' + name + '_' + choice + '" name="' + name + '" value=""' + ( '' == optionValue ? ' checked' : '' ) + ' class="radio-default">';
                        control_html += '<label for="' + name + '_' + choice + '" class="label-default fas fa-redo"' + ( optionValue ? '' : ' checked="true"' ) + '></label>';
                        control_html += '<div class="alpha-radio-image-set">';
                        for ( var choice in control.options ) {
                            control_html += '<input type="radio" id="' + name + '_' + choice + '" name="' + name + '" value="' + choice + '"' + ( choice == optionValue ? ' checked' : '' ) + '>'; // check checked
                            control_html += '<label for="' + name + '_' + choice + '" class="alpha_' + part + '_' + optionName + '_' + choice + '">';
                            control_html += '<img src="' + alpha_layout_vars.layout_images_url + control.options[ choice ].image + '" title="' + control.options[ choice ].title + '">';
                            control_html += '</label>';
                        }
                        control_html += '</div>';

                    } else if ( control.type.startsWith( 'block' ) ) {
                        var blockType = control.type.replace( 'block_', '' ) ? control.type.replace( 'block_', '' ) : 'block';
                        var blocks = themeAdmin.LayoutBuilder.model.getTemplates( blockType );

                        control_html += '<div class="alpha-block-select' + ( optionValue && optionValue != 'hide' ? '' : ' inactive-my' ) + '">';

                        control_html += '<div class="alpha-radio-button-set">';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_" value=""' + ( optionValue ? '' : ' checked' ) + '>';
                        control_html += '<label class="fa fa-redo" for="' + name + '_" title="' + alpha_layout_vars.text_default + '"></label>';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_hide" value="hide"' + ( optionValue == 'hide' ? ' checked' : '' ) + '>';
                        control_html += '<label class="fa fa-eye-slash"for="' + name + '_hide"  title="' + alpha_layout_vars.text_hide + '"></label>';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_my" value="my"' + ( optionValue && optionValue != 'hide' ? ' checked' : '' ) + '>';
                        control_html += '<label class="far fa-hdd" for="' + name + '_my" title="' + alpha_layout_vars.text_my_templates + '"></label>';
                        control_html += '</div>';
                        // control_html += '<div class="alpha-radio-button-extend">';
                        // control_html += '<a href="#" class="alpha-add-new-template fa fa-plus"></a>';
                        // control_html += '<a href="#" class="far fa-edit"></a>';
                        // control_html += '</div>';

                        control_html += '<select class="alpha-layout-part-select alpha-layout-part-control" id="' + name + '" name="' + name + '">';
                        for ( var block in blocks ) {
                            control_html += '<option value="' + block + '"' + ( optionValue == block ? ' selected' : '' ) + '>' + blocks[ block ] + '</option>';
                        }
                        control_html += '</select>';
                        if ( alpha_layout_vars.template_builder[ blockType ] ) {
                            if ( blockType == 'sidebar' ) {
                                control_html += '<a href="' + window.location.href.substr( 0, window.location.href.indexOf( 'wp-admin' ) + 8 ) + '/admin.php?page=alpha-sidebar" target="_blank" class="new-block-template">' + wp.i18n.__( 'Please create a new ', 'alpha' ) + blockType + '</a>';
                            } else {
                                control_html += '<a href="' + ( window.location.href.substr( 0, window.location.href.indexOf( 'wp-admin' ) + 8 ) + '/edit.php?post_type=' + alpha_admin_vars.theme + '_template&' + alpha_admin_vars.theme + '_template_type=' + blockType ) + '" target="_blank" class="new-block-template">' + wp.i18n.__( 'Please create a new ', 'alpha' ) + ( blockType == 'product_layout' ? wp.i18n.__( 'single product', 'alpha' ) : blockType == 'shop_layout' ? wp.i18n.__( 'shop layout', 'alpha' ) : blockType ) + '</a>';
                            }
                        } else {
                            control_html += '<a href="' + window.location.href.substr( 0, window.location.href.indexOf( 'wp-admin' ) + 8 ) + '/admin.php?page=alpha-setup-wizard&step=default_plugins" class="new-block-template">' + wp.i18n.__( 'Please activate ', 'alpha' ) + alpha_admin_vars.theme_display_name + ' Core</a>';
                        }
                        control_html += '</div>';

                    } else if ( 'number' == control.type ) {

                        if ( optionValue ) { // check min, max validation.
                            var value = optionValue;
                            'undefined' != typeof control.min && ( value = Math.max( control.min, value ) );
                            'undefined' != typeof control.max && ( value = Math.min( control.max, value ) );
                            optionValue != value && themeAdmin.LayoutBuilder.model.setConditionOption( currentCategory, conditionNo, optionName, value );
                        }

                        control_html += '<input type="number" class="alpha-layout-part-control" name="' + name + '" id="' + name + '"' +
                            ( 'undefined' == typeof control.min ? '' : ' min="' + control.min + '"' ) +
                            ( 'undefined' == typeof control.max ? '' : ' max="' + control.max + '"' ) +
                            ' step="1" value="' + optionValue + '">';

                    } else if ( 'select' == control.type ) {

                        control_html += '<select class="alpha-layout-part-select alpha-layout-part-control" id="' + name + '" name="' + name + '">';
                        for ( var choice in control.options ) {
                            control_html += '<option value="' + choice + '"' + ( choice == optionValue ? ' selected' : '' ) + '>' + control.options[ choice ] + '</option>';
                        }
                        control_html += '</select>';

                    } else if ( 'text' == control.type ) {

                        control_html += '<input type="text" name="' + name + '" class="alpha-layout-part-input alpha-layout-part-control" id="' + name + '" value="' + optionValue + '"></input>';

                    } else if ( 'toggle' == control.type ) {

                        control_html += '<div class="alpha-radio-button-set">';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_" value=""' + ( optionValue ? '' : ' checked' ) + '>';
                        control_html += '<label class="fa fa-redo" for="' + name + '_" title="' + alpha_layout_vars.text_default + '"></label>';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_no" value="no"' + ( optionValue == 'no' ? ' checked' : '' ) + '>';
                        control_html += '<label class="fa fa-eye-slash" for="' + name + '_no"></label>';
                        control_html += '<input type="radio" name="' + name + '" id="' + name + '_yes" value="yes"' + ( optionValue == 'yes' ? ' checked' : '' ) + '>';
                        control_html += '<label class="fa fa-check"for="' + name + '_yes"></label>';
                        control_html += '</div>';

                    } else if ( 'multicheck' == control.type ) {
                        control_html += '<ul>';
                        if ( control.options ) {
                            for ( var choice in control.options ) {
                                control_html += '<li><label><input type="checkbox" name="' + name + '[]" value="' + choice + '">' + control.options[ choice ] + '</label></li>';
                            }
                        }
                        control_html += '</ul>';
                    }

                    // custom controls condition
                    var show = true;
                    if ( optionName == 'single_product_block' && ( !optionValues || 'builder' != optionValues[ 'single_product_type' ] ) ) {
                        show = false;
                    }

                    controls_html += '<div class="alpha-layout-control" ' + ( show ? '' : ' style="display:none"' ) + 'data-option="' + optionName + '">' + control_html + '</div>';
                }
            }

            $options.html( controls_html );

            // Show controls UI.
            $item.addClass( 'edit' );
        }
        $( document.body ).off( 'click', '.alpha-layout .layout-part' ).on( 'click', '.alpha-layout .layout-part', themeAdmin.LayoutBuilder.view.editPart );
    } );
} )( jQuery );
