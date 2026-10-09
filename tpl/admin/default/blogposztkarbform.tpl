<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('Blogposzt')}</h3>
    <h4><a href="{$mainurl}/blog/{$egyed.slug}" target="_blank">{$egyed.cim}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/blogposzt/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TermekTab">{at('Termékek')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="LathatoCheck" name="lathato" type="checkbox"
                   {if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}
            {mezocsoport}
                {mezo cimke="Megjelenés dátuma" for="MegjelenesDatumEdit"}
                    <input id="MegjelenesDatumEdit" name="megjelenesdatum" type="text" size="12"
                        data-datum="{$egyed.megjelenesdatumstr}">
                {/mezo}
                {mezo cimke="Kategóriák"}
                    <span id="TermekKategoria1" class="js-termekfabutton" data-text="{at('válasszon')}"
                        data-name="termekfa1"
                        data-value="{$egyed.termekfa1}">{if ($egyed.termekfa1nev)}{$egyed.termekfa1nev}{else}{at('válasszon')}{/if}</span>
                    <span id="TermekKategoria2" class="js-termekfabutton" data-text="{at('válasszon')}"
                        data-name="termekfa2"
                        data-value="{$egyed.termekfa2}">{if ($egyed.termekfa2nev)}{$egyed.termekfa2nev}{else}{at('válasszon')}{/if}</span>
                    <span id="TermekKategoria3" class="js-termekfabutton" data-text="{at('válasszon')}"
                        data-name="termekfa3"
                        data-value="{$egyed.termekfa3}">{if ($egyed.termekfa3nev)}{$egyed.termekfa3nev}{else}{at('válasszon')}{/if}</span>
                {/mezo}
            {/mezocsoport}
            {mezocsoport}
                {mezo cimke="Cím" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="cim" type="text" size="83" maxlength="255"
                        value="{$egyed.cim}" required autofocus>
                {/mezo}
                {mezo cimke="Kivonat" for="RovidLeirasEdit"}
                    <input id="RovidLeirasEdit" name="kivonat" type="text" size="100" maxlength="255"
                        value="{$egyed.kivonat}">
                {/mezo}
                {mezo cimke="Szöveg" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="szoveg">{$egyed.szoveg}</textarea>
                {/mezo}
                {mezo cimke="META leírás" for="SeoDescriptionEdit" szeles=true}
                    <textarea id="SeoDescriptionEdit" name="seodescription"
                        cols="70">{$egyed.seodescription}</textarea>
                {/mezo}
            {/mezocsoport}
            <div>
                {include 'termekimagekarb.tpl'}
            </div>
        </div>
        <div id="TermekTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.termekek as $termek}
                {include 'blogposzttermekkarb.tpl'}
            {/foreach}
            <a class="js-termeknewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>