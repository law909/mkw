{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/bizonylatellenorzes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Tételek ellenőrzése')} – {$egyed.tipusnev} {$egyed.id}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post" onsubmit="return false;">
            <div id="DefaTab" class="mattkarb-page js-ellenorzes" data-bizonylat="{$egyed.id}">
                <div>
                    {$egyed.partnernev|escape}, {at('kelt')}: {$egyed.keltstr}
                    {if ($egyed.listaurl)}<a href="{$egyed.listaurl}" target="_blank">{at('Ugrás a bizonylathoz')}</a>{/if}
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EllenorzesKeresoEdit">{at('Vonalkód / cikkszám / keresés')}:</label>
                    <input id="EllenorzesKeresoEdit" class="js-ellkereso" type="text" size="50" autocomplete="off" autofocus>
                    <label for="EllenorzesMennyisegEdit">{at('Mennyiség')}:</label>
                    <input id="EllenorzesMennyisegEdit" class="js-ellmennyiseg" type="number" step="any" value="1" size="6">
                    <span class="js-ellhiba redtext"></span>
                </div>
                <div class="js-ellvaltozat"></div>
                <div class="matt-hseparator"></div>
                <table class="js-elltabla">
                    <thead>
                    <tr>
                        <th class="headercell">{at('Cikkszám')}</th>
                        <th class="headercell">{at('Név')}</th>
                        <th class="headercell">{at('Változat')}</th>
                        <th class="headercell">{at('Vonalkód')}</th>
                        <th class="headercell textalignright">{at('Bizonylaton')}</th>
                        <th class="headercell textalignright">{at('Ellenőrzött')}</th>
                        <th class="headercell textalignright">{at('Eltérés')}</th>
                        <th class="headercell"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $tetelek as $tetel}
                        <tr class="js-ellsor" data-termekid="{$tetel.termekid}" data-valtozatid="{$tetel.valtozatid}" data-elvart="{$tetel.mennyiseg}">
                            <td class="datacell">{$tetel.cikkszam|escape}</td>
                            <td class="datacell">{$tetel.nev|escape}</td>
                            <td class="datacell">{$tetel.valtozatnev|escape}</td>
                            <td class="datacell">{$tetel.vonalkod|escape}</td>
                            <td class="datacell textalignright">{$tetel.mennyiseg|string_format:"%g"}</td>
                            <td class="datacell textalignright"><input class="js-ellszamolt" type="number" step="any" value="0" size="6"></td>
                            <td class="datacell textalignright js-ellelteres"></td>
                            <td class="datacell"></td>
                        </tr>
                    {/foreach}
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="datacell" colspan="4">{at('Összesen')}</td>
                        <td class="datacell textalignright js-ellosszelvart"></td>
                        <td class="datacell textalignright js-ellosszszamolt"></td>
                        <td class="datacell textalignright js-ellosszelteres"></td>
                        <td class="datacell"></td>
                    </tr>
                    </tfoot>
                </table>
                <div class="matt-hseparator"></div>
                <div class="js-ellosszegzes"></div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-ellujra">{at('Újrakezdés')}</a>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
