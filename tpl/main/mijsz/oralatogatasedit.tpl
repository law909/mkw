<div class="mijszoralatogatasdoboz">
    <input name="mijszoralatogatasid[]" type="hidden" value="{$mijszoralatogatas.id}">
    <input name="mijszoralatogatasoper_{$mijszoralatogatas.id}" type="hidden" value="{$mijszoralatogatas.oper}">

    <div class="control-group">
        <label for="oralatogatasevEdit{$mijszoralatogatas.id}" class="control-label">{t('Melyik évben')}:</label>
        <div class="controls">
            <input class="input-mini" id="oralatogatasevEdit{$mijszoralatogatas.id}" type="number" name="mijszoralatogatasev_{$mijszoralatogatas.id}" value="{$mijszoralatogatas.ev}" required="required">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="MIJSZOralatogatasTanarEdit{$mijszoralatogatas.id}">{t('Tanár')}:</label>
        <div class="controls">
            <select id="MIJSZOralatogatasTanarEdit{$mijszoralatogatas.id}" name="mijszoralatogatastanar_{$mijszoralatogatas.id}">
                <option value="">{t('válasszon')}</option>
                {foreach $mijszoralatogatas.mijszoralatogatastanarlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
            <input type="text" name="mijszoralatogatastanaregyeb_{$mijszoralatogatas.id}" value="{$mijszoralatogatas.tanaregyeb}" placeholder="{t('egyéb')}">
        </div>
    </div>

    <div class="control-group">
        <label for="oralatogatashelyszinEdit{$mijszoralatogatas.id}" class="control-label">{t('Helyszín')}:</label>
        <div class="controls">
            <input class="input" id="oralatogatashelyszinEdit{$mijszoralatogatas.id}" type="text" name="mijszoralatogatashelyszin_{$mijszoralatogatas.id}" value="{$mijszoralatogatas.helyszin}" required="required">
        </div>
    </div>
    <div class="control-group">
        <label for="oralatogatasmikorEdit{$mijszoralatogatas.id}" class="control-label">{t('Mikor vagy milyen rendszeresen')}:</label>
        <div class="controls">
            <input class="input" id="oralatogatasmikorEdit{$mijszoralatogatas.id}" type="text" name="mijszoralatogatasmikor_{$mijszoralatogatas.id}" value="{$mijszoralatogatas.mikor}" required="required">
        </div>
    </div>
    <div class="control-group">
        <label for="oralatogatasoraszamEdit{$mijszoralatogatas.id}" class="control-label">{t('Hány órát (60 perc) töltöttél ott')}:</label>
        <div class="controls">
            <input class="input-mini" id="oralatogatasoraszamEdit{$mijszoralatogatas.id}" type="number" name="mijszoralatogatasoraszam_{$mijszoralatogatas.id}" value="{$mijszoralatogatas.oraszam}" required="required">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
        <a class="js-mijszoralatogatasdelbutton btn btn-warning" href="#" data-id="{$mijszoralatogatas.id}"{if ($mijszoralatogatas.oper=='add')} data-source="client"{/if} title="{t('Töröl')}">{t('Töröl')}</a>
        </div>
    </div>
</div>