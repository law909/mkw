<div class="matt-hseparator"></div>
<div class="js-posvaltozatsor bizonylatpos-valtozatsor" data-termekid="{$termekid}">
    <div class="bizonylatpos-valtozattermeknev">{$termekcikkszam|escape} {$termeknev|escape}</div>
    <div class="matt-hseparator"></div>
    <label>{at('Változat')}:</label>
    <select class="js-posvaltozatvalaszto">
        <option value="">{at('Válasszon változatot')}</option>
        {foreach $valtozatlist as $_v}
            <option value="{$_v.id}"{if (!$_v.elerheto || ($_v.keszlet <= 0))} class="nemelerhetovaltozat"{/if}>{$_v.caption} ({$_v.keszlet})</option>
        {/foreach}
    </select>
    <a class="js-posvaltozatmegse" href="#">{at('Mégse')}</a>
</div>
