<?php

add_filter('add_custom_fields_nl_article', 'nl_article_custom_fields',10,4);

function nl_article_custom_fields($result,$nl_articles,$index,$field){
	if($field == "color"){
		if(isset($nl_articles[$index]['serviciel']) && $nl_articles[$index]['serviciel'] == 1){
			return "#206ba8";
		}
		return "#DA2B33";
	}
	if($field == "experts_logo"){
		if(isset($nl_articles[$index]['serviciel']) && $nl_articles[$index]['serviciel'] == 1){
			return '<img src="https://sf.viepratique.fr/wp-content/uploads/sites/8/2019/01/experts.png" alt="Les experts M&T" border="0" height="170" width="310" style="display:block;">';
		}
		return "";
	}
	return $result;
}

function encart_serviciel_emplacement_pave($attrs){
	global $show_pave_serviciel;
	$encart_serviciel_page = get_page_by_path( 'encart-serviciel' );
	if(!empty($encart_serviciel_page)){
	$encart_serviciel = get_post_meta($encart_serviciel_page->ID);
	if( isset($show_pave_serviciel) && $show_pave_serviciel == 1 && !empty($encart_serviciel['emplacement_pave'][0])){

		return $encart_serviciel['emplacement_pave'][0];
		
	}else{

		return '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                  <tbody><tr>
                    <td style="padding: 10px 10px;">
                      <table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                        <tbody><tr>
                          <td><a href="http://ml.maison-travaux.fr/ssp/mail/redirect/cb/{(CampaignID)}-{(email_md5)}-Pave1?pgname=maisonettravaux&siteid=145374&fmtid=45635" target="_blank">
                            <img border="0" alt="" src="http://ml.maison-travaux.fr/ssp/mail/cb/{(CampaignID)}-{(email_md5)}-Pave1?pgname=maisonettravaux&ref=maison-travaux.fr&exid={(email_md5)}&siteid=145374&fmtid=45635&visit=s">
                          </a><img border="0" alt="" width="1" height="1" src="http://ml.maison-travaux.fr/ssp/mail/pixel/cb/{(CampaignID)}-{(email_md5)}-Pave1/0" />
							<img border="0" alt="" width="1" height="1" src="http://ml.maison-travaux.fr/ssp/mail/pixel/cb/{(CampaignID)}-{(email_md5)}-Pave1/1" />
							<img border="0" alt="" width="1" height="1" src="http://ml.maison-travaux.fr/ssp/mail/pixel/cb/{(CampaignID)}-{(email_md5)}-Pave1/2" />
                        </td>
                      </tr>
                    </tbody></table>
                  </td>
                </tr>
              </tbody></table>';
		
	}
	}
	return "";
}
add_shortcode('nl_pave_serviciel','encart_serviciel_emplacement_pave');

function encart_serviciel_emplacement_bandeau(){
	global $show_bandeau_serviciel;
	$encart_serviciel_page = get_page_by_path( 'encart-serviciel' );
	if(!empty($encart_serviciel_page)){
		$encart_serviciel = get_post_meta($encart_serviciel_page->ID);
		if( isset($show_bandeau_serviciel) && $show_bandeau_serviciel == 1 && !empty($encart_serviciel['emplacement_bandeau'][0])){
			$bandeau = preg_replace('/class="alignnone.*src/', 'class="center-on-narrow" style="width: 100%; max-width: 330px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555; display: block;" src', $encart_serviciel['emplacement_bandeau'][0]);
			return $bandeau;
			
		}
	}
	return '';
}

add_shortcode('nl_bandeau_serviciel','encart_serviciel_emplacement_bandeau');


add_filter('tiny_mce_before_init', function($init) {
	global $post;
	if( $post->post_name == 'encart-serviciel'){
	    $init['wpautop'] = false;
	    $init['forced_root_blocks'] = false;
	    $init['force_p_newlines'] = false;
	    $init['force_br_newlines'] = true;
	}
    return $init;
});
