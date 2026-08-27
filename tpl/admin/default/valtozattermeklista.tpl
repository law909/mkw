<table class="valtozattermeklista">
    <thead>
    <tr>
        <td>{at('Cikkszám')}</td>
        <td>{at('Megnevezés')}</td>
    </tr>
    </thead>
    <tbody>
    {foreach $lista as $_t}
        <tr>
            <td>{$_t.cikkszam}</td>
            <td><a href="{$_t.karburl}" target="_blank" title="{at('Ugrás a termékhez')}">{$_t.nev}</a></td>
        </tr>
    {foreachelse}
        <tr>
            <td colspan="2">{at('Nincs ilyen változatú termék.')}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
