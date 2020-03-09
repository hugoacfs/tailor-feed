require(['jquery', 'lodash'], function ($, _) {
    // on scroll to bottom event
    var username = $('#current-username').text();
    var safelock = $('#current-safelock').text();
    var runscroll = true;
    var page = 1;
    window.onscroll = _.debounce(function (ev) {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            page = parseInt(page) + 1;
            if (runscroll) {
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
        }

    }, 500, {
        leading: true
    });
});
