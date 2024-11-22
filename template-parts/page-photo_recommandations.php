<div class="galerie-post">
    <article>
            <div class="page-photo_recommandations__photo_img"> <?php the_post_thumbnail(); ?>
                    <div class="hover-photo hover-photo-recommandations">
                            <a href="#">
                                <img data-src="<?php the_post_thumbnail_url(); ?>" data-postId="<?php echo $post->ID; ?>" class="icone-plein-ecran icone-plein-ecran-recommadations" src="<?php echo get_template_directory_uri(); ?>/assets/images/Icon_fullscreen.png" alt="Icone plein écran">
                            </a>

                            <a class="" href="<?php the_permalink(); ?>">
                                <img class="icone-details-photo icone-details-photo-recommadations" src="<?php echo get_template_directory_uri(); ?>/assets/images/Icon_eye.png" alt="Icone détails photo">
                            </a>

                            <p class="contenu-ref"><?= get_field('reference'); ?></p>
                            <p class="contenu-categorie"><?php the_terms($post->ID, 'categorie') ?></p>
                        
                    </div>
                </div>

    </article>
</div>