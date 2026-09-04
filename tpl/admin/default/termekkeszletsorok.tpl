{* A termék készletsorai: változatonként (változat nélkül a termék maga) készlet, foglalás,
   szabad készlet (készlet − min. készlet − foglalás) és a még beérkezésre váró mennyiség.
   A foglalt és az érkező mennyiség linkje a foglaló / érkeztető bizonylatok modalját nyitja
   (mkwcomp.keszletBizonylatok).
   A terméklista készlet oszlopa és a termék karbantartó Készlet füle ugyanezt mutatja. *}
<table>
    <thead>
    <tr>
        {if ($maintheme == 'galad')}
            <th>{at('Cikkszám')}</th>
        {/if}
        <th colspan="2">{at('Változat')}</th>
        <th class="keszletoszlop">{at('Készlet')}</th>
        <th class="keszletoszlop">{at('Foglalt')}</th>
        <th class="keszletoszlop" title="{at('Készlet − min. készlet − foglalás')}">{at('Szabad')}</th>
        <th class="keszletoszlop">{at('Érkezik')}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {if ($termek.valtozatkeszlet)}
        {foreach $termek.valtozatkeszlet as $vk}
            <tr>
                {if ($maintheme == 'galad')}
                    <td>{$vk.cikkszam}</td>
                {/if}
                <td><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.ertek1}</a></td>
                <td><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.ertek2}</a></td>
                <td class="keszletoszlop"><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.keszlet}</a></td>
                <td class="keszletoszlop">{if ($vk.foglaltmennyiseg != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termek.id}"
                                              data-valtozatid="{$vk.id}" data-tipus="foglal">{$vk.foglaltmennyiseg}</a>{else}{$vk.foglaltmennyiseg}{/if}</td>
                <td class="keszletoszlop" title="{at('Készlet − min. készlet − foglalás')}">{$vk.szabadkeszlet}</td>
                <td class="keszletoszlop">{if ($vk.erkezik != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termek.id}"
                                              data-valtozatid="{$vk.id}" data-tipus="erkezik">{$vk.erkezik}</a>{else}{$vk.erkezik}{/if}</td>
                <td><a href="/admin/termek/cimke?termek={$termek.id|escape:'url'}&valtozat={$vk.id|escape:'url'}" target="_blank"
                       class="js-termekcimke ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                       data-keszlet="{$vk.keszlet}"
                       title="{at('Címke nyomtatás')}"><span class="ui-button-text"><span
                                class="ui-icon ui-icon-tag"></span></span></a></td>
            </tr>
        {/foreach}
    {else}
        <tr>
            {if ($maintheme == 'galad')}
                <td>{$termek.cikkszam}</td>
            {/if}
            <td colspan="2"></td>
            <td class="keszletoszlop"><a href="#" data-id="{$termek.id}" class="js-keszletreszletezobutton">{$termek.keszlet}</a></td>
            <td class="keszletoszlop">{if ($termek.foglaltmennyiseg != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termek.id}"
                                          data-tipus="foglal">{$termek.foglaltmennyiseg}</a>{else}{$termek.foglaltmennyiseg}{/if}</td>
            <td class="keszletoszlop" title="{at('Készlet − min. készlet − foglalás')}">{$termek.szabadkeszlet}</td>
            <td class="keszletoszlop">{if ($termek.erkezik != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termek.id}"
                                          data-tipus="erkezik">{$termek.erkezik}</a>{else}{$termek.erkezik}{/if}</td>
            <td><a href="/admin/termek/cimke?termek={$termek.id|escape:'url'}" target="_blank"
                   class="js-termekcimke ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                   data-keszlet="{$termek.keszlet}"
                   title="{at('Címke nyomtatás')}"><span class="ui-button-text"><span
                            class="ui-icon ui-icon-tag"></span></span></a></td>
        </tr>
    {/if}
    </tbody>
</table>
