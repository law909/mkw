<?php

namespace Services;

use Entities\Termek;
use Entities\TermekKep;

/**
 * UNAS termékképek letöltése, származékok (_100 … _2000) és `TermekKep` sorok.
 *
 * A képet NEM kötjük változathoz: az UNAS nem mondja meg, melyik kép melyik változaté.
 * Lásd docs/unas-integracio.md 3.5.
 */
class UnasKepService
{

    /** az UNAS max. 9 képet enged */
    private const MAXKEP = 12;

    private const KEPEXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

    /** a takarítás riportjában legfeljebb ennyi fájlnév megy vissza listánként */
    private const CLEANUPSAMPLE = 500;

    private $absFolder;

    /** @var string a tárolt URL-ek eleje (vezető perjel nélkül, záró perjellel) */
    private $urlPrefix;

    /** @var MediatarService|null */
    private $mediatar;

    private $mediatarError;

    /** @var array termékID => [tartalom-lenyomat => URL] */
    private $contentIndex = [];

    /**
     * @param string|null $folder csak a takarításnak: az import mindig az UNAS képmappáját tölti.
     *                            Megadva az URL-előtag is ez lesz.
     */
    public function __construct($folder = null)
    {
        $folder = trim((string)$folder);
        $custom = $folder !== '';
        if ($custom) {
            $folder = rtrim(str_replace('\\', '/', $folder), '/') . '/';
        } else {
            $folder = UnasService::getKepPath();
        }
        $this->absFolder = rtrim(getcwd() . '/' . ltrim($folder, '/'), '/') . '/';
        $this->urlPrefix = ltrim($custom ? $folder : UnasService::getKepUrlPrefix(), '/');

        try {
            $this->mediatar = new MediatarService('Images');
        } catch (\Exception $e) {
            // származékok nélkül a kép önmagában még használható
            $this->mediatar = null;
            $this->mediatarError = $e->getMessage();
        }
    }

    /**
     * @param array $kepek [['url' => ..., 'alt' => ..., 'fokep' => bool], ...] UNAS sorrendben
     * @param bool $force a már meglévő fájlt is újratölti
     *
     * @return array{letoltve: int, kihagyva: int, duplikatum: int, hozzarendelve: int,
     *               hibak: array<string>}
     */
    public function importKepek(Termek $termek, array $kepek, $force = false)
    {
        $result = ['letoltve' => 0, 'kihagyva' => 0, 'duplikatum' => 0, 'hozzarendelve' => 0, 'hibak' => []];

        $kepek = $this->cleanKepList($kepek);
        if (!$kepek) {
            return $result;
        }
        if (!$this->ensureFolder()) {
            $result['hibak'][] = t('A képmappa nem hozható létre') . ': ' . $this->absFolder;
            return $result;
        }

        // a főkép a getProduct `base` típusú képe, egyébként az első
        $mainIndex = 0;
        foreach ($kepek as $i => $k) {
            if (!empty($k['fokep'])) {
                $mainIndex = $i;
                break;
            }
        }

        $existingUrls = $this->getExistingKepUrls($termek);
        $vanFokep = ltrim((string)$termek->getKepurl(''), '/') !== '';
        foreach ($kepek as $i => $kep) {
            $name = $this->sourceFilename($kep['url']);
            if ($name === '') {
                $result['hibak'][] = $termek->getCikkszam() . ': '
                    . t('a kép fájlneve nem használható') . ': ' . $kep['url'];
                continue;
            }
            $ext = strtolower(\mkw\store::getExtension($name));
            $abs = $this->absFolder . $name;
            $url = $this->urlPrefix . $name;

            if (is_file($abs) && filesize($abs) > 0 && !$force) {
                $result['kihagyva']++;
            } else {
                $error = $this->download($kep['url'], $abs);
                if ($error) {
                    $result['hibak'][] = $termek->getCikkszam() . ': ' . $error;
                    continue;
                }
                $this->createDerivatives($abs, $ext);
                $result['letoltve']++;
            }

            // Az UNAS a VÁLTOZAT cikkszáma szerint nevezi a képet, nálunk viszont a változatok
            // egy termék alá tartoznak: ugyanaz a fotó változatonként más néven jönne be. A név
            // tehát nem elég, a tartalmat kell összevetni a termék meglévő képeivel.
            $hash = @sha1_file($abs);
            $index = $this->getContentIndex($termek);
            if ($hash !== false && isset($index[$hash]) && $index[$hash] !== $url) {
                $result['duplikatum']++;
                continue;
            }
            if ($hash !== false) {
                $this->contentIndex[(int)$termek->getId()][$hash] = $url;
            }

            $alt = trim((string)($kep['alt'] ?? '')) ?: $termek->getNev();

            // Egy termékünkhöz több UNAS termék is tartozhat (a változataink külön UNAS sorok),
            // és mindegyik hozza a saját főképét. Főkép csak az elsőből lesz, a többi a galériába
            // megy: felülírva a kepurl-t az előző főkép hozzárendelés nélkül maradna a lemezen.
            if ($i === $mainIndex && !$vanFokep) {
                // setKepurl(hamis) a kepleiras-t is nullázza
                $termek->setKepurl($url);
                $termek->setKepleiras($alt);
                $existingUrls[$url] = true;
                $vanFokep = true;
                $result['hozzarendelve']++;
                continue;
            }
            if (isset($existingUrls[$url])) {
                continue;
            }
            $tk = new TermekKep();
            $termek->addTermekKep($tk);
            $tk->setUrl($url);
            $tk->setLeiras($alt);
            \mkw\store::getEm()->persist($tk);
            $existingUrls[$url] = true;
            $result['hozzarendelve']++;
        }

        return $result;
    }

