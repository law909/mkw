document.addEventListener("alpine:init", () => {
    Alpine.data("adataim", () => ({
        show_adataim_egyebadatok: true,
        validation: [],
        firstLoad: true,
        loaded: false,
        egyetemlist: [],
        karlist: [],
        reg: {
            invcsoportos: null,
            invmaganszemely: null,
            vatstatus: null,
            nev: null,
            nevelotag: null,
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
            mptngyegyetem: null,
            mptngykar: null,
            mptngyegyetemegyeb: null,
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
            mptngyegyetem: ['egyetem'],
        },
        szerepkorlist: [],
        selectedSzerepkorIndex: null,
        selectedSzerepkor: null,

        init() {
            this.setRules();
            this.setWatchers();
            this.getLists();
        },
        clearErrors() {
            this.validation = {};
        },
        setRules() {
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

            Iodine.rule('egyetem', () => {
                return !((!this.reg.mptngyegyetem || !this.reg.mptngykar) && !this.reg.mptngyegyetemegyeb);

            });
            Iodine.setErrorMessage('egyetem', 'Egyetemet és kart vagy "Egyetem egyéb"-t meg kell adni');
        },
        setWatchers() {
            this.$watch('reg.mptngyegyetem', (value, oldvalue) => {
                if (value) {
                    let url = new URL('/karlist', location.origin);
                    url.searchParams.append('egyetem', value);
                    fetch(url)
                        .then((response) => response.json())
                        .then((data) => {
                            this.karlist = data;
                            if (!this.firstLoad) {
                                this.reg.mptngykar = null;
                            }
                            let kar = this.reg.mptngykar;
                            this.reg.mptngykar = null;
                            this.reg.mptngykar = kar;
                            this.firstLoad = false;
                        });
                } else {
                    this.karlist = [];
                    this.reg.mptngykar = null;
                }
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
        getLists() {
            this.show_adataim_egyebadatok = this.$el.dataset.showAdataimEgyebadatok === 'true';

            const szerepkorlistfetch = fetch(new URL('/szerepkorlist', location.origin))
                .then((response) => response.json());
            const partnerfetch = fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json());
            const egyetemlistfetch = fetch(new URL('/egyetemlist', location.origin)).then((response) => response.json());

            Promise.all([szerepkorlistfetch, partnerfetch, egyetemlistfetch])
                .then(([szerepkorlistdata, partnerdata, egyetemlistdata]) => {
                    this.egyetemlist = egyetemlistdata;
                    this.szerepkorlist = szerepkorlistdata;

                    this.reg.mptngyegyetem = partnerdata.mptngyegyetem ? partnerdata.mptngyegyetem.toString() : null;
                    this.$nextTick(() => {
                        this.reg.mptngykar = partnerdata.mptngykar ? partnerdata.mptngykar.toString() : null;
                    });

                    this.reg.adoszam = partnerdata.adoszam;
                    this.reg.nev = partnerdata.nev;
                    this.reg.nevelotag = partnerdata.nevelotag;
                    this.reg.szlanev = partnerdata.szlanev;
                    this.reg.irszam = partnerdata.irszam;
                    this.reg.varos = partnerdata.varos;
                    this.reg.utca = partnerdata.utca;
                    this.reg.vatstatus = partnerdata.vatstatus;
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
                    this.reg.mptngybankszamlaszam = partnerdata.mptngybankszamlaszam;
                    this.reg.mptngykapcsolatnev = partnerdata.mptngykapcsolatnev;
                    this.reg.mptngycsoportosfizetes = partnerdata.mptngycsoportosfizetes;
                    this.reg.mptngynemveszreszt = partnerdata.mptngynemveszreszt;
                    this.reg.mptngyvipvacsora = partnerdata.mptngyvipvacsora;
                    this.reg.mptngybankett = partnerdata.mptngybankett;
                    this.reg.mptngynapreszvetel1 = partnerdata.mptngynapreszvetel1;
                    this.reg.mptngynapreszvetel2 = partnerdata.mptngynapreszvetel2;
                    this.reg.mptngynapreszvetel3 = partnerdata.mptngynapreszvetel3;
                    this.reg.mptngynyugdijas = partnerdata.mptngynyugdijas;
                    this.reg.mptngydiak = partnerdata.mptngydiak;
                    this.reg.mptngyphd = partnerdata.mptngyphd;
                    this.reg.mptngympttag = partnerdata.mptngympttag;
                    this.reg.mptngyszerepkor = partnerdata.mptngyszerepkor;
                    this.reg.mptngyegyetemegyeb = partnerdata.mptngyegyetemegyeb;

                    this.loaded = true;
                })
                .catch((error) => {
                    console.error(error);
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
            this.firstLoad = true;
            this.loaded = false;
            location.href = '/szakmaianyagok';
        }
    }));
});
