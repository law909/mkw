<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('MNR Navigáció')}</h3>
    <h4><a href="{$mainurl}/mnrnavigation/{$egyed.id}" target="_blank">{$egyed.nev}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/mnrnavigation/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            {if ($setup.multilang)}
                <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            {/if}
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
                    <td><label for="SzamEdit">{at('Szám')}:</label></td>
                    <td colspan="3"><input id="SzamEdit" name="szam" type="text" size="83" maxlength="255"
                                           value="{$egyed.szam}"></td>
                </tr>
                <tr>
                    <td><label for="SzlogenEdit">{at('Szlogen')}:</label></td>
                    <td colspan="3"><input id="SzlogenEdit" name="szlogen" type="text" size="83" maxlength="255"
                                           value="{$egyed.szlogen}"></td>
                </tr>
                <tr>
                    <td><label for="">{at('Statikus lap')}:</label></td>
                    <td>
                        <select name="mnrstatic">
                            <option value="">válasszon</option>
                            {foreach $mnrstaticlist as $mnrstatic}
                                <option value="{$mnrstatic.id}"{if ($mnrstatic.selected)} selected="selected"{/if}>{$mnrstatic.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        {if ($setup.multilang)}
            <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.translations as $translation}
                    {include 'translationkarb.tpl'}
                {/foreach}
                <a class="js-translationnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
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