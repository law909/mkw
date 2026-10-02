<tr id="mattable-row_{$_termek.id}" data-egyedid="{$_termek.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo termeklista-fo">
        <div class="termeklista-fej">
            {if ($_termek.kepurl)}
                <a class="termeklista-kep js-toflyout" href="{$mainurl}{$_termek.kepurl}" target="_blank"><img src="{$mainurl}{$_termek.kepurlsmall}" alt=""></a>
            {/if}
            <div class="termeklista-cim">
                <a class="mattable-editlink termeklista-nev" href="#" data-termekid="{$_termek.id}" data-oper="edit"
                   title="{at('Szerkeszt')}">{if ($maintheme == 'superzoneb2b' || $maintheme == 'mugenrace2026' || $maintheme == 'superzonehu')}{$_termek.cikkszam}&nbsp;{/if}{$_termek.nev}</a>
                <div class="bizlista-muveletek">
                    {if (haveJog(20))}
                        <a class="js-karton" href="#" data-termekid="{$_termek.id}" title="{at('Karton')}" target="_blank"><span
                                class="ui-icon ui-icon-folder-collapsed"></span></a>
                    {/if}
                    <a class="mattable-dellink" href="#" data-termekid="{$_termek.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
                </div>
            </div>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Kategória')}:</dt><dd>{$_termek.termekfa1nev}{if ($_termek.termekfa1nev && $_termek.termekfa2nev)} · {/if}{$_termek.termekfa2nev}{if (($_termek.termekfa1nev || $_termek.termekfa2nev) && $_termek.termekfa3nev)} · {/if}{$_termek.termekfa3nev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Címkék')}:</dt><dd class="termeklista-cimkek">
                {foreach $_termek.cimkenevlista as $_cimkenev}
                    <span class="termeklista-cimke">{$_cimkenev}</span>
                {/foreach}
            </dd></div>
            <div class="bizlista-sor"><dt>{at('Azonosító')}:</dt><dd>{$_termek.id}</dd></div>
            <div class="bizlista-sor"><dt>{at('Cikkszám')}:</dt><dd>{$_termek.cikkszam}</dd></div>
            <div class="bizlista-sor"><dt>{at('Gyártó')}:</dt><dd>{$_termek.gyartonev}</dd></div>
            <div class="bizlista-sor"><dt>{at('ME')}:</dt><dd>{$_termek.me}</dd></div>
            <div class="bizlista-sor"><dt>{at('Link')}:</dt><dd class="termeklista-link"><a href="{$mainurl}/termek/{$_termek.slug}" target="_blank">/termek/{$_termek.slug}</a></dd></div>
            <div class="bizlista-sor"><dt>{at('Létrehozva')}:</dt><dd>{$_termek.createdstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Módosítva')}:</dt><dd>{$_termek.lastmodstr}</dd></div>
        </dl>
        {/strip}
        {if ($_termek.doklinkek)}
            <div class="bizlista-kapcs">
                <div class="bizlista-kapcs-cim">{at('Dokumentumok')}:</div>
                {include 'dokumentumlinkek.tpl' doklinkek=$_termek.doklinkek}
            </div>
        {/if}
    </td>
    <td class="cell">
        {strip}
        <dl class="bizlista-adatok termeklista-arak">
            {if (!$setup.arsavok)}
                <div class="bizlista-sor"><dt>{at('Nettó ár')}:</dt><dd>{number_format($_termek.netto,4,'.',' ')}</dd></div>
                <div class="bizlista-sor mattable-important"><dt>{at('Bruttó ár')}:</dt><dd>{number_format($_termek.brutto,4,'.',' ')}</dd></div>
                <div class="bizlista-sor"><dt>{at('Akciós n.ár')}:</dt><dd>{number_format($_termek.akciosnetto,4,'.',' ')}</dd></div>
                <div class="bizlista-sor"><dt>{at('Akciós b.ár')}:</dt><dd>{number_format($_termek.akciosbrutto,4,'.',' ')}</dd></div>
            {else}
                <div class="bizlista-sor"><dt>{at('Nettó ár')}:</dt><dd>{number_format($_termek.netto,2,'.',' ')}</dd></div>
                <div class="bizlista-sor mattable-important"><dt>{at('Bruttó ár')}:</dt><dd>{number_format($_termek.brutto,2,'.',' ')}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Min. bolti készlet')}:</dt><dd>{number_format($_termek.minkeszlet|default:0, 2, '.', ' ')}</dd></div>
            <div class="bizlista-sor"><dt>{at('Garancia')}:</dt><dd>{$_termek.garancia}</dd></div>
            <div class="bizlista-sor"><dt>{at('Hűségpont arány')}:</dt><dd>{$_termek.hparany}</dd></div>
            <div class="bizlista-sor"><dt>{at('Megtekintve')}:</dt><dd>{$_termek.megtekintesdb}</dd></div>
            <div class="bizlista-sor"><dt>{at('Megvásárolva')}:</dt><dd>{$_termek.megvasarlasdb}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {if ($_termek.mozgat)}
            <a href="#" data-id="{$_termek.id}" class="js-keszletreszletezobutton termeklista-keszlet">{at('Készlet')}: {$_termek.keszlet}</a>
            <div class="termeklista-keszletsorok">
                {include 'termekkeszletsorok.tpl' termek=$_termek}
            </div>
        {/if}
    </td>
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover, a termek.js ezt váltja *}
        <div class="termeklista-jellemzok">
            <div class="termeklista-jellemzocsoport">
                <a href="#" data-id="{$_termek.id}" data-flag="inaktiv"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="nemkaphato"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.nemkaphato)} ui-state-hover{/if}">{at('Nem kapható')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="fuggoben"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.fuggoben)} ui-state-hover{/if}">{at('Függőben')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="kifuto"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.kifuto)} ui-state-hover{/if}">{at('Kifutó')}</a>
            </div>
            <div class="termeklista-jellemzocsoport">
                <a href="#" data-id="{$_termek.id}" data-flag="lathato"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.lathato)} ui-state-hover{/if}">{at('Látható')} {$webshop1name}</a>
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                        <a href="#" data-id="{$_termek.id}" data-flag="lathato{$cikl}"
                           class="js-flagcheckbox termeklista-kapcsolo{if ($_termek["lathato$cikl"])} ui-state-hover{/if}">{at('Látható')} {$webshop{$cikl}name}</a>
                    {/for}
                {/if}
                <a href="#" data-id="{$_termek.id}" data-flag="feltoltheto"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.feltoltheto)} ui-state-hover{/if}">{at('Feltölthető')} {$webshop1name}</a>
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                        <a href="#" data-id="{$_termek.id}" data-flag="feltoltheto{$cikl}"
                           class="js-flagcheckbox termeklista-kapcsolo{if ($_termek["feltoltheto$cikl"])} ui-state-hover{/if}">{at('Feltölthető')} {$webshop{$cikl}name}</a>
                    {/for}
                {/if}
            </div>
            <div class="termeklista-jellemzocsoport">
                <a href="#" data-id="{$_termek.id}" data-flag="ajanlott"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.ajanlott)} ui-state-hover{/if}">{at('Ajánlott')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="kiemelt"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.kiemelt)} ui-state-hover{/if}">{at('Kiemelt')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="uj"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.uj)} ui-state-hover{/if}">{at('Új')}</a>
            </div>
            <div class="termeklista-jellemzocsoport">
                <a href="#" data-id="{$_termek.id}" data-flag="hozzaszolas"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.hozzaszolas)} ui-state-hover{/if}">{at('Hozzá lehet szólni')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="mozgat"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.mozgat)} ui-state-hover{/if}">{at('Készletet mozgat')}</a>
                <a href="#" data-id="{$_termek.id}" data-flag="termekexportbanszerepel"
                   class="js-flagcheckbox termeklista-kapcsolo{if ($_termek.termekexportbanszerepel)} ui-state-hover{/if}">{at('Exportokban szerepel')}</a>
            </div>
        </div>
    </td>
</tr>
