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
            <div class="bizlista-sor"><dt>{at('Típus')}:</dt><dd>{$_egyed.tipusnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Terminál típus')}:</dt><dd>{$_egyed.terminaltipus}</dd></div>
            {if ($setup.multishop)}
                <div class="bizlista-sor"><dt>{at('Webes')}:</dt><dd>{if ($_egyed.webes)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
                <div class="bizlista-sor"><dt>{at('Webes 2')}:</dt><dd>{if ($_egyed.webes2)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
                <div class="bizlista-sor"><dt>{at('Webes 3')}:</dt><dd>{if ($_egyed.webes3)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
                <div class="bizlista-sor"><dt>{at('Webes 4')}:</dt><dd>{if ($_egyed.webes4)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            {else}
                <div class="bizlista-sor"><dt>{at('Webes')}:</dt><dd>{if ($_egyed.webes)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Szállítási költség')}:</dt><dd>{if ($_egyed.vanszallitasiktg)}{at('van')}{else}{at('nincs')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Csomagpont')}:</dt><dd>{if ($_egyed.csomagpont)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_egyed.sorrend}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
