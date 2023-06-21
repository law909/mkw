document.addEventListener("alpine:init", () => {
    Alpine.data("termeklap", () => ({
        colors: [],
        sizes: [],
        stocks: [],
        selectedSize: null,
        selectedColor: null,
        selectedColorIndex: null,
        selectedSizeIndex: null,
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
            this.$watch('selectedColorIndex', (value) => {
                this.selectColor(this.colors[value]);
            });
            this.$watch('selectedSizeIndex', (value) => {
                this.selectSize(this.sizes[value]);
            });
        },
        selectSize(size) {
            this.canAddToCart = false;
            if (this.selectedColor && size) {
                this.canAddToCart = this.stocks[this.selectedColor.value + size.value];
            }
            this.selectedSize = size;
        },
        selectColor(color) {
            this.canAddToCart = false;
            if (this.selectedSize && color) {
                this.canAddToCart = this.stocks[color.value + this.selectedSize.value];
            }
            this.selectedColor = color;
            let termekimg = document.getElementById('termek-image');
            termekimg.src = this.imagepath + color.largekepurl;
            termekimg.setAttribute('data-zoom', this.imagepath + color.eredetikepurl);
        },
        addToCart() {
            const fm = new FormData();
            fm.append('jax', 4);
            fm.append('id', this.termekid);
            fm.append('color', this.selectedColor.value);
            fm.append('size', this.selectedSize.value);
            fetch('/kosar/add', {
                method: 'POST',
                //headers: {'Content-type': 'application/json'},
                body: fm
            })
                .then((response) => response.json())
                .then((respdata) => {
                    Alpine.store('header').termekdb = respdata.termekdb;
                })
                .catch((error) => {
                    alert(error);
                })
                .finally(() => {
                });
        }
    }));
});

document.addEventListener('DOMContentLoaded', function () {
    let desktopsplide = new Splide('.tl-splide-desktop', {
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
    });
    let mobilesplide = new Splide('.tl-splide-mobile', {
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
    });

    let driftTriggerEl = document.getElementById('termek-image');
    let drift = new Drift(driftTriggerEl, {
        paneContainer: document.getElementById('termek-infobox'),
        inlinePane: false,
    });

    let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
    if (isMobile) {

        const termekfakep = document.getElementById('termekfa-kep');
        if (termekfakep) {
            termekfakep.remove();
        }

        document.querySelector('.filter-opener').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!document.querySelector('.termek-filter').classList.contains('termek-filter-down')) {
                document.querySelector('.termek-filter').classList.add('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.add('dontscroll'));
            } else {
                document.querySelector('.termek-filter').classList.remove('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
            }
        });

        document.querySelector('.filter-closer').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (document.querySelector('.termek-filter').classList.contains('termek-filter-down')) {
                document.querySelector('.termek-filter').classList.remove('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
            }
        });
    }

    desktopsplide.on('click', (slide, e) => {
        const img = document.querySelector('.tl-termek-img');
        img.src = e.target.getAttribute('data-url');
        img.setAttribute('data-zoom', e.target.getAttribute('data-zoom'));
    });
    desktopsplide.mount();

    mobilesplide.mount();

});
