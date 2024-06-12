<?php

class GAIACRM
{
    static $instance = null; 

    

    private function __construct()
    {

    }
    public static function getInstance(){
        if(!isset(GAIACRM::$instance)) GAIACRM::$instance = new GAIACRM();
        return GAIACRM::$instance;
    }
    
    /**
     * Login and GET Access Token if not already in the envirement 
     */
    private function login()
    {
        $is_expired = true;
        if (isset($_ENV["GAIA_KEY_EXPIRATION_DATE"])){
            $expiration_date = $_ENV["GAIA_KEY_EXPIRATION_DATE"]; 
            $is_expired = (strtotime($expiration_date) - time()) > 0;    
            if (!$is_expired && isset($_ENV["GAIA_API_KEY"])) {
                return $_ENV["GAIA_API_KEY"];
            }
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://eventflow.svc.calypso-event.net/eventflow/account/getAccessToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "login":"t.aittouda@webpick.info",
                "password":"uYpTjHGEC!jJ"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: Application/Json',
                'X-GAIA-ClientApp: ApiWsBO'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        if($data){
            if($data->isValid){
                $_ENV["GAIA_API_KEY"] = $data->data->accessToken;
                $_ENV["GAIA_KEY_EXPIRATION_DATE"] = $data->data->expires;
                return $_ENV["GAIA_KEY_EXPIRATION_DATE"];
            }
        }
    }

    private function checkEmailIfExist($email){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://eventflow.svc.calypso-event.net/eventflow/entity/get',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "Individu": {
                "inlineCount":true, 
                "fields":[
                    "Email","x_Import_visiteurssaloreno"
                ], 
                "filter":{
                    "type": "ClauseSet",
                    "operator": "And",
                    "clauses":[
                        {
                            "type": "Condition",
                            "leftOperand": {
                                "type": "FieldPath",
                                "fieldPath": "Email"
                            },
                            "operator": "Equal",
                            "rightOperand": {
                                "type": "Value",
                                "value": "'.$email.'"
                            }
                        }
                    ]
                },
                "start" : 1, 
                "take" : 1
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Gaia '.$_ENV["GAIA_API_KEY"],
            'X-GAIA-ClientApp: ApiWsBO',
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        if ($data) {
            if ($data->isValid && $data->count > 0) {
                return $data->data[0];
            }
            return null;
        }
    }
    /**
     * Modifier un contact deja existant 
     * mettre x_Import_visiteurssaloreno true  
     */
    private function subscribeToNL($email,$id)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://eventflow.svc.calypso-event.net/eventflow/entity/save',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "Individu":[
                {
                    "_ActionFlag": "update",
                    "x_Import_visiteurssaloreno": true,
                    "Email":"'.$email.'",
                    "Id": "'.$id.'"
                }
            ]
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Gaia ' . $_ENV["GAIA_API_KEY"],
            'X-GAIA-ClientApp: ApiWsBO',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response)->isValid;
    }
    /**
     * Creer un contact qui n'existe pas 
     * mettre x_Import_visiteurssaloreno true  
     */
    private function registerToNL($email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://eventflow.svc.calypso-event.net/eventflow/entity/save',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "Individu":[
                {
                    "_ActionFlag": "create",
                    "x_Import_visiteurssaloreno": true,
                    "Email":"'.$email.'",
                    "Id": "d743d4eb-a5e4-eb11-80f3-005056ae0696"
                }
            ]
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Gaia ' . $_ENV["GAIA_API_KEY"],
            'X-GAIA-ClientApp: ApiWsBO',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response)->isValid;
    } 
    
    public function synchronyzeDbToGaia($email)
    {
        $this->login();
        $user = $this->checkEmailIfExist($email);
        if (isset($user)){
            if (!$user->x_Import_visiteurssaloreno) $this->subscribeToNL($email,$user->Id);
        }
        else{
            $this->registerToNL($email);
        }
    }
}


