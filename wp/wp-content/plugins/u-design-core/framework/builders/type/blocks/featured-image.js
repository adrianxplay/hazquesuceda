/**
 * Post Type Builder - Featured Image
 * 
 * @since 1.2.0
 */

import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS } from '../../../plugins/gutenberg/assets/controls/style-options';
import { alphaAddHelperClasses } from '../../../plugins/gutenberg/assets/controls/editor-extra-classes';

(function (wpI18n, wpBlocks, wpBlockEditor, wpComponents) {
    "use strict";

    const __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        InnerBlocks = wpBlockEditor.InnerBlocks,
        SelectControl = wpComponents.SelectControl,
        TextControl = wpComponents.TextControl,
        ToggleControl = wpComponents.ToggleControl,
        Disabled = wpComponents.Disabled,
        PanelBody = wpComponents.PanelBody,
        ColorPicker = wpComponents.ColorPicker,
        ServerSideRender = wp.serverSideRender,
        UnitControl = wp.components.__experimentalUnitControl;

    const AlphaTBImage = function ({ attributes, setAttributes, name, clientId }) {

        const content_type = document.getElementById('content_type').value;
        let content_type_value,
            attrs = { el_class: attributes.el_class, image_type: attributes.image_type, show_badges: attributes.show_badges, hover_effect: attributes.hover_effect, className: attributes.className };
        if (content_type) {
            attrs.content_type = content_type;
            content_type_value = document.getElementById('content_type_' + content_type);
            if (content_type_value) {
                content_type_value = content_type_value.value;
                attrs.content_type_value = content_type_value;
            }
        }

        const style_options = Object.assign({}, attributes.style_options),
            hover_padding = Object.assign({}, attributes.hover_padding);
        let selectorCls;
        if (attributes.el_class) {
            selectorCls = attributes.el_class;
        } else {
            selectorCls = 'alpha-tb-featured-image-' + Math.ceil(Math.random() * 10000);
            setAttributes({ el_class: selectorCls });
        }

        let internalStyle = alphaGenerateStyleOptionsCSS(style_options, selectorCls);
        if (attributes.hover_bgcolor || hover_padding || attributes.hover_halign || attributes.hover_valign) {
            internalStyle += '.wp-block[data-type="' + alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-featured-image"] .block-editor-block-list__layout {';
            if (attributes.hover_bgcolor) {
                internalStyle += 'background-color:' + attributes.hover_bgcolor + ';';
            }
            if (hover_padding) {
                if (hover_padding.top) {
                    internalStyle += 'padding-top:' + hover_padding.top + ';';
                }
                if (hover_padding.right) {
                    internalStyle += 'padding-right:' + hover_padding.right + ';';
                }
                if (hover_padding.bottom) {
                    internalStyle += 'padding-bottom:' + hover_padding.bottom + ';';
                }
                if (hover_padding.left) {
                    internalStyle += 'padding-left:' + hover_padding.left + ';';
                }
            }
            if (attributes.hover_halign) {
                internalStyle += 'align-items:' + attributes.hover_halign + ';';
            }
            if (attributes.hover_valign) {
                internalStyle += 'justify-content:' + attributes.hover_valign + ';';
            }
            internalStyle += '}';
        }

        // add helper classes to parent block element
        if (attributes.className) {
            alphaAddHelperClasses(attributes.className, clientId);
        }

        return (
            <>
                <InspectorControls key="inspector">
                    <SelectControl
                        label={__('Image Type', 'alpha-core')}
                        value={attributes.image_type}
                        options={[
                            { label: __('Single image', 'alpha-core'), value: '' },
                            { label: __('Show secondary image on hover', 'alpha-core'), value: 'hover' },
                            { label: __('Slider', 'alpha-core'), value: 'slider' },
                            { label: __('Video & Image', 'alpha-core'), value: 'video' },
                            { label: __('Grid Gallery', 'alpha-core'), value: 'gallery' }
                        ]}
                        onChange={(value) => { setAttributes({ image_type: value }); }}
                        help={__('Please select the image type.', 'alpha-core')}
                    />
                    {('' === attributes.image_type || 'slider' === attributes.image_type || 'gallery' === attributes.image_type) && (
                        <SelectControl
                            label={__('Image Hover Effect', 'alpha-core')}
                            value={attributes.hover_effect}
                            options={[
                                { label: __('None', 'alpha-core'), value: '' },
                                { label: __('Zoom In', 'alpha-core'), value: 'zoom' },
                                { label: __('Effect 1', 'alpha-core'), value: 'effect-1' },
                                { label: __('Effect 2', 'alpha-core'), value: 'effect-2' },
                                { label: __('Effect 3', 'alpha-core'), value: 'effect-3' },
                                { label: __('Effect 4', 'alpha-core'), value: 'effect-4' },
                            ]}
                            onChange={(value) => { setAttributes({ hover_effect: value }); }}
                        />
                    )}
                    <ToggleControl
                        label={__('Show Content on hover', 'alpha-core')}
                        help={__('Please choose to show or hide the inner blocks on hover.', 'alpha-core')}
                        checked={attributes.show_content_hover}
                        onChange={(value) => { setAttributes({ show_content_hover: value }); }}
                    />
                    <ToggleControl
                        label={__('Show Product Badges', 'alpha-core')}
                        help={__('Please choose to show or hide the badges such as hot, sale, new, etc. This applies to only products.', 'alpha-core')}
                        checked={attributes.show_badges}
                        onChange={(value) => { setAttributes({ show_badges: value }); }}
                    />
                    <SelectControl
                        label={__('Add Link to Image', 'alpha-core')}
                        value={attributes.add_link}
                        options={[{ 'label': __('Yes', 'alpha-core'), 'value': 'yes' }, { 'label': __('No', 'alpha-core'), 'value': 'no' }, { 'label': __('Custom Link', 'alpha-core'), 'value': 'custom' }]}
                        onChange={(value) => { setAttributes({ add_link: value }); }}
                    />
                    {'custom' === attributes.add_link && (
                        <TextControl
                            label={__('Custom Link', 'alpha-core')}
                            value={attributes.custom_url}
                            onChange={(value) => { setAttributes({ custom_url: value }); }}
                            help={__('Please input custom url.', 'alpha-core')}
                        />
                    )}
                    {'custom' === attributes.add_link && attributes.custom_url && (
                        <SelectControl
                            label={__('Link Target', 'alpha-core')}
                            value={attributes.link_target}
                            options={[{ 'label': '_self', 'value': '' }, { 'label': '_blank', 'value': '_blank' }]}
                            onChange={(value) => { setAttributes({ link_target: value }); }}
                        />
                    )}
                    <PanelBody title={__('Hover Content', 'alpha-core')} initialOpen={false}>
                        <SelectControl
                            label={__('Horizontal Layout', 'alpha-core')}
                            value={attributes.hover_halign}
                            options={[{ 'label': __('Default', 'alpha-core'), 'value': '' }, { 'label': __('Left', 'alpha-core'), 'value': 'flex-start' }, { 'label': __('Center', 'alpha-core'), 'value': 'center' }, { 'label': __('Right', 'alpha-core'), 'value': 'flex-end' }]}
                            onChange={(value) => { setAttributes({ hover_halign: value }); }}
                        />
                        <SelectControl
                            label={__('Vertical Layout', 'alpha-core')}
                            value={attributes.hover_valign}
                            options={[{ 'label': __('None', 'alpha-core'), 'value': '' }, { 'label': __('Top', 'alpha-core'), 'value': 'flex-start' }, { 'label': __('Middle', 'alpha-core'), 'value': 'center' }, { 'label': __('Bottom', 'alpha-core'), 'value': 'flex-end' }]}
                            onChange={(value) => { setAttributes({ hover_valign: value }); }}
                        />
                        <SelectControl
                            label={__('Hover Effect', 'alpha-core')}
                            value={attributes.hover_start_effect}
                            options={[{ 'label': __('None', 'alpha-core'), 'value': '' }, { 'label': __('Fade In', 'alpha-core'), 'value': 'fadein' }, { 'label': __('Translate In Left', 'alpha-core'), 'value': 'translateleft' }, { 'label': __('Translate In Top', 'alpha-core'), 'value': 'translatetop' }, { 'label': __('Translate In Bottom', 'alpha-core'), 'value': 'translatebottom' }]}
                            onChange={(value) => { setAttributes({ hover_start_effect: value }); }}
                        />
                        <label>
                            {__('Background Color', 'alpha-core')}
                        </label>
                        <ColorPicker
                            label={__('Background Color', 'alpha-core')}
                            value={attributes.hover_bgcolor}
                            onChangeComplete={(value) => {
                                setAttributes({ hover_bgcolor: 'rgba(' + value.rgb.r + ',' + value.rgb.g + ',' + value.rgb.b + ',' + value.rgb.a + ')' });
                            }}
                        />
                        <button className="components-button components-range-control__reset is-secondary is-small" onClick={(e) => {
                            setAttributes({ hover_bgcolor: undefined });
                        }} style={{ margin: '-10px 0 10px 3px' }}>
                            {__('Reset', 'alpha-core')}
                        </button>
                        <div className="alpha-typography-control alpha-dimension-control">
                            <h3 className="components-base-control" style={{ marginBottom: 15 }}>
                                {__('Padding', 'alpha-core')}
                            </h3>
                            <div></div>
                            <UnitControl
                                label={__('Top', 'alpha-core')}
                                value={hover_padding.top}
                                onChange={(value) => {
                                    hover_padding.top = value;
                                    setAttributes({ hover_padding: hover_padding });
                                }}
                            />
                            <UnitControl
                                label={__('Right', 'alpha-core')}
                                value={hover_padding.right}
                                onChange={(value) => {
                                    hover_padding.right = value;
                                    setAttributes({ hover_padding: hover_padding });
                                }}
                            />
                            <UnitControl
                                label={__('Bottom', 'alpha-core')}
                                value={hover_padding.bottom}
                                onChange={(value) => {
                                    hover_padding.bottom = value;
                                    setAttributes({ hover_padding: hover_padding });
                                }}
                            />
                            <UnitControl
                                label={__('Left', 'alpha-core')}
                                value={hover_padding.left}
                                onChange={(value) => {
                                    hover_padding.left = value;
                                    setAttributes({ hover_padding: hover_padding });
                                }}
                            />
                        </div>
                    </PanelBody>
                    <AlphaStyleOptionsControl
                        label={__('Style Options', 'alpha-core')}
                        value={style_options}
                        options={{}}
                        onChange={(value) => { setAttributes({ style_options: value }); }}
                    />
                </InspectorControls>
                <>
                    <Disabled>
                        <style>
                            {internalStyle}
                        </style>
                        <ServerSideRender
                            block={name}
                            attributes={attrs}
                        />
                    </Disabled>
                    {attributes.show_content_hover && (
                        <InnerBlocks
                            allowedBlocks={['core/image', alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-heading', alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-button', alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-icon', alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-icon-box', alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-container', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-content', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-price', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-rating', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-stock', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-desc', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-woo-buttons', alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-meta']}
                        />
                    )}
                </>
            </>
        )
    }

    registerBlockType(alpha_admin_vars.theme + '-tb/' + alpha_admin_vars.theme + '-featured-image', {
        title: __('Featured Image', 'alpha-core'),
        icon: 'alpha',
        category: alpha_admin_vars.theme + '-tb',
        keywords: ['type builder', 'mini', 'card', 'post', 'attachment', 'thumbnail'],
        attributes: {
            image_type: {
                type: 'string',
                default: '',
            },
            hover_effect: {
                type: 'string',
                default: '',
            },
            show_content_hover: {
                type: 'boolean',
            },
            show_badges: {
                type: 'boolean',
            },
            content_type: {
                type: 'string',
            },
            content_type_value: {
                type: 'string',
            },
            add_link: {
                type: 'string',
                default: 'yes',
            },
            custom_url: {
                type: 'string',
            },
            link_target: {
                type: 'string',
            },
            hover_halign: {
                type: 'string',
            },
            hover_valign: {
                type: 'string',
            },
            hover_start_effect: {
                type: 'string',
            },
            hover_bgcolor: {
                type: 'string',
            },
            hover_padding: {
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
        edit: AlphaTBImage,
        save: function (props) {
            return (
                <InnerBlocks.Content />
            );
        }
    });
})(wp.i18n, wp.blocks, wp.blockEditor, wp.components);