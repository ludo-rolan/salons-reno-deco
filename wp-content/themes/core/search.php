<?php
global $wp_query;
get_header();
?>
<div id="content" class="pull-left">
	<div id="results" class="search list-large-items">
		<ol class="breadcrumb">
			<li class="home">
				<a href="#"><?php _e('Accueil' , REWORLDMEDIA_TERMS) ; ?></a>
			</li>
			<li class="parent">
				<a href="#"><?php _e('Recherche' , REWORLDMEDIA_TERMS) ; ?></a>
			</li>
		</ol>
	<?php if (have_posts()) { ?>
	
		<div class="bar_result_search">
			<?php 
	     		$before_search = "<span class='count-search'>".$wp_query->found_posts . '</span> ';
				$before_search .= sprintf( __( 'résultats pour : %s', REWORLDMEDIA_TERMS ), '<span class="search_query" >' . $s . '</span>' ); 
				do_action("search_results_filtre");
				echo apply_filters('before_search_results' , $before_search , $s );
			?>

		</div>
		
		<?php 
			do_action( 'rw_result_search' );
			echo RW_Utils::reworldmedia_pagination();
		} else { 
			$msg=__('Il n\'y a pas de résultat pour cette recherche.',REWORLDMEDIA_TERMS); 
			echo apply_filters('empty_search_result',$msg);
			do_action( 'rw_no_result_search' );
		}
		do_action('after_pagination_recherche');
	?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>