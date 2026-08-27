<?php

namespace mkwhelpers\Exceptions;

/**
 * Olyan kivétel, aminek az üzenete a felhasználónak szól: az {@see \mkwhelpers\ErrorMessage}
 * változtatás nélkül továbbadja neki. Belső részletet (SQL, fájlnév) tilos beletenni.
 *
 * A $fields a hibás űrlapmezőket nevesíti (mezőnév => üzenet); a kliens ezeket jelöli meg
 * a karbantartó formon.
 */
class UserMessageException extends \Exception
{

    private $fields;

    public function __construct($message, array $fields = [])
    {
        parent::__construct($message);
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

}
