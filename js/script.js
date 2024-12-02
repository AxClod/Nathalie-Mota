// Menu Burger //

jQuery(document).ready(function($) {
    $('.burgerBouton').on('click', function() {
        $(this).toggleClass('open'); // Change l'apparence du bouton
        $('nav').toggleClass('active'); // Affiche ou cache le menu
    });
});


// Modal contact //

document.addEventListener('DOMContentLoaded', (event) => {
    // Sélectionne la modale et les boutons de contact
    const modal = document.getElementById('modale-contact');
    const btns = document.getElementsByClassName('bouton-contact');
    const referencePhotoField = document.querySelector('input[name="RÉF. PHOTO"]'); // Champ du formulaire CF7

    // Ajout d'un écouteur de clic à chaque bouton 
    for(let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(event){
            // Empêche le comportement par défaut du clic (naviguer vers le lien)
            event.preventDefault();

            // Si le champ de référence est trouvé, on met à jour sa valeur
            const referenceValue = btn.dataset.reference;
            if (referenceValue) {
                // Si une référence est disponible, elle est pré-remplie
                referencePhotoField.value = referenceValue;
            } else {
                // Si aucune référence n'est disponible, le champ reste vide
                referencePhotoField.value = "";
            }

            // Affiche la modale de contact
            modal.style.display = "block";
        });
    }
    

    // Ajoute un écouteur pour fermer la modale en cliquant en dehors de son contenu
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
    
    // On ajoute un écouteur d'événements pour l'événement 'wpcf7mailsent', qui est déclenché lorsque le formaulaire est correctement soumis
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        // On affiche une alerte indiquant que le message a été envoyé
        alert('Votre message a été envoyé !');
        setTimeout(function(){ 
            modal.style.display = "none"; 
        }, 3000);
    }, false );
    
    // On ajoute un écouteur d'événements pour l'événement 'wpcf7invalid', qui est déclenché lorsque le formulaire est soumis avec des données invalides
    document.addEventListener( 'wpcf7invalid', function( event ) {
        // On affiche une alerte indiquant que tous les champs doivent être correctement remplis
        alert('Veuillez remplir les champs correctement. ');
    }, false );
    
    });

