<?php
/**
* Cheetah Digital - API
*/

class Cheetah{
	private static $_instance;


	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new Cheetah();
		}
		return self::$_instance;
	}

	public function __construct(){
	}

	/**
	 * Récupération du token pour s’authentifier
	 * @return [String] access_token
	 */
	public function get_token(){
		$response = wp_remote_post(
			"https://api.ccmp.eu/services2/authorization/oAuth2/Token",
			array(
				'method' => 'POST',
				'timeout' => 15,
				'headers' => array(),
				'body' => array(
					'grant_type' => 'password',
					'username' => 'Mjg1Mzc6MTA0MQ==',
					'password' =>"4b7276263e74414f98b8b30920b920ec"
				),
			)
		);
		if(is_wp_error( $response )){
			return null;
		}else {
			if($response['response']['code'] == 200){
				$body = json_decode($response['body'], true);
				$access_token = $body['access_token'];
				return $access_token;
			}
		}
		return null;
	}

	/**
	 * Vérification si contact existant et abonné
	 * @param  [String] $email : Email du contact
	 * @return [String] Si le contact existe ou pas
	 */
	public function check_contact($email, $optins){
		$access_token = $this->get_token();
		if($access_token != null){
			global $site_config;
			$optins_rw = implode(',',is_array($optins) ? $optins : array($optins));
			$response = wp_remote_get(
				"https://api.ccmp.eu/services2/api/SearchRecords?viewName=Reworld Media&prop=email,$optins_rw&columnName=email&operation=eq&param=$email",
				array(
					'method' => 'GET',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => array(),
				)
			);
			if(!empty($response['response']) && $response['response']){
				$decode_response = json_decode($response['body'], true);
				if($decode_response['message'] == "No results for input"){
					return "NOT_EXIST";
				}else{
					return "EXIST";
				}
			}
			return "Error Checking if existing contact";
		}
		return "Erreur Token";
	}
	/**
	 * function : bridge_cheetah_for_partners
	 */
	function check_exist_email(){
		$result = array(
			'error' => true,
			'message' => 'missing/wrong token or missing mail .'
		);
		$token = (isset($_GET['token']) && $_GET['token'] == 'AZppA45PAc4872AKCzeaPk134KEz') ? $_GET['token'] : '';
		$email = (isset($_GET['email'])) ? $_GET['email'] : '';
		if( empty($token) || empty($email) ){
			return json_encode($result);
		}
		rw_send_cache_headers(60);//1 min
		$response = $this->check_contact($email, "");
		
		$result = array(
			'error' => false,
			'message' => $response
		);
		if($response == "Error Checking if existing contact"){
			$result = array(
				'error' => true,
				'message' => $response." . Please contact your service provider !"
			);
		}elseif($response == "Erreur Token"){
			$result = array(
				'error' => true,
				'message' => $response." . Please contact your service provider !"
			);
		}
		return json_encode($result);
	}
	
