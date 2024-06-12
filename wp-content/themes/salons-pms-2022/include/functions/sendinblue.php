<?php

class SendinBlueCRM {

    /**
     * function to add a contact to the SendinBlue CRM
     * @param array $contact_sample
     * @return bool|string
     */

    static function addContact($contact) {
        $ch = curl_init();
        // $contact_sample = array (
        //     'email' => 't.aittouda@gmail.com',
        //     'attributes' => [
        //         'NOM' => 'Tachafine',
        //         'PRENOM' => 'AIT TOUDA',
        //         'DOUBLE_OPT-IN' => "1",
        //         'SMS' => '+33123456789',
        //         'OPT_IN' => true,
        //     ],
        //     'emailBlacklisted' => false,
        //     'smsBlacklisted' => false,
        //     'listIds' => [2],
        //     'updateEnabled' => false,
        //     'smtpBlacklistSender' => ['user@example.com']
        // );
        curl_setopt($ch, CURLOPT_URL, 'https://api.sendinblue.com/v3/contacts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contact));

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Api-Key: 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';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        $result = json_decode($result, true);
        if (!empty($result['id'])) {
            return "NEW_USER";
        } else {
            return "UPDATE_USER";
        }
    }

    /**
     * function to add a wordpress ajax action that will be called when the user click on the button
     * to retrieve the contact list parameters and call the addContact function and display the result
     * @return void
     */

    static function addContactAjax() {
        add_action('init', array('SendinBlueCRM', 'addContactAjaxCallback'));
    }

    /**
    * function to add a wordpress ajax callback that will be called when the user click on the button
    * to retrieve the contact list parameters and call the addContact function and display the result
    * @return void
    */

    static function addContactAjaxCallback() {

        if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'update_user_sendinblue_ajax') ){
            if (isset($_POST['src_nl']) && $_POST['src_nl'] === 'Poool') {
                check_ajax_referer( 'poool_cheetah', 'nonce_poool' );
            }
            $fields = isset($_POST['fields']) ? $_POST['fields'] : '';
            $src_nl = isset($_POST['src_nl']) ? $_POST['src_nl'] : '';
            if( $fields && $src_nl ){
                $contact = array(
                    'email' => $fields['email'],
                    'attributes' => array(
                        'SMS' => $fields['phone'],
                        'NOM' => $fields['name'],
                        'PRENOM' => $fields['prenom'],
                        'DOUBLE_OPT-IN' => (count($fields['optins'])==2) ? '1' : '0',
                        'OPT_IN' =>  (count($fields['optins'])>0),
                    ),
                    'emailBlacklisted' => false,
                    'smsBlacklisted' => false,
                    'listIds' => array(2),
                    'updateEnabled' => false,
                );
                echo json_encode(SendinBlueCRM::addContact($contact));
            }
            echo false;
            exit();
        }
    }
}

SendinBlueCRM::addContactAjax();