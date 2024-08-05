const AlphaTypographyControl = function ({
	label,
	value,
	options,
	onChange
}) {
	const __ = wp.i18n.__,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		RangeControl = wp.components.RangeControl,
		PanelColorSettings = wp.blockEditor.PanelColorSettings,
		el = wp.element.createElement;

	if (!value) {
		value = {};
	}

	let fonts = [{ label: __('Default', 'alpha-core'), value: '' }];
	if (alpha_block_vars.googlefonts) {
		for (let font of Object.keys(alpha_block_vars.googlefonts)) {
			fonts.push({ label: font, value: font });
		}
	}

	return el(
		'div',
		{ className: 'alpha-typography-control' },
		el(
			'h3',
			{ className: 'components-base-control', style: { marginBottom: 15 } },
			label
		),
		(!options || false !== options.fontFamily) && el(SelectControl, {
			label: __('Font Family', 'alpha-core'),
			value: value.fontFamily,
			options: fonts,
			help: __('If you want to use other Google font, please add it in Theme Options -> Style -> Typography -> Custom Font.', 'alpha-core'),
			onChange: (val) => { value.fontFamily = val; onChange(value) },
		}),
		el(TextControl, {
			label: __('Font Size', 'alpha-core'),
			value: value.fontSize,
			help: __('Enter value including any valid CSS unit, ex: 30px.', 'alpha-core'),
			onChange: (val) => { value.fontSize = val; onChange(value) },
		}),
		el(RangeControl, {
			label: __('Font Weight', 'alpha-core'),
			value: value.fontWeight,
			min: 100,
			max: 900,
			step: 100,
			allowReset: true,
			onChange: (val) => { value.fontWeight = val; onChange(value) },
		}),
		(!options || false !== options.textTransform) && el(SelectControl, {
			label: __('Text Transform', 'alpha-core'),
			value: value.textTransform,
			options: [{ label: __('Default', 'alpha-core'), value: '' }, { label: __('Inherit', 'alpha-core'), value: 'inherit' }, { label: __('Uppercase', 'alpha-core'), value: 'uppercase' }, { label: __('Lowercase', 'alpha-core'), value: 'lowercase' }, { label: __('Capitalize', 'alpha-core'), value: 'capitalize' }, { label: __('None', 'alpha-core'), value: 'none' }],
			onChange: (val) => { value.textTransform = val; onChange(value) },
		}),
		(!options || false !== options.lineHeight) && el(TextControl, {
			label: __('Line Height', 'alpha-core'),
			value: value.lineHeight,
			help: __('Enter value including any valid CSS unit, ex: 30px.', 'alpha-core'),
			onChange: (val) => { value.lineHeight = val; onChange(value) },
		}),
		(!options || false !== options.letterSpacing) && el(TextControl, {
			label: __('Letter Spacing', 'alpha-core'),
			value: value.letterSpacing,
			help: __('Enter value including any valid CSS unit, ex: 30px.', 'alpha-core'),
			onChange: (val) => { value.letterSpacing = val; onChange(value) },
		}),
		el(PanelColorSettings, {
			title: __('Color Settings', 'alpha-core'),
			initialOpen: false,
			colorSettings: [
				{
					label: __('Font Color', 'alpha-core'),
					value: value.color,
					onChange: (val) => { value.color = val; onChange(value); }
				},
				{
					label: __('Hover Color', 'alpha-core'),
					value: value.h_color,
					onChange: (val) => { value.h_color = val; onChange(value); }
				}
			]
		}),
	);
};

export default AlphaTypographyControl;

export const alphaGenerateTypographyCSS = function (font_settings, selector) {
	var internalStyle = '';
	if (!font_settings) {
		return '';
	}
	if (font_settings) {
		internalStyle += 'html .' + selector + '{';
		if (font_settings.alignment) {
			internalStyle += 'text-align:' + font_settings.alignment + ';';
		}
		if (font_settings.fontFamily) {
			internalStyle += 'font-family:' + font_settings.fontFamily + ';';
		}
		if (font_settings.fontSize) {
			let unitVal = font_settings.fontSize;
			const unit = unitVal.trim().replace(/[0-9.]/g, '');
			if (!unit) {
				unitVal += 'px';
			}
			internalStyle += 'font-size:' + unitVal + ';';
			internalStyle += '--alpha-title-size:' + unitVal + ';';
		}
		if (font_settings.fontWeight) {
			internalStyle += 'font-weight:' + font_settings.fontWeight + ';';
		}
		if (font_settings.textTransform) {
			internalStyle += 'text-transform:' + font_settings.textTransform + ';';
		}
		if (font_settings.lineHeight) {
			let unitVal = font_settings.lineHeight;
			const unit = unitVal.trim().replace(/[0-9.]/g, '');
			if (!unit && Number(unitVal) > 3) {
				unitVal += 'px';
			}
			internalStyle += 'line-height:' + unitVal + ';';
		}
		if (font_settings.letterSpacing) {
			let unitVal = font_settings.letterSpacing;
			const unit = unitVal.trim().replace(/[0-9.-]/g, '');
			if (!unit) {
				unitVal += 'px';
			}
			internalStyle += 'letter-spacing:' + unitVal + ';';
		}
		if (font_settings.color) {
			internalStyle += 'color:' + font_settings.color;
		}
		internalStyle += '}';
	}

	if (font_settings.h_color) {
		internalStyle += '.' + selector + ':hover,' + '.' + selector + ' a:hover{';
		internalStyle += 'color:' + font_settings.h_color;
		internalStyle += '}';
	}

	return internalStyle;
}
