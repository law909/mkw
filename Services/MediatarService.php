<?php

namespace Services;

/**
 * Médiatár – a CKFinder 2.3 leváltása.
 *
 * Ez az osztály a fájlrendszer- és biztonsági mag: útvonal-behatárolás, validáció,
 * listázás, származékok (_100 … _2000), család-műveletek. Se HTTP, se echo –
 * a HTTP réteg a \Controllers\mediatarController.
 */
class MediatarService
{

    const QUALITY = 80;

    const DIR_PERMISSON = 0775;
    const FILE_PERMISSION = 0664;

    /**
     * A generált származékok. A kulcs a fájlnév-utótag (foo_250.jpg), az érték
     * a doboz mérete.
     */
    const SIZES = [
        '100' => [100, 100],
        '150' => [150, 150],
        '250' => [250, 250],
        '400' => [400, 400],
        '1000' => [1000, 800],
        '2000' => [2000, 1600],
    ];

    const THUMBSIZE = 250;

    /**
     * A származékokat elrejtő minta. Kiterjesztésre horgonyozva – egy naiv
     * strpos('_100') a valós bck_d_about01_1000.jpg-t is elrejtené.
     */
    const DERIVEDPATTERN = '/_(100|150|250|400|1000|2000)\.[^.]+$/i';

    const HIDDENFOLDERS = ['_thumbs', '.svn', 'CVS', '.git'];

    const THUMBDIR = '_thumbs';

    const MAXMEGAPIXEL = 50;

    /**
     * Erőforrás-típusok – az ÉLES `ckfinder/config.php` ResourceType tömbjének tükre.
     *
     * Az `Images` típus szándékosan tartalmaz dokumentum-kiterjesztéseket is: az admin
     * több helye (pl. a Partner/Termék „Dokumentumok" fül `.js-dokbrowsebutton`-ja)
     * `resourceType = 'Images'`-szel nyitja a tallózót, és onnan választ PDF-et, Word-öt,
     * táblázatot. Ha ezek kimaradnának, az a funkció némán megszűnne.
     *
     * Ez itt csak az ALAPÉRTELMEZÉS – deploymentenként felülírható a `config.ini`-ből,
     * lásd {@see getTypes()}.
     */
    const DEFAULTTYPES = [
        'Images' => [
            'dir' => '',
            'ext' => [
                'bmp',
                'gif',
                'jpeg',
                'jpg',
                'png',
                'doc',
                'docx',
                'xls',
                'xlsx',
                'ppt',
                'pptx',
                'pdf',
                'csv',
                'txt',
                'rtf',
                'ods',
                'odt',
                'zip',
            ],
            'max' => 0,
        ],
        'Videos' => ['dir' => 'videos', 'ext' => ['mp4', 'webm'], 'max' => 52428800],
        'Files' => [
            'dir' => 'files',
            'ext' => [
                '7z',
                'aiff',
                'asf',
                'bmp',
                'csv',
                'doc',
                'docx',
                'gz',
                'gzip',
                'jpeg',
                'jpg',
                'mid',
                'ods',
                'odt',
                'pdf',
                'png',
                'ppt',
                'pptx',
                'pxd',
                'qt',
                'ram',
                'rar',
                'rm',
                'rmi',
                'rmvb',
                'rtf',
                'sdc',
                'sitd',
                'sxc',
                'sxw',
                'tar',
                'tgz',
                'tif',
                'tiff',
                'txt',
                'vsd',
                'xls',
                'xlsx',
                'zip',
            ],
            'max' => 0,
        ],
    ];

    /**
     * A feloldott (config.ini-vel felülírt) típustábla, kérésenként egyszer.
     *
     * @var array|null
     */
    private static $types = null;

    const IMAGEEXT = ['bmp', 'gif', 'jpeg', 'jpg', 'png'];

    const VIDEOEXT = ['mp4', 'webm'];

    const HTMLTAGS = [
        '<!doctype',
        '<html',
        '<head',
        '<body',
        '<script',
        '<title',
        '<table',
        '<img',
        '<pre',
        '<svg',
        '<?php',
        '<?=',
        '<%',
    ];

    /**
     * @var string A típus neve (Images, Videos, …)
     */
    private $type;

    /**
     * @var array A TYPES bejegyzés
     */
    private $typedef;

    /**
     * @var string A típus gyökere a fájlrendszeren, záró elválasztó nélkül, realpath-olva
     */
    private $rootreal;

    /**
     * @var string A típus gyökerének URL-je, záró perjellel (pl. /kepek/ vagy /kepek/videos/)
     */
    private $rooturl;

    /**
     * @param string $type erőforrás-típus neve
     *
     * @throws \RuntimeException ismeretlen típusra vagy hiányzó gyökérre
     */
    public function __construct($type)
    {
        $types = self::getTypes();
        if (!is_string($type) || !array_key_exists($type, $types)) {
            throw new \RuntimeException('Ismeretlen erőforrás-típus');
        }
        $this->type = $type;
        $this->typedef = $types[$type];

        $base = self::getBaseUrl();
        $this->rooturl = $base . ($this->typedef['dir'] ? $this->typedef['dir'] . '/' : '');

        $basereal = realpath(self::getDocRoot() . $base);
        if ($basereal === false) {
            throw new \RuntimeException('A képkönyvtár nem található');
        }
        if ($this->typedef['dir']) {
            $dir = $basereal . DIRECTORY_SEPARATOR . $this->typedef['dir'];
            if (!is_dir($dir)) {
                @mkdir($dir, self::DIR_PERMISSON, true);
            }
            $real = realpath($dir);
            if ($real === false) {
                throw new \RuntimeException('A(z) ' . $type . ' könyvtár nem hozható létre');
            }
            if ($real !== $basereal
                && strncmp($real . DIRECTORY_SEPARATOR, $basereal . DIRECTORY_SEPARATOR, strlen($basereal) + 1) !== 0) {
                throw new \RuntimeException('A(z) ' . $type . ' könyvtára a gyökéren kívülre mutat');
            }
            $this->rootreal = $real;
        } else {
            $this->rootreal = $basereal;
        }
    }

