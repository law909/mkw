<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Termek;
use mkwhelpers\FilterDescriptor;

/**
 * Advance invoices (elolegszamla) and their offsetting on the final invoice.
 *
 * The offset is one NEGATIVE line per VAT rate on the final invoice, carrying the advance's own
 * VAT rate, its payment date and its exchange rate. One line per rate is not cosmetics: an advance
 * spanning several rates cannot be offset by a single line without breaking the NAV
 * summaryByVatRate block.
 *
 * The stock trap: the offset line sits on a szamla, whose type has mozgat=1, and
 * Bizonylattetel::setMozgat() is re-derived by BizonylatfejListener on every flush - so an
 * application-level $tetel->setMozgat(false) is silently overwritten. The only working handle is
 * Termek.mozgat = false on the configured advance product; a negative-quantity line on a
 * stock-moving product would INCREASE stock. Hence buildOffsetLines() refuses to work when the
 * product is unset or still moves stock. There is no silent fallback to DefaultTermek.
 */
class ElolegService
{

    public const BIZTIPUS = 'elolegszamla';

    /**
     * The configured advance product, or null with the reason in $hiba.
     *
     * @param string|null $hiba
     */
    public static function getElolegTermek(&$hiba = null): ?Termek
    {
        $id = \mkw\store::getIntParameter(\mkw\consts::ElolegTermek);
        if (!$id) {
            $hiba = t('Nincs beállítva előleg beszámítás termék (Beállítások / Bizonylatok).');
            return null;
        }
        /** @var Termek $termek */
        $termek = \mkw\store::getEm()->getRepository(Termek::class)->find($id);
        if (!$termek) {
            $hiba = t('A beállított előleg beszámítás termék nem található.');
            return null;
        }
        if ($termek->getMozgat()) {
            // a negative-quantity line on a stock-moving product would INCREASE stock
            $hiba = t('Az előleg beszámítás termék készletet mozgat, így nem használható. Vedd ki rajta a "Készletet mozgat" pipát.');
            return null;
        }
        return $termek;
    }

