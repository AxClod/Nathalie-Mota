<?php
// page.php

get_header(); // Inclut l'en-tête du thème
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            // Afficher le titre de la page
            the_title('<h1 class="entry-title">', '</h1>');
            // Afficher le contenu de la page
            the_content();
            // Si vous souhaitez afficher les commentaires, vous pouvez ajouter la fonction comment_template() ici
        endwhile;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // Inclut le pied de page du thème