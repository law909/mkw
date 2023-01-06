document.addEventListener("alpine:init", () => {
    Alpine.data("anyaglist", () => ({
        showEditor: false,
        anyaglist: [],
        sajatanyaglist: [],
        anyagtipuslist: [],
        me: {
            nev: null,
        },
        anyag: {
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
        },
        szerzo1unknown: null,
        szerzo2unknown: null,
        szerzo3unknown: null,
        szerzo4unknown: null,
        szerzo5unknown: null,

        createNew() {
            this.anyag.tulajdonos = this.me;
            this.showEditor = true;
        },
        getLists() {
            fetch(new URL('/anyaglist', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.anyaglist = data;
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
            url.searchParams.append('email', this.anyag[f]);
            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    this[t] = data.unknown;
                })
        },
        logout() {
            location.href = '/logout';
        },
        save() {
            fetch(new URL('/regisztracio/ment', location.origin), {
                method: 'POST',
                body: new URLSearchParams(this.compileData())
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
        },
    }));
});
