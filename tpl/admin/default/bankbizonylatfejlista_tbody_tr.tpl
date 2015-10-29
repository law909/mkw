<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($_egyed.editprinted || (!$_egyed.editprinted && !$_egyed.nyomtatva))}
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.id}</a>
        {else}
            {$_egyed.id}
        {/if}
        {if ($_egyed.nemrossz)}
            <a class="js-rontbizonylat" href="#" data-egyedid="{$_egyed.id}" title="{t('Ront')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        {/if}
        <table>
            <tbody>
                {if ($showerbizonylatszam)}
                <tr><td>Er.biz.szám:</td><td>{$_egyed.erbizonylatszam}</td></tr>
                {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        {t('Kelt')}: {$_egyed.keltstr}
    </td>
    <td class="cell textalignright">
        {number_format($_egyed.brutto, 2, '.', ' ')} {$_egyed.valutanemnev}
    </td>
</tr>