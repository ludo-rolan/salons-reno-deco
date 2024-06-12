<?php get_header(); ?>
<div class="col-xs-12 page-landing-billetterie" >

    <?php 
        include(locate_template('include/templates/billetterie-visual-header.php'));
        include(locate_template('include/templates/billetterie-chiffres.php'));
        include(locate_template('include/templates/billetterie-partners.php'));
        
        include(locate_template('include/templates/billetterie-carousel.php'));  
    ?>

</div>

<?php
get_footer();
