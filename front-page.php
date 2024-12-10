<?php get_header(); // Inclut le header du thème // ?>
    <main>

<!-- section hero -->

    <div>
        <?php get_template_part('/template-parts/index-hero'); ?>
    </div>

<!--  section catalogue --> 
    <section id="section-catalogue" class="container-photo align-left">

    <?php
    $categories = get_terms(array(
        'taxonomy' => 'categorie',
        'hide_empty' => true,
    ));

    $formats = get_terms(array(
        'taxonomy' => 'formats',
        'hide_empty' => true,
    ));

    $args = array(
        'post_type' => 'photos',
        'orderby' => 'date',
        'order' => 'ASC',
        'posts_per_page' => 8,
        'paged' => 1,
    );
    ?>
        <!--  Les filtres --> 
        <section class="filtre">
            <form id="form-filters">
                    <!-- Filtre catégorie -->
                    <div class="filtres-gauche">
                        <label for="categories"></label>
                        <select name="categories" id="ajax_call_categories" size=1>
                            <option value="">Catégories</option>
                            <?php
                            if (!empty($categories) && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    $category_value = $category->slug;
                                    $category_name = $category->name;
                                    echo '<option value="' . $category_value . '">' . $category_name . '</option>';
                                }
                            }
                        ?>
                        </select>

                    <!-- Filtre formats -->
                        <label for="formats"></label>
                        <select name="formats" id="ajax_call_formats" size=1>
                            <option value="">Formats</option>
                            <?php
                            if (!empty($formats) && !is_wp_error($formats)) {
                                foreach ($formats as $format) {
                                    $format_value = $format->slug;
                                    $format_name = $format->name;
                                    echo '<option value="' . $format_value . '">' . $format_name . '</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>

                <div class="filtres-droite">
                    <!-- Filtre trier par -->
                        <label for="dates"></label>
                        <select name="dates" id="ajax_call_dates" size=1>
                            <option value="">Trier par</option>
                            <option value="DESC">Les plus récentes</option>
                            <option value="ASC">Les plus anciennes</option>  
                        </select>
                </div>        
            </form>
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