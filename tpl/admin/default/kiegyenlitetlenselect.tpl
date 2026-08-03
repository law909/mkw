<table class="kiegyenlitetlenselect">
    <tbody>
    <thead>
        <tr>
            <td></td>
            <td>{at('Bizonylat')}</td>
            <td>{at('Er.biz.szám')}</td>
            <td>{at('Fiz.mód')}</td>
            <td>{at('Esedékesség')}</td>
            <td>{at('Egyenleg')}</td>
        </tr>
    </thead>
    {foreach $bizonylatok as $biz}
        <tr data-bizszam="{$biz.bizszam}" data-datum="{$biz.datum}" data-egyenleg="{$biz.egyenleg}">
            <td>
                {if ($biz.bizszamlink)}
                    <a class="js-bizlink" href="{$biz.bizszamlink}" target="_blank"
                       title="{at('Ugrás a bizonylathoz')}"><span class="ui-icon ui-icon-extlink"></span></a>
                {/if}
            </td>
            <td>{$biz.bizszam}</td>
            <td>{$biz.erbizszam}</td>
            <td>{$biz.fizmod}</td>
            <td>{$biz.datum}</td>
            <td class="textalignright">{$biz.egyenleg}</td>
        </tr>
    {/foreach}
    </tbody>
</table>