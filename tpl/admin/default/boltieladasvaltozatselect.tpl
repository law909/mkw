<div class="matt-hseparator"></div>
<div class="js-boltieladas-valtozatsor boltieladas-valtozatsor" data-termekid="{$termekid}">
    <div class="boltieladas-valtozattermeknev">{$termekcikkszam|escape} {$termeknev|escape}</div>
    <div class="matt-hseparator"></div>
    <label>{t('Változat')}:</label>
    <select class="js-be-valtozatvalaszto">
        <option value="">{t('Válasszon változatot')}</option>
        {foreach $valtozatlist as $_v}
            <option value="{$_v.id}"{if (!$_v.elerheto || ($_v.keszlet <= 0))} class="nemelerhetovaltozat"{/if}>{$_v.caption} ({$_v.keszlet})</option>
        {/foreach}
    </select>
    <a href="#" class="js-be-valtozatmegse">{t('Mégse')}</a>
</div>
