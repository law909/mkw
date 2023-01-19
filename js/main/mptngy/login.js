document.addEventListener("alpine:init", () => {
    Alpine.data("login", () => ({
        regNeeded: false,
        postaleqinv: false,
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
            mptngyszerepkor: 'input[name="szerepkor"]',
        },
        szerepkorlist: [],
        selectedSzerepkorIndex: null,
        selectedSzerepkor: null,

        clearErrors() {
            Object.values(this.selectors).forEach((val) => {
                const els = document.querySelectorAll(val);
                els.forEach((el) => {
                    el.classList.remove('error');
                });
            });
        },
        getLists() {
            Iodine.rule('passwordsSame', (value) => value === this.reg.jelszo2);
            Iodine.setErrorMessage('passwordsSame', 'A két jelszó nem egyezik');

            fetch(new URL('/szerepkorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szerepkorlist = data;
                });
            this.$watch('reg.nev', (value) => {
                if (!this.reg.szlanev) {
                    this.reg.szlanev = value;
                }
            });
            this.$watch('postaleqinv', (value) => {
                if (value) {
                    this.reg.lirszam = this.reg.irszam;
                    this.reg.lvaros = this.reg.varos;
                    this.reg.lutca = this.reg.utca;
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
                        els.forEach((el) => {
                            el.classList.add('error');
                        });
                    }
                }
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