    public static function getBaseUrl()
    {
        $root = \mkw\store::getConfigValue('path.mediatar');
        if (!$root) {
            $root = \mkw\store::getConfigValue('path.ckfinder', '/kepek/');
        }
        return '/' . trim(str_replace('\\', '/', $root), '/') . '/';
    }

    /**
     * Egy kép-URL összes alakja, amiben az adatbázisban előfordulhat: a médiatár nyersen
     * írja ki, a CKFinder 2.3 szegmensenként rawurlencode-olva tette, és a vezető perjel
     * is hol van, hol nincs.
     *
     * @return array
     */
    public static function urlVariants($url)
    {
        $decoded = rawurldecode((string)$url);
        $encoded = implode('/', array_map('rawurlencode', explode('/', $decoded)));

        $out = [];
        foreach ([(string)$url, $decoded, $encoded] as $form) {
            $out[] = '/' . ltrim($form, '/');
            $out[] = ltrim($form, '/');
        }
        return array_values(array_unique($out));
    }

    /**
     * Az erőforrás-típusok feloldott táblája: a {@see DEFAULTTYPES} alapértelmezés fölé
     * húzva a `config.ini` beállításai.
     *
     * Szemantika:
     * - **Típusonkénti felülírás, mezőnként.** Amiről a config.ini nem szól, az az
     *   alapértelmezésből jön – tehát elég az `ext` sort kiírni, ha csak azt bővíted.
     * - **Új típus is felvehető** egy eddig nem létező névvel. Ilyenkor a `dir` és az
     *   `ext` is kötelező (üres alapértelmezésről indul). *(Ha új típusNEVET veszel fel,
     *   a `js/admin/default/mediatar.js` TYPES tömbjébe is bekerülhet, hogy a shim
     *   felismerje a `Név:` prefixet a startupPath-ban – a meglévő 37 hívási hely
     *   egyike sem használ mást, mint `Images`-t, tehát általában nincs rá szükség.)*
     * - A `max` érti a `50M` rövidítést; `0` = nincs típus-plafon, a PHP-limit dönt.
     * - A `dir` a képgyökérhez képest relatív; a konstruktor ellenőrzi, hogy tényleg
     *   alatta van-e.
     *
     * @return array
     */
    public static function getTypes()
    {
        if (self::$types !== null) {
            return self::$types;
        }
        $types = self::DEFAULTTYPES;

        foreach (\mkw\store::getConfig() as $key => $val) {
            if (!preg_match('/^mediatar\.type\.([A-Za-z0-9_]+)\.(dir|ext|max)$/', (string)$key, $m)) {
                continue;
            }
            $name = $m[1];
            if (!isset($types[$name])) {
                $types[$name] = ['dir' => '', 'ext' => [], 'max' => 0];
            }
            switch ($m[2]) {
                case 'dir':
                    $types[$name]['dir'] = trim(str_replace('\\', '/', (string)$val), '/');
                    break;
                case 'ext':
                    $types[$name]['ext'] = self::parseExtList($val);
                    break;
                case 'max':
                    $types[$name]['max'] = \mkw\thumbnail::returnBytes($val);
                    break;
            }
        }

        self::$types = $types;
        return $types;
    }

    /**
     * Vesszős kiterjesztés-lista normalizálása: kisbetű, csak alfanumerikus, egyedi.
     * Elfogadja a `.pdf` és a ` PDF ` alakot is.
     */
    private static function parseExtList($val)
    {
        if (is_array($val)) {
            $val = implode(',', $val);
        }
        $out = [];
        foreach (explode(',', (string)$val) as $e) {
            $e = preg_replace('/[^a-z0-9]/', '', strtolower(trim($e)));
            if ($e !== '' && !in_array($e, $out, true)) {
                $out[] = $e;
            }
        }
        return $out;
    }

    /**
     * Teszteléshez / config újratöltéshez: a memorizált tábla eldobása.
     */
    public static function resetTypes()
    {
        self::$types = null;
    }

