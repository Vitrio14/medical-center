// script.js

// Aggiungi eventuali script interattivi qui
document.addEventListener('DOMContentLoaded', function() {
    // Esempio: conferma cancellazione di un paziente
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm("Sei sicuro di voler eliminare questo paziente?")) {
                event.preventDefault();
            }
        });
    });
});
