<div id="valtozattable_{$valtozat.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable valtozattable">
    <input name="valtozatid[]" type="hidden" value="{$valtozat.id}">
    <input name="valtozatoper_{$valtozat.id}" type="hidden" value="{$valtozat.oper}">
    <div class="valtozat-fej">
        <span class="valtozat-cim">
            {if ($valtozat.oper == 'add')}
                {at('Új változat')}
            {else}
                {$valtozat.ertek1}{if ($valtozat.ertek2)} / {$valtozat.ertek2}{/if}
            {/if}
        </span>
        {if ($valtozat.cikkszam)}<span class="valtozat-jelveny">{$valtozat.cikkszam}</span>{/if}
        {if ($valtozat.oper != 'add')}<span class="valtozat-jelveny">{at('Készlet')}: {$valtozat.keszlet}</span>{/if}
        {if ($valtozat.inaktiv)}<span class="valtozat-jelveny valtozat-jelveny-inaktiv">{at('inaktív')}</span>{/if}
        <a class="js-valtozatdelbutton valtozat-torles" href="#" data-id="{$valtozat.id}"{if ($valtozat.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span
                class="ui-icon ui-icon-circle-minus"></span></a>
    </div>
    <div class="valtozat-mezok">
        {mezocsoport cim="Tulajdonságok"}
            {if ($setup.szinmode === 'fix')}
                {mezo cimke="Szín"}
                    <select name="valtozatszin_{$valtozat.id}">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valtozat.szinlista as $at}
                            <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Méret"}
                    <select name="valtozatmeret_{$valtozat.id}">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valtozat.meretlista as $at}
                            <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/if}
            {mezo cimke="1. tulajdonság"}
                <select name="valtozatadattipus1_{$valtozat.id}" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $valtozat.adattipus1lista as $at}
                        <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                    {/foreach}
                </select>
                <input name="valtozatertek1_{$valtozat.id}" type="text" value="{$valtozat.ertek1}" {if ($setup.szinmode != 'fix')}required="required"{/if}>
            {/mezo}
            {mezo cimke="2. tulajdonság"}
                <select name="valtozatadattipus2_{$valtozat.id}">
                    <option value="">{at('válasszon')}</option>
                    {foreach $valtozat.adattipus2lista as $at}
                        <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                    {/foreach}
                </select>
                <input name="valtozatertek2_{$valtozat.id}" type="text" value="{$valtozat.ertek2}">
            {/mezo}
            {if (!$setup.arsavok)}
                {mezo cimke="Nettó" for="NettoEdit_{$valtozat.id}"}
                    <input class="js-valtozatnetto" id="NettoEdit_{$valtozat.id}" name="valtozatnetto_{$valtozat.id}" type="number" step="any"
                           value="{$valtozat.netto}">
                {/mezo}
                {mezo cimke="Bruttó" for="BruttoEdit_{$valtozat.id}"}
                    <input class="js-valtozatbrutto" id="BruttoEdit_{$valtozat.id}" name="valtozatbrutto_{$valtozat.id}" type="number" step="any"
                           value="{$valtozat.brutto}">
                {/mezo}
            {/if}
        {/mezocsoport}
        {mezocsoport cim="Azonosítók"}
            {mezo cimke="Cikkszám" for="CikkszamEdit_{$valtozat.id}"}
                <input id="CikkszamEdit_{$valtozat.id}" name="valtozatcikkszam_{$valtozat.id}" type="text" maxlength="50" value="{$valtozat.cikkszam}">
            {/mezo}
            {mezo cimke="Szállítói cikkszám" for="IdegenCikkszamEdit_{$valtozat.id}"}
                <input id="IdegenCikkszamEdit_{$valtozat.id}" name="valtozatidegencikkszam_{$valtozat.id}" type="text" value="{$valtozat.idegencikkszam}">
            {/mezo}
            {if ($setup.vonalkod)}
                {mezo cimke="Vonalkód" for="VonalkodEdit_{$valtozat.id}"}
                    <input id="VonalkodEdit_{$valtozat.id}" name="valtozatvonalkod_{$valtozat.id}" type="text" value="{$valtozat.vonalkod}">
                {/mezo}
            {/if}
            {if ($setup.unas)}
                {* akkor van kitöltve, ha az UNAS termék nálunk ez a változat *}
                {mezo cimke="UNAS azonosító" for="VUnasidEdit_{$valtozat.id}"}
                    <input id="VUnasidEdit_{$valtozat.id}" name="valtozatunasid_{$valtozat.id}" type="text" value="{$valtozat.unasid}">
                {/mezo}
                {mezo cimke="UNAS alap típus" for="VUnasalaptipusEdit_{$valtozat.id}"}
                    <input id="VUnasalaptipusEdit_{$valtozat.id}" name="valtozatunasalaptipus_{$valtozat.id}" type="text" value="{$valtozat.unasalaptipus}">
                {/mezo}
            {/if}
            {mezo cimke="Videó link" for="VideolinkEdit_{$valtozat.id}"}
                <input id="VideolinkEdit_{$valtozat.id}" name="valtozatvideolink_{$valtozat.id}" type="url" maxlength="255" value="{$valtozat.videolink|escape}">
            {/mezo}
        {/mezocsoport}
        {mezocsoport cim="Elérhetőség, készlet"}
            {mezo cimke=$webshop1name nyers=true}
                <label><input id="VElerhetoEdit{$valtozat.id}" name="valtozatelerheto_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto)} checked="checked"{/if}> {at('elérhető')}</label>
                <label><input id="VLathatoEdit{$valtozat.id}" name="valtozatlathato_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato)} checked="checked"{/if}> {at('látható')}</label>
            {/mezo}
            {if ($setup.multishop)}
                {for $cikl = 2 to $enabledwebshops}
                    {capture assign="_webshopnev"}{$webshop{$cikl}name}{/capture}
                    {mezo cimke=$_webshopnev nyers=true}
                        <label><input id="VElerheto{$cikl}Edit{$valtozat.id}" name="valtozatelerheto{$cikl}_{$valtozat.id}"
                                      type="checkbox"{if ($valtozat["elerheto$cikl"])} checked="checked"{/if}> {at('elérhető')}</label>
                        <label><input id="VLathato{$cikl}Edit{$valtozat.id}" name="valtozatlathato{$cikl}_{$valtozat.id}"
                                      type="checkbox"{if ($valtozat["lathato$cikl"])} checked="checked"{/if}> {at('látható')}</label>
                    {/mezo}
                {/for}
            {/if}
            {mezo cimke="Inaktív" for="VInaktivEdit{$valtozat.id}"}
                <input id="VInaktivEdit{$valtozat.id}" name="valtozatinaktiv_{$valtozat.id}" type="checkbox"{if ($valtozat.inaktiv)} checked="checked"{/if}>
            {/mezo}
            {mezo cimke="Előrendelhető" for="ElorendelhetoEdit{$valtozat.id}"}
                <input id="ElorendelhetoEdit{$valtozat.id}" name="valtozatelorendelheto_{$valtozat.id}"
                       type="checkbox"{if ($valtozat.elorendelheto)} checked="checked"{/if}>
            {/mezo}
            {mezo cimke="Beérkezés" for="BeerkezesdatumEdit{$valtozat.id}"}
                <input id="BeerkezesdatumEdit{$valtozat.id}" name="valtozatbeerkezesdatum_{$valtozat.id}"
                       class="js-valtozatbeerkezesdatumedit mezo-rovid" type="text" size="12" data-datum="{$valtozat.beerkezesdatumstr}">
            {/mezo}
            {mezo cimke="Készlet"}
                {$valtozat.keszlet}
            {/mezo}
        {/mezocsoport}
        {if ($setup.arsavok)}
            {mezocsoport cim="Ársávos ár" class="valtozat-szeles"}
                {* a változat saját ára megelőzi a termékét; ahol nincs, a termék ára érvényes *}
                {foreach $valtozat.arak as $ar}
                    {include 'termekvaltozatarkarb.tpl'}
                {/foreach}
                <a class="js-valtozatarnewbutton" href="#" data-valtozatid="{$valtozat.id}">{at('Új ár')}</a>
            {/mezocsoport}
        {/if}
        {mezocsoport cim="Kép" class="valtozat-szeles"}
            <label class="valtozat-fokep">
                <input id="ValtozatTermekKepCB_{$valtozat.id}" name="valtozattermekfokep_{$valtozat.id}"
                       type="checkbox"{if ($valtozat.termekfokep)} checked="checked"{/if}>
                {at('A kép a termék főképe')}
            </label>
            <ul id="ValtozatKepEdit_{$valtozat.id}" class="valtozatkepedit js-valtozatkepedit">
                {foreach $valtozat.keplista as $kep}
                    <li data-value="{$kep.id}" data-valtozatid="{$valtozat.id}"
                        class="ui-state-default{if ($valtozat.kepid==$kep.id)} ui-selected ui-state-highlight{/if}">
                        {if ($kep.url)}<img src="{$mainurl}{$kep.url}"/>{/if}
                    </li>
                {/foreach}
            </ul>
            <input id="ValtozatKepId_{$valtozat.id}" name="valtozatkepid_{$valtozat.id}" type="hidden" value="{$valtozat.kepid}">
        {/mezocsoport}
    </div>
    {if ($valtozat.oper != 'add')}
        <div class="valtozat-lab">{at('Létrehozva')}: {$valtozat.createdstr} · {at('Módosítva')}: {$valtozat.lastmodstr}</div>
    {/if}
</div>
{if ($valtozat.oper=='add')}
    <a class="js-valtozatnewbutton" href="#" title="{at('Új')}" data-termekid="{$valtozat.termek.id}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
