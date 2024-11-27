// Ce code s'exécute lorsque le DOM (Document Object Model) est complètement chargé
document.addEventListener('DOMContentLoaded', function() {

    // Récupérer les éléments nécessaires du DOM
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    const prevButton = document.getElementById('prev-button');
    const nextButton = document.getElementById('next-button');
    const closeButton = document.getElementById('close-button');

    // Initialisation du tableau qui contiendra toutes les images pour la lightbox
    let allImagesForLightbox = [];

    // URL de l'API pour récupérer les images (ceci est fourni par PHP)
    const apiEndpoint = adminAjax.ajax_url;

    // Fonction asynchrone pour charger toutes les images depuis l'API
    async function loadAllImagesForLightbox() {
        const response = await fetch(apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=load_all_photos_for_lightbox',
        });
        const data = await response.json();
        allImagesForLightbox = data;
    }

    // Nouvelle ligne : Ajouter un écouteur d'événement pour fermer la lightbox
    console.log(closeButton);
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            lightbox.classList.add('hidden');
        });
    } else {
        console.warn('closeButton n\'est pas trouvé dans le DOM.');
    }

    // Fonction pour ajuster la position des éléments span en fonction de la taille et de la position de l'image
    function adjustSpanPositions() {
        // Obtenir la position et la taille de l'image
        const imgRect = img.getBoundingClientRect();
    
        // Récupérer les éléments span
        let referenceElement = document.querySelector('.lightbox-reference');
        let categoryElement = document.querySelector('.lightbox-category');
    
        // Vérifier l'existence des éléments avant de les manipuler
        if (referenceElement) {
            referenceElement.style.left = `${imgRect.left}px`;
            referenceElement.style.top = `${imgRect.bottom + 10}px`;
        }
    
        if (categoryElement) {
            categoryElement.style.right = `calc(100% - ${imgRect.right}px)`;
            categoryElement.style.top = `${imgRect.bottom + 10}px`;
        }
    }

    // Fonction pour ouvrir la lightbox avec une image spécifique
    function openLightbox(imageSrc, reference, category) {
        // Modifier la source de l'image et rendre la lightbox visible
        img.src = imageSrc;
        lightbox.classList.remove('hidden');

        // Charger toutes les images pour une navigation future
        loadAllImagesForLightbox();

        // Gestion des éléments supplémentaires dans la lightbox : référence et catégorie
        let referenceElement = document.querySelector('.lightbox-reference');
        let categoryElement = document.querySelector('.lightbox-category');

        // Création de l'élément pour afficher la référence si nécessaire
        if (!referenceElement) {
            referenceElement = document.createElement('span');
            referenceElement.classList.add('lightbox-reference');
            lightbox.appendChild(referenceElement);
        }

        // Création de l'élément pour afficher la catégorie si nécessaire
        if (!categoryElement) {
            categoryElement = document.createElement('span');
            categoryElement.classList.add('lightbox-category');
            lightbox.appendChild(categoryElement);
        }

        // Mise à jour des éléments avec les nouvelles valeurs
        referenceElement.innerText = reference;
        categoryElement.innerText = category;

        // Lorsque l'image est chargée, ajustez les positions
        img.onload = adjustSpanPositions;
    }

    // Écouter l'événement resize de la fenêtre
    window.addEventListener('resize', adjustSpanPositions);

    // Fonction pour naviguer entre les images de la lightbox
    function navigate(direction) {
        // Obtenir l'image actuelle
        const currentSrc = img.src;
        let currentIndex;

        // Trouver l'index de l'image actuelle dans le tableau
        allImagesForLightbox.forEach(function(imageData, index) {
            if (imageData.url === currentSrc) {
                currentIndex = index;
            }
        });

        // Calculer le nouvel index en fonction de la direction (précédent ou suivant)
        let newIndex = (direction === 'prev') ? currentIndex + 1 : currentIndex - 1;

        // Vérifier si le nouvel index est valide
        if (newIndex >= 0 && newIndex < allImagesForLightbox.length) {
            const newImgData = allImagesForLightbox[newIndex];
            openLightbox(newImgData.url, newImgData.reference, newImgData.category);
        }
    }

    function handleFullscreenClick(event) {
        if (event.target.classList.contains('fullscreen-icon')) {
            // Trouver l'élément image associé à l'icône cliquée
            const imgElement = event.target.closest('.bloc-photo') || event.target.closest('.img-container') || event.target.closest('.img-single');
            const imgSrc = imgElement.querySelector('img').src;
    
            // Trouver les données de l'image cliquée pour l'ouvrir dans la lightbox
            let clickedImage;
            allImagesForLightbox.forEach(function(imageData) {
                if (imageData.url === imgSrc) {
                    clickedImage = imageData;
                }
            });
    
            // Ouvrir la lightbox avec l'image cliquée
            if (clickedImage) {
                openLightbox(clickedImage.url, clickedImage.reference, clickedImage.category);
            }
        }
    }
    
    // Écouter les clics sur les icônes "fullscreen" pour ouvrir la lightbox
    document.querySelectorAll('.container-bloc-photo, .page-photo_section-bloc').forEach(function(container) {
        container.addEventListener('click', handleFullscreenClick);
    });
        
    // Écouter les clics sur les icônes "fullscreen" dans img-container
    document.querySelectorAll('.img-container').forEach(function(container) {
        container.addEventListener('click', handleFullscreenClick);
    });

    // Écouter les clics sur les boutons "précédent" et "suivant" pour naviguer entre les images
    prevButton.addEventListener('click', function() {
        navigate('prev');
    });
    nextButton.addEventListener('click', function() {
        navigate('next');
    });

    // Écouter les clics en dehors de l'image ou sur l'image pour fermer la lightbox
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox || event.target === img) {
            lightbox.classList.add('hidden');
        }
    });

    // Charger toutes les images dès que le DOM est prêt
    loadAllImagesForLightbox();
});