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
    
    $categories = $_POST['categories'];
    $formats = $_POST['formats'];
    $dates = $_POST['dates'];
    $paged = $_POST['paged'];

    if($categories != "") {
        $argCategories = array(
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => $categories,
        );
    } else {
        $argCategories = null;
    }

    if( $formats != "") {
        $argFormats = array(
            'taxonomy' => 'formats',
            'field' => 'slug',
            'terms' => $formats,
        );
    } else {
        $argFormats = null;
    }

    $query = new WP_Query([
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
        'meta_key' => 'annee',
        'tax_query' => array(
            $argCategories ?? "",
            $argFormats ?? "",
        ),
        'meta_key' => 'annee',
            'order' => $dates,
            'orderby' => 'meta_value'
    ]);


    if( $query -> have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post(); 
            $response = get_template_part('templates_parts/bloc-photo');
        } 
        $my_html = ob_get_contents();
        ob_end_clean();
        $response = [
            'my_html' => $my_html,
            'found_posts' => $query->found_posts
        ];
        
    } else {
        $response = false;
    }

    wp_send_json($response);
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

?>