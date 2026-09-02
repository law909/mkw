document.addEventListener('alpine:init', () => {
    Alpine.data('login', () => ({
        hiba: '',
        adat: {
            email: '',
            jelszo: ''
        },

        belep() {
            this.hiba = '';
            if (!this.adat.email || !this.adat.jelszo) {
                this.hiba = 'Az emailcím és a jelszó megadása kötelező.';
                return;
            }
            fetch(new URL('/login/ment', location.origin), {
                method: 'POST',
                body: new URLSearchParams(this.adat)
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.url) {
                        location.href = data.url;
                    }
                })
                .catch(() => {
                    this.hiba = 'A belépés nem sikerült, próbálja újra.';
                });
        }
    }));
});
