<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">{$_egyed.createdstr}</td>
    <td class="cell">{$_egyed.partnernev}</td>
    <td class="cell">{$_egyed.termekfanev}</td>
    <td class="cell">{$_egyed.esemeny}</td>
    <td class="cell textalignright">{if ($_egyed.regikedvezmeny !== null)}{$_egyed.regikedvezmeny} %{/if}</td>
    <td class="cell textalignright">{if ($_egyed.ujkedvezmeny !== null)}{$_egyed.ujkedvezmeny} %{/if}</td>
    <td class="cell">{$_egyed.modositonev}</td>
</tr>
