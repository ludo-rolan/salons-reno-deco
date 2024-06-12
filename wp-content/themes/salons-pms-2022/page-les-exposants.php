<?php get_header(); ?>
<h2 class="page-title"><?php the_title(); ?></h2>
<div id="content"class="col-xs-12 col-md-8">
    <div class="page-content page-listing" style="padding:0">

        <div class="intro" style="text-align:center; margin:0 0 50px 0;">
            <p>
                <?php echo get_option('text_page_exposant' , '') ?>
            </p>
            <div class="search-form-container">
                <div class="search-form">
                    <input type="text" id="search-exposants" placeholder="Rechercher un exposant" />
                    <span id="search-exposants-submit" class="search-icon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        <div class="row" id="cards-listing">
        <?php 
            //get all post of cpt exposant 
            $args = array(
                'post_type' => 'exposant',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            );
            $exposants = get_posts($args);
            $count = 0;
            // var_dump($exposants)//
            foreach ($exposants as $exposant) {
                $hidden_expo = false;
                if ($count >9) {
                    $hidden_expo = true;
                }
                include(locate_template("include/templates/exposant-card.php"));
                $count++;
            }
        ?>
        </div>        
        <div class="col-xs-12 text-center">
            <button class="see-more-btn">Voir Plus</button>
        </div>
    </div>                              
</div>

<script>
    //search exposant by name, and display only the exposant with the same name (toogle class hidden)
    jQuery(document).ready(function($) {
        var search_exposants = $('#search-exposants');
        var search_exposants_submit = $('#search-exposants-submit');
        var cards_listing = $('#cards-listing');
        var see_more_btn = $('.see-more-btn');
        var hidden_class = 'hidden';

        search_exposants.on('keyup', function(e) {
            var search_value = $(this).val();
            var exposants = cards_listing.find('.exposant-card');
            //order by first letter of search value
            var search_value_first_letter = search_value.charAt(0).toUpperCase();
            exposants.each(function(index, el) {
                var exposant_title = $(this).find('h3').text();
                //show or hide with fadein animation the exposant card
                if (exposant_title.toLowerCase().indexOf(search_value.toLowerCase()) == 0) {
                    $(this).fadeIn();
                    $(this).removeClass(hidden_class);
                } else {
                    $(this).addClass(hidden_class);
                    $(this).fadeOut();
                }
            });
        });

        //show all exposant card when click on see more button and hide the button
        see_more_btn.on('click', function(e) {
            var exposants = cards_listing.find('.exposant-card');
            exposants.each(function(index, el) {
                $(this).fadeIn();
                $(this).removeClass(hidden_class);
            });
            see_more_btn.addClass(hidden_class);
            // clear search input
            search_exposants.val('');
        });

    });
</script>


<?php
get_sidebar();
get_footer();
