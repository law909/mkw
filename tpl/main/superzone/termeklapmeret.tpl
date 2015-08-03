{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$termek.cikkszam} {$termek.caption}</h3>
            <h4>{$termek.szin}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 valtozatnagykep">
            <img src="{$termek.kepurlmedium}">
        </div>
        <div class="col-md-8">
            <form>
                {foreach $termek.valtozatok as $_valt}
                <div class="row valtozatsor">
                    <div class="col-md-7 valtozatkozep">
                        <span>{$termek.szin} - {$_valt.caption}</span>
                    </div>
                    <div class="col-md-1 valtozatkozep">
                        <span>{$termek.brutto}</span>
                    </div>
                    <div class="col-md-1 valtozatkozep">
                        <img src="{if ($_valt.keszlet <= 0)}/themes/main/superzone/nincs.gif{else}/themes/main/superzone/van.gif{/if}">
                    </div>
                    <div class="col-md-3">
                        <input name="mennyiseg_{$_valt.id}" size="5">
                        <button class="btn btn-mini js-mennyincrement" data-name="mennyiseg_{$_valt.id}">+</button>
                        <button class="btn btn-mini js-mennydecrement" data-name="mennyiseg_{$_valt.id}">-</button>
                    </div>
                </div>
                {/foreach}
            </form>
        </div>
    </div>
{/block}