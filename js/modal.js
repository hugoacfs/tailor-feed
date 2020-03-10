// ajax for pages
define(['jquery', 'lodash', 'toggle'], function ($, _) {

    refreshSubscribed = function (username, safelock, modalType) {
        // Hide Modal - it resets the modal upon closing
        $("#" + modalType + "Modal").on("hidden.bs.modal", _.debounce(function () {
            $("#" + modalType + "-modal-form").html('');
            $('#search-area-' + modalType).value = "";
            $("#" + modalType + "-modal-form").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
        }), 150, {
            leading: true
        });
        // AJAX - it populates the modal upon opening (btn click)
        $("body").on("click", "#" + modalType + "-btn", _.debounce(function () {
            ajaxPages = $.ajax({
                url: "inc/php/load-" + modalType + ".php",
                type: 'POST',
                data: {
                    username: username,
                    safelock: safelock
                },
            });
            ajaxPages.done(function (response, textStatus, jqXHR) {
                $("#" + modalType + "-modal-form").html('');
                $("#" + modalType + "-modal-form").html(response);
                $("#" + modalType + "-modal-form [data-toggle='toggle']").bootstrapToggle('destroy')
                $("#" + modalType + "-modal-form [data-toggle='toggle']").bootstrapToggle();
            });
        }, 1500, {
            leading: true
        }));
    };
    return {
        refreshSubscribed: refreshSubscribed
    }
});