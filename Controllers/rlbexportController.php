<?php

namespace Controllers;


class rlbexportController extends \mkwhelpers\MattableController {

    private $szovegkorul;
    private $datumformat;

    public function view() {
        $view = $this->createView('rlbcsvexport.tpl');

        $view->setVar('utolsoszamla', \mkw\store::getParameter(\mkw\consts::RLBCSVUtolsoSzamlaszam));

        $view->printTemplateResult();
    }

    private function encstr($str) {
        return mb_convert_encoding($str, 'ISO-8859-2', 'UTF8');
    }

    protected function korbeir($mit) {
        switch ($this->szovegkorul) {
            case 1:
                return $mit;
            case 2:
                if (is_string($mit)) {
                    return '"' . $mit . '"';
                }
                return $mit;
            case 3:
                return '"' . $mit . '"';
            default:
                return $mit;
        }
    }

    public function RLBCSVExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $bizrepo = \mkw\store::getEm()->getRepository('Entities\Bizonylatfej');
        $bt = \mkw\store::getEm()->getRepository('Entities\Bizonylattipus')->find('szamla');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', $bt);

        $mar = $this->params->getStringRequestParam('utolsoszamla');
        if ($mar) {
            $filter->addFilter('id', '>', $mar);
        }

        $r = $bizrepo->getAll($filter, array('id' => 'ASC'));

        switch ($this->params->getIntRequestParam('elvalaszto')) {
            case 1:
                $elvalaszto = ';';
                break;
            case 2:
                $elvalaszto = ',';
                break;
            case 3:
                $elvalaszto = "\t";
                break;
            default:
                $elvalaszto = ';';
                break;
        }

        $this->szovegkorul = $this->params->getIntRequestParam('szovegkorul');

        switch ($this->params->getIntRequestParam('datum')) {
            case 1:
                $this->datumformat = \mkw\store::$DateFormat;
                break;
            case 2:
                $this->datumformat = \mkw\store::$SQLDateFormat;
                break;
            case 3:
                $this->datumformat = \mkw\store::$PerDateFormat;
                break;
            case 4:
                $this->datumformat = \mkw\store::$DBFDateFormat;
                break;
            default:
                $this->datumformat = \mkw\store::$DateFormat;
                break;
        }

        /**
        t.FieldByName('kelt').AsDateTime:=StrToDate(ew(st[cikl],1));
        t.FieldByName('datum').AsDateTime:=StrToDate(ew(st[cikl],2));
        t.FieldByName('fizhat').AsDateTime:=StrToDate(ew(st[cikl],3));
        t.FieldByName('bizszam').AsString:=ew(st[cikl],4);
        t.FieldByName('partkod').value:=StrToIntZero(ew(st[cikl],5));
        t.FieldByName('cegnev').AsString:=ew(st[cikl],6);
        t.FieldByName('irszam').AsString:=ew(st[cikl],7);
        t.FieldByName('varos').AsString:=ew(st[cikl],8);
        t.FieldByName('cim').AsString:=ew(st[cikl],9);
        t.FieldByName('szoveg').AsString:=ew(st[cikl],10);
        t.FieldByName('fizmod').AsInteger:=StrToIntZero(ew(st[cikl],11));
        t.FieldByName('afakod1').AsInteger:=StrToIntZero(ew(st[cikl],12));
        t.FieldByName('netto1').AsFloat:=StrToFloat(nu(ew(st[cikl],13)));
        t.FieldByName('afa1').AsFloat:=StrToFloat(nu(ew(st[cikl],14)));
        t.FieldByName('afakod2').AsInteger:=StrToIntZero(ew(st[cikl],15));
        t.FieldByName('netto2').AsFloat:=StrToFloat(nu(ew(st[cikl],16)));
        t.FieldByName('afa2').AsFloat:=StrToFloat(nu(ew(st[cikl],17)));
        t.FieldByName('afakod3').AsInteger:=StrToIntZero(ew(st[cikl],18));
        t.FieldByName('netto3').AsFloat:=StrToFloat(nu(ew(st[cikl],19)));
        t.FieldByName('afa3').AsFloat:=StrToFloat(nu(ew(st[cikl],20)));
        t.FieldByName('afakod4').AsInteger:=StrToIntZero(ew(st[cikl],21));
        t.FieldByName('netto4').AsFloat:=StrToFloat(nu(ew(st[cikl],22)));
        t.FieldByName('afa4').AsFloat:=StrToFloat(nu(ew(st[cikl],23)));
        t.Post;
        end;
         */
        $sor = array(
            'konyvel',
            'kelt',
            'datum',
            'fizhat',
            'bizszam',
            'partkod',
            'cegnev',
            'irszam',
            'varos',
            'cim',
            'szoveg',
            'fizmod',
            'afakod1',
            'netto1',
            'afa1',
            'onyilt1',
            'onyilsz1',
            'afakod2',
            'netto2',
            'afa2',
            'onyilt2',
            'onyilsz2',
            'afakod3',
            'netto3',
            'afa3',
            'onyilt3',
            'onyilsz3',
            'afakod4',
            'netto4',
            'afa4',
            'onyilt4',
            'onyilsz4',
            'kiegy',
            'penzkod',
            'kiegybiz',
            'kiegydat',
            'afadat',
            'adoszam',
            'kadoszam',
            'okod',
            'dnetto1',
            'dafa1',
            'dnetto2',
            'dafa2',
            'dnetto3',
            'dafa3',
            'dnetto4',
            'dafa4',
            'devnem',
            'arfolyam',
            'ebizszam',
            'ekelt',
            'etelj',
            'enetto',
            'eafa'
        );
        echo implode($elvalaszto, $sor) . "\n";

