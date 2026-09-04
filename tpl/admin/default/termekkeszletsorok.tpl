{* A termék készletsorai: változatonként (változat nélkül a termék maga) készlet, foglalás és
   szabad készlet (készlet − min. készlet − foglalás).
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
                <td class="keszletoszlop">{$vk.foglaltmennyiseg}</td>
                <td class="keszletoszlop" title="{at('Készlet − min. készlet − foglalás')}">{$vk.szabadkeszlet}</td>
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
            <td class="keszletoszlop">{$termek.foglaltmennyiseg}</td>
            <td class="keszletoszlop" title="{at('Készlet − min. készlet − foglalás')}">{$termek.szabadkeszlet}</td>
            <td><a href="/admin/termek/cimke?termek={$termek.id|escape:'url'}" target="_blank"
                   class="js-termekcimke ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                   data-keszlet="{$termek.keszlet}"
                   title="{at('Címke nyomtatás')}"><span class="ui-button-text"><span
                            class="ui-icon ui-icon-tag"></span></span></a></td>
        </tr>
    {/if}
    </tbody>
</table>
