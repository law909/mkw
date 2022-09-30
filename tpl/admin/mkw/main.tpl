{extends "../base.tpl"}

{block "kozep"}
{include "../default/comp_noallapot.tpl"}
{if (haveJog(20))}
<div class="ui-widget ui-widget-content ui-corner-all">
    <div class="ui-widget-header ui-corner-top">
        <div class="mainboxinner ui-corner-top">Nem kapható termékek, amikre van feliratkozó</div>
    </div>
    <div class="mainboxinner">
        <form action="/admin/lista/nemkaphatoertesito" target="_blank">
            <div>
                <label for="SorrendEdit">{t('Sorrend')}:</label>
                <select id="SorrendEdit" name="sorrend">
                    <option value="1">Név</option>
                    <option value="2">Cikkszám</option>
                    <option value="3">Első feliratkozás</option>
                </select>
            </div>
            <input type="submit" class="ui-widget ui-button ui-state-default ui-corner-all" value="OK">
        </form>
    </div>
</div>
<div class="ui-widget ui-widget-content ui-corner-all">
    <div class="ui-widget-header ui-corner-top">
        <div class="mainboxinner ui-corner-top">Termék népszerűség</div>
    </div>
    <div class="mainboxinner">
        <button class="js-nepszerusegclear ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Nulláz</span></button>
    </div>
</div>

{/if}
{/block}