/**
	 * Paramètres à placer dans le body
	 * @param  [Array] $fields les champs contact
	 * @param  [int] $api_post_id  api post id
	 * @param  [int] $value_optin 
	 * @return [String] les paramètres body
	 */
	public function get_info_update_contact($fields, $api_post_id, $value_optin, $src_nl){
		$access_token = $this->get_token();
		global $site_config;
		//initialize values
		$part_value="";//part value 
		$edito_value="";//edito Value 
		$date_optin_part = "" ; //date_optin_part
		$date_optin_edito = "" ; //envoyée si edito cochée
		$source_nl_edito = "";

		$new_config_nl_cheetah = true;

		$checked_part = false;
		$checked_edito = true;
		$checked_edito_box = false;
		$category_optin_value ="";

		$new_user = true;

		if($api_post_id == 23){
				//OLD USER
				$category_optin_value = 1;
				$new_user = false;
		}elseif($api_post_id == 0){
				//NEW USER
				$category_optin_value = 99;
		}

		$cheetah_nl = isset($site_config['cheetah_nl']) ? $site_config['cheetah_nl'] : false ;
		if( !$cheetah_nl )
			return array();
		
		//prefix from site config
		$prefix = isset($cheetah_nl['prefix']) ? $cheetah_nl['prefix'] : '';
		//acronym from site config
		$acronym = isset($cheetah_nl['acronym']) ? $cheetah_nl['acronym'] : '';
		// optins containes the checkboxes each present element means it has been checked edito/part
		$optins = isset($fields['optins']) ? $fields['optins'] : array();
		$optins_categories = isset($fields['optins_categories']) ? $fields['optins_categories'] : array();
		if(!empty($cheetah_nl['disable_unbounce_src'])){
			if(isset($optins['optin_part']) && $optins['optin_part']=='checked'){
					$checked_part = true;			
			}
		}else{
			foreach ($optins as $optin) {
				if(strpos($optin,'optin_edito')!== false){
					$checked_edito_box=true;
				}elseif(strpos($optin,'optin_part')!== false){
					$checked_part = true;			
				}
			}
		}
		if($src_nl == 'NL' && !$checked_edito_box){
			$checked_edito = false;
		}elseif($src_nl== 'inread'){
			//to keep functionnal the existing inread sent data checked <=> optin part
			$checked = isset($fields['checked']) ? $fields['checked'] : 'false';
			//convert to boolean
			$checked_part = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
		}
		if($checked_part){
			date_default_timezone_set("Europe/Paris");
			$date_optin_part= date("Y-m-d H:i");
			$current_blog_id = get_current_blog_id();
			 if($src_nl != 'NL' && isset($current_blog_id) &&get_current_blog_id() !=5) //MF inscription NL
				$checked_edito = true; //TO SUBSCRIBE TO PARTENAIRES MEANS YOU VE ALREADY SUBSCRIBED TO EDITO [except MF NL]
			//WHO CHECKED PART OPTIN
			if($api_post_id == 23){
				//OLD USER
				$part_value = 1;
			}
			elseif($api_post_id == 0 || $api_post_id == 30){
				//NEW USER
				$part_value = 1;
			}	
		}
		if($checked_edito){
			//WHO CHECKED PART edito
			$date_optin_edito = date("Y-m-d H:i");
			if($src_nl == 'NL'){
				$source_nl_edito =  isset($cheetah_nl['src_nl']) ? $cheetah_nl['src_nl'] : $src_nl;
			}elseif($src_nl == 'inread'){
				$source_nl_edito =  isset($cheetah_nl['src_nl_inread']) ? $cheetah_nl['src_nl_inread'] : $src_nl;
			}


			if($api_post_id == 23){
				//OLD USER
				$edito_value = 1;
			}
			elseif($api_post_id == 0 || $api_post_id == 30){
				//NEW USER
				$edito_value = 1;
			}	
		}
		

		$conf_optins = isset($cheetah_nl['optin']) ? $cheetah_nl['optin'] : array();

		$lang = isset($fields['lang']) ? $fields['lang'] : '';
		if($api_post_id == 0){
			if( !empty($lang) && isset($cheetah_nl['apiPostIds_by_lang']) && isset($cheetah_nl['apiPostIds_by_lang'][$lang]) ){
				$api_post_id = $cheetah_nl['apiPostIds_by_lang'][$lang];
			}else if (isset($fields['site'])){
				switch ($fields['site']) {
					case 'topsante':
						$api_post_id = 93;
						break;
					case 'pleinevie':
						$api_post_id = 94;
						break;
				}
			}else if( isset( $cheetah_nl['apiPostId']) ){
				$api_post_id =  $cheetah_nl['apiPostId'];
			}
		}


		if($src_nl == "UNBOUNCE"){
			$source_nl_edito = $src_nl;
			if (!$cheetah_nl['disable_unbounce_src']){
				$part_value = '';
				$api_post_id = isset(	$cheetah_nl['apiPostId_unbounce']) ? $cheetah_nl['apiPostId_unbounce'] :  56 ;
				$edito_value='1';
			}
			if (isset($fields['site'])){
				switch ($fields['site']) {
					case 'topsante':
						$prefix = $acronym = 'topsante';
						break;
					case 'pleinevie':
						$prefix = $acronym = 'pleinevie';
						break;
				}
			}
		}
		if ($src_nl == 'Poool') {
		 	$source_nl_edito = $src_nl;
		 	$part_value = 1;
		 	$date_optin_part = date("Y-m-d H:i");
		}
		$optin_part_key_suffix = apply_filters('optin_part_cheetah_suffix','part');
		$date_optin_part_key_suffix = apply_filters('date_optin_part_cheetah_suffix','part');
		$param_body_array = array(
			'apiPostId'=> $api_post_id,
			'data' => array(
				array(
					'name' => 'email',
					'value' =>$fields['email']
				),
				array(
					'name' => $new_config_nl_cheetah ? 'optin_edito' : $prefix.'_optin_edito',
					'value' =>$edito_value, 
				),
				array(
					'name' => $new_config_nl_cheetah ? 'date_optin_edito' : 'date_optin_'.$acronym.'_edito',
					'value' =>$date_optin_edito, 
				),
				array(
					'name' => $new_config_nl_cheetah ? 'source_edito' : 'source_'.$acronym.'_edito',
					'value' => $source_nl_edito,
				),
				array(
					'name' => $new_config_nl_cheetah ? 'optin_partenaires' : $acronym.'_optin_'.$optin_part_key_suffix,
					'value' =>$part_value
				),
				array(
					'name'  => $new_config_nl_cheetah ? 'date_optin_partenaires' : 'date_optin_'.$acronym.'_'.$date_optin_part_key_suffix,
					'value' => $date_optin_part
				),
				array(
					'name'  => 'email_md5',
					'value' => hash('md5', $fields['email'])
				),
				array(
					'name'  => 'email_sha256',
					'value' => hash('sha256', $fields['email'])
				),
			)
		);
		if($src_nl == "NL" || $src_nl == "Poool"){
			//news letter
			$source_partenaires = 'NL';
			if ($src_nl == 'Poool') {
			 	$param_body_array['data'][] = array('name'  => 'source_site', 'value' => 'Inscription_poool');
				$source_partenaires = 'Poool';
			}
			$param_body_array['data'][] = array('name'  => 'source_site', 'value' => 'LP_inscription');

			$param_body_array['data'][] = array('name'  => 'nom', 'value' => $fields['name']);
			$param_body_array['data'][] = array('name'  => 'prenom', 'value' => $fields['prenom']);
			$param_body_array['data'][] = array('name'  => 'civilité', 'value' => $fields['civilite']);
			$param_body_array['data'][] = array('name'  => 'code postal', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'adresse', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'ville', 'value' =>"");
			$param_body_array['data'][] = array('name'  => 'pays', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'telephone', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'date_creation', 'value' => "");
			$param_body_array['data'][] = array('name'  => 'proprietaire', 'value' => "");

			if( isset($fields['date_de_naissance']) && !empty($fields['date_de_naissance']) ){
				$param_body_array['data'][] = array('name'  => 'date_de_naissance', 'value' => $fields['date_de_naissance']);
			}
			
			if( isset($fields['secteur']) && !empty($fields['secteur']) ){
				$param_body_array['data'][] = array('name'  => 'secteur', 'value' => $fields['secteur']);
			}
			if( isset($fields['profession']) && !empty($fields['profession']) ){
				$param_body_array['data'][] = array('name'  => 'profession', 'value' => $fields['profession']);
			}


			if( $cheetah_nl['add_outpout_params'] ){
				$param_body_array['data'][] = array('name'  => 'date_optout_'.$acronym.'_edito', 'value' => $date_optin_edito);
				$param_body_array['data'][] = array('name'  => 'raison_optout_'.$acronym.'_edito', 'value' => 'texte');
				$param_body_array['data'][] = array('name'  => 'source_'.$prefix.'_part', 'value' => $src_nl);
				$param_body_array['data'][] = array('name'  => 'date_optout_'.$acronym.'_part', 'value' => $date_optin_part);
				$param_body_array['data'][] = array('name'  => 'raison_optout_'.$acronym.'_part', 'value' => 'texte');
			}

			if($new_config_nl_cheetah){
				$site_name = isset($cheetah_nl['categorie_cheetah']) ? $cheetah_nl['categorie_cheetah'] : htmlspecialchars_decode( get_bloginfo() );
				$param_body_array['data'][] = array('name'  => 'categorie', 'value' => $site_name);
				$param_body_array = apply_filters('cheetah_param_body_array', $param_body_array, count($optins_categories));
				$param_body_array['data'][] = array('name'  => 'source_partenaires', 'value' =>  $part_value ? $source_partenaires : '');
				$param_body_array['apiPostId'] = '';
				if($new_user && isset($cheetah_nl['apiPostId'])){
					$param_body_array['apiPostId'] = $cheetah_nl['apiPostId'];
				}else{
					$param_body_array['apiPostId'] = 161;
				}
			}

		}
		if($src_nl == "UNBOUNCE"){
			if( isset($fields['civilite']) && !empty($fields['civilite'])){
				$civilite ='';
				if ($fields['civilite']=='Mr'){
					$civilite ='1';
				}
				else $civilite ='2';
				$param_body_array['data'][] = array('name'  => 'civilite', 'value' => $civilite);
			}
			if( isset($fields['nom']) && !empty($fields['nom']) ){
				$param_body_array['data'][] = array('name'  => 'nom', 'value' => $fields['nom']);
			}
			if( isset($fields['prenom']) && !empty($fields['prenom']) ){
				$param_body_array['data'][] = array('name'  => 'prenom', 'value' => $fields['prenom']);
			}
		}
		if($src_nl == "premium"){
			if( isset($fields['email']) && !empty($fields['email']) ){
				$param_body_array['data'][] = array('name'  => 'email', 'value' => $fields['email']);
			}
		}
		if( !empty($lang) && isset($cheetah_nl['tme_lang']) && isset($cheetah_nl['tme_lang'][$lang]) ){
			$param_body_array['data'][] = array('name'  => $cheetah_nl['tme_lang'][$lang], 'value' => 1);
		}
		//TO CONFIRM
		$optins=$optins_categories;
		$nbr_optins = count($optins);
		for ($i=0; $i < $nbr_optins; $i++) {
			
			$param_body_array['data'][] = array('name'  => $optins[$i], 'value' => $category_optin_value);
			
			if(!empty($conf_optins[$optins[$i]]['date_opt']))
				$param_body_array['data'][] = array('name'  => $conf_optins[$optins[$i]]['date_opt'], 'value' => date('Y-m-d H:i'));
			if($conf_optins[$optins[$i]]['src_opt'])
				$param_body_array['data'][] = array('name'  => $conf_optins[$optins[$i]]['src_opt'], 'value' => $src_nl);
		}
		$info = array(
			'method' => 'POST',
			'timeout'=> 15,
			'headers' => array(
				'authorization' => 'Bearer '.$access_token,
				'accept' => 'application/json',
				'content-type' => 'application/json'
			),
			'body' => json_encode($param_body_array)
		);
		
		return $info;
	}

	/**
	 * Insertion ou Modification d'un contact
	 * @param  [Array] $fields les champs contact
	 * @return  [String] 
	 */
	public function update_contact($fields, $src_nl){
		global $site_config;
		$check = $this->check_contact($fields['email'], $fields['optins']);
		$apiPostId =  isset($site_config['cheetah_nl']['apiPostId']) ? $site_config['cheetah_nl']['apiPostId'] : 0;
		if($check == "NOT_EXIST"){
				$response = wp_remote_post(
					"https://api.ccmp.eu/services2/api/Recipients",
					$this->get_info_update_contact($fields, 0, 99, $src_nl)
				);		
			if($response['response']['code'] == 200)
				return "NEW_USER";
		}else if($check == "EXIST"){
				$response = wp_remote_post(
					"https://api.ccmp.eu/services2/api/Recipients",
					$this->get_info_update_contact($fields, 23, 1, $src_nl)
				);	
			if($response['response']['code'] == 200)
				return "UPDATE_USER";
		}
		return $check;
	}

	/**
	 * Récupération de la configuration du template
	 * @param  [type] $id l'ID du template
	 * @return [String]
	 */
	public function get_template_config($id){
		$access_token = $this->get_token();
		if($access_token != null){
			$response = wp_remote_get(
				"https://api.ccmp.eu/services2/api/EmailCampaign?id=$id",
				array(
					'method' => 'GET',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => array(),
				)
			);
			if($response['body']){
				return json_decode($response['body'], true);
			}
			return "Erreur récupération de config";
		}
		return "Erreur Token";
	}

	/**
	 * Creation de la compagne
	 * @param  [String] $json le contenu du body dans la fonction wp_Remote_post
	 * @return [Array] 
	 */
	public function create_campagne($json){
		$access_token = $this->get_token();
		if($access_token != null){
			$response = wp_remote_post(
				"https://api.ccmp.eu/services2/api/EmailCampaign",
				array(
					'method' => 'POST',
					'timeout'=> 50,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => $json,
				)
			);
			if($response['response']['code'] == 200){
				$body_response = json_decode($response['body'], true);
				return array(
					'result' => 'success',
					'ID'	 => $body_response['campId']
				);
			}else if(json_decode($response['body'], true)['message'] == "An Obj with this name already exists"){
				return array('result' => 'An Obj with this name already exists',);
			}
			return array('result' => 'Erreur de création compagne',);
		}
		return array('result' => 'Erreur Token',);
	}


	/**
	 * Le lancement de la compagne  ==> LAUNCH
	 * @param  [int] $campagn_ID l'ID de la compagne
	 * @return [Array]
	 */
	public function launch_compagne($campagn_ID, $compName){
		$access_token = $this->get_token();
		if($access_token){
			$response = wp_remote_request(
				"https://api.ccmp.eu/services2/api/EmailCampaign?id=".$campagn_ID,
				array(
					'method' => 'PUT',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => '{
						"CampId": "'.$campagn_ID.'",
						"CampAction": "LAUNCH"
					}',
				)
			);
			if($response['response']['code'] == 200 || (json_decode($response['body'], true)['message'] == "Campaign has been launched already.")){
				sleep(5);
				$this->approve_compagne($campagn_ID, $compName);
				return array('result' => 'success');
			}else{
				return array('result' => "Erreur LAUNCH");
			}
		}
		return array('result' => 'Erreur Token',);
	}


	/**
	 * Le lancement de la compagne  ==> APPROVE
	 * @param  [int] $campagn_ID l'ID de la compagne
	 * @return [Array]
	 */
	public function approve_compagne($campagn_ID, $compName){
		$access_token = $this->get_token();
		if($access_token){
			$response = wp_remote_request(
				"https://api.ccmp.eu/services2/api/EmailCampaign?id=".$campagn_ID,
				array(
					'method' => 'PUT',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => '{
						"CampId": "'.$campagn_ID.'",
						"CampAction": "APPROVE"
					}',
				)
			);
			if($response['response']['code'] == 200 || (json_decode($response['body'], true)['message'] == "Campaign has been launched already.")){
				$this->send_alert($compName);
				return array('result' => 'success');
			}else{
				return array('result' => "Erreur APPROVE ");
			}
		}
		return array('result' => 'Erreur Token',);
	}


	private function send_alert($id){
		$headers = array();
		$headers[] = 'From: no-replay@reworldmediafactory.fr';
		$headers[] = 'Content-Type: text/plain'; 
		$headers[] = 'charset=utf-8';
		$to = array('crm@reworldmediafactory.fr');
		$to = apply_filters('emails_cheetah', $to);
		$subject = '[CHEETAH] - Nouvelle campaigne - '.$id;
		$message = 'Une nouvelle newsletter a été créée et enregistrée via le service CHEETAH.';
		wp_mail( $to, $subject, $message, $headers );
	}

}

