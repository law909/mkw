{* A tételsor termékválasztója melletti két gomb. Mindkettő új lapra nyit, hogy a félig kitöltött
   bizonylat ne vesszen el. Az „Adatlap" href-jét a bizonylathelper.js állítja össze kattintáskor,
   mert a tétel terméke a betöltés után is változhat. *}
{if (($tetel.oper|default:'') !== 'storno')}
    <a class="js-ujtermek ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
       href="/admin/termek/viewkarb?id=0&amp;oper=add" target="_blank"
       title="{at('Új termék felvitele új lapon')}"><span class="ui-button-text">{at('Új')}</span></a>
{/if}
<a class="js-termekadatlap ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
   href="#" target="_blank"
   title="{at('A kiválasztott termék karbantartója új lapon')}"><span class="ui-button-text">{at('Adatlap')}</span></a>
