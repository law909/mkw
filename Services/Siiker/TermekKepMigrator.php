<?php

namespace Services\Siiker;

use Entities\Termek;
use Entities\TermekKep;
use Services\MediatarService;
use mkw\store;

/**
 * A SIIKer dokumentumtárának termékképei a médiatárba: termékenként egy mappa a
 * `path.termekkep`/siiker alatt, a főkép (dokumentumtipus 1) a termék kepurl-je, a többi galéria.
 *
 * Az újrafuttatás a fájl tartalma (sha1) alapján ismeri fel a már átvitt képet, mert a
 * médiatár ütköző névnél -1, -2 utótagot ad, a név tehát nem azonosít.
 */
class TermekKepMigrator extends AbstractMigrator
{
    const FLUSHBATCH = 50;
    const SUBFOLDER = 'siiker';
    const FOKEP = 1;

    private MediatarService $mediatar;
    private string $basePath;
    private array $folderCache = [];

    public function run(): void
    {
        $this->mediatar = new MediatarService('Images');
        $this->basePath = self::resolveBasePath();
        $this->report->note('Képmappa: ' . $this->mediatar->url($this->basePath, ''));

        $termekMap = $this->loadIdMap(Termek::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT d.kod, d.termek, d.dokumentumtipus, ' . $this->src->text('d.filenev', 'filenev')
            . ', ' . $this->src->text('t.cikkszam', 'cikkszam')
            . ' FROM ' . $this->src->table('dokumentumtar') . ' d JOIN ' . $this->src->table('termek') . ' t ON t.kod = d.termek'
            . ' WHERE t.inaktiv = 0 AND d.binarisadat IS NOT NULL ORDER BY d.termek, d.dokumentumtipus, d.kod');

        $groups = [];
        foreach ($rows as $r) {
            $groups[(int)$r['termek']][] = $r;
        }
        if ($this->limit) {
            $groups = array_slice($groups, 0, $this->limit, true);
        }

        foreach ($groups as $kod => $kepek) {
            $tid = $termekMap[(string)$kod] ?? null;
            if (!$tid) {
                $this->report->skipped('TermekKep', 'nem migrált termék ' . $kod . ' (' . count($kepek) . ' kép)');
                continue;
            }
            $termek = $this->em->find(Termek::class, $tid);
            try {
                $this->migrateTermekKepek($termek, $kepek);
            } catch (\Throwable $e) {
                $this->report->error('TermekKep', $termek->getCikkszam() . ': ' . $e->getMessage());
            }
            $this->tick();
        }
        $this->flushClear();
    }

    private function migrateTermekKepek(Termek $termek, array $kepek): void
    {
        $path = $this->basePath . store::urlize($termek->getCikkszam() ?: 'termek-' . $termek->getId()) . '/';
        $folder = $this->ensureFolder($path);

        $index = $this->contentIndex($termek, $folder);
        $urls = [];
        foreach ($termek->getTermekKepek() as $tk) {
            $urls[ltrim((string)$tk->getUrl(), '/')] = true;
        }
        $kepurl = ltrim((string)$termek->getKepurl(''), '/');

        foreach ($kepek as $kep) {
            $data = $this->src->fetchBlob((int)$kep['kod']);
            if ($data === null) {
                $this->report->error('TermekKep', $termek->getCikkszam() . ': üres vagy kibonthatatlan blob (dokumentumtar ' . $kep['kod'] . ')');
                continue;
            }
            $info = @getimagesizefromstring($data);
            if ($info === false) {
                $this->report->error('TermekKep', $termek->getCikkszam() . ': nem kép (dokumentumtar ' . $kep['kod'] . ')');
                continue;
            }
            $hash = sha1($data);
            if (isset($index[$hash])) {
                $this->report->skipped('TermekKep');
                continue;
            }

            $name = $this->fileName((string)$kep['filenev'], $info['mime'] ?? '');
            $name = $this->mediatar->uniqueName($folder, $name);
            $abs = $folder . DIRECTORY_SEPARATOR . $name;
            if (file_put_contents($abs, $data) === false) {
                $this->report->error('TermekKep', $termek->getCikkszam() . ': nem írható ' . $abs);
                continue;
            }
            @chmod($abs, MediatarService::FILE_PERMISSION);
            $this->mediatar->createDerivatives($abs);
            $url = ltrim($this->mediatar->url($path, $name), '/');
            $index[$hash] = $url;

            if ((int)$kep['dokumentumtipus'] === self::FOKEP && $kepurl === '') {
                $termek->setKepurl($url);
                $termek->setKepleiras($termek->getNev());
                $kepurl = $url;
            } elseif (!isset($urls[$url])) {
                $tk = new TermekKep();
                $termek->addTermekKep($tk);
                $tk->setUrl($url);
                $tk->setLeiras($this->leiras((string)$kep['filenev']));
                $this->em->persist($tk);
                $urls[$url] = true;
            }
            $this->em->persist($termek);
            $this->report->created('TermekKep');
        }
    }

