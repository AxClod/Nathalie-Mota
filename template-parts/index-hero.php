<section class="container-hero">
    <?php
$args = array(
    'post_type'      => 'photos', // Type de contenu personnalisé
    'orderby'        => 'rand',  // Tri aléatoire
    'posts_per_page' => 1,       // Un seul post
    'tax_query'      => array(   // Utilisation d'un tableau pour tax_query
        array(
            'taxonomy' => 'formats', 
            'field'    => 'slug',  
            'terms'    => 'paysage', 
        ),
    ),
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); // URL de l'image
        ?>
        <div class="container-hero_image" style="background-image: url('<?php echo esc_url($image_url); ?>');">
        <h1 class="container-hero_title">Photographe event</h1>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
else :
    echo '<p>Aucune photo disponible.</p>';
endif;
    ?>
</section>