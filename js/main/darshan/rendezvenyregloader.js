if (typeof billyloader !== "object") {
    billyloader = {
        iFrameId : 'rendezvenyregframe',
        params: '',
        BaseUrl: '',
        SetBaseUrl: function(Url) {
            if (billyloader.BaseUrl != '') return;
            matches = String(Url).toLowerCase().match(/(http|https):\/\/(.[^\/]+)\//i);
            billyloader.BaseUrl = matches[0]
        },
        getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(billyloader.params);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        },
        MessageHandler: function(e) {
            var obj = JSON.parse(e.data);
            if (typeof obj != "object") return;
            if (obj.action == "sizing") {
                iframe = document.getElementById(obj.target);
                iframe.style.width = "100%";
                iframe.style.height = 40 + parseInt(obj.height) + "px"
            }
        },
        IFrameOnLoad: function() {
            var iframe = document.getElementById(billyloader.iFrameId);
            var data = '{"action": "sizing?", "target": "' + billyloader.iFrameId + '"}';
            iframe.contentWindow.postMessage(data, billyloader.BaseUrl);
        },
        Init: function() {
            var scripts = document.getElementsByTagName("script");

            function _toConsumableArray(arr) {
                for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
                    arr2[i] = arr[i]
                }
                return arr2
            }

            var myScripts = [].concat(_toConsumableArray(scripts)).filter(function (x) {
                return x.src.match(/rendezvenyregloader\.js/)
            });
            var me = myScripts[myScripts.length - 1];
            var src = me.src.split("?");
            billyloader.params = '?' + src[1];
            billyloader.SetBaseUrl(src[0]);

            var iframe = document.createElement("iframe");
            if (iframe.attachEvent) {
                iframe.attachEvent("onload", function () {
                    billyloader.IFrameOnLoad()
                })
            } else {
                iframe.addEventListener("load", function () {
                    billyloader.IFrameOnLoad()
                }, false)
            }
            iframe.setAttribute('src', 'http://darshan.billy.hu/rendezveny/reg?r=' + billyloader.getUrlParameter('r'));
            iframe.setAttribute('id', billyloader.iFrameId);
            iframe.setAttribute('height', '100%');
            iframe.setAttribute('width', '100%');
            iframe.style.overflow = 'hidden';
            iframe.style.border = 'none';
            me.parentNode.appendChild(iframe);
            setTimeout(function () {
            }, 1e3)
        },
        InitOnce: function() {
            if (typeof window.addEventListener != "undefined") {
                window.addEventListener("message", billyloader.MessageHandler, false)
            } else if (typeof window.attachEvent != "undefined") {
                window.attachEvent("onmessage", billyloader.MessageHandler)
            }
        }
    };
    billyloader.InitOnce()
}
billyloader.Init();
