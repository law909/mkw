<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.cim}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        <table>
            <tbody>
                <tr><td>{t('Link')}:</td><td><a href="{$_egyed.link}" target="_blank">{$_egyed.link}</a></td></tr>
                <tr><td>{t('Dátum')}:</td><td>{$_egyed.datumstr}</td></tr>
                <tr><td>{t('Forrás')}:</td><td>{$_egyed.forras}</td></tr>
                <tr><td>{t('Lead')}:</td><td>{$_egyed.lead}</td></tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td><a href="#" data-id="{$_egyed.id}" class="js-flagcheckbox{if ($_egyed.lathato)} ui-state-hover{/if}">{t('Látható')}</a></td></tr>
                <tr><td>{t('Első megjelenés')}:</td><td>{$_egyed.elsodatumstr}</td></tr>
                <tr><td>{t('Utolsó megjelenés')}:</td><td>{$_egyed.utolsodatumstr}</td></tr>
                <tr><td>{t('Sorrend')}:</td><td>{$_egyed.sorrend}</td></tr>
            </tbody>
        </table>
    </td>
</tr>