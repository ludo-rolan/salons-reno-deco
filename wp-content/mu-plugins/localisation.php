<?php
class Localisation_Geoip
{
    public static function get_the_ip($ip = NULL, $deep_detect = TRUE)    {

        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            return $ip;
        }    }
    public static function ip_info()
    {

        $ip = self::get_the_ip();
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $accessToken = "466089e87c4849515a644813488a75c3";
            $ipdat = json_decode(file_get_contents("http://api.ipstack.com/" . $ip . "?access_key=" . $accessToken . "&output=json&legacy=1"));
            return $ipdat;
        }
        else
            return false;
    }
    /**
     * Get country code from cureent user avec utilisation du web service.
     * Le web service utilisé est geoplugin
     * @return string Country code or false if error
     */
    public static function client_ip_info()
    {
        $ip = self::get_the_ip();

        if (empty($ip)) {
            return false;
        }
        $url = 'http://www.geoplugin.net/php.gp?ip=' . $ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data_country = curl_exec($ch);
        curl_close($ch);
        if ($data_country) {
            return unserialize($data_country);
        }
        else
            return false;
    }
    /**
     * Get country code from IP
     * @return string Country code
     */
    public static function get_country_code()
    {

        if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            return ($_SERVER['HTTP_CF_IPCOUNTRY']);
        }
        //Si aucune des informations précedentes ne sont pas présentes, on tante de récupérer le pays par l'ip du client
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $data_country = self::client_ip_info();
            if (isset($data_country['geoplugin_status']) && $data_country['geoplugin_status'] == 200 && isset($data_country['geoplugin_countryCode'])) {
                return ($data_country['geoplugin_countryCode']);
            }
            else {
                $ipdat = self::ip_info();
                if (@strlen(trim($ipdat->country_code)) == 2) {
                    return (@$ipdat->country_code);
                }
            }
        }
        return false;
    }
    public static function get_country_language()
    {

        if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            return self::convert_pays_to_langue($_SERVER['HTTP_CF_IPCOUNTRY']);
        }
        //Si aucune des informations précedentes ne sont pas présentes, on tante de récupérer le pays par l'ip du client
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $data_country = self::client_ip_info();
            if (isset($data_country['geoplugin_status']) && $data_country['geoplugin_status'] == 200 && isset($data_country['geoplugin_countryCode'])) {
                return self::convert_pays_to_langue($data_country['geoplugin_countryCode']);
            }
            else {
                $ipdat = self::ip_info();
                if (@strlen(trim($ipdat->country_code)) == 2) {
                    return self::convert_pays_to_langue(@$ipdat->country_code);
                }
            }
        }
        return false;
    }

    public static function convert_pays_to_langue($pays_code)
    {
        $pays_info = self::$pays_langue[strtoupper($pays_code)];
        $pays_info['pays_iso_code'] = strtoupper($pays_code);

        return $pays_info;
    }
    public static $pays_langue = array(
        "AE" => array(
            "Libellé Pays FR" => "EMIRATS ARABES UNIS",
            "Libellé Pays EN" => "UNITED ARAB EMIRATES",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"))
        ),
        "AM" => array(
            "Libellé Pays FR" => "Arménie",
            "Libellé Pays EN" => "ARMENIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English")
            )
        ),

        "AO" => array(
            "Libellé Pays FR" => "ANGOLA",
            "Libellé Pays EN" => "ANGOLA",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ),
                "PT" => array(
                    "Libellé Langue FR" => "portugais",
                    "Libellé Langue EN" => "Portuguese",
                )
            )
        ),
        "AR" => array(
            "Libellé Pays FR" => "ARGENTINE",
            "Libellé Pays EN" => "ARGENTINA",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "AT" => array(
            "Libellé Pays FR" => "AUTRICHE",
            "Libellé Pays EN" => "AUSTRIA",
            "Code Langue" => array("DE" => array(
                    "Libellé Langue FR" => "allemand",
                    "Libellé Langue EN" => "German"))
        ),
        "AU" => array(
            "Libellé Pays FR" => "AUSTRALIE",
            "Libellé Pays EN" => "AUSTRALIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "BE" => array(
            "Libellé Pays FR" => "BELGIQUE",
            "Libellé Pays EN" => "BELGIUM",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English",
                ),
                "FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French",
                ),
                "NL" => array(
                    "Libellé Langue FR" => "néerlandais",
                    "Libellé Langue EN" => "Dutch",
                )
            )
        ),
        "BF" => array(
            "Libellé Pays FR" => "BURKINA FASO",
            "Libellé Pays EN" => "BURKINA FASO",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "BG" => array(
            "Libellé Pays FR" => "BULGARIE",
            "Libellé Pays EN" => "BULGARIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "BH" => array(
            "Libellé Pays FR" => "BAHREIN",
            "Libellé Pays EN" => "BAHRAIN",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "BJ" => array(
            "Libellé Pays FR" => "Bénin",
            "Libellé Pays EN" => "BENIN",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "BR" => array(
            "Libellé Pays FR" => "Brésil",
            "Libellé Pays EN" => "BRAZIL",
            "Code Langue" => array("PT" => array(
                    "Libellé Langue FR" => "portugais",
                    "Libellé Langue EN" => "Portuguese", ))

        ),
        "CA" => array(
            "Libellé Pays FR" => "CANADA",
            "Libellé Pays EN" => "CANADA",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ),
                "FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French"
                )
            )
        ),
        "CD" => array(
            "Libellé Pays FR" => "REP DEM DU CONGO",
            "Libellé Pays EN" => "CONGO THE DEM REP",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "CF" => array(
            "Libellé Pays FR" => "REP CENTRAFRICAINE",
            "Libellé Pays EN" => "CENTRAL AFRICAN REP",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "CG" => array(
            "Libellé Pays FR" => "CONGO BRAZZAVILLE",
            "Libellé Pays EN" => "CONGO BRAZZAVILLE",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "CH" => array(
            "Libellé Pays FR" => "SUISSE",
            "Libellé Pays EN" => "SWITZERLAND",
            "Code Langue" => array(
                "DE" => array(
                    "Libellé Langue FR" => "allemand",
                    "Libellé Langue EN" => "German"
                ), "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ), "FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French"
                )
            )
        ),
        "CI" => array(
            "Libellé Pays FR" => "Côte d Ivoire",
            "Libellé Pays EN" => "Côte d Ivoire",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))
        ),
        "CL" => array(
            "Libellé Pays FR" => "CHILI",
            "Libellé Pays EN" => "CHILE",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "CM" => array(
            "Libellé Pays FR" => "CAMEROUN",
            "Libellé Pays EN" => "CAMEROON",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "CN" => array(
            "Libellé Pays FR" => "CHINE",
            "Libellé Pays EN" => "CHINA",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ), "ZH" => array(
                    "Libellé Langue FR" => "chinois",
                    "Libellé Langue EN" => "Chinese"
                )
            )

        ),
        "CO" => array(
            "Libellé Pays FR" => "COLOMBIE",
            "Libellé Pays EN" => "COLOMBIA",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "CR" => array(
            "Libellé Pays FR" => "COSTA RICA",
            "Libellé Pays EN" => "COSTA RICA",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "CU" => array(
            "Libellé Pays FR" => "CUBA",
            "Libellé Pays EN" => "CUBA",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "CZ" => array(
            "Libellé Pays FR" => "Rep Tchèque",
            "Libellé Pays EN" => "CZECH REP",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "DE" => array(
            "Libellé Pays FR" => "ALLEMAGNE",
            "Libellé Pays EN" => "GERMANY",
            "Code Langue" => array(
                "DE" => array(
                    "Libellé Langue FR" => "allemand",
                    "Libellé Langue EN" => "German"
                ), "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                )
            )


        ),
        "DJ" => array(
            "Libellé Pays FR" => "DJIBOUTI",
            "Libellé Pays EN" => "DJIBOUTI",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "DK" => array(
            "Libellé Pays FR" => "DANEMARK",
            "Libellé Pays EN" => "DENMARK",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "DO" => array(
            "Libellé Pays FR" => "REP DOMINICAINE",
            "Libellé Pays EN" => "DOMINICAN REP",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "DZ" => array(
            "Libellé Pays FR" => "Algérie",
            "Libellé Pays EN" => "ALGERIA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "EC" => array(
            "Libellé Pays FR" => "EQUATEUR",
            "Libellé Pays EN" => "ECUADOR",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "EG" => array(
            "Libellé Pays FR" => "EGYPTE",
            "Libellé Pays EN" => "EGYPT",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "ES" => array(
            "Libellé Pays FR" => "ESPAGNE",
            "Libellé Pays EN" => "SPAIN",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "FI" => array(
            "Libellé Pays FR" => "FINLANDE",
            "Libellé Pays EN" => "FINLAND",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "FR" => array(
            "Libellé Pays FR" => "FRANCE",
            "Libellé Pays EN" => "FRANCE",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GA" => array(
            "Libellé Pays FR" => "GABON",
            "Libellé Pays EN" => "GABON",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GB" => array(
            "Libellé Pays FR" => "ROYAUME UNI",
            "Libellé Pays EN" => "UNITED KINGDOM",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "GE" => array(
            "Libellé Pays FR" => "GEORGIE",
            "Libellé Pays EN" => "GEORGIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "GF" => array(
            "Libellé Pays FR" => "Guyane Française",
            "Libellé Pays EN" => "FRENCH GUIANA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GH" => array(
            "Libellé Pays FR" => "GHANA",
            "Libellé Pays EN" => "GHANA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "GN" => array(
            "Libellé Pays FR" => "Guinée",
            "Libellé Pays EN" => "GUINEA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GP" => array(
            "Libellé Pays FR" => "GUADELOUPE",
            "Libellé Pays EN" => "GUADELOUPE",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GQ" => array(
            "Libellé Pays FR" => "Guinée Equatoriale",
            "Libellé Pays EN" => "EQUATORIAL GUINEA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "GR" => array(
            "Libellé Pays FR" => "Grèce",
            "Libellé Pays EN" => "GREECE",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "HK" => array(
            "Libellé Pays FR" => "HONG KONG",
            "Libellé Pays EN" => "HONG KONG",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "HR" => array(
            "Libellé Pays FR" => "CROATIE",
            "Libellé Pays EN" => "CROATIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "HT" => array(
            "Libellé Pays FR" => "HAITI",
            "Libellé Pays EN" => "HAITI",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "HU" => array(
            "Libellé Pays FR" => "HONGRIE",
            "Libellé Pays EN" => "HUNGARY",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "ID" => array(
            "Libellé Pays FR" => "Indonésie",
            "Libellé Pays EN" => "INDONESIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "IE" => array(
            "Libellé Pays FR" => "IRLANDE",
            "Libellé Pays EN" => "IRELAND",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "IL" => array(
            "Libellé Pays FR" => "Israël",
            "Libellé Pays EN" => "ISRAEL",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "IN" => array(
            "Libellé Pays FR" => "INDE",
            "Libellé Pays EN" => "INDIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "IR" => array(
            "Libellé Pays FR" => "IRAN",
            "Libellé Pays EN" => "IRAN",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "IT" => array(
            "Libellé Pays FR" => "ITALIE",
            "Libellé Pays EN" => "ITALY",
            "Code Langue" => array("IT" => array(
                    "Libellé Langue FR" => "italien",
                    "Libellé Langue EN" => "Italian", ))

        ),
        "JP" => array(
            "Libellé Pays FR" => "JAPON",
            "Libellé Pays EN" => "JAPAN",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ), "JA" => array(
                    "Libellé Langue FR" => "japonais",
                    "Libellé Langue EN" => "Japanese"
                )
            )

        ),
        "KE" => array(
            "Libellé Pays FR" => "KENYA",
            "Libellé Pays EN" => "KENYA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "LU" => array(
            "Libellé Pays FR" => "LUXEMBOURG",
            "Libellé Pays EN" => "LUXEMBOURG",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "MA" => array(
            "Libellé Pays FR" => "MAROC",
            "Libellé Pays EN" => "MOROCCO",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "MG" => array(
            "Libellé Pays FR" => "MADAGASCAR",
            "Libellé Pays EN" => "MADAGASCAR",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "ML" => array(
            "Libellé Pays FR" => "MALI",
            "Libellé Pays EN" => "MALI",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "MQ" => array(
            "Libellé Pays FR" => "MARTINIQUE",
            "Libellé Pays EN" => "MARTINIQUE",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "MU" => array(
            "Libellé Pays FR" => "Ile Maurice",
            "Libellé Pays EN" => "MAURITIUS",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "MX" => array(
            "Libellé Pays FR" => "MEXIQUE",
            "Libellé Pays EN" => "MEXICO",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "MY" => array(
            "Libellé Pays FR" => "MALAISIE",
            "Libellé Pays EN" => "MALAYSIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "NC" => array(
            "Libellé Pays FR" => "Nlle Calédonie",
            "Libellé Pays EN" => "NEW CALEDONIA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "NG" => array(
            "Libellé Pays FR" => "Nigéria",
            "Libellé Pays EN" => "NIGERIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "NL" => array(
            "Libellé Pays FR" => "PAYS BAS",
            "Libellé Pays EN" => "NETHERLANDS",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "NO" => array(
            "Libellé Pays FR" => "Norvège",
            "Libellé Pays EN" => "NORWAY",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "NZ" => array(
            "Libellé Pays FR" => "NLLE ZELANDE",
            "Libellé Pays EN" => "NEW ZEALAND",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "PA" => array(
            "Libellé Pays FR" => "PANAMA",
            "Libellé Pays EN" => "PANAMA",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "PE" => array(
            "Libellé Pays FR" => "Pérou",
            "Libellé Pays EN" => "PERU",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "PF" => array(
            "Libellé Pays FR" => "Polynésie Française",
            "Libellé Pays EN" => "FRENCH POLYNESIA",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "PH" => array(
            "Libellé Pays FR" => "PHILIPPINES",
            "Libellé Pays EN" => "PHILIPPINES",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "PL" => array(
            "Libellé Pays FR" => "POLOGNE",
            "Libellé Pays EN" => "POLAND",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "PT" => array(
            "Libellé Pays FR" => "PORTUGAL",
            "Libellé Pays EN" => "PORTUGAL",
            "Code Langue" => array("PT" => array(
                    "Libellé Langue FR" => "portugais",
                    "Libellé Langue EN" => "Portuguese", ))

        ),
        "RE" => array(
            "Libellé Pays FR" => "Réunion",
            "Libellé Pays EN" => "REUNION",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "RU" => array(
            "Libellé Pays FR" => "RUSSIE",
            "Libellé Pays EN" => "RUSSIA",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ), "RU" => array(
                    "Libellé Langue FR" => "russe",
                    "Libellé Langue EN" => "Russian"
                )
            )

        ),
        "RW" => array(
            "Libellé Pays FR" => "RWANDA",
            "Libellé Pays EN" => "RWANDA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SA" => array(
            "Libellé Pays FR" => "ARABIE SAOUDITE",
            "Libellé Pays EN" => "SAUDI ARABIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SE" => array(
            "Libellé Pays FR" => "Suède",
            "Libellé Pays EN" => "SWEDEN",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SG" => array(
            "Libellé Pays FR" => "SINGAPOUR",
            "Libellé Pays EN" => "SINGAPORE",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SI" => array(
            "Libellé Pays FR" => "Slovénie",
            "Libellé Pays EN" => "SLOVENIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SK" => array(
            "Libellé Pays FR" => "SLOVAQUIE",
            "Libellé Pays EN" => "SLOVAKIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "SN" => array(
            "Libellé Pays FR" => "Sénégal",
            "Libellé Pays EN" => "SENEGAL",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "TG" => array(
            "Libellé Pays FR" => "TOGO",
            "Libellé Pays EN" => "TOGO",
            "Code Langue" => array("FR" => array(
                    "Libellé Langue FR" => "français",
                    "Libellé Langue EN" => "French", ))

        ),
        "TH" => array(
            "Libellé Pays FR" => "THAILANDE",
            "Libellé Pays EN" => "THAILAND",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "TR" => array(
            "Libellé Pays FR" => "TURQUIE",
            "Libellé Pays EN" => "TURKEY",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "TW" => array(
            "Libellé Pays FR" => "TAIWAN",
            "Libellé Pays EN" => "TAIWAN",
            "Code Langue" => array("CT" => array(
                    "Libellé Langue FR" => "chinois traditionnel",
                    "Libellé Langue EN" => "traditional Chinese", ))

        ),
        "TZ" => array(
            "Libellé Pays FR" => "TANZANIE",
            "Libellé Pays EN" => "TANZANIA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "UA" => array(
            "Libellé Pays FR" => "UKRAINE",
            "Libellé Pays EN" => "UKRAINE",
            "Code Langue" => array(
                "EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English"
                ), "RU" => array(
                    "Libellé Langue FR" => "russe",
                    "Libellé Langue EN" => "Russian"
                )
            )
        ),
        "US" => array(
            "Libellé Pays FR" => "Etats Unis",
            "Libellé Pays EN" => "United States of America",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        ),
        "UY" => array(
            "Libellé Pays FR" => "URUGUAY",
            "Libellé Pays EN" => "URUGUAY",
            "Code Langue" => array("ES" => array(
                    "Libellé Langue FR" => "espagnol",
                    "Libellé Langue EN" => "Spanish", ))

        ),
        "ZA" => array(
            "Libellé Pays FR" => "AFRIQUE DU SUD",
            "Libellé Pays EN" => "SOUTH AFRICA",
            "Code Langue" => array("EN" => array(
                    "Libellé Langue FR" => "anglais",
                    "Libellé Langue EN" => "English", ))

        )

    );
}
