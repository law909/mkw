<?php
namespace mkw;

class mkwmailer {
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
        $this->headers = "From: " . Store::getParameter(consts::EmailFrom) . "\r\n"
            . "Reply-to: " . Store::getParameter(consts::EmailReplyTo) . "\r\n"
            . "MIME-version: 1.0\r\n"
            . "Content-Type: text/html; charset=utf-8\r\n";
        return mail($this->to, $this->subject, $this->message, $this->headers);
    }
}