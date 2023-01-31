document.addEventListener("alpine:init", () => {
    Alpine.data("login", () => ({
        validation: [],
        login: {
            email: null,
            jelszo: null,
        },
        rules: {
            email: ['required', 'email', 'emailFoglalt'],
        },
        clearErrors() {
            this.validation = {};
        },
        getLists() {
            Iodine.setErrorMessage('required', 'Kötelező kitölteni');
            Iodine.setErrorMessage('email', 'Hibás email cím');
        },
        dologin() {
            fetch(new URL('/pubadmin/login/ment', location.origin), {
                method: 'POST',
                body: new URLSearchParams(this.login)
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
        }
    }));
});
