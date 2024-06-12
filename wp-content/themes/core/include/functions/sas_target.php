<?php

if(get_param_global('ciblage_sas_target_reno')){
add_filter('sas_target','sas_target_cat_filter');
add_action( 'reword_option_form', 'sam_reword_option_form' );
add_action('category_edit_form', 'sam_category_edit_form', 10, 1 );
add_action( 'edit_term', 'sam_save_category', 10, 3 );
}
function sas_target_cat_filter($array){
	global $wp_query ;

 	$sam_sas_target = get_option('sam_sas_target' , '');
 	if($sam_sas_target){
		$array[] = $sam_sas_target ;

 	}

	if(is_category()){
		$cat_id = $wp_query->query_vars['cat'] ;
		$meta_tags = get_term_meta($cat_id, 'meta_tags', true);
		$sas_target = isset($meta_tags['sas_target'])? $meta_tags['sas_target']:'' ;
		/*sas target home page*/
		$sas_target_hp = isset($meta_tags['sas_target_hp'])? $meta_tags['sas_target_hp']:'' ;
		if(!empty($sas_target_hp)){
			$array[] = $sas_target_hp ;
		}

		if(!empty($sas_target)){
			$array[] = $sas_target ;
		}

		if(category_has_parent($cat_id)){
			$catobject = get_category($cat_id,false);
			$cat_parent = $catobject->category_parent;
			$meta_tags = get_term_meta($cat_parent, 'meta_tags', true);
			$sas_target = isset($meta_tags['sas_target'])? $meta_tags['sas_target']:'' ;
			if(!empty($sas_target)){
				$array[] = $sas_target;
			}
		}
		
	}elseif(is_singular()){
		$cats = array( get_menu_cat_post() );

		if(is_dev('sas_target_rubrique_green_143305061')){
			$categories = get_the_category() ;
			foreach ($categories as $cat) {
				$meta_ = get_term_meta($cat->term_id, 'meta_tags', true);
				if(isset($meta_['force_sas_target']) && $meta_['force_sas_target'] == 1 ){
					if($cats[0]->term_id != $cat->term_id){
						$cats[] = $cat;
					}
				}
			}
		}

		foreach ($cats as $category) {
			if(isset($category->term_id)){
				$cat_parents = reworld_get_category_parents_ids($category->term_id, '/' );
				$cat_parents = trim($cat_parents,'/');
				$cat_parents = explode( '/', $cat_parents);
				foreach ($cat_parents as  $cat_id) {
					$meta_tags = get_term_meta($cat_id, 'meta_tags', true);
					$sas_target = isset($meta_tags['sas_target'])? $meta_tags['sas_target']:'' ;
					if(!empty($sas_target)){
						$array[] = $sas_target ;
					}
				}
			}
		}
	}
	return $array ;
}

function sam_reword_option_form(){

	if (isset($_POST['sam_sas_target'])) {
        update_option('sam_sas_target', $_POST['sam_sas_target'], false);

    }
 	$sam_sas_target = esc_attr( get_option('sam_sas_target' , ''));



	?>
		<h3><?php _e('Targeting',REWORLDMEDIA_TERMS )  ; ?></h3>
		<table class="form-table" >
			<tr valign="top">
				<th scope="row" ><label for="sam_sas_target"><?php _e('SAS Target',REWORLDMEDIA_TERMS )  ; ?></label>
				</th>
				<td ><input name="sam_sas_target"
					id="sam_sas_target"  value="<?php echo $sam_sas_target; ?>" />
				</td>
			</tr>
		</table>

	<?php

}


function sam_category_edit_form($tag){
	global $wpdb;
	$meta_tags = get_term_meta($tag->term_id,'meta_tags', true);
	$sas_target = isset($meta_tags['sas_target'])? $meta_tags['sas_target']:'' ;
	/*sas target home page catégorie*/
	$sas_target_hp = isset($meta_tags['sas_target_hp'])? $meta_tags['sas_target_hp']:'' ;
	if(is_dev('sas_target_rubrique_green_143305061')){
		$force_sas_target = (isset($meta_tags['force_sas_target']) && $meta_tags['force_sas_target'] == 1 )? 'checked': '' ;
	}
	$output =  '<table class="form-table">
		<tr class="form-field">
	    <th scope="row">
	        <label for="sas_target"> Sas Target Categorie et articles </label>
	    </th>
	    <td>';
	    $output .= "<input type='text' id='sas_target' value='$sas_target' name='meta_tags[sas_target]'>";
	    $output .= '</td></tr>';
	    
	    if(is_dev('sas_target_rubrique_green_143305061')){
			$output .=  '<tr class="form-field">
		   					 <th scope="row">
		       				 	<label for="force_sas_target"> Forcer l\'intégration du SAS Target dans les articles non permalinké. </label>
		   					 </th>
		   					 <td><input type="checkbox" id="force_sas_target" value="1" name="meta_tags[force_sas_target]"  '.$force_sas_target.'></td>
		   				</tr>';
	    }

		$output .=  '<tr class="form-field">
	    				<th scope="row">
	        				<label for="sas_target_hp"> Sas Target HP Catégorie </label>
	    				</th>
	    			<td>';
	    $output .= "<input type='text' id='sas_target_hp' value='$sas_target_hp' name='meta_tags[sas_target_hp]'>";
	    $output .= '</td></tr>';

	    $output .= '</table>';
	    echo $output ;
}

function sam_save_category($term_id, $tt_id, $taxonomy){
	if ($taxonomy == 'category' && isset($_POST['meta_tags']) ){
		$meta_tags = $_POST['meta_tags'] ;
		update_term_meta($term_id,'meta_tags', $meta_tags);
	}
}