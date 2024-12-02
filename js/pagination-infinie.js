// Appels Ajax filtres
let sectionFiltres = document.querySelector('.filtre');
document.addEventListener('DOMContentLoaded', function() {
  if ( sectionFiltres != null) {
      document.querySelector('#ajax_call_categories').addEventListener('change', getImages );
      document.querySelector('#ajax_call_formats').addEventListener('change', getImages );
      document.querySelector('#ajax_call_dates').addEventListener('change', getImages );
      document.querySelector('#load-more').addEventListener('click', getImages );
  }
});

// Styliser les selects
var selectCat = document.querySelector('#ajax_call_categories');
var selectFormat = document.querySelector('#ajax_call_formats');
var selectDate = document.querySelector('#ajax_call_dates');

document.addEventListener('DOMContentLoaded', function() {
  var selects = document.querySelectorAll('select');

  selects.forEach((select) => {
    select.addEventListener('mousedown', function(e) {
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
  document.addEventListener('mousedown', function(e) {
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

    console.log(motaphoto_js.ajax_url);

  let form = document.getElementById("form-filters");
      let formData = new FormData(form);
      formData.append('action', 'request_filtered');
      if(e.target == document.querySelector('#load-more')) {
        formData.append( 'paged', page );
        page ++;
      } else {
        page = 2;
      }

      // Fermeture des select
      e.target.size = 1;

      fetch(motaphoto_js.ajax_url, {
        method: 'POST',
        body: formData,
      }).then(function(response) {
        if (!response.ok) {
          throw new Error('Network response error.');
        }
   
        return response.json();
      }).then(function(data) {
        if (data.found_posts < page * 8 - 8) {
          document.querySelector('#load-more').style.display = "none";
        } else {
          document.querySelector('#load-more').style.display = "block";
        }
        if (e.target != document.querySelector('#load-more')) {

          document.querySelector("#ajax_return").innerHTML = "";
        }
        if( data != false) {
            document.querySelector('#ajax_return').insertAdjacentHTML('beforeend', data.my_html);
        } else {
          document.querySelector('#ajax_return').insertAdjacentHTML('beforeend', '<div> Aucune photo ne correspond à ces critères </div>');
        }
      }).catch(function(error) {
        console.error('There was a problem with the fetch operation: ', error);
      });
}