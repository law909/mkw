<?php

namespace Services;

use Entities\TermekFa;

/**
 * UNAS kategóriák → termékfa (termekfa). Egyetlen `getCategory` hívás, szűrő nélkül: az a teljes
 * kategórialista.
 *
 * Párosítás: a `termekfa.unasid`, annak híján a szülő alatt azonos nevű csomópont (ugyanaz a
 * név-útvonal, amelyet a termékimport `sortRootTermek()`-je is épít), végül új csomópont.
 * Meglévő csomópontot nem mozgatunk: a karkód a termékeken is le van másolva, az áthelyezés
 * teljes karkód-újragenerálás lenne. Ilyenkor csak jelezzük.
 *
 * A válasz elemneveit élő hívással még nem láttuk, ezért a mezők több jelölt névből olvasódnak,
 * és a riport a nyers válasz fájlját is megadja.
 */
class UnasKategoriaImportService
{

    private $unas;

    /** @var UnasKepService|null lustán, csak ha van mit letölteni */
    private $kepService;

    private $root;

    /** @var array<string, TermekFa> UNAS id → csomópont, az ebben a futásban feldolgozottak */
    private $byUnasid = [];

    public function __construct(?UnasService $unas = null)
    {
        $this->unas = $unas ?: new UnasService();
    }

    /**
     * @param bool $dryRun mindent végigszámol, de a végén visszagörget
     * @param bool $update a meglévő csomópontok nevét, sorrendjét, szövegeit és láthatóságát is
     *                      átírja; nélküle csak az újakat veszi fel és az unasid-t köti be
     */
    public function import(bool $dryRun, bool $update): array
    {
        $api = $this->unas->getApi();
        $params = [];
        $lang = UnasService::getLang();
        if ($lang) {
            $params['Lang'] = $lang;
        }
        $response = $api->getCategory($params);
        $dump = $api->getLastDumpFile();
        if (!$response) {
            throw new \Exception(
                ($api->getLasterrorsAsString() ?: t('Az UNAS nem adott értelmezhető választ.'))
                . ($dump ? ' (' . $dump . ')' : '')
            );
        }

        $categories = $this->parse($response);
        $report = [
            'osszes' => count($categories),
            'uj' => 0,
            'osszekotve' => 0,
            'frissitve' => 0,
            'valtozatlan' => 0,
            'masszulo' => [],
            'hibas' => [],
            'kep_letoltve' => 0,
            'kep_beallitva' => 0,
            'kep_hibak' => [],
            'dumpfajl' => $dump,
            'szarazfutas' => $dryRun,
            'frissit' => $update,
        ];

        $em = \mkw\store::getEm();
        $this->root = $em->getRepository(TermekFa::class)->findOneBy(['parent' => null], ['id' => 'ASC']);
        if (!$this->root) {
            throw new \Exception(t('A termékfának nincs gyökere, a kategóriák nem építhetők fel.'));
        }

        $em->beginTransaction();
        try {
            foreach ($this->sortParentsFirst($categories, $report) as $cat) {
                $this->importOne($cat, $update, $report);
            }
            $em->flush();
            if ($dryRun) {
                $em->rollback();
                $em->clear();
            } else {
                $em->commit();
            }
        } catch (\Exception $e) {
            $em->rollback();
            $em->clear();
            throw $e;
        }
        return $report;
    }

    /** @return array<int, array{id: string, nev: string, parentid: string, utvonal: string, sorrend: ?int, lathato: ?bool, oldalcim: string, seodescription: string, leiras: string}> */
    private function parse(\SimpleXMLElement $response): array
    {
        $ret = [];
        foreach ($response->xpath('//Category') ?: [] as $cat) {
            $ret[] = [
                'id' => $this->first($cat, ['Id']),
                'nev' => $this->first($cat, ['Name']),
                'parentid' => $this->first($cat, ['Parent/Id', 'ParentId', 'Parent']),
                'utvonal' => $this->first($cat, ['Parent/Tree', 'Tree']),
                'sorrend' => ($s = $this->first($cat, ['Order', 'Sort', 'Position'])) !== '' ? (int)$s : null,
                'lathato' => ($d = strtolower($this->first($cat, ['Display/Page', 'Display']))) !== '' ? !in_array($d, ['no', 'n', '0', 'false'], true) : null,
                'oldalcim' => $this->first($cat, ['Meta/Title', 'PageData/Title', 'Title']),
                'seodescription' => $this->first($cat, ['Meta/Description', 'PageData/Description']),
                'leiras' => $this->first($cat, ['Texts/Top', 'Text/Top', 'Texts/Text', 'Description']),
                'kep' => $this->first($cat, ['Image/Url/Big', 'Image/Url/Medium', 'Image/Url', 'Image/Big', 'Image/Src', 'Image', 'Picture/Url', 'ImageUrl']),
            ];
        }
        return $ret;
    }

    /** Az első nem üres jelölt, a szöveg levágva. Csak levélelemet fogad el, burkolót nem. */
    private function first(\SimpleXMLElement $node, array $paths): string
    {
        foreach ($paths as $path) {
            foreach ($node->xpath($path) ?: [] as $el) {
                if (!$el->count() && trim((string)$el) !== '') {
                    return trim((string)$el);
                }
            }
        }
        return '';
    }

