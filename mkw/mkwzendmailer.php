<?php
namespace mkw;

class mkwzendmailer {

    /*
		$ertesito=new Zend_Mail();
		$ertesito->setBodyHtml('Regisztráció érkezett.'.$pluszszoveg);
		$ertesito->setSubject('Regisztráció');
		$ertesito->setFrom(siiker_store::getDbParams()->getParam(siiker_const::$pWEBinfoemail,'info'));
		$cimek=explode(',',siiker_store::getDbParams()->getParam(siiker_const::$pWEBRegisztracioErtesitesEmail,siiker_store::getDbParams()->getParam(siiker_const::$pWEBinfoemail,'')));
		foreach($cimek as $cim) {
			$ertesito->addTo($cim);
		}
		if (!siiker_store::getConfig()->developer) {
			$ertesito->send();
		}
    */


    private $mailer;
    private $to;
    private $subject;
    private $message;
    private $headers;

    public function setTo($to) {
        $this->to = $to;
    }

    public function getTo() {
        return $this->to;
    }

    public function setSubject($param) {
        $this->subject = $param;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getMessage() {
        return $this->message;
    }

    public function send() {
        $this->mailer = new \Zend_Mail('UTF-8');
        $this->mailer->setBodyHtml($this->message);
        $this->mailer->setSubject($this->subject);
        $from = Store::getParameter(consts::EmailFrom);
        $fromdata = explode(';', $from);
        $this->mailer->setFrom($fromdata[0], $fromdata[1]);
        if (!$this->to) {
            $this->mailer->addTo(Store::getParameter(consts::EmailBcc));
        }
        else {
            $this->mailer->addTo($this->to);
        }
        $this->mailer->addBcc(Store::getParameter(consts::EmailBcc));
        $this->mailer->setReplyTo(Store::getParameter(consts::EmailReplyTo));
        $this->mailer->send();
    }
}