    /** A termék már hozzárendelt képeinek tartalom-lenyomata: ez ismeri fel az újrafuttatást. */
    private function contentIndex(Termek $termek, string $folder): array
    {
        $index = [];
        $urls = [ltrim((string)$termek->getKepurl(''), '/')];
        foreach ($termek->getTermekKepek() as $tk) {
            $urls[] = ltrim((string)$tk->getUrl(), '/');
        }
        foreach ($urls as $url) {
            if ($url === '') {
                continue;
            }
            $abs = MediatarService::getDocRoot() . '/' . $url;
            if (is_file($abs)) {
                $index[sha1_file($abs)] = $url;
            }
        }
        return $index;
    }

    private function fileName(string $filenev, string $mime): string
    {
        $name = $this->mediatar->sanitizeName($filenev !== '' ? $filenev : 'kep.jpg');
        $ext = strtolower(store::getExtension($name));
        if (!MediatarService::isImageExt($ext)) {
            $name .= $mime === 'image/png' ? '.png' : ($mime === 'image/gif' ? '.gif' : '.jpg');
        }
        return $name;
    }

    private function leiras(string $filenev): string
    {
        return trim((string)pathinfo($filenev, PATHINFO_FILENAME));
    }

    /** A siiker képmappa abszolút útvonala a lemezen (a törléshez). */
    public static function baseFolderAbs(): string
    {
        return rtrim(MediatarService::getDocRoot(), '/') . MediatarService::getBaseUrl() . ltrim(self::resolveBasePath(), '/');
    }

    /** `path.termekkep` a médiatár gyökeréhez képest, alatta a siiker mappa. */
    private static function resolveBasePath(): string
    {
        $root = trim(MediatarService::getBaseUrl(), '/');
        $termekkep = trim((string)store::getConfigValue('path.termekkep', 'kepek/termek/'), '/');
        if ($root !== '' && strncmp($termekkep . '/', $root . '/', strlen($root) + 1) === 0) {
            $termekkep = trim(substr($termekkep, strlen($root)), '/');
        } else {
            $termekkep = 'termek';
        }
        return '/' . ($termekkep !== '' ? $termekkep . '/' : '') . self::SUBFOLDER . '/';
    }

    private function ensureFolder(string $path): string
    {
        if (isset($this->folderCache[$path])) {
            return $this->folderCache[$path];
        }
        $current = '/';
        foreach (explode('/', trim($path, '/')) as $segment) {
            try {
                $this->mediatar->absFolder($current . $segment . '/');
            } catch (\RuntimeException $e) {
                $this->mediatar->createFolder($current, $segment);
            }
            $current .= $segment . '/';
        }
        return $this->folderCache[$path] = $this->mediatar->absFolder($path);
    }
}
