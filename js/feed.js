define(['jquery', 'lodash'], function ($, _) {
    loadMore = function (username, safelock) {
        // on scroll to bottom event
        var runscroll = true;
        var page = 1;
        console.log('scroll');
        var element = document.querySelector('.load-feed-spinner');
        var position = element.getBoundingClientRect();
        visibleEvent = position.top < window.innerHeight && position.bottom >= 0;
        console.log(visibleEvent);
        getMeNews(page, username, safelock, runscroll, visibleEvent);
    };

    getMeNews = _.debounce(function(page, username, safelock, runscroll) {
        console.log(page);
        scrollEvent = ($(window).scrollTop() + $(window).height()) >= $(document).height() - 100;
        if (scrollEvent || visibleEvent) {
            console.log(scrollEvent);
            console.log(visibleEvent);
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
                            $(".load-feed-spinner").hide();
                        }
                    } else {
                        $("#news-feed").append(response);
                    }
                });
            };
        };
    }, 750, { leading: true }
    );
    return {
        loadMore: loadMore
    };
});
