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