class billyloader {

    constructor(frameid, baseurl, path, params) {

        this.baseUrl = baseurl;
        this.params = params;
        this.iFrameId = frameid;
        this.path = path;

        console.log(this.path);

        if (this.params && (this.path.slice(-1) !== '?')) {
            this.path = this.path + '?';
        }

        console.log(this.path.slice(-1));
        console.log(this.path);

        let iframe = document.createElement("iframe");
        if (iframe.attachEvent) {
            iframe.attachEvent("onload", () => {
                iFrameResize({}, '#' + this.iFrameId);
            })
        } else {
            iframe.addEventListener("load", () => {
                iFrameResize({}, '#' + this.iFrameId);
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
}
