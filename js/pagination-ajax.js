jQuery(document).ready(function($) {
    var pageNum = 1; // Numéro de la page actuelle
    var isLoading = false; // Éviter les requêtes multiples
    var noMorePhotos = false; // Vérifie s'il y a encore des données à charger

    // Fonction de chargement des photos (CPT "photos")
    function loadMorePhotos() {
        if (isLoading || noMorePhotos) {
            return;
        }

        isLoading = true; // Bloque d'autres requêtes jusqu'à ce que celle-ci soit terminée

        // Ajout d'un indicateur de chargement
        $('.load-more').text('Chargement...');

        $.ajax({
            url: myAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_photos', // Nom de l'action utilisée dans le backend
                page: pageNum,
                post_type: 'photos' // Indique que cela concerne le type de post personnalisé "photos"
            },
            success: function(response) {
                if (response.trim() === '') {
                    noMorePhotos = true; // Plus de photos à charger
                    $('.load-more').hide(); // Cache le bouton "Charger plus"
                    $('.container-photo-apparente').append('<p>Pas de photos supplémentaires à charger.</p>');
                } else {
                    $('.container-photo-apparente').append(response); // Ajouter les nouvelles photos
                    pageNum++; // Incrémenter le numéro de page
                }
            },
            error: function() {
                console.error('Erreur lors du chargement des photos.');
            },
            complete: function() {
                isLoading = false; // Requêtes à nouveau autorisées
                $('.load-more').text('Charger plus'); // Rétablir le texte du bouton
            }
        });
    }

    // Déclenchement du chargement au clic sur le bouton
    $('.load-more').on('click', function() {
        loadMorePhotos(); // Charger les photos
    });
});