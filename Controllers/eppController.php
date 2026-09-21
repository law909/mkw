<?php

namespace Controllers;

use Entities\Eppnaplo;
use Services\EppService;

class eppController extends \mkwhelpers\Controller
{

    public function validate()
    {
        $this->respond(Eppnaplo::VEGPONTVALIDATE);
    }

    public function status()
    {
        $this->respond(Eppnaplo::VEGPONTSTATUS);
    }

    private function respond(string $vegpont)
    {
        [$status, $payload] = (new EppService())->handle($vegpont, $_SERVER, (string)file_get_contents('php://input'));
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($payload);
    }

}
