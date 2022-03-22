<table id="mnrstaticpagetable_{$page.id}"  class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="mnrstaticpageid[]" type="hidden" value="{$page.id}">
        <input name="mnrstaticpageoper_{$page.id}" type="hidden" value="{$page.oper}">
        <tr>
            <td colspan="3" class="mattable-cell">
                <a class="js-valtozatdelbutton" href="#" data-id="{$page.id}"{if ($page.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpagenevEdit_{$page.id}">{at('Név')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpagenevEdit_{$page.id}" name="mnrstaticpagenev_{$page.id}" type="text" value="{$page.nev}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpageszlogen1Edit_{$page.id}">{at('Szlogen 1')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpagszlogen1Edit_{$page.id}" name="mnrstaticpageszlogen1_{$page.id}" type="text" value="{$page.szlogen1}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpageszlogen2Edit_{$page.id}">{at('Szlogen 2')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpagszlogen2Edit_{$page.id}" name="mnrstaticpageszlogen2_{$page.id}" type="text" value="{$page.szlogen2}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpageszoveg1Edit_{$page.id}">{at('Szöveg 1')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpageszoveg1Edit_{$page.id}" name="mnrstaticpageszoveg1_{$page.id}" type="text" value="{$page.szoveg1}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpageszoveg2Edit_{$page.id}">{at('Szöveg 2')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpageszoveg2Edit_{$page.id}" name="mnrstaticpageszoveg2_{$page.id}" type="text" value="{$page.szoveg2}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpageszoveg3Edit_{$page.id}">{at('Szöveg 3')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="" id="mnrstaticpageszoveg3Edit_{$page.id}" name="mnrstaticpageszoveg3_{$page.id}" type="text" value="{$page.szoveg3}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="mnrstaticpagetartalomEdit_{$page.id}">{at('Tartalom')}:</label>
            </td>
            <td class="mattable-cell">
                <textarea class="" id="mnrstaticpagetartalomEdit_{$page.id}" name="mnrstaticpagetartalom_{$page.id}">{$page.tartalom}</textarea>
            </td>
        </tr>

        {if ($setup.multilang)}
            <tr>
                <td>
                {foreach $page.translations as $translation}
                    {include 'mnrstaticpagetranslationkarb.tpl'}
                {/foreach}
                </td>
                <td>
                <a class="js-pagetranslationnewbutton" href="#" title="{at('Új fordítás')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
                </td>
            </tr>
        {/if}



    </tbody>
</table>
{if ($page.oper=='add')}
    <a class="js-mnrstaticpagenewbutton" href="#" title="{at('Új')}" data-mnrstaticid="{$page.id}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}