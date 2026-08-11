<div class="matt-hseparator"></div>
<div class="js-leltarvaltozatsor leltarfelvetel-valtozatsor" data-termekid="{$termekid}">
    <div class="leltarfelvetel-valtozattermeknev">{$termekcikkszam|escape} {$termeknev|escape}</div>
    <div class="matt-hseparator"></div>
    <label>{at('Változat')}:</label>
    <select class="js-leltarvaltozatvalasztoselect">
        <option value="">{at('Válasszon változatot')}</option>
        {foreach $valtozatlist as $_v}
            <option value="{$_v.id}">{$_v.caption} ({$_v.keszlet})</option>
        {/foreach}
    </select>
    <a class="js-leltarvaltozatmegse" href="#">{at('Mégse')}</a>
</div>
