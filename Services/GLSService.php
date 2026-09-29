<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\CsomagTerminal;

class GLSService
{
    /**
     * @return array ['letoltve' => a listában szereplő pontok, 'inaktivalt' => a listából kimaradt, most inaktivált pontok]
     * @throws \Exception ha nincs URL, vagy a letöltés nem hozott egyetlen csomagpontot sem
     */
    public function downloadGLSTerminalList()
    {
        $sep = ';';
        $url = trim((string)\mkw\store::getParameter(\mkw\consts::GLSTerminalURL));
        if ($url === '') {
            throw new \Exception(t('Nincs megadva a GLS csomagpont URL a Beállításokban.'));
        }
        $ch = curl_init($url);
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'w');
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $sikeres = curl_exec($ch);
        $curlhiba = curl_error($ch);
        fclose($fh);
        if (!$sikeres) {
            throw new \Exception(sprintf(t('A GLS csomagpont lista letöltése nem sikerült: %s'), $curlhiba));
        }
        $inaktivalt = 0;
        $pontok = [];
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'r');
        if ($fh) {
            fgetcsv($fh, 0, $sep);
            while ($data = fgetcsv($fh, 0, $sep)) {
                $pontok[] = $data;
            }
            // üres lista mellett a lenti kör minden meglévő GLS pontot inaktiválna
            if (!$pontok) {
                throw new \Exception(t('A letöltött GLS csomagpont lista üres, a meglévő csomagpontok változatlanok maradtak.'));
            }
            $db = 0;
            foreach ($pontok as $i => $r) {
                $db++;
                $terminal = \mkw\store::getEm()->getRepository(CsomagTerminal::class)->findOneBy(['idegenid' => $r[\mkw\store::n('a')], 'tipus' => 'gls']);
                if (!$terminal) {
                    $terminal = new CsomagTerminal();
                }
                $terminal->setIdegenid($r[\mkw\store::n('a')]);
                $terminal->setNev(\mkw\store::toutf($r[\mkw\store::n('f')]));
                $terminal->setCim(\mkw\store::toutf($r[\mkw\store::n('e')]));
                $terminal->setCsoport(\mkw\store::toutf($r[\mkw\store::n('d')]));
                $terminal->setFindme(\mkw\store::toutf($r[\mkw\store::n('g')] . ' ' . $r[\mkw\store::n('h')]));
                $terminal->setInaktiv(false);
                $terminal->setTipus('gls');
                \mkw\store::getEm()->persist($terminal);
                if ($db % 20 === 0) {
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('tipus', '=', 'gls');
            $terminalok = \mkw\store::getEm()->getRepository(CsomagTerminal::class)->getAll($filter);
            /** @var \Entities\CsomagTerminal $terminal */
            foreach ($terminalok as $terminal) {
                $megvan = false;
                foreach ($pontok as $r) {
                    $megvan = $megvan || ($r[\mkw\store::n('a')] == $terminal->getIdegenid());
                }
                if (!$megvan && !$terminal->getInaktiv()) {
                    $terminal->setInaktiv(true);
                    \mkw\store::getEm()->persist($terminal);
                    $inaktivalt++;
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();
        }
        return ['letoltve' => count($pontok), 'inaktivalt' => $inaktivalt];
    }

    /** @return array ['aktiv' => int, 'inaktiv' => int] a GLS csomagpontok száma a törzsben */
    public function getTerminalStat()
    {
        $sorok = \mkw\store::getEm()->getConnection()->fetchAllKeyValue(
            'SELECT inaktiv, COUNT(*) FROM csomagterminal WHERE tipus = ? GROUP BY inaktiv',
            ['gls']
        );
        return ['aktiv' => (int)($sorok[0] ?? 0), 'inaktiv' => (int)($sorok[1] ?? 0)];
    }

    /**
     * @return array a GLS hibái olvasható formában – üres tömb, ha minden címke elkészült
     */
    public function sendToGLS($ids)
    {
        $db = 0;
        $pdfname = false;
        $glsmegrend = [];
        $errors = [];
        $osszes = 0;
        foreach ($ids as $id) {
            /** @var Bizonylatfej $megrendfej */
            $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($megrendfej
                && (\mkw\store::isGLSSzallitasimod($megrendfej->getSzallitasimodId())
                    || \mkw\store::isGLSFutarSzallitasimod($megrendfej->getSzallitasimodId()))
                && (!$megrendfej->getGlsparcelid())
            ) {
                if (!$pdfname) {
                    $pdfname = $megrendfej->getSanitizedId() . '_parcel_label.pdf';
                }
                $db++;
                $osszes++;
                $glsmegrend[] = $megrendfej->toGLSAPI();
                if ($db == 4) {
                    $errors = array_merge($errors, $this->_sendToGLS($glsmegrend, $pdfname));
                    $db = 0;
                    $pdfname = false;
                    $glsmegrend = [];
                }
            }
        }
        if ($glsmegrend) {
            $errors = array_merge($errors, $this->_sendToGLS($glsmegrend, $pdfname));
        }
        if (!$osszes) {
            $errors[] = t('A kijelöltek közt nincs olyan GLS-es megrendelés, amelynek még nincs csomagcímkéje.');
        }
        return $errors;
    }

    private function _sendToGLS($glsmegrend, $pdfname)
    {
        $glsapi = new \mkwhelpers\GLSAPI([
                'clientnumber' => \mkw\store::getParameter(\mkw\consts::GLSClientNumber),
                'username' => \mkw\store::getParameter(\mkw\consts::GLSUsername),
                'password' => \mkw\store::getParameter(\mkw\consts::GLSPassword),
                'apiurl' => \mkw\store::getParameter(\mkw\consts::GLSApiURL),
                'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::GLSParcelLabelDir)
            ]
        );
        $glsres = $glsapi->printLabels($glsmegrend, $pdfname);
        $glserror = $glsapi->getLasterrors();
        if ($glserror) {
            \mkw\store::writeLog('GLS API error: ' . json_encode($glserror), 'gls_api_error.txt');
        }
        if ($glsres) {
            $pdfname = implode('/', [
                rtrim($glsapi->getPdfdirectory(), '/'),
                $pdfname
            ]);
            foreach ($glsres as $item) {
                /** @var Bizonylatfej $megrendfej */
                $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($item->ClientReference);
                if ($megrendfej) {
                    $megrendfej->setSimpleedit(true);
                    $megrendfej->setGlsparcelid($item->ParcelId);
                    $megrendfej->setGlsparcellabelurl($pdfname);
                    $megrendfej->setFuvarlevelszam($item->ParcelNumber);
                    \mkw\store::getEm()->persist($megrendfej);
                    \mkw\store::getEm()->flush();
                }
            }
        }
        return $this->collectErrors($glserror, $glsres);
    }

    /**
     * A GLS hibalistája bizonylatszámmal együtt, hogy a felhasználó lássa, melyik küldemény
     * min bukott el (pl. "Invalid data in 'Delivery Zip Code'").
     */
    private function collectErrors($glserror, $glsres)
    {
        $result = [];
        foreach ((array)$glserror as $error) {
            $bizonylatok = implode(', ', (array)($error->ClientReferenceList ?? []));
            $result[] = trim($bizonylatok . ': ' . ($error->ErrorDescription ?? ''), ': ');
        }
        if (!$result && !$glsres) {
            $result[] = t('A GLS nem válaszolt.');
        }
        return $result;
    }

    public function delGLSParcel($id)
    {
        /** @var \Entities\Bizonylatfej $megrendfej */
        $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if ($megrendfej) {
            $glsapi = new \mkwhelpers\GLSAPI([
                    'clientnumber' => \mkw\store::getParameter(\mkw\consts::GLSClientNumber),
                    'username' => \mkw\store::getParameter(\mkw\consts::GLSUsername),
                    'password' => \mkw\store::getParameter(\mkw\consts::GLSPassword),
                    'apiurl' => \mkw\store::getParameter(\mkw\consts::GLSApiURL),
                    'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::GLSParcelLabelDir)
                ]
            );
            $glsres = $glsapi->deleteLabels([$megrendfej->getGlsparcelid()]);
            if ($glsres && $glsres[0]->ParcelId == $megrendfej->getGlsparcelid()) {
                $megrendfej->setSimpleedit(true);
                $megrendfej->setGlsparcellabelurl(null);
                $megrendfej->setGlsparcelid(null);
                $megrendfej->setFuvarlevelszam(null);
                \mkw\store::getEm()->persist($megrendfej);
                \mkw\store::getEm()->flush();
            }
        }
    }

}