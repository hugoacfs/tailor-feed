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
    getCookie = function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    deleteCookie = function (name, path, domain) {
        if (getCookie(name)) {
            document.cookie = name + "=" +
                ((path) ? ";path=" + path : "") +
                ((domain) ? ";domain=" + domain : "") +
                ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
        }
    }
    hideWelcomeToast = function () {
        $('.toast').on('hidden.bs.toast', function () {
            // destroy 2 cookies
            deleteCookie('welcomemessage');
            deleteCookie('wmtimestamp');
        });
    }
    return {
        searchOnKeyUp: searchOnKeyUp,
        getUserName: getUserName,
        getSafeLock: getSafeLock,
        showToast: showToast,
        hideWelcomeToast: hideWelcomeToast
    };
});