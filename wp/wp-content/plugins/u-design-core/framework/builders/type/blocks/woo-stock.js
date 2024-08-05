/**
 * Post Type Builder - WooCommerce Stock
 * 
 * @since 2.3.0
 */
import AlphaStyleOptionsControl, {alphaGenerateStyleOptionsCSS} from '../../../plugins/gutenberg/assets/controls/style-options';
import AlphaTypographyControl, {alphaGenerateTypographyCSS} from '../../../plugins/gutenberg/assets/controls/typography';
import {alphaAddHelperClasses} from '../../../plugins/gutenberg/assets/controls/editor-extra-classes';

( function ( wpI18n, wpBlocks, wpBlockEditor, wpComponents ) {
    "use strict";

    const __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        SelectControl = wpComponents.SelectControl,
        TextControl = wpComponents.TextControl,
        RangeControl = wpComponents.RangeControl,
        ToggleControl = wpComponents.ToggleControl,
        Disabled = wpComponents.Disabled,
        PanelBody = wpComponents.PanelBody,
        ServerSideRender = wp.serverSideRender;

    const AlphaTBWooStock = function ( { attributes, setAttributes, name, clientId } ) {

        const content_type = document.getElementById( 'content_type' ).value;
        let content_type_value,
            attrs = { el_class: attributes.el_class };
        if ( content_type ) {
            attrs.content_type = content_type;
            content_type_value = document.getElementById( 'content_type_' + content_type );
            if ( content_type_value ) {
                content_type_value = content_type_value.value;
                attrs.content_type_value = content_type_value;
            }
        }

        let internalStyle = '',
            font_settings = Object.assign( {}, attributes.font_settings );

        const style_options = Object.assign( {}, attributes.style_options );
        let selectorCls;
        if ( attributes.el_class ) {
            selectorCls = attributes.el_class;
        } else {
            selectorCls = 'alpha-tb-woo-stock-' + Math.ceil( Math.random() * 10000 );
            setAttributes( { el_class: selectorCls } );
        }

        if ( attributes.alignment || attributes.font_settings ) {
            let fontAtts = attributes.font_settings;
            fontAtts.alignment = attributes.alignment;

            internalStyle += alphaGenerateTypographyCSS( fontAtts, selectorCls + ' .stock' );
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alphaAddHelperClasses( attributes.className, clientId );
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <PanelBody title={ __( 'Font Settings', 'alpha-core' ) } initialOpen={ true }>
                        <SelectControl
                            label={ __( 'Alignment', 'alpha-core' ) }
                            value={ attributes.alignment }
                            options={ [ { 'label': __( 'Inherit', 'alpha-core' ), 'value': '' }, { 'label': __( 'Left', 'alpha-core' ), 'value': 'left' }, { 'label': __( 'Center', 'alpha-core' ), 'value': 'center' }, { 'label': __( 'Right', 'alpha-core' ), 'value': 'right' }, { 'label': __( 'Justify', 'alpha-core' ), 'value': 'justify' } ] }
                            onChange={ ( value ) => { setAttributes( { alignment: value } ); } }
                        />
                        <AlphaTypographyControl
                            label={ __( 'Typography', 'alpha-core' ) }
                            value={ font_settings }
                            options={ { } }
                            onChange={ ( value ) => {
                                setAttributes( { font_settings: value } );
                            } }
                        />
                    </PanelBody>
                    <AlphaStyleOptionsControl
                        label={ __( 'Style Options', 'alpha-core' ) }
                        value={ style_options }
                        options={ {} }
                        onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                    />
                </InspectorControls>
                <Disabled>
                    <style>
                        { internalStyle }
                        { alphaGenerateStyleOptionsCSS( style_options, selectorCls ) }
                    </style>
                    <ServerSideRender
                        block={ name }
                        attributes={ attrs }
                    />
                </Disabled>
            </>
        )
    }
    registerBlockType( alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-stock', {
        title: __( 'Woo Stock', 'alpha-core' ),
        icon: 'alpha',
        category: alpha_admin_vars.theme + '-tb',
        keywords: [ 'type builder', 'mini', 'card', 'post', 'amount', 'product' ],
        attributes: {
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            alignment: {
                type: 'string',
            },
            font_settings: {
                type: 'object',
                default: {},
            },
            style_options: {
                type: 'object',
            },
            el_class: {
                type: 'string',
            }
        },
        edit: AlphaTBWooStock,
        save: function () {
            return null;
        }
    } );
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );