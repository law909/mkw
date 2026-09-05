{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Készlet</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    <h5>{$nevfilter}</h5>
    <h5>{$foglalasstr}</h5>
    <h5>{$minkeszletstr}</h5>
    <h5>{$arsav}</h5>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
            <th class="textalignright">Ár</th>
            <th class="textalignright">Érték</th>
            <th>Bizonylat</th>
        </tr>
        </thead>
        <tbody>
        {$sum = 0}
        {$arsum = 0}
        {foreach $lista as $elem}
            {* FIFO-nál a sor értéke tárolt szám, nem ár × készlet: a rétegek külön áron állnak *}
            {if isset($elem.ertek)}{$sorertek = $elem.ertek}{else}{$sorertek = $elem.ar * $elem.keszlet}{/if}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.keszlet}</td>
                <td class="cell textalignright nowrap{if isset($elem.becsult) && $elem.becsult} redtext{/if}">{$elem.ar}</td>
                <td class="cell textalignright nowrap">{$sorertek}</td>
                <td class="cell">{$elem.bizid}</td>
            </tr>
            {$sum = $sum + $elem.keszlet}
            {$arsum = $arsum + $sorertek}
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td class="textalignright">{$sum}</td>
            <td></td>
            <td class="textalignright">{$arsum}</td>
            <td></td>
        </tr>
        </tfoot>
    </table>
{/block}