<tr id="mattable-row_{$_cimke.id}" data-cimkeid="{$_cimke.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr>{if ($kellkep)}<td><a class="toFlyout" href="{$mainurl}{$_cimke.kepurl}" target="_blank"><img src="{$mainurl}{$_cimke.kepurlsmall}"/></a></td>{/if}
                    <td><a class="mattable-editlink" href="#" data-cimkeid="{$_cimke.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_cimke.nev}</a></td>
                    <td><span class="jobbra"><a class="mattable-dellink" href="#" data-cimkeid="{$_cimke.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span></td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">{$_cimke.cimkekatnev}</td>
    <td class="cell">
        <a href="#" class="js-menulathatocheckbox{if ($_cimke.menu1lathato)} ui-state-hover{/if}" data-num=1>{t('Menü 1')}</a>
        <a href="#" class="js-menulathatocheckbox{if ($_cimke.menu2lathato)} ui-state-hover{/if}" data-num=2>{t('Menü 2')}</a>
        <a href="#" class="js-menulathatocheckbox{if ($_cimke.menu3lathato)} ui-state-hover{/if}" data-num=3>{t('Menü 3')}</a>
        <a href="#" class="js-menulathatocheckbox{if ($_cimke.menu4lathato)} ui-state-hover{/if}" data-num=4>{t('Menü 4')}</a>
        <a href="#" class="js-menulathatocheckbox{if ($_cimke.kiemelt)} ui-state-hover{/if}" data-num=5>{t('Kiemelt')}</a>
    </td>
</tr>