import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS, alphaGenerateStyleOptionsClass } from '../controls/style-options';
import AlphaDynamicContentControl from '../controls/dynamic-content';

/**
 * 04. Alpha Image
 * 
 * @since 1.2.0
 */
( function ( wpI18n, wpBlocks, wpBlockEditor, wpComponents, wpElement, $ ) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        MediaUploadCheck = wpBlockEditor.MediaUploadCheck,
        MediaUpload = wpBlockEditor.MediaUpload,
        ColorPalette = wp.components.ColorPalette,
        IconButton = wpComponents.IconButton,
        PanelBody = wpComponents.PanelBody,
        RangeControl = wpComponents.RangeControl,
        SelectControl = wpComponents.SelectControl,
        TextControl = wpComponents.TextControl,
        UnitControl = wpComponents.__experimentalUnitControl,
        useEffect = wpElement.useEffect;

    function getFilterStyle( style ) {
        let res = '';
        style.opacity && ( res += 'opacity:' + style.opacity + ';' );
        if ( style.blur || style.contrast || style.brightness || style.saturation || style.hue ) {
            res += 'filter:';
            style.blur && ( res += 'blur(' + style.blur + 'px)' );
            style.contrast && ( res += ' contrast(' + style.contrast + '%)' );
            style.brightness && ( res += ' brightness(' + style.brightness + '%)' );
            style.saturation && ( res += ' saturate(' + style.saturation + '%)' );
            style.hue && ( res += ' hue-rotate(' + style.hue + 'deg)' );
            res += ';'
        }
        return res;
    }

    const AlphaImage = function ( { attributes, setAttributes } ) {
        let selectorCls = 'alpha-gb-image-' + attributes.block_id,
            size_options = [],
            style_options = Object.assign( {}, attributes.style_options ),
            caption_font_settings_selector = Object.assign( {}, attributes.caption_font_settings_selector ),
            responsiveCls = alphaGenerateStyleOptionsClass( style_options ),
            additionalCls = attributes.className ? attributes.className + ' ' : '',
            dynamic_content = Object.assign( {}, attributes.dynamic_content );
        if ( 'dynamic' === attributes.image_source ) {
            size_options = alpha_block_vars.image_sizes;
        }

        /* start type builder */
        let content_type = document.getElementById( 'content_type' );
        if ( typeof content_type == 'undefined' ) {
            content_type = false;
        } else if ( content_type ) {
            content_type = content_type.value;
        }
        let content_type_value = '';
        if ( content_type ) {
            content_type_value = document.getElementById( 'content_type_' + content_type );
            if ( content_type_value ) {
                content_type_value = content_type_value.value;
            }
        }
        /* end type builder */
        useEffect(
            () => {
                let field_name = '';
                if ( attributes.dynamic_content && attributes.dynamic_content.source ) {
                    if ( 'post' == attributes.dynamic_content.source ) {
                        field_name = attributes.dynamic_content.post_info;
                    } else {
                        field_name = attributes.dynamic_content[ attributes.dynamic_content.source ];
                    }
                    if ( field_name ) {
                        jQuery.ajax( {
                            url: alpha_core_vars.ajax_url,
                            data: {
                                action: 'alpha_dynamic_tags_get_value',
                                nonce: alpha_core_vars.nonce,
                                content_type: content_type ? content_type : 'post',
                                content_type_value: content_type ? content_type_value : alpha_block_vars.edit_post_id,
                                source: attributes.dynamic_content.source,
                                field_name: field_name,
                                type: 'image',
                                img_size: attributes.img_size,
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function ( res ) {
                                let img = {};
                                if ( res && res.success ) {
                                    if ( typeof res.data == 'object' ) {
                                        img = res.data;
                                    } else {
                                        img.url = '' + res.data;
                                    }
                                } else {
                                    img.url = attributes.dynamic_content.fallback;
                                }
                                setAttributes( { img_source: img } );
                            }
                        } );
                    }
                }
            },
            [ attributes.image_source, attributes.img_size, attributes.dynamic_content && attributes.dynamic_content.source, attributes.dynamic_content && attributes.dynamic_content.post_info, attributes.dynamic_content && attributes.dynamic_content.metabox, attributes.dynamic_content && attributes.dynamic_content.acf, attributes.dynamic_content && attributes.dynamic_content.meta, attributes.dynamic_content && attributes.dynamic_content.tax ],
        );

        let imageStyle = '';
        imageStyle = alphaGenerateStyleOptionsCSS( style_options, selectorCls );
        if ( attributes.img_align_selector ) {
            imageStyle += '.' + selectorCls + '{text-align:' + attributes.img_align_selector + '; }';
        }

        let style = getFilterStyle( attributes.img_filter_selector );
        style && ( imageStyle += '.' + selectorCls + ' img{' + getFilterStyle( attributes.img_filter_selector ) + '}' );

        let hoverStyle = getFilterStyle( attributes.img_hover_filter_selector );
        hoverStyle && ( imageStyle += '.' + selectorCls + ':hover img{' + getFilterStyle( attributes.img_hover_filter_selector ) + '}' );

        if ( attributes.show_caption_selector ) {
            imageStyle += alphaGenerateTypographyCSS( caption_font_settings_selector, selectorCls + ' figcaption' );
        }
        if ( attributes.img_source && !attributes.image_source ) {
            for ( let size in attributes.img_source.sizes ) {
                size_options = [
                    ...size_options,
                    {
                        label: __( size, 'alpha-core' ),
                        value: size
                    }
                ]
            }
        }

        if ( attributes.link == 'media' && attributes.lightbox == 'yes' ) {
            if ( !document.getElementById( 'alpha-magnific-popup-css' ) ) {
                let style = document.createElement( 'link' );
                style.setAttribute( "rel", "stylesheet" );
                style.id = "alpha-magnific-popup-css";
                style.href = alpha_block_vars.theme_url + '/assets/vendor/jquery.magnific-popup/magnific-popup' + ( document.body.classList.contains( 'rtl' ) ? '-rtl' : '' ) + '.min.css';
                document.body.insertAdjacentElement( 'beforeend', style );
            }
            if ( !document.getElementById( 'alpha-magnific-popup-js' ) ) {
                let script = document.createElement( 'script' );
                script.id = "alpha-magnific-popup-js";
                script.src = alpha_block_vars.theme_url + '/assets/vendor/jquery.magnific-popup/jquery.magnific-popup.min.js';
                document.body.insertAdjacentElement( 'beforeend', script );
            }
        }

        useEffect( () => {
            setAttributes( { block_id: Math.ceil( Math.random() * 10000 ) } );
        }, [] )

        const Img = ( attributes ) => {

            function openLightBox( e ) {
                e.preventDefault();
                if ( typeof $.magnificPopup !== 'undefined' ) {
                    $.magnificPopup.open( {
                        items: {
                            src: attributes.img_source.sizes[ attributes.img_size ].url
                        },
                        type: 'image',
                        mainClass: 'mfp-with-zoom'
                    } );
                }
            }

            return ( attributes.link !== '' ?
                <a href={ attributes.link_url ? attributes.link_url : '#' } onClick={ attributes.link == 'media' && attributes.lightbox == 'yes' ? openLightBox : undefined }>
                    <img
                        style={ attributes.img_style_selector }
                        src={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].url : attributes.img_source.url }
                        alt={ attributes.img_source.alt_text ? attributes.img_source.alt_text : '' }
                        width={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].width : undefined }
                        height={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].height : undefined }
                    />
                </a>
                : <img
                    style={ attributes.img_style_selector }
                    src={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].url : attributes.img_source.url }
                    alt={ attributes.img_source.alt_text ? attributes.img_source.alt_text : '' }
                    width={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].width : undefined }
                    height={ attributes.img_source.sizes && attributes.img_source.sizes[ attributes.img_size ] ? attributes.img_source.sizes[ attributes.img_size ].height : undefined }
                />
            )
        };
        return (
            <>
                {
                    <InspectorControls key="inspector">
                        <PanelBody
                            title={ __( 'Image', 'allpha-core' ) }
                        >
                            <SelectControl
                                label={ __( 'Image Source', 'alpha-core' ) }
                                value={ attributes.image_source }
                                options={ [ { label: __( 'Custom Image', 'alpha-core' ), value: '' }, { label: __( 'Dymamic Content', 'alpha-core' ), value: 'dynamic' } ] }
                                onChange={ ( value ) => { setAttributes( { image_source: value } ); } }
                            />

                            { 'dynamic' == attributes.image_source && (
                                <AlphaDynamicContentControl
                                    label={ __( 'Dynamic Text', 'alpha-core' ) }
                                    value={ dynamic_content }
                                    options={ { field_type: 'image', content_type: content_type, content_type_value: content_type_value } }
                                    onChange={ ( value ) => { setAttributes( { dynamic_content: value } ); } }
                                />
                            ) }

                            { !attributes.image_source && (
                                <div className="components-base-control">
                                    <MediaUploadCheck>
                                        <label className="components-input-control__label" >{ __( 'Choose image', 'alpha-core' ) }</label>
                                        <div className="components-input-control__container">
                                            <MediaUpload
                                                onSelect={ ( media ) => { setAttributes( { img_source: media } ) } }
                                                value={ attributes.img_source ? attributes.img_source.id : null }
                                                render={ ( { open } ) => (
                                                    <IconButton
                                                        label={ __( 'Choose image', 'alpha-core' ) }
                                                        icon='edit'
                                                        onClick={ open }
                                                    />
                                                ) }
                                            />
                                            <IconButton
                                                label={ __( 'Remove image', 'alpha-core' ) }
                                                icon='no'
                                                onClick={ () => setAttributes( { img_source: null } ) }
                                            />
                                        </div>
                                    </MediaUploadCheck>
                                </div>
                            ) }

                            <SelectControl
                                label={ __( 'Image size', 'alpha-core' ) }
                                value={ attributes.img_size }
                                options={ size_options }
                                onChange={ value => setAttributes( { img_size: value } ) }
                            />

                            <SelectControl
                                label={ __( 'Image Alignment', 'alpha-core' ) }
                                value={ attributes.img_align_selector }
                                options={ [
                                    { label: __( 'Left', 'alpha-core' ), value: 'left' },
                                    { label: __( 'Center', 'alpha-core' ), value: 'center' },
                                    { label: __( 'Right', 'alpha-core' ), value: 'right' },
                                ] }
                                onChange={ value => setAttributes( { img_align_selector: value } ) }
                            />

                            <SelectControl
                                label={ __( 'Caption', 'alpha-core' ) }
                                value={ attributes.show_caption_selector }
                                options={ [
                                    { label: __( 'None', 'alpha-core' ), value: '' },
                                    { label: __( 'Attachment Caption', 'alpha-core' ), value: 'attachment' },
                                    { label: __( 'Custom Caption', 'alpha-core' ), value: 'custom' },
                                ] }
                                onChange={ ( value ) => setAttributes( { show_caption_selector: value } ) }
                            />

                            { attributes.show_caption_selector === 'custom' ?
                                <TextControl
                                    label={ __( 'Custom Caption', 'alpha-core' ) }
                                    value={ attributes.custom_caption }
                                    onChange={ value => setAttributes( { custom_caption: value } ) }
                                /> : ''
                            }

                            <SelectControl
                                label={ __( 'Link', 'alpha-core' ) }
                                value={ attributes.link }
                                options={ [
                                    { label: __( 'None', 'alpha-core' ), value: '' },
                                    { label: __( 'Media File', 'alpha-core' ), value: 'media' },
                                    { label: __( 'Custom URL', 'alpha-core' ), value: 'custom' },
                                ] }
                                onChange={ ( value ) => setAttributes( { link: value } ) }
                            />

                            { attributes.link === 'media' ?
                                <SelectControl
                                    label={ __( 'Lightbox', 'alpha-core' ) }
                                    value={ attributes.lightbox }
                                    options={ [
                                        { label: __( 'Yes', 'alpha-core' ), value: 'yes' },
                                        { label: __( 'No', 'alpha-core' ), value: 'no' },
                                    ] }
                                    onChange={ ( value ) => setAttributes( { lightbox: value } ) }
                                /> : ''
                            }

                            { attributes.link === 'custom' ?
                                <TextControl
                                    label={ __( 'Custom Link', 'alpha-core' ) }
                                    url={ attributes.link_url }
                                    onChange={ ( url ) => setAttributes( { link_url: url } ) }
                                />
                                : ''
                            }
                        </PanelBody>

                        <PanelBody
                            title={ __( 'Image Style', 'alpha-core' ) }
                            initialOpen={ false }
                        >
                            <UnitControl
                                label={ __( 'Width', 'alpha-core' ) }
                                value={ attributes.img_style_selector.width }
                                onChange={ ( val ) => setAttributes( {
                                    img_style_selector: {
                                        ...attributes.img_style_selector,
                                        width: val
                                    }
                                } ) }
                            />
                            <UnitControl
                                label={ __( 'Max Width', 'alpha-core' ) }
                                value={ attributes.img_style_selector.maxWidth }
                                onChange={ ( val ) => setAttributes( {
                                    img_style_selector: {
                                        ...attributes.img_style_selector,
                                        maxWidth: val
                                    }
                                } ) }
                            />
                            <UnitControl
                                label={ __( 'Height', 'alpha-core' ) }
                                value={ attributes.img_style_selector.height }
                                onChange={ ( val ) => setAttributes( {
                                    img_style_selector: {
                                        ...attributes.img_style_selector,
                                        height: val
                                    }
                                } ) }
                            />
                            <div className="alpha-typography-control alpha-dimension-control">
                                <h3 className="components-base-control" style={ { marginBottom: 15 } }>
                                    { __( 'Normal', 'alpha-core' ) }
                                </h3>
                                <RangeControl
                                    label={ __( 'Opacity', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.opacity }
                                    min={ 0 }
                                    max={ 1 }
                                    step={ 0.1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            opacity: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Blur', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.blur }
                                    min={ 0 }
                                    max={ 10 }
                                    step={ 0.1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            blur: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Brightness', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.brightness }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            brightness: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Contrast', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.contrast }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            contrast: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Saturation', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.saturation }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            saturation: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Hue', 'alpha-core' ) }
                                    value={ attributes.img_filter_selector.hue }
                                    min={ 0 }
                                    max={ 360 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_filter_selector: {
                                            ...attributes.img_filter_selector,
                                            hue: val
                                        }
                                    } ) }
                                />
                            </div>
                            <div className="alpha-typography-control alpha-dimension-control">
                                <h3 className="components-base-control" style={ { marginBottom: 15 } }>
                                    { __( 'Hover', 'alpha-core' ) }
                                </h3>
                                <RangeControl
                                    label={ __( 'Opacity', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.opacity }
                                    min={ 0 }
                                    max={ 1 }
                                    step={ 0.1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            opacity: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Blur', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.blur }
                                    min={ 0 }
                                    max={ 10 }
                                    step={ 0.1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            blur: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Brightness', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.brightness }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            brightness: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Contrast', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.contrast }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            contrast: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Saturation', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.saturation }
                                    min={ 0 }
                                    max={ 200 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            saturation: val
                                        }
                                    } ) }
                                />
                                <RangeControl
                                    label={ __( 'Hue', 'alpha-core' ) }
                                    value={ attributes.img_hover_filter_selector.hue }
                                    min={ 0 }
                                    max={ 360 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        img_hover_filter_selector: {
                                            ...attributes.img_hover_filter_selector,
                                            hue: val
                                        }
                                    } ) }
                                />
                            </div>

                            <div className="alpha-dimension-control">
                                <SelectControl
                                    label={ __( 'Border Style', 'alpha-core' ) }
                                    value={ attributes.img_style_selector.borderStyle }
                                    options={ [ { label: __( 'None', 'alpha-core' ), value: '' }, { label: __( 'Solid', 'alpha-core' ), value: 'solid' }, { label: __( 'Double', 'alpha-core' ), value: 'double' }, { label: __( 'Dotted', 'alpha-core' ), value: 'dotted' }, { label: __( 'Dashed', 'alpha-core' ), value: 'dashed' }, { label: __( 'Groove', 'alpha-core' ), value: 'groove' } ] }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderStyle: value
                                        }
                                    } ) }
                                />
                                <div style={ { display: 'flex', flexWrap: 'wrap' } }>
                                    <label style={ { width: '100%', marginBottom: 5 } }>
                                        { __( 'Border Width', 'alpha-core' ) }
                                    </label>
                                    <UnitControl
                                        label={ __( 'Top', 'alpha-core' ) }
                                        value={ attributes.img_style_selector.borderTopWidth }
                                        onChange={ ( value ) => setAttributes( {
                                            img_style_selector: {
                                                ...attributes.img_style_selector,
                                                borderTopWidth: value
                                            }
                                        } ) }
                                    />
                                    <UnitControl
                                        label={ __( 'Right', 'alpha-core' ) }
                                        value={ attributes.img_style_selector.borderRightWidth }
                                        onChange={ ( value ) => setAttributes( {
                                            img_style_selector: {
                                                ...attributes.img_style_selector,
                                                borderRightWidth: value
                                            }
                                        } ) }
                                    />
                                    <UnitControl
                                        label={ __( 'Bottom', 'alpha-core' ) }
                                        value={ attributes.img_style_selector.borderBottomWidth }
                                        onChange={ ( value ) => setAttributes( {
                                            img_style_selector: {
                                                ...attributes.img_style_selector,
                                                borderBottomWidth: value
                                            }
                                        } ) }
                                    />
                                    <UnitControl
                                        label={ __( 'Left', 'alpha-core' ) }
                                        value={ attributes.img_style_selector.borderLeftWidth }
                                        onChange={ ( value ) => setAttributes( {
                                            img_style_selector: {
                                                ...attributes.img_style_selector,
                                                borderLeftWidth: value
                                            }
                                        } ) }
                                    />
                                </div>
                                <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                    { __( 'Border Color', 'alpha-core' ) }
                                    <span className="alpha-color-show" style={ { backgroundColor: attributes.img_style_selector.borderColor } }>
                                    </span>
                                </label>
                                <ColorPalette
                                    label={ __( 'Color', 'alpha-core' ) }
                                    colors={ [] }
                                    value={ attributes.img_style_selector.borderColor }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderColor: value
                                        }
                                    } ) }
                                />
                                <label style={ { width: '100%', marginBottom: 5 } }>
                                    { __( 'Border Radius', 'alpha-core' ) }
                                </label>
                                <UnitControl
                                    label={ __( 'Top', 'alpha-core' ) }
                                    value={ attributes.img_style_selector.borderTopLeftRadius }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderTopLeftRadius: value
                                        }
                                    } ) }
                                />
                                <UnitControl
                                    label={ __( 'Right', 'alpha-core' ) }
                                    value={ attributes.img_style_selector.borderTopRightRadius }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderTopRightRadius: value
                                        }
                                    } ) }
                                />
                                <UnitControl
                                    label={ __( 'Bottom', 'alpha-core' ) }
                                    value={ attributes.img_style_selector.borderBottomRightRadius }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderBottomRightRadius: value
                                        }
                                    } ) }
                                />
                                <UnitControl
                                    label={ __( 'Left', 'alpha-core' ) }
                                    value={ attributes.img_style_selector.borderBottomLeftRadius }
                                    onChange={ ( value ) => setAttributes( {
                                        img_style_selector: {
                                            ...attributes.img_style_selector,
                                            borderBottomLeftRadius: value
                                        }
                                    } ) }
                                />
                            </div>
                        </PanelBody>

                        { attributes.show_caption_selector ?
                            <PanelBody
                                title={ __( 'Caption Style', 'alpha-core' ) }
                                initialOpen={ false }
                            >
                                <SelectControl
                                    label={ __( 'Text Alignment', 'alpha-core' ) }
                                    value={ attributes.caption_style_selector.textAlign }
                                    options={ [
                                        { label: __( 'Left', 'alpha-core' ), value: 'left' },
                                        { label: __( 'Center', 'alpha-core' ), value: 'center' },
                                        { label: __( 'Right', 'alpha-core' ), value: 'right' },
                                        { label: __( 'Justified', 'alpha-core' ), value: 'justify' },
                                    ] }
                                    onChange={ value => setAttributes( {
                                        caption_style_selector: {
                                            ...attributes.caption_style_selector,
                                            textAlign: value
                                        }
                                    } ) }
                                />

                                <label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
                                    { __( 'Background Color', 'alpha-core' ) }
                                    <span className="alpha-color-show" style={ { backgroundColor: attributes.caption_style_selector.backgroundColor } }>
                                    </span>
                                </label>
                                <ColorPalette
                                    label={ __( 'Color', 'alpha-core' ) }
                                    colors={ [] }
                                    value={ attributes.caption_style_selector.backgroundColor }
                                    onChange={ ( value ) => setAttributes( {
                                        caption_style_selector: {
                                            ...attributes.caption_style_selector,
                                            backgroundColor: value
                                        }
                                    } ) }
                                />

                                <AlphaTypographyControl
                                    label={ __( 'Typography', 'alpha-core' ) }
                                    value={ caption_font_settings_selector }
                                    options={ {} }
                                    onChange={ ( value ) => setAttributes( { caption_font_settings_selector: value } ) }
                                />

                                <RangeControl
                                    label={ __( 'Spacing', 'alpha-core' ) }
                                    value={ attributes.caption_style_selector.marginTop }
                                    min={ 0 }
                                    max={ 100 }
                                    step={ 1 }
                                    allowReset={ true }
                                    onChange={ ( val ) => setAttributes( {
                                        caption_style_selector: {
                                            ...attributes.caption_style_selector,
                                            marginTop: val
                                        }
                                    } ) }
                                />
                            </PanelBody> : '' }

                        <AlphaStyleOptionsControl
                            label={ __( 'Style Options', 'alpha-core' ) }
                            value={ style_options }
                            options={ {} }
                            onChange={ ( value ) => { setAttributes( { style_options: value } ); } }
                        />
                    </InspectorControls>
                }
                {
                    attributes.img_source ?
                        (
                            <div className={ 'alpha-gb-image ' + selectorCls + ' ' + responsiveCls + ' ' + additionalCls }>
                                <style>{ imageStyle }</style>
                                { attributes.show_caption_selector ?
                                    (
                                        <figure>
                                            <Img { ...attributes } />
                                            <figcaption style={ attributes.caption_style_selector } className="alpha-gb-caption-text">{ attributes.show_caption_selector === 'attachment' ? attributes.img_source.caption : attributes.custom_caption }</figcaption>
                                        </figure>
                                    )
                                    :
                                    <Img { ...attributes } /> }
                            </div>
                        ) : <p>{ __( 'Choose image from inspector panel', 'alpha_core' ) }</p>
                }
            </>
        )
    };

    if ( alpha_admin_vars ) {
        registerBlockType( alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-image', {
            title: alpha_admin_vars.theme_display_name + __( ' Image', 'alpha-core' ),
            icon: 'alpha',
            category: alpha_admin_vars.theme,
            attributes: {
                block_id: {
                    type: 'number'
                },
                image_source: {
                    type: 'string'
                },
                dynamic_content: {
                    type: 'object'
                },
                img_source: {
                    type: 'object',
                    default: null
                },
                img_size: {
                    type: 'string',
                    default: 'full'
                },
                img_align_selector: {
                    type: 'string'
                },
                show_caption_selector: {
                    type: 'string',
                    default: ''
                },
                custom_caption: {
                    type: 'string',
                    default: ''
                },
                link: {
                    type: 'string',
                    default: ''
                },
                lightbox: {
                    type: 'string',
                    default: 'yes'
                },
                link_url: {
                    type: 'string',
                    default: ''
                },
                img_style_selector: {
                    type: 'object',
                    default: {}
                },
                img_filter_selector: {
                    type: 'object',
                    default: {}
                },
                img_hover_filter_selector: {
                    type: 'object',
                    default: {}
                },
                caption_style_selector: {
                    type: 'object',
                    default: {}
                },
                style_options: {
                    type: 'object'
                },
                caption_font_settings_selector: {
                    type: 'object',
                    default: {}
                }
            },
            keywords: [
                __( 'image', 'alpha-core' ),
                __( 'lightbox', 'alpha-core' )
            ],
            edit: AlphaImage
        } );
    }
} )( wp.i18n, wp.blocks, wp.blockEditor, wp.components, wp.element, window.jQuery );