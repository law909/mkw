class billyloader {

    constructor(frameid, baseurl, path, params) {

        const me = new URL(document.currentScript.src);

        this.baseUrl = baseurl;
        this.params = params;
        this.iFrameId = frameid;
        this.path = path;

        console.log('init: ' + this.iFrameId);

        let iframe = document.createElement("iframe");
        if (iframe.attachEvent) {
            iframe.attachEvent("onload", () => {
                this.IFrameOnLoad()
            })
        } else {
            iframe.addEventListener("load", () => {
                this.IFrameOnLoad()
            }, false)
        }
        iframe.setAttribute('src', new URL(this.baseUrl + this.path + this.params.toString()));
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
