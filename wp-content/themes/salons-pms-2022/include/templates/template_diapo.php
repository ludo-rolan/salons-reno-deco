<?php

		$content_diapo.= '<div id="large_diapo_rw" class="large-diapo">';
		$content_diapo = apply_filters('large_diapo_content_start', $content_diapo);
		$content_diapo.= "<div class='block_diapo'>
		<div class='visu_block'>";		 		
		$content_diapo = apply_filters('diapo_visu_block', $content_diapo);
		$content_diapo.= "<div class='list_thumbs'>";
		$content_diapo.='<div class="list_block">';
		$content_diapo.="<ul class='list-inline thumb_list'>";
		$i_gal = 0;
		foreach ($attachments as $diapo_attachment) {
			$class_active = ($i_gal == 0) ? " class='active'" : "";
			if(is_dev('generer_une_miniature_du_post_instagram_113730507') && !empty($diapo_attachment['gallery_insta'])){
				$class_active = ($i_gal == 0) ? " class='active instagram-diapo-thumbnail'" : " class='instagram-diapo-thumbnail'";
			}
			else{
				$class_active = ($i_gal == 0) ? " class='active'" : "";
			}

			$attrs = '' ;

			if(!empty($diapo_attachment["data"]) ){
				foreach ($diapo_attachment["data"] as $key => $value) {
					$attrs .= "data-$key='$value' " ;
				}
			 	$attrs = apply_filters('atts_first_diapo_img_tag', $attrs, $diapo_attachment );
				
			}

			/**
			*	Récupérer l'image de taille full 
			*	ticket #160311298 : **AUTO MOTO** | Pouvoir agrandir les images des diaporamas
			*	By bouhou@webpick.info
			**/
			if(get_param_global('pouvoir_agrandir_images_diaporama')) {
				if(!empty($diapo_attachment["full"][0])) {
					$attrs .= "data-full='". $diapo_attachment["full"][0] ."' ";
				}
			}
			/**
			*  End ticket #160311298
			**/

			$content_diapo.= "<li" . $class_active  . " $attrs  >";
			// $content_diapo.= "<img src='" . $attachment['gallery_thumb'][0] . "' alt='' />";
			$content_diapo.= "</li>";

			$i_gal++;
		}
		
		$content_diapo.= "</ul></div></div>";
		// $legende_diapo.='<div class="diapo-navigation">';
		$content_diapo.= "<span class='btn-navs _left' data-index='0' data-count='" . $attachments_count . "'>
			<i class='fa fa-arrow-left'></i>
		</span>
		<span class='btn-navs _right' data-index='1' data-count='" . $attachments_count . "'>
			<i class='fa fa-arrow-right'></i>
		</span>";
		// $content_diapo.= "</div>";
		// Legend diaporama
		$legende_diapo_v3  ='<figcaption>';
		
		$legende_diapo_v3 .='<aside class="widget widgetLegende">';
		$legende_diapo_v3 .='<div class="masc_legende">';
		$legende_diapo_v3 .='<ul class="lists_legend">';
		$i=1;
		foreach ($current_gallery_images as $at ){
			$legende_diapo_v3 .='<li>';
			if(get_param_global('show_gallery_caption_navs')){
				$legende_diapo_v3 .="<div class='caption-navs _left' data-index='0' data-count='" . ($attachments_count+1) . "'></div>";
				$legende_diapo_v3 .="<div class='caption-navs _right' data-index='1' data-count='" . ($attachments_count+1) . "'></div>";
			}

			/**
			*	Ajouter un '0' devant les nombres inférieur à 10 de pagination
			*	Ticket : 2201 : Projet Dubai | Intégration page article diaporama version EN
			*	By: bouhou@webpick.info
			**/
			$i = apply_filters('pagination_numerotation', $i);
			$attachments_count = apply_filters('pagination_numerotation', $attachments_count);
			/**
			*	End of ticket 2201
			**/

			$legende_diapo_v3 .='<span class="active_slide">'.$i.'/'.$attachments_count.'</span>';
			

			/*Dupliquer le titre dans tous les images si la case duplique titre est couche*/
			if(!empty($attr['titre_dupliquer']) && $attr['titre_dupliquer'] == "true"){
				$legende_diapo_v3 .= '<div class="legend_title">' . get_the_title($current_gallery_images[0]->ID) . "</div>";
			}else{
				$legende_diapo_v3 .= '<div class="legend_title">' . get_the_title($at->ID) . "</div>";

			}
			if( !get_param_global('hide_diapo_legende_excerpt') ){
				/*Dupliquer la légende dans tous les images si la case duplique legende est couche*/
				if(!empty($attr['extrait_dupliquer']) && $attr['extrait_dupliquer'] == "true"){
					$diapo_excerpt = $current_gallery_images[0]->post_excerpt;
				}else{
					$diapo_excerpt = $at->post_excerpt;
				}
				if( !empty($diapo_excerpt) ){
					if( !get_param_global('diapo_legend_keep_p_tag') ){
						$diapo_excerpt = preg_replace("/<\/p *>/", "</p></br>", $diapo_excerpt);
						$diapo_excerpt = preg_replace("/<\/?p[^>]*>/", "", $diapo_excerpt);
					}
					$diapo_excerpt = '<div class="legende_excerpt">'. $diapo_excerpt .'</div>';
					$legende_diapo_v3 .= do_shortcode( $diapo_excerpt );
				}
			}

			$legende_diapo_v3 .='</li>';
			$i++;
		}
		$legende_diapo_v3 .='</ul>';
		
		$legende_diapo_v3 .='</div>';
		$legende_diapo_v3 .='</aside>';
		$legende_diapo_v3 .='</figcaption>';
		$legende_diapo_v3 = apply_filters('after_img_legend', $legende_diapo_v3);
		if( get_param_global('legende_diapo_v3_bofore_img')){
			$content_diapo .= $legende_diapo_v3 ;
		}

		$content_diapo.= "<figure>";
		if(isset($attachments[0]["gallery_video"]) && $attachments[0]["gallery_video"]!="") {
			$content_diapo.= '<div class="block_video_gallery">';
			$content_diapo.= do_shortcode('[fpvideo mediaid="'.$attachments[0]["gallery_video"].'" height="420"]');
			$content_diapo.= '</div>';
			$content_diapo.= "<img  src='" . $attachments[0]["gallery_full"][0] . "' alt='' />";
		} elseif(isset($attachments[0]["gallery_full"][0])) {
			$content_diapo.= "<img src='" . $attachments[0]["gallery_full"][0] . "' alt='' class='pinit-here' />";
		}
		if(isset($carousel_indicators) && get_param_global('indicators_diapo_article')) {
			$content_diapo.= '<!-- Indicators -->
			<ol class="carousel-indicators">
			'.$carousel_indicators.'
			</ol>';
		}
		$content_diapo.= "</div>";

		if( !get_param_global('legende_diapo_v3_bofore_img') ){
			$content_diapo .= $legende_diapo_v3 ;
		}

		$content_diapo .= '</figure>';

		if(!empty($attr['content']) && !get_param_global('hide_diapo_desc')){
			$content_diapo .= "<div class='gallery_desc'>".$attr['content']."</div>";
		}

		$content_diapo.= "</div>";
		$content_diapo = apply_filters('large_diapo_content_end', $content_diapo);
		$content_diapo.= "</div>";
		
		$content_diapo = apply_filters('diapo_content', $content_diapo);