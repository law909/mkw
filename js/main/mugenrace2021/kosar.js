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
        calcSum(i) {
            if (i !== undefined && this.tetellist[i].mennyiseg < 1) {
                this.tetellist[i].mennyiseg = 1;
            }
            this.sum = 0;
            this.tetellist.forEach((tetel) => {
                tetel.brutto = tetel.bruttoegysar * tetel.mennyiseg;
                this.sum += tetel.brutto;
            });
        },
        refreshData(i) {
            if (i !== undefined && this.tetellist[i].mennyiseg < 1) {
                this.tetellist[i].mennyiseg = 1;
            }
            console.log(this.tetellist[i].editlink);
            fetch(new URL(this.tetellist[i].editlink, location.origin), {
                method: 'POST',
                body: new URLSearchParams({
                    id: this.tetellist[i].id,
                    mennyiseg: this.tetellist[i].mennyiseg
                })
            })
                .then((response) => response.json())
                .then((data) => {
                    this.sum = data.kosarertek;
                    console.log(data);
                });
        },
    }));
});
