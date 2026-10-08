<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Csomagolasidoboz;
use Entities\Csomagolasitetel;

/**
 * Csomagolási lista: a bizonylat tételeinek darabjai dobozszám szerint, a dobozok súlya és mérete, és a
 * belőlük összeálló nyomtatási forma. Terv: docs/csomagolasi-lista-terv-20261007.md
 */
class csomagolasilistaController extends \mkwhelpers\Controller
{
    public function view()
    {
        $bizonylat = $this->findBizonylat();
        if (!$bizonylat) {
            return;
        }
        $hozzarendelt = [];
        $dobozok = [];
        foreach ($this->getDobozok($bizonylat) as $doboz) {
            $dobozok[] = [
                'dobozszam' => $doboz->getDobozszam(),
                'nettosuly' => $doboz->getNettosuly(),
                'bruttosuly' => $doboz->getBruttosuly(),
                'szelesseg' => $doboz->getSzelesseg(),
                'magassag' => $doboz->getMagassag(),
                'melyseg' => $doboz->getMelyseg(),
            ];
            /** @var Csomagolasitetel $ct */
            foreach ($doboz->getTetelek() as $ct) {
                $hozzarendelt[$ct->getBizonylattetel()->getId()][$doboz->getDobozszam()] = (float)$ct->getMennyiseg();
            }
        }

        $tetelek = [];
        foreach ($this->getCsomagolandoTetelek($bizonylat) as $tetel) {
            $parok = [];
            foreach ($hozzarendelt[$tetel->getId()] ?? [] as $dobozszam => $db) {
                $parok[] = ['doboz' => $dobozszam, 'db' => $db];
            }
            $tetelek[] = [
                'id' => $tetel->getId(),
                'cikkszam' => $tetel->getDisplayCikkszam(),
                'nev' => $tetel->getTermeknev(),
                'szin' => $this->getSzin($tetel),
                'meret' => $this->getMeret($tetel),
                'mennyiseg' => (float)$tetel->getMennyiseg(),
                'suly' => (float)$tetel->getSuly(),
                'parok' => $parok ?: [['doboz' => '', 'db' => (float)$tetel->getMennyiseg()]],
            ];
        }

        $view = $this->createView('csomagolasilista.tpl');
        $view->setVar('pagetitle', t('Csomagolási lista'));
        $view->setVar('egyed', [
            'id' => $bizonylat->getId(),
            'tipusnev' => $bizonylat->getBizonylattipus()?->getNev() ?: $bizonylat->getBizonylatnev(),
            'partnernev' => $bizonylat->getPartnernev(),
            'keltstr' => $bizonylat->getKeltStr(),
            'listaurl' => $bizonylat->getListaUrl(),
        ]);
        $view->setVar('tetelek', $tetelek);
        $view->setVar('dobozok', $dobozok);
        $view->printTemplateResult();
    }

