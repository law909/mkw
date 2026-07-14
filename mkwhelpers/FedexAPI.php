<?php

namespace mkwhelpers;

class FedexAPI
{

    private const TOKENMARGIN = 300;

    private $apikey;
    private $secretkey;
    private $accountnumber;
    private $apiurl = 'https://apis-sandbox.fedex.com';
    private $pdfdirectory;
    private $token;
    private $tokenexpires = 0;
    private $lasterrors = [];

    public function __construct($param)
    {
        $this->apikey = $param['apikey'];
        $this->secretkey = $param['secretkey'];
        $this->accountnumber = $param['accountnumber'];
        if ($param['apiurl']) {
            $this->apiurl = rtrim($param['apiurl'], '/');
        }
        $this->pdfdirectory = $param['pdfdirectory'];
    }

    /**
     * @return array
     */
    public function getLasterrors()
    {
        return $this->lasterrors;
    }

    /**
     * @return mixed
     */
    public function getPdfdirectory()
    {
        return $this->pdfdirectory;
    }

    /**
     * @return mixed
     */
    public function getAccountnumber()
    {
        return $this->accountnumber;
    }

    protected function getToken()
    {
        if ($this->token && $this->tokenexpires > time()) {
            return $this->token;
        }
        $cached = $this->readTokenCache();
        if ($cached) {
            $this->token = $cached['token'];
            $this->tokenexpires = $cached['expires'];
            if ($this->token && $this->tokenexpires > time()) {
                return $this->token;
            }
        }
        return $this->fetchToken();
    }

    public function clearToken()
    {
        $this->token = null;
        $this->tokenexpires = 0;
        \mkw\store::getEm()->getConnection()->executeStatement(
            'DELETE FROM parameterek WHERE id = ?',
            [\mkw\consts::FedexToken]
        );
    }

    private function credFingerprint()
    {
        return sha1($this->apiurl . '|' . $this->apikey);
    }

    private function readTokenCache()
    {
        $ertek = \mkw\store::getEm()->getConnection()->fetchOne(
            'SELECT ertek FROM parameterek WHERE id = ?',
            [\mkw\consts::FedexToken]
        );
        if (!$ertek) {
            return false;
        }
        $data = json_decode($ertek, true);
        if (!is_array($data)
            || !isset($data['token'], $data['expires'], $data['cred'])
            || $data['cred'] !== $this->credFingerprint()
            || $data['expires'] <= time()
        ) {
            return false;
        }
        return $data;
    }

    private function writeTokenCache()
    {
        $ertek = json_encode([
            'token' => $this->token,
            'expires' => $this->tokenexpires,
            'cred' => $this->credFingerprint()
        ]);
        \mkw\store::getEm()->getConnection()->executeStatement(
            'INSERT INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)'
            . ' ON DUPLICATE KEY UPDATE ertek = VALUES(ertek), specialchars = 0',
            [\mkw\consts::FedexToken, $ertek]
        );
    }

