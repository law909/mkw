<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo termeklista-fo">
        <div class="termeklista-fej">
            {if ($_egyed.kepurl)}
                <a class="termeklista-kep js-toflyout" href="{$_egyed.kepurl}" target="_blank"><img src="{$_egyed.kepurlsmall}" alt="{$_egyed.kepleiras}" title="{$_egyed.kepleiras}"></a>
            {/if}
            <div class="termeklista-cim">
                <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.cim}</a>
                <div class="bizlista-muveletek">
                    <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                                class="ui-icon ui-icon-circle-minus"></span></a>
                </div>
            </div>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Dátum')}:</dt><dd>{$_egyed.datumstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Forrás')}:</dt><dd>{$_egyed.forras}</dd></div>
            <div class="bizlista-sor"><dt>{at('Link')}:</dt><dd class="bizlista-link">{if ($_egyed.link)}<a href="{$_egyed.link}" target="_blank">{$_egyed.link}</a>{/if}</dd></div>
            <div class="bizlista-sor"><dt>{at('Lead')}:</dt><dd>{$_egyed.lead}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover, a hir.js ezt váltja *}
        <div class="bizlista-kapcsolocsoport">
            <a href="#" data-id="{$_egyed.id}" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.lathato)} ui-state-hover{/if}">{at('Látható')}</a>
        </div>
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Első megjelenés')}:</dt><dd>{$_egyed.elsodatumstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Utolsó megjelenés')}:</dt><dd>{$_egyed.utolsodatumstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_egyed.sorrend}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
