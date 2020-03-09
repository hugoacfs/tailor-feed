define([
    'jquery',
    'methods',
    'bootstrap',
    'toggle',
    'lodash',
    'topics'
], function ($, methods) {
    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
    // prevents form re-submission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    var username = methods.getUserName();
    var safelock = methods.getSafeLock();
    $(".search-me").on("keyup", methods.searchOnKeyUp);
    // console.log(username);
    // console.log(safelock);
});