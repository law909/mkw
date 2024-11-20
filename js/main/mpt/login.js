function loginForm() {
    return {
        email: '',
        password: '',
        rememberMe: false,
        errors: {},
        submitForm() {
            this.errors = {};
            if (!this.email) {
                this.errors.email = 'Az email cím megadása kötelező.';
            }
            if (!this.password) {
                this.errors.password = 'A jelszó megadása kötelező.';
            }
            if (Object.keys(this.errors).length === 0) {
                // Perform login action (e.g., send data to the server)
                alert('Bejelentkezés folyamatban...');
                // Reset form (optional)
                // this.email = '';
                // this.password = '';
                // this.rememberMe = false;
            }
        }
    };
}

document.addEventListener('alpine:init', () => {
    Alpine.data('loginForm', loginForm);
});
