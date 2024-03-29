<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr>
                    <td>
                        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.partnernev}</a>
                        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
            {if ($_egyed.elutasitva)}
                <tr>
                    <td>
                        Elutasítva
                    </td>
                </tr>
            {/if}
                {if ($_egyed.anonim)}
                    <tr>
                        <td>
                            Anonim
                        </td>
                    </tr>
                {/if}
                <tr><td>{at('Létrehozva')}:</td><td>{$_egyed.createdstr}</td></tr>
                <tr><td>{at('Módosítva')}:</td><td>{$_egyed.lastmodstr}</td></tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <a href="{$mainurl}/termek/{$_egyed.termekslug}" target="_blank">{$_egyed.termeknev}</a>
    </td>
    <td class="cell">
        <div>{$_egyed.ertekeles} pont</div>
        <div>{at('')}</div>
        <div>{$_egyed.szoveg}</div>
        <div>{at('')}</div>
        <div>{$_egyed.elony}</div>
        <div>{at('')}</div>
        <div>{$_egyed.hatrany}</div>
    </td>
    <td class="cell">
        {$_egyed.valasz}
    </td>
</tr>