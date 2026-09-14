<div id="ArlistaTab" class="mattkarb-page" data-visible="visible">
    <input type="hidden" name="arlistaposted" value="1">
    <p>{at('Vásárlási sávok (tól-ig) és termékkategóriánként a sávban adott kedvezmény %.')}</p>
    <table class="ui-widget ui-widget-content ui-corner-all">
        <thead>
        <tr>
            <th>{at('Termékkategória')}</th>
            {foreach $partner.arlista.savok as $_sav}
                <th class="js-arlistasav" data-sav="{$_sav.id}">
                    <input type="hidden" name="arlistasavid[]" value="{$_sav.id}">
                    <input name="arlistasavtol_{$_sav.id}" type="text" size="9" value="{$_sav.tol}"> -
                    <input name="arlistasavig_{$_sav.id}" type="text" size="9" value="{$_sav.ig}">
                    <a class="js-arlistasavdelbutton" href="#" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                </th>
            {/foreach}
            <th><a class="js-arlistasavnewbutton" href="#" title="{at('Új sáv')}"><span class="ui-icon ui-icon-circle-plus"></span></a></th>
        </tr>
        </thead>
        <tbody class="js-arlistasorok">
        {foreach $partner.arlista.sorok as $_sor}
            <tr class="js-arlistasor" data-sor="{$_sor.id}">
                <td>
                    <input type="hidden" name="arlistasor[]" value="{$_sor.id}">
                    <span><input type="hidden" name="arlistatermekfa_{$_sor.id}" value="{$_sor.termekfaid}"><a class="js-termekkategoriafabutton" href="#" data-text="{at('válasszon')}">{$_sor.termekfanev}</a></span>
                    <a class="js-arlistasordelbutton" href="#" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                </td>
                {foreach $partner.arlista.savok as $_sav}
                    <td data-sav="{$_sav.id}"><input name="arlistakedvezmeny_{$_sor.id}_{$_sav.id}" type="text" size="6" value="{$_sor.kedvezmenyek[$_sav.id]|default}"> %</td>
                {/foreach}
                <td></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <a class="js-arlistasornewbutton" href="#" title="{at('Új kategória')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
    {if ($partner.id)}
        <div class="matt-hseparator"></div>
        <h4>{at('Árlista nyomtatás')}</h4>
        <p>{at('A mentett árlistát nyomtatja, a partner nyelvén, ársávjának és valutanemének nettó árával.')}</p>
        <div>
            <label>{at('Szöveg a táblázat fölött')}:</label><br>
            <textarea class="js-arlistafejszoveg" rows="3" cols="80"></textarea>
        </div>
        <div>
            <label>{at('Szöveg a táblázat alatt')}:</label><br>
            <textarea class="js-arlistalabszoveg" rows="3" cols="80"></textarea>
        </div>
        <div>
            <label>{at('Csak ezekkel a címkékkel jelölt termékek (ha egy sincs bejelölve: mind)')}:</label>
            <div style="max-height: 180px; overflow: auto">
                {foreach $partner.arlistacimkekat as $_cimkekat}
                    <div>
                        <strong>{$_cimkekat.caption}:</strong>
                        {foreach $_cimkekat.cimkek as $_cimke}
                            <label><input type="checkbox" class="js-arlistacimke" value="{$_cimke.id}"> {$_cimke.caption}</label>
                        {/foreach}
                    </div>
                {/foreach}
            </div>
        </div>
        <a class="js-arlistanyomtatas" href="#" data-partner="{$partner.id}">{at('Nyomtatás')}</a>
    {/if}
</div>
