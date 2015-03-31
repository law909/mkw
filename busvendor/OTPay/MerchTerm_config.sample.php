<?php

class MerchTerm_config {

    /**
     * Visszaadja a beállításokat tartalmazó tömböt, név megadása esetén
     * annak egy elemét.
     *
     * @param string $key Beállítás neve
     * @return array Beállításokat tartalmazó tömb
     */
    public static function getConfig($key = null) {
        $config = array(

			// Alapértelmezett kereskedői terminál azonosító
			'merchTermId'=>XXXXXXXX,

			// MerchTerm_mt_service log bekapcsolása
            'MerchTerm_mt_service_log' => true,

            // MerchTerm_mt_service log útvonal
            'MerchTerm_mt_service_path' => dirname(__FILE__) . '/log/MerchTerm_mt_service.log',

            // SSLv3 protokoll beállítása
			// Korábbi php verzióknál előfordulhat, hogy a https kapcsolat nem tud felépülni, mert a kliens SSLv2-vel próbál kapcsolódni
			// Hibaüzenet: SSL: An existing connection was forcibly closed by the remote host.
            'force_SSLv3' => false,

			// Tanúsítványokkal kapcsolatos beállításokat tartalmazó tömb
			'stream_context'=> array(
                            'ssl'=>array(
								// Kliens tanúsítvány helyének magadása
								'local_cert' => dirname(__FILE__) . '/XXXXX_otp.pem',

								//Ha a ".pem" fájl jelszóval védett
								'passphrase' => 'pass',

								// CA tanúsítvány ellenőrzése
								'cafile' => dirname(__FILE__) . '/mpp_ca.crt',
								'verify_peer' => true
                                )
            ),

			// SoapClient opcióit tartalmazó tömb
            'soap_client_options' => array(
                'trace' => true,

				// Szerver URL beállítása (ha nincs megadva, akkor a WSDL-ben lévő URL-t használja)
				//a kliens fel kell hogy oldja a 195.56.127.158 -at a lenti hostnévre
                'location' => 'https://otpaymerchif.mpprt.hu:10443/otp/MerchantProxyDev2/forward.aspx'
			)
        );
        if (isset($key))
            return array_key_exists($key, $config) ? $config[$key] : null;
        else
            return $config;
    }

}

?>