    // ------------------------------------------------------------------

    private function cleanKepList(array $kepek)
    {
        $result = [];
        $seen = [];
        foreach ($kepek as $kep) {
            $url = trim((string)($kep['url'] ?? ''));
            if ($url === '' || isset($seen[$url])) {
                continue;
            }
            $seen[$url] = true;
            $result[] = [
                'url' => $url,
                'alt' => (string)($kep['alt'] ?? ''),
                'fokep' => !empty($kep['fokep']),
            ];
            if (count($result) >= self::MAXKEP) {
                break;
            }
        }
        return $result;
    }

    /** A tárolt URL-ek hol vezető perjellel, hol anélkül szerepelnek – normalizálva hasonlítunk. */
    private function getExistingKepUrls(Termek $termek)
    {
        $set = [];
        $fokep = ltrim((string)$termek->getKepurl(''), '/');
        if ($fokep !== '') {
            $set[$fokep] = true;
        }
        foreach ($termek->getTermekKepek() as $kep) {
            $u = ltrim((string)$kep->getUrl(''), '/');
            if ($u !== '') {
                $set[$u] = true;
            }
        }
        return $set;
    }

    /**
     * A termék már meglévő képeinek tartalom-lenyomata: lenyomat => URL. Terméknként egyszer
     * épül fel, utána a példány gyorsítótárából jön.
     *
     * @return array<string,string>
     */
    private function getContentIndex(Termek $termek)
    {
        $id = (int)$termek->getId();
        if (array_key_exists($id, $this->contentIndex)) {
            return $this->contentIndex[$id];
        }
        $map = [];
        foreach ($this->getExistingKepUrls($termek) as $url => $x) {
            $abs = $this->absFromUrl($url);
            if ($abs !== null && is_file($abs)) {
                $hash = @sha1_file($abs);
                if ($hash !== false && !isset($map[$hash])) {
                    $map[$hash] = $url;
                }
            }
        }
        return $this->contentIndex[$id] = $map;
    }

    /** Csak a saját mappánkban lévő fájlt oldjuk fel – másét nem a mi dolgunk összehasonlítani. */
    private function absFromUrl($url)
    {
        $name = basename($url);
        if ($name === '' || ltrim($url, '/') !== ltrim($this->urlPrefix . $name, '/')) {
            return null;
        }
        return $this->absFolder . $name;
    }

    private function ensureFolder()
    {
        if (is_dir($this->absFolder)) {
            return true;
        }
        \mkw\store::createDirectoryRecursively($this->absFolder);
        return is_dir($this->absFolder);
    }

