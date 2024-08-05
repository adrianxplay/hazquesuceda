import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS, alphaGenerateStyleOptionsClass } from '../controls/style-options';
import AlphaDynamicContentControl from '../controls/dynamic-content';
/**
 * 02. Alpha Button
 * 
 * @since 1.2.0
 */
(function (wpI18n, wpBlocks, wpBlockEditor, wpComponents) {
    "use strict";

    var __ = wpI18n.__,
        registerBlockType = wpBlocks.registerBlockType,
        InspectorControls = wpBlockEditor.InspectorControls,
        UnitControl = wp.components.__experimentalUnitControl,
        RichText = wpBlockEditor.RichText,
        TextControl = wpComponents.TextControl,
        ToggleControl = wpComponents.ToggleControl,
        SelectControl = wpComponents.SelectControl,
        PanelBody = wpComponents.PanelBody,
        useEffect = wp.element.useEffect,
        useState = wp.element.useState;

    const AlphaButton = function ({ attributes, setAttributes }) {
        const [buttonUrl, setButtonUrl] = useState(attributes.link);

        let buttonCls = 'btn',
            font_settings = Object.assign({}, attributes.font_settings),
            style_options = Object.assign({}, attributes.style_options),
            responsiveCls = alphaGenerateStyleOptionsClass(style_options),
            selectorCls = 'btn-wrapper-' + Math.ceil(Math.random() * 10000),
            additionalCls = attributes.className ? ' ' + attributes.className : '',
            dynamic_content = Object.assign({}, attributes.dynamic_content);

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
        useEffect(
            () => {
                let field_name = '';
                if (attributes.dynamic_content && attributes.dynamic_content.source) {
                    if ('post' == attributes.dynamic_content.source) {
                        field_name = attributes.dynamic_content.post_info;
                    } else {
                        field_name = attributes.dynamic_content[attributes.dynamic_content.source];
                    }
                    jQuery.ajax({
                        url: alpha_core_vars.ajax_url,
                        data: {
                            action: 'alpha_dynamic_tags_get_value',
                            nonce: alpha_core_vars.nonce,
                            content_type: content_type ? content_type : 'post',
                            content_type_value: content_type ? content_type_value : alpha_block_vars.edit_post_id,
                            source: attributes.dynamic_content.source,
                            field_name: field_name
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (res) {
                            if (res && res.success) {
                                if (res.data) {
                                    setButtonUrl(res.data);
                                } else if (attributes.dynamic_content && attributes.dynamic_content.fallback) {
                                    setButtonUrl(attributes.dynamic_content.fallback);
                                }
                            }
                        }
                    });
                }
            },
            [attributes.link_source, attributes.dynamic_content && attributes.dynamic_content.source, attributes.dynamic_content && attributes.dynamic_content.post_info, attributes.dynamic_content && attributes.dynamic_content.metabox, attributes.dynamic_content && attributes.dynamic_content.acf, attributes.dynamic_content && attributes.dynamic_content.meta, attributes.dynamic_content && attributes.dynamic_content.tax],
        );

        buttonCls += additionalCls;

        if (attributes.button_expand) {
            buttonCls += ' btn-block';
        }
        if (attributes.button_type) {
            buttonCls += ' ' + attributes.button_type;
        }
        if ('btn-gradient' !== attributes.button_type && attributes.button_skin) {
            buttonCls += ' ' + attributes.button_skin;
        } else if ('btn-gradient' === attributes.button_type && attributes.button_gradient_skin) {
            buttonCls += ' ' + attributes.button_gradient_skin;
        }

        if (attributes.button_text_hover_effect) {
            buttonCls += ' btn-text-hover-effect ' + attributes.button_text_hover_effect;
        }

        if (('' == attributes.button_type || 'btn-outline' == attributes.button_type) && attributes.button_bg_hover_effect) {
            buttonCls += ' btn-bg-hover-effect ' + attributes.button_bg_hover_effect;
            if (attributes.button_bg_hover_color) {
                buttonCls += ' ' + attributes.button_bg_hover_color;
            }
        }

        if (attributes.button_size) {
            buttonCls += ' ' + attributes.button_size;
        }
        if ('btn-link' !== attributes.button_type && 'btn-outline' !== attributes.button_type && attributes.shadow) {
            buttonCls += ' ' + attributes.shadow;
        }
        if ('btn-link' !== attributes.button_type && attributes.button_border) {
            buttonCls += ' ' + attributes.button_border;
        }
        if (attributes.show_icon) {
            if ('before' === attributes.icon_pos) {
                buttonCls += ' btn-icon-left';
            }
            if ('after' === attributes.icon_pos) {
                buttonCls += ' btn-icon-right';
            }
            if (attributes.icon_hover_effect) {
                buttonCls += ' ' + attributes.icon_hover_effect;
            }
            if ('' !== attributes.icon_hover_effect && 'btn-reveal' !== attributes.icon_hover_effect && attributes.icon_hover_effect_infinite) {
                buttonCls += ' btn-infinite';
            }
        }

        buttonCls += ' ' + responsiveCls;

        let buttonStyle = '';

        buttonStyle += alphaGenerateTypographyCSS(font_settings, selectorCls + ' .btn') +
            alphaGenerateStyleOptionsCSS(style_options, selectorCls + ' .btn');
        if (attributes.button_align_selector) {
            buttonStyle += '.' + selectorCls + '{ text-align:' + attributes.button_align_selector + '; }';
        }
        if (attributes.show_icon && attributes.icon_space_selector) {
            buttonStyle += '.button-label + i, i + .button-label{ margin-left:' + attributes.icon_space_selector + 'px; }'
        }
        if (attributes.icon_size_selector) {
            buttonStyle += '.' + selectorCls + ' i{ font-size:' + attributes.icon_size_selector + '; }';
        }

        return (
            <>
                <InspectorControls key="inspector">

                    <TextControl
                        label={__('Text', 'alpha-core')}
                        value={attributes.label}
                        onChange={(value) => { setAttributes({ label: value }); }}
                        placeholder={__('Click here', 'alpha-core')}
                        help={__('Type text that will be shown on button.', 'alpha-core')}
                    />

                    <SelectControl
                        label={__('Link Source', 'alpha-core')}
                        value={attributes.link_source}
                        options={[{ label: __('Custom Link', 'alpha-core'), value: '' }, { label: __('Dymamic Content', 'alpha-core'), value: 'dynamic' }]}
                        onChange={(value) => { setAttributes({ link_source: value }); }}
                    />

                    {'dynamic' == attributes.link_source && (
                        <AlphaDynamicContentControl
                            label={__('Dynamic Content', 'alpha-core')}
                            value={dynamic_content}
                            options={{ field_type: 'link', content_type: content_type, content_type_value: content_type_value }}
                            onChange={(value) => { setAttributes({ dynamic_content: value }); }}
                        />
                    )}

                    {!attributes.link_source && (
                        <TextControl
                            label={__('Button URL', 'alpha-core')}
                            value={attributes.link}
                            onChange={(value) => { setAttributes({ link: value }); setButtonUrl(value); }}
                            placeholder={__('Paste url or type', 'alpha-core')}
                            help={__('Input URL where you will move when button is clicked.', 'alpha-core')}
                        />
                    )}

                    <ToggleControl
                        label={__('Expand', 'alpha-core')}
                        checked={attributes.button_expand}
                        onChange={(value) => { setAttributes({ button_expand: value }); }}
                        help={__('Controls button\'s alignment. Choose from Left, Center, Right.', 'alpha-core')}
                    />

                    {!attributes.button_expand && (
                        <SelectControl
                            label={__('Alignments', 'alpha-core')}
                            value={attributes.button_align_selector}
                            options={[
                                { label: __('Left', 'alpha-core'), value: 'left' },
                                { label: __('Center', 'alpha-core'), value: 'center' },
                                { label: __('Right', 'alpha-core'), value: 'right' }
                            ]}
                            onChange={(value) => { setAttributes({ button_align_selector: value }); }}
                        />
                    )}

                    <SelectControl
                        label={__('Type', 'alpha-core')}
                        value={attributes.button_type}
                        options={[
                            { label: __('Default', 'alpha-core'), value: '' },
                            { label: __('Gradient', 'alpha-core'), value: 'btn-gradient' },
                            { label: __('Outline', 'alpha-core'), value: 'btn-outline' },
                            { label: __('Link', 'alpha-core'), value: 'btn-link' }
                        ]}
                        onChange={(value) => { setAttributes({ button_type: value }); }}
                    />

                    {'btn-gradient' !== attributes.button_type && (
                        <SelectControl
                            label={__('Skin', 'alpha-core')}
                            value={attributes.button_skin}
                            options={[
                                { label: __('Default', 'alpha-core'), value: '' },
                                { label: __('Primary', 'alpha-core'), value: 'btn-primary' },
                                { label: __('Secondary', 'alpha-core'), value: 'btn-secondary' },
                                { label: __('Warning', 'alpha-core'), value: 'btn-warning' },
                                { label: __('Danger', 'alpha-core'), value: 'btn-danger' },
                                { label: __('Success', 'alpha-core'), value: 'btn-success' },
                                { label: __('Dark', 'alpha-core'), value: 'btn-dark' },
                                { label: __('White', 'alpha-core'), value: 'btn-white' }
                            ]}
                            onChange={(value) => { setAttributes({ button_skin: value }); }}
                        />
                    )}

                    {'btn-gradient' === attributes.button_type && (
                        <SelectControl
                            label={__('Gradient Skin', 'alpha-core')}
                            value={attributes.button_gradient_skin}
                            options={[
                                { label: __('None', 'alpha-core'), value: '' },
                                { label: __('Default', 'alpha-core'), value: 'btn-gra-default' },
                                { label: __('Blue', 'alpha-core'), value: 'btn-gra-blue' },
                                { label: __('Orange', 'alpha-core'), value: 'btn-gra-orange' },
                                { label: __('Pink', 'alpha-core'), value: 'btn-gra-pink' },
                                { label: __('Green', 'alpha-core'), value: 'btn-gra-green' },
                                { label: __('Dark', 'alpha-core'), value: 'btn-gra-dark' }
                            ]}
                            onChange={(value) => { setAttributes({ button_gradient_skin: value }); }}
                        />
                    )}

                    <SelectControl
                        label={__('Text Hover Effect', 'alpha-core')}
                        value={attributes.button_text_hover_effect}
                        options={[
                            { label: __('No Effect', 'alpha-core'), value: '' },
                            { label: __('Switch Left', 'alpha-core'), value: 'btn-text-switch-left' },
                            // { label: __( 'Switch Up', 'alpha-core' ), value: 'btn-text-switch-up' },
                            { label: __('Marquee Left', 'alpha-core'), value: 'btn-text-marquee-left' }
                            // { label: __( 'Marquee Up', 'alpha-core' ), value: 'btn-text-marquee-up' },
                            // { label: __( 'Marquee Down', 'alpha-core' ), value: 'btn-text-marquee-down' }
                        ]}
                        onChange={(value) => { setAttributes({ button_text_hover_effect: value }); }}
                    />

                    {('' === attributes.button_type || 'btn-outline' === attributes.button_type) && (
                        <SelectControl
                            label={__('Background Hover Effect', 'alpha-core')}
                            value={attributes.button_bg_hover_effect}
                            options={[
                                { label: __('No Effect', 'alpha-core'), value: '' },
                                { label: __('Sweep To Right', 'alpha-core'), value: 'btn-sweep-to-right' },
                                { label: __('Sweep To Left', 'alpha-core'), value: 'btn-sweep-to-left' },
                                { label: __('Sweep To Bottom', 'alpha-core'), value: 'btn-sweep-to-bottom' },
                                { label: __('Sweep To Top', 'alpha-core'), value: 'btn-sweep-to-top' },
                                { label: __('Sweep To Horizontal', 'alpha-core'), value: 'btn-sweep-to-horizontal' },
                                { label: __('Sweep To Vertical', 'alpha-core'), value: 'btn-sweep-to-vertical' },
                                { label: __('Antiman', 'alpha-core'), value: 'btn-antiman' },
                                { label: __('Bubble', 'alpha-core'), value: 'btn-bubble' }
                            ]}
                            onChange={(value) => { setAttributes({ button_bg_hover_effect: value }); }}
                        />
                    )}

                    {('' === attributes.button_type || 'btn-outline' === attributes.button_type) && ('' !== attributes.button_bg_hover_effect) && (
                        <SelectControl
                            label={__('Hover Effect Color', 'alpha-core')}
                            value={attributes.button_bg_hover_color}
                            options={[
                                { label: __('Primary', 'alpha-core'), value: 'hover-bg-primary' },
                                { label: __('Secondary', 'alpha-core'), value: 'hover-bg-secondary' },
                                { label: __('Warning', 'alpha-core'), value: 'hover-bg-warning' },
                                { label: __('Danger', 'alpha-core'), value: 'hover-bg-danger' },
                                { label: __('Success', 'alpha-core'), value: 'hover-bg-success' },
                                { label: __('Dark', 'alpha-core'), value: 'hover-bg-dark' }
                            ]}
                            onChange={(value) => { setAttributes({ button_bg_hover_color: value }); }}
                        />
                    )}

                    <SelectControl
                        label={__('Size', 'alpha-core')}
                        value={attributes.button_size}
                        options={[
                            { label: __('Default', 'alpha-core'), value: '' },
                            { label: __('Small', 'alpha-core'), value: 'btn-sm' },
                            { label: __('Medium', 'alpha-core'), value: 'btn-md' },
                            { label: __('Large', 'alpha-core'), value: 'btn-lg' },
                            { label: __('Extra Large', 'alpha-core'), value: 'btn-xl' }
                        ]}
                        onChange={(value) => { setAttributes({ button_size: value }); }}
                    />

                    {'btn-link' !== attributes.button_type && 'btn-outline' !== attributes.button_type && (
                        <SelectControl
                            label={__('Box Shadow', 'alpha-core')}
                            value={attributes.shadow}
                            options={[
                                { label: __('None', 'alpha-core'), value: '' },
                                { label: __('Shadow 1', 'alpha-core'), value: 'btn-shadow-sm' },
                                { label: __('Shadow 2', 'alpha-core'), value: 'btn-shadow' },
                                { label: __('Shadow 3', 'alpha-core'), value: 'btn-shadow-lg' }
                            ]}
                            onChange={(value) => { setAttributes({ shadow: value }); }}
                        />
                    )}

                    {'btn-link' === attributes.button_type && (
                        <SelectControl
                            label={__('Hover Underline', 'alpha-core')}
                            value={attributes.link_hover_type}
                            options={[
                                { label: __('None', 'alpha-core'), value: '' },
                                { label: __('Underline1', 'alpha-core'), value: 'btn-underline sm' },
                                { label: __('Underline2', 'alpha-core'), value: 'btn-underline' },
                                { label: __('Underline3', 'alpha-core'), value: 'btn-underline lg' }
                            ]}
                            onChange={(value) => { setAttributes({ link_hover_type: value }); }}
                        />
                    )}

                    {'btn-link' !== attributes.button_type && (
                        <SelectControl
                            label={__('Border Style', 'alpha-core')}
                            value={attributes.button_border}
                            options={[
                                { label: __('Square', 'alpha-core'), value: '' },
                                { label: __('Rounded', 'alpha-core'), value: 'btn-rounded' },
                                { label: __('Ellipse', 'alpha-core'), value: 'btn-ellipse' },
                                { label: __('50%', 'alpha-core'), value: 'btn-circle' }
                            ]}
                            onChange={(value) => { setAttributes({ button_border: value }); }}
                        />
                    )}

                    <ToggleControl
                        label={__('Show Icon?', 'alpha-core')}
                        checked={attributes.show_icon}
                        onChange={(value) => { setAttributes({ show_icon: value }); }}
                        help={__('Allows to show icon before or after button text.', 'alpha-core')}
                    />

                    <PanelBody>

                        {attributes.show_icon && (
                            <TextControl
                                label={__('Icon', 'alpha-core')}
                                value={attributes.icon}
                                onChange={(value) => { setAttributes({ icon: value }); }}
                                placeholder={__('Type the icon class name', 'alpha-core')}
                                help={__('Please check this link to see icons which Alpha supports.', 'alpha-core')}
                            />
                        )}

                        {attributes.show_icon && (
                            <TextControl
                                label={__('Icon Spacing', 'alpha-core')}
                                value={attributes.icon_space_selector}
                                onChange={(value) => { setAttributes({ icon_space_selector: value }); }}
                                help={__('Set the spacing(px) between button label and icon.', 'alpha-core')}
                            />
                        )}

                        {attributes.show_icon && (
                            <UnitControl
                                label={__('Icon Size', 'alpha-core')}
                                value={attributes.icon_size_selector}
                                onChange={(value) => { setAttributes({ icon_size_selector: value }); }}
                            />
                        )}

                        {attributes.show_icon && (
                            <SelectControl
                                label={__('Icon Position', 'alpha-core')}
                                value={attributes.icon_pos}
                                options={[
                                    { label: __('After', 'alpha-core'), value: 'after' },
                                    { label: __('Before', 'alpha-core'), value: 'before' }
                                ]}
                                onChange={(value) => { setAttributes({ icon_pos: value }); }}
                            />
                        )}

                        {attributes.show_icon && (
                            <SelectControl
                                label={__('Icon Hover Effect', 'alpha-core')}
                                value={attributes.icon_hover_effect}
                                options={[
                                    { label: __('None', 'alpha-core'), value: '' },
                                    { label: __('Slide Left', 'alpha-core'), value: 'btn-slide-left' },
                                    { label: __('Slide Right', 'alpha-core'), value: 'btn-slide-right' },
                                    { label: __('Slide Up', 'alpha-core'), value: 'btn-slide-up' },
                                    { label: __('Slide Down', 'alpha-core'), value: 'btn-slide-down' },
                                    { label: __('Slide Reveal', 'alpha-core'), value: 'btn-reveal' }
                                ]}
                                onChange={(value) => { setAttributes({ icon_hover_effect: value }); }}
                            />
                        )}

                        {'' !== attributes.icon_hover_effect && 'btn-reveal' !== attributes.icon_hover_effect && (
                            <ToggleControl
                                label={__('Animation Infinite', 'alpha-core')}
                                checked={attributes.icon_hover_effect_infinite}
                                onChange={(value) => { setAttributes({ icon_hover_effect_infinite: value }); }}
                            />
                        )}

                    </PanelBody>

                    <PanelBody label={__('Font Settings', 'alpha-core')}>

                        <AlphaTypographyControl
                            label={__('Text Typography', 'alpha-core')}
                            value={font_settings}
                            options={{}}
                            onChange={(value) => { setAttributes({ font_settings: value }); }}
                        />

                    </PanelBody>

                    <AlphaStyleOptionsControl
                        label={__('Style Options', 'alpha-core')}
                        value={style_options}
                        options={{ hoverOptions: true }}
                        onChange={(value) => { setAttributes({ style_options: value }); }}
                    />

                </InspectorControls>

                <style>
                    {buttonStyle}
                </style>

                <div className={`btn-wrapper ${selectorCls}`}>
                    <a className={buttonCls} href={attributes.link ? attributes.link : '#'}>
                        {attributes.show_icon && 'before' === attributes.icon_pos && (
                            <i className={attributes.icon ? attributes.icon : ''}></i>
                        )}
                        <RichText
                            key='editable'
                            tagName='span'
                            className='button-label'
                            value={attributes.label}
                            data-text={attributes.button_text_hover_effect ? attributes.label : ''}
                            onChange={(value) => { setAttributes({ label: value }); }}
                        />
                        {attributes.show_icon && 'after' === attributes.icon_pos && (
                            <i className={attributes.icon ? attributes.icon : ''}></i>
                        )}
                    </a>
                </div>
            </>
        )
    };

    if (alpha_admin_vars) {
        registerBlockType(alpha_admin_vars.theme + '/' + alpha_admin_vars.theme + '-button', {
            title: alpha_admin_vars.theme_display_name + __(' Button', 'alpha-core'),
            icon: 'alpha',
            category: alpha_admin_vars.theme,
            attributes: {
                label: {
                    type: 'string',
                    default: __('Click Here', 'alpha-core'),
                },
                link_source: {
                    type: 'string',
                },
                dynamic_content: {
                    type: 'object',
                },
                link: {
                    type: 'string',
                    default: ''
                },
                button_expand: {
                    type: 'boolean'
                },
                button_align_selector: {
                    type: 'string',
                    default: ''
                },
                button_type: {
                    type: 'string',
                    default: ''
                },
                button_skin: {
                    type: 'string',
                    default: ''
                },
                button_gradient_skin: {
                    type: 'string',
                    default: ''
                },
                button_text_hover_effect: {
                    type: 'string',
                },
                button_bg_hover_effect: {
                    type: 'string',
                },
                button_bg_hover_color: {
                    type: 'string',
                },
                button_size: {
                    type: 'string',
                },
                link_hover_type: {
                    type: 'string',
                    default: ''
                },
                shadow: {
                    type: 'string',
                    default: ''
                },
                button_border: {
                    type: 'string',
                    default: ''
                },
                show_icon: {
                    type: 'boolean'
                },
                icon: {
                    type: 'string',
                    default: 'fas fa-arrow-right'
                },
                icon_pos: {
                    type: 'string',
                    default: 'after'
                },
                icon_hover_effect: {
                    type: 'string',
                    default: ''
                },
                icon_hover_effect_infinite: {
                    type: 'boolean'
                },
                link_break: {
                    type: 'string',
                    default: 'nowrap'
                },
                button_typography: {
                    type: 'object',
                    default: {}
                },
                icon_space_selector: {
                    type: 'int'
                },
                icon_size_selector: {
                    type: 'string'
                },
                style_options: {
                    type: 'object',
                },
                color_tab: {
                    type: 'string',
                    default: 'normal'
                },
                font_settings: {
                    type: 'object',
                    default: {}
                }
            },
            keywords: [
                __('button', 'alpha-core')
            ],
            edit: AlphaButton,
            save: function () {
                return null;
            }
        });
    }
})(wp.i18n, wp.blocks, wp.blockEditor, wp.components);