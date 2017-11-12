<table id="mijszpunetable_{$mijszpune.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="mijszpuneid[]" type="hidden" value="{$mijszpune.id}">
    <input name="mijszpuneoper_{$mijszpune.id}" type="hidden" value="{$mijszpune.oper}">
    <tr>
        <td><label for="puneevEdit{$mijszpune.id}">{at('Látogatás éve')}:</label></td>
        <td><input id="puneevEdit{$mijszpune.id}" type="text" name="mijszpuneev_{$mijszpune.id}" value="{$mijszpune.ev}"></td>
        <td><label for="punehonapEdit{$mijszpune.id}">{at('Látogatás hónapja')}:</label></td>
        <td><input id="punehonapEdit{$mijszpune.id}" type="text" name="mijszpunehonap_{$mijszpune.id}" value="{$mijszpune.honap}"></td>
        <td>
            <a class="js-mijszpunedelbutton" href="#" data-id="{$mijszpune.id}"{if ($mijszpune.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($mijszpune.oper=='add')}
    <a class="js-mijszpunenewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}