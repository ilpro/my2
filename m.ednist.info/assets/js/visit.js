var Browser = browserInfo();
var browserName = Browser.browserName + ' ' + Browser.majorVersion + ' (' + Browser.version + ')';
var userAgent = navigator.userAgent;
var mobile = Browser.mobile;
var language = navigator.language || navigator.userLanguage;
var os = osInfo();
var cookie = cookieInfo();
var screen = screenSize();
var referrerUrl = document.referrer || '';
var referrer = referrerUrl != '' ? referrerUrl.match(/:\/\/(.[^/]+)/)[1] : '';
var host = window.location.host != '' ? window.location.host : document.location.host;
var plugins = getPlugins();
var url = window.location.pathname != '' ? window.location.pathname : document.location.pathname;

var all = browserName + '|'
    + userAgent + '|'
    + mobile + '|'
    + language + '|'
    + os + '|'
    + cookie + '|'
    + screen + '|'
    + referrer + '|'
    + host + '|';
for(var i = 0; i<plugins.length; i++){
    all += plugins[i];
}

var sum = base64_encode(all);

function getPlugins(){
    var prev;
    var plugins = [];
    try{
        for(var i=0;i<navigator.plugins.length;i++) {
            var plugin = navigator.plugins[i];
            plugin = plugin.name+" "+(plugin.version || '');
            if (prev == plugin ) continue;
            plugins.push(plugin);
            prev = plugin;
        }
    }catch(e){}

    return plugins;
}

function screenSize() {
    var screenSize = '';
    try{
        if (window.screen.width) {
            width = (window.screen.width) ? window.screen.width : '';
            height = (window.screen.height) ? window.screen.height : '';
            screenSize += '' + width + " x " + height;
        }
    }catch(e){}

    if(screenSize == ''){
        try{
            if (window.outerWidth) {
                width = (window.outerWidth) ? window.outerWidth : '';
                height = (window.outerHeight) ? window.outerHeight : '';
                screenSize += '' + width + " x " + height;
            }
        }catch(e){}
    }

    if(screenSize == ''){
        try{
            if (document.documentElement.offsetWidth) {
                width = (document.documentElement.offsetWidth) ? document.documentElement.offsetWidth : '';
                height = (document.documentElement.offsetHeight) ? document.documentElement.offsetHeight : '';
                screenSize += '' + width + " x " + height;
            }
        }catch(e){}
    }
    return screenSize;
}

function browserInfo() {
    var Browser = {
        nVer: navigator.appVersion,
        nAgt: navigator.userAgent,
        browserName: navigator.appName,
        version: '' + parseFloat(navigator.appVersion),
        majorVersion: parseInt(navigator.appVersion, 10),
        mobil: ''
    };
    var nameOffset, verOffset, ix;

    try{// Opera
        if ((verOffset = Browser.nAgt.indexOf('Opera')) != -1) {
            Browser.browserName = 'Opera';
            Browser.version = Browser.nAgt.substring(verOffset + 6);
            if ((verOffset = Browser.nAgt.indexOf('Version')) != -1) {
                Browser.version = Browser.nAgt.substring(verOffset + 8);
            }
        }
        // Opera Next
        if ((verOffset = Browser.nAgt.indexOf('OPR')) != -1) {
            Browser.browserName = 'Opera';
            Browser.version = Browser.nAgt.substring(verOffset + 4);
        }
        // MSIE
        else if ((verOffset = Browser.nAgt.indexOf('MSIE')) != -1) {
            Browser.browserName = 'Microsoft Internet Explorer';
            Browser.version = Browser.nAgt.substring(verOffset + 5);
        }
        // Chrome
        else if ((verOffset = Browser.nAgt.indexOf('Chrome')) != -1) {
            Browser.browserName = 'Chrome';
            Browser.version = Browser.nAgt.substring(verOffset + 7);
        }
        // Safari
        else if ((verOffset = Browser.nAgt.indexOf('Safari')) != -1) {
            Browser.browserName = 'Safari';
            Browser.version = Browser.nAgt.substring(verOffset + 7);
            if ((verOffset = Browser.nAgt.indexOf('Version')) != -1) {
                Browser.version = Browser.nAgt.substring(verOffset + 8);
            }
        }
        // Firefox
        else if ((verOffset = Browser.nAgt.indexOf('Firefox')) != -1) {
            Browser.browserName = 'Firefox';
            Browser.version = Browser.nAgt.substring(verOffset + 8);
        }
        // MSIE 11+
        else if (Browser.nAgt.indexOf('Trident/') != -1) {
            Browser.browserName = 'Microsoft Internet Explorer';
            Browser.version = Browser.nAgt.substring(Browser.nAgt.indexOf('rv:') + 3);
        }
        // Other browsers
        else if ((nameOffset = Browser.nAgt.lastIndexOf(' ') + 1) < (verOffset = Browser.nAgt.lastIndexOf('/'))) {
            Browser.browserName = Browser.nAgt.substring(nameOffset, verOffset);
            Browser.version = Browser.nAgt.substring(verOffset + 1);
            if (Browser.browser.toLowerCase() == Browser.browser.toUpperCase()) {
                Browser.browserName = navigator.appName;
            }
        }
        // trim the version string
        if ((ix = Browser.version.indexOf(';')) != -1) Browser.version = Browser.version.substring(0, ix);
        if ((ix = Browser.version.indexOf(' ')) != -1) Browser.version = Browser.version.substring(0, ix);
        if ((ix = Browser.version.indexOf(')')) != -1) Browser.version = Browser.version.substring(0, ix);

        Browser.majorVersion = parseInt('' + Browser.version, 10);
        if (isNaN(Browser.majorVersion)) {
            Browser.version = '' + parseFloat(navigator.appVersion);
            Browser.majorVersion = parseInt(navigator.appVersion, 10);
        }

        // mobile version
        Browser.mobile = /Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(Browser.nVer);
    }catch(e){}


    return Browser;
}

