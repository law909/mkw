document.addEventListener( 'DOMContentLoaded', function() {
    var splide = new Splide( '.splide', {
        type: 'loop',
        pagination: false,
        dragMinTreshold: {
            mouse: 5,
            touch: 10
        },
        direction: 'ttb',
        autoHeight: true,
        height: '77vh',
        gap: '3px',
        arrowPath: 'm 9.25,34.25 v -2.82 l 5.05,-3.77 5.05,-3.77 c 2.69,0.04 4.81,0.03 7.11,0.04 L 17.38,30.79 9.25,37.08 Z M 16.50,14.16 9.25,8.50 9.25,5.71 9.25,2.92 17,8.95 l 7.75,6.03 3,2.47 3,2.47 -3.5,-0.05 -3.5,-0.05 -7.25,-5.66 z',
    } );

    splide.on('click', (slide, e) => {
        document.querySelector('.tl-termek-img').src = e.target.getAttribute('data-url');
    });

    splide.mount();
} );
