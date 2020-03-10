define([
    'jquery',
    'methods',
    'modal',
    'feed',
    'bootstrap',
    'toggle',
    'lodash'
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
    console.log(username)

    $(".search-me").on("keyup", methods.searchOnKeyUp);
    if ($("#news-feed").length) {
        feed.loadMore(username, safelock);
    };
    modal.refreshSubscribed(username, safelock, 'topics');
    modal.refreshSubscribed(username, safelock, 'pages');

});