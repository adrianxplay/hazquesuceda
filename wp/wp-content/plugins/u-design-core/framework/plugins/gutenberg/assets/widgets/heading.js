import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS, alphaGenerateStyleOptionsClass } from '../controls/style-options';
import AlphaDynamicContentControl from '../controls/dynamic-content';
import { alphaAddHelperClasses } from '../controls/editor-extra-classes';

/**
 * 01. Alpha Heading
 * 
 * @since 1.2.0
 */
(function (wpI18n, wpBlocks, wpBlockEditor, wpComponents) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        RichText = wpBlockEditor.RichText,
        UnitControl = wp.components.__experimentalUnitControl,
        PanelBody = wpComponents.PanelBody,
        TextareaControl = wpComponents.TextareaControl,
        SelectControl = wpComponents.SelectControl,
        ToggleControl = wpComponents.ToggleControl,
        ColorPicker = wpComponents.ColorPicker,
        useEffect = wp.element.useEffect,
        useState = wp.element.useState;

    const AlphaHeading = function ({ attributes, setAttributes, clientId }) {
        const [headingText, setHeadingText] = useState(attributes.title);

        let titleCls = 'title-wrapper ',
            font_settings = Object.assign({}, attributes.font_settings),
            style_options = Object.assign({}, attributes.style_options),
            selectorCls = 'alpha-heading-' + Math.ceil(Math.random() * 10000),
            responsiveCls = alphaGenerateStyleOptionsClass(style_options),
            additionalCls = attributes.className ? attributes.className + ' ' : '',
            realHeadingText = headingText,
            dynamic_content = Object.assign({}, attributes.dynamic_content),
            link_dynamic_content = Object.assign({}, attributes.link_dynamic_content);

        /* start type builder */
        let content_type = document.getElementById('content_type');
        if (typeof content_type == 'undefined') {
            content_type = false;
        } else if (content_type) {
            content_type = content_type.value;
        }
        let content_type_value = '';
        if (content_type) {
            content_type_value = document.getElementById('content_type_' + content_type);
            if (content_type_value) {
                content_type_value = content_type_value.value;
            }
        }
        /* end type builder */

        // add helper classes to parent block element
        if (attributes.className) {
            alphaAddHelperClasses(attributes.className, clientId);
        }

        useEffect(
            () => {
                let field_name = '';
                if (attributes.dynamic_content && attributes.dynamic_content.source) {
                    if ('post' == attributes.dynamic_content.source) {
                        field_name = attributes.dynamic_content.post_info;
                    } else {
                        field_name = attributes.dynamic_content[attributes.dynamic_content.source];
                    }
                    if (field_name) {
                        jQuery.ajax({
                            url: alpha_core_vars.ajax_url,
                            data: {
                                action: 'alpha_dynamic_tags_get_value',
                                nonce: alpha_core_vars.nonce,
                                content_type: content_type ? content_type : 'post',
                                content_type_value: typeof content_type != 'undefined' ? content_type_value : alpha_block_vars.edit_post_id,
                                source: attributes.dynamic_content.source,
                                field_name: field_name
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                let text;
                                if (res && res.success) {
                                    text = '' + res.data;
                                } else {
                                    text = attributes.dynamic_content.fallback;
                                }
                                setHeadingText(text);
                            }
                        });
                    }
                }
            },
            [attributes.text_source, attributes.dynamic_content && attributes.dynamic_content.source, attributes.dynamic_content && attributes.dynamic_content.post_info, attributes.dynamic_content && attributes.dynamic_content.metabox, attributes.dynamic_content && attributes.dynamic_content.acf, attributes.dynamic_content && attributes.dynamic_content.meta, attributes.dynamic_content && attributes.dynamic_content.tax],
        );
        if (attributes.text_source) {
            if (!realHeadingText) {
                realHeadingText = '';
            }
            if (attributes.dynamic_content && attributes.dynamic_content.before) {
                realHeadingText = attributes.dynamic_content.before + realHeadingText;
            }
            if (attributes.dynamic_content && attributes.dynamic_content.after) {
                realHeadingText += attributes.dynamic_content.after;
            }
        } else {
            realHeadingText = attributes.title;
        }

        titleCls += additionalCls;

        if ('simple' === attributes.decoration) {
            titleCls += '';
        } else {
            titleCls += 'title-' + attributes.decoration + ' ';
        }
        if (attributes.title_align) {
            titleCls += attributes.title_align + ' ';
        }
        titleCls += responsiveCls + ' ';

        let headingStyle = '';

        headingStyle += alphaGenerateTypographyCSS(font_settings, selectorCls + '.title-wrapper .title') +
            alphaGenerateStyleOptionsCSS(style_options, selectorCls + ' .title');

        if (attributes.decoration_spacing_selector) {
            headingStyle += '.' + selectorCls + '.title-cross .title:after{ margin-left: ' + attributes.decoration_spacing_selector + '; } .title-cross .title:before{ margin-right:' + attributes.decoration_spacing_selector + '; }';
        }

        if (attributes.border_color_selector) {
            headingStyle += '.' + selectorCls + '.title-cross .title:after{ background-color: ' + attributes.border_color_selector + '; } .title-cross .title:before{ background-color:' + attributes.border_color_selector + '; }';
        }

        return (
            <>
                <InspectorControls key="inspector">

                    <PanelBody label={__('Title', 'alpha-core')}>

                        <SelectControl
                            label={__('Text Source', 'alpha-core')}
                            value={attributes.text_source}
                            options={[{ label: __('Custom Text', 'alpha-core'), value: '' }, { label: __('Dymamic Content', 'alpha-core'), value: 'dynamic' }]}
                            onChange={(value) => { setAttributes({ text_source: value }); }}
                        />

                        {'dynamic' == attributes.text_source && (
                            <AlphaDynamicContentControl
                                label={__('Dynamic Text', 'alpha-core')}
                                value={dynamic_content}
                                options={{ field_type: 'field', content_type: content_type, content_type_value: content_type_value }}
                                onChange={(value) => { setAttributes({ dynamic_content: value }); }}
                            />
                        )}

                        {!attributes.text_source && (
                            <TextareaControl
                                label={__('Title', 'alpha-core')}
                                value={attributes.title}
                                onChange={(value) => { setAttributes({ title: value }); }}
                                placeholder={__('Enter your title', 'alpha-core')}
                            />
                        )}

                        {'dynamic' == attributes.text_source && (
                            <ToggleControl
                                label={__('Add Link?', 'alpha-core')}
                                checked={attributes.add_link}
                                onChange={(value) => { setAttributes({ add_link: value }); }}
                            />
                        )}

                        {'dynamic' == attributes.text_source && attributes.add_link && (
                            <AlphaDynamicContentControl
                                label={__('Dynamic Link', 'alpha-core')}
                                value={link_dynamic_content}
                                options={{ field_type: 'link', content_type: content_type, content_type_value: content_type_value }}
                                onChange={(value) => { setAttributes({ link_dynamic_content: value }); }}
                            />
                        )}

                        <SelectControl
                            label={__('HTML Tag', 'alpha-core')}
                            value={attributes.tag}
                            options={[
                                { label: __('H1', 'alpha-core'), value: 'h1' },
                                { label: __('H2', 'alpha-core'), value: 'h2' },
                                { label: __('H3', 'alpha-core'), value: 'h3' },
                                { label: __('H4', 'alpha-core'), value: 'h4' },
                                { label: __('H5', 'alpha-core'), value: 'h5' },
                                { label: __('H6', 'alpha-core'), value: 'h6' },
                                { label: __('p', 'alpha-core'), value: 'p' }
                            ]}
                            onChange={(value) => { setAttributes({ tag: value }); }}
                        />

                        <SelectControl
                            label={__('Type', 'alpha-core')}
                            value={attributes.decoration}
                            options={[
                                { label: __('Simple', 'alpha-core'), value: 'simple' },
                                { label: __('Cross', 'alpha-core'), value: 'cross' },
                                { label: __('Underline', 'alpha-core'), value: 'underline' }
                            ]}
                            onChange={(value) => { setAttributes({ decoration: value }); }}
                        />

                        {'cross' === attributes.decoration && (
                            <UnitControl
                                label={__('Decoration Spacing', 'alpha-core')}
                                value={attributes.decoration_spacing_selector}
                                onChange={(value) => { setAttributes({ decoration_spacing_selector: value }); }}
                            />
                        )}

                        {'cross' === attributes.decoration && (
                            <h3 className="component-title">{__('Background', 'alpha-core')}</h3>
                        )}

                        {'cross' === attributes.decoration && (
                            <ColorPicker
                                label={__('Border Color', 'alpha-core')}
                                value={attributes.border_color_selector}
                                onChangeComplete={(value) => {
                                    setAttributes({ border_color_selector: 'rgba(' + value.rgb.r + ',' + value.rgb.g + ',' + value.rgb.b + ',' + value.rgb.a + ')' });
                                }}
                            />
                        )}

                        <SelectControl
                            label={__('Title Align', 'alpha-core')}
                            value={attributes.title_align}
                            options={[
                                { label: __('Left', 'alpha-core'), value: 'title-left' },
                                { label: __('Center', 'alpha-core'), value: 'title-center' },
                                { label: __('Right', 'alpha-core'), value: 'title-right' }
                            ]}
                            onChange={(value) => { setAttributes({ title_align: value }); }}
                        />

                        <AlphaTypographyControl
                            label={__('Typography', 'alpha-core')}
                            value={font_settings}
                            options={{}}
                            onChange={(value) => { setAttributes({ font_settings: value }); }}
                        />

                    </PanelBody>

                    <AlphaStyleOptionsControl
                        label={__('Style Options', 'alpha-core')}
                        value={style_options}
                        options={{}}
                        onChange={(value) => { setAttributes({ style_options: value }); }}
                    />

                </InspectorControls>

                <div className={titleCls + selectorCls}>

                    <style>
                        {headingStyle}
                    </style>

                    <RichText
                        key='editable'
                        tagName={attributes.tag}
                        className={'title '}
                        value={realHeadingText}
                        onChange={(value) => { setAttributes({ title: value }); }}
                    />
                </div>
            </>
        )
    };

    if (alpha_admin_vars) {
        registerBlockType(alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-heading', {
            title: alpha_admin_vars.theme_display_name + __(' Heading', 'alpha-core'),
            icon: 'alpha',
            category: alpha_admin_vars.theme,
            attributes: {
                text_source: {
                    type: 'string',
                },
                dynamic_content: {
                    type: 'object',
                },
                title: {
                    type: 'string',
                    default: __('Add Your Heading Text Here', 'alpha-core'),
                },
                add_link: {
                    type: 'boolean',
                },
                link_dynamic_content: {
                    type: 'object',
                },
                tag: {
                    type: 'string',
                    default: 'h2',
                },
                decoration: {
                    type: 'string',
                    default: 'simple',
                },
                title_align: {
                    type: 'string',
                    default: 'title-left',
                },
                decoration_spacing_selector: {
                    type: 'string',
                },
                border_color_selector: {
                    type: 'string',
                },
                font_settings: {
                    type: 'object',
                    default: {},
                },
                style_options: {
                    type: 'object',
                }
            },
            keywords: [
                __('heading', 'alpha-core'),
                __('title', 'alpha-core'),
            ],
            edit: AlphaHeading,
            save: function () {
                return null;
            }
        });
    }
})(wp.i18n, wp.blocks, wp.blockEditor, wp.components);