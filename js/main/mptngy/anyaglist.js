document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        showEditor: false,
        anyaglist: [],
        sajatanyaglist: [],
        anyagtipuslist: [],
        datumlist: [],
        temakorlist: [],
        me: {
            nev: null,
        },
        anyag: null,
        szerzo1unknown: null,
        szerzo2unknown: null,
        szerzo3unknown: null,
        szerzo4unknown: null,
        szerzo5unknown: null,

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
                tartalom: null,
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
            fetch(new URL('/anyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyaglist = data;
                });
            fetch(new URL('/temakorlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.temakorlist = data;
                });
            fetch(new URL('/sajatanyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.sajatanyaglist = data;
                });
            fetch(new URL('/szakmaianyagtipuslist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyagtipuslist = data;
                });
            fetch(new URL('/datumlist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.datumlist = data;
                });
            fetch(new URL('/partner/getdata', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.me = data;
                    this.anyag.tulajdonosnev = this.me.nev;
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
        },
    }));
});
