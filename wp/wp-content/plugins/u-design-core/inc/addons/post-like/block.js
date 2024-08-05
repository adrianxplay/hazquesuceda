/**
 * Alpha Framework Post Type Builder blocks
 *
 * @since 1.0
 */

import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../../../framework/plugins/gutenberg/assets/controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS } from '../../../framework/plugins/gutenberg/assets/controls/style-options';
import { alphaAddHelperClasses } from '../../../framework/plugins/gutenberg/assets/controls/editor-extra-classes';

(function (wpI18n, wpBlocks, wpBlockEditor, wpComponents) {
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

    const tmp_options = document.getElementById('content_type_term').options, alpha_all_terms = [];
    for (var i = 0; i < tmp_options.length; i++) {
        var option = tmp_options[i];
        if (option.value) {
            alpha_all_terms.push({ label: option.innerText.trim(), value: option.value });
        }
    }

    const AlphaTBPostLike = function ({ attributes, setAttributes, name, clientId }) {

        const content_type = document.getElementById('content_type').value;
        let content_type_value,
            attrs = { disable_action: attributes.disable_action, icon_cls: attributes.icon_cls, dislike_icon_cls: attributes.dislike_icon_cls, icon_pos: attributes.icon_pos, el_class: attributes.el_class };
        if (content_type) {
            attrs.content_type = content_type;
            content_type_value = document.getElementById('content_type_' + content_type);
            if (content_type_value) {
                content_type_value = content_type_value.value;
                attrs.content_type_value = content_type_value;
            }
        }

        let internalStyle = '',
            font_settings = Object.assign({}, attributes.font_settings);

        const style_options = Object.assign({}, attributes.style_options);
        let selectorCls;
        if (attributes.el_class) {
            selectorCls = attributes.el_class;
        } else {
            selectorCls = 'alpha-tb-post-like-' + Math.ceil(Math.random() * 10000);
            setAttributes({ el_class: selectorCls });
        }

        if (attributes.alignment || attributes.font_settings) {
            let fontAtts = attributes.font_settings;
            fontAtts.alignment = attributes.alignment;

            internalStyle += alphaGenerateTypographyCSS(fontAtts, selectorCls);
        }

        if (attributes.st_icon_fs) {
            internalStyle += '.' + selectorCls + ' .alpha-tb-icon{font-size:' + attributes.st_icon_fs + '}';
        }
        if (attributes.st_icon_spacing || 0 === attributes.st_icon_spacing) {
            if ('right' === attributes.icon_pos) {
                internalStyle += '.' + selectorCls + ' .alpha-tb-icon{margin-left:' + Number(attributes.st_icon_spacing) + 'px}';
            } else {
                internalStyle += '.' + selectorCls + ' .alpha-tb-icon{margin-right:' + Number(attributes.st_icon_spacing) + 'px}';
            }
        }

        // add helper classes to parent block element
        if (attributes.className) {
            alphaAddHelperClasses(attributes.className, clientId);
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <ToggleControl
                        label={__('Disable action?', 'alpha-core')}
                        checked={attributes.disable_action}
                        onChange={(value) => { setAttributes({ disable_action: value }); }}
                    />
                    <TextControl
                        label={__('Like Icon Class (ex: fas fa-pencil-alt)', 'alpha-core')}
                        value={attributes.icon_cls}
                        onChange={(value) => { setAttributes({ icon_cls: value }); }}
                    />
                    {attrs.icon_cls && (
                        <TextControl
                            label={__('Dislike Icon Class (ex: fas fa-pencil-alt)', 'alpha-core')}
                            value={attributes.dislike_icon_cls}
                            onChange={(value) => { setAttributes({ dislike_icon_cls: value }); }}
                        />
                    )}
                    {attrs.icon_cls && (
                        <SelectControl
                            label={__('Icon Position', 'alpha-core')}
                            value={attributes.icon_pos}
                            options={[{ label: __('Left', 'alpha-core'), value: '' }, { label: __('Right', 'alpha-core'), value: 'right' }]}
                            onChange={(value) => { setAttributes({ icon_pos: value }); }}
                        />
                    )}
                    {attrs.icon_cls && (
                        <UnitControl
                            label={__('Icon Size', 'alpha-core')}
                            value={attributes.st_icon_fs}
                            onChange={(value) => { setAttributes({ st_icon_fs: value }); }}
                        />
                    )}
                    {attrs.icon_cls && (
                        <div className={'spacer'} />
                    )}
                    {attrs.icon_cls && (
                        <RangeControl
                            label={__('Spacing (px)', 'alpha-core')}
                            help={__('Spacing between icon and meta', 'alpha-core')}
                            value={attributes.st_icon_spacing}
                            min="0"
                            max="100"
                            allowReset={true}
                            onChange={(value) => { setAttributes({ st_icon_spacing: value }); }}
                        />
                    )}
                    <PanelBody title={__('Font Settings', 'alpha-core')} initialOpen={false}>
                        <SelectControl
                            label={__('Text Align', 'alpha-core')}
                            value={attributes.alignment}
                            options={[{ 'label': __('Inherit', 'alpha-core'), 'value': '' }, { 'label': __('Left', 'alpha-core'), 'value': 'left' }, { 'label': __('Center', 'alpha-core'), 'value': 'center' }, { 'label': __('Right', 'alpha-core'), 'value': 'right' }, { 'label': __('Justify', 'alpha-core'), 'value': 'justify' }]}
                            onChange={(value) => { setAttributes({ alignment: value }); }}
                        />
                        <AlphaTypographyControl
                            label={__('Typography', 'alpha-core')}
                            value={font_settings}
                            options={{}}
                            onChange={(value) => {
                                setAttributes({ font_settings: value });
                            }}
                        />
                    </PanelBody>
                    <AlphaStyleOptionsControl
                        label={__('Style Options', 'alpha-core')}
                        value={style_options}
                        options={{ hoverOptions: true }}
                        onChange={(value) => { setAttributes({ style_options: value }); }}
                    />
                </InspectorControls>
                <Disabled>
                    <style>
                        {internalStyle}
                        {alphaGenerateStyleOptionsCSS(style_options, selectorCls)}
                    </style>
                    <ServerSideRender
                        block={name}
                        attributes={attrs}
                    />
                </Disabled>
            </>
        )
    }
    registerBlockType(alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-post-like', {
        title: __('Post Like', 'alpha-core'),
        icon: 'alpha',
        category: alpha_admin_vars.theme + '-tb',
        keywords: ['type builder', 'mini', 'card', 'post', 'like', 'feature', 'care', 'wishlist', 'recommend', 'dislike'],
        attributes: {
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            disable_action: {
                type: 'boolean',
            },
            icon_cls: {
                type: 'string',
            },
            dislike_icon_cls: {
                type: 'string',
            },
            icon_pos: {
                type: 'string',
            },
            st_icon_fs: {
                type: 'string',
            },
            st_icon_spacing: {
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
        edit: AlphaTBPostLike,
        save: function () {
            return null;
        }
    });
})(wp.i18n, wp.blocks, wp.blockEditor, wp.components);