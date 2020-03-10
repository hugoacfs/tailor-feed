define(['jquery', 'lodash'], function ($, _) {
    loadMore = function (username, safelock) {
        // on scroll to bottom event
        var runscroll = true;
        var page = 1;
        $(window).scroll(_.debounce(function (ev) {
            scrollEvent = ($(window).scrollTop() + $(window).height()) >= $(document).height() - 100;
            // console.log(scrollEvent);
            if (scrollEvent) {
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
        }));
    };
    return {
        loadMore: loadMore
    }
});
