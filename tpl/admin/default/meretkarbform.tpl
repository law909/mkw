<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('Méret')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/meret/save" data-id="{$egyed.id}">
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
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td colspan="3"><input id="SorrendEdit" name="sorrend" type="number" size="6" maxlength="6" step="any"
                                           value="{$egyed.sorrend}"></td>
                </tr>
                <tr>
                    {include 'termekimagekarb.tpl'}
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
