class billyloader {

    constructor(frameid) {

        const me = new URL(document.currentScript.src);

        this.baseUrl = me.origin;
        this.params = new URLSearchParams(me.search);
        this.iFrameId = frameid + this.params.get('i');

        console.log('init: ' + this.iFrameId);

        let iframe = document.createElement("iframe");
        if (iframe.attachEvent) {
            iframe.attachEvent("onload", function () {
                this.IFrameOnLoad()
            })
        } else {
            iframe.addEventListener("load", function () {
                this.IFrameOnLoad()
            }, false)
        }
        iframe.setAttribute('src', new URL(this.baseUrl + 'rendezveny/reg?' + this.params.toString()));
        iframe.setAttribute('id', this.iFrameId);
        iframe.setAttribute('height', '100%');
        iframe.setAttribute('width', '100%');
        iframe.style.overflow = 'hidden';
        iframe.style.border = 'none';
        document.currentScript.parentNode.appendChild(iframe);
        setTimeout(function () {
        }, 1e3)
    }
    IFrameOnLoad() {
        console.log('iframeonload: ' + this.iFrameId);

        iFrameResize({ log: true }, '#' + this.iFrameId);
    }
}

new billyloader('rendezvenyregframe');