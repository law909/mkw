{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/raktarkeszletnullazo.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Raktár készlet nullázás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Nullázás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="raktarkeszletnullazo" method="post" action="/admin/raktarkeszletnullazo/process">
                    <table>
                        <tbody>
                        <tr>
                            <td><label for="RaktarEdit">{at('Raktár')}:</label></td>
                            <td>
                                <select id="RaktarEdit" name="raktar" required>
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $raktarlist as $_raktar}
                                        <option value="{$_raktar.id}"{if ($_raktar.selected)} selected="selected"{/if}>{$_raktar.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="admin-form-footer">
                        <input type="submit" value="{at('Készlet nullázása')}"
                               onclick="return confirm('{at('Biztosan lefuttatja a készlet nullázást a kiválasztott raktárra?')}');">
                    </div>
                </form>
                {if ($result)}
                    <div class="matt-hseparator"></div>
                    <div>
                        <strong>{if ($result.success)}{at('Siker')}{else}{at('Hiba')}{/if}:</strong> {$result.message}
                    </div>
                    {if ($result.success) && ($result.bevetid || $result.kivetid)}
                        <div>{at('Bevét bizonylat')}: {$result.bevetid|default:'-'}, {at('tételek')}: {$result.bevetdb|default:0}</div>
                        <div>{at('Kivét bizonylat')}: {$result.kivetid|default:'-'}, {at('tételek')}: {$result.kivetdb|default:0}</div>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
{/block}
