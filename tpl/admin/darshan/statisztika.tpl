<div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
<div class="mattable-important">Új partnerek száma: {$ujpartnercount}</div>
<table>
    {foreach $ujpartnerlista as $k => $ujp}
        <tr>
            <td>{$ujp.datum}</td>
            <td>({$ujp.createdby})</td>
            <td>{$ujp.nev}</td>
            <td>{$ujp.email}</td>
        </tr>
    {/foreach}
</table>
</div>
<div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <div class="mattable-important">Óralátogatások száma: {$reszvetelcount}</div>
    <div>Üres terem: {$uresteremcount}</div>
    <table>
        {foreach $resztvevolista as $k => $resz}
            <tr>
                <td>{$resz.termek}</td>
                <td>{$resz.db}</td>
            </tr>
        {/foreach}
    </table>
</div>