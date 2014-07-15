<?php

class TrustedShop {

  const ServiceHost = 'www.arukereso.hu';

  const ServiceUrl = '/affiliation/TrustedShop.php';

  const ErrorEmail = 'Nem adta meg a vasarlo email cimet';

  const ErrorService = 'Nem sikerult menteni a vasarlo adatait.';

  private $WebApiKey;

  private $Email;

  private $Products = array();

  private $Protocol;

  public function __construct($WebApiKey) {
    $this->WebApiKey = $WebApiKey;
    $this->Protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
  }

  public function SetEmail($Email) {
    $this->Email = $Email;
  }

  public function AddProduct($ProductName) {
    $this->Products[] = $ProductName;
  }

  public function Send() {
    if (empty($this->Email)) {
      throw new Exception(self::ErrorEmail);
    }
    
    $String = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $C = '';
    for ($i = 0; $i < 20; $i++) {
      $C .= $String{mt_rand(0, strlen($String) - 1)};
    }
    
    $Timestamp = time();
    $HashedKey = md5($this->WebApiKey . $Timestamp);

    $Query = 'HashedKey=' . $HashedKey . '&Email=' . urlencode($this->Email);
    foreach ($this->Products as $ProductName) {
      $Query .= '&Products[]=' . urlencode($ProductName);
    }
    $Query .= '&Timestamp=' . $Timestamp;
    
    echo '<script type="text/javascript" src="' . $this->Protocol . '://' . self::ServiceHost . '/fc.js"></script>';
    echo
      '<script type="text/javascript">',
      'function fc_request_done(C) { var I = new Image(); I.src=\'' . $this->Protocol . '://' . self::ServiceHost . self::ServiceUrl . "?" . $Query . '&C=\'+C; }',
      'set_fc("' . self::ServiceHost . '", "__aku","' . $C . '");',
      '</script>';
    
    echo
      '<noscript>',
      '<img src="' . $this->Protocol . '://' . self::ServiceHost . self::ServiceUrl . "?" . $Query . '&C=' . $C . '">',
      '</noscript>';
  }
}

?>