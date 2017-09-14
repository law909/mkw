<table class="kiegyenlitetlenselect">
    <tbody>
    <thead>
        <tr>
            <td>{at('Bizonylat')}</td>
            <td>{at('Fiz.mód')}</td>
            <td>{at('Esedékesség')}</td>
            <td>{at('Egyenleg')}</td>
        </tr>
    </thead>
    {foreach $bizonylatok as $biz}
        <tr data-bizszam="{$biz.bizszam}" data-datum="{$biz.datum}" data-egyenleg="{$biz.egyenleg}">
            <td>{$biz.bizszam}</td>
            <td>{$biz.fizmod}</td>
            <td>{$biz.datum}</td>
            <td class="textalignright">{$biz.egyenleg}</td>
        </tr>
    {/foreach}
    </tbody>
</table>