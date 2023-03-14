<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell">
        <input class="maincheckbox" type="checkbox">
    </td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.cim}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div>Azonosító: {$_egyed.id}</div>
        <div>Kezdés: {$_egyed.kezdodatumstr} - {$_egyed.kezdoido}</div>
        <div>Típus: {$_egyed.tipusnev}</div>
        <div>Tulajdonos: {$_egyed.tulajdonosnev}</div>
    </td>
    <td class="cell">
        <div>Első szerző: {$_egyed.szerzo1nev} ({$_egyed.szerzo1email})</div>
        <div>Szerző 2: {$_egyed.szerzo2nev} ({$_egyed.szerzo2email})</div>
        <div>Szerző 3: {$_egyed.szerzo3nev} ({$_egyed.szerzo3email})</div>
        <div>Szerző 4: {$_egyed.szerzo4nev} ({$_egyed.szerzo4email})</div>
        {if ($_egyed.szimpozium)}
            <div>Opponens: {$_egyed.szerzo5nev} ({$_egyed.szerzo5email})</div>
        {/if}
        <div>Egyéb szerzők:</div>
        <div>
            <pre class="ui-widget">{$_egyed.egyebszerzok}</pre>
        </div>
    </td>
    <td class="cell">
        <div>Összes pont: {$_egyed.osszespont}</div>
        {if ($_egyed.pluszbiralokell && !$_egyed.biralo3)}
            <div class="redtext">HARMADIK BÍRÁLÓ KELL</div>
        {/if}
        <div>Bíráló 1: {$_egyed.biralo1nev} - {$_egyed.biralo1pont} pont{if ($_egyed.b1biralatkesz)} - KÉSZ{/if}</div>
        <div>Bíráló 2: {$_egyed.biralo2nev} - {$_egyed.biralo2pont} pont{if ($_egyed.b2biralatkesz)} - KÉSZ{/if}</div>
        <div>Bíráló 3: {$_egyed.biralo3nev} - {$_egyed.biralo3pont} pont{if ($_egyed.b3biralatkesz)} - KÉSZ{/if}</div>
    </td>
    <td class="cell">
        <div>{$_egyed.temakor1nev}</div>
        <div>{$_egyed.temakor2nev}</div>
        <div>{$_egyed.temakor3nev}</div>
    </td>
    <td class="cell">
        {if ($_egyed.vegleges)}
            <div class="greentext">
                BEKÜLDVE
            </div>
        {/if}
        <div>
            {if ($_egyed.biralatkesz)}
                Bírálat kész
            {else}
                Bírálat nincs kész
            {/if}
        </div>
        <div>
            {if ($_egyed.konferencianszerepelhet)}
                Konferencián szerepelhet
            {else}
                Konferencián nem szerepelhet
            {/if}
        </div>
        {if (!allszerzoregistered)}
            <div class="redtext">
                Nincs minden szerző regisztrálva
            </div>
        {/if}
    </td>
</tr>