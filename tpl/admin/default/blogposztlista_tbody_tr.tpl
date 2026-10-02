<tr id="mattable-row_{$_blogposzt.id}" data-egyedid="{$_blogposzt.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo termeklista-fo">
        <div class="termeklista-fej">
            {if ($_blogposzt.kepurl)}
                <a class="termeklista-kep js-toflyout" href="{$mainurl}{$_blogposzt.kepurl}" target="_blank"><img src="{$mainurl}{$_blogposzt.kepurlsmall}" alt=""></a>
            {/if}
            <div class="termeklista-cim">
                <a class="mattable-editlink bizlista-nev" href="#" data-blogposztid="{$_blogposzt.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_blogposzt.cim}</a>
                <div class="bizlista-muveletek">
                    <a class="mattable-dellink" href="#" data-blogposztid="{$_blogposzt.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                </div>
            </div>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Megjelenés')}:</dt><dd>{$_blogposzt.megjelenesdatumstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Kategória')}:</dt><dd>{$_blogposzt.termekfa1nev}{if ($_blogposzt.termekfa1nev && $_blogposzt.termekfa2nev)} · {/if}{$_blogposzt.termekfa2nev}{if (($_blogposzt.termekfa1nev || $_blogposzt.termekfa2nev) && $_blogposzt.termekfa3nev)} · {/if}{$_blogposzt.termekfa3nev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Link')}:</dt><dd class="bizlista-link"><a href="{$mainurl}/blogposzt/{$_blogposzt.slug}" target="_blank">/blogposzt/{$_blogposzt.slug}</a></dd></div>
            <div class="bizlista-sor"><dt>{at('Azonosító')}:</dt><dd>{$_blogposzt.id}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover, a blogposzt.js ezt váltja *}
        <div class="bizlista-kapcsolocsoport">
            <a href="#" data-id="{$_blogposzt.id}" data-flag="lathato" class="js-flagcheckbox bizlista-kapcsolo{if ($_blogposzt.lathato)} ui-state-hover{/if}">{at('Látható')}</a>
        </div>
    </td>
</tr>
