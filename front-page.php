<?php get_header(); // Inclut le header du thème // ?>
    <main>

<!-- section hero -->

    <div>
        <?php get_template_part('/template-parts/index-hero'); ?>
    </div>

<!-- Section catalogue -->
<section id="section-catalogue" class="container-photo align-left">
    <?php
    // Récupération des taxonomies
    $categories = get_terms(array(
        'taxonomy' => 'categorie',
        'hide_empty' => true,
    ));

    $formats = get_terms(array(
        'taxonomy' => 'formats',
        'hide_empty' => true,
    ));
    ?>

    <!-- Les filtres -->
    <section class="filtre">
        <form id="form-filters">
            <!-- Filtre catégories -->
            <div class="filtres-gauche">
                <div class="custom-select-wrapper" id="ajax_call_categories">
                    <div class="custom-select">
                        <div class="custom-select__trigger">
                            <span>Catégories</span>
                            <div class="arrow"></div>
                        </div>
                        <div class="custom-options">
                            <?php
                            if (!empty($categories) && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    echo '<span class="custom-option" data-value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Filtre formats -->
                <div class="custom-select-wrapper" id="ajax_call_formats">
                    <div class="custom-select">
                        <div class="custom-select__trigger">
                            <span>Formats</span>
                            <div class="arrow"></div>
                        </div>
                        <div class="custom-options">
                            <?php
                            if (!empty($formats) && !is_wp_error($formats)) {
                                foreach ($formats as $format) {
                                    echo '<span class="custom-option" data-value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</span>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trier par -->
            <div class="filtres-droite">
                <div class="custom-select-wrapper" id="ajax_call_dates">
                    <div class="custom-select">
                        <div class="custom-select__trigger">
                            <span>Trier par</span>
                            <div class="arrow"></div>
                        </div>
                        <div class="custom-options">
                            <span class="custom-option" data-value="DESC">Les plus récentes</span>
                            <span class="custom-option" data-value="ASC">Les plus anciennes</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</section>

    <!--  La grille de photos --> 
    <div class="container-bloc-photo" id="ajax_return">
        <?php
        // Récupération de la page courante pour la pagination
        $pagin = get_query_var('paged') ? get_query_var('paged') : 1;

        // Récupération des paramètres des filtres
        $categories = isset($_POST['categories']) ? $_POST['categories'] : '';
        $formats = isset($_POST['formats']) ? $_POST['formats'] : '';
        $dates = isset($_POST['dates']) ? $_POST['dates'] : 'DESC'; // Valeur par défaut
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1; // Valeur de la page

        // Arguments de la requête
        $args_photos = array(
            'post_type' => 'photos', 
            'posts_per_page' => 8,
            'paged' => $paged,
            'orderby' => 'date', 
            'order' => $dates,
            'tax_query' => array(
                'relation' => 'AND', 
            ),
        );

        // Ajout de la taxonomie pour le filtrage des catégories
        if (!empty($categories)) {
            $args_photos['tax_query'][] = array(
                'taxonomy' => 'categorie',
                'field'    => 'slug',
                'terms'    => $categories,
            );
        }

        // Ajout de la taxonomie pour le filtrage des formats
        if (!empty($formats)) {
            $args_photos['tax_query'][] = array(
                'taxonomy' => 'formats', 
                'field'    => 'slug',
                'terms'    => $formats,
            );
        }

        // Exécution de la requête
        $catalogue_photos = new WP_Query($args_photos);

        // Vérification des résultats
        if ($catalogue_photos->have_posts()) {
            while ($catalogue_photos->have_posts()) {
                $catalogue_photos->the_post();

                // Structure du catalogue
                get_template_part('template-parts/bloc-photo');
            }
            wp_reset_postdata();
        } else {
            // Afficher un message si aucune photo n'est trouvée
            echo '<div>Aucune photo trouvée.</div>';
        }
        ?>
    </div>

    <!--  Bouton charger plus --> 
    <div id="load-more_container">
        <button id="load-more" class="load-more_bouton">Charger plus</button>
    </div>

    </main>
<?php get_footer(); ?>
</body>
</html>