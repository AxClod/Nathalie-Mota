<!-- section photo de la single-photo -->

<div class="page-photo">
    <section class="page-photo_section-bloc">
		<ul class="page-photo_section-bloc_info">
            <li> <h1> <?= the_title() ?> </h1> </li>
			<li>
				<p class="detail-info-photo">RÉFÉRENCE : <span id="reference"><?= get_field('reference'); ?></span></p>
			</li>
			<li>
				<p class="detail-info-photo">CATÉGORIE : <?php the_terms($post->ID, 'categorie') ?></p>
			</li>
			<li>
				<p class="detail-info-photo">FORMAT : <?php the_terms($post->ID, 'formats') ?> </p>
			</li>
			<li>
				<p class="detail-info-photo">TYPE : <?= get_field('type'); ?></p>
			</li>
            <li>
            <p class="detail-info-photo">ANNÉE : <?= get_the_date('Y'); ?></p>
            </li>
        </ul>

        <div class="img-single">
            <div class="img-single__overlay">
                <?php if (has_post_thumbnail()) : ?>
                    <img data-src="<?php the_post_thumbnail_url(); ?>" src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>"/>
                <?php endif; ?>
                <div class="overlay-single">
                    <img src="<?php echo get_theme_file_uri() .'/assets/images/Icon_fullscreen.png';?>" class="fullscreen-icon" alt="Voir en plein écran">
                </div>
            </div>
        </div>
    </section>


<!-- section contact -->

    <section class="page-photo_contact">
    <div class="page-photo_contact__bouton">
        <p class="texte">Cette photo vous intéresse ?</p>
        <input 
            id="btn-contact" 
            class="bouton-contact" 
            type="button" 
            value="Contact" 
            data-reference="<?php echo get_field('reference'); ?>" 
        />
    </div>
        
        <?php
                //Flèches précédent et suivant
                $next_post = get_next_post();
                $previous_post = get_previous_post();

                // Si on est sur la dernière photo, définir $next_post comme le premier post
                if (empty($next_post)) {
                    $args = array(
                        'posts_per_page' => 1,
                        'order'          => 'ASC',
                        'post_type'      => 'photos' // 
                    );
                    $first_post = get_posts($args);
                    if (!empty($first_post)) {
                        $next_post = $first_post[0];
                    }
                }

                // Si on est sur la première photo, définir $previous_post comme le dernier post
                if (empty($previous_post)) {
                    $args = array(
                        'posts_per_page' => 1,
                        'order'          => 'DESC',
                        'post_type'      => 'photos' // 
                    );
                    $last_post = get_posts($args);
                    if (!empty($last_post)) {
                        $previous_post = $last_post[0];
                    }
                }
            ?>

            <div class="page-photo_contact__navigation-arrows">
                <?php if (!empty($previous_post) || !empty($next_post)) { ?>
                    
                    <!-- Bloc pour la photo précédente -->
                    <div class="arrow-block">
                        <div class="container-miniature container-miniature-previous">
                            <?php
                                if (!empty($previous_post)) {
                                    $thumbnail_ID_prev = get_post_thumbnail_id($previous_post->ID);
                                    if ($thumbnail_ID_prev) {
                                        echo wp_get_attachment_image($thumbnail_ID_prev, 'thumbnail', false, ['class' => 'container-miniature-previous__img-arrows']);
                                    }
                                }
                            ?>
                        </div>
                        <?php if (!empty($previous_post)) { ?>
                            <a href="<?php echo get_permalink($previous_post->ID) ?>"><img class="arrow-left" src="<?php echo get_theme_file_uri() .'/assets/images/arrow-left.png';?>" alt="Flèche précédent"></a>
                        <?php } ?>
                    </div>

                    <!-- Bloc pour la photo suivante -->
                    <div class="arrow-block">
                        <div class="container-miniature container-miniature-next">
                            <?php
                                if (!empty($next_post)) {
                                    $thumbnail_ID_next = get_post_thumbnail_id($next_post->ID);
                                    if ($thumbnail_ID_next) {
                                        echo wp_get_attachment_image($thumbnail_ID_next, 'thumbnail', false, ['class' => 'container-miniature-next__img-arrows']);
                                    }
                                }
                            ?>
                        </div>
                        <?php if (!empty($next_post)) { ?>
                            <a href="<?php echo get_permalink($next_post->ID) ?>"><img class="arrow-right" src="<?php echo get_theme_file_uri() .'/assets/images/arrow-right.png';?>" alt="Flèche suivant"></a>
                        <?php } ?>
                    </div>
                    
                <?php } ?>
            </div>
    </section>


<!-- section recommandations -->

    <div class="container-photo">

        <p class="container-photo__title"> vous aimerez aussi </p>
        <div class="container-bloc-photo">

        <?php
            $categories = get_the_terms(get_the_ID(), 'categorie');
                if ($categories && !is_wp_error($categories)) {
                    $category_ids = wp_list_pluck($categories, 'term_id');

                    $args = array(
                        'post_type' => 'photos',
                        'posts_per_page' => 2,
                        'orderby' => 'rand',
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'categorie',
                                'field' => 'term_id',
                                'terms' => $category_ids,
                            ),
                        ),
                    );

                        $photos_query = new WP_Query($args);

                        if ($photos_query->have_posts()) {
                            while ($photos_query->have_posts()) {
                                $photos_query->the_post();
                                $photo_retrieval = get_field('photos');
                                ?>
                                
                                <?php get_template_part('template-parts/bloc-photo'); ?>

                                <?php
                            }
                            wp_reset_postdata();
                        }
                    }
                ?>

    </div>

</div>
