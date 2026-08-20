<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class FolyoszamlaRepository extends \mkwhelpers\Repository
{

    /**
     * A stornó bizonylat sorai a SZÜLŐJE csoportjában számítanak: a stornó a szülő bizonylat
     * követelését szünteti meg, tehát a kettő egy tételként nullázza ki egymást. E nélkül egy
     * kifizetetlen, majd stornózott számla a kintlevőségen, a stornója pedig a tartozáson állna.
     */
    private const STORNOJOIN = ' LEFT JOIN bizonylatfej sbf ON (sbf.id = f.hivatkozottbizonylat) AND (sbf.storno = 1)';

    /** A csoport bizonylatszáma: stornónál a szülőé. */
    private const CSOPORTBIZ = 'IFNULL(sbf.parbizonylatfej_id, f.hivatkozottbizonylat)';

    /**
     * A csoport esedékessége. Hivatkozás nélküli befizetésnél maga a befizetés napja; a bizonylat
     * sorainál a saját esedékesség (részletfizetésnél soronként más); a bizonylatra könyvelt
     * pénzmozgásnál pedig annak a részletnek az esedékessége, amelyikhez tartozik – ha a
     * hivatkozott dátuma egyikkel sem egyezik, akkor a legkorábbié. E nélkül egy elgépelt
     * dátumú befizetés önálló, hamis nyitott tételként jelenne meg.
     */
    private const CSOPORTDATUM = 'IF(IFNULL(f.hivatkozottbizonylat, "") = "", f.datum,'
        . ' IF(f.bizonylatfej_id IS NOT NULL, f.hivatkozottdatum,'
        . '  IFNULL((SELECT MIN(fi.hivatkozottdatum) FROM folyoszamla fi'
        . '          WHERE (fi.bizonylatfej_id = ' . self::CSOPORTBIZ . ') AND (fi.rontott = 0)'
        . '            AND (fi.hivatkozottdatum <=> f.hivatkozottdatum)),'
        . '   IFNULL((SELECT MIN(fj.hivatkozottdatum) FROM folyoszamla fj'
        . '           WHERE (fj.bizonylatfej_id = ' . self::CSOPORTBIZ . ') AND (fj.rontott = 0)),'
        . '    f.hivatkozottdatum))))';


    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Folyoszamla::class);
    }

    public function getAllByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT _xx, f, bankfej, banktetel, penztarfej, penztartetel'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.bankbizonylatfej bankfej'
            . ' LEFT JOIN _xx.bankbizonylattetel banktetel'
            . ' LEFT JOIN _xx.penztarbizonylatfej penztarfej'
            . ' LEFT JOIN _xx.penztarbizonylattetel penztartetel'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getBefizetesByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);
        $filter->addSql('_xx.bizonylatfej IS NULL');

        $q = $this->_em->createQuery(
            'SELECT _xx, f, bankfej, banktetel, penztarfej, penztartetel'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.bankbizonylatfej bankfej'
            . ' LEFT JOIN _xx.bankbizonylattetel banktetel'
            . ' LEFT JOIN _xx.penztarbizonylatfej penztarfej'
            . ' LEFT JOIN _xx.penztarbizonylattetel penztartetel'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    /**
     * @param string|array $bizszam egy bizonylatszám, vagy több (a bizonylat és a stornói együtt)
     */
    public function getSumByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', is_array($bizszam) ? 'IN' : '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT SUM(_xx.brutto * _xx.irany)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getCountByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);
        $filter->addSql('_xx.bizonylatfej IS NULL');

        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /**
     * @param string|array $bizszam egy bizonylatszám, vagy több (a bizonylat és a stornói együtt)
     */
    public function getSumByHivatkozottBizonylatDatum($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', is_array($bizszam) ? 'IN' : '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT _xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottdatum'
            . ' ORDER BY _xx.hivatkozottdatum'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    /**
     * A partner nyitott tételei bizonylatonként. Az oldalt (kintlevőség / tartozás) a csoport
     * NETTÓ egyenlegének előjele dönti el, nem a soronkénti irány: egy negatív összegű bizonylat
     * (stornó, jóváíró) és a hozzá tartozó pénzmozgás így egy csoportba kerül, és kinullázza
     * egymást. A stornó és stornózott sorok bent maradnak – csak a rontott esik ki.
     *
     * @param mixed $partnerid
     * @param int $irany 1: kintlevőség (a partner tartozik), -1: tartozás (mi tartozunk)
     */
    public function getSumByPartner($partnerid, $irany)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partnerid)
            ->addFilter('rontott', '=', false)
            ->addSql('((_xx.bizonylatfej IS NULL) OR (_xx.bizonylatfej=_xx.hivatkozottbizonylat))');

        $q = $this->_em->createQuery(
            'SELECT _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg,'
            . ' fm.nev AS fizmodnev, IDENTITY(sbf.parbizonylatfej) AS stornoszulo'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod fm'
            . ' LEFT JOIN Entities\Bizonylatfej sbf WITH (sbf.id = _xx.hivatkozottbizonylat) AND (sbf.storno = true)'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,fm.nev,sbf.id'
            . ' HAVING egyenleg<>0'
            . ' ORDER BY _xx.hivatkozottdatum'
        );
        $q->setParameters($this->getQueryParameters($filter));

        // A stornó bizonylat a szülője csoportjába olvad, az összevonás UTÁN dől el, melyik
        // oldalra tartozik – e nélkül a pár két külön sorként, két külön listán jelenne meg.
        $csoportok = [];
        foreach ($q->getResult() as $sor) {
            $bizszam = $sor['stornoszulo'] ?: $sor['hivatkozottbizonylat'];
            $kulcs = $bizszam . '|' . ($sor['hivatkozottdatum'] ? $sor['hivatkozottdatum']->format('Y-m-d') : '');
            if (!isset($csoportok[$kulcs])) {
                $csoportok[$kulcs] = [
                    'hivatkozottbizonylat' => $bizszam,
                    'hivatkozottdatum' => $sor['hivatkozottdatum'],
                    'fizmodnev' => $sor['fizmodnev'],
                    'egyenleg' => 0,
                ];
            }
            $csoportok[$kulcs]['egyenleg'] += $sor['egyenleg'] * 1;
        }

        $result = [];
        foreach ($csoportok as $csoport) {
            $egyenleg = $csoport['egyenleg'];
            if (abs($egyenleg) < 0.005) {
                continue;
            }
            if ((($irany === 1) && ($egyenleg < 0)) || (($irany === -1) && ($egyenleg > 0))) {
                continue;
            }
            $result[] = $csoport;
        }
        return $result;
    }

    /**
     * Valutanemenkénti kintlevőség a főoldali dobozoknak. A partnercímke szűrő miatt ugyanez a
     * metódus szolgálja ki a szűretlen és a spanyol dobozt is.
     */
    public function getKintlevosegByValutanem($cimkek = null, $csaklejart = false)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $sql = 'SELECT egyen.valutanemnev AS nev,SUM(egyenleg) AS egyenleg FROM ('
            . $this->getEgyenlegSql($cimkek) . ') AS egyen'
            . ' WHERE (egyen.egyenleg>0)'
            . ($csaklejart ? ' AND (egyen.hivatkozottdatum<CURDATE())' : '')
            . ' GROUP BY egyen.valutanemnev';
        $q = $this->_em->createNativeQuery($sql, $rsm);
        return $q->getScalarResult();
    }

    public function getLejartKintlevosegByValutanem($cimkek = null)
    {
        return $this->getKintlevosegByValutanem($cimkek, true);
    }

    /**
     * A pénzügyi lista sorai: bizonylatonként (részletfizetésnél esedékességenként) a nettó
     * egyenleg, a megjelenítéshez kellő bizonylat- és partneradatokkal. A szűrő a csoport (`e.`)
     * és a bizonylat (`gbf.`) mezőire hivatkozhat; a bizonylat nélküli csoportoknál `gbf` NULL.
     */
    public function getEgyenlegSorok($filter = null, $befdatum = null, $order = '')
    {
        $rsm = new ResultSetMapping();
        foreach ([
            'bizonylatfej_id', 'partner_id', 'nev', 'telefon', 'mobil', 'email', 'irszam', 'varos', 'utca',
            'kelt', 'teljesites', 'esedekesseg', 'datum', 'hivatkozottdatum', 'brutto', 'tartozas',
            'valutanemnev', 'fizmodnev', 'felhasznalonev',
        ] as $mezo) {
            $rsm->addScalarResult($mezo, $mezo);
        }

        $sql = 'SELECT IFNULL(NULLIF(e.hivatkozottbizonylat, ""), e.penzbizonylat) AS bizonylatfej_id, e.partner_id,'
            . ' IFNULL(gbf.fizmodnev, e.fizmodnev) AS fizmodnev, gbf.felhasznalonev,'
            . ' p.nev, p.telefon, p.mobil, p.email, p.irszam, p.varos, p.utca,'
            . ' IFNULL(gbf.kelt, e.datum) AS kelt, IFNULL(gbf.teljesites, e.datum) AS teljesites,'
            . ' IFNULL(gbf.esedekesseg, e.datum) AS esedekesseg,'
            . ' e.datum, e.hivatkozottdatum, e.brutto, e.egyenleg AS tartozas, e.valutanemnev'
            . ' FROM (' . $this->getEgyenlegSql(null, $befdatum) . ') e'
            . ' LEFT JOIN bizonylatfej gbf ON (gbf.id = e.hivatkozottbizonylat)'
            . ' LEFT JOIN partner p ON (p.id = e.partner_id)'
            . ($filter ? $filter->getFilterString('', 'par') : '')
            . ' ' . $order;

        $q = $this->_em->createNativeQuery($sql, $rsm);
        $params = $filter ? $filter->getQueryParameters('par') : [];
        if ($befdatum) {
            $params['befdatum'] = $befdatum;
        }
        $q->setParameters($params);
        return $q->getScalarResult();
    }

    /**
     * Bizonylatonkénti (és esedékességenkénti) nettó egyenleg – a kintlevőség aggregátumok közös
     * belső lekérdezése. Csak a rontott sorok maradnak ki; hogy egy csoport kintlevőség-e vagy
     * tartozás, azt a nettó egyenleg előjele dönti el, nem a soronkénti irány.
     *
     * @param array|null $cimkek partnercímke szűrő
     */
    private function getEgyenlegSql($cimkek = null, $befdatum = null)
    {
        $join = '';
        if ($cimkek) {
            $join = ' JOIN partner_cimkek pc ON (f.partner_id=pc.partner_id) AND (pc.cimketorzs_id IN ('
                . \mkw\store::getCommaList($cimkek) . '))';
        }
        // A "befizetéseket eddig" határ a csoport saját bizonylatának a sorára nem vonatkozik (azt az
        // időszak szűri), minden másra – befizetésre és a stornó soraira – igen.
        $befszures = $befdatum
            ? ' AND ((f.bizonylatfej_id = ' . self::CSOPORTBIZ . ') OR (f.datum <= :befdatum))'
            : '';
        return ' SELECT IFNULL(gbf.valutanemnev, fv.nev) AS valutanemnev,'
            . ' ' . self::CSOPORTBIZ . ' AS hivatkozottbizonylat,'
            . ' ' . self::CSOPORTDATUM . ' AS hivatkozottdatum,'
            . ' IFNULL(MAX(gbf.partner_id), MAX(f.partner_id)) AS partner_id,'
            . ' MAX(f.datum) AS datum,'
            . ' MAX(fm.nev) AS fizmodnev,'
            // bizonylat nélküli csoportnál a bank/pénztár bizonylat száma azonosítja a sort
            . ' COALESCE(MAX(f.bankbizonylatfej_id), MAX(f.penztarbizonylatfej_id)) AS penzbizonylat,'
            // a csoport bizonylatának saját összege (Fizetendő oszlop); a stornójáé már nem
            . ' SUM(IF(f.bizonylatfej_id = ' . self::CSOPORTBIZ . ', f.brutto*f.irany, 0)) AS brutto,'
            . ' SUM(f.brutto*f.irany) AS egyenleg'
            . ' FROM folyoszamla f'
            . $join
            . self::STORNOJOIN
            // A valutanem a bizonylaté – a kintlevőség lista is aszerint összesít. A folyószámla
            // soré csak ott számít, ahol nincs bizonylat (hivatkozás nélküli befizetés); ha a
            // kettő eltér, azt a folyószámla ellenőrzés riport jelzi.
            . ' LEFT JOIN bizonylatfej gbf ON (gbf.id = ' . self::CSOPORTBIZ . ')'
            . ' LEFT JOIN valutanem fv ON (fv.id = f.valutanem_id)'
            . ' LEFT JOIN fizmod fm ON (fm.id = f.fizmod_id)'
            . ' WHERE (f.rontott=0) AND ((f.bizonylatfej_id IS NULL) OR (f.bizonylatfej_id=f.hivatkozottbizonylat))'
            . $befszures
            . ' GROUP BY IFNULL(gbf.valutanemnev, fv.nev), ' . self::CSOPORTBIZ . ', ' . self::CSOPORTDATUM . ','
            // a semmihez nem kötött befizetések partnerenként külön tételek – egy csoportba téve
            // az egyik partner kifizetése a másikét oltaná ki
            . ' IF(IFNULL(f.hivatkozottbizonylat, "") = "", f.partner_id, NULL)';
    }

    public function getFakeKintlevosegByValutanem($cimkek = null)
    {
        $filter = new FilterDescriptor();

        if ($cimkek) {
            $filter->addJoin('JOIN partner_cimkek pc ON (bf.partner_id=pc.partner_id) AND (pc.cimketorzs_id IN (' . \mkw\store::getCommaList($cimkek) . '))');
        }

        $filter
            ->addFilter('bf.irany', '<', 0)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.storno', '=', false)
            ->addFilter('bf.stornozott', '=', false)
            ->addFilter('bf.fakekintlevoseg', '=', true)
            ->addFilter('bf.fakekifizetve', '=', false);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $q = $this->_em->createNativeQuery(
            'SELECT bf.valutanemnev AS nev, SUM(bf.brutto) AS egyenleg'
            . ' FROM bizonylatfej bf'
            . $filter->getFilterString('', 'par')
            . ' GROUP BY bf.valutanemnev'
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters('par'));
        return $q->getScalarResult();
    }

}