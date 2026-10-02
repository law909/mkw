<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
            {if ($_egyed.inaktiv)}
                <span class="bizlista-jelveny">{at('Inaktív')}</span>
            {/if}
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
            <div class="bizlista-sor"><dt>{at('Típus')}:</dt><dd>{if ($_egyed.tipus == 'P')}{at('Pénztár')}{elseif ($_egyed.tipus == 'B')}{at('Bank')}{else}{at('Ismeretlen típus')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Webes')}:</dt><dd>{if ($_egyed.webes)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Rugalmas')}:</dt><dd>{if ($_egyed.rugalmas)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Pénzmozgás')}:</dt><dd>{if ($_egyed.nincspenzmozgas)}{at('nincs')}{else}{at('van')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Aktív')}:</dt><dd>{if (!$_egyed.inaktiv)}{at('igen')}{else}{at('nem')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_egyed.sorrend}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
