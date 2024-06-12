<?php

if (is_admin()){
    
    abstract class CiblageTarget {
        
        
        abstract function add_ciblage_target_admin();

        function target_admin($partner_key){ 
            global $ciblage_options;
            $ciblage_par_titre_chapo = get_param_global('ciblage_par_titre_chapo');
            $ciblage_options = get_option('ciblage_'.$partner_key.'_target_option' , array() );
            if(isset($_REQUEST['edit'])){
                $this->get_target_form_admin($partner_key);
            }else{
                if(isset($_REQUEST['delete'])&& isset($_REQUEST['index'])){
                    unset($ciblage_options[$_REQUEST['index']]);
                    update_option('ciblage_'.$partner_key.'_target_option' , $ciblage_options );
                }

                ?>
                <div class="wrap">
                    <h2> <?php _e('Ciblage '.$partner_key.' TARGET', REWORLDMEDIA_TERMS); ?> <a class="add-new-h2" href="<?php echo home_url(); ?>/wp-admin/options-general.php?page=ciblage_<?php echo $partner_key; ?>_target_admin&edit">Ajouter</a></h2>
                    <br>
                    <table class="wp-list-table widefat fixed posts">
                        <thead>
                            <tr>
                                <th width="150"><?php _e($partner_key.' target' , REWORLDMEDIA_TERMS) ; ?></th>
                                <th><?php _e('Cibles' , REWORLDMEDIA_TERMS) ; ?></th>
                                <th width="80"><?php _e('Actif' , REWORLDMEDIA_TERMS) ; ?></th>
                                <th width="80"><?php _e('Editer' , REWORLDMEDIA_TERMS) ; ?></th>
                                <?php if ($ciblage_par_titre_chapo) { ?>
                                    <th width="80"><?php _e('Cibler le titre & chapô' , REWORLDMEDIA_TERMS) ; ?></th>
                                <?php }?>
                                <th width="80"><?php _e('Supprimer' , REWORLDMEDIA_TERMS) ; ?></th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($ciblage_options as $key => $s) {
                            ?>
                            <tr>
                                <td><?php echo $s[$partner_key.'_target_'] ; ?></td>
                                <td><?php echo $s['cibles_'] ; ?></td>
                                <td><?php echo ($s['active'])? __('Oui' , REWORLDMEDIA_TERMS):__('Non' , REWORLDMEDIA_TERMS)  ; ?></td>
                                <td><a href='<?php echo home_url(); ?>/wp-admin/options-general.php?page=ciblage_<?php echo $partner_key; ?>_target_admin&edit&index=<?php echo $key ?>'><?php _e('Editer' , REWORLDMEDIA_TERMS) ; ?></a></td>
                                <?php if ( $ciblage_par_titre_chapo) { ?>
                                    <td><?php echo ($s['titre_chapo_cible'])? __('Oui' , REWORLDMEDIA_TERMS):__('Non' , REWORLDMEDIA_TERMS)  ; ?></td>
                                <?php } ?>
                                <td><a href='<?php echo home_url(); ?>/wp-admin/options-general.php?page=ciblage_<?php echo $partner_key; ?>_target_admin&delete&index=<?php echo $key ?>'><?php _e('Supprimer' , REWORLDMEDIA_TERMS) ; ?></a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php

            }
        }

        function get_target_form_admin($partner_key) {
            global $ciblage_options; 
            $ciblage_par_titre_chapo = get_param_global('ciblage_par_titre_chapo');
            if(isset($_REQUEST['index'])){
                $index = $_REQUEST['index'] ;
                $targets = $ciblage_options[$index];
            }else{
                $index = false ;
                $targets = array();
            }

            $chars=array('\\','\"',"\'");

            if (isset($_POST[$partner_key.'_target_'])) {
                $targets[$partner_key.'_target_'] = str_replace($chars, '', $_POST[$partner_key.'_target_']);
            }
            if (isset($_POST['cibles_'])) {
                $targets['cibles_'] = str_replace($chars, '', $_POST['cibles_']);
            }
            if (isset($_POST['cats_exclude'])) {
                $targets['cats_exclude'] = str_replace($chars, '', $_POST['cats_exclude']);
            }
    
            if (isset($_POST['ids_exclude'])) {
                $targets['ids_exclude'] = str_replace($chars, '', $_POST['ids_exclude']);
            }
            if (isset($_POST['ciblage_active'])) {
                $targets['active'] = str_replace($chars, '', $_POST['ciblage_active']);
            }

            if(isset($_POST['submit'])){
                $targets['ops_projet_collect'] = false;
                $targets['titre_chapo_cible'] = false;
                $targets['blacklisted_enable'] = false;

                if (isset($_POST['ops_projet_collect'])) {
                    $targets['ops_projet_collect'] = str_replace($chars, '', $_POST['ops_projet_collect']);
                }
                if ( isset($_POST['titre_chapo_cible'])) {
                    $targets['titre_chapo_cible'] = str_replace($chars, '', $_POST['titre_chapo_cible']);
                }
                //blacklist Keywords 
    
                if (isset($_POST['blacklisted_enable'])) {
                    $targets['blacklisted_enable'] = true;
                }
              if (isset($_POST['blacklist'])) {
                $targets['blacklist'] = str_replace($chars, '', $_POST['blacklist']);
              }
            }

            $targ     = $targets[$partner_key.'_target_'];
            $cibles   = $targets['cibles_'];
            $cats_exclude   = $targets['cats_exclude'];
            $ids_exclude   = $targets['ids_exclude'];
            $blacklist = $targets['blacklist'];
            $blacklisted_enable = $targets['blacklisted_enable'];
            $active   = $targets['active'];
            $ops_projet_collect = $targets['ops_projet_collect'];
            if ( $ciblage_par_titre_chapo) {
                $titre_chapo_cible = $targets['titre_chapo_cible'];
            }


            if (isset($_POST['submit'])) {
                if($index !== false){
                    $ciblage_options[$index] = $targets  ;
                }else{
                    $ciblage_options[] = $targets  ; 
                    $index = count($ciblage_options )-1 ;               
                }
                $ciblage_options[$index] = $targets  ;
                update_option('ciblage_'.$partner_key.'_target_option' , $ciblage_options );
            }


            ?>
            <h2><?php _e('Réglage',REWORLDMEDIA_TERMS )  ; ?></h2>
            <form method="post" class="validate">
                <table class="form-table" >
                    <tr valign="top">
                        <th scope="row" ><label for="ciblage_target"><?php _e($partner_key.' target',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td ><input type='text' id="ciblage_target" name="<?php echo $partner_key.'_target_' ?>" value="<?php echo wp_kses_data($targ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" ><label for="ciblage_active"><?php _e('Activer ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td >
                            <select name="ciblage_active" id="ciblage_active">
                                <option value="1" <?php if ($active == 1) { echo 'selected'; } ?>><?php _e('Oui',REWORLDMEDIA_TERMS )  ; ?></option>
                                <option value="0" <?php if ($active == 0) { echo 'selected'; } ?>><?php _e('Non',REWORLDMEDIA_TERMS )  ; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" ><label for="cibles"><?php _e('Cibles ( séparés par des virgules ) ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td ><textarea id="cibles" name="cibles_" rows="10" cols="50"><?php echo wp_kses_data($cibles); ?></textarea></td>
                    </tr> 
                    <tr valign="top">
                        <th scope="row" ><label for="ids_exclude"><?php _e('Ids des articles à exclure ( séparés par des virgules ) ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td ><textarea id="ids_exclude" name="ids_exclude" rows="10" cols="50"><?php echo wp_kses_data($ids_exclude); ?></textarea></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" ><label for="cats_exclude"><?php _e('Slugs des catégorie à exclure ( séparés par des virgules ) ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td ><textarea id="cats_exclude" name="cats_exclude" rows="10" cols="50"><?php echo wp_kses_data($cats_exclude); ?></textarea></td>
                    </tr>
                  <th scope="row" ><label for="blacklist"><?php _e('Les mots du blacklist ( séparés par des virgules ) ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                  <td ><textarea id="blacklist" name="blacklist" rows="10" cols="50"><?php echo wp_kses_data($blacklist); ?></textarea></td>
                  </tr>
                    <tr valign="top">
                        <th scope="row" ><label for="blacklisted_enable"><?php _e('Exclure les mots ( dealer, erotique, flatulence ....  ) ',REWORLDMEDIA_TERMS )  ; ?></label></th>
                        <td ><input id="blacklisted_enable" name="blacklisted_enable" type="checkbox" value="1" <?php echo !empty($blacklisted_enable) ? "checked" : "";  ?> /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" >
                            <label for="projet_collect_ops">
                                <?php _e('Considerer comme OPS (Collecte premium) ',REWORLDMEDIA_TERMS )  ; ?>
                            </label>
                        </th>
                        <td >
                            <input type="checkbox" value="1" name="ops_projet_collect"
                             <?php echo !empty($ops_projet_collect) ? "checked" : ""; ?> > 
                        </td>
                    </tr>
                    <?php if ($ciblage_par_titre_chapo) { ?>
                        <tr valign="top">
                            <th scope="row" >
                                <label for="cible_titre_chapo">
                                    <?php _e('Cibler en fonction du titre et du chapô',REWORLDMEDIA_TERMS )  ; ?>
                                </label>
                            </th>
                            <td >
                                <input type="checkbox" value="1" name="titre_chapo_cible"
                                 <?php echo !empty($titre_chapo_cible) ? "checked" : ""; ?> >
                            </td>
                        </tr>
                    <?php } ?>

                </table>

                <p class="submit">  
                    <?php if($index !== false){ ?>
                        <input type="hidden" name="index" value="<?php echo $index; ?>" > 
                    <?php } ?>

                    <input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer',REWORLDMEDIA_TERMS )  ; ?>" name="submit" />
                </p>
            </form>
            <?php
        }

    }
}