global $cheetah;
$cheetah =  new Cheetah();


// Actions
add_action('init', 'launch_compagne_ajax');
add_action('init', 'update_user_chetaah_ajax');
add_action('init', 'create_campagne_chetaah_ajax');
add_action( 'wp', 'ws_partenaire_cheetah' );
add_action('wp','ws_cheetah_api',1,1);
function ws_cheetah_api(){
	global $wp_query;
	if(!empty($wp_query->query['category_name']) && $wp_query->query['category_name']=='api/cheetah'){	
		$cheetah =  new Cheetah();
		if(isset($_GET['action']) && $_GET['action'] == 'check_exist_email'){
			header("HTTP/1.1 200 OK");
			echo $cheetah->check_exist_email();
			exit();
		}
	}
}

// Filters 
add_filter('default_option_rewrite_rules',  'create_rewrite_rules_cheetah');
add_filter('option_rewrite_rules', 'create_rewrite_rules_cheetah');



// Fonctions
function launch_compagne_ajax(){
	if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'launch_compagne_ajax') ){
		global $cheetah ;
		$compagn_ID = isset($_POST['compagn_ID']) ? $_POST['compagn_ID'] : '';
		$compName = isset($_POST['compName']) ? $_POST['compName'] : '';
		echo json_encode($cheetah->launch_compagne($compagn_ID,$compName));;
		exit();
	}
}

