<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{if ($_egyed.ismetlodo)}{at('minden')} {$_egyed.napnev}{else}{$_egyed.kezdet}{/if}</a>
        <a class="js-emailkezdes" href="#" data-egyedid="{$_egyed.id}">{at('Kezdés emlékeztető email')}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                class="ui-icon ui-icon-circle-minus"></span></a>
        <div>{if ($_egyed.tipus == 'rendezveny')}{at('Rendezvény')}{else}{at('Időpont (foglalható)')}{/if}
            - {if ($_egyed.ismetlodo)}{at('Ismétlődő (heti)')}{else}{at('Egyszeri')}{/if}</div>
        {if ($_egyed.ismetlodo)}
            <div>{$_egyed.idotartam}</div>
        {else}
            <div>{$_egyed.napnev}</div>
            {if ($_egyed.veg)}
                <div>{at('Vége')}: {$_egyed.veg}</div>{/if}
        {/if}
        {if ($_egyed.kerdoivkerdesdb)}
            <div>{at('Kérdőív')}: {$_egyed.kerdoivkerdesdb} {at('kérdés')}</div>
        {/if}
        <div class="matt-hseparator"></div>
        <div>{at('Regisztrációs form')}:
            <a href="#" class="js-uidcopy" data-clipboard-text="{$_egyed.reglink}">{at('Másolás vágólapra')}</a></div>
    </td>
    <td class="cell">
        {if ($_egyed.nev)}
            <div>{at('Név')}: <b>{$_egyed.nev}</b></div>
        {/if}
        {if ($_egyed.idoponttemanev)}
            <div>{at('Téma')}: {$_egyed.idoponttemanev}</div>
        {/if}
        <div>{at('Tanár')}: {$_egyed.dolgozonev}</div>
        <div>{at('Helyszín')}: {$_egyed.jogahelyszinnev}</div>
        <div>
            {if (!$_egyed.maxresztvevo)}
                {at('Nincs létszámkorlát')}
            {elseif ($_egyed.ismetlodo)}
                {at('Max. résztvevő')}: {$_egyed.maxresztvevo} {at('alkalmanként')}
            {else}
                {at('Jelentkezés')}:
                <b>{$_egyed.foglalasdb} / {$_egyed.maxresztvevo}</b>
                ({at('szabad')}: {$_egyed.szabadhely})
            {/if}
            {if ($_egyed.varolistavan)} – {at('van várólista')}{/if}
        </div>
        <div class="matt-hseparator"></div>
        <div>{at('Ár')}: <span class="pricenowrap">{number_format($_egyed.ar|default:0, 2, '.', ' ')}</span></div>
        {if ($_egyed.earlybirdar)}
            <div>{at('Early bird')}: <span class="pricenowrap">{number_format($_egyed.earlybirdar, 2, '.', ' ')}</span>
                {if ($_egyed.earlybirdvege)}({$_egyed.earlybirdvege}){/if}</div>
        {/if}
        {if ($_egyed.termeknev)}
            <div>{at('Termék a számlán')}: {$_egyed.termeknev}</div>
        {/if}
        <div>{at('Számlázási adat bekérés')}: {if ($_egyed.kellszamlazasiadat)}{at('van')}{else}{at('nincs')}{/if}</div>
        {if ($_egyed.url)}
            <div>{at('Webcím')}: <a href="{$_egyed.url}" target="_blank">{$_egyed.url}</a></div>
        {/if}
    </td>
    <td class="cell">
        {$_egyed.idopontallapotnev}
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
            </tbody>
        </table>
    </td>
</tr>
