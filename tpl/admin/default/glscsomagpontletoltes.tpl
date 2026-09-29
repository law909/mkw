{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/glscsomagpontletoltes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('GLS csomagpont letöltés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Letöltés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {if (!$vanurl)}
                    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {at('Nincs megadva a GLS csomagpont URL (Beállítások, "Azonosítók, kódok" fül).')}
                    </div>
                {/if}
                <div style="margin:5px 0;">
                    {at('A GLS csomagpont listát tölti le, és frissíti vele a csomagpont törzset: az új pontokat felveszi, a meglévőket frissíti, a listából kimaradtakat inaktiválja. A webshop és az UNAS rendelés import innen párosítja a csomagpontot.')}
                </div>
                <div style="margin:5px 0;">
                    <strong>{at('Csomagpontok a törzsben')}:</strong>
                    <span class="js-glsaktiv">{$stat.aktiv}</span> {at('aktív')},
                    <span class="js-glsinaktiv">{$stat.inaktiv}</span> {at('inaktív')}
                </div>
                <div class="matt-hseparator"></div>

                <form id="glscsomagpontletoltes" method="post" action="/admin/import/glsterminal"
                      data-hibauzenet="{at('A letöltés nem futott le. Nézze meg a hibanaplót, majd próbálja újra.')}"
                      data-kesz="{at('Kész: %1 csomagpont a listában, %2 inaktiválva.')}">
                    <div class="admin-form-footer">
                        <button type="submit"{if (!$vanurl)} disabled="disabled"{/if}
                                class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Letöltés')}</button>
                    </div>
                </form>

                <div class="js-glsfolyamat" style="display:none;margin:5px 0;">
                    {at('Letöltés folyamatban, ez eltarthat egy ideig...')}
                </div>
                <div class="js-glseredmeny"></div>
            </div>
        </div>
    </div>
{/block}
