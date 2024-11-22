<?php
// single-photo.php

get_header(); // en-tête du thème
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
            $args = array(
                'post_type' => 'photos', 
                'p' =>get_the_ID(), 
            );

            $photo_request = new WP_Query($args);


            if ($photo_request->have_posts()) {
                while ($photo_request->have_posts()) {
                    $photo_request->the_post();

                    get_template_part('template-parts/page-photo');
                }

                wp_reset_postdata();
            } else {
                echo 'Aucune photo trouvée.';
            }
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // pied de page du thème