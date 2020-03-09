// ajax for pages
define(['jquery', 'lodash', 'toggle'], function ($, _) {

    $(document).ready(function () {
        var username = $('#current-username').text();
        var safelock = $('#current-safelock').text();
        var loadingspinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        // Topics Hide Modal
        $("#topicsModal").on("hidden.bs.modal", _.debounce(function () {
            $("#topics-modal-form").html('');
            document.getElementById('search-area-topics').value = "";
            $("#topics-modal-form").html(loadingspinner);
        }), 150, {
            leading: true
        });
        // Topics AJAX
        $("#topics-btn").on("click", _.debounce(function () {
            ajaxPages = $.ajax({
                url: "inc/php/load-topics.php",
                type: 'POST',
                data: {
                    username: username,
                    safelock: safelock
                },
            });
            ajaxPages.done(function (response, textStatus, jqXHR) {
                $("#topics-modal-form").html('');
                $("#topics-modal-form").html(response);
                $("[data-toggle='toggle']").bootstrapToggle('destroy')
                $("[data-toggle='toggle']").bootstrapToggle();
            });
        }, 1500, {
            leading: true
        }));
    });
});