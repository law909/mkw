{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$termek.cikkszam} {$termek.caption}</h3>
            <h4>{$termek.szin}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 valtozatnagykep">
            <img src="{$termek.kepurlmedium}">
            <div class="textalignnone">{$termek.leiras}</div>
        </div>
        <div class="col-md-7">
            <form>
                {foreach $termek.valtozatok as $_valt}
                <div class="row valtozatsor">
                    <div class="col-md-4 valtozatkozep">
                        <span>{$termek.szin} - {$_valt.caption}</span>
                    </div>
                    <div class="col-md-3 valtozatkozep">
                        <span>{number_format($termek.ar,0,',','')} {$termek.valutanemnev}</span>
                    </div>
                    <div class="col-md-1 valtozatkozep">
                        <img src="{if ($_valt.keszlet <= 0)}/themes/main/superzone/nincs.jpg{else}/themes/main/superzone/van.jpg{/if}">
                    </div>
                    <div class="col-md-4">
                        <div class="desktopright">
                            <input name="mennyiseg_{$_valt.id}" size="5" data-id="{$_valt.id}" class="js-mennyiseginput">
                            <button class="btn btn-mini js-mennyincrement" data-name="mennyiseg_{$_valt.id}">+</button>
                            <button class="btn btn-mini js-mennydecrement" data-name="mennyiseg_{$_valt.id}">-</button>
                        </div>
                    </div>
                </div>
                {/foreach}
            </form>
            <div>
                <a href="/kosar/multiadd" class="btn btn-large btn-primary desktopright js-kosarbabtn" data-termekid="{$termek.id}">Add to cart</a>
            </div>
        </div>
    </div>
{/block}