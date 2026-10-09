<div id="mattkarb-header">
    <h3>{at('Blokk')}</h3>
    <h4>{$blokk.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/blokk/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#Blokk1Tab">{at('Blokk 1')}</a></li>
            <li><a href="#Blokk2Tab">{at('Blokk 2')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$blokk.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" value="{$blokk.sorrend}">
                {/mezo}
                {mezo cimke="Látható" for="LathatoEdit"}
                    <input id="LathatoEdit" name="lathato" type="checkbox"{if ($blokk.lathato)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Típus" for="TipusEdit"}
                    <select id="TipusEdit" name="tipus">
                        {foreach $tipuslist as $key => $val}
                            <option value="{$key}"{if ($blokk.tipus == $key)} selected="selected"{/if}>{at($val)}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="CSS class" for="ClassEdit"}
                    <input id="ClassEdit" name="cssclass" type="text" size="80" maxlength="255" value="{$blokk.cssclass}">
                {/mezo}
                {mezo cimke="CSS style" for="StyleEdit"}
                    <input id="StyleEdit" name="cssstyle" type="text" size="80" maxlength="255" value="{$blokk.cssstyle}">
                {/mezo}
                {mezo cimke="Blokk magasság" for="BlokkmagassagEdit"}
                    <select id="BlokkmagassagEdit" name="blokkmagassag">
                        {foreach $blokkmagassaglist as $key => $val}
                            <option value="{$key}"{if ($blokk.blokkmagassag == $key)} selected="selected"{/if}>{at($val)}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="Blokk1Tab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Háttérkép url" for="HatterkepurlEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="HatterkepurlEdit" name="hatterkepurl" type="text" size="70" maxlength="255" value="{$blokk.hatterkepurl}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="HatterkepurlEdit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="HatterkepurlEdit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </div>
                {/mezo}
                {mezo cimke="Video url" for="VideourlEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="VideourlEdit" name="videourl" type="text" size="70" maxlength="255" value="{$blokk.videourl}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="VideourlEdit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="VideourlEdit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </div>
                {/mezo}
                {mezo cimke="Cím" for="CimEdit"}
                    <input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$blokk.cim}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras">{$blokk.leiras}</textarea>
                {/mezo}
                {mezo cimke="Gomb felirat" for="GombfeliratEdit"}
                    <input id="GombfeliratEdit" name="gombfelirat" type="text" size="80" maxlength="255" value="{$blokk.gombfelirat}">
                {/mezo}
                {mezo cimke="Gomb url" for="GomburlEdit"}
                    <input id="GomburlEdit" name="gomburl" type="text" size="80" maxlength="255" value="{$blokk.gomburl}">
                {/mezo}
                {mezo cimke="Szöveg igazítás" for="SzovegigazitasEdit"}
                    <select id="SzovegigazitasEdit" name="szovegigazitas">
                        {foreach $szovegigazitaslist as $key => $val}
                            <option value="{$key}"{if ($blokk.szovegigazitas == $key)} selected="selected"{/if}>{at($val)}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="Blokk2Tab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Háttérkép url 2" for="Hatterkepurl2Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Hatterkepurl2Edit" name="hatterkepurl2" type="text" size="70" maxlength="255" value="{$blokk.hatterkepurl2}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="Hatterkepurl2Edit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="Hatterkepurl2Edit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </div>
                {/mezo}
                {mezo cimke="Video url 2" for="Videourl2Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Videourl2Edit" name="videourl2" type="text" size="70" maxlength="255" value="{$blokk.videourl2}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="Videourl2Edit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="Videourl2Edit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </div>
                {/mezo}
                {mezo cimke="Cím 2" for="Cim2Edit"}
                    <input id="Cim2Edit" name="cim2" type="text" size="80" maxlength="255" value="{$blokk.cim2}">
                {/mezo}
                {mezo cimke="Leírás 2" for="Leiras2Edit" szeles=true}
                    <textarea id="Leiras2Edit" name="leiras2">{$blokk.leiras2}</textarea>
                {/mezo}
                {mezo cimke="Gomb felirat 2" for="Gombfelirat2Edit"}
                    <input id="Gombfelirat2Edit" name="gombfelirat2" type="text" size="80" maxlength="255" value="{$blokk.gombfelirat2}">
                {/mezo}
                {mezo cimke="Gomb url 2" for="Gomburl2Edit"}
                    <input id="Gomburl2Edit" name="gomburl2" type="text" size="80" maxlength="255" value="{$blokk.gomburl2}">
                {/mezo}
                {mezo cimke="Szöveg igazítás 2" for="Szovegigazitas2Edit"}
                    <select id="Szovegigazitas2Edit" name="szovegigazitas2">
                        {foreach $szovegigazitaslist as $key => $val}
                            <option value="{$key}"{if ($blokk.szovegigazitas2 == $key)} selected="selected"{/if}>{at($val)}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$blokk.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
