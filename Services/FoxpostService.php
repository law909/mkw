<?php

namespace Services;

use Controllers\csomagterminalController;
use Entities\Bizonylatfej;
use Entities\CsomagTerminal;

class FoxpostService
{

    public function sendToFoxPost($ids)
    {
        foreach ($ids as $id) {
            /** @var \Entities\Bizonylatfej $bizfej */
            $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($bizfej && \mkw\store::isFoxpostSzallitasimod($bizfej->getSzallitasimodId()) && !$bizfej->getFoxpostBarcode()) {
                $bizfej->setSimpleedit(true);
                $fpres = $this->sendMegrendelesToFoxpost($bizfej);
                if ($fpres) {
                    switch (\mkw\store::getParameter(\mkw\consts::FoxpostApiVersion, 'v2')) {
                        case 'v1':
                            $bizfej->setFoxpostBarcode($fpres['barcode']);
                            $bizfej->setFuvarlevelszam($fpres['barcode']);
                            if (array_key_exists('trace', $fpres)) {
                                $bizfej->setTraceurl($fpres['trace']['href']);
                            }
                            \mkw\store::getEm()->persist($bizfej);
                            \mkw\store::getEm()->flush();
                            break;
                        case 'v2':
                            if (is_array($fpres)) {
                                if ($fpres['success']) {
                                    $bizfej->setFoxpostBarcode($fpres['data']['clFoxId']);
                                    if (array_key_exists('barcodeTof', $fpres) && ($fpres['data']['barcodeTof'])) {
                                        $bizfej->setFuvarlevelszam($fpres['data']['barcodeTof']);
                                    } else {
                                        $bizfej->setFuvarlevelszam($fpres['data']['clFoxId']);
                                    }
                                    $bizfej->setSysmegjegyzes('');
                                    \mkw\store::getEm()->persist($bizfej);
                                } else {
                                    $bizfej->setSysmegjegyzes(json_encode($fpres['errors']));
                                    \mkw\store::getEm()->persist($bizfej);
                                }
                                \mkw\store::getEm()->flush();
                            }
                            break;
                    }
                }
            }
        }
    }

    public function generateFoxpostLabel($ids)
    {
        $clfids = [];
        foreach ($ids as $id) {
            /** @var \Entities\Bizonylatfej $bizfej */
            $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($bizfej && \mkw\store::isFoxpostSzallitasimod($bizfej->getSzallitasimodId()) && $bizfej->getFoxpostBarcode()) {
                $clfids[] = $bizfej->getFoxpostBarcode();
            }
        }
        if ($clfids) {
            $res = $this->generateFoxpostLabels($clfids);
            if ($res && $res['success']) {
                foreach ($ids as $id) {
                    /** @var \Entities\Bizonylatfej $bizfej */
                    $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
                    $bizfej->setSimpleedit(true);
                    $bizfej->setGlsparcellabelurl($res['data']);
                    $bizfej->setSysmegjegyzes(null);
                    \mkw\store::getEm()->persist($bizfej);
                }
                \mkw\store::getEm()->flush();
            } else {
                foreach ($ids as $id) {
                    /** @var \Entities\Bizonylatfej $bizfej */
                    $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
                    $bizfej->setSimpleedit(true);
                    $bizfej->setSysmegjegyzes(json_encode($res['errors']));
                    \mkw\store::getEm()->persist($bizfej);
                }
                \mkw\store::getEm()->flush();
            }
        }
    }

    private function initFoxpostCurl($resource)
    {
        switch (\mkw\store::getParameter(\mkw\consts::FoxpostApiVersion, 'v2')) {
            case 'v2':
                return $this->initFoxpostCurlv2($resource);
            default:
                return $this->initFoxpostCurlv2($resource);
        }
    }

