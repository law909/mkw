<div id="mattkarb-header">
    <h3>{at('Méret sor')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/meretsor/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td class="colspan"><label>{at('Méretek')}:</label></td>
                    <td colspan="3">
                        <div class="mattkarb-checklist">
                            {foreach $meretek as $_meret}
                                <label class="mattkarb-checkitem">
                                    <input type="checkbox" name="meretek[]" value="{$_meret->getId()}"
                                           {if in_array($_meret->getId(), $egyed.meretids)}checked{/if}>
                                    {$_meret->getNev()}
                                </label>
                                <br>
                            {/foreach}
                        </div>
                    </td>
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