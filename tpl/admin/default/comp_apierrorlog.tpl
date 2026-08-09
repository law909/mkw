{* API hibapostafiók. A "Rendben" gombot a js/admin/default/appinit.js kezeli:
   a sort helyben törli, a js- osztályok az ő fogódzói. *}
{if ($apierrorlog)}
    <div class="ui-widget ui-widget-content ui-corner-all js-apierrorlog">
        <div class="ui-widget-header ui-corner-top">
            <div class="mainboxinner ui-corner-top">{at('API hibák')} (<span class="js-apierrorlogcount">{$apierrorlog|@count}</span>)</div>
        </div>
        <div class="mainboxinner">
            <table style="width:100%;border-collapse:collapse;">
                <tbody>
                {foreach $apierrorlog as $_hiba}
                    <tr>
                        <td style="padding:2px 5px;white-space:nowrap;">{$_hiba.created}</td>
                        <td style="padding:2px 5px;">{$_hiba.type}</td>
                        <td style="padding:2px 5px;">{$_hiba.objectid}</td>
                        <td style="padding:2px 5px;white-space:nowrap;">
                            {if ($_hiba.bizonylatlink)}
                                <a href="{$_hiba.bizonylatlink|escape}" target="_blank"
                                   title="{at('Ugrás a bizonylathoz')}">{$_hiba.bizonylatszam}</a>
                            {else}
                                {$_hiba.bizonylatszam}
                            {/if}
                        </td>
                        <td class="redtext" style="padding:2px 5px;">{$_hiba.message}</td>
                        <td style="padding:2px 5px;white-space:nowrap;">
                            <a href="#" class="js-apierrorlogclose" data-id="{$_hiba.id}">{at('Rendben')}</a>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
{/if}
