{* A kérdőív szerkesztője (időpont és időpont téma karbantartó Kérdőív füle). A blokkokat a
   mkwcomp.kerdoivSzerkeszto építi a rejtett kerdoiv mező JSON-jából, és minden módosításkor vissza is
   írja oda – a form a JSON-t küldi el. Várt változók: kerdoivjson, kerdoivhint, kerdoivforraslist (opcionális). *}
<input class="js-kerdoivjson" name="kerdoiv" type="hidden" value="{$kerdoivjson|escape}">
<p class="mattkarb-hint">{$kerdoivhint}</p>
<table>
    <tbody>
    <tr>
        <td><label for="KerdoivCimEdit">{at('Kérdőív címe')}:</label></td>
        <td><input id="KerdoivCimEdit" class="js-kerdoivcim" type="text" size="83" maxlength="255"></td>
    </tr>
    <tr>
        <td><label for="KerdoivLeirasEdit">{at('Bevezető szöveg')}:</label></td>
        <td><textarea id="KerdoivLeirasEdit" class="js-kerdoivleiras" rows="3" cols="80"></textarea></td>
    </tr>
    {if ($kerdoivforraslist|default)}
        <tr>
            <td><label for="KerdoivForrasSelect">{at('Kérdőív átvétele')}:</label></td>
            <td><select id="KerdoivForrasSelect" class="js-kerdoivforras">
                    <option value="">{at('válasszon')}</option>
                    {foreach $kerdoivforraslist as $_f}
                        <option value="{$_f.id}">{$_f.caption|escape}</option>
                    {/foreach}
                </select>
                <span class="mattkarb-hint">{at('A mostani kérdéseket lecseréli, csak a mentéssel véglegesül.')}</span></td>
        </tr>
    {/if}
    </tbody>
</table>
<div class="js-kerdoivkerdesek"></div>
<a class="js-kerdoivujkerdes" href="#" title="{at('Új kérdés')}"><span class="ui-icon ui-icon-circle-plus"></span>{at('Új kérdés')}</a>
