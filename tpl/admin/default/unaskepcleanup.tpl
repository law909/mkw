{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/unaskepcleanup.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('UNAS képtakarítás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Takarítás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {if ($figyelmeztetes)}
                    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {$figyelmeztetes}
                    </div>
                {/if}
                {if ($fut)}
                    <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin-bottom:5px;">
                        {at('Jelenleg fut egy UNAS import. A törlés a végéig nem indítható.')}
                    </div>
                {/if}

                <div style="margin:5px 0;">
                    {at('A képimport az azonos tartalmú képet nem köti a termékhez, de a fájlt a lemezen hagyja. Itt gyűlnek a régi néven letöltött és a törölt termékek képei is. Ez a művelet azokat a fájlokat keresi meg, amikre az adatbázisból semmi nem hivatkozik.')}
                </div>
                <div style="margin:5px 0;">
                    <strong>{at('Mappa')}:</strong> {$mappa}
                </div>
                <div class="matt-hseparator"></div>

                <form id="unaskepcleanup" method="post" action="/admin/unaskepcleanup/futtat"
                      data-hibauzenet="{at('A takarítás nem futott le. Nézze meg a hibanaplót, majd próbálja újra.')}"
                      data-kerdes="{at('Biztosan törli az árva fájlokat? A művelet nem vonható vissza.')}">
                    <div>
                        <label for="ForceEdit">{at('Akkor is töröljön, ha egyetlen hivatkozást sem talált')}:</label>
                        <input id="ForceEdit" name="force" type="checkbox">
                        <span>{at('Enélkül a takarítás ilyenkor leáll: sokkal valószínűbb a rossz beállítás, mint hogy tényleg minden kép árva.')}</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div class="admin-form-footer">
                        <button type="submit" name="torles" value=""
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Számolás (nem töröl)')}</button>
                        <button type="submit" name="torles" value="1" id="unaskepcleanuptorles"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Árva fájlok törlése')}</button>
                    </div>
                </form>

                <div id="unaskepcleanupfolyamat" style="display:none;margin:5px 0;">
                    {at('Az adatbázis átnézése folyamatban, ez eltarthat egy ideig...')}
                </div>

                <div id="unaskepcleanuperedmeny"></div>
            </div>
        </div>
    </div>
{/block}
