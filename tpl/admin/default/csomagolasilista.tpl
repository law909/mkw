{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/csomagolasilista.js"></script>
{/block}

{block "kozep"}
    {* saját mentés, nem a mattkarb plugin: az mentés után visszalépne a listára *}
    <div id="mattkarb" class="ui-widget ui-widget-content ui-corner-all mattkarb">
        <div id="mattkarb-header" class="mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix">
            <h3>{at('Csomagolási lista')}</h3>
        </div>
        <form id="csomagolasform" class="js-csomagolas" action="/admin/csomagolasilista/save?id={$egyed.id|escape:'url'}" method="post"
              data-printurl="/admin/csomagolasilista/print?id={$egyed.id|escape:'url'}">
            <div class="mattkarb-page csomagolas-oldal">
                <div>
                    <label>{at('Bizonylat')}:</label>
                    {if ($egyed.listaurl)}
                        <a href="{$egyed.listaurl}" target="_blank">{$egyed.tipusnev} {$egyed.id}</a>
                    {else}
                        {$egyed.tipusnev} {$egyed.id}
                    {/if}
                    &nbsp;{$egyed.partnernev|escape}&nbsp;{at('kelt')}: {$egyed.keltstr}
                </div>

                <fieldset class="mattkarb-doboz">
                    <legend>{at('1. Melyik darab melyik dobozba került')}</legend>
                    <p class="mattkarb-hint">{at('Minden darabhoz a doboz számát írja be. A "Mind" mezővel a sor összes darabja egyszerre kitölthető.')}</p>
                    <table class="csomagolas-tetelek">
                        <thead>
                        <tr>
                            <th>{at('Cikkszám')}</th>
                            <th>{at('Termék')}</th>
                            <th>{at('Méret')}</th>
                            <th class="textalignright">{at('Mennyiség')}</th>
                            <th>{at('Mind')}</th>
                            <th>{at('Dobozszám darabonként')}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $tetelek as $_tetel}
                            <tr class="js-csomagtetel" data-suly="{$_tetel.suly}" data-darabonkent="{if ($_tetel.darabonkent)}1{else}0{/if}"
                                data-mennyiseg="{$_tetel.mennyiseg}">
                                <td>{$_tetel.cikkszam|escape}</td>
                                <td>{$_tetel.nev|escape}</td>
                                <td>{$_tetel.meret|escape}</td>
                                <td class="textalignright">{$_tetel.mennyiseg*1}</td>
                                <td><input class="js-csomagmind csomagolas-szam" type="number" min="1" step="1"></td>
                                <td class="csomagolas-darabok">
                                    {foreach $_tetel.mezok as $_szam}
                                        <input class="js-csomagdarab csomagolas-szam" name="doboz_{$_tetel.id}[]" type="number" min="1" step="1"
                                               value="{$_szam}"{if (!$_tetel.darabonkent)} title="{at('A teljes mennyiség egy dobozba')}"{/if}>
                                    {/foreach}
                                </td>
                            </tr>
                        {foreachelse}
                            <tr><td colspan="6">{at('A bizonylaton nincs dobozba tehető (készletet mozgató) tétel.')}</td></tr>
                        {/foreach}
                        </tbody>
                    </table>
                    <div class="js-csomaghianyzik redtext"></div>
                </fieldset>

                <fieldset class="mattkarb-doboz">
                    <legend>{at('2. Dobozok')}</legend>
                    <p class="mattkarb-hint">{at('A nettó súlyt a tételek súlyából előre kitöltjük, felülírható. Súly kg-ban, méret cm-ben.')}</p>
                    <table class="csomagolas-dobozok">
                        <thead>
                        <tr>
                            <th>{at('Doboz')}</th>
                            <th>{at('Nettó kg')}</th>
                            <th>{at('Bruttó kg')}</th>
                            <th>{at('Szélesség')}</th>
                            <th>{at('Magasság')}</th>
                            <th>{at('Mélység')}</th>
                        </tr>
                        </thead>
                        <tbody class="js-csomagdobozok">
                        {foreach $dobozok as $_doboz}
                            <tr class="js-csomagdoboz" data-szam="{$_doboz.dobozszam}">
                                <td>{$_doboz.dobozszam}</td>
                                <td><input class="js-csomagnetto" name="dobozadat[{$_doboz.dobozszam}][nettosuly]" type="number" step="any" min="0"{if ($_doboz.nettosuly !== null)} value="{$_doboz.nettosuly*1}" data-kezi="1"{/if}></td>
                                <td><input name="dobozadat[{$_doboz.dobozszam}][bruttosuly]" type="number" step="any" min="0" value="{if ($_doboz.bruttosuly !== null)}{$_doboz.bruttosuly*1}{/if}"></td>
                                <td><input name="dobozadat[{$_doboz.dobozszam}][szelesseg]" type="number" step="any" min="0" value="{if ($_doboz.szelesseg !== null)}{$_doboz.szelesseg*1}{/if}"></td>
                                <td><input name="dobozadat[{$_doboz.dobozszam}][magassag]" type="number" step="any" min="0" value="{if ($_doboz.magassag !== null)}{$_doboz.magassag*1}{/if}"></td>
                                <td><input name="dobozadat[{$_doboz.dobozszam}][melyseg]" type="number" step="any" min="0" value="{if ($_doboz.melyseg !== null)}{$_doboz.melyseg*1}{/if}"></td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="admin-form-footer">
                <button type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Mentés')}</button>
                <a href="#" class="js-csomagnyomtat ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Mentés és nyomtatás')}</a>
            </div>
        </form>
    </div>
{/block}
