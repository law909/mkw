<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($_egyed.kepurl)}
            <div class="balra">
                <a class="js-toflyout" href="{$_egyed.kepurl}" target="_blank">
                    <img src="{$_egyed.kepurlsmall}" alt="{$_egyed.kepleiras}" title="{$_egyed.kepleiras}"/>
                </a>
            </div>
        {/if}
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.cim}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <table>
            <tbody>
            <tr>
                <td>{at('Link')}:</td>
                <td><a href="{$_egyed.link}" target="_blank">{$_egyed.link}</a></td>
            </tr>
            <tr>
                <td>{at('Dátum')}:</td>
                <td>{$_egyed.datumstr}</td>
            </tr>
            <tr>
                <td>{at('Forrás')}:</td>
                <td>{$_egyed.forras}</td>
            </tr>
            <tr>
                <td>{at('Lead')}:</td>
                <td>{$_egyed.lead}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" class="js-flagcheckbox{if ($_egyed.lathato)} ui-state-hover{/if}">{at('Látható')}</a></td>
            </tr>
            <tr>
                <td>{at('Első megjelenés')}:</td>
                <td>{$_egyed.elsodatumstr}</td>
            </tr>
            <tr>
                <td>{at('Utolsó megjelenés')}:</td>
                <td>{$_egyed.utolsodatumstr}</td>
            </tr>
            <tr>
                <td>{at('Sorrend')}:</td>
                <td>{$_egyed.sorrend}</td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>