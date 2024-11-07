<?php
    function register_my_menus() {
        register_nav_menus( array(
            'header' => 'Menu principal',
            'footer' => 'Menu pied de page',
        ) );
    }
    add_action( 'init', 'register_my_menus' );
    function theme_script() {
        
        wp_enqueue_style( 'style', get_stylesheet_uri() );
    }
    add_action('wp_enqueue_scripts', 'theme_script');
?>