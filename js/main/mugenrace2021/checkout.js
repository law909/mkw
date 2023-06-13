document.addEventListener("alpine:init", () => {
    Alpine.data("checkout", () => ({
        regNeeded: "1",
        login: {
            email: null,
            jelszo: null,
        },
        loginRules: {
            email: ['required', 'email'],
            jelszo: ['required'],
        },
        loginValidation: {},
        validation: {},
        data: {
            inveqdel: false,
            vezeteknev: null,
            keresztnev: null,
            telefon: null,
            email: null,
            password1: null,
            password2: null,
            szallnev: null,
            szallirszam: null,
            szallvaros: null,
            szallutca: null,
            szallhazszam: null,
            adoszam: null,
            szlanev: null,
            irszam: null,
            varos: null,
            utca: null,
            hazszam: null,
            webshopmessage: null,
            couriermessage: null,
            akciohirlevel: null,
            ujdonsaghirlevel: null,
            cegesvasarlo: false,
            aszfready: null,
        },
        selectedSzallitasimod: null,
        selectedFizetesimod: null,
        selectedSzallitasimodIndex: null,
        selectedFizetesimodIndex: null,
        dataRules: {
            vezeteknev: ['required'],
            keresztnev: ['required'],
            telefon: ['required'],
            email: ['required', 'email'],
            szallnev: ['required'],
            szallirszam: ['required'],
            szallvaros: ['required'],
            szallutca: ['required'],
            adoszam: ['adoszam'],
            szlanev: ['required'],
            irszam: ['required'],
            varos: ['required'],
            utca: ['required'],
            aszfready: ['required'],
        },
        areacodes: [],
        tetellist: [],
        szallmodlist: [],
        init() {
            this.getLists();
            this.$watch('selectedSzallitasimodIndex', (value) => {
                this.selectSzallitasimod(value);
                this.selectedFizetesimodIndex = this.selectedSzallitasimod.selectedFizetesimodIndex;
            });
            this.$watch('selectedFizetesimodIndex', (value) => {
                this.selectFizetesimod(value);
            });
            this.$watch('data.inveqdel', (value) => {
                if (value) {
                    this.data.szlanev = this.data.szallnev;
                    this.data.irszam = this.data.szallirszam;
                    this.data.varos = this.data.szallvaros;
                    this.data.utca = this.data.szallutca;
                    this.data.hazszam = this.data.szallhazszam;
                }
            });
            this.$watch('data.keresztnev', (value) => {
                if (!this.data.szallnev) {
                    this.data.szallnev = this.data.vezeteknev + ' ' + this.data.keresztnev;
                }
            });
        },
        getLists() {
            this.loadTetelList();
            fetch(new URL('/checkout/getszallmodfizmodlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szallmodlist = data;
                    this.selectedSzallitasimodIndex = 0;
                });
            this.loadPartnerData();
        },
        loadPartnerData() {
            fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    Object.assign(this.data, data);
                });
        },
        loadTetelList() {
            let geturl = new URL('/checkout/gettetellistdata', location.origin);
            if (this.selectedSzallitasimod) {
                geturl.searchParams.append('szallitasimod', this.selectedSzallitasimod.id);
            }
            if (this.selectedFizetesimod) {
                geturl.searchParams.append('fizmod', this.selectedFizetesimod.id);
            }
            fetch(geturl)
                .then((response) => response.json())
                .then((data) => {
                    this.tetellist = data;
                });
        },
        selectSzallitasimod(szallmodindex) {
            this.selectedSzallitasimod = this.szallmodlist[szallmodindex];
        },
        selectFizetesimod(fizmodindex) {
            this.selectedFizetesimod = this.selectedSzallitasimod.fizmodlist[fizmodindex];
            this.szallmodlist[this.selectedSzallitasimodIndex].selectedFizetesimodIndex = fizmodindex;
        },
        clearLoginErrors() {
            this.loginValidation = {};
        },
        clearErrors() {
            this.validation = {};
        },
        dologin() {
            const valid = Iodine.assert(this.login, this.loginRules);

            this.clearLoginErrors();

            if (valid.valid) {
                this.login.c = 'c';
                fetch(new URL('/login/ment', location.origin), {
                    method: 'POST',
                    body: new URLSearchParams(this.login)
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.loginerror) {
                            this.loginValidation.email = {
                                valid: false,
                                error: data.errormsg
                            };
                        } else {
                            this.loadPartnerData();
                        }
                    });
            } else {
                this.loginValidation = valid.fields;
            }
        },
        save() {
            const valid = Iodine.assert(this.data, this.dataRules);

            this.clearErrors();

            if (valid.valid) {
                fetch(new URL('/checkout/ment', location.origin), {
                    method: 'POST',
                    body: new URLSearchParams(this.data)
                })
                    .then((response) => response.json())
                    .then((data) => {

                    });
            } else {
                this.validation = valid.fields;
            }
        }
    }));
});
