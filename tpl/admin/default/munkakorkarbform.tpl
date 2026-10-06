<div id="mattkarb-header">
    <h3>{at('Munkakör')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            {if (isset($egyed.menucsoportok))}
                <li><a href="#MenuTab">{at('Menüpontok')}</a></li>
            {/if}
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="JogEdit">{at('Jog')}:</label></td>
                    <td><input id="JogEdit" name="jog" type="number" step="1" value="{$egyed.jog}" required="required"></td>
                </tr>
                </tbody>
            </table>
        </div>
        {if (isset($egyed.menucsoportok))}
            <div id="MenuTab" class="mattkarb-page" data-visible="visible">
                <input type="hidden" name="menujogok" value="1">
                {foreach $egyed.menucsoportok as $_csoport}
                    <div class="munkakormenu-csoport js-munkakormenucsoport">
                        <div class="mattkarb-szakaszcim">
                            {$_csoport.nev}
                            <a href="#" class="munkakormenu-mind js-munkakormenumind">{at('mind / egyik sem')}</a>
                        </div>
                        <div class="munkakormenu-lista">
                            {foreach $_csoport.menuk as $_menu}
                                <label class="munkakormenu-pont{if (!$_menu.lathato)} munkakormenu-rejtett{/if}">
                                    {if ($_menu.mindenki)}
                                        <input type="checkbox" checked="checked" disabled="disabled"> {$_menu.nev}
                                        <span class="munkakormenu-megj">({at('mindenki eléri')})</span>
                                    {else}
                                        <input type="checkbox" name="menuk[]" value="{$_menu.id}"{if ($_menu.checked)} checked="checked"{/if}> {$_menu.nev}
                                    {/if}
                                    {if (!$_menu.lathato)}<span class="munkakormenu-megj">({at('rejtett')})</span>{/if}
                                </label>
                            {/foreach}
                        </div>
                    </div>
                {/foreach}
            </div>
        {/if}
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
