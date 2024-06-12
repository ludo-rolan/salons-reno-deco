<?php
if( !empty($sub_categories) ){
	foreach ($sub_categories as $categorie) {
		$posts = get_data_from_cache('sub_cat_posts_'.$categorie->term_id, 'home_rubrique', 60*60*24, function() use( $categorie, $nb_posts, $posts_type ) {
			global $posts_exclude;
			if( empty($posts_type) ) $posts_type = 'exposant';
			if( empty($nb_posts) ) $nb_posts = 6;
			$args = array(
				'showposts' => $nb_posts,
				'category' => $categorie->term_id, 
				'post__not_in' => $posts_exclude,
				'orderby' => 'date',
				'order' => 'DESC',
			);

			if( defined('_LOCKING_ON_') && _LOCKING_ON_ &&  $args_lock = get_locking_config('home', 'bloc_rubrique_'.$categorie->term_id)){
				return Locking::get_locking_ids($args_lock , $args);
			}else{
				return get_posts( $args);
			}
		});
		if( !empty($posts) ){
			$categorie_link = get_category_link($categorie);
			?>
			<div class="bloc-posts" data-hash-target="#<?php echo $categorie->slug; ?>">
				<div class="bloc_rubrique_head">
					<h2 class="default-title">
						<a href="<?php echo $categorie_link; ?>"><?php echo $categorie->name; ?></a>
					</h2>
					<?php if( isset($hide_read_more_link) && !$hide_read_more_link ){ ?>
					<a class="read_more" href="<?php echo $categorie_link; ?>">
						<?php echo ($posts_type == "eposant") ? _e('Voir tous les exposants') : _e('Voir tous les conseils'); ?>
					<?php } ?>
					</a>
				</div>
				<div class="row">
					<?php
					foreach ($posts as $post) {
						setup_postdata($post);
						?>
						<div class="post col-xs-12 col-sm-6">
							<?php include(locate_template('include/templates/block_post.php')); ?>
						</div>
						<?php 
						wp_reset_postdata();
					}
				?>
				<a href="<?php echo $categorie_link; ?>" class="col-xs-12 btn btn-lire-plus">Voir tous les articles</a>
				</div>
			</div> <!-- .bloc_exposants -->
			<?php
		}
	}
}