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
    getUserId = function () {
        var value = $('#current-userid').text();
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

    deleteUserCookie = function (userid, safelock) {
        $('.toast').on('hidden.bs.toast', function () {
            cookiename = this.dataset.cookie;
            action = 'delete';
            ajaxFeed = $.ajax({
                url: "inc/php/cookie-manager.php",
                type: 'POST',
                data: {
                    userid: userid,
                    cookiename: cookiename,
                    safelock: safelock,
                    action: action
                },
            });
            ajaxFeed.done(function (response, textStatus) {
                console.log('success!' + response);
            });
            return runscroll;
        });
    }

    return {
        searchOnKeyUp: searchOnKeyUp,
        getUserName: getUserName,
        getUserId: getUserId,
        getSafeLock: getSafeLock,
        showToast: showToast,
        deleteUserCookie: deleteUserCookie
    };
});