<?php

ini_set('default_charset', 'utf-8');
require_once("MerchTerm_config.php");

/**
 * SOAP kliens osztály az OTPay szolgáltatás használatához.
 */
class MerchTerm_umg_client extends SoapClient {

    private $options;
    private $force_SSLv3;
	private $stream_context;

    public function __construct() {

        $this->options = MerchTerm_config::getConfig("soap_client_options");
        $this->force_SSLv3 = MerchTerm_config::getConfig("force_SSLv3");
		$this->stream_context = MerchTerm_config::getConfig("stream_context");
        $this -> checkCertificate();
		$this->options["stream_context"] = stream_context_create($this->stream_context);
        parent::__construct((dirname(__FILE__) . '/wsdl/I_MerchTerm_umg.wsdl'), $this->options);
    }

    /**
     * Beállítja azokat a paraméter elemeket, amelyek típusa származtatott osztály.
     *
     * @param SoapClient $request Kérés paramétereit tartalmazó tömb
     * @return object SOAP válasz objektum
     */
    private function SetSoapVars($request) {
        if (isset($request["clientId"])) {
            if (get_class($request["clientId"]) == "ClientMsisdn")
                $clientId = '<clientId xsi:type="ClientMsisdn"><value>' . $request["clientId"]->value . '</value></clientId>';
            if (get_class($request["clientId"]) == "ClientMpid")
                $clientId = '<clientId xsi:type="ClientMpid"><value>' . $request["clientId"]->value . '</value></clientId>';
        }
        if (isset($request["timeout"])) {
            if (get_class($request["timeout"]) == "TimeoutCategory")
                $timeout = '<timeout xsi:type="TimeoutCategory"><value>' . $request["timeout"]->value . '</value></timeout>';
            if (get_class($request["timeout"]) == "TimeoutValue")
                $timeout = '<timeout xsi:type="TimeoutValue"><value>' . $request["timeout"]->value . '</value></timeout>';
        }
        if (isset($request["imNotifFilter"])) {
            if (get_class($request["imNotifFilter"]) == "ImNotifFilterDate") {
                if (isset($request["imNotifFilter"]->endDate)) {
                    $imNotifFilter = '<imNotifFilter xsi:type="ImNotifFilterDate"><startDate>' . $request["imNotifFilter"]->startDate . '</startDate><endDate>' . $request["imNotifFilter"]->endDate . '</endDate></imNotifFilter>';
                } else {
                    $imNotifFilter = '<imNotifFilter xsi:type="ImNotifFilterDate"><startDate>' . $request["imNotifFilter"]->startDate . '</startDate></imNotifFilter>';
                }
            }
            if (get_class($request["imNotifFilter"]) == "ImNotifFilterBankTrxId") {
                $imNotifFilter = '<imNotifFilter xsi:type="ImNotifFilterBankTrxId"><bankTrxId>' . $request["imNotifFilter"]->bankTrxId . '</bankTrxId></imNotifFilter>';
            }
        }

        if (isset($clientId))
            $request["clientId"] = new SoapVar($clientId, XSD_ANYXML);
        if (isset($timeout))
            $request["timeout"] = new SoapVar($timeout, XSD_ANYXML);
        if (isset($imNotifFilter))
            $request["imNotifFilter"] = new SoapVar($imNotifFilter, XSD_ANYXML);

        return $request;
    }

    /**
     * PostImCreditInit metódus
     *
     * @return object SOAP válasz objektum
     */
    public function PostImCreditInit($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostImCreditInit", array("request" => $request));
    }

    /**
     * PostImAuthComplete metódus
     *
     * @return object SOAP válasz objektum
     */
    public function PostImAuthorization($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostImAuthorization", array("request" => $request));
    }

    /**
     * PostImAuthComplete metódus
     *
     * @return object SOAP válasz objektum
     */
    public function PostImAuthComplete($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostImAuthComplete", array("request" => $request));
    }

    /**
     * PostImRefund metódus
     *
     * @return object SOAP válasz objektum
     */
    public function PostImRefund($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostImRefund", array("request" => $request));
    }

    /**
     * PostImStorno metódus
     *
     * @return object SOAP válasz objektum
     */
    public function PostImStorno($request) {

        return $this->__soapCall("PostImStorno", array("request" => $request));
    }

    /**
     * GetImNotif metódus
     *
     * @return object SOAP válasz objektum
     */
    public function GetImNotif($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("GetImNotif", array("request" => $request));
    }

