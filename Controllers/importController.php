<?php
namespace Controllers;

use Entities\TermekRecept;
use Symfony\Component\DomCrawler\Crawler;

class importController extends \mkwhelpers\Controller {

    private $settings;

    public function __construct($params) {
        parent::__construct($params);
        $this->settings = array(
            'quality' => 80,
            'sizes' => array('100' => '100x100', '150' => '150x150', '250' => '250x250', '1000' => '1000x800')
        );
    }

    private function toutf($mit) {
        return mb_convert_encoding($mit, 'UTF8', 'ISO-8859-2');
    }

    private function n($mit) {
        return ord($mit) - 97;
    }

    private function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true) {
        $a = array_map(
            function ($line) use ($delimiter, $trim_fields) {
                return array_map(
                    function ($field) {
                        return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                    },
                    $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
                );
            },
            preg_split(
                $skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s',
                preg_replace_callback(
                    '/"(.*?)"/s',
                    function ($field) {
                        return urlencode(utf8_encode($field[1]));
                    },
                    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
                )
            )
        );
        return $a[0];
    }

    public function view() {
        $view = $this->createView('imports.tpl');

        $view->setVar('pagetitle', t('Importok'));

        $termekfa = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find(\mkw\store::getParameter(\mkw\consts::ImportNewKatId));
        if ($termekfa) {
            $view->setVar('termekfa', array(
                'id' => $termekfa->getId(),
                'caption' => $termekfa->getNev()
            ));
        }
        else {
            $view->setVar('termekfa', array(
                'id' => null,
                'caption' => t('Ebbe a kategóriába kerüljenek a termékek')
            ));
        }

        $view->printTemplateResult(false);
    }

    public function repairFuggoTermek($gyartoid) {
        $parentid = \mkw\store::getParameter(\mkw\consts::ImportNewKatId);
        $parent = $this->getRepo('Entities\TermekFa')->find($parentid);

        $conn = \mkw\store::getEm()->getConnection();
        if ($parent) {
            $st2 = $conn->prepare('UPDATE termek SET inaktiv=0, fuggoben=0 WHERE gyarto_id=' . $gyartoid . ' and termekfa1karkod not like \'' . $parent->getKarkod() . '%\'');
            $st2->execute();
        }
    }

    public function createKategoria($nev, $parentid) {
        $me = \mkw\store::getEm()->getRepository('Entities\TermekFa')->findBy(array('nev' => $nev, 'parent' => $parentid));

        if ($nev) {
            if (!$me || $me[0]->getParentId() !== $parentid) {
                $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                $me = new \Entities\TermekFa();
                $me->setNev($nev);
                $me->setParent($parent);
                $me->setMenu1lathato(false);
                $me->setMenu2lathato(false);
                $me->setMenu3lathato(false);
                $me->setMenu4lathato(false);
                \mkw\store::getEm()->persist($me);
                \mkw\store::getEm()->flush();
            }
            else {
                $me = $me[0];
            }
            return $me;
        }
        return null;
    }

    public function getKategoriaByIdegenkod($ik) {
        $me = \mkw\store::getEm()->getRepository('Entities\TermekFa')->findBy(array('idegenkod' => $ik));
        if (!$me) {
            $me = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find(1);
        }
        return $me[0];
    }

    public function createTermekValtozatAdatTipus($nev) {
        $res = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->findByNev($nev);
        if (!$res) {
            $res = new \Entities\TermekValtozatAdatTipus();
            $res->setNev($nev);
            \mkw\store::getEm()->persist($res);
            \mkw\store::getEm()->flush();
            return $res;
        }
        return $res[0];
    }

    public function createVtsz($szam, $afa) {
        $res = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam($szam);
        if (!$res) {
            $res = new \Entities\Vtsz();
            $res->setSzam($szam);
            $res->setAfa($afa);
            \mkw\store::getEm()->persist($res);
            \mkw\store::getEm()->flush();
            return $res;
        }
        return $res[0];
    }

    public function createAfa($afa) {
        $res = \mkw\store::getEm()->getRepository('Entities\Afa')->findByErtek($afa);
        if (!$res) {
            $res = new \Entities\Afa();
            $res->setErtek($afa);
            $res->setNev($afa);
            \mkw\store::getEm()->persist($res);
            \mkw\store::getEm()->flush();
            return $res;
        }
        return $res[0];
    }

    private function createPartnerCimke($ckat, $nev) {
        if (!$nev) {
            return null;
        }
        $cimke1 = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->getByNevAndKategoria($nev, $ckat);
        if (!$cimke1) {
            $cimke1 = new \Entities\Partnercimketorzs();
            $cimke1->setKategoria($ckat);
            $cimke1->setNev($nev);
            $cimke1->setMenu1lathato(false);
            \mkw\store::getEm()->persist($cimke1);
        }
        return $cimke1;
    }

    private function checkRunningImport($imp) {
        return (boolean) \mkw\store::getParameter($imp);
    }

    private function setRunningImport($imp, $onoff) {
        \mkw\store::setParameter($imp, $onoff);
    }

    public function stop() {
        $impname = $this->params->getStringParam('impname');

        $imp = '';

        switch ($impname) {
            case 'kreativ':
                $imp = \mkw\consts::RunningKreativImport;
                break;
            case 'delton':
                $imp = \mkw\consts::RunningDeltonImport;
                break;
            case 'reintex':
                $imp = \mkw\consts::RunningReintexImport;
                break;
            case 'tutisport':
                $imp = \mkw\consts::RunningTutisportImport;
                break;
            case 'maxutov':
            case 'makszutov':
                $imp = \mkw\consts::RunningMaxutovImport;
                break;
            case 'silko':
                $imp = \mkw\consts::RunningSilkoImport;
                break;
            case 'btech':
                $imp = \mkw\consts::RunningBtechImport;
                break;
            case 'kressgep':
                $imp = \mkw\consts::RunningKressgepImport;
                break;
            case 'kresstartozek':
                $imp = \mkw\consts::RunningKresstartozekImport;
                break;
            case 'legavenue':
                $imp = \mkw\consts::RunningLegavenueImport;
                break;
            case 'nomad':
                $imp = \mkw\consts::RunningNomadImport;
                break;
            default:
                $imp = false;
                break;
        }

        if ($imp) {
            $this->setRunningImport($imp, 0);
        }
    }

    public function repair() {
        $impname = $this->params->getStringParam('impname');

        switch ($impname) {
            case 'kreativ':
                $imp = \mkw\consts::GyartoKreativ;
                break;
            case 'delton':
                $imp = \mkw\consts::GyartoDelton;
                break;
            case 'reintex':
                $imp = \mkw\consts::GyartoReintex;
                break;
            case 'tutisport':
                $imp = \mkw\consts::GyartoTutisport;
                break;
            case 'maxutov':
            case 'makszutov':
                $imp = \mkw\consts::GyartoMaxutov;
                break;
            case 'silko':
                $imp = \mkw\consts::GyartoSilko;
                break;
            case 'btech':
                $imp = \mkw\consts::GyartoBtech;
                break;
            case 'kress':
                $imp = \mkw\consts::GyartoKress;
                break;
            case 'legavenue':
                $imp = \mkw\consts::GyartoLegavenue;
                break;
            case 'nomad':
                $imp = \mkw\consts::GyartoNomad;
                break;
            default:
                $imp = false;
                break;
        }

        if ($imp) {
            $this->repairFuggoTermek(\mkw\store::getParameter($imp));
        }
    }

    public function kreativpuzzleImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningKreativImport)) {

            $this->setRunningImport(\mkw\consts::RunningKreativImport, 1);

            $sep = ';';

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoKreativ);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathKreativ));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathKreativ));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @unlink('kreativ_fuggoben.txt');

            $ch = \curl_init('http://kreativpuzzle.hu/lawstocklist/');
            $fh = fopen('kreativpuzzlestock.txt', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);

            $ch = \curl_init('http://kreativpuzzle.hu/lawstocklist/images.php');
            $fh = fopen('kreativpuzzleimages.txt', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);

            $imagelist = array();
            $fh = fopen('kreativpuzzleimages.txt', 'r');
            if ($fh) {
                fgetcsv($fh, 0, $sep, '"');
                while ($data = fgetcsv($fh, 0, $sep, '"')) {
                    if ($data[$this->n('a')]) {
                        if (array_key_exists($data[$this->n('a')], $imagelist)) {
                            $imagelist[$data[$this->n('a')]][] = $data[$this->n('b')];
                        }
                        else {
                            $imagelist[$data[$this->n('a')]] = array($data[$this->n('b')]);
                        }
                    }
                }
            }
            fclose($fh);


            $linecount = 0;
            $fh = fopen('kreativpuzzlestock.txt', 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen('kreativpuzzlestock.txt', 'r');
                if ($fh) {
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                        if ($data[$this->n('c')] * 1 > 0) {
                            $katnev = $this->toutf(trim($data[$this->n('h')]));
                            $parent = $this->createKategoria($katnev, $parentid);
                        }
                    }

                    rewind($fh);

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                        if ($data[$this->n('c')] * 1 > 0) {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[$this->n('a')]);
                            if (!$termek) {

                                if ($createuj) {
                                    $katnev = $this->toutf(trim($data[$this->n('h')]));
                                    $urlkatnev = \mkw\store::urlize($katnev);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                    $parent = $this->createKategoria($katnev, $parentid);
                                    $termeknev = $this->toutf(trim($data[$this->n('b')]));

                                    $hosszuleiras = $this->toutf(trim($data[$this->n('n')]));
                                    $rovidleiras = $this->toutf(trim($data[$this->n('e')]));

                                    $idegenkod = 'KP' . $data[$this->n('a')];

                                    $termek = new \Entities\Termek();
                                    $termek->setFuggoben(true);
                                    $termek->setMe('db');
                                    $termek->setNev($termeknev);
                                    $termek->setLeiras($hosszuleiras);
                                    $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                    $termek->setCikkszam($data[$this->n('a')]);
                                    $termek->setIdegencikkszam($data[$this->n('a')]);
                                    $termek->setIdegenkod($idegenkod);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(3);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                    // kepek
                                    if (array_key_exists($data[$this->n('a')], $imagelist)) {
                                        $imgcnt = 0;
                                        foreach ($imagelist[$data[$this->n('a')]] as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $idegenkod);
                                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegenkod);
                                            if (count($imagelist[$data[$this->n('a')]]) > 1) {
                                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                                $kepnev = $kepnev . '_' . $imgcnt;
                                            }

                                            $extension = \mkw\store::getExtension($imgurl);
                                            $imgpath = $nameWithoutExt . '.' . $extension;

                                            $ch = \curl_init($imgurl);
                                            $ih = fopen($imgpath, 'w');
                                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                            }
                                            if (((count($imagelist[$data[$this->n('a')]]) > 1) && ($imgcnt == 1)) || (count($imagelist[$data[$this->n('a')]]) == 1)) {
                                                $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                                $termek->setKepleiras($termeknev);
                                            }
                                            else {
                                                $kep = new \Entities\TermekKep();
                                                $termek->addTermekKep($kep);
                                                $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                                $kep->setLeiras($termeknev);
                                                \mkw\store::getEm()->persist($kep);
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                $termek = $termek[0];
                                if ($editleiras) {
                                    $hosszuleiras = $this->toutf(trim($data[$this->n('n')]));
                                    $termek->setLeiras($hosszuleiras);
                                    //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                    //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                }
                            }
                            if ($termek) {
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(($data[$this->n('g')] * 1) == 0);
                                }
                                if (!$termek->getAkcios()) {
                                    $termek->setNetto($data[$this->n('d')] * 1 * $arszaz / 100);
                                    $termek->setBrutto(round($termek->getBrutto(), -1));
                                }
                                \mkw\store::getEm()->persist($termek);
                            }
                        }
                        else {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[$this->n('a')]);
                            if ($termek) {
                                $termek = $termek[0];
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(true);
                                    $termek->setLathato(false);
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();

                    $lettfuggoben = false;
                    if ($gyarto) {
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        $idegenkodok = array();
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            $idegenkodok[] = 'KP' . $data[0];
                        }
                        $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                        foreach ($termekek as $t) {
                            if (!in_array($t['idegenkod'], $idegenkodok)) {
                                /** @var \Entities\Termek $termek */
                                $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                if ($termek && $termek->getKeszlet() <= 0) {
                                    $lettfuggoben = true;
                                    \mkw\store::writelog('cikkszám: ' . $termek->getCikkszam(), 'kreativ_fuggoben.txt');
                                    $termek->setInaktiv(true);
                                    \mkw\store::getEm()->persist($termek);
                                    \mkw\store::getEm()->flush();
                                }
                            }
                        }
                    }
                    if ($lettfuggoben) {
                        echo json_encode(array('url' => '/kreativ_fuggoben.txt'));
                    }
                }
                fclose($fh);
            }
            else {
                echo json_encode(array('url' => '/kreativpuzzlestock.txt'));
            }
            $this->setRunningImport(\mkw\consts::RunningKreativImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    private function fgetdeltoncsv($handle) {
        $escaped = false;
        $enclosed = false;
        $vege = false;
        $str = '';
        while (!$vege && (false !== ($char = fgetc($handle)))) {
            switch ($char) {
                case "\n":
                    if ($escaped || $enclosed) {
                        $str .= $char;
                    }
                    else {
                        $vege = true;
                    }
                    break;
                case '"':
                    if (!$escaped) {
                        $enclosed = !$enclosed;
                    }
                    $str .= $char;
                    break;
                default:
                    $str .= $char;
                    break;
            }
            $escaped = ($char == '\\');
        }
        if ($str) {
            return $this->parse_csv($str, 'Ł');
        }
        return false;
    }

    public function deltonImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningDeltonImport)) {

            $this->setRunningImport(\mkw\consts::RunningDeltonImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoDelton);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $deltondownload = $this->params->getBoolRequestParam('deltondownload', false);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathDelton));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathDelton));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @unlink('delton_fuggoben.txt');

            if ($deltondownload) {
                \unlink('delton.txt');
                $ch = \curl_init('http://delton.hu/csv.php?verzio=2&jelszo=ORJ194');
                $fh = fopen('delton.txt', 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_exec($ch);
                fclose($fh);
            }

            $linecount = 0;
            $fh = fopen('delton.txt', 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = $this->fgetdeltoncsv($fh))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen('delton.txt', 'r');
                if ($fh) {
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    $termekdb = 0;
                    // $this->fgetdeltoncsv($fh);
                    while (($termekdb < $dbtol) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                        if ($data[1]) {
                            if ($data[6] && $data[6] !== '"') {
                                $katnev = trim($data[6]);
                            }
                            elseif ($data[5] && $data[5] !== '"') {
                                $katnev = trim($data[5]);
                            }
                            elseif ($data[4] && $data[4] !== '"') {
                                $katnev = trim($data[4]);
                            }
                            $parent = $this->createKategoria($katnev, $parentid);
                        }
                    }

                    rewind($fh);
                    $termekdb = 0;

                    // $this->fgetdeltoncsv($fh);
                    while (($termekdb < $dbtol) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                        if ($data[1]) {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('DT' . $data[1]);
                            if (!$termek) {

                                if ($createuj) {
                                    if ($data[6] && $data[6] !== '"') {
                                        $katnev = trim($data[6]);
                                    }
                                    elseif ($data[5] && $data[5] !== '"') {
                                        $katnev = trim($data[5]);
                                    }
                                    elseif ($data[4] && $data[4] !== '"') {
                                        $katnev = trim($data[4]);
                                    }
                                    $urlkatnev = \mkw\store::urlize($katnev);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                    $parent = $this->createKategoria($katnev, $parentid);
                                    $termeknev = trim($data[0]);

                                    $hosszuleiras = trim($data[3]);
                                    $rovidleiras = trim($data[2]);

                                    $idegenkod = 'DT' . $data[1];

                                    $termek = new \Entities\Termek();
                                    $termek->setFuggoben(true);
                                    $termek->setMe(trim($data[9]));
                                    $termek->setNev($termeknev);
                                    $termek->setLeiras($hosszuleiras);
                                    $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                    $termek->setCikkszam($data[1]);
                                    $termek->setIdegencikkszam($data[1]);
                                    $termek->setIdegenkod($idegenkod);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(1);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                    // kepek

                                    $imgurl = trim($data[14]);
                                    if (!strpos($imgurl, 'http://')) {
                                        $imgurl = 'http://' . $imgurl;
                                    }
                                    $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $idegenkod);
                                    $kepnev = \mkw\store::urlize($termeknev . '_' . $idegenkod);

                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                    }
                                    $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                    $termek->setKepleiras($termeknev);
                                }
                            }
                            else {
                                /** @var \Entities\Termek $termek */
                                $termek = $termek[0];
                                if ($editleiras) {
                                    $hosszuleiras = trim($data[3]);
                                    $termek->setLeiras($hosszuleiras);
                                    //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                    //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                }
                                if ($termek->getTermekfa1Nev() === '"') {
                                    if ($data[6] && $data[6] !== '"') {
                                        $katnev = trim($data[6]);
                                    }
                                    elseif ($data[5] && $data[5] !== '"') {
                                        $katnev = trim($data[5]);
                                    }
                                    elseif ($data[4] && $data[4] !== '"') {
                                        $katnev = trim($data[4]);
                                    }
                                    $urlkatnev = \mkw\store::urlize($katnev);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                    $parent = $this->createKategoria($katnev, $parentid);

                                    $termek->setTermekfa1($parent);
                                }
                            }
                            // $termek->setNemkaphato(($data[6] * 1) == 0);
                            if ($termek) {
                                // $termek->setAfa($afa[0]);
                                if ((substr($data[11], -6) == 'rkezik') || (substr($data[11], 0, 6) == 'rendel')) {
                                    if ($termek->getKeszlet() <= 0) {
                                        $termek->setNemkaphato(true);
                                    }
                                }
                                else {
                                    $termek->setNemkaphato(false);
                                }
                                if (!$termek->getAkcios()) {
                                    $kiskerar = $data[7] * 1;
                                    $nagykerar = $data[8] * 1;
                                    if (($kiskerar / $nagykerar * 100 < 115) || ($kiskerar / ($nagykerar * $arszaz / 100) * 100 < 115)) {
                                        $termek->setNetto($nagykerar * 115 / 100);
                                    }
                                    else {
                                        $termek->setNetto($kiskerar * $arszaz / 100);
                                    }
                                    $termek->setBrutto(round($termek->getBrutto(), -1));
                                }
                                \mkw\store::getEm()->persist($termek);
                                // \mkw\store::getEm()->flush();
                            }
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();

                    $lettfuggoben = false;
                    if ($gyarto) {
                        rewind($fh);
                        $idegenkodok = array();
                        while ($data = $this->fgetdeltoncsv($fh)) {
                            $idegenkodok[] = 'DT' . $data[1];
                        }
                        if ($idegenkodok) {
                            $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                            $termekdb = 0;
                            foreach ($termekek as $t) {
                                if (!in_array($t['idegenkod'], $idegenkodok)) {
                                    /** @var \Entities\Termek $termek */
                                    $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                    if ($termek && $termek->getKeszlet() <= 0) {
                                        $termekdb++;
                                        \mkw\store::writelog('cikkszám: ' . $termek->getCikkszam(), 'delton_fuggoben.txt');
                                        $lettfuggoben = true;
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                        if (($termekdb % $batchsize) === 0) {
                                            \mkw\store::getEm()->flush();
                                            \mkw\store::getEm()->clear();
                                        }
                                    }
                                }
                            }
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                    if ($lettfuggoben) {
                        echo json_encode(array('url' => '/delton_fuggoben.txt'));
                    }
                }
                fclose($fh);
            }
            else {
                echo json_encode(array('url' => '/delton.txt'));
            }
            $this->setRunningImport(\mkw\consts::RunningDeltonImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function nomadImport() {

        function toArr($obj) {
            return array(
                'sku' => (string) $obj->sku,
                'name' => (string) $obj->name,
                'brand' => (string) $obj->brand,
                'price' => (int) $obj->price,
                'photo' => (string) $obj->photo,
                'catalog_first' => (string) $obj->catalog_first,
                'catalog_second' => (string) $obj->catalog_second,
                'size' => (string) $obj->size,
                'color' => (string) $obj->color,
                'available' => (int) $obj->available,
                'parent' => (string) $obj->parent,
                'child' => (string) $obj->child,
                'category' => (string) $obj->category,
                'short_description' => (string) $obj->short_description,
                'long_description' => (string) $obj->long_description
            );
        }

        function keres($mit, $miben) {
            $dbig = count($miben);
            $termekdb = 0;
            $megvan = false;
            while (($dbig && ($termekdb < $dbig)) && !$megvan) {
                $megvan = ((string) $miben[$termekdb]->sku) == $mit;
                $termekdb++;
            }
            return $megvan;
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningNomadImport)) {

            $this->setRunningImport(\mkw\consts::RunningNomadImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoNomad);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathNomad));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathNomad));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @unlink('nomad_fuggoben.txt');

            $ch = \curl_init('http://www.nomadsport.eu/upload/stocks/nomadsport_6.xml');
            $fh = fopen('nomad.xml', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);

            $xml = simplexml_load_file("nomad.xml");
            if ($xml) {
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $szulok = array();
                $gyereklist = array();
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if ($data['parent']) {
                        $szulok[$data['parent']]['gyerekek'][] = $data;
                        $gyereklist[$data['sku']] = $data['sku'];
                    }
                    $termekdb++;
                }
                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if (array_key_exists($data['sku'], $szulok)) {
                        $szulok[$data['sku']]['szulo'] = $data;
                    }
                    $termekdb++;
                }
                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if ($data['category']) {
                        $this->createKategoria($data['category'], $parentid);
                    }
                    $termekdb++;
                }

