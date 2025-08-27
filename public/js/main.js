// public/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    // Confirmation de suppression d'annonce
    const deleteButtons = document.querySelectorAll('.delete-annonce-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.')) {
                event.preventDefault(); // Annule l'action si l'utilisateur clique sur "Annuler"
            }
        });
    });

    // Filtrage des annonces par catégorie (boutons)
    const categoryFilterButtons = document.querySelectorAll('.category-filter-btn');
    const annonces = document.querySelectorAll('.annonce-card');

    categoryFilterButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Empêche le comportement par défaut du lien

            // Supprime la classe 'active' de tous les boutons
            categoryFilterButtons.forEach(btn => btn.classList.remove('active'));

            // Ajoute la classe 'active' au bouton cliqué
            button.classList.add('active');

            const selectedCategoryId = button.dataset.categoryId; // Récupère l'ID de la catégorie du bouton

            annonces.forEach(annonce => {
                const annonceCategoryId = annonce.dataset.categoryId; // Assurez-vous d'ajouter data-category-id="ID_CAT" dans le HTML de chaque annonce
                if (selectedCategoryId === '' || annonceCategoryId === selectedCategoryId) {
                    annonce.style.display = 'block'; // Afficher l'annonce
                } else {
                    annonce.style.display = 'none'; // Cacher l'annonce
                }
            });
        });
    });

    // Gestion des messages de notification (optionnel: les cacher après un certain temps)
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (!alert.classList.contains('no-autohide')) { // Ajoutez la classe 'no-autohide' si vous ne voulez pas qu'une alerte disparaisse
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500); // Supprime l'élément après la transition
            }, 5000); // Disparaît après 5 secondes
        }
    });
});