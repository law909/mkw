{extends "../rep_base.tpl"}

{block "body"}
    <h4>Nem kapható termékek, amikre van feliratkozó</h4>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Termék név</th>
            <th>Első feliratkozás</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell"><a href="{$elem.karburl}" target="_blank">{$elem.nev}</a></td>
                <td class="cell">{$elem.created}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}