<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($loggedinuser.admin || ($_egyed.id == $loggedinuser.id))}
            <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        {else}
            {$_egyed.nev}
        {/if}
        {if ($loggedinuser.admin)}
            <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        {/if}
        <table>
            <tbody>
                <tr><td>{$_egyed.szulidostr} {$_egyed.szulhely}</td></tr>
                <tr><td>{$_egyed.munkakornev} {$_egyed.munkaviszonykezdetestr} {at('óta')}</td></tr>
            {if ($_egyed.havilevonas)}
                <tr><td>{at('Havi levonás')}: {$_egyed.havilevonas}</td></tr>
            {/if}
                <tr><td>{$_egyed.fizmodnev}</td></tr>
            {if ($_egyed.szamlatad)}
                <tr><td>Számlát ad</td></tr>
            {/if}
            {if ($_egyed.oraelmaradaskonyvelonek)}
                <tr><td>Óra elmaradásról értesítjük a könyvelőt</td></tr>
            {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{$_egyed.irszam} {$_egyed.varos}, {$_egyed.utca}</td></tr>
                <tr><td>{$_egyed.telefon}</td></tr>
                {if ($_egyed.email!=='')}<tr><td><a href="mailto:{$_egyed.email}" title="{at('Levélküldés')}">{$_egyed.email}</a></td></tr>{/if}
                {if ($_egyed.url!=='')}<tr><td>{$_egyed.url}</td></tr>{/if}
            </tbody>
        </table>
    </td>
</tr>