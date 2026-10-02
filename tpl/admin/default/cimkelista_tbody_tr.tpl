<tr id="mattable-row_{$_cimke.id}" data-cimkeid="{$_cimke.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo termeklista-fo">
        <div class="termeklista-fej">
            {if ($cimketipus === 'termek' && $_cimke.kepurl)}
                <a class="termeklista-kep toFlyout" href="{$mainurl}{$_cimke.kepurl}" target="_blank"><img src="{$mainurl}{$_cimke.kepurlsmall}" alt=""></a>
            {/if}
            <div class="termeklista-cim">
                <a class="mattable-editlink bizlista-nev" href="#" data-cimkeid="{$_cimke.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_cimke.nev}</a>
                <div class="bizlista-muveletek">
                    <a class="mattable-dellink" href="#" data-cimkeid="{$_cimke.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                </div>
            </div>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Címkecsoport')}:</dt><dd>{$_cimke.cimkekatnev}</dd></div>
            {if ($cimketipus === 'termek')}
                <div class="bizlista-sor"><dt>{at('Gyártó')}:</dt><dd>{$_cimke.gyartonev}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_cimke.sorrend}</dd></div>
            <div class="bizlista-sor"><dt>{at('Azonosító')}:</dt><dd>{$_cimke.id}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover; a JS a sor data-cimkeid-jéből veszi a címkét *}
        <div class="bizlista-kapcsolocsoport">
            <a href="#" class="js-menulathatocheckbox bizlista-kapcsolo{if ($_cimke.menu1lathato)} ui-state-hover{/if}" data-num="1">{at('Menü 1')}</a>
            <a href="#" class="js-menulathatocheckbox bizlista-kapcsolo{if ($_cimke.menu2lathato)} ui-state-hover{/if}" data-num="2">{at('Menü 2')}</a>
            <a href="#" class="js-menulathatocheckbox bizlista-kapcsolo{if ($_cimke.menu3lathato)} ui-state-hover{/if}" data-num="3">{at('Menü 3')}</a>
            <a href="#" class="js-menulathatocheckbox bizlista-kapcsolo{if ($_cimke.menu4lathato)} ui-state-hover{/if}" data-num="4">{at('Menü 4')}</a>
            {* a partnercímkének nincs kiemelt mezője, a szerver nem is menti *}
            {if ($cimketipus === 'termek')}
                <a href="#" class="js-menulathatocheckbox bizlista-kapcsolo{if ($_cimke.kiemelt)} ui-state-hover{/if}" data-num="5">{at('Kiemelt')}</a>
            {/if}
        </div>
    </td>
</tr>
