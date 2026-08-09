{* Az UNAS-ból betöltött státusz- és módlista összerendelése, AJAX-ból töltve – lásd unasrendeles.js. *}
{function name="lekepezesblokk"}
    <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
        <strong>{$cim}</strong>
        {if (!$sorok)}
            <div>{at('Az UNAS nem adott vissza elemet.')}</div>
        {else}
            <table class="ui-widget ui-widget-content ui-corner-all unastable">
                <thead>
                <tr>
                    <th>{at('UNAS azonosító')}</th>
                    <th>{at('UNAS név')}</th>
                    <th>{at('Típus')}</th>
                    <th>{at('MKW megfelelő')}</th>
                </tr>
                </thead>
                <tbody>
                {foreach $sorok as $_sor}
                    <tr>
                        <td>
                            {$_sor.unasid}
                            <input name="{$prefix}unasid[{$_sor@index}]" type="hidden" value="{$_sor.unasid}">
                        </td>
                        <td>
                            {$_sor.unasnev}
                            {* redtext, nem ui-state-error-text: az utóbbi a hot-sneaks UI témában fehér *}
                            {if (!$_sor.aktiv)}<span class="redtext">({at('inaktív')})</span>{/if}
                        </td>
                        <td>{$_sor.unastipus}</td>
                        <td>
                            <select name="{$prefix}mkwid_{$_sor@index}">
                                <option value="">{at('nincs összerendelve')}</option>
                                {foreach $_sor.lista as $_item}
                                    <option value="{$_item.id}"{if ($_item.selected)} selected="selected"{/if}>{$_item.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        {/if}
    </div>
{/function}

{lekepezesblokk cim=at('Rendelésstátuszok') prefix='statusz' sorok=$statuszok}
{lekepezesblokk cim=at('Fizetési módok') prefix='fizmod' sorok=$fizmodok}
{lekepezesblokk cim=at('Szállítási módok') prefix='szallmod' sorok=$szallmodok}
