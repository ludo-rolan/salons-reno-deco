<?php
	$first_postID = get_option('hp_options_first_post_actu');
    if(!empty($first_postID)){
        $first_post = get_post($first_postID);
        $excluded_posts = (!empty($first_post))? array($first_post->ID) : array(); 
    }
   
    $categorie_link = get_category_link($current_cat);
    $args = array(
        'showposts' => $nb_posts,
        'category' => $categorie->term_id, 
        'post__not_in' => $excluded_posts,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $posts = get_posts($args);
   
    if(!empty($first_post))  array_unshift($posts,$first_post);

    ?>
    <div class="actu-bloc-posts" data-hash-target="#<?php echo $current_cat->slug; ?>">
        <div class="actu-bloc_head">
            <h2 class="actu-bloc_title">
                <a href="<?php echo $categorie_link; ?>"><?php echo $current_cat->name; ?></a>
            </h2>
        </div>
        <div class="row">
            <?php
            if( !empty($posts) ){
                
                foreach ($posts as $post) {
                    $is_first_post = (!empty($first_postID) && ($post->ID==$first_postID));
                    $col_param =($is_first_post)? '12' : '6';
                    setup_postdata($post);
                    ?>
                    <div class="post-card  col-xs-<?php echo $col_param;?>">
                        <?php include(locate_template('include/templates/actus_card_post.php')); ?>
                    </div>
                    <?php 
                    wp_reset_postdata();
                }
            }
            ?>
        </div>
        <a href="<?php echo $categorie_link; ?>" class="btn-display-all">Toutes Les actualités</a>
    </div>