    private function initFoxpostCurlv2($resource, array $headers = [])
    {
        $ch = curl_init(\mkw\store::getParameter(\mkw\consts::Foxpostv2ApiURL) . $resource);
        $vanaccept = false;
        foreach ($headers as $h) {
            if (strtolower(substr($h, 0, 7)) === 'accept:') {
                $vanaccept = true;
            }
        }
        if (!$vanaccept) {
            $headers[] = 'accept: application/json';
        }
        $headers[] = 'Content-Type: application/json';
        $headers[] = "api-key: " . \mkw\store::getParameter(\mkw\consts::Foxpostv2ApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt(
            $ch,
            CURLOPT_USERPWD,
            \mkw\store::getParameter(\mkw\consts::Foxpostv2Username) . ":" . \mkw\store::getParameter(\mkw\consts::Foxpostv2Password)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        return $ch;
    }

    public function downloadFoxpostTerminalList()
    {
        switch (\mkw\store::getParameter(\mkw\consts::FoxpostApiVersion, 'v2')) {
            case 'v2':
                return $this->downloadFoxpostTerminalListv2();
            default:
                return $this->downloadFoxpostTerminalListv2();
        }
    }

    private function downloadFoxpostTerminalListv2()
    {
        $ch = curl_init('https://cdn.foxpost.hu/apms.json');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/vnd.cleveron+json; version=1.0");
        curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/vnd.cleveron+json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $res = json_decode($res);
        curl_close($ch);
        if ($res && is_array($res)) {
            $db = 0;
            foreach ($res as $r) {
                $db++;
                $terminal = \mkw\store::getEm()->getRepository(CsomagTerminal::class)->findOneBy(['idegenid' => $r->operator_id, 'tipus' => 'foxpost']);
                if (!$terminal) {
                    $terminal = new \Entities\CsomagTerminal();
                }
                $terminal->setIdegenid($r->operator_id);
                $terminal->setNev($r->name);
                $terminal->setCim($r->address);
                $terminal->setCsoport($r->group);
                $terminal->setFindme(mb_substr($r->findme, 0, 254));
                $open = 'hétfő: ' . $r->open->hetfo
                    . '; kedd: ' . $r->open->kedd
                    . '; szerda: ' . $r->open->szerda
                    . '; csütörtök: ' . $r->open->csutortok
                    . '; péntek: ' . $r->open->pentek
                    . '; szombat: ' . $r->open->szombat
                    . '; vasárnap: ' . $r->open->vasarnap;
                $terminal->setNyitva($open);
                $terminal->setGeolat($r->geolat);
                $terminal->setGeolng($r->geolng);
                $terminal->setInaktiv(false);
                $terminal->setTipus('foxpost');
                \mkw\store::getEm()->persist($terminal);
                if ($db % 20 === 0) {
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->clear();
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('tipus', '=', 'foxpost');
            $terminalok = \mkw\store::getEm()->getRepository(CsomagTerminal::class)->getAll($filter);
            /** @var \Entities\CsomagTerminal $terminal */
            foreach ($terminalok as $terminal) {
                $megvan = false;
                foreach ($res as $r) {
                    $megvan = $megvan || ($r->operator_id == $terminal->getIdegenid());
                }
                if (!$megvan) {
                    $terminal->setInaktiv(!$megvan);
                    \mkw\store::getEm()->persist($terminal);
                }
            }
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->clear();
        }
        return $res;
    }

    public function sendMegrendelesToFoxpost(Bizonylatfej $fej)
    {
        switch (\mkw\store::getParameter(\mkw\consts::FoxpostApiVersion, 'v2')) {
            case 'v2':
                return $this->sendMegrendelesToFoxpostv2($fej);
            default:
                return $this->sendMegrendelesToFoxpostv2($fej);
        }
    }

    private function sendMegrendelesToFoxpostv2(Bizonylatfej $fej)
    {
        $ch = $this->initFoxpostCurl('parcel');

        $fields = [$fej->toFoxpostv2API()];
        $tosend = json_encode($fields);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $tosend);
        $res = curl_exec($ch);
        $res = json_decode($res, true);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            switch ($info['http_code']) {
                case 201:
                    if ($res['valid']) {
                        $retval = [
                            'success' => true,
                            'data' => $res['parcels'][0]
                        ];
                    } else {
                        $retval = [
                            'success' => false,
                            'http_code' => $info['http_code'],
                            'errors' => $res['parcels'][0]['errors']
                        ];
                    }
                    break;
                case 400:
                case 401:
                    $retval = [
                        'success' => false,
                        'http_code' => $info['http_code'],
                        'errors' => json_decode($res, true)
                    ];
                    break;
                default:
                    $retval = [
                        'success' => false,
                        'http_code' => $info['http_code'],
                        'errors' => 'unknown'
                    ];
                    break;
            }
        } else {
            $retval = [
                'success' => false,
                'http_code' => $info['http_code'],
                'errors' => [
                    'curl_errno' => curl_errno($ch),
                    'curl_error' => curl_error($ch)
                ]
            ];
        }
        curl_close($ch);
        return $retval;
    }

    private function generateFoxpostLabels(array $ids)
    {
        $ch = $this->initFoxpostCurlv2('label/A7?startPos=0', ['accept: application/pdf']);
        $tosend = json_encode($ids);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $tosend);
        $pdfname = implode('/', [
                rtrim(\mkw\store::getParameter(\mkw\consts::GLSParcelLabelDir), '/'),
                'foxpost' . date('YmdHis') . '.pdf'
            ]
        );
        $ih = fopen($pdfname, 'w');
        \curl_setopt($ch, CURLOPT_FILE, $ih);
        $res = curl_exec($ch);
        $res = json_decode($res, true);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            switch ($info['http_code']) {
                case 200:
                    $retval = [
                        'success' => true,
                        'data' => $pdfname
                    ];
                    break;
                case 400:
                case 401:
                    $retval = [
                        'success' => false,
                        'http_code' => $info['http_code'],
                        'errors' => json_decode($res, true)
                    ];
                    break;
                default:
                    $retval = [
                        'success' => false,
                        'http_code' => $info['http_code'],
                        'errors' => 'unknown'
                    ];
                    break;
            }
        } else {
            $retval = [
                'success' => false,
                'http_code' => $info['http_code'],
                'errors' => [
                    'curl_errno' => curl_errno($ch),
                    'curl_error' => curl_error($ch)
                ]
            ];
        }
        curl_close($ch);
        return $retval;
    }

}