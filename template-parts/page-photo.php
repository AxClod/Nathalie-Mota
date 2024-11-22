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

		<div class="page-photo_photo-container">
            <div class="photo">
                <?php if (has_post_thumbnail()) : ?>
                    <img data-src="<?php the_post_thumbnail_url(); ?>" src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>"/>
                <?php endif; ?>
            </div>
            <!-- Div pour le hover -->
            <div class="hover-photo">
                <a href="#"><img id="icone-plein-ecran" class="icone-plein-ecran" src="<?php echo get_template_directory_uri(); ?>/assets/images/Icon_fullscreen.png" alt="Icone plein écran"></a>
            </div>
		</div>
    </section>


<!-- section précédent/suivant -->

<section class="page-photo_contact">
        <div class="page-photo_contact__bouton">
            <p class="texte">Cette photo vous intéresse ?</p>
            <input id="btn-contact" class="bouton-contact page-photo_contact__btn" type="button" value="Contact">
        </div>
        
        <div class="page-photo_contact__navigation">
            <?php
                $prevPost = get_previous_post();
                $nextPost = get_next_post();
            ?>

            <div class="arrows">
                <?php if (!empty($prevPost)) : 
                        $prevThumbnail = get_the_post_thumbnail_url( $prevPost->ID );
                        $prevLink = get_permalink($prevPost); ?>
                        <a id="arrow-left" href="<?= $prevLink; ?>">
                            <img class="arrow arrow-gauche" src="<?= get_template_directory_uri(); ?>/assets/images/arrow-left.png" alt="Flèche pointant vers la gauche" />
                        </a>
                        <?php endif;
                        if (!empty($nextPost)) :
                            $nextThumbnail = get_the_post_thumbnail_url( $nextPost->ID );
                            $nextLink = get_permalink($nextPost); ?>
                            <a href="<?= $nextLink; ?>">
                                <img id="arrow-right" class="arrow arrow-droite" src="<?= get_template_directory_uri(); ?>/assets/images/arrow-right.png" alt="Flèche pointant vers la droite" />
                            </a>
                <?php endif; ?>
            </div>
            
            <div class="div-preview">
            <div class="preview">
                <?php if (!empty($prevPost)) :
                        $prevThumbnail = get_the_post_thumbnail_url( $prevPost->ID );
                        $prevLink = get_permalink($prevPost); ?>
                        <a href="<?= $prevLink; ?>">
                        <img id="previous-image" class="previous-image" src="<?php echo $prevThumbnail; ?>" alt="Prévisualisation image précédente">
                        </a>
                <?php endif; ?>
            </div>

            <div class="preview">
                <?php if (!empty($nextPost)) :
                            $nextThumbnail = get_the_post_thumbnail_url( $nextPost->ID );
                            $nextLink = get_permalink($nextPost); ?>
                            <a href="<?= $nextLink; ?>">
                            <img id="next-image" class="next-image" src="<?php echo $nextThumbnail; ?>" alt="Prévisualisation image suivante">
                            </a>
                <?php endif ?>
            </div>
            </div>
        </div>
  </section>


<!-- section recommandations -->

<div class="page-photo_recommandations">

    <h3>VOUS AIMEREZ AUSSI</h3>
    <div class="page-photo_recommandations__photo">

        <?php   

            $categorie = strip_tags(get_the_term_list($post->ID, 'categorie'));

            // 1. On définit les arguments pour définir ce que l'on souhaite récupérer 
            $args = array(
                'post_type' => 'photo',
                'posts_per_page' => 2,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'categorie',
                        'field' => 'slug',
                        'terms' => $categorie,
                    ),
                ),
            );

            // 2. On exécute la WP Query
            $my_query = new WP_Query( $args );

            // 3. On lance la boucle !
            if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();?> 

                <?php get_template_part('templates_parts/page-photo_recommandations'); ?>
        
            <?php
            endwhile;
            endif;

            // 4. On réinitialise à la requête principale (important)
            wp_reset_postdata();

        ?>

    </div>

</div>
