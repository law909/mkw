<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{$_egyed.datum} {$_egyed.idopontkezdet}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        <div>{$_egyed.napnev}</div>
        {if ($_egyed.idopontnev)}<div>{$_egyed.idopontnev}</div>{/if}
        {if ($_egyed.idoponttemanev)}<div>{$_egyed.idoponttemanev}</div>{/if}
        <div>{$_egyed.idopontdolgozonev}</div>
        <div>{$_egyed.idoponthelyszinnev}</div>
    </td>
    <td class="cell">
        <div>{$_egyed.partnernev}</div>
        <div>{$_egyed.partneremail}</div>
        <div>{$_egyed.partnertelefon}</div>
        {if ($_egyed.megjegyzes)}<div class="mattable-note">{$_egyed.megjegyzes}</div>{/if}
    </td>
    <td class="cell">{$_egyed.foglalasido}</td>
    <td class="cell">
        {if ($_egyed.online)}{at('online')}{else}{at('élő')}{/if}
        {if ($_egyed.varolistas)}<div><span class="mattable-important">{at('Várólistás')}</span></div>{/if}
    </td>
    <td class="cell">
        {if ($_egyed.lemondva)}
            <div>
                <span class="mattable-important">{at('Lemondva')}</span> ({$_egyed.lemondasdatum})<br>
                {if ($_egyed.lemondasoka)}{at('Oka')}: {$_egyed.lemondasoka}{/if}
            </div>
        {/if}
        {if ($_egyed.fizetve)}
            <div>
                <span class="mattable-important">{at('Fizetve')}</span> ({$_egyed.fizetesdatum}): {bizformat($_egyed.fizetveosszeghuf)}<br>
                {$_egyed.fizmodnev}<br>
                {if ($_egyed.fizetvepenztarnev)}
                    {$_egyed.fizetvepenztarnev}<br>
                    {if ($_egyed.fizetvepenztarbizonylatszamlink)}
                        <a href="{$_egyed.fizetvepenztarbizonylatszamlink}" target="_blank"
                           title="{at('Ugrás a bizonylathoz')}">{$_egyed.fizetvepenztarbizonylatszam}</a>
                    {else}
                        {$_egyed.fizetvepenztarbizonylatszam}
                    {/if}
                {else}
                    {$_egyed.fizetvebankszamlaszam}<br>
                    {if ($_egyed.fizetvebankbizonylatszamlink)}
                        <a href="{$_egyed.fizetvebankbizonylatszamlink}" target="_blank"
                           title="{at('Ugrás a bizonylathoz')}">{$_egyed.fizetvebankbizonylatszam}</a>
                    {else}
                        {$_egyed.fizetvebankbizonylatszam}
                    {/if}
                {/if}
            </div>
        {/if}
        {if ($_egyed.szamlazva)}
            <div>
                <span class="mattable-important">{at('Számlázva')}</span> ({$_egyed.szamlazasdatum}): {bizformat($_egyed.szamlazvaosszeghuf)}<br>
                {if ($_egyed.szamlaszamlink)}
                    <a href="{$_egyed.szamlaszamlink}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$_egyed.szamlaszam}</a>
                {else}
                    {$_egyed.szamlaszam}
                {/if}<br>
                {at('Kért kelt')}: {$_egyed.szamlazvakelt}<br>
                {at('Kért teljesítés')}: {$_egyed.szamlazvateljesites}
            </div>
        {/if}
        {if ($_egyed.visszautalva)}
            <div>
                <span class="mattable-important">{at('Visszautalva')}</span> ({$_egyed.visszautalasdatum}): {bizformat($_egyed.visszautalasosszeghuf)}<br>
                {$_egyed.visszautalasfizmodnev}<br>
                {if ($_egyed.visszautalaspenztarbizonylatszamlink)}
                    <a href="{$_egyed.visszautalaspenztarbizonylatszamlink}" target="_blank"
                       title="{at('Ugrás a bizonylathoz')}">{$_egyed.visszautalaspenztarbizonylatszam}</a>
                {elseif ($_egyed.visszautalasbankbizonylatszamlink)}
                    <a href="{$_egyed.visszautalasbankbizonylatszamlink}" target="_blank"
                       title="{at('Ugrás a bizonylathoz')}">{$_egyed.visszautalasbankbizonylatszam}</a>
                {/if}
            </div>
        {/if}
    </td>
    <td class="cell">
        {if ($_egyed.emailkoszono)}
            <div>{at('Jelentkezés megköszönve')}</div>
        {/if}
        {if ($dijbekerosablonvan && !$_egyed.lemondva && !$_egyed.fizetve)}
            {if ($_egyed.emaildijbekero)}
                <div>{at('Díjbekérő')}: {$_egyed.emaildijbekerodatum}</div>
            {/if}
            <div><a class="js-emaildijbekero" href="#" data-id="{$_egyed.id}">{at('Díjbekérő email')}</a></div>
        {/if}
        {if ($emlekeztetosablonvan && !$_egyed.lemondva)}
            {if ($_egyed.emailemlekezteto)}
                <div>{at('Utolsó emlékeztető')}: {$_egyed.emailemlekeztetodatum}</div>
            {/if}
            <div><a class="js-emailemlekezteto" href="#" data-id="{$_egyed.id}">{at('Emlékeztető email')}</a></div>
        {/if}
        {if ($_egyed.lemondva)}
            <div><a class="js-visszaallit" href="#" data-id="{$_egyed.id}">{at('Visszaállít')}</a></div>
        {else}
            <div><a class="js-lemond" href="#" data-id="{$_egyed.id}">{at('Lemond')}</a></div>
            {if (!$_egyed.fizetve)}
                <div><a class="js-fizet" href="#" data-id="{$_egyed.id}">{at('Kifizet')}</a></div>
            {elseif (!$_egyed.szamlazva && $szamlazhato && haveJog(20) && $csinalhatujszamlat)}
                <div><a class="js-szamlaz" href="#" data-id="{$_egyed.id}">{at('Számláz')}</a></div>
            {/if}
        {/if}
    </td>
</tr>
