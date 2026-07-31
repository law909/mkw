<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattipus;

/**
 * Egy időszak bejövő (szállítói) számláit tölti le a NAV Online Számla rendszerből, és a
 * {@see KoltsegszamlaJSONImportService}-szel költségszámlát készít belőlük.
 *
 * A menete:
 * 1. digest (számlalista) lekérése az időszakra – ebből csak a számlaszámok kellenek,
 * 2. amire már van nem rontott költségszámlánk (érkeztetett bizonylatszám), azt kihagyjuk,
 * 3. a maradék számla adatainak lekérése kötegelve,
 * 4. számlánként költségszámla készítése.
 *
 * Az időszak végét a {@see \mkw\consts::KoltsegszamlaImportDatum} paraméterben tároljuk: a
 * következő import ettől a dátumtól indul. Csak hibátlan futás után tároljuk el, így egy
 * elakadt számla a következő futáskor újra sorra kerül.
 */
class NAVKoltsegszamlaImportService
{
    /** a NAV ennél hosszabb időszakra nem ad digestet */
    public const MAX_IDOSZAK_NAP = 35;

    /** a felkínált időszak hossza: az utoljára importált dátum + ennyi nap */
    public const IDOSZAK_NAP = 30;

    /** ennyi számla adatait kérjük le egy hívásban */
    private const KOTEG_MERET = 25;

    private const BIZONYLATTIPUS = 'koltsegszamla';

    private KoltsegszamlaJSONImportService $importer;

    public function __construct(?KoltsegszamlaJSONImportService $importer = null)
    {
        $this->importer = $importer ?: new KoltsegszamlaJSONImportService();
    }

    /**
     * A következő import felkínált időszaka: az utoljára importált időszak vége, plusz
     * IDOSZAK_NAP nap – de a mai napnál nem tovább, különben a köztes időben megérkező
     * számlák kimaradnának. Ha még nem volt import, az utolsó IDOSZAK_NAP nap.
     *
     * @return array{tol: \DateTime, ig: \DateTime}
     */
    public function kovetkezoIdoszak(): array
    {
        $ma = new \DateTime('today');
        $tol = $this->datum((string)\mkw\store::getParameter(\mkw\consts::KoltsegszamlaImportDatum, ''))
            ?: (clone $ma)->modify('-' . self::IDOSZAK_NAP . ' days');
        if ($tol > $ma) {
            $tol = $ma;
        }
        $ig = (clone $tol)->modify('+' . self::IDOSZAK_NAP . ' days');
        if ($ig > $ma) {
            $ig = $ma;
        }
        return ['tol' => $tol, 'ig' => $ig];
    }

    /**
     * Egy időszak bejövő számláinak letöltése és költségszámlává alakítása.
     *
     * @return array{
     *     tol: \DateTime, ig: \DateTime, idoszak: string, digestdb: int, ujdb: int,
     *     letezodb: int, hibadb: int, megszakadt: bool, datummentve: bool, tetelek: array[]
     * }
     *
     * @throws \Exception ha nincs beállítva a költség termék, rossz az időszak, vagy a
     *                    számlalista nem tölthető le (ilyenkor semmi nem készül el)
     */
    public function import(\DateTime $tol, \DateTime $ig): array
    {
        $this->checkIdoszak($tol, $ig);
        $this->importer->getKoltsegTermek();

        $digest = $this->downloadDigest($tol, $ig);
        $letezok = $this->letezoKoltsegszamlak(array_column($digest, 'szamlaszam'));

        $tetelek = [];
        $letoltendok = [];
        foreach ($digest as $d) {
            if (isset($letezok[$d['szamlaszam']])) {
                $tetelek[$d['szamlaszam']] = $d + [
                        'statusz' => 'letezo',
                        'bizonylatszam' => $letezok[$d['szamlaszam']],
                        'uzenet' => '',
                    ];
            } else {
                $letoltendok[] = $d['szamlaszam'];
                $tetelek[$d['szamlaszam']] = $d + ['statusz' => 'hiba', 'bizonylatszam' => '', 'uzenet' => ''];
            }
        }

        $megszakadt = false;
        foreach (array_chunk($letoltendok, self::KOTEG_MERET) as $koteg) {
            $szamlak = $this->downloadSzamlak($koteg);
            foreach ($koteg as $szamlaszam) {
                if (!isset($szamlak[$szamlaszam])) {
                    $tetelek[$szamlaszam]['uzenet'] = t('A számla adatai nem tölthetők le a NAV-tól.');
                    continue;
                }
                try {
                    $fej = $this->importer->createFromArray($szamlak[$szamlaszam]);
                    $tetelek[$szamlaszam]['statusz'] = 'uj';
                    $tetelek[$szamlaszam]['bizonylatszam'] = $fej->getId();
                } catch (\Exception $e) {
                    $tetelek[$szamlaszam]['uzenet'] = $e->getMessage();
                    \mkw\store::writelog('NAV költségszámla import hiba (' . $szamlaszam . '): ' . $e->getMessage());
                    if (!\mkw\store::getEm()->isOpen()) {
                        $megszakadt = true;
                        break 2;
                    }
                }
            }
        }

        if ($megszakadt) {
            foreach ($tetelek as $szamlaszam => $tetel) {
                if ($tetel['statusz'] === 'hiba' && $tetel['uzenet'] === '') {
                    $tetelek[$szamlaszam]['uzenet'] = t('A feldolgozás megszakadt, ez a számla nem került sorra.');
                }
            }
        }

        $tetelek = array_values($tetelek);
        $ujdb = $this->darab($tetelek, 'uj');
        $letezodb = $this->darab($tetelek, 'letezo');
        $hibadb = $this->darab($tetelek, 'hiba');

        // csak hibátlan futás után lépünk tovább az időszakkal
        $datummentve = false;
        if (!$hibadb && !$megszakadt) {
            \mkw\store::setParameter(\mkw\consts::KoltsegszamlaImportDatum, $ig->format(\mkw\store::$SQLDateFormat));
            $datummentve = true;
        }

        return [
            'tol' => $tol,
            'ig' => $ig,
            'idoszak' => $tol->format(\mkw\store::$DateFormat) . ' - ' . $ig->format(\mkw\store::$DateFormat),
            'digestdb' => count($tetelek),
            'ujdb' => $ujdb,
            'letezodb' => $letezodb,
            'hibadb' => $hibadb,
            'megszakadt' => $megszakadt,
            'datummentve' => $datummentve,
            'tetelek' => $tetelek,
        ];
    }

