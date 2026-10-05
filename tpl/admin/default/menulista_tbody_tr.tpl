<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                        class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Menücsoport')}:</dt><dd>{$_egyed.menucsoportnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_egyed.sorrend}</dd></div>
            <div class="bizlista-sor"><dt>{at('URL')}:</dt><dd>{$_egyed.url}</dd></div>
            <div class="bizlista-sor"><dt>{at('Munkakörök')}:</dt><dd>{$_egyed.munkakornevek}</dd></div>
            <div class="bizlista-sor"><dt>{at('Látható')}:</dt><dd>{if ($_egyed.lathato)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
