<?php

namespace Controllers;

/** A megrendeléssel azonos viselkedésű B2B rendelés: csak a bizonylattípus és a felirat más. */
class b2brendelesfejController extends megrendelesfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('b2brendeles');
        $this->setPageTitle('B2B rendelés');
        $this->setPluralPageTitle('B2B rendelések');
    }
}
