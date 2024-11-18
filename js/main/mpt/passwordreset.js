function resetPasswordForm() {
    return {
        password: '',
        confirmPassword: '',
        errors: {},
        submitForm() {
            this.errors = {};

            // Define the password complexity regex pattern
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

            // Validate the new password using Iodine
            if (!Iodine.isMinLength(this.password, 8)) {
                this.errors.password = 'A jelszónak legalább 8 karakter hosszúnak kell lennie.';
            } else if (!Iodine.isRegexMatch(this.password, passwordPattern)) {
                this.errors.password = 'A jelszónak tartalmaznia kell kis- és nagybetűt, számot és speciális karaktert.';
            }

            // Validate the password confirmation
            if (this.confirmPassword !== this.password) {
                this.errors.confirmPassword = 'A jelszavak nem egyeznek.';
            }

            // Check if there are any errors
            if (Object.keys(this.errors).length === 0) {
                // Perform password reset action (e.g., send data to the server)
                alert('Jelszó sikeresen megváltoztatva!');
                // Redirect to login page or reset form fields
                // window.location.href = 'login.html';
                // this.password = '';
                // this.confirmPassword = '';
            }
        }
    };
}

document.addEventListener('alpine:init', () => {
    Alpine.data('resetPasswordForm', resetPasswordForm);
});
