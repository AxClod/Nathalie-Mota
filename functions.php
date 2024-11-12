<?php

function theme_script() {
        
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        // Charger jQuery
        wp_enqueue_script('jquery');
        // Charger le fichier script.js
        wp_enqueue_script('script-personnalise', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
    }
    add_action('wp_enqueue_scripts', 'theme_script');
    
    
    function register_my_menus() {
        register_nav_menus( array(
            'header' => 'Menu principal',
            'footer' => 'Menu pied de page',
        ) );
    }
    add_action( 'init', 'register_my_menus' );


function ajouter_classe_bouton_contact($classes, $item, $args) {
    if ($item->ID == 14) { 
        $classes[] = 'bouton-contact';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'ajouter_classe_bouton_contact', 10, 3);

?>