    /**
     * A dokumentumgyökér. A kódbázis végig relatív útvonalakat használ
     * (path.termekkep = kepek/termek/), tehát a cwd a docroot; a DOCUMENT_ROOT
     * csak tartalék.
     */
    public static function getDocRoot()
    {
        $cwd = getcwd();
        if ($cwd) {
            return rtrim(str_replace('\\', '/', $cwd), '/');
        }
        return rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '.'), '/');
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRootUrl()
    {
        return $this->rooturl;
    }

    public function getMaxSize()
    {
        return $this->typedef['max'];
    }

    public function getAllowedExtensions()
    {
        return $this->typedef['ext'];
    }

    public function getEffectiveMaxSize()
    {
        $limits = [];
        if ($this->typedef['max']) {
            $limits[] = $this->typedef['max'];
        }
        foreach (['upload_max_filesize', 'post_max_size'] as $k) {
            // Kisbetűs névtér: a fájl mkw/thumbnail.php, az osztály Thumbnail –
            // Linuxon a \mkw\Thumbnail:: alak fatal (lásd a terv 1. kockázatát).
            $v = \mkw\thumbnail::returnBytes(ini_get($k));
            if ($v) {
                $limits[] = $v;
            }
        }
        return $limits ? min($limits) : 0;
    }

    // ------------------------------------------------------------------
    // Útvonal-behatárolás
    // ------------------------------------------------------------------

    /**
     * A nyers útvonal-inputot normalizálja: '/', vezető és záró perjellel.
     * Nadrágszíj a realpath() elé – elutasít mindent, ami nem egyszerű, létező
     * névből álló relatív útvonal.
     *
     * @throws \RuntimeException
     */
    public function normalizePath($path)
    {
        $path = (string)$path;
        if ($path === '') {
            return '/';
        }
        if (strpos($path, "\0") !== false || strpos($path, '\\') !== false) {
            throw new \RuntimeException('Érvénytelen útvonal');
        }
        $path = '/' . trim($path, '/') . '/';
        if ($path === '//') {
            return '/';
        }
        foreach (explode('/', trim($path, '/')) as $segment) {
            $this->checkSegment($segment);
        }
        return $path;
    }

    /**
     * Egyetlen útvonal-szegmens (mappanév) ellenőrzése.
     *
     * @throws \RuntimeException
     */
    private function checkSegment($segment)
    {
        if ($segment === '' || $segment === '.' || $segment === '..' || $segment[0] === '.') {
            throw new \RuntimeException('Érvénytelen útvonal');
        }
        // Szándékosan megengedő: a valódi határt a realpath() + prefix-ellenőrzés adja,
        // a meglévő mappák nevében pedig lehet ékezet. Csak a vezérlőkaraktereket
        // és az elválasztókat zárjuk ki.
        if (preg_match('/[\x00-\x1f\x7f\/\\\\]/', $segment)) {
            throw new \RuntimeException('Érvénytelen mappanév: ' . $segment);
        }
        if (strlen($segment) > 200) {
            throw new \RuntimeException('Túl hosszú mappanév');
        }
    }

    /**
     * Egy *létező* mappa abszolút útvonala a gyökéren belül. A realpath() oldja fel
     * a .. -t, a symlinkeket és a . -ot a prefix-ellenőrzés ELŐTT – ez teszi vízhatlanná.
     *
     * @throws \RuntimeException
     */
    public function absFolder($path)
    {
        $path = $this->normalizePath($path);
        $abs = realpath($this->rootreal . DIRECTORY_SEPARATOR . trim($path, '/'));
        $this->assertInRoot($abs);
        if (!is_dir($abs)) {
            throw new \RuntimeException('A mappa nem található');
        }
        return $abs;
    }

    /**
     * Egy *létező* fájl abszolút útvonala. A nevet is validálja.
     *
     * @throws \RuntimeException
     */
    public function absFile($path, $name)
    {
        $folder = $this->absFolder($path);
        $name = $this->checkName($name);
        $abs = realpath($folder . DIRECTORY_SEPARATOR . $name);
        $this->assertInRoot($abs);
        if (!is_file($abs)) {
            throw new \RuntimeException('A fájl nem található: ' . $name);
        }
        return $abs;
    }

    /**
     * A böngésző nyitó helyének feloldása egy nyers hívói értékből.
     *
     * Elfogadja mindkét alakot, mert a hívók kétfélét adnak:
     *   - mappa:            '/termek/', 'termek', '/'         → ['/termek/', '']
     *   - teljes fájl-út:   '/termek/foo.jpg'                 → ['/termek/', 'foo.jpg']
     *
     * A gyökér MAGA a /kepek/ (illetve a típus alkönyvtára), tehát a bemenetben a
     * 'kepek/' prefix NEM szerepelhet – az dupla gyökeret adna.
     *
     * Ami sehogy nem oldható fel, arra a gyökeret adja vissza: egy elavult vagy
     * elgépelt startupPath nyisson a gyökérben, ne dobjon hibaoldalt.
     *
     * @return array [mappa, kijelölendő fájlnév]
     */
    public function resolveStartPath($raw)
    {
        $raw = (string)$raw;

        $candidates = [$raw];
        // A CKFinder 2.3 rawurlencode-olt URL-t adott vissza, tehát a régi adatokban
        // '/2023%20csizm%C3%A1k/kep.jpg' áll, a fájlrendszeren viszont '2023 csizmák'.
        // Nyersen próbáljuk előbb: egy fájlnévben a '%' is lehet valódi karakter.
        if (preg_match('/%[0-9A-Fa-f]{2}/', $raw)) {
            $candidates[] = rawurldecode($raw);
        }

        $partial = null;
        foreach ($candidates as $candidate) {
            $exact = false;
            $res = $this->matchStartPath($candidate, $exact);
            if ($res === null) {
                continue;
            }
            if ($exact) {
                return $res;
            }
            if ($partial === null) {
                $partial = $res;
            }
        }

        return $partial === null ? ['/', ''] : $partial;
    }

    /**
     * A {@see resolveStartPath()} egy jelöltre. `null`, ha semmi nem oldható fel;
     * a $exact akkor igaz, ha létező mappára vagy létező fájlra sikerült illeszteni –
     * csak a nem egzakt (fájl nincs meg, de a mappa létezik) találatot érdemes egy
     * másik jelölttel felülírni.
     *
     * @return array|null [mappa, kijelölendő fájlnév]
     */
    private function matchStartPath($raw, &$exact)
    {
        $exact = false;

        // 1) Mappaként értelmezve – ez a gyakori eset.
        try {
            $path = $this->normalizePath($raw);
            $this->absFolder($path);
            $exact = true;
            return [$path, ''];
        } catch (\Exception $e) {
            // nem mappa; lehet, hogy a hívó a teljes fájl-útvonalat adta át
        }

        // 2) Az utolsó szegmenst fájlnévnek véve.
        $trimmed = trim($raw, '/');
        $pos = strrpos($trimmed, '/');
        $file = $pos === false ? $trimmed : substr($trimmed, $pos + 1);
        $folder = $pos === false ? '/' : '/' . substr($trimmed, 0, $pos) . '/';

        // A fájlnév a 3. ágon akkor is visszakerül, ha maga a fájl nincs meg – ezért
        // ITT kell megszűrni, ne csak az absFile()-ban. Enélkül egy '\0', backslash
        // vagy dotfile-név átcsorogna a sablon data-sel attribútumába.
        if ($file === '' || $file[0] === '.' || preg_match('#[\x00-\x1f\x7f/\\\\]#', $file)) {
            return null;
        }

        try {
            $this->absFile($folder, $file);
            $exact = true;
            return [$this->normalizePath($folder), $file];
        } catch (\Exception $e) {
            // a fájl nincs meg – a mappa attól még létezhet
        }

        try {
            $this->absFolder($folder);
            return [$this->normalizePath($folder), $file];
        } catch (\Exception $e) {
            // sem mappa, sem fájl
        }

        return null;
    }

    /**
     * A kijelölendő fájl neve a mappában. A shim a tárolt URL-t maga vágja mappára és
     * fájlnévre, tehát a CKFinder-kori adatokban a NÉV is kódolva érkezik.
     * Ha semmi nem illeszkedik, a kapott nevet adja vissza – a kijelölés marad el, nem a nyitás.
     */
    public function resolveSelName($path, $name)
    {
        $name = (string)$name;
        if ($name === '') {
            return '';
        }
        foreach ([$name, rawurldecode($name)] as $candidate) {
            try {
                $this->absFile($path, $candidate);
                return $candidate;
            } catch (\Exception $e) {
                // nem ez a név
            }
        }
        return $name;
    }

    /**
     * Létrehozási cél (mkdir, feltöltés): a realpath() itt false-t adna, ezért a
     * SZÜLŐT oldjuk fel és azt validáljuk, majd hozzáfűzzük a már sanitizált levelet.
     *
     * @throws \RuntimeException
     */
    public function absNew($path, $name)
    {
        $folder = $this->absFolder($path);
        $name = $this->checkName($name);
        return $folder . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * A feloldott abszolút útvonal tényleg a gyökér alatt van-e.
     *
     * @throws \RuntimeException
     */
    private function assertInRoot($abs)
    {
        if ($abs === false || $abs === null || $abs === '') {
            throw new \RuntimeException('Érvénytelen útvonal');
        }
        if ($abs === $this->rootreal) {
            return;
        }
        if (strncmp(
                $abs . DIRECTORY_SEPARATOR,
                $this->rootreal . DIRECTORY_SEPARATOR,
                strlen($this->rootreal) + 1
            ) !== 0) {
            throw new \RuntimeException('Érvénytelen útvonal');
        }
    }

    /**
     * Meglévő fájlnév ellenőrzése (nem feltöltésnél – ott sanitizeName épít újat).
     *
     * @throws \RuntimeException
     */
    public function checkName($name)
    {
        $name = (string)$name;
        if ($name === '' || strpos($name, "\0") !== false) {
            throw new \RuntimeException('Érvénytelen fájlnév');
        }
        if (basename($name) !== $name || $name[0] === '.') {
            throw new \RuntimeException('Érvénytelen fájlnév: ' . $name);
        }
        if (strlen($name) > 200) {
            throw new \RuntimeException('Túl hosszú fájlnév');
        }
        return $name;
    }

    /**
     * Az abszolút útvonalból a gyökérhez képesti mappa-útvonal (/termek/ alakban).
     */
    public function relFolder($abs)
    {
        $rel = substr($abs, strlen($this->rootreal));
        $rel = str_replace('\\', '/', $rel);
        return '/' . trim($rel, '/') . ($rel === '' || $rel === '/' ? '' : '/');
    }

    /**
     * Egy fájl publikus URL-je.
     */
    public function url($path, $name)
    {
        return $this->rooturl . ltrim($this->normalizePath($path), '/') . $name;
    }

    // ------------------------------------------------------------------
    // Név-sanitizálás
    // ------------------------------------------------------------------

    /**
     * Fájlnév újraépítése szűrés helyett: a kiterjesztést és a törzset külön vesszük,
     * a törzset urlize-oljuk. Ez strukturálisan öli meg a dupla kiterjesztést
     * (foo.php.jpg → foo-php.jpg), a dotfile-t, a perjelet és az unicode-meglepetéseket.
     */
    public function sanitizeName($orig)
    {
        $orig = str_replace(["\0", '/', '\\'], '', (string)$orig);
        $ext = strtolower(\mkw\store::getExtension($orig));
        $base = \mkw\store::urlize(pathinfo($orig, PATHINFO_FILENAME));
        if ($base === '') {
            $base = 'kep-' . uniqid();
        }
        $ext = preg_replace('/[^a-z0-9]/', '', $ext);
        $name = $ext === '' ? $base : $base . '.' . $ext;
        if (strlen($name) > 200) {
            $base = substr($base, 0, 200 - strlen($ext) - 1);
            $name = $ext === '' ? $base : $base . '.' . $ext;
        }
        return $name;
    }

    /**
     * Származéknévnek látszik-e (foo_250.jpg)? Ilyet nem engedünk feltölteni vagy
     * ráírni, különben foo_250_100.jpg keletkezne és az "eredeti" láthatatlan lenne.
     */
    public static function isDerivedName($name)
    {
        return (bool)preg_match(self::DERIVEDPATTERN, $name);
    }

    /**
     * Ütközés-feloldás: foo.jpg → foo-1.jpg → foo-2.jpg. A CKFinder (1), (2) helyett
     * URL-biztos kötőjeles forma, konzisztensen az urlize-zal.
     */
    public function uniqueName($folder, $name)
    {
        if (!file_exists($folder . DIRECTORY_SEPARATOR . $name)) {
            return $name;
        }
        $ext = \mkw\store::getExtension($name);
        $base = $ext === '' ? $name : substr($name, 0, -(strlen($ext) + 1));
        for ($i = 1; $i < 1000; $i++) {
            $try = $base . '-' . $i . ($ext === '' ? '' : '.' . $ext);
            if (!file_exists($folder . DIRECTORY_SEPARATOR . $try)) {
                return $try;
            }
        }
        throw new \RuntimeException('Nem sikerült szabad fájlnevet találni');
    }

    // ------------------------------------------------------------------
    // Validáció
    // ------------------------------------------------------------------

    /**
     * Kiterjesztés-allowlist – mindig a sanitizálás UTÁN hívva.
     *
     * @throws \RuntimeException
     */
    public function checkExtension($name)
    {
        $ext = strtolower(\mkw\store::getExtension($name));
        // Backstop: ma minden típusnak van allowlistje (a Flash típus kivezetésével az
        // üres lista megszűnt), de ha valaha visszakerülne egy csak olvasható típus,
        // ez fogja meg – az in_array() üres tömbre amúgy is elutasítana, csak
        // értelmezhetetlen hibaüzenettel.
        if (!$this->typedef['ext']) {
            throw new \RuntimeException('Ehhez a típushoz nem tölthető fel fájl');
        }
        if (!in_array($ext, $this->typedef['ext'], true)) {
            throw new \RuntimeException(
                'Nem engedélyezett kiterjesztés: ' . ($ext === '' ? '(nincs)' : $ext)
                . '. Engedélyezett: ' . implode(', ', $this->typedef['ext'])
            );
        }
        return $ext;
    }

    /**
     * A feltöltött ideiglenes fájl tartalmi ellenőrzése: MIME, kiterjesztés-egyezés,
     * beágyazott HTML, megapixel-plafon.
     *
     * @throws \RuntimeException
     */
    public function checkContent($tmp, $name)
    {
        $ext = strtolower(\mkw\store::getExtension($name));

        // A CKFinder ezt minden feltöltésre lefuttatta, típustól függetlenül.
        $this->checkEmbeddedHtml($tmp);

        // A tartalmi ellenőrzés a KITERJESZTÉSHEZ igazodik, nem a típus nevéhez:
        // az `Images` típus élesben dokumentumokat is tartalmaz, egy PDF-en pedig
        // értelmetlen (és hibás) volna képérvényességet követelni. Ugyanez a
        // szemantika, mint a CKFinder SecureImageUploads ága (isImageValid() csak
        // képkiterjesztésekre nézi a tartalmat).
        if (self::isImageExt($ext)) {
            $info = @getimagesize($tmp);
            if ($info === false || empty($info[2])) {
                throw new \RuntimeException('A fájl nem érvényes kép');
            }
            $allowed = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP, IMAGETYPE_WBMP];
            if (!in_array($info[2], $allowed, true)) {
                throw new \RuntimeException('Nem támogatott képformátum');
            }
            $real = ltrim(strtolower(image_type_to_extension($info[2])), '.');
            if ($real === 'jpeg') {
                $real = 'jpg';
            }
            $given = $ext === 'jpeg' ? 'jpg' : $ext;
            if ($real !== $given) {
                throw new \RuntimeException(
                    'A fájl tartalma nem egyezik a kiterjesztésével (' . $ext . ' helyett ' . $real . ')'
                );
            }
            if (($info[0] * $info[1]) > (self::MAXMEGAPIXEL * 1000000)) {
                throw new \RuntimeException(
                    'A kép felbontása túl nagy (max. ' . self::MAXMEGAPIXEL . ' megapixel)'
                );
            }
            return;
        }

        if (self::isVideoExt($ext) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = finfo_file($finfo, $tmp);
                finfo_close($finfo);
                if (!in_array($mime, ['video/mp4', 'video/webm'], true)) {
                    throw new \RuntimeException('Nem támogatott videóformátum: ' . $mime);
                }
            }
        }

        // Dokumentumok/archívumok: nincs MIME-ellenőrzés – a CKFinder sem csinált ilyet,
        // és egy doc/xls/zip/odt MIME-jét megbízhatóan felismerni sem lehet. A védelmet
        // itt a kiterjesztés-allowlist, a fájlnév újraépítése és a HTML-detektálás adja.
    }

    /**
     * Az első kilobájtban HTML/script/PHP nyitótag keresése.
     *
     * @throws \RuntimeException
     */
    private function checkEmbeddedHtml($file)
    {
        $fh = @fopen($file, 'rb');
        if (!$fh) {
            throw new \RuntimeException('A feltöltött fájl nem olvasható');
        }
        $head = strtolower((string)fread($fh, 1024));
        fclose($fh);
        foreach (self::HTMLTAGS as $tag) {
            if (strpos($head, $tag) !== false) {
                throw new \RuntimeException('A fájl beágyazott HTML/script kódot tartalmaz');
            }
        }
    }

    // ------------------------------------------------------------------
    // Listázás
    // ------------------------------------------------------------------

    /**
     * Egy mappa tartalma. A származékok, a rejtett mappák és minden dotfile kimarad.
     * Fájlonkénti getimagesize() szándékosan nincs – a rácsban nem mutatunk méretet.
     */
    public function listFolder($path)
    {
        $path = $this->normalizePath($path);
        $abs = $this->absFolder($path);

        $folders = [];
        $files = [];

        $dh = @opendir($abs);
        if (!$dh) {
            throw new \RuntimeException('A mappa nem olvasható');
        }
        while (($entry = readdir($dh)) !== false) {
            if ($entry === '.' || $entry === '..' || $entry[0] === '.') {
                continue;
            }
            $full = $abs . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($full)) {
                if (in_array($entry, self::HIDDENFOLDERS, true)) {
                    continue;
                }
                $folders[] = [
                    'name' => $entry,
                    'path' => $path . $entry . '/',
                ];
                continue;
            }
            if (!is_file($full) || self::isDerivedName($entry)) {
                continue;
            }
            $ext = strtolower(\mkw\store::getExtension($entry));
            // A listázás ugyanazt az allowlistet használja, mint a feltöltés – tehát
            // ami nem tölthető fel, azt látni sem lehet (a CKFinder GetFiles.php:98
            // ugyanígy szűrt).
            if (!in_array($ext, $this->typedef['ext'], true)) {
                continue;
            }
            $files[] = [
                'name' => $entry,
                'url' => $this->rooturl . ltrim($path, '/') . $entry,
                'thumb' => $this->resolveThumb($abs, $path, $entry, $ext),
                'size' => (int)@filesize($full),
                'mtime' => (int)@filemtime($full),
                'image' => self::isImageExt($ext),
            ];
        }
        closedir($dh);

        usort($folders, function ($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });
        usort($files, function ($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });

        return [
            'type' => $this->type,
            'path' => $path,
            'parent' => self::parentPath($path),
            'baseurl' => $this->rooturl,
            'folders' => $folders,
            'files' => $files,
        ];
    }

    /**
     * A szülőmappa útvonala, vagy null a gyökérben.
     */
    public static function parentPath($path)
    {
        $path = trim((string)$path, '/');
        if ($path === '') {
            return null;
        }
        $parts = explode('/', $path);
        array_pop($parts);
        return $parts ? '/' . implode('/', $parts) . '/' : '/';
    }

    /**
     * Kép-e a kiterjesztés? Ez dönti el, hogy készül-e származék és bélyegkép, és hogy
     * fut-e a tartalmi képérvényesség-ellenőrzés – NEM a típus neve.
     */
    public static function isImageExt($ext)
    {
        return in_array(strtolower((string)$ext), self::IMAGEEXT, true);
    }

    public static function isVideoExt($ext)
    {
        return in_array(strtolower((string)$ext), self::VIDEOEXT, true);
    }

    /**
     * A csempe képének feloldása, fallback-lánccal. A _250 származék már létezik
     * minden olyan fájlhoz, amit a serverresize plugin telepítése óta töltöttek fel –
     * nulla plusz lemez, nulla plusz generálás, és közvetlenül az Apache szolgálja ki.
     */
    private function resolveThumb($absfolder, $path, $name, $ext)
    {
        if (!self::isImageExt($ext)) {
            return null;
        }
        $base = substr($name, 0, -(strlen($ext) + 1));
        foreach (['250', '150'] as $size) {
            $cand = $base . '_' . $size . '.' . $ext;
            if (is_file($absfolder . DIRECTORY_SEPARATOR . $cand)) {
                return $this->rooturl . ltrim($path, '/') . $cand;
            }
        }
        if (@filesize($absfolder . DIRECTORY_SEPARATOR . $name) < 204800) {
            return $this->rooturl . ltrim($path, '/') . $name;
        }
        return '/admin/mediatar/thumb?type=' . rawurlencode($this->type)
            . '&path=' . rawurlencode($path)
            . '&name=' . rawurlencode($name);
    }

    // ------------------------------------------------------------------
    // Bélyegkép-cache
    // ------------------------------------------------------------------

    /**
     * A lusta bélyegkép-cache útvonala: <gyökér>/_thumbs/<Type>/<relpath>/<name>.
     * Pontosan a CKFinder elrendezése, tehát meleg cache-t öröklünk.
     */
    public function thumbCachePath($path, $name)
    {
        $path = $this->normalizePath($path);
        $name = $this->checkName($name);
        $base = realpath(self::getDocRoot() . self::getBaseUrl());
        if ($base === false) {
            throw new \RuntimeException('A képkönyvtár nem található');
        }
        return $base . DIRECTORY_SEPARATOR . self::THUMBDIR . DIRECTORY_SEPARATOR . $this->type
            . str_replace('/', DIRECTORY_SEPARATOR, rtrim($path, '/'))
            . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * A bélyegkép fájl előállítása, ha hiányzik vagy elavult.
     *
     * @return string|null a cache-fájl abszolút útvonala, vagy null ha nem generálható
     */
    public function ensureThumb($path, $name)
    {
        $src = $this->absFile($path, $name);
        $dst = $this->thumbCachePath($path, $name);

        if (is_file($dst) && @filemtime($dst) >= @filemtime($src)) {
            return $dst;
        }
        $dir = dirname($dst);
        if (!is_dir($dir) && !@mkdir($dir, self::DIR_PERMISSON, true) && !is_dir($dir)) {
            return null;
        }
        if (!\mkw\thumbnail::createThumb($src, $dst, self::THUMBSIZE, self::THUMBSIZE, self::QUALITY, true)) {
            return null;
        }
        @chmod($dst, 0664);
        return $dst;
    }

    // ------------------------------------------------------------------
    // Feltöltés + származékok
    // ------------------------------------------------------------------

    /**
     * Egyetlen feltöltött fájl feldolgozása: validáció, elhelyezés, származékok.
     *
     * @param array $file egy $_FILES bejegyzés
     * @param string $path célmappa a gyökérhez képest
     *
     * @return array ['name'=>…, 'url'=>…, 'thumb'=>…]
     * @throws \RuntimeException
     */
    public function upload($file, $path)
    {
        $this->checkUploadError($file);

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('Érvénytelen feltöltés');
        }
        if ($this->typedef['max'] && $file['size'] > $this->typedef['max']) {
            throw new \RuntimeException(
                'A fájl túl nagy (max. ' . self::formatSize($this->typedef['max']) . ')'
            );
        }

        $name = $this->sanitizeName($file['name']);
        $ext = $this->checkExtension($name);
        // Backstop, nem az elsődleges védelem: a sanitizeName() urlize-a az aláhúzást
        // kötőjelre cseréli, tehát egy "foo_250.jpg" feltöltés már "foo-250.jpg"-ként
        // érkezik ide, és nem ütközik a származéknevekkel. Ez jobb, mint az elutasítás
        // (a felhasználó nem kap hibát), de ha az urlize viselkedése valaha változna,
        // ez a sor fogja meg.
        if (self::isDerivedName($name)) {
            throw new \RuntimeException(
                'A fájlnév a származékok elnevezését ütközteti (_100 … _2000). Nevezd át a feltöltés előtt.'
            );
        }
        $this->checkContent($file['tmp_name'], $name);

        $folder = $this->absFolder($path);
        $name = $this->uniqueName($folder, $name);
        $dst = $folder . DIRECTORY_SEPARATOR . $name;

        if (!@move_uploaded_file($file['tmp_name'], $dst)) {
            throw new \RuntimeException('A fájl mentése nem sikerült');
        }
        @chmod($dst, self::FILE_PERMISSION);

        $this->createDerivatives($dst, $ext);

        return [
            'name' => $name,
            'url' => $this->rooturl . ltrim($this->normalizePath($path), '/') . $name,
            'thumb' => $this->resolveThumb($folder, $this->normalizePath($path), $name, $ext),
        ];
    }

    /**
     * A hat származék előállítása – kizárólag az eredetiből, soha származékból.
     *
     * A createThumb true-t ad és egyszerűen copy()-zik, ha a forrás már belefér a
     * dobozba: a kis képek így hat azonos másolatot kapnak. Ez SZÁNDÉKOS – a
     * getUrlLarge() feltételezi, hogy a fájl létezik, az "optimalizálás" 404-eket gyárt.
     */
    public function createDerivatives($abs, $ext = null)
    {
        if ($ext === null) {
            $ext = strtolower(\mkw\store::getExtension($abs));
        }
        if (!self::isImageExt($ext)) {
            return;
        }
        if (@getimagesize($abs) === false) {
            return;
        }
        $base = substr($abs, 0, -(strlen($ext) + 1));
        foreach (self::SIZES as $key => $size) {
            $dst = $base . '_' . $key . '.' . $ext;
            // $bmpSupported szándékosan false – a CKFinder és az importerek is így
            // hívják, BMP-hez ma sincs származék. Paritás.
            if (\mkw\thumbnail::createThumb($abs, $dst, $size[0], $size[1], self::QUALITY, true)) {
                @chmod($dst, self::FILE_PERMISSION);
            }
        }
    }

    /**
     * @throws \RuntimeException
     */
    private function checkUploadError($file)
    {
        $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($code === UPLOAD_ERR_OK) {
            return;
        }
        $msg = [
            UPLOAD_ERR_INI_SIZE => 'A fájl nagyobb, mint a szerveren beállított upload_max_filesize ('
                . ini_get('upload_max_filesize') . ')',
            UPLOAD_ERR_FORM_SIZE => 'A fájl nagyobb a megengedettnél',
            UPLOAD_ERR_PARTIAL => 'A fájl csak részben töltődött fel',
            UPLOAD_ERR_NO_FILE => 'Nem érkezett fájl',
            UPLOAD_ERR_NO_TMP_DIR => 'Hiányzik az ideiglenes könyvtár a szerveren',
            UPLOAD_ERR_CANT_WRITE => 'A fájl nem írható a lemezre',
            UPLOAD_ERR_EXTENSION => 'Egy PHP kiterjesztés megállította a feltöltést',
        ];
        throw new \RuntimeException($msg[$code] ?? 'Ismeretlen feltöltési hiba');
    }

    // ------------------------------------------------------------------
    // Család-műveletek
    // ------------------------------------------------------------------

    /**
     * Egy fájl "családja": maga a fájl, a hat származék és a _thumbs tükör.
     * A CKFinder csak az egy fájlt mozgatta/törölte, hat származékot árván hagyva.
     *
     * @return array abszolút útvonalak (csak a létezők)
     */
    public function familyPaths($path, $name)
    {
        $folder = $this->absFolder($path);
        $name = $this->checkName($name);
        $ext = \mkw\store::getExtension($name);
        $base = $ext === '' ? $name : substr($name, 0, -(strlen($ext) + 1));

        $out = [];
        $orig = $folder . DIRECTORY_SEPARATOR . $name;
        if (is_file($orig)) {
            $out[] = $orig;
        }
        foreach (array_keys(self::SIZES) as $key) {
            $p = $folder . DIRECTORY_SEPARATOR . $base . '_' . $key . ($ext === '' ? '' : '.' . $ext);
            if (is_file($p)) {
                $out[] = $p;
            }
        }
        try {
            $t = $this->thumbCachePath($path, $name);
            if (is_file($t)) {
                $out[] = $t;
            }
        } catch (\Exception $e) {
            // a cache hiánya nem hiba
        }
        return $out;
    }

    /**
     * Egy fájlnévhez tartozó összes családtag célneve az új név alapján.
     *
     * @return array [régi abszolút => új abszolút]
     */
    private function familyRenameMap($path, $name, $newname)
    {
        $folder = $this->absFolder($path);
        $ext = \mkw\store::getExtension($name);
        $base = $ext === '' ? $name : substr($name, 0, -(strlen($ext) + 1));
        $newext = \mkw\store::getExtension($newname);
        $newbase = $newext === '' ? $newname : substr($newname, 0, -(strlen($newext) + 1));

        $map = [];
        $orig = $folder . DIRECTORY_SEPARATOR . $name;
        if (is_file($orig)) {
            $map[$orig] = $folder . DIRECTORY_SEPARATOR . $newname;
        }
        foreach (array_keys(self::SIZES) as $key) {
            $from = $folder . DIRECTORY_SEPARATOR . $base . '_' . $key . ($ext === '' ? '' : '.' . $ext);
            if (is_file($from)) {
                $map[$from] = $folder . DIRECTORY_SEPARATOR . $newbase . '_' . $key
                    . ($newext === '' ? '' : '.' . $newext);
            }
        }
        try {
            $from = $this->thumbCachePath($path, $name);
            if (is_file($from)) {
                $map[$from] = $this->thumbCachePath($path, $newname);
            }
        } catch (\Exception $e) {
            // a cache hiánya nem hiba
        }
        return $map;
    }

    /**
     * Fájl átnevezése a teljes származék-családdal együtt.
     *
     * @return string az új (sanitizált, ütközésmentes) név
     * @throws \RuntimeException
     */
    public function rename($path, $name, $newname)
    {
        $folder = $this->absFolder($path);
        $this->absFile($path, $name);

        $newname = $this->sanitizeName($newname);
        if ($newname === '') {
            throw new \RuntimeException('Érvénytelen új név');
        }
        $oldext = strtolower(\mkw\store::getExtension($name));
        $newext = strtolower(\mkw\store::getExtension($newname));
        if ($newext !== $oldext) {
            throw new \RuntimeException('A kiterjesztés nem változtatható meg');
        }
        $this->checkExtension($newname);
        // Backstop, ugyanaz az ok, mint az upload()-ban: a sanitizeName() aláhúzás →
        // kötőjel cseréje miatt ide már nem érkezhet származéknévnek látszó érték.
        if (self::isDerivedName($newname)) {
            throw new \RuntimeException('Az új név a származékok elnevezését ütköztetné (_100 … _2000)');
        }
        if ($newname === $name) {
            return $name;
        }
        $newname = $this->uniqueName($folder, $newname);

        foreach ($this->familyRenameMap($path, $name, $newname) as $from => $to) {
            $dir = dirname($to);
            if (!is_dir($dir)) {
                @mkdir($dir, self::DIR_PERMISSON, true);
            }
            @rename($from, $to);
        }
        return $newname;
    }

    /**
     * Fájlok törlése a teljes származék-családdal együtt.
     *
     * @param array $names
     *
     * @return int a törölt fájlok (családtagok) száma
     */
    public function delete($path, array $names)
    {
        $cnt = 0;
        foreach ($names as $name) {
            $this->absFile($path, $name);
            foreach ($this->familyPaths($path, $name) as $p) {
                if (@unlink($p)) {
                    $cnt++;
                }
            }
        }
        return $cnt;
    }

    /**
     * Új mappa.
     *
     * @return string a létrehozott mappa útvonala a gyökérhez képest
     * @throws \RuntimeException
     */
    public function createFolder($path, $name)
    {
        $name = \mkw\store::urlize((string)$name);
        if ($name === '') {
            throw new \RuntimeException('Érvénytelen mappanév');
        }
        $this->checkSegment($name);
        $abs = $this->absNew($path, $name);
        if (file_exists($abs)) {
            throw new \RuntimeException('Már létezik ilyen nevű mappa vagy fájl');
        }
        if (!@mkdir($abs, self::DIR_PERMISSON)) {
            throw new \RuntimeException('A mappa létrehozása nem sikerült');
        }
        return $this->normalizePath($path) . $name . '/';
    }

    /**
     * Mappa törlése – csak üresen (a _thumbs tükröt leszámítva). Rekurzív törlést
     * szándékosan nem adunk: egy elgépelt kattintás nem vihet el egy termékkép-fát.
     *
     * @throws \RuntimeException
     */
    public function deleteFolder($path, $name)
    {
        $abs = $this->absFolder($this->normalizePath($path) . $name . '/');
        if ($abs === $this->rootreal) {
            throw new \RuntimeException('A gyökér nem törölhető');
        }
        $entries = @scandir($abs);
        if ($entries === false) {
            throw new \RuntimeException('A mappa nem olvasható');
        }
        $entries = array_diff($entries, ['.', '..']);
        if ($entries) {
            throw new \RuntimeException('A mappa nem üres (' . count($entries) . ' elem)');
        }
        if (!@rmdir($abs)) {
            throw new \RuntimeException('A mappa törlése nem sikerült');
        }
        // a _thumbs tükör maradéka
        try {
            $t = dirname($this->thumbCachePath($this->normalizePath($path) . $name . '/', 'x'));
            if (is_dir($t)) {
                @array_map('unlink', (array)glob($t . DIRECTORY_SEPARATOR . '*'));
                @rmdir($t);
            }
        } catch (\Exception $e) {
            // nem hiba
        }
        return true;
    }

    // ------------------------------------------------------------------
    // Egyéb
    // ------------------------------------------------------------------

    public static function formatSize($bytes)
    {
        $bytes = (int)$bytes;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * A SIZES tábla és a hat *imgpost DB-paraméter összevetése. A storefront
     * olvasó oldala DB-vezérelt, az író oldal (mi) fix – eltérés esetén az a
     * storefront ma is 404-el. Ez hibafeltárás, nem migráció.
     *
     * @return array eltérésenként egy magyar mondat; üres tömb = minden rendben
     */
    public static function checkImgPostParams()
    {
        $map = [
            \mkw\consts::Miniimgpost => '_100',
            \mkw\consts::Smallimgpost => '_150',
            \mkw\consts::Mediumimgpost => '_250',
            \mkw\consts::I400imgpost => '_400',
            \mkw\consts::Bigimgpost => '_1000',
            \mkw\consts::I2000imgpost => '_2000',
        ];
        $out = [];
        foreach ($map as $key => $expected) {
            $val = \mkw\store::getParameter($key);
            if ($val && $val !== $expected) {
                $out[] = 'A(z) ' . $key . ' paraméter értéke "' . $val . '", a médiatár viszont "'
                    . $expected . '" végződésű származékot állít elő.';
            }
        }
        return $out;
    }
}
