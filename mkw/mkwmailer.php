<?php
namespace mkw;

class mkwmailer {
    private $to;
    private $subject;
    private $message;

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
        return mail($this->to, $this->subject, $this->message);
    }
}