<table class="kiegyenlitetlenselect">
    <tbody>
    <thead>
        <tr>
            <td>Bizonylat</td>
            <td>Esedékesség</td>
            <td>Egyenleg</td>
        </tr>
    </thead>
    {foreach $bizonylatok as $biz}
        <tr data-bizszam="{$biz.bizszam}" data-datum="{$biz.datum}" data-egyenleg="{$biz.egyenleg}">
            <td>{$biz.bizszam}</td>
            <td>{$biz.datum}</td>
            <td class="textalignright">{$biz.egyenleg}</td>
        </tr>
    {/foreach}
    </tbody>
</table>