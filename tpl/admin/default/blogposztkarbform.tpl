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
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="LathatoCheck" name="lathato" type="checkbox"
                   {if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}
            <table>
                <tbody>
                <tr>
                    <td><label for="MegjelenesDatumEdit">{at('Megjelenés dátuma')}:</label></td>
                    <td><input id="MegjelenesDatumEdit" name="megjelenesdatum" type="text" size="12"
                               data-datum="{$egyed.megjelenesdatumstr}"></td>
                </tr>
                <tr>
                    <td><label>{at('Kategóriák')}:</label></td>
                    <td><span id="TermekKategoria1" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa1"
                              data-value="{$egyed.termekfa1}">{if ($egyed.termekfa1nev)}{$egyed.termekfa1nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria2" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa2"
                              data-value="{$egyed.termekfa2}">{if ($egyed.termekfa2nev)}{$egyed.termekfa2nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria3" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa3"
                              data-value="{$egyed.termekfa3}">{if ($egyed.termekfa3nev)}{$egyed.termekfa3nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Cím')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="cim" type="text" size="83" maxlength="255"
                                           value="{$egyed.cim}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="RovidLeirasEdit">{at('Kivonat')}:</label></td>
                    <td><input id="RovidLeirasEdit" name="kivonat" type="text" size="100" maxlength="255"
                               value="{$egyed.kivonat}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Szöveg')}:</label></td>
                    <td><textarea id="LeirasEdit" name="szoveg">{$egyed.szoveg}</textarea></td>
                </tr>
                </tbody>
            </table>
            <div>
                {include 'termekimagekarb.tpl'}
            </div>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>