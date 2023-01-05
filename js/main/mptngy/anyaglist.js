document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        regNeeded: false,
        postaleqinv: false,
        login: {
            email: null,
            jelszo: null,
        },
        reg: {
            nev: null,
            email: null,
            password1: null,
            password2: null,
            invoice: {
                nev: null,
                irszam: null,
                varos: null,
                utca: null,
                adoszam: null,
                bankszamla: null
            },
            postal: {
                irszam: null,
                varos: null,
                utca: null,
            },
            szerepkor: null,
            nap1: false,
            nap2: false,
            nap3: false,
            csoportosfizetes: null,
            vipvacsora: false,
            kapcsolatnev: null,
            diak: false,
            mpttag: false,
        },
        szerepkorlist: [],
        selectedSzerepkorIndex: null,

        compileData() {
            return {
                mptngycsoportosfizetes: this.reg.csoportosfizetes,
                mptngykapcsolatnev: this.reg.kapcsolatnev,
                mptngyvipvacsora: this.reg.vipvacsora,
                mptngybankszamlaszam: this.reg.invoice.bankszamla,
                mptngynap1reszvetel: this.reg.nap1,
                mptngynap2reszvetel: this.reg.nap2,
                mptngynap3reszvetel: this.reg.nap3,
                mptngydiak: this.reg.diak,
                mptngympttag: this.reg.mpttag,
                mptngyszerepkor: this.reg.szerepkor,
                nev: this.reg.nev,
                email: this.reg.email,
                jelszo1: this.reg.password1,
                jelszo2: this.reg.password2,
                lirszam: this.reg.postal.irszam,
                lvaros: this.reg.postal.varos,
                lutca: this.reg.postal.utca,
                szlanev: this.reg.invoice.nev,
                irszam: this.reg.invoice.irszam,
                varos: this.reg.invoice.varos,
                utca: this.reg.invoice.utca,
                adoszam: this.reg.invoice.adoszam,
            }
        },
        getLists() {
            fetch(new URL('/szerepkorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szerepkorlist = data;
                });
            this.$watch('selectedSzerepkorIndex', (value) => {
                this.selectSzerepkor(value);
            });
            this.$watch('reg.nev', (value) => {
                if (!this.reg.invoice.nev) {
                    this.reg.invoice.nev = value;
                }
            });
            this.$watch('postaleqinv', (value) => {
                if (value) {
                    this.reg.postal.irszam = this.reg.invoice.irszam;
                    this.reg.postal.varos = this.reg.invoice.varos;
                    this.reg.postal.utca = this.reg.invoice.utca;
                }
            });
        },
        selectSzerepkor(index) {
            this.reg.szerepkor = this.szerepkorlist[index];
        },
        save() {
            fetch(new URL('/regisztracio/ment', location.origin), {
                method: 'POST',
                body: new URLSearchParams(this.compileData())
            })
                .then((response) => response.json())
                .then((respdata) => {
                    if (respdata.url) {
                        location.href = respdata.url;
                    }
                })
                .catch((error) => {
                    alert(error);
                })
                .finally(() => {
                });
        },
        dologin() {
            fetch(new URL('/login/ment', location.origin), {
                method: 'POST',
                body: new URLSearchParams(this.login)
            })
                .then((response) => response.json())
                .then((respdata) => {
                    if (respdata.url) {
                        location.href = respdata.url;
                    }
                })
                .catch((error) => {
                    alert(error);
                })
                .finally(() => {
                });
        }
    }));
});
