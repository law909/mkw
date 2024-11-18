function mainData() {
    return {
        page: 'myData',
        data: {},
        finances: [],
        password: {new: '', confirm: ''},
        errors: {},

        init() {
            this.fetchData();
            this.fetchFinances();
        },

        fetchData() {
            fetch('/api/data')
                .then(response => response.json())
                .then(json => {
                    this.data = json;
                });
        },

        validateData() {
            this.errors = {};

            const rules = {
                nev: ['required'],
                'cim.iranyitoszam': ['required'],
                'cim.varos': ['required'],
                'cim.utca': ['required'],
                'cim.hazszam': ['required'],
            };

            const fieldNames = {
                nev: 'Név',
                'cim.iranyitoszam': 'Irányítószám',
                'cim.varos': 'Város',
                'cim.utca': 'Utca',
                'cim.hazszam': 'Házszám',
            };

            const dataToValidate = {
                nev: this.data.nev,
                'cim.iranyitoszam': this.data.cim?.iranyitoszam,
                'cim.varos': this.data.cim?.varos,
                'cim.utca': this.data.cim?.utca,
                'cim.hazszam': this.data.cim?.hazszam,
            };

            for (const field in rules) {
                const value = dataToValidate[field];
                const validationRules = rules[field];
                const result = Iodine.is(value, validationRules);
                if (result !== true) {
                    const fieldName = fieldNames[field] || field;
                    this.errors[field] = Iodine.getErrorMessage(result, fieldName);
                }
            }

            return Object.keys(this.errors).length === 0;
        },

        saveData() {
            if (!this.validateData()) {
                return;
            }

            fetch('/api/data', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(this.data),
            })
                .then(response => response.json())
                .then(json => {
                    alert('Adatok sikeresen mentve.');
                });
        },

        fetchFinances() {
            fetch('/api/finances')
                .then(response => response.json())
                .then(json => {
                    this.finances = json;
                });
        },

        changePassword() {
            if (this.password.new !== this.password.confirm) {
                alert('A jelszavak nem egyeznek.');
                return;
            }
            fetch('/api/password', {
                method: 'PUT',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({password: this.password.new}),
            })
                .then(response => response.json())
                .then(json => {
                    alert('Jelszó sikeresen módosítva.');
                    this.password.new = '';
                    this.password.confirm = '';
                });
        },

        logout() {
            window.location.href = '/logout';
        },
    };
}

document.addEventListener('alpine:init', () => {
    Alpine.data('mainData', mainData);
});
