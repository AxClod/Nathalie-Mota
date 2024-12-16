document.addEventListener('DOMContentLoaded', function () {
  let page = 2;

  // Écoute les événements des filtres
  const sectionFiltres = document.querySelector('.filtre');
  if (sectionFiltres != null) {
    document.querySelector('#ajax_call_categories').addEventListener('change', getImages);
    document.querySelector('#ajax_call_formats').addEventListener('change', getImages);
    document.querySelector('#ajax_call_dates').addEventListener('change', getImages);
    const loadMoreBtn = document.querySelector('#load-more');
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function (e) {
        e.preventDefault();
        getImages(true); // Charge les prochaines pages
      });
    }
  }

  // Stylisation des selects personnalisés
  const selectWrappers = document.querySelectorAll('.custom-select');

  selectWrappers.forEach((select) => {
    const trigger = select.querySelector('.custom-select__trigger');
    const options = select.querySelectorAll('.custom-option');
    const span = trigger.querySelector('span');

    // Ouvrir ou fermer le menu des options
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      closeAllSelects();
      select.classList.toggle('open');
    });

    // Gérer la sélection d'une option
    options.forEach((option) => {
      option.addEventListener('click', () => {
        span.textContent = option.textContent; // Met à jour le texte
        trigger.setAttribute('data-value', option.dataset.value); // Met à jour la valeur

        // Marquer l'option comme sélectionnée
        options.forEach((opt) => opt.classList.remove("selected"));
        option.classList.add("selected");

        // Ajoute la valeur sélectionnée dans le formulaire
        const form = document.getElementById('form-filters');
        const inputName = select.closest('.custom-select-wrapper').id.replace('ajax_call_', '');
        form.querySelectorAll(`input[name="${inputName}"]`).forEach((el) => el.remove());
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = inputName;
        input.value = option.dataset.value;
        form.appendChild(input);

        // Actualise les photos
        page = 2; // Réinitialise la pagination
        getImages(false); // Charge les filtres mis à jour
      });
    });
  });

  // Fermer tous les menus ouverts quand on clique à l'extérieur
  document.addEventListener('click', () => {
    closeAllSelects();
  });

  function closeAllSelects() {
    document.querySelectorAll('.custom-select').forEach((el) => el.classList.remove('open'));
  }

  // Ajouter un style "active" pour les filtres ouverts
  const triggers = document.querySelectorAll('.custom-select__trigger');

  triggers.forEach((trigger) => {
    trigger.addEventListener('click', function () {
      triggers.forEach((el) => el.classList.remove('active')); // Retire "active" des autres
      trigger.classList.add('active'); // Ajoute "active" au trigger actuel
    });
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('.custom-select')) {
      triggers.forEach((trigger) => trigger.classList.remove('active'));
    }
  });

  // Fonction principale pour récupérer les images
  function getImages(isLoadMore = false) {
    const form = document.getElementById('form-filters');
    const formData = new FormData(form);
    formData.append('action', 'request_filtered');
    if (isLoadMore) {
      formData.append('paged', page);
      page++;
    } else {
      page = 2; // Réinitialise la pagination si un filtre change
    }

    fetch(motaphoto_js.ajax_url, {
      method: 'POST',
      body: formData,
    })
      .then((response) => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
      })
      .then((data) => {
        const ajaxReturn = document.querySelector('#ajax_return');
        if (!isLoadMore) {
          ajaxReturn.innerHTML = ''; // Réinitialise le contenu si un filtre change
        }

        if (data.success) {
          ajaxReturn.insertAdjacentHTML('beforeend', data.my_html);
          const loadMoreBtn = document.querySelector('#load-more');
          if (loadMoreBtn) {
            loadMoreBtn.style.display = page > data.max_num_pages ? 'none' : 'block';
          }
        } else {
          ajaxReturn.innerHTML = '<div>Aucune photo trouvée.</div>';
          const loadMoreBtn = document.querySelector('#load-more');
          if (loadMoreBtn) {
            loadMoreBtn.style.display = 'none';
          }
        }
      })
      .catch((error) => console.error('Erreur avec fetch :', error));
  }
});


