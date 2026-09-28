<?php

namespace Services;

/**
 * Termék szín képek (`termekszinkep`) az UNAS import képneveiből.
 *
 * Az UNAS a képet a VÁLTOZAT cikkszáma szerint nevezi (`<cikkszam>.jpg` a főkép,
 * `<cikkszam>_altpic_<N>.jpg` a többi), a változatnak pedig színe van – a névből így kiderül,
 * melyik kép melyik színé. Csak hozzáad: a már összerendelt termék + szín párhoz nem nyúl.
 */
class UnasSzinKepService
{

    private const ALTPICPATTERN = '/^(.+)_altpic_(\d+)$/i';

    private $conn;

    /** @var array|null kulcs => [[fájlnév, pozíció], ...] a képmappa fájljai */
    private $diskFiles;

    private $absFolder;

    /** @var array abszolút útvonal => sha1|false */
    private $hashes = [];

    public function __construct()
    {
        $this->conn = \mkw\store::getEm()->getConnection();
        $this->absFolder = rtrim(getcwd() . '/' . ltrim(UnasService::getKepPath(), '/'), '/') . '/';
    }

    /**
     * @param bool $apply false esetén csak számol
     *
     * @return array a riport
     */
    public function assignAll($apply = false)
    {
        $report = [
            'termek' => 0,
            'uj_sor' => 0,
            'nev_szerint' => 0,
            'tartalom_szerint' => 0,
            'mar_osszerendelt_szin' => 0,
            'ketertelmu_cikkszam' => [],
            'nem_valtozat_nevu' => 0,
        ];

        $variantMap = [];
        $rows = $this->conn->fetchAllAssociative(
            'SELECT termek_id, cikkszam, szin_id FROM termekvaltozat'
            . ' WHERE szin_id IS NOT NULL AND termek_id IS NOT NULL AND COALESCE(cikkszam, "") <> ""'
        );
        foreach ($rows as $r) {
            $key = $this->key($r['cikkszam']);
            $szinId = (int)$r['szin_id'];
            $old = $variantMap[$r['termek_id']][$key] ?? null;
            if ($old !== null && $old !== $szinId) {
                $variantMap[$r['termek_id']][$key] = false;
                $report['ketertelmu_cikkszam'][] = $r['termek_id'] . ': ' . $r['cikkszam'];
                continue;
            }
            $variantMap[$r['termek_id']][$key] = $szinId;
        }

        $kepek = [];
        foreach ($this->conn->fetchAllAssociative(
            'SELECT id, kepurl FROM termek WHERE COALESCE(kepurl, "") <> ""'
        ) as $r) {
            if (isset($variantMap[$r['id']])) {
                $kepek[$r['id']][] = ['id' => null, 'url' => $r['kepurl']];
            }
        }
        foreach ($this->conn->fetchAllAssociative(
            'SELECT id, termek_id, url FROM termekkep WHERE COALESCE(url, "") <> "" ORDER BY id'
        ) as $r) {
            if (isset($variantMap[$r['termek_id']])) {
                $kepek[$r['termek_id']][] = ['id' => (int)$r['id'], 'url' => $r['url']];
            }
        }

        $assigned = [];
        foreach ($this->conn->fetchAllAssociative('SELECT DISTINCT termek_id, szin_id FROM termekszinkep') as $r) {
            $assigned[$r['termek_id'] . '/' . $r['szin_id']] = true;
        }

        foreach ($kepek as $termekId => $termekKepek) {
            $rows = $this->collectRows($variantMap[$termekId], $termekKepek, $report);
            $inserted = false;
            foreach ($rows as $szinId => $szinRows) {
                if (isset($assigned[$termekId . '/' . $szinId])) {
                    $report['mar_osszerendelt_szin']++;
                    continue;
                }
                asort($szinRows);
                foreach ($szinRows as $kepKey => $sorrend) {
                    if ($apply) {
                        $this->conn->executeStatement(
                            'INSERT INTO termekszinkep (created, lastmod, termek_id, szin_id, termekkep_id, sorrend)'
                            . ' VALUES (NOW(), NOW(), ?, ?, ?, ?)',
                            [$termekId, $szinId, $kepKey === 'fokep' ? null : (int)substr($kepKey, 1), $sorrend]
                        );
                    }
                    $report['uj_sor']++;
                    $inserted = true;
                }
            }
            if ($inserted) {
                $report['termek']++;
            }
        }
        return $report;
    }

