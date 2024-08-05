/**
 * Selective Refresh Extend for Customize
 * 
 * @package  Alpha Framework
 * @since 4.0
 */
'use strict';

var alpha_cs_tooltips = [{
    target: '.page-header',
    text: 'Page Title Bar',
    elementID: 'title_bar',
    pos: 'bottom',
    type: 'section'
}, {
    target: '.post-archive',
    text: 'Archive Page',
    elementID: 'blog_archive',
    pos: 'top',
    type: 'section'
}, {
    target: '.single .post-single',
    text: 'Single Page',
    elementID: 'blog_single',
    pos: 'top',
    type: 'section'
}, {
    target: '.main-content > .products, .main-content > .yit-wcan-container > .products',
    text: 'Product Archive Page',
    elementID: 'products_archive',
    pos: 'top',
    type: 'section'
}, {
    target: '.single .product-single',
    text: 'Product Page',
    elementID: 'product_detail',
    pos: 'top',
    type: 'section'
}, {
    target: '.products .product-wrap .product',
    text: 'Product Type',
    elementID: 'product_type',
    pos: 'center',
    type: 'section'
}, {
    target: '.cookies-popup',
    text: 'Privacy Setting',
    elementID: 'cookie_law_info',
    pos: 'top',
    type: 'section'
}];

