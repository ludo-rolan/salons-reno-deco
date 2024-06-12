<?php 


add_filter('enable_bandeau_partners','activer_bandeau_partners_pms', 2 ,1);
add_filter( 'newsletter_qualif' ,  'default_newsletter_qualif', 1 , 1 );
add_action('admin_enqueue_scripts', 'admin_enqueue_theme_styles');
//add extra fields to category edit form hook
add_action ( 'category_edit_form_fields', 'extra_category_fields');
// save extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fileds');
add_action('wp_enqueue_scripts',function (){
    wp_enqueue_style( 'font-awesome','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );
    wp_dequeue_script('fix_social_widget_js');
    wp_enqueue_script('bandeau_js', STYLESHEET_DIR_URI.'/assets/javascripts/bandeau_partenaires.js', array('jquery'), false, true);
});

function default_newsletter_qualif($return=false){
	return RW_Hooks::default_newsletter_qualif($return);
}

function activer_bandeau_partners_pms($bandeau_is_enabled){
    //disactiver le bandeau de partenaire quand c'est une page sans Sidebar
    // à décommenter en Phase 2
    // return (strpos(get_page_template(),"page-no-sidebar.php") !== false)?false:true;
    return false;
}

// add_action("wp_footer","add_bandeau");

// function add_bandeau(){
//     echo '<script type="text/javascript">window.gdprAppliesGlobally=true;(function(){function a(e){if(!window.frames[e]){if(document.body&&document.body.firstChild){var t=document.body;var n=document.createElement("iframe");n.style.display="none";n.name=e;n.title=e;t.insertBefore(n,t.firstChild)} else{setTimeout(function(){a(e)},5)}}}function e(n,r,o,c,s){function e(e,t,n,a){if(typeof n!=="function"){return}if(!window[r]){window[r]=[]}var i=false;if(s){i=s(e,t,n)}if(!i){window[r].push({command:e,parameter:t,callback:n,version:a})}}e.stub=true;function t(a){if(!window[n]||window[n].stub!==true){return}if(!a.data){return} var i=typeof a.data==="string";var e;try{e=i?JSON.parse(a.data):a.data}catch(t){return}if(e[o]){var r=e[o];window[n](r.command,r.parameter,function(e,t){var n={};n[c]={returnValue:e,success:t,callId:r.callId};a.source.postMessage(i?JSON.stringify(n):n,"*")},r.version)}} if(typeof window[n]!=="function"){window[n]=e;if(window.addEventListener){window.addEventListener("message",t,false)}else{window.attachEvent("onmessage",t)}}}e("__tcfapi","__tcfapiBuffer","__tcfapiCall","__tcfapiReturn");a("__tcfapiLocator");(function(e){ var t=document.createElement("script");t.id="spcloader";t.type="text/javascript";t.async=true;
//         t.src=""
//         n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n)})("745a135f-908d-4bfb-beca-56b93e25dc45")})();</script>'
// }

function get_youtube_embed_url($url)
{
     $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}

add_shortcode("fyoutube","get_youtube_iframe");

function get_youtube_iframe($attrs){
    $url = $attrs["url"];
    $width = $attrs["width"];
    $height = $attrs["height"];
    $autoplay = $attrs["autoplay"];
    $loop = $attrs["loop"];
    if(!empty($url)){
        $url = get_youtube_embed_url($url);
        $widthhtm = '';
        $heighthtm = '';
        if(!empty($width)) {
            $widthhtm = " width=\"$width\" ";
        }
        if(!empty($height)) {
            $heighthtm = " height=\"$height\" ";
        }
        echo '<iframe '.$width.' '.$width.'  src="'.$url.'" style="height:300px;width:100%;border:none;overflow:hidden;"></iframe>';
    }
}

add_action('footer-menu-list', 'footer_menu_list');
function footer_menu_list(){
    echo do_shortcode('[footermenulist]');
}

add_action('side-nl-form', 'side_nl_form');
function side_nl_form(){
    echo do_shortcode('[sidenl]');
}
add_action('footer-nl-form', 'footer_nl_form');
function footer_nl_form(){
    echo do_shortcode('[footernl]');
}

function admin_enqueue_theme_styles()
{
  wp_enqueue_script( 'color-weel-script', 'https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.6/jscolor.min.js', array( 'jquery' )  );
  wp_enqueue_script( 'single-image-uploader', STYLESHEET_DIR_URI . '/assets/javascripts/admin/image-upload.js', array( 'jquery' ), CACHE_VERSION_CDN, true );
}


//add extra fields to category edit form callback function
function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
<?php
    global $site_config;
    if (isset($site_config["theme_colors"])){
        $colors =  json_encode($site_config["theme_colors"]);
    }
?>
<th scope="row" valign="top"><label for="cat_color" style="width:100%">Color Cat </label></th>
<td>
    <input data-jscolor="{}" id="Cat_meta[color]" name="Cat_meta[color]" value="<?php echo $cat_meta['color']; ?>"><br/>
    <span class="description"><?php _e('Couleur pour l\'affichage du Nom de la Categorie '); ?></span>
</td>
</th>
<script>
    jscolor.presets.default = {
        width: 141,
        position: 'left',
        previewPosition: 'bottom',
        previewSize: 40,
        preset: 'dark large',
        palette: <?php echo  $colors; ?>,
        paletteCols: 5,
        paletteHeight: 30
    };
</script>
<?php
}
// save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}


