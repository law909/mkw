<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('MNR Statikus menü')}</h3>
    <h4><a href="{$mainurl}/mnrstatic/{$egyed.id}" target="_blank">{$egyed.nev}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/mnrstatic/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            {if ($setup.multilang)}
                <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            {/if}
            <li><a href="#MNRStaticPageTab">{at('Oldalak')}</a></li>
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
                    <td><label for="Szlogen1Edit">{at('Szlogen 1')}:</label></td>
                    <td colspan="3"><input id="Szlogen1Edit" name="szlogen1" type="text" size="83" maxlength="255"
                                           value="{$egyed.szlogen1}"></td>
                </tr>
                <tr>
                    <td><label for="Szlogen2Edit">{at('Szlogen 2')}:</label></td>
                    <td colspan="3"><input id="Szlogen2Edit" name="szlogen2" type="text" size="83" maxlength="255"
                                           value="{$egyed.szlogen2}"></td>
                </tr>
                </tbody>
            </table>
            {include 'mnrstaticimagekarb.tpl'}
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
        <div id="MNRStaticPageTab" class="mattkarb-page" data-visible="visible">
            <a class="js-mnrstaticpagedelallbutton" href="#" title="{at('Mind törlése')}"
               data-mnrstaticid="{$egyed.id}"><span class="ui-button-text">{at('Mind törlése')}</span></a>
            {foreach $egyed.valtozatok as $valtozat}
                {include 'mnrstaticmnrstaticpagekarb.tpl'}
            {/foreach}
            <a class="js-mnrstaticpagenewbutton" href="#" title="{at('Új')}" data-mnrstaticid="{$egyed.id}"><span
                    class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>