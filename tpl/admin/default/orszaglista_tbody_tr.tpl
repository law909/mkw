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
            <div class="bizlista-sor"><dt>{at('ISO 3166')}:</dt><dd>{$_egyed.iso3166}</dd></div>
            <div class="bizlista-sor"><dt>{at('Valutanem')}:</dt><dd>{$_egyed.valutanemnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('ÁFA kulcs')}:</dt><dd>{$_egyed.afanev}</dd></div>
            <div class="bizlista-sor"><dt>{at('EU')}:</dt><dd>{if ($_egyed.eu)}{at('EU-n belüli')}{else}{at('EU-n kívüli')}{/if}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
