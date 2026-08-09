<?php

namespace mkwhelpers;

/**
 * UNAS API kliens (https://unas.hu/tudastar). Domain-mentes: XML, curl, token cache, rate limiter.
 * A hibákat nem kivétellel adja vissza, hanem a {@see getLasterrors()}-szal – ez a ház stílusa.
 *
 * Amit tudni kell: a token 2 óráig él és nem szabad hívásonként loginolni; 20 egymást követő hibás
 * hívás 1 órás IP-tiltást hoz, a `login` végponton pedig már 5 sikertelen is; karbantartás éjfél
 * ±10 perc. A válaszok a `storage/logs` mappába is kimennek ({@see dump()}).
 */
class UnasAPI
{

    private const DEFAULTAPIURL = 'https://api.unas.eu/shop';

    /** ennyivel a lejárat előtt már újat kérünk */
    private const TOKENMARGIN = 300;

    private const LOGFILE = 'unas_api.txt';
    private const ERRORFILE = 'unas_api_error.txt';

    /** a getProductDB generálása lassú lehet */
    private const TIMEOUT = 120;

    /** ennyi dump fájl marad meg típusonként */
    private const DUMPKEEP = 20;

    /** a termékadatbázisból kevesebb, a mérete miatt */
    private const DBDUMPKEEP = 5;

    private const LOGRESPONSEMAX = 2000;

    /**
     * Órás korlátok a szűkebb (PREMIUM) csomag szerint; a limiter a 90%-uknál fékez.
     * A `login` sor a SIKERTELEN hívásokra vonatkozik, ezért azt csak hibánál léptetjük.
     */
    private const HOURLYLIMIT = [
        'login' => 5,
        'getProductDB' => 10,
        'getProduct' => 30,
        'getOrder' => 30,
        'setOrder' => 30,
        'getStock' => 30,
        'setStock' => 30,
        'getCategory' => 30,
        'default' => 2000,
    ];

    /**
     * A `set*` végpontok gyökéreleme nem `Params`, hanem a saját listájuk. Csak az itt felsoroltak
     * ellenőrzöttek – új `set*` burkoló hozzáadásakor ezt is ki kell egészíteni.
     */
    private const ROOTELEMENT = [
        'setOrder' => 'Orders',
    ];

    private $apiurl;
    private $apikey;
    private $token;
    private $tokenexpires = 0;
    private $lasterrors = [];
    private $logininfo = [];
    private $dumpseq = 0;
    private $lastdumpfile;

    public function __construct($param)
    {
        $this->apiurl = rtrim(trim((string)($param['apiurl'] ?? '')) ?: self::DEFAULTAPIURL, '/');
        $this->apikey = trim((string)($param['apikey'] ?? ''));
    }

    /** @return array [['code' => ..., 'message' => ...], ...] */
    public function getLasterrors()
    {
        return $this->lasterrors;
    }

    public function getLasterrorsAsString()
    {
        $t = [];
        foreach ($this->lasterrors as $e) {
            $t[] = trim($e['code'] . ' ' . $e['message']);
        }
        return implode('; ', $t);
    }

    public function getApiurl()
    {
        return $this->apiurl;
    }

    /**
     * A legutóbbi hívás nyers válaszának dump fájlneve a `storage/logs` alatt, vagy null.
     * A hívónak ritkán kell – az „csak letöltés" menetnek viszont ezt kell megmutatnia.
     */
    public function getLastDumpFile()
    {
        return $this->lastdumpfile;
    }

    public function isConfigured()
    {
        return $this->apiurl !== '' && $this->apikey !== '';
    }

    /** @return array ShopId, Subscription, Permissions, Expire */
    public function getLoginInfo()
    {
        return $this->logininfo;
    }

    // ------------------------------------------------------------------
    // Végpontok
    // ------------------------------------------------------------------

