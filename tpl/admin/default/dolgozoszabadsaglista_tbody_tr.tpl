<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.dolgozonev}</a>
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor mattable-important"><dt>{at('Időszak')}:</dt><dd>{$_egyed.datumtolstr} - {$_egyed.datumigstr}</dd></div>
            {if ($_egyed.napok !== '')}
                <div class="bizlista-sor"><dt>{at('Napok')}:</dt><dd>{$_egyed.napok} {at('nap')}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Típus')}:</dt><dd>{$_egyed.tipusnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Megjegyzés')}:</dt><dd>{$_egyed.megjegyzes}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