function simple_sharer_html_pms22($val, $sharedcount, $pos, $spec_social_arra=array(), $attr = array()) {
	/* Paramètre pour desactiver les boutons de reseau sociaux : no_social_share */
	if( !empty( $_GET['no_social_share'] ) ){
		return '';
	}
	if(is_single()) {
		if($pos == 'v') {
			$deactivate_vertical_sharer = apply_filters('deactivate_vertical_sharer' , false);
			if($deactivate_vertical_sharer)
				return '';
			$html='<div class="social blockShare_vertical">' ;
					$html .='<div class="addthis_toolbox " >';
						$html .='<a class="social-network fb addthis_button_facebook" data-network="facebook"></a>';
						$html .='<a class="social-network addthis_button_tweet  tw " data-network="twitter" data-text ="'.$spec_social_arra['twitter']['title'] .'"  data-url="' . $spec_social_arra['twitter']['url'] . '" data-hashtags="' . $spec_social_arra['twitter']['hashtags'] . '" data-via="' . $spec_social_arra['twitter']['via'] . '"></a>';
					$html .='</div>';
				$html .='</div>';
				$html = apply_filters('new_addthis_filter', $html, $attr);
		}
		else {
			$total_shares = apply_filters('share_total' , $sharedcount ) ; 

			$html = '';
			$show_total_shares = $total_shares && count($sharedcount) > 0;
			if(!get_param_global("move_total_shares_to_li") && $show_total_shares ){
				$html .=  $total_shares;
			}
			$html .= '<div class="social blockShare_horizontal ">';
			$html .= '	<ul>';

			$sharer_to_deactivated = apply_filters('sharer_to_deactivated' , array());
				if(!in_array('facebook', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share facebook fab fa-facebook-f" data-network="facebook"></li>';
				if(!in_array('twitter', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share twitter  fab fa-twitter" data-network="twitter" data-text ="'.str_replace(array('"','#'),'',($spec_social_arra['twitter']['title'])) .'"  data-url="' . $spec_social_arra['twitter']['url'] . '" data-hashtags="' . $spec_social_arra['twitter']['hashtags'] . '" data-via="' . $spec_social_arra['twitter']['via'] . '">
			</li>';
				if(!in_array('linkedin', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share linkedin fab fa-linkedin-in" data-network="linkedin"></li>';
				if(!in_array('email', $sharer_to_deactivated))

			$html .= '	</ul>';
			$html .= '</div>';
			$html = apply_filters('new_addthis_filter', $html, $attr);
		}
	}
	return $html;
}
add_filter( 'simple_sharer_html' , 'simple_sharer_html_pms22', 11, 5);
function custom_widget_title( $title ) {
    $title = str_replace( 'pms_line_break', '<br/>', $title );
    return $title;
}    
add_filter( 'widget_title', 'custom_widget_title' );


//locking Home

define('_LOCKING_ON_',true);


// Locking THematique
$thematiques = get_categories();

if ($thematiques){
    foreach ($thematiques as $thematique) { 
    //Config des posts - bloc posts suggeres  : 

        if( !strpos($thematique->slug, 'edito') ){
            $site_config['locking']['post']['post_exposants_par_thematique_'.$thematique->slug] = 
            [
                'desc' => 'Posts suggérés de la thematique '.$thematique->name,
                'title' => 'Posts suggérés de la thematique '.$thematique->name,
                'nb_pos' => 3,
                'args' => array(
                    'post_type'=> 'exposant',
                ) 
            ];

            $site_config['locking']['category']['bloc_conseils_experts_'.$thematique->slug] = 
            [
                'desc' => 'Bloc conseils d\'expert de la categorie '.$thematique->name,
                'title' => 'Conseils d\'expert de la category '.$thematique->name, 
                'nb_pos' => 4,
                'args' => array(
                    'post_type'=> $site_config['locking_posts_types'],
                ) 
            ];

            $site_config['locking']['sous_category']['conseils-experts'.$thematique->slug] = 
            [
                'desc' => 'Bloc conseils d experts de la sous categories '.$thematique->name,
                'title' => 'Bloc conseils d experts de la sous categories '.$thematique->name,
                'nb_pos' => 4,
                'args' => array(
                    'post_type'=> 'post',
                ) 
            ];
            

            
        }
    //Config de la sous categorie - bloc carousel : 

        $site_config['locking']['sous_category']['carousel'.$thematique->slug] = 
        [
            'desc' => 'Carousel de la Categorie '.$thematique->name,
            'title' => 'Carousel de la Categorie '.$thematique->name,
            'nb_pos' => 5,
            'args' => array(
                'post_type'=> 'exposant',
            ) 
        ];
    //Config de la sous categorie - bloc exposant : 

        $site_config['locking']['sous_category']['exposants'.$thematique->slug] = 
        [
            'desc' => 'Bloc Exposants de  la sous categorie'.$thematique->name,
            'title' => 'Bloc des Exposants de la sous-category '.$thematique->name, 
            'nb_pos' => 2,
            'args' => array(
                'post_type'=> 'exposant',
            ) 
        ];

        
    }
}
$site_config['jwplayer_key'] = (isset($site_config['jwplayer_key'])) ? $site_config['jwplayer_key'] : "vbUK3gfdKMai96zkE8lPTnO99dVXm6FLeuCa1EG+yFg=";
$site_config_js['jwplayer_key'] = $site_config['jwplayer_key']  ;


//reworld
$jwplayer7_key = "Clz60zvch5p+0wkRYHn4I+SKMhj1ed29Xto3xg==";

// FIn de la config du locking 

// set website language depending on the country code

add_action('init','redirect_newuser_by_lang');
function redirect_newuser_by_lang(){
    // if the plugin is activated
    if (class_exists('SitePress')) {
		global $sitepress;
        $french_countries = [
            "FR",
            "DZ",
            "MA",
            "TN",
            "SZ",
            "CH",
            "BE",
            "GF",
            "PF",
            "TF",
            "GA",
            "CG",
            "CA",
            "CM",
            "CI",
            "MG",
            "SN",
            "BF",
            "BJ",
            "GN",
            "TG",
            "CF",
            "GA",
            "BI",
            "RW",
            "LU",
            "DJ",
            "GQ",
            "KM",
            "VU",
        ];
        // check if user was already redirected once or logged in
        if( !is_user_logged_in() && !isset($_COOKIE['already_redirected_by_lang'])){
            // donot 
            $country_code = Localisation_Geoip::get_country_code();
            if( $country_code ){
                setcookie("already_redirected_by_lang", true);
                // check country lang 
                $redirect_to_lang = "fr";
                $current_uri = $_SERVER['REQUEST_URI'];
                $new_uri = null;
                $is_french = in_array($country_code,$french_countries);
                if ( $is_french ){
                    $sitepress->switch_lang("FR");
                }else{
                    $sitepress->switch_lang("EN");
                    $redirect_to_lang = "en";
                }
                $replace_lang = ($redirect_to_lang == "fr")?"en":"fr";
                if(strpos($current_uri,"/$replace_lang/")==0 || $current_uri == "/$replace_lang"){
                    $new_uri = str_replace("/$replace_lang","/$redirect_to_lang",$current_uri);
                }
                else{
                    $new_uri = "/$redirect_to_lang"+$current_uri;
                }
                if(!empty($new_uri)){
                    wp_redirect($new_uri);
                    exit();
                }
            }
        }
	}
}