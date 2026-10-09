<table class="mattkarb-adattabla">
    <thead>
    <tr>
        <th colspan="2">{at('Kapcsolódó költség')}</th>
        <th>{at('Csoport')}</th>
        <th>{at('Számítás alapja')}</th>
        <th class="mattable-rightaligned">{at('Ár')}</th>
        <th class="mattable-rightaligned">{at('Mennyiség')}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $kapcsolodokoltseglist as $_kk}
        <tr>
            <td><input id="KapcsolodoKoltseg{$_kk.id}" name="kapcsolodokoltsegek[]" type="checkbox"
                       value="{$_kk.id}"{if ($_kk.selected)} checked="checked"{/if}></td>
            <td><label for="KapcsolodoKoltseg{$_kk.id}">{$_kk.caption}</label></td>
            <td>{$_kk.csoportnev}</td>
            <td>{$_kk.szamitasalapnev}</td>
            <td class="mattable-rightaligned">{number_format($_kk.ar|default:0, 4, '.', ' ')}</td>
            <td class="mattable-rightaligned">
                <input type="hidden" name="kapcsolodokoltsegid[]" value="{$_kk.id}">
                <input id="KapcsolodoKoltsegMennyiseg{$_kk.id}" name="kkmennyiseg_{$_kk.id}"
                       type="number" step="0.0001" value="{$_kk.mennyiseg|default:''}"
                       title="{at('Kitöltve ez a számítás alapja a termék adata helyett.')}">
            </td>
        </tr>
    {foreachelse}
        <tr>
            <td colspan="6">{at('Nincs kapcsolódó költség rögzítve.')}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
<div class="mattkarb-megjegyzes">{at('A pipával jelölt költségek tartoznak a termékhez. A mennyiség kitöltve a számítás alapja a termék adata (pl. súlya) helyett.')}</div>
