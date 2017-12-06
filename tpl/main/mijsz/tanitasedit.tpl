<div class="mijszoralatogatasdoboz">
    <input name="mijsztanitasid[]" type="hidden" value="{$mijsztanitas.id}">
    <input name="mijsztanitasoper_{$mijsztanitas.id}" type="hidden" value="{$mijsztanitas.oper}">

    <div class="control-group">
        <label for="tanitashelyszinEdit{$mijsztanitas.id}" class="control-label">{t('Helyszín')}:</label>
        <div class="controls">
            <input class="input" id="tanitashelyszinEdit{$mijsztanitas.id}" type="text" name="mijsztanitashelyszin_{$mijsztanitas.id}" value="{$mijsztanitas.helyszin}" required="required">
        </div>
    </div>
    <div class="control-group">
        <label for="tanitasmikorEdit{$mijsztanitas.id}" class="control-label">{t('Milyen rendszeresen')}:</label>
        <div class="controls">
            <input class="input" id="tanitasmikorEdit{$mijsztanitas.id}" type="text" name="mijsztanitasmikor_{$mijsztanitas.id}" value="{$mijsztanitas.mikor}" required="required">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="MIJSZtanitasNapEdit{$mijsztanitas.id}">{t('Melyik nap')}:</label>
        <div class="controls">
            <select id="MIJSZtanitasNapEdit{$mijsz.id}" name="mijsztanitasnap_{$mijsztanitas.id}">
                <option value="">{t('válasszon')}</option>
                {foreach $mijsztanitas.mijsztanitasnaplist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{t($_valuta.caption)}</option>
                {/foreach}
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="MIJSZtanitasSzintEdit{$mijsztanitas.id}">{t('Óra szintje')}:</label>
        <div class="controls">
            <select id="MIJSZtanitasSzintEdit{$mijsz.id}" name="mijsztanitasszint_{$mijsztanitas.id}">
                <option value="">{t('válasszon')}</option>
                {foreach $mijsztanitas.mijsztanitasszintlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
            <input type="text" name="mijsztanitasszintegyeb_{$mijsztanitas.id}" value="{$mijsztanitas.szintegyeb}" placeholder="{t('egyéb')}">
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        <a class="js-mijsztanitasdelbutton btn btn-warning" href="#" data-id="{$mijsztanitas.id}"{if ($mijsztanitas.oper=='add')} data-source="client"{/if} title="{t('Töröl')}">{t('Töröl')}</a>
        </div>
    </div>
</div>