    /**
     * Felüldefiniált __doRequest metódus. Módosítja a SOAP üzenetet küldés előtt.
     * Kényszerített SSLv3 beállítás esetén CURL-t használ a küldéshez.
     *
     * @param SoapClient $request  XML SOAP kérés
     * @param SoapClient $location Kérés URl-je
     * @param SoapClient $action   SOAP action
     * @param SoapClient $version  SOAP verzió
     * @return string XML SOAP válasz
     */
    public function __doRequest($request, $location, $action, $version, $one_way = NULL) {

        $request = str_replace("ns1:", "", $request);
        $request = str_replace("<SOAP-ENV:Body>", "<SOAP-ENV:Body xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">", $request);
        $request = str_replace("<PostImCreditInitReq>", "<PostImCreditInitReq xmlns=\"http://cellumpay.cellum.com\">", $request);
        $request = str_replace("<PostImStornoReq>", "<PostImStornoReq xmlns=\"http://cellumpay.cellum.com\">", $request);
        $request = str_replace("<PostImRefundReq>", "<PostImRefundReq xmlns=\"http://cellumpay.cellum.com\">", $request);
        $request = str_replace("<PostImAuthorizationReq>", "<PostImAuthorizationReq xmlns=\"http://cellumpay.cellum.com\">", $request);
        $request = str_replace("<PostImAuthCompleteReq>", "<PostImAuthCompleteReq xmlns=\"http://cellumpay.cellum.com\">", $request);
        $request = str_replace("<GetImNotifReq>", "<GetImNotifReq xmlns=\"http://cellumpay.cellum.com\">", $request);

		if ($this->force_SSLv3)
            return $this->doRequestSSLv3($request, $location, $action);
        else
            return parent::__doRequest($request, $location, $action, $version);
    }

    /**
     * Kiírja az utolsó SOAP kérést és választ fejléccel együtt, valamint a
     * WSDL alapján generált SOAP adattípusokat és függvényeket.
     *
     * @return string
     */
    public function getSoapInfo() {

        $str = "<h4>Lekérdezés infók:</h4>";

        $str.=nl2br("Request:\n\n" . htmlentities($this->__getLastRequest()) . "\n");
        $str.=nl2br("Request Header:\n\n" . htmlentities($this->__getLastRequestHeaders()));
        $str.=nl2br("Response:\n\n" . htmlentities($this->__getLastResponse()) . "\n\n");
        $str.=nl2br("Response Header:\n\n" . htmlentities($this->__getLastResponseHeaders()) . "\n");

        $str.="<h3>-----------------------------------------------------------------------------<br>";
        $str.="<b>A WSDL alapján generált SOAP adattípusok és függvények</b><br>";
        $str.="-----------------------------------------------------------------------------<br></h3>";

        $str.= "<h4>Adattípusok: </h4>";
        ob_start();
        var_dump($this->__getTypes());
        $res = ob_get_clean();
        $str.= $res;

        $str.= "<h4>Függvények:</h4>";
        $str.= '<pre>';
        $str.= print_r($this->__getFunctions(), true);
        $str.= '</pre>';

        return $str;
    }

    /**
     *
     * Ellenőrzi a kliens tanúsítványt
     *
     * @return:  object
     */
    private function checkCertificate() {
        if (!file_exists($this->stream_context["ssl"]["local_cert"])) {
            throw new Exception("Kliens tanúsítvány nem található a megadott helyen: " . $this->stream_context["ssl"]["local_cert"]);
        }
        if (openssl_pkey_get_private(file_get_contents($this->stream_context["ssl"]["local_cert"]), isset($this->stream_context["ssl"]["passphrase"]) ? $this->stream_context["ssl"]["passphrase"] : null) == FALSE) {
            throw new Exception("A tanúsítvány nem tartalmaz privát kulcsot vagy a kulcshoz használt jelszó nem megfelelő!");
        }
    }

    /**
     *
     * SOAP hívás SSLv3 protokollal
     *
	 * @param SoapClient $request  XML SOAP kérés
     * @param SoapClient $location Kérés URl-je
     * @param SoapClient $action   SOAP action
     * @return string XML SOAP válasz
     */
    private function doRequestSSLv3($request, $location, $action) {

		$handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $location);
        curl_setopt($handle, CURLOPT_HTTPHEADER, Array("Accept:","User-Agent: PHP-SOAP","Content-Type: text/xml; charset=utf-8", 'SOAPAction: "' . $action . '"'));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($handle, CURLOPT_SSLVERSION, 3);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $this->stream_context["ssl"]["verify_peer"]);
		curl_setopt($handle, CURLOPT_CAINFO, $this->stream_context["ssl"]["cafile"]);
		curl_setopt($handle, CURLOPT_SSLCERT, $this->stream_context["ssl"]["local_cert"]);
		curl_setopt($handle, CURLOPT_SSLCERTPASSWD, $this->stream_context["ssl"]["passphrase"]);

		$response = curl_exec($handle);
        if (empty($response)) {
            throw new SoapFault('CURL error: ' . curl_error($handle), curl_errno($handle));
        }
        curl_close($handle);

