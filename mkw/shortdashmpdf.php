<?php

namespace mkw;

use Mpdf\Mpdf;

/**
 * mPDF rövidebb vonalkákkal rajzolt "dashed" szegéllyel.
 *
 * Az mPDF a szaggatott szegély osztását bedrótozza (2 mm vonalka, ~2 mm köz, se CSS-ből, se
 * beállításból nem módosítható), és ez a bizonylat tételei közt hosszú vonaldaraboknak látszik.
 * A rajzoláskor hívott SetDash-t fogjuk el: a szaggatott mintát rövidebbre cseréljük, a pontozott
 * (0,001 mm-es "vonalka") és a folytonos (paraméter nélküli) hívás változatlanul megy tovább.
 */
class shortdashmpdf extends Mpdf
{
    private const DASH = 0.8;
    private const GAP = 0.8;

    public function SetDash($black = false, $white = false)
    {
        if ($black && $black >= 1) {
            $black = self::DASH;
            $white = self::GAP;
        }
        parent::SetDash($black, $white);
    }
}
