<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Megnéz')}">{$_egyed.createdstr}</a>
        </div>
        <div>{$_egyed.createdbynev}</div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Termék')}:</dt><dd>{if ($_egyed.termekid)}<a href="/admin/termek/viewkarb?id={$_egyed.termekid}&oper=edit" target="_blank">{$_egyed.termeknev}</a>{else}{$_egyed.termeknev}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Változat')}:</dt><dd>{$_egyed.valtozatnev} (#{$_egyed.termekvaltozatid})</dd></div>
            <div class="bizlista-sor"><dt>{at('Új termék')}:</dt><dd>{if ($_egyed.ujtermekid)}<a href="/admin/termek/viewkarb?id={$_egyed.ujtermekid}&oper=edit" target="_blank">{$_egyed.ujtermeknev}</a>{else}{$_egyed.ujtermeknev}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Másolva')}:</dt><dd>{if ($_egyed.kepekmasolasa)}{at('képek')} {/if}{if ($_egyed.dokumentumokmasolasa)}{at('dokumentumok')} {/if}{if ($_egyed.arakmasolasa)}{at('árak')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Bizonylattétel')}:</dt><dd>{$_egyed.bizonylattetel}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