    /**
     * @return array szín id => [képkulcs ('fokep' | 'k<termekkep id>') => sorrend]
     */
    private function collectRows(array $variants, array $termekKepek, array &$report)
    {
        $result = [];
        $namesOfTermek = [];
        foreach ($termekKepek as $kep) {
            $name = basename((string)$kep['url']);
            $namesOfTermek[$name] = true;
            [$key, $pos] = $this->parseName($name);
            $szinId = $variants[$key] ?? null;
            if (!$szinId) {
                if ($szinId === null) {
                    $report['nem_valtozat_nevu']++;
                }
                continue;
            }
            $this->addRow($result, $szinId, $kep['id'], $pos);
            $report['nev_szerint']++;
        }

        // A tartalomra azonos képet az import nem veszi fel újra, ha már egy másik változat
        // nevén bejött: a mappában maradt fájlja viszont megmondja, hogy ennek a színnek is része.
        foreach ($variants as $key => $szinId) {
            if (!$szinId) {
                continue;
            }
            foreach ($this->getDiskFiles()[$key] ?? [] as [$file, $pos]) {
                if (isset($namesOfTermek[$file])) {
                    continue;
                }
                $hash = $this->hash($this->absFolder . $file);
                if ($hash === false) {
                    continue;
                }
                foreach ($termekKepek as $kep) {
                    if ($this->hash($this->absPath($kep['url'])) === $hash) {
                        if ($this->addRow($result, $szinId, $kep['id'], $pos)) {
                            $report['tartalom_szerint']++;
                        }
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /** @return bool új sor lett-e */
    private function addRow(array &$result, $szinId, $kepId, $pos)
    {
        $kepKey = $kepId === null ? 'fokep' : 'k' . $kepId;
        $sorrend = ($pos + 1) * 10;
        $isNew = !isset($result[$szinId][$kepKey]);
        if ($isNew || $sorrend < $result[$szinId][$kepKey]) {
            $result[$szinId][$kepKey] = $sorrend;
        }
        return $isNew;
    }

    /** `ABC_altpic_2.jpg` → ['abc', 2], `ABC.jpg` → ['abc', 0] */
    private function parseName($name)
    {
        $base = pathinfo($name, PATHINFO_FILENAME);
        if (preg_match(self::ALTPICPATTERN, $base, $m)) {
            return [$this->key($m[1]), (int)$m[2]];
        }
        return [$this->key($base), 0];
    }

    /** kis/nagybetű és `_`/`-` független, ahogy az import is párosítja a cikkszámot */
    private function key($cikkszam)
    {
        return mb_strtolower(str_replace('_', '-', trim((string)$cikkszam)), 'UTF-8');
    }

    private function getDiskFiles()
    {
        if ($this->diskFiles !== null) {
            return $this->diskFiles;
        }
        $this->diskFiles = [];
        foreach (is_dir($this->absFolder) ? (scandir($this->absFolder) ?: []) : [] as $file) {
            if (!is_file($this->absFolder . $file) || preg_match(MediatarService::DERIVEDPATTERN, $file)) {
                continue;
            }
            [$key, $pos] = $this->parseName($file);
            $this->diskFiles[$key][] = [$file, $pos];
        }
        return $this->diskFiles;
    }

    private function absPath($url)
    {
        return getcwd() . '/' . ltrim((string)$url, '/');
    }

    private function hash($abs)
    {
        if (!array_key_exists($abs, $this->hashes)) {
            $this->hashes[$abs] = is_file($abs) ? @sha1_file($abs) : false;
        }
        return $this->hashes[$abs];
    }

}
