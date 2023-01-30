document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        loadCount: 6,
        loaded: 0,
        showEditor: false,
        validation: {},
        anyaglist: [],
        sajatanyaglist: [],
        sajatanyaglistLoaded: false,
        anyagtipuslist: [],
        datumlist: [],
        temakorlist: [],
        me: {
            nev: null,
        },
        anyag: null,
        szimpozium: false,
        szerzo1unknown: null,
        szerzo2unknown: null,
        szerzo3unknown: null,
        szerzo4unknown: null,
        szerzo5unknown: null,
        rules: {
            cim: ['required'],
            tipus: ['required'],
            szerzo1email: ['optional', 'email'],
            szerzo2email: ['optional', 'email'],
            szerzo3email: ['optional', 'email'],
            szerzo4email: ['optional', 'email'],
        },
        bekuldRules: {
            szerzo5email: ['opponensrequired', 'opponensregistered', 'opponensvstulaj'],
            szerzo1email: ['allszerzoregistered'],
            eloadas1: ['eloadas'],
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
                kezdodatum: null,
                kezdoido: null,
                tipus: null,
                szimpozium: false,
                szerzo1email: null,
                szerzo2email: null,
                szerzo3email: null,
                szerzo4email: null,
                szerzo5email: null,
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
            };
            this.szerzo1unknown = null;
            this.szerzo2unknown = null;
            this.szerzo3unknown = null;
            this.szerzo4unknown = null;
            this.szerzo5unknown = null;

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

            Iodine.rule('eloadas', () => {
                if (this.anyag.szimpozium) {
                    let db = 0;
                    if (Iodine.assertRequired(this.anyag.eloadas1)) {
                        db++;
                    }
                    if (Iodine.assertRequired(this.anyag.eloadas2)) {
                        db++;
                    }
                    if (Iodine.assertRequired(this.anyag.eloadas3)) {
                        db++;
                    }
                    if (Iodine.assertRequired(this.anyag.eloadas4)) {
                        db++;
                    }
                    console.log(this.anyag.eloadas1);
                    return db === 3 || db === 4;
                }
                return true;
            });
            Iodine.setErrorMessage('eloadas', 'Szimpóziumban minimum 3 előadásnak kell lennie');

            Iodine.rule('temakor', () => {
                return Iodine.assertRequired(this.anyag.temakor1) ||
                    Iodine.assertRequired(this.anyag.temakor2) ||
                    Iodine.assertRequired(this.anyag.temakor3);
            });
            Iodine.setErrorMessage('temakor', 'Legalább egy témakört meg kell adni');

            Iodine.rule('kulcsszo', () => {
                let db = 0;
                if (Iodine.assertRequired(this.anyag.kulcsszo1)) {
                    db++;
                }
                if (Iodine.assertRequired(this.anyag.kulcsszo2)) {
                    db++;
                }
                if (Iodine.assertRequired(this.anyag.kulcsszo3)) {
                    db++;
                }
                if (Iodine.assertRequired(this.anyag.kulcsszo4)) {
                    db++;
                }
                if (Iodine.assertRequired(this.anyag.kulcsszo5)) {
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
                    return !this.szerzo5unknown;
                }
                return true;
            });
            Iodine.setErrorMessage('opponensregistered', 'Az opponensnek regisztrálnia kell');

            Iodine.rule('opponensvstulaj', () => this.anyag.szerzo5email !== this.me.email);
            Iodine.setErrorMessage('opponensvstulaj', 'Az elnök és az opponens nem lehet ugyanaz');

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
            fetch(new URL('/datumlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.datumlist = data;
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
            });
        },
        checkSzerzo(num) {
            let url = new URL('/checkpartnerunknown', location.origin),
                f = 'szerzo' + num + 'email',
                t = 'szerzo' + num + 'unknown';
            if (this.anyag[f] && Iodine.assertEmail(this.anyag[f])) {
                url.searchParams.append('email', this.anyag[f]);
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        this[t] = data.unknown;
                    });
            } else {
                this[t] = false;
            }
        },
        logout() {
            location.href = '/logout';
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
