<?php

namespace Controllers;

use Entities\TermekMenu2;

/**
 * A második termékmenü-fa karbantartója. Mindenben az elsőt követi (ugyanaz a fa-szerkesztő,
 * ugyanazok a képernyők), csak más entitáson, táblán és sablonokon dolgozik – és a terméket a
 * `termekmenu2` mezője köti hozzá.
 *
 * Hogy a webshop melyik fából építse a menüjét, a beállításokban webshoponként megadható
 * (\mkw\consts::Termekmenutipus).
 */
class termekmenu2Controller extends termekmenuController
{

    protected $entityClass = TermekMenu2::class;
    protected $tablaNev = 'termekmenu2';
    protected $listaTplName = 'termekmenu2lista.tpl';
    protected $termekMezo = 'termekmenu2';

    protected function getMenuName(): string
    {
        return \mkw\store::getTermekmenu2Name();
    }

}