    /** @return array|false ShopId, Subscription, Permissions, Expire */
    public function login()
    {
        $this->lasterrors = [];
        if (!$this->isConfigured()) {
            $this->addError('NOTCONFIGURED', 'Nincs beállítva az UNAS API URL vagy API kulcs.');
            return false;
        }
        if ($this->isMaintenanceWindow()) {
            $this->addError('MAINTENANCE', 'Az UNAS karbantartási ablakában vagyunk (23:55–00:10).');
            return false;
        }
        // itt a SIKERTELEN hívások vannak korlátozva
        if (!$this->rateOk('login')) {
            return false;
        }

        \mkw\store::writelog('login() start: ' . $this->apiurl, self::LOGFILE);
        $response = $this->httpPost('/login', $this->buildXml(['ApiKey' => $this->apikey]), null);
        // a válaszban ott a Bearer token, ezért ezt az egy végpontot nem dumpoljuk fájlba
        $xml = $this->parseResponse('login', $response['body'], $response['httpcode'], false);
        if (!$xml || !isset($xml->Token) || (string)$xml->Token === '') {
            $this->rateInc('login');
            if (!$this->lasterrors) {
                $this->addError('LOGINFAILED', 'Az UNAS nem adott vissza tokent.');
            }
            $this->writeErrorLog('login');
            return false;
        }

        $this->token = (string)$xml->Token;
        $expiretime = isset($xml->ExpireTime) ? (int)$xml->ExpireTime : 0;
        $this->tokenexpires = ($expiretime > 0 ? $expiretime : time() + 7200) - self::TOKENMARGIN;
        $this->writeTokenCache();

        $this->logininfo = [
            'shopid' => isset($xml->ShopId) ? (string)$xml->ShopId : '',
            'subscription' => isset($xml->Subscription) ? (string)$xml->Subscription : '',
            'expire' => isset($xml->Expire) ? (string)$xml->Expire : '',
            'expiretime' => $expiretime,
            'permissions' => $this->permissionList($xml),
        ];
        \mkw\store::writelog(
            'login() ok: shop=' . $this->logininfo['shopid']
            . ' subscription=' . $this->logininfo['subscription']
            . ' permissions=' . implode(',', $this->logininfo['permissions']),
            self::LOGFILE
        );
        return $this->logininfo;
    }

    /**
     * A válasz egyetlen `Url` mező; a fájlt 1 órán belül le kell tölteni ({@see downloadToFile()}).
     *
     * @return \SimpleXMLElement|false
     */
    public function getProductDB(array $params)
    {
        return $this->callAPI('getProductDB', $params);
    }

    /**
     * A `Sku`-ban vesszővel több cikkszám is mehet, de a több termékes hívás limitje szűk (30/óra).
     *
     * @return \SimpleXMLElement|false
     */
    public function getProduct(array $params)
    {
        return $this->callAPI('getProduct', $params);
    }

    // a 2–3. fázis végpontjai, előre kikészítve – lásd docs/unas-integracio.md

