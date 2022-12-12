document.addEventListener("alpine:init", () => {
    Alpine.data("checkout", () => ({
        regNeeded: "1",
        inveqdel: false,
        login: {
            email: null,
            password: null,
        },
        contact: {
            lastName: null,
            firstName: null,
            phone: null,
        },
        email: null,
        password1: null,
        password2: null,
        delivery: {
            name: null,
            postalcode: null,
            city: null,
            street: null,
        },
        invoice: {
            name: null,
            postalcode: null,
            city: null,
            street: null,
        },
        areacodes: [],
        init() {

        },
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
