define(['jquery'], function ($) {
    searchOnKeyUp = function (event) {
        var value = this.value.toLowerCase();
        var id = this.id;
        var container = '';
        switch (id) {
            case 'search-area-topics':
                container = '#topics-modal-form .search-item';
                break;
            case 'search-area-pages':
                container = '#pages-modal-form .search-item';
                break;
            case 'search-area-admin':
                container = '.table tbody tr';
                break;
            default:
                break;
        }
        $(container).filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
    getUserName = function () {
        var value = $('#current-username').text();
        return value;
    };
    getSafeLock = function () {
        var value = $('#current-safelock').text();
        return value;
    };
    showToast = function () {
        $('.toast').toast('show');
    };

    return {
        searchOnKeyUp: searchOnKeyUp,
        getUserName: getUserName,
        getSafeLock: getSafeLock,
        showToast: showToast,
    };
});