//                \mkw\store::writelog(print_r($szulok, true), 'nomadtree.log');

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $szulogyerek = array_key_exists($data['sku'], $szulok) || array_key_exists($data['sku'], $gyereklist);
                    if (!$szulogyerek) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $data['sku'], 'gyarto' => $gyartoid));

                        if (!$termek) {
                            if ($createuj) {

//                                \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new', 'nomadimport.log');

                                $urlkatnev = \mkw\store::urlize($data['category']);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $parent = $this->createKategoria($data['category'], $parentid);
                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMe('db');
                                $termek->setNev($data['name']);
                                $termek->setIdegencikkszam($data['sku']);
                                if ($data['catalog_first']) {
                                    $termek->setCikkszam($data['catalog_first']);
                                }
                                elseif ($data['catalog_second']) {
                                    $termek->setCikkszam($data['catalog_second']);
                                }
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ));
                                $hosszuleiras = $puri->sanitize(trim($data['long_description']));

                                $puri2 = \mkw\store::getSanitizer();
                                $rovidleiras = $puri2->sanitize(trim($data['short_description']));
                                $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                                $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);

                                // kepek

                                $imgurl = trim($data['photo']);
                                $imgurl = str_replace('http://', 'http:/', $imgurl);
                                $imgurl = str_replace('http:/', 'http://', $imgurl);
                                $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                $extension = \mkw\store::getExtension($imgurl);
                                $imgpath = $nameWithoutExt . '.' . $extension;

                                $ch = \curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                \curl_setopt($ch, CURLOPT_FILE, $ih);
                                \curl_exec($ch);
                                fclose($ih);

                                foreach ($this->settings['sizes'] as $k => $size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x', $size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                }
                                $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                $termek->setKepleiras($data['name']);
                            }
                        }
                        else {
                            /** @var \Entities\Termek $termek */
//                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - edit', 'nomadimport.log');
                            $termek = $termek[0];
                            if ($editleiras) {
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ));
                                $hosszuleiras = $puri->sanitize(trim($data['long_description']));

                                $puri2 = \mkw\store::getSanitizer();
                                $rovidleiras = $puri2->sanitize(trim($data['short_description']));
                                $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                                //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            }
                        }
                        if ($termek) {
                            if (!$data['available']) {
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(true);
                                }
                            }
                            else {
                                $termek->setNemkaphato(false);
                            }
                            if (!$termek->getAkcios()) {
                                $termek->setBrutto($data['price']);
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $szulo = array_key_exists($data['sku'], $szulok);
                    if ($szulo) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $data['sku'], 'gyarto' => $gyartoid));

                        if (!$termek) {
                            if ($createuj) {

//                                \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new - parent', 'nomadimport.log');

                                $urlkatnev = \mkw\store::urlize($data['category']);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $parent = $this->createKategoria($data['category'], $parentid);
                                $termek = new \Entities\Termek();
                                $termek->setIdegencikkszam($data['sku']);
                                $termek->setFuggoben(true);
                                $termek->setMe('db');
                                $termek->setNev($data['name']);
                                if ($data['catalog_first']) {
                                    $termek->setCikkszam($data['catalog_first']);
                                }
                                elseif ($data['catalog_second']) {
                                    $termek->setCikkszam($data['catalog_second']);
                                }
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ));
                                $hosszuleiras = $puri->sanitize(trim($data['long_description']));

                                $puri2 = \mkw\store::getSanitizer();
                                $rovidleiras = $puri2->sanitize(trim($data['short_description']));
                                $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                                $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);

                                // kepek

                                $imgurl = trim($data['photo']);
                                $imgurl = str_replace('http://', 'http:/', $imgurl);
                                $imgurl = str_replace('http:/', 'http://', $imgurl);
                                $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                $extension = \mkw\store::getExtension($imgurl);
                                $imgpath = $nameWithoutExt . '.' . $extension;

                                $ch = \curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                \curl_setopt($ch, CURLOPT_FILE, $ih);
                                \curl_exec($ch);
                                fclose($ih);
                                \curl_close($ch);

                                foreach ($this->settings['sizes'] as $k => $size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x', $size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                }
                                $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                $termek->setKepleiras($data['name']);

                                $valtozat = new \Entities\TermekValtozat();
                                $valtozat->setAdatTipus1($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)));
                                $valtozat->setErtek1($data['color']);
                                $valtozat->setAdatTipus2($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)));
                                $valtozat->setErtek2($data['size']);
                                $valtozat->setIdegencikkszam($data['sku']);
                                if ($data['catalog_first']) {
                                    $valtozat->setCikkszam($data['catalog_first']);
                                }
                                elseif ($data['catalog_second']) {
                                    $valtozat->setCikkszam($data['catalog_second']);
                                }
                                $valtozat->setTermek($termek);
                                if (!$data['available']) {
                                    $valtozat->setElerheto(false);
                                }
                                else {
                                    $valtozat->setElerheto(true);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                            }
                        }
                        else {
                            /** @var \Entities\Termek $termek */
//                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - edit - parent', 'nomadimport.log');
                            $termek = $termek[0];
                            if ($editleiras) {
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ));
                                $hosszuleiras = $puri->sanitize(trim($data['long_description']));

                                $puri2 = \mkw\store::getSanitizer();
                                $rovidleiras = $puri2->sanitize(trim($data['short_description']));
                                $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                                //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            }
                            $valtozat = null;
                            /** @var \Entities\TermekValtozat $v */
                            foreach($termek->getValtozatok() as $v) {
                                if ($v->getIdegencikkszam() == $data['sku']) {
                                    $valtozat = $v;
                                }
                            }
                            if ($valtozat) {
                                if (!$data['available']) {
                                    if ($valtozat->getKeszlet() <= 0) {
                                        $valtozat->setElerheto(false);
                                    }
                                }
                                else {
                                    $valtozat->setElerheto(true);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                            }
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $gyerek = array_key_exists($data['sku'], $gyereklist);
                    if ($gyerek) {

                        $termek = false;
                        $valtozat = false;
                        $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(array('idegencikkszam' => $data['sku']));
                        if ($valtozatok) {
                            foreach ($valtozatok as $v) {
                                $termek = $v->getTermek();
                                if ($termek && $termek->getGyartoId() == $gyartoid) {
                                    $valtozat = $v;
                                    break;
                                }
                            }
                        }
                        else {
                            $termek = $this->getRepo('Entities\Termek')->findBy(array('idegencikkszam' => $data['parent'], 'gyarto' => $gyartoid));
                        }

                        if (is_array($termek)) {
                            $termek = $termek[0];
                        }
                        if ($termek) {
                            if (!$valtozat) {
                                if ($createuj) {

//                                    \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new - child', 'nomadimport.log');

                                    $urlkatnev = \mkw\store::urlize($data['category']);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                    $valtozat = new \Entities\TermekValtozat();
                                    $valtozat->setAdatTipus1($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)));
                                    $valtozat->setErtek1($data['color']);
                                    $valtozat->setAdatTipus2($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)));
                                    $valtozat->setErtek2($data['size']);
                                    $valtozat->setIdegencikkszam($data['sku']);
                                    if ($data['catalog_first']) {
                                        $valtozat->setCikkszam($data['catalog_first']);
                                    }
                                    elseif ($data['catalog_second']) {
                                        $valtozat->setCikkszam($data['catalog_second']);
                                    }
                                    $valtozat->setTermek($termek);

                                    // kepek

                                    $imgurl = trim($data['photo']);
                                    $imgurl = str_replace('http://', 'http:/', $imgurl);
                                    $imgurl = str_replace('http:/', 'http://', $imgurl);
                                    $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                    $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_exec($ch);
                                    fclose($ih);
                                    \curl_close($ch);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                    }
                                    if (!$termek->getKepurl()) {
                                        $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $termek->setKepleiras($data['name']);
                                        $valtozat->setTermekfokep(true);
                                    }
                                    else {
                                        $tkep = new \Entities\TermekKep();
                                        $termek->addTermekKep($tkep);
                                        $tkep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $tkep->setLeiras($data['name']);
                                        $valtozat->setKep($tkep);
                                        \mkw\store::getEm()->persist($tkep);
                                    }
                                    \mkw\store::getEm()->persist($valtozat);
                                }
                            }
                            if ($valtozat) {
                                if (!$data['available']) {
                                    if ($valtozat->getKeszlet() <= 0) {
                                        $valtozat->setElerheto(false);
                                    }
                                }
                                else {
                                    $valtozat->setElerheto(true);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                            }
                        }
                        else {
                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - NO PARENT TERMEK - child', 'nomadimport.log');
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();

                $szulok = null;
                $gyereklist = null;

                $lettfuggoben = false;
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                if ($gyarto) {
                    $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                    $termekdb = 0;
                    foreach ($termekek as $t) {
                        /** @var \Entities\Termek $termek */
                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                        if ($termek && !$termek->getFuggoben() && !$termek->getInaktiv()) {
                            $valtozatok = $termek->getValtozatok();
                            /** @var \Entities\TermekValtozat $valtozat */
                            foreach ($valtozatok as $valtozat) {
                                if ($valtozat->getElerheto()) {
                                    if (!keres($valtozat->getIdegencikkszam(), $products)) {
                                        if ($valtozat->getKeszlet() <= 0) {
                                            $lettfuggoben = true;
                                            \mkw\store::writelog('változat cikkszám: ' . $valtozat->getCikkszam()
                                                . ' szállítói cikkszám: ' . $valtozat->getIdegencikkszam() . ' ' . $valtozat->getNev()
                                                . ' | termék: ' . $termek->getCikkszam(),
                                                'nomad_fuggoben.txt');
                                            $valtozat->setElerheto(false);
                                            \mkw\store::getEm()->persist($valtozat);
                                        }
                                    }
                                }
                            }
                            if (!keres($t['idegencikkszam'], $products)) {
                                if ($termek->getKeszlet() <= 0) {
                                    $nincselerhetovaltozat = true;
                                    $vkvk = $termek->getValtozatok();
                                    /** @var \Entities\TermekValtozat $valtozat */
                                    foreach ($vkvk as $valtozat) {
                                        if ($valtozat->getElerheto()) {
                                            $nincselerhetovaltozat = false;
                                        }
                                    }
                                    if ($nincselerhetovaltozat) {
                                        $lettfuggoben = true;
                                        \mkw\store::writelog('termék cikkszám: ' . $termek->getCikkszam() . ' szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' ' . $termek->getNev(), 'nomad_fuggoben.txt');
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            }
                            $termekdb++;
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
                if ($lettfuggoben) {
                    echo json_encode(array('url' => '/nomad_fuggoben.txt'));
                }
            }
            \unlink('nomad.xml');

            $this->setRunningImport(\mkw\consts::RunningNomadImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function nikaImport() {

        function toArr($obj) {
            $xmlkepek = (array) $obj->images;
            $kepek = array();
            foreach ($xmlkepek as $xk) {
                $kepek[] = $xk['image_url'];
            }
            return array(
                'number' => (string) $obj->number,
                'name' => (string) $obj->name,
                'manufacturerName' => (string) $obj->manufacturerName,
                'available' => (int) $obj->available,
                'storageCondition' => (int) $obj->storageCondition,
                'unitType' => (string) $obj->unitType,
                'cbsNumber' => (string) $obj->cbsNumber,
                'mainGroupCode' => (string) $obj->mainGroupCode,
                'mainGroupName' => (string) $obj->mainGroupName,
                'productGroupCode' => (string) $obj->productGroupCode,
                'productGroupName' => (string) $obj->productGroupName,
                'descriptionShort' => (string) $obj->descriptionShort,
                'description' => (string) $obj->description,
                'weight' => (string) $obj->weight,
                'sizeX' => (string) $obj->sizeX,
                'sizeY' => (string) $obj->sizeY,
                'sizeZ' => (string) $obj->sizeZ,
                'taxRate' => (string) $obj->taxRate,
                'manufacturerNumber' => (string) $obj->manufacturerNumber,
                'stockFree' => (string) $obj->stockFree,
                'stockAvailableDate' => (string) $obj->stockAvailableDate,
                'stockOuterType' => (string) $obj->stockOuterType,
                'priceMembership' => (string) $obj->prices->priceMembership,
                'images' => $kepek
            );
        }

        function keres($mit, $miben) {
            $dbig = count($miben);
            $termekdb = 0;
            $megvan = false;
            while (($dbig && ($termekdb < $dbig)) && !$megvan) {
                $megvan = ((string) $miben[$termekdb]->number) == $mit;
                $termekdb++;
            }
            return $megvan;
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningNikaImport)) {

            $this->setRunningImport(\mkw\consts::RunningNikaImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoNika);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathNika));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathNika));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @unlink('nika_fuggoben.txt');

            /**
            $ch = \curl_init('http://fototechnika.dyndns.org:21643/mindennap/011544/products.xml');
            $fh = fopen('nikaproducts.xml', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);
             */

            $xml = simplexml_load_file("nikaproducts.xml");
            if ($xml) {
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $termekek = array();
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    $termekek[$data['number']] = $data;
                    $termekdb++;
                }

                foreach ($termekek as $_t) {
                    $parent = null;
                    if ($_t['mainGroupName']) {
                        $parent = $this->createKategoria($_t['mainGroupName'], $parentid);
                    }
                    if ($_t['productGroupName']) {
                        if ($parent) {
                            $this->createKategoria($_t['productGroupName'], $parent->getId());
                        }
                        else {
                            $this->createKategoria($_t['productGroupName'], $parentid);
                        }
                    }
                    if ($_t['cbsNumber'] && $_t['taxRate']) {
                        $afa = $this->createAfa($_t['taxRate']);
                        $this->createVtsz($_t['cbsNumber'], $afa);
                    }
                }

                foreach ($termekek as $data) {

                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('idegenkod' => $data['number'], 'gyarto' => $gyartoid));

                    if (!$termek) {
                        if ($createuj) {

//                                \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new', 'nomadimport.log');

                            $urlkatnev = \mkw\store::urlize($data['mainGroupName'] . '-' . $data['productGroupName']);
                            \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                            $afa = $this->createAfa($data['taxRate']);
                            $vtsz = $this->createVtsz($data['cbsNumber'], $afa);
                            $parent = $this->createKategoria($data['mainGroupName'], $parentid);
                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe($data['unitType']);
                            $termek->setNev($data['name']);
                            $termek->setIdegenkod($data['number']);
                            $termek->setIdegencikkszam($data['manufacturerNumber']);
                            $termek->setCikkszam($data['manufacturerNumber']);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ));
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortDescription']));
                            $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz);
                            $termek->setHosszusag($data['sizeX']);
                            $termek->setSzelesseg($data['sizeY']);
                            $termek->setMagassag($data['sizeZ']);
                            $termek->setSuly($data['weight']);

                            // kepek
                            $elso = true;
                            foreach ($data['images'] as $i) {

                                $imgurl = trim($i);
                                $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($data['name'] . '_' . $data['number']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['number']);

                                $extension = \mkw\store::getExtension($imgurl);
                                $imgpath = $nameWithoutExt . '.' . $extension;

                                $ch = \curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                \curl_setopt($ch, CURLOPT_FILE, $ih);
                                \curl_exec($ch);
                                fclose($ih);

                                foreach ($this->settings['sizes'] as $k => $size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x', $size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                }
                                if ($elso) {
                                    $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                    $termek->setKepleiras($data['name']);
                                }
                                $elso = false;
                            }
                        }
                    }
                    else {
                        /** @var \Entities\Termek $termek */
//                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - edit', 'nomadimport.log');
                        $termek = $termek[0];
                        if ($editleiras) {
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ));
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortDescription']));
                            $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                        }
                    }
                    if ($termek) {
                        if ($data['available'] !== 2 && $data['storageCondition'] !== 2) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setNemkaphato(true);
                            }
                        }
                        else {
                            $termek->setNemkaphato(false);
                        }
                        if (!$termek->getAkcios()) {
                            $termek->setBrutto($data['priceMembership'] * 1 * $arszaz / 100);
                        }
                        \mkw\store::getEm()->persist($termek);
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();

                $lettfuggoben = false;
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                if ($gyarto) {
                    $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                    $termekdb = 0;
                    foreach ($termekek as $t) {
                        /** @var \Entities\Termek $termek */
                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                        if ($termek && !$termek->getFuggoben() && !$termek->getInaktiv()) {
                            if (!keres($t['idegenkod'], $products)) {
                                if ($termek->getKeszlet() <= 0) {
                                    $nincselerhetovaltozat = true;
                                    $vkvk = $termek->getValtozatok();
                                    /** @var \Entities\TermekValtozat $valtozat */
                                    foreach ($vkvk as $valtozat) {
                                        if ($valtozat->getElerheto()) {
                                            $nincselerhetovaltozat = false;
                                        }
                                    }
                                    if ($nincselerhetovaltozat) {
                                        $lettfuggoben = true;
                                        \mkw\store::writelog('termék cikkszám: ' . $termek->getCikkszam() . ' szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' ' . $termek->getNev(), 'nika_fuggoben.txt');
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            }
                            $termekdb++;
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
                if ($lettfuggoben) {
                    echo json_encode(array('url' => '/nika_fuggoben.txt'));
                }
            }
            //\unlink('nikaproducts.xml');

            $this->setRunningImport(\mkw\consts::RunningNikaImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function reintexImport() {

        if (!$this->checkRunningImport(\mkw\consts::RunningReintexImport)) {

            $this->setRunningImport(\mkw\consts::RunningReintexImport, 1);

            $sep = ';';

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoReintex);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);
            move_uploaded_file($_FILES['toimport']['tmp_name'], 'reinteximport.csv');

            $fh = fopen('reinteximport.csv', 'r');
            if ($fh) {
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                $termekdb = 0;
                fgetcsv($fh, 0, $sep, '"');
                while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                }
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                    if ($data[$this->n('a')]) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[$this->n('a')], 'gyarto' => $gyartoid));
                        if (!$termek) {

                            if ($createuj) {

                                $termeknev = $data[$this->n('b')];

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMe('darab');
                                $termek->setNev($termeknev);
                                $termek->setCikkszam($data[$this->n('a')]);
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);
                                $termek->setHparany(3);
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                            }
                        }
                        else {
                            $termek = $termek[0];
                            if ($editleiras) {
                                //$hosszuleiras = mb_convert_encoding(trim($data[3]), 'UTF8', 'ISO-8859-2');
                                //$termek->setLeiras($hosszuleiras);
                                //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            }
                        }
                        if ($termek) {
                            if (!$termek->getAkcios()) {
                                $termek->setBrutto(round($data[$this->n('i')] * 1 * $arszaz / 100, -1));
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                    }
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
                fclose($fh);
                \unlink('reinteximport.csv');
            }
            $this->setRunningImport(\mkw\consts::RunningReintexImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function tutisportImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningTutisportImport)) {

            $this->setRunningImport(\mkw\consts::RunningTutisportImport, 1);

            $sep = ';';

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoTutisport);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);
            move_uploaded_file($_FILES['toimport']['tmp_name'], 'tutisportimport.csv');

            $fh = fopen('tutisportimport.csv', 'r');
            if ($fh) {
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                $termekdb = 0;
                $termekdb = 0;
                fgetcsv($fh, 0, $sep, '"');
                while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                }
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                    if ($data[$this->n('a')]) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[$this->n('a')], 'gyarto' => $gyartoid));
                        if (!$termek) {
                            $csz = str_replace(' ', '', $data[$this->n('a')]);
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $csz, 'gyarto' => $gyartoid));
                        }
                        if ($data[$this->n('d')] * 1 != 0) {
                            if (!$termek) {

                                if ($createuj) {

                                    $termeknev = $this->toutf(trim($data[$this->n('b')]));
                                    $me = $this->toutf(trim($data[$this->n('c')]));

                                    $termek = new \Entities\Termek();
                                    $termek->setFuggoben(true);
                                    $termek->setMe($me);
                                    $termek->setNev($termeknev);
                                    $termek->setCikkszam($data[$this->n('a')]);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(3);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                }
                            }
                            else {
                                $termek = $termek[0];
                                if ($editleiras) {
                                    //$hosszuleiras = mb_convert_encoding(trim($data[3]), 'UTF8', 'ISO-8859-2');
                                    //$termek->setLeiras($hosszuleiras);
                                    //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                    //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                }
                            }
                            if ($termek) {
                                $termek->setCikkszam($data[$this->n('a')]);
                                if (!$termek->getAkcios()) {
                                    $termek->setBrutto(round($data[$this->n('e')] * 1 * $arszaz / 100, -1));
                                }
                                \mkw\store::getEm()->persist($termek);
                            }
                        }
                        else {
                            if ($termek) {
                                $termek = $termek[0];
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(true);
                                    $termek->setLathato(false);
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                    }
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
                fclose($fh);
                \unlink('tutisportimport.csv');
            }
            $this->setRunningImport(\mkw\consts::RunningTutisportImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function makszutovImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningMaxutovImport)) {

            $this->setRunningImport(\mkw\consts::RunningMaxutovImport, 1);

            $sep = ';';

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoMaxutov);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathMaxutov));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathMaxutov));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @\unlink('makszutov_fuggoben.txt');

