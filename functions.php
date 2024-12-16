<?php

function theme_script() {
    // Charger le style principal
    wp_enqueue_style('style', get_stylesheet_uri());

    // Charger jQuery
    wp_enqueue_script('jquery');

    // Charger le fichier script.js
    wp_enqueue_script(
        'script-personnalise',
        get_template_directory_uri() . '/js/script.js',
        ['jquery'],
        null,
        true
    );

    // Charger le fichier lightbox.js
    wp_enqueue_script(
        'lightbox',
        get_template_directory_uri() . '/js/lightbox.js',
        ['jquery'],
        '1.0',
        true
    );

    // Ajouter la variable adminAjax pour lightbox.js
    wp_localize_script('lightbox', 'adminAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);

    //Déclarer le fichier pour les requêtes ajax
    wp_enqueue_script(
        'motaphoto', 
        get_template_directory_uri() . '/js/pagination-infinie.js', array('jquery'), 
        '1.0.0', 
        true
     );
     
     // Passer les données de PHP vers Javascript de manière sécurisée
     wp_localize_script(
         'motaphoto', 
         'motaphoto_js', 
         array('ajax_url' => admin_url('admin-ajax.php'))
     );
}

add_action('wp_enqueue_scripts', 'theme_script');

    
// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );
add_theme_support( "title-tag" );

    
    function register_my_menus() {
        register_nav_menus( array(
            'header' => 'Menu principal',
            'footer' => 'Menu pied de page',
        ) );
    }
    add_action( 'init', 'register_my_menus' );


// Change la class du lien "bouton" dans le menu
function ajouter_classe_bouton_contact($classes, $item, $args) {
    
    if ($item->ID == 22) { 
        $classes[] = 'bouton-contact';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'ajouter_classe_bouton_contact', 10, 3);


// Pagination infinie
function motaphoto_request_filtered() {
    $categories = isset($_POST['categories']) ? sanitize_text_field($_POST['categories']) : '';
    $formats = isset($_POST['formats']) ? sanitize_text_field($_POST['formats']) : '';
    $dates = isset($_POST['dates']) ? sanitize_text_field($_POST['dates']) : 'DESC';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $tax_query = [];

    if ($categories) {
        $tax_query[] = [
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => $categories,
        ];
    }

    if ($formats) {
        $tax_query[] = [
            'taxonomy' => 'formats',
            'field' => 'slug',
            'terms' => $formats,
        ];
    }

    $query = new WP_Query([
        'post_type' => 'photos',
        'posts_per_page' => 8,
        'paged' => $paged,
        'order' => $dates,
        'orderby' => 'date',
        'tax_query' => $tax_query,
    ]);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/bloc-photo'); // Ton template d'affichage de photo
        }
        $output = ob_get_clean();
        wp_send_json([
            'success' => true,
            'my_html' => $output,
            'max_num_pages' => $query->max_num_pages,
        ]);
    } else {
        wp_send_json([
            'success' => false,
            'message' => 'Aucune photo trouvée.',
        ]);
    }

    wp_die();
}
add_action('wp_ajax_request_filtered', 'motaphoto_request_filtered');
add_action('wp_ajax_nopriv_request_filtered', 'motaphoto_request_filtered');



// Lightbox
    // Fonction pour charger toutes les photos pour une lightbox
    function load_all_photos_for_lightbox() {
        $args = array(
            'post_type' => 'photos',
            'posts_per_page' => -1,
        );

        $query = new WP_Query($args);
        $all_images = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $image_data = array(
                    'url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'reference' => get_field('reference'),
                    'category' => implode(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'categorie'), 'name')),
                );

                $all_images[] = $image_data;
            }
        }

        echo json_encode($all_images);  // Retourne les données au format JSON
        wp_die();  // Arrête l'exécution de la requête AJAX
    }

    add_action('wp_ajax_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');         // Si l'utilisateur est connecté
    add_action('wp_ajax_nopriv_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');  // Si l'utilisateur n'est pas connecté


// Limiter le poids des images uploadées à moins de 1 Mo
function limiter_taille_image($file) {
    // Taille maximale autorisée en octets (1 Mo)
    $taille_max = 1 * 1024 * 1024;

    // Vérifiez si le fichier est une image
    $types_autorises = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($file['type'], $types_autorises)) {
        // Vérifiez la taille
        if ($file['size'] > $taille_max) {
            // Redimensionner ou rejeter l'image
            $file['error'] = 'L\'image est trop lourde. La taille maximale autorisée est de 1 Mo. Veuillez compresser votre fichier.';
        }
    }

    return $file;
    }
    add_filter('wp_handle_upload_prefilter', 'limiter_taille_image');

?>