<?php

namespace Services\Siiker;

use Doctrine\DBAL\ArrayParameterType;
use mkw\consts;

/**
 * A cél adatbázis kiürítése a migráció előtt: a migrált törzsek és minden rájuk hivatkozó adat
 * (árak, képek, változatok, bizonylatok, mozgás, folyószámla, kosár, leltár, FIFO…) törlődik, a
 * törzs-jellegű hivatkozás (dolgozó raktára, ország valutaneme, szállítási mód terméke…) csak
 * nullázódik. A függőségeket az information_schema-ból számolja, ezért egy új tábla sem marad ki:
 * CASCADE gyerek vagy a DATATABLES listán szereplő forgalmi tábla → törlés, minden más → NULL.
 *
 * Csak `--purge` kapcsolóval fut, és éles menetben a képernyőn megerősítést kér.
 */
class TargetPurger extends AbstractMigrator
{
    /** A migráció által írt táblák. */
    const ROOTS = ['termek', 'partner', 'termekfa', 'arsav', 'afa', 'valutanem', 'arfolyam', 'bankszamla', 'me', 'vtsz', 'fizmod', 'raktar'];

    /** Forgalmi táblák: a RESTRICT hivatkozás ellenére törlendők, nem nullázandók. */
    const DATATABLES = [
        'bizonylatfej', 'bizonylattetel', 'bizonylatnaplo', 'bizonylattetelkapcsolodokoltseg',
        'bankbizonylatfej', 'bankbizonylattetel', 'banktranzakcio', 'penztarbizonylatfej', 'penztarbizonylattetel',
        'folyoszamla', 'mptfolyoszamla', 'kosar', 'leltarfej', 'leltartetel', 'termekbevetimport',
        'idopontfoglalas', 'idopontreszvetel', 'jogaberlet', 'jogareszvetel', 'jogaszamlazatlaneladas',
        'fifoertek', 'fiforeteg', 'fifovaltozas', 'elallas', 'elallasnaplo', 'glsutanvet',
        'fizmod_hatar', 'szallitasimod_hatar', 'szallitasimod_orszag', 'szallitasimod_fizmodnovelo',
        'partnertermekkedvezmeny', 'partnertermekcsoportkedvezmeny', 'partnergyartokedvezmeny',
        'partnertermekszerzodes', 'partnertermekcsoportszerzodes', 'termekvaltozat', 'termekar', 'termekkep',
    ];

    /** Törölt törzs-sorokra mutató paraméterek; a migráció újra beállítja az alapokat. */
    const PARAMETERS = [
        consts::Arsav, consts::ShowTermekArsav, consts::ShowTermekArsavValutanem,
        consts::Webshop2Price, consts::Webshop2Discount, consts::Webshop3Price, consts::Webshop3Discount,
        consts::Webshop4Price, consts::Webshop4Discount, consts::Webshop5Price, consts::Webshop5Discount,
        consts::Valutanem, consts::WebshopValutanem, consts::PartnerAlapValutanem,
        consts::Raktar, consts::UnasRaktar,
        consts::Fizmod, consts::UtanvetFizmod, consts::KeszpenzFizmod,
        consts::NullasAfa, consts::Tulajpartner,
    ];

    private array $deleteTables = [];
    private array $nullifyColumns = [];

    public function run(): void
    {
        $conn = $this->em->getConnection();
        $this->buildPlan();
        $this->printPlan();

        $dryRun = !empty($this->options['dry-run']);
        if (!$dryRun && empty($this->options['yes']) && !$this->confirm()) {
            throw new \RuntimeException('Törlés megszakítva');
        }

        $conn->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        try {
            foreach ($this->nullifyColumns as [$table, $column]) {
                $n = $conn->executeStatement('UPDATE `' . $table . '` SET `' . $column . '` = NULL WHERE `' . $column . '` IS NOT NULL');
                if ($n) {
                    $this->report->note('Nullázva: ' . $table . '.' . $column . ' (' . $n . ' sor)');
                }
            }
            foreach ($this->deleteTables as $table) {
                $n = $conn->executeStatement('DELETE FROM `' . $table . '`');
                if ($n) {
                    $this->report->note('Törölve: ' . $table . ' (' . $n . ' sor)');
                }
            }
        } finally {
            $conn->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        }
        $n = $conn->executeStatement('DELETE FROM parameterek WHERE id IN (?)', [self::PARAMETERS], [ArrayParameterType::STRING]);
        $this->report->note('Törölt paraméter: ' . $n);
        $this->em->clear();

        if ($dryRun) {
            $this->report->note('Próba menet: a képmappa marad');
        } else {
            $this->removeImageFolder();
        }
    }

