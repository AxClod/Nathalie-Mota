<?php get_template_part('template-parts/modale-contact'); ?>
<footer class="footer">
    <div class="footer__menu">
        <?php
            wp_nav_menu(array(
                'theme_location' => 'footer', 
                'container' => false, 
                'menu_class' => 'menu-footer',
            ));
            ?>
        <p>TOUT DROITS RÉSERVÉS</p>
    </div>
    <!-- lightbox -->
    <?php get_template_part('template-parts/lightbox'); ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>