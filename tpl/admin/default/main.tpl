{extends "base.tpl"}

{block "kozep"}
    <div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Árfolyamok</div>
            </div>
            <div class="mainboxinner">
                <label for="ArfolyamDatumEdit">Dátum:</label>
                <input id="ArfolyamDatumEdit" name="datum" type="text" data-datum="{$today}">
                <button class="js-arfolyamdownload ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Letölt</span></button>
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Egy raktárban nincs, de a többiben van</div>
            </div>
            <div class="mainboxinner">
                <form action="/admin/lista/boltbannincsmasholvan" target="_blank">
                <label for="RaktarEdit">Raktár, amelyikben nincs:</label>
                    <select id="RaktarEdit" name="raktar">
                        <option value="">{t('válasszon')}</option>
                        {foreach $raktarlist as $_mk}
                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                    <input type="submit" class="ui-widget ui-button ui-state-default ui-corner-all" value="OK">
                </form>
            </div>
        </div>
    </div>
    <div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Napi jelentés</div>
            </div>
            <div class="mainboxinner">
                <table>
                    <thead>
                        <tr>
                            <th class="headercell">Csoport</th>
                            <th class="headercell">Mennyiség</th>
                            <th class="headercell">Nettó HUF</th>
                            <th class="headercell">Bruttó HUF</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$summenny = 0}
                        {$sumnetto = 0}
                        {$sumbrutto = 0}
                        {foreach $napijelenteslista as $elem}
                            {$summenny = $summenny + $elem.mennyiseg}
                            {$sumnetto = $sumnetto + $elem.netto}
                            {$sumbrutto = $sumbrutto + $elem.brutto}
                            <tr>
                                <td class="datacell">{$elem.caption}</td>
                                <td class="textalignright datacell">{number_format($elem.mennyiseg, 0, ',', ' ')}</td>
                                <td class="textalignright datacell">{number_format($elem.netto, 2, ',', ' ')}</td>
                                <td class="textalignright datacell">{number_format($elem.brutto, 2, ',', ' ')}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="headercell">Összesen</td>
                            <td class="textalignright headercell">{number_format($summenny, 0, ',', ' ')}</td>
                            <td class="textalignright headercell">{number_format($sumnetto, 2, ',', ' ')}</td>
                            <td class="textalignright headercell">{number_format($sumbrutto, 2, ',', ' ')}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
{/block}