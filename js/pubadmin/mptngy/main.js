document.addEventListener("alpine:init", () => {
    Alpine.data("main", () => ({
        biralas() {
            location.href = '/pubadmin/mptngybiralas';
        },
        beallitasok() {
            location.href = '/pubadmin/mptngysetup';
        },
        logout() {
            location.href = '/pubadmin/logout';
        }
    }));
});
