// Create the XHR object.
function createCORSRequest(method, url) {
    var xhr = new XMLHttpRequest();
    if ("withCredentials" in xhr) {
        // XHR for Chrome/Firefox/Opera/Safari.
        xhr.open(method, url, true);
    } else if (typeof XDomainRequest != "undefined") {
        // XDomainRequest for IE.
        xhr = new XDomainRequest();
        xhr.open(method, url);
    } else {
        // CORS not supported.
        xhr = null;
    }
    return xhr;
}
// Make the actual CORS request.
function makeCorsRequest() {
    // This is a sample server that supports CORS.
    var url = 'fetch-html.php';

    var xhr = createCORSRequest('GET', url);
    if (!xhr) {
        alert('CORS not supported');
        return;
    }
    // Response handlers.
    xhr.onload = function () {
        // alert('Response from CORS request to ' + url);
        document.getElementsByClassName("news-container")[0].innerHTML = xhr.responseText;
    };
    xhr.onerror = function () {
        alert('Woops, there was an error making the request.');
    };
    xhr.send();
}
// Create new link Element 
var link = document.createElement('link');

// set the attributes for link element 
link.rel = 'stylesheet';
link.type = 'text/css';
link.href = 'news.css';
// Get HTML head element to append  
// link element to it  
document.getElementsByTagName('HEAD')[0].appendChild(link);
makeCorsRequest();