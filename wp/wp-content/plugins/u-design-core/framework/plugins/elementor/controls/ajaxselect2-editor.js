jQuery(window).on('elementor:init', function ($) {
    var ControlAjaxselect2ItemView = elementor.modules.controls.BaseData.extend({
        onReady: function () {
            var self = this,
                el = self.ui.select,
                url = el.attr('data-ajax-url'),
                condition_name = el.attr('data-condition'),
                condition_val = el.attr('data-condition-val'),
                add_default = el.attr('data-add_default');

            el.select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            s: params.term,
                        }
                        if (condition_name) {
                            if (condition_val) {
                                query['condition'] = condition_val;
                            } else {
                                var $condition_obj = {}, $repeater = el.closest('.elementor-repeater-fields');

                                if ($repeater.length) {
                                    $condition_obj = $repeater.find('select[data-setting="' + condition_name + '"]');
                                }
                                if ($condition_obj.length == 0) {
                                    $condition_obj = jQuery('select[data-setting="' + condition_name + '"]');
                                }

                                if ($condition_obj.length) {
                                    query['condition'] = $condition_obj.val();
                                } else {
                                    for (key in elementor.selection.elements) {
                                        condition_value = elementor.selection.elements[key].settings.attributes[condition_name];
                                        if (condition_value) {
                                            query['condition'] = condition_value;
                                        }
                                        break;
                                    }
                                }
                            }
                        }
                        if (add_default) {
                            query['add_default'] = 1;
                        }
                        return query;
                    }
                },
                cache: true
            });
            if (el.closest('.elementor-hidden-control').length) {
                return;
            }
            var ids = (typeof self.getControlValue() !== 'undefined') ? self.getControlValue() : '';
            if (ids.isArray) {
                ids = self.getControlValue().join();
            }

            jQuery.ajax({
                url: url,
                dataType: 'json',
                data: {
                    ids: String(ids)
                }
            }).then(function (ret) {
                if (ret !== null && ret.results.length > 0) {
                    jQuery.each(ret.results, function (i, v) {
                        var op = new Option(v.text, v.id, true, true);
                        el.append(op).trigger('change');
                    });
                    el.trigger({
                        type: 'select2:select',
                        params: {
                            data: ret
                        }
                    });
                }
            });

        },
        onBeforeDestroy: function onBeforeDestroy() {
            if (this.ui.select.data('select2')) {
                this.ui.select.select2('destroy');
            }
            this.$el.remove();
        }
    });
    elementor.addControlView('ajaxselect2', ControlAjaxselect2ItemView);
});
