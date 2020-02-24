function searchArea(target) {
    var value = $(target).val().toLowerCase();
    $(target).filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}
function modalAjax(username, safelock = null, phprequestfile) {
    var username = username;
    var safelock = safelock;
    var phprequestfile = phprequestfile;
    ajaxPages = $.ajax({
        url: phprequestfile,
        type: 'POST',
        data: {
            username: username,
            safelock: safelock
        },
    });
    ajaxPages.done(function (response, textStatus, jqXHR) {
        $("#modal-form").html('');
        $("#modal-form").html(response);
        $("[data-toggle='toggle']").bootstrapToggle('destroy')
        $("[data-toggle='toggle']").bootstrapToggle();
    });
}

function modalLoadSpinner() {
    // Hide Modals
    var loadingspinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
    $(".modal").on("hidden.bs.modal", _.debounce(function () {
        $(".modal").html('');
        document.getElementById('search-area-topics').value = "";
        document.getElementById('search-area-pages').value = "";
        $(".modal").html(loadingspinner);
    }), 150, {
        leading: true
    });
}

function loadMoreArticles(username, safelock, page) {
    var username = username;
    var safelock = safelock;
    var page = page;
    ajaxFeed = $.ajax({
        url: "inc/php/load-feed.php",
        type: 'POST',
        data: {
            username: username,
            page: page,
            safelock: safelock
        },
    });
    ajaxFeed.done(function (response, textStatus, jqXHR) {
        // code 340 means nothing to show
        if (~response.indexOf("newscode:340")) {
            runscroll = false;
            if ($('#end-news').length === 0) {
                $("#news-feed").append(response);
            }
        } else {
            $("#news-feed").append(response);
        }
    });
}

// prevents form re-submission
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
$(document).ready(function () {
    var username = "<?php echo $_SESSION['username']; ?>";
    var safelock = '<?php echo false; ?>';
    var runscroll = true;
    var page = 1;

    // $("#search-area-admin").on("keyup", searchArea(".table tbody tr"));
    // $("#search-area-pages").on("keyup", searchArea(".search-item"));
    // $("#search-area-topics").on("keyup", searchArea(".search-item"));
    $('.toast').toast('show');
    $("#topics-btn").on("click", _.debounce(function () {
        modalAjax(username, safelock, 'inc/php/load-topics.php')
    },
        1500,
        {
            leading: true
        }
    ));
    $("#pages-btn").on("click", _.debounce(function () {
        modalAjax(username, safelock, 'inc/php/load-pages.php')
    },
        1500,
        {
            leading: true
        }
    ));
    window.onscroll = _.debounce(function () {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            page = parseInt(page) + 1;
            if (runscroll) {
                loadMoreArticles(username, safelock, page)
            };
        }
    },
        500,
        {
            leading: true
        });
});