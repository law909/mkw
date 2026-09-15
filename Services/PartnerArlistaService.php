<?php

namespace Services;

use Entities\Arsav;
use Entities\Partner;
use Entities\PartnerArlistaKedvezmeny;
use Entities\PartnerArlistaSav;
use Entities\Termek;
use Entities\Termekcimketorzs;
use Entities\TermekFa;
use mkwhelpers\FilterDescriptor;
use mkwhelpers\ParameterHandler;

/**
 * A partner sávos árlistája: vásárlási sávok, és bennük termékkategóriánként (termékfa-ág) kedvezmény %.
 */
class PartnerArlistaService
{

    /**
     * A nyomtatott árlista: az árlista kategóriái, alattuk azok a termékek, amelyeknek ez a legszűkebb árlistás
     * kategóriájuk, a partner ársávjának és valutanemének nettó árával és sávonként a kedvezményes árral. Árlistán
     * nem szereplő kategóriájú és ár nélküli termék nem kerül bele.
     *
     * @param int[] $cimkeIds ha nem üres, csak ezek valamelyikével jelölt termékek
     */
    public function getPrintData(Partner $partner, array $cimkeIds = []): array
    {
        $em = \mkw\store::getEm();
        $arlista = $this->getArlista($partner);
        $locale = \mkw\store::translateToLongLocaleName($partner->getBizonylatnyelv() ?: 'hu_hu');
        // a getKedvezmenynelkuliNettoAr is erre az ársávra esik vissza, ha a partnernek nincs sajátja
        $arsavId = \mkw\store::getParameter(\mkw\consts::Arsav);
        $arsav = $partner->getArsav() ?: ($arsavId ? $em->getRepository(Arsav::class)->find($arsavId) : null);

        $groups = [];
        foreach ($arlista['sorok'] as $sor) {
            $groups[$sor['termekfaid']] = [
                'karkod' => $sor['termekfa']->getKarkod(),
                'nev' => $sor['termekfa']->getLocalizedFieldValue('nev', $locale) ?: $sor['termekfa']->getNev(),
                'kedvezmenyek' => $sor['kedvezmenyek'],
                'termekek' => [],
            ];
        }
        if ($groups) {
            $filter = new FilterDescriptor();
            $filter->addFilter(
                ['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'],
                'LIKE',
                array_map(fn($group) => $group['karkod'] . '%', array_values($groups))
            );
            $filter->addFilter('inaktiv', '=', false);
            $filter->addFilter('fuggoben', '=', false);
            if ($cimkeIds) {
                $termekIds = array_column($em->getRepository(Termekcimketorzs::class)->getTermekIdsWithCimke($cimkeIds), 'id');
                $filter->addFilter('id', 'IN', $termekIds ?: [0]);
            }
            /** @var Termek $termek */
            foreach ($em->getRepository(Termek::class)->getAll($filter, ['cikkszam' => 'ASC']) as $termek) {
                $groupId = $this->findGroup($groups, $termek);
                $price = $groupId ? $termek->getKedvezmenynelkuliNettoAr(null, $partner) : 0;
                if ($price <= 0) {
                    continue;
                }
                $bandPrices = [];
                foreach ($arlista['savok'] as $sav) {
                    $kedvezmeny = $groups[$groupId]['kedvezmenyek'][$sav['id']] ?? null;
                    $bandPrices[] = $kedvezmeny === null ? null : $price * (100 - $kedvezmeny) / 100;
                }
                $groups[$groupId]['termekek'][] = [
                    'cikkszam' => $termek->getCikkszam(),
                    'nev' => $termek->getLocalizedFieldValue('nev', $locale) ?: $termek->getNev(),
                    'ar' => $price,
                    'savarak' => $bandPrices,
                ];
            }
        }
        return [
            'locale' => $locale,
            'arsavnev' => (string)$arsav?->getNev(),
            'savok' => $arlista['savok'],
            'csoportok' => array_values(array_filter($groups, fn($group) => $group['termekek'])),
        ];
    }

    /** a termék kategóriái (termekfa1-3) közül a leghosszabb karkodú árlistás ág */
    private function findGroup(array $groups, Termek $termek)
    {
        $found = null;
        $foundLength = -1;
        foreach (['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'] as $field) {
            $karkod = (string)$termek->getFieldValue($field);
            if ($karkod === '') {
                continue;
            }
            foreach ($groups as $termekfaid => $group) {
                if (str_starts_with($karkod, $group['karkod']) && strlen($group['karkod']) > $foundLength) {
                    $found = $termekfaid;
                    $foundLength = strlen($group['karkod']);
                }
            }
        }
        return $found;
    }

