<?php get_header(); // Inclut le header du thème ?>
    <main>
    <div>
        <?php get_template_part('template-parts/index-hero'); ?>
    </div>
        <?php
        while (have_posts()) :
            the_post();
            // Afficher le titre de la page
            the_title('<h1>', '</h1>');
            // Afficher le contenu de la page
            the_content();
            // Si vous souhaitez afficher les commentaires, vous pouvez ajouter la fonction comment_template() ici
        endwhile;
        ?>
    </main><!-- #main -->

<?php get_footer(); // Inclut le pied de page du thème ?>