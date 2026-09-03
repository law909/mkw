{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$termek.cikkszam} {$termek.caption}</h3>
        </div>
    </div>
    <div class="row valtozatcontainer">
        <div class="col-md-4 valtozatnagykep">
            {if ($termek.kepurlmedium)}
                <a href="{$imagepath}{$termek.kepurllarge}" class="js-lightbox" title="{$termek.caption}">
                    <img src="{$imagepath}{$termek.kepurlmedium}">
                </a>
            {/if}
            <div class="textalignnone">{$termek.leiras}</div>
        </div>
        <div class="col-md-8">
            {if (count($termek.valtozatok) > 0)}
                <form class="valtozatform">
                    <table class="valtozattable">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="textalignright">{t('Listaár')}</th>
                            <th class="textalignright">{t('Kedvezmény')}</th>
                            <th class="textalignright">{t('Egységár')}</th>
                            <th class="textaligncenter">{t('Készlet')}</th>
                            <th class="textaligncenter">{t('Mennyiség')}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $termek.valtozatok as $_valt}
                            <tr class="valtozatkozep">
                                <td><span>{$_valt.caption}</span></td>
                                <td class="textalignright">
                                    <span>{number_format($termek.eredetiar, 2, ',', ' ')} {$termek.valutanemnev}</span>
                                </td>
                                <td class="textalignright">
                                    {if ($uzletkoto.loggedin)}
                                        <input name="kedvezmeny_{$_valt.id}" type="number" data-id="{$_valt.id}" data-eredetiar="{$termek.eredetiar}"
                                               data-eredetikedvezmeny="{$termek.kedvezmeny}" value="{$termek.kedvezmeny}" class="js-kedvezmenyinput">
                                        %
                                    {else}
                                        <span>{number_format($termek.kedvezmeny|default:0, 2, ',', ' ')} %</span>
                                    {/if}
                                </td>
                                <td class="textalignright">
                                    <span class="js-ar{$_valt.id}">{number_format($termek.ar, 2, ',', ' ')}</span><span> {$termek.valutanemnev}</span>
                                </td>
                                {if ($showkeszlet)}
                                    <td class="textalignright">
                                        {if ($_valt.keszlet <= 0)}0{else}{$_valt.keszlet}{/if} {t('db')}
                                    </td>
                                {else}
                                    <td class="valtozatkeszlet textaligncenter">
                                        {if ($_valt.keszlet > 0)}
                                            <img src="/themes/main/galad/van.jpg">
                                        {else}
                                            {if ($_valt.bejon)}
                                                <span class="onroad">{t('úton')}</span>
                                            {else}
                                                <img src="/themes/main/galad/nincs.jpg">
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
                        <a href="/kosar/multiadd" class="btn btn-large btn-primary desktopright js-kosarbabtn"
                           data-termekid="{$termek.id}">{t('Kosárba')}</a>
                    {/if}
                </div>
            {else}
                <p>{t('Ez a termék jelenleg nem rendelhető.')}</p>
            {/if}
        </div>
    </div>
{/block}
