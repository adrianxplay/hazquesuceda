/**
 * Alpha Gutenberg Blocks
 *
 * @since 1.2.0
 */

import AlphaTypographyControl, { alphaGenerateTypographyCSS } from './controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS } from './controls/style-options';
import { alphaGenerateStyleOptionsClass } from './controls/style-options';

window.alphaTypographyControl = AlphaTypographyControl;
window.alphaStyleOptionsControl = AlphaStyleOptionsControl;

import './widgets/heading';
import './widgets/button';
import './widgets/image';
import './widgets/icon-box';
import './widgets/container';
import './widgets/icon';