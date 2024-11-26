<?php get_header(); // Inclut le header du thème // ?>
    <main>
    <div>
        <?php get_template_part('/template-parts/index-hero'); ?>
    </div>

    <section id="section-catalogue" class="container-photo align-left">
    <div class="container-bloc-photo" data-page="1">
    <?php

    $pagin = get_query_var('paged') ? get_query_var('paged') : 1;
            $args_photos = array(
                'post_type' => 'photos',
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'ASC', 
            );

            $catalogue_photos = new WP_Query($args_photos);

            if ($catalogue_photos->have_posts()) {
                while ($catalogue_photos->have_posts()) {
                    $catalogue_photos->the_post();

                    // structure du catalogue
                    get_template_part('template-parts/bloc-photo');
                }
                wp_reset_postdata();
            }
            ?>
        </div>

        <div id="load-more_container">
            <button id="load-more" class="load-more_bouton">Charger plus</button>
        </div>

    </main>
<?php get_footer(); ?>
</body>
</html>