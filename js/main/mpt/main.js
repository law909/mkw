// ennyi ideig marad a képernyőn a sikeres mentés visszajelzése
const UZENET_IDO = 15000;

document.addEventListener('alpine:init', () => {
    Alpine.data('mptadatok', () => ({
        lap: 'adataim',
        menuOpen: false,
        mentesfolyik: false,
        hiba: '',
        uzenet: '',
        hibak: {},
        partner: {},
        folyoszamla: {egyenleg: 0, tetelek: []},
        jelszo: {jelszoregi: '', jelszo1: '', jelszo2: ''},
        jelszohiba: '',
        jelszouzenet: '',
        uzenetidozito: null,

        init() {
            this.betolt();
        },

        lapValt(lap) {
            this.lap = lap;
            this.menuOpen = false;
        },

        uzenetKiir(szoveg) {
            this.uzenet = szoveg;
            clearTimeout(this.uzenetidozito);
            this.uzenetidozito = setTimeout(() => {
                this.uzenet = '';
            }, UZENET_IDO);
        },

        osszeg(ertek) {
            return new Intl.NumberFormat('hu-HU').format(ertek || 0) + ' Ft';
        },

        betolt() {
            fetch(new URL('/adataim/adatok', location.origin))
                .then((response) => response.json())
                .then((data) => {
                    if (data.hiba) {
                        this.hiba = data.hiba;
                        return;
                    }
                    this.folyoszamla = data.folyoszamla;
                    this.partnerBeallit(data.partner);
                })
                .catch(() => {
                    this.hiba = 'Az adatok betöltése nem sikerült.';
                });
        },

        partnerBeallit(partner) {
            // a mentés a mezőket stringként küldi vissza, a null a szerveren figyelmeztetést adna
            Object.keys(partner).forEach((mezo) => {
                if (partner[mezo] === null || partner[mezo] === false) {
                    partner[mezo] = '';
                }
            });
            this.partner = partner;
        },

        ment() {
            this.hiba = '';
            clearTimeout(this.uzenetidozito);
            this.uzenet = '';
            this.hibak = {};
            this.mentesfolyik = true;
            fetch(new URL('/adataim/ment', location.origin), {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(this.partner)
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.hiba) {
                        this.hiba = data.hiba;
                        return;
                    }
                    if (data.hibak) {
                        this.hibak = data.hibak;
                        this.hiba = 'Kérjük javítsa a pirossal jelölt mezőket.';
                        return;
                    }
                    this.partnerBeallit(data.partner);
                    this.uzenetKiir('Az adatokat elmentettük.');
                })
                .catch(() => {
                    this.hiba = 'A mentés nem sikerült.';
                })
                .finally(() => {
                    this.mentesfolyik = false;
                });
        },

        jelszoMentes() {
            this.jelszohiba = '';
            this.jelszouzenet = '';
            fetch(new URL('/jelszo/ment', location.origin), {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(this.jelszo)
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.hiba) {
                        this.jelszohiba = data.hiba;
                        return;
                    }
                    this.jelszo.jelszoregi = '';
                    this.jelszo.jelszo1 = '';
                    this.jelszo.jelszo2 = '';
                    this.jelszouzenet = data.uzenet;
                })
                .catch(() => {
                    this.jelszohiba = 'A jelszó módosítása nem sikerült.';
                });
        }
    }));
});
