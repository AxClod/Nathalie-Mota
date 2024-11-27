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
    const referencePhotoField = document.getElementById('reference'); // Champ de formulaire pour la référence de la photo

    // Ajout d'un écouteur de clic à chaque bouton 'contact-link'
    for(let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(event){
            // Empêche le comportement par défaut du clic (naviguer vers le lien)
            event.preventDefault();

            // Si le champ de référence est trouvé, on met à jour sa valeur
            if (referencePhotoField) {
                const referenceValue = this.dataset.reference;
                if (referenceValue) {
                    referencePhotoField.value = referenceValue;
                }
            } else {
                console.error("L'élément avec l'ID 'reference' n'a pas été trouvé.");
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

