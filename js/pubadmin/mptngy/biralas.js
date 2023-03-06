document.addEventListener("alpine:init", () => {
    Alpine.data("biralas", () => ({
        validation: [],
        anyaglist: [],
        anyag: null,
        rules: {},
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
                szempont1: 0,
                szempont2: 0,
                szempont3: 0,
                szempont4: 0,
                szempont5: 0,
                szovegesertekeles: null,
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
        save() {
            const valid = Iodine.assert(this.anyag, this.rules);

            this.clearErrors();

            if (valid.valid) {

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
