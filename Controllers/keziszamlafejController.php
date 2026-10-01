<?php

namespace Controllers;

use mkw\store;

class keziszamlafejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('keziszamla');
        $this->setPageTitle('Kézi számla');
        $this->setPluralPageTitle('Kézi számlák');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        $source = $this->params->getStringRequestParam('source', '');
        if ($oper == 'inherit') {
            $egyed = $this->inheritEgyed($egyed, $record, $id);
            if ($this->isOrderSource($source)) {
                $egyed['megjegyzes'] = \mkw\store::translate('Rendelés', $record->getBizonylatnyelv()) . ': ' . $id;
            } elseif ($source === 'szallito') {
                $egyed['megjegyzes'] = \mkw\store::translate('Szállítólevél', $record->getBizonylatnyelv()) . ': ' . $id;
            }
        }
        return $egyed;
    }

}
