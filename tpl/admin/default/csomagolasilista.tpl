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
                    &nbsp;{$egyed.partnernev}&nbsp;{at('kelt')}: {$egyed.keltstr}
                </div>

                <fieldset class="mattkarb-doboz">
                    <legend>{at('1. Melyik tételből mennyi került az egyes dobozokba')}</legend>
                    <p class="mattkarb-hint">{at('Tételenként a doboz számát és a beletett mennyiséget írja be. Ha a tétel több dobozba kerül, a + gomb alá új sort ad a még ki nem osztott mennyiséggel. Enterrel a következő tétel dobozszámára ugrik.')}</p>
                    <table class="csomagolas-tetelek">
                        <thead>
                        <tr>
                            <th>{at('Cikkszám')}</th>
                            <th>{at('Termék')}</th>
                            <th>{at('Szín')}</th>
                            <th>{at('Méret')}</th>
                            <th class="textalignright">{at('Mennyiség')}</th>
                            <th>{at('Doboz × mennyiség')}</th>
                            <th class="textalignright">{at('Maradék')}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $tetelek as $_tetel}
                            <tr class="js-csomagtetel" data-id="{$_tetel.id}" data-suly="{$_tetel.suly}" data-mennyiseg="{$_tetel.mennyiseg}">
                                <td>{$_tetel.cikkszam}</td>
                                <td>{$_tetel.nev}</td>
                                <td>{$_tetel.szin}</td>
                                <td>{$_tetel.meret}</td>
                                <td class="textalignright">{$_tetel.mennyiseg*1}</td>
                                <td>
                                    <div class="csomagolas-parok">
                                        {foreach $_tetel.parok as $_par}
                                            <span class="js-csomagpar csomagolas-par">
                                                <input class="js-csomagpardoboz csomagolas-szam" name="tetel[{$_tetel.id}][{$_par@index}][doboz]" type="number" min="1" step="1"
                                                       value="{$_par.doboz}" title="{at('Doboz')}">
                                                ×
                                                <input class="js-csomagpardb csomagolas-szam" name="tetel[{$_tetel.id}][{$_par@index}][db]" type="number" min="0" step="any"
                                                       value="{if ($_par.db !== '')}{$_par.db*1}{/if}" title="{at('Mennyiség')}">
                                                <button type="button" class="js-csomagpardel csomagolas-gomb ui-button ui-widget ui-state-default ui-corner-all" title="{at('Töröl')}"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></button>
                                                <button type="button" class="js-csomagparadd csomagolas-gomb ui-button ui-widget ui-state-default ui-corner-all" title="{at('Újabb doboz ez alá')}"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-plus"></span></span></button>
                                            </span>
                                        {/foreach}
                                    </div>
                                </td>
                                <td class="textalignright js-csomagmaradek"></td>
                            </tr>
                        {foreachelse}
                            <tr><td colspan="7">{at('A bizonylaton nincs dobozba tehető (készletet mozgató) tétel.')}</td></tr>
                        {/foreach}
                        </tbody>
                    </table>
                    <div class="js-csomaghianyzik redtext"></div>
                </fieldset>

                <fieldset class="mattkarb-doboz">
                    <legend>{at('2. Dobozok')}</legend>
                    <p class="mattkarb-hint">{at('A nettó súlyt a tételek súlyából előre kitöltjük, felülírható. Súly kg-ban, méret cm-ben. A törlés a dobozba tett tételeket is kiveszi.')}</p>
                    <table class="csomagolas-dobozok">
                        <thead>
                        <tr>
                            <th>{at('Doboz')}</th>
                            <th>{at('Nettó kg')}</th>
                            <th>{at('Bruttó kg')}</th>
                            <th>{at('Szélesség')}</th>
                            <th>{at('Magasság')}</th>
                            <th>{at('Mélység')}</th>
                            <th></th>
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
                                <td><button type="button" class="js-csomagdobozdel csomagolas-gomb ui-button ui-widget ui-state-default ui-corner-all" title="{at('Töröl')}"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></button></td>
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