    /**
     * Az UNAS eredeti fájlneve, változatlanul – a névhez nem fűzünk hozzá semmit. Így egy kép
     * ugyanoda tölt le minden futásban (a kihagyás emiatt működik), és a törzsben is felismerhető
     * marad, melyik UNAS képről van szó.
     *
     * Amit KI KELL szűrni: a `kepek/` mappát a webszerver közvetlenül kiszolgálja, ezért az
     * útvonal-elemek és a nem képes kiterjesztés (pl. `.php`) elfogadhatatlan – egy érvényes
     * képnek látszó, de PHP kódot is tartalmazó fájl futtathatóvá válna.
     *
     * @return string üres, ha a név nem használható
     */
    private function sourceFilename($url)
    {
        $path = (string)parse_url($url, PHP_URL_PATH);
        $name = rawurldecode(basename($path));
        $name = str_replace(["\0", '/', '\\'], '', $name);
        $name = ltrim(trim($name), '.');
        if ($name === '' || !preg_match('//u', $name)) {
            return '';
        }
        if (!in_array(strtolower(\mkw\store::getExtension($name)), self::KEPEXTENSIONS, true)) {
            return '';
        }
        return $name;
    }

    /** Teszt-varrat: felülírható. @return string|null hibaüzenet, vagy null ha sikerült */
    protected function download($url, $abs)
    {
        $fh = @fopen($abs, 'w');
        if (!$fh) {
            return t('a képfájl nem írható') . ': ' . $abs;
        }
        $ch = \curl_init($url);
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        \curl_exec($ch);
        $errno = \curl_errno($ch);
        $error = \curl_error($ch);
        $httpcode = (int)\curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);
        fclose($fh);

