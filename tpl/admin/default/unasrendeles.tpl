{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/unasrendeles.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('UNAS megrendelések')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Állapot')}</a></li>
                <li><a href="#LekepezesTab">{at('Leképezés')}</a></li>
                <li><a href="#OutboxTab">{at('Visszaírás')}</a></li>
            </ul>

            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {if ($figyelmeztetes)}
                    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {$figyelmeztetes}
                    </div>
                {/if}

                <div style="margin:5px 0;">
                    {at('A rendeléseket a /admin/cron végpont hozza le, az utolsó módosítás időpontja szerint – a végpontot kívülről kell hívni (crontab), és 5 percen belül másodszor nem fut le. A letöltés soha nem változtatja meg a rendelés UNAS-beli állapotát.')}
                </div>
                <table class="ui-widget ui-widget-content ui-corner-all unastable">
                    <tbody>
                    <tr>
                        <td>{at('Import kurzor (utolsó hibátlan futás)')}:</td>
                        <td id="unaskurzor">{$kurzor}</td>
                    </tr>
                    <tr>
                        <td>{at('Utolsó cron futás')}:</td>
                        <td>{$utolsocron}</td>
                    </tr>
                    <tr>
                        <td>{at('Órás hívásszám')}:</td>
                        <td>
                            {foreach $ratelimit as $_vegpont => $_db}{$_vegpont|escape}: {$_db|escape}{if !$_db@last} | {/if}{/foreach}
                            <span>({at('egy tételes + több tételes hívások, külön korláttal')})</span>
                        </td>
                    </tr>
                    <tr>
                        <td>{at('Visszaírás')}:</td>
                        <td>
                            {at('státusz')}: {if ($visszairas.statusz)}{at('be')}{else}{at('ki')}{/if} |
                            {at('számla')}: {if ($visszairas.szamla)}{at('be')}{else}{at('ki')}{/if} |
                            {at('csomagszám')}: {if ($visszairas.csomag)}{at('be')}{else}{at('ki')}{/if}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="matt-hseparator"></div>
                <form id="unasrendelespoll" method="post" action="/admin/unasrendeles/poll"
                      data-hibauzenet="{at('A lehúzás nem futott le. Nézze meg a hibanaplót, majd próbálja újra.')}">
                    <div>
                        <label for="CsakLetoltesEdit">{at('Csak letöltés importálás nélkül')}:</label>
                        <input id="CsakLetoltesEdit" name="csakletoltes" type="checkbox">
                        <span>{at('A nyers XML a storage/logs mappába kerül, bizonylat nem készül, és a kurzor sem lép – a következő igazi lehúzás ugyanezeket hozza.')}</span>
                    </div>
                    <div class="admin-form-footer">
                        <button type="submit"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Új rendelések lehúzása most')}</button>
                    </div>
                </form>

                <form id="unasrendelesimport" method="post" action="/admin/unasrendeles/import"
                      data-hibauzenet="{at('Az import nem futott le. Nézze meg a hibanaplót, majd próbálja újra.')}">
                    <div>
                        <label for="UnasKeyEdit">{at('Rendelés azonosító (Key)')}:</label>
                        <input id="UnasKeyEdit" name="unaskey" type="text" size="30" autocomplete="off">
                        <button type="submit"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Import')}</button>
                    </div>
                </form>

                <form id="unasrendeleskurzor" method="post" action="/admin/unasrendeles/kurzor"
                      data-hibauzenet="{at('A kurzor mentése nem sikerült.')}"
                      data-kerdes="{at('Biztosan átállítja a kurzort? A korábbi rendelések újra sorra kerülnek.')}">
                    <div>
                        <label for="UnasKurzorEdit">{at('Kurzor átállítása (dátum)')}:</label>
                        <input id="UnasKurzorEdit" name="kurzor" type="text" size="14" value="{$kurzorinput}"
                               autocomplete="off">
                        <button type="submit"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Mentés')}</button>
                        <span>{at('Üresen hagyva az első futás az utolsó egy hétre néz vissza.')}</span>
                    </div>
                </form>

                <div id="unasrendelesfolyamat" style="display:none;margin:5px 0;">
                    {at('Folyamatban, ez eltarthat egy ideig...')}
                </div>
                <div id="unasrendeleseredmeny"></div>
            </div>

            <div id="LekepezesTab" class="mattkarb-page" data-visible="visible">
                {if ($kezelesiktgfigyelmeztetes)}
                    <div id="unaskezelesiktgfigyelmeztetes"
                         class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {$kezelesiktgfigyelmeztetes}
                    </div>
                {else}
                    <div id="unaskezelesiktgfigyelmeztetes"
                         class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;display:none;"></div>
                {/if}

                <div style="margin:5px 0;">
                    {at('Az UNAS státuszai és fizetési / szállítási módjai boltonként szabadon konfigurálhatók, ezért az összerendelést a bolt saját listájából generáljuk. Leképezés nélkül a rendelés a Beállítások → UNAS fülön megadott tartalékokra esik.')}
                </div>
                <form id="unasrendeleslekepezes" method="post" action="/admin/unasrendeles/lekepezesmentes"
                      data-betoltes="/admin/unasrendeles/lekepezes"
                      data-hibauzenet="{at('A leképezés betöltése nem sikerült. Nézze meg a hibanaplót, majd próbálja újra.')}">
                    <div class="admin-form-footer">
                        <button type="button" id="unaslekepezesbetolt"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Leképezések betöltése az UNAS-ból')}</button>
                        <button type="submit" id="unaslekepezesmentes" style="display:none;"
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Mentés')}</button>
                    </div>
                    <div id="unaslekepezestartalom"></div>
                </form>
            </div>

            <div id="OutboxTab" class="mattkarb-page" data-visible="visible">
                <div style="margin:5px 0;">
                    {at('A bizonylat státuszváltása, a belőle képzett számla és a rögzített csomagszám kerül vissza az UNAS-ba. A sorokat a cron küldi ki; ami 5 próbálkozás után sem megy át, hibás lesz és a hibapostafiókba is bekerül.')}
                </div>
                <div class="admin-form-footer">
                    <button type="button" id="unasoutboxfuttat" data-href="/admin/unasrendeles/outboxfuttat"
                            data-hibauzenet="{at('A visszaírás nem futott le.')}"
                            class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Kimenő sor küldése most')}</button>
                    <button type="button" id="unasoutboxfrissit" data-href="/admin/unasrendeles/outbox"
                            class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Lista frissítése')}</button>
                </div>
                <div id="unasoutboxtartalom" data-href="/admin/unasrendeles/outbox"
                     data-ujrahref="/admin/unasrendeles/outboxujra"></div>
            </div>
        </div>
    </div>
{/block}
