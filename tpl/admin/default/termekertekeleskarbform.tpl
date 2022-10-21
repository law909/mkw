<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
    <h3>{at('Termék értékelés')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/termekertekeles/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td class="mattable-important"><label for="PartnerEdit">{at('Vevő')}:</label></td>
                    {if ($setup.partnerautocomplete)}
                        <td colspan="7">
                            {if ($oper === 'add')}
                                <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" value="{$egyed.partnernev}" size=90 required="required">
                            {else}
                                {$egyed.partnernev}
                            {/if}
                            <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                        </td>
                    {else}
                        <td colspan="7"><select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required">
                                <option value="">{at('válasszon')}</option>
                                <option value="-1">{at('Új felvitel')}</option>
                                {foreach $partnerlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td class="mattable-important"><label for="TermekSelect">{at('Termék')}:</label></td>
                    {if ($setup.termekautocomplete)}
                        <td colspan="5">
                            {if ($oper === 'add')}
                                <input id="TermekSelect" type="text" name="termeknev" class="js-termekselect termekselect mattable-important" value="{$egyed.termeknev}" required="required">
                            {else}
                                {$egyed.termeknev}
                            {/if}
                            <input class="js-termekid" name="termek" type="hidden" value="{$egyed.termek}">
                        </td>
                    {else}
                        <td colspan="5">
                            <select class="js-termekselectreal js-termekid" name="termek" required="required">
                                <option value="">{t('válasszon')}</option>
                                {foreach $termeklist as $_termekadat}
                                    <option value="{$_termekadat.id}"{if ($_termekadat.id == $egyed.termek)} selected="selected"{/if}>{$_termekadat.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td><label for="ErtekelesEdit">{at('Értékelés')}:</label></td>
                    <td><input id="ErtekelesEdit" name="ertekeles" type="number" size="5" value="{$egyed.ertekeles}" required></td>
                </tr>
                <tr>
                    <td><label for="SzovegEdit">{at('Szöveg')}:</label></td>
                    <td><textarea id="SzovegEdit" name="szoveg">{$egyed.szoveg}</textarea></td>
                </tr>
                <tr>
                    <td><label for="ElonyEdit">{at('Előny')}:</label></td>
                    <td><textarea id="ElonyEdit" name="elony">{$egyed.elony}</textarea></td>
                </tr>
                <tr>
                    <td><label for="HatranyEdit">{at('Hátrány')}:</label></td>
                    <td><textarea id="HatranyEdit" name="hatrany">{$egyed.hatrany}</textarea></td>
                </tr>
                <tr>
                    <td><label for="ValaszEdit">{at('Válasz')}:</label></td>
                    <td><textarea id="ValaszEdit" name="valasz">{$egyed.valasz}</textarea></td>
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
