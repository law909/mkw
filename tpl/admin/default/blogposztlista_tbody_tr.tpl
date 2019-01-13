<tr id="mattable-row_{$_blogposzt.id}" data-egyedid="{$_blogposzt.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{if ($_blogposzt.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_blogposzt.kepurl}" target="_blank"><img src="{$mainurl}{$_blogposzt.kepurlsmall}"/></a>{/if}</td>
                    <td>
                        <a class="mattable-editlink" href="#" data-blogposztid="{$_blogposzt.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_blogposzt.cim}</a>
                        <a class="mattable-dellink" href="#" data-blogposztid="{$_blogposzt.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="3">{$_blogposzt.termekfa1nev} | {$_blogposzt.termekfa2nev} | {$_blogposzt.termekfa3nev}</td>
                                </tr>
                                <tr>
                                    <td>{at('Link')}:</td>
                                    <td colspan="3"><a href="{$mainurl}/blog/{$_blogposzt.slug}" target="_blank">/blog/{$_blogposzt.slug}</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_blogposzt.megjelenesdatumstr}
    </td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td><a href="#" data-id="{$_blogposzt.id}" data-flag="lathato" class="js-flagcheckbox{if ($_blogposzt.lathato)} ui-state-hover{/if}">{at('Látható')}</a></td></tr>
            </tbody>
        </table>
    </td>
</tr>