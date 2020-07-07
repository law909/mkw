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
<div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <div class="mattable-important">Pénztár egyenlegek {$ma}</div>
    <table>
        {foreach $penztaregyenlegek as $egyenleg}
            {if ($egyenleg[2] != 0)}
            <tr>
                <td>{$egyenleg.nev}</td>
                <td class="textalignright">{$egyenleg[2]}</td>
            </tr>
            {/if}
        {/foreach}
    </table>
</div>
<div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <div class="mattable-important">Tanár elszámolás {$telszeleje} - {$telszvege}</div>
    {$tanarelszamolas}
</div>
<div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <div class="mattable-important">Még felhasználható bérlet alkalom: {$berletalkalom['mennyiseg']}</div>
    <div>Még felhasználható bérlet érték: {$berletalkalom['ertek']} HUF</div>
    <div class="mattable-important">Ennyi jár belőle a tanároknak: {$berletalkalom['kifizetendo']} HUF</div>
</div>