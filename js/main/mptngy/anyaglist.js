document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        konyvkiadashoTol: '2021.09',
        konyvkiadashoIg: '2023.06',
        loadCount: 5,
        loaded: 0,
        showEditor: false,
        validation: {},
        anyaglist: [],
        sajatanyaglist: [],
        sajatanyaglistLoaded: false,
        anyagtipuslist: [],
        temakorlist: [],
        me: {
            nev: null,
        },
        anyag: null,
        szimpozium: false,
        konyvbemutato: false,
        szerzo1unknown: null,
        szerzo2unknown: null,
        szerzo3unknown: null,
        szerzo4unknown: null,
        szerzo5unknown: null,
        szerzo6unknown: null,
        szerzo7unknown: null,
        szerzo8unknown: null,
        szerzo9unknown: null,
        szerzo10unknown: null,
        opponensunknown: null,
        beszelgetopartnerunknown: null,
        rules: {
            cim: ['required'],
            tipus: ['required'],
            szerzo1email: ['optional', 'email'],
            szerzo2email: ['optional', 'email'],
            szerzo3email: ['optional', 'email'],
            szerzo4email: ['optional', 'email'],
            szerzo5email: ['optional', 'email'],
            szerzo6email: ['optional', 'email'],
            szerzo7email: ['optional', 'email'],
            szerzo8email: ['optional', 'email'],
            szerzo9email: ['optional', 'email'],
            szerzo10email: ['optional', 'email'],
            konyvkiadasho: ['konyvkiadashoreal'],
        },
        bekuldRules: {
            opponensemail: ['opponensrequired', 'opponensregistered', 'opponensvstulaj'],
            beszelgetopartneremail: ['beszelgetorequired', 'beszelgetoregistered', 'beszelgetovstulaj'],
            konyvkiadasho: ['konyvkiadashorequired', 'konyvkiadashoreal', 'konyvkiadashorange'],
            szerzo1email: ['allszerzoregistered'],
            eloadas1: ['eloadas', 'eloadasnotsame'],
            tartalom: [
                'required',
                {rule: 'minLength', param: 1500},
                {rule: 'maxLength', param: 3000}
            ],
            temakor1: ['temakor'],
            kulcsszo1: ['kulcsszo'],
        },
        initVars() {
            this.anyag = {
                id: null,
                cim: null,
                tulajdonosnev: null,
                tipus: null,
                szimpozium: false,
                opponensemail: null,
                szerzo1email: null,
                szerzo2email: null,
                szerzo3email: null,
                szerzo4email: null,
                szerzo5email: null,
                szerzo6email: null,
                szerzo7email: null,
                szerzo8email: null,
                szerzo9email: null,
                szerzo10email: null,
                beszelgetopartneremail: null,
                eloadas1: null,
                eloadas2: null,
                eloadas3: null,
                eloadas4: null,
                eloadas5: null,
                tartalom: '',
                kulcsszo1: null,
                kulcsszo2: null,
                kulcsszo3: null,
                kulcsszo4: null,
                kulcsszo5: null,
                vegleges: false,
                temakor1: null,
                temakor2: null,
                temakor3: null,
                konyvkiadasho: '',
                egyebszerzok: '',
            };
            this.opponensunknown = null;
            this.szerzo1unknown = null;
            this.szerzo2unknown = null;
            this.szerzo3unknown = null;
            this.szerzo4unknown = null;
            this.szerzo5unknown = null;
            this.szerzo6unknown = null;
            this.szerzo7unknown = null;
            this.szerzo8unknown = null;
            this.szerzo9unknown = null;
            this.szerzo10unknown = null;
            this.beszelgetopartnerunknown = null;

        },
        clearErrors() {
            this.validation = {};
        },
        createNew() {
            this.anyag.tulajdonosnev = this.me.nev;
            this.showEditor = true;
        },
        edit(id) {
            const a = this.anyaglist.find(el => el.id === parseInt(id));
            Object.keys(this.anyag).forEach(key => {
                this.anyag[key] = a[key];
            });
            this.anyag.tulajdonosnev = this.me.nev;
            this.szerzo1unknown = !a.szerzo1registered;
            this.szerzo2unknown = !a.szerzo2registered;
            this.szerzo3unknown = !a.szerzo3registered;
            this.szerzo4unknown = !a.szerzo4registered;
            this.szerzo5unknown = !a.szerzo5registered;
            this.szerzo6unknown = !a.szerzo6registered;
            this.szerzo7unknown = !a.szerzo7registered;
            this.szerzo8unknown = !a.szerzo8registered;
            this.szerzo9unknown = !a.szerzo9registered;
            this.szerzo10unknown = !a.szerzo10registered;
            this.opponensunknown = !a.opponensregistered;
            this.beszelgetopartnerunknown = !a.beszelgetopartnerregistered;
            this.showEditor = true;
        },
        cancel() {
            this.showEditor = false;
            this.getLists();
        },
        getLists() {
            this.initVars();

            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');
            Iodine.setErrorMessage('minLength', 'Legalább [PARAM] karakter hosszú legyen');
            Iodine.setErrorMessage('maxLength', 'Legfeljebb [PARAM] karakter hosszú legyen');

            Iodine.rule('konyvkiadashorequired', (value) => {
                if (this.anyag.konyvbemutato) {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('konyvkiadashorequired', 'Kötelező kitölteni');

            Iodine.rule('konyvkiadashoreal', (value) => {
                if (value) {
                    const evho = value.split('.');
                    return evho[1] >= '01' && evho[1] <= '12';
                }
                return true;
            });
            Iodine.setErrorMessage('konyvkiadashoreal', 'Létező hónapot adjon meg');
            Iodine.rule('konyvkiadashorange', (value) => {
                if (this.anyag.konyvbemutato) {
                    return value >= this.konyvkiadashoTol && value <= this.konyvkiadashoIg;
                }
                return true;
            });
            Iodine.setErrorMessage('konyvkiadashorange', this.konyvkiadashoTol + ' - ' + this.konyvkiadashoIg + ' között kell lennie');

            Iodine.rule('eloadas', () => {
                if (this.anyag.szimpozium) {
                    let db = 0;
                    if (this.anyag.eloadas1) {
                        db++;
                    }
                    if (this.anyag.eloadas2) {
                        db++;
                    }
                    if (this.anyag.eloadas3) {
                        db++;
                    }
                    if (this.anyag.eloadas4) {
                        db++;
                    }
                    return db === 3 || db === 4;
                }
                return true;
            });
            Iodine.setErrorMessage('eloadas', 'Szimpóziumban minimum 3 előadásnak kell lennie');

            Iodine.rule('eloadasnotsame', () => {
                if (this.anyag.szimpozium) {
                    let eloadasok = {};
                    ret = true;

                    if (this.anyag.eloadas1) {
                        eloadasok[parseInt(this.anyag.eloadas1, 10)] = 0;
                    }
                    if (this.anyag.eloadas2) {
                        eloadasok[parseInt(this.anyag.eloadas2, 10)] = 0;
                    }
                    if (this.anyag.eloadas3) {
                        eloadasok[parseInt(this.anyag.eloadas3, 10)] = 0;
                    }
                    if (this.anyag.eloadas4) {
                        eloadasok[parseInt(this.anyag.eloadas4, 10)] = 0;
                    }

                    if (this.anyag.eloadas1) {
                        eloadasok[parseInt(this.anyag.eloadas1, 10)]++;
                    }
                    if (this.anyag.eloadas2) {
                        eloadasok[parseInt(this.anyag.eloadas2, 10)]++;
                    }
                    if (this.anyag.eloadas3) {
                        eloadasok[parseInt(this.anyag.eloadas3, 10)]++;
                    }
                    if (this.anyag.eloadas4) {
                        eloadasok[parseInt(this.anyag.eloadas4, 10)]++;
                    }
                    Object.values(eloadasok).forEach(value => {
                        if (value > 1) {
                            ret = false;
                        }
                    });
                    return ret;
                }
                return true;
            });
            Iodine.setErrorMessage('eloadasnotsame', 'Az előadásoknak különbözniük kell');

            Iodine.rule('temakor', () => {
                return (this.anyag.temakor1) ||
                    (this.anyag.temakor2) ||
                    (this.anyag.temakor3);
            });
            Iodine.setErrorMessage('temakor', 'Legalább egy témakört meg kell adni');

            Iodine.rule('kulcsszo', () => {
                let db = 0;
                if (this.anyag.kulcsszo1) {
                    db++;
                }
                if (this.anyag.kulcsszo2) {
                    db++;
                }
                if (this.anyag.kulcsszo3) {
                    db++;
                }
                if (this.anyag.kulcsszo4) {
                    db++;
                }
                if (this.anyag.kulcsszo5) {
                    db++;
                }
                return db >= 3;
            });
            Iodine.setErrorMessage('kulcsszo', 'Legalább 3 kulcsszót meg kell adni');

            Iodine.rule('opponensrequired', (value) => {
                if (this.anyag.szimpozium) {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('opponensrequired', 'Kötelező kitölteni');

            Iodine.rule('opponensregistered', () => {
                if (this.anyag.szimpozium) {
                    return !this.opponensunknown;
                }
                return true;
            });
            Iodine.setErrorMessage('opponensregistered', 'Az opponensnek regisztrálnia kell');

            Iodine.rule('opponensvstulaj', () => this.anyag.opponensemail !== this.me.email);
            Iodine.setErrorMessage('opponensvstulaj', 'Az elnök és az opponens nem lehet ugyanaz');

            Iodine.rule('beszelgetorequired', (value) => {
                if (this.anyag.konyvbemutato) {
                    return Iodine.assertRequired(value);
                }
                return true;
            });
            Iodine.setErrorMessage('beszelgetorequired', 'Kötelező kitölteni');

            Iodine.rule('beszelgetoregistered', () => {
                if (this.anyag.konyvbemutato) {
                    return !this.beszelgetopartnerunknown;
                }
                return true;
            });
            Iodine.setErrorMessage('beszelgetoregistered', 'A beszélgetőpartnernek regisztrálnia kell');

            Iodine.rule('beszelgetovstulaj', () => this.anyag.beszelgetopartneremail !== this.me.email);
            Iodine.setErrorMessage('beszelgetovstulaj', 'A beszélgetőpartner nem lehet saját maga');

            Iodine.rule('allszerzoregistered', () => {
                let ret = true;
                if (this.anyag.szerzo1email && this.szerzo1unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo2email && this.szerzo2unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo3email && this.szerzo3unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo4email && this.szerzo4unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo5email && this.szerzo5unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo6email && this.szerzo6unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo7email && this.szerzo7unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo8email && this.szerzo8unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo9email && this.szerzo9unknown) {
                    ret = false;
                }
                if (this.anyag.szerzo10email && this.szerzo10unknown) {
                    ret = false;
                }
                return ret;
            });
            Iodine.setErrorMessage('allszerzoregistered', 'Minden szerzőnek regisztrálnia kell');

            fetch(new URL('/anyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyaglist = data;
                    this.loaded++;
                });
            fetch(new URL('/temakorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.temakorlist = data;
                    this.loaded++;
                });
            fetch(new URL('/sajatanyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.sajatanyaglist = data;
                    this.sajatanyaglistLoaded = true;
                    this.loaded++;
                });
            fetch(new URL('/szakmaianyagtipuslist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyagtipuslist = data;
                    this.loaded++;
                });
            fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.me = data;
                    this.anyag.tulajdonosnev = this.me.nev;
                    this.loaded++;
                });

            this.$watch('anyag.tipus', (value) => {
                const atl = this.anyagtipuslist.find(el => el.id === parseInt(value));
                this.szimpozium = (atl && atl.szimpozium);
                this.anyag.szimpozium = (atl && atl.szimpozium);
                this.konyvbemutato = (atl && atl.konyvbemutato);
                this.anyag.konyvbemutato = (atl && atl.konyvbemutato);
            });
        },
        checkSzerzo(num) {
            let url = new URL('/checkpartnerunknown', location.origin),
                f = 'szerzo' + num + 'email',
                t = 'szerzo' + num + 'unknown';
            if (num === 0) {
                f = 'beszelgetopartneremail';
                t = 'beszelgetopartnerunknown';
            }
            if (num === -1) {
                f = 'opponensemail';
                t = 'opponensunknown';
            }
            if (this.anyag[f] && Iodine.assertEmail(this.anyag[f])) {
                url.searchParams.append('email', this.anyag[f]);
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        this.validation[f] = {
                            'valid': true,
                            'error': null,
                        };
                        this[t] = data.unknown;
                    });
            } else {
                this[t] = false;
            }
        },
        logout() {
            location.href = '/logout';
        },
        adataim() {
            location.href = '/adataim';
        },
        save(send = false) {
            let rules = this.rules;
            if (send) {
                rules = {...this.rules, ...this.bekuldRules}
            }
            const valid = Iodine.assert(this.anyag, rules);

            this.clearErrors();

            if (valid.valid) {
                if (send) {
                    this.anyag.vegleges = true;
                }
                fetch(new URL('/szakmaianyag/ment', location.origin), {
                    method: 'POST',
                    body: new URLSearchParams(this.anyag)
                })
                    .then((response) => response.json())
                    .then((respdata) => {
                        if (respdata.success) {
                            this.showEditor = false;
                            this.getLists();
                        } else {
                            this.validation = respdata.fields;
                            alert('Kérjük javítsa a pirossal jelölt mezőket.');
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
    }));
});
