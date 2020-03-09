define(['jquery', 'lodash', 'toggle'], function ($, _) {
    // ajax for pages
    $(document).ready(function () {
        var username = $('#current-username').text();
        var safelock = $('#current-safelock').text();
        var loadingspinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        // Pages Hide Modal
        $("#pagesModal").on("hidden.bs.modal", _.debounce(function () {
            $("#pages-modal-form").html('');
            document.getElementById('search-area-pages').value = "";
            $("#pages-modal-form").html(loadingspinner);
        }), 150, {
            leading: true
        });
        // Pages AJAX
        $("#pages-btn").on("click", _.debounce(function () {
            ajaxPages = $.ajax({
                url: "inc/php/load-pages.php",
                type: 'POST',
                data: {
                    username: username,
                    safelock: safelock
                },
            });
            ajaxPages.done(function (response, textStatus, jqXHR) {
                $("#pages-modal-form").html('');
                $("#pages-modal-form").html(response);
                $("[data-toggle='toggle']").bootstrapToggle('destroy')
                $("[data-toggle='toggle']").bootstrapToggle();
            });
        }, 1500, {
            leading: true
        }));
    });
});