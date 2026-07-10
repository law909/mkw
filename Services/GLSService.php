<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\CsomagTerminal;

class GLSService
{
    public function downloadGLSTerminalList()
    {
        $sep = ';';
        $ch = curl_init(\mkw\store::getParameter(\mkw\consts::GLSTerminalURL));
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'w');
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        fclose($fh);
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'r');
        if ($fh) {
            $pontok = [];
            fgetcsv($fh, 0, $sep);
            while ($data = fgetcsv($fh, 0, $sep)) {
                $pontok[] = $data;
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
                if (!$megvan) {
                    $terminal->setInaktiv(!$megvan);
                    \mkw\store::getEm()->persist($terminal);
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();
        }
    }

    public function sendToGLS($ids)
    {
        $db = 0;
        $pdfname = false;
        $glsmegrend = [];
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
                $glsmegrend[] = $megrendfej->toGLSAPI();
                if ($db == 4) {
                    $this->_sendToGLS($glsmegrend, $pdfname);
                    $db = 0;
                    $pdfname = false;
                    $glsmegrend = [];
                }
            }
        }
        if ($glsmegrend) {
            $this->_sendToGLS($glsmegrend, $pdfname);
        }
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