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
            <div class="bizlista-sor mattable-important"><dt>{at('Kulcs')}:</dt><dd>{$_egyed.ertek}%</dd></div>
            <div class="bizlista-sor"><dt>{at('Típus')}:</dt><dd>{if ($_egyed.magyar)}{at('magyar ÁFA kulcs')}{else}{at('nem magyar ÁFA kulcs')}{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('NAV case')}:</dt><dd>{$_egyed.navcase}</dd></div>
            <div class="bizlista-sor"><dt>{at('RLB kód')}:</dt><dd>{$_egyed.rlbkod}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
