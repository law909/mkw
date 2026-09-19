/*
 * A foglalási lapok a wordpress oldal iframe-jében élnek. Navigáció után a szülő oldal
 * görgetési pozíciója marad, az iframe magassága viszont megváltozik, így a látogató a
 * wordpress lap aljára kerül. Az iframe-resizer onReady-jében a szülőt az iframe tetejére
 * görgetjük, hogy az űrlapot és a visszaigazolást azonnal lássa.
 *
 * Az iframeResizer.contentWindow.min.js ELŐTT kell betölteni: a beállításait induláskor olvassa.
 */
(function () {
    const scrollToTop = () => {
        if (window.parentIFrame) {
            window.parentIFrame.scrollToOffset(0, 0);
        } else if (window.self === window.top) {
            window.scrollTo(0, 0);
        }
    };

    window.iFrameResizer = window.iFrameResizer || {};
    const elozoOnReady = window.iFrameResizer.onReady;
    window.iFrameResizer.onReady = function () {
        if (typeof elozoOnReady === 'function') {
            elozoOnReady.apply(this, arguments);
        }
        scrollToTop();
    };

    document.addEventListener('DOMContentLoaded', scrollToTop);
})();