    /** Bejárja a hivatkozásokat a gyökerektől: törlendő táblák és nullázandó oszlopok. */
    private function buildPlan(): void
    {
        $fks = $this->loadForeignKeys();
        $delete = array_fill_keys(self::ROOTS, true);
        $nullify = [];
        $queue = self::ROOTS;
        $data = array_fill_keys(self::DATATABLES, true);

        while ($queue) {
            $target = array_shift($queue);
            foreach ($fks[$target] ?? [] as $fk) {
                $table = $fk['table'];
                if (isset($delete[$table])) {
                    continue;
                }
                if ($fk['rule'] === 'CASCADE' || isset($data[$table])) {
                    $delete[$table] = true;
                    $queue[] = $table;
                    continue;
                }
                if (!$fk['nullable']) {
                    throw new \RuntimeException('Nem törölhető: ' . $table . '.' . $fk['column'] . ' kötelező mezővel hivatkozik a(z) ' . $target . ' táblára');
                }
                $nullify[$table . '.' . $fk['column']] = [$table, $fk['column']];
            }
        }
        foreach ($nullify as $key => [$table, $column]) {
            if (isset($delete[$table])) {
                unset($nullify[$key]);
            }
        }
        $this->deleteTables = array_keys($delete);
        $this->nullifyColumns = array_values($nullify);
        sort($this->deleteTables);
    }

    /** hivatkozott tábla => [[table, column, rule, nullable], …] */
    private function loadForeignKeys(): array
    {
        $conn = $this->em->getConnection();
        $rows = $conn->fetchAllAssociative(
            'SELECT k.TABLE_NAME t, k.COLUMN_NAME c, k.REFERENCED_TABLE_NAME r, rc.DELETE_RULE d, col.IS_NULLABLE n'
            . ' FROM information_schema.KEY_COLUMN_USAGE k'
            . ' JOIN information_schema.REFERENTIAL_CONSTRAINTS rc ON rc.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND rc.CONSTRAINT_NAME = k.CONSTRAINT_NAME'
            . ' JOIN information_schema.COLUMNS col ON col.TABLE_SCHEMA = k.TABLE_SCHEMA AND col.TABLE_NAME = k.TABLE_NAME AND col.COLUMN_NAME = k.COLUMN_NAME'
            . ' WHERE k.CONSTRAINT_SCHEMA = ? AND k.REFERENCED_TABLE_NAME IS NOT NULL',
            [$conn->getDatabase()]
        );
        $fks = [];
        foreach ($rows as $r) {
            $fks[$r['r']][] = ['table' => $r['t'], 'column' => $r['c'], 'rule' => $r['d'], 'nullable' => $r['n'] === 'YES'];
        }
        return $fks;
    }

    private function printPlan(): void
    {
        $conn = $this->em->getConnection();
        $lines = ['Törlendő táblák (sor):'];
        foreach ($this->deleteTables as $table) {
            $n = (int)$conn->fetchOne('SELECT COUNT(*) FROM `' . $table . '`');
            if ($n) {
                $lines[] = sprintf('  %-34s %8d', $table, $n);
            }
        }
        $lines[] = 'Nullázandó hivatkozások (sor):';
        foreach ($this->nullifyColumns as [$table, $column]) {
            $n = (int)$conn->fetchOne('SELECT COUNT(*) FROM `' . $table . '` WHERE `' . $column . '` IS NOT NULL');
            if ($n) {
                $lines[] = sprintf('  %-34s %8d', $table . '.' . $column, $n);
            }
        }
        $lines[] = 'Képmappa: ' . TermekKepMigrator::baseFolderAbs();
        $this->report->note(implode(PHP_EOL, $lines));
    }

    private function confirm(): bool
    {
        if (PHP_SAPI !== 'cli') {
            return false;
        }
        echo 'A fenti adatok visszavonhatatlanul törlődnek a(z) ' . $this->em->getConnection()->getDatabase() . ' adatbázisból. Folytatod? (igen/nem) ';
        $answer = trim((string)fgets(STDIN));
        return $answer === 'igen';
    }

    private function removeImageFolder(): void
    {
        $abs = TermekKepMigrator::baseFolderAbs();
        if (!is_dir($abs)) {
            return;
        }
        $n = 0;
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($abs, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($it as $file) {
            if ($file->isDir()) {
                @rmdir($file->getPathname());
            } elseif (@unlink($file->getPathname())) {
                $n++;
            }
        }
        @rmdir($abs);
        $this->report->note('Képmappa törölve: ' . $abs . ' (' . $n . ' fájl)');
    }
}
