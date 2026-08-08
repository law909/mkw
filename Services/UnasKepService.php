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

    private $absFolder;

    /** @var string a tárolt URL-ek eleje (vezető perjel nélkül, záró perjellel) */
    private $urlPrefix;

    /** @var MediatarService|null */
    private $mediatar;

    private $mediatarError;

    /** @var array termékID => [tartalom-lenyomat => URL] */
    private $contentIndex = [];

    public function __construct()
    {
        $folder = UnasService::getKepPath();
        $this->absFolder = rtrim(getcwd() . '/' . ltrim($folder, '/'), '/') . '/';
        $this->urlPrefix = ltrim(UnasService::getKepUrlPrefix(), '/');

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
     * @return array{letoltve: int, kihagyva: int, duplikatum: int, hibak: array<string>}
     */
    public function importKepek(Termek $termek, array $kepek, $force = false)
    {
        $result = ['letoltve' => 0, 'kihagyva' => 0, 'duplikatum' => 0, 'hibak' => []];

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
            if ($i === $mainIndex) {
                // setKepurl(hamis) a kepleiras-t is nullázza
                $termek->setKepurl($url);
                $termek->setKepleiras($alt);
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
