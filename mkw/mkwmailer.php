<?php
namespace mkw;

class mkwMailer {
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
        Store::writelog($this->to);
        Store::writelog($this->subject);
        Store::writelog($this->message);
        return mail($this->to, $this->subject, $this->message);
    }
}