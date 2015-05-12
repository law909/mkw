<?php

ini_set('default_charset', 'utf-8');
require_once("MasterPass_config.php");

class MasterPassClient extends SoapClient {

    private $options;
    private $force_SSLv3;
	private $stream_context;

    public function __construct() {

        $this->options = MasterPassConfig::getConfig("soap_client_options");
        $this->force_SSLv3 = MasterPassConfig::getConfig("force_SSLv3");
		$this->stream_context = MasterPassConfig::getConfig("stream_context");
        $this->checkCertificate();
		$this->options["stream_context"] = stream_context_create($this->stream_context);
        parent::__construct((dirname(__FILE__) . '/wsdl/I_MerchTerm_umg/I_MerchTerm_umg.wsdl'), $this->options);
    }

    private function SetSoapVars($request) {
        return $request;
    }

    public function PostMpShoppingCart($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostMpShoppingCart", array("request" => $request));
    }

    public function PostMpCheckout($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostMpCheckout", array("request" => $request));
    }

    public function PostMpPayment($request) {

        $request = $this->SetSoapVars($request);
        return $this->__soapCall("PostMpPayment", array("request" => $request));
    }

    public function __doRequest($request, $location, $action, $version, $one_way = NULL) {

        //$request = str_replace("ns1:", "", $request);
        //$request = str_replace("<SOAP-ENV:Body>", "<SOAP-ENV:Body xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">", $request);

		if ($this->force_SSLv3) {
            return $this->doRequestSSLv3($request, $location, $action);
        }
        else {
            return parent::__doRequest($request, $location, $action, $version);
        }
    }

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

    private function checkCertificate() {
        if (!file_exists($this->stream_context["ssl"]["local_cert"])) {
            throw new Exception("Kliens tanúsítvány nem található a megadott helyen: " . $this->stream_context["ssl"]["local_cert"]);
        }
        if (openssl_pkey_get_private(file_get_contents($this->stream_context["ssl"]["local_cert"]), isset($this->stream_context["ssl"]["passphrase"]) ? $this->stream_context["ssl"]["passphrase"] : null) == FALSE) {
            throw new Exception("A tanúsítvány nem tartalmaz privát kulcsot vagy a kulcshoz használt jelszó nem megfelelő!");
        }
    }

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
