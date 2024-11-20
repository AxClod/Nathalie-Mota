<?php 
    $thumbnail_url = get_the_post_thumbnail_url();
?>

<div class="catalogue-image">
    <img src="<?php echo $thumbnail_url;?>" alt="<?php the_title_attribute(); ?>">
    <div class="catalogue-image_overlay">
        <a href="<?php the_permalink(); ?>"> 
            <img src="<?php echo get_theme_file_uri() .'/assets/images/Icon_eye.png';?>" class="eye-icon" alt="Voir les infos">
        </a>
        <img src="<?php echo get_theme_file_uri() .'/assets/images/Icon_fullscreen.png';?>" class="fullscreen-icon" alt="Voir en plein écran">
        <span class="catalogue-image_overlay_title"><?php the_title(); ?></span>
        <span class="catalogue-image_overlay_category">
            <?php
                $terms = wp_get_post_terms( get_the_ID(), 'categorie' ); 
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                    echo $terms[0]->name;
                }
            ?>
        </span>
    </div>
</div>