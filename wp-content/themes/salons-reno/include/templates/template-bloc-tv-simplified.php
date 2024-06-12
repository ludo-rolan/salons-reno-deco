<h2 class="cat_big_title"><span><?php echo $category_name;?> </span></h2>
<div id="results" class="list-large-items">  
			
	<div id="homeBody" class="" >
		<div class="row items-posts">


			<?php
			$i=0;
				foreach ($posts as $post) {
					$i++ ;
					global $posts_exclude;
					$posts_exclude = RW_Utils::add_value_to_array($post->ID, $posts_exclude);
			?>

					<div class="col-xs-12 col-sm-6 item-post post-video" id="<?php echo $post->ID; ?>">
					   <div class="thumbnail">
					      <div class="thumbnail-visu">
					         <a title="<?php echo $post->post_title;?>" class="link-post" href="javascript:void(0);" data-href="<?php echo get_permalink($post->ID);?>">
					            <?php echo get_the_post_thumbnail($post,'wp_medium'); ?>
					         </a>
					         <a class="info_cat info_cat maison-travaux" href="<?php echo $category_link ?>"><?php echo $category_name; ?></a>		
					      </div>
					      <div class="caption">
					         <div class="caption_wrapper">
					            <span class="info_link"><?php echo get_the_date(get_param_global('date_format'),$post->ID) ; ?></span>
					            <h3>	
					               <a title="<?php echo $post->post_title; ?>" href="<?php echo get_permalink($post->ID);?>">		
					              		<?php echo mini_text_for_lines($post->post_title, 30, 2) ; ?>	
					               </a>                	
					            </h3>
					         </div>
					      </div>
					   </div>
					</div>
			<?php } ?>
		</div>
	</div>
</div>