function update_user_chetaah_ajax(){
	if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'update_user_chetaah_ajax') ){
		if (isset($_POST['src_nl']) && $_POST['src_nl'] === 'Poool') {
			check_ajax_referer( 'poool_cheetah', 'nonce_poool' );
		}
		global $cheetah;
		$fields = isset($_POST['fields']) ? $_POST['fields'] : '';
		$src_nl = isset($_POST['src_nl']) ? $_POST['src_nl'] : '';
		if( $fields && $src_nl ){
			echo json_encode($cheetah->update_contact($fields, $src_nl));
		}
		echo false;
		exit();
	}
}

function create_campagne_chetaah_ajax(){
	if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'create_campagne_chetaah_ajax') ){
		global $cheetah;
		$compName = isset($_POST['compName']) ? $_POST['compName'] : '';
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : '';
		$obj = isset($_POST['obj']) ? $_POST['obj'] : '';
		$data_html = isset($_POST['data_html']) ? $_POST['data_html'] : '';
		$template_id = isset($_POST['template_id']) ? $_POST['template_id'] : '';

		$json = false;
		if($compName && $startTime && $obj && $data_html ){
			$array_config = $cheetah->get_template_config($template_id);

			if($array_config != null){

				$campParam = $array_config['campParam'];
				$emailMsgTemplate = $array_config['emailMsgTemplate'];
				$parent_obj_id = $array_config['obj']['parent_obj_id'];

				$json = <<<HJSON
				{
					"campName": "$compName",
					"custId": $array_config[custId],
					"entityId": $array_config[entityId],
					"channelTypeId": "$array_config[channelTypeId]",
					"typeId": "$array_config[typeId]",
					"toFilterId": $array_config[toFilterId],
					"contBodies": [
						{
							"type": "HTML",
							"usageMask": "ALL_EMAIL_STYLE_USAGE_MASK",
							"body" : "$data_html"
						}
					],
					"linkTrackingUsageMask": "$array_config[linkTrackingUsageMask]",
					"linkTrackingDomainId": $array_config[linkTrackingDomainId],
					"campParam": {
						"shortenLinksFlag": $campParam[shortenLinksFlag],
						"carryOverToNextDayFlag": $campParam[carryOverToNextDayFlag],
						"stopNightDeliveryFlag": $campParam[stopNightDeliveryFlag],
						"ignoreConfirmationFlag": $campParam[ignoreConfirmationFlag],
						"sendSchedule": {
							"StartTime": "$startTime",
							"timeZone": "Romance_Standard_Time"
						},
						"queueSchedule": {
							"StartTime": "$startTime",
							"timeZone": "Romance_Standard_Time"
						}
					},
					"campStepProcedures": [],
					"emailMsgTemplate": {
						"fromName": "$emailMsgTemplate[fromName]",
						"toName": "{(email)}",
						"toAddressPropId": $emailMsgTemplate[toAddressPropId],
						"fromAddressId": $emailMsgTemplate[fromAddressId],
						"Subject": "$obj",
						"codePageId": "$emailMsgTemplate[codePageId]",
						"vmtaPoolId": "$emailMsgTemplate[vmtaPoolId]",
						"proofSubjectPrefix": "PROOF"
					},
					"obj": {
						"display_name": "$compName",
						"type_id": "CampaignEmail",
						"parent_obj_id": $parent_obj_id,
						"eligibility_status_id": "READY"
					}
				}
HJSON;
			}
			echo json_encode($cheetah->create_campagne($json));
			exit();
		}
		echo false;
		exit();
	}
}



