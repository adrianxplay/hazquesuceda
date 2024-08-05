/**
 * Alpha Elementor Admin
 * 
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
jQuery(document).ready(function ($) {
    'use strict';

    var themeElementorAdmin = {
        init: function () {
            this.initCustomCSS();
            this.initCustomJS();
            this.addStudioButtons();
            this.initArchiveSinglePreview();
            this.initTooltip();
            this.repeaterPopup();

            elementor.hooks.addFilter('panel/elements/regionViews', function (panel) {
                var categories = panel.categories.options.collection;
                var categoryIndex = categories.findIndex({
                    name: "basic"
                });

                var libraryIndex = categories.findIndex({
                    name: "alpha-notice"
                });

                if (-1 === libraryIndex && categoryIndex) {
                    categories.add({
                        name: "alpha-notice",
                        title: alpha_studio.texts.theme_display_name + wp.i18n.__(' Library', 'alpha-core'),
                        defaultActive: 1,
                        items: [],
                        promotion: null
                    }, {
                        at: categoryIndex - 1
                    });
                }
                return panel;
            });

            if (typeof Marionette != 'undefined' && Marionette.ItemView && Marionette.Behavior) {
                class alphaStudioItem extends Marionette.ItemView {
                    className() {
                        return 'elementor-panel-category-items-alpha-notice';
                    }
                    getTemplate() {
                        return '#tmpl-alpha-elementor-studio-notice';
                    }
                }

                class alphaStudioHandle extends Marionette.Behavior {
                    initialize() {
                        if ('alpha-notice' == this.view.options.model.get('name')) {
                            this.view.emptyView = alphaStudioItem;
                        }
                    }
                }
                elementor.hooks.addFilter('panel/category/behaviors', function (behaviors) {
                    return Object.assign({}, behaviors, {
                        studioNotice: {
                            behaviorClass: alphaStudioHandle
                        }
                    });
                });
            }

            elementor.on('panel:init', function () {
                elementor.panel.currentView.on('set:page', themeElementorAdmin.panelChange);
                elementor.channels.editor.on('section:activated', themeElementorAdmin.changeControls);

                if (typeof alpha_core_vars.template_type != 'undefined') {
                    $('body').addClass(alpha_core_vars.template_type + '-template');
                }
            });

            if (typeof $e != 'undefined') {
                $e.commands.on('run:before', function (component, command, args) {
                    if ('document/elements/delete' == command && args && args.containers && args.containers.length) {
                        args.containers.forEach(function (cnt) {
                            elementorFrontend.hooks.doAction('alpha_elementor_element_before_delete', cnt.model);
                        });
                    }
                });
                $e.commands.on('run:after', function (component, command, args) {
                    if ('document/elements/create' == command && args && args.model && args.model.id) {
                        elementorFrontend.hooks.doAction('alpha_elementor_element_after_add', args.model);
                    }
                    if ('document/elements/empty' == command && typeof args.force != 'undefined' && args.force) {
                        elementor.settings.page.model.set('page_css', '');
                        elementorFrontend.hooks.doAction('refresh_page_css', '');
                        $('textarea[data-setting="page_css"]').val('');
                    }
                });
                $(document.body).on('click', '#elementor-panel-saver-button-publish:not(.elementor-disabled)', function (e) {
                    if (alpha_core_vars.layout_save) {
                        $('#alpha-elementor-panel-alpha-studio').trigger('click');
                        $('.blocks-section-switch [href="#layout-section"]').trigger('click');
                    }
                });
            }

            // Force enable handle buttons
            var _elementor_getPreferences = elementor.getPreferences;
            elementor.getPreferences = function (param) {
                if (param == 'edit_buttons') {
                    return 'yes';
                }
                return _elementor_getPreferences(param);
            }

            elementor.hooks.addFilter('elements/edit-buttons', function (editButtons) {
                var title = editButtons.edit.title;
                editButtons.editStyle = {
                    /* Translators: %s: Element name. */
                    title: title + ' Style',
                    icon: 'adjust'
                }
                editButtons.editAnim = {
                    /* Translators: %s: Element name. */
                    title: title + ' Animation',
                    icon: 'flash'
                }
                editButtons.addon = {
                    /* Translators: %s: Element name. */
                    title: title + ' Addon',
                    icon: 'cogs'
                }
                return editButtons;
            })
        },
        /**
         * Elementor grid layout columns in control.
         * 
         * On Desktop
         * On Tablet Landscape
         * On Tablet Portrait
         * On Mobile Landscape
         * On Mobile Portrait
         * 
         * @since 1.2.0
         */
        initLayoutColumns: function () {
            // $( '.elementor-control-col_cnt .elementor-control-title' ).text( 'Columns ( >= ' + ( elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value + 1 ) + 'px)' );
            // $( '.elementor-control-col_cnt_tablet .elementor-control-title' ).text( 'Columns ( >= ' + ( elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value + 1 ) + 'px)' );
            // $( '.elementor-control-col_cnt_mobile .elementor-control-title' ).text( 'Columns ( >= 576px )' );

            var $bgControl = $('.elementor-control-background_background.elementor-control-type-choose.elementor-group-control.elementor-label-inline');
            if ($bgControl.length > 0 && $bgControl.find('.elementor-choices label').length == 4) {
                $bgControl.removeClass('elementor-label-inline').addClass('elementor-label-block');
            }
            var $imageSizeControl = $('.elementor-group-control-image-size.elementor-label-inline');
            if ($imageSizeControl.length > 0) {
                $imageSizeControl.removeClass('elementor-label-inline').addClass('elementor-label-block');
            }
        },
        /**
         * Elementor Repeater Popup
         * 
         * @since 1.2.0
         */
        repeaterPopup: function () {
            $(document.body).on('mouseup', '.elementor-repeater-row-item-title', function () {
                var $this = $(this);
                var $thisControls = $this.parent().siblings('.elementor-repeater-row-controls');
                $thisControls.removeClass('editable-effect');
                if (window.innerHeight > $this.offset().top + 546) {
                    $thisControls.css({ 'top': $this.offset().top + 'px', bottom: '' });
                } else {
                    $thisControls.css({ 'bottom': '46px', 'top': '' });
                }
                if ($this.closest('.elementor-repeater-fields-wrapper').find('.editable').length == 0) {
                    $thisControls.addClass('editable-effect');
                }
            }).on('click', function (e) {
                if ($(e.target).closest('.elementor-repeater-fields').length == 0 && $(e.target).closest('.pcr-app').length == 0 && !$(e.target).hasClass('select2-search__field')) {
                    $('.elementor-repeater-row-controls.editable').removeClass('editable editable-effect');
                }
            });
        },
        /**
         * Elementor Control Description
         * 
         * @since 1.2.0
         */
        initTooltip: function () {
            $(document).on('mouseover', '.elementor-control .elementor-control-input-wrapper', function () {
                var $this = $(this);
                var $description = $this.parent().siblings('.elementor-control-field-description');
                if ($description.length == 0) {
                    $description = $this.siblings('.elementor-control-field-description');
                }
                $description.addClass('show');
                if ($this.closest('.elementor-label-block').length || $this.closest('.elementor-control-type-image_choose').length) {
                    $description.addClass('block-pos');
                }
                var $control = $this.closest('.elementor-control').get(0);
                if ($this.closest('.elementor-repeater-row-controls').length && $control.offsetTop + $control.offsetHeight + $description.outerHeight() >= 490) {
                    $description.addClass('show-top');
                }
            }).on('mouseout', '.elementor-control .elementor-control-input-wrapper', function (e) {
                var $this = $(this);
                var $description = $this.parent().siblings('.elementor-control-field-description');
                if ($description.length == 0) {
                    $description = $this.siblings('.elementor-control-field-description');
                }
                if (!$(e.relatedTarget).hasClass('elementor-control-field-description show')) {
                    $description.removeClass('show block-pos show-top');
                }
            }).on('mouseout', '.elementor-control .elementor-control-field-description.show', function (e) {
                $(this).removeClass('show block-pos show-top');
            });
        },
        initCustomCSS: function () {
            // custom page css
            var custom_css = elementor.settings.page.model.get('page_css');

            setTimeout(function () {
                typeof custom_css != 'undefined' && elementorFrontend.hooks.doAction('refresh_page_css', custom_css);
            }, 2000);

            $(document.body).on('input', 'textarea[data-setting="page_css"]', function (e) {
                if ($(this).closest('.elementor-control').siblings('.elementor-control-_alpha_custom_css').length) {
                    elementor.settings.page.model.set('page_css', $(this).val());

                    $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
                    $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
                }

                elementorFrontend.hooks.doAction('refresh_page_css', $(this).val());
            })
        },
        initCustomJS: function () {
            // custom page css
            var custom_js = elementor.settings.page.model.get('page_js');

            $(document.body).on('input', 'textarea[data-setting="page_js"]', function (e) {
                if ($(this).closest('.elementor-control').siblings('.elementor-control-_alpha_custom_js').length) {
                    elementor.settings.page.model.set('page_js', $(this).val());

                    $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
                    $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
                }
            })
        },
        addStudioButtons: function () {
            // Add Studio Block Button
            var addSectionTmpl = document.getElementById('tmpl-elementor-add-section'),
                noSettingsBuilders = ['block', 'product_layout', 'shop_layout', 'cart', 'checkout'];
            if (addSectionTmpl) {
                addSectionTmpl.textContent = addSectionTmpl.textContent.replace(
                    '<div class="elementor-add-section-area-button elementor-add-template-button',
                    '<div class="elementor-add-section-area-button elementor-studio-section-button" ' +
                    'onclick="window.parent.runStudio(this);" ' +
                    'title="' + alpha_studio.texts.studio_tooltip + '"><i class="alpha-mini-logo"></i><i class="eicon-insert"></i></div>\r\n' +
                    (alpha_core_vars.template_type && noSettingsBuilders.indexOf(alpha_core_vars.template_type) === -1 ? ('<div class="elementor-add-section-area-button elementor-builder-settings-section-button" ' +
                        'onclick="window.parent.showAlphaBuilderSettings(this);" ' +
                        'title="' + alpha_core_vars.texts.builder_tooltip + '"><i class="eicon-cog"></i></div>\r\n') : '') +
                    '<div class="elementor-add-section-area-button elementor-add-template-button');
            }
        },
        panelChange: function (panel) {
            if ("_alpha_section_custom_css" == panel.activeSection || "_alpha_section_custom_js" == panel.activeSection) {
                var oldName = panel.activeSection.replaceAll('_section', ''),
                    newName = oldName.replaceAll('_alpha_custom', 'page');

                if ($('.elementor-control-' + newName).length) {
                    return;
                }

                var $newControl = $('.elementor-control-' + oldName).clone().removeClass('elementor-control-' + oldName).addClass('elementor-control-' + newName);

                $newControl.insertAfter($('.elementor-control-' + oldName));
                $newControl.find('textarea').attr('data-setting', newName).val(elementor.settings.page.model.get(newName));

                if (newName == 'page_css') {
                    $('.elementor-control-page_js').remove();
                } else {
                    $('.elementor-control-page_css').remove();
                }
            } else if ("alpha_custom_css_settings" == panel.activeSection) {
                $('.elementor-control-page_css').val(elementor.settings.page.model.get('page_css'));
            } else if ("alpha_custom_js_settings" == panel.activeSection) {
                $('.elementor-control-page_js').val(elementor.settings.page.model.get('page_js'));
            }
            themeElementorAdmin.initLayoutColumns();
        },
        changeControls: function (activeSection, editor) {
            if ("_alpha_section_custom_css" != activeSection && "_alpha_section_custom_js" != activeSection) {
                $('.elementor-control-page_css, .elementor-control-page_js').remove();
            } else {
                var oldName = activeSection.replaceAll('_section', ''),
                    newName = oldName.replaceAll('_alpha_custom', 'page'),
                    $newControl = $('.elementor-control-' + oldName).clone().removeClass('elementor-control-' + oldName).addClass('elementor-control-' + newName);

                $newControl.insertAfter($('.elementor-control-' + oldName));
                $newControl.find('textarea').attr('data-setting', newName).val(elementor.settings.page.model.get(newName));

                if (newName == 'page_css') {
                    $('.elementor-control-page_js').remove();
                } else {
                    $('.elementor-control-page_css').remove();
                }
            }
            themeElementorAdmin.initLayoutColumns();
            themeElementorAdmin.initFlipboxWidget(activeSection, editor);
        },
        initFlipboxWidget: function (sectionName, editor) {
            var editedElement = editor.getOption('editedElementView');

            if (alpha_core_vars.theme + '_widget_flipbox' !== editedElement.model.get('widgetType')) {
                return;
            }

            var isSideBSection = -1 !== ['section_back_side_content', 'section_flipbox_back_style'].indexOf(sectionName);
            editedElement.$el.toggleClass('flipped', isSideBSection);
            var $backLayer = editedElement.$el.find('.flipbox_back');

            if (isSideBSection) {
                $backLayer.css('transition', 'none');
            }

            if (!isSideBSection) {
                setTimeout(function () {
                    $backLayer.css('transition', '');
                }, 10);
            }
        },
        initArchiveSinglePreview: function () {
            $(document)
                .on('click', '.elementor-control-archive_preview_apply .elementor-button', function (e) {
                    $.post(alpha_core_vars.ajax_url, {
                        action: 'alpha_archive_builder_preview_apply',
                        nonce: alpha_core_vars.nonce,
                        post_id: ElementorConfig.document.id,
                        mode: $('.elementor-control-archive_preview_type select').val(),
                    }, function () {
                        elementor.reloadPreview();
                    });
                })
                .on('click', '.elementor-control-single_preview_apply .elementor-button', function (e) {
                    $.post(alpha_core_vars.ajax_url, {
                        action: 'alpha_single_builder_preview_apply',
                        nonce: alpha_core_vars.nonce,
                        post_id: ElementorConfig.document.id,
                        mode: $('.elementor-control-single_preview_type select').val(),
                    }, function () {
                        elementor.reloadPreview();
                    });
                })
        }
    }

    // Active Settings Panel
    window.showAlphaBuilderSettings = function (addButton) {
        var template_type = alpha_core_vars.template_type;
        $('#elementor-panel-footer-settings').trigger('click');
        if ($('.elementor-control-alpha_' + template_type + '_settings').length && !$('.elementor-control-alpha_' + template_type + '_settings').hasClass('e-open')) {
            $('.elementor-control-alpha_' + template_type + '_settings .elementor-panel-heading').trigger('click');
        }
    }

    // Setup Alpha Elementor Admin
    elementor.on('frontend:init', themeElementorAdmin.init.bind(themeElementorAdmin));
});