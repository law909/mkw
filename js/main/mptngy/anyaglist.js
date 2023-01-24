document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        loadCount: 6,
        loaded: 0,
        showEditor: false,
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
            szerzo5email: ['optional', 'email'],
            eloadas1: ['eloadas'],
            eloadas2: ['eloadas'],
            eloadas3: ['eloadas'],
            eloadas4: ['eloadas'],
            eloadas5: ['eloadas'],
            tartalom: [
                'required',
                {rule: 'minLength', param: 1500},
                {rule: 'maxLength', param: 3000}
            ],
            temakor1: ['temakor'],
            temakor2: ['temakor'],
            temakor3: ['temakor'],
        },
        selectors: {
            cim: '#cimEdit',
            tipus: '#tipusEdit',
            szerzo1email: '#szerzo1Edit',
            szerzo2email: '#szerzo2Edit',
            szerzo3email: '#szerzo3Edit',
            szerzo4email: '#szerzo4Edit',
            szerzo5email: '#szerzo5Edit',
            eloadas1: '#eloadas1Edit',
            eloadas2: '#eloadas2Edit',
            eloadas3: '#eloadas3Edit',
            eloadas4: '#eloadas4Edit',
            eloadas5: '#eloadas5Edit',
            tartalom: '#tartalomEdit',
            temakor1: '#temakor1Edit',
            temakor2: '#temakor2Edit',
            temakor3: '#temakor3Edit',
        },
        initVars() {
            this.anyag = {
                id: null,
                cim: null,
                tulajdonosnev: null,
                kezdodatum: null,
                kezdoido: null,
                tipus: null,
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
            Object.values(this.selectors).forEach((val) => {
                const els = document.querySelectorAll(val);
                els.forEach((el) => {
                    el.classList.remove('error');
                });
            });
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

            Iodine.rule('eloadas', () => {
                if (this.szimpozium) {
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
                    if (Iodine.assertRequired(this.anyag.eloadas5)) {
                        db++;
                    }
                    return db === 4 || db === 5;
                }
                return true;
            });
            Iodine.setErrorMessage('eloadas', '');

            Iodine.rule('temakor', () => {
                return Iodine.assertRequired(this.anyag.temakor1) ||
                    Iodine.assertRequired(this.anyag.temakor2) ||
                    Iodine.assertRequired(this.anyag.temakor3);
            });
            Iodine.setErrorMessage('temakor', '');

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
            Iodine.setErrorMessage('kulcsszo', '');

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
            });
        },
        checkSzerzo(num) {
            let url = new URL('/checkpartnerunknown', location.origin),
                f = 'szerzo' + num + 'email',
                t = 'szerzo' + num + 'unknown';
            if (this.anyag[f]) {
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
        send() {
            this.anyag.vegleges = true;
            this.save();
        },
        save() {
            const valid = Iodine.assert(this.anyag, this.rules);
            this.clearErrors();
            if (valid.valid) {
                fetch(new URL('/szakmaianyag/ment', location.origin), {
                    method: 'POST',
                    body: new URLSearchParams(this.anyag)
                })
                    .then((response) => response.json())
                    .then((respdata) => {
                        if (respdata.result === 'ok') {
                            this.showEditor = false;
                            this.getLists();
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
                        console.log(key);
                        const els = document.querySelectorAll(this.selectors[key]);
                        els.forEach((el) => {
                            el.classList.add('error');
                        });
                    }
                }
                alert('Kérjük javítsa a pirossal jelölt mezőket.');
            }
        },
    }));
});
