<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{if ($_egyed.ismetlodo)}{at('minden')} {$_egyed.napnev}{else}{$_egyed.kezdet}{/if}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        {if ($_egyed.ismetlodo)}
            <div>{$_egyed.idotartam}</div>
        {else}
            <div>{$_egyed.napnev}</div>
            <div>{at('Vége')}: {$_egyed.veg}</div>
        {/if}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{at('Téma')}: {$_egyed.idoponttemanev}</td>
            </tr>
            <tr>
                <td>{at('Tanár')}: {$_egyed.dolgozonev}</td>
            </tr>
            <tr>
                <td>{at('Helyszín')}: {$_egyed.jogahelyszinnev}</td>
            </tr>
            <tr>
                <td>{at('Ár')}: {$_egyed.ar}</td>
            </tr>
            <tr>
                <td>
                    {if ($_egyed.ismetlodo)}
                        {at('Max. résztvevő')}: {$_egyed.maxresztvevo} {at('alkalmanként')}
                    {else}
                        {at('Foglalás')}: {$_egyed.foglalasdb} / {$_egyed.maxresztvevo} ({at('szabad')}: {$_egyed.szabadhely})
                    {/if}
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="inaktiv"
                       class="js-flagcheckbox{if ($_egyed.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="onlinevalaszthato"
                       class="js-flagcheckbox{if ($_egyed.onlinevalaszthato)} ui-state-hover{/if}">{at('Online választható')}</a></td>
            </tr>
            <tr>
                <td>{if ($_egyed.ismetlodo)}{at('Ismétlődő (heti)')}{else}{at('Egyszeri')}{/if}</td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
