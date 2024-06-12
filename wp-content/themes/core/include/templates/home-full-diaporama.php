<?php
	$balise_seo_title = get_balisage_seo('home_full_diaporama_title', 'h2');
	$show_desc_diapo  = get_param_global('show_desc_diapo') ;

?>
<div id="custom-full-carousel-type-1" class="carousel row slide" data-ride="carousel">
	<!-- Wrapper for slides -->
	<div class="carousel-inner">
		<?php 
		global $posts_exclude ;
		$i=0 ;
		$thumbs_block=array();
		$thumbs_block_="";
		foreach ($diaporama_accueil as $post) { 
			setup_postdata($post);
			$posts_exclude = RW_Utils::add_value_to_array(get_the_ID(), $posts_exclude);
			$slug_item = RW_Category::get_origin_cat(get_the_ID(),'diaporama-accueil');
			$excerpt = mini_excerpt_for_lines(50, 2);

			$link_attr = apply_filters('link_attr','',$post->ID) ;
		?>
		    <div class="item item-<?php echo $slug_item; ?> <?php echo ($i==0)?'active':''; ?>"> 
		        
		         <?php 
		         echo '<div class="carousel_img">';
		         $image_format = apply_filters("diapo_full_format", 'rw_diapo_full');
		         $args_ = array('class'=>'img-responsive');
		         echo get_the_post_thumbnail(get_the_ID(), $image_format, $args_); 
		         echo '</div>';

		         $no_desc_class = ($excerpt || !$show_desc_diapo) ? '' : ' no-desc-post';
		         $have_excerpt = $excerpt ? ' have_excerpt' : '';

		         ?> 
		        
		        <div class="carousel-caption<?php echo $have_excerpt; ?>">
		        	<?php do_action('before_carousel_description', $post); ?>
		            <div class="col-sm-12 col-md-12">
		                <div class="pull-title<?php echo $no_desc_class; ?>">
		                    <?php
		                    if($show_desc_diapo)
		                    	echo "<div class='block'>";
							
							echo "<div class='block_categorie_title'>".RW_Category::get_menu_cat_link($post, 'info_cat', false, false, true)."</div>";			

		                    ?>

		                    <<?php echo $balise_seo_title;?> class="info_title">	
		                        <a href="<?php echo get_permalink(); ?>" <?php echo $link_attr ?> >	 						
		                        	<?php $title = apply_filters('carousel-gallery-title', get_the_title() ,  get_the_ID()); 
		                        		echo $title;
		                        	?>
		                        </a>							
		                    </<?php echo $balise_seo_title;?>>
		                    <?php
		                    if($show_desc_diapo && $excerpt){
		                    	echo '<a href="'. get_permalink($post) .'"><div class="carousel_excerpt"><p>'.$excerpt.'</p></div></a>';
		                    }
		                    if($show_desc_diapo){
		                    	echo "</div>";
		                    }

		                    ?>
		                </div>
		            </div>
		        </div>
		    </div>
		  <?php
		  	$thumb_format = apply_filters("diapo_vignette_format", 'rw_thumb');
		  	$args_ = array('class'=>'img-responsive');
		  	$thumbs_block[$i]= get_the_post_thumbnail(get_the_ID(), $thumb_format, $args_); ;
		  $i++;
		  wp_reset_postdata();
		} 
		?>
	</div>
	<!-- Controls -->
	<a class="left carousel-control" href="#custom-full-carousel-type-1" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left"></span>
	</a>
	<a class="right carousel-control" href="#custom-full-carousel-type-1" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right"></span>
	</a>
</div>