    /**
     * A sávok sorrendben, és kategóriánként a sávok kedvezménye (sáv id => %), a fa sorrendjében.
     *
     * @return array{savok: array, sorok: array}
     */
    public function getArlista(Partner $partner): array
    {
        $savok = [];
        $sorok = [];
        /** @var PartnerArlistaSav $sav */
        foreach ($partner->getArlistasavok() as $sav) {
            $savok[] = [
                'id' => $sav->getId(),
                'tol' => $sav->getTol() === null ? '' : $sav->getTol() * 1,
                'ig' => $sav->getIg() === null ? '' : $sav->getIg() * 1,
                'nev' => $sav->getNev(),
            ];
            /** @var PartnerArlistaKedvezmeny $kdv */
            foreach ($sav->getKedvezmenyek() as $kdv) {
                $termekfa = $kdv->getTermekfa();
                if (!isset($sorok[$termekfa->getId()])) {
                    $sorok[$termekfa->getId()] = [
                        'id' => 'r' . $termekfa->getId(),
                        'termekfaid' => $termekfa->getId(),
                        'termekfanev' => $termekfa->getNevWithParent(),
                        'termekfa' => $termekfa,
                        'kedvezmenyek' => [],
                    ];
                }
                $sorok[$termekfa->getId()]['kedvezmenyek'][$sav->getId()] = $kdv->getKedvezmeny() * 1;
            }
        }
        // testvér ágak a fa sorrendje szerint, a különböző szülők egymás után
        uasort($sorok, fn($a, $b) => [$a['termekfa']->getParentId(), $a['termekfa']->getSorrend(), $a['termekfa']->getNev()]
            <=> [$b['termekfa']->getParentId(), $b['termekfa']->getSorrend(), $b['termekfa']->getNev()]);
        return ['savok' => $savok, 'sorok' => array_values($sorok)];
    }

    /**
     * A karbantartó "Árlista" fülének mentése. A fül a teljes árlistát küldi: ami nincs benne, az törlődik. Ha a fül
     * nem volt a formon (arlistaposted), nem nyúl semmihez.
     */
    public function saveFromRequest(Partner $partner, ParameterHandler $params): void
    {
        if (!$params->getBoolRequestParam('arlistaposted')) {
            return;
        }
        $em = \mkw\store::getEm();

        // kategóriánként egy sor: a kétszer felvett ágból az első számít
        $rows = [];
        foreach ($params->getArrayRequestParam('arlistasor') as $sorid) {
            $termekfa = $em->getRepository(TermekFa::class)->find($params->getIntRequestParam('arlistatermekfa_' . $sorid));
            if ($termekfa && !isset($rows[$termekfa->getId()])) {
                $rows[$termekfa->getId()] = ['sor' => $sorid, 'termekfa' => $termekfa];
            }
        }

        $existingSavok = [];
        foreach ($partner->getArlistasavok() as $sav) {
            $existingSavok[$sav->getId()] = $sav;
        }
        $keptSavok = [];
        $sorrend = 0;
        foreach ($params->getArrayRequestParam('arlistasavid') as $savid) {
            $sav = $existingSavok[$savid] ?? null;
            if (!$sav) {
                $sav = new PartnerArlistaSav();
                $partner->addArlistasav($sav);
            }
            $sav->setSorrend(++$sorrend);
            $sav->setTol($this->parseNumber($params->getStringRequestParam('arlistasavtol_' . $savid)) ?? 0);
            $sav->setIg($this->parseNumber($params->getStringRequestParam('arlistasavig_' . $savid)));
            $em->persist($sav);
            $keptSavok[] = $sav;
            $this->saveKedvezmenyek($sav, $savid, $rows, $params);
        }
        foreach ($existingSavok as $sav) {
            if (!in_array($sav, $keptSavok, true)) {
                $partner->removeArlistasav($sav);
                $em->remove($sav);
            }
        }
    }

    private function saveKedvezmenyek(PartnerArlistaSav $sav, $savid, array $rows, ParameterHandler $params): void
    {
        $em = \mkw\store::getEm();
        $existing = [];
        foreach ($sav->getKedvezmenyek() as $kdv) {
            $existing[$kdv->getTermekfaId()] = $kdv;
        }
        foreach ($rows as $termekfaid => $row) {
            $kedvezmeny = $this->parseNumber($params->getStringRequestParam('arlistakedvezmeny_' . $row['sor'] . '_' . $savid));
            if ($kedvezmeny === null) {
                continue;
            }
            $kdv = $existing[$termekfaid] ?? null;
            unset($existing[$termekfaid]);
            if (!$kdv) {
                $kdv = new PartnerArlistaKedvezmeny();
                $kdv->setTermekfa($row['termekfa']);
                $sav->addKedvezmeny($kdv);
            }
            $kdv->setKedvezmeny($kedvezmeny);
            $em->persist($kdv);
        }
        // kiürített cella vagy törölt kategória
        foreach ($existing as $kdv) {
            $sav->removeKedvezmeny($kdv);
            $em->remove($kdv);
        }
    }

    /** tizedesvesszőt és ezres pontot is elfogad: "12,5" → 12.5, "1.000" → 1000 */
    private function parseNumber(string $value): ?float
    {
        $value = str_replace(' ', '', trim($value));
        if (preg_match('/^\d{1,3}(\.\d{3})+$/', $value)) {
            $value = str_replace('.', '', $value);
        }
        $value = str_replace(',', '.', $value);
        return is_numeric($value) ? (float)$value : null;
    }
}
