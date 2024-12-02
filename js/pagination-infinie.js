// Appels Ajax filtres
let sectionFiltres = document.querySelector('.filtre');
document.addEventListener('DOMContentLoaded', function () {
  if (sectionFiltres != null) {
    document.querySelector('#ajax_call_categories').addEventListener('change', getImages);
    document.querySelector('#ajax_call_formats').addEventListener('change', getImages);
    document.querySelector('#ajax_call_dates').addEventListener('change', getImages);
    document.querySelector('#load-more').addEventListener('click', getImages);
  }
});

// Styliser les selects
var selectCat = document.querySelector('#ajax_call_categories');
var selectFormat = document.querySelector('#ajax_call_formats');
var selectDate = document.querySelector('#ajax_call_dates');

document.addEventListener('DOMContentLoaded', function () {
  var selects = document.querySelectorAll('select');

  selects.forEach((select) => {
    select.addEventListener('mousedown', function (e) {
      e.stopPropagation();

      // Calculer la taille en fonction du nombre d'options
      var optionCount = select.options.length;
      var calculatedSize = Math.min(optionCount, 10); // limiter à une taille maximale de 10

      // Fermer tous les autres selects sauf celui qui a été cliqué
      selects.forEach((otherSelect) => {
        if (otherSelect !== select) {
          otherSelect.size = 1;
          otherSelect.parentElement.classList.remove('opened');
        }
      });

      // Appliquer la taille calculée
      select.size = calculatedSize;
      select.parentElement.classList.add('opened');
    });
  });

  // Gestionnaire d'événements pour fermer le menu déroulant lorsqu'on clique à l'extérieur
  document.addEventListener('mousedown', function (e) {
    selects.forEach((select) => {
      if (select.parentElement.classList.contains('opened') && !select.parentElement.contains(e.target)) {
        select.size = 1;
        select.parentElement.classList.remove('opened');
      }
    });
  });
});

let page = 2;

function getImages(e) {
  e.preventDefault(); // Empêche tout comportement par défaut
  const form = document.getElementById('form-filters');
  const formData = new FormData(form);

  formData.append('action', 'request_filtered');

  if (e.target.id === 'load-more') {
    formData.append('paged', page);
    page++;
  } else {
    page = 2; // Réinitialise la pagination si un filtre change
  }

  console.log('FormData envoyé :');
  for (let [key, value] of formData.entries()) {
    console.log(key, value);
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
      if (data.found_posts < page * 8 - 8) {
        document.querySelector('#load-more').style.display = 'none';
      } else {
        document.querySelector('#load-more').style.display = 'block';
      }

      if (e.target.id !== 'load-more') {
        document.querySelector('#ajax_return').innerHTML = '';
      }

      if (data.my_html) {
        document.querySelector('#ajax_return').insertAdjacentHTML('beforeend', data.my_html);
      } else {
        document.querySelector('#ajax_return').innerHTML = '<div>Aucune photo trouvée.</div>';
      }
    })
    .catch((error) => console.error('Problème avec fetch :', error));
}