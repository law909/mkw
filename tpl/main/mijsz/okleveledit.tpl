<div class="control-group">
    <input name="mijszoklevelid[]" type="hidden" value="{$mijszoklevel.id}">
    <input name="mijszokleveloper_{$mijszoklevel.id}" type="hidden" value="{$mijszoklevel.oper}">
    <label class="control-label" for="MIJSZOklevelOklevelkibocsajtoEdit{$mijszoklevel.id}">{at('Oklevél')}:</label>
    <select id="MIJSZOklevelOklevelkibocsajtoEdit{$mijszoklevel.id}" name="mijszokleveloklevelkibocsajto_{$mijszoklevel.id}" required="required">
        <option value="">{at('válasszon')}</option>
        {foreach $mijszoklevel.mijszoklevelkibocsajtolist as $_valuta}
            <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
        {/foreach}
    </select>
    <select id="MIJSZOklevelOklevelszintEdit{$mijszoklevel.id}" name="mijszokleveloklevelszint_{$mijszoklevel.id}" required="required">
        <option value="">{at('válasszon')}</option>
        {foreach $mijszoklevel.mijszoklevelszintlist as $_valuta}
            <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
        {/foreach}
    </select>
    <input class="input-mini" id="OklevelevEdit{$mijszoklevel.id}" type="text" name="mijszokleveloklevelev_{$mijszoklevel.id}" value="{$mijszoklevel.oklevelev}">
    <a class="js-mijszokleveldelbutton btn btn-warning" href="#" data-id="{$mijszoklevel.id}"{if ($mijszoklevel.oper=='add')} data-source="client"{/if} title="{at('Töröl')}">{at('Töröl')}</a>
</div>