    /** A szülő mindig a gyereke előtt jöjjön; a gyökér szintűek (szülő 0 vagy üres) elöl. */
    private function sortParentsFirst(array $categories, array &$report): array
    {
        $byId = [];
        foreach ($categories as $cat) {
            if ($cat['id'] === '' || $cat['nev'] === '') {
                $report['hibas'][] = $cat['id'] . ' ' . $cat['nev'];
                continue;
            }
            $byId[$cat['id']] = $cat;
        }
        $sorted = [];
        $seen = [];
        $visit = function ($id, $depth = 0) use (&$visit, &$sorted, &$seen, $byId) {
            if (isset($seen[$id]) || !isset($byId[$id]) || $depth > 50) {
                return;
            }
            $seen[$id] = true;
            $parentId = $byId[$id]['parentid'];
            if ($parentId !== '' && $parentId !== '0' && isset($byId[$parentId])) {
                $visit($parentId, $depth + 1);
            }
            $sorted[] = $byId[$id];
        };
        foreach (array_keys($byId) as $id) {
            $visit($id);
        }
        return $sorted;
    }

    private function importOne(array $cat, bool $update, array &$report): void
    {
        $em = \mkw\store::getEm();
        $parent = $this->resolveParent($cat, $report);
        $nev = mb_substr($cat['nev'], 0, 255);

        $node = $em->getRepository(TermekFa::class)->findOneBy(['unasid' => $cat['id']]);
        if (!$node) {
            $node = $em->getRepository(TermekFa::class)->findOneBy(['parent' => $parent, 'nev' => $nev, 'unasid' => null], ['id' => 'ASC']);
            if ($node) {
                $node->setUnasid($cat['id']);
                $report['osszekotve']++;
            }
        }

        if (!$node) {
            $node = new TermekFa();
            $node->setNev($nev);
            // a `_l1`-nek nincs visszaesése a magyar mezőre, üresen az idegen nyelvű bolt névtelen kategóriát mutatna
            $node->setNevL1($nev);
            $node->setUnasid($cat['id']);
            $node->setParent($parent);
            $this->applyData($node, $cat);
            $em->persist($node);
            // az azonosító kell a karkódhoz, azt pedig a setParent tömés nélkül állítja be
            $em->flush();
            $node->setKarkod($parent->getKarkod() . sprintf('%05d', $node->getId()));
            $report['uj']++;
            $this->importImage($node, $cat, $report);
        } else {
            if ($node->getParent() !== $parent) {
                $report['masszulo'][] = $nev . ' (UNAS ' . $cat['id'] . ')';
            }
            $imageChanged = ($update || !$node->getKepurl()) && $this->importImage($node, $cat, $report);
            if (($update && $this->applyData($node, $cat, $nev)) || $imageChanged) {
                $report['frissitve']++;
            } else {
                $report['valtozatlan']++;
            }
        }
        $this->byUnasid[$cat['id']] = $node;
    }

    /**
     * Szárazfutásban nem tölt le, csak megszámolja, mi történne: a fájl a visszagörgetés után
     * is a lemezen maradna.
     *
     * @return bool változott-e a kategória képe
     */
    private function importImage(TermekFa $node, array $cat, array &$report): bool
    {
        if ($cat['kep'] === '') {
            return false;
        }
        if ($report['szarazfutas']) {
            $report['kep_beallitva']++;
            return true;
        }
        $this->kepService ??= new UnasKepService();
        $result = $this->kepService->downloadKep($cat['kep']);
        if ($result['hiba'] !== '') {
            $report['kep_hibak'][] = $cat['nev'] . ': ' . $result['hiba'];
            return false;
        }
        if ($result['letoltve']) {
            $report['kep_letoltve']++;
        }
        if (ltrim((string)$node->getKepurl(), '/') === ltrim($result['url'], '/')) {
            return false;
        }
        $node->setKepurl($result['url']);
        $node->setKepleiras($node->getNev());
        $report['kep_beallitva']++;
        return true;
    }

    /** Csak a kitöltött UNAS mezőket írja: a hiányzó elem nem törölheti az MKW-ban megadott szöveget. */
    private function applyData(TermekFa $node, array $cat, ?string $nev = null): bool
    {
        $changed = false;
        $write = function ($getter, $setter, $value) use ($node, &$changed) {
            if ($value !== null && $value !== '' && $node->$getter() != $value) {
                $node->$setter($value);
                $changed = true;
            }
        };
        $write('getNev', 'setNev', $nev);
        $write('getSorrend', 'setSorrend', $cat['sorrend']);
        $write('getOldalcim', 'setOldalcim', $cat['oldalcim']);
        $write('getSeodescription', 'setSeodescription', $cat['seodescription']);
        $write('getLeiras', 'setLeiras', $cat['leiras']);
        if ($cat['lathato'] !== null && (bool)$node->getInaktiv() === $cat['lathato']) {
            $node->setInaktiv(!$cat['lathato']);
            $changed = true;
        }
        return $changed;
    }

    /** A szülő az UNAS id-je alapján, ha nincs, a `Tree` név-útvonal alapján, végül a gyökér. */
    private function resolveParent(array $cat, array &$report): TermekFa
    {
        $id = $cat['parentid'];
        if ($id !== '' && $id !== '0') {
            if (isset($this->byUnasid[$id])) {
                return $this->byUnasid[$id];
            }
            $node = \mkw\store::getEm()->getRepository(TermekFa::class)->findOneBy(['unasid' => $id]);
            if ($node) {
                return $this->byUnasid[$id] = $node;
            }
        }
        if ($cat['utvonal'] !== '') {
            $node = $this->root;
            foreach (array_filter(array_map('trim', explode('|', $cat['utvonal'])), 'strlen') as $nev) {
                $child = \mkw\store::getEm()->getRepository(TermekFa::class)->findOneBy(['parent' => $node, 'nev' => mb_substr($nev, 0, 255)], ['id' => 'ASC']);
                if (!$child) {
                    return $this->root;
                }
                $node = $child;
            }
            return $node;
        }
        return $this->root;
    }
}
