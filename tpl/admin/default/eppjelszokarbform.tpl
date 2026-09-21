<div id="mattkarb-header">
    <h3>{at('WordPress oldal jelszó')}</h3>
    <h4>{if ($egyed.id)}{at('Oldal')} {$egyed.oldalid}{if ($egyed.megjegyzes)} – {$egyed.megjegyzes}{/if}{/if}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/eppjelszo/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="OldalidEdit">{at('WordPress oldal ID')}:</label></td>
                    <td>
                        {if ($egyed.id)}
                            {$egyed.oldalid}
                        {else}
                            <input id="OldalidEdit" name="oldalid" type="number" min="1" style="width: 10em" required autofocus>
                            <span>{at('a WP oldal (post) azonosítója, a szerkesztő URL-jében: post.php?post=123')}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                    <td><input id="MegjegyzesEdit" name="megjegyzes" type="text" size="80" maxlength="255" value="{$egyed.megjegyzes}"></td>
                </tr>
                <tr>
                    <td><label for="LejaratEdit">{at('Lejárat')}:</label></td>
                    <td><input id="LejaratEdit" name="lejarat" type="datetime-local" value="{$egyed.lejaratinput}" required></td>
                </tr>
                {if ($egyed.id)}
                    <tr>
                        <td>{at('Azonosító')}:</td>
                        <td><code>{$egyed.azonosito}</code></td>
                    </tr>
                    <tr>
                        <td>{at('Létrehozva')}:</td>
                        <td>{$egyed.createdstr}{if ($egyed.createdbynev)} ({$egyed.createdbynev}){/if}</td>
                    </tr>
                    <tr>
                        <td><label for="VisszavonEdit">{at('Visszavonás')}:</label></td>
                        <td>
                            {if ($egyed.visszavonva)}
                                {at('visszavonva')}: {$egyed.visszavonvaonstr}{if ($egyed.visszavonvabynev)} ({$egyed.visszavonvabynev}){/if}
                            {else}
                                <input id="VisszavonEdit" name="visszavon" type="checkbox">
                                <span>{at('a jelszó azonnal érvénytelen lesz, és nem állítható vissza')}</span>
                            {/if}
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td></td>
                        <td>{at('A jelszót a rendszer generálja; mentés után egyszer látszik, utána nem kérhető le újra.')}</td>
                    </tr>
                {/if}
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
