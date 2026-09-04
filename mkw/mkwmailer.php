<?php
namespace mkw;

/**
 * A PHP mail() fölötti küldő – ezt kapja a store::getMailer(), ha a config.ini mail.mailer
 * kulcsa nem phpmailer és nem gmail. A felülete a másik kettőével azonos (mkwphpmailer,
 * mkwgmailmailer): a hívók duck typing alapján bármelyiket megkaphatják.
 */
class mkwmailer {

    private $to = [];
    private $subject;
    private $message;
    private $headers;
    private $replyto;
    private $attachment;

    public function clear() {
        $this->to = [];
        unset($this->subject, $this->message, $this->headers, $this->replyto, $this->attachment);
    }

    public function addTo($to) {
        $this->to = array_merge($this->to, $this->toArray($to));
    }

    public function setTo($to) {
        $this->to = $this->toArray($to);
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

    private function toArray($to) {
        if (is_string($to)) {
            $to = explode(',', $to);
        }
        if (!is_array($to)) {
            return [];
        }
        return array_values(array_filter(array_map(static fn($e) => trim((string)$e), $to)));
    }

    private function getBcc($statusvaltas) {
        $bcc = $statusvaltas ? \mkw\store::getParameter(\mkw\consts::EmailStatuszValtas) : '';
        return $bcc ?: \mkw\store::getParameter(\mkw\consts::EmailBcc);
    }

    /**
     * A csatolmány miatt a levél többrészes lesz; a fejlécet és a törzset együtt kell
     * felépíteni, ezért ad vissza mindkettőt.
     *
     * @return array{0: string, 1: string} [fejlécrészlet, törzs]
     */
    private function buildBody() {
        $eol = "\r\n";
        if (!$this->attachment || !is_readable($this->attachment)) {
            if ($this->attachment) {
                error_log('mkwmailer: a csatolmány nem olvasható, a levél csatolmány nélkül megy: ' . $this->attachment);
            }
            return ["Content-Type: text/html; charset=utf-8" . $eol, (string)$this->message];
        }
        $boundary = '=_' . md5(uniqid('', true));
        $nev = str_replace(['"', "\r", "\n"], '', basename($this->attachment));
        $body = '--' . $boundary . $eol
            . 'Content-Type: text/html; charset=utf-8' . $eol
            . 'Content-Transfer-Encoding: 8bit' . $eol . $eol
            . $this->message . $eol . $eol
            . '--' . $boundary . $eol
            . 'Content-Type: application/octet-stream; name="' . $nev . '"' . $eol
            . 'Content-Disposition: attachment; filename="' . $nev . '"' . $eol
            . 'Content-Transfer-Encoding: base64' . $eol . $eol
            . chunk_split(base64_encode(file_get_contents($this->attachment)), 76, $eol) . $eol
            . '--' . $boundary . '--' . $eol;
        return ['Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $eol, $body];
    }

    public function send($statusvaltas = false) {
        [$contenttype, $body] = $this->buildBody();

        $this->headers = "From: " . \mkw\store::getParameter(\mkw\consts::EmailFrom) . "\r\n";
        if (!$this->replyto) {
            $this->headers .= "Reply-to: " . \mkw\store::getParameter(\mkw\consts::EmailReplyTo) . "\r\n";
        }
        else {
            $this->headers .= $this->replyto . "\r\n";
        }
        $this->headers .= "Bcc: " . $this->getBcc($statusvaltas) . "\r\n"
            . "MIME-version: 1.0\r\n"
            . $contenttype;

        $ret = mail(implode(', ', $this->to), $this->subject, $body, $this->headers);
        $this->clear();
        return $ret;
    }
}
