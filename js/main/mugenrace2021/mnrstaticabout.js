(() => {

    function debounce_leading(func, timeout = 400) {
        let timer;
        return (...args) => {
            if (!timer) {
                func.apply(this, args);
            }
            clearTimeout(timer);
            timer = setTimeout(() => {
                timer = undefined;
            }, timeout);
        };
    }

    function doScroll(up) {
        window.scrollBy({
            top: (window.innerHeight + 4) * up,
            behavior: "smooth"
        });
    }

    function keydown(e) {
        switch (e.code) {
            case 'ArrowLeft':
            case 'ArrowUp':
            case 'PageUp':
                doScroll(-1);
                break;
            case 'ArrowRight':
            case 'ArrowDown':
            case 'PageDown':
                doScroll(1);
                break;
        }
    }

    function wheel(e) {
        e.deltaY = 0;
        if (e.deltaY > 0) {
            doScroll(1);
        } else {
            doScroll(-1);
        }
    }

    const
        onKeydown = debounce_leading((e) => keydown(e)),
        onWheel = debounce_leading((e) => wheel(e));

    document.addEventListener('keydown', function (e) {
        e.preventDefault();
        onKeydown(e);
    });

    document.addEventListener('wheel', function (e) {
        e.preventDefault();
        onWheel(e);
    }, {passive: false});

    window.addEventListener('resize', function (e) {
        let A = window.innerWidth * 0.7;
        let B = window.innerHeight * 0.06;
        let C = Math.sqrt(A * A + B * B);
        // let alfa = Math.asin(stranaA/stranaC);
        // alfa = alfa * 180 / Math.PI;
        let beta = Math.asin(B / C);
        beta = beta * 180 / Math.PI;
        const szlogens = document.querySelectorAll('.page-szlogen1, .page-szlogen2');
        szlogens.forEach((szlogen) => {
            szlogen.style.transform = 'rotate(${beta}deg)';
        });
    });

})();