function cookieInfo() {
    var cookieEnabled = (navigator.cookieEnabled) ? true : false;

    if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
        document.cookie = 'testcookie';
        cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
    }
    return cookieEnabled;
}

function osInfo() {
    var os = '-';
    var clientStrings = [
        {s: 'Windows 10', r: /(Windows 10.0|Windows NT 10.0)/},
        {s: 'Windows 8.1', r: /(Windows 8.1|Windows NT 6.3)/},
        {s: 'Windows 8', r: /(Windows 8|Windows NT 6.2)/},
        {s: 'Windows 7', r: /(Windows 7|Windows NT 6.1)/},
        {s: 'Windows Vista', r: /Windows NT 6.0/},
        {s: 'Windows Server 2003', r: /Windows NT 5.2/},
        {s: 'Windows XP', r: /(Windows NT 5.1|Windows XP)/},
        {s: 'Windows 2000', r: /(Windows NT 5.0|Windows 2000)/},
        {s: 'Windows ME', r: /(Win 9x 4.90|Windows ME)/},
        {s: 'Windows 98', r: /(Windows 98|Win98)/},
        {s: 'Windows 95', r: /(Windows 95|Win95|Windows_95)/},
        {s: 'Windows NT 4.0', r: /(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
        {s: 'Windows CE', r: /Windows CE/},
        {s: 'Windows 3.11', r: /Win16/},
        {s: 'Android', r: /Android/},
        {s: 'Open BSD', r: /OpenBSD/},
        {s: 'Sun OS', r: /SunOS/},
        {s: 'Linux', r: /(Linux|X11)/},
        {s: 'iOS', r: /(iPhone|iPad|iPod)/},
        {s: 'Mac OS X', r: /Mac OS X/},
        {s: 'Mac OS', r: /(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
        {s: 'QNX', r: /QNX/},
        {s: 'UNIX', r: /UNIX/},
        {s: 'BeOS', r: /BeOS/},
        {s: 'OS/2', r: /OS\/2/},
        {s: 'Search Bot', r: /(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
    ];
    for (var id in clientStrings) {
        var cs = clientStrings[id];
        if (cs.r.test(Browser.nAgt)) {
            os = cs.s;
            break;
        }
    }

    var osVersion = '-';

    if (/Windows/.test(os)) {
        osVersion = /Windows (.*)/.exec(os)[1];
        os = 'Windows';
    }

    switch (os) {
        case 'Mac OS X':
            osVersion = /Mac OS X (10[\.\_\d]+)/.exec(Browser.nAgt)[1];
            break;

        case 'Android':
            osVersion = /Android ([\.\_\d]+)/.exec(Browser.nAgt)[1];
            break;

        case 'iOS':
            osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(Browser.nVer);
            osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
            break;
    }
    return os + ' ' + osVersion;
}

function base64_encode(data) {
    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = '',
        tmp_arr = [];
    if (!data) {
        return data;
    }
    do {
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    var r = data.length % 3;

    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

var URL = 'http://test.follownews.info/counter.php';
var personId = getCookie("personId");
var xhr = ("onload" in new XMLHttpRequest()) ? new XMLHttpRequest() : new XDomainRequest();

function counters(){
    if(personId != undefined) {
        xhr.open('POST', URL, true);
        xhr.onload = function () {
            console.log(this.responseText);
        };
        xhr.onerror = function () {
            console.log('Error ' + this.status);
        };
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send("act=a2&FH8d4bJD=" + sum + "&GJrbb54j=" + personId + "&url=" + url);
    }else{
        xhr.open('POST', URL, true);
        xhr.onload = function () {
            console.log(this.responseText);
            var date = new Date(new Date().getTime() + 364 * 24 * 60 * 60 * 1000);
            document.cookie = "personId="+xhr.responseText+"; path=/; expires=" + date.toUTCString();
        };
        xhr.onerror = function () {
            console.log('Error ' + this.status);
        };
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send("act=a1&FH8d4bJD=" + sum + "&url=" + url);
    }
}

counters();