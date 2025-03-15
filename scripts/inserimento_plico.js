document.addEventListener('DOMContentLoaded', function() {
    // Gestisce i pulsanti "Avanti"
    document.querySelectorAll('.next-section').forEach(button => {
        button.addEventListener('click', function() {
            const currentSection = this.closest('.form-section');
            const nextSectionId = this.getAttribute('data-next');

            // Verifica che tutti i campi obbligatori nella sezione corrente siano compilati
            const requiredFields = currentSection.querySelectorAll('input[required], select[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (isValid) {
                currentSection.style.display = 'none';
                document.getElementById(nextSectionId).style.display = 'block';

                // Aggiorna la progress bar
                updateProgressBar();
            }
        });
    });

    // Gestisce i pulsanti "Indietro"
    document.querySelectorAll('.prev-section').forEach(button => {
        button.addEventListener('click', function() {
            const currentSection = this.closest('.form-section');
            const prevSectionId = this.getAttribute('data-prev');

            currentSection.style.display = 'none';
            document.getElementById(prevSectionId).style.display = 'block';

            // Aggiorna la progress bar
            updateProgressBar();
        });
    });

    // Funzione per aggiornare la progress bar
    function updateProgressBar() {
        const totalSections = document.querySelectorAll('.form-section').length;
        let visibleSectionIndex = 0;

        document.querySelectorAll('.form-section').forEach((section, index) => {
            if (section.style.display !== 'none') {
                visibleSectionIndex = index;
            }
        });

        const progressPercentage = Math.round(((visibleSectionIndex + 1) / totalSections) * 100);
        const progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = progressPercentage + '%';
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        progressBar.textContent = 'Step ' + (visibleSectionIndex + 1) + '/' + totalSections;
    }

    // Gestisce la validazione dei campi in tempo reale
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        field.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
            }
        });
    });
});