<?php

function theme_script() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );

    // Charger jQuery
    wp_enqueue_script('jquery');

    // Charger le fichier script.js
    wp_enqueue_script('script-personnalise', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    //Déclarer le fichier pour les requêtes ajax
    wp_enqueue_script(
        'pagination-infinie', 
        get_template_directory_uri() . '/js/pagination-infinie.js', array('jquery'), 
        '1.0.0', 
        true
     );
     // Passer les données de PHP vers Javascript de manière sécurisée
     wp_localize_script(
         'pagination-infinie', 
         'pagination-infinie_js', 
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
// Filtres
function nathaliemota_request_filtered() {
    $categories = isset($_POST['categories']) ? sanitize_text_field($_POST['categories']) : '';
    $formats = isset($_POST['formats']) ? sanitize_text_field($_POST['formats']) : '';
    $dates = isset($_POST['dates']) ? sanitize_text_field($_POST['dates']) : 'ASC';
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
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $formats,
        ];
    }

    $query_args = [
        'post_type' => 'photos',
        'posts_per_page' => 8,
        'paged' => $paged,
        'orderby' => 'meta_value',
        'meta_key' => 'annee',
        'order' => $dates,
    ];

    if (!empty($tax_query)) {
        $query_args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('templates-parts/bloc-photo');
        }
        $my_html = ob_get_clean();

        $response = [
            'my_html' => $my_html,
            'found_posts' => $query->found_posts,
        ];
    } else {
        $response = [
            'my_html' => '<div>Aucune photo ne correspond à ces critères.</div>',
            'found_posts' => 0,
        ];
    }

    wp_send_json($response);
    wp_die();
}
add_action('wp_ajax_request_filtered', 'pagination-infinie_request_filtered');
add_action('wp_ajax_nopriv_request_filtered', 'pagination-infinie_request_filtered');