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
            adoszam: null,
        },
        selectedSzallitasimod: null,
        selectedFizetesimod: null,
        selectedSzallitasimodIndex: null,
        selectedFizetesimodIndex: null,
        areacodes: [],
        tetellist: [],
        szallmodlist: [],
        webshopmessage: null,
        couriermessage: null,
        akciohirlevel: null,
        ujdonsaghirlevel: null,
        aszfready: null,
        cegesvasarlo: false,
        getLists() {
            fetch(new URL('/checkout/gettetellistdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.tetellist = data;
                });
            fetch(new URL('/checkout/getszallmodfizmodlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szallmodlist = data;
                    this.selectedSzallitasimodIndex = 0;
                });
            this.$watch('selectedSzallitasimodIndex', (value) => {
                this.selectSzallitasimod(value);
                this.selectedFizetesimodIndex = this.selectedSzallitasimod.selectedFizetesimodIndex;
            });
            this.$watch('selectedFizetesimodIndex', (value) => {
                this.selectFizetesimod(value);
            });
            this.$watch('inveqdel', (value) => {
                if (value) {
                    this.invoice.name = this.delivery.name;
                    this.invoice.postalcode = this.delivery.postalcode;
                    this.invoice.city = this.delivery.city;
                    this.invoice.street = this.delivery.street;
                }
            });
            this.$watch('contact.firstName', (value) => {
                if (!this.delivery.name) {
                    this.delivery.name = this.contact.lastName + ' ' + this.contact.firstName;
                }
            });
        },
        selectSzallitasimod(szallmodindex) {
            this.selectedSzallitasimod = this.szallmodlist[szallmodindex];
        },
        selectFizetesimod(fizmodindex) {
            this.selectedFizetesimod = this.selectedSzallitasimod.fizmodlist[fizmodindex];
            this.szallmodlist[this.selectedSzallitasimodIndex].selectedFizetesimodIndex = fizmodindex;
        }
    }));
});