/**
 * Création de l'URL personnalisée pour API
 * @param  [Array] $rewrite
 * @return [Array]
 */
function create_rewrite_rules_cheetah($rewrite) {
	global $wp_rewrite;
	$new_rules = array( 'api\/crm$' => 'index.php?category_name=crm_api01' );
	if(!is_admin() && is_array($rewrite) ){
 		$rewrite = $new_rules + $rewrite;
	}
	return $rewrite;
}


/**
 * Creation WP pour les partenaire pour la récupération des emails par la methode GET de l'API cheetah
 * @return [json]
 */
function ws_partenaire_cheetah(){
	global $wp_query;
	if( isset($wp_query->query['category_name']) && ($wp_query->query['category_name'] == 'crm_api01') ){
		$config = array(
			'1Cnj0ctKE2h29aGAlUNt2Hw85eJdZwz0' => array(
				"name" => 'Eperflex',
				"fields" => array(
					'email' => 'email',
					'firstname' => 'nom',
					'lastname' => 'prenom',
					'civilite' => 'civilite',
					'MD5' => 'email_md5'
				)
			),
			'6PauNj3MjYeVISe4YOqCnnX38HkCkAWV' => array(
				"name" => 'Notify',
				"fields" => array(
					'email' => 'email',
					'firstname' => 'nom',
					'lastname' => 'prenom',
					'zipcode' => 'code_postal',
					'city' => 'ville',
					'dob' => 'date_de_naissance',
					'phone' => 'telephone',
					'title' => 'civilite'
				)
			),
			/** 
				service squadata pour le traitement des requets de type : 
					/api/crm?email=f3be1da7d40248f0d7f2f982fba37d02&token_api=DGCzeXGdlRHLfvEFu89SErULxlIjGhIZ&hash=md5
				c'est appliquable pour tout le projet network. (ticket TM : 10839)
			**/
			'DGCzeXGdlRHLfvEFu89SErULxlIjGhIZ' => array(
				"name" => 'Squadata',
				"fields" => array(
					'email' => 'email',
					'firstname' => 'nom',
					'lastname' => 'prenom',
					'civilite' => 'civilite'
				)
			)
		);

		header("Content-type: application/json; charset=utf-8");
		if( isset($_GET['token_api']) && isset($config[$_GET['token_api']]) ){
			$hash = ( isset($_GET['hash']) && $_GET['hash'] == "sha256" ) ? 'email_sha256' : 'email_md5';
			$cheetah_api = new Cheetah();
			$access_token = $cheetah_api->get_token();
			$url_api = "https://api.ccmp.eu/services2/api/SearchRecords?viewName=Contacts&prop=email,nom,prenom,date_de_naissance,ville,telephone,civilite,email_md5,email_md5,sha256&columnName=".$hash."&operation=eq&param=".$_GET['email'];

			$response = wp_remote_get(
				$url_api,
				array(
					'method' => 'GET',
					'timeout'=> 15,
					'headers' => array(
						'authorization' => 'Bearer '.$access_token,
						'accept' => 'application/json',
						'content-type' => 'application/json'
					),
					'body' => array(),
				)
			);
			if(is_array($response) && $response['response']['code'] ==  200){
				$properties = json_decode($response['body'])[0]->properties;
				$array_properties = array();
				foreach ($properties as $key => $value) {
					$array_properties[$value->propName] = $value->value;
				}
				http_response_code(200);
				$json = array(
					'status'	=> true,
				);
				foreach ($config[$_GET['token_api']]['fields'] as $key => $value) {
					$json['data'][$key] = $array_properties[$value];
				}

			}else{
				http_response_code(404);
				$json = array(
					'status'	=> false,
					'message' => "Email address does not exist"
				);
			}
			echo json_encode($json);
			exit();

		}else{
			http_response_code(404);
			echo json_encode(array('message' => "Permission denied !!"));
			exit();
		}
	}
}