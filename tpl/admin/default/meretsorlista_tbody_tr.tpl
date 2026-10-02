<tr id="mattable-row_{$_meretsor.id}" data-egyedid="{$_meretsor.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-meretsorid="{$_meretsor.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_meretsor.nev}</a>
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-meretsorid="{$_meretsor.id}" data-oper="del" title="{at('Töröl')}"><span
                        class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Méretek')}:</dt><dd>{$_meretsor.meretek}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
