<?php

global $post;
			
$cat_id = "";

$cat = get_permalinked_category($post->ID, true);

if(isset($cat->term_id)) {
	$cat_id = $cat->term_id;
}

$args = array( 
	'numberposts' => '2',
    'category' => $cat_id,
    'post__not_in' => array($post->ID),
);

$posts = get_posts($args); 

if(count($posts)) {
	?>
	<div class="read_more_block">
		<h2 class="read_more_block_title default-title"><?php echo __( apply_filters('read_more_title', 'Sur le même sujet') ,  REWORLDMEDIA_TERMS ); ?> </h2>
		<div class="row">
			<?php
			$where = 'read-more-block';
			foreach ($posts as $post) {
				echo '<div class="post col-xs-6">';
				include locate_template('include/templates/block_post_readmore.php');
				echo '</div>';
			}
			?>
		</div>
	</div>
	<?php
}