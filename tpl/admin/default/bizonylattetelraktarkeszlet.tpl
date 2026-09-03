{* A tétel termékének/változatának raktárankénti készlete. A tartalmat termék- és
   változatváltáskor a /admin/bizonylattetel/getraktarkeszlet cseréli le. *}
{if ($lista)}
    <table class="tetelkeszlettabla">
        <tbody>
        <tr>
            {foreach $lista as $elem}
                <td>{$elem.raktarnev}</td>
            {/foreach}
        </tr>
        <tr>
            {foreach $lista as $elem}
                <td class="keszletoszlop">{$elem.keszlet}</td>
            {/foreach}
        </tr>
        </tbody>
    </table>
{else}
    &ndash;
{/if}