        // a fél fájlt eldobjuk, különben a következő futás késznek hinné
        if ($errno || $httpcode >= 400 || !filesize($abs)) {
            @unlink($abs);
            return t('a kép nem tölthető le') . ' (http ' . $httpcode . ($error ? ', ' . $error : '') . '): ' . $url;
        }
        if (@getimagesize($abs) === false) {
            @unlink($abs);
            return t('a letöltött fájl nem kép') . ': ' . $url;
        }
        return null;
    }

    protected function createDerivatives($abs, $ext)
    {
        if (!$this->mediatar) {
            return;
        }
        $this->mediatar->createDerivatives($abs, $ext);
    }

    // ------------------------------------------------------------------
    // Takarítás
    // ------------------------------------------------------------------

    /**
     * A mappa árva fájljai: amikre az adatbázisból semmi nem hivatkozik. Ide gyűlik az azonos
     * tartalmú duplikátum (amit az {@see importKepek()} szándékosan a lemezen hagy), a régi
     * névkonvencióval letöltött kép és a törölt termékek képe is.
     *
     * A fájllal együtt a médiatár bélyegkép-tükre is törlődik; almappákba nem megyünk be, és az
     * adatbázishoz nem nyúlunk – a hiányzó fájlra mutató hivatkozások csak a riportba kerülnek.
     *
     * @param bool $apply false esetén csak számol
     * @param bool $force üres találat mellett is töröl
     *
     * @return array
     */
    public function cleanupOrphans($apply = false, $force = false)
    {
        $result = [
            'mappa' => $this->absFolder,
            'urlprefix' => $this->urlPrefix,
            'oszlop' => 0,
            'hivatkozott' => 0,
            'fajl' => 0,
            'megtartva' => 0,
            'megtartva_meret' => 0,
            'arva' => 0,
            'arva_meret' => 0,
            'almappa' => 0,
            'lista' => [],
            'hianyzo' => [],
            'hianyzo_db' => 0,
            'torolve' => 0,
            'torolve_meret' => 0,
            'hiba' => [],
            'hiba_db' => 0,
            'megallt' => false,
            'uzenet' => null,
        ];
        if (!is_dir($this->absFolder)) {
            $result['megallt'] = true;
            $result['uzenet'] = t('A képmappa nem található') . ': ' . $this->absFolder;
            return $result;
        }

        $scan = $this->collectReferencedNames();
        $result['oszlop'] = $scan['oszlop'];
        $result['hivatkozott'] = count($scan['nevek']);
        $protected = $this->buildProtectedSet($scan['nevek']);

        $onDisk = [];
        $doomed = [];
        foreach (scandir($this->absFolder) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $abs = $this->absFolder . $entry;
            if (is_dir($abs)) {
                $result['almappa']++;
                continue;
            }
            $onDisk[$entry] = true;
            $result['fajl']++;
            $size = (int)@filesize($abs);
            if ($this->isProtected($entry, $protected)) {
                $result['megtartva']++;
                $result['megtartva_meret'] += $size;
                continue;
            }
            $doomed[$entry] = $size;
            $result['arva']++;
            $result['arva_meret'] += $size;
        }

        $result['lista'] = array_slice(array_keys($doomed), 0, self::CLEANUPSAMPLE);
        $missing = array_diff_key($scan['nevek'], $onDisk);
        $result['hianyzo_db'] = count($missing);
        $result['hianyzo'] = array_slice(array_keys($missing), 0, self::CLEANUPSAMPLE);

        // Üres találat mellett a törlés az egész mappát vinné – ilyenkor jóval valószínűbb, hogy
        // a mappa vagy az URL-előtag téves, mint hogy tényleg minden kép árva.
        if ($doomed && !$scan['nevek'] && !$force) {
            $result['megallt'] = true;
            $result['uzenet'] = t('Egyetlen hivatkozást sem találtunk erre az előtagra, ezért a törlés leállt.');
            return $result;
        }
        if (!$apply) {
            return $result;
        }

        foreach ($doomed as $name => $size) {
            if (!@unlink($this->absFolder . $name)) {
                $result['hiba_db']++;
                if (count($result['hiba']) < self::CLEANUPSAMPLE) {
                    $result['hiba'][] = $name;
                }
                continue;
            }
            $result['torolve']++;
            $result['torolve_meret'] += $size;
            $thumb = $this->thumbCachePath($name);
            if ($thumb !== null && is_file($thumb)) {
                @unlink($thumb);
            }
        }
        return $result;
    }

    /**
     * Minden képnév, amire az adatbázis bárhonnan hivatkozik. Nem elég a `termek.kepurl` és a
     * `termekkep.url`: leírásba ágyazott `<img>` is mutathat ide, ezért az URL-gyanús nevű
     * oszlopok mellé a szöveges (HTML-t tároló) oszlopokat is végigvisszük.
     *
     * @return array{nevek: array<string,bool>, oszlop: int}
     */
    private function collectReferencedNames()
    {
        $conn = \mkw\store::getEm()->getConnection();
        $columns = $conn->fetchAllAssociative(
            "SELECT TABLE_NAME t, COLUMN_NAME c"
            . " FROM information_schema.COLUMNS"
            . " WHERE TABLE_SCHEMA = DATABASE()"
            . " AND DATA_TYPE IN ('varchar','char','tinytext','text','mediumtext','longtext')"
            . " AND (COLUMN_NAME LIKE '%url%' OR COLUMN_NAME LIKE '%kep%'"
            . " OR DATA_TYPE IN ('text','mediumtext','longtext'))"
        );

        $needle = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $this->urlPrefix) . '%';
        $names = [];
        foreach ($columns as $column) {
            $table = '`' . str_replace('`', '``', $column['t']) . '`';
            $field = '`' . str_replace('`', '``', $column['c']) . '`';
            $values = $conn->fetchFirstColumn(
                'SELECT DISTINCT ' . $field . ' FROM ' . $table . ' WHERE ' . $field . ' LIKE ?',
                [$needle]
            );
            foreach ($values as $value) {
                foreach ($this->extractNames($value) as $name) {
                    $names[$name] = true;
                }
            }
        }
        return ['nevek' => $names, 'oszlop' => count($columns)];
    }

    /** @return array<string> a mezőben szereplő képek fájlneve */
    private function extractNames($value)
    {
        $value = str_replace('\\/', '/', (string)$value);
        $pattern = '#' . preg_quote($this->urlPrefix, '#') . '([^\s"\'<>()\[\]{},;|\\\\]+)#i';
        if (!preg_match_all($pattern, $value, $m)) {
            return [];
        }
        $names = [];
        foreach ($m[1] as $hit) {
            $name = basename(rawurldecode((string)strtok($hit, '?#')));
            if ($name !== '' && $name !== '.' && $name !== '..') {
                $names[] = $name;
            }
        }
        return $names;
    }

    /**
     * Egy név és a származék-ősei: `abc_250.jpg` → [`abc_250.jpg`, `abc.jpg`]. A MediatarService
     * DERIVEDPATTERN-je csak felismeri a származékot, az alapnevet nem adja vissza.
     *
     * @return array<string>
     */
    private function nameChain($name)
    {
        $pattern = '/^(.+)_(' . implode('|', array_keys(MediatarService::SIZES)) . ')(\.[^.]+)$/i';
        $chain = [$name];
        while (preg_match($pattern, $name, $m)) {
            $name = $m[1] . $m[3];
            $chain[] = $name;
        }
        return $chain;
    }

    /**
     * A hivatkozott nevek és az őseik. Az ősre azért van szükség, mert egy leírásból hivatkozott
     * `abc_250.jpg` az `abc.jpg`-t is életben tartja – abból generálódik újra minden méret.
     *
     * @return array<string,bool>
     */
    private function buildProtectedSet(array $referenced)
    {
        $protected = [];
        foreach (array_keys($referenced) as $name) {
            foreach ($this->nameChain($name) as $link) {
                $protected[$link] = true;
            }
        }
        return $protected;
    }

    /** Élő a fájl, ha ő maga vagy valamelyik őse hivatkozott – az utóbbi a származékokat védi. */
    private function isProtected($name, array $protected)
    {
        foreach ($this->nameChain($name) as $link) {
            if (isset($protected[$link])) {
                return true;
            }
        }
        return false;
    }

    /**
     * A médiatár lusta bélyegkép-cache-e ugyanezt a fájlt tükrözi
     * ({@see MediatarService::thumbCachePath()}); a képeket `Images` típussal töltjük.
     *
     * @return string|null null, ha a mappa a képgyökéren kívül van
     */
    private function thumbCachePath($name)
    {
        $base = realpath(MediatarService::getDocRoot() . MediatarService::getBaseUrl());
        $real = realpath($this->absFolder);
        if ($base === false || $real === false) {
            return null;
        }
        $base = rtrim(str_replace('\\', '/', $base), '/');
        $real = rtrim(str_replace('\\', '/', $real), '/');
        if (strncmp($real . '/', $base . '/', strlen($base) + 1) !== 0) {
            return null;
        }
        $rel = trim(substr($real, strlen($base)), '/');
        return $base . '/' . MediatarService::THUMBDIR . '/Images'
            . ($rel === '' ? '' : '/' . $rel) . '/' . $name;
    }

    /**
     * A getProduct `Images` blokkjából képlista.
     *
     * @return array [['url' => ..., 'alt' => ..., 'fokep' => bool], ...]
     */
    public static function getKepekFromProduct(\SimpleXMLElement $product)
    {
        $kepek = [];
        if (!isset($product->Images->Image)) {
            return $kepek;
        }
        $defaultAlt = isset($product->Images->DefaultAlt) ? trim((string)$product->Images->DefaultAlt) : '';
        foreach ($product->Images->Image as $kep) {
            $url = isset($kep->SefUrl) ? trim((string)$kep->SefUrl) : '';
            if ($url === '' && isset($kep->Filename)) {
                $url = trim((string)$kep->Filename);
            }
            if ($url === '') {
                continue;
            }
            $alt = isset($kep->Alt) ? trim((string)$kep->Alt) : '';
            $kepek[] = [
                'url' => $url,
                'alt' => $alt !== '' ? $alt : $defaultAlt,
                'fokep' => isset($kep->Type) && strtolower(trim((string)$kep->Type)) === 'base',
            ];
        }
        return $kepek;
    }

    /**
     * A getProductDB "Kép link" oszlopából (`url1|url2|…`); a főkép az első.
     *
     * @return array [['url' => ..., 'alt' => ..., 'fokep' => bool], ...]
     */
    public static function getKepekFromColumn($column, $alt = '')
    {
        $kepek = [];
        foreach (explode('|', (string)$column) as $url) {
            $url = trim($url);
            if ($url === '') {
                continue;
            }
            $kepek[] = ['url' => $url, 'alt' => $alt, 'fokep' => count($kepek) === 0];
        }
        return $kepek;
    }

}
