{* Pubadminban eladott, de ki nem számlázott órajegy/bérlet. A "Megoldva" gombot a
   js/admin/darshan/appinit.js kezeli: a sort helyben törli, a js- osztályok az ő fogódzói. *}
{if ($szamlazatlaneladas)}
    <div class="ui-widget ui-widget-content ui-corner-all js-szamlazatlaneladas">
        <div class="ui-widget-header ui-corner-top">
            <div class="mainboxinner ui-corner-top">{at('Számla nélküli eladások')}
                (<span class="js-szamlazatlancount">{$szamlazatlaneladas|@count}</span>)
            </div>
        </div>
        <div class="mainboxinner">
            <table style="width:100%;border-collapse:collapse;">
                <tbody>
                {foreach $szamlazatlaneladas as $_sor}
                    <tr>
                        <td style="padding:2px 5px;white-space:nowrap;">{$_sor.created}</td>
                        <td style="padding:2px 5px;">{$_sor.dolgozonev}</td>
                        <td style="padding:2px 5px;">
                            {if ($_sor.partnerlink)}
                                <a href="{$_sor.partnerlink|escape}" target="_blank"
                                   title="{at('Ugrás a partnerhez')}">{$_sor.partnernev}</a>
                            {else}
                                {$_sor.partnernev}
                            {/if}
                            <div>{$_sor.partneremail}</div>
                        </td>
                        <td style="padding:2px 5px;">{$_sor.megnevezes}</td>
                        <td style="padding:2px 5px;white-space:nowrap;text-align:right;">{bizformat($_sor.osszeg)}</td>
                        <td class="redtext" style="padding:2px 5px;">{$_sor.oka}</td>
                        <td style="padding:2px 5px;white-space:nowrap;">
                            <a href="#" class="js-szamlazatlanmegoldva" data-id="{$_sor.id}">{at('Megoldva')}</a>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
{/if}
