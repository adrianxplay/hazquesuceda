/**
 * Alpha Addon - GDPR
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
'use strict';

(function ($) {

    /**
     * Initialize cookie law popup
     * 
     * @since 1.0
     * 
     * @return {void}
     */
    theme.initCookiePopup = function () {
        var cookie_version = alpha_vars.cookie_version;

        if ('accepted' === theme.getCookie('alpha_cookies_' + cookie_version)) {
            return;
        }

        var $el = $('.cookies-popup');

        setTimeout(function () {
            var height = parseFloat(theme.$body.css('--alpha-bottom-sticky-h').slice(0, -2)),
                elHeight = parseFloat($el.outerHeight()) + 20;
            if (height < elHeight) {
                theme.$body.css('--alpha-bottom-sticky-h', elHeight + 'px');
            }
            $el.addClass('show');

            theme.$body.on('click', '.accept-cookie-btn', function (e) {
                e.preventDefault();
                height = parseFloat(theme.$body.css('--alpha-bottom-sticky-h').slice(0, -2));
                if (height == elHeight) {
                    theme.$body.css('--alpha-bottom-sticky-h', '0px');
                }
                $el.removeClass('show');
                theme.setCookie('alpha_cookies_' + cookie_version, 'accepted', 60);
            });

            theme.$body.on('click', '.decline-cookie-btn', function (e) {
                e.preventDefault();
                height = parseFloat(theme.$body.css('--alpha-bottom-sticky-h').slice(0, -2));
                if (height == elHeight) {
                    theme.$body.css('--alpha-bottom-sticky-h', '0px');
                }
                $el.removeClass('show');
            })
        }, 2500);
    }

    $(window).on('alpha_complete', theme.initCookiePopup)
})(jQuery);