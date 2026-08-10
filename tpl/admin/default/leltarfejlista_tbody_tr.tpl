<tr id="mattable-row_{$_leltarfej.id}" data-egyedid="{$_leltarfej.id}"{if ($_leltarfej.zarva)} class="rontott"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <div>
            <a class="mattable-editlink" href="#" data-leltarfejid="{$_leltarfej.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_leltarfej.nev}</a>
            {if (!$_leltarfej.zarva)}
                <a class="js-felvetel" href="/admin/leltarfelvetel/view?leltar={$_leltarfej.id}" target="_blank">{at('Felvétel')}</a>
            {/if}
            <a class="js-export" href="/admin/leltarfej/viewexport?leltar={$_leltarfej.id}" target="_blank">{at('Felvételi ív')}</a>
            <a class="js-import" href="/admin/leltarfej/viewimport?leltar={$_leltarfej.id}" target="_blank">{at('Tény adat betöltés')}</a>
            <a class="js-zar" href="#" data-href="/admin/leltarfej/zar" data-leltarfejid="{$_leltarfej.id}">{at('Zárás')}</a>

            {if (!$_leltarfej.zarva)}
            <a class="mattable-dellink" href="#" data-leltarfejid="{$_leltarfej.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            {/if}
        </div>
        <div>{$_leltarfej.nev}</div>
        <div>{at('Raktár')}: {$_leltarfej.raktarnev}</div>
    </td>
    <td class="cell">{$_leltarfej.nyitasstr}</td>
    <td class="cell">{$_leltarfej.zarasstr}</td>
</tr>