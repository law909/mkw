<?php

namespace Controllers;

/**
 * A galad b2b webáruház pénztára. Mindenben a superzoneb2b-t követi, csak a keletkező
 * bizonylat típusa más: b2brendeles, nem megrendelés.
 */
class galadCheckoutController extends superzoneb2bCheckoutController
{

    protected function getBizonylattipusId()
    {
        return 'b2brendeles';
    }
}
