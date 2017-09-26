<?php

namespace Controllers;

class emagController extends \mkwhelpers\Controller {

    protected function calcHash($data) {
        return sha1(http_build_query($data) . sha1(\mkw\store::getParameter(\mkw\consts::EmagPassword)));
    }

    protected function sendRequest($resource, $action, $data) {
        $requestData = array(
            'code' => 'usercode',
            'username' => 'username',
            'data' => $data,
            'hash' => $this->calcHash($data));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \mkw\store::getParameter(\mkw\consts::EmagAPIUrl) . '/' . $resource . '/' . $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        $result = curl_exec($ch);
        return json_decode($result, true);
    }

    protected function checkResult($res) {
        return array_key_exists('isError', $res) && !$res['isError'];
    }

    public function getVAT() {
        $params = array(
            'currentPage' => 1,
            'itemsPerPage' => 10
        );
        return $this->sendRequest('vat', 'read', $params);
    }

    public function countCategories() {
        $ret = $this->sendRequest('category', 'count', array());
        if ($this->checkResult($ret)) {
            return $ret['results'];
        }
        return false;
    }

    public function getCategories() {
        $perpage = 100;
        $count = $this->countCategories();
        $cats = array();
        if ($count !== false) {
            $szor = ($count % $perpage) + 1;
            for ($i = 1; $i <= $szor; $i++) {
                $r = $this->sendRequest(
                    'category',
                    'read',
                    array(
                        'currentPage' => $i,
                        'itemsPerPage' => $perpage
                    )
                );
                if ($this->checkResult($r)) {
                    $cats[] = $r['results'];
                }
            }
        }
        return $cats;
    }
}