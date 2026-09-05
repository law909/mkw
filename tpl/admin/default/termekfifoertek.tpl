{* A termék FIFO készletértéke raktáranként, csak olvasható – a termék karbantartó árak fülén.
   A számot az éjszakai cron frissíti; a gomb egyetlen termékre kéri az azonnali újraszámolást. *}
<div class="matt-hseparator"></div>
<div>
    <h4>{at('FIFO készletérték')}</h4>
    <a class="js-fiforecalcbutton" href="/admin/keszletertek/recalctermek"
       data-termekid="{$egyed.id}">{at('Készletérték újraszámolása')}</a>
    <span class="js-fifouzenet"></span>
    <table id="fifoertektabla">
        <thead>
        <tr>
            <th>{at('Raktár')}</th>
            <th>{at('Változat')}</th>
            <th class="keszletoszlop">{at('Készlet')}</th>
            <th class="keszletoszlop">{at('Egységérték')}</th>
            <th class="keszletoszlop">{at('Érték')}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $egyed.fiforaktarak as $sor}
            <tr>
                <td>{$sor.raktarnev}</td>
                <td>{$sor.valtozatnev}</td>
                <td class="keszletoszlop">{$sor.mennyiseg}</td>
                <td class="keszletoszlop{if $sor.becsult} redtext{/if}">{$sor.egysegertek}</td>
                <td class="keszletoszlop">{$sor.ertek}</td>
            </tr>
        {foreachelse}
            <tr>
                <td colspan="5">{at('Nincs FIFO készletérték erre a termékre.')}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
