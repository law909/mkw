document.addEventListener("alpine:init", () => {
    Alpine.data("adataim", () => ({
        show_adataim_egyebadatok: true,
        validation: [],
        reg: {
            invcsoportos: null,
            invmaganszemely: null,
            vatstatus: null,
            nev: null,
            szlanev: null,
            irszam: null,
            varos: null,
            utca: null,
            adoszam: null,
            csoportosadoszam: null,
            mptngybankszamlaszam: null,
            mptngycsoportosfizetes: null,
            mptngykapcsolatnev: null,
            mptngynapreszvetel1: false,
            mptngynapreszvetel2: false,
            mptngynapreszvetel3: false,
            mptngyvipvacsora: false,
            mptngybankett: false,
            mptngynemveszreszt: false,
            mptngydiak: false,
            mptngynyugdijas: false,
            mptngyphd: false,
            mptngympttag: false,
            mptngyszerepkor: null,
            mpt_munkahelynev: null,
            jelszo1: null,
            jelszo2: null,
        },
        rules: {
            nev: ['required'],
            szlanev: ['required'],
            adoszam: ['adoszam'],
            irszam: ['required'],
            varos: ['required'],
            utca: ['required'],
            jelszo1: ['passwordLength'],
            jelszo2: ['passwordsSame'],
            invcsoportos: ['required'],
            invmaganszemely: ['requiredIfCsoportos'],
            mptngynemveszreszt: ['reszvetel'],
        },
        szerepkorlist: [],
        selectedSzerepkorIndex: null,
        selectedSzerepkor: null,

        clearErrors() {
            this.validation = {};
        },
        getLists() {
            this.show_adataim_egyebadatok = this.$el.dataset.showAdataimEgyebadatok === 'true';
            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');
            Iodine.setErrorMessage('passwordLength', 'Legalább 10 karakter hosszú legyen');

            Iodine.rule('passwordLength', (value) => {
                if (value) {
                    return value.length >= 10;
                }
                return true;
            });
            Iodine.rule('passwordsSame', (value) => value === this.reg.jelszo1);
            Iodine.setErrorMessage('passwordsSame', 'A két jelszó nem egyezik');

            if (this.show_adataim_egyebadatok) {
                Iodine.rule('reszvetel', (value) => {
                    return this.reg.mptngynemveszreszt ||
                        this.reg.mptngynapreszvetel1 ||
                        this.reg.mptngynapreszvetel2 ||
                        this.reg.mptngynapreszvetel3 ||
                        this.reg.mptngybankett ||
                        this.reg.mptngyvipvacsora;
                });
                Iodine.setErrorMessage('reszvetel', 'Jelölje meg, hogyan vesz részt');
            }

            Iodine.rule('requiredIfCsoportos', (value) => {
                if (this.reg.invcsoportos === '2') {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('requiredIfCsoportos', 'Válassza ki, hogy hogyan fogadja be a számlát');

            Iodine.rule('adoszam', (value) => {
                if (this.reg.invmaganszemely === '2') {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('adoszam', 'Céges fizetés esetén kötelező megadni')

            fetch(new URL('/szerepkorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szerepkorlist = data;
                });

            fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.reg.adoszam = data.adoszam;
                    this.reg.nev = data.nev;
                    this.reg.szlanev = data.szlanev;
                    this.reg.irszam = data.irszam;
                    this.reg.varos = data.varos;
                    this.reg.utca = data.utca;
                    this.reg.vatstatus = data.vatstatus;
                    switch (this.reg.vatstatus) {
                        case 0:
                            this.reg.invcsoportos = "1";
                            break;
                        case 1:
                            this.reg.invcsoportos = "2";
                            this.reg.invmaganszemely = "2";
                            break;
                        case 2:
                            this.reg.invcsoportos = "2";
                            this.reg.invmaganszemely = "1";
                            break;
                    }
                    this.reg.mptngybankszamlaszam = data.mptngybankszamlaszam;
                    this.reg.mptngykapcsolatnev = data.mptngykapcsolatnev;
                    this.reg.mptngycsoportosfizetes = data.mptngycsoportosfizetes;
                    this.reg.mpt_munkahelynev = data.mpt_munkahelynev;
                    this.reg.mptngynemveszreszt = data.mptngynemveszreszt;
                    this.reg.mptngyvipvacsora = data.mptngyvipvacsora;
                    this.reg.mptngybankett = data.mptngybankett;
                    this.reg.mptngynapreszvetel1 = data.mptngynapreszvetel1;
                    this.reg.mptngynapreszvetel2 = data.mptngynapreszvetel2;
                    this.reg.mptngynapreszvetel3 = data.mptngynapreszvetel3;
                    this.reg.mptngynyugdijas = data.mptngynyugdijas;
                    this.reg.mptngydiak = data.mptngydiak;
                    this.reg.mptngyphd = data.mptngyphd;
                    this.reg.mptngympttag = data.mptngympttag;
                    this.reg.mptngyszerepkor = data.mptngyszerepkor;
                });

            this.$watch('reg.nev', (value) => {
                if (!this.reg.szlanev) {
                    this.reg.szlanev = value;
                }
            });
            this.$watch('reg.invmaganszemely', (value) => {
                switch (value) {
                    case "1":
                        this.reg.vatstatus = 2;
                        break;
                    case "2":
                        this.reg.vatstatus = 1;
                        break;
                    default:
                        this.reg.vatstatus = null;
                }
            });
            if (this.show_adataim_egyebadatok) {
                this.$watch('reg.mptngynemveszreszt', (value) => {
                    if (value) {
                        this.reg.mptngynapreszvetel1 = false;
                        this.reg.mptngyvipvacsora = false;
                        this.reg.mptngynapreszvetel2 = false;
                        this.reg.mptngybankett = false;
                        this.reg.mptngynapreszvetel3 = false;
                    }
                });
                this.$watch('reg.mptngynapreszvetel1', (value) => {
                    if (value) {
                        this.reg.mptngynemveszreszt = false;
                    }
                });
                this.$watch('reg.mptngyvipvacsora', (value) => {
                    if (value) {
                        this.reg.mptngynemveszreszt = false;
                    }
                });
                this.$watch('reg.mptngynapreszvetel2', (value) => {
                    if (value) {
                        this.reg.mptngynemveszreszt = false;
                    }
                });
                this.$watch('reg.mptngybankett', (value) => {
                    if (value) {
                        this.reg.mptngynemveszreszt = false;
                    }
                });
                this.$watch('reg.mptngynapreszvetel3', (value) => {
                    if (value) {
                        this.reg.mptngynemveszreszt = false;
                    }
                });
                this.$watch('reg.mptngynyugdijas', (value) => {
                    if (value) {
                        this.reg.mptngydiak = false;
                        this.reg.mptngyphd = false;
                    }
                });
                this.$watch('reg.mptngydiak', (value) => {
                    if (value) {
                        this.reg.mptngynyugdijas = false;
                        this.reg.mptngyphd = false;
                    }
                });
                this.$watch('reg.mptngyphd', (value) => {
                    if (value) {
                        this.reg.mptngynyugdijas = false;
                        this.reg.mptngydiak = false;
                    }
                });
            }
        },
        save() {
            const valid = Iodine.assert(this.reg, this.rules);
            this.clearErrors();
            if (valid.valid) {
                fetch(new URL('/adataim/ment', location.origin), {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(this.reg)
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
            } else {
                this.validation = valid.fields;
                alert('Kérjük javítsa a pirossal jelölt mezőket.');
            }
        },
        cancel() {
            location.href = '/szakmaianyagok';
        }
    }));
});