    /**
     * Advance invoices still offsettable against this final invoice: same partner, same currency,
     * not voided, not reversed, and with a remaining amount.
     *
     * @return array<int, array{id: string, keltstr: string, teljesitesstr: string, egyenleg: float,
     *                          netto: float, brutto: float}>
     */
    public static function getOffsettableAdvances(Bizonylatfej $szamla): array
    {
        if (!$szamla->getPartnerId()) {
            return [];
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', self::BIZTIPUS);
        $filter->addFilter('partner', '=', $szamla->getPartnerId());
        $filter->addFilter('rontott', '=', false);
        $filter->addFilter('stornozott', '=', false);
        // a differently denominated advance is not offered: at what rate would we convert it
        if ($szamla->getValutanemId()) {
            $filter->addFilter('valutanem', '=', $szamla->getValutanemId());
        }
        $repo = \mkw\store::getEm()->getRepository(Bizonylatfej::class);
        $ret = [];
        /** @var Bizonylatfej $eloleg */
        foreach ($repo->getAll($filter, ['kelt' => 'DESC']) as $eloleg) {
            if ($eloleg->getId() === $szamla->getId()) {
                continue;
            }
            $maradek = self::getRemainingByAfa($eloleg, $szamla->getId());
            $netto = 0;
            $brutto = 0;
            foreach ($maradek as $sor) {
                $netto += $sor['netto'];
                $brutto += $sor['brutto'];
            }
            if (round($netto, 2) == 0 && round($brutto, 2) == 0) {
                continue;
            }
            $ret[] = [
                'id' => $eloleg->getId(),
                'keltstr' => $eloleg->getKeltStr(),
                'teljesitesstr' => $eloleg->getTeljesitesStr(),
                'egyenleg' => $eloleg->getEgyenleg(),
                'netto' => $netto,
                'brutto' => $brutto,
            ];
        }
        return $ret;
    }

    /**
     * What is left of the advance, per VAT rate: its own VAT summary plus everything already
     * offset against it. The offset lines are negative, so this is an addition.
     *
     * @param string|null $kivevebiz this document's own offset lines do not count (re-selection)
     *
     * @return array<int, array{afaid: int, afakulcs: float, afanev: string, netto: float,
     *                          afa: float, brutto: float}>
     */
    public static function getRemainingByAfa(Bizonylatfej $eloleg, $kivevebiz = null): array
    {
        $ret = [];
        $osszesito = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->getAFAOsszesito($eloleg);
        foreach ($osszesito as $afaid => $sor) {
            $ret[$afaid] = [
                'afaid' => $afaid,
                'afakulcs' => $sor['afakulcs'],
                'afanev' => $sor['caption'],
                'netto' => $sor['netto'],
                'afa' => $sor['afa'],
                'brutto' => $sor['brutto'],
            ];
        }
        foreach (self::getOffsetSums($eloleg, $kivevebiz) as $afaid => $sor) {
            if (!array_key_exists($afaid, $ret)) {
                // offset at a rate the advance itself does not carry - still show the remainder
                $ret[$afaid] = [
                    'afaid' => $afaid,
                    'afakulcs' => $sor['afakulcs'],
                    'afanev' => $sor['afanev'],
                    'netto' => 0,
                    'afa' => 0,
                    'brutto' => 0,
                ];
            }
            $ret[$afaid]['netto'] += $sor['netto'];
            $ret[$afaid]['afa'] += $sor['afa'];
            $ret[$afaid]['brutto'] += $sor['brutto'];
        }
        return $ret;
    }

    /**
     * Amounts already offset against this advance, per VAT rate (negative). Lines of voided
     * documents do not count; a reversal's mirror lines do, and they cancel the original out -
     * that is what makes the advance offsettable again after the final invoice is reversed.
     *
     * @return array<int, array{afakulcs: float, afanev: string, netto: float, afa: float, brutto: float}>
     */
    private static function getOffsetSums(Bizonylatfej $eloleg, $kivevebiz = null): array
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('bt.elolegbizonylat_id', '=', $eloleg->getId());
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        $filter->addSql('((bf.rontott = 0) OR (bf.rontott IS NULL))');
        if ($kivevebiz) {
            $filter->addFilter('bf.id', '<>', $kivevebiz);
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('afa_id', 'afa_id');
        $rsm->addScalarResult('afakulcs', 'afakulcs');
        $rsm->addScalarResult('afanev', 'afanev');
        $rsm->addScalarResult('netto', 'netto');
        $rsm->addScalarResult('afaertek', 'afaertek');
        $rsm->addScalarResult('brutto', 'brutto');

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bt.afa_id AS afa_id, MAX(bt.afakulcs) AS afakulcs, MAX(bt.afanev) AS afanev,'
            . ' SUM(bt.netto) AS netto, SUM(bt.afaertek) AS afaertek, SUM(bt.brutto) AS brutto'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . $filter->getFilterString()
            . ' GROUP BY bt.afa_id',
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());
        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $ret[(int)$sor['afa_id']] = [
                'afakulcs' => $sor['afakulcs'],
                'afanev' => $sor['afanev'],
                'netto' => $sor['netto'],
                'afa' => $sor['afaertek'],
                'brutto' => $sor['brutto'],
            ];
        }
        return $ret;
    }

    /**
     * The offset lines for one advance: one negative line per VAT rate that still has a remainder.
     * The lines are NOT persisted - they are handed to the editor, which posts them back like any
     * other line, and setFields() re-derives everything server side.
     *
     * quantity -1 with a positive unit price: checkTetelOsszegHibak() is proportional so both
     * signings are mathematically fine, but the repo's convention is to negate the quantity (see
     * the storno branch of bizonylattetelController::loadVars()) and NAV expects
     * <quantity>-1</quantity> with a positive <unitPrice>.
     *
     * @return array<int, array<string, mixed>> line arrays in loadVars() shape
     */
    public static function buildOffsetLines(Bizonylatfej $szamla, ?Bizonylatfej $eloleg, &$hiba = null): array
    {
        $termek = self::getElolegTermek($hiba);
        if (!$termek) {
            return [];
        }
        if (!self::isOffsettable($szamla, $eloleg, $hiba)) {
            return [];
        }
        $ret = [];
        $cikl = 1;
        foreach (self::getRemainingByAfa($eloleg, $szamla->getId()) as $afaid => $sor) {
            if (round($sor['netto'], 2) == 0 && round($sor['brutto'], 2) == 0) {
                continue;
            }
            $ret[] = self::buildLine($szamla, $eloleg, $termek, $afaid, $sor, $cikl);
            $cikl++;
        }
        if (!$ret) {
            $hiba = t('Ezen az előlegszámlán nincs beszámítható összeg.');
        }
        return $ret;
    }

    /**
     * The header-only inheritance from an order: one single advance line, NOT the order's goods.
     * The amount is the order's gross total; the recorder overwrites it (typically with a
     * percentage of it).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function buildInheritedLines(Bizonylatfej $rendeles): array
    {
        $termek = self::getElolegTermek($hiba);
        if (!$termek) {
            return [];
        }
        $tetelek = $rendeles->getBizonylattetelek();
        if (!count($tetelek)) {
            return [];
        }
        /** @var Bizonylattetel $elso */
        $elso = $tetelek[0];
        $sor = [
            'afaid' => $elso->getAfaId(),
            'afakulcs' => $elso->getAfakulcs(),
            'afanev' => $elso->getAfanev(),
            'netto' => $rendeles->getNetto(),
            'afa' => $rendeles->getAfa(),
            'brutto' => $rendeles->getBrutto(),
        ];
        $tetel = self::buildLine($rendeles, null, $termek, $elso->getAfaId(), $sor, 1);
        $tetel['mennyiseg'] = 1;
        $tetel['termeknev'] = t('Előleg') . ': ' . $rendeles->getId();
        $tetel['netto'] = $sor['netto'];
        $tetel['afa'] = $sor['afa'];
        $tetel['brutto'] = $sor['brutto'];
        return [$tetel];
    }

    /**
     * The offset advances of a document, for the printout: one row per advance invoice with the
     * offset net/VAT/gross. Empty on every document without an offset, so the summary block adds
     * nothing to the existing output.
     *
     * @return array<int, array{id: string, datumstr: string, netto: float, afa: float, brutto: float}>
     */
    public static function getPrintSummary(Bizonylatfej $biz): array
    {
        $ret = [];
        /** @var Bizonylattetel $bt */
        foreach ($biz->getBizonylattetelek() as $bt) {
            $szam = $bt->getElolegbizonylatszam();
            if (!$szam) {
                continue;
            }
            if (!array_key_exists($szam, $ret)) {
                $ret[$szam] = [
                    'id' => $szam,
                    'datumstr' => $bt->getElolegfizetesdatumStr(),
                    'netto' => 0,
                    'afa' => 0,
                    'brutto' => 0,
                ];
            }
            $ret[$szam]['netto'] += $bt->getNetto();
            $ret[$szam]['afa'] += $bt->getAfaertek();
            $ret[$szam]['brutto'] += $bt->getBrutto();
        }
        return array_values($ret);
    }

    /**
     * Whether this advance may still be offset into this invoice. The POST is never trusted:
     * setFields() runs the same check before it sets the relation.
     */
    public static function isOffsettable(Bizonylatfej $szamla, ?Bizonylatfej $eloleg, &$hiba = null): bool
    {
        if (!$eloleg) {
            $hiba = t('Az előlegszámla nem található.');
            return false;
        }
        if ($eloleg->getBizonylattipusId() !== self::BIZTIPUS) {
            $hiba = t('A hivatkozott bizonylat nem előlegszámla.');
            return false;
        }
        if ($eloleg->getRontott() || $eloleg->getStornozott()) {
            $hiba = t('A hivatkozott előlegszámla rontott vagy stornózott.');
            return false;
        }
        if ($eloleg->getPartnerId() != $szamla->getPartnerId()) {
            $hiba = t('Az előlegszámla más partneré.');
            return false;
        }
        if ($eloleg->getValutanemId() != $szamla->getValutanemId()) {
            $hiba = t('Az előlegszámla más valutanemű.');
            return false;
        }
        return true;
    }

    /** The VTSZ of the advance's first line, when the configured product carries none. */
    private static function getElolegVtszId(?Bizonylatfej $eloleg)
    {
        if (!$eloleg) {
            return null;
        }
        /** @var Bizonylattetel $bt */
        foreach ($eloleg->getBizonylattetelek() as $bt) {
            if ($bt->getVtszId()) {
                return $bt->getVtszId();
            }
        }
        return null;
    }

    /**
     * One offset line. The two exchange rates differ on purpose: the line check requires the final
     * invoice's rate on the HUF fields, while advanceExchangeRate means the advance's - which is
     * exactly why NAV asks for a separate field.
     */
    private static function buildLine(
        Bizonylatfej $szamla,
        ?Bizonylatfej $eloleg,
        Termek $termek,
        $afaid,
        array $sor,
        int $cikl
    ): array {
        $ctrl = new \Controllers\bizonylattetelController();
        $tetel = new Bizonylattetel();
        \mkw\store::getEm()->detach($tetel);
        // Bizonylattetel::setBizonylatfej() also puts the line into the header's collection, so this
        // WORKING COPY is taken back out below (removeBizonylatfej). Without that, merely opening the
        // selector would put the line on the saved invoice at the next flush.
        $tetel->setBizonylatfej($szamla);
        $tetel->setTermek($termek);
        $tetel->setAfa($afaid);
        // the VTSZ is required on the form: prefer the product's own, fall back to the advance's,
        // so a service product without a VTSZ does not leave an empty required field on every
        // offset line
        $tetel->setVtsz($termek->getVtszId() ?: self::getElolegVtszId($eloleg));
        $tetel->setMennyiseg(-1);
        $tetel->setNettoegysar($sor['netto']);
        $tetel->setBruttoegysar($sor['brutto']);
        $tetel->setEnettoegysar($sor['netto']);
        $tetel->setEbruttoegysar($sor['brutto']);
        $tetel->setKedvezmeny(0);
        $tetel->setArfolyam($szamla->getArfolyam());
        // The HUF fields too, with the same helper setFields() uses - calc() only multiplies the
        // HUF unit prices by the quantity, so without these the whole HUF side of the line stays
        // zero and the document's HUF totals come out short on a multivaluta deployment.
        $arak = $ctrl->calcAr(
            $afaid,
            $tetel->getArfolyam(),
            $tetel->getNettoegysar(),
            $tetel->getEnettoegysar(),
            $tetel->getMennyiseg()
        );
        $tetel->setNettoegysarhuf($arak['nettoegysarhuf']);
        $tetel->setBruttoegysarhuf($arak['bruttoegysarhuf']);
        $tetel->setEnettoegysarhuf($arak['enettoegysarhuf']);
        $tetel->setEbruttoegysarhuf($arak['ebruttoegysarhuf']);
        if ($eloleg) {
            $tetel->setElolegbizonylat($eloleg);
            $tetel->setElolegfizetesdatum($eloleg->getTeljesites());
            $tetel->setElolegarfolyam($eloleg->getArfolyam());
            // the reference goes into the line NAME: that is what NAV gets as lineDescription and
            // what all 41 print templates already write out - no template change needed
            $tetel->setTermeknev(
                t('Előleg beszámítás') . ': ' . $eloleg->getId()
                . ($eloleg->getTeljesitesStr() ? ' (' . $eloleg->getTeljesitesStr() . ')' : '')
            );
        }
        $tetel->calc();

        $x = $ctrl->loadVars($tetel, true);
        $tetel->removeBizonylatfej();
        $x['id'] = \mkw\store::createUID($cikl);
        $x['oper'] = 'add';
        // the relation ids too, so the row array is usable on its own
        $x['afaid'] = $tetel->getAfaId();
        $x['vtszid'] = $tetel->getVtszId();
        $x['meid'] = $tetel->getMekodId();
        return $x;
    }
}
