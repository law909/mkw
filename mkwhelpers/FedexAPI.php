<?php

namespace mkwhelpers;

class FedexAPI
{

    private const TOKENMARGIN = 300;

    private $apikey;
    private $secretkey;
    private $accountnumber;
    private $apiurl = 'https://apis-sandbox.fedex.com';
    private $docapiurl;
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
        if (!empty($param['docapiurl'])) {
            $this->docapiurl = rtrim($param['docapiurl'], '/');
        }
        $this->pdfdirectory = $param['pdfdirectory'];
    }

    /**
     * A dokumentum feltöltés (ETD) a többi Fedex API-tól eltérő hoston fut, a
     * tokent viszont ugyanonnan kapjuk. Ha nincs külön beállítva, az apiurl-ből
     * (sandbox / éles) következtetünk rá.
     */
    protected function getDocApiUrl()
    {
        if ($this->docapiurl) {
            return $this->docapiurl;
        }
        if (str_contains($this->apiurl, 'sandbox')) {
            return 'https://documentapitest.prod.fedex.com/sandbox';
        }
        return 'https://documentapi.prod.fedex.com';
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
            \mkw\store::writelog('Token a példányból', 'fedex_api.txt');
            return $this->token;
        }
        $cached = $this->readTokenCache();
        if ($cached) {
            $this->token = $cached['token'];
            $this->tokenexpires = $cached['expires'];
            if ($this->token && $this->tokenexpires > time()) {
                \mkw\store::writelog('Token cacheból', 'fedex_api.txt');
                return $this->token;
            }
        }
        \mkw\store::writelog('Token fetch indul', 'fedex_api.txt');
        return $this->fetchToken();
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
        \mkw\store::writelog('requestToken() start', 'fedex_api.txt');
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
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        );
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        \mkw\store::writelog('requestToken() end', 'fedex_api.txt');
        \mkw\store::writelog('errno: ' . $errno . ' error: ' . $error . ' status: ' . $status, 'fedex_api.txt');

        if ($response) {
            return json_decode($response);
        }
        return false;
    }

    protected function callAPI($endpoint, $data, $method = 'POST', $retry = true)
    {
        \mkw\store::writelog('callAPI() starts: ' . $this->apiurl . $endpoint, 'fedex_api.txt');

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
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'X-locale: hu_HU',
                'Content-Length: ' . strlen($req)
            ]
        );
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        \mkw\store::writelog($req, 'fedex_api.txt');
        \mkw\store::writelog($response, 'fedex_api.txt');
        \mkw\store::writelog('errno: ' . $errno . ' error: ' . $error . ' status: ' . $status, 'fedex_api.txt');
        \mkw\store::writelog($httpcode, 'fedex_api.txt');

        \mkw\store::writelog('callAPI() end: ' . $this->apiurl . $endpoint, 'fedex_api.txt');

        if ($retry && ($httpcode == 401 || $httpcode == 403)) {
            $this->clearToken();
            return $this->callAPI($endpoint, $data, $method, false);
        }

        return $response;
    }

    /**
     * A dokumentum feltöltés multipart/form-data-t vár: a "document" rész a
     * dokumentum leíró json, az "attachment" rész maga a fájl.
     */
    protected function callUploadAPI($endpoint, $documentdata, $filepath, $filename, $mimetype, $retry = true)
    {
        $token = $this->getToken();
        if (!$token) {
            return false;
        }
        $documentjson = json_encode($documentdata, JSON_UNESCAPED_UNICODE);
        $req = [
            'document' => $documentjson,
            'attachment' => new \CURLFile($filepath, $mimetype, $filename)
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $this->getDocApiUrl() . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        // a Content-Type-ot a curl állítja be, mert a multipart boundary is kell bele
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'X-locale: hu_HU'
            ]
        );
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        \mkw\store::writelog($this->getDocApiUrl() . $endpoint, 'fedex_api.txt');
        \mkw\store::writelog($documentjson . ' [' . $filename . ']', 'fedex_api.txt');
        \mkw\store::writelog($response, 'fedex_api.txt');
        \mkw\store::writelog($httpcode, 'fedex_api.txt');
        curl_close($curl);

        if ($retry && ($httpcode == 401 || $httpcode == 403)) {
            $this->clearToken();
            return $this->callUploadAPI($endpoint, $documentdata, $filepath, $filename, $mimetype, false);
        }

        return $response;
    }

    /**
     * Kereskedelmi dokumentumot (pl. számlakép) tölt fel a Fedexhez.
     *
     * A $meta lehetséges kulcsai:
     *   shipdocumenttype (alap: COMMERCIAL_INVOICE), origincountrycode (alap: HU),
     *   destinationcountrycode, carriercode (alap: FDXE), contenttype (alap: application/pdf),
     *   trackingnumber + shipdate (Y-m-d): ha mindkettő megvan, utólagos (post-shipment)
     *   feltöltés történik, egyébként feladás előtti (pre-shipment).
     *
     * Pre-shipment esetén a visszakapott docid-t a küldemény feladásakor kell megadni
     * (shipmentSpecialServices / etdDetail / attachedDocuments / documentId).
     *
     * @return array|false ['docid' => ..., 'folderid' => ..., 'documenttype' => ...]
     */
    public function uploadTradeDocument($filepath, $filename, $meta = [])
    {
        $this->lasterrors = [];
        if (!$filepath || !is_readable($filepath)) {
            $this->lasterrors = [
                (object)[
                    'code' => 'DOCUMENT.NOT.FOUND',
                    'message' => 'A feltöltendő dokumentum nem olvasható: ' . $filepath
                ]
            ];
            return false;
        }
        $utolagos = !empty($meta['trackingnumber']) && !empty($meta['shipdate']);
        $documentdata = [
            'workflowName' => ($utolagos ? 'ETDPostShipment' : 'ETDPreshipment'),
            'carrierCode' => $meta['carriercode'] ?? 'FDXE',
            'name' => $filename,
            'contentType' => $meta['contenttype'] ?? 'application/pdf',
            'meta' => [
                'shipDocumentType' => $meta['shipdocumenttype'] ?? 'COMMERCIAL_INVOICE',
                'originCountryCode' => $meta['origincountrycode'] ?? 'HU',
                'destinationCountryCode' => $meta['destinationcountrycode'] ?? 'HU'
            ]
        ];
        if ($utolagos) {
            $documentdata['meta']['trackingNumber'] = $meta['trackingnumber'];
            $documentdata['meta']['shipmentDate'] = $meta['shipdate'];
        }

        $response = $this->callUploadAPI(
            '/documents/v1/etds/upload',
            $documentdata,
            $filepath,
            $filename,
            $meta['contenttype'] ?? 'application/pdf'
        );
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $this->extractErrors($response);
            if (!$this->lasterrors) {
                $docid = $response->output->meta->docId ?? null;
                if ($docid) {
                    return [
                        'docid' => $docid,
                        'folderid' => $response->output->meta->folderId ?? null,
                        'documenttype' => $response->output->meta->documentType ?? null
                    ];
                }
                $this->lasterrors = [
                    (object)[
                        'code' => 'DOCUMENT.UPLOAD.FAILED',
                        'message' => 'A Fedex nem adott vissza dokumentum azonosítót'
                    ]
                ];
            }
        }
        return false;
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
     * @return array|false mastertrackingnumber, shipdate (Y-m-d) és a
     *                     csomagonkénti [trackingnumber, pdfname] lista
     */
    public function createShipment($shipmentdata, $pdfname)
    {
        \mkw\store::writelog('createShipment() starts ', 'fedex_api.txt');
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
                    'shipdate' => $shipment->shipDatestamp ?? null,
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
