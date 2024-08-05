const AlphaStyleOptionsControl = function ( {
	label,
	value,
	options,
	onChange
} ) {
	const __ = wp.i18n.__,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		UnitControl = wp.components.__experimentalUnitControl,
		RangeControl = wp.components.RangeControl,
		PanelBody = wp.components.PanelBody,
		ColorPalette = wp.components.ColorPalette,
		ColorPicker = wp.components.ColorPicker,
		IconButton = wp.components.IconButton,
		ToggleControl = wp.components.ToggleControl,
		MediaUpload = wp.blockEditor.MediaUpload;

	if ( !value ) {
		value = {};
	}

	const marginEnabled = !options || false !== options.margin,
		paddingEnabled = !options || false !== options.padding,
		positionEnabled = !options || false !== options.position,
		borderEnabled = !options || false !== options.border,
		bgEnabled = !options || false !== options.bg,
		visibilityEnabled = !options || false !== options.visibility,
		boxShadowEnabled = ! options || false !== options.boxShadow,
		transformEnabled = ! options || false !== options.transform;
	return (
		<>
			<PanelBody title={ label } initialOpen={ false }>
				{ bgEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Background', 'alpha-core' ) }
						</h3>
						<ColorPalette
							label={ __( 'Color', 'alpha-core' ) }
							value={ value.bg && value.bg.color }
							colors={ [] }
							onChange={ ( val ) => {
								if ( !value.bg ) {
									value.bg = {};
								}
								value.bg.color = val;
								onChange( value );
							} }
						/>
						<button className="components-button components-range-control__reset is-secondary is-small" onClick={ ( e ) => {
							value.bg.color = '';
							onChange( value );
						} } style={ { margin: '-10px 0 10px 3px' } }>
							{ __( 'Reset', 'alpha-core' ) }
						</button>
						<MediaUpload
							allowedTypes={ [ 'image' ] }
							value={ value.bg && value.bg.img_id }
							onSelect={ ( image ) => {
								if ( !value.bg ) {
									value.bg = {};
								}
								value.bg.img_url = image.url;
								value.bg.img_id = image.id;
								onChange( value );
							} }
							render={ ( _ref ) => {
								var open = _ref.open;
								return (
									<div>
										{ value.bg && value.bg.img_id && (
											<img src={ value.bg.img_url } width="100" />
										) }
										<IconButton
											className="components-toolbar__control"
											label={ __( 'Change image', 'alpha-core' ) }
											icon="edit"
											onClick={ open }
										/>
										<IconButton
											className="components-toolbar__control"
											label={ __( 'Remove image', 'alpha-core' ) }
											icon="no"
											onClick={ () => {
												if ( !value.bg ) {
													value.bg = {};
												}
												value.bg.img_url = undefined;
												value.bg.img_id = undefined;
												onChange( value );
											} }
										/>
									</div>
								);
							} }
						/>
						{ value.bg && value.bg.img_id && (
							<SelectControl
								label={ __( 'Position', 'alpha-core' ) }
								value={ value.bg && value.bg.position }
								options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Center Center', 'alpha-core' ), value: 'center center' }, { label: __( 'Center Left', 'alpha-core' ), value: 'center left' }, { label: __( 'Center Right', 'alpha-core' ), value: 'center right' }, { label: __( 'Top Center', 'alpha-core' ), value: 'top center' }, { label: __( 'Top Left', 'alpha-core' ), value: 'top left' }, { label: __( 'Top Right', 'alpha-core' ), value: 'top right' }, { label: __( 'Bottom Center', 'alpha-core' ), value: 'bottom center' }, { label: __( 'Bottom Left', 'alpha-core' ), value: 'bottom left' }, { label: __( 'Bottom Right', 'alpha-core' ), value: 'bottom right' } ] }
								onChange={ ( val ) => {
									if ( !value.bg ) {
										value.bg = {};
									}
									value.bg.position = val;
									onChange( value );
								} }
							/>
						) }
						{ value.bg && value.bg.img_id && (
							<SelectControl
								label={ __( 'Attachment', 'alpha-core' ) }
								value={ value.bg && value.bg.attachment }
								options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Scroll', 'alpha-core' ), value: 'scroll' }, { label: __( 'Fixed' ), value: 'fixed' } ] }
								onChange={ ( val ) => {
									if ( !value.bg ) {
										value.bg = {};
									}
									value.bg.attachment = val;
									onChange( value );
								} }
							/>
						) }
						{ value.bg && value.bg.img_id && (
							<SelectControl
								label={ __( 'Repeat', 'alpha-core' ) }
								value={ value.bg && value.bg.repeat }
								options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'No-repeat', 'alpha-core' ), value: 'no-repeat' }, { label: __( 'Repeat', 'alpha-core' ), value: 'repeat' }, { label: __( 'Repeat-x', 'alpha-core' ), value: 'repeat-x' }, { label: __( 'Repeat-y', 'alpha-core' ), value: 'repeat-y' } ] }
								onChange={ ( val ) => {
									if ( !value.bg ) {
										value.bg = {};
									}
									value.bg.repeat = val;
									onChange( value );
								} }
							/>
						) }
						{ value.bg && value.bg.img_id && (
							<SelectControl
								label={ __( 'Size', 'alpha-core' ) }
								value={ value.bg && value.bg.size }
								options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Auto', 'alpha-core' ), value: 'auto' }, { label: __( 'Cover', 'alpha-core' ), value: 'cover' }, { label: __( 'Contain', 'alpha-core' ), value: 'contain' } ] }
								onChange={ ( val ) => {
									if ( !value.bg ) {
										value.bg = {};
									}
									value.bg.size = val;
									onChange( value );
								} }
							/>
						) }
					</div>
				) }
				{ borderEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Border', 'alpha-core' ) }
						</h3>
						<SelectControl
							label={ __( 'Style', 'alpha-core' ) }
							value={ value.border && value.border.style }
							options={ [ { label: __( 'None', 'alpha-core' ), value: '' }, { label: __( 'Solid', 'alpha-core' ), value: 'solid' }, { label: __( 'Double', 'alpha-core' ), value: 'double' }, { label: __( 'Dotted', 'alpha-core' ), value: 'dotted' }, { label: __( 'Dashed', 'alpha-core' ), value: 'dashed' }, { label: __( 'Groove', 'alpha-core' ), value: 'groove' } ] }
							onChange={ ( val ) => {
								if ( !value.border ) {
									value.border = {};
								}
								value.border.style = val;
								onChange( value );
							} }
						/>
						<div style={ { display: 'flex', flexWrap: 'wrap' } }>
							<label style={ { width: '100%', marginBottom: 5 } }>
								{ __( 'Width', 'alpha-core' ) }
							</label>
							<UnitControl
								label={ __( 'Top', 'alpha-core' ) }
								value={ value.border && value.border.top }
								onChange={ ( val ) => {
									if ( !value.border ) {
										value.border = {};
									}
									value.border.top = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Right', 'alpha-core' ) }
								value={ value.border && value.border.right }
								onChange={ ( val ) => {
									if ( !value.border ) {
										value.border = {};
									}
									value.border.right = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Bottom', 'alpha-core' ) }
								value={ value.border && value.border.bottom }
								onChange={ ( val ) => {
									if ( !value.border ) {
										value.border = {};
									}
									value.border.bottom = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Left', 'alpha-core' ) }
								value={ value.border && value.border.left }
								onChange={ ( val ) => {
									if ( !value.border ) {
										value.border = {};
									}
									value.border.left = val;
									onChange( value );
								} }
							/>
							<label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
								{ __( 'Color', 'alpha-core' ) }
								<span className="alpha-color-show" style={ { backgroundColor: value.border && value.border.color } }>
								</span>
							</label>
							<ColorPicker
								label={ __( 'Color', 'alpha-core' ) }
								color={ value.border && value.border.color }
								onChangeComplete={ ( val ) => {
									if ( ! value.border ) {
										value.border = {};
									}
									value.border.color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')';
									onChange( value );
								} }
							/>
							<button className="components-button components-range-control__reset is-secondary is-small" onClick={ ( e ) => {
								if ( ! value.border ) {
									value.border = {};
								}
								value.border.color = '';
								onChange( value );
							} } style={ { margin: '-10px 0 20px 3px' } }>
								{ __( 'Reset', 'alpha-core' ) }
							</button>
						</div>
						<label style={ { width: '100%', marginBottom: 5 } }>
							{ __( 'Border Radius', 'alpha-core' ) }
						</label>
						<UnitControl
							label={ __( 'Top', 'alpha-core' ) }
							value={ value.borderRadius && value.borderRadius.top }
							onChange={ ( val ) => {
								if ( !value.borderRadius ) {
									value.borderRadius = {};
								}
								value.borderRadius.top = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Right', 'alpha-core' ) }
							value={ value.borderRadius && value.borderRadius.right }
							onChange={ ( val ) => {
								if ( !value.borderRadius ) {
									value.borderRadius = {};
								}
								value.borderRadius.right = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Bottom', 'alpha-core' ) }
							value={ value.borderRadius && value.borderRadius.bottom }
							onChange={ ( val ) => {
								if ( !value.borderRadius ) {
									value.borderRadius = {};
								}
								value.borderRadius.bottom = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Left', 'alpha-core' ) }
							value={ value.borderRadius && value.borderRadius.left }
							onChange={ ( val ) => {
								if ( !value.borderRadius ) {
									value.borderRadius = {};
								}
								value.borderRadius.left = val;
								onChange( value );
							} }
						/>
					</div>
				) }
				{ marginEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Margin', 'alpha-core' ) }
						</h3>
						<div></div>
						<UnitControl
							label={ __( 'Top', 'alpha-core' ) }
							value={ value.margin && value.margin.top }
							onChange={ ( val ) => {
								if ( !value.margin ) {
									value.margin = {};
								}
								value.margin.top = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Right', 'alpha-core' ) }
							value={ value.margin && value.margin.right }
							onChange={ ( val ) => {
								if ( !value.margin ) {
									value.margin = {};
								}
								value.margin.right = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Bottom', 'alpha-core' ) }
							value={ value.margin && value.margin.bottom }
							onChange={ ( val ) => {
								if ( !value.margin ) {
									value.margin = {};
								}
								value.margin.bottom = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Left', 'alpha-core' ) }
							value={ value.margin && value.margin.left }
							onChange={ ( val ) => {
								if ( !value.margin ) {
									value.margin = {};
								}
								value.margin.left = val;
								onChange( value );
							} }
						/>
					</div>
				) }
				{ paddingEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Padding', 'alpha-core' ) }
						</h3>
						<div></div>
						<UnitControl
							label={ __( 'Top', 'alpha-core' ) }
							value={ value.padding && value.padding.top }
							onChange={ ( val ) => {
								if ( !value.padding ) {
									value.padding = {};
								}
								value.padding.top = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Right', 'alpha-core' ) }
							value={ value.padding && value.padding.right }
							onChange={ ( val ) => {
								if ( !value.padding ) {
									value.padding = {};
								}
								value.padding.right = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Bottom', 'alpha-core' ) }
							value={ value.padding && value.padding.bottom }
							onChange={ ( val ) => {
								if ( !value.padding ) {
									value.padding = {};
								}
								value.padding.bottom = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Left', 'alpha-core' ) }
							value={ value.padding && value.padding.left }
							onChange={ ( val ) => {
								if ( !value.padding ) {
									value.padding = {};
								}
								value.padding.left = val;
								onChange( value );
							} }
						/>
					</div>
				) }
				{ positionEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Position', 'alpha-core' ) }
						</h3>
						<SelectControl
							label={ __( 'Style', 'alpha-core' ) }
							value={ value.position && value.position.style }
							options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Static', 'alpha-core' ), value: 'static' }, { label: __( 'Relative', 'alpha-core' ), value: 'relative' }, { label: __( 'Absolute', 'alpha-core' ), value: 'absolute' }, { label: __( 'Fixed', 'alpha-core' ), value: 'fixed' }, { label: __( 'Sticky', 'alpha-core' ), value: 'sticky' } ] }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.style = val;
								onChange( value );
							} }
						/>
						<RangeControl
							label={ __( 'Z-index', 'alpha-core' ) }
							value={ value.position && typeof value.position.zindex != 'undefined' && value.position.zindex }
							min="-10"
							max="100"
							allowReset={ true }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.zindex = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Top', 'alpha-core' ) }
							value={ value.position && value.position.top }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.top = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Right', 'alpha-core' ) }
							value={ value.position && value.position.right }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.right = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Bottom', 'alpha-core' ) }
							value={ value.position && value.position.bottom }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.bottom = val;
								onChange( value );
							} }
						/>
						<UnitControl
							label={ __( 'Left', 'alpha-core' ) }
							value={ value.position && value.position.left }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.left = val;
								onChange( value );
							} }
						/>
						<div className="spacer" />
						<SelectControl
							label={ __( 'Width', 'alpha-core' ) }
							value={ value.position && value.position.width }
							options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Full Width (100%)', 'alpha-core' ), value: '100%' }, { label: __( 'Inline (auto)', 'alpha-core' ), value: 'auto' }, { label: __( 'Fit Content', 'alpha-core' ), value: 'fit-content' }, { label: __( 'Custom', 'alpha-core' ), value: 'custom' } ] }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.width = val;
								onChange( value );
							} }
						/>
						{ value.position && 'custom' === value.position.width && (
							<UnitControl
								label={ __( 'Width', 'alpha-core' ) }
								value={ value.position && value.position.width_val }
								onChange={ ( val ) => {
									if ( ! value.position ) {
										value.position = {};
									}
									value.position.width_val = val;
									onChange( value );
								} }
							/>
						) }
						{ value.position && 'custom' === value.position.width && (
							<div className="spacer" />
						) }
						<SelectControl
							label={ __( 'Horizontal Align', 'alpha-core' ) }
							help={ __( 'This only works in flex container or with width property together.', 'alpha-core' ) }
							value={ value.position && value.position.halign }
							options={ [ { label: __( 'Default', 'alpha-core' ), value: '' }, { label: __( 'Center', 'alpha-core' ), value: 'x' }, { label: __( 'Left', 'alpha-core' ), value: 'e' }, { label: __( 'Right', 'alpha-core' ), value: 's' } ] }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.halign = val;
								onChange( value );
							} }
						/>
						<RangeControl
							label={ __( 'Opacity', 'alpha-core' ) }
							value={ value.position && typeof value.position.opacity != 'undefined' && value.position.opacity }
							min="0"
							max="1"
							step="0.01"
							allowReset={ true }
							onChange={ ( val ) => {
								if ( !value.position ) {
									value.position = {};
								}
								value.position.opacity = val;
								onChange( value );
							} }
						/>
					</div>
				) }
				{ transformEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Transform', 'alpha-core' ) }
						</h3>

						<ToggleControl
							label={ __( 'Translate', 'alpha-core' ) }
							checked={ value.transform && value.transform.translate }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.translate = val;
								onChange( value );
							} }
						/>
						{ value.transform && value.transform.translate && (
							<div className="mb-3" style={ { display: 'flex', flexWrap: 'wrap', marginTop: -10 } }>
								<UnitControl
									label={ __( 'X', 'alpha-core' ) }
									value={ value.transform && value.transform.translatex }
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.translatex = val;
										onChange( value );
									} }
								/>
								<UnitControl
									label={ __( 'Y', 'alpha-core' ) }
									value={ value.transform && value.transform.translatey }
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.translatey = val;
										onChange( value );
									} }
								/>
							</div>
						) }

						<ToggleControl
							label={ __( 'Rotate', 'alpha-core' ) }
							checked={ value.transform && value.transform.rotate }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.rotate = val;
								onChange( value );
							} }
						/>
						{ value.transform && value.transform.rotate && (
							<div className="mb-3" style={ { marginTop: -10 } }>
								<RangeControl
									label={ __( 'Degree', 'alpha-core' ) }
									value={ value.transform && value.transform.rotatedeg }
									min="-360"
									max="360"
									allowReset="true"
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.rotatedeg = val;
										onChange( value );
									} }
								/>
							</div>
						) }

						<ToggleControl
							label={ __( 'Scale', 'alpha-core' ) }
							checked={ value.transform && value.transform.scale }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.scale = val;
								onChange( value );
							} }
						/>
						{ value.transform && value.transform.scale && (
							<div className="mb-3" style={ { marginTop: -10 } }>
								<RangeControl
									label={ __( 'X', 'alpha-core' ) }
									value={ value.transform && value.transform.scalex }
									min="0"
									max="2"
									step="0.1"
									allowReset="true"
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.scalex = val;
										onChange( value );
									} }
								/>
								<RangeControl
									label={ __( 'Y', 'alpha-core' ) }
									value={ value.transform && value.transform.scaley }
									min="0"
									max="2"
									step="0.1"
									allowReset="true"
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.scaley = val;
										onChange( value );
									} }
								/>
							</div>
						) }

						<ToggleControl
							label={ __( 'Skew', 'alpha-core' ) }
							checked={ value.transform && value.transform.skew }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.skew = val;
								onChange( value );
							} }
						/>
						{ value.transform && value.transform.skew && (
							<div className="mb-3" style={ { marginTop: -10 } }>
								<RangeControl
									label={ __( 'X', 'alpha-core' ) }
									value={ value.transform && value.transform.skewx }
									min="-360"
									max="360"
									allowReset="true"
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.skewx = val;
										onChange( value );
									} }
								/>
								<RangeControl
									label={ __( 'Y', 'alpha-core' ) }
									value={ value.transform && value.transform.skewy }
									min="-360"
									max="360"
									allowReset="true"
									onChange={ ( val ) => {
										if ( ! value.transform ) {
											value.transform = {};
										}
										value.transform.skewy = val;
										onChange( value );
									} }
								/>
							</div>
						) }

						<ToggleControl
							label={ __( 'Flip Horizontal', 'alpha-core' ) }
							checked={ value.transform && value.transform.flipx }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.flipx = val;
								onChange( value );
							} }
						/>
						<ToggleControl
							label={ __( 'Flip Vertical', 'alpha-core' ) }
							checked={ value.transform && value.transform.flipy }
							onChange={ ( val ) => {
								if ( ! value.transform ) {
									value.transform = {};
								}
								value.transform.flipy = val;
								onChange( value );
							} }
						/>
					</div>
				) }
				{ boxShadowEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Box Shadow', 'alpha-core' ) }
						</h3>
						<SelectControl
							label={ __( 'Type', 'alpha-core' ) }
							value={ value.boxshadow && value.boxshadow.type }
							options={ [ { label: __( 'Outset', 'alpha-core' ), value: '' }, { label: __( 'Inset', 'alpha-core' ), value: 'inset' }, { label: __( 'None', 'alpha-core' ), value: 'none' }, { label: __( 'Inherit', 'alpha-core' ), value: 'inherit' } ] }
							onChange={ ( val ) => {
								if ( ! value.boxshadow ) {
									value.boxshadow = {};
								}
								value.boxshadow.type = val;
								onChange( value );
							} }
						/>
						<div className="mb-3" style={ { display: 'flex', flexWrap: 'wrap' } }>
							<UnitControl
								label={ __( 'X', 'alpha-core' ) }
								value={ value.boxshadow && value.boxshadow.x }
								onChange={ ( val ) => {
									if ( ! value.boxshadow ) {
										value.boxshadow = {};
									}
									value.boxshadow.x = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Y', 'alpha-core' ) }
								value={ value.boxshadow && value.boxshadow.y }
								onChange={ ( val ) => {
									if ( ! value.boxshadow ) {
										value.boxshadow = {};
									}
									value.boxshadow.y = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Blur', 'alpha-core' ) }
								value={ value.boxshadow && value.boxshadow.blur }
								onChange={ ( val ) => {
									if ( ! value.boxshadow ) {
										value.boxshadow = {};
									}
									value.boxshadow.blur = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Spread', 'alpha-core' ) }
								value={ value.boxshadow && value.boxshadow.spread }
								onChange={ ( val ) => {
									if ( ! value.boxshadow ) {
										value.boxshadow = {};
									}
									value.boxshadow.spread = val;
									onChange( value );
								} }
							/>
						</div>
						<ColorPicker
							label={ __( 'Color', 'alpha-core' ) }
							color={ value.boxshadow && value.boxshadow.color }
							onChangeComplete={ ( val ) => {
								if ( ! value.boxshadow ) {
									value.boxshadow = {};
								}
								value.boxshadow.color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')';
								onChange( value );
							} }
						/>
					</div>
				) }
				{ visibilityEnabled && (
					<div className="alpha-typography-control alpha-dimension-control">
						<h3 className="components-base-control" style={ { marginBottom: 15 } }>
							{ __( 'Visibility', 'alpha-core' ) }
						</h3>
						<p className="help">{ __( 'Visibility will take effect only on live page.', 'alpha-core' ) }</p>
						<ToggleControl
							label={ __( 'Hide On Large Desktop', 'alpha-core' ) }
							checked={ value.hideXl }
							onChange={ ( val ) => {
								value.hideXl = val;
								onChange( value );
							} }
						/>
						<ToggleControl
							label={ __( 'Hide On Desktop', 'alpha-core' ) }
							checked={ value.hideLg }
							onChange={ ( val ) => {
								value.hideLg = val;
								onChange( value );
							} }
						/>
						<ToggleControl
							label={ __( 'Hide On Tablet', 'alpha-core' ) }
							checked={ value.hideMd }
							onChange={ ( val ) => {
								value.hideMd = val;
								onChange( value );
							} }
						/>
						<ToggleControl
							label={ __( 'Hide On Mobile', 'alpha-core' ) }
							checked={ value.hideSm }
							onChange={ ( val ) => {
								value.hideSm = val;
								onChange( value );
							} }
						/>
					</div>
				) }
			</PanelBody>
			{ options && options.hoverOptions && (
				<PanelBody title={ __( 'Hover Style Options', 'alpha-core' ) } initialOpen={ false }>
					<div className="alpha-typography-control alpha-dimension-control">
						<p style={ { marginBottom: 4, marginTop: 15 } }>
							{ __( 'Background Color', 'alpha-core' ) }
						</p>
						<ColorPicker
							label={ __( 'Color', 'alpha-core' ) }
							value={ value.hover && value.hover.bg }
							onChangeComplete={ ( val ) => {
								if ( !value.hover ) {
									value.hover = {};
								}
								value.hover.bg = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')';
								onChange( value );
							} }
						/>
						<button className="components-button components-range-control__reset is-secondary is-small" onClick={ ( e ) => {
							if ( !value.hover ) {
								value.hover = {};
							}
							value.hover.bg = '';
							onChange( value );
						} } style={ { margin: '-10px 0 20px 3px' } }>
							{ __( 'Reset', 'alpha-core' ) }
						</button>

						<p style={ { marginBottom: 4, width: '100%' } }>
							{ __( 'Text Color', 'alpha-core' ) }
							<span className="alpha-color-show" style={ { backgroundColor: value.hover && value.hover.color } }>
							</span>
						</p>
						<ColorPalette
							label={ __( 'Text Color', 'alpha-core' ) }
							value={ value.hover && value.hover.color }
							colors={ [] }
							onChange={ ( val ) => {
								if ( !value.hover ) {
									value.hover = {};
								}
								value.hover.color = val;
								onChange( value );
							} }
						/>
						<SelectControl
							label={ __( 'Border Style', 'alpha-core' ) }
							value={ value.hover && value.hover.border_style }
							options={ [ { label: __( 'None', 'alpha-core' ), value: '' }, { label: __( 'Solid', 'alpha-core' ), value: 'solid' }, { label: __( 'Double', 'alpha-core' ), value: 'double' }, { label: __( 'Dotted', 'alpha-core' ), value: 'dotted' }, { label: __( 'Dashed', 'alpha-core' ), value: 'dashed' }, { label: __( 'Groove', 'alpha-core' ), value: 'groove' } ] }
							onChange={ ( val ) => {
								if ( !value.hover ) {
									value.hover = {};
								}
								value.hover.border_style = val;
								onChange( value );
							} }
						/>
						<div style={ { display: 'flex', flexWrap: 'wrap' } }>
							<label style={ { width: '100%', marginBottom: 5 } }>
								{ __( 'Border Width', 'alpha-core' ) }
							</label>
							<UnitControl
								label={ __( 'Top', 'alpha-core' ) }
								value={ value.hover && value.hover.border_top }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.border_top = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Right', 'alpha-core' ) }
								value={ value.hover && value.hover.border_right }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.border_right = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Bottom', 'alpha-core' ) }
								value={ value.hover && value.hover.border_bottom }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.border_bottom = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Left', 'alpha-core' ) }
								value={ value.hover && value.hover.border_left }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.border_left = val;
									onChange( value );
								} }
							/>
						</div>
						<label style={ { width: '100%', marginTop: 10, marginBottom: 5 } }>
							{ __( 'Border Color', 'alpha-core' ) }
							<span className="alpha-color-show" style={ { backgroundColor: value.hover && value.hover.border_color } }>
							</span>
						</label>
						<ColorPicker
							label={ __( 'Color', 'alpha-core' ) }
							color={ value.hover && value.hover.border_color }
							onChangeComplete={ ( val ) => {
								if ( ! value.hover ) {
									value.hover = {};
								}
								value.hover.border_color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')';
								onChange( value );
							} }
						/>
						<button className="components-button components-range-control__reset is-secondary is-small" onClick={ ( e ) => {
							if ( ! value.hover ) {
								value.hover = {};
							}
							value.hover.border_color = '';
							onChange( value );
						} } style={ { margin: '-10px 0 20px 3px' } }>
							{ __( 'Reset', 'alpha-core' ) }
						</button>

						<div className="spacer" style={ { display: 'flex', flexWrap: 'wrap' } }>
							<label style={ { width: '100%', marginBottom: 5 } }>
								{ __( 'Position', 'alpha-core' ) }
							</label>
							<UnitControl
								label={ __( 'Top', 'alpha-core' ) }
								value={ value.hover && value.hover.top }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.top = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Right', 'alpha-core' ) }
								value={ value.hover && value.hover.right }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.right = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Bottom', 'alpha-core' ) }
								value={ value.hover && value.hover.bottom }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.bottom = val;
									onChange( value );
								} }
							/>
							<UnitControl
								label={ __( 'Left', 'alpha-core' ) }
								value={ value.hover && value.hover.left }
								onChange={ ( val ) => {
									if ( !value.hover ) {
										value.hover = {};
									}
									value.hover.left = val;
									onChange( value );
								} }
							/>
						</div>

						<RangeControl
							label={ __( 'Opacity', 'alpha-core' ) }
							value={ value.hover && typeof value.hover.opacity != 'undefined' && value.hover.opacity }
							min="0"
							max="1"
							step="0.01"
							allowReset={ true }
							onChange={ ( val ) => {
								if ( !value.hover ) {
									value.hover = {};
								}
								value.hover.opacity = val;
								onChange( value );
							} }
						/>
					</div>

					{ transformEnabled && (
						<div className="alpha-typography-control alpha-dimension-control">
							<h3 className="components-base-control" style={ { marginBottom: 15 } }>
								{ __( 'Transform', 'alpha-core' ) }
							</h3>

							<ToggleControl
								label={ __( 'Translate', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.translate }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.translate = val;
									onChange( value );
								} }
							/>
							{ value.hover && value.hover.transform && value.hover.transform.translate && (
								<div className="mb-3" style={ { display: 'flex', flexWrap: 'wrap', marginTop: -10 } }>
									<UnitControl
										label={ __( 'X', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.translatex }
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.translatex = val;
											onChange( value );
										} }
									/>
									<UnitControl
										label={ __( 'Y', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.translatey }
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.translatey = val;
											onChange( value );
										} }
									/>
								</div>
							) }

							<ToggleControl
								label={ __( 'Rotate', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.rotate }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.rotate = val;
									onChange( value );
								} }
							/>
							{ value.hover && value.hover.transform && value.hover.transform.rotate && (
								<div className="mb-3" style={ { marginTop: -10 } }>
									<RangeControl
										label={ __( 'Degree', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.rotatedeg }
										min="-360"
										max="360"
										allowReset="true"
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.rotatedeg = val;
											onChange( value );
										} }
									/>
								</div>
							) }

							<ToggleControl
								label={ __( 'Scale', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.scale }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.scale = val;
									onChange( value );
								} }
							/>
							{ value.hover && value.hover.transform && value.hover.transform.scale && (
								<div className="mb-3" style={ { marginTop: -10 } }>
									<RangeControl
										label={ __( 'X', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.scalex }
										min="0"
										max="2"
										step="0.1"
										allowReset="true"
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.scalex = val;
											onChange( value );
										} }
									/>
									<RangeControl
										label={ __( 'Y', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.scaley }
										min="0"
										max="2"
										step="0.1"
										allowReset="true"
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.scaley = val;
											onChange( value );
										} }
									/>
								</div>
							) }

							<ToggleControl
								label={ __( 'Skew', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.skew }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.skew = val;
									onChange( value );
								} }
							/>
							{ value.hover && value.hover.transform && value.hover.transform.skew && (
								<div className="mb-3" style={ { marginTop: -10 } }>
									<RangeControl
										label={ __( 'X', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.skewx }
										min="-360"
										max="360"
										allowReset="true"
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.skewx = val;
											onChange( value );
										} }
									/>
									<RangeControl
										label={ __( 'Y', 'alpha-core' ) }
										value={ value.hover && value.hover.transform && value.hover.transform.skewy }
										min="-360"
										max="360"
										allowReset="true"
										onChange={ ( val ) => {
											if ( ! value.hover ) {
												value.hover = {};
											}
											if ( ! value.hover.transform ) {
												value.hover.transform = {};
											}
											value.hover.transform.skewy = val;
											onChange( value );
										} }
									/>
								</div>
							) }

							<ToggleControl
								label={ __( 'Flip Horizontal', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.flipx }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.flipx = val;
									onChange( value );
								} }
							/>
							<ToggleControl
								label={ __( 'Flip Vertical', 'alpha-core' ) }
								checked={ value.hover && value.hover.transform && value.hover.transform.flipy }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.transform ) {
										value.hover.transform = {};
									}
									value.hover.transform.flipy = val;
									onChange( value );
								} }
							/>
							<RangeControl
								label={ __( 'Transition Duration (ms)', 'alpha-core' ) }
								value={ value.transform && value.transform.duration }
								min="0"
								max="2000"
								step="10"
								allowReset="true"
								onChange={ ( val ) => {
									if ( ! value.transform ) {
										value.transform = {};
									}
									value.transform.duration = val;
									onChange( value );
								} }
							/>
						</div>
					) }
					{ boxShadowEnabled && (
						<div className="alpha-typography-control alpha-dimension-control">
							<h3 className="components-base-control" style={ { marginBottom: 15 } }>
								{ __( 'Box Shadow', 'alpha-core' ) }
							</h3>
							<SelectControl
								label={ __( 'Type', 'alpha-core' ) }
								value={ value.hover && value.hover.boxshadow && value.hover.boxshadow.type }
								options={ [ { label: __( 'Outset', 'alpha-core' ), value: '' }, { label: __( 'Inset', 'alpha-core' ), value: 'inset' }, { label: __( 'None', 'alpha-core' ), value: 'none' }, { label: __( 'Inherit', 'alpha-core' ), value: 'inherit' } ] }
								onChange={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.boxshadow ) {
										value.hover.boxshadow = {};
									}
									value.hover.boxshadow.type = val;
									onChange( value );
								} }
							/>
							<div className="mb-3" style={ { display: 'flex', flexWrap: 'wrap' } }>
								<UnitControl
									label={ __( 'X', 'alpha-core' ) }
									value={ value.hover && value.hover.boxshadow && value.hover.boxshadow.x }
									onChange={ ( val ) => {
										if ( ! value.hover ) {
											value.hover = {};
										}
										if ( ! value.hover.boxshadow ) {
											value.hover.boxshadow = {};
										}
										value.hover.boxshadow.x = val;
										onChange( value );
									} }
								/>
								<UnitControl
									label={ __( 'Y', 'alpha-core' ) }
									value={ value.hover && value.hover.boxshadow && value.hover.boxshadow.y }
									onChange={ ( val ) => {
										if ( ! value.hover ) {
											value.hover = {};
										}
										if ( ! value.hover.boxshadow ) {
											value.hover.boxshadow = {};
										}
										value.hover.boxshadow.y = val;
										onChange( value );
									} }
								/>
								<UnitControl
									label={ __( 'Blur', 'alpha-core' ) }
									value={ value.hover && value.hover.boxshadow && value.hover.boxshadow.blur }
									onChange={ ( val ) => {
										if ( ! value.hover ) {
											value.hover = {};
										}
										if ( ! value.hover.boxshadow ) {
											value.hover.boxshadow = {};
										}
										value.hover.boxshadow.blur = val;
										onChange( value );
									} }
								/>
								<UnitControl
									label={ __( 'Spread', 'alpha-core' ) }
									value={ value.hover && value.hover.boxshadow && value.hover.boxshadow.spread }
									onChange={ ( val ) => {
										if ( ! value.hover ) {
											value.hover = {};
										}
										if ( ! value.hover.boxshadow ) {
											value.hover.boxshadow = {};
										}
										value.hover.boxshadow.spread = val;
										onChange( value );
									} }
								/>
							</div>
							<ColorPicker
								label={ __( 'Color', 'alpha-core' ) }
								color={ value.hover && value.hover.boxshadow && value.hover.boxshadow.color }
								onChangeComplete={ ( val ) => {
									if ( ! value.hover ) {
										value.hover = {};
									}
									if ( ! value.hover.boxshadow ) {
										value.hover.boxshadow = {};
									}
									value.hover.boxshadow.color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')';
									onChange( value );
								} }
							/>
						</div>
					) }
				</PanelBody>
			) }
		</>
	);
};

