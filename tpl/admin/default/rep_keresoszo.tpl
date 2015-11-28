{extends "../rep_base.tpl"}

{block "body"}
    <h4>Keresések</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <table>
        <thead>
        <tr>
            <th>Keresett</th>
            <th class="textalignright">Keresések száma</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell nowrap">{$elem.szo}</td>
                <td class="cell textalignright nowrap">{$elem.db}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}