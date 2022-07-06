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
    private $to = array();
    private $subject;
    private $message;
    private $headers;
    private $replyto;
    private $attachment;

    public function clear() {
        $this->to = array();
        unset($this->subject, $this->message, $this->headers, $this->replyto, $this->attachment);
    }

    public function setTo($to) {
        if ($to) {
            if (is_string($to)) {
                $to = explode(',', $to);
            }
            if (is_array($to)) {
                $this->to = array_merge($this->to, $to);
            }
        }
    }

    public function addTo($to) {
        $this->setTo($to);
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

    public function setReplyTo($param) {
        $this->replyto = $param;
    }

    public function getReplyTo() {
        return $this->replyto;
    }

    public function setAttachment($fname) {
        $this->attachment = $fname;
    }

    public function getAttachment() {
        return $this->attachment;
    }

    protected function getBccArray() {
        $bcc = \mkw\store::getParameter(\mkw\consts::EmailBcc);
        return explode(',', $bcc);
    }

    protected function getStatuszValtasArray() {
        $bcc = \mkw\store::getParameter(\mkw\consts::EmailStatuszValtas);
        if (!$bcc) {
            $bcc = \mkw\store::getParameter(\mkw\consts::EmailBcc);
        }
        return explode(',', $bcc);
    }

    public function send($statusvaltas = false) {

        $this->mailer = new \Zend_Mail('UTF-8');
        $this->mailer->setBodyHtml($this->message);
        $this->mailer->setSubject($this->subject);

        if ($this->attachment) {
            $content = file_get_contents($this->attachment);
            $attachment = new \Zend_Mime_Part($content);
            $attachment->type = 'application/pdf';
            $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
            $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
            $attachment->filename = $this->attachment;
            $this->mailer->addAttachment($attachment);
        }

        $from = \mkw\store::getParameter(\mkw\consts::EmailFrom);
        $fromdata = explode(';', $from);
        $this->mailer->setFrom($fromdata[0], $fromdata[1]);

        if (!$this->replyto) {
            $this->mailer->setReplyTo(\mkw\store::getParameter(\mkw\consts::EmailReplyTo));
        }
        else {
            $this->mailer->setReplyTo($this->replyto);
        }

        if (!$this->to) {
            if ($statusvaltas) {
                $bcc = $this->getStatuszValtasArray();
            }
            else {
                $bcc = $this->getBccArray();
            }
            foreach($bcc as $cim) {
                if ($cim) {
                    $this->mailer->addTo($cim);
                }
            }
        }
        else {
            foreach($this->to as $t) {
                if ($t) {
                    $this->mailer->addTo($t);
                }
            }
        }
        if ($statusvaltas) {
            $bcc = $this->getStatuszValtasArray();
        }
        else {
            $bcc = $this->getBccArray();
        }
        foreach($bcc as $_bcc) {
            if ($_bcc) {
                $this->mailer->addBcc($_bcc);
            }
        }

        try {
            if ($this->mailer->getRecipients()) {
                $this->mailer->send();
            }
        }
        finally {
            $this->clear();
        }
    }
}