        return $response;
    }

    public function getRCErrorText($errorcode) {
        $ret = $errorcode . ' - ';
        switch ($errorcode) {
            case 0:
                $ret .= 'Sikeres hívás az UMG felé.';
                break;
            case 2118:
                $ret .= 'A kereskedő nem azonosítható.';
                break;
            case 2120:
                $ret .= 'A kereskedő státusza felfüggesztett.';
                break;
            case 2122:
                $ret .= 'Az MPG nem elérhető.';
                break;
            case 2125:
                $ret .= 'A megadott kereskedői terminál nem létezik.';
                break;
            case 2142:
                $ret .= 'A megadott azonosító nem teszt azonosító.';
                break;
            case 2151:
                $ret .= 'Az adott terminálhoz nincs a megadott azonosítóval rendelkező kereskedői értesítés.';
                break;
            case 2154:
                $ret .= 'Az origBankTrxId-t kötelező megadni.';
                break;
            case 2156:
                $ret .= 'A megadott időintervallum nagyobb, mint 31 nap.';
                break;
            case 2900:
                $ret .= 'Technikai hiba.';
                break;
            default:
                break;
        }
        return $ret;
    }

    public function getErrorText($errorcode) {
        $ret = (string)$errorcode . ' - ';
        switch ($errorcode) {
            case 0:
                $ret .= 'A művelet rendben lefutott.';
                break;
            case 101:
                $ret .= 'A terhelésre megjelölt Ügyfél nem azonosítható.';
                break;
            case 102:
                $ret .= 'A terhelésre megjelölt Ügyfélnek nincs regisztrált MF fizetőeszköze.';
                break;
            case 103:
                $ret .= 'A terhelésre megjelölt Ügyfélnek nincs aktív MF fizetőeszköze.';
                break;
            case 104:
                $ret .= 'Nincs közös Küldő Csoport.';
                break;
            case 105:
                $ret .= 'Az Ügyfél Megerősítés kérés kikézbesítése nem sikerült.';
                break;
            case 110:
                $ret .= 'Az Ügyfél Megerősítés elutasításra került.';
                break;
            case 112:
                $ret .= 'Időközben változás történt: a választott fizetőeszköz korábban nem volt alternatíva.';
                break;
            case 113:
                $ret .= 'Időközben változás történt: a terhelésre megjelölt fizetőeszköz már nem aktív.';
                break;
            case 114:
                $ret .= 'Időközben változás történt: a Kereskedői Virtuális terminál adatai nem elérhetők.';
                break;
            case 115:
                $ret .= 'Időközben változás történt: nincs közös Küldő Csoport.';
                break;
            case 116:
                $ret .= 'Ügyfél Megerősítés válaszra adott határidő idő elmúlt.';
                break;
            case 140:
                $ret .= 'A megadott azonosítókkal nincs regisztrált Integrált Kereskedői virtuális terminál.';
                break;
            case 154:
                $ret .= 'A fizetőeszköz Banki hitelesítése sikertelen.';
                break;
            case 166:
                $ret .= 'Elégtelen egyenleg.';
                break;
            case 172:
                $ret .= 'Időközben változás történt: a terhelésre megjelölt SIM-en nincs regisztrált MPI.';
                break;
            case 174:
                $ret .= 'Időközben változás történt: a terhelésre megjelölt fizetőeszköz nem azonosítható.';
                break;
            case 189:
                $ret .= 'A Kereskedői referencia adatok nem találhatók.';
                break;
            case 190:
                $ret .= 'Bankkártya rendszer elutasítás: Nem elfogadott kártya.';
                break;
            case 191:
                $ret .= 'Bankkártya rendszer elutasítás: Lejárt kártya.';
                break;
            case 192:
                $ret .= 'Bankkártya rendszer elutasítás: A tranzakció nem engedélyezett a kártyatulajdonos számára.';
                break;
            case 193:
                $ret .= 'Bankkártya rendszer elutasítás: Elveszett kártya.';
                break;
            case 194:
                $ret .= 'Bankkártya rendszer elutasítás: Korlátozott kártya.';
                break;
            case 195:
                $ret .= 'Bankkártya rendszer elutasítás: Lopott kártya használata.';
                break;
            case 196:
                $ret .= 'Bankkártya rendszer elutasítás: Érvénytelen kártyaszám.';
                break;
            case 198:
                $ret .= 'A kérést a Bankkártya rendszer egyéb okból elutasította.';
                break;
            case 199:
                $ret .= 'A Sztornó válasz nem azonosítható.';
                break;
            case 200:
                $ret .= 'A Bankkártya rendszer nem elérhető.';
                break;
            case 203:
                $ret .= 'A terhelés sikertelen, a kérés egyéb okból elutasításra került.';
                break;
            case 215:
                $ret .= 'Technikai hiba történt, hívja az MF ügyfélszolgálatot.';
                break;
            case 218:
                $ret .= 'A tranzakció kérésben megadott érvényességi idő nem megfelelő.';
                break;
            case 226:
                $ret .= 'A megadott azonosítóval még nincs tranzakció.';
                break;
            case 228:
                $ret .= 'Sztornó nem szükséges, pénzmozgás nem történt.';
                break;
            case 234:
                $ret .= 'A terhelésre megjelölt Mobil Alkalmazáshoz nincs érvényes MPI. A kapcsolódó bankkártya érvényességi ideje lejárt.';
                break;
            case 240:
                $ret .= 'A kérésben megadott bankTrxId által azonosított folyamat nem a kérésben megadott kereskedőre vonatkozik.';
                break;
            case 241:
                $ret .= 'A pénz foglalás elfogadás határideje lejárt. Felszabadításra vonatkozó üzenet kerül elküldésre.';
                break;
            case 242:
                $ret .= 'A foglalás utáni terhelést a vevő elutasította. Felszabadításra vonatkozó üzenet kerül elküldésre.';
                break;
            case 243:
                $ret .= 'A pénzfoglalás folyamata már befejeződött.';
                break;
            case 244:
                $ret .= 'A pénzügyi tranzakció értesítés üzenet fog kiküldésre kerülni.';
                break;
            case 246:
                $ret .= 'Az elszámoló megváltozott az eredeti tranzakció óta.';
                break;
            case 247:
                $ret .= 'A megadott kereskedői terminálnak nincs joga a Refund művelethez.';
                break;
            case 248:
                $ret .= 'A Refund művelethez rendelt napi limit túllépésre került.';
                break;
            case 249:
                $ret .= 'A Refund művelethez rendelt havi limit túllépésre került.';
                break;
            case 250:
                $ret .= 'A jóváírásra kiválasztott MPI nem azonos az eredetileg kiválasztottal.';
                break;
            case 253:
                $ret .= 'A megadott kereskedői terminálnak nincs joga a ’Authorize’ művelethez.';
                break;
            case 254:
                $ret .= 'A jóváírás kérés elutasításra került.';
                break;
            case 255:
                $ret .= 'Nincs megfelelő fizetőeszköz és a központi fizetőeszköz tulajdonságtár nem elérhető.';
                break;
            case 256:
                $ret .= 'A Mobil Kliens eléréshez szükséges MPG nem elérhető.';
                break;
            case 257:
                $ret .= 'A Mobil Kliens eléréshez szükséges MPG nem ismert.';
                break;
            case 260:
                $ret .= 'A megadott origBankTrxId nem létezik.';
                break;
            case 261:
                $ret .= 'A bankkártyához nincs megfelelő tulajdonság csoport hozzárendelés.';
                break;
            case 269:
                $ret .= 'Bankkártya rendszer elutasítás: Zárolt Kártya.';
                break;
            case 270:
                $ret .= 'Bankkártya rendszer elutasítás: Hibás vagy hiányos adatbevitel.';
                break;
            case 271:
                $ret .= 'Bankkártya rendszer elutasítás: Hibás üzenet.';
                break;
            case 272:
                $ret .= 'Bankkártya rendszer elutasítás: Nincs ilyen ügyfél.';
                break;
            case 279:
                $ret .= 'Időközben változás történt: A kereskedőhöz tartozó elszámoló megváltozott.';
                break;
            case 281:
                $ret .= 'A sztornó üzenet kiküldése sikertelen.';
                break;
            case 282:
                $ret .= 'A sztornó művelet végrehajtási ideje lejárt.';
                break;
            case 283:
                $ret .= 'Bankkártya rendszer elutasítás: Nem valós művelet.';
                break;
            case 284:
                $ret .= 'Bankkártya rendszer elutasítás: Visszavonási limit túllépésre került.';
                break;
            case 285:
                $ret .= 'Bankkártya rendszer elutasítás: A Refund limit túllépésre került.';
                break;
            case 289:
                $ret .= 'A folyamat már korábban sikeresen sztornózásra került.';
                break;
            case 290:
                $ret .= 'A folyamat rendben lefutott, majd hibátlanul stornózásra került.';
                break;
            default:
                break;
        }
        return $ret;
    }

}

class ClientMsisdn {

    public $value;

}

class ClientMpid {

    public $value;

}

class TimeoutCategory {

    public $value;

}

class TimeoutValue {

    public $value;

}

class ImNotifFilterBankTrxId {

    public $bankTrxId;

}

class ImNotifFilterDate {

    public $startDate;
    public $endDate;

}

?>