<?php

if (is_admin()):

    class Bandeau_partners {
        
        function __construct() {
            add_action('admin_menu', array(&$this, 'add_options_admin'));
            add_action('current_screen', array(&$this, 'current_screen'));
        }

        function current_screen(){
            $screen = get_current_screen();
            if( strpos( $screen->id , 'bandeau_partner') !== false){
                wp_enqueue_media();
                wp_enqueue_script("upload_logo_partner" , RW_THEME_DIR_URI . "/assets/javascripts/bandeau_partners.js", array(), CACHE_VERSION_CDN,true);
            }
        } 
        
        function add_options_admin() {
            add_options_page('Bandeau des Partenaires', 'Bandeau des Partenaires', 'administrator', 'bandeau_partner', array(&$this, 'bandeau_partner'));
        }
        
        function bandeau_partner() {
            if (isset($_POST['bandeau_partner'])) {
                update_option('bandeau_partner',  $_POST['bandeau_partner'], false);
            }
            $bandeau_partner = get_option('bandeau_partner' , '');
            ?>
            <style type="text/css">
                #add-partner .partner {
                    margin-bottom: 40px;
                }
                #add-partner .all_partners h3 {
                    margin-bottom: 10px;
                }
                #add-partner .all_partners {
                    max-width: 50%;
                }
                #add-partner .all_partners label {
                    text-align: left;
                    display: block;
                    width: 100%;
                }
                #add-partner .all_partners input{
                    width: 100%;
                }
                #add-partner .delete-partner{
                    font-weight: bold;
                    color: #c70a0a;
                    text-decoration: underline;
                    cursor: pointer;
                    margin-top: 15px;
                    font-size: 14px;
                }
                #add-partner .partner_logo {
                    display: block;
                    max-width: 200px;
                    margin-left: auto;
                    margin-right: auto;
                    margin-top: 20px;
                }
                #add-partner .form-group{
                    margin-bottom: 10px;
                }
            </style>

            <h2><?php _e('Gestion de bandeau partners HP')  ; ?></h2>

            <form id="add-partner" method="post" class="validate">
                <div class="all_partners">
                    <?php 
                    if( count($bandeau_partner['name']) < 4 ){
                        $nbr = 4;
                    }else{
                        $nbr = count($bandeau_partner['name']);
                    }
                    for ($i=0; $i < $nbr; $i++) { ?>
                            <div class="partner">
                                <h3><?php _e('Partner #'.($i+1) )  ; ?> | <span class="delete-partner">Supprimer</span></h3>
                                <div class="form-group">
                                    <label><?php echo 'Nom'  ; ?></label>
                                    <input name="bandeau_partner[name][]" value="<?php echo $bandeau_partner['name'][$i]; ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo 'URL'  ; ?></label>
                                    <input name="bandeau_partner[url][]"  value="<?php echo $bandeau_partner['url'][$i]; ?>" />
                                </div>


                                <div class="form-group">
                                    <label for="partner_img"><?php echo 'Image'; ?></label>
                                    <input type="button" class="button button-secondary add_logo" value="Upload Image" id="<?php echo 'partner_img_button_'.$i; ?>" data-id="<?php echo $i; ?>"/>
                                    <img id="<?php echo 'partner_img_preview_'.$i; ?>" class="partner_logo <?php if( empty($bandeau_partner['logo'][$i])) echo 'hidden' ; ?>" src="<?php echo $bandeau_partner['logo'][$i]; ?>"/>
                                    <input type="hidden" name="bandeau_partner[logo][]" id="<?php echo 'partner_img_'.$i; ?>" value="<?php echo $bandeau_partner['logo'][$i]; ?>"/>
                                </div>
                                
                            </div>
                    <?php } ?>
                </div>
                <p class="submit">
                    <input id="new-partner" class="button button-primary" type="button" value="<?php _e('Ajouter un partner')  ; ?>" name="new-partner" />
                    <input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications')  ; ?>" name="submit" />
                </p>
            </form>
            <?php
            $html = '<div class="form-group"><label>Nom</label><input name="bandeau_partner[name][]" /></div><div class="form-group"><label>URL</label><input name="bandeau_partner[url][]" /></div><div class="form-group"><label for="partner_img">Logo</label><input type="button" class="button button-secondary add_logo" value="Upload Image" id="partner_img_button_'.$i.'" data-id="'.$i.'"/><input type="hidden" name="bandeau_partner[logo][]" id="partner_img_'.$i.'"/><br /><img id="partner_img_preview_'.$i.'" src=""/></div><span class="delete-partner">Supprimer ce partenaire</span>';
            ?>
            <script type="text/javascript">
                $ = jQuery;
                window.nbr_partners = <?php echo $nbr;?>;
                $( "#new-partner" ).click(function() {
                    nbr_partners = nbr_partners+1;
                    $(".delete-partner").remove();
                    $("#add-partner .all_partners").append('<div class="partner"><h3>Partner #'+nbr_partners+'</h3> <?php echo $html ?></div>');
                    if( nbr_partners == 10 ){
                        $(this).prop("disabled",true);
                    }
                });
                $("body").on("click",".delete-partner",function(){
                    $(this).parents('.partner').remove();
                    nbr_partners = nbr_partners-1;
                    if( nbr_partners > 5 ){
                        $( '.partner' ).last().append('<span class="delete-partner">Supprimer ce partenaire</span>')
                    }
                    if( nbr_partners < 10 ){
                        $('#new-partner').removeAttr("disabled");
                    }
                });
            </script>
        <?php
        }
    }

    new Bandeau_partners();

endif;