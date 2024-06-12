<?php
require_once RW_THEME_DIR.'/include/functions/ciblage-target.php';

if (is_admin()){
    
    class CiblageSasTarget extends CiblageTarget{
        
        function __construct() {
            add_action('admin_menu', array(&$this, 'add_ciblage_target_admin'));
        }
        
        function add_ciblage_target_admin() {
            add_options_page('Ciblage SAS target', 'Ciblage SAS target', 'manage_options', 'ciblage_sas_target_admin', array(&$this, 'sas_target_admin'));
        }

        function sas_target_admin(){
            parent::target_admin('sas');
        }
 

    }
    new CiblageSasTarget();
}

//SAS target by etiquette
add_filter('sas_target','sas_target_add_marque_by_keys');	
add_filter('ops_sas_target','is_ops_collect_premium');		
 
/* get active sas target */
function active_sas_target(){
	global $active_sas_target;
	if($active_sas_target === null){
		$active_sas_target = array();
		$sas_target = get_param_global('sas_target_marques_keys');
		if($sas_target){
			foreach ($sas_target as $marque => $marque_data) {
				$ciblage_par_chapo_titre = get_param_global('ciblage_par_titre_chapo') ? $marque_data['ciblage_par_chapo_titre'] : null;
				if(navigation_contain_sas_marque_keys($marque, $marque_data['keys'] , $ciblage_par_chapo_titre )){
					$active_sas_target[$marque] = $marque_data;
				}
			}
		}
	}
	return $active_sas_target;
}

function is_ops_collect_premium($is_ops){
	$active_sas_target = active_sas_target();
	if(!empty($active_sas_target)){
		foreach ($active_sas_target as $marque => $marque_data) {
			if(!empty($marque_data['is_ops_collect_premium']) && $marque_data['is_ops_collect_premium']){
				$is_ops = true;
				break;
			}
		}
	}
	return $is_ops;
}

/**
 * [sas_target_add_marque_by_keys function to add sas target by key (tag)]
 * @param  [type] $s [none]
 * @return [type]    [none]
 */
function sas_target_add_marque_by_keys($s){
	$active_sas_target = active_sas_target();
	if(!empty($active_sas_target)){
		foreach ($active_sas_target as $marque => $marque_data) {
			$s[] = $marque_data['sas_target'];
		}
	}
	return $s ;
}
/**
 * [navigation_contain_sas_marque_keys check to correspondance btwn a]
 * @param  [type] $marque [description]
 * @param  [type] $keys   [description]
 * @return [type]         [description]
 */
function navigation_contain_sas_marque_keys($marque, $keys , $ciblage_par_chapo_titre = null){
	global $saved_sas_marques, $post;
	if(isset($saved_sas_marques[$marque]) && !defined('NO_CACHE_SAS_TARGET')){
		return $saved_sas_marques[$marque];
	}
	$has_keys = page_contain_cible_keys($keys , $ciblage_par_chapo_titre);
	$saved_sas_marques[$marque] = $has_keys;
	return $has_keys;
}

add_action('init', 'update_sas_target_marques_keys');	

function update_sas_target_marques_keys(){
	global $site_config;
	$sas_options = get_option('ciblage_sas_target_option' , array() );
	if( !empty($sas_options) ){
		$sas_marque = '';
		foreach ($sas_options as $s) {
			if( $s['active'] ){
				$sas_m = strtolower($s['sas_target_']);
				if( $sas_m ){
					$marque_uniq = $sas_m.uniqid();
					$sas_marque = "marque=".$sas_m;
					$cbls = explode(',', $s['cibles_']);
					$is_ops = false;
					$ciblage_par_chapo_titre = false;
					if(!empty($s['ops_projet_collect'])){
						$is_ops = $s['ops_projet_collect'];
					}
					if(!empty($s['titre_chapo_cible'])){
						$ciblage_par_chapo_titre = $s['titre_chapo_cible'];
					}
					$site_config['sas_target_marques_keys'][$sas_m] = array( 'sas_target'=> $sas_marque, 'keys' => $cbls, 'is_ops_collect_premium'=>$is_ops,'ciblage_par_chapo_titre'=>$ciblage_par_chapo_titre);
				}
			}
		}
	}
}
