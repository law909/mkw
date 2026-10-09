<div id="artable_{$ar.id}" class="arsav-sor">
    <input name="arid[]" type="hidden" value="{$ar.id}">
    <input name="aroper_{$ar.id}" type="hidden" value="{$ar.oper}">
    <div class="arsav-cella">
        <label class="arsav-cimke" for="AzonEdit{$ar.id}">{at('Ársáv')}:</label>
        <select id="AzonEdit{$ar.id}" name="arsav_{$ar.id}" required="required">
            <option value="">{at('válasszon')}</option>
            {foreach $ar.arsavlist as $_valuta}
                <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="arsav-cella">
        <label class="arsav-cimke" for="ArValutaEdit{$ar.id}">{at('Valutanem')}:</label>
        <select id="ArValutaEdit{$ar.id}" name="arvalutanem_{$ar.id}" required="required">
            <option value="">{at('válasszon')}</option>
            {foreach $ar.valutanemlist as $_valuta}
                <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="arsav-cella">
        <label class="arsav-cimke" for="NettoEdit{$ar.id}">{at('Nettó')}:</label>
        <input id="NettoEdit{$ar.id}" class="js-arnetto" data-par="BruttoEdit{$ar.id}" type="text"
               name="arnetto_{$ar.id}" value="{$ar.netto}">
    </div>
    <div class="arsav-cella">
        <label class="arsav-cimke" for="BruttoEdit{$ar.id}">{at('Bruttó')}:</label>
        <input id="BruttoEdit{$ar.id}" class="js-arbrutto" data-par="NettoEdit{$ar.id}" type="text"
               name="arbrutto_{$ar.id}" value="{$ar.brutto}">
    </div>
    <div class="arsav-cella arsav-kepletes">
        <input id="KepletesEdit{$ar.id}" class="js-arkepletes" name="arkepletes_{$ar.id}" type="checkbox"
               data-id="{$ar.id}"{if ($ar.kepletes)} checked="checked"{/if}>
        <label for="KepletesEdit{$ar.id}">{at('Képlettel számolt ár')}</label>
    </div>
    <div class="arsav-cella">
        <a class="js-ardelbutton" href="#" data-id="{$ar.id}"{if ($ar.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span
                class="ui-icon ui-icon-circle-minus"></span></a>
    </div>
    <div class="arsav-keplet js-arkepletrow_{$ar.id}"{if (!$ar.kepletes)} style="display:none;"{/if}>
        <span>
            <label for="ForrasArsavEdit{$ar.id}">{at('Forrás ársáv')}:</label>
            <select id="ForrasArsavEdit{$ar.id}" name="arforrasarsav_{$ar.id}">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.forrasarsavlist as $_fa}
                    <option value="{$_fa.id}"{if ($_fa.selected)} selected="selected"{/if}>{$_fa.caption}</option>
                {/foreach}
            </select>
        </span>
        <span>
            <label for="SzazalekEdit{$ar.id}">{at('Szorzó')}:</label>
            <input id="SzazalekEdit{$ar.id}" type="number" step="any" size="6" name="arszazalek_{$ar.id}" value="{$ar.szazalek}"> %
        </span>
        <span>
            <label for="HozzaadEdit{$ar.id}">{at('Hozzáadandó')}:</label>
            <input id="HozzaadEdit{$ar.id}" type="number" step="any" size="8" name="arhozzaad_{$ar.id}" value="{$ar.hozzaad}">
        </span>
    </div>
    <div class="arsav-keplet js-arkepletrow_{$ar.id}"{if (!$ar.kepletes)} style="display:none;"{/if}>
        <label>{at('Hozzáadandó kapcsolódó költségek')}:</label>
        {foreach $ar.kepletkoltseglist as $_kk}
            <span>
                <input id="KepletKoltseg{$ar.id}_{$_kk.id}" name="arkepletkoltseg_{$ar.id}[]" type="checkbox"
                       value="{$_kk.id}"{if ($_kk.selected)} checked="checked"{/if}>
                <label for="KepletKoltseg{$ar.id}_{$_kk.id}">{$_kk.caption}</label>
            </span>
        {foreachelse}
            <span class="mattkarb-megjegyzes">{at('A termékhez nincs kapcsolódó költség rendelve.')}</span>
        {/foreach}
    </div>
</div>
