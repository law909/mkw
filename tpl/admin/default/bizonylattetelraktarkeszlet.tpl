{* A tétel termékének/változatának raktárankénti készlete, foglalása, szabad készlete és érkező mennyisége.
   A tartalmat termék- és változatváltáskor a /admin/bizonylattetel/getraktarkeszlet cseréli le.
   A foglalt és az érkező mennyiség linkje a foglaló / érkeztető bizonylatok modalját nyitja
   (mkwcomp.keszletBizonylatok); ehhez kell a termekid és a valtozatid. *}
{if ($lista)}
    <table class="tetelkeszlettabla">
        <tbody>
        <tr>
            <td></td>
            {foreach $lista as $elem}
                <td>{$elem.raktarnev}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Készlet')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.keszlet}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Foglalt')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{if ($elem.foglalt != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termekid}"
                                              data-valtozatid="{$valtozatid}" data-raktarid="{$elem.raktarid}" data-tipus="foglal">{$elem.foglalt}</a>{else}{$elem.foglalt}{/if}</td>
            {/foreach}
        </tr>
        <tr>
            <td title="{at('Készlet − min. készlet − foglalás')}">{at('Szabad')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.szabad}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Érkezik')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{if ($elem.erkezik != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termekid}"
                                              data-valtozatid="{$valtozatid}" data-raktarid="{$elem.raktarid}" data-tipus="erkezik">{$elem.erkezik}</a>{else}{$elem.erkezik}{/if}</td>
            {/foreach}
        </tr>
        </tbody>
    </table>
{else}
    &ndash;
{/if}
