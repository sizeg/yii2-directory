/**
 * Yii2 AJAX form
 */
(function(){
    $.fn.yiiDirectoryForm = function (options) {
        if (public[options]) {
            return public[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            var $form = $(this);
            if (!$form.data('yiiDirectoryForm')) {
                var settings = $.extend({}, defaults, options);
                if (settings.actionUrl === undefined) {
                    settings.actionUrl = $form.attr('action');
                }
                $form.data('yiiDirectoryForm', {
                    settings: settings
                });
            }

            // As $.yiiActiveForm already binded submit event, will control it
            $form.on('beforeSubmit.yiiActiveForm', beforeSubmitWrapper);

            return this;
        }
    };

    var defaults = {
        // the GET parameter name indicating an AJAX-based validation
        ajaxParam: 'ajax-submit',
        // the type of data that you're expecting back from the server
        ajaxDataType: 'json',
        // the URL for performing AJAX-based submit. If not set, it will use the the form's action
        actionUrl: undefined,
        // the jQuery selector of the container of the alert
        alertContainer: '.alert-container',
        // the container CSS class representing the corresponding message success submit
        alertSuccessCssClass: 'alert alert-success',
        // the container CSS class representing the corresponding message error submit
        alertErrorCssClass: 'alert alert-danger',
        // Remove form after sucess submit. This may has no effect if event.alert property callable
        removeOnSuccess: true,
        // Success message
        alertSuccessMessage: 'Form successfully saved.',
        // Error message
        alertErrorMessage: 'Internal server error.'
    };

    var events = {
        // ajaxBeforeSubmit event is triggered before form submit and $.ajax prepared
        beforeSubmitForm: 'beforeSubmitForm',
        // ajaxSubmitBeforeSend event is triggered when $.ajax is prepared to submit form
        ajaxBeforeSubmit: 'ajaxBeforeSubmit',
        // ajaxSubmitDone event is triggered after completing AJAX-based form submit request successfully
        ajaxSubmitDone: 'ajaxSubmitDone',
        // ajaxSubmitFail event is triggered after AJAX-based form submit request failes
        ajaxSubmitFail: 'ajaxSubmitFail',
        // ajaxSubmitAlways event is similat to $.yiiActiveForm.events.ajaxComplete
        ajaxSubmitAlways: 'ajaxSubmitAlways'
    };

    // Prevent $.yiiActiveForm.submitForm() and launch submit via AJAX
    var beforeSubmitWrapper = function (e) {
        var $form = $(this);

        // return false if form still have some validation errors
        if ($form.find('.has-error').length) {
            return false;
        }

        var event = $.Event(events.beforeSubmitForm);
        $form.trigger(event);
        if (event.result !== false) {
            public.submitForm.call($form);
        }

        return false;
    };

    var public = {
        submitForm: function () {
            var $form = $(this),
                data = $form.data('yiiDirectoryForm'),
                extData = '&' + data.settings.ajaxParam + '=' + $form.attr('id');
            $.ajax({
                url: data.settings.actionUrl,
                method: $form.attr('method'),
                data: $form.serialize() + extData,
                dataType: data.settings.ajaxDataType,
                beforeSend: function (jqXHR, settings) {
                    $form.trigger(events.ajaxBeforeSubmit, [jqXHR, settings]);
                }
            }).done(function (response, textStatus, jqXHR) {
                // Updates error messages and summary.
                if (response !== null && typeof response === 'object') {
                    $form.updateMessages(response, true);
                } else {
                    var event = $.Event(events.ajaxSubmitDone, {
                        alert: true
                    });
                    $form.trigger(event, [response, textStatus, jqXHR]);
                    // Display alert
                    if (typeof event.alert === 'boolean' && event.alert === true) {
                        public.alertSuccess.call($form, data.settings.alertSuccessMessage);
                    } else if (typeof event.alert === 'function') {
                        event.alert();
                    }
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                var event = $.Event(events.ajaxSubmitFail, {
                    alert: true
                });
                $form.trigger(event, [jqXHR, textStatus, errorThrown]);
                // Display alert
                if (typeof event.alert === 'boolean' && event.alert === true) {
                    public.alertError.call($form, data.settings.alertErrorMessage);
                } else if (typeof event.alert === 'function') {
                    event.alert();
                }
            }).always(function (response, textStatus, jqXHR) {
                $form.trigger(events.ajaxSubmitAlways, [response, textStatus, jqXHR]);
            });
        },
        alertSuccess: function (message) {
            var $form = $(this),
                data = $form.data('yiiDirectoryForm');

            if (typeof data.settings.alertContainer !== undefined) {
                var $container = $form.find(data.settings.alertContainer);
                if (data.settings.removeOnSuccess) {
                    $form.html($container);
                }
                $container.removeClass(data.settings.alertErrorCssClass).addClass(data.settings.alertSuccessCssClass);
                $container.html(message);
            }
        },
        alertError: function (message) {
            var $form = $(this),
                data = $form.data('yiiDirectoryForm');

            if (typeof data.settings.alertContainer !== undefined) {
                var $container = $form.find(data.settings.alertContainer);
                $container.removeClass(data.settings.alertSuccessCssClass).addClass(data.settings.alertErrorCssClass);
                $container.html(message);
            }
        }
    };
})(jQuery);
