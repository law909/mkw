<?php

namespace mkw;

use mikehaertl\wkhtmlto\Pdf;

class mkwwkhtmltopdf
{
    private $engine;

    public function __construct($html)
    {
        $this->engine = new Pdf($html);
        $this->engine->setOptions(['encoding' => 'UTF-8']);
        return $this;
    }

    public function saveAs($filename)
    {
        $this->engine->saveAs($filename);
    }

    public function send($filename)
    {
        $this->engine->send($filename);
    }
}