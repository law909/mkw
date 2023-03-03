document.addEventListener("alpine:init", () => {
    Alpine.data("adataim", () => ({
        validation: [],
        reg: {
            invcsoportos: null,
            invmaganszemely: null,
            vatstatus: null,
            szlanev: null,
            irszam: null,
            varos: null,
            utca: null,
            adoszam: null,
            mptngybankszamlaszam: null,
            mptngycsoportosfizetes: null,
            mptngykapcsolatnev: null,
            mptngynapreszvetel1: false,
            mptngynapreszvetel2: false,
            mptngynapreszvetel3: false,
            mptngyvipvacsora: false,
            mptngybankett: false,
            mptngynemveszreszt: false,
        },
        rules: {
            szlanev: ['required'],
            adoszam: ['adoszam'],
            irszam: ['required'],
            varos: ['required'],
            utca: ['required'],
            invcsoportos: ['required'],
            invmaganszemely: ['requiredIfCsoportos'],
            mptngynemveszreszt: ['reszvetel'],
        },

        clearErrors() {
            this.validation = {};
        },
        getLists() {
            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');

            Iodine.rule('reszvetel', (value) => {
                return this.reg.mptngynemveszreszt ||
                    this.reg.mptngynapreszvetel1 ||
                    this.reg.mptngynapreszvetel2 ||
                    this.reg.mptngynapreszvetel3 ||
                    this.reg.mptngybankett ||
                    this.reg.mptngyvipvacsora;
            });
            Iodine.setErrorMessage('reszvetel', 'Jelölje meg, hogyan vesz részt');

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

            fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.reg.adoszam = data.adoszam;
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
                });

            this.$watch('reg.nev', (value) => {
                this.reg.szlanev = value;
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
