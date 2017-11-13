<div class="control-group">
    <input name="mijszpuneid[]" type="hidden" value="{$mijszpune.id}">
    <input name="mijszpuneoper_{$mijszpune.id}" type="hidden" value="{$mijszpune.oper}">
    <label for="puneevEdit{$mijszpune.id}" class="control-label">{t('Év, hónap')}:</label>
    <input class="input-mini" id="puneevEdit{$mijszpune.id}" type="text" name="mijszpuneev_{$mijszpune.id}" value="{$mijszpune.ev}">
    <input class="input-mini" id="punehonapEdit{$mijszpune.id}" type="text" name="mijszpunehonap_{$mijszpune.id}" value="{$mijszpune.honap}">
    <a class="js-mijszpunedelbutton btn btn-warning" href="#" data-id="{$mijszpune.id}"{if ($mijszpune.oper=='add')} data-source="client"{/if} title="{at('Töröl')}">{at('Töröl')}</a>
</div>
