document.addEventListener("alpine:init", () => {
    Alpine.data("login", () => ({
        regNeeded: false,
        postaleqinv: false,
        validationErrors: [],
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
            szlanev: null,
            irszam: null,
            varos: null,
            utca: null,
            adoszam: null,
            mptngybankszamla: null,
            lirszam: null,
            lvaros: null,
            lutca: null,
            mptngyszerepkor: null,
            mptngynapreszvetel1: false,
            mptngynapreszvetel2: false,
            mptngynapreszvetel3: false,
            mptngycsoportosfizetes: null,
            mptngyvipvacsora: false,
            mptngykapcsolatnev: null,
            mptngydiak: false,
            mptngynyugdijas: false,
            mptngympttag: false,
        },
        rules: {
            nev: ['required'],
            telefon: ['required'],
            email: ['required', 'email'],
            jelszo1: ['required', 'passwordsSame'],
            jelszo2: ['required'],
            szlanev: ['required'],
            irszam: ['required'],
            varos: ['required'],
            utca: ['required'],
            mptngyszerepkor: ['required'],
            invcsoportos: ['required'],
            invmaganszemely: ['requiredIfCsoportos'],
        },
        selectors: {
            nev: '#regNevEdit',
            telefon: '#regTelEdit',
            email: '#regEmailEdit',
            jelszo1: '#regPw1Edit',
            jelszo2: '#regPw2Edit',
            szlanev: '#regInvNevEdit',
            irszam: '#regInvIrszamEdit',
            varos: '#regInvVarosEdit',
            utca: '#regInvUtcaEdit',
            mptngyszerepkor: '.js-szerepkor',
            invcsoportos: '.js-invcsoportos',
            invmaganszemely: '.js-invmaganszemely'
        },
        errorClasses: {
            mptngyszerepkor: 'error-border',
            invcsoportos: 'error-border',
            invmaganszemely: 'error-border',
        },
        szerepkorlist: [],
        selectedSzerepkorIndex: null,
        selectedSzerepkor: null,

        clearErrors() {
            this.validationErrors = [];
            for (const [key, value] of Object.entries(this.selectors)) {
                const els = document.querySelectorAll(value);
                let errorClass;
                if (Object.keys(this.errorClasses).find(el => el === key) !== key) {
                    errorClass = 'error';
                } else {
                    errorClass = this.errorClasses[key];
                }
                els.forEach((el) => {
                    el.classList.remove(errorClass);
                });
            }
        },
        getLists() {
            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.rule('passwordsSame', (value) => value === this.reg.jelszo2);
            Iodine.setErrorMessage('passwordsSame', 'A két jelszó nem egyezik');

            Iodine.rule('requiredIfCsoportos', (value) => {
                if (this.reg.invcsoportos === '2') {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('requiredIfCsoportos', 'Válassza ki, hogy magánszemélyként vagy cégként fogadja be a számlát');

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
            this.$watch('reg.mptngynyugdijas', (value) => {
                if (value) {
                    this.reg.mptngydiak = false;
                }
            });
            this.$watch('reg.mptngydiak', (value) => {
                if (value) {
                    this.reg.mptngynyugdijas = false;
                }
            });
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
                for (const [key, value] of Object.entries(valid.fields)) {
                    if (!value.valid) {
                        const els = document.querySelectorAll(this.selectors[key]);
                        let errorClass;
                        if (Object.keys(this.errorClasses).find(el => el === key) !== key) {
                            errorClass = 'error';
                        } else {
                            errorClass = this.errorClasses[key];
                        }
                        els.forEach((el) => {
                            el.classList.add(errorClass);
                        });
                    }
                }
                this.validationErrors = valid.fields;
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
