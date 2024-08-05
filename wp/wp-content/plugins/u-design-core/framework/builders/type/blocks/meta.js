/**
 * Post Type Builder - Meta element
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
        UnitControl = wp.components.__experimentalUnitControl,
        Disabled = wpComponents.Disabled,
        PanelBody = wpComponents.PanelBody,
        ServerSideRender = wp.serverSideRender;

    const tmp_options = document.getElementById( 'content_type_term' ).options, alpha_all_terms = [];
    for (var i = 0; i < tmp_options.length; i++) {
        var option = tmp_options[i];
        if ( option.value ) {
            alpha_all_terms.push( { label: option.innerText.trim(), value: option.value } );
        }
    }

    const AlphaTBMeta = function ( { attributes, setAttributes, name, clientId } ) {

        const content_type = document.getElementById( 'content_type' ).value;
        let content_type_value,
            attrs = { field: attributes.field, date_format: attributes.date_format, icon_cls: attributes.icon_cls, icon_pos: attributes.icon_pos, el_class: attributes.el_class };
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
            selectorCls = 'alpha-tb-meta-' + Math.ceil( Math.random() * 10000 );
            setAttributes( { el_class: selectorCls } );
        }

        if ( attributes.alignment || attributes.font_settings ) {
            let fontAtts = attributes.font_settings;
            fontAtts.alignment = attributes.alignment;

            internalStyle += alphaGenerateTypographyCSS( fontAtts, selectorCls );
        }

        if ( attributes.st_icon_fs ) {
            internalStyle += '.' + selectorCls + ' .alpha-tb-icon{font-size:' + attributes.st_icon_fs + '}';
        }
        if ( attributes.spacing || 0 === attributes.spacing ) {
            if ( 'right' === attributes.icon_pos ) {
                internalStyle += '.' + selectorCls + ' .alpha-tb-icon{margin-left:' + Number( attributes.spacing ) + 'px}';
            } else {
                internalStyle += '.' + selectorCls + ' .alpha-tb-icon{margin-right:' + Number( attributes.spacing ) + 'px}';
            }
        }

        // add helper classes to parent block element
        if ( attributes.className ) {
            alphaAddHelperClasses( attributes.className, clientId );
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <SelectControl
                        label={ __( 'Field', 'alpha-core' ) }
                        value={ attributes.field }
                        options={
                            [ { 'label': __( 'Author', 'alpha-core' ), 'value': 'author' }, { 'label': __( 'Published Date', 'alpha-core' ), 'value': 'published_date' }, { 'label': __( 'Modified Date', 'alpha-core' ), 'value': 'modified_date' }, { 'label': __( 'Comments', 'alpha-core' ), 'value': 'comments' }, { 'label': __( 'Comments Number', 'alpha-core' ), 'value': 'comments_number' }, { 'label': __( 'Product SKU', 'alpha-core' ), 'value': 'sku' } ].concat( alpha_all_terms )
                        }
                        onChange={ ( value ) => { setAttributes( { field: value } ); } }
                    />
                    { ( 'published_date' === attrs.field || 'modified_date' === attrs.field ) && (
                        <TextControl
                            label={ __( 'Date Format', 'alpha-core' ) }
                            help={ __( 'j = 1-31, F = January-December, M = Jan-Dec, m = 01-12, n = 1-12', 'alpha-core' ) }
                            value={ attributes.date_format }
                            onChange={ ( value ) => { setAttributes( { date_format: value } ); } }
                        />
                    ) }
                    <TextControl
                        label={ __( 'Icon Class (ex: fas fa-pencil-alt)', 'alpha-core' ) }
                        value={ attributes.icon_cls }
                        onChange={ ( value ) => { setAttributes( { icon_cls: value } ); } }
                    />
                    { attrs.icon_cls && (
                        <SelectControl
                            label={ __( 'Icon Position', 'alpha-core' ) }
                            value={ attributes.icon_pos }
                            options={ [ { label: __( 'Left', 'alpha-core' ), value: '' }, { label: __( 'Right', 'alpha-core' ), value: 'right' } ] }
                            onChange={ ( value ) => { setAttributes( { icon_pos: value } ); } }
                        />
                    ) }
                    { attrs.icon_cls && (
                        <UnitControl
                            label={ __( 'Icon Size', 'alpha-core' ) }
                            value={ attributes.st_icon_fs }
                            onChange={ ( value ) => { setAttributes( { st_icon_fs: value } ); } }
                        />
                    ) }
                    { attrs.icon_cls && (
                        <div className={ 'spacer' } />
                    ) }
                    { attrs.icon_cls && (
                        <RangeControl
                            label={ __( 'Spacing (px)', 'alpha-core' ) }
                            help={ __( 'Spacing between icon and meta', 'alpha-core' ) }
                            value={ attributes.spacing }
                            min="0"
                            max="100"
                            allowReset={ true }
                            onChange={ ( value ) => { setAttributes( { spacing: value } ); } }
                        />
                    ) }
                    <PanelBody title={ __( 'Font Settings', 'alpha-core' ) } initialOpen={ false }>
                        <SelectControl
                            label={ __( 'Text Align', 'alpha-core' ) }
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
    registerBlockType( alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-meta', {
        title: __( 'Meta', 'alpha-core' ),
        icon: 'alpha',
        category: alpha_admin_vars.theme + '-tb',
        keywords: [ 'type builder', 'mini', 'card', 'post', 'author', 'categories', 'date' ],
        attributes: {
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            field: {
                type: 'string',
            },
            date_format: {
                type: 'string',
            },
            icon_cls: {
                type: 'string',
            },
            icon_pos: {
                type: 'string',
            },
            st_icon_fs: {
                type: 'string',
            },
            spacing: {
                type: 'int',
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
        edit: AlphaTBMeta,
        save: function () {
            return null;
        }
    } );
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components );