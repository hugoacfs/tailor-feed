define(['jquery', 'lodash'], function ($, _) {
    load = _.debounce(
        function (username, safelock, page) {
            // console.log('received page:' + page);
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
                // console.log('exiting ajax call with runscroll:' + runscroll);
            });
            return runscroll;
        }, 1000, { leading: true })

    condition = function () {
        var element = document.querySelector('.load-feed-spinner');
        var position = element.getBoundingClientRect();
        scrollEvent = ($(window).scrollTop() + $(window).height()) >= $(document).height() - 100;
        visibleEvent = position.top < window.innerHeight && position.bottom >= 0;
        // console.log('scroll is:' + scrollEvent + ' visible is:' + visibleEvent);
        if (scrollEvent || visibleEvent) {
            return true;
        }
        console.log(visibleEvent + ' event.');
        return false;
    };
    moreNews = function (username, safelock) {
        if (condition()) {
            var page = $('#current-page').text();;
            if (page != 'end') {
                load(username, safelock, page);
                page = parseInt(page) + 1;
                $('#current-page').html(page);
                // console.log('exiting moreNews call with runscroll:' + runscroll);
                // console.log('next page:' + page);
            };
        };
        // imagePop();
        carouselPop();
    };
    imagePop = function () {
        $('.img-pop').on('click', function (e) {
            $('.image-viewer').attr('src', $(this).find('img').attr('src'));
            $('#image-modal').modal('show');
        });
    };
    carouselPop = function () {
        $('.carousel-pop').on('click', function (e) {
            var chtml = $(e.currentTarget).html();
            var children = $(e.currentTarget).find('.carousel-inner').children('.carousel-item');
            console.log(children.length);
            if (children.length > 1) {
                // get outta here;
                chtml += '<a class="carousel-control-prev" href="#carousel-lander" role="button" data-slide="prev">' +
                            '<span class="fas fa-arrow-left fa-lg text-dark" aria-hidden="true"></span>' +
                            '<span class="sr-only">Previous</span>' +
                        '</a>' +
                        '<a class="carousel-control-next " href="#carousel-lander" role="button" data-slide="next">' +
                            '<span class="fas fa-arrow-right fa-lg text-dark" aria-hidden="true"></span>' +
                            '<span class="sr-only">Next</span>' +
                        '</a>';
            }
            $('#carousel-lander').html(chtml);
            $('#carousel-lander').carousel('pause');
            // console.log($(this).html());
            //console.log(e);
            $('#carousel-modal').modal('show');
        });
    };
    return {
        moreNews: moreNews
    };
});
