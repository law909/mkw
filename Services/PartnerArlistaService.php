<?php

namespace Services;

use Entities\Partner;
use Entities\PartnerArlistaKedvezmeny;
use Entities\PartnerArlistaSav;
use Entities\TermekFa;
use mkwhelpers\ParameterHandler;

/**
 * A partner sávos árlistája: vásárlási sávok, és bennük termékkategóriánként (termékfa-ág) kedvezmény %.
 */
class PartnerArlistaService
{

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
