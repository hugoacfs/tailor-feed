define(['jquery', 'lodash'], function ($, _) {
    load = _.debounce(
        function (safelock, page) {
            runscroll = true;
            ajaxFeed = $.ajax({
                url: "inc/php/load-feed.php",
                type: 'POST',
                data: {
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
                        $('#current-page').html('end');
                        $(".load-feed-spinner").hide();
                    }
                } else {
                    $("#news-feed").append(response);
                    page = parseInt(page) + 1;
                    $('#current-page').html(page);
                }
                $('.timeline-badge .timeline-img').one('load', function () {
                    $('.timeline-badge .img-spinner').css("display", "none");
                });
                carouselPop();
                $('video').on('fullscreenchange webkitfullscreenchange mozfullscreenchange', function () {
                    this.muted = document.fullscreenElement !== this;
                });
            });
            return runscroll;
        }, 1000, { leading: true })

    condition = function () {
        var element = document.querySelector('.load-feed-spinner');
        var position = element.getBoundingClientRect();
        scrollEvent = ($(window).scrollTop() + $(window).height()) >= $(document).height() - 100;
        visibleEvent = position.top < window.innerHeight && position.bottom >= 0;
        if (scrollEvent || visibleEvent) {
            return true;
        }
        return false;
    };
    moreNews = function (safelock) {
        if (condition()) {
            var page = $('#current-page').text();;
            if (page != 'end') {
                load(safelock, page);
            };
        };
    };

    carouselPop = function () {
        $('#news-feed').on('click', '.carousel-pop .carouselArticle .carousel-inner', function (e) {
            var chtml = $(e.currentTarget).parent().html();
            var children = $(e.currentTarget).children('.carousel-item');
            var vidChildren = $(e.currentTarget).find('.carousel-item').children('video');
            if (children.length > 1 && vidChildren.length < 1) {
                chtml += '<a class="carousel-control-prev" href="#carousel-lander" role="button" data-slide="prev">' +
                    '<span class="fas fa-arrow-left fa-lg text-primary p-5 rounded" aria-hidden="true"></span>' +
                    '<span class="sr-only">Previous</span>' +
                    '</a>' +
                    '<a class="carousel-control-next " href="#carousel-lander" role="button" data-slide="next">' +
                    '<span class="fas fa-arrow-right fa-lg text-primary p-5 rounded" aria-hidden="true"></span>' +
                    '<span class="sr-only">Next</span>' +
                    '</a>';
            } else {
                for (const item of children) {
                    item.setAttribute('data-dismiss', 'modal');
                };
            };
            if (vidChildren.length < 1) { //ignores videos
                var imgChildren = $(e.currentTarget).find('.carousel-item').children('img');
                for (const img of imgChildren) {
                    img.setAttribute('data-dismiss', 'modal');
                };
                $('#carousel-lander').html(chtml);
                $('#carousel-lander').carousel('pause');
                $('#carousel-modal').modal('show');
            };
        });
    };
    return {
        moreNews: moreNews,
        carouselPop: carouselPop
    };
});
