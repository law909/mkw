<div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin-top:5px;">
    {if ($riport.szarazfutas)}
        <div class="ui-state-highlight" style="padding:3px;">{at('Szárazfutás: semmi nem mentődött.')}</div>
    {/if}
    <table>
        <tbody>
        <tr><td>{at('UNAS kategória')}:</td><td>{$riport.osszes}</td></tr>
        <tr><td>{at('Új kategória')}:</td><td>{$riport.uj}</td></tr>
        <tr><td>{at('Név alapján bekötve')}:</td><td>{$riport.osszekotve}</td></tr>
        {if ($riport.frissit)}
            <tr><td>{at('Frissítve')}:</td><td>{$riport.frissitve}</td></tr>
        {/if}
        <tr><td>{at('Változatlan')}:</td><td>{$riport.valtozatlan}</td></tr>
        <tr><td>{at('Nyers válasz')}:</td><td>{$riport.dumpfajl|default:'-'|escape}</td></tr>
        </tbody>
    </table>
    {if ($riport.masszulo)}
        <div class="ui-state-error-text">{at('Az MKW-ban más szülő alatt vannak, nem helyeztük át')}:</div>
        <div>{foreach $riport.masszulo as $_sor}{$_sor|escape}{if (!$_sor@last)}, {/if}{/foreach}</div>
    {/if}
    {if ($riport.hibas)}
        <div class="ui-state-error-text">{at('Azonosító vagy név nélküli UNAS kategória, kimaradt')}:</div>
        <div>{foreach $riport.hibas as $_sor}{$_sor|escape}{if (!$_sor@last)}, {/if}{/foreach}</div>
    {/if}
</div>
