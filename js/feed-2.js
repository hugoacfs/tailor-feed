define(['jquery', 'lodash'], function ($, _) {
    load = _.debounce(
        function (username, safelock, page) {
            console.log('received page:' + page);
            runscroll = true;
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
                page = parseInt(page) + 1;
                $('#current-page').html(page);
                // code 340 means nothing to show
                if (~response.indexOf("newscode:340")) {
                    runscroll = false;
                    if ($('#end-news').length === 0) {
                        $("#news-feed").append(response);
                        $('#current-page').html('end');
                        $(".load-feed-spinner").hide();
                    }
                } else {
                    $("#news-feed").append(response);
                }
                console.log('exiting ajax call with runscroll:' + runscroll);
            });
            return runscroll;
        }, 1000, { leading: true })

    condition = function () {
        var element = document.querySelector('.load-feed-spinner');
        var position = element.getBoundingClientRect();
        scrollEvent = ($(window).scrollTop() + $(window).height()) >= $(document).height() - 100;
        visibleEvent = position.top < window.innerHeight && position.bottom >= 0;
        console.log('scroll is:' + scrollEvent + ' visible is:' + visibleEvent);
        if (scrollEvent || visibleEvent) {
            return true;
        }
        return false;
    };
    moreNews = function (username, safelock) {
        if (condition()) {
            var page = $('#current-page').text();;
            if (page != 'end') {
                load(username, safelock, page);
                console.log('exiting moreNews call with runscroll:' + runscroll);
                console.log('next page:' + page);
            };
        };
    };
    return {
        moreNews: moreNews
    };
});
