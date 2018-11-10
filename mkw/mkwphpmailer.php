<?php
namespace mkw;

use PHPMailer\PHPMailer\PHPMailer;

class mkwphpmailer {

    private $mailer;
    private $to = array();
    private $subject;
    private $message;
    private $headers;
    private $replyto;
    private $attachment;

    protected function clear() {
        $this->to = array();
        unset($this->subject, $this->message, $this->headers, $this->replyto);
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

        $this->mailer = new PHPMailer();

        $this->mailer->SMTPDebug = 2;

        $this->mailer->isSMTP();
        $this->mailer->Host = \mkw\store::getConfigValue('mail.host');
        $this->mailer->Port = \mkw\store::getConfigValue('mail.port');
        $this->mailer->SMTPSecure = \mkw\store::getConfigValue('mail.ssl');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = \mkw\store::getConfigValue('mail.username');
        $this->mailer->Password = \mkw\store::getConfigValue('mail.password');

        $this->mailer->msgHTML($this->message);
        $this->mailer->Subject = $this->subject;

        if ($this->attachment) {
            $this->mailer->addAttachment($this->attachment);
        }

        $from = \mkw\store::getParameter(\mkw\consts::EmailFrom);
        $fromdata = explode(';', $from);
        $this->mailer->setFrom($fromdata[0], $fromdata[1]);

        if (!$this->replyto) {
            $this->mailer->addReplyTo(\mkw\store::getParameter(\mkw\consts::EmailReplyTo));
        }
        else {
            $this->mailer->addReplyTo($this->replyto);
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
                    $this->mailer->addAddress($cim);
                }
            }
        }
        else {
            foreach($this->to as $t) {
                if ($t) {
                    $this->mailer->addAddress($t);
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
                $this->mailer->addBCC($_bcc);
            }
        }

        try {
            if ($this->mailer->getAllRecipientAddresses()) {
                $this->mailer->send();
            }
        }
        finally {
            $this->clear();
        }
    }
}
