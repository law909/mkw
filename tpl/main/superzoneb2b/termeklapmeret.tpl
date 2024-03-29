{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$termek.cikkszam} {$termek.caption}</h3>
            <h4>{$termek.szin}</h4>
        </div>
    </div>
    <div class="row valtozatcontainer">
        <div class="col-md-4 valtozatnagykep">
            <a href="{$imagepath}{$termek.kepurllarge}" class="js-lightbox" title="{$termek.caption}">
                <img src="{$imagepath}{$termek.kepurlmedium}">
            </a>
            <div class="textalignnone">{$termek.leiras}</div>
        </div>
        <div class="col-md-8">
            <form class="valtozatform">
                <table class="valtozattable">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="textalignright">Retail price</th>
                        <th class="textalignright">Discount</th>
                        <th class="textalignright">Unit price</th>
                        <!--th class="textalignright">Stock</th-->
                        <th class="textaligncenter">Arrival</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $termek.valtozatok as $_valt}
                        <tr class="valtozatkozep">
                            <td class="">
                                <span>{$termek.szin} - {$_valt.caption}</span>
                            </td>
                            <td class="textalignright">
                                <span>{number_format($termek.eredetiar, 2, ',', ' ')} {$termek.valutanemnev}</span>
                            </td>
                            <td class="textalignright">
                                {if ($uzletkoto.loggedin)}
                                    <input name="kedvezmeny_{$_valt.id}" type="number" data-id="{$_valt.id}" data-eredetiar="{$termek.eredetiar}"
                                           data-eredetikedvezmeny="{$termek.kedvezmeny}" value="{$termek.kedvezmeny}" class="js-kedvezmenyinput">
                                    %
                                {else}
                                    <span>{number_format($termek.kedvezmeny, 2, ',', ' ')} %</span>
                                {/if}
                            </td>
                            <td class="textalignright">
                                <span class="js-ar{$_valt.id}">{number_format($termek.ar, 2, ',', ' ')}</span><span> {$termek.valutanemnev}</span>
                            </td>
                            {if ($showkeszlet)}
                                <td class="textalignright">
                                    {if ($_valt.keszlet <= 0)}0{else}{$_valt.keszlet}{/if} pcs
                                </td>
                            {else}
                                <td class="valtozatkeszlet textaligncenter">
                                    {if ($_valt.keszlet > 0)}
                                        <img src="/themes/main/superzoneb2b/van.jpg">
                                    {else}
                                        {if ($_valt.bejon)}
                                            <span class="onroad">on road</span>
                                        {else}
                                            <img src="/themes/main/superzoneb2b/nincs.jpg">
                                        {/if}
                                    {/if}
                                </td>
                            {/if}
                            <td class="valtozatmenny">
                                <div class="desktopright">
                                    {if (!$nemrendelhet)}
                                        <input name="mennyiseg_{$_valt.id}" type="number" data-id="{$_valt.id}" class="js-mennyiseginput">
                                        <button type="button" class="btn x btn-mini js-mennyincrement" data-name="mennyiseg_{$_valt.id}">+</button>
                                        <button type="button" class="btn x btn-mini js-mennydecrement" data-name="mennyiseg_{$_valt.id}">-</button>
                                    {/if}
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </form>
            <div>
                {if ($termek.ar > 0 && !$nemrendelhet)}
                    <a href="/kosar/multiadd" class="btn btn-large btn-primary desktopright js-kosarbabtn" data-termekid="{$termek.id}">Add to cart</a>
                {/if}
            </div>
        </div>
    </div>
{/block}