document.addEventListener("alpine:init", () => {
    Alpine.data("login", () => ({
        regNeeded: false,
        postaleqinv: false,
        emailFoglalt: false,
        validation: [],
        login: {
            email: null,
            jelszo: null,
        },
        reg: {
            nev: null,
            telefon: null,
            email: null,
            jelszo1: null,
            jelszo2: null,
            invcsoportos: null,
            invmaganszemely: null,
            vatstatus: null,
            szlanev: null,
            irszam: null,
            varos: null,
            utca: null,
            adoszam: null,
            csoportosadoszam: null,
            mptngybankszamlaszam: null,
            lirszam: null,
            lvaros: null,
            lutca: null,
            mptngyszerepkor: null,
            mptngynapreszvetel1: false,
            mptngynapreszvetel2: false,
            mptngynapreszvetel3: false,
            mptngycsoportosfizetes: null,
            mptngyvipvacsora: false,
            mptngybankett: false,
            mptngykapcsolatnev: null,
            mptngydiak: false,
            mptngyphd: false,
            mptngynyugdijas: false,
            mptngympttag: false,
            mpt_munkahelynev: null,
            mptngynemveszreszt: false,
        },
        rules: {
            nev: ['required'],
            telefon: ['required'],
            email: ['required', 'email', 'emailFoglalt'],
            jelszo1: ['required', {rule: 'minLength', param: 10}],
            jelszo2: ['required', 'passwordsSame'],
            szlanev: ['required'],
            adoszam: ['adoszam'],
            irszam: ['required'],
            varos: ['required'],
            utca: ['required'],
            invcsoportos: ['required'],
            invmaganszemely: ['requiredIfCsoportos'],
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
            Iodine.setErrorMessage('minLength', 'Legalább [PARAM] karakter hosszú legyen');

            Iodine.rule('passwordsSame', (value) => value === this.reg.jelszo1);
            Iodine.setErrorMessage('passwordsSame', 'A két jelszó nem egyezik');

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

            Iodine.rule('emailFoglalt', () => {
                return !this.emailFoglalt;
            });
            Iodine.setErrorMessage('emailFoglalt', 'Az email már foglalt')

            if (this.show_adataim_egyebadatok) {
                this.rules.mptngyszerepkor = ['required'];
                this.rules.mptngynemveszreszt = ['reszvetel'];
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

            fetch(new URL('/szerepkorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szerepkorlist = data;
                });
            this.$watch('reg.nev', (value) => {
                this.reg.szlanev = value;
            });
            this.$watch('postaleqinv', (value) => {
                if (value) {
                    this.reg.lirszam = this.reg.irszam;
                    this.reg.lvaros = this.reg.varos;
                    this.reg.lutca = this.reg.utca;
                }
            });
            this.$watch('reg.mptngyphd', (value) => {
                if (value) {
                    this.reg.mptngynyugdijas = false;
                    this.reg.mptngydiak = false;
                }
            })
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
            }
        },
        checkEmail() {
            let url = new URL('/checkemail', location.origin);
            if (Iodine.assertEmail(this.reg.email)) {
                url.searchParams.append('email', this.reg.email);
                fetch(url, {
                    method: 'POST',
                    body: new URLSearchParams({email: this.reg.email})
                })
                    .then((response) => response.json())
                    .then((data) => {
                        this.emailFoglalt = data.hibas;
                    });
            }
        },
        save() {
            const valid = Iodine.assert(this.reg, this.rules);
            this.clearErrors();
            if (valid.valid) {
                fetch(new URL('/regisztracio/ment', location.origin), {
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