    protected function requestToken()
    {
        $req = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->apikey,
            'client_secret' => $this->secretkey
        ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $this->apiurl . '/oauth/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        );
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            return json_decode($response);
        }
        return false;
    }

    protected function fetchToken()
    {
        $response = $this->requestToken();
        if ($response) {
            if (isset($response->access_token)) {
                $this->token = $response->access_token;
                $this->tokenexpires = time()
                    + (isset($response->expires_in) ? (int)$response->expires_in : 3600)
                    - self::TOKENMARGIN;
                $this->writeTokenCache();
                return $this->token;
            }
            $this->lasterrors = $this->extractErrors($response);
        }
        return false;
    }

    protected function callAPI($endpoint, $data, $method = 'POST', $retry = true)
    {
        $token = $this->getToken();
        if (!$token) {
            return false;
        }
        $req = json_encode($data, JSON_UNESCAPED_UNICODE);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $this->apiurl . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'X-locale: hu_HU',
                'Content-Length: ' . strlen($req)
            ]
        );
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // a cache-elt token idő előtt érvénytelenné vált: eldobjuk és újrapróbáljuk
        if ($retry && ($httpcode == 401 || $httpcode == 403)) {
            $this->clearToken();
            return $this->callAPI($endpoint, $data, $method, false);
        }

        return $response;
    }

    protected function extractErrors($response)
    {
        $errors = [];
        if (is_object($response)) {
            if (isset($response->errors) && $response->errors) {
                $errors = $response->errors;
            } elseif (isset($response->error)) {
                $errors = [$response->error];
            }
        }
        return $errors;
    }

    /**
     * Egy küldeményt ad fel, a címkéket pdf-be menti.
     * A pdfname a több csomagos küldemény első csomagjának címkéje, a többi
     * csomag címkéje _2, _3 ... postfix-szel kerül mentésre.
     *
     * @return array|false a csomagonkénti [trackingnumber, pdfname] lista
     */
    public function createShipment($shipmentdata, $pdfname)
    {
        $this->lasterrors = [];
        $response = $this->callAPI('/ship/v1/shipments', $shipmentdata);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $this->extractErrors($response);
            if (!$this->lasterrors
                && isset($response->output->transactionShipments)
                && count($response->output->transactionShipments) > 0
            ) {
                $shipment = $response->output->transactionShipments[0];
                $result = [
                    'mastertrackingnumber' => $shipment->masterTrackingNumber ?? null,
                    'packages' => []
                ];
                $db = 0;
                foreach (($shipment->pieceResponses ?? []) as $piece) {
                    foreach (($piece->packageDocuments ?? []) as $doc) {
                        $db++;
                        $fname = $this->savePdf(
                            $doc,
                            ($db === 1 ? $pdfname : $this->postfixPdfname($pdfname, $db))
                        );
                        if ($fname) {
                            $result['packages'][] = [
                                'trackingnumber' => $piece->trackingNumber ?? null,
                                'pdfname' => $fname
                            ];
                        }
                    }
                }
                if ($result['packages']) {
                    return $result;
                }
            }
        }
        return false;
    }

    public function cancelShipment($trackingnumber, $sendercountrycode = 'HU')
    {
        $this->lasterrors = [];
        $requestdata = [
            'accountNumber' => [
                'value' => $this->accountnumber
            ],
            'senderCountryCode' => $sendercountrycode,
            'deletionControl' => 'DELETE_ALL_PACKAGES',
            'trackingNumber' => $trackingnumber
        ];

        $response = $this->callAPI('/ship/v1/shipments/cancel', $requestdata, 'PUT');
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $this->extractErrors($response);
            if (!$this->lasterrors && isset($response->output->cancelledShipment)) {
                return $response->output->cancelledShipment;
            }
        }
        return false;
    }

    public function getRates($ratedata)
    {
        $this->lasterrors = [];
        $response = $this->callAPI('/rate/v1/rates/quotes', $ratedata);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $this->extractErrors($response);
            if (!$this->lasterrors && isset($response->output->rateReplyDetails)) {
                $result = [];
                foreach ($response->output->rateReplyDetails as $rate) {
                    $dij = $this->pickRatedShipmentDetail($rate);
                    if ($dij) {
                        $result[] = [
                            'servicetype' => $rate->serviceType ?? null,
                            'servicename' => $rate->serviceName ?? ($rate->serviceType ?? ''),
                            'netto' => (float)($dij->totalNetCharge ?? 0),
                            'brutto' => (float)($dij->totalNetChargeWithDutiesAndTaxes ?? $dij->totalNetCharge ?? 0),
                            'valutanem' => $dij->currency ?? null,
                            'ratetype' => $dij->rateType ?? null,
                            'szallitasiido' => $rate->commit->dateDetail->dayFormat ?? null
                        ];
                    }
                }
                if ($result) {
                    return $result;
                }
            }
        }
        return false;
    }

    private function pickRatedShipmentDetail($rate)
    {
        $elso = false;
        foreach (($rate->ratedShipmentDetails ?? []) as $dij) {
            if (!$elso) {
                $elso = $dij;
            }
            if (isset($dij->rateType) && $dij->rateType === 'ACCOUNT') {
                return $dij;
            }
        }
        return $elso;
    }

    private function postfixPdfname($pdfname, $db)
    {
        $ext = pathinfo($pdfname, PATHINFO_EXTENSION);
        if ($ext) {
            return substr($pdfname, 0, -(strlen($ext) + 1)) . '_' . $db . '.' . $ext;
        }
        return $pdfname . '_' . $db;
    }

    private function savePdf($doc, $pdfname)
    {
        $pdf = false;
        if (isset($doc->encodedLabel) && $doc->encodedLabel) {
            $pdf = base64_decode($doc->encodedLabel);
        } elseif (isset($doc->url) && $doc->url) {
            $pdf = file_get_contents($doc->url);
        }
        if ($pdf === false || $pdf === '') {
            return false;
        }
        $fname = implode('/', [
            rtrim($this->pdfdirectory, '/'),
            $pdfname
        ]);
        file_put_contents($fname, $pdf);
        return $fname;
    }

}