export default AlphaStyleOptionsControl;

export const alphaGenerateStyleOptionsCSS = function ( style_options, selector ) {
	var css = '';
	if ( !style_options ) {
		return '';
	}
	const options = {
		bg: {
			color: 'background-color',
			img_url: 'background-image',
			position: 'background-position',
			attachment: 'background-attachment',
			repeat: 'background-repeat',
			size: 'background-size',
		},
		border: {
			color: 'border-color',
			style: 'border-style',
			top: 'border-top-width',
			right: 'border-right-width',
			bottom: 'border-bottom-width',
			left: 'border-left-width',
		},
		borderRadius: {
			top: 'border-top-left-radius',
			right: 'border-top-right-radius',
			bottom: 'border-bottom-right-radius',
			left: 'border-bottom-left-radius',
		},
		margin: {
			top: 'margin-top',
			right: 'margin-right',
			bottom: 'margin-bottom',
			left: 'margin-left',
		},
		padding: {
			top: 'padding-top',
			right: 'padding-right',
			bottom: 'padding-bottom',
			left: 'padding-left',
		},
		position: {
			style: 'position',
			zindex: 'z-index',
			top: 'top',
			right: 'right',
			bottom: 'bottom',
			left: 'left',
			width: 'width',
			width_val: 'width',
			opacity: 'opacity',
		}
	},
		hover_options = {
			bg: 'background-color',
			color: 'color',
			border_style: 'border-style',
			border_top: 'border-top-width',
			border_right: 'border-right-width',
			border_bottom: 'border-bottom-width',
			border_left: 'border-left-width',
			border_color: 'border-color',
			top: 'top',
			right: 'right',
			bottom: 'bottom',
			left: 'left',
			opacity: 'opacity'
		};

	css += 'html .' + selector + '{';
	_.each( options, function ( item, property ) {
		if ( typeof style_options[ property ] != 'undefined' && style_options[ property ] ) {
			_.each( item, function ( css_property, attr_name ) {
				if ( typeof style_options[ property ][ attr_name ] != 'undefined' && ( '' + style_options[ property ][ attr_name ] ).length ) {
					var val = style_options[ property ][ attr_name ];
					if ( 'background-image' == css_property ) {
						val = 'url(' + val + ')';
					}
					css += css_property + ':' + val + ';';
				}
			} );
		}
	} );
	if ( style_options.position ) {
		if ( style_options.position.halign ) {
			if ( 'x' === style_options.position.halign ) {
				css += 'margin-left:auto;margin-right:auto;';
			} else if ( 's' === style_options.position.halign ) {
				css += 'margin-left:auto;';
			} else if ( 'e' === style_options.position.halign ) {
				css += 'margin-right:auto;';
			}
		}
		if ( style_options.position.translatex || style_options.position.translatey ) {
			css += 'transform:';
			if ( style_options.position.translatex ) {
				css += ' translateX(' + style_options.position.translatex + ')';
			}
			if ( style_options.position.translatey ) {
				css += ' translateY(' + style_options.position.translatey + ')';
			}
			css += ';';
		}
	}

	if ( style_options.transform ) {
		let transform_css = '';
		if ( style_options.transform.translate ) {
			if ( style_options.transform.translatex && style_options.transform.translatey ) {
				transform_css += ' translate(' + style_options.transform.translatex + ', ' + style_options.transform.translatey + ')';
			} else if ( style_options.transform.translatex ) {
				transform_css += ' translateX(' + style_options.transform.translatex + ')';
			} else if ( style_options.transform.translatey ) {
				transform_css += ' translateY(' + style_options.transform.translatey + ')';
			}
		}
		if ( style_options.transform.rotate && style_options.transform.rotatedeg ) {
			transform_css += ' rotate(' + style_options.transform.rotatedeg + 'deg)';
		}
		if ( style_options.transform.scale || style_options.transform.flipx || style_options.transform.flipy ) {
			let scaleX = style_options.transform.scalex,
				scaleY = style_options.transform.scaley;
			if ( style_options.transform.flipx ) {
				if ( scaleX ) {
					scaleX *= -1;
				} else {
					scaleX = -1;
				}
			}
			if ( style_options.transform.flipy ) {
				if ( scaleY ) {
					scaleY *= -1;
				} else {
					scaleY = -1;
				}
			}
			if ( scaleX && scaleY ) {
				transform_css += ' scale(' + scaleX + ', ' + scaleY + ')';
			} else if ( scaleX ) {
				transform_css += ' scaleX(' + scaleX + ')';
			} else if ( scaleY ) {
				transform_css += ' scaleY(' + scaleY + ')';
			}
		}
		if ( style_options.transform.skew ) {
			if ( style_options.transform.skewx && style_options.transform.skewy ) {
				transform_css += ' skew(' + style_options.transform.skewx + 'deg, ' + style_options.transform.skewy + 'deg)';
			} else if ( style_options.transform.skewx ) {
				transform_css += ' skewX(' + style_options.transform.skewx + 'deg)';
			} else if ( style_options.transform.skewy ) {
				transform_css += ' skewY(' + style_options.transform.skewy + 'deg)';
			}
		}
		if ( transform_css ) {
			css += 'transform:' + transform_css + ';';
		}
		if ( style_options && style_options.transform && style_options.transform.duration ) {
			css += 'transition:' + style_options.transform.duration + 'ms;';
		}
	}
	if ( style_options.boxshadow && ( style_options.boxshadow.type || ( style_options.boxshadow.color ) ) ) {
		css += 'box-shadow:';
		if ( style_options.boxshadow.type && 'inset' != style_options.boxshadow.type ) {
			css += style_options.boxshadow.type;
		} else {
			if ( style_options.boxshadow.type ) {
				css += style_options.boxshadow.type;
			}
			if ( style_options.boxshadow.x ) {
				css += ' ' + style_options.boxshadow.x;
			} else {
				css += ' 0';
			}
			if ( style_options.boxshadow.y ) {
				css += ' ' + style_options.boxshadow.y;
			} else {
				css += ' 0';
			}
			if ( style_options.boxshadow.blur ) {
				css += ' ' + style_options.boxshadow.blur;
			}
			if ( style_options.boxshadow.spread ) {
				css += ' ' + style_options.boxshadow.spread;
			}
			if ( style_options.boxshadow.color ) {
				css += ' ' + style_options.boxshadow.color;
			}
		}
		css += ';';
	}

	css += '}';

	if ( style_options.hover ) {
		css += 'html .' + selector + ':hover{';
		_.each( hover_options, function ( css_property, attr_name ) {
			if ( typeof style_options.hover[ attr_name ] != 'undefined' && ( '' + style_options.hover[ attr_name ] ).length ) {
				css += css_property + ':' + style_options.hover[ attr_name ] + ';';
			}
		} );
		if ( style_options.hover.translatex || style_options.hover.translatey ) {
			css += 'transform:';
			if ( style_options.hover.translatex ) {
				css += ' translateX(' + style_options.hover.translatex + ')';
			}
			if ( style_options.hover.translatey ) {
				css += ' translateY(' + style_options.hover.translatey + ')';
			}
			css += ';';
		}

		if ( style_options.hover.transform ) {
			let transform_css = '';
			if ( style_options.hover.transform.translate ) {
				if ( style_options.hover.transform.translatex && style_options.hover.transform.translatey ) {
					transform_css += ' translate(' + style_options.hover.transform.translatex + ', ' + style_options.hover.transform.translatey + ')';
				} else if ( style_options.hover.transform.translatex ) {
					transform_css += ' translateX(' + style_options.hover.transform.translatex + ')';
				} else if ( style_options.hover.transform.translatey ) {
					transform_css += ' translateY(' + style_options.hover.transform.translatey + ')';
				}
			}
			if ( style_options.hover.transform.rotate && style_options.hover.transform.rotatedeg ) {
				transform_css += ' rotate(' + style_options.hover.transform.rotatedeg + 'deg)';
			}
			if ( style_options.hover.transform.scale || style_options.hover.transform.flipx || style_options.hover.transform.flipy ) {
				let scaleX = style_options.hover.transform.scalex,
					scaleY = style_options.hover.transform.scaley;
				if ( style_options.hover.transform.flipx ) {
					if ( scaleX ) {
						scaleX *= -1;
					} else {
						scaleX = -1;
					}
				}
				if ( style_options.hover.transform.flipy ) {
					if ( scaleY ) {
						scaleY *= -1;
					} else {
						scaleY = -1;
					}
				}
				if ( scaleX && scaleY ) {
					transform_css += ' scale(' + scaleX + ', ' + scaleY + ')';
				} else if ( scaleX ) {
					transform_css += ' scaleX(' + scaleX + ')';
				} else if ( scaleY ) {
					transform_css += ' scaleY(' + scaleY + ')';
				}
			}
			if ( style_options.hover.transform.skew ) {
				if ( style_options.hover.transform.skewx && style_options.hover.transform.skewy ) {
					transform_css += ' skew(' + style_options.hover.transform.skewx + 'deg, ' + style_options.hover.transform.skewy + 'deg)';
				} else if ( style_options.hover.transform.skewx ) {
					transform_css += ' skewX(' + style_options.hover.transform.skewx + 'deg)';
				} else if ( style_options.hover.transform.skewy ) {
					transform_css += ' skewY(' + style_options.hover.transform.skewy + 'deg)';
				}
			}
			if ( transform_css ) {
				css += 'transform:' + transform_css + ';';
			}
		}

		if ( style_options.hover.boxshadow && ( style_options.hover.boxshadow.type || ( style_options.hover.boxshadow.color ) ) ) {
			css += 'box-shadow:';
			if ( style_options.hover.boxshadow.type && 'inset' != style_options.hover.boxshadow.type ) {
				css += style_options.hover.boxshadow.type;
			} else {
				if ( style_options.hover.boxshadow.type ) {
					css += style_options.hover.boxshadow.type;
				}
				if ( style_options.hover.boxshadow.x ) {
					css += ' ' + style_options.hover.boxshadow.x;
				} else {
					css += ' 0';
				}
				if ( style_options.hover.boxshadow.y ) {
					css += ' ' + style_options.hover.boxshadow.y;
				} else {
					css += ' 0';
				}
				if ( style_options.hover.boxshadow.blur ) {
					css += ' ' + style_options.hover.boxshadow.blur;
				}
				if ( style_options.hover.boxshadow.spread ) {
					css += ' ' + style_options.hover.boxshadow.spread;
				}
				if ( style_options.hover.boxshadow.color ) {
					css += ' ' + style_options.hover.boxshadow.color;
				}
			}
			css += ';';
		}
		css += '}';
	}

	return css;
};

export const alphaGenerateStyleOptionsClass = function ( style_options ) {
	var visibilityClass = '';
	if ( !style_options ) {
		return '';
	}
	if ( style_options.hideXl ) {
		visibilityClass += ' hide-on-xl';
	}
	if ( style_options.hideLg ) {
		visibilityClass += ' hide-on-lg';
	}
	if ( style_options.hideMd ) {
		visibilityClass += ' hide-on-md';
	}
	if ( style_options.hideSm ) {
		visibilityClass += ' hide-on-sm';
	}
	return visibilityClass;
}