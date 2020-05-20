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
        $x = $biz->toBarionModel();
        $payment = $bc->PreparePayment($biz->toBarionModel());

        \mkw\store::writelog(print_r($x, true));
        \mkw\store::writelog(print_r($payment, true));

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

        \mkw\store::writelog(print_r($state, true));

        if ($state && $state->RequestSuccessful && $state->PaymentId === $biz->getBarionpaymentid() && $state->OrderNumber === $biz->getId()) {
            $last = null;
            foreach ($state->Transactions as $trans) {
                switch ($trans->TransactionType) {
                    case 'GatewayFee':
                        break;
                    case 'CardPayment':
                    case 'Unspecified':
                    case 'RefundToBankCard':
                        $last = $trans;
                        break;
                }
            }
            if ($last) {
                $res = array(
                    'result' => true,
                    'lastTransaction' => $last
                );
            }
        }
        return $res;
    }

    public function callback() {
        header('HTTP/1.1 200 OK');
        $paymentid = $this->params->getStringRequestParam('PaymentId');

        \mkw\store::writelog(print_r('PAYMENTID ' . $paymentid, true));

        /** @var \Entities\Bizonylatfej $megrendeles */
        $megrendeles = $this->getRepo('\Entities\Bizonylatfej')->findOneBy(array('barionpaymentid' => $paymentid));
        if ($megrendeles && $paymentid === $megrendeles->getBarionpaymentid()) {
            $state = $this->getPaymentState($megrendeles);
            if ($state['result']) {

                /** @var \TransactionDetailModel $lasttrans */
                $lasttrans = $state['lastTransaction'];

                switch ($lasttrans->TransactionType) {
                    case 'RefundToBankCard':
                        $bizstatusz = null;
                        if ($lasttrans->Status === \PaymentStatus::Succeeded && $lasttrans->Total == $megrendeles->getBrutto()) {
                            $megrendeles->setSimpleedit(true);
                            $megrendeles->setBarionpaymentstatus('Refunded');
                            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionRefundedStatusz));
                            if ($bizstatusz) {
                                $megrendeles->setBizonylatstatusz($bizstatusz);
                            }
                            $this->getEm()->persist($megrendeles);
                            $this->getEm()->flush();
                            if ($bizstatusz) {
                                $megrendeles->sendStatuszEmail($bizstatusz->getEmailtemplate());
                            }
                        }
                        break;
                    default:
                        $bizstatusz = null;
                        $megrendeles->setSimpleedit(true);
                        $megrendeles->setBarionpaymentstatus($lasttrans->Status);
                        if ($lasttrans->Status === \PaymentStatus::Succeeded) {
                            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionFizetveStatusz));
                            if ($bizstatusz) {
                                $megrendeles->setBizonylatstatusz($bizstatusz);
                            }
                        }
                        $this->getEm()->persist($megrendeles);
                        $this->getEm()->flush();
                        if ($bizstatusz) {
                            $megrendeles->sendStatuszEmail($bizstatusz->getEmailtemplate());
                        }
                        break;
                }
            }
        }
    }
}