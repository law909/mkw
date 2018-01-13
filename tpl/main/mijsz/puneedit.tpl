<div class="control-group">
    <input name="mijszpuneid[]" type="hidden" value="{$mijszpune.id}">
    <input name="mijszpuneoper_{$mijszpune.id}" type="hidden" value="{$mijszpune.oper}">
    <div style="position: relative">
        <label for="TolEdit{$mijszpune.id}" class="control-label">{t('Pontos dátum')}:</label>
        <input id="TolEdit{$mijszpune.id}" type="text" class="js-idoszakedit" value="{$mijszpune.tolstr}" data-date="{$mijszpune.tolstr}" name="mijszpunetol_{$mijszpune.id}">
        <input id="IgEdit{$mijszpune.id}" type="text" class="js-idoszakedit" value="{$mijszpune.igstr}" data-date="{$mijszpune.igstr}" name="mijszpuneig_{$mijszpune.id}">
    </div>
    <div>
    <label for="NapszamEdit{$mijszpune.id}" class="control-label">{t('Napok száma')}:</label>
    <input id="NapszamEdit{$mijszpune.id}" type="number" value="{$mijszpune.napszam}" name="mijszpunenapszam_{$mijszpune.id}">
    </div>
    <a class="js-mijszpunedelbutton btn btn-warning" href="#" data-id="{$mijszpune.id}"{if ($mijszpune.oper=='add')} data-source="client"{/if} title="{t('Töröl')}">{t('Töröl')}</a>
</div>
