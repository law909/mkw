<?php

namespace Services;

/**
 * A folyószámla két oldalának összevetése.
 *
 * Egy bizonylat kintlevősége akkor tűnik el rendesen, ha a bizonylat sora és a rá könyvelt
 * pénzmozgás sora ugyanúgy viselkedik: együtt esnek ki (rontás), vagy együtt maradnak bent és
 * kinullázzák egymást. Ahol ez elcsúszik, ott a partner egyenlege hamis nyitott tételt vagy
 * hamis túlfizetést mutat – ezeket a "féloldalas" sorokat keresi ez a riport, plusz azokat,
 * ahol a folyószámla sor elavult a forrásához (bizonylat, bank-/pénztártétel) képest.
 *
 * Az egyes esetek leírása a docs/penzkezeles.md-ben van.
 */
class FolyoszamlaCheckService
{

    /** Ennyi sort mutatunk ellenőrzésenként a képernyőn; a talált összes darabszám ettől függetlenül látszik. */
    public const ROWLIMIT = 500;

    /** Excel exportban ennyi; itt az a cél, hogy minden kiférjen, a korlát csak a memória védelme. */
    public const EXPORTROWLIMIT = 10000;

    /**
     * Minden ellenőrzés eredménye, a legsúlyosabbal kezdve. A `db` mindig a talált összes sor,
     * akkor is, ha a `rows` a korlát miatt kevesebb.
     *
     * @return array [['key','nev','leiras','db','rows'], …]
     */
    public function getReport(int $rowlimit = self::ROWLIMIT): array
    {
        $conn = \mkw\store::getEm()->getConnection();
        $report = [];
        foreach ($this->getChecks() as $check) {
            $db = (int)$conn->fetchOne('SELECT COUNT(*) FROM (' . $check['sql'] . ') ellenorzes');
            $report[] = [
                'key' => $check['key'],
                'nev' => $check['nev'],
                'leiras' => $check['leiras'],
                'db' => $db,
                'rows' => $db ? $conn->fetchAllAssociative($check['sql'] . ' LIMIT ' . $rowlimit) : [],
            ];
        }
        return $report;
    }

    /**
     * Az elavult (és a hiányzó) folyószámla sorok újraképzése.
     *
     * A sorokat a pénzmozgás mentésekor futó listener írja, ezért elég a bank- és
     * pénztárbizonylatokat újra elmenteni: adatot nem írunk át, csak újraszámoltatjuk azt,
     * aminek a forrása amúgy is a bizonylat tétele.
     *
     * @return array ['penztar' => n, 'bank' => n]
     */
    public function regenerate(): array
    {
        return [
            'penztar' => $this->regenerateFor(
                \Entities\Penztarbizonylatfej::class,
                'SELECT DISTINCT pt.penztarbizonylatfej_id AS id FROM penztarbizonylattetel pt'
                . ' LEFT JOIN folyoszamla f ON (f.penztarbizonylattetel_id = pt.id)'
                . ' WHERE (f.id IS NULL)'
                . '  OR NOT (f.hivatkozottbizonylat <=> pt.hivatkozottbizonylat)'
                . '  OR NOT (f.hivatkozottdatum <=> pt.hivatkozottdatum)'
                . '  OR NOT (f.brutto <=> pt.brutto) OR NOT (f.rontott <=> pt.rontott)'
            ),
            'bank' => $this->regenerateFor(
                \Entities\Bankbizonylatfej::class,
                'SELECT DISTINCT bt.bankbizonylatfej_id AS id FROM bankbizonylattetel bt'
                . ' LEFT JOIN folyoszamla f ON (f.bankbizonylattetel_id = bt.id)'
                . ' WHERE (f.id IS NULL)'
                . '  OR NOT (f.hivatkozottbizonylat <=> bt.hivatkozottbizonylat)'
                . '  OR NOT (f.hivatkozottdatum <=> bt.hivatkozottdatum)'
                . '  OR NOT (f.brutto <=> bt.brutto) OR NOT (f.rontott <=> bt.rontott)'
            ),
        ];
    }

    /**
     * A megadott bizonylatok újramentése kötegenként. A scheduleForUpdate arra kell, hogy a
     * listener akkor is lefusson, ha magán a bizonylaton nem változott semmi – üres changesettel
     * a Doctrine nem ad ki UPDATE-et, tehát a bizonylat maga érintetlen marad.
     */
    private function regenerateFor(string $entityname, string $sql): int
    {
        $em = \mkw\store::getEm();
        $idk = $em->getConnection()->fetchFirstColumn($sql);
        $db = 0;
        foreach (array_chunk($idk, 50) as $koteg) {
            $uow = $em->getUnitOfWork();
            foreach ($koteg as $id) {
                $bizonylat = $em->getRepository($entityname)->find($id);
                if ($bizonylat) {
                    $uow->scheduleForUpdate($bizonylat);
                    $db++;
                }
            }
            $em->flush();
            $em->clear();
        }
        return $db;
    }