    /**
     * @throws \Exception
     */
    private function checkIdoszak(\DateTime $tol, \DateTime $ig): void
    {
        if ($tol > $ig) {
            throw new \Exception(t('A kezdő dátum későbbi, mint a záró dátum.'));
        }
        if ((int)$tol->diff($ig)->days > self::MAX_IDOSZAK_NAP) {
            throw new \Exception(sprintf(t('A NAV legfeljebb %s napos időszakra ad számlalistát.'), self::MAX_IDOSZAK_NAP));
        }
    }

    private function downloadDigest(\DateTime $tol, \DateTime $ig): array
    {
        $no = $this->navOnline();
        if (!$no->getInboundDigests(
            $tol->format(\mkw\store::$SQLDateFormat),
            $ig->format(\mkw\store::$SQLDateFormat)
        )) {
            throw new \Exception(t('A bejövő számlák listája nem tölthető le.') . ' ' . $no->getErrorsAsHtml());
        }
        $adat = json_decode(base64_decode($no->getResult()), true);
        if (!is_array($adat)) {
            throw new \Exception(t('A NAV-tól kapott számlalista nem értelmezhető.'));
        }

        $digest = [];
        foreach ($adat as $sor) {
            $szamlaszam = $this->digestErtek($sor, 'invoiceNumber');
            if ($szamlaszam === '' || isset($digest[$szamlaszam])) {
                continue;   // ugyanaz a számla módosítással többször is szerepelhet
            }
            $digest[$szamlaszam] = [
                'szamlaszam' => $szamlaszam,
                'szallito' => $this->digestErtek($sor, 'supplierName'),
                'adoszam' => $this->digestErtek($sor, 'supplierTaxNumber'),
                'kelt' => $this->digestErtek($sor, 'invoiceIssueDate'),
                'netto' => $this->digestErtek($sor, 'invoiceNetAmount'),
                'afa' => $this->digestErtek($sor, 'invoiceVatAmount'),
                'valutanem' => $this->digestErtek($sor, 'currency'),
            ];
        }
        return array_values($digest);
    }

    /**
     * Egy digest-mező értéke. A mezők egyelemű tömbben érkeznek, de üresen is jöhetnek
     * (pl. egyszerűsített számlánál az invoiceNetAmount: {}), ezért nem indexelünk vakon.
     */
    private function digestErtek(array $sor, string $kulcs): string
    {
        return isset($sor[$kulcs][0]) ? trim((string)$sor[$kulcs][0]) : '';
    }

    private function downloadSzamlak(array $szamlaszamok): array
    {
        $eredmeny = [];

        $no = $this->navOnline();
        if ($no->getInboundList($szamlaszamok)) {
            $szamlak = json_decode(base64_decode($no->getResult()), true);
            foreach (is_array($szamlak) ? $szamlak : [] as $adat) {
                if (is_array($adat) && !empty($adat['invoiceNumber'])) {
                    $eredmeny[$adat['invoiceNumber']] = $adat;
                }
            }
        }
        \mkw\store::writelog(json_encode($eredmeny, JSON_PRETTY_PRINT));
        return $eredmeny;
    }

    /**
     * A megadott (szállítói) számlaszámokhoz tartozó, még nem rontott költségszámlák.
     *
     * @return array<string,string> érkeztetett bizonylatszám => saját bizonylatszám
     */
    private function letezoKoltsegszamlak(array $szamlaszamok): array
    {
        if (!$szamlaszamok) {
            return [];
        }
        $biztipus = \mkw\store::getEm()->getRepository(Bizonylattipus::class)->find(self::BIZONYLATTIPUS);
        if (!$biztipus) {
            return [];
        }
        $letezok = [];
        foreach (array_chunk($szamlaszamok, 500) as $koteg) {
            $sorok = \mkw\store::getEm()->createQuery(
                'SELECT b.erbizonylatszam AS ernum, b.id AS bizszam'
                . ' FROM Entities\Bizonylatfej b'
                . ' WHERE b.bizonylattipus=:biztipus AND b.rontott=0 AND b.erbizonylatszam IN (:szamok)'
            )
                ->setParameter('biztipus', $biztipus)
                ->setParameter('szamok', $koteg)
                ->getArrayResult();
            foreach ($sorok as $sor) {
                $letezok[$sor['ernum']] = $sor['bizszam'];
            }
        }
        return $letezok;
    }

    private function darab(array $tetelek, string $statusz): int
    {
        return count(array_filter($tetelek, fn($t) => $t['statusz'] === $statusz));
    }

    private function datum(string $s): ?\DateTime
    {
        $s = trim($s);
        if ($s === '') {
            return null;
        }
        try {
            return new \DateTime($s);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * A NAV kliens példányosítása (hívásonként újat kérünk, mert a hiba/eredmény állapotot
     * magában tárolja).
     */
    protected function navOnline(): \mkwhelpers\NAVOnline
    {
        return new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam());
    }
}