    /** @return \SimpleXMLElement|false */
    public function getOrder(array $params)
    {
        return $this->callAPI('getOrder', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function setOrder(array $params)
    {
        return $this->callAPI('setOrder', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getOrderStatus(array $params = [])
    {
        return $this->callAPI('getOrderStatus', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getMethod(array $params = [])
    {
        return $this->callAPI('getMethod', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getStock(array $params)
    {
        return $this->callAPI('getStock', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function setStock(array $params)
    {
        return $this->callAPI('setStock', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getWarehouse(array $params = [])
    {
        return $this->callAPI('getWarehouse', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getCategory(array $params = [])
    {
        return $this->callAPI('getCategory', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function setProduct(array $params)
    {
        return $this->callAPI('setProduct', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function getAutomatism(array $params = [])
    {
        return $this->callAPI('getAutomatism', $params);
    }

    /** @return \SimpleXMLElement|false */
    public function setAutomatism(array $params)
    {
        return $this->callAPI('setAutomatism', $params);
    }

    // ------------------------------------------------------------------
    // Hívás
    // ------------------------------------------------------------------

    /**
     * 401/403 esetén egyszer eldobja a tokent és újrapróbálja.
     *
     * @return \SimpleXMLElement|false
     */
    protected function callAPI($endpoint, array $params, $retry = true)
    {
        $this->lasterrors = [];
        $this->lastdumpfile = null;
        if (!$this->isConfigured()) {
            $this->addError('NOTCONFIGURED', 'Nincs beállítva az UNAS API URL vagy API kulcs.');
            return false;
        }
        if ($this->isMaintenanceWindow()) {
            $this->addError('MAINTENANCE', 'Az UNAS karbantartási ablakában vagyunk (23:55–00:10).');
            \mkw\store::writelog('callAPI(' . $endpoint . ') kihagyva: karbantartási ablak', self::LOGFILE);
            return false;
        }
        if (!$this->rateOk($endpoint)) {
            return false;
        }

        $token = $this->getToken();
        if (!$token) {
            return false;
        }

        $req = $this->buildXml($params, self::ROOTELEMENT[$endpoint] ?? 'Params');
        $this->rateInc($endpoint);
        $response = $this->httpPost('/' . $endpoint, $req, $token);

        \mkw\store::writelog('callAPI(' . $endpoint . ') kérés: ' . $this->maskXml($req), self::LOGFILE);

        if ($retry && ($response['httpcode'] == 401 || $response['httpcode'] == 403)) {
            \mkw\store::writelog('callAPI(' . $endpoint . ') ' . $response['httpcode'] . ' – token eldobása', self::LOGFILE);
            $this->clearToken();
            return $this->callAPI($endpoint, $params, false);
        }

        $xml = $this->parseResponse($endpoint, $response['body'], $response['httpcode']);
        if (!$xml) {
            $this->writeErrorLog($endpoint);
        }
        return $xml;
    }

    /**
     * A GLSAPI `CURLOPT_SSL_VERIFYPEER = 0` beállítását NEM másoljuk: az UNAS tanúsítványa jó.
     *
     * @return array{body: string|false, httpcode: int, errno: int, error: string}
     */
    protected function httpPost($endpoint, $body, $token)
    {
        $headers = ['Content-Type: application/xml; charset=UTF-8'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $this->apiurl . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $httpcode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($errno) {
            $this->addError('CURL' . $errno, $error);
            \mkw\store::writelog('curl hiba (' . $endpoint . '): ' . $errno . ' ' . $error, self::LOGFILE);
        }

        return ['body' => $response, 'httpcode' => $httpcode, 'errno' => $errno, 'error' => $error];
    }

    /**
     * Fájlba töltünk, mert a termékadatbázis több tíz MB is lehet.
     *
     * @param string|null $keepFile ezt a fájlnevet a takarítás nem dobhatja el (folyamatban lévő menet)
     *
     * @return array{fajl: string, meret: int}|false  a fájl NEVE (nem teljes útvonala) és mérete
     */
    public function downloadToFile($url, $prefix = 'productdb', $ext = 'csv', $keepFile = null)
    {
        $this->lasterrors = [];
        $url = trim((string)$url);
        if ($url === '') {
            $this->addError('NOURL', 'Nincs letöltendő URL.');
            return false;
        }

        $file = $this->dumpName($prefix, $ext);
        $abs = \mkw\store::logsPath($file);

        $fh = @fopen($abs, 'w');
        if (!$fh) {
            $this->addError('FILEOPEN', 'A letöltés célfájlja nem írható: ' . $abs);
            $this->writeErrorLog('download');
            return false;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FILE, $fh);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $httpcode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        fclose($fh);

        $size = @filesize($abs);
        \mkw\store::writelog(
            'downloadToFile(' . $url . ') -> ' . $file . ' (' . (int)$size . ' bájt, http ' . $httpcode . ')',
            self::LOGFILE
        );

        if ($errno || $httpcode >= 400 || !$size) {
            @unlink($abs);
            $this->addError(
                'DOWNLOAD',
                'A fájl nem tölthető le (http ' . $httpcode . ($error ? ', ' . $error : '') . '): ' . $url
            );
            $this->writeErrorLog('download');
            return false;
        }

        $this->pruneDumps($prefix, $ext, self::DBDUMPKEEP, $keepFile);
        return ['fajl' => $file, 'meret' => (int)$size];
    }

    // ------------------------------------------------------------------
    // XML
    // ------------------------------------------------------------------

    /** Lista értékből ismétlődő elem lesz, asszociatív tömbből beágyazott. */
    protected function buildXml(array $params, $root = 'Params')
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $el = $doc->createElement($root);
        $this->appendNodes($doc, $el, $params);
        $doc->appendChild($el);
        return $doc->saveXML();
    }

    private function appendNodes(\DOMDocument $doc, \DOMElement $parent, array $params)
    {
        foreach ($params as $key => $value) {
            if (is_array($value) && array_is_list($value)) {
                foreach ($value as $item) {
                    $this->appendNode($doc, $parent, $key, $item);
                }
                continue;
            }
            $this->appendNode($doc, $parent, $key, $value);
        }
    }

    private function appendNode(\DOMDocument $doc, \DOMElement $parent, $key, $value)
    {
        if ($value === null || $value === '' || $value === false) {
            return;
        }
        $el = $doc->createElement($key);
        if (is_array($value)) {
            $this->appendNodes($doc, $el, $value);
        } else {
            if ($value === true) {
                $value = '1';
            }
            // createTextNode escape-eli a &-et és a <-t
            $el->appendChild($doc->createTextNode((string)$value));
        }
        $parent->appendChild($el);
    }

    /**
     * LIBXML_NOENT szándékosan NINCS: azzal külső XML-lel XXE-t kapnánk.
     *
     * @return \SimpleXMLElement|false
     */
    protected function parseResponse($endpoint, $body, $httpcode, $dumpable = true)
    {
        if ($dumpable) {
            $this->lastdumpfile = $this->dump($endpoint, 'xml', (string)$body);
        }

        \mkw\store::writelog(
            'callAPI(' . $endpoint . ') válasz (http ' . $httpcode . '): '
            . $this->maskXml(mb_substr((string)$body, 0, self::LOGRESPONSEMAX)),
            self::LOGFILE
        );

        if ($body === false || trim((string)$body) === '') {
            $this->addError('EMPTYRESPONSE', 'Az UNAS üres választ adott (http ' . $httpcode . ').');
            return false;
        }

        $prev = libxml_use_internal_errors(true);
        libxml_clear_errors();
        $xml = simplexml_load_string((string)$body, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET);
        $xmlErrors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        if ($xml === false) {
            $message = $xmlErrors ? trim($xmlErrors[0]->message) : 'ismeretlen XML hiba';
            $this->addError('BADXML', 'Az UNAS válasza nem értelmezhető XML (' . $message . ').');
            return false;
        }

        $errors = $this->extractErrors($xml);
        if ($errors) {
            $this->lasterrors = array_merge($this->lasterrors, $errors);
            return false;
        }
        if ($httpcode >= 400) {
            $this->addError('HTTP' . $httpcode, 'Az UNAS hibás HTTP státusszal válaszolt.');
            return false;
        }

        return $xml;
    }

    /**
     * Csak a gyökér szintjét nézzük: egy termékbe ágyazott hibaelem miatt nem dobhatjuk el az
     * egész választ, abban a többi termék adata jó.
     */
    protected function extractErrors(\SimpleXMLElement $xml)
    {
        $errors = [];
        if ($xml->getName() === 'Error') {
            $errors[] = $this->errorFromNode($xml);
            return $errors;
        }
        if (isset($xml->Error)) {
            foreach ($xml->Error as $node) {
                $errors[] = $this->errorFromNode($node);
            }
        }
        return $errors;
    }

    private function errorFromNode(\SimpleXMLElement $node)
    {
        $code = isset($node->Code) ? (string)$node->Code : '';
        $message = isset($node->Message) ? (string)$node->Message : '';
        if ($message === '' && !count($node)) {
            $message = trim((string)$node);
        }
        return ['code' => $code ?: 'ERROR', 'message' => $message];
    }

    private function permissionList(\SimpleXMLElement $xml)
    {
        $list = [];
        if (isset($xml->Permissions->Permission)) {
            foreach ($xml->Permissions->Permission as $p) {
                $list[] = trim((string)$p);
            }
        }
        return array_values(array_filter($list));
    }

    // ------------------------------------------------------------------
    // Token cache – nyers DBAL-lal, hogy ne zavarjuk a UnitOfWork-öt
    // ------------------------------------------------------------------

    protected function getToken()
    {
        if ($this->token && $this->tokenexpires > time()) {
            \mkw\store::writelog('Token a példányból', self::LOGFILE);
            return $this->token;
        }
        $cached = $this->readTokenCache();
        if ($cached) {
            $this->token = $cached['token'];
            $this->tokenexpires = $cached['expires'];
            \mkw\store::writelog('Token cacheból', self::LOGFILE);
            return $this->token;
        }
        return $this->login() ? $this->token : false;
    }

    public function clearToken()
    {
        $this->token = null;
        $this->tokenexpires = 0;
        \mkw\store::getEm()->getConnection()->executeStatement(
            'DELETE FROM parameterek WHERE id = ?',
            [\mkw\consts::UnasToken]
        );
    }

    /** Az API kulcs cseréje után a cache-elt token magától érvénytelen lesz. */
    private function credFingerprint()
    {
        return sha1($this->apiurl . '|' . $this->apikey);
    }

    private function readTokenCache()
    {
        $value = \mkw\store::getEm()->getConnection()->fetchOne(
            'SELECT ertek FROM parameterek WHERE id = ?',
            [\mkw\consts::UnasToken]
        );
        if (!$value) {
            return false;
        }
        $data = json_decode($value, true);
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
        $value = json_encode([
            'token' => $this->token,
            'expires' => $this->tokenexpires,
            'cred' => $this->credFingerprint(),
        ]);
        \mkw\store::getEm()->getConnection()->executeStatement(
            'INSERT INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)'
            . ' ON DUPLICATE KEY UPDATE ertek = VALUES(ertek), specialchars = 0',
            [\mkw\consts::UnasToken, $value]
        );
    }

    // ------------------------------------------------------------------
    // Rate limiter
    // ------------------------------------------------------------------

    /** A küszöb a korlát 90%-a: a maradék a kézi próbálkozásoké. */
    public function rateOk($endpoint)
    {
        $limit = self::HOURLYLIMIT[$endpoint] ?? self::HOURLYLIMIT['default'];
        $threshold = (int)floor($limit * 0.9);
        if ($threshold < 1) {
            $threshold = 1;
        }
        $used = $this->rateCount($endpoint);
        if ($used >= $threshold) {
            $this->addError(
                'RATELIMIT',
                'Az UNAS órás hívásszám korlátja elérve (' . $endpoint . ': ' . $used . '/' . $limit
                . '). Próbálja újra a következő órában.'
            );
            \mkw\store::writelog(
                'rate limit fék: ' . $endpoint . ' ' . $used . '/' . $limit . ' (küszöb ' . $threshold . ')',
                self::LOGFILE
            );
            $this->writeErrorLog($endpoint);
            return false;
        }
        return true;
    }

    public function rateCount($endpoint)
    {
        $data = $this->readRateLimit();
        return (int)($data['db'][$endpoint] ?? 0);
    }

    private function rateInc($endpoint)
    {
        $data = $this->readRateLimit();
        $data['db'][$endpoint] = (int)($data['db'][$endpoint] ?? 0) + 1;
        \mkw\store::getEm()->getConnection()->executeStatement(
            'INSERT INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)'
            . ' ON DUPLICATE KEY UPDATE ertek = VALUES(ertek), specialchars = 0',
            [\mkw\consts::UnasRateLimit, json_encode($data)]
        );
    }

    /** @return array{ora: string, db: array<string,int>} */
    private function readRateLimit()
    {
        $hour = date('Y-m-d-H');
        $value = \mkw\store::getEm()->getConnection()->fetchOne(
            'SELECT ertek FROM parameterek WHERE id = ?',
            [\mkw\consts::UnasRateLimit]
        );
        $data = $value ? json_decode($value, true) : null;
        if (!is_array($data) || ($data['ora'] ?? '') !== $hour || !isset($data['db']) || !is_array($data['db'])) {
            return ['ora' => $hour, 'db' => []];
        }
        return $data;
    }

    /** Karbantartás éjfél ±10 perc – ilyenkor a hívás úgyis elhalna. */
    private function isMaintenanceWindow()
    {
        $hourMinute = (int)date('Hi');
        return $hourMinute >= 2355 || $hourMinute <= 10;
    }

    // ------------------------------------------------------------------
    // Naplózás, dump
    // ------------------------------------------------------------------

    private function addError($code, $message)
    {
        $this->lasterrors[] = ['code' => $code, 'message' => $message];
    }

    private function writeErrorLog($endpoint)
    {
        foreach ($this->lasterrors as $e) {
            \mkw\store::writelog($endpoint . ': ' . $e['code'] . ' ' . $e['message'], self::ERRORFILE);
        }
    }

    /**
     * A nyers válasz a `storage/logs`-ba, hogy utólag is megnézhető legyen (a naplóba csak az eleje).
     *
     * @return string|null a létrejött fájl NEVE
     */
    public function dump($prefix, $ext, $content)
    {
        if ($content === '' || $content === false || $content === null) {
            return null;
        }
        $file = $this->dumpName($prefix, $ext);
        // biztonsági öv, ha valamelyik válaszba mégis kerülne token
        if ($ext === 'xml') {
            $content = $this->maskXml($content);
        }
        if (@file_put_contents(\mkw\store::logsPath($file), $content) === false) {
            \mkw\store::writelog('a dump fájl nem írható: ' . $file, self::LOGFILE);
            return null;
        }
        @chmod(\mkw\store::logsPath($file), 0640);
        $this->pruneDumps($prefix, $ext, self::DUMPKEEP);
        \mkw\store::writelog('a teljes válasz a storage/logs mappában: ' . $file, self::LOGFILE);
        return $file;
    }

    /** `unas_<prefix>_<dátum>_<sorszám>.<ext>` – a sorszám kell, egy mp-en belül több hívás is fut. */
    private function dumpName($prefix, $ext)
    {
        $this->dumpseq++;
        return 'unas_' . $this->safeName($prefix) . '_' . date('Ymd_His')
            . '_' . str_pad((string)$this->dumpseq, 3, '0', STR_PAD_LEFT)
            . '.' . $this->safeName($ext);
    }

    private function safeName($s)
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '', (string)$s);
    }

    private function pruneDumps($prefix, $ext, $keep, $keepFile = null)
    {
        // A minta pontosan a dumpName() alakjára illeszkedik (…_<dátum>_<idő>_<3 jegy>.<ext>).
        // Csupasz `_*.ext` NEM volna jó: az a mellékfájlokat (…_nemtalalt.csv, …_riport.json) is
        // eltakarítaná a letöltött adatbázis mellől.
        $pattern = \mkw\store::logsPath('unas_' . $this->safeName($prefix) . '_*_*_???.' . $this->safeName($ext));
        $files = glob($pattern) ?: [];
        if ($keepFile) {
            // a folyamatban lévő menet fájlja nem eshet ki: a sorszámai félúton elcsúsznának
            $keepAbs = \mkw\store::logsPath(basename($keepFile));
            $files = array_values(array_filter($files, static fn($f) => $f !== $keepAbs));
        }
        if (count($files) <= $keep) {
            return;
        }
        // a névben a dátum van, tehát a névsor egyben időrend is
        sort($files);
        foreach (array_slice($files, 0, count($files) - $keep) as $f) {
            @unlink($f);
        }
    }

    /** Kitakarja az API kulcsot és a tokent. */
    private function maskXml($xml)
    {
        return preg_replace(
            ['#<ApiKey>.*?</ApiKey>#s', '#<Token>.*?</Token>#s'],
            ['<ApiKey>***</ApiKey>', '<Token>***</Token>'],
            (string)$xml
        );
    }

}
