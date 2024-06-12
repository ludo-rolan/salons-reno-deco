<?php
$post=$posts[0];
?>
<h2 class="cat_big_title">
	<span>
		<?php echo $category_name;?> 
	</span>
</h2>
<div id="results" class="list-large-items">  		
	<div id="homeBody" class="" >
		<div class="row items-posts">
			<div class="col-xs-12 col-sm-12 item-post post-video" id="<?php echo get_the_ID(); ?>">
			   <div class="thumbnail">
			      <div class="thumbnail-visu">
			         <a title="<?php echo get_the_title($post->ID);?>" class="link-post" href="javascript:void(0);" data-href="<?php echo get_permalink($post->ID);?>">
			            <?php echo get_the_post_thumbnail($post->ID,'rw_medium'); ?>
			         </a>
			         <a class="info_cat info_cat maison-travaux" href="<?php echo $category_link ?>"><?php echo $category_name; ?></a>		
			      </div>
			      <div class="caption">
			         <div class="caption_wrapper">
			            <span class="info_link"><?php echo get_the_date(get_param_global('date_format'),$post->ID); ?></span>
			            <h3>	
			               <a title="<?php echo get_the_title($post->ID); ?>" href="<?php echo get_permalink($post->ID);?>">		
			              		<?php echo mini_text_for_lines(get_the_title($post->ID), 30, 2); ?>	
			               </a>                	
			            </h3>
			         </div>
			      </div>
			   </div>
			</div>
		</div>
	</div>
</div>