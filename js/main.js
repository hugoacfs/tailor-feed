define([
    'jquery',
    'methods',
    'modal',
    'feed',
    'admin',
    'bootstrap',
    'toggle'
], function ($, methods, modal, feed) {
    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
    // prevents form re-submission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    var username = methods.getUserName();
    var safelock = methods.getSafeLock();
    // console.log(username)

    $(".search-me").on("keyup", methods.searchOnKeyUp);
    if ($("#news-feed").length) {
        feed.loadMore(username, safelock);
    };
    //Login page js, toggles new account creation mode
    if ($("#newaccount").length) {
        $('#newaccount').click(function () {
            $('#name-input').toggle();
        });
    };
    modal.refreshSubscribed(username, safelock, 'topics');
    modal.refreshSubscribed(username, safelock, 'pages');
});