{extends "base.tpl"}

{block "kozep"}
    <div class="ui-widget ui-widget-content ui-corner-all">
        <div class="ui-widget-header ui-corner-top">
            <div class="mainbox ui-corner-top">Árfolyamok</div>
        </div>
        <div class="mainbox">
            <label for="ArfolyamDatumEdit">Dátum:</label>
            <input id="ArfolyamDatumEdit" name="datum" type="text" data-datum="{$today}">
            <button class="js-arfolyamdownload ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Letölt</span></button>
        </div>
    </div>
{/block}