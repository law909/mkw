<?php

namespace mkwhelpers;

class NAVOnline
{

    const NAVOnlineProduction = 'prod';
    const NAVOnlineDeveloper = 'dev';

    private $serviceURL = 'http://no.billy.hu/api';
    private $cegAdoszam;
    private $errors = [];
    private $result;

    /*
    no.ServiceURL:=DM._Param.ReadString(pNOURL,'http://no.billy.hu');
    no.API:='api';
    no.CegAdoszam:=DM._Param.ReadString(pTulajAdoszam,'');
    */

    public function __construct($adoszam)
    {
        $this->cegAdoszam = $adoszam;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsAsHtml()
    {
        $str = '';
        foreach ($this->getErrors() as $error) {
            $str .= $error['code'] . ' - ' . $error['message'];
        }
        return $str;
    }

    public function getResult()
    {
        return $this->result;
    }

    private function getServiceURL()
    {
        return $this->serviceURL;
    }

    private function callAPI($httpcommand, $command, $data = null)
    {
        $sikeres = false;
        $this->result = null;
        $this->errors = [];
        $ch = curl_init($this->getServiceURL() . $command);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpcommand);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $res = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($res === false) {
            $this->errors[] = [
                'code' => '1',
                'message' => 'A NAV Online számla beküldő szolgáltatás nem érhető el.'
            ];
        } else {
            switch ($http_code) {
                case 200:
                    $errmsg = false;
                    $this->result = $res;
                    $sikeres = true;
                    break;
                case 403:
                    $errmsg = 'Nem engedélyezett hozzáférés.';
                    break;
                case 404:
                    $errmsg = 'Nem található URL.';
                    break;
                case 460:
                    $errmsg = 'XML formai hiba.';
                    break;
                case 461:
                    $errmsg = 'XML tartalmi hiba. Az XML nem validálható a NAV-os XSD-vel.';
                    break;
                case 462:
                    $errmsg = 'Nincs ilyen számla a NAV Számlabeküldő szolgáltatásban.';
                    break;
                case 463:
                    $errmsg = 'A számla nem módosítható, a NAV már befogadta.';
                    break;
                case 464:
                    $errmsg = 'A számla nem módosítható, már elindult a beküldés.';
                    break;
                case 465:
                    $errmsg = 'A számla még nincs beküldve a NAV-hoz.';
                    break;
                case 466:
                    $errmsg = 'Az adószám nem valid.';
                    break;
                default:
                    $errmsg = 'Egyéb hiba';
            }
            if ($errmsg) {
                $this->errors[] = [
                    'code' => $http_code,
                    'message' => $errmsg
                ];
            }
        }
        curl_close($ch);
        return $sikeres;
    }

    public function hello()
    {
        return $this->callAPI('GET', '/hello/' . $this->cegAdoszam);
    }

    public function version()
    {
        return $this->callAPI('GET', '/version/' . $this->cegAdoszam);
    }

    public function querytaxpayer($payernum)
    {
        return $this->callAPI('GET', '/querytaxpayer/' . $this->cegAdoszam . '/' . $payernum);
    }

    public function sendSzamla($bizszam, $data)
    {
        if (\mkw\store::isTeszt()) {
            $this->errors = [];
            $this->result = 'TESZT';
            return true;
        }
        $operation = substr($data, 0, 6);
        $szladata = substr($data, 6);
        $postdata = [
            'operation' => $operation,
            'data' => $szladata
        ];
        return $this->callAPI('POST', '/invoice/save/' . $this->cegAdoszam . '/' . base64_encode($bizszam), $postdata);
    }

    public function validate($data)
    {
        $szladata = substr($data, 6);
        $postdata = [
            'data' => $szladata
        ];
        return $this->callAPI('POST', '/invoice/validate/' . $this->cegAdoszam, $postdata);
    }

    public function getSzamlaInfo($bizszam)
    {
        return $this->callAPI('GET', '/invoice/' . $this->cegAdoszam . '/' . base64_encode($bizszam));
    }

    public function getSzamlaContent($bizszam)
    {
        return $this->callAPI('GET', '/invoice/getcontent/' . $this->cegAdoszam . '/' . base64_encode($bizszam));
    }

    public function getAllSzamlaInfo()
    {
        return $this->callAPI('GET', '/invoices/' . $this->cegAdoszam);
    }

    public function getSomeSzamlaInfo($bizszamlist)
    {
        $bl = implode('##', $bizszamlist);
        $postdata = [
            'bizszamlist' => $bl
        ];
        return $this->callAPI('POST', '/someinvoices/' . $this->cegAdoszam, $postdata);
    }

    public function requeryFromNAV($bizszam)
    {
        return $this->callAPI('GET', '/invoice/requery/' . $this->cegAdoszam . '/' . base64_encode($bizszam));
    }

    public function getInboundDigests($fromDate, $toDate)
    {
        return $this->callAPI('GET', '/inbound/digests/' . $this->cegAdoszam . '/' . $fromDate . '/' . $toDate);
    }

    public function getInbound($bizszam)
    {
        return $this->callAPI('GET', '/inbound/' . $this->cegAdoszam . '/' . base64_encode($bizszam));
    }

    public function getInboundList($bizszamlist)
    {
        $bizszamlist = array_map('base64_encode', $bizszamlist);
        $bl = implode('##', $bizszamlist);
        $postdata = [
            'bizszamlist' => $bl
        ];
        return $this->callAPI('POST', '/inbound/list/' . $this->cegAdoszam, $postdata);
    }

}