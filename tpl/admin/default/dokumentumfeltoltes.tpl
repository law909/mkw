{* A dokumentum fül tetején álló azonnali feltöltés. A fájl a config.ini path.dokumentum
   mappájába kerül, a belőle képzett sor pedig oper=add-del jelenik meg – a rekord csak a
   karbantartó mentésekor születik meg. *}
<div class="mattkarb-dokfeltoltes">
    <a class="js-dokuploadbutton ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
       href="#" title="{at('Fájl feltöltése a dokumentumok mappájába')}"><span
                class="ui-button-text">{at('Azonnali feltöltés')}</span></a>
    <span class="mattkarb-hint">{at('Cél mappa')}: {$dokumentumurl}</span>
</div>
