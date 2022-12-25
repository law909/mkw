<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <a class="js-emailrendezvenykezdes" href="#" data-egyedid="{$_egyed.id}">{at('Kezdés emlékeztető email')}</a>

        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        <table>
            <tbody>
                <tr><td>Webcím:</td><td><a href="{$_egyed.url}" target="_blank">{$_egyed.url}</a></td></tr>
                <tr><td>Dátum:</td><td>{$_egyed.kezdodatum} {$_egyed.kezdoido}</td></tr>
                <tr><td>Termék a számlán</td><td>{$_egyed.termeknev}</td></tr>
                <tr><td>Ár:</td><td class="pricenowrap">{number_format($_egyed.ar, 2, '.', ' ')}</td></tr>
                <tr><td>Helyszín:</td><td>{$_egyed.helyszinnev}</td></tr>
                <tr><td>Terem:</td><td>{$_egyed.jogateremnev}</td></tr>
                <tr><td>Számlázási adat bekérés:</td><td>{if ($_egyed.kellszamlazasiadat)}van{else}nincs{/if}</td></tr>
                <tr><td>Órarendben szerepel:</td><td>{if ($_egyed.orarendbenszerepel)}igen{else}nem{/if}</td></tr>
                <tr><td>Regisztrációs form:</td><td><a href="#" class="js-uidcopy" data-clipboard-text="{$_egyed.reglink}">Másolás vágólapra</a></td></tr>
                <tr><td>Max. férőhely:</td><td>{$_egyed.maxferohely}</td></tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_egyed.tanarnev}
    </td>
    <td class="cell">
        {$_egyed.rendezvenyallapotnev}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todonaptar" class="js-flagcheckbox{if (!$_egyed.todonaptar)} ui-state-hover{/if}">{at('Naptár frissítés')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todofotobe" class="js-flagcheckbox{if (!$_egyed.todofotobe)} ui-state-hover{/if}">{at('Fotó bekérés')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todoleirasbe" class="js-flagcheckbox{if (!$_egyed.todoleirasbe)} ui-state-hover{/if}">{at('Leírás bekérés')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todoplakat" class="js-flagcheckbox{if (!$_egyed.todoplakat)} ui-state-hover{/if}">{at('Plakát')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todowebposzt" class="js-flagcheckbox{if (!$_egyed.todowebposzt)} ui-state-hover{/if}">{at('Poszt a weboldalra')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todowebslider" class="js-flagcheckbox{if (!$_egyed.todowebslider)} ui-state-hover{/if}">{at('Új dia weboldal sliderbe')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todourlap" class="js-flagcheckbox{if (!$_egyed.todourlap)} ui-state-hover{/if}">{at('Űrlap jelentkezéshez')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todofbevent" class="js-flagcheckbox{if (!$_egyed.todofbevent)} ui-state-hover{/if}">{at('Facebook event')}</a></td></tr>
            <tr><td><a href="#" data-id="{$_egyed.id}" data-flag="todofbhirdetes" class="js-flagcheckbox{if (!$_egyed.todofbhirdetes)} ui-state-hover{/if}">{at('Facebook hirdetés')}</a></td></tr>
            </tbody>
        </table>
    </td>

</tr>