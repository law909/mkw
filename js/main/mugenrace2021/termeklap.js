document.addEventListener("alpine:init", () => {
    Alpine.data("termeklap", () => ({
        colors: [],
        sizes: [],
        stocks: [],
        selectedSize: null,
        selectedColor: null,
        canAddToCart: false,
        getLists() {
            const url = new URL('/valtozatadatok', location.origin);
            url.searchParams.append('tid', this.termekid);
            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    this.colors = data.szinek;
                    this.sizes = data.meretek;
                    this.stocks = data.keszlet;
                });
        },
        selectSize(size) {
            this.canAddToCart = false;
            if (this.selectedColor) {
                this.canAddToCart = this.stocks[this.selectedColor.value + size.value];
            }
            this.selectedSize = size;
        },
        selectColor(color) {
            this.canAddToCart = false;
            if (this.selectedSize) {
                this.canAddToCart = this.stocks[color.value + this.selectedSize.value];
            }
            this.selectedColor = color;
        },
        addToCart() {
            alert('A kosárban lesz majd: ' + this.selectedColor.value + ' - ' + this.selectedSize.value);
        }
    }));
});

document.addEventListener( 'DOMContentLoaded', function() {
    let desktopsplide = new Splide( '.tl-splide-desktop', {
        mediaQuery: 'max',
        breakPoints: {
            760: {
                destroy: true
            }
        },
        type: 'loop',
        pagination: false,
        direction: 'ttb',
        autoHeight: true,
        height: '77vh',
        gap: '3px',
        arrowPath: 'm 9.25,34.25 v -2.82 l 5.05,-3.77 5.05,-3.77 c 2.69,0.04 4.81,0.03 7.11,0.04 L 17.38,30.79 9.25,37.08 Z M 16.50,14.16 9.25,8.50 9.25,5.71 9.25,2.92 17,8.95 l 7.75,6.03 3,2.47 3,2.47 -3.5,-0.05 -3.5,-0.05 -7.25,-5.66 z',
    } );
    let mobilesplide = new Splide( '.tl-splide-mobile', {
        mediaQuery: 'min',
        breakPoints: {
            761: {
                destroy: true
            }
        },
        type: 'loop',
        pagination: false,
        gap: '3px',
        arrowPath: 'm 9.25,34.25 v -2.82 l 5.05,-3.77 5.05,-3.77 c 2.69,0.04 4.81,0.03 7.11,0.04 L 17.38,30.79 9.25,37.08 Z M 16.50,14.16 9.25,8.50 9.25,5.71 9.25,2.92 17,8.95 l 7.75,6.03 3,2.47 3,2.47 -3.5,-0.05 -3.5,-0.05 -7.25,-5.66 z',
    } );

    desktopsplide.on('click', (slide, e) => {
        document.querySelector('.tl-termek-img').src = e.target.getAttribute('data-url');
    });
    desktopsplide.mount();

    mobilesplide.mount();

});
