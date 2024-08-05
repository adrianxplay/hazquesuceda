/**
 * Post Type Builder - WooCommerce Links
 * 
 * @since 2.3.0
 */
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS } from '../../../plugins/gutenberg/assets/controls/style-options';
import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../../../plugins/gutenberg/assets/controls/typography';
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
        UnitControl = wp.components.__experimentalUnitControl,
        Disabled = wpComponents.Disabled,
        PanelBody = wpComponents.PanelBody,
        ServerSideRender = wp.serverSideRender;

    const AlphaTBWooButtons = function ( { attributes, setAttributes, name, clientId } ) {

        const content_type = document.getElementById( 'content_type' ).value;
        let content_type_value,
            attrs = { link_source: attributes.link_source, show_quantity_input: attributes.show_quantity_input, hide_title: attributes.hide_title, icon_cls: attributes.icon_cls, icon_pos: attributes.icon_pos, el_class: attributes.el_class };
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
            selectorCls = 'alpha-tb-woo-buttons-' + Math.ceil( Math.random() * 10000 );
            setAttributes( { el_class: selectorCls } );
        }

        if ( attributes.font_settings ) {
            let fontAtts = attributes.font_settings;

            internalStyle += alphaGenerateTypographyCSS( fontAtts, selectorCls );
        }

        if ( ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source ) {
            if ( attributes.st_icon_fs ) {
                internalStyle += '.' + selectorCls + ' i{font-size:' + attributes.st_icon_fs + '}';
            }
            if ( attributes.st_spacing ) {
                if ( 'right' === attributes.icon_pos ) {
                    internalStyle += '.' + selectorCls + ' i{margin-left:' + attributes.st_spacing + '}';
                } else {
                    internalStyle += '.' + selectorCls + ' i{margin-right:' + attributes.st_spacing + '}';
                }
            }
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alphaAddHelperClasses( attributes.className, clientId );
        }

        let icon_cls_ex = 'w-icon-cart';
        if ( 'compare' === attributes.link_source ) {
            icon_cls_ex = 'w-icon-compare';
        } else if ( 'quickview' === attributes.link_source ) {
            icon_cls_ex = 'w-icon-search';
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <SelectControl
                        label={ __( 'Link Source', 'alpha-core' ) }
                        value={ attributes.link_source }
                        options={ [ { 'label': __( 'Select...', 'alpha-core' ), 'value': '' }, { 'label': __( 'Add to cart', 'alpha-core' ), 'value': 'cart' }, { 'label': __( 'Add to wishlist', 'alpha-core' ), 'value': 'wishlist' }, { 'label': __( 'Compare', 'alpha-core' ), 'value': 'compare' }, { 'label': __( 'Quick View', 'alpha' ), 'value': 'quickview' }, { 'label': __( 'Image / Color Swatch', 'alpha-core' ), 'value': 'swatch' } ] }
                        onChange={ ( value ) => { setAttributes( { link_source: value } ); } }
                    />
                    { 'cart' == attributes.link_source && (
                        <ToggleControl
                            label={ __( 'Show Quantity Input', 'alpha-core' ) }
                            checked={ attributes.show_quantity_input }
                            onChange={ ( value ) => { setAttributes( { show_quantity_input: value } ); } }
                        />
                    ) }
                    { 'wishlist' !== attributes.link_source && (
                        <ToggleControl
                            label={ __( 'Hide Title', 'alpha-core' ) }
                            checked={ attributes.hide_title }
                            onChange={ ( value ) => { setAttributes( { hide_title: value } ); } }
                        />
                    ) }
                    { 'wishlist' !== attributes.link_source && (
                        <TextControl
                            label={ __( 'Icon Class (ex: %s)', 'alpha-core' ).replace( '%s', 'w-icon-' ) }
                            value={ attributes.icon_cls }
                            onChange={ ( value ) => { setAttributes( { icon_cls: value } ); } }
                        />
                    ) }
                    { 'cart' === attributes.link_source && (
                        <TextControl
                            label={ __( 'Icon Class for Variable Product (ex: %s)', 'alpha-core' ).replace( '%s', 'w-icon-long-arrow-right' ) }
                            value={ attributes.icon_cls_variable }
                            onChange={ ( value ) => { setAttributes( { icon_cls_variable: value } ); } }
                        />
                    ) }
                    { 'compare' === attributes.link_source && (
                        <TextControl
                            label={ __( 'Icon Class for Added status (ex: %s)', 'alpha-core' ).replace( '%s', 'w-icon-check-solid' ) }
                            value={ attributes.icon_cls_added }
                            onChange={ ( value ) => { setAttributes( { icon_cls_added: value } ); } }
                        />
                    ) }
                    { ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source && (
                        <SelectControl
                            label={ __( 'Icon Position', 'alpha-core' ) }
                            value={ attributes.icon_pos }
                            options={ [ { label: __( 'Left', 'alpha-core' ), value: 'left' }, { label: __( 'Right', 'alpha-core' ), value: 'right' } ] }
                            onChange={ ( value ) => { setAttributes( { icon_pos: value } ); } }
                        />
                    ) }
                    { ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source && (
                        <UnitControl
                            label={ __( 'Icon Size', 'alpha-core' ) }
                            value={ attributes.st_icon_fs }
                            onChange={ ( value ) => { setAttributes( { st_icon_fs: value } ); } }
                        />
                    ) }
                    { ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source && (
                        <div className={ 'spacer' } />
                    ) }
                    { ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source && (
                         <UnitControl
                            label={ __( 'Spacing', 'alpha-core' ) }
                            value={ attributes.st_spacing }
                            onChange={ ( value ) => { setAttributes( { st_spacing: value } ); } }
                        />
                    ) }
                    { ( attrs.icon_cls || attributes.icon_cls_variable ) && 'wishlist' != attributes.link_source && (
                        <div className={ 'spacer' } />
                    ) }
                    <PanelBody title={ __( 'Font Settings', 'alpha-core' ) } initialOpen={ true }>
                        <AlphaTypographyControl
                            label={ __( 'Typography', 'alpha-core' ) }
                            value={ font_settings }
                            options={ {} }
                            onChange={ ( value ) => {
                                setAttributes( { font_settings: value } );
                            } }
                        />
                    </PanelBody>
                    <AlphaStyleOptionsControl
                        label={ __( 'Style Options', 'alpha-core' ) }
                        value={ style_options }
                        options={ { hoverOptions: true } }
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
    };

    registerBlockType( alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-buttons', {
        title: __( 'Woo Links', 'alpha-core' ),
        icon: 'alpha',
        category: alpha_admin_vars.theme + '-tb',
        keywords: [ 'type builder', 'mini', 'card', 'post', 'woocommerce', 'add to cart', 'quick view', 'compare', 'wishlist', 'yith', 'button', 'product', 'swatch', 'variation' ],
        attributes: {
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            link_source: {
                type: 'string',
            },
            show_quantity_input: {
                type: 'boolean',
            },
            hide_title: {
                type: 'boolean',
            },
            icon_cls: {
                type: 'string',
            },
            icon_cls_variable: {
                type: 'string',
            },
            icon_cls_added: {
                type: 'string',
            },
            icon_pos: {
                type: 'string',
            },
            st_icon_fs: {
                type: 'string',
            },
            st_spacing: {
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
        edit: AlphaTBWooButtons,
        save: function () {
            return null;
        }
    } );
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );