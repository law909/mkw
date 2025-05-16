<?php

namespace mkw;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class mkwgmailmailer
{

    private $to = [];
    private $subject;
    private $message;
    private $headers;
    private $replyto;
    private $attachment;
    public $ErrorInfo;

    public function clear()
    {
        $this->to = [];
        unset($this->subject, $this->message, $this->headers, $this->replyto, $this->attachment);
    }

    public function setTo($to)
    {
        if ($to) {
            if (is_string($to)) {
                $to = explode(',', $to);
            }
            if (is_array($to)) {
                $this->to = array_merge($this->to, $to);
            }
        }
    }

    public function addTo($to)
    {
        $this->setTo($to);
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setSubject($param)
    {
        $this->subject = $param;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setReplyTo($param)
    {
        $this->replyto = $param;
    }

    public function getReplyTo()
    {
        return $this->replyto;
    }

    public function setAttachment($fname)
    {
        $this->attachment = $fname;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }

    protected function getBccArray()
    {
        $bcc = \mkw\store::getParameter(\mkw\consts::EmailBcc);
        return explode(',', $bcc);
    }

    protected function getStatuszValtasArray()
    {
        $bcc = \mkw\store::getParameter(\mkw\consts::EmailStatuszValtas);
        if (!$bcc) {
            $bcc = \mkw\store::getParameter(\mkw\consts::EmailBcc);
        }
        return explode(',', $bcc);
    }

    public function send($statusvaltas = false)
    {
        $client = \mkw\googleoauth::getClient();
        $client->setAccessToken(\mkw\googleoauth::getExistingToken());

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents(\mkw\store::tokensPath('token.json'), json_encode($client->getAccessToken()));
        }

        $service = new \Google_Service_Gmail($client);

        $boundaryMixed = 'B_B_' . md5(rand());
        $boundaryAlternative = 'B_A_' . md5(rand());

        $messageid = md5(rand()) . '@billy';

        $from = \mkw\store::getParameter(\mkw\consts::EmailFrom);
        $fromdata = explode(';', $from);
        $fromName = mb_encode_mimeheader($fromdata[1], 'UTF-8', 'Q');
        $fromAddress = $fromdata[0];

        if (!$this->getTo()) {
            if ($statusvaltas) {
                $to = implode(',', $this->getStatuszValtasArray());
            } else {
                $to = implode(',', $this->getBccArray());
            }
        } else {
            $to = implode(',', $this->getTo());
        }

        if ($statusvaltas) {
            $bcc = implode(',', $this->getStatuszValtasArray());
        } else {
            $bcc = implode(',', $this->getBccArray());
        }

        $subject = mb_encode_mimeheader($this->getSubject(), 'UTF-8', 'Q');
        $message = $this->getMessage();

        if ($this->getAttachment()) {
            $filePath = $this->getAttachment();
            $fileName = basename($filePath);
            $encodedData = chunk_split(base64_encode(file_get_contents($filePath)));
            $fileMimeType = mime_content_type($filePath);
        }

        $rawMessage = "From: $fromName <$fromAddress>\r\n";
        $rawMessage .= "To: $to\r\n";
        if ($bcc) {
            $rawMessage .= 'Bcc: ' . $bcc . "\r\n";
        }
        $rawMessage .= "Subject: $subject\r\n";
        $rawMessage .= "User-Agent: Billy v1\r\n";
        $rawMessage .= "Return-Path: $fromAddress\r\n";
        $rawMessage .= "X-Priority: 3\r\n";
        $rawMessage .= "X-Sender: $fromAddress\r\n";
        $rawMessage .= "X-Mailer: Billy v1\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";

        if ($this->attachment) {
            $rawMessage .= "Content-Type: multipart/mixed; boundary=\"$boundaryMixed\"\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "--$boundaryMixed\r\n";
            $rawMessage .= "Content-Type: multipart/alternative; boundary=\"$boundaryAlternative\"\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "--$boundaryAlternative\r\n";
            $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= quoted_printable_encode(strip_tags($message)) . "\r\n";
            $rawMessage .= "--$boundaryAlternative\r\n";
            $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
            $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= quoted_printable_encode($message) . "\r\n";
            $rawMessage .= "--$boundaryAlternative--\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "--$boundaryMixed\r\n";
            $rawMessage .= "Content-Type: $fileMimeType; name=\"$fileName\"\r\n";
            $rawMessage .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
            $rawMessage .= "Content-Transfer-Encoding: base64\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "$encodedData\r\n";
            $rawMessage .= "--$boundaryMixed--\r\n";
        } else {
            $rawMessage .= "Content-Type: multipart/alternative; boundary=\"$boundaryAlternative\"\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "--$boundaryAlternative\r\n";
            $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= quoted_printable_encode(strip_tags($message)) . "\r\n";
            $rawMessage .= "--$boundaryAlternative\r\n";
            $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
            $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= quoted_printable_encode($message) . "\r\n";
            $rawMessage .= "--$boundaryAlternative--\r\n";
        }

        $mime = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');
        $gmailmessage = new \Google_Service_Gmail_Message();
        $gmailmessage->setRaw($mime);

        try {
            if (\mkw\store::isMailDebug()) {
                \mkw\store::writelog($rawMessage, 'emaildebug.log');
            }
            $x = $service->users_messages->send("me", $gmailmessage);
            if (\mkw\store::isMailDebug()) {
                \mkw\store::writelog(json_encode($x), 'emaildebug.log');
            }
        } catch (\Exception $e) {
            \mkw\store::writelog($e->getMessage() . '. Subject: ' . $subject, 'emailerror.log');
        }
    }
}
