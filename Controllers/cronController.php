<?php

namespace Controllers;

use Carbon\Carbon;
use Entities\JogaBejelentkezes;
use Entities\Orarend;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class cronController extends \mkwhelpers\Controller {

    public function run() {
        if (\mkw\store::isDarshan()) {
            $this->checkJogaBejelentkezes();
        }
    }

    public function checkJogaBejelentkezes() {
        /*
        $realma = Carbon::now();
        $tesztma = Carbon::create($realma->year, $realma->month, $realma->day, 12, 31);
        Carbon::setTestNow($tesztma);
        */

        $ma = Carbon::now();
        $nap = $ma->format('N');
        $filter = new FilterDescriptor();
        $filter->addFilter('nap', '=', $nap);
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('bejelentkezeskell', '=', true);

        /** @var Orarend $ora */
        $maiorak = $this->getRepo(Orarend::class)->getAll($filter);
        foreach ($maiorak as $ora) {
            $oradatetime = Carbon::createFromTimeString($ora->getKezdetStr());
            $kul = $ma->floatDiffInMinutes($oradatetime, false);

            \mkw\store::writelog('---------------------------------');
            \mkw\store::writelog($ora->getId());
            \mkw\store::writelog($ora->getNev());
            \mkw\store::writelog('most:      ' . $ma->format(\mkw\store::$DateTimeFormat));
            \mkw\store::writelog('ora ekkor: ' . $oradatetime->format(\mkw\store::$DateTimeFormat));
            \mkw\store::writelog('elteres:   ' . $kul);

            if ($kul > 115 && $kul < 125) {
                $bejfilter = new FilterDescriptor();
                $bejfilter->addFilter('orarend', '=', $ora);
                $bejcnt = $this->getRepo(JogaBejelentkezes::class)->getCount($bejfilter);
                if ($ora->getMinbejelentkezes() > 0 && $bejcnt < $ora->getMinbejelentkezes()) {
                    \mkw\store::writelog('jajajj, nincsenek elegen, lemondjuk a tanarnal es a jelentkezoknel');
                }
                else {
                    \mkw\store::writelog('okenak tunik');
                }
                \mkw\store::writelog($bejcnt . ' < ' . $ora->getMinbejelentkezes());
            }
            else {
                \mkw\store::writelog('meg messze van az ora, varunk tovabb: ' . $kul);
            }
        }
    }
}