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
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_egyed.sorrend}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover, a szallitasimod.js ezt váltja *}
        <div class="bizlista-kapcsolocsoport">
            <a href="#" data-id="{$_egyed.id}" data-flag="webes" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.webes)} ui-state-hover{/if}">{at('Webes')}</a>
            {if ($setup.multishop)}
                <a href="#" data-id="{$_egyed.id}" data-flag="webes2" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.webes2)} ui-state-hover{/if}">{at('Webes 2')}</a>
                <a href="#" data-id="{$_egyed.id}" data-flag="webes3" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.webes3)} ui-state-hover{/if}">{at('Webes 3')}</a>
                <a href="#" data-id="{$_egyed.id}" data-flag="webes4" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.webes4)} ui-state-hover{/if}">{at('Webes 4')}</a>
            {/if}
            <a href="#" data-id="{$_egyed.id}" data-flag="vanszallitasiktg" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.vanszallitasiktg)} ui-state-hover{/if}">{at('Van szállítási költség')}</a>
            <a href="#" data-id="{$_egyed.id}" data-flag="csomagpont" class="js-flagcheckbox bizlista-kapcsolo{if ($_egyed.csomagpont)} ui-state-hover{/if}">{at('Csomagpont')}</a>
        </div>
    </td>
</tr>