        /** @var \Entities\Bizonylatfej $bizonylat */
        foreach ($r as $bizonylat) {
            $mar = $bizonylat->getId();
            $fm = $bizonylat->getFizmod();
            $aossz = $bizrepo->getAFAOsszesito($bizonylat);
            $sor = array(
                $this->korbeir(0),
                $this->korbeir($bizonylat->getKelt()->format($this->datumformat)),
                $this->korbeir($bizonylat->getTeljesites()->format($this->datumformat)),
                $this->korbeir($bizonylat->getEsedekesseg()->format($this->datumformat)),
                $this->korbeir($bizonylat->getId()),
                $this->korbeir($bizonylat->getPartnerId()),
                $this->korbeir($this->encstr($bizonylat->getPartnernev())),
                $this->korbeir($this->encstr($bizonylat->getPartnerirszam())),
                $this->korbeir($this->encstr($bizonylat->getPartnervaros())),
                $this->korbeir($this->encstr($bizonylat->getPartnerutca())),
                $this->korbeir($this->encstr('Értékesítés árbevétele')),
                $this->korbeir(($fm->getTipus() == 'P' ? 1 : 2))
            );

            $i = 1;
            foreach ($aossz as $ao) {
                $sor[] = $this->korbeir($ao['rlbkod']);
                $sor[] = $this->korbeir(round($ao['netto']));
                $sor[] = $this->korbeir(round($ao['afa']));
                $sor[] = $this->korbeir(0);
                $sor[] = $this->korbeir(0);
                $i++;
                if ($i > 4) {
                    break;
                }
            }
            for ($i; $i <= 4; $i++) {
                $sor[] = $this->korbeir(0);
                $sor[] = $this->korbeir(0);
                $sor[] = $this->korbeir(0);
                $sor[] = $this->korbeir(0);
                $sor[] = $this->korbeir(0);
            }
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir($bizonylat->getTeljesites()->format($this->datumformat));
            $sor[] = $this->korbeir($bizonylat->getPartneradoszam());
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir('');
            $sor[] = $this->korbeir(0);
            $sor[] = $this->korbeir(0);
            echo implode($elvalaszto, $sor) . "\n";
        }
        if ($mar) {
            \mkw\store::setParameter(\mkw\consts::RLBCSVUtolsoSzamlaszam, $mar);
        }
    }
}