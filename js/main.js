define([
    'jquery',
    'lodash',
    'methods',
    'modal',
    'feed',
    'admin',
    'bootstrap',
    'toggle'
], function ($, _, methods, modal, feed) {
    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
    $('.carousel').carousel('pause');
    // prevents form re-submission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    var userid = methods.getUserId();
    var safelock = methods.getSafeLock();
    methods.showToast();
    methods.deleteUserCookie(userid, safelock);
    $(".search-me").on("keyup", methods.searchOnKeyUp);
    if ($('body').attr("data-pageid") == 'login') {
        //Login page js, toggles new account creation mode
        if ($("#newaccount").length) {
            $('#newaccount').click(function () {
                $('#name-input').toggle();
            });
        };
    }
    if ($('body').attr("data-pageid") == 'timeline') {
        feed.moreNews(safelock);
        document.addEventListener('scroll',
            _.debounce(
                function () {
                    feed.moreNews(safelock);
                }, 50, {
                immediate: true
            }
            )
        );
        modal.refreshSubscribed(safelock, 'topics');
        modal.refreshSubscribed(safelock, 'pages');
    }
    $(document).ready(function () {
        feed.carouselPop();
    });
    //video
    function playVisibleVideos() {
        document.querySelectorAll("video").forEach(video => elementIsVisible(video) ? video.play() : video.pause());
    }
    function elementIsVisible(el) {
        let rect = el.getBoundingClientRect();
        return (rect.bottom >= 0 && rect.right >= 0 && rect.top <= (window.innerHeight || document.documentElement.clientHeight) && rect.left <= (window.innerWidth || document.documentElement.clientWidth));
    }
    let playVisibleVideosTimeout;
    window.addEventListener("scroll", () => {
        clearTimeout(playVisibleVideosTimeout);
        playVisibleVideosTimeout = setTimeout(playVisibleVideos, 100);
    });

    window.addEventListener("resize", playVisibleVideos);
    window.addEventListener("DOMContentLoaded", playVisibleVideos);
});