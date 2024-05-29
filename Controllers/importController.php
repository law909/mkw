<?php

namespace Controllers;

use Entities\Afa;
use Entities\ME;
use Entities\Partner;
use Entities\Termek;
use Entities\Termekcimkekat;
use Entities\Termekcimketorzs;
use Entities\TermekFa;
use Entities\TermekValtozat;
use Entities\Vtsz;
use mkw\store;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\DomCrawler\Crawler;

class importController extends \mkwhelpers\Controller
{

    private $settings;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->settings = [
            'quality' => 80,
            'sizes' => ['100' => '100x100', '150' => '150x150', '250' => '250x250', '1000' => '1000x800']
        ];
    }

    private function toutf($mit)
    {
        return mb_convert_encoding($mit, 'UTF8', 'ISO-8859-2');
    }

    private function n($mit)
    {
        return ord($mit) - 97;
    }

    private function urlkatnev($nev)
    {
        if ($nev) {
            return $nev . DIRECTORY_SEPARATOR;
        }
        return '';
    }

    private function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
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

    public function view()
    {
        $view = $this->createView('imports.tpl');

        $view->setVar('pagetitle', t('Importok'));

        $termekfa = \mkw\store::getEm()->getRepository(TermekFa::class)->find(\mkw\store::getParameter(\mkw\consts::ImportNewKatId));
        if ($termekfa) {
            $view->setVar('termekfa', [
                'id' => $termekfa->getId(),
                'caption' => $termekfa->getNev()
            ]);
        } else {
            $view->setVar('termekfa', [
                'id' => null,
                'caption' => t('Ebbe a kategóriába kerüljenek a termékek')
            ]);
        }

        $view->printTemplateResult(false);
    }

    public function repairFuggoTermek($gyartoid)
    {
        $parentid = \mkw\store::getParameter(\mkw\consts::ImportNewKatId);
        $parent = $this->getRepo('Entities\TermekFa')->find($parentid);

        $conn = \mkw\store::getEm()->getConnection();
        if ($parent) {
            $st2 = $conn->prepare(
                'UPDATE termek SET inaktiv=0, fuggoben=0 WHERE gyarto_id=' . $gyartoid . ' and termekfa1karkod not like \'' . $parent->getKarkod() . '%\''
            );
            $st2->executeStatement();
        }
    }

    public function createKategoria($nev, $parentid)
    {
        $me = \mkw\store::getEm()->getRepository(TermekFa::class)->findBy(['nev' => $nev, 'parent' => $parentid]);

        if ($nev) {
            if (!$me || $me[0]->getParentId() !== $parentid) {
                $parent = \mkw\store::getEm()->getRepository(TermekFa::class)->find($parentid);
                $me = new \Entities\TermekFa();
                $me->setNev($nev);
                $me->setParent($parent);
                $me->setMenu1lathato(false);
                $me->setMenu2lathato(false);
                $me->setMenu3lathato(false);
                $me->setMenu4lathato(false);
                \mkw\store::getEm()->persist($me);
                \mkw\store::getEm()->flush();
            } else {
                $me = $me[0];
            }
            return $me;
        } else {
            return \mkw\store::getEm()->getRepository(TermekFa::class)->find($parentid);
        }
    }

    private function getME($me)
    {
        $meobj = \mkw\store::getEm()->getRepository(ME::class)->findOneBy(['nev' => $me]);
        return $meobj;
    }

    private function createME($me)
    {
        $meobj = $this->getME($me);
        if (!$meobj) {
            $meobj = new \Entities\ME();
            $meobj->setNev($me);
            \mkw\store::getEm()->persist($meobj);
            \mkw\store::getEm()->flush();
        }
    }

    public function getKategoriaByIdegenkod($ik)
    {
        $me = \mkw\store::getEm()->getRepository(TermekFa::class)->findBy(['idegenkod' => $ik]);
        if (!$me) {
            $me = \mkw\store::getEm()->getRepository(TermekFa::class)->find(1);
        }
        return $me[0];
    }

    public function createTermekValtozatAdatTipus($nev)
    {
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

    public function createVtsz($szam, $afa)
    {
        $res = \mkw\store::getEm()->getRepository(Vtsz::class)->findBySzam($szam);
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

    public function createAfa($afa)
    {
        $res = \mkw\store::getEm()->getRepository(Afa::class)->findByErtek($afa);
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

    private function createPartnerCimke($ckat, $nev)
    {
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

    private function createTermekCimkeKat($nev)
    {
        if (!$nev) {
            return null;
        }
        $kat = \mkw\store::getEm()->getRepository(Termekcimkekat::class)->findOneBy(['nev' => $nev]);
        if (!$kat) {
            $kat = new \Entities\Termekcimkekat();
            $kat->setNev($nev);
            $kat->setTermeklaponlathato(true);
            $kat->setLathato(true);
            $kat->setTermekszurobenlathato(true);
            $kat->setTermeklistabanlathato(true);
            \mkw\store::getEm()->persist($kat);
            \mkw\store::getEm()->flush($kat);
        }
        return $kat;
    }

    private function createTermekCimke($ckat, $nev)
    {
        if (!$nev || !$ckat) {
            return null;
        }
        $cimke1 = \mkw\store::getEm()->getRepository(Termekcimketorzs::class)->getByNevAndKategoria($nev, $ckat);
        if (!$cimke1) {
            $cimke1 = new \Entities\Termekcimketorzs();
            $cimke1->setKategoria($ckat);
            $cimke1->setNev($nev);
            $cimke1->setMenu1lathato(false);
            \mkw\store::getEm()->persist($cimke1);
            \mkw\store::getEm()->flush($cimke1);
        }
        return $cimke1;
    }

    private function checkRunningImport($imp)
    {
        return (boolean)\mkw\store::getParameter($imp);
    }

    private function setRunningImport($imp, $onoff)
    {
        \mkw\store::setParameter($imp, $onoff);
    }

    public function stop()
    {
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
            case 'legavenue':
                $imp = \mkw\consts::RunningLegavenueImport;
                break;
            case 'nomad':
                $imp = \mkw\consts::RunningNomadImport;
                break;
            case 'nika':
                $imp = \mkw\consts::RunningNikaImport;
                break;
            case 'haffner24':
                $imp = \mkw\consts::RunningHaffner24Import;
                break;
            case 'evona':
                $imp = \mkw\consts::RunningEvonaImport;
                break;
            case 'evonaxml':
                $imp = \mkw\consts::RunningEvonaXMLImport;
                break;
            case 'gulf':
                $imp = \mkw\consts::RunningGulfImport;
                break;
            case 'smileebike':
                $imp = \mkw\consts::RunningSmileebikeImport;
                break;
            case 'copydepotermek':
                $imp = \mkw\consts::RunningCopydepoTermekImport;
                break;
            case 'copydepokeszlet':
                $imp = \mkw\consts::RunningCopydepoKeszletImport;
                break;
            default:
                $imp = false;
                break;
        }

        if ($imp) {
            $this->setRunningImport($imp, 0);
        }
    }

    public function repair()
    {
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
            case 'legavenue':
                $imp = \mkw\consts::GyartoLegavenue;
                break;
            case 'nomad':
                $imp = \mkw\consts::GyartoNomad;
                break;
            case 'nika':
                $imp = \mkw\consts::GyartoNika;
                break;
            case 'haffner24':
                $imp = \mkw\consts::GyartoHaffner24;
                break;
            case 'evona':
                $imp = \mkw\consts::GyartoEvona;
                break;
            case 'gulf':
                $imp = \mkw\consts::GyartoGulf;
                break;
            case 'smileebike':
                $imp = \mkw\consts::GyartoSmileebike;
                break;
            default:
                $imp = false;
                break;
        }

        if ($imp) {
            $this->repairFuggoTermek(\mkw\store::getParameter($imp));
        }
    }

    public function kreativpuzzleImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningKreativImport)) {
            $this->setRunningImport(\mkw\consts::RunningKreativImport, 1);

            $sep = ';';
            $logfile = 'kreativ_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

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

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlKreativ));
            $fh = fopen(\mkw\store::storagePath('kreativpuzzlestock.txt'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlKreativImages));
            $fh = fopen(\mkw\store::storagePath('kreativpuzzleimages.txt'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);

            $imagelist = [];
            $fh = fopen(\mkw\store::storagePath('kreativpuzzleimages.txt'), 'r');
            if ($fh) {
                fgetcsv($fh, 0, $sep, '"');
                while ($data = fgetcsv($fh, 0, $sep, '"')) {
                    if ($data[$this->n('a')]) {
                        if (array_key_exists($data[$this->n('a')], $imagelist)) {
                            $imagelist[$data[$this->n('a')]][] = $data[$this->n('b')];
                        } else {
                            $imagelist[$data[$this->n('a')]] = [$data[$this->n('b')]];
                        }
                    }
                }
            }
            fclose($fh);


            $linecount = 0;
            $fh = fopen(\mkw\store::storagePath('kreativpuzzlestock.txt'), 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen(\mkw\store::storagePath('kreativpuzzlestock.txt'), 'r');
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
                        if ((int)$data[$this->n('c')] > 0) {
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
                    $lettlog = false;
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                        if ((int)$data[$this->n('c')] > 0) {
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
                                    $termek->setMekod($this->getME('db'));
                                    $termek->setNev($termeknev);
                                    $termek->setLeiras($hosszuleiras);
                                    $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                    $termek->setCikkszam($data[$this->n('a')]);
                                    $termek->setIdegencikkszam($data[$this->n('a')]);
                                    $termek->setIdegenkod($idegenkod);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(3);
                                    $termek->setVonalkod($data[$this->n('o')]);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                    // kepek
                                    if (array_key_exists($data[$this->n('a')], $imagelist)) {
                                        $imgcnt = 0;
                                        foreach ($imagelist[$data[$this->n('a')]] as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegenkod);
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
                                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb(
                                                    $imgpath,
                                                    $newFilePath,
                                                    (int)$matches[0],
                                                    (int)$matches[1],
                                                    $this->settings['quality'],
                                                    true
                                                );
                                            }
                                            if (((count($imagelist[$data[$this->n('a')]]) > 1) && ($imgcnt == 1)) || (count(
                                                        $imagelist[$data[$this->n('a')]]
                                                    ) == 1)) {
                                                $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                $termek->setKepleiras($termeknev);
                                            } else {
                                                $kep = new \Entities\TermekKep();
                                                $termek->addTermekKep($kep);
                                                $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                $kep->setLeiras($termeknev);
                                                \mkw\store::getEm()->persist($kep);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $termek = $termek[0];
                                if (!$termek->getVonalkod()) {
                                    $termek->setVonalkod($data[$this->n('o')]);
                                }
                                if ($editleiras) {
                                    $hosszuleiras = $this->toutf(trim($data[$this->n('n')]));
                                    $termek->setLeiras($hosszuleiras);
                                    //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                    //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                }
                            }
                            if ($termek) {
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato((int)$data[$this->n('g')] === 0);
                                    if ($termek->getNemkaphato()) {
                                        \mkw\store::writelog(
                                            'NEM KAPHATÓ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                }
                                if (!$termek->getAkcios()) {
                                    $termek->setNetto((float)$data[$this->n('d')] * $arszaz / 100);
                                    $termek->setBrutto(round($termek->getBrutto(), -1));
                                }
                                \mkw\store::getEm()->persist($termek);
                            }
                        } else {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[$this->n('a')]);
                            if ($termek) {
                                $termek = $termek[0];
                                if (!$termek->getVonalkod()) {
                                    $termek->setVonalkod($data[$this->n('o')]);
                                    \mkw\store::getEm()->persist($termek);
                                }
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(true);
                                    $termek->setLathato(false);
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ | NEM LÁTHATÓ'
                                        . ' cikkszám: ' . $termek->getCikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
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
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    if ($gyarto) {
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        $idegenkodok = [];
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            $idegenkodok[] = 'KP' . $data[0];
                        }
                        $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                        foreach ($termekek as $t) {
                            if (!in_array($t['idegenkod'], $idegenkodok)) {
                                /** @var \Entities\Termek $termek */
                                $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                if ($termek && $termek->getKeszlet() <= 0) {
                                    $termek->setInaktiv(true);
                                    \mkw\store::writelog(
                                        'INAKTÍV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                    \mkw\store::getEm()->persist($termek);
                                    \mkw\store::getEm()->flush();
                                }
                            }
                        }
                    }
                    if ($lettlog) {
                        echo json_encode(['url' => $logurl]);
                    }
                }
                fclose($fh);
            } else {
                echo json_encode(['url' => \mkw\store::storageUrl('kreativpuzzlestock.txt')]);
            }
            $this->setRunningImport(\mkw\consts::RunningKreativImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    private function fgetdeltoncsv($handle)
    {
        $escaped = false;
        $enclosed = false;
        $vege = false;
        $str = '';
        while (!$vege && (false !== ($char = fgetc($handle)))) {
            switch ($char) {
                case "\n":
                    if ($escaped || $enclosed) {
                        $str .= $char;
                    } else {
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

    public function deltonImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningDeltonImport)) {
            $this->setRunningImport(\mkw\consts::RunningDeltonImport, 1);

            $minarszaz = 120;

            $logfile = 'delton_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

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

            if ($deltondownload) {
                \unlink(\mkw\store::storagePath('delton.txt'));
                $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlDelton));
                $fh = fopen(\mkw\store::storagePath('delton.txt'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
            }

            $linecount = 0;
            $fh = fopen(\mkw\store::storagePath('delton.txt'), 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = $this->fgetdeltoncsv($fh))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen(\mkw\store::storagePath('delton.txt'), 'r');
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
                            } elseif ($data[5] && $data[5] !== '"') {
                                $katnev = trim($data[5]);
                            } elseif ($data[4] && $data[4] !== '"') {
                                $katnev = trim($data[4]);
                            }
                            $parent = $this->createKategoria($katnev, $parentid);
                            $this->createME(trim($data[9]));
                        }
                    }

                    rewind($fh);
                    $termekdb = 0;

                    // $this->fgetdeltoncsv($fh);
                    while (($termekdb < $dbtol) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                    }
                    $lettlog = false;
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = $this->fgetdeltoncsv($fh))) {
                        $termekdb++;
                        if ($data[1]) {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('DT' . $data[1]);
                            if (!$termek) {
                                if ($createuj && (substr($data[11], -6) !== 'rkezik') && (substr($data[11], 0, 6) !== 'rendel')) {
                                    if ($data[6] && $data[6] !== '"') {
                                        $katnev = trim($data[6]);
                                    } elseif ($data[5] && $data[5] !== '"') {
                                        $katnev = trim($data[5]);
                                    } elseif ($data[4] && $data[4] !== '"') {
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
                                    $termek->setMekod($this->getME(trim($data[9])));
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
                                    $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegenkod);
                                    $kepnev = \mkw\store::urlize($termeknev . '_' . $idegenkod);

                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb(
                                            $imgpath,
                                            $newFilePath,
                                            (int)$matches[0],
                                            (int)$matches[1],
                                            $this->settings['quality'],
                                            true
                                        );
                                    }
                                    $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                    $termek->setKepleiras($termeknev);
                                    \mkw\store::writelog(
                                        'ÚJ TERMÉK'
                                        . ' termék név: ' . $termek->getNev()
                                        . ' termék cikkszám: ' . $termek->getCikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            } else {
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
                                    } elseif ($data[5] && $data[5] !== '"') {
                                        $katnev = trim($data[5]);
                                    } elseif ($data[4] && $data[4] !== '"') {
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
                                        \mkw\store::writelog(
                                            'NEM KAPHATÓ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    } else {
                                        $termek->setNemkaphato(false);
                                    }
                                } else {
                                    $termek->setNemkaphato(false);
                                }
                                if (!$termek->getAkcios()) {
                                    $kiskerar = (float)$data[7];
                                    $nagykerar = (float)$data[8];
                                    if (
                                        ($kiskerar < 50000) &&
                                        (
                                            ($kiskerar / $nagykerar * 100 < $minarszaz) ||
                                            ($kiskerar / ($nagykerar * $arszaz / 100) * 100 < $minarszaz)
                                        )
                                    ) {
                                        $termek->setNetto($nagykerar * $minarszaz / 100);
                                    } else {
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
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    if ($gyarto) {
                        rewind($fh);
                        $idegenkodok = [];
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
                                        \mkw\store::writelog(
                                            'INAKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                        if (($termekdb % $batchsize) === 0) {
                                            \mkw\store::getEm()->flush();
                                            \mkw\store::getEm()->clear();
                                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                                        }
                                    }
                                }
                            }
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    if ($lettlog) {
                        echo json_encode(['url' => $logurl]);
                    }
                }
                fclose($fh);
            } else {
                echo json_encode(['url' => \mkw\store::storageUrl('delton.txt')]);
            }
            $this->setRunningImport(\mkw\consts::RunningDeltonImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function nomadImport()
    {
        function toArr($obj)
        {
            return [
                'sku' => (string)$obj->sku,
                'name' => (string)$obj->name,
                'brand' => (string)$obj->brand,
                'price' => (int)$obj->price,
                'photo' => (string)$obj->photo,
                'catalog_first' => (string)$obj->catalog_first,
                'catalog_second' => (string)$obj->catalog_second,
                'size' => (string)$obj->size,
                'color' => (string)$obj->color,
                'available' => (int)$obj->available,
                'parent' => (string)$obj->parent,
                'child' => (string)$obj->child,
                'category' => (string)$obj->category,
                'short_description' => (string)$obj->short_description,
                'long_description' => (string)$obj->long_description
            ];
        }

        function keres($mit, $miben)
        {
            $dbig = count($miben);
            $termekdb = 0;
            $megvan = false;
            while (($dbig && ($termekdb < $dbig)) && !$megvan) {
                $megvan = ((string)$miben[$termekdb]->sku) == $mit;
                $termekdb++;
            }
            return $megvan;
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningNomadImport)) {
            $this->setRunningImport(\mkw\consts::RunningNomadImport, 1);

            $logfile = 'nomad_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

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

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlNomad));
            $fh = fopen(\mkw\store::storagePath('nomad.xml'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);

            $xml = simplexml_load_file(\mkw\store::storagePath("nomad.xml"));
            if ($xml) {
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $szulok = [];
                $gyereklist = [];
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

                $lettlog = false;

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $szulogyerek = array_key_exists($data['sku'], $szulok) || array_key_exists($data['sku'], $gyereklist);
                    if (!$szulogyerek) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $data['sku'], 'gyarto' => $gyartoid]);

                        if (!$termek) {
                            if ($createuj) {
//                                \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new', 'nomadimport.log');

                                $urlkatnev = \mkw\store::urlize($data['category']);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $parent = $this->createKategoria($data['category'], $parentid);
                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMekod($this->getME('db'));
                                $termek->setNev($data['name']);
                                $termek->setIdegencikkszam($data['sku']);
                                if ($data['catalog_first']) {
                                    $termek->setCikkszam($data['catalog_first']);
                                } elseif ($data['catalog_second']) {
                                    $termek->setCikkszam($data['catalog_second']);
                                }
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ]);
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
                                $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                $extension = \mkw\store::getExtension($imgurl);
                                $imgpath = $nameWithoutExt . '.' . $extension;

                                $ch = \curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                \curl_setopt($ch, CURLOPT_FILE, $ih);
                                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                \curl_exec($ch);
                                fclose($ih);

                                foreach ($this->settings['sizes'] as $k => $size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x', $size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                                }
                                $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                $termek->setKepleiras($data['name']);
                            }
                        } else {
                            /** @var \Entities\Termek $termek */
//                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - edit', 'nomadimport.log');
                            $termek = $termek[0];
                            if ($editleiras) {
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ]);
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
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ'
                                        . ' termék cikkszám: ' . $termek->getCikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                } else {
                                    $termek->setNemkaphato(false);
                                    if ($termek->getInaktiv()) {
                                        \mkw\store::writelog(
                                            'ÚJRA AKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' ' . $termek->getNev(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                    $termek->setInaktiv(false);
                                }
                            } else {
                                $termek->setNemkaphato(false);
                                if ($termek->getInaktiv()) {
                                    \mkw\store::writelog(
                                        'ÚJRA AKTÍV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                        . ' ' . $termek->getNev(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                                $termek->setInaktiv(false);
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
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $szulo = array_key_exists($data['sku'], $szulok);
                    if ($szulo) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $data['sku'], 'gyarto' => $gyartoid]);

                        if (!$termek) {
                            if ($createuj) {
//                                \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - new - parent', 'nomadimport.log');

                                $urlkatnev = \mkw\store::urlize($data['category']);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $parent = $this->createKategoria($data['category'], $parentid);
                                $termek = new \Entities\Termek();
                                $termek->setIdegencikkszam($data['sku']);
                                $termek->setFuggoben(true);
                                $termek->setMekod($this->getME('db'));
                                $termek->setNev($data['name']);
                                if ($data['catalog_first']) {
                                    $termek->setCikkszam($data['catalog_first']);
                                } elseif ($data['catalog_second']) {
                                    $termek->setCikkszam($data['catalog_second']);
                                }
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ]);
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
                                $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                $extension = \mkw\store::getExtension($imgurl);
                                $imgpath = $nameWithoutExt . '.' . $extension;

                                $ch = \curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                \curl_setopt($ch, CURLOPT_FILE, $ih);
                                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                \curl_exec($ch);
                                fclose($ih);
                                \curl_close($ch);

                                foreach ($this->settings['sizes'] as $k => $size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x', $size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                                }
                                $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                $termek->setKepleiras($data['name']);

                                $valtozat = new \Entities\TermekValtozat();
                                $valtozat->setAdatTipus1(
                                    $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin))
                                );
                                $valtozat->setErtek1($data['color']);
                                $valtozat->setAdatTipus2(
                                    $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret))
                                );
                                $valtozat->setErtek2($data['size']);
                                $valtozat->setIdegencikkszam($data['sku']);
                                if ($data['catalog_first']) {
                                    $valtozat->setCikkszam($data['catalog_first']);
                                } elseif ($data['catalog_second']) {
                                    $valtozat->setCikkszam($data['catalog_second']);
                                }
                                $valtozat->setTermek($termek);
                                if (!$data['available']) {
                                    $valtozat->setElerheto(false);
                                    \mkw\store::writelog(
                                        'NEM ELÉRHETŐ'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                } else {
                                    $valtozat->setElerheto(true);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                            }
                        } else {
                            /** @var \Entities\Termek $termek */
//                            \mkw\store::writelog($data['sku'] . '|' . $data['name'] . ' - edit - parent', 'nomadimport.log');
                            $termek = $termek[0];
                            if ($editleiras) {
                                $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                    'HTML.Allowed' => 'p,ul,li,b,strong,br'
                                ]);
                                $hosszuleiras = $puri->sanitize(trim($data['long_description']));

                                $puri2 = \mkw\store::getSanitizer();
                                $rovidleiras = $puri2->sanitize(trim($data['short_description']));
                                $termek->setLeiras('<p>' . $rovidleiras . '</p>' . $hosszuleiras);
                                //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            }
                            $valtozat = null;
                            /** @var \Entities\TermekValtozat $v */
                            foreach ($termek->getValtozatok() as $v) {
                                if ($v->getIdegencikkszam() == $data['sku']) {
                                    $valtozat = $v;
                                }
                            }
                            if ($valtozat) {
                                if (!$data['available']) {
                                    if ($valtozat->getKeszlet() <= 0) {
                                        $valtozat->setElerheto(false);
                                        \mkw\store::writelog(
                                            'NEM ELÉRHETŐ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    } else {
                                        $valtozat->setElerheto(true);
                                        if ($termek->getInaktiv()) {
                                            \mkw\store::writelog(
                                                'ÚJRA AKTÍV'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                        $termek->setNemkaphato(false);
                                        $termek->setInaktiv(false);
                                    }
                                } else {
                                    $valtozat->setElerheto(true);
                                    if ($termek->getInaktiv()) {
                                        \mkw\store::writelog(
                                            'ÚJRA AKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                    $termek->setNemkaphato(false);
                                    $termek->setInaktiv(false);
                                }
                                \mkw\store::getEm()->persist($valtozat);
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
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);

                    $gyerek = array_key_exists($data['sku'], $gyereklist);
                    if ($gyerek) {
                        $termek = false;
                        $valtozat = false;
                        $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(['idegencikkszam' => $data['sku']]);
                        if ($valtozatok) {
                            foreach ($valtozatok as $v) {
                                $termek = $v->getTermek();
                                if ($termek && $termek->getGyartoId() == $gyartoid) {
                                    $valtozat = $v;
                                    break;
                                }
                            }
                            if (!$valtozat) {
                                $termek = false;
                            }
                        }
                        if (!$valtozat) {
                            $termek = $this->getRepo('Entities\Termek')->findBy(['idegencikkszam' => $data['parent'], 'gyarto' => $gyartoid]);
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
                                    $valtozat->setAdatTipus1(
                                        $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin))
                                    );
                                    $valtozat->setErtek1($data['color']);
                                    $valtozat->setAdatTipus2(
                                        $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret))
                                    );
                                    $valtozat->setErtek2($data['size']);
                                    $valtozat->setIdegencikkszam($data['sku']);
                                    if ($data['catalog_first']) {
                                        $valtozat->setCikkszam($data['catalog_first']);
                                    } elseif ($data['catalog_second']) {
                                        $valtozat->setCikkszam($data['catalog_second']);
                                    }
                                    $valtozat->setTermek($termek);

                                    // kepek

                                    $imgurl = trim($data['photo']);
                                    $imgurl = str_replace('http://', 'http:/', $imgurl);
                                    $imgurl = str_replace('http:/', 'http://', $imgurl);
                                    $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                    $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    \curl_exec($ch);
                                    fclose($ih);
                                    \curl_close($ch);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb(
                                            $imgpath,
                                            $newFilePath,
                                            (int)$matches[0],
                                            (int)$matches[1],
                                            $this->settings['quality'],
                                            true
                                        );
                                    }
                                    if (!$termek->getKepurl()) {
                                        $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                        $termek->setKepleiras($data['name']);
                                        $valtozat->setTermekfokep(true);
                                    } else {
                                        $tkep = new \Entities\TermekKep();
                                        $termek->addTermekKep($tkep);
                                        $tkep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
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
                                        \mkw\store::writelog(
                                            'NEM ELÉRHETŐ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    } else {
                                        $valtozat->setElerheto(true);
                                        if ($termek->getInaktiv()) {
                                            \mkw\store::writelog(
                                                'ÚJRA ELÉRHETŐ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                        $termek->setInaktiv(false);
                                        $termek->setNemkaphato(false);
                                    }
                                } else {
                                    $valtozat->setElerheto(true);
                                    if ($termek->getInaktiv()) {
                                        \mkw\store::writelog(
                                            'ÚJRA ELÉRHETŐ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                    $termek->setInaktiv(false);
                                    $termek->setNemkaphato(false);
                                }
                                \mkw\store::getEm()->persist($valtozat);
                            }
                            if (!$termek->getAkcios()) {
                                $termek->setBrutto($data['price']);
                            }
                            \mkw\store::getEm()->persist($termek);
                        } else {
                            /**
                             * \mkw\store::writelog(
                             * 'NO PARENT'
                             * . ' ' . $data['sku'] . '|' . $data['name'],
                             * $logfile
                             * );
                             * $lettlog = true;
                             **/
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
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $szulok = null;
                $gyereklist = null;

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
                                            \mkw\store::writelog(
                                                'NEM ELÉRHETŐ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                            $valtozat->setElerheto(false);
                                            \mkw\store::getEm()->persist($valtozat);
                                        }
                                    }
                                } elseif (keres($valtozat->getIdegencikkszam(), $products)) {
                                    \mkw\store::writelog(
                                        'ÚJRA ELÉRHETŐ'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                        . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                        . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                    $valtozat->setElerheto(true);
                                    \mkw\store::getEm()->persist($valtozat);
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
                                        \mkw\store::writelog(
                                            'INAKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                        $termek->setInaktiv(true);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            } elseif ($termek->getInaktiv()) {
                                $vanelerhetovaltozat = false;
                                $vkvk = $termek->getValtozatok();
                                /** @var \Entities\TermekValtozat $valtozat */
                                foreach ($vkvk as $valtozat) {
                                    if ($valtozat->getElerheto()) {
                                        $vanelerhetovaltozat = true;
                                    }
                                }
                                if ($vanelerhetovaltozat) {
                                    \mkw\store::writelog(
                                        'ÚJRA AKTÍV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                    $termek->setInaktiv(true);
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                            $termekdb++;
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
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                }
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }

            $this->setRunningImport(\mkw\consts::RunningNomadImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function nikaImport()
    {
        function toArr($obj)
        {
            $xmlkepek = (array)$obj->images;
            $kepek = [];
            foreach ($xmlkepek as $xk) {
                $kepek[] = $xk;
            }
            $xmleans = (array)$obj->eanCodes;
            return [
                'number' => (string)$obj->number,
                'name' => (string)$obj->name,
                'manufacturerName' => (string)$obj->manufacturerName,
                'available' => (int)$obj->available,
                'storageCondition' => (int)$obj->storageCondition,
                'unitType' => (string)$obj->unitType,
                'cbsNumber' => (string)$obj->cbsNumber,
                'mainGroupCode' => (string)$obj->mainGroupCode,
                'mainGroupName' => (string)$obj->mainGroupName,
                'productGroupCode' => (string)$obj->productGroupCode,
                'productGroupName' => (string)$obj->productGroupName,
                'descriptionShort' => (string)$obj->descriptionShort,
                'description' => (string)$obj->description,
                'weight' => (string)$obj->weight,
                'sizeX' => (string)$obj->sizeX,
                'sizeY' => (string)$obj->sizeY,
                'sizeZ' => (string)$obj->sizeZ,
                'taxRate' => (string)$obj->taxRate,
                'manufacturerNumber' => (string)$obj->manufacturerNumber,
                'stockFree' => (string)$obj->stockFree,
                'stockAvailableDate' => (string)$obj->stockAvailableDate,
                'stockOuterType' => (string)$obj->stockOuterType,
                'priceMembership' => (string)$obj->prices->priceMembership,
                'images' => $kepek,
                'ean' => $xmleans[0]
            ];
        }

        function keres($mit, $miben)
        {
            $dbig = count($miben);
            $termekdb = 0;
            $megvan = false;
            while (($dbig && ($termekdb < $dbig)) && !$megvan) {
                $megvan = ((string)$miben[$termekdb]->number) == $mit;
                $termekdb++;
            }
            return $megvan;
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningNikaImport)) {
            $this->setRunningImport(\mkw\consts::RunningNikaImport, 1);

            $logfile = 'nika_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoNika);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $editnev = $this->params->getBoolRequestParam('editnev', false);
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

            @unlink(\mkw\store::storagePath('nikaproducts.xml'));

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlNika));
            $fh = fopen(\mkw\store::storagePath('nikaproducts.xml'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);

            $xml = simplexml_load_file(\mkw\store::storagePath("nikaproducts.xml"));
            if ($xml) {
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $termekek = [];
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
                        } else {
                            $this->createKategoria($_t['productGroupName'], $parentid);
                        }
                    }
                    if ($_t['cbsNumber'] && $_t['taxRate']) {
                        $afa = $this->createAfa($_t['taxRate']);
                        $this->createVtsz($_t['cbsNumber'], $afa);
                    }
                    $this->createME($_t['unitType']);
                }

                $lettlog = false;

                foreach ($termekek as $data) {
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegenkod' => $data['number'], 'gyarto' => $gyartoid]);

                    if (!$termek) {
                        if ($createuj) {
                            $urlkatnev = \mkw\store::urlize($data['mainGroupName'] . '-' . $data['productGroupName']);
                            \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                            $afa = $this->createAfa($data['taxRate']);
                            $vtsz = $this->createVtsz($data['cbsNumber'], $afa);
                            $parent = null;
                            if ($data['mainGroupName']) {
                                $parent = $this->createKategoria($data['mainGroupName'], $parentid);
                            }
                            if ($data['productGroupName']) {
                                if ($parent) {
                                    $parent = $this->createKategoria($data['productGroupName'], $parent->getId());
                                } else {
                                    $parent = $this->createKategoria($data['productGroupName'], $parentid);
                                }
                            }
                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMekod($this->getME($data['unitType']));
                            if ($data['manufacturerName']) {
                                $termek->setNev($data['manufacturerName'] . ' ' . $data['name']);
                            } else {
                                $termek->setNev($data['name']);
                            }
                            $termek->setIdegenkod($data['number']);
                            $termek->setIdegencikkszam($data['manufacturerNumber']);
                            $termek->setCikkszam($data['manufacturerNumber']);
                            $termek->setVonalkod($data['ean']);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ]);
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortDescription']));
                            $termek->setLeiras('<p>' . $hosszuleiras . '</p>');
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
                                $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['number']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['number']);

                                $parsedpath = parse_url($imgurl, PHP_URL_PATH);
                                $extension = \mkw\store::getExtension($parsedpath);
                                if ($extension) {
                                    $imgpath = $nameWithoutExt . '.' . $extension;
                                } else {
                                    $imgpath = $nameWithoutExt;
                                }

                                $ih = fopen($imgpath, 'w');
                                if ($ih) {
                                    $ch = \curl_init($imgurl);
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    $curlretval = \curl_exec($ch);
                                    fclose($ih);
                                    if (($curlretval !== false) && (!\curl_errno($ch))) {
                                        switch ($http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                                            case 200:
                                                foreach ($this->settings['sizes'] as $k => $size) {
                                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                    $matches = explode('x', $size);
                                                    \mkw\thumbnail::createThumb(
                                                        $imgpath,
                                                        $newFilePath,
                                                        (int)$matches[0],
                                                        (int)$matches[1],
                                                        $this->settings['quality'],
                                                        true
                                                    );
                                                }
                                                if ($elso) {
                                                    $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                    $termek->setKepleiras($data['name']);
                                                }
                                                $elso = false;
                                                break;
                                            default:
                                                \mkw\store::writelog(
                                                    'ELÉRHETETLEN KÉP'
                                                    . ' ' . $http_code . ' :'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                    . ' url: ' . $imgurl,
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                break;
                                        }
                                    } else {
                                        \mkw\store::writelog(
                                            'ELÉRHETETLEN KÉP'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' url: ' . $imgurl,
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                } else {
                                    \mkw\store::writelog(
                                        'HIBÁS KÉP NÉV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                        . ' url: ' . $imgurl,
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            }
                        }
                    } else {
                        /** @var \Entities\Termek $termek */
                        $termek = $termek[0];
                        if (!$termek->getVonalkod()) {
                            $termek->setVonalkod($data['ean']);
                        }
                        if ($editleiras) {
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ]);
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortDescription']));
                            $termek->setLeiras('<p>' . $hosszuleiras . '</p>');
                        }
                        if ($editnev) {
                            if ($data['manufacturerName']) {
                                $termek->setNev($data['manufacturerName'] . ' ' . $data['name']);
                            } else {
                                $termek->setNev($data['name']);
                            }
                        }
                    }
                    if ($termek) {
                        // if ($data['available'] == 2 && $data['storageCondition'] == 2) {
                        if ($data['stockFree'] <= 0) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setNemkaphato(true);
                                \mkw\store::writelog(
                                    'NEM KAPHATÓ'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                        } else {
                            $termek->setNemkaphato(false);
                        }
                        if (!$termek->getAkcios()) {
                            $termek->setNetto((float)$data['priceMembership']);
                            $termek->setBrutto(round($termek->getBrutto() * $arszaz / 100, -1));
                        }
                        \mkw\store::getEm()->persist($termek);
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
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

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
                                        \mkw\store::writelog(
                                            'INAKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
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
                            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                }
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }

            $this->setRunningImport(\mkw\consts::RunningNikaImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function haffner24Import()
    {
        function toArr($obj)
        {
            $x = (array)$obj->categories;
            if (is_array($x['cat'])) {
                $x = $x['cat'][0];
            } else {
                $x = $x['cat'];
            }

            $desc = (string)$obj->description[0];
            if (!$desc) {
                $desc = (string)$obj->description[1];
            }
            $desc = str_replace(['&lt,', '&gt,'], ['&lt;', '&gt;'], $desc);
            $desc = html_entity_decode($desc);

            $images = [];
            foreach ($obj->image->img as $img) {
                $images[] = (string)$img;
            }
            return [
                'sku' => (string)$obj->sku,
                'name' => (string)$obj->name,
                'id' => (int)$obj->id,
                'taxrate' => (int)$obj->tax_rate,
                'manufacturer' => (string)$obj->manufacturer,
                'category' => $x,
                'description' => $desc,
                'shortdescription' => (string)$obj->short_description,
                'price' => (float)$obj->basic_price,
                'discountprice' => (float)$obj->discount_price,
                'compatibledevices' => (string)$obj->compatible_devices,
                'stock' => (int)$obj->stock,
                'emailnotify' => (int)$obj->email_notify,
                'available' => (int)$obj->available,
                'prodtype' => (string)$obj->prod_type,
                'prodstart' => (string)$obj->prod_start,
                'imageurl' => (string)$obj->image_url,
                'images' => $images
            ];
        }

        function keres($mit, $miben)
        {
            $dbig = $miben->count();
            $termekdb = 0;
            $megvan = false;
            while (($dbig && ($termekdb < $dbig)) && !$megvan) {
                $megvan = ((string)$miben[$termekdb]->sku) == $mit;
                if ($megvan) {
                    $ret = $miben[$termekdb];
                }
                $termekdb++;
            }
            if (!$megvan) {
                return false;
            }
            return $ret;
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningHaffner24Import)) {
            $this->setRunningImport(\mkw\consts::RunningHaffner24Import, 1);

            $logfile = 'haffner24_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoHaffner24);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $editnev = $this->params->getBoolRequestParam('editnev', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $addimages = true;
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);
            $minimumar = $this->params->getNumRequestParam('minimumar', 490);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathHaffner24));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathHaffner24));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urlkatnev = '';

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlHaffner24));
            $fh = fopen(\mkw\store::storagePath('haffner24products.xml'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);

            $xml = simplexml_load_file(\mkw\store::storagePath("haffner24products.xml"));
            if ($xml) {
                if (!$dbig) {
                    $dbig = $xml->product->count();
                }

                $termekdb = $dbtol;
                $termekek = [];
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($xml->product[$termekdb]);
                    if (($data['stock'] > 0) || (($data['stock'] <= 0) && ($data['emailnotify'] == 1))) {
                        $termekek[$data['sku']] = $data;
                    }
                    $termekdb++;
                }

                foreach ($termekek as $_t) {
                    $elsokategoria = explode('|', $_t['category']);
                    $kategoriak = explode('>', $elsokategoria[0]);
                    $parent = null;
                    $pid = $parentid;
                    foreach ($kategoriak as $kat) {
                        $kat = trim($kat);
                        $parent = $this->createKategoria($kat, $pid);
                        $pid = $parent->getId();
                    }
                }

                $this->getEm()->clear();
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $lettlog = false;

                foreach ($termekek as $data) {
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['cikkszam' => $data['sku'], 'gyarto' => $gyartoid]);

                    if (!$termek) {
                        if ($createuj && (($data['stock'] > 0) || (($data['stock'] <= 0) && ($data['emailnotify'] == 1)))) {
                            $elsokategoria = explode('|', $data['category']);
                            $kategoriak = explode('>', $elsokategoria[0]);

                            $urlkatnev = \mkw\store::urlize(implode('-', $kategoriak));
                            \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                            $parent = null;
                            $pid = $parentid;
                            foreach ($kategoriak as $kat) {
                                $kat = trim($kat);
                                $parent = $this->createKategoria($kat, $pid);
                                $pid = $parent->getId();
                            }

                            $afa = $this->createAfa($data['taxrate']);
                            $vtsz = $this->createVtsz('-', $afa);

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMekod($this->getME('db'));
                            $termek->setNev($data['name']);
                            $termek->setIdegenkod($data['id']);
                            $termek->setIdegencikkszam($data['sku']);
                            $termek->setCikkszam($data['sku']);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ]);
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortdescription']));
                            $termek->setLeiras('<p>' . $hosszuleiras . '</p>');
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz);

                            // kepek
                            $elso = true;
                            foreach ($data['images'] as $i) {
                                $imgurl = trim($i);
                                $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                $parsedpath = parse_url($imgurl, PHP_URL_PATH);
                                $extension = \mkw\store::getExtension($parsedpath);
                                if ($extension) {
                                    $imgpath = $nameWithoutExt . '.' . $extension;
                                } else {
                                    $imgpath = $nameWithoutExt;
                                }

                                $ih = fopen($imgpath, 'w');
                                if ($ih) {
                                    $ch = \curl_init($imgurl);
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    $curlretval = \curl_exec($ch);
                                    fclose($ih);
                                    if (($curlretval !== false) && (!\curl_errno($ch))) {
                                        switch ($http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                                            case 200:
                                                foreach ($this->settings['sizes'] as $k => $size) {
                                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                    $matches = explode('x', $size);
                                                    \mkw\thumbnail::createThumb(
                                                        $imgpath,
                                                        $newFilePath,
                                                        (int)$matches[0],
                                                        (int)$matches[1],
                                                        $this->settings['quality'],
                                                        true
                                                    );
                                                }
                                                if ($elso) {
                                                    $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                    $termek->setKepleiras($data['name']);
                                                } else {
                                                    $tkep = new \Entities\TermekKep();
                                                    $termek->addTermekKep($tkep);
                                                    $tkep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                    $tkep->setLeiras($data['name']);
                                                    \mkw\store::getEm()->persist($tkep);
                                                }
                                                $elso = false;
                                                break;
                                            default:
                                                \mkw\store::writelog(
                                                    'ELÉRHETETLEN KÉP'
                                                    . ' ' . $http_code . ' :'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                    . ' url: ' . $imgurl,
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                break;
                                        }
                                    } else {
                                        \mkw\store::writelog(
                                            'ELÉRHETETLEN KÉP'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' url: ' . $imgurl
                                            . ' errno: ' . curl_errno($ch)
                                            . ' error: ' . curl_error($ch),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                } else {
                                    \mkw\store::writelog(
                                        'HIBÁS KÉP NÉV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                        . ' url: ' . $imgurl,
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            }
                        }
                    } else {
                        /** @var \Entities\Termek $termek */
                        $termek = $termek[0];
                        if ($editleiras) {
                            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ]);
                            $hosszuleiras = $puri->sanitize(trim($data['description']));

                            $puri2 = \mkw\store::getSanitizer();
                            $rovidleiras = $puri2->sanitize(trim($data['shortdescription']));
                            $termek->setLeiras('<p>' . $hosszuleiras . '</p>');
                        }
                        if ($editnev) {
                            $termek->setNev($data['name']);
                        }
                        $termekkepdb = count($termek->getTermekKepek());
                        if ($addimages && $termekkepdb == 0) {
                            $elso = true;
                            foreach ($data['images'] as $i) {
                                if (!$elso) {
                                    $imgurl = trim($i);
                                    $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($data['name'] . '_' . $data['sku']);
                                    $kepnev = \mkw\store::urlize($data['name'] . '_' . $data['sku']);

                                    $parsedpath = parse_url($imgurl, PHP_URL_PATH);
                                    $extension = \mkw\store::getExtension($parsedpath);
                                    if ($extension) {
                                        $imgpath = $nameWithoutExt . '.' . $extension;
                                    } else {
                                        $imgpath = $nameWithoutExt;
                                    }

                                    $ih = fopen($imgpath, 'w');
                                    if ($ih) {
                                        $ch = \curl_init($imgurl);
                                        \curl_setopt($ch, CURLOPT_FILE, $ih);
                                        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                        $curlretval = \curl_exec($ch);
                                        fclose($ih);
                                        if (($curlretval !== false) && (!\curl_errno($ch))) {
                                            switch ($http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                                                case 200:
                                                    foreach ($this->settings['sizes'] as $k => $size) {
                                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                        $matches = explode('x', $size);
                                                        \mkw\thumbnail::createThumb(
                                                            $imgpath,
                                                            $newFilePath,
                                                            (int)$matches[0],
                                                            (int)$matches[1],
                                                            $this->settings['quality'],
                                                            true
                                                        );
                                                    }
                                                    $tkep = new \Entities\TermekKep();
                                                    $termek->addTermekKep($tkep);
                                                    $tkep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                    $tkep->setLeiras($data['name']);

                                                    \mkw\store::writelog($termek->getId(), 'haffnertermekkepek.txt');

                                                    \mkw\store::getEm()->persist($tkep);
                                                    break;
                                                default:
                                                    \mkw\store::writelog(
                                                        'ELÉRHETETLEN KÉP'
                                                        . ' ' . $http_code . ' :'
                                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                        . ' url: ' . $imgurl,
                                                        $logfile
                                                    );
                                                    $lettlog = true;
                                                    break;
                                            }
                                        } else {
                                            \mkw\store::writelog(
                                                'ELÉRHETETLEN KÉP'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' url: ' . $imgurl
                                                . ' errno: ' . curl_errno($ch)
                                                . ' error: ' . curl_error($ch),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                    } else {
                                        \mkw\store::writelog(
                                            'HIBÁS KÉP NÉV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' url: ' . $imgurl,
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                }
                                $elso = false;
                            }
                        }
                    }
                    if ($termek) {
                        if ($data['stock'] == 0) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setNemkaphato(true);
                                \mkw\store::writelog(
                                    'NEM KAPHATÓ'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            } else {
                                $termek->setNemkaphato(false);
                                \mkw\store::writelog(
                                    'KAPHATÓ'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                        } else {
                            $termek->setNemkaphato(false);
                            \mkw\store::writelog(
                                'KAPHATÓ'
                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;
                        }
                        if (!$termek->getAkcios()) {
                            $termek->setNetto((float)$data['price']);
                            $brutto = round($termek->getBrutto() * $arszaz / 100, -1);
                            if ($brutto < $minimumar) {
                                $brutto = $minimumar;
                            }
                            $termek->setBrutto($brutto);
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

                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                if ($gyarto) {
                    $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                    $termekdb = 0;
                    foreach ($termekek as $t) {
                        /** @var \Entities\Termek $termek */
                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                        if ($termek && !$termek->getFuggoben() && !$termek->getInaktiv()) {
                            $talalat = keres($t['cikkszam'], $xml->product);
                            if ($talalat) {
                                $talalat = toArr($talalat);
                            }
                            if (!$talalat || (($talalat['stock'] == 0) && ($talalat['emailnotify'] == 0))) {
                                if ($termek->getKeszlet() <= 0) {
                                    \mkw\store::writelog(
                                        'INAKTÍV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                    $termek->setInaktiv(true);
                                    \mkw\store::getEm()->persist($termek);
                                } else {
                                    \mkw\store::writelog(
                                        'AKTÍV'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                    $termek->setInaktiv(false);
                                    \mkw\store::getEm()->persist($termek);
                                }
                            }
                            $termekdb++;
                        }
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        }
                    }
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                }
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }

            $this->setRunningImport(\mkw\consts::RunningHaffner24Import, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function reintexImport()
    {
//        echo json_encode(array('msg' => 'Fejlesztés alatt.'));
//        return false;
        function trimCikkszam($csz)
        {
            return str_replace('g', '', $csz);
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningReintexImport)) {
            $this->setRunningImport(\mkw\consts::RunningReintexImport, 1);

            $sep = ';';

            $logfile = 'reintex_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoReintex);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $editnev = $this->params->getBoolRequestParam('editnev', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathReintex));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathReintex));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (\mkw\store::isReintexTeszt()) {
                $ch = \curl_init('https://www.mindentkapni.hu/t/reintexdownload');
                $fh = fopen(\mkw\store::storagePath('reintex.csv'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
                \curl_close($ch);
            } else {
                if (\mkw\store::isDeveloper()) {
                    move_uploaded_file($_FILES['toimport']['tmp_name'], \mkw\store::storagePath('reintex.csv'));
                } else {
                    $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlReintex));
                    $fh = fopen(\mkw\store::storagePath('reintex.csv'), 'w');
                    \curl_setopt($ch, CURLOPT_FILE, $fh);
                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    \curl_exec($ch);
                    fclose($fh);
                    \curl_close($ch);
                }
            }
            $fh = fopen(\mkw\store::storagePath('reintex.csv'), 'r');
            if ($fh) {
                $lettlog = false;
                $isFileOk = false;

                $_line = fgets($fh, 4096);
                rewind($fh);
                if ($_line) {
                    $isFileOk = substr_count($_line, $sep) > 10;
                    if ($isFileOk) {
                        $data = fgetcsv($fh, 0, $sep, '"');
                        rewind($fh);
                        $isFileOk = $data && substr($data[0], 0, 6) === 'Cikksz' && substr($data[1], 0, 4) === 'Term';
                    }
                } else {
                    $isFileOk = false;
                }

                if (!$isFileOk) {
                    \mkw\store::writelog('Nem stimmel a letöltött file', $logfile);
                    $lettlog = true;
                } else {
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                    $termekdb = 0;
                    $minden = [];
                    $termekek = [];
                    $szulogyerekek = [];
                    $cikkszamok = [];
                    fgetcsv($fh, 0, $sep, '"');
                    while ($data = fgetcsv($fh, 0, $sep, '"')) {
                        if (($data[$this->n('c')] != 3) && (!\mkw\store::strpos_array(
                                $data[$this->n('a')],
                                explode(',', \mkw\store::getParameter(\mkw\consts::ExcludeReintex))
                            ))) {
                            $minden[] = $data;
                            $cikkszamok[] = trimCikkszam($data[$this->n('a')]);
                            $kats = explode('|', $data[$this->n('k')]);
                            $kid = $parentid;
                            foreach ($kats as $kat) {
                                $kp = $this->createKategoria($this->toutf($kat), $kid);
                                $kid = $kp->getId();
                            }
                            $this->createME($this->toutf($data[$this->n('h')]));
                        }
                    }

                    $minden = null;

                    if ($gyarto) {
                        if ($cikkszamok) {
                            $termekek = $this->getRepo('Entities\Termek')->getForImport($gyarto);
                            $termekdb = 0;
                            foreach ($termekek as $t) {
                                // CSVben megvan
                                if (in_array($t['cikkszam'], $cikkszamok)) {
                                    /** @var \Entities\Termek $termek */
                                    $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                    if (count($termek->getValtozatok()) > 0) {
                                        /** @var \Entities\TermekValtozat $valtozat */
                                        foreach ($termek->getValtozatok() as $valtozat) {
                                            if ($valtozat->getIdegencikkszam()) {  // van idegencikkszam
                                                if (!in_array($valtozat->getIdegencikkszam(), $cikkszamok)) {  // nincs meg a reintexnel
                                                    if ($valtozat->getKeszlet() <= 0) {  // nincs keszleten
                                                        if ($valtozat->getElerheto()) {
                                                            \mkw\store::writelog(
                                                                'NEM ELÉRHETŐ'
                                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                                . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                                $logfile
                                                            );
                                                            $lettlog = true;
                                                            $valtozat->setElerheto(false);
                                                            \mkw\store::getEm()->persist($valtozat);
                                                            \mkw\store::getEm()->flush();
                                                        }
                                                    }
                                                }
                                            }
                                            if (
                                                ($valtozat->getKeszlet() > 0)
                                                ||
                                                ($valtozat->getIdegencikkszam() && in_array($valtozat->getIdegencikkszam(), $cikkszamok))
                                            ) {
                                                if (!$valtozat->getElerheto()) {
                                                    \mkw\store::writelog(
                                                        'ELÉRHETŐ'
                                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                        . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                        . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                        $logfile
                                                    );
                                                    $lettlog = true;
                                                    $valtozat->setElerheto(true);
                                                    \mkw\store::getEm()->persist($valtozat);
                                                    \mkw\store::getEm()->flush();
                                                }
                                            }
                                        }
                                        $elerhetovaltozatdb = 0;
                                        foreach ($termek->getValtozatok() as $valtozat) {
                                            if ($valtozat->getElerheto()) {
                                                $elerhetovaltozatdb++;
                                            }
                                        }
                                        if ($elerhetovaltozatdb == 0) {
                                            if ($termek && $termek->getKeszlet() <= 0) {
                                                $termekdb++;
                                                if (!$termek->getInaktiv()) {
                                                    \mkw\store::writelog(
                                                        'INAKTÍV'
                                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                        $logfile
                                                    );
                                                    $lettlog = true;
                                                    $termek->setInaktiv(true);
                                                    \mkw\store::getEm()->persist($termek);
                                                    \mkw\store::getEm()->flush();
                                                }
                                            }
                                        } else {
                                            if ($termek->getInaktiv()) {
                                                \mkw\store::writelog(
                                                    'AKTÍV'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                $termek->setInaktiv(false);
                                                \mkw\store::getEm()->persist($termek);
                                                \mkw\store::getEm()->flush();
                                            }
                                        }
                                    } else {
                                        if ($termek->getInaktiv()) {
                                            \mkw\store::writelog(
                                                'AKTÍV'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                            $termek->setInaktiv(false);
                                            \mkw\store::getEm()->persist($termek);
                                            \mkw\store::getEm()->flush();
                                        }
                                    }
                                } // CSVben NINCS meg
                                else {
                                    /** @var \Entities\Termek $termek */
                                    $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                    /** @var \Entities\TermekValtozat $valtozat */
                                    foreach ($termek->getValtozatok() as $valtozat) {
                                        if ($valtozat->getIdegencikkszam()) {
                                            if (!in_array($valtozat->getIdegencikkszam(), $cikkszamok)) {
                                                if ($valtozat->getKeszlet() <= 0) {
                                                    if ($valtozat->getElerheto()) {
                                                        \mkw\store::writelog(
                                                            'NEM ELÉRHETŐ'
                                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                            . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                            $logfile
                                                        );
                                                        $lettlog = true;
                                                        $valtozat->setElerheto(false);
                                                        \mkw\store::getEm()->persist($valtozat);
                                                        \mkw\store::getEm()->flush();
                                                    }
                                                }
                                            }
                                        }
                                        if (
                                            ($valtozat->getKeszlet() > 0)
                                            ||
                                            ($valtozat->getIdegencikkszam() && in_array($valtozat->getIdegencikkszam(), $cikkszamok))
                                        ) {
                                            if (!$valtozat->getElerheto()) {
                                                \mkw\store::writelog(
                                                    'ELÉRHETŐ'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                    . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                    . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                $valtozat->setElerheto(true);
                                                \mkw\store::getEm()->persist($valtozat);
                                                \mkw\store::getEm()->flush();
                                            }
                                        }
                                    }
                                    $elerhetovaltozatdb = 0;
                                    foreach ($termek->getValtozatok() as $valtozat) {
                                        if ($valtozat->getElerheto()) {
                                            $elerhetovaltozatdb++;
                                        }
                                    }
                                    if ($elerhetovaltozatdb > 0) {
                                        if ($termek->getInaktiv()) {
                                            \mkw\store::writelog(
                                                'AKTÍV'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                            $termek->setInaktiv(false);
                                            \mkw\store::getEm()->persist($termek);
                                            \mkw\store::getEm()->flush();
                                        }
                                    } else {
                                        $keszlet = $termek->getKeszlet();
                                        if ($termek && $keszlet <= 0) {
                                            if (!$termek->getInaktiv()) {
                                                \mkw\store::writelog(
                                                    'INAKTÍV'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                $termek->setInaktiv(true);
                                                \mkw\store::getEm()->persist($termek);
                                                \mkw\store::getEm()->flush();
                                            }
                                        }
                                    }
                                }
                            }
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }

                    if ($createuj) {
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            if (($data[$this->n('c')] != 2) && (!\mkw\store::strpos_array(
                                    $data[$this->n('a')],
                                    explode(',', \mkw\store::getParameter(\mkw\consts::ExcludeReintex))
                                ))) {
                                if (!$data[$this->n('u')] && ($data[$this->n('c')] != 2)) {
                                    $termekek[] = $data;
                                }
                            }
                        }
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            if (($data[$this->n('c')] != 2) && (!\mkw\store::strpos_array(
                                    $data[$this->n('a')],
                                    explode(',', \mkw\store::getParameter(\mkw\consts::ExcludeReintex))
                                ))) {
                                if (($data[$this->n('u')] === 'unas') && ($data[$this->n('c')] != 2)) {
                                    $szulogyerekek[$data[$this->n('a')]][] = $data;
                                }
                            }
                        }
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            if (($data[$this->n('c')] != 2) && (!\mkw\store::strpos_array(
                                    $data[$this->n('a')],
                                    explode(',', \mkw\store::getParameter(\mkw\consts::ExcludeReintex))
                                ))) {
                                if (($data[$this->n('u')] !== 'unas') && $data[$this->n('u')] && ($data[$this->n('c')] != 2)) {
                                    $szulogyerekek[$data[$this->n('u')]][] = $data;
                                }
                            }
                        }

                        \mkw\store::writelog(print_r($termekek, true), 'reintex.log');
                        \mkw\store::writelog(print_r($szulogyerekek, true), 'reintexszulo.log');

                        foreach ($termekek as $data) {
                            $termekdb++;
                            $cikkszam = trimCikkszam($data[$this->n('a')]);
                            if ($cikkszam) {
                                $termek = $this->getRepo('Entities\Termek')->findBy(['cikkszam' => $cikkszam, 'gyarto' => $gyartoid]);
                                if (!$termek) {
                                    $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(['idegencikkszam' => $cikkszam]);
                                    if ($valtozatok) {
                                        foreach ($valtozatok as $v) {
                                            $termek = $v->getTermek();
                                            if ($termek && $termek->getGyartoId() == $gyartoid) {
                                                $valtozat = $v;
                                                break;
                                            }
                                        }
                                        if (!$valtozat) {
                                            $termek = false;
                                        }
                                    }

                                    if (!$valtozat) {
                                        $termeknev = $this->toutf($data[$this->n('b')]);

                                        $termek = new \Entities\Termek();
                                        $termek->setFuggoben(true);
                                        $termek->setMekod($this->getME($this->toutf($data[$this->n('h')])));
                                        $termek->setNev($termeknev);

                                        $hosszuleiras = $this->toutf(trim($data[$this->n('d')]));
                                        $termek->setLeiras($hosszuleiras);

                                        $puri2 = \mkw\store::getSanitizer();
                                        $rovidleiras = $puri2->sanitize($hosszuleiras);
                                        $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');

                                        $termek->setCikkszam($cikkszam);
                                        $termek->setTermekfa1($parent);
                                        $termek->setVtsz($vtsz[0]);
                                        $termek->setHparany(3);
                                        if ($gyarto) {
                                            $termek->setGyarto($gyarto);
                                        }
                                        $this->getEm()->persist($termek);
                                    }
                                }
                            }
                            if (($termekdb % $batchsize) === 0) {
                                $this->getEm()->flush();
                                $this->getEm()->clear();
                                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                                $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                            }
                        }
                    }
                }
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
                fclose($fh);
            }
            $this->setRunningImport(\mkw\consts::RunningReintexImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function tutisportImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningTutisportImport)) {
            $this->setRunningImport(\mkw\consts::RunningTutisportImport, 1);

            $sep = ';';

            $logfile = 'tutisport_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoTutisport);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $filenev = \mkw\store::storagePath('tutisportimport.csv');
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);

            $fh = fopen($filenev, 'r');
            if ($fh) {
                $lettlog = false;
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
                    if ($data[$this->n('a')]) {
                        if ((int)$data[$this->n('d')] != 0) {
                            $this->createME($this->toutf(trim($data[$this->n('c')])));
                        }
                    }
                }
                rewind($fh);
                fgetcsv($fh, 0, $sep, '"');
                while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                }
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $termekdb++;
                    if ($data[$this->n('a')]) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['cikkszam' => $data[$this->n('a')], 'gyarto' => $gyartoid]);
                        if (!$termek) {
                            $csz = str_replace(' ', '', $data[$this->n('a')]);
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['cikkszam' => $csz, 'gyarto' => $gyartoid]);
                        }
                        if ((int)$data[$this->n('d')] != 0) {
                            if (!$termek) {
                                if ($createuj) {
                                    $termeknev = $this->toutf(trim($data[$this->n('b')]));
                                    $me = $this->toutf(trim($data[$this->n('c')]));

                                    $termek = new \Entities\Termek();
                                    $termek->setFuggoben(true);
                                    $termek->setMekod($this->getME($me));
                                    $termek->setNev($termeknev);
                                    $termek->setCikkszam($data[$this->n('a')]);
                                    $termek->setTermekfa1($parent);
                                    $termek->setVtsz($vtsz[0]);
                                    $termek->setHparany(3);
                                    if ($gyarto) {
                                        $termek->setGyarto($gyarto);
                                    }
                                }
                            } else {
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
                                    $termek->setBrutto(round((float)$data[$this->n('e')] * $arszaz / 100, -1));
                                }
                                \mkw\store::getEm()->persist($termek);
                            }
                        } else {
                            if ($termek) {
                                $termek = $termek[0];
                                if ($termek->getKeszlet() <= 0) {
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ | NEM LÁTHATÓ'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
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
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
                fclose($fh);
            }
            $this->setRunningImport(\mkw\consts::RunningTutisportImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function makszutovImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningMaxutovImport)) {
            $this->setRunningImport(\mkw\consts::RunningMaxutovImport, 1);

            $sep = ';';
            $minarszaz = 120;

            $logfile = 'makszutov_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

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

            if (\mkw\store::isMakszutovTeszt()) {
                $ch = \curl_init('https://www.mindentkapni.hu/t/makszutovdownload');
                $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'w');
                \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
                \curl_close($ch);
            } else {
                $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlMaxutov));
                $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'w');
                \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
            }

            $linecount = 0;
            $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'r');
            if ($fh) {
                while (($linecount < 10) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                    $linecount++;
                }
            }
            fclose($fh);

            if ($linecount > 1) {
                $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'r');
                if ($fh) {
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $katnev = $data[$this->n('c')];
                        $parent = $this->createKategoria($katnev, $parentid);
                    }

                    rewind($fh);

                    $lettlog = false;

                    $termekdb = 0;
                    fgetcsv($fh, 0, $sep, '"');
                    while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $termekdb++;
                    }
                    while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                        $idegencikkszam = (string)$data[$this->n('b')];
                        $termekdb++;
                        $termek = false;
                        $valtozat = false;
                        $valtozatok = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->findBy(['idegencikkszam' => $idegencikkszam]);
                        if ($valtozatok) {
                            foreach ($valtozatok as $v) {
                                $termek = $v->getTermek();
                                if ($termek && $termek->getGyartoId() == $gyartoid) {
                                    $valtozat = $v;
                                    break;
                                }
                            }
                            if (!$valtozat) {
                                $termek = false;
                            }
                        }
                        if (!$valtozat) {
                            $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid]
                            );
                        }
                        if ($data[$this->n('k')]) {
                            $ch = \curl_init($data[$this->n('k')]);
                            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            $le = \curl_exec($ch);

                            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                                'HTML.Allowed' => 'p,ul,li,b,strong,br'
                            ]);
                            $leiras = $puri->sanitize($le);

                            $puri2 = \mkw\store::getSanitizer();
                            $kisleiras = $puri2->sanitize($le);
                        } else {
                            $leiras = '';
                            $kisleiras = '';
                        }

                        $kaphato = (strpos($data[$this->n('i')], 'szleten') !== false);

                        if (!$termek) {
                            if ($createuj && $kaphato) {
                                $katnev = $data[$this->n('c')];
                                $urlkatnev = \mkw\store::urlize($katnev);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                $parent = $this->createKategoria($katnev, $parentid);
                                $termeknev = $data[$this->n('e')];

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMekod($this->getME('db'));
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

                                $kiskerar = (float)$data[$this->n('h')];
                                $nagykerar = (float)$data[$this->n('g')];

                                $termek->setBrutto($kiskerar * $arszaz / 100);
                                $termek->setBrutto(round($termek->getBrutto(), -1));

                                // kepek
                                $imagelist = explode(',', $data[$this->n('j')]);
                                $imgcnt = 0;
                                foreach ($imagelist as $imgurl) {
                                    $imgcnt++;

                                    $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
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
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb(
                                            $imgpath,
                                            $newFilePath,
                                            (int)$matches[0],
                                            (int)$matches[1],
                                            $this->settings['quality'],
                                            true
                                        );
                                    }
                                    if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                        $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                        $termek->setKepleiras($termeknev);
                                    } else {
                                        $kep = new \Entities\TermekKep();
                                        $termek->addTermekKep($kep);
                                        $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                        $kep->setLeiras($termeknev);
                                        \mkw\store::getEm()->persist($kep);
                                    }
                                }
                                \mkw\store::getEm()->persist($termek);
                                \mkw\store::writelog(
                                    'ÚJ TERMÉK'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                        } else {
                            if (is_array($termek)) {
                                $termek = $termek[0];
                            }
                            if ($valtozat) {
                                if ($termek) {
                                    if (!$termek->getAkcios()) {
                                        $kiskerar = (float)$data[$this->n('h')];
                                        $nagykerar = (float)$data[$this->n('g')];
                                        $termek->setBrutto($kiskerar * $arszaz / 100);
                                        $termek->setBrutto(round($termek->getBrutto(), -1));
                                    }
                                }
                                if (!$kaphato) {
                                    if ($valtozat->getKeszlet() <= 0) {
                                        $valtozat->setElerheto(false);
                                        \mkw\store::writelog(
                                            'NEM ELÉRHETŐ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                } else {
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
                                    if ($egysemkaphato) {
                                        \mkw\store::writelog(
                                            'NEM KAPHATÓ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                    \mkw\store::getEm()->persist($termek);
                                }
                            } else {
                                if ($termek) {
                                    if ($editleiras) {
                                        $termek->setLeiras($leiras);
                                    }
                                    if (!$kaphato) {
                                        $termek->setNemkaphato($termek->getKeszlet() <= 0);
                                        if ($termek->getNemkaphato()) {
                                            \mkw\store::writelog(
                                                'NEM KAPHATÓ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                    } else {
                                        $termek->setNemkaphato(false);
                                        \mkw\store::writelog(
                                            'KAPHATÓ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
                                    }
                                    if (!$termek->getAkcios()) {
                                        $kiskerar = (float)$data[$this->n('h')];
                                        $nagykerar = (float)$data[$this->n('g')];
                                        $termek->setBrutto($kiskerar * $arszaz / 100);
                                        $termek->setBrutto(round($termek->getBrutto(), -1));
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
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                    if ($gyarto) {
                        rewind($fh);
                        fgetcsv($fh, 0, $sep, '"');
                        $idegenkodok = [];
                        while ($data = fgetcsv($fh, 0, $sep, '"')) {
                            $idegenkodok[] = (string)$data[$this->n('b')];
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
                                                } // a változat nincs készleten, nincs meg az id.cikkszám makszutovnál, a terméknek sincs id.cikkszáma
                                                else {
                                                    $termekdb++;
                                                    \mkw\store::writelog(
                                                        'NEM LÁTHATÓ | NEM ELÉRHETŐ'
                                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                        . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                        . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam()
                                                        ,
                                                        $logfile
                                                    );
                                                    $lettlog = true;
                                                    $valtozat->setLathato(false);
                                                    $valtozat->setElerheto(false);
                                                    \mkw\store::getEm()->persist($valtozat);
                                                    if (($termekdb % $batchsize) === 0) {
                                                        \mkw\store::getEm()->flush();
                                                    }
                                                }
                                            } // megvan az idegen cikkszám makszutovnál
                                            else {
                                                $termeketkikellvenni = false;
                                            }
                                        } // nincs idegen cikkszám
                                        else {
                                            $termeketkikellvenni = false;
                                        }
                                    }
                                    if ($termeketkikellvenni) {
                                        if ($termek && $termek->getKeszlet() <= 0) {
                                            if (!$termek->getInaktiv()) {
                                                $termekdb++;
                                                \mkw\store::writelog(
                                                    'INAKTÍV'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                $termek->setInaktiv(true);
                                                \mkw\store::getEm()->persist($termek);
                                            }
                                            if (($termekdb % $batchsize) === 0) {
                                                \mkw\store::getEm()->flush();
                                            }
                                        }
                                    }
                                } else {
                                    if (!in_array($t['idegencikkszam'], $idegenkodok)) {
                                        /** @var \Entities\Termek $termek */
                                        $termek = $this->getRepo('Entities\Termek')->find($t['id']);
                                        if ($termek && $termek->getKeszlet() <= 0) {
                                            if (!$termek->getInaktiv()) {
                                                $termekdb++;
                                                \mkw\store::writelog(
                                                    'INAKTÍV'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                                $termek->setInaktiv(true);
                                                \mkw\store::getEm()->persist($termek);
                                            }
                                            if (($termekdb % $batchsize) === 0) {
                                                \mkw\store::getEm()->flush();
                                            }
                                        }
                                    }
                                }
                            }
                            \mkw\store::getEm()->flush();
                            \mkw\store::getEm()->clear();
                        }
                    }
                    if ($lettlog) {
                        echo json_encode(['url' => $logurl]);
                    }
                }
                fclose($fh);
            } else {
                echo json_encode(['url' => \mkw\store::storageUrl('makszutov.txt')]);
            }
            $this->setRunningImport(\mkw\consts::RunningMaxutovImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function createVateraPartner($pa)
    {
        $me = \mkw\store::getEm()->getRepository('Entities\Partner')->findBy(['email' => $pa['temail']]);

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
            } else {
                if ($pa['tszamlanev']) {
                    $me->setNev($pa['tszamlanev']);
                    $me->setIrszam($pa['tszamlairszam']);
                    $me->setVaros($pa['tszamlavaros']);
                    $me->setUtca($pa['tszamlautca']);
                } else {
                    if ($pa['szallnev']) {
                        $me->setNev($pa['szallnev']);
                        $me->setIrszam($pa['szallirszam']);
                        $me->setVaros($pa['szallvaros']);
                        $me->setUtca($pa['szallutca']);
                    } else {
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
            } else {
                if ($pa['tszallnev']) {
                    $me->setSzallnev($pa['tszallnev']);
                    $me->setSzallirszam($pa['tszallirszam']);
                    $me->setSzallvaros($pa['tszallvaros']);
                    $me->setSzallutca($pa['tszallutca']);
                } else {
                    if ($pa['szamlanev']) {
                        $me->setSzallnev($pa['szamlanev']);
                        $me->setSzallirszam($pa['szamlairszam']);
                        $me->setSzallvaros($pa['szamlavaros']);
                        $me->setSzallutca($pa['szamlautca']);
                    } else {
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
        } else {
            $me = $me[0];
        }
        return $me;
    }

    public function createVateraSzallitasimod($nev)
    {
        $me = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->findBy(['nev' => $nev]);
        if (!$me) {
            $me = new \Entities\Szallitasimod();
            $me->setNev($nev);
            $me->setWebes(false);
            $me->setFizmodok('1');

            \mkw\store::getEm()->persist($me);
            \mkw\store::getEm()->flush();
        } else {
            $me = $me[0];
        }
        return $me;
    }

    public function vateraImport()
    {
        $sep = ';';

        move_uploaded_file($_FILES['vaterarendeles']['tmp_name'], \mkw\store::storagePath('vaterarendeles.csv'));
        move_uploaded_file($_FILES['vateratermek']['tmp_name'], \mkw\store::storagePath('vateratermek.csv'));

        $fhrendeles = fopen(\mkw\store::storagePath('vaterarendeles.csv'), 'r');
        $fhtermek = fopen(\mkw\store::storagePath('vateratermek.csv'), 'r');
        if ($fhrendeles && $fhtermek) {
            $termekek = [];
            fgetcsv($fhtermek, 0, $sep, '"');
            while ($data = fgetcsv($fhtermek, 0, $sep, '"')) {
                $termekek[$data[$this->n('c')]] = $data;
            }

            $rendelesek = [];
            fgetcsv($fhrendeles, 0, $sep, '"');
            while ($data = fgetcsv($fhrendeles, 0, $sep, '"')) {
                if (array_key_exists($data[$this->n('b')], $termekek)) {
                    $rid = $data[$this->n('a')];
                    if ($data[$this->n('f')]) {
                        $rendelesek[$rid]['datum'] = $data[$this->n('g')];
                        $rendelesek[$rid]['szallmod'] = $data[$this->n('h')];
                        $rendelesek[$rid]['szallktg'] = (float)$data[$this->n('i')];
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
                        $rendelesek[$rid]['termek'] = [];
                    }
                    $t = $termekek[$data[$this->n('b')]];
                    $rendelesek[$rid]['tusernev'] = $t[$this->n('h')];
                    $rendelesek[$rid]['tnev'] = $t[$this->n('i')];
                    $rendelesek[$rid]['ttelefon'] = $t[$this->n('j')];
                    $rendelesek[$rid]['temail'] = $t[$this->n('k')];
                    $rendelesek[$rid]['vasarlashelye'] = $t[$this->n('l')];
                    $rendelesek[$rid]['tdatum'] = $t[$this->n('o')];
                    $rendelesek[$rid]['tszallmod'] = $t[$this->n('p')];
                    $rendelesek[$rid]['tszallktg'] = (float)$t[$this->n('q')];
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

                    $rendelesek[$rid]['termek'][] = [
                        'kod' => $data[$this->n('b')],
                        'nev' => $data[$this->n('c')],
                        'tnev' => $t[$this->n('a')],
                        'tcikkszam' => $t[$this->n('b')],
                        'mennyiseg' => (float)$data[$this->n('d')],
                        'egysar' => (float)$data[$this->n('e')],
                        'tmennyiseg' => (float)$t[$this->n('d')],
                        'tegysar' => (float)$t[$this->n('e')]
                    ];
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

                $hibascikkszam = [];
                foreach ($r['termek'] as $rtetel) {
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['cikkszam' => $rtetel['tcikkszam']]);
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
                    } else {
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

        \unlink(\mkw\store::storagePath('vaterarendeles.csv'));
        \unlink(\mkw\store::storagePath('vateratermek.csv'));
    }

    public function SIIKerPartnerImport()
    {
        $sep = ',';
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
//        move_uploaded_file($_FILES['toimport']['tmp_name'], 'siikerpartnerek.csv');
        $fh = fopen('siikerpartnerek.csv', 'r');
        if ($fh) {
            $termekdb = 0;
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                $me = new \Entities\Partner();
                $me->setVendeg(false);
                if ($data[$this->n('a')] != '') {
                    $me->setIdegenkod($data[$this->n('a')]);
                }
                if ($data[$this->n('b')] != '') {
                    $me->setNev($data[$this->n('b')]);
                }
                if ($data[$this->n('c')] != '') {
                    $me->setIrszam($data[$this->n('c')]);
                }
                if ($data[$this->n('d')] != '') {
                    $me->setVaros($data[$this->n('d')]);
                }
                if ($data[$this->n('e')] != '') {
                    $me->setUtca($data[$this->n('e')]);
                }
                if ($data[$this->n('f')] != '') {
                    $me->setAdoszam($data[$this->n('f')]);
                }
                if ($data[$this->n('g')] != '') {
                    $me->setEuadoszam($data[$this->n('g')]);
                }
                if ($data[$this->n('h')] != '') {
                    $me->setCjszam($data[$this->n('h')]);
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
                if ($data[$this->n('m')] != '') {
                    $me->setFizhatido($data[$this->n('m')]);
                }
                if ($data[$this->n('n')] != '') {
                    $me->setVatstatus($data[$this->n('n')]);
                }
                \mkw\store::getEm()->persist($me);
                \mkw\store::getEm()->flush();
            }
        }
        fclose($fh);
//        \unlink('siikerpartnerek.csv');
        echo 'Kész';
    }

    public function legavenueSzotar()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningLegavenueImport)) {
            $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 1);

            @\unlink(\mkw\store::logsPath('legavenue_forditani.txt'));

            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlLegavenue));
            $fh = fopen(\mkw\store::storagePath('legavenue.xml'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);

            $db = 0;
            $xml = simplexml_load_file(\mkw\store::storagePath("legavenue.xml"));
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

            if ($db) {
                echo json_encode(['url' => \mkw\store::logsUrl('legavenue_forditani.txt')]);
            }

            $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function legavenueImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningLegavenueImport)) {
            if (!$this->getRepo('Entities\Szotar')->isAllTranslated()) {
                $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 1);

                $logfile = 'legavenue_log.txt';
                $logurl = \mkw\store::logsUrl($logfile);

                @unlink(\mkw\store::logsPath($logfile));

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

                $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlLegavenue));
                $fh = fopen(\mkw\store::storagePath('legavenue.xml'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);

                $xml = simplexml_load_file(\mkw\store::storagePath("legavenue.xml"));
                if ($xml) {
                    $lettlog = false;
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

                        $kaphato = (int)$data->qty_on_hand > 0;
                        $ar = (float)$data->unit_price * 650;
                        $ar = round($ar, -1);
                        $idegencikkszam = (string)$data->sku;
                        $style = (string)$data->Style;
                        $vonalkod = (string)$data->ean_barcode;

                        if ($idegencikkszam) {
                            $termek = false;
                            $valtozat = false;
                            $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(['idegencikkszam' => $idegencikkszam]);
                            if ($valtozatok) {
                                foreach ($valtozatok as $v) {
                                    $termek = $v->getTermek();
                                    if ($termek && $termek->getGyartoId() == $gyartoid) {
                                        $valtozat = $v;
                                        break;
                                    }
                                }
                            }
                            if (!$valtozat) {
                                $termek = $this->getRepo('Entities\Termek')->findBy(['idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid]);
                            }
                            if (!$termek) {
                                if ($createuj) {
                                    $katnev = (string)$data->class;
                                    $urlkatnev = \mkw\store::urlize($katnev);
                                    \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                                    $parent = $this->createKategoria($katnev, $parentid);

                                    $termeknev = trim((string)$data->Short_Desc);
                                    $rovidleiras = trim((string)$data->Short_Desc);

                                    $termek = $this->getRepo('Entities\Termek')->findBy(['cikkszam' => $style, 'gyarto' => $gyartoid]);
                                    if ($termek) {
                                        if (is_array($termek)) {
                                            $termek = $termek[0];
                                        }
                                        $valtozat = new \Entities\TermekValtozat();
                                        $valtozat->setAdatTipus1(
                                            $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin))
                                        );
                                        $valtozat->setErtek1($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Color))));
                                        $valtozat->setAdatTipus2(
                                            $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret))
                                        );
                                        $valtozat->setErtek2($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Size))));
                                        $valtozat->setIdegencikkszam($idegencikkszam);
                                        $valtozat->setVonalkod($vonalkod);
                                        // kepek
                                        $imagelist = (array)$data->images;
                                        if (is_array($imagelist['image'])) {
                                            $imagelist = $imagelist['image'];
                                        }
                                        $imgcnt = 0;
                                        foreach ($imagelist as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
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
                                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb(
                                                    $imgpath,
                                                    $newFilePath,
                                                    (int)$matches[0],
                                                    (int)$matches[1],
                                                    $this->settings['quality'],
                                                    true
                                                );
                                            }
                                            $kep = new \Entities\TermekKep();
                                            $termek->addTermekKep($kep);
                                            $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                            $kep->setLeiras($termeknev);
                                            if ($imgcnt === 1) {
                                                $valtozat->setKep($kep);
                                            }
                                            \mkw\store::getEm()->persist($kep);
                                        }
                                        $valtozat->setTermek($termek);
                                        \mkw\store::getEm()->persist($valtozat);
                                    } else {
                                        $termek = new \Entities\Termek();
                                        $termek->setFuggoben(true);
                                        $termek->setMekod($this->getME('db'));
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
                                        $valtozat->setAdatTipus1(
                                            $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin))
                                        );
                                        $valtozat->setErtek1($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Color))));
                                        $valtozat->setAdatTipus2(
                                            $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret))
                                        );
                                        $valtozat->setErtek2($this->getRepo('Entities\Szotar')->translate(htmlspecialchars(strtolower((string)$data->Size))));
                                        $valtozat->setIdegencikkszam($idegencikkszam);
                                        $valtozat->setVonalkod($vonalkod);
                                        $valtozat->setTermek($termek);

                                        // kepek
                                        $imagelist = (array)$data->images;
                                        if (is_array($imagelist['image'])) {
                                            $imagelist = $imagelist['image'];
                                        }
                                        $imgcnt = 0;
                                        foreach ($imagelist as $imgurl) {
                                            $imgcnt++;

                                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
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
                                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                            \curl_exec($ch);
                                            fclose($ih);

                                            foreach ($this->settings['sizes'] as $k => $size) {
                                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                                $matches = explode('x', $size);
                                                \mkw\thumbnail::createThumb(
                                                    $imgpath,
                                                    $newFilePath,
                                                    (int)$matches[0],
                                                    (int)$matches[1],
                                                    $this->settings['quality'],
                                                    true
                                                );
                                            }
                                            if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                                $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                $termek->setKepleiras($termeknev);
                                                $valtozat->setTermekfokep(true);
                                            } else {
                                                $kep = new \Entities\TermekKep();
                                                $termek->addTermekKep($kep);
                                                $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                                $kep->setLeiras($termeknev);
                                                $valtozat->setKep($kep);
                                                \mkw\store::getEm()->persist($kep);
                                            }
                                        }
                                        \mkw\store::getEm()->persist($valtozat);
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                }
                            } else {
                                if (is_array($termek)) {
                                    $termek = $termek[0];
                                }
                                if ($valtozat) {
                                    if (!$valtozat->getVonalkod()) {
                                        $valtozat->setVonalkod($vonalkod);
                                    }
                                    if ($termek && !$termek->getAkcios()) {
                                        //$valtozat->setBrutto($ar - $termek->getBrutto());
                                    }
                                    if (!$kaphato) {
                                        if ($valtozat->getKeszlet() <= 0) {
                                            $valtozat->setElerheto(false);
                                            \mkw\store::writelog(
                                                'NEM ELÉRHETŐ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                    } else {
                                        $valtozat->setElerheto(true);
                                        \mkw\store::writelog(
                                            'ELÉRHETŐ'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
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
                                        if ($termek->getNemkaphato()) {
                                            \mkw\store::writelog(
                                                'NEM KAPHATÓ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
                                        }
                                        \mkw\store::getEm()->persist($termek);
                                    }
                                } else {
                                    if ($termek) {
                                        if (!$kaphato) {
                                            if ($termek->getKeszlet() <= 0) {
                                                $termek->setNemkaphato(true);
                                                \mkw\store::writelog(
                                                    'NEM KAPHATÓ'
                                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                    $logfile
                                                );
                                                $lettlog = true;
                                            }
                                        } else {
                                            $termek->setNemkaphato(false);
                                            \mkw\store::writelog(
                                                'KAPHATÓ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
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

                    if ($gyarto) {
                        $idegenkodok = [];
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
                                        \mkw\store::writelog(
                                            'INAKTÍV'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
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
                                            \mkw\store::writelog(
                                                'NEM ELÉRHETŐ'
                                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                                . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                                $logfile
                                            );
                                            $lettlog = true;
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
                                \mkw\store::writelog(
                                    'NEM KAPHATÓ'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
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
                    if ($lettlog) {
                        echo json_encode(['url' => $logurl]);
                    }
                }
                $this->setRunningImport(\mkw\consts::RunningLegavenueImport, 0);
            } else {
                echo json_encode(['msg' => 'Nincs minden lefordítva a szótárban.']);
            }
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function evonaImport()
    {
        function toArray($sheet, $row)
        {
            $kepek = $sheet->getCell('Q' . $row)->getValue();
            $kepek = array_filter(explode('|||', $kepek));

            $puri = new \mkwhelpers\HtmlPurifierSanitizer([
                'HTML.Allowed' => 'p,ul,li,b,strong,br'
            ]);
            $hosszuleiras = $puri->sanitize(trim($sheet->getCell('F' . $row)->getValue()));

            $puri2 = \mkw\store::getSanitizer();
            $rovidleiras = $puri2->sanitize(trim($sheet->getCell('E' . $row)->getValue()));
            $rovidleiras = mb_substr($rovidleiras, 0, 100, 'UTF8') . '...';

            $nev = $sheet->getCell('D' . $row)->getValue();
            $nev = ltrim($nev, '- ');
            $nev = 'Evona ' . \mkw\store::mb_ucfirst($nev);

            return [
                'cikkszam' => trim($sheet->getCell('A' . $row)->getValue()),
                'nev' => $nev,
                'rovidleiras' => $rovidleiras,
                'leiras' => $hosszuleiras,
                'termekkepek' => $kepek,
                'termekkep' => $sheet->getCell('Y' . $row)->getValue(),
                'suly' => $sheet->getCell('AB' . $row)->getValue(),
                'hossz' => $sheet->getCell('AC' . $row)->getValue(),
                'szelesseg' => $sheet->getCell('AD' . $row)->getValue(),
                'magassag' => $sheet->getCell('AE' . $row)->getValue(),
                'statusz' => $sheet->getCell('AF' . $row)->getValue(),
                'netto' => $sheet->getCell('AW' . $row)->getValue(),
                'szin' => trim($sheet->getCell('BQ' . $row)->getValue()),
                'meret' => trim($sheet->getCell('BR' . $row)->getValue()),
                'kategoria' => \mkw\store::mb_ucfirst($sheet->getCell('BS' . $row)->getValue()),
                'den' => $sheet->getCell('BT' . $row)->getValue(),
                'kaphato' => $sheet->getCell('AF' . $row)->getValue() == 1,
                'szulocikkszam' => trim($sheet->getCell('BP' . $row)->getValue())
            ];
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningEvonaImport)) {
            $this->setRunningImport(\mkw\consts::RunningEvonaImport, 1);

            $logfile = 'evona_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoEvona);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);
            $vtsz = $this->getRepo('Entities\Vtsz')->findBySzam('-');
            $gyarto = $this->getRepo('Entities\Partner')->find($gyartoid);
            $dencs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::DENCs));

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathEvona));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathEvona));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = IOFactory::identify($filenev);
            $reader = IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = (int)$sheet->getHighestRow();

            $katnevek = [];
            $szulocikkszamok = [];
            for ($row = 2; $row <= $maxrow; ++$row) {
                if ($sheet->getCell('Y' . $row)->getValue()) {
                    $szulocikkszam = $sheet->getCell('BP' . $row)->getValue();
                    $af = \mkw\store::mb_ucfirst($sheet->getCell('BS' . $row)->getValue());
                    $katnevek[$af] = $af;
                    if ($szulocikkszam) {
                        $szulocikkszamok[] = $szulocikkszam;
                    }
                }
            }
            $szulok = [];
            $termekek = [];
            for ($row = 2; $row <= $maxrow; ++$row) {
                if ($sheet->getCell('Y' . $row)->getValue()) {
                    $cikkszam = $sheet->getCell('A' . $row)->getValue();
                    if ($cikkszam && in_array($cikkszam, $szulocikkszamok)) {
                        $x = toArray($sheet, $row);
                        $x['gyerekek'] = [];
                        $szulok[$cikkszam] = $x;
                    }
                }
            }

            for ($row = 2; $row <= $maxrow; ++$row) {
                if ($sheet->getCell('Y' . $row)->getValue()) {
                    $szulocikkszam = $sheet->getCell('BP' . $row)->getValue();
                    if ($szulocikkszam) {
                        $szulok[$szulocikkszam]['gyerekek'][] = toArray($sheet, $row);
                    } else {
                        $cikkszam = $sheet->getCell('A' . $row)->getValue();
                        if ($cikkszam && !in_array($cikkszam, $szulocikkszamok)) {
                            $termekek[] = toArray($sheet, $row);
                        }
                    }
                }
            }

            foreach ($katnevek as $katnev) {
                $parent = $this->createKategoria($katnev, $parentid);
            }

            $termekkepszotar = [];

            $termekdb = 0;

            $lettlog = false;

            foreach ($termekek as $data) {
                $termekdb++;

                $ar = (float)$data['netto'] * $arszaz / 100;

                $termek = $this->getRepo('Entities\Termek')->findBy(['idegencikkszam' => $data['cikkszam'], 'gyarto' => $gyartoid]);
                if (!$termek) {
                    if ($createuj && $data['kaphato']) {
                        $termeknev = $data['nev'];
                        $idegencikkszam = $data['cikkszam'];
                        $katnev = $data['kategoria'];
                        $urlkatnev = \mkw\store::urlize($katnev);
                        \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                        $parent = $this->createKategoria($katnev, $parentid);

                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMekod($this->getME('db'));
                        $termek->setNev($termeknev);
                        $termek->setRovidleiras($data['rovidleiras']);
                        $termek->setLeiras($data['leiras']);
                        $termek->setCikkszam($idegencikkszam);
                        $termek->setIdegencikkszam($idegencikkszam);
                        $termek->setTermekfa1($parent);
                        $termek->setVtsz($vtsz[0]);
                        $termek->setNemkaphato(!$data['kaphato']);
                        if ($data['den']) {
                            $cimke = $this->createTermekCimke($dencs, $data['den'] . ' DEN');
                            if ($cimke) {
                                $termek->addCimke($cimke);
                            }
                        }
                        if ($gyarto) {
                            $termek->setGyarto($gyarto);
                        }

                        $termek->setNetto($ar);
                        $termek->setBrutto(round($termek->getBrutto(), -1));

                        $imgcnt = 1;
                        if ($data['termekkep']) {
                            // fokep
                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                            if (count($data['termekkepek']) >= 1) {
                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                $kepnev = $kepnev . '_' . $imgcnt;
                            }

                            $imgurl = \mkw\store::getParameter(\mkw\consts::KepUrlEvona) . $data['termekkep'];
                            $extension = \mkw\store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init($imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($this->settings['sizes'] as $k => $size) {
                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                $matches = explode('x', $size);
                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                            }
                            $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                            $termek->setKepleiras($termeknev);
                        }

                        // kepek
                        $imagelist = $data['termekkepek'];
                        foreach ($imagelist as $imgurl) {
                            $imgcnt++;

                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam) . '_' . $imgcnt;
                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam) . '_' . $imgcnt;

                            $extension = \mkw\store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::KepUrlEvona) . $imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($this->settings['sizes'] as $k => $size) {
                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                $matches = explode('x', $size);
                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                            }
                            $kep = new \Entities\TermekKep();
                            $termek->addTermekKep($kep);
                            $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                            $kep->setLeiras($termeknev);
                            \mkw\store::getEm()->persist($kep);
                        }
                        \mkw\store::getEm()->persist($termek);
                    }
                } else {
                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($editleiras) {
                        $termek->setRovidleiras($data['rovidleiras']);
                        $termek->setLeiras($data['leiras']);
                    }
                    if (!$data['kaphato']) {
                        if ($termek->getKeszlet() <= 0) {
                            $termek->setNemkaphato(true);
                            \mkw\store::writelog(
                                'NEM KAPHATÓ'
                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;
                        }
                    } else {
                        $termek->setNemkaphato(false);
                        \mkw\store::writelog(
                            'KAPHATÓ'
                            . ' termék cikkszám: ' . $termek->getCikkszam()
                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                            $logfile
                        );
                        $lettlog = true;
                    }
                    if (!$termek->getAkcios()) {
                        $termek->setNetto($ar);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                    }
                    \mkw\store::getEm()->persist($termek);
                }
                if (($termekdb % $batchsize) === 0) {
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    $dencs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::DENCs));
                }
            }

            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();
            $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $dencs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::DENCs));

            $termekdb = 0;

            foreach ($szulok as $data) {
                $termekdb++;

                $ar = (float)$data['netto'] * $arszaz / 100;

                $termekkeplista = [];
                $termekkepszotar = [];

                $termek = $this->getRepo('Entities\Termek')->findBy(['idegencikkszam' => $data['cikkszam'], 'gyarto' => $gyartoid]);
                if (!$termek) {
                    if ($createuj && $data['kaphato']) {
                        $termeknev = $data['nev'];
                        $idegencikkszam = $data['cikkszam'];
                        $katnev = $data['kategoria'];
                        $urlkatnev = \mkw\store::urlize($katnev);
                        \mkw\store::createDirectoryRecursively($path . $urlkatnev);
                        $parent = $this->createKategoria($katnev, $parentid);

                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMekod($this->getME('db'));
                        $termek->setNev($termeknev);
                        $termek->setRovidleiras($data['rovidleiras']);
                        $termek->setLeiras($data['leiras']);
                        $termek->setCikkszam($idegencikkszam);
                        $termek->setIdegencikkszam($idegencikkszam);
                        $termek->setTermekfa1($parent);
                        $termek->setVtsz($vtsz[0]);
                        $termek->setNemkaphato(!$data['kaphato']);
                        if ($data['den']) {
                            $cimke = $this->createTermekCimke($dencs, $data['den'] . ' DEN');
                            if ($cimke) {
                                $termek->addCimke($cimke);
                            }
                        }
                        if ($gyarto) {
                            $termek->setGyarto($gyarto);
                        }

                        $termek->setNetto($ar);
                        $termek->setBrutto(round($termek->getBrutto(), -1));

                        $imgcnt = 1;
                        if ($data['termekkep']) {
                            // fokep
                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                            if (count($data['termekkepek']) >= 1) {
                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                $kepnev = $kepnev . '_' . $imgcnt;
                            }

                            $imgurl = \mkw\store::getParameter(\mkw\consts::KepUrlEvona) . $data['termekkep'];
                            $extension = \mkw\store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init($imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($this->settings['sizes'] as $k => $size) {
                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                $matches = explode('x', $size);
                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                            }
                            $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                            $termek->setKepleiras($termeknev);
                            $termekkepszotar[$data['termekkep']] = $urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension;
                        }

                        // kepek
                        $imagelist = $data['termekkepek'];
                        foreach ($imagelist as $imgurl) {
                            $imgcnt++;

                            $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam) . '_' . $imgcnt;
                            $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam) . '_' . $imgcnt;

                            $extension = \mkw\store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::KepUrlEvona) . $imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($this->settings['sizes'] as $k => $size) {
                                $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                $matches = explode('x', $size);
                                \mkw\thumbnail::createThumb($imgpath, $newFilePath, (int)$matches[0], (int)$matches[1], $this->settings['quality'], true);
                            }
                            $kep = new \Entities\TermekKep();
                            $termek->addTermekKep($kep);
                            $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                            $kep->setLeiras($termeknev);
                            $termekkeplista[] = $kep;
                            $termekkepszotar[$imgurl] = $urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension;
                            \mkw\store::getEm()->persist($kep);
                        }
                        /*
                                                $valtozat = new \Entities\TermekValtozat();
                                                $valtozat->setAdatTipus1($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)));
                                                $valtozat->setErtek1($data['szin']);
                                                $valtozat->setAdatTipus2($this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)));
                                                $valtozat->setErtek2($data['meret']);
                                                $valtozat->setIdegencikkszam($data['cikkszam']);
                                                $valtozat->setCikkszam($data['cikkszam']);
                                                $valtozat->setTermek($termek);
                                                $valtozat->setTermekfokep(true);
                                                if (!$data['kaphato']) {
                                                    $valtozat->setElerheto(false);
                                                }
                                                else {
                                                    $valtozat->setElerheto(true);
                                                }
                                                \mkw\store::getEm()->persist($valtozat);
                        */
                        \mkw\store::getEm()->persist($termek);

                        foreach ($data['gyerekek'] as $gyerekdata) {
                            $valtozat = new \Entities\TermekValtozat();
                            $valtozat->setAdatTipus1(
                                $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin))
                            );
                            $valtozat->setErtek1($gyerekdata['szin']);
                            $valtozat->setAdatTipus2(
                                $this->getRepo('Entities\TermekValtozatAdatTipus')->find(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret))
                            );
                            $valtozat->setErtek2($gyerekdata['meret']);
                            $valtozat->setIdegencikkszam($gyerekdata['cikkszam']);
                            $valtozat->setCikkszam($gyerekdata['cikkszam']);
                            $valtozat->setTermek($termek);
                            if (!$gyerekdata['kaphato']) {
                                $valtozat->setElerheto(false);
                            } else {
                                $valtozat->setElerheto(true);
                            }
                            if ($gyerekdata['termekkep']) {
                                if (array_key_exists($gyerekdata['termekkep'], $termekkepszotar)) {
                                    $megvanakep = false;
                                    /** @var \Entities\TermekKep $tkl */
                                    foreach ($termekkeplista as $tkl) {
                                        if ($tkl->getUrl('') == $termekkepszotar[$gyerekdata['termekkep']]) {
                                            $megvanakep = $tkl;
                                        }
                                    }
                                    if ($megvanakep) {
                                        $valtozat->setKep($megvanakep);
                                    } else {
                                        $valtozat->setTermekfokep(true);
                                    }
                                } else {
                                    $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize(
                                            $termeknev . '_' . $idegencikkszam
                                        ) . '_' . $imgcnt;
                                    $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam) . '_' . $imgcnt;

                                    $imgurl = \mkw\store::getParameter(\mkw\consts::KepUrlEvona) . $gyerekdata['termekkep'];
                                    $extension = \mkw\store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($this->settings['sizes'] as $k => $size) {
                                        $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                        $matches = explode('x', $size);
                                        \mkw\thumbnail::createThumb(
                                            $imgpath,
                                            $newFilePath,
                                            (int)$matches[0],
                                            (int)$matches[1],
                                            $this->settings['quality'],
                                            true
                                        );
                                    }
                                    $kep = new \Entities\TermekKep();
                                    $termek->addTermekKep($kep);
                                    $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                    $valtozat->setKep($kep);
                                    \mkw\store::getEm()->persist($kep);
                                    $termekkeplista[] = $kep;
                                    $termekkepszotar[$gyerekdata['termekkep']] = $urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension;
                                }
                            }
                            \mkw\store::getEm()->persist($valtozat);
                        }
                    }
                } else {
                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($editleiras) {
                        $termek->setRovidleiras($data['rovidleiras']);
                        $termek->setLeiras($data['leiras']);
                    }
                    if (!$data['kaphato']) {
                        if ($termek->getKeszlet() <= 0) {
                            $termek->setNemkaphato(true);
                            \mkw\store::writelog(
                                'NEM KAPHATÓ'
                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;
                        }
                    } else {
                        $termek->setNemkaphato(false);
                        \mkw\store::writelog(
                            'KAPHATÓ'
                            . ' termék cikkszám: ' . $termek->getCikkszam()
                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                            $logfile
                        );
                        $lettlog = true;
                    }
                    if (!$termek->getAkcios()) {
                        $termek->setNetto($ar);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                    }
                    \mkw\store::getEm()->persist($termek);
                }
                if (($termekdb % $batchsize) === 0) {
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                    $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                    $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    $dencs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::DENCs));
                }
            }

            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $excel->disconnectWorksheets();
            \unlink($filenev);
            if ($lettlog) {
                echo json_encode(['url' => $logurl]);
            }
            $this->setRunningImport(\mkw\consts::RunningEvonaImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function evonaxmlImport()
    {
        function toArr($obj)
        {
            return [
                'manufacturer' => (string)$obj->manufacturer,
                'name' => (string)$obj->name,
                'price' => (int)$obj->grossprice,
                'category' => (string)$obj->category,
                'description' => (string)$obj->describe,
                'delivery_time' => (int)$obj->deliverytime,
                'termekkod' => (int)$obj->id,
                'stock' => (string)$obj->stock
            ];
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningEvonaXMLImport)) {
            $this->setRunningImport(\mkw\consts::RunningEvonaXMLImport, 1);

            $logfile = 'evonaxml_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoEvona);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlEvonaXML));
            $fh = fopen(\mkw\store::storagePath('evona.xml'), 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            fclose($fh);
            \curl_close($ch);

            $lettlog = false;

            $xml = simplexml_load_file(\mkw\store::storagePath("evona.xml"));
            if ($xml) {
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);


                    \mkw\store::writelog(
                        '1.kör, data[termekkod]=' . $data['termekkod'] . ';data[stock]=' . $data['stock'] . ';data[name]=' . $data['name'],
                        $logfile
                    );
                    $lettlog = true;

                    $termek = false;
                    $valtozat = false;
                    $valtozatok = $this->getRepo('Entities\TermekValtozat')->findBy(['idegencikkszam' => $data['termekkod']]);
                    if ($valtozatok) {
                        foreach ($valtozatok as $v) {
                            $termek = $v->getTermek();
                            if ($termek && $termek->getGyartoId() == $gyartoid) {
                                /** @var TermekValtozat $valtozat */
                                $valtozat = $v;
                                break;
                            }
                        }
                    }
                    if (!$valtozat) {
                        /** @var Termek $termek */
                        $termek = $this->getRepo('Entities\Termek')->findBy(['idegencikkszam' => $data['termekkod'], 'gyarto' => $gyartoid]);
                    }

                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($termek && $termek->getGyartoId() != $gyartoid) {
                        $termek = null;
                    }

                    if ($valtozat) {
                        if ($data['stock'] === 'false') {
                            if ($valtozat->getKeszlet() <= 0) {
                                $valtozat->setElerheto(false);
                                \mkw\store::writelog(
                                    'NEM ELÉRHETŐ - 1.kör, változat, nincs stock, nincs készlet -'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                    . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                    . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                        } else {
                            $valtozat->setElerheto(true);
                            \mkw\store::writelog(
                                'ELÉRHETŐ - 1.kör, változat, van stock -'
                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;
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
                            if ($egysemkaphato) {
                                \mkw\store::writelog(
                                    'NEM KAPHATÓ - 1.kör, változat, termék egyik változata sem elérhető - '
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
                    } else {
                        if ($termek) {
                            if ($data['stock'] === 'false') {
                                if ($termek->getKeszlet() <= 0) {
                                    $termek->setNemkaphato(true);
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ - 1.kör, termék, nincs stock, nincs készlet'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            } else {
                                $termek->setNemkaphato(false);
                                \mkw\store::writelog(
                                    'KAPHATÓ - 1.kör, termék, van stock'
                                    . ' termék cikkszám: ' . $termek->getCikkszam()
                                    . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                    $logfile
                                );
                                $lettlog = true;
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
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

                $termekek = $this->getRepo('Entities\Termek')->getWithValtozatokForImport($gyarto);
                $termekdb = 0;
                /** @var \Entities\Termek $termek */
                foreach ($termekek as $termek) {
                    \mkw\store::writelog(
                        '2.kör, $termek->idegencikkszam=' . $termek->getIdegencikkszam(),
                        $logfile
                    );
                    $lettlog = true;

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
                        \mkw\store::writelog(
                            'NEM KAPHATÓ - 2.kör, terméknek van változata de egy sem elérhető -'
                            . ' termék cikkszám: ' . $termek->getCikkszam()
                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                            $logfile
                        );
                        $lettlog = true;
                        $termek->setNemkaphato(true);
                        \mkw\store::getEm()->persist($termek);
                        if (($termekdb % $batchsize) === 0) {
                            \mkw\store::getEm()->flush();
//                            \mkw\store::getEm()->clear();
                        }
                    }
                }

                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();

                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

                if ($gyarto) {
                    $idegenkodok = [];
                    foreach ($products as $data) {
                        $idegenkodok[] = (string)$data->id;
                    }
                    if ($idegenkodok) {
                        $termekek = $this->getRepo('Entities\Termek')->getWithValtozatokForImport($gyarto);
                        $termekdb = 0;
                        /** @var \Entities\Termek $termek */
                        foreach ($termekek as $termek) {
                            \mkw\store::writelog(
                                '3.kör, $termek->idegencikkszam=' . $termek->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;

                            if ($termek->getIdegencikkszam() && !in_array($termek->getIdegencikkszam(), $idegenkodok)) {
                                if ($termek->getKeszlet() <= 0) {
                                    $termekdb++;
                                    \mkw\store::writelog(
                                        'INAKTÍV - 3.kör, termék nincs az xmlben, nincs készleten -'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
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
                                        \mkw\store::writelog(
                                            'NEM ELÉRHETŐ - 3.kör, változat nincs az xml-ben, nincs készleten -'
                                            . ' termék cikkszám: ' . $termek->getCikkszam()
                                            . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam()
                                            . ' változat cikkszám: ' . $valtozat->getCikkszam()
                                            . ' változat szállítói cikkszám: ' . $valtozat->getIdegencikkszam(),
                                            $logfile
                                        );
                                        $lettlog = true;
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
                        \mkw\store::writelog(
                            '4.kör, $termek->idegencikkszam=' . $termek->getIdegencikkszam(),
                            $logfile
                        );
                        $lettlog = true;

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
                            \mkw\store::writelog(
                                'NEM KAPHATÓ - 4.kör, terméknek van változata de egy sem elérhető -'
                                . ' termék cikkszám: ' . $termek->getCikkszam()
                                . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                $logfile
                            );
                            $lettlog = true;
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

                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
            }
            if ($lettlog) {
                echo json_encode(['url' => $logurl]);
            }

            $this->setRunningImport(\mkw\consts::RunningEvonaXMLImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function gulfImport()
    {
        if (!$this->checkRunningImport(\mkw\consts::RunningGulfImport)) {
            $this->setRunningImport(\mkw\consts::RunningGulfImport, 1);

            $volthiba = false;

            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoGulf);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            if ($dbtol < 1) {
                $dbtol = 1;
            }

            \mkw\store::writelog(print_r($_FILES['toimport'], true));

            $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
            move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
            //pathinfo

            $filetype = IOFactory::identify($filenev);
            $reader = IOFactory::createReader($filetype);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $sheet = $excel->getActiveSheet();
            $maxrow = (int)$sheet->getHighestRow();
            if (!$dbig) {
                $dbig = $maxrow;
            }

            $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);

            $termekdb = 0;
            for ($row = $dbtol; $row <= $dbig; ++$row) {
                $cikkszam = $sheet->getCell('A' . $row)->getValue();
                if ($cikkszam) {
                    $termekdb++;

                    /** @var Termek $termek */
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $cikkszam, 'gyarto' => $gyartoid]);

                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($termek) {
                        $ar = (float)$sheet->getCell('M' . $row)->getValue();
                        if ($ar && !$termek->getAkcios()) {
                            $termek->setBrutto(round($ar, -1));
                        }

                        $kaphato = (int)$sheet->getCell('R' . $row)->getValue() > 0;
                        if (!$kaphato) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setNemkaphato(true);
                            } else {
                                $termek->setNemkaphato(false);
                            }
                        } else {
                            $termek->setNemkaphato(false);
                        }

                        \mkw\store::getEm()->persist($termek);
                    }

                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                    }
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $excel->disconnectWorksheets();
            \unlink($filenev);

            $this->setRunningImport(\mkw\consts::RunningGulfImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function makszutovIdCsere()
    {
        $sep = ';';

        $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoMaxutov);
        $batchsize = 20;

        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlMaxutov));
        $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'w');
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_exec($ch);
        fclose($fh);

        $linecount = 0;
        $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'r');
        if ($fh) {
            while (($linecount < 10) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $linecount++;
            }
        }
        fclose($fh);

        if ($linecount > 1) {
            $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'r');
            if ($fh) {
                $termekdb = 0;
                fgetcsv($fh, 0, $sep, '"');
                while ($data = fgetcsv($fh, 0, $sep, '"')) {
                    $regiidegencikkszam = (string)$data[$this->n('a')];
                    $ujidegencikkszam = (string)$data[$this->n('b')];
                    $termekdb++;
                    /** @var Termek $termek */
                    $termek = false;
                    /** @var TermekValtozat $valtozat */
                    $valtozat = false;
                    $valtozatok = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->findBy(['idegencikkszam' => $regiidegencikkszam]);
                    if ($valtozatok) {
                        foreach ($valtozatok as $v) {
                            $termek = $v->getTermek();
                            if ($termek && $termek->getGyartoId() == $gyartoid) {
                                $valtozat = $v;
                                break;
                            }
                        }
                        if (!$valtozat) {
                            $termek = false;
                        }
                    }
                    if (!$valtozat) {
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $regiidegencikkszam, 'gyarto' => $gyartoid]
                        );
                    }

                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($valtozat) {
                        $valtozat->setIdegencikkszam($ujidegencikkszam);
                        if ($termek && $termek->getIdegencikkszam() == $regiidegencikkszam) {
                            $termek->setIdegencikkszam($ujidegencikkszam);
                            \mkw\store::getEm()->persist($termek);
                        }
                        \mkw\store::getEm()->persist($valtozat);
                    } else {
                        if ($termek) {
                            $termek->setIdegencikkszam($ujidegencikkszam);
                            \mkw\store::getEm()->persist($termek);
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                    }
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
            }
            fclose($fh);
        }
    }

    public function smileebikeImport()
    {
        function toArr($obj)
        {
            $xmlkepek = (array)$obj->image_url;
            $kepek = [];
            foreach ($xmlkepek as $xk) {
                $kepek[] = (string)$xk;
            }
            $xmlparams = (array)$obj->parameters;
            $params = [];
            foreach ($xmlparams['parameter'] as $xp) {
                $params[(string)$xp->name] = (string)$xp->value;
            }
            return [
                'identifier' => (string)$obj->identifier,
                'name' => (string)$obj->name,
                'ean' => (string)$obj->ean,
                'stock' => (int)$obj->stock,
                'deliveryTime' => (int)$obj->delivery_time,
                'description' => (string)$obj->description,
                'grossPrice' => (string)$obj->gross_price,
                'netPrice' => (string)$obj->net_price,
                'groupGrossPrice' => (string)$obj->group_gross_price,
                'groupNetPrice' => (string)$obj->group_net_price,
                'images' => $kepek,
                'parameters' => $params
            ];
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningSmileebikeImport)) {
            $this->setRunningImport(\mkw\consts::RunningSmileebikeImport, 1);

            $logfile = 'smileebike_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoSmileebike);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $todownload = $this->params->getBoolRequestParam('smileebikedownload', false);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathSmileebike));

            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathSmileebike));
            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            @unlink(\mkw\store::storagePath('smileebike_fuggoben.txt'));

            if ($todownload) {
                @\unlink(\mkw\store::storagePath('smileebike.xml'));
                $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlSmileebike));
                $fh = fopen(\mkw\store::storagePath('smileebike.xml'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
            }

            $xml = simplexml_load_file(\mkw\store::storagePath('smileebike.xml'));
            $lettlog = false;
            if ($xml) {
                $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                $markacs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::MarkaCs));

                $products = $xml->product;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $termekek = [];
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if ($data['identifier']) {
                        $idegencikkszam = $data['identifier'];
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid]);
                        if (!$termek) {
                            if ($createuj) {
                                $parent = $this->getRepo('Entities\TermekFa')->find($parentid);
                                $urlkatnev = \mkw\store::urlize('ebike');
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $termeknev = trim($data['name']);

                                $hosszuleiras = trim($data['description']);
                                $rovidleiras = trim($data['description']);

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMekod($this->getME('db'));
                                $termek->setNev($termeknev);
                                $termek->setLeiras($hosszuleiras);
                                $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                $termek->setCikkszam($idegencikkszam);
                                $termek->setIdegencikkszam($idegencikkszam);
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);
                                $termek->setHparany(3);
                                $termek->setVonalkod($data['ean']);
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                if (trim($data['parameters']['Gyártó'])) {
                                    $cimke = $this->createTermekCimke($markacs, trim($data['parameters']['Gyártó']));
                                    if ($cimke) {
                                        $termek->addCimke($cimke);
                                    }
                                }
                                foreach ($data['parameters'] as $k => $v) {
                                    if ($k !== 'Szín' &&
                                        $k !== 'Gyártó' &&
                                        $k !== 'Wsfind Kategória' &&
                                        $k !== 'EAN' &&
                                        $k !== 'Státusz-készlet' &&
                                        $k !== 'Szállítási napok száma, kiszállítás esetén' &&
                                        $k !== 'AggregateRating' &&
                                        $k !== 'Arukereso.hu Szállítási Idő') {
                                        if (trim($v) && trim($k)) {
                                            $kat = $this->createTermekCimkeKat(trim($k));
                                            $cimke = $this->createTermekCimke($kat, trim($v));
                                            if ($cimke) {
                                                $termek->addCimke($cimke);
                                            }
                                        }
                                    }
                                }
                                // kepek
                                if ($data['images']) {
                                    $imgcnt = 0;
                                    foreach ($data['images'] as $imgurl) {
                                        $imgcnt++;

                                        $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                        $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                        if (count($data['images']) > 1) {
                                            $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                            $kepnev = $kepnev . '_' . $imgcnt;
                                        }

                                        $extension = \mkw\store::getExtension($imgurl);
                                        $imgpath = $nameWithoutExt . '.' . $extension;

                                        $ch = \curl_init($imgurl);
                                        $ih = fopen($imgpath, 'w');
                                        \curl_setopt($ch, CURLOPT_FILE, $ih);
                                        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                        \curl_exec($ch);
                                        fclose($ih);

                                        foreach ($this->settings['sizes'] as $k => $size) {
                                            $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                            $matches = explode('x', $size);
                                            \mkw\thumbnail::createThumb(
                                                $imgpath,
                                                $newFilePath,
                                                (int)$matches[0],
                                                (int)$matches[1],
                                                $this->settings['quality'],
                                                true
                                            );
                                        }
                                        if (((count($data['images']) > 1) && ($imgcnt == 1)) || (count($data['images']) == 1)) {
                                            $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                            $termek->setKepleiras($termeknev);
                                        } else {
                                            $kep = new \Entities\TermekKep();
                                            $termek->addTermekKep($kep);
                                            $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                            $kep->setLeiras($termeknev);
                                            \mkw\store::getEm()->persist($kep);
                                        }
                                    }
                                }
                            }
                        } else {
                            $termek = $termek[0];
                            if (!$termek->getVonalkod()) {
                                $termek->setVonalkod($data['ean']);
                            }
                            if ($editleiras) {
                                $hosszuleiras = trim($data['description']);
                                $termek->setLeiras($hosszuleiras);
                            }
                        }
                        if ($termek) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setNemkaphato((int)$data['stock'] == 0);
                                if ($termek->getNemkaphato()) {
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            }
                            if (!$termek->getAkcios()) {
                                $termek->setNetto((float)$data['netPrice'] * $arszaz / 100);
                                $termek->setBrutto(round($termek->getBrutto(), -1));
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->findBySzam('-');
                        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
                        $markacs = $this->getRepo('Entities\Termekcimkekat')->find(\mkw\store::getParameter(\mkw\consts::MarkaCs));
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }
            $this->setRunningImport(\mkw\consts::RunningSmileebikeImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function szimport()
    {
//        $translaterepo = \mkw\store::getEm()->getRepository('Gedmo\Translatable\Entity\Translation');

        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $parentid = $this->params->getIntRequestParam('katid', 0);
        $parent = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        if ($dbtol < 2) {
            $dbtol = 2;
        }

        $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
        //pathinfo

        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();
        if (!$dbig) {
            $dbig = $maxrow;
        }
        $maxcol = $sheet->getHighestColumn();
        $maxcolindex = Coordinate::columnIndexFromString($maxcol);

        $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
        $afa = $afa[0];
        $termekrepo = \mkw\store::getEm()->getRepository('Entities\Termek');
        $termekarrepo = \mkw\store::getEm()->getRepository('Entities\TermekAr');
        $valutanemek = [];
        $vnemek = \mkw\store::getEm()->getRepository('Entities\Valutanem')->getAll([], []);
        foreach ($vnemek as $vn) {
            $valutanemek[$vn->getNev()] = $vn;
        }

        $fej = [];
        for ($col = 0; $col < $maxcolindex; ++$col) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
            $fej[$col] = $cell->getValue();
        }

        for ($row = $dbtol; $row <= $dbig; ++$row) {
            $ujtermek = false;
            $kod = false;
            $vonalkod = false;
            $cikkszam = false;
            $nev = [];
            $vtsz = false;
            $netto = [];
            $brutto = [];

            for ($col = 0; $col < $maxcolindex; ++$col) {
                $cell = $sheet->getCellByColumnAndRow($col + 1, $row);
                if ($fej[$col] == 'kod') {
                    $kod = $cell->getValue();
                } elseif ($fej[$col] == 'vonalkod') {
                    $vonalkod = $cell->getValue();
                } elseif ($fej[$col] == 'cikkszam') {
                    $cikkszam = $cell->getValue();
                } elseif ($fej[$col] == 'vtsz') {
                    $vtsz = $cell->getValue();
                } elseif ($cell->getValue() && substr($fej[$col], 0, 4) == 'nev_') {
                    $nyelv = strtoupper(substr($fej[$col], 4));
                    $nev[\mkw\store::getLocaleName($nyelv)] = $cell->getValue();
                } elseif ($cell->getValue() && substr($fej[$col], 0, 3) == 'nev') {
                    $nev[\mkw\store::getLocaleName('HU')] = $cell->getValue();
                    // TODO: arsav
                } elseif ($cell->getValue() && substr($fej[$col], 0, 6) == 'netto_') {
                    $n = explode('_', $fej[$col]);
                    $netto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                    // TODO: arsav
                } elseif ($cell->getValue() && substr($fej[$col], 0, 7) == 'brutto_') {
                    $n = explode('_', $fej[$col]);
                    $brutto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                }
            }

            $termek = false;
            if ($kod) {
                $termek = $termekrepo->find($kod);
            } elseif ($vonalkod) {
                $termek = $termekrepo->findByVonalkod($vonalkod);
            } elseif ($cikkszam) {
                $termek = $termekrepo->findByCikkszam($cikkszam);
            }

            if ($termek) {
                if (is_array($termek)) {
                    $termek = $termek[0];
                }
            } else {
                if ($createuj && is_array($nev) && array_key_exists('HU', $nev)) {
                    $ujtermek = true;
                    $termek = new \Entities\Termek();
                    $termek->setMekod($this->getME('db'));
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
                                    // TODO: arsav
                                    $ar = $termekarrepo->findBy(['termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename]);
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    // TODO: arsav
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
                                    // TODO: arsav
                                    $ar = $termekarrepo->findBy(['termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename]);
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    // TODO: arsav
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
                                    ['object' => $termek->getId(), 'locale' => $loc, 'field' => 'nev']
                                );
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
                        } else {
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

    public function copydepotermekImport()
    {
        function toArr($obj)
        {
            $xmlkepek = (array)$obj->dokumentumok;
            $kepek = [];
            foreach ($xmlkepek['dokurl'] as $xk) {
                $kepek[] = (string)$xk;
            }
            return [
                'cikkszam' => (string)$obj->cikkszam,
                'cikknev' => (string)$obj->cikknev,
                'ean' => (string)$obj->ean,
                'megys' => (string)$obj->megys,
                'ear' => (float)$obj->ear,
                'afakulcs' => (string)$obj->afakulcs,
                'vtsz' => (string)$obj->vtsz,
                'tiltott' => (string)$obj->tiltott,
                'leiras' => (string)$obj->leiras,
                'csop0kod' => (string)$obj->csop0kod,
                'csop0' => (string)$obj->csop0,
                'marka' => (string)$obj->csop1,
                'images' => $kepek,
            ];
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningCopydepoTermekImport)) {
            $this->setRunningImport(\mkw\consts::RunningCopydepoTermekImport, 1);

            $logfile = 'copydepotermek_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $parentid = $this->params->getIntRequestParam('katid', 0);
            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoCopydepo);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $editleiras = $this->params->getBoolRequestParam('editleiras', false);
            $editnev = $this->params->getBoolRequestParam('editnev', false);
            $createuj = $this->params->getBoolRequestParam('createuj', false);
            $arszaz = $this->params->getNumRequestParam('arszaz', 100);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            $urleleje = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathCopydepo));
            $path = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('path.termekkep') . \mkw\store::getParameter(\mkw\consts::PathCopydepo));

            $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
            if ($mainpath) {
                $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $path = $mainpath . $path;
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (\mkw\store::isCopydepoTeszt()) {
                $ch = \curl_init('https://www.mindentkapni.hu/t/copydepotermekdownload');
                $fh = fopen(\mkw\store::storagePath('copydepotermek.xml'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
                \curl_close($ch);
            } else {
                if (\mkw\store::isDeveloper()) {
                    move_uploaded_file($_FILES['toimport']['tmp_name'], \mkw\store::storagePath('copydepotermek.xml'));
                } else {
                    $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlCopydepoTermek));
                    $fh = fopen(\mkw\store::storagePath('copydepotermek.xml'), 'w');
                    \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    \curl_setopt($ch, CURLOPT_FILE, $fh);
                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $curlretval = \curl_exec($ch);
                    if ($curlretval !== false && (!\curl_errno($ch))) {
                        $http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if ($http_code !== 200) {
                            \mkw\store::writelog($http_code);
                        }
                    } else {
                        \mkw\store::writelog(\curl_errno($ch));
                        \mkw\store::writelog(\curl_error($ch));
                    }
                    fclose($fh);
                    \curl_close($ch);
                }
            }
            $xml = simplexml_load_file(\mkw\store::storagePath('copydepotermek.xml'));
            $lettlog = false;
            if ($xml) {
                $gyarto = \mkw\store::getEm()->getRepository(Partner::class)->find($gyartoid);
                $markacs = $this->getRepo(Termekcimkekat::class)->find(\mkw\store::getParameter(\mkw\consts::MarkaCs));

                $products = $xml->cikk;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                $termekek = [];
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if ($data['cikkszam']) {
                        $ujtermek = false;
                        $idegencikkszam = $data['cikkszam'];
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid]);
                        if (!$termek) {
                            if ($createuj) {
                                $ujtermek = true;
                                $parent = $this->createKategoria($data['csop0'], $parentid);
                                $urlkatnev = \mkw\store::urlize($data['csop0']);
                                \mkw\store::createDirectoryRecursively($path . $urlkatnev);

                                $termeknev = trim($data['cikknev']);

                                $hosszuleiras = trim($data['leiras']);
                                $rovidleiras = trim($data['leiras']);

                                $afa = $this->createAfa($data['afakulcs']);
                                $vtsz = $this->createVtsz($data['vtsz'], $afa);

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $this->createME($data['megys']);
                                $termek->setMekod($this->getME($data['megys']));
                                $termek->setNev($termeknev);
                                $termek->setLeiras($hosszuleiras);
                                $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                                $termek->setCikkszam($idegencikkszam);
                                $termek->setIdegencikkszam($idegencikkszam);
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz);
                                $termek->setHparany(3);
                                $termek->setVonalkod($data['ean']);
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                                if (trim($data['csop1'])) {
                                    $cimke = $this->createTermekCimke($markacs, trim($data['csop1']));
                                    if ($cimke) {
                                        $termek->addCimke($cimke);
                                    }
                                }
                                // kepek
                                if ($data['images']) {
                                    $imgcnt = 0;
                                    foreach ($data['images'] as $imgurl) {
                                        $imgcnt++;

                                        $nameWithoutExt = $path . $this->urlkatnev($urlkatnev) . \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                        $kepnev = \mkw\store::urlize($termeknev . '_' . $idegencikkszam);
                                        if (count($data['images']) > 1) {
                                            $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                            $kepnev = $kepnev . '_' . $imgcnt;
                                        }

                                        $extension = \mkw\store::getExtension($imgurl);
                                        $imgpath = $nameWithoutExt . '.' . $extension;

                                        $ch = \curl_init($imgurl);
                                        $ih = fopen($imgpath, 'w');
                                        \curl_setopt($ch, CURLOPT_FILE, $ih);
                                        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                        \curl_exec($ch);
                                        fclose($ih);

                                        foreach ($this->settings['sizes'] as $k => $size) {
                                            $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                            $matches = explode('x', $size);
                                            \mkw\thumbnail::createThumb(
                                                $imgpath,
                                                $newFilePath,
                                                (int)$matches[0],
                                                (int)$matches[1],
                                                $this->settings['quality'],
                                                true
                                            );
                                        }
                                        if (((count($data['images']) > 1) && ($imgcnt == 1)) || (count($data['images']) == 1)) {
                                            $termek->setKepurl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                            $termek->setKepleiras($termeknev);
                                        } else {
                                            $kep = new \Entities\TermekKep();
                                            $termek->addTermekKep($kep);
                                            $kep->setUrl($urleleje . $this->urlkatnev($urlkatnev) . $kepnev . '.' . $extension);
                                            $kep->setLeiras($termeknev);
                                            \mkw\store::getEm()->persist($kep);
                                        }
                                    }
                                }
                            }
                        } else {
                            $termek = $termek[0];
                            if (!$termek->getVonalkod()) {
                                $termek->setVonalkod($data['ean']);
                            }
                            if ($editleiras) {
                                $hosszuleiras = trim($data['leiras']);
                                $termek->setLeiras($hosszuleiras);
                            }
                        }
                        if ($termek) {
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setInaktiv($data['tiltott'] === 'I');
                                if ($termek->getInaktiv()) {
                                    \mkw\store::writelog(
                                        'INAKTÍV (tiltott=I)'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            }
                            if (!$termek->getAkcios()) {
                                $termek->setNetto((float)$data['ear'] * $arszaz / 100);
                                $termek->setBrutto(round($termek->getBrutto(), -1));
                            }
                            \mkw\store::getEm()->persist($termek);
                        }
                    }
                    if (($termekdb % $batchsize) === 0) {
                        \mkw\store::getEm()->flush();
                        \mkw\store::getEm()->clear();
                        $gyarto = \mkw\store::getEm()->getRepository(Partner::class)->find($gyartoid);
                        $markacs = $this->getRepo(Termekcimkekat::class)->find(\mkw\store::getParameter(\mkw\consts::MarkaCs));
                    }
                    $termekdb++;
                }
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->clear();
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }
            $this->setRunningImport(\mkw\consts::RunningCopydepoTermekImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function copydepokeszletImport()
    {
        function toArr($keszlet)
        {
            $obj = $keszlet;
            return [
                'cikkszam' => (string)$obj->cikkszam,
                'keszleten' => (string)$obj->keszleten,
                'uton' => (string)$obj->uton,
                'erkezes' => (string)$obj->erkezes,
                'rendelheto' => (string)$obj->rendelheto,
                'rszszi' => (string)$obj->rszszi,
            ];
        }

        if (!$this->checkRunningImport(\mkw\consts::RunningCopydepoKeszletImport)) {
            $this->setRunningImport(\mkw\consts::RunningCopydepoKeszletImport, 1);

            $logfile = 'copydepokeszlet_log.txt';
            $logurl = \mkw\store::logsUrl($logfile);

            @unlink(\mkw\store::logsPath($logfile));

            $gyartoid = \mkw\store::getParameter(\mkw\consts::GyartoCopydepo);
            $dbtol = $this->params->getIntRequestParam('dbtol', 0);
            $dbig = $this->params->getIntRequestParam('dbig', 0);
            $batchsize = $this->params->getNumRequestParam('batchsize', 20);

            if (\mkw\store::isCopydepoTeszt()) {
                $ch = \curl_init('https://www.mindentkapni.hu/t/copydepokeszletdownload');
                $fh = fopen(\mkw\store::storagePath('copydepokeszlet.xml'), 'w');
                \curl_setopt($ch, CURLOPT_FILE, $fh);
                \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                \curl_exec($ch);
                fclose($fh);
                \curl_close($ch);
            } else {
                if (\mkw\store::isDeveloper()) {
                    move_uploaded_file($_FILES['toimport']['tmp_name'], \mkw\store::storagePath('copydepokeszlet.xml'));
                } else {
                    $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlCopydepoKeszlet));
                    $fh = fopen(\mkw\store::storagePath('copydepokeszlet.xml'), 'w');
                    \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    \curl_setopt($ch, CURLOPT_FILE, $fh);
                    \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    \curl_exec($ch);
                    fclose($fh);
                    \curl_close($ch);
                }
            }
            $xml = simplexml_load_file(\mkw\store::storagePath('copydepokeszlet.xml'));
            $lettlog = false;
            if ($xml) {
                $products = $xml->cikkeszlet;
                if (!$dbig) {
                    $dbig = count($products);
                }

                $termekdb = $dbtol;
                while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                    $data = toArr($products[$termekdb]);
                    if ($data['cikkszam']) {
                        $idegencikkszam = $data['cikkszam'];
                        /** @var Termek $termek */
                        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->findBy(['idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid]);
                        if ($termek) {
                            /** @var Termek $termek */
                            $termek = $termek[0];
                            if ($termek->getKeszlet() <= 0) {
                                $termek->setInaktiv($data['rendelheto'] === 'N');
                                if ($termek->getInaktiv()) {
                                    \mkw\store::writelog(
                                        'INAKTÍV (rendelheto=N)'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                                $termek->setNemkaphato($data['keszleten'] === 'N');
                                if ($termek->getNemkaphato()) {
                                    \mkw\store::writelog(
                                        'NEM KAPHATÓ (keszleten=N)'
                                        . ' termék cikkszám: ' . $termek->getCikkszam()
                                        . ' termék szállítói cikkszám: ' . $termek->getIdegencikkszam(),
                                        $logfile
                                    );
                                    $lettlog = true;
                                }
                            }
                            \mkw\store::getEm()->persist($termek);
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
                if ($lettlog) {
                    echo json_encode(['url' => $logurl]);
                }
            }
            $this->setRunningImport(\mkw\consts::RunningCopydepoKeszletImport, 0);
        } else {
            echo json_encode(['msg' => 'Már fut ilyen import.']);
        }
    }

    public function aszfdownload()
    {
        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::ASZFUrl));
        $fh = fopen(\mkw\store::mainStoragePath(\mkw\consts::ASZFPDFName), 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_exec($ch);
        fclose($fh);
    }
}