<tr id="mattable-row_{$_meret.id}" data-egyedid="{$_meret.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo termeklista-fo">
        <div class="termeklista-fej">
            {if ($_meret.kepurl)}
                <a class="termeklista-kep js-toflyout" href="{$mainurl}{$_meret.kepurl}" target="_blank"><img src="{$mainurl}{$_meret.kepurlsmall}" alt=""></a>
            {/if}
            <div class="termeklista-cim">
                <a class="mattable-editlink bizlista-nev" href="#" data-meretid="{$_meret.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_meret.nev}</a>
                <div class="bizlista-muveletek">
                    <a class="js-termeklistabutton" href="#" data-egyedid="{$_meret.id}" title="{at('Termékek')}"><span
                                class="ui-icon ui-icon-search"></span></a>
                    <a class="mattable-dellink" href="#" data-meretid="{$_meret.id}" data-oper="del" title="{at('Töröl')}"><span
                                class="ui-icon ui-icon-circle-minus"></span></a>
                </div>
            </div>
        </div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Charkód')}:</dt><dd>{$_meret.charkod}</dd></div>
            <div class="bizlista-sor"><dt>{at('Sorrend')}:</dt><dd>{$_meret.sorrend}</dd></div>
        </dl>
        {/strip}
    </td>
</tr>
