<?php

namespace Controllers;

class csomagfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('csomag');
        $this->setPageTitle('Csomag');
        $this->setPluralPageTitle('Csomagok');
    }

    /**
     * @param $view
     * @param \Entities\Bizonylatfej $record
     * @param $egyed
     * @param $oper
     * @param $id
     *
     * @return mixed
     */
    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        $source = $this->params->getStringRequestParam('source', '');
        if ($oper == 'inherit') {
            $egyed = $this->inheritEgyed($egyed, $record, $id);
            if ($this->isOrderSource($source)) {
                $egyed['megjegyzes'] = \mkw\store::translate('Rendelés szám', $record->getBizonylatnyelv()) . ': ' . $id;
            }
        }
        return $egyed;
    }

}
