{* Az ársávok listája a termék karbantartón (default és darshan); a sorok a termektermekarkarb.tpl-ből jönnek,
   az új sort a JS a .js-arlista végére fűzi *}
<div class="arsav-lista js-arlista">
    <div class="arsav-fej">
        <span>{at('Ársáv')}</span>
        <span>{at('Valutanem')}</span>
        <span>{at('Nettó')}</span>
        <span>{at('Bruttó')}</span>
        <span></span>
        <span></span>
    </div>
    {foreach $egyed.arak as $ar}
        {include './termektermekarkarb.tpl'}
    {/foreach}
</div>
<div class="arsav-gombok">
    <a class="js-arnewbutton" href="#">{at('Új ársáv')}</a>
    <a class="js-arrecalcbutton" href="#" title="{at('A képlettel számolt ársávok nettó és bruttó árát a forrás ársávból újraszámolja; menteni az OK gombbal kell.')}">{at('Képlettel számolt árak újraszámolása')}</a>
</div>
<div class="js-arrecalchibak"></div>
