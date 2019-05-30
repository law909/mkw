<div id="mattkarb-header">
    <h3>{at('Helyettesítés')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/orarendhelyettesites/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="ElmaradCheck" name="elmarad" type="checkbox"
                   {if ($egyed.elmarad)}checked="checked"{/if}>{at('Elmarad')}
            <table>
                <tbody>
                <tr>
                    {if ($oper !== 'edit')}
                    <td><label for="DatumEdit">{at('Dátum')}:</label></td>
                    <td><input id="DatumEdit" name="datum" type="text" required></td>
                    {else}
                        <td>{at('Dátum')}:</td>
                        <td>{$egyed.datum}</td>
                    {/if}
                </tr>
                <tr>
                    {if ($oper !== 'edit')}
                    <td><label for="OrarendEdit">{at('Óra')}:</label></td>
                    <td><select id="OrarendEdit" name="orarend" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $orarendlist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                    {else}
                        <td>{at('Óra')}:</td>
                        <td>{$egyed.oranev}</td>
                    {/if}
                </tr>
                <tr>
                    <td><label for="HelyettesitoEdit">{at('Helyettesítő')}:</label></td>
                    <td><select id="HelyettesitoEdit" name="helyettesito">
                            <option value="">{at('válasszon')}</option>
                            {foreach $helyettesitolist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>