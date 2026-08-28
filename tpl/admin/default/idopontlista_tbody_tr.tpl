<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{if ($_egyed.ismetlodo)}{at('minden')} {$_egyed.napnev}{else}{$_egyed.kezdet}{/if}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        {if ($_egyed.ismetlodo)}
            <div>{$_egyed.idotartam}</div>
        {else}
            <div>{$_egyed.napnev}</div>
            {if ($_egyed.veg)}<div>{at('Vége')}: {$_egyed.veg}</div>{/if}
        {/if}
        {if ($_egyed.tipus == 'rendezveny')}
            <div><a class="js-emailkezdes" href="#" data-egyedid="{$_egyed.id}">{at('Kezdés emlékeztető email')}</a></div>
        {/if}
    </td>
    <td class="cell">
        <table>
            <tbody>
            {if ($_egyed.nev)}
                <tr>
                    <td>{at('Név')}: {$_egyed.nev}</td>
                </tr>
            {/if}
            {if ($_egyed.idoponttemanev)}
                <tr>
                    <td>{at('Téma')}: {$_egyed.idoponttemanev}</td>
                </tr>
            {/if}
            <tr>
                <td>{at('Tanár')}: {$_egyed.dolgozonev}</td>
            </tr>
            <tr>
                <td>{at('Helyszín')}: {$_egyed.jogahelyszinnev}{if ($_egyed.jogateremnev)} / {$_egyed.jogateremnev}{/if}</td>
            </tr>
            <tr>
                <td>{at('Ár')}: <span class="pricenowrap">{number_format($_egyed.ar|default:0, 2, '.', ' ')}</span></td>
            </tr>
            {if ($_egyed.earlybirdar)}
                <tr>
                    <td>{at('Early bird')}: <span class="pricenowrap">{number_format($_egyed.earlybirdar, 2, '.', ' ')}</span>
                        {if ($_egyed.earlybirdvege)}({$_egyed.earlybirdvege}){/if}</td>
                </tr>
            {/if}
            <tr>
                <td>
                    {if (!$_egyed.maxresztvevo)}
                        {at('Nincs létszámkorlát')}
                    {elseif ($_egyed.ismetlodo)}
                        {at('Max. résztvevő')}: {$_egyed.maxresztvevo} {at('alkalmanként')}
                    {else}
                        {at('Jelentkezés')}: {$_egyed.foglalasdb} / {$_egyed.maxresztvevo} ({at('szabad')}: {$_egyed.szabadhely})
                    {/if}
                    {if ($_egyed.varolistavan)} – {at('van várólista')}{/if}
                </td>
            </tr>
            {if ($_egyed.tipus == 'rendezveny')}
                {if ($_egyed.url)}
                    <tr>
                        <td>{at('Webcím')}: <a href="{$_egyed.url}" target="_blank">{$_egyed.url}</a></td>
                    </tr>
                {/if}
                {if ($_egyed.termeknev)}
                    <tr>
                        <td>{at('Termék a számlán')}: {$_egyed.termeknev}</td>
                    </tr>
                {/if}
                <tr>
                    <td>{at('Számlázási adat bekérés')}: {if ($_egyed.kellszamlazasiadat)}{at('van')}{else}{at('nincs')}{/if}</td>
                </tr>
                <tr>
                    <td>{at('Órarendben szerepel')}: {if ($_egyed.orarendbenszerepel)}{at('igen')}{else}{at('nem')}{/if}</td>
                </tr>
                <tr>
                    <td>{at('Regisztrációs form')}:
                        <a href="#" class="js-uidcopy" data-clipboard-text="{$_egyed.reglink}">{at('Másolás vágólapra')}</a></td>
                </tr>
            {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        {if ($_egyed.tipus == 'rendezveny')}{$_egyed.idopontallapotnev}{/if}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="inaktiv"
                       class="js-flagcheckbox{if ($_egyed.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="onlinevalaszthato"
                       class="js-flagcheckbox{if ($_egyed.onlinevalaszthato)} ui-state-hover{/if}">{at('Online választható')}</a></td>
            </tr>
            <tr>
                <td>{if ($_egyed.ismetlodo)}{at('Ismétlődő (heti)')}{else}{at('Egyszeri')}{/if}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {if ($_egyed.tipus == 'rendezveny')}
            {* a teendőknél a kiemelés azt jelenti, hogy még nincs kész – ezért data-invert *}
            <table>
                <tbody>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todonaptar" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todonaptar)} ui-state-hover{/if}">{at('Naptár frissítés')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todofotobe" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todofotobe)} ui-state-hover{/if}">{at('Fotó bekérés')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todoleirasbe" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todoleirasbe)} ui-state-hover{/if}">{at('Leírás bekérés')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todoplakat" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todoplakat)} ui-state-hover{/if}">{at('Plakát')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todowebposzt" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todowebposzt)} ui-state-hover{/if}">{at('Poszt a weboldalra')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todowebslider" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todowebslider)} ui-state-hover{/if}">{at('Új dia weboldal sliderbe')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todourlap" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todourlap)} ui-state-hover{/if}">{at('Űrlap jelentkezéshez')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todofbevent" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todofbevent)} ui-state-hover{/if}">{at('Facebook event')}</a></td>
                </tr>
                <tr>
                    <td><a href="#" data-id="{$_egyed.id}" data-flag="todofbhirdetes" data-invert="1"
                           class="js-flagcheckbox{if (!$_egyed.todofbhirdetes)} ui-state-hover{/if}">{at('Facebook hirdetés')}</a></td>
                </tr>
                </tbody>
            </table>
        {/if}
    </td>
</tr>
