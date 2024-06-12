<?php
global $wp_query;
get_header();
?>
<div id="content"class="col-xs-12 col-md-8 pull-left">
	
	
	<div id="results" class="search list-large-items">
	<?php if (have_posts()) { ?>
		<div class="bar_result_search">
			<?php 
	     		$before_search = "<span class='count-search'>".$wp_query->found_posts . '</span> ';
				$before_search .= sprintf( __( 'résultats pour : %s', REWORLDMEDIA_TERMS ), '<span class="search_query" >' . $s . '</span>' ); 
				do_action("search_results_filtre");
				echo apply_filters('before_search_results' , $before_search , $s );
			?>
		</div>
		<div class="row">
		<?php do_action( 'rw_result_search' );?>
		</div>
		<?php
		 echo RW_Utils::reworldmedia_pagination(); 
			} else { 
				$msg=__('Il n\'y a pas de résultat pour cette recherche.',REWORLDMEDIA_TERMS); 
				echo '<div class="bar_result_search">'. $msg .'</div>';
				do_action( 'rw_no_result_search' );
			}
			do_action('after_pagination_recherche');
		?>
		
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>