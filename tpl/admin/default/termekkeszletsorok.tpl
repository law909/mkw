{* A termék készletsorai: változatonként (változat nélkül a termék maga) készlet és foglalás.
   A terméklista készlet oszlopa és a termék karbantartó Készlet füle ugyanezt mutatja. *}
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
    </tr>
{/if}
