<?php

namespace Controllers;

require_once "busvendor/Barion/library/BarionClient.php";

class barionController extends \mkwhelpers\Controller {

    private function createClient() {
        $poskey = \mkw\store::getParameter(\mkw\consts::BarionPOSKey);
        $apiversion = \mkw\store::getParameter(\mkw\consts::BarionAPIVersion);
        switch (\mkw\store::getParameter(\mkw\consts::BarionEnvironment)) {
            case 1:
                $env = \BarionEnvironment::Test;
                break;
            case 2:
                $env = \BarionEnvironment::Prod;
                break;
        }
        return new \BarionClient($poskey, $apiversion, $env);
    }

    /**
     * @param \Entities\Bizonylatfej $biz
     */
    public function startPayment($biz) {
        $res = array('result' => false);
        $bc = $this->createClient();
        $payment = $bc->PreparePayment($biz->toBarionModel());

        if ($payment->RequestSuccessful) {
            if ($payment->PaymentRequestId === $biz->getId()) {
                $biz->setBarionpaymentid($payment->PaymentId);
                $biz->setBarionpaymentstatus($payment->Status);
                $this->getEm()->persist($biz);
                $this->getEm()->flush();
                $res = array(
                    'result' => true,
                    'redirecturl' => $payment->PaymentRedirectUrl
                );
            }
        }
        return $res;
    }

    /**
     * @param $biz \Entities\Bizonylatfej
     */
    public function getPaymentState($biz) {
        $res = array('result' => false);
        $bc = $this->createClient();
        $state = $bc->GetPaymentState($biz->getBarionpaymentid());
        if ($state && $state->RequestSuccessful && $state->PaymentId === $biz->getBarionpaymentid() && $state->OrderNumber === $biz->getId()) {
            $res = array(
                'result' => true,
                'status' => $state->Status
            );
        }
        return $res;
    }

    public function callback() {
        header('HTTP/1.1 200 OK');
        $paymentid = $this->params->getStringRequestParam('PaymentId');
        /** @var \Entities\Bizonylatfej $megrendeles */
        $megrendeles = $this->getRepo('\Entities\Bizonylatfej')->findOneBy(array('paymentid' => $paymentid));
        if ($megrendeles && $paymentid === $megrendeles->getBarionpaymentid()) {
            $state = $this->getPaymentState($megrendeles);
            if ($state['result']) {
                $megrendeles->setBarionpaymentstatus($state['status']);
                if ($state['status'] === \PaymentStatus::Succeeded) {
                    $megrendeles->setBizonylatstatusz($this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionFizetveStatusz)));
                }
            }
        }
    }
}