//            $ch = \curl_init('https://www.tavcso-mikroszkop.hu/partner-arlista?format=csv');
            $ch = \curl_init('https://www.tavcso-mikroszkop.hu/dealer-prices.csv?username=quixoft&password=$H$9FHZxcbwkGLAgAmXDgLhf7A/4vk/5R1');
            $fh = fopen('makszutov.txt', 'w');
            \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);

            $linecount = 0;
            $fh = fopen('makszutov.txt', 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen('makszutov.txt', 'r');
                if ($fh) {
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $katnev = $data[$this->n('b')];
                        $parent = $this->createKategoria($katnev, $parentid);
                    }

                    rewind($fh);

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $idegencikkszam = (string)$data[$this->n('a')];
                        $termekdb++;
                        $termek = false;
                        $valtozat = false;
                        $valtozatok = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->findBy(array('idegencikkszam' => $idegencikkszam));
                        if ($valtozatok) {
                            foreach ($valtozatok as $v) {
                                $termek = $v->getTermek();
                                if ($termek && $termek->getGyartoId() == $gyartoid) {
                                    $valtozat = $v;
                                    break;
                                }
                            }
                        }
                        else {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid));
                        }
                        if ($data[$this->n('j')]) {
                            $ch = \curl_init($data[$this->n('j')]);
                            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $le = \curl_exec($ch);

                            $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ));
                            $leiras = $puri->sanitize($le);

                            $puri2 = \mkw\store::getSanitizer();
                            $kisleiras = $puri2->sanitize($le);
                        }
                        else {
                            $leiras = '';
                            $kisleiras = '';
                        }

                        $kaphato = (substr($data[$this->n('h')], 0, 5) !== 'Nincs') && (strpos($data[$this->n('h')], 'rk.:') === false);

                        if (!$termek) {

                            if ($createuj && $kaphato) {
                                $katnev = $data[$this->n('b')];
                                $urlkatnev = \mkw\store::urlize($katnev);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                $parent = $this->createKategoria($katnev, $parentid);
                                $termeknev = $data[$this->n('d')];

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMe('db');
                                $termek->setNev($termeknev);
                                $termek->setLeiras($leiras);
                                $termek->setRovidleiras(mb_substr($kisleiras, 0, 100, 'UTF8') . '...');
                                $termek->setIdegencikkszam($idegencikkszam);
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);
                                $termek->setHparany(3);
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                $termek->setNemkaphato(false);
                                $termek->setBrutto(round($data[$this->n('g')] * 1 * $arszaz / 100, -1));
                                // kepek
                                $imagelist = explode(',', $data[$this->n('i')]);
                                $imgcnt = 0;
                                foreach ($imagelist as $imgurl) {
                                    $imgcnt++;

                                    $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                    $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                    if (count($imagelist) > 1) {
                                        $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                        $kepnev = $kepnev . '_' . $imgcnt;
                                    }

                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                    }
                                    if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                        $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $termek->setKepleiras($termeknev);
                                    }
                                    else {
                                        $kep = new \Entities\TermekKep();
                                        $termek->addTermekKep($kep);
                                        $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $kep->setLeiras($termeknev);
                                        \mkw\store::getEm()->persist($kep);
                                    }
                                }
                                \mkw\store::getEm()->persist($termek);
                            }
                        }
                        else {
                            if (is_array($termek)) {
                                $termek = $termek[0];
                            }
                            if ($valtozat) {
                                if ($termek) {
                                    if (!$termek->getAkcios()) {
                                        $ar = $data[$this->n('g')] * 1 * $arszaz / 100;
                                        $ar = round($ar, -1);
                                        $valtozat->setBrutto($ar - $termek->getBrutto());
                                    }
                                }
                                if (!$kaphato) {
                                    if ($valtozat->getKeszlet() <= 0) {
                                        $valtozat->setElerheto(false);
                                    }
                                }
                                else {
                                    $valtozat->setElerheto(true);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                                if ($termek) {
                                    $egysemkaphato = true;
                                    foreach ($termek->getValtozatok() as $valt) {
                                        if ($valt->getElerheto()) {
                                            $egysemkaphato = false;
                                        }
                                    }
                                    $termek->setNemkaphato($egysemkaphato);
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                            else {
                                if ($termek) {
                                    if ($editleiras) {
                                        $termek->setLeiras($leiras);
                                    }
                                    if (!$kaphato) {
                                        $termek->setNemkaphato($termek->getKeszlet() <= 0);
                                    }
                                    else {
                                        $termek->setNemkaphato(false);
                                    }
                                    if (!$termek->getAkcios()) {
                                        $termek->setBrutto(round($data[$this->n('g')] * 1 * $arszaz / 100, -1));
                                    }
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();

                    $lettfuggoben = false;
                    if ($gyarto) {
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        $idegenkodok = array();
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            $idegenkodok[] = (string)$data[0];
                        }
                        if ($idegenkodok) {
                            $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                            $termekdb = 0;
                            foreach ($termekek as $t) {
                                if ($t['idegencikkszam'] === '') {
                                    $termeketkikellvenni = true;
                                    /** @var \Entities\Termek $termek */
                                    $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                    /** @var \Entities\TermekValtozat $valtozat */
                                    foreach ($termek->getValtozatok() as $valtozat) {
                                        if ($valtozat->getIdegencikkszam()) {
                                            if (!in_array($valtozat->getIdegencikkszam(), $idegenkodok)) {
                                                if ($valtozat->getKeszlet() > 0) {
                                                    $termeketkikellvenni = false;
                                                }
                                                // a változat nincs készleten, nincs meg az id.cikkszám makszutovnál, a terméknek sincs id.cikkszáma
                                                else {
                                                    $termekdb++;
                                                    \mkw\store::writelog('VÁLTOZAT idegen cikkszám: ' . $valtozat->getIdegencikkszam() . ' | saját cikkszám: ' . $valtozat->getCikkszam(), 'makszutov_fuggoben.txt');
                                                    $lettfuggoben = true;
                                                    $valtozat->setLathato(false);
                                                    $valtozat->setElerheto(false);
                                                    \mkw\store::getEm()->persist($valtozat);
                                                    if (($termekdb % $batchsize) === 0) {
                                                        \mkw\store::getEm()->flush();
                                                        \mkw\store::getEm()->clear();
                                                    }
                                                }
                                            }
                                            // megvan az idegen cikkszám makszutovnál
                                            else {
                                                $termeketkikellvenni = false;
                                            }
                                        }
                                        // nincs idegen cikkszám
                                        else {
                                            $termeketkikellvenni = false;
                                        }
                                    }
                                    if ($termeketkikellvenni) {
                                        if ($termek && $termek->getKeszlet() <= 0) {
                                            $termekdb++;
                                            \mkw\store::writelog('idegen cikkszám: ' . $t['idegencikkszam'] . ' | saját cikkszám: ' . $termek->getCikkszam(), 'makszutov_fuggoben.txt');
                                            $lettfuggoben = true;
                                            $termek->setInaktiv(true);
                                            \mkw\store::getEm()->persist($termek);
                                            if (($termekdb % $batchsize) === 0) {
                                                \mkw\store::getEm()->flush();
                                                \mkw\store::getEm()->clear();
                                            }
                                        }
                                    }
                                }
                                else {
                                    if (!in_array($t['idegencikkszam'], $idegenkodok)) {
                                        /** @var \Entities\Termek $termek */
                                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                        if ($termek && $termek->getKeszlet() <= 0) {
                                            $termekdb++;
                                            \mkw\store::writelog('idegen cikkszám: ' . $t['idegencikkszam'] . ' | saját cikkszám: ' . $termek->getCikkszam(), 'makszutov_fuggoben.txt');
                                            $lettfuggoben = true;
                                            $termek->setInaktiv(true);
                                            \mkw\store::getEm()->persist($termek);
                                            if (($termekdb % $batchsize) === 0) {
                                                \mkw\store::getEm()->flush();
                                                \mkw\store::getEm()->clear();
                                            }
                                        }
                                    }
                                }
                            }
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                    if ($lettfuggoben) {
                        echo json_encode(array('url' => '/makszutov_fuggoben.txt'));
                    }
                }
                fclose($fh);
                \unlink('makszutov.txt');
            }
            else {
                echo json_encode(array('url' => '/makszutov.txt'));
            }
            $this->setRunningImport(\mkw\consts::RunningMaxutovImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function silkoImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningSilkoImport)) {

            $this->setRunningImport(\mkw\consts::RunningSilkoImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoSilko);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
            if ($dbtol < 2) {
                $dbtol = 2;
            }

            $filenev = $_FILES['toimport']['name'];
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = \PHPExcel_IOFactory::identify($filenev);
            $reader = \PHPExcel_IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = $sheet->getHighestRow() * 1;
            if (!$dbig) {
                $dbig = $maxrow;
            }
            $maxcol = $sheet->getHighestColumn();
            $maxcolindex = \PHPExcel_Cell::columnIndexFromString($maxcol);

            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $katnev = $sheet->getCell('F' . $row)->getValue();
                $kats = explode('|', $katnev);
                if ($kats[0]) {
                    $parent = $this->createKategoria($kats[0], $parentid);
                }
                if ($kats[1]) {
                    if ($parent) {
                        $this->createKategoria($kats[1], $parent->getId());
                    }
                    else {
                        $this->createKategoria($kats[1], $parentid);
                    }
                }
            }

            $termekdb = 0;
            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $termekdb++;

                $cikkszam = $sheet->getCell('A' . $row)->getValue();
                $kaphato = $sheet->getCell('C' . $row)->getValue();
                $katnev = $sheet->getCell('F' . $row)->getValue();

                $le = $sheet->getCell('G' . $row)->getValue() . ' ' . $sheet->getCell('H' . $row)->getValue();
                $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                ));
                $leiras = $puri->sanitize($le);

                $puri2 = \mkw\store::getSanitizer();
                $kisleiras = $puri2->sanitize($le);


                $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $cikkszam, 'gyarto' => $gyartoid));

                if (!$termek) {
                    if ($createuj && $kaphato) {

                        $parent = null;
                        $kats = explode('|', $katnev);
                        if ($kats[0]) {
                            $parent = $this->createKategoria($kats[0], $parentid);
                        }
                        if ($kats[1]) {
                            if ($parent) {
                                $parent = $this->createKategoria($kats[1], $parent->getId());
                            }
                            else {
                                $parent = $this->createKategoria($kats[1], $parentid);
                            }
                        }
                        //$parent = $this->createKategoria($katnev, $parentid);
                        $termeknev = $sheet->getCell('B' . $row)->getValue();

                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMe($sheet->getCell('J' . $row)->getValue());
                        $termek->setNev($termeknev);
                        $termek->setLeiras($leiras);
                        $termek->setRovidleiras(mb_substr($kisleiras, 0, 100, 'UTF8') . '...');
                        $termek->setCikkszam($cikkszam);
                        $termek->setIdegencikkszam($cikkszam);
                        $termek->setTermekfa1($parent);
                        $termek->setVtsz($vtsz[0]);
                        $termek->setHparany(3);
                        if ($gyarto) {
                            $termek->setGyarto($gyarto);
                        }
                        $termek->setNemkaphato(false);
                        $termek->setBrutto(round($sheet->getCell('E' . $row)->getValue() * 1 * $arszaz / 100, -1));
                        \mkw\store::getEm()->persist($termek);
                    }
                }
                else {
                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($editleiras) {
                        $termek->setLeiras($leiras);
                    }
                    if (!$kaphato) {
                        if ($termek->getKeszlet() <= 0) {
                            $termek->setNemkaphato(true);
                        }
                    }
                    else {
                        $termek->setNemkaphato(false);
                    }
                    if (!$termek->getAkcios()) {
                        $termek->setBrutto(round($sheet->getCell('E' . $row)->getValue() * 1 * $arszaz / 100, -1));
                    }
                    \mkw\store::getEm()->persist($termek);
                }

                if (($termekdb % $batchsize) === 0) {
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $excel->disconnectWorksheets();
            \unlink($filenev);
            $this->setRunningImport(\mkw\consts::RunningSilkoImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }

    }

    public function btechImport() {

        if (!$this->checkRunningImport(\mkw\consts::RunningBtechImport)) {
            function isTermeksor($adat) {
                return trim($adat, '\'') * 1 > 0;
            }

            $this->setRunningImport(\mkw\consts::RunningBtechImport, 1);

            $volthiba = false;

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoBtech);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathBtech));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathBtech));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
            if ($dbtol < 3) {
                $dbtol = 3;
            }

            $filenev = $_FILES['toimport']['name'];
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = \PHPExcel_IOFactory::identify($filenev);
            $reader = \PHPExcel_IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = $sheet->getHighestRow() * 1;
            if (!$dbig) {
                $dbig = $maxrow;
            }

            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $adat = $sheet->getCell('A' . $row)->getValue();
                if ($adat && !isTermeksor($adat)) {
                    $katnev = $adat;
                    $parent = $this->createKategoria($katnev, $parentid);
                }
            }

            @unlink('btechimport.error');

            $letezocikkszamok = array();

            $termekdb = 0;
            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $adat = $sheet->getCell('A' . $row)->getValue();
                if ($adat) {
                    if (isTermeksor($adat)) {
                        $letezocikkszamok[] = $adat;
                        $termekdb++;

                        $kaphato = true;
                        $cikkszam = $sheet->getCell('A' . $row)->getValue();
                        $termeknev = $sheet->getCell('B' . $row)->getValue();
                        $link = $sheet->getCell('D' . $row)->getValue();

                        if ($link) {
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $link);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_USERAGENT, 'MKW Webshop Import');
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                            $termekpage = curl_exec($ch);
                            $curlerror = curl_error($ch);
                            $curlerrno = curl_errno($ch);
                            curl_close($ch);
                        }
                        else {
                            $volthiba = true;
                            $termekpage = false;
                            \mkw\store::writelog($cikkszam . ': empty url', 'btechimport.error');
                        }

                        if ($termekpage) {

                            $crawler = new Crawler($termekpage);

                            $ar = 0;

                            $nodelist = $crawler->filter('div#item-page > div.left > div.buy > span.price > span.old-price');
                            if ($nodelist->count()) {
                                $ar = $nodelist->text();
                                $ar = str_replace(array(' ', 'Ft'), '', $ar);
                                $ar = $ar * 1;
                            }
                            else {
                                $nodelist = $crawler->filter('div#item-page > div.left > div.buy > span.price');
                                if ($nodelist->count()) {
                                    $ar = $nodelist->text();
                                    $ar = str_replace(array(' ', 'Ft'), '', $ar);
                                    $ar = $ar * 1;
                                }
                            }

                            if (!$ar) {
                                \mkw\store::writelog($cikkszam . ': nem akcios, de nincs ar');
                            }

                            $nodelist = $crawler->filter('div#item-page > div.left > h1');
                            if ($nodelist->count()) {
                                $termeknev = $nodelist->text();
                            }

                            $nodelist = $crawler->filter('div#item-page > div.description');
                            $le = '';
                            if ($nodelist->count()) {
                                $le = $nodelist->html();
                            }
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ));
                            $leiras = str_replace("\t", '', $puri->sanitize($le));
                            $puri2 = \mkw\store::getSanitizer();
                            $kisleiras = str_replace("\t", '', $puri2->sanitize($le));

                            $nodelist = $crawler->filter('div#item-page > div.right > div.item-image a');
                            $imagelist = $nodelist->each(function (Crawler $node, $i) {
                                return 'http://btech.hu' . $node->attr('href');
                            });

                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $cikkszam, 'gyarto' => $gyartoid));

                            if (!$termek) {
                                if ($createuj && $kaphato) {

                                    $termek = new \Entities\Termek();
                                    $termek->setFuggoben(true);
                                    $termek->setMe('db');
                                    $termek->setNev($termeknev);
                                    $termek->setLeiras($leiras);
                                    $termek->setRovidleiras(mb_substr($kisleiras, 0, 100, 'UTF8') . '...');
                                    $termek->setCikkszam($cikkszam);
                                    $termek->setIdegencikkszam($cikkszam);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(3);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                    $termek->setNemkaphato(false);
                                    if ($ar) {
                                        $termek->setBrutto($ar);
                                    }

                                    $imgcnt = 0;
                                    foreach ($imagelist as $imgurl) {
                                        $imgcnt++;

                                        $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $cikkszam);
                                        $kepnev = \mkw\store::urlize($termeknev . '_' . $cikkszam);
                                        if (count($imagelist) > 1) {
                                            $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                            $kepnev = $kepnev . '_' . $imgcnt;
                                        }

                                        $extension = \mkw\store::getExtension($imgurl);
                                        $imgpath = $nameWithoutExt . '.' . $extension;

                                        $ch = \curl_init($imgurl);
                                        $ih = fopen($imgpath, 'w');
                                        \curl_setopt($ch, CURLOPT_FILE, $ih);
                                        \curl_exec($ch);
                                        fclose($ih);

                                        foreach ($this->settings['sizes'] as $k => $size) {
                                            $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                            $matches = explode('x', $size);
                                            \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                        }
                                        if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                            $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                            $termek->setKepleiras($termeknev);
                                        }
                                        else {
                                            $kep = new \Entities\TermekKep();
                                            $termek->addTermekKep($kep);
                                            $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                            $kep->setLeiras($termeknev);
                                            \mkw\store::getEm()->persist($kep);
                                        }
                                    }

                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                            else {
                                if (is_array($termek)) {
                                    $termek = $termek[0];
                                }
                                if ($editleiras) {
                                    $termek->setLeiras($leiras);
                                }
                                if (!$kaphato) {
                                    if ($termek->getKeszlet() <= 0) {
                                        $termek->setNemkaphato(true);
                                    }
                                }
                                else {
                                    $termek->setNemkaphato(false);
                                }
                                if ($ar && !$termek->getAkcios()) {
                                    $termek->setBrutto($ar);
                                }
                                \mkw\store::getEm()->persist($termek);
                            }

                            if (($termekdb % $batchsize) === 0) {
                                \mkw\store::getEm()->flush();
                                \mkw\store::getEm()->clear();
                                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                                $parent = $this->createKategoria($katnev, $parentid);
                            }
                        }
                        else {
                            $volthiba = true;
                            if ($termekpage === false) {
                                \mkw\store::writelog($cikkszam . ': ' . $curlerrno . ' - ' . $curlerror, 'btechimport.error');
                            }
                            else {
                                \mkw\store::writelog($cikkszam . ': ' . gettype($termekpage) . ' = ' . $termekpage, 'btechimport.error');
                            }
                        }
                    }
                    else {
                        $katnev = $sheet->getCell('A' . $row)->getValue();
                        $parent = $this->createKategoria($katnev, $parentid);
                        $urlkatnev = \mkw\store::urlize($katnev);
                        \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            if ($gyarto && $letezocikkszamok) {
                $termekdb = 0;
                $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                foreach ($termekek as $t) {
                    if (!in_array($t['cikkszam'], $letezocikkszamok)) {
                        /** @var \Entities\Termek $termek */
                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                        if ($termek && $termek->getKeszlet() <= 0) {
                            $termekdb++;
                            $termek->setInaktiv(true);
                            \mkw\store::getEm()->persist($termek);
                            if (($termekdb % $batchsize) === 0) {
                                \mkw\store::getEm()->flush();
                                \mkw\store::getEm()->clear();
                            }
                        }
                    }
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
            }

            $excel->disconnectWorksheets();
            \unlink($filenev);

            if ($volthiba) {
                echo json_encode(array('url' => '/btechimport.error'));
            }
            $this->setRunningImport(\mkw\consts::RunningBtechImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }

    }

    public function kressgepImport() {

        if (!$this->checkRunningImport(\mkw\consts::RunningKressgepImport)) {
            function isTermeksor($adat) {
                return (bool)str_replace(' ', '', $adat);
            }

            $this->setRunningImport(\mkw\consts::RunningKressgepImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoKress);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            if ($dbtol < 6) {
                $dbtol = 6;
            }

            $filenev = $_FILES['toimport']['name'];
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = \PHPExcel_IOFactory::identify($filenev);
            $reader = \PHPExcel_IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = $sheet->getHighestRow() * 1;
            if (!$dbig) {
                $dbig = $maxrow;
            }

            $termekdb = 0;
            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $adat = $sheet->getCell('C' . $row)->getValue();
                if ($adat) {
                    if (isTermeksor($adat)) {
                        $termekdb++;

                        $cikkszam = str_replace(' ', '', $sheet->getCell('C' . $row)->getValue());
                        $ar = $sheet->getCell('F' . $row)->getValue() * 1;

                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $cikkszam, 'gyarto' => $gyartoid));

                        if ($termek) {
                            if (is_array($termek)) {
                                $termek = $termek[0];
                            }
                            if ($ar && !$termek->getAkcios()) {
                                $termek->setBrutto($ar);
                            }
                            \mkw\store::getEm()->persist($termek);
                        }

                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $excel->disconnectWorksheets();
            \unlink($filenev);
            $this->setRunningImport(\mkw\consts::RunningKressgepImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function kresstartozekImport() {

        if (!$this->checkRunningImport(\mkw\consts::RunningKresstartozekImport)) {
            function isTermeksor($adat) {
                return (bool)str_replace(' ', '', $adat);
            }

            $this->setRunningImport(\mkw\consts::RunningKresstartozekImport, 1);

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoKress);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            if ($dbtol < 5) {
                $dbtol = 5;
            }

            $filenev = $_FILES['toimport']['name'];
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = \PHPExcel_IOFactory::identify($filenev);
            $reader = \PHPExcel_IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = $sheet->getHighestRow() * 1;
            if (!$dbig) {
                $dbig = $maxrow;
            }

            $termekdb = 0;
            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $adat = $sheet->getCell('B' . $row)->getValue();
                if ($adat) {
                    if (isTermeksor($adat)) {
                        $termekdb++;

                        $cikkszam = str_replace(' ', '', $sheet->getCell('B' . $row)->getValue());
                        $ar = $sheet->getCell('E' . $row)->getValue() * 1;

                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $cikkszam, 'gyarto' => $gyartoid));

                        if ($termek) {
                            if (is_array($termek)) {
                                $termek = $termek[0];
                            }
                            if ($ar && !$termek->getAkcios()) {
                                $termek->setBrutto($ar);
                            }
                            \mkw\store::getEm()->persist($termek);
                        }

                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $excel->disconnectWorksheets();
            \unlink($filenev);
            $this->setRunningImport(\mkw\consts::RunningKresstartozekImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function createVateraPartner($pa) {
        $me = \mkw\store::getEm()->getRepository('Entities\Partner')->findBy(array('email' => $pa['temail']));

        if (!$me) {
            $me = new \Entities\Partner();
            $me->setVendeg(true);
            $me->setReferrer('http://' . $pa['vasarlashelye']);
            $me->setTelefon($pa['ttelefon']);
            $me->setEmail($pa['temail']);

            $nev = explode(' ', $pa['tnev']);
            $me->setKeresztnev(array_pop($nev));
            $me->setVezeteknev(implode(' ', $nev));

            $me->setAkcioshirlevelkell(true);
            $me->setUjdonsaghirlevelkell(true);

            if ($pa['szamlanev']) {
                $me->setNev($pa['szamlanev']);
                $me->setIrszam($pa['szamlairszam']);
                $me->setVaros($pa['szamlavaros']);
                $me->setUtca($pa['szamlautca']);
            }
            else {
                if ($pa['tszamlanev']) {
                    $me->setNev($pa['tszamlanev']);
                    $me->setIrszam($pa['tszamlairszam']);
                    $me->setVaros($pa['tszamlavaros']);
                    $me->setUtca($pa['tszamlautca']);
                }
                else {
                    if ($pa['szallnev']) {
                        $me->setNev($pa['szallnev']);
                        $me->setIrszam($pa['szallirszam']);
                        $me->setVaros($pa['szallvaros']);
                        $me->setUtca($pa['szallutca']);
                    }
                    else {
                        if ($pa['tszallnev']) {
                            $me->setNev($pa['tszallnev']);
                            $me->setIrszam($pa['tszallirszam']);
                            $me->setVaros($pa['tszallvaros']);
                            $me->setUtca($pa['tszallutca']);
                        }
                    }
                }
            }

            if ($pa['szallnev']) {
                $me->setSzallnev($pa['szallnev']);
                $me->setSzallirszam($pa['szallirszam']);
                $me->setSzallvaros($pa['szallvaros']);
                $me->setSzallutca($pa['szallutca']);
            }
            else {
                if ($pa['tszallnev']) {
                    $me->setSzallnev($pa['tszallnev']);
                    $me->setSzallirszam($pa['tszallirszam']);
                    $me->setSzallvaros($pa['tszallvaros']);
                    $me->setSzallutca($pa['tszallutca']);
                }
                else {
                    if ($pa['szamlanev']) {
                        $me->setSzallnev($pa['szamlanev']);
                        $me->setSzallirszam($pa['szamlairszam']);
                        $me->setSzallvaros($pa['szamlavaros']);
                        $me->setSzallutca($pa['szamlautca']);
                    }
                    else {
                        if ($pa['tszamlanev']) {
                            $me->setSzallnev($pa['tszamlanev']);
                            $me->setSzallirszam($pa['tszamlairszam']);
                            $me->setSzallvaros($pa['tszamlavaros']);
                            $me->setSzallutca($pa['tszamlautca']);
                        }
                    }
                }
            }

            \mkw\store::getEm()->persist($me);
            \mkw\store::getEm()->flush();
        }
        else {
            $me = $me[0];
        }
        return $me;
    }

    public function createVateraSzallitasimod($nev) {
        $me = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->findBy(array('nev' => $nev));
        if (!$me) {
            $me = new \Entities\Szallitasimod();
            $me->setNev($nev);
            $me->setWebes(false);
            $me->setFizmodok('1');

            \mkw\store::getEm()->persist($me);
            \mkw\store::getEm()->flush();
        }
        else {
            $me = $me[0];
        }
        return $me;
    }

    public function vateraImport() {
        $sep = ';';

        move_uploaded_file($_FILES['vaterarendeles']['tmp_name'], 'vaterarendeles.csv');
        move_uploaded_file($_FILES['vateratermek']['tmp_name'], 'vateratermek.csv');

        $fhrendeles = fopen('vaterarendeles.csv', 'r');
        $fhtermek = fopen('vateratermek.csv', 'r');
        if ($fhrendeles && $fhtermek) {

            $termekek = array();
            fgetcsv($fhtermek, 0, $sep, '"');
            while ($data = fgetcsv($fhtermek, 0, $sep, '"')) {
                $termekek[$data[$this->n('c')]] = $data;
            }

            $rendelesek = array();
            fgetcsv($fhrendeles, 0, $sep, '"');
            while ($data = fgetcsv($fhrendeles, 0, $sep, '"')) {
                if (array_key_exists($data[$this->n('b')], $termekek)) {
                    $rid = $data[$this->n('a')];
                    if ($data[$this->n('f')]) {
                        $rendelesek[$rid]['datum'] = $data[$this->n('g')];
                        $rendelesek[$rid]['szallmod'] = $data[$this->n('h')];
                        $rendelesek[$rid]['szallktg'] = $data[$this->n('i')] * 1;
                        $rendelesek[$rid]['szallnev'] = $data[$this->n('j')];
                        $rendelesek[$rid]['szallirszam'] = $data[$this->n('k')];
                        $rendelesek[$rid]['szallvaros'] = $data[$this->n('l')];
                        $rendelesek[$rid]['szallutca'] = $data[$this->n('m')];
                        $rendelesek[$rid]['szamlanev'] = $data[$this->n('n')];
                        $rendelesek[$rid]['szamlairszam'] = $data[$this->n('o')];
                        $rendelesek[$rid]['szamlavaros'] = $data[$this->n('p')];
                        $rendelesek[$rid]['szamlautca'] = $data[$this->n('q')];
                        $rendelesek[$rid]['megjegyzes'] = $data[$this->n('r')];
                    }
                    if (!array_key_exists('termek', $rendelesek[$rid])) {
                        $rendelesek[$rid]['termek'] = array();
                    }
                    $t = $termekek[$data[$this->n('b')]];
                    $rendelesek[$rid]['tusernev'] = $t[$this->n('h')];
                    $rendelesek[$rid]['tnev'] = $t[$this->n('i')];
                    $rendelesek[$rid]['ttelefon'] = $t[$this->n('j')];
                    $rendelesek[$rid]['temail'] = $t[$this->n('k')];
                    $rendelesek[$rid]['vasarlashelye'] = $t[$this->n('l')];
                    $rendelesek[$rid]['tdatum'] = $t[$this->n('o')];
                    $rendelesek[$rid]['tszallmod'] = $t[$this->n('p')];
                    $rendelesek[$rid]['tszallktg'] = $t[$this->n('q')] * 1;
                    $rendelesek[$rid]['tszallnev'] = $t[$this->n('r')];
                    $cim = \mkw\store::explodeCim($t[$this->n('s')]);
                    $rendelesek[$rid]['tszallirszam'] = $cim[0];
                    $rendelesek[$rid]['tszallvaros'] = $cim[1];
                    $rendelesek[$rid]['tszallutca'] = $cim[2];
                    $rendelesek[$rid]['tszamlanev'] = $t[$this->n('t')];
                    $cim = \mkw\store::explodeCim($t[$this->n('u')]);
                    $rendelesek[$rid]['tszamlairszam'] = $cim[0];
                    $rendelesek[$rid]['tszamlavaros'] = $cim[1];
                    $rendelesek[$rid]['tszamlautca'] = $cim[2];
                    $rendelesek[$rid]['tmegjegyzes'] = $t[$this->n('v')];

                    $rendelesek[$rid]['termek'][] = array(
                        'kod' => $data[$this->n('b')],
                        'nev' => $data[$this->n('c')],
                        'tnev' => $t[$this->n('a')],
                        'tcikkszam' => $t[$this->n('b')],
                        'mennyiseg' => $data[$this->n('d')] * 1,
                        'egysar' => $data[$this->n('e')] * 1,
                        'tmennyiseg' => $t[$this->n('d')] * 1,
                        'tegysar' => $t[$this->n('e')] * 1
                    );
                }
            }

            unset($termekek);

            uasort($rendelesek, function ($a, $b) {
                if ($a['datum'] == $b['datum']) {
                    return 0;
                }
                return ($a['datum'] < $b['datum']) ? -1 : 1;
            });

            //echo '<pre>';print_r($rendelesek);echo '</pre>';
            //die();

            foreach ($rendelesek as $rk => $r) {
                $fej = new \Entities\Bizonylatfej();
                $fej->setBizonylattipus(\mkw\store::getEm()->getRepository('Entities\Bizonylattipus')->find('megrendeles'));
                $fej->setPersistentData();
                $fej->setErbizonylatszam($rk);
                $partner = $this->createVateraPartner($r);
                $fej->setPartner($partner);
                $szallmod = $this->createVateraSzallitasimod($r['szallmod']);
                $fej->setSzallitasimod($szallmod);
                $ck = \mkw\store::getEm()->getRepository('Entities\Raktar')->find(\mkw\store::getParameter(\mkw\consts::Raktar));
                if ($ck) {
                    $fej->setRaktar($ck);
                }
                $ck = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find(\mkw\store::getParameter(\mkw\consts::Fizmod));
                if ($ck) {
                    $fej->setFizmod($ck);
                }
                $fej->setKelt();
                $fej->setTeljesites();
                $fej->setEsedekesseg();
                $fej->setHatarido($r['datum']);
                $ck = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                if ($ck) {
                    $fej->setValutanem($ck);
                    $fej->setBankszamla($ck->getBankszamla());
                }
                $fej->setArfolyam(1);

                $ck = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                if ($ck) {
                    $fej->setBizonylatstatusz($ck);
                }

                if ($r['megjegyzes'] || $r['tmegjegyzes']) {
                    $fej->setWebshopmessage($r['megjegyzes'] . ' ' . $r['tmegjegyzes']);
                }
                $fej->setBelsomegjegyzes('Vatera ' . $rk);

                //$fej->generateId(); // az üres kelt miatt került a végére

                $hibascikkszam = array();
                foreach ($r['termek'] as $rtetel) {
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $rtetel['tcikkszam']));
                    if ($termek) {
                        $tetel = new \Entities\Bizonylattetel();
                        $fej->addBizonylattetel($tetel);
                        $tetel->setPersistentData();
                        $tetel->setArvaltoztat(0);
                        $tetel->setTermek($termek[0]);
                        $tetel->setMozgat();
                        // mkw frissítés miatt egyelőre nem kell
                        //$tetel->setFoglal();
                        $tetel->setMennyiseg($rtetel['mennyiseg']);
                        $tetel->setBruttoegysar($rtetel['egysar']);
                        $tetel->setBruttoegysarhuf($rtetel['egysar']);
                        $tetel->calc();
                        \mkw\store::getEm()->persist($tetel);
                    }
                    else {
                        $hibascikkszam[] = $rtetel['tcikkszam'];
                    }
                }
                if ($hibascikkszam) {
                    $fej->setBelsomegjegyzes($fej->getBelsomegjegyzes() . ' Hibás cikkszámok: ' . implode(',', $hibascikkszam));
                }

                $fej->setKellszallitasikoltsegetszamolni((boolean)$r['szallktg']);
                $fej->setSzallitasikoltsegbrutto($r['szallktg']);
                \mkw\store::getEm()->persist($fej);
                \mkw\store::getEm()->flush();
                /**
                 * $statusz = $fej->getBizonylatstatusz();
                 * if ($statusz) {
                 * $emailtpl = $statusz->getEmailtemplate();
                 * $fej->sendStatuszEmail($emailtpl, null, false);
                 * }
                 *
                 */
            }

        }

        \unlink('vaterarendeles.csv');
        \unlink('vateratermek.csv');
    }

    public function szAtalakit() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sep = ';';

        move_uploaded_file($_FILES['toimport']['tmp_name'], 'szatalakit.csv');
        $er = fopen('eredmeny.csv', 'w');
        $fh = fopen('szatalakit.csv', 'r');
        if ($fh) {
            fgetcsv($fh, 0, $sep, '"');
            while ($data = fgetcsv($fh, 0, $sep, '"')) {
                $nev = str_getcsv($data[$this->n('e')], ' ');
                $cikkszam = $nev[0];
                $i = count($nev);
                $meret = $nev[$i - 1];
                $szin = $nev[$i - 2];
                unset($nev[$i - 2]);
                unset($nev[$i - 1]);
                unset($nev[0]);
                $out = array(
                    $data[$this->n('a')],
                    $data[$this->n('b')],
                    $data[$this->n('c')],
                    $data[$this->n('d')],
                    $cikkszam,
                    implode(' ', $nev),
                    $szin,
                    $meret,
                    $data[$this->n('f')],
                    $data[$this->n('g')]
                );
                fwrite($er, implode(';', $out) . "\n\r");
            }
        }
        fclose($fh);
        fclose($er);
        \unlink('szatalakit.csv');
    }

    public function szInvarImport() {
        $sep = ',';
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        move_uploaded_file($_FILES['toimport']['tmp_name'], 'szinvarimport.csv');
        $fh = fopen('szinvarimport.csv', 'r');
        if ($fh) {
            $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $afa = $afa[0];
            $szinvalt = $this->createTermekValtozatAdatTipus('Szín');
            $meretvalt = $this->createTermekValtozatAdatTipus('Méret');
            $termekdb = 0;
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                if ($data[$this->n('i')]) {
                    $vtsz = $this->createVtsz($data[$this->n('i')], $afa);
                }
                else {
                    $vtsz = $this->createVtsz('-', $afa);
                }
                $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findOneBy(array('cikkszam' => $data[$this->n('e')], 'nev' => $data[$this->n('f')]));
                if (!$termek) {

                    $parent = $this->getKategoriaByIdegenkod((string)$data[$this->n('a')]);

                    $termek = new \Entities\Termek();
                    $termek->setMe('db');
                    $termek->setNev($data[$this->n('f')]);
                    $termek->setCikkszam($data[$this->n('e')]);
                    $termek->setVonalkod($data[$this->n('d')]);
                    $termek->setIdegenkod($data[$this->n('c')]);
                    $termek->setTermekfa1($parent);
                    $termek->setVtsz($vtsz);

                    $valt = new \Entities\TermekValtozat();
                    $valt->setTermek($termek);
                    $valt->setAdatTipus1($szinvalt);
                    $valt->setErtek1($data[$this->n('h')]);
                    $valt->setAdatTipus2($meretvalt);
                    $valt->setErtek2($data[$this->n('j')]);
                    $valt->setCikkszam($data[$this->n('e')]);
                    $valt->setVonalkod($data[$this->n('d')]);
                    \mkw\store::getEm()->persist($valt);
                }
                else {
                    $termek->setNev($data[$this->n('f')]);
                    $termek->setCikkszam($data[$this->n('e')]);
                    $termek->setTermekfa1($parent);
                    $termek->setVtsz($vtsz);

                    $valt = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->findByVonalkod($data[$this->n('d')]);
                    if (!$valt) {
                        $valt = new \Entities\TermekValtozat();
                        $valt->setTermek($termek);
                        $valt->setVonalkod($data[$this->n('d')]);
                    }
                    else {
                        $valt = $valt[0];
                    }
                    $valt->setAdatTipus1($szinvalt);
                    $valt->setErtek1($data[$this->n('h')]);
                    $valt->setAdatTipus2($meretvalt);
                    $valt->setErtek2($data[$this->n('j')]);
                    $valt->setCikkszam($data[$this->n('e')]);
                    \mkw\store::getEm()->persist($valt);
                }
                \mkw\store::getEm()->persist($termek);
                \mkw\store::getEm()->flush();
            }
        }
        fclose($fh);
        \unlink('szatalakit.csv');
    }

    public function szInvarPartnerImport() {
        $sep = ';';
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        move_uploaded_file($_FILES['toimport']['tmp_name'], 'szinvarpartner.csv');
        $fh = fopen('szinvarpartner.csv', 'r');
        if ($fh) {
            $tipuskat = \mkw\store::getEm()->getRepository('Entities\Partnercimkekat')->findOneBynev('Típus');
            if (!$tipuskat) {
                $tipuskat = new \Entities\Partnercimkekat();
                $tipuskat->setLathato(true);
                $tipuskat->setNev('Típus');
                \mkw\store::getEm()->persist($tipuskat);
                \mkw\store::getEm()->flush();
            }
            $termekdb = 0;
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                $me = new \Entities\Partner();
                $me->setVendeg(false);
                if ($data[$this->n('b')] != 'NULL') {
                    $me->setNev($data[$this->n('b')]);
                }
                if ($data[$this->n('c')] != 'NULL') {
                    $me->setIrszam($data[$this->n('c')]);
                }
                if ($data[$this->n('d')] != 'NULL') {
                    $me->setVaros($data[$this->n('d')]);
                }
                if ($data[$this->n('e')] != 'NULL') {
                    $me->setUtca($data[$this->n['e']]);
                }
                if ($data[$this->n('f')] != 'NULL') {
                    $me->setHonlap($data[$this->n('f')]);
                }
                if ($data[$this->n('g')] != 'NULL') {
                    $me->setEmail($data[$this->n('g')]);
                }
                $tel = '';
                if ($data[$this->n('i')] != 'NULL') {
                    $tel = $data[$this->n('i')];
                }
                if ($data[$this->n('j')] != 'NULL') {
                    $tel .= $data[$this->n('j')];
                }
                if ($tel) {
                    $me->setTelefon($tel);
                }
                if ($data[$this->n('h')] != 'NULL') {
                    $marka = $this->createPartnerCimke($tipuskat, $data[$this->n('h')]);
                    if ($marka) {
                        $me->addCimke($marka);
                    }
                }
                \mkw\store::getEm()->persist($me);
                \mkw\store::getEm()->flush();
            }
        }
        fclose($fh);
        \unlink('szinvarpartner.csv');
    }

    public function szimport() {
//        $translaterepo = \mkw\store::getEm()->getRepository('Gedmo\Translatable\Entity\Translation');

        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $parentid = $this->params->getIntRequestParam('katid', 0);
        $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        if ($dbtol < 2) {
            $dbtol = 2;
        }

        $filenev = $_FILES['toimport']['name'];
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
        //pathinfo

        $filetype = \PHPExcel_IOFactory::identify($filenev);
        $reader = \PHPExcel_IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = $sheet->getHighestRow() * 1;
        if (!$dbig) {
            $dbig = $maxrow;
        }
        $maxcol = $sheet->getHighestColumn();
        $maxcolindex = \PHPExcel_Cell::columnIndexFromString($maxcol);

        $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
        $afa = $afa[0];
        $termekrepo = \mkw\store::getEm()->getRepository('Entities\Termek');
        $termekarrepo = \mkw\store::getEm()->getRepository('Entities\TermekAr');
        $valutanemek = array();
        $vnemek = \mkw\store::getEm()->getRepository('Entities\Valutanem')->getAll(array(), array());
        foreach ($vnemek as $vn) {
            $valutanemek[$vn->getNev()] = $vn;
        }

        $fej = array();
        for ($col = 0; $col < $maxcolindex; ++$col) {
            $cell = $sheet->getCellByColumnAndRow($col, 1);
            $fej[$col] = $cell->getValue();
        }

        for ($row = $dbtol; $row <= $dbig; ++$row) {
            $ujtermek = false;
            $kod = false;
            $vonalkod = false;
            $cikkszam = false;
            $nev = array();
            $vtsz = false;
            $netto = array();
            $brutto = array();

            for ($col = 0; $col < $maxcolindex; ++$col) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                if ($fej[$col] == 'kod') {
                    $kod = $cell->getValue();
                }
                elseif ($fej[$col] == 'vonalkod') {
                    $vonalkod = $cell->getValue();
                }
                elseif ($fej[$col] == 'cikkszam') {
                    $cikkszam = $cell->getValue();
                }
                elseif ($fej[$col] == 'vtsz') {
                    $vtsz = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 4) == 'nev_') {
                    $nyelv = strtoupper(substr($fej[$col], 4));
                    $nev[\mkw\store::getLocaleName($nyelv)] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 3) == 'nev') {
                    $nev[\mkw\store::getLocaleName('HU')] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 6) == 'netto_') {
                    $n = explode('_', $fej[$col]);
                    $netto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 7) == 'brutto_') {
                    $n = explode('_', $fej[$col]);
                    $brutto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                }
            }

            $termek = false;
            if ($kod) {
                $termek = $termekrepo->find($kod);
            }
            elseif ($vonalkod) {
                $termek = $termekrepo->findByVonalkod($vonalkod);
            }
            elseif ($cikkszam) {
                $termek = $termekrepo->findByCikkszam($cikkszam);
            }

            if ($termek) {
                if (is_array($termek)) {
                    $termek = $termek[0];
                }
            }
            else {
                if ($createuj && is_array($nev) && array_key_exists('HU', $nev)) {
                    $ujtermek = true;
                    $termek = new \Entities\Termek();
                    $termek->setMe('db');
                    if ($parent) {
                        $termek->setTermekfa1($parent);
                    }
                }
            }
            if ($termek) {
                if ($vonalkod) {
                    $termek->setVonalkod($vonalkod);
                }
                if ($cikkszam) {
                    $termek->setCikkszam($cikkszam);
                }
                if ($vtsz) {
                    $vtsz = $this->createVtsz($vtsz, $afa);
                    $termek->setVtsz($vtsz);
                }
                if ($brutto) {
                    foreach ($brutto as $evalu => $bruttox) {
                        $valutanem = $valutanemek[$evalu];
                        if ($valutanem) {
                            foreach ($bruttox as $ename => $ertek) {
                                if (is_array($netto)) {
                                    unset($netto[$evalu][$ename]);
                                }
                                if (!$ujtermek) {
                                    $ar = $termekarrepo->findBy(array('termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename));
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    $ar->setAzonosito($ename);
                                }
                                $ar->setBrutto($ertek);
                                \mkw\store::getEm()->persist($ar);
                            }
                        }
                    }
                }
                if ($netto) {
                    foreach ($netto as $evalu => $nettox) {
                        $valutanem = $valutanemek[$evalu];
                        if ($valutanem) {
                            foreach ($nettox as $ename => $ertek) {
                                if (!$ujtermek) {
                                    $ar = $termekarrepo->findBy(array('termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename));
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    $ar->setAzonosito($ename);
                                }
                                $ar->setNetto($ertek);
                                \mkw\store::getEm()->persist($ar);
                            }
                        }
                    }
                }

                if ($nev) {
                    foreach ($nev as $loc => $text) {
                        if ($loc !== \mkw\store::getTranslationListener()->getDefaultLocale()) {
                            if (!$ujtermek) {
                                $translation = \mkw\store::getEm()->getRepository('Entities\TermekTranslation')->findBy(
                                    array('object' => $termek->getId(), 'locale' => $loc, 'field' => 'nev'));
                                if ($translation) {
                                    $translation = $translation[0];
                                }
                            }
                            if ($ujtermek || !$translation) {
                                $translation = new \Entities\TermekTranslation('', 'nev', '');
                                $translation->setObject($termek);
                            }
                            $translation->setLocale($loc);
                            $translation->setContent($text);
                            \mkw\store::getEm()->persist($translation);
                        }
                        else {
                            $termek->setNev($text);
                        }
                    }
                }
                \mkw\store::getEm()->persist($termek);
                \mkw\store::getEm()->flush();

//                if (is_array($nev)) {
//                    foreach($nev as $loc => $text) {
//                        $termek->setNev($text);
//                        $termek->setTranslatableLocale($loc);
//                        \mkw\store::getEm()->persist($termek);
//                        \mkw\store::getEm()->flush();
//                    }
//                }
//                else {
//                    \mkw\store::getEm()->persist($termek);
//                    \mkw\store::getEm()->flush();
//                }
            }
        }

    }

    public function szSIIKerPartnerImport() {
        $sep = ',';
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
//        move_uploaded_file($_FILES['toimport']['tmp_name'], 'siikerpartnerek.csv');
        $fh = fopen('siikerpartnerek.csv', 'r');
        if ($fh) {
            $tipuskat = \mkw\store::getEm()->getRepository('Entities\Partnercimkekat')->findOneBynev('Típus');
            if (!$tipuskat) {
                $tipuskat = new \Entities\Partnercimkekat();
                $tipuskat->setLathato(true);
                $tipuskat->setNev('Típus');
                \mkw\store::getEm()->persist($tipuskat);
                \mkw\store::getEm()->flush();
            }
            $termekdb = 0;
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                $me = new \Entities\Partner();
                $me->setVendeg(false);
                if ($data[$this->n('c')] != '') {
                    $me->setNev($data[$this->n('c')]);
                }
                if ($data[$this->n('d')] != '') {
                    $me->setIrszam($data[$this->n('d')]);
                }
                if ($data[$this->n('e')] != '') {
                    $me->setVaros($data[$this->n('e')]);
                }
                if ($data[$this->n('f')] != '') {
                    $me->setUtca($data[$this->n('f')]);
                }
                if ($data[$this->n('g')] != '') {
                    $me->setAdoszam($data[$this->n('g')]);
                }
                if ($data[$this->n('h')] != '') {
                    $me->setEuadoszam($data[$this->n('h')]);
                }
                if ($data[$this->n('i')] != '') {
                    $me->setTelefon($data[$this->n('i')]);
                }
                if ($data[$this->n('j')] != '') {
                    $me->setMobil($data[$this->n('j')]);
                }
                if ($data[$this->n('k')] != '') {
                    $me->setEmail($data[$this->n('k')]);
                }
                if ($data[$this->n('l')] != '') {
                    $me->setHonlap($data[$this->n('l')]);
                }
                $marka = $this->createPartnerCimke($tipuskat, 'Kiskereskedelmi vevők');
                if ($marka) {
                    $me->addCimke($marka);
                }
                \mkw\store::getEm()->persist($me);
                \mkw\store::getEm()->flush();
            }
        }
        fclose($fh);
//        \unlink('siikerpartnerek.csv');
    }

    public function legavenueSzotar() {
        if (!$this->checkRunningImport(\mkw\consts::RunningLegavenueImport)) {

            $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 1);

            @\unlink('legavenue_forditani.txt');

            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $ch = \curl_init('http://www.legavenueeurope.com/legavenue/xml_inventory');
            $fh = fopen('legavenue.xml', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);

            $db = 0;
            $xml = simplexml_load_file("legavenue.xml");
            if ($xml) {

                $products = $xml;
                $maxrow = count($products);
                if (!$dbig) {
                    $dbig = $maxrow;
                }

                $rep = $this->getRepo('Entities\Szotar');

                for ($row = $dbtol; $row < $dbig; ++$row) {
                    $data = $products->Row[$row];
                    $szin = htmlspecialchars(strtolower((string)$data->Color));
                    $meret = htmlspecialchars(strtolower((string)$data->Size));
                    if (!$rep->find($szin)) {
                        $db++;
                        $o = new \Entities\Szotar();
                        $o->setMit($szin);
                        \mkw\store::getEm()->persist($o);
                        \mkw\store::getEm()->flush();
                        \mkw\store::writelog($szin, 'legavenue_forditani.txt');
                    }
                    if (!$rep->find($meret)) {
                        $db++;
                        $o = new \Entities\Szotar();
                        $o->setMit($meret);
                        \mkw\store::getEm()->persist($o);
                        \mkw\store::getEm()->flush();
                        \mkw\store::writelog($meret, 'legavenue_forditani.txt');
                    }
                }
            }
            \unlink('legavenue.xml');

            if ($db) {
                echo json_encode(array('url' => '/legavenue_forditani.txt'));
            }

            $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 0);
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function legavenueImport() {
        if (!$this->checkRunningImport(\mkw\consts::RunningLegavenueImport)) {

            if (!$this->getRepo('Entities\Szotar')->isAllTranslated()) {

                $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 1);

                @\unlink('legavenue_fuggoben.txt');

                $parentid = $this->params->getIntRequestParam('katid', 0);
                $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoLegavenue);
                $dbtol = $this->params->getIntRequestParam('dbtol', 0);
                $dbig = $this->params->getIntRequestParam('dbig', 0);
                $editleiras = $this->params->getBoolRequestParam('editleiras', false);
                $createuj = $this->params->getBoolRequestParam('createuj', false);
                $arszaz = $this->params->getNumRequestParam('arszaz', 100);
                $batchsize = $this->params->getNumRequestParam('batchsize', 20);

                $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathLegavenue));

                $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathLegavenue));
                $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
                if ($mainpath) {
                    $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }
                $path = $mainpath . $path;
                $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

                $ch = \curl_init('http://www.legavenueeurope.com/legavenue/xml_inventory');
                $fh = fopen('legavenue.xml', 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_exec($ch);
                fclose($fh);

                $xml = simplexml_load_file("legavenue.xml");
                if ($xml) {
                    $vtsz = $this->getRepo('Entities\Vtsz')->findBySzam('-');
                    $gyarto = $this->getRepo('Entities\Partner')->find($gyartoid);

                    $products = $xml;
                    $maxrow = count($products);
                    if (!$dbig) {
                        $dbig = $maxrow;
                    }

                    for ($row = $dbtol; $row < $dbig; ++$row) {
                        $data = $products->Row[$row];
                        $parent = $this->createKategoria((string)$data->class, $parentid);
                    }

                    $termekdb = 0;

                    for ($row = $dbtol; $row < $dbig; ++$row) {

                        $termekdb++;

                        $data = $products->Row[$row];

                        $kaphato = $data->qty_on_hand * 1 > 0;
                        $ar = $data->unit_price * 1 * 650;
                        $ar = round($ar, -1);
                        $idegencikkszam = (string)$data->sku;
                        $style = (string)$data->Style;

                        if ($idegencikkszam) {
                            $termek = false;
                            $valtozat = false;
                            $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(array('idegencikkszam' => $idegencikkszam));
                            if ($valtozatok) {
                                foreach ($valtozatok as $v) {
                                    $termek = $v->getTermek();
                                    if ($termek && $termek->getGyartoId() == $gyartoid) {
                                        $valtozat = $v;
                                        break;
                                    }
                                }
                            }
                            else {
                                $termek = $this->getRepo('Entities\Termek')->findBy(array('idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid));
                            }
                            if (!$termek) {

                                if ($createuj) {

                                    $katnev = (string)$data->class;
                                    $urlkatnev = \mkw\store::urlize($katnev);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                    $parent = $this->createKategoria($katnev, $parentid);

                                    $termeknev = trim((string)$data->Short_Desc);
                                    $rovidleiras = trim((string)$data->Short_Desc);

                                    $termek = $this->getRepo('Entities\Termek')->findBy(array('cikkszam' => $style, 'gyarto' => $gyartoid));
                                    if ($termek) {
                                        if (is_array($termek)) {
                                            $termek = $termek[0];
                                        }
                                        $valtozat = new \Entities\TermekValtozat();
                                        $valtozat->setAdatTipus1($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)));
                                        $valtozat->setErtek1($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Color))));
                                        $valtozat->setAdatTipus2($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)));
                                        $valtozat->setErtek2($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Size))));
                                        $valtozat->setIdegencikkszam($idegencikkszam);
                                        // kepek
                                        $imagelist = (array)$data->images;
                                        if (is_array($imagelist['image'])) {
                                            $imagelist = $imagelist['image'];
                                        }
                                        $imgcnt = 0;
                                        foreach ($imagelist as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                            if (count($imagelist) > 1) {
                                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                                $kepnev = $kepnev . '_' . $imgcnt;
                                            }

                                            $extension = \mkw\store::getExtension($imgurl);
                                            $imgpath = $nameWithoutExt . '.' . $extension;

                                            $ch = \curl_init($imgurl);
                                            $ih = fopen($imgpath, 'w');
                                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                            }
                                            $kep = new \Entities\TermekKep();
                                            $termek->addTermekKep($kep);
                                            $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                            $kep->setLeiras($termeknev);
                                            if ($imgcnt === 1) {
                                                $valtozat->setKep($kep);
                                            }
                                            \mkw\store::getEm()->persist($kep);
                                        }
                                        $valtozat->setTermek($termek);
                                        \mkw\store::getEm()->persist($valtozat);
                                    }
                                    else {
                                        $termek = new \Entities\Termek();
                                        $termek->setFuggoben(true);
                                        $termek->setMe('db');
                                        $termek->setNev($termeknev);
                                        $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                        $termek->setCikkszam($style);
                                        $termek->setIdegencikkszam($idegencikkszam);
                                        $termek->setTermekfa1($parent);
                                        $termek->setVtsz($vtsz[0]);
                                        $termek->setHparany(3);
                                        if ($gyarto) {
                                            $termek->setGyarto($gyarto);
                                        }

                                        $termek->setBrutto($ar);

                                        $valtozat = new \Entities\TermekValtozat();
                                        $valtozat->setAdatTipus1($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)));
                                        $valtozat->setErtek1($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Color))));
                                        $valtozat->setAdatTipus2($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)));
                                        $valtozat->setErtek2($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Size))));
                                        $valtozat->setIdegencikkszam($idegencikkszam);
                                        $valtozat->setTermek($termek);

                                        // kepek
                                        $imagelist = (array)$data->images;
                                        if (is_array($imagelist['image'])) {
                                            $imagelist = $imagelist['image'];
                                        }
                                        $imgcnt = 0;
                                        foreach ($imagelist as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                            if (count($imagelist) > 1) {
                                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                                $kepnev = $kepnev . '_' . $imgcnt;
                                            }

                                            $extension = \mkw\store::getExtension($imgurl);
                                            $imgpath = $nameWithoutExt . '.' . $extension;

                                            $ch = \curl_init($imgurl);
                                            $ih = fopen($imgpath, 'w');
                                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $this->settings['quality'], true);
                                            }
                                            if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                                $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                                $termek->setKepleiras($termeknev);
                                                $valtozat->setTermekfokep(true);
                                            }
                                            else {
                                                $kep = new \Entities\TermekKep();
                                                $termek->addTermekKep($kep);
                                                $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                                $kep->setLeiras($termeknev);
                                                $valtozat->setKep($kep);
                                                \mkw\store::getEm()->persist($kep);
                                            }
                                        }
                                        \mkw\store::getEm()->persist($valtozat);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            }
                            else {
                                if (is_array($termek)) {
                                    $termek = $termek[0];
                                }
                                if ($valtozat) {
                                    if ($termek && !$termek->getAkcios()) {
                                        //$valtozat->setBrutto($ar - $termek->getBrutto());
                                    }
                                    if (!$kaphato) {
                                        if ($valtozat->getKeszlet() <= 0) {
                                            $valtozat->setElerheto(false);
                                        }
                                    }
                                    else {
                                        $valtozat->setElerheto(true);
                                    }
                                    \mkw\store::getEm()->persist($valtozat);
                                    if ($termek) {
                                        $egysemkaphato = true;
                                        foreach ($termek->getValtozatok() as $valt) {
                                            if ($valt->getElerheto()) {
                                                $egysemkaphato = false;
                                            }
                                        }
                                        $termek->setNemkaphato($egysemkaphato);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                                else {
                                    if ($termek) {
                                        if (!$kaphato) {
                                            if ($termek->getKeszlet() <= 0) {
                                                $termek->setNemkaphato(true);
                                            }
                                        }
                                        else {
                                            $termek->setNemkaphato(false);
                                        }
                                        if (!$termek->getAkcios()) {
                                            //$termek->setBrutto($ar);
                                        }
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            }
                        }
                        //if (($termekdb % $batchsize) === 0) {

                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $vtsz = $this->getRepo('Entities\Vtsz')->findBySzam('-');
                            $gyarto = $this->getRepo('Entities\Partner')->find($gyartoid);
                        //}
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $gyarto = $this->getRepo('Entities\Partner')->find($gyartoid);

                    $lettfuggoben = false;
                    if ($gyarto) {
                        $idegenkodok = array();
                        foreach ($products as $data) {
                            $idegenkodok[] = (string)$data->sku;
                        }
                        if ($idegenkodok) {
                            $termekek = $this->getRepo('Entities\Termek')->getWithValtozatokForImport($gyarto);
                            $termekdb = 0;
                            /** @var \Entities\Termek $termek */
                            foreach ($termekek as $termek) {
                                if ($termek->getIdegencikkszam() && !in_array($termek->getIdegencikkszam(), $idegenkodok)) {
                                    if ($termek->getKeszlet() <= 0) {
                                        $termekdb++;
                                        \mkw\store::writelog('idegen cikkszám: ' . $termek->getIdegencikkszam() . ' | saját cikkszám: ' . $termek->getCikkszam(), 'legavenue_fuggoben.txt');
                                        $lettfuggoben = true;
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                                $valtozatok = $termek->getValtozatok();
                                /** @var \Entities\TermekValtozat $valtozat */
                                foreach ($valtozatok as $valtozat) {
                                    if ($valtozat->getIdegencikkszam() && !in_array($valtozat->getIdegencikkszam(), $idegenkodok)) {
                                        if ($valtozat->getKeszlet() <= 0) {
                                            $termekdb++;
                                            \mkw\store::writelog('változat idegen cikkszám: ' . $valtozat->getIdegencikkszam() . ' | saját cikkszám: ' . $valtozat->getCikkszam(), 'legavenue_fuggoben.txt');
                                            $lettfuggoben = true;
                                            $valtozat->setElerheto(false);
                                            \mkw\store::getEm()->persist($valtozat);
                                        }
                                    }
                                }
                                if ($termekdb >= $batchsize) {
                                    $termekdb = 0;
                                    \mkw\store::getEm()->flush();
//                                    \mkw\store::getEm()->clear();
                                }
                            }
                            \mkw\store::getEm()->flush();
//                            \mkw\store::getEm()->clear();
                        }
                        $termekek = $this->getRepo('Entities\Termek')->getWithValtozatokForImport($gyarto);
                        $termekdb = 0;
                        /** @var \Entities\Termek $termek */
                        foreach ($termekek as $termek) {
                            $vanelerheto = false;
                            $vanvaltozat = false;
                            $valtozatok = $termek->getValtozatok();
                            /** @var \Entities\TermekValtozat $valtozat */
                            foreach ($valtozatok as $valtozat) {
                                $vanvaltozat = true;
                                if ($valtozat->getElerheto()) {
                                    $vanelerheto = true;
                                }
                            }
                            if ($vanvaltozat && !$vanelerheto) {
                                $termekdb++;
                                \mkw\store::writelog('NINCS ELÉRHETŐ VÁLTOZAT: idegen cikkszám: ' . $termek->getIdegencikkszam() . ' | saját cikkszám: ' . $termek->getCikkszam(), 'legavenue_fuggoben.txt');
                                $lettfuggoben = true;
                                $termek->setNemkaphato(true);
                                \mkw\store::getEm()->persist($termek);
                                if (($termekdb % $batchsize) === 0) {
                                    \mkw\store::getEm()->flush();
//                                    \mkw\store::getEm()->clear();
                                }
                            }
                        }
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                    if ($lettfuggoben) {
                        echo json_encode(array('url' => '/legavenue_fuggoben.txt'));
                    }
                }
                \unlink('legavenue.xml');

                $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 0);
            }
            else {
                echo json_encode(array('msg' => 'Nincs minden lefordítva a szótárban.'));
            }
        }
        else {
            echo json_encode(array('msg' => 'Már fut ilyen import.'));
        }
    }

    public function kerriiimport() {

        function getUtca($mibol) {
            $p = strrpos($mibol, ' ');
            if ($p !== false) {
                return substr($mibol, 0, $p);
            }
            return $mibol;
        }

        function getHazszam($mibol) {
            $p = strrpos($mibol, ' ');
            if ($p !== false) {
                return substr($mibol, $p + 1);
            }
            return $mibol;
        }

        $dsn = 'mysql:host=' . \mkw\store::getConfigValue('kerrii.host') . ';dbname=' . \mkw\store::getConfigValue('kerrii.dbname') . ';port='
            . \mkw\store::getConfigValue('kerrii.port');
        $dbh = new \PDO(
            $dsn,
            \mkw\store::getConfigValue('kerrii.username'),
            \mkw\store::getConfigValue('kerrii.password'),
            array(
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            )
        );
        if ($dbh) {


            /**



            $stmt = $dbh->prepare('SELECT * FROM csk');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Csk')->findOneBy(array('migrid' => $r['kod']))) {
                    $csk = new \Entities\Csk();
                    $csk->setNev($this->toutf($r['nev']));
                    $csk->setErtek($r['netto']);
                    $csk->setMigrid($r['kod']);
                    \mkw\store::getEm()->persist($csk);
                    \mkw\store::getEm()->flush();
                }
            }

            $stmt = $dbh->prepare('SELECT * FROM afatorzs');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Afa')->findOneBy(array('migrid' => $r['kod']))) {
                    $afa = new \Entities\Afa();
                    $afa->setNev($this->toutf($r['afanev']));
                    $afa->setErtek($r['afaertek']);
                    $afa->setMigrid($r['kod']);
                    \mkw\store::getEm()->persist($afa);
                    \mkw\store::getEm()->flush();
                }
            }

            $stmt = $dbh->prepare('SELECT * FROM fizmod');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Fizmod')->findOneBy(array('migrid' => $r['kod']))) {
                    $fizmod = new \Entities\Fizmod();
                    $fizmod->setNev($this->toutf($r['nev']));
                    $fizmod->setHaladek($r['haladek']);
                    $fizmod->setTipus($r['tipus']);
                    $fizmod->setMigrid($r['kod']);
                    $fizmod->setRugalmas(false);
                    \mkw\store::getEm()->persist($fizmod);
                    \mkw\store::getEm()->flush();
                }
            }

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM vtsz');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Vtsz')->findOneBy(array('migrid' => $r['kod']))) {
                    $db++;
                    $vtsz = new \Entities\Vtsz();
                    $vtsz->setNev($this->toutf($r['szoveg']));
                    $vtsz->setSzam($this->toutf($r['szam']));
                    $vtsz->setMigrid($r['kod']);
                    $vtsz->setAfa($this->getRepo('Entities\Afa')->findOneBy(array('migrid' => $r['afa'])));
                    $vtsz->setCsk($this->getRepo('Entities\Csk')->findOneBy(array('migrid' => $r['csk'])));
                    $vtsz->setKt($this->getRepo('Entities\Csk')->findOneBy(array('migrid' => $r['kt'])));
                    \mkw\store::getEm()->persist($vtsz);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $stmt = $dbh->prepare('SELECT * FROM bankszamla');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Bankszamla')->findOneBy(array('migrid' => $r['kod']))) {
                    $bankszamla = new \Entities\Bankszamla();
                    $bankszamla->setBanknev($this->toutf($r['banknev']));
                    $bankszamla->setBankcim($this->toutf($r['bankcim']));
                    $bankszamla->setSzamlaszam($r['szlaszam']);
                    $bankszamla->setSwift($r['swift']);
                    $bankszamla->setMigrid($r['kod']);
                    \mkw\store::getEm()->persist($bankszamla);
                    \mkw\store::getEm()->flush();
                }
            }

            $stmt = $dbh->prepare('SELECT * FROM valutanem');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Valutanem')->findOneBy(array('migrid' => $r['kod']))) {
                    $valu = new \Entities\Valutanem();
                    $valu->setNev($this->toutf($r['nev']));
                    $valu->setKerekit($r['kerekit']);
                    $valu->setHivatalos($r['hivatalos']);
                    $valu->setMincimlet(($r['mincimlet'] ? $r['mincimlet'] : 0));
                    $valu->setMigrid($r['kod']);
                    $valu->setBankszamla($this->getRepo('Entities\Bankszamla')->findOneBy(array('migrid' => $r['defabankszla'])));
                    \mkw\store::getEm()->persist($valu);
                    \mkw\store::getEm()->flush();
                }
            }

            $stmt = $dbh->prepare('SELECT * FROM bankszamla');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                $bankszamla = $this->getRepo('Entities\Bankszamla')->findOneBy(array('migrid' => $r['kod']));
                if ($bankszamla) {
                    $bankszamla->setValutanem($this->getRepo('Entities\Valutanem')->findOneBy(array('migrid' => $r['defavaluta'])));
                    \mkw\store::getEm()->persist($bankszamla);
                    \mkw\store::getEm()->flush();
                }
            }

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM partner');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Partner')->findOneBy(array('migrid' => $r['kod']))) {
                    $db++;
                    $partner = new \Entities\Partner();
                    $partner->setNev($this->toutf($r['nev']));
                    $partner->setMigrid($r['kod']);
                    $partner->setAkcioshirlevelkell(false);
                    $partner->setUjdonsaghirlevelkell(false);
                    $partner->setSzallito(false);
                    $partner->setSzamlatipus(0);
                    $partner->setEzuzletkoto(false);
                    $partner->setBanknev($this->toutf($r['banknev']));
                    $partner->setAdoszam(\mkw\store::toutf($r['adoszam']));
                    $partner->setCjszam(\mkw\store::toutf($r['cegjszam']));
                    $partner->setMukengszam(\mkw\store::toutf($r['mukengszam']));
                    $partner->setJovengszam(\mkw\store::toutf($r['jovengszam']));
                    $partner->setEuadoszam(\mkw\store::toutf($r['euadoszam']));
                    $partner->setEmail(\mkw\store::toutf($r['email']));
                    $partner->setTelefon(\mkw\store::toutf($r['telefon']));
                    $partner->setMobil(\mkw\store::toutf($r['mobil']));
                    $partner->setFax(\mkw\store::toutf($r['fax']));
                    $partner->setHonlap(\mkw\store::toutf($r['honlap']));
                    $partner->setIban($r['szlaszam']);
                    $valuta = $this->getRepo('Entities\Valutanem')->findOneBy(array('migrid' => $r['valutanem']));
                    if ($valuta) {
                        $partner->setValutanem($valuta);
                    }
                    $fizmod = $this->getRepo('Entities\Fizmod')->findOneBy(array('migrid' => $r['fizmod']));
                    if ($fizmod) {
                        $partner->setFizmod($fizmod);
                    }
                    $partner->setFizhatido($r['fizhatido']);
                    if ($r['alapar']) {
                        $partner->setTermekarazonosito($r['alapar']);
                    }
                    $partner->setIrszam($this->toutf($r['irszam']));
                    $partner->setVaros($this->toutf($r['varos']));
                    $partner->setUtca(getUtca($this->toutf($r['utca'])));
                    $partner->setHazszam(getHazszam($this->toutf($r['utca'])));
                    $partner->setValligszam($r['valligszam']);
                    $partner->setLirszam($this->toutf($r['pirszam']));
                    $partner->setLvaros($this->toutf($r['pvaros']));
                    $partner->setLutca(getUtca($this->toutf($r['putca'])));
                    $partner->setLhazszam(getHazszam($this->toutf($r['putca'])));
                    $partner->setKtdatalany($r['ktdatalany']);
                    $partner->setKtdatvallal($r['ktdatvallal']);
                    $partner->setKtdszerzszam($r['ktdszerzszam']);
                    \mkw\store::getEm()->persist($partner);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM kontakt');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Kontakt')->findOneBy(array('migrid' => $r['kod']))) {
                    $partner = $this->getRepo('Entities\Partner')->findOneBy(array('migrid' => $r['cegkod']));
                    if ($partner) {
                        $db++;
                        $kontakt = new \Entities\Kontakt();
                        $kontakt->setNev($this->toutf($r['nev']));
                        $kontakt->setMigrid($r['kod']);
                        $kontakt->setBeosztas($this->toutf($r['beosztas']));
                        $kontakt->setTelefon($this->toutf($r['telefon']));
                        $kontakt->setFax($this->toutf($r['fax']));
                        $kontakt->setMobil($this->toutf($r['mobil']));
                        $kontakt->setEmail($this->toutf($r['email']));
                        $kontakt->setPartner($partner);
                        \mkw\store::getEm()->persist($kontakt);
                        if (($db % 20) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM arkategoria');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Termekcsoport')->findOneBy(array('migrid' => $r['kod']))) {
                    $db++;
                    $tcs = new \Entities\Termekcsoport();
                    $tcs->setNev($this->toutf($r['nev']));
                    $tcs->setMigrid($r['kod']);
                    \mkw\store::getEm()->persist($tcs);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $stmt = $dbh->prepare('SELECT * FROM termekcsoport WHERE torolt=0 ORDER BY kod');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\TermekFa')->find($r['kod'])) {
                    $q = \mkw\store::getEm()->getConnection()->prepare('INSERT INTO termekfa '
                        . '(id,parent_id,created,nev,sorrend,karkod,inaktiv) VALUES '
                        . '('
                        . $r['kod'] . ','
                        . ($r['szulokod'] == '0' ? 'null' : $r['szulokod']) . ','
                        . 'now(),'
                        . '"' . $this->toutf($r['nev']) . '",'
                        . '0,'
                        . '"' . $r['karkod'] . '",'
                        . '0'
                        . ')'
                    );
                    $q->execute();
                }
            }
            $this->getRepo('Entities\TermekFa')->regenerateKarkod();
            $this->getRepo('Entities\TermekFa')->regenerateSlug();

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM termek WHERE (hasznalt=1) AND (kod>0)');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                if (!$this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['kod']))) {
                    $db++;
                    $t = new \Entities\Termek();
                    $t->setCikkszam($this->toutf($r['cikkszam']));
                    $t->setNev($this->toutf($r['nev']));
                    $t->setNev2($this->toutf($r['nev2']));
                    $t->setNev3($this->toutf($r['nev3']));
                    $t->setNev4($this->toutf($r['nev4']));
                    $t->setNev5($this->toutf($r['nev5']));
                    $t->setMe($this->toutf($r['me']));
                    $t->setKiszereles($r['gyujto']);
                    $t->setVtsz($this->getRepo('Entities\Vtsz')->findOneBy(array('migrid' => $r['vtsz'])));
                    $fa = $this->getRepo('Entities\TermekFa')->find($r['csoportkod']);
                    if (!$fa) {
                        $fa = $this->getRepo('Entities\TermekFa')->find(1);
                    }
                    $t->setTermekfa1($fa);
                    $t->setSzelesseg($r['meretx']);
                    $t->setHosszusag($r['merety']);
                    $t->setSuly($r['suly']);
                    $t->setSuruseg($r['anyagsuruseg']);
                    $t->setValutameszorzo($r['valutameszorzo']);
                    $arkat = $this->getRepo('Entities\Termekcsoport')->findOneBy(array('migrid' => $r['arkategoria']));
                    if ($arkat) {
                        $t->setTermekcsoport($arkat);
                    }
                    $t->setMigrid($r['kod']);
                    \mkw\store::getEm()->persist($t);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            function createptszar($r, $szam) {
                $mennysz = 'menny' . $szam;
                if ($szam == 1) {
                    $nettosz = 'netto';
                    $bruttosz = 'brutto';
                    $nettovsz = 'nettov';
                    $bruttovsz = 'bruttov';
                }
                else {
                    $nettosz = 'netto' . $szam;
                    $bruttosz = 'brutto' . $szam;
                    $nettovsz = 'nettov' . $szam;
                    $bruttovsz = 'bruttov' . $szam;
                }
                if (($r[$nettosz] * 1 > 0) || ($r[$nettovsz] * 1 > 0)) {
                    $ret = new \Entities\PartnerTermekSzerzodesAr();
                    $ret->setMennyiseg($r[$mennysz] * 1);
                    if ($r[$nettosz] * 1 > 0) {
                        $ret->setNetto($r[$nettosz] * 1);
                        $ret->setBrutto($r[$bruttosz] * 1);
                    }
                    else {
                        $ret->setNetto($r[$nettovsz] * 1);
                        $ret->setBrutto($r[$bruttovsz] * 1);
                    }
                    return $ret;
                }
                return false;
            }

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM partnerszerzodes WHERE kod>0');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {

                $partner = $this->getRepo('Entities\Partner')->findOneBy(array('migrid' => $r['partner']));
                $termek = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['termek']));

                if ($partner && $termek) {

                    $eur = $this->getRepo('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));
                    $huf = $this->getRepo('Entities\Valutanem')->findOneBy(array('nev' => 'HUF'));

                    $szerz = $this->getRepo('Entities\PartnerTermekSzerzodes')->findOneBy(array('termek' => $termek, 'partner' => $partner));

                    if (!$szerz) {
                        $szerz = new \Entities\PartnerTermekSzerzodes();
                        $szerz->setTermek($termek);
                        $szerz->setPartner($partner);
                        if ($r['ervenyestol']) {
                            $szerz->setErvenyestol(new \DateTime(\mkw\store::convDate($r['ervenyestol'])));
                        }
                        if ($r['ervenyesig']) {
                            $szerz->setErvenyesig(new \DateTime(\mkw\store::convDate($r['ervenyesig'])));
                        }
                        if ($r['netto'] * 1 > 0) {
                            $szerz->setValutanem($huf);
                        }
                        else {
                            $szerz->setValutanem($eur);
                        }
                        \mkw\store::getEm()->persist($szerz);

                        $ptszar = createptszar($r, 1);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 2);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 3);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 4);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 5);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 6);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 7);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 8);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 9);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptszar($r, 10);
                        if ($ptszar) {
                            $ptszar->setPartnertermekszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
            }

            function createptcsszar($r, $szam) {
                $mennysz = 'menny' . $szam;
                if ($szam == 1) {
                    $nettosz = 'netto';
                    $bruttosz = 'brutto';
                    $nettovsz = 'nettov';
                    $bruttovsz = 'bruttov';
                }
                else {
                    $nettosz = 'netto' . $szam;
                    $bruttosz = 'brutto' . $szam;
                    $nettovsz = 'nettov' . $szam;
                    $bruttovsz = 'bruttov' . $szam;
                }
                if (($r[$nettosz] * 1 > 0) || ($r[$nettovsz] * 1 > 0)) {
                    $ret = new \Entities\PartnerTermekcsoportSzerzodesAr();
                    $ret->setMennyiseg($r[$mennysz] * 1);
                    if ($r[$nettosz] * 1 > 0) {
                        $ret->setNetto($r[$nettosz] * 1);
                        $ret->setBrutto($r[$bruttosz] * 1);
                    }
                    else {
                        $ret->setNetto($r[$nettovsz] * 1);
                        $ret->setBrutto($r[$bruttovsz] * 1);
                    }
                    return $ret;
                }
                return false;
            }

            $db = 0;
            $stmt = $dbh->prepare('SELECT * FROM partnerszerzodes2 WHERE kod>0');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {

                $partner = $this->getRepo('Entities\Partner')->findOneBy(array('migrid' => $r['partner']));
                $termekcs = $this->getRepo('Entities\Termekcsoport')->findOneBy(array('migrid' => $r['arkategoria']));

                if ($partner && $termekcs) {

                    $eur = $this->getRepo('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));
                    $huf = $this->getRepo('Entities\Valutanem')->findOneBy(array('nev' => 'HUF'));

                    $szerz = $this->getRepo('Entities\PartnerTermekcsoportSzerzodes')->findOneBy(array('termekcsoport' => $termekcs, 'partner' => $partner));

                    if (!$szerz) {
                        $szerz = new \Entities\PartnerTermekcsoportSzerzodes();
                        $szerz->setTermekcsoport($termekcs);
                        $szerz->setPartner($partner);
                        if ($r['ervenyestol']) {
                            $szerz->setErvenyestol(new \DateTime(\mkw\store::convDate($r['ervenyestol'])));
                        }
                        if ($r['netto'] * 1 > 0) {
                            $szerz->setValutanem($huf);
                        }
                        else {
                            $szerz->setValutanem($eur);
                        }
                        \mkw\store::getEm()->persist($szerz);

                        $ptszar = createptcsszar($r, 1);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 2);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 3);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 4);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 5);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 6);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 7);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 8);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 9);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                        $ptszar = createptcsszar($r, 10);
                        if ($ptszar) {
                            $ptszar->setPartnertermekcsoportszerzodes($szerz);
                            \mkw\store::getEm()->persist($ptszar);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
            }

             */

            $db = 0;
            $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Csomagelem'));
            $stmt = $dbh->prepare('SELECT * FROM termekcsomagelem WHERE kod>0');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                $termek = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['termek']));
                $elem = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['elem']));

                if ($termek && $elem) {
                    $db++;
                    $re = new TermekRecept();
                    $re->setTermek($termek);
                    $re->setAlTermek($elem);
                    $re->setTipus($ttcsomagelem);
                    $re->setMennyiseg(($r['mennyiseg'] * 1 == 0 ? 1 : $r['mennyiseg'] * 1));
                    \mkw\store::getEm()->persist($re);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Csomagelem'));
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $db = 0;
            $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Stanckés'));
            $stmt = $dbh->prepare('SELECT * FROM termekkisegito WHERE kod>0');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                $termek = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['termek']));
                $elem = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['elem']));

                if ($termek && $elem) {
                    $db++;
                    $re = new TermekRecept();
                    $re->setTermek($termek);
                    $re->setAlTermek($elem);
                    $re->setTipus($ttcsomagelem);
                    $re->setMennyiseg(($r['mennyiseg'] * 1 == 0 ? 1 : $r['mennyiseg'] * 1));
                    \mkw\store::getEm()->persist($re);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Stanckés'));
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $db = 0;
            $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Klisé'));
            $stmt = $dbh->prepare('SELECT * FROM termekkisegito2 WHERE kod>0');
            $stmt->execute();
            while (($r = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
                $termek = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['termek']));
                $elem = $this->getRepo('Entities\Termek')->findOneBy(array('migrid' => $r['elem']));

                if ($termek && $elem) {
                    $db++;
                    $re = new TermekRecept();
                    $re->setTermek($termek);
                    $re->setAlTermek($elem);
                    $re->setTipus($ttcsomagelem);
                    $re->setMennyiseg(($r['mennyiseg'] * 1 == 0 ? 1 : $r['mennyiseg'] * 1));
                    \mkw\store::getEm()->persist($re);
                    if (($db % 20) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $ttcsomagelem = $this->getRepo('Entities\TermekReceptTipus')->findOneBy(array('nev' => 'Klisé'));
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();
        }

        echo 'kész';
    }
}
