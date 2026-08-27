<?php

namespace Traits;

use Entities\Termek;

/**
 * A méret- és a színtörzs listájáról nyíló modal tartalma: azoknak a termékeknek a karbantartó
 * linkjei, amelyeknek van a sorhoz tartozó változata.
 */
trait ValtozatTermekLista
{

    /** a TermekValtozat mezője, amire a lista szűr: 'meret' vagy 'szin' */
    abstract protected function getValtozatFieldName(): string;

    public function getTermekList()
    {
        $termekek = $this->getRepo(Termek::class)->getTermekListByValtozat(
            $this->getValtozatFieldName(),
            $this->params->getIntRequestParam('id')
        );
        $lista = [];
        foreach ($termekek as $termek) {
            $lista[] = [
                'nev' => $termek->getNev(),
                'cikkszam' => $termek->getCikkszam(),
                'karburl' => \mkw\store::getRouter()->generate(
                    'admintermekviewkarb',
                    false,
                    [],
                    ['oper' => 'edit', 'id' => $termek->getId()]
                ),
            ];
        }
        $view = $this->createView('valtozattermeklista.tpl');
        $view->setVar('lista', $lista);
        echo json_encode(['html' => $view->getTemplateResult()]);
    }

}
