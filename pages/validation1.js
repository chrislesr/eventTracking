document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('clientForm'); // Assurez-vous que votre formulaire a cet ID
    const inputs = form.querySelectorAll('input, textarea, select'); // Sélectionnez tous les inputs et textarea

    form.addEventListener('submit', function(event) {
        let isValid = true;

        inputs.forEach(input => {
            if (input.value.trim() === '') {
                isValid = false;
                input.classList.add('is-invalid'); // Ajoute une classe Bootstrap pour le style
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault(); // Empêche l'envoi du formulaire si les validations échouent
        }
    });
});
