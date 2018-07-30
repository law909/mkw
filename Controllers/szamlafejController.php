<?php

namespace Controllers;

class SzamlafejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'szamla';
        $this->setPageTitle('Számla');
        $this->setPluralPageTitle('Számlák');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id, $stornotip) {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['teljesitesstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['reportfile'] = '';
                $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->biztipus));
                switch ($source) {
                    case 'megrendeles':
                        $egyed['megjegyzes'] = \mkw\store::translate('Rendelés', $record->getBizonylatnyelv()) . ': ' . $id;
                        $arf = $this->getRepo('Entities\Arfolyam')->getActualArfolyam($egyed['valutanem'], new \DateTime(\mkw\store::convDate($egyed['teljesitesstr'])));
                        if ($arf) {
                            $egyed['arfolyam'] = $arf->getArfolyam();
                        }
                        break;
                    case 'szallito':
                        $egyed['megjegyzes'] = \mkw\store::translate('Szállítólevél', $record->getBizonylatnyelv()) . ': ' . $id;
                        break;
                }
                $ttk = array();
                $cikl = 1;
                foreach($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = \mkw\store::createUID($cikl);
                    $tetel['oper'] = 'inherit';
                    if ($source == 'megrendeles') {
                        $tetel['nettoegysarhuf'] = $tetel['nettoegysar'] * $egyed['arfolyam'];
                        $tetel['bruttoegysarhuf'] = $tetel['bruttoegysar'] * $egyed['arfolyam'];
                        $tetel['nettohuf'] = $tetel['netto'] * $egyed['arfolyam'];
                        $tetel['bruttohuf'] = $tetel['brutto'] * $egyed['arfolyam'];
                        $tetel['afahuf'] = $tetel['afa'] * $egyed['arfolyam'];
                    }
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
            case 'storno':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $egyed['stornotip'] = $stornotip;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
//                $egyed['teljesitesstr'] = $kelt;
//                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                switch (\mkw\store::getTheme()) {
                    case 'mkwcansas':
                        $egyed['megjegyzes'] = $id . ' stornó bizonylata. Stornózás oka:';
                        break;
                    default:
                        $egyed['megjegyzes'] = $id . ' stornó bizonylata';
                }
                $ttk = array();
                $cikl = 1;
                foreach($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = \mkw\store::createUID($cikl);
                    $tetel['oper'] = 'storno';
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
        }
        return $egyed;
    }

    public function navonline() {
        $id = $this->params->getStringRequestParam('id', '');
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo()->find($id);
        if ($biz) {
            $xml = $biz->toNAVOnlineXML();
            header("Content-type: application/xml");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $xml;
        }
    }

}
