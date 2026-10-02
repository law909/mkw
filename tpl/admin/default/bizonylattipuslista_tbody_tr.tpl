<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
    </td>
    <td class="cell">
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
            <dl class="bizlista-adatok">
                <div class="bizlista-sor">
                    <dt>{at('Azonosító')}:</dt>
                    <dd>{$_egyed.id}</dd>
                </div>
                <div class="bizlista-sor">
                    <dt>{at('Rövid azonosító')}:</dt>
                    <dd>{$_egyed.azonosito}</dd>
                </div>
                <div class="bizlista-sor">
                    <dt>{at('Irány')}:</dt>
                    <dd>{if ($_egyed.irany > 0)}{at('bevét')}{elseif ($_egyed.irany < 0)}{at('kivét')}{else}{at('nincs')}{/if}</dd>
                </div>
                <div class="bizlista-sor">
                    <dt>{at('Nyomtatási forma')}:</dt>
                    <dd>{$_egyed.tplname}</dd>
                </div>
                <div class="bizlista-sor">
                    <dt>{at('2. nyomtatási forma')}:</dt>
                    <dd>{$_egyed.tplname2}{if ($_egyed.tplname2)} ({$_egyed.tplcaption2}){/if}</dd>
                </div>
                <div class="bizlista-sor">
                    <dt>{at('Email küldés sablonja')}:</dt>
                    <dd>{$_egyed.pdflevelsablonnev}</dd>
                </div>
            </dl>
        {/strip}
    </td>
    <td class="cell">
        <div class="bizlista-cimkek">
            {if ($_egyed.mozgat)}<span class="bizlista-jelveny">{at('készletet mozgat')}</span>{/if}
            {if ($_egyed.foglal)}<span class="bizlista-jelveny">{at('foglal')}</span>{/if}
            {if ($_egyed.penztmozgat)}<span class="bizlista-jelveny">{at('pénzt mozgat')}</span>{/if}
            {if ($_egyed.navbekuldendo)}<span class="bizlista-jelveny">{at('NAV-hoz beküldendő')}</span>{/if}
        </div>
    </td>
</tr>