    /**
     * The whole packing of the document in one go: `tetel[<tetelid>][][doboz|db]` = box number and quantity pairs,
     * `dobozadat[<dobozszam>][<mezo>]` = the box data.
     */
    public function save()
    {
        $bizonylat = $this->findBizonylat();
        if (!$bizonylat) {
            return;
        }
        $dobozadatok = $this->params->getArrayRequestParam('dobozadat');
        $parok = $this->params->getArrayRequestParam('tetel');
        $em = $this->getEm();
        $em->getConnection()->beginTransaction();
        try {
            foreach ($this->getDobozok($bizonylat) as $doboz) {
                $em->remove($doboz);
            }
            $em->flush();

            $dobozok = [];
            foreach ($this->getCsomagolandoTetelek($bizonylat) as $tetel) {
                $mennyiseg = (float)$tetel->getMennyiseg();
                $darabok = [];
                foreach ((array)($parok[$tetel->getId()] ?? []) as $par) {
                    $dobozszam = (int)($par['doboz'] ?? 0);
                    $db = (float)str_replace(',', '.', (string)($par['db'] ?? ''));
                    if ($dobozszam > 0 && $db > 0) {
                        $darabok[$dobozszam] = ($darabok[$dobozszam] ?? 0) + $db;
                    }
                }
                if (array_sum($darabok) > $mennyiseg + 0.0001) {
                    throw new \RuntimeException(sprintf(t('%s: több van dobozba téve, mint a tétel mennyisége.'), $tetel->getDisplayCikkszam()));
                }
                foreach ($darabok as $dobozszam => $db) {
                    if (!isset($dobozok[$dobozszam])) {
                        $dobozok[$dobozszam] = $this->createDoboz($bizonylat, $dobozszam, $dobozadatok[$dobozszam] ?? []);
                    }
                    $ct = new Csomagolasitetel();
                    $ct->setBizonylattetel($tetel);
                    $ct->setMennyiseg($db);
                    $dobozok[$dobozszam]->addTetel($ct);
                    $em->persist($ct);
                }
            }
            $em->flush();
            $em->getConnection()->commit();
        } catch (\RuntimeException $e) {
            $em->getConnection()->rollBack();
            $this->jsonError($e->getMessage(), 422);
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true]);
    }

    public function doPrint()
    {
        $bizonylat = $this->findBizonylat();
        if (!$bizonylat) {
            return;
        }
        $meretek = [];
        $dobozlista = [];
        $tartalom = [];
        $becsomagolt = 0;
        $osszesen = ['nettosuly' => 0, 'bruttosuly' => 0, 'terfogat' => 0];
        foreach ($this->getDobozok($bizonylat) as $doboz) {
            $meret = $this->formatMeret($doboz);
            $meretek[$meret] = ($meretek[$meret] ?? 0) + 1;
            $dobozlista[] = [
                'dobozszam' => $doboz->getDobozszam(),
                'meret' => $meret,
                'nettosuly' => (float)$doboz->getNettosuly(),
                'bruttosuly' => (float)$doboz->getBruttosuly(),
                'terfogat' => $doboz->getTerfogat(),
            ];
            $osszesen['nettosuly'] += (float)$doboz->getNettosuly();
            $osszesen['bruttosuly'] += (float)$doboz->getBruttosuly();
            $osszesen['terfogat'] += $doboz->getTerfogat();
            $sorok = [];
            $dobozdarab = 0;
            /** @var Csomagolasitetel $ct */
            foreach ($doboz->getTetelek() as $ct) {
                $tetel = $ct->getBizonylattetel();
                $dobozdarab += (float)$ct->getMennyiseg();
                $sorok[] = [
                    'dobozszam' => $doboz->getDobozszam(),
                    'cikkszam' => $tetel->getDisplayCikkszam(),
                    'nev' => $tetel->getTermeknev(),
                    'szin' => $this->getSzin($tetel),
                    'meret' => $this->getMeret($tetel),
                    'mennyiseg' => (float)$ct->getMennyiseg(),
                ];
            }
            if ($sorok) {
                $tartalom[] = ['dobozszam' => $doboz->getDobozszam(), 'sorok' => $sorok, 'darab' => $dobozdarab];
            }
            $becsomagolt += $dobozdarab;
        }
        ksort($meretek, SORT_NATURAL);

        $hianyzik = 0;
        foreach ($this->getCsomagolandoTetelek($bizonylat) as $tetel) {
            $hianyzik += (float)$tetel->getMennyiseg();
        }
        $hianyzik -= $becsomagolt;

        $view = $this->createView('csomagolasilistapdf.tpl');
        $view->setVar('egyed', [
            'id' => $bizonylat->getId(),
            'partnernev' => $bizonylat->getPartnernev(),
            'keltstr' => $bizonylat->getKeltStr(),
        ]);
        $view->setVar('meretek', $meretek);
        $view->setVar('dobozlista', $dobozlista);
        $view->setVar('osszesen', $osszesen);
        $view->setVar('tartalom', $tartalom);
        $view->setVar('becsomagolt', $becsomagolt);
        $view->setVar('hianyzik', round($hianyzik, 4));
        $pdf = new \mkw\mkwmpdf($view->getTemplateResult());
        $pdf->getEngine()->SetTitle(t('Csomagolási lista') . ' ' . $bizonylat->getId());
        $pdf->inline(\mkw\store::urlize($bizonylat->getId()) . '-csomagolasilista.pdf');
    }

    private function findBizonylat(): ?Bizonylatfej
    {
        /** @var Bizonylatfej|null $bizonylat */
        $bizonylat = $this->getRepo(Bizonylatfej::class)->find($this->params->getStringRequestParam('id'));
        if (!$bizonylat || !$bizonylat->getBizonylattipus()?->getShowcsomagolasilista()) {
            $this->jsonError(t('A bizonylat nem található, vagy a típusához nincs csomagolási lista.'), 404);
            return null;
        }
        return $bizonylat;
    }

    /** @return Csomagolasidoboz[] */
    private function getDobozok(Bizonylatfej $bizonylat): array
    {
        return $this->getRepo(Csomagolasidoboz::class)->findBy(['bizonylatfej' => $bizonylat], ['dobozszam' => 'ASC']);
    }

    /**
     * The lines that go into boxes: stock-moving products with a positive quantity (no shipping cost, no service).
     *
     * @return Bizonylattetel[]
     */
    private function getCsomagolandoTetelek(Bizonylatfej $bizonylat): array
    {
        $ret = [];
        foreach ($bizonylat->getRendezettTetelek() as $tetel) {
            /** @var Bizonylattetel $tetel */
            if ($tetel->getTermek()?->getMozgat() && (float)$tetel->getMennyiseg() > 0) {
                $ret[] = $tetel;
            }
        }
        return $ret;
    }

    private function getSzin(Bizonylattetel $tetel): string
    {
        $valtozat = $tetel->getTermekvaltozat();
        if (!$valtozat) {
            return '';
        }
        return $valtozat->getSzinNev() ?: (string)$valtozat->getSzin();
    }

    private function getMeret(Bizonylattetel $tetel): string
    {
        $valtozat = $tetel->getTermekvaltozat();
        if (!$valtozat) {
            return '';
        }
        return $valtozat->getMeretNev() ?: (string)$valtozat->getMeret();
    }

    private function createDoboz(Bizonylatfej $bizonylat, int $dobozszam, array $adat): Csomagolasidoboz
    {
        $doboz = new Csomagolasidoboz();
        $doboz->setBizonylatfej($bizonylat);
        $doboz->setDobozszam($dobozszam);
        foreach (['nettosuly', 'bruttosuly', 'szelesseg', 'magassag', 'melyseg'] as $mezo) {
            $ertek = str_replace(',', '.', trim((string)($adat[$mezo] ?? '')));
            $doboz->{'set' . ucfirst($mezo)}(is_numeric($ertek) ? $ertek : null);
        }
        $this->getEm()->persist($doboz);
        return $doboz;
    }

    private function formatMeret(Csomagolasidoboz $doboz): string
    {
        if (!$doboz->getSzelesseg() && !$doboz->getMagassag() && !$doboz->getMelyseg()) {
            return t('nincs megadva');
        }
        return implode(' × ', array_map(fn($m) => rtrim(rtrim(number_format((float)$m, 2, ',', ''), '0'), ','), [
            $doboz->getSzelesseg(), $doboz->getMagassag(), $doboz->getMelyseg()
        ])) . ' cm';
    }
}
