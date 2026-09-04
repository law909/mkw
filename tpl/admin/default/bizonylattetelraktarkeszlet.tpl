{* A tétel termékének/változatának raktárankénti készlete, foglalása és érkező mennyisége.
   A tartalmat termék- és változatváltáskor a /admin/bizonylattetel/getraktarkeszlet cseréli le. *}
{if ($lista)}
    <table class="tetelkeszlettabla">
        <tbody>
        <tr>
            <td></td>
            {foreach $lista as $elem}
                <td>{$elem.raktarnev}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Készlet')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.keszlet}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Foglalt')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.foglalt}</td>
            {/foreach}
        </tr>
        <tr>
            <td>{at('Érkezik')}</td>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.erkezik}</td>
            {/foreach}
        </tr>
        </tbody>
    </table>
{else}
    &ndash;
{/if}
