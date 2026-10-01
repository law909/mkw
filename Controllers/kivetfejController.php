<?php

namespace Controllers;

class kivetfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('kivet');
        $this->setPageTitle('Kivét');
        $this->setPluralPageTitle('Kivétek');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        $source = $this->params->getStringRequestParam('source', '');
        if ($oper == 'inherit') {
            $egyed = $this->inheritEgyed($egyed, $record, $id);
            if ($this->isOrderSource($source)) {
                $egyed['megjegyzes'] = \mkw\store::translate('Rendelés szám', $record->getBizonylatnyelv()) . ': ' . $id;
            } elseif ($source === 'szallito') {
                $egyed['megjegyzes'] = \mkw\store::translate('Szállítólevél szám', $record->getBizonylatnyelv()) . ': ' . $id;
            }
        }
        return $egyed;
    }

}

