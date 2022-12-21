document.addEventListener("alpine:init", () => {
    Alpine.data("kosar", () => ({
        tetellist: [],
        valutanem: null,
        szallitasiido: null,
        sum: null,
        getLists() {
            fetch(new URL('/kosar/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.tetellist = data.tetellista;
                    this.szallitasiido = data.szallitasiido;
                    this.valutanem = data.valutanem;
                    this.calcSum();
                });
        },
        calcSum() {
            this.tetellist.forEach((tetel) => {
                this.sum += tetel.brutto;
            });
        }
    }));
});