jQuery(document).ready(function ($) {

    eventsInit();

    function getCustomize(option) {
        var o = wp.customize(option);
        return o ? o.get() : '';
    }

    var options = [
        'page_transition_bg', 'preloader_color',
        ['primary_color', 'secondary_color', 'dark_color', 'light_color', 'accent_color', 'success_color', 'info_color', 'alert_color', 'danger_color'],
        'rounded_skin',
        'dark_skin',
        'typo_h1_size', 'typo_h2_size', 'typo_h3_size', 'typo_h4_size', 'typo_h5_size', 'typo_h6_size',
        'ptb_top_space', 'ptb_bottom_space', 'ptb_bg_color'
    ];

    var firstLoad = 0;

    for (var i = 0; i < options.length; i++) {
        if (Array.isArray(options[i])) {
            var option = options[i];
        } else {
            var option = [options[i]];
        }

        for (var j = 0; j < option.length; j++) {
            wp.customize(option[j], function (e) {
                var event = option[0];
                e.bind(function (value) {
                    $(document.body).trigger(event);
                });
            });
        }

        $(document.body).trigger(option[0]);
    }

    function setBackgroundColor(id, value) {
        var input = window.top.document.querySelector('#customize-control-' + id + '-background-color input.kirki-color-input');
        var nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, "value").set;
        nativeInputValueSetter.call(input, value);

        var ev2 = new Event('input', { bubbles: true });
        input.dispatchEvent(ev2);
    }

    function setTypographyColor(id, value) {
        var input = window.top.document.querySelector('#customize-control-' + id + '-color input.kirki-color-input');
        var nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, "value").set;
        nativeInputValueSetter.call(input, value);

        var ev2 = new Event('input', { bubbles: true });
        input.dispatchEvent(ev2);
    }

    function eventsInit() {

        var style = $('html')[0].style,
            headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

        $(document.body).on('page_transition_bg', function () {
            if (document.querySelector('.loading-screen')) {
                $('.loading-screen').css('--alpha-page-transition-bg', getCustomize('page_transition_bg'));
            }
        })

        $(document.body).on('preloader_color', function () {
            if (document.querySelector('.loading-screen')) {
                $('.loading-screen').css('--alpha-preloader-color', getCustomize('preloader_color'));
            }
        })

        headings.forEach(function (heading) {
            $(document.body).on('typo_' + heading + '_size', function () {
                if (document.querySelector(heading))
                    alpha_selective_size(style, heading, getCustomize('typo_' + heading + '_size'));
            })
        });

        $(document.body).on('ptb_top_space', function () {
            if (document.querySelector('.page-header')) {
                setTimeout(function () {
                    $('.page-header').css('--alpha-ptb-top-space', getCustomize('ptb_top_space') + 'px');
                });
            }
        })

        $(document.body).on('ptb_bottom_space', function () {
            if (document.querySelector('.page-header')) {
                setTimeout(function () {
                    $('.page-header').css('--alpha-ptb-bottom-space', getCustomize('ptb_bottom_space') + 'px');
                });
            }
        })

        $(document.body).on('ptb_bg_color', function () {
            if (document.querySelector('.page-header')) {
                setTimeout(function () {
                    $('.page-header').css('--alpha-ptb-bg-color', getCustomize('ptb_bg_color'));
                });
            }
        })

        $(document.body).on('rounded_skin', function () {
            var roundedSkin = getCustomize('rounded_skin');
            if (roundedSkin) {
                $(document.body).addClass('alpha-rounded-skin');
            } else {
                $(document.body).removeClass('alpha-rounded-skin');
            }
        })

        $(document.body).on('dark_skin', function () {
            firstLoad++;

            if (firstLoad < 2) {
                return;
            }

            var darkSkin = getCustomize('dark_skin');

            $(document.body).toggleClass('alpha-dark', darkSkin);

            if (darkSkin) {
                // Save Previous Values
                window.top.wp.customize.typo_default_light_color = window.top.wp.customize.instance('typo_default').get()['color'];
                window.top.wp.customize.typo_heading_light_color = window.top.wp.customize.instance('typo_heading').get()['color'];
                window.top.wp.customize.site_bg_light_color = window.top.wp.customize.instance('site_bg').get()['background-color'];
                window.top.wp.customize.content_bg_light_color = window.top.wp.customize.instance('content_bg').get()['background-color'];

                // Update Controls
                setBackgroundColor('site_bg', undefined != window.top.wp.customize.site_bg_dark_color ? window.top.wp.customize.site_bg_dark_color : '#111');
                setBackgroundColor('content_bg', undefined != window.top.wp.customize.content_bg_dark_color ? window.top.wp.customize.content_bg_dark_color : '#111');
                setTypographyColor('typo_default', undefined != window.top.wp.customize.typo_default_dark_color ? window.top.wp.customize.typo_default_dark_color : '#777');
                setTypographyColor('typo_heading', undefined != window.top.wp.customize.typo_heading_dark_color ? window.top.wp.customize.typo_heading_dark_color : '#fff');

                // Update Preview Change
                style.setProperty('--alpha-change-color-dark-1', '#ccc');
                style.setProperty('--alpha-dark-body-color', '#aaa');
                style.setProperty('--alpha-body-color', '#666');
                style.setProperty('--alpha-grey-color', '#797979');
                style.setProperty('--alpha-grey-color-light', '#555');
                style.setProperty('--alpha-change-color-light-3', '#323334');
                style.setProperty('--alpha-change-border-color', '#2f2f2f');
                style.setProperty('--alpha-change-border-color-light', '#2c2c2c');
                style.setProperty('--alpha-change-color-light-1', '#212121');
                style.setProperty('--alpha-change-color-light-2', '#2a2a2a');
                style.setProperty('--alpha-traffic-white-color', '#272727');
                style.setProperty('--alpha-dark-color-hover', getLighten('#ccc'));
                style.setProperty('--alpha-heading-color', '#ccc');
                style.setProperty('--alpha-site-bg-color', '#171717');
                document.querySelector('.page-wrapper').style.setProperty('--alpha-page-wrapper-bg-color', '#171717');
            } else {
                // Save Previous Values
                window.top.wp.customize.typo_default_dark_color = window.top.wp.customize.instance('typo_default').get()['color'];
                window.top.wp.customize.typo_heading_dark_color = window.top.wp.customize.instance('typo_heading').get()['color'];
                window.top.wp.customize.site_bg_dark_color = window.top.wp.customize.instance('site_bg').get()['background-color'];
                window.top.wp.customize.content_bg_dark_color = window.top.wp.customize.instance('content_bg').get()['background-color'];

                // Update Controls
                setBackgroundColor('site_bg', undefined != window.top.wp.customize.site_bg_light_color ? window.top.wp.customize.site_bg_light_color : '');
                setBackgroundColor('content_bg', undefined != window.top.wp.customize.content_bg_light_color ? window.top.wp.customize.content_bg_light_color : '');
                setTypographyColor('typo_default', undefined != window.top.wp.customize.typo_default_light_color ? window.top.wp.customize.typo_default_light_color : window.top.wp.customize.control('typo_default').params.default.color);
                setTypographyColor('typo_heading', undefined != window.top.wp.customize.typo_heading_light_color ? window.top.wp.customize.typo_heading_light_color : window.top.wp.customize.control('typo_heading').params.default.color);

                // Update Preview Change
                style.setProperty('--alpha-change-color-dark-1', getCustomize('dark_color'));
                style.setProperty('--alpha-dark-body-color', '#666');
                alpha_selective_typography(style, 'body', getCustomize('typo_default'));
                style.setProperty('--alpha-grey-color', '#999');
                style.setProperty('--alpha-grey-color-light', '#aaa');
                style.setProperty('--alpha-change-color-light-3', getCustomize('light_color'));
                style.setProperty('--alpha-change-border-color', '#e1e1e1');
                style.setProperty('--alpha-change-border-color-light', '#eee');
                style.setProperty('--alpha-change-color-light-1', '#fff');
                style.setProperty('--alpha-change-color-light-2', '#f4f4f4');
                style.setProperty('--alpha-traffic-white-color', '#f9f9f9');
                style.setProperty('--alpha-dark-color-hover', getLighten(getCustomize('dark_color')));
                alpha_selective_typography(style, 'heading', getCustomize('typo_heading'));
                style.setProperty('--alpha-site-bg-color', '#fff');
                document.querySelector('.page-wrapper').style.setProperty('--alpha-page-wrapper-bg-color', '#fff');
            }
        })

        $(document.body).on('primary_color', function () {
            style.setProperty('--alpha-accent-color', getCustomize('accent_color'));
            style.setProperty('--alpha-accent-color-hover', getLighten(getCustomize('accent_color')));
            style.setProperty('--alpha-success-color', getCustomize('success_color'));
            style.setProperty('--alpha-success-color-hover', getLighten(getCustomize('success_color')));
            style.setProperty('--alpha-info-color', getCustomize('info_color'));
            style.setProperty('--alpha-info-color-hover', getLighten(getCustomize('info_color')));
            style.setProperty('--alpha-alert-color', getCustomize('alert_color'));
            style.setProperty('--alpha-alert-color-hover', getLighten(getCustomize('alert_color')));
            style.setProperty('--alpha-danger-color', getCustomize('danger_color'));
            style.setProperty('--alpha-danger-color-hover', getLighten(getCustomize('danger_color')));

            style.setProperty('--alpha-primary-color-light', getLighten(getCustomize('primary_color'), 40));
            style.setProperty('--alpha-secondary-color-light', getLighten(getCustomize('secondary_color'), 40));
            style.setProperty('--alpha-dark-color-light', getLighten(getCustomize('dark_color'), 40));
            style.setProperty('--alpha-light-color-light', getLighten(getCustomize('light_color'), 40));
            style.setProperty('--alpha-accent-color-light', getLighten(getCustomize('accent_color'), 40));
            style.setProperty('--alpha-success-color-light', getLighten(getCustomize('success_color'), 40));
            style.setProperty('--alpha-info-color-light', getLighten(getCustomize('info_color'), 40));
            style.setProperty('--alpha-alert-color-light', getLighten(getCustomize('warning_color'), 40));
            style.setProperty('--alpha-danger-color-light', getLighten(getCustomize('danger_color'), 40));

            style.setProperty('--alpha-primary-gradient-1', getDarken(getCustomize('primary_color'), 0.6));
            style.setProperty('--alpha-primary-gradient-2', getLighten(getCustomize('primary_color'), 10));


            style.setProperty('--alpha-white-color', '#fff');
            style.setProperty('--alpha-change-border-color', getCustomize('dark_skin') ? '#2f2f2f' : '#e1e1e1');
            style.setProperty('--alpha-change-border-color-light', getCustomize('dark_skin') ? '#2c2c2c' : '#eee');
            style.setProperty('--alpha-change-color-light-1', getCustomize('dark_skin') ? '#212121' : '#fff');
            style.setProperty('--alpha-change-color-light-2', getCustomize('dark_skin') ? '#2a2a2a' : '#f4f4f4');
            style.setProperty('--alpha-change-color-light-3', getCustomize('dark_skin') ? '#323334' : '#ccc');
            style.setProperty('--alpha-change-color-dark-1', getCustomize('dark_skin') ? '#ccc' : getCustomize('dark_color'));
            style.setProperty('--alpha-change-color-dark-1-hover', getLighten(getCustomize('dark_skin') ? '#ccc' : getCustomize('dark_color')));
        })
    }

    function alpha_selective_size(style, id, size) {
        var default_sizes = {
            h1: '5rem',
            h2: '3.8rem',
            h3: '2.8rem',
            h4: '2.2rem',
            h5: '1.8rem',
            h6: '1.6rem',
        }

        if (size) {
            var unit = size.replace(/[0-9.]*/, '');
            if (!unit) {
                size += 'px';
            }

            style.setProperty('--alpha-' + id + '-font-size', size);
        } else {
            style.setProperty('--alpha-' + id + '-font-size', default_sizes[id]);
        }
    }


    /**
     * Generate font styles.
     * 
     * @since 4.0
     */
    function alpha_selective_typography(style, id, typo) {
        if (typo['font-family'] && 'inherit' != typo['font-family']) {
            style.setProperty('--alpha-' + id + '-font-family', "'" + typo['font-family'] + "', sans-serif");

            if (!typo['variant']) {
                typo['variant'] = 400;
            }
        } else {
            style.removeProperty('--alpha-' + id + '-font-family');
        }
        if (typo['variant']) {
            style.setProperty('--alpha-' + id + '-font-weight', 'regular' == typo['variant'] ? 400 : typo['variant']);
        } else if ('heading' == id) {
            style.setProperty('--alpha-' + id + '-font-weight', 600);
        } else {
            style.removeProperty('--alpha-' + id + '-font-weight');
        }
        if (typo['font-size'] && '' != typo['font-size']) {
            style.setProperty('--alpha-' + id + '-font-size', (Number(typo['font-size']) ? (typo['font-size'] + 'px') : typo['font-size']));
        } else {
            style.removeProperty('--alpha-' + id + '-font-size');
        }
        if (typo['line-height'] && '' != typo['line-height']) {
            style.setProperty('--alpha-' + id + '-line-height', typo['line-height']);
        } else {
            style.removeProperty('--alpha-' + id + '-line-height');
        }
        if (typo['letter-spacing'] && '' != typo['letter-spacing']) {
            style.setProperty('--alpha-' + id + '-letter-spacing', typo['letter-spacing']);
        } else {
            style.removeProperty('--alpha-' + id + '-letter-spacing');
        }
        if (typo['text-transform'] && '' != typo['text-transform']) {
            style.setProperty('--alpha-' + id + '-text-transform', typo['text-transform']);
        } else {
            style.removeProperty('--alpha-' + id + '-text-transform');
        }
        if (typo['color'] && '' != typo['color']) {
            style.setProperty('--alpha-' + id + '-color', typo['color']);
        } else {
            style.removeProperty('--alpha-' + id + '-color');
        }
    }

    /**
     * Transform color format.
     * 
     * @since 4.0
     */
    function getHSL(color) {
        color = Number.parseInt(color.slice(1), 16);
        var $blue = color % 256;
        color /= 256;
        var $green = color % 256;
        var $red = color = color / 256;

        var $min = Math.min($red, $green, $blue);
        var $max = Math.max($red, $green, $blue);

        var $l = $min + $max;
        var $d = Number($max - $min);
        var $h = 0;
        var $s = 0;

        if ($d) {
            if ($l < 255) {
                $s = $d / $l;
            } else {
                $s = $d / (510 - $l);
            }

            if ($red == $max) {
                $h = 60 * ($green - $blue) / $d;
            } else if ($green == $max) {
                $h = 60 * ($blue - $red) / $d + 120;
            } else if ($blue == $max) {
                $h = 60 * ($red - $green) / $d + 240;
            }
        }

        return [($h + 360) % 360, ($s * 100), ($l / 5.1 + 7)];
    }

    /**
     * Change hue to rgb.
     * 
     * @since 4.0
     * @param {int} $m1 
     * @param {int} $m2 
     * @param {int} $h 
     */
    function hueToRGB($m1, $m2, $h) {
        if ($h < 0) {
            $h += 1;
        } else if ($h > 1) {
            $h -= 1;
        }

        if ($h * 6 < 1) {
            return $m1 + ($m2 - $m1) * $h * 6;
        }

        if ($h * 2 < 1) {
            return $m2;
        }

        if ($h * 3 < 2) {
            return $m1 + ($m2 - $m1) * (2 / 3 - $h) * 6;
        }

        return $m1;
    }

    /**
     * Get RGB
     * 
     * @since 4.0
     * @param {int} $hue 
     * @param {int} $saturation 
     * @param {int} $lightness 
     */
    function getRGB($hue, $saturation, $lightness) {
        if ($hue < 0) {
            $hue += 360;
        }

        var $h = $hue / 360;
        var $s = Math.min(100, Math.max(0, $saturation)) / 100;
        var $l = Math.min(100, Math.max(0, $lightness)) / 100;

        var $m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;
        var $m1 = $l * 2 - $m2;

        var $r = hueToRGB($m1, $m2, $h + 1 / 3) * 255;
        var $g = hueToRGB($m1, $m2, $h) * 255;
        var $b = hueToRGB($m1, $m2, $h - 1 / 3) * 255;

        var $out = [Math.ceil($r), Math.ceil($g), Math.ceil($b)];

        return $out;
    }

    /**
     * Adjust the hsl.
     * 
     * @since 4.0
     * @param {string} color 
     * @param {int} amount 
     */
    function adjustHsl(color, amount) {
        var $hsl = getHSL(color);
        $hsl[2] += amount;
        var $out = getRGB($hsl[0], $hsl[1], $hsl[2]);
        return 'rgb(' + $out[0] + ',' + $out[1] + ',' + $out[2] + ')';
    }

    /**
     * Returns the light color.
     * 
     * @since 4.0
     * @param {string} color 
     * @param {int} amount
     */
    function getLighten(color, amount = 5) {
        if (!color || 'transparent' == color) {
            return 'transparent';
        }
        if (color.length == 4) {
            color = '#' + color[1] + color[1] + color[2] + color[2] + color[3] + color[3];
        }
        return adjustHsl(color, amount);
    }

    /**
     * Returns the dark color.
     * 
     * @since 4.0
     * @param {string} color 
     * @param {int} amount
     */
    function getDarken(color, amount = 5) {
        if (!color || 'transparent' == color) {
            return 'transparent';
        }
        if (color.length == 4) {
            color = '#' + color[1] + color[1] + color[2] + color[2] + color[3] + color[3];
        }
        return adjustHsl(color, -amount);
    }
})