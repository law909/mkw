document.addEventListener("alpine:init", () => {
    Alpine.data("setup", () => ({
        validation: [],
        biralo: {
            maxdb: null,
            temakorlist: [],
        },
        rules: {
            maxdb: ['required', 'min:10'],
            temakorlist: ['min1db'],
        },
        clearErrors() {
            this.validation = {};
        },
        init() {
            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');
            Iodine.setErrorMessage('min', 'Legalább [PARAM]-t adjon meg');

            Iodine.rule('min1db', () => {
                let db = 0;
                this.biralo.temakorlist.forEach(v => {
                    if (v.selected) {
                        db++;
                    }
                });
                return db > 0;
            });
            Iodine.setErrorMessage('min1db', 'Legalább egyet válasszon ki');

            fetch(new URL('/pubadmin/mptngyme', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    this.biralo.temakorlist = data.temakorlist;
                    this.biralo.maxdb = data.maxdb;
                });
        },
        cancel() {
            location.href = '/pubadmin';
        },
        save() {
            const valid = Iodine.assert(this.biralo, this.rules);

            this.clearErrors();

            if (valid.valid) {

                let params = new URLSearchParams();
                params.append('maxdb', this.biralo.maxdb);
                params.append('temakorlist', JSON.stringify(this.biralo.temakorlist));

                fetch(new URL('/pubadmin/mptngysetup/ment', location.origin), {
                    method: 'POST',
                    body: params
                })
                    .then((response) => response.json())
                    .then((respdata) => {
                        if (respdata.success) {
                            location.href = new URL('/pubadmin', location.origin);
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