    /**
     * Az oszlopok minden ellenőrzésnél azonosak, hogy a riport egyetlen táblázattal
     * meg tudja jeleníteni őket: penzmozgas, bizonylat, partner, datum, osszeg, valutanem,
     * megjegyzes.
     */
    private function getChecks(): array
    {
        // a pénzmozgás sorokat a bizonylat sorokról ez különbözteti meg
        $penzmozgas = '(f.bizonylatfej_id IS NULL)';
        $azonosito = 'COALESCE(f.bankbizonylatfej_id, f.penztarbizonylatfej_id) AS penzmozgas';
        $kozos = ' p.nev AS partner, DATE_FORMAT(f.datum, "%Y.%m.%d.") AS datum,'
            . ' f.brutto * f.irany AS osszeg, v.nev AS valutanem';
        $joinok = ' LEFT JOIN partner p ON (p.id = f.partner_id)'
            . ' LEFT JOIN valutanem v ON (v.id = f.valutanem_id)';

        return [
            [
                'key' => 'deadreference',
                'nev' => 'Holt bizonylatra könyvelt pénzmozgás',
                'leiras' => 'Élő pénzmozgás, aminek a hivatkozott bizonylata rontott, stornó vagy stornózott.'
                    . ' A bizonylat sora kiesik a kintlevőségből, a pénzmozgásé bennmarad – ez hamis túlfizetés a partneren.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT_WS(", ", IF(bf.rontott, "rontott", NULL), IF(bf.storno, "stornó", NULL),'
                    . '  IF(bf.stornozott, "stornózott", NULL)) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0)'
                    . '  AND ((bf.rontott = 1) OR (bf.storno = 1) OR (bf.stornozott = 1))'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'missingbizonylat',
                'nev' => 'Nem létező bizonylatra hivatkozó pénzmozgás',
                'leiras' => 'A hivatkozott bizonylatszámhoz nincs bizonylat – elgépelés vagy azóta megszűnt bizonylat.'
                    . ' A pénzmozgás sosem fog párosodni.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' "nincs ilyen bizonylatszám" AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' LEFT JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0)'
                    . '  AND (f.hivatkozottbizonylat IS NOT NULL) AND (f.hivatkozottbizonylat <> "")'
                    . '  AND (bf.id IS NULL)'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'nobizonylatrow',
                'nev' => 'Pénzmozgás bizonylat oldali sor nélkül',
                'leiras' => 'A bizonylatra van élő pénzmozgás, a bizonylatnak viszont nincs élő folyószámla sora'
                    . ' (nem képez kintlevőséget, vagy készpénzes és a kpfolyoszamla ki van kapcsolva).'
                    . ' A befizetésnek nincs párja, ezért túlfizetésnek látszik.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT_WS(", ", IF(bf.penztmozgat, NULL, "nem képez kintlevőséget"), bf.fizmodnev) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0) AND (bf.rontott = 0)'
                    . '  AND NOT EXISTS (SELECT 1 FROM folyoszamla fb WHERE (fb.bizonylatfej_id = bf.id) AND (fb.rontott = 0))'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'datemismatch',
                'nev' => 'Nem párosuló esedékesség',
                'leiras' => 'A pénzmozgás hivatkozott dátuma egyetlen bizonylat oldali sor esedékességével sem egyezik.'
                    . ' A kintlevőség és a tartozás lista dátumra is párosít, ezért a bizonylat teljes összegben nyitva'
                    . ' marad, a befizetés pedig eltűnik a listáról.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT("pénzmozgás: ", IFNULL(DATE_FORMAT(f.hivatkozottdatum, "%Y.%m.%d."), "-"),'
                    . '  ", bizonylat: ", IFNULL((SELECT GROUP_CONCAT(DISTINCT DATE_FORMAT(fb.hivatkozottdatum, "%Y.%m.%d.")'
                    . '   ORDER BY fb.hivatkozottdatum) FROM folyoszamla fb WHERE fb.bizonylatfej_id = bf.id), "-")) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0)'
                    . '  AND (bf.rontott = 0) AND (bf.storno = 0) AND (bf.stornozott = 0)'
                    . '  AND EXISTS (SELECT 1 FROM folyoszamla fb WHERE (fb.bizonylatfej_id = bf.id) AND (fb.rontott = 0))'
                    . '  AND NOT EXISTS (SELECT 1 FROM folyoszamla fb WHERE (fb.bizonylatfej_id = bf.id)'
                    . '   AND (fb.hivatkozottdatum <=> f.hivatkozottdatum))'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'partnermismatch',
                'nev' => 'Eltérő partner',
                'leiras' => 'A pénzmozgás és a hivatkozott bizonylat más partneren van. A kintlevőség az egyik'
                    . ' partneren marad, a fizetés a másikon – mindkettő féloldalas.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT("bizonylat partnere: ", IFNULL(bf.partnernev, "-")) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0) AND (bf.rontott = 0)'
                    . '  AND NOT (f.partner_id <=> bf.partner_id)'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'valutanemmismatch',
                'nev' => 'Eltérő valutanem',
                'leiras' => 'A pénzmozgás más valutanemben van, mint a bizonylat. A folyószámla összeadja őket,'
                    . ' tehát az egyenleg számszerűen hamis.',
                'sql' => 'SELECT ' . $azonosito . ', f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT("sor: ", IFNULL(v.nev, "-"), ", bizonylat: ", IFNULL(bf.valutanemnev, "-")) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.hivatkozottbizonylat)'
                    . $joinok
                    . ' WHERE ' . $penzmozgas . ' AND (f.rontott = 0) AND (bf.rontott = 0)'
                    . '  AND NOT (f.valutanem_id <=> bf.valutanem_id)'
                    . ' ORDER BY f.datum DESC',
            ],
            [
                'key' => 'stalepenzmozgas',
                'nev' => 'Elavult folyószámla sor (pénzmozgás oldal)',
                'leiras' => 'A folyószámla sor nem egyezik a bank- vagy pénztártétellel, amiből képződött.'
                    . ' A sorok a bizonylat mentésekor képződnek újra: ezeket azóta más úton írták át.',
                'sql' => 'SELECT f.bankbizonylatfej_id AS penzmozgas, f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT("sor: ", IFNULL(f.hivatkozottbizonylat, "-"), " / ", f.brutto, IF(f.rontott, " / rontott", ""),'
                    . '  ", banktétel: ", IFNULL(bt.hivatkozottbizonylat, "-"), " / ", bt.brutto,'
                    . '  IF(bt.rontott, " / rontott", "")) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bankbizonylattetel bt ON (bt.id = f.bankbizonylattetel_id)'
                    . $joinok
                    . ' WHERE NOT (f.hivatkozottbizonylat <=> bt.hivatkozottbizonylat)'
                    . '  OR NOT (f.hivatkozottdatum <=> bt.hivatkozottdatum)'
                    . '  OR NOT (f.brutto <=> bt.brutto) OR NOT (f.rontott <=> bt.rontott)'
                    . ' UNION ALL'
                    . ' SELECT f.penztarbizonylatfej_id AS penzmozgas, f.hivatkozottbizonylat AS bizonylat,' . $kozos . ','
                    . ' CONCAT("sor: ", IFNULL(f.hivatkozottbizonylat, "-"), " / ", f.brutto, IF(f.rontott, " / rontott", ""),'
                    . '  ", pénztártétel: ", IFNULL(pt.hivatkozottbizonylat, "-"), " / ", pt.brutto,'
                    . '  IF(pt.rontott, " / rontott", "")) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN penztarbizonylattetel pt ON (pt.id = f.penztarbizonylattetel_id)'
                    . $joinok
                    . ' WHERE NOT (f.hivatkozottbizonylat <=> pt.hivatkozottbizonylat)'
                    . '  OR NOT (f.hivatkozottdatum <=> pt.hivatkozottdatum)'
                    . '  OR NOT (f.brutto <=> pt.brutto) OR NOT (f.rontott <=> pt.rontott)',
            ],
            [
                'key' => 'stalebizonylat',
                'nev' => 'Elavult folyószámla sor (bizonylat oldal)',
                'leiras' => 'A bizonylat sorának állapotjelzői vagy partnere eltérnek magától a bizonylattól.'
                    . ' Ilyenkor a bizonylat kiesése (vagy bent maradása) nem azt csinálja a listákon, amit várunk.',
                'sql' => 'SELECT "" AS penzmozgas, f.bizonylatfej_id AS bizonylat,' . $kozos . ','
                    . ' CONCAT("bizonylat: ", CONCAT_WS(", ", IF(bf.rontott, "rontott", NULL),'
                    . '  IF(bf.storno, "stornó", NULL), IF(bf.stornozott, "stornózott", NULL), IFNULL(bf.partnernev, "-")),'
                    . '  " / sor: ", CONCAT_WS(", ", IF(f.rontott, "rontott", NULL), IF(f.storno, "stornó", NULL),'
                    . '  IF(f.stornozott, "stornózott", NULL), IFNULL(p.nev, "-"))) AS megjegyzes'
                    . ' FROM folyoszamla f'
                    . ' JOIN bizonylatfej bf ON (bf.id = f.bizonylatfej_id)'
                    . $joinok
                    . ' WHERE NOT (f.rontott <=> bf.rontott) OR NOT (f.storno <=> bf.storno)'
                    . '  OR NOT (f.stornozott <=> bf.stornozott) OR NOT (f.partner_id <=> bf.partner_id)'
                    . ' ORDER BY f.datum DESC',
            ],
        ];
    }
}
