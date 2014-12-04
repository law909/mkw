var mkwerrorlog = (function($) {

window.onerror = function (errorMsg, url, lineNumber) {

    var guid = function() {
      function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
                   .toString(16)
                   .substring(1);
      }
      return function() {
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
               s4() + '-' + s4() + s4() + s4();
      };
    };

    var egyediid = guid();

    function getSessid() {
        var x = document.cookie.match(/PHPSESSID=[^;]+/);
        if (!x) {
            return egyediid;
        }
        if (typeof(x) == 'object') {
            x = x[0];
        }
        if (typeof(x) == 'string') {
            return x.substring(10);
        }
        return egyediid;
    }

    function ajaxlog(str) {
        $.ajax({
            type: 'POST',
            url: '/jslogger.php',
            data: {
                req: 'write',
                data: getSessid() + ' ## ' + str
            }
        });
    }

    ajaxlog('Error: ' + errorMsg + ' Script: ' + url + ' Line: ' + lineNumber);
}
})(jQuery);