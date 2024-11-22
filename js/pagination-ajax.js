jQuery(document).ready(function($) {

    // Variable globale pour la page actuelle
    let pageNum = 1;

    // Cette fonction retourne les valeurs actuelles des filtres.
    function getCurrentFilters() {
        return {
            categorie: $('#filtre-categorie').val(), // Valeur du filtre catégorie
            format: $('#filtre-format').val(),      // Valeur du filtre format
            annee: $('#filtre-date').val()          // Valeur du filtre année
        };
    }

    // Déclenchement de la fonction au clic du bouton "Charger plus"
    $('#load-more-btn').click(function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du bouton

        const currentFilters = getCurrentFilters();  // Récupération des filtres actuels

        // Requête AJAX pour charger plus de photos
        $.ajax({
            url: myAjax.ajaxurl, // URL générée via wp_localize_script
            type: 'POST',
            data: {
                action: 'load_more_photos', // Action liée au handler PHP
                page: pageNum,              // Numéro de la page actuelle
                categorie: currentFilters.categorie, // Filtre catégorie
                format: currentFilters.format,       // Filtre format
                annee: currentFilters.annee          // Filtre année
            },
            beforeSend: function() {
                // Ajouter un spinner ou désactiver le bouton pendant le chargement
                $('#load-more-btn').text('Chargement...').prop('disabled', true);
            },
            success: function(response) {
                if (!response || response.trim() === '') { // Vérifier si la réponse est vide
                    // Ajouter un message pour informer qu'il n'y a plus de photos à charger
                    $('.container-photo-apparente').append('<p class="no-more-photos">Pas de photos supplémentaires à charger.</p>');
                    $('#load-more-btn').hide(); // Masquer le bouton
                } else {
                    $('.container-photo-apparente').append(response); // Ajouter les nouvelles photos
                    pageNum++; // Incrémenter le numéro de page
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors du chargement des photos :', error);
                alert('Une erreur est survenue lors du chargement des photos. Veuillez réessayer.');
            },
            complete: function() {
                // Réactiver le bouton et réinitialiser le texte après chargement
                $('#load-more-btn').text('Charger plus').prop('disabled', false);
            }
        });
    });
});