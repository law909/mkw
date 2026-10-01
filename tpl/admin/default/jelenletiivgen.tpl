{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jelenletiivgen.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Munkaidő nyilvántartás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Munkaidő nyilvántartás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="jelenletiivgen" action="" target="_blank">
                    {include "comp_idoszak.tpl" comptype="datum"}
                    <div class="matt-hseparator"></div>
                    <div>{at('Munkakörök')}:</div>
                    <div class="js-munkakorok">
                        {foreach $munkakorlist as $_mk}
                            <div>
                                <input id="Munkakor{$_mk.id}Edit" type="checkbox" name="munkakor[]" value="{$_mk.id}">
                                <label for="Munkakor{$_mk.id}Edit">{$_mk.caption}</label>
                            </div>
                        {/foreach}
                    </div>
                    <span>{at('Ha nincs munkakör kipipálva, minden munkakör dolgozója látszik.')}</span>
                    <div class="matt-hseparator"></div>
                    {include "comp_dolgozoselect.tpl"}
                    <span>{at('Dolgozó nélkül a kiválasztott munkakörök minden aktív dolgozójának nyilvántartása elkészül.')}</span>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/jelenletiivgen/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/jelenletiivgen/matrix" class="js-okbutton">{at('Mátrix')}</a>
                        <a href="/admin/jelenletiivgen/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
