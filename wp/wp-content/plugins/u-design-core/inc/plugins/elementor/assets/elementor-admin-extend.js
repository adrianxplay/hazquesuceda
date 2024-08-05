/**
 * Alpha Elementor Admin Extend
 * 
 * @package Alpha Core FrameWork
 * @since 4.0
 */

'use strict';

var themeElementorAdminExtend = window.themeElementorAdminExtend || {};

(function ($) {
    themeElementorAdminExtend.activeSection = null;
    themeElementorAdminExtend.editedElement = null;

    themeElementorAdminExtend.init = function () {
        var self = this;

        this.initHeaderPreview();
        this.initFixedFooterPreview();

        elementor.channels.editor.on('section:activated', self.initFlipboxSectionActivated);

        window.elementor.on('preview:loaded', function () {
            elementor.$preview[0].contentWindow.themeElementorAdminExtend = themeElementorAdminExtend;
        });
    }

    // Init flipbox section activated event
    themeElementorAdminExtend.initFlipboxSectionActivated = function (sectionName, editor) {

        var editedElement = editor.getOption('editedElementView'),
            prevEditedElement = editedElement;

        if (prevEditedElement
            && 'udesign_widget_flipbox' === prevEditedElement.model.get('widgetType')
            && 'udesign_widget_flipbox' !== editedElement.model.get('widgetType')
        ) {

            prevEditedElement.$el.find('.flipbox').removeClass('hover');

            self.editedElement = null;
        }

        if ('udesign_widget_flipbox' !== editedElement.model.get('widgetType')) {
            return;
        }

        themeElementorAdminExtend.editedElement = editedElement;
        themeElementorAdminExtend.activeSection = sectionName;

        var isBackSide = -1 !== ['section_back_side_content'].indexOf(sectionName);

        if (isBackSide) {
            editedElement.$el.find('.flipbox').addClass('flipped');
        } else {
            editedElement.$el.find('.flipbox').removeClass('flipped');
        }
    }

    //Init Side Header or not event
    themeElementorAdminExtend.initHeaderPreview = function () {
        $(document)
            .on('change', '.elementor-control-alpha_sticky_transparent input', function (e) {
                var iframejQuery = document.getElementById('elementor-preview-iframe').contentWindow.jQuery;
                if ($(this).is(':checked')) {
                    iframejQuery('body').addClass('sticky-header');
                } else {
                    iframejQuery('body').removeClass('sticky-header');
                }
            })
            .on('change', '.elementor-control-alpha_header_pos select', function (e) {
                var iframejQuery = document.getElementById('elementor-preview-iframe').contentWindow.jQuery;
                if ($(this).val()) {
                    iframejQuery('body').addClass('side-header');
                    if (!iframejQuery('.custom-header').parent().hasClass('header-area')) {
                        iframejQuery('.custom-header').wrap('<div class="header-area"></div>');
                    }
                } else {
                    iframejQuery('body').removeClass('side-header');
                    if (iframejQuery('.custom-header').parent().hasClass('header-area')) {
                        iframejQuery('.custom-header').unwrap('.header-area');
                    }
                }
            })
            .on('change', '.elementor-control-alpha_side_header_breakpoint select', function (e) {
                var iframejQuery = document.getElementById('elementor-preview-iframe').contentWindow.jQuery;
                iframejQuery('body').removeClass('side-on-desktop side-on-tablet side-on-mobile');
                if ($(this).val()) {
                    iframejQuery('body').addClass('side-on-' + $(this).val());
                }
            })
    }

    //Init Fixed Footer event
    themeElementorAdminExtend.initFixedFooterPreview = function () {
        $(document)
            .on('change', '.elementor-control-alpha_fixed_footer .elementor-switch-input', function (e) {
                var iframejQuery = document.getElementById('elementor-preview-iframe').contentWindow.jQuery;
                if ($(this).is(':checked')) {
                    iframejQuery('body').addClass('fixed-footer');
                } else {
                    iframejQuery('body').removeClass('fixed-footer');
                }
            })
    }

    $(window).on('elementor:init', function () {
        themeElementorAdminExtend.init();
    });
})(jQuery);
