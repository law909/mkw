<?php

namespace mkw;

class mkwdompdf
{
    private $engine;

    public function __construct($html)
    {
        $this->engine = new \Dompdf\Dompdf();
        $this->engine->setPaper('A4', 'portrait');
        $this->engine->loadHtml($html);
        return $this;
    }

    public function saveAs($filename)
    {
        $this->engine->render();
        file_put_contents($filename, $this->engine->output());
    }

    public function send($filename)
    {
        $this->engine->render();
        $this->engine->stream($filename, ['Attachment' => true]);
    }
}