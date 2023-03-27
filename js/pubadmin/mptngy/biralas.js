document.addEventListener("alpine:init", () => {
    Alpine.data("biralas", () => ({
        validation: [],
        anyaglist: [],
        anyag: null,
        rules: {
            szempont1: ['min:0', 'max:5'],
            szempont2: ['min:0', 'max:5'],
            szempont3: ['min:0', 'max:5'],
            szempont4: ['min:0', 'max:5'],
            szempont5: ['min:0', 'max:5'],
        },
        bekuldRules: {
            szovegesertekeles: ['required'],
        },
        showEditor: false,
        loadCount: 2,
        loaded: 0,
        szempontlist: [],
        clearErrors() {
            this.validation = {};
        },
        init() {
            this.initVars();

            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');
            Iodine.setErrorMessage('min', 'Legalább [PARAM]-t adjon meg');
            Iodine.setErrorMessage('max', 'Legfeljebb [PARAM]-t adjon meg');

            fetch(new URL('/pubadmin/biralandoanyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyaglist = data;
                    this.anyaglist.forEach((v, i) => {
                        v.szempont1 = v[`b${v.biralosorszam}szempont1`];
                        v.szempont2 = v[`b${v.biralosorszam}szempont2`];
                        v.szempont3 = v[`b${v.biralosorszam}szempont3`];
                        v.szempont4 = v[`b${v.biralosorszam}szempont4`];
                        v.szempont5 = v[`b${v.biralosorszam}szempont5`];
                        v.szovegesertekeles = v[`b${v.biralosorszam}szovegesertekeles`];
                        v.bbiralatkesz = v[`b${v.biralosorszam}biralatkesz`];
                    })
                    this.loaded++;
                });
            fetch(new URL('/pubadmin/szempontlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.szempontlist = data;
                    this.loaded++;
                });
        },
        initVars() {
            this.loaded = 0;
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
                beszelgetopartneremail: null,
                eloadas1: null,
                eloadas1cim: '',
                eloadas2: null,
                eloadas2cim: '',
                eloadas3: null,
                eloadas3cim: '',
                eloadas4: null,
                eloadas4cim: '',
                eloadas5: null,
                eloadas5cim: '',
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
                szempont1: 0,
                szempont2: 0,
                szempont3: 0,
                szempont4: 0,
                szempont5: 0,
                szovegesertekeles: null,
                bbiralatkesz: null,
            };
        },
        edit(id) {
            const a = this.anyaglist.find(el => el.id === parseInt(id));
            Object.keys(this.anyag).forEach(key => {
                this.anyag[key] = a[key];
            });
            this.showEditor = true;
        },
        cancel() {
            this.showEditor = false;
            this.init();
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
                    this.anyag.bbiralatkesz = true;
                }
                fetch(new URL('/pubadmin/mptngybiralas/ment', location.origin), {
                    method: 'POST',
                    body: new URLSearchParams(this.anyag)
                })
                    .then((response) => response.json())
                    .then((respdata) => {
                        if (respdata.success) {
                            this.showEditor = false;
                            this.init();
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
