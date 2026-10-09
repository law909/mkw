<div id="mattkarb-header">
    <h3>{at('Szakmai anyag')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/mptngyszakmaianyag/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#BiralatTab">{at('Bírálók és bírálatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Azonosító"}
                    {$egyed.id}
                {/mezo}
                {mezo szeles=true}
                    <label for="veglegesEdit">{at('Beküldve')}:</label>
                    <input id="veglegesEdit" type="checkbox" name="vegleges"{if ($egyed.vegleges)} checked{/if}>
                    <label for="biralatkeszEdit">{at('Bírálat kész')}:</label>
                    <input id="biralatkeszEdit" type="checkbox" name="biralatkesz"{if ($egyed.biralatkesz)} checked{/if} disabled>
                    <label for="kszEdit">{at('Konferencián szerepelhet')}:</label>
                    <input id="kszEdit" type="checkbox" name="konferencianszerepelhet"{if ($egyed.konferencianszerepelhet)} checked{/if} disabled>
                {/mezo}
                {mezo cimke="Cím" for="CimEdit"}
                    <input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim|htmlentities}" required>
                {/mezo}
                {mezo cimke="Kezdés" for="kezdodatumEdit" szeles=true}
                    <div class="mattkarb-mezogomb">
                        <select id="kezdodatumEdit" name="kezdodatum">
                        <option value="">{at('válasszon')}</option>
                        {foreach $datumlist as $_mk}
                        <option
                            value="{$_mk.id}"
                        {if ($_mk.selected)} selected="selected"{/if}
                            >{$_mk.caption}</option>
                        {/foreach}
                        </select>
                        <input name="kezdoido" value="{$egyed.kezdoido}"> -
                        <input name="vegido" value="{$egyed.vegido}">
                    </div>
                {/mezo}
                {mezo cimke="Terem" for="teremEdit"}
                    <select id="teremEdit" name="terem">
                        <option value="">{at('válasszon')}</option>
                        {foreach $teremlist as $_mk}
                            <option
                                value="{$_mk.id}"
                                    {if ($_mk.selected)} selected="selected"{/if}
                                    {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                            >{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Típus" for="tipusEdit"}
                    <select id="tipusEdit" name="tipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tipuslist as $_mk}
                            <option
                                value="{$_mk.id}"
                                    {if ($_mk.selected)} selected="selected"{/if}
                                    {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                            >{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Tulajdonos" for="tulajdonosEdit"}
                    <select id="tulajdonosEdit" name="tulajdonos">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tulajdonoslist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Szerzők" for="szerzo1Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo1emailEdit" name="szerzo1email" type="email" value="{$egyed.szerzo1email}">
                        <select id="szerzo1Edit" name="szerzo1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo2emailEdit" name="szerzo2email" type="email" value="{$egyed.szerzo2email}">
                        <select id="szerzo2Edit" name="szerzo2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo3emailEdit" name="szerzo3email" type="email" value="{$egyed.szerzo3email}">
                        <select id="szerzo3Edit" name="szerzo3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo4emailEdit" name="szerzo4email" type="email" value="{$egyed.szerzo4email}">
                        <select id="szerzo4Edit" name="szerzo4">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo4list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo5emailEdit" name="szerzo5email" type="email" value="{$egyed.szerzo5email}">
                        <select id="szerzo5Edit" name="szerzo5">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo5list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo6emailEdit" name="szerzo6email" type="email" value="{$egyed.szerzo6email}">
                        <select id="szerzo6Edit" name="szerzo6">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo6list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo7emailEdit" name="szerzo7email" type="email" value="{$egyed.szerzo7email}">
                        <select id="szerzo7Edit" name="szerzo7">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo7list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo8emailEdit" name="szerzo8email" type="email" value="{$egyed.szerzo8email}">
                        <select id="szerzo8Edit" name="szerzo8">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo8list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo9emailEdit" name="szerzo9email" type="email" value="{$egyed.szerzo9email}">
                        <select id="szerzo9Edit" name="szerzo9">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo9list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="szerzo10emailEdit" name="szerzo10email" type="email" value="{$egyed.szerzo10email}">
                        <select id="szerzo10Edit" name="szerzo10">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo10list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo cimke="Egyéb szerzők" for="egyebszerzokEdit" szeles=true}
                    <textarea id="egyebszerzokEdit" name="egyebszerzok" rows="10" cols="80">{$egyed.egyebszerzok}</textarea>
                {/mezo}
                {mezo cimke="Eredeti egyéb szerzők" for="egyebszerzokorgEdit" szeles=true}
                    <textarea id="egyebszerzokorgEdit" rows="10" cols="80" disabled>{$egyed.egyebszerzokorg}</textarea>
                {/mezo}
                {mezo cimke="Opponens" for="opponensEdit" class="onlyszimpozium hidden"}
                    <div class="mattkarb-mezogomb">
                        <input id="opponensemailEdit" name="opponensemail" type="email" value="{$egyed.opponensemail}" class="onlyszimpozium hidden">
                        <select id="opponensEdit" name="opponens" class="onlyszimpozium hidden">
                            <option value="">{at('válasszon')}</option>
                            {foreach $opponenslist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo cimke="Előadás 1" for="eloadas1Edit" class="onlyszimpozium hidden"}
                    <select id="eloadas1Edit" name="eloadas1">
                        <option value="">{at('válasszon')}</option>
                        {foreach $eloadas1list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Előadás 2" for="eloadas2Edit" class="onlyszimpozium hidden"}
                    <select id="eloadas2Edit" name="eloadas2">
                        <option value="">{at('válasszon')}</option>
                        {foreach $eloadas2list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Előadás 3" for="eloadas3Edit" class="onlyszimpozium hidden"}
                    <select id="eloadas3Edit" name="eloadas3">
                        <option value="">{at('válasszon')}</option>
                        {foreach $eloadas3list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Előadás 4" for="eloadas4Edit" class="onlyszimpozium hidden"}
                    <select id="eloadas4Edit" name="eloadas4">
                        <option value="">{at('válasszon')}</option>
                        {foreach $eloadas4list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Témakörök" for="temakor1Edit" szeles=true}
                    <div class="mattkarb-mezogomb">
                        <select id="temakor1Edit" name="temakor1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <select id="temakor2Edit" name="temakor2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <select id="temakor3Edit" name="temakor3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                {/mezo}
                {mezo cimke="Téma" for="temaEdit"}
                    <select id="temaEdit" name="tema">
                        <option value="">{at('válasszon')}</option>
                        {foreach $temalist as $_mk}
                            <option
                                value="{$_mk.id}"
                                    {if ($_mk.selected)} selected="selected"{/if}
                                    {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                            >{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Kulcsszavak" for="kulcsszo1Edit" szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="kulcsszo1Edit" name="kulcsszo1" type="text" value="{$egyed.kulcsszo1}">
                        <input name="kulcsszo2" type="text" value="{$egyed.kulcsszo2}">
                        <input name="kulcsszo3" type="text" value="{$egyed.kulcsszo3}">
                        <input name="kulcsszo4" type="text" value="{$egyed.kulcsszo4}">
                        <input name="kulcsszo5" type="text" value="{$egyed.kulcsszo5}">
                    </div>
                {/mezo}
                {mezo cimke="Tartalom" for="tartalomEdit" szeles=true}
                    <textarea id="tartalomEdit" name="tartalom" rows="20" cols="80">{$egyed.tartalom}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="BiralatTab" class="mattkarb-page">
            {mezocsoport}
                {mezo cimke="Bíráló" for="biralo1Edit"}
                    <select id="biralo1Edit" name="biralo1">
                        <option value="">{at('válasszon')}</option>
                        {foreach $biralo1list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Bírálat kész" for="b1biralatkeszEdit"}
                    <input id="b1biralatkeszEdit" type="checkbox" name="b1biralatkesz"{if ($egyed.b1biralatkesz)} checked{/if}>
                {/mezo}
                {mezo cimke=$szempont1nev nyers=true for="b1szempont1Edit"}
                    <input id="b1szempont1Edit" type="number" name="b1szempont1" value="{$egyed.b1szempont1}">
                {/mezo}
                {mezo cimke=$szempont2nev nyers=true for="b1szempont2Edit"}
                    <input id="b1szempont2Edit" type="number" name="b1szempont2" value="{$egyed.b1szempont2}">
                {/mezo}
                {mezo cimke=$szempont3nev nyers=true for="b1szempont3Edit"}
                    <input id="b1szempont3Edit" type="number" name="b1szempont3" value="{$egyed.b1szempont3}">
                {/mezo}
                {mezo cimke=$szempont4nev nyers=true for="b1szempont4Edit"}
                    <input id="b1szempont4Edit" type="number" name="b1szempont4" value="{$egyed.b1szempont4}">
                {/mezo}
                {mezo cimke=$szempont5nev nyers=true for="b1szempont5Edit"}
                    <input id="b1szempont5Edit" type="number" name="b1szempont5" value="{$egyed.b1szempont5}">
                {/mezo}
                {mezo cimke="Szöveges értékelés" for="b1szovegesEdit" szeles=true}
                    <textarea id="b1szovegesEdit" name="b1szovegesertekeles" rows="10" cols="80">{$egyed.b1szovegesertekeles}</textarea>
                {/mezo}
                {mezo cimke="Bíráló" for="biralo2Edit"}
                    <select id="biralo2Edit" name="biralo2">
                        <option value="">{at('válasszon')}</option>
                        {foreach $biralo2list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Bírálat kész" for="b2biralatkeszEdit"}
                    <input id="b2biralatkeszEdit" type="checkbox" name="b2biralatkesz"{if ($egyed.b2biralatkesz)} checked{/if}>
                {/mezo}
                {mezo cimke=$szempont1nev nyers=true for="b2szempont1Edit"}
                    <input id="b2szempont1Edit" type="number" name="b2szempont1" value="{$egyed.b2szempont1}">
                {/mezo}
                {mezo cimke=$szempont2nev nyers=true for="b2szempont2Edit"}
                    <input id="b2szempont2Edit" type="number" name="b2szempont2" value="{$egyed.b2szempont2}">
                {/mezo}
                {mezo cimke=$szempont3nev nyers=true for="b2szempont3Edit"}
                    <input id="b2szempont3Edit" type="number" name="b2szempont3" value="{$egyed.b2szempont3}">
                {/mezo}
                {mezo cimke=$szempont4nev nyers=true for="b2szempont4Edit"}
                    <input id="b2szempont4Edit" type="number" name="b2szempont4" value="{$egyed.b2szempont4}">
                {/mezo}
                {mezo cimke=$szempont5nev nyers=true for="b2szempont5Edit"}
                    <input id="b2szempont5Edit" type="number" name="b2szempont5" value="{$egyed.b2szempont5}">
                {/mezo}
                {mezo cimke="Szöveges értékelés" for="b2szovegesEdit" szeles=true}
                    <textarea id="b2szovegesEdit" name="b2szovegesertekeles" rows="10" cols="80">{$egyed.b2szovegesertekeles}</textarea>
                {/mezo}
                {mezo cimke="Bíráló" for="biralo3Edit"}
                    <select id="biralo3Edit" name="biralo3">
                        <option value="">{at('válasszon')}</option>
                        {foreach $biralo3list as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Bírálat kész" for="b3biralatkeszEdit"}
                    <input id="b3biralatkeszEdit" type="checkbox" name="b3biralatkesz"{if ($egyed.b3biralatkesz)} checked{/if}>
                {/mezo}
                {mezo cimke=$szempont1nev nyers=true for="b3szempont1Edit"}
                    <input id="b3szempont1Edit" type="number" name="b3szempont1" value="{$egyed.b3szempont1}">
                {/mezo}
                {mezo cimke=$szempont2nev nyers=true for="b3szempont2Edit"}
                    <input id="b3szempont2Edit" type="number" name="b3szempont2" value="{$egyed.b3szempont2}">
                {/mezo}
                {mezo cimke=$szempont3nev nyers=true for="b3szempont3Edit"}
                    <input id="b3szempont3Edit" type="number" name="b3szempont3" value="{$egyed.b3szempont3}">
                {/mezo}
                {mezo cimke=$szempont4nev nyers=true for="b3szempont4Edit"}
                    <input id="b3szempont4Edit" type="number" name="b3szempont4" value="{$egyed.b3szempont4}">
                {/mezo}
                {mezo cimke=$szempont5nev nyers=true for="b3szempont5Edit"}
                    <input id="b3szempont5Edit" type="number" name="b3szempont5" value="{$egyed.b3szempont5}">
                {/mezo}
                {mezo cimke="Szöveges értékelés" for="b3szovegesEdit" szeles=true}
                    <textarea id="b3szovegesEdit" name="b3szovegesertekeles" rows="10" cols="80">{$egyed.b3szovegesertekeles}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>