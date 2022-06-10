{

    function doScroll(up) {
        window.scrollBy({
            top: (window.innerHeight + 4) * up,
            behavior: "smooth"
        });
        console.log('doScroll');
    }

    document.addEventListener('keydown', function (e) {
        switch (e.code) {
            case 'ArrowLeft':
            case 'ArrowUp':
            case 'PageUp':
                e.preventDefault();
                doScroll(-1);
                break;
            case 'ArrowRight':
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
        console.log('onwheel');
        if (e.deltaY > 0) {
            doScroll(1);
        }
        else {
            doScroll(-1);
        }
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
    })
}