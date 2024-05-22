<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.partnernev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <table>
            <tbody>
            <tr>
                <td>{$_egyed.partneremail}</td>
            </tr>
            <tr>
                <td>{$_egyed.partnertelefon}</td>
            </tr>
            <tr>
                <td>{$_egyed.datum}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{$_egyed.rendezvenynev}</td>
            </tr>
            <tr>
                <td>{$_egyed.rendezvenykezdodatum}</td>
            </tr>
            <tr>
                <td>{$_egyed.rendezvenytanarnev}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            {if ($_egyed.varolistas)}
                <tr>
                    <td>
                        Várólistás
                    </td>
                </tr>
            {/if}
            {if ($_egyed.visszautalva)}
                <tr>
                    <td>
                        <span class="mattable-important">{at('Visszautalva')}</span> ({$_egyed.visszautalasdatum})<br>
                        {$_egyed.visszautalasfizmodnev}<br>
                        {$_egyed.visszautalasosszeghuf}<br>
                        {if ($_egyed.visszautalaspenztarnev)}
                            {$_egyed.visszautalaspenztarnev}
                            <br>
                            {$_egyed.visszautalaspenztarbizonylatszam}
                            <br>
                        {else}
                            {$_egyed.visszautalasbankszamlaszam}
                            <br>
                            {$_egyed.visszautalasbankbizonylatszam}
                            <br>
                        {/if}
                    </td>
                </tr>
            {/if}
            {if ($_egyed.lemondva)}
                <tr>
                    <td>
                        <span class="mattable-important">{at('Lemondva')}</span> ({$_egyed.lemondasdatum})<br>
                        {at('Oka')}: {$_egyed.lemondasoka}<br>
                    </td>
                </tr>
            {/if}
            {if ($_egyed.szamlazva)}
                <tr>
                    <td>
                        <span class="mattable-important">{at('Számlázva')}</span> ({$_egyed.szamlazasdatum}): {bizformat($_egyed.szamlazvaosszeghuf)}<br>
                        {$_egyed.szamlaszam}<br>
                        {at('Kért kelt')}: {$_egyed.szamlazvakelt}<br>
                        {at('Kért teljesítés')}: {$_egyed.szamlazvateljesites}<br>
                    </td>
                </tr>
            {/if}
            {if ($_egyed.fizetve)}
                <tr>
                    <td>
                        <span class="mattable-important">{at('Fizetve')}</span> ({$_egyed.fizetesdatum}): {bizformat($_egyed.fizetveosszeghuf)}<br>
                        {$_egyed.fizmodnev}<br>
                        {if ($_egyed.fizetvepenztarnev)}
                            {$_egyed.fizetvepenztarnev}
                            <br>
                            {$_egyed.fizetvepenztarbizonylatszam}
                            <br>
                        {else}
                            {$_egyed.fizetvebankszamlaszam}
                            <br>
                            {$_egyed.fizetvebankbizonylatszam}
                            <br>
                        {/if}
                    </td>
                </tr>
            {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            {if ($_egyed.emailregkoszono)}
                <tr>
                    <td>
                        Jelentkezés megköszönve
                    </td>
                </tr>
            {/if}
            {if (!$_egyed.fizetve)}
                {if ($_egyed.emaildijbekero)}
                    <tr>
                        <td>
                            Utolsó díjbekérő email: {$_egyed.emaildijbekerodatum}
                        </td>
                    </tr>
                {/if}
                <tr>
                    <td>
                        <a class="js-emaildijbekero" href="#" data-id="{$_egyed.id}">{at('Díjbekérő email')}</a>
                    </td>
                </tr>
            {/if}
            {if (!$_egyed.emailrendezvenykezdes)}
                <tr>
                    <td>
                        <a class="js-emailrendezvenykezdes" href="#" data-id="{$_egyed.id}">{at('Kezdés emlékeztető email')}</a>
                    </td>
                </tr>
            {/if}
            {if (!$_egyed.fizetve && !$_egyed.szamlazva && !$_egyed.lemondva && !$_egyed.visszautalva)}
                <tr>
                    <td>
                        <a class="js-fizetve" href="#" data-id="{$_egyed.id}">{at('Fizet')}</a>
                    </td>
                </tr>
            {/if}
            {if (!$_egyed.szamlazva && $_egyed.fizetve && haveJog(20))}
                <tr>
                    <td>
                        <a class="js-szamlazva" href="#" data-id="{$_egyed.id}">{at('Számláz')}</a>
                    </td>
                </tr>
            {/if}
            {if (!$_egyed.lemondva && !$_egyed.visszautalva)}
                <tr>
                    <td>
                        <a class="js-lemondva" href="#" data-id="{$_egyed.id}">{at('Lemond')}</a>
                    </td>
                </tr>
            {/if}
            {if ($_egyed.lemondva && $_egyed.fizetve && haveJog(20))}
                <tr>
                    <td>
                        {if (!$_egyed.visszautalva)}
                            <a class="js-visszautalva" href="#" data-id="{$_egyed.id}">{at('Visszautal')}</a>
                        {/if}
                    </td>
                </tr>
            {/if}
            </tbody>
        </table>
    </td>
</tr>