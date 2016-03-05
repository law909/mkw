<table id="kapcsolodotable_{$kapcsolodo.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="kapcsolodoid[]" type="hidden" value="{$kapcsolodo.id}">
    <input name="kapcsolodooper_{$kapcsolodo.id}" type="hidden" value="{$kapcsolodo.oper}">
    <tr>
        <td>
            {if ($kapcsolodo.oper=='add')}
                <input id="KapcsolodoSelect{$kapcsolodo.id}" type="text" name="kapcsolodotermeknev_{$kapcsolodo.id}" class="js-kapcsolodoselect termekselect mattable-important" value="{$kapcsolodo.termeknev}" required="required">
            {else}
                {$kapcsolodo.altermeknev}
            {/if}
            <input class="js-kapcsolodotermekid" name="kapcsolodoaltermek_{$kapcsolodo.id}" type="hidden" value="{$kapcsolodo.altermekid}">
        </td>
        <td>
            <a class="js-kapcsolododelbutton" href="#" data-id="{$kapcsolodo.id}"{if ($kapcsolodo.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
</tbody>
</table>
{if ($kapcsolodo.oper=='add')}
    <a class="js-kapcsolodonewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}