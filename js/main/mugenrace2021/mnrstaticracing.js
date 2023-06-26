(() => {

    let splideParams = {
        type: 'loop',
        height: '100vh',
        width: '100vw',
        arrowPath: 'm 9.25,34.25 v -2.82 l 5.05,-3.77 5.05,-3.77 c 2.69,0.04 4.81,0.03 7.11,0.04 L 17.38,30.79 9.25,37.08 Z M 16.50,14.16 9.25,8.50 9.25,5.71 9.25,2.92 17,8.95 l 7.75,6.03 3,2.47 3,2.47 -3.5,-0.05 -3.5,-0.05 -7.25,-5.66 z',
    }

    function doScroll(up) {
        window.scrollBy({
            top: (window.innerHeight + 4) * up,
            behavior: "smooth"
        });
    }

    document.addEventListener('keydown', function (e) {
        switch (e.code) {
            case 'ArrowUp':
            case 'PageUp':
                e.preventDefault();
                doScroll(-1);
                break;
            case 'ArrowDown':
            case 'PageDown':
                e.preventDefault();
                doScroll(1);
                break;
        }
    });

    document.addEventListener('wheel', function (e) {
        e.preventDefault();
        e.deltaY = 0;
        if (e.deltaY > 0) {
            doScroll(1);
        } else {
            doScroll(-1);
        }
    }, {passive: false});

    document.addEventListener('DOMContentLoaded', () => {
        let splides = document.getElementsByClassName('rc-img-container');

        if (splides) {
            for (let i = 0; i < splides.length; i++) {
                let a = new Splide('#' + splides[i].id, splideParams);
                a.mount();
            }
        }
    })
})();