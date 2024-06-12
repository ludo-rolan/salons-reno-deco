<div id="carousel-gallery-generic" class="carousel slide" data-ride="carousel">
  <div class="carousel-content">
    
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <?php 


	$rw_large = 'rw_large' ;

	if(is_dev('optimiser_les_images_mobile_3459') && rw_is_mobile() && existe_image_size('rw_medium')){
		$rw_large = 'rw_medium' ;
	}


      $rw_large = apply_filters('home-diaporama-v2_rw_large', $rw_large) ;
      global $posts_exclude ;
		$i=0 ;
    	foreach ($diaporama_accueil as $post) { 
			
			setup_postdata($post);
			$posts_exclude = RW_Utils::add_value_to_array(get_the_ID(), $posts_exclude); 
			$slug_item= RW_Category::get_origin_cat(get_the_ID(),'diaporama-accueil');
			$excerpt = mini_excerpt_for_lines(50, 2);
			$link_attr = apply_filters('link_attr','',$post->ID) ;

			$item_attribute = apply_filters('home_diaporama_v2_item_attribute', '') ;

		?>
            <div class="item item-<?php echo $slug_item; ?> <?php echo ($i==0)?'active':''; ?>" <?php echo $item_attribute; ?> > 
            	<?php if(get_param_global('image_diaporama_link')){  ?>

            	<a href="javascript:void(0);" title="<?php echo $post->post_title ;?>" data-href="<?php echo get_permalink(); ?>" <?php echo $link_attr ?>>

            	<?php } ?>
            	
                 <?php 	
                 	$img_attr = array('class'=>'img-responsive');
                 	if(!is_dev('activer_le_lazyload_sur_le_home_diapos_3459')){
                 		$img_attr['data-lazyloading'] =   'false' ;
                 	}

                 	$img_attr = apply_filters('attributes_image_home_diaporama', $img_attr, $post);
             		echo get_the_post_thumbnail($post, $rw_large, $img_attr);

             		$picto_content_type_carousel = apply_filters('picto_content_type_carousel', '');
					echo $picto_content_type_carousel;
                 ?>
            	<?php if(get_param_global('image_diaporama_link')){  ?>
                </a>
            	<?php } ?>
                <div class="carousel-caption">
                    <div class="col-sm-12 col-md-12">
	                    <div class="pull-title">
	                        <?php 
	                        if( !get_param_global('hide_hp_diapo_v2_tags') ){
		                        echo RW_Category::get_menu_cat_link($post, 'info_cat', false, false, true, false);
	                        }
	                        if(get_param_global('show_desc_diapo'))
	                        	echo "<div class='block'>";
	                        
	                        ?>

	                        <h2 >	
		                        <a href="<?php echo get_permalink(); ?>" <?php echo $link_attr ?>>							
		                        	<?php $title = apply_filters('carousel-gallery-title', get_the_title() ,  get_the_ID()); 
		                        		echo $title;
		                        	?>	
		                        </a>							
	                        </h2>
	                        <?php
	                        if(get_param_global('show_desc_diapo')){
	                        	echo '<p>'.$excerpt.'</p>';

	                        	echo "</div>";
	                        }

	                        ?>
	                    	<?php do_action('display_carousel_more_details', $slug_item); ?>
	                    </div>
                    </div>
                </div>
            </div>
		  <?php
		  $i++;
		} ?>
    </div>
    <!-- Controls -->
	  <a class="left carousel-control visible-xs" href="#carousel-gallery-generic" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left"></span>
	  </a>
	  <a class="right carousel-control visible-xs" href="#carousel-gallery-generic" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right"></span>
	  </a>
    <!-- Controls --> 
    <!--<a class="left carousel-control" href="#carousel-gallery-generic" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-gallery-generic" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> -->
  <!-- Indicators -->
  <?php do_action('after_carousel_inner_home_diapo_v2', $diaporama_accueil); ?>

</div>
</div>