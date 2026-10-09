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
            {mezocsoport}
                {mezo cimke="WordPress oldal ID" for="OldalidEdit"}
                    <div class="mattkarb-mezogomb">
                        {if ($egyed.id)}
                            {$egyed.oldalid}
                        {else}
                            <input id="OldalidEdit" name="oldalid" type="number" min="1" class="mezo-rovid" required autofocus>
                            <span>{at('a WP oldal (post) azonosítója, a szerkesztő URL-jében: post.php?post=123')}</span>
                        {/if}
                    </div>
                {/mezo}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="50" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Email" for="EmailEdit"}
                    <input id="EmailEdit" name="email" type="email" size="50" maxlength="255" value="{$egyed.email}">
                {/mezo}
                {mezo cimke="Megjegyzés" for="MegjegyzesEdit"}
                    <input id="MegjegyzesEdit" name="megjegyzes" type="text" size="80" maxlength="255" value="{$egyed.megjegyzes}">
                {/mezo}
                {mezo cimke="Lejárat" for="LejaratEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="LejaratEdit" name="lejarat" type="datetime-local" value="{$egyed.lejaratinput}" required>
                        {if ($egyed.honap)}<span>{at('kiadva')} {$egyed.honap} {at('hónapra')}</span>{/if}
                    </div>
                {/mezo}
                {if ($egyed.jelentkezes)}
                    {mezo cimke="Jelentkezés"}
                        {$egyed.jelentkezes}
                    {/mezo}
                {/if}
                {if ($egyed.id)}
                    {mezo cimke="Azonosító"}
                        <code>{$egyed.azonosito}</code>
                    {/mezo}
                    {mezo cimke="Létrehozva"}
                        {$egyed.createdstr}{if ($egyed.createdbynev)} ({$egyed.createdbynev}){/if}
                    {/mezo}
                    {mezo cimke="Visszavonás" for="VisszavonEdit"}
                        {if ($egyed.visszavonva)}
                            {at('visszavonva')}: {$egyed.visszavonvaonstr}{if ($egyed.visszavonvabynev)} ({$egyed.visszavonvabynev}){/if}
                        {else}
                            <input id="VisszavonEdit" name="visszavon" type="checkbox">
                            <span>{at('a jelszó azonnal érvénytelen lesz, és nem állítható vissza')}</span>
                        {/if}
                    {/mezo}
                {else}
                    {mezo szeles=true}
                        {at('A jelszót a rendszer generálja; mentés után egyszer látszik, utána nem kérhető le újra.')}
                    {/mezo}
                {/if}
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
