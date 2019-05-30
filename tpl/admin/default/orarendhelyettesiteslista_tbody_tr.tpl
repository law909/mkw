<tr id="mattable-row_{$_helyettesites.id}" data-egyedid="{$_helyettesites.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr>
                    <td>
                        <a class="mattable-editlink" href="#" data-orarendid="{$_helyettesites.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_helyettesites.datum} {$_helyettesites.oranev}</a>
                        <a class="mattable-dellink" href="#" data-orarendid="{$_helyettesites.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        <table>
                            <tbody>
                                {if ($_helyettesites.elmarad)}
                                <tr>
                                    <td>{at('Elmarad')}</td>
                                </tr>
                                {else}
                                <tr>
                                    <td>Helyettesítő: {$_helyettesites.helyettesitonev}</td>
                                </tr>
                                {/if}
                                {if ($_helyettesites.inaktiv)}
                                <tr>
                                    <td>{at('Inaktív')}</td>
                                </tr>
                                {/if}
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>