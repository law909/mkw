<?php

namespace Controllers;

class garancialevelfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('garancialevel');
        $this->setPageTitle('Garancialevél');
        $this->setPluralPageTitle('Garancialevelek');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
                $egyed = $this->inheritEgyed($egyed, $record, $id, false);
                if ($this->isOrderSource($source)) {
                    $egyed['megjegyzes'] = \mkw\store::translate('Rendelés', $record->getBizonylatnyelv()) . ': ' . $id;
                }
                break;
        }
        return $egyed;
    }

}
