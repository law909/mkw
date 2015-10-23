<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($_egyed.editprinted || (!$_egyed.editprinted && !$_egyed.nyomtatva))}
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.id}</a>
        {else}
            {$_egyed.id}
        {/if}
            <a class="js-printbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}" title="{t('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
            {if ($_egyed.nemrossz)}
                <a class="js-rontbizonylat" href="#" data-egyedid="{$_egyed.id}" title="{t('Ront')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            {/if}
        <table>
            <tbody>
                <tr><td  colspan="2" class="mattable-important">
                        {$_egyed.partnernev}
                    </td></tr>
                <tr><td colspan="2">
                        {$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca}
                    </td></tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table><tbody>
                {if ($showerbizonylatszam)}
                    <tr><td>Er.biz.szám:</td><td>{$_egyed.erbizonylatszam}</td></tr>
                {/if}
                <tr><td>{t('Kelt')}:</td><td>{$_egyed.keltstr}</td></tr>
            </tbody></table>
    </td>
    <td class="cell">
        <table>
            <tbody>
                <tr>
                    <td></td>
                    <td class="mattable-rightaligned">{$_egyed.valutanemnev}</td>
                </tr>
                <tr>
                    <td>{t('Nettó')}:</td><td class="mattable-rightaligned pricenowrap">{number_format($_egyed.netto, 2, '.', ' ')}</td>
                </tr>
                <tr>
                    <td>{t('ÁFA')}:</td>
                    <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.afa, 2, '.', ' ')}</td>
                </tr>
                <tr class="mattable-important">
                    <td>{t('Bruttó')}:</td>
                    <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.brutto, 2, '.', ' ')}</td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>