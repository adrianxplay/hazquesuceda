/**
 * Alpha Gutenberg Blocks
 *
 * @since 4.1
 */

import AlphaTypographyControl, { alphaGenerateTypographyCSS } from '../../../../framework/plugins/gutenberg/assets/controls/typography';
import AlphaStyleOptionsControl, { alphaGenerateStyleOptionsCSS } from '../../../../framework/plugins/gutenberg/assets/controls/style-options';
import { alphaGenerateStyleOptionsClass } from '../../../../framework/plugins/gutenberg/assets/controls/style-options';

window.alphaTypographyControl = AlphaTypographyControl;
window.alphaStyleOptionsControl = AlphaStyleOptionsControl;

import '../../../../framework/plugins/gutenberg/assets/widgets/heading';
import '../../../../framework/plugins/gutenberg/assets/widgets/button';
import '../../../../framework/plugins/gutenberg/assets/widgets/image';
import '../../../../framework/plugins/gutenberg/assets/widgets/container';
import '../../../../framework/plugins/gutenberg/assets/widgets/icon';