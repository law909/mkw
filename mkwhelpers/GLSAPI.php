<?php

namespace mkwhelpers;

class GLSAPI {

    private $clientnumber;
    private $username;
    private $password;
    private $apiurl = 'https://api.test.mygls.hu/ParcelService.svc/json/';
    private $pdfdirectory;
    private $lasterrors = [];

    public function __construct($param) {
        $this->clientnumber = $param['clientnumber'];
        $this->username = $param['username'];
        $this->password = array_values(unpack('C*', hash('sha512', $param['password'], true)));
            //"[".implode(',',unpack('C*', hash('sha512', $password, true)))."]";
        if ($param['apiurl']) {
            $this->apiurl = $param['apiurl'];
        }
        $this->pdfdirectory = $param['pdfdirectory'];
    }

    /**
     * @return array
     */
    public function getLasterrors() {
        return $this->lasterrors;
    }

    /**
     * @return mixed
     */
    public function getPdfdirectory() {
        return $this->pdfdirectory;
    }

    protected function callAPI($endpoint, $data, $printpos = null, $printdialog = null) {
        $req = [];
        $req['Username'] = $this->username;
        $req['Password'] = $this->password;
        $req = array_merge_recursive($req, $data);
        if ($printpos) {
            $req['PrintPosition'] = $printpos;
        }
        if ($printdialog) {
            $req['ShowPrintDialog'] = 1;
        }
        $req = json_encode($req, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        \mkw\store::writelog($req, 'gls.log');
        \mkw\store::writelog($this->apiurl . $endpoint, 'gls.log');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $this->apiurl . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($req))
        );
        $response = curl_exec($curl);

        \mkw\store::writelog($response, 'gls.log');
        if (!curl_errno($curl)) {
            \mkw\store::writelog(print_r(curl_getinfo($curl), true));
        }
        else {
            \mkw\store::writelog(curl_errno($curl) . ' ' . curl_error($curl));
        }

        curl_close($curl);

        return $response;
    }

    public function prepareLabels($parceldata) {
        $requestdata = [];
        $requestdata['ParcelList'] = $parceldata;

        $response = $this->callAPI('PrepareLabels', $requestdata);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $response->PrepareLabelsErrorList;
            if(count($response->PrepareLabelsErrorList) === 0 && count($response->ParcelInfoList) > 0) {
                return $response->ParcelInfoList;
            }
        }
        return false;
    }

    public function getPrintedLabels($parcelids, $pdfname, $printposition = 1, $printdialog = 0) {
        $requestdata = [];
        $requestdata['ParcelIdList'] = $parcelids;

        $pdfname = implode('/', [
            rtrim($this->pdfdirectory, '/'),
            $pdfname
        ]);

        $response = $this->callAPI('GetPrintedLabels', $requestdata, $printposition, $printdialog);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $response->GetPrintedLabelsErrorList;
            if (count($response->GetPrintedLabelsErrorList) === 0 && count($response->Labels) > 0) {
                $pdf = implode(array_map('chr', $response->Labels));
                file_put_contents($pdfname, $pdf);
                return true;
            }
        }
        return false;
    }

    public function printLabels($parcellist, $pdfname, $printposition = 1, $printdialog = 0) {
        $requestdata = [];
        $requestdata['ParcelList'] = $parcellist;

        $pdfname = implode('/', [
            rtrim($this->pdfdirectory, '/'),
            $pdfname
        ]);

        $response = $this->callAPI('PrintLabels', $requestdata, $printposition, $printdialog);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $response->PrintLabelsErrorList;
            if (count($response->PrintLabelsErrorList) === 0 && count($response->Labels) > 0) {
                $pdf = implode(array_map('chr', $response->Labels));
                file_put_contents($pdfname, $pdf);
                return $response->PrintLabelsInfoList;
            }
        }
        return false;
    }

    public function getPrintData($parcellist) {
        $requestdata = [];
        $requestdata['ParcelList'] = $parcellist;

        $response = $this->callAPI('GetPrintData', $requestdata);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $response->GetPrintDataErrorList;
            if (count($response->GetPrintDataErrorList) === 0 && count($response->PrintDataInfoList) > 0) {
                return $response->PrintDataInfoList;
            }
        }
        return false;
    }

    public function deleteLabels($parcelidlist) {
        $requestdata = [];
        $requestdata['ParcelIdList'] = $parcelidlist;

        $response = $this->callAPI('DeleteLabels', $requestdata);
        if ($response) {
            $response = json_decode($response);
            $this->lasterrors = $response->DeleteLabelsErrorList;
            if (count($response->DeleteLabelsErrorList) === 0 && count($response->SuccessfullyDeletedList) > 0) {
                return $response->SuccessfullyDeletedList;
            }
        }
        return false;
    }

    public function modifyCOD($parcelid = null, $parcelnumber = null, $codamount = 0) {
        $requestdata = [];
        if ($parcelid) {
            $requestdata['ParcelId'] = $parcelid;
        }
        else {
            $requestdata['ParcelNumber'] = $parcelnumber;
        }
        $requestdata['CODAmount'] = $codamount;

        $response = $this->callAPI('ModifyCOD', $requestdata);
        if ($response) {
            $response = json_decode($response);;
            $this->lasterrors = $response->ModifyCODError;
            if (count($response->ModifyCODError) === 0) {
                return $response->Successful;
            }
        }
        return false;
    }

}