<?php

ini_set('default_charset', 'utf-8');
require_once("MerchTerm_config.php");

/*
 * Implementálja a ReceiveImNotif SOAP metódust
 */

Class ImNotifHandler {

    public $callback;
    public $response;

    /**
     *  ReceiveImNotif SOAP metódus 
     *  
     *  Fogadja a kereskedői értesítést, amelyet a response változóba tesz 
     *  és meghívja a callback függvényt, ha be van állítva. 
     *  
     *  @param object $response Kereskedői értesítés objektum
     *  @return object Kereskedői értesítés válasz objektum
     */
    public function ReceiveImNotif($response) {

        $result = 0;
        $this->response = $response;

        if (isset($this->callback) && is_callable($this->callback))
            $result = call_user_func($this->callback, $response);
        $response = new StdClass();
        $response->result = $result;
        return $response;
    }

}

/**
 * SOAP szerver kereskedői értesítések fogadásához 
 */
class MerchTerm_mt_service extends SoapServer {

    public $response;

    public function __construct() {
        parent::__construct((dirname(__FILE__) . '/wsdl/I_MerchTerm_mt.wsdl'));
    }

    /**
     *  
     *  Lekezeli a beérkező keresekedői értesítéseket. 
     *  
     *  @param $callback callback függvény a kereskedői értesítések feldolgozásához
     *  @return void
     */
    public function handleImNotif($callback = null) {

        $imNotifHandler = new ImNotifHandler();
        $imNotifHandler->callback = $callback;
        $this->setObject($imNotifHandler);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->handle();
            $this->response = $imNotifHandler->response;
            // Beérkező SOAP kérések naplózása
            if (MerchTerm_config::getConfig("MerchTerm_mt_service_log")) {
                $xml = file_get_contents('php://input');
                file_put_contents(MerchTerm_config::getConfig("MerchTerm_mt_service_path"), date("Y-m-d H:i:s") . substr((string) microtime(), 1, 6) . "  " . ($xml . "\n"), FILE_APPEND | LOCK_EX);
            }
        }
    }

    /**
     * Visszaadja a WSDL alapján generált SOAP adattípusokat és függvényeket.
     *  
     * @return string
     */
    public function getSoapInfo() {
        $soapClient = new SoapClient((dirname(__FILE__) . '/wsdl/I_MerchTerm_mt.wsdl'));

        $str = "<h3>----------------------------------------------------------------------------<br/>";
        $str.= "<b>A WSDL alapján generált SOAP adattípusok és függvények</b><br/>";
        $str.= "----------------------------------------------------------------------------<br/></h3>";

        $str.= "<h4>Adattípusok: </h4>";
        ob_start();
        var_dump($soapClient->__getTypes());
        $str.= ob_get_clean();

        $str.= "<h4>Függvények:</h4>";
        $str.= '<pre>';
        $str.= print_r($soapClient->__getFunctions(), true);
        $str.= '</pre>';
        return $str;
    }

}

?>