{* A tétel termékének/változatának raktárankénti készlete, foglalása, szabad készlete és érkező mennyisége.
   A tartalmat termék- és változatváltáskor a /admin/bizonylattetel/getraktarkeszlet cseréli le.
   A foglalt és az érkező mennyiség linkje a foglaló / érkeztető bizonylatok modalját nyitja
   (mkwcomp.keszletBizonylatok); ehhez kell a termekid és a valtozatid. *}
{if ($nemmozgat|default:false)}
    <div class="tetelkeszlet-uzenet">{at('A termék nem mozgat készletet')}</div>
{elseif ($lista)}
    <div class="tetelkeszlet-raktarak">
        {foreach $lista as $elem}
            <div class="tetelkeszlet-raktar">
                <div class="tetelkeszlet-raktarnev">{$elem.raktarnev}</div>
                <div class="tetelkeszlet-szabad" title="{at($szabadkeszletfelirat)}">
                    <span class="tetelkeszlet-cimke">{at('Szabad')}</span>
                    <span class="tetelkeszlet-ertek{if ($elem.szabad < 0)} tetelkeszlet-negativ{/if}">{$elem.szabad}</span>
                </div>
                <dl class="tetelkeszlet-reszletek">
                    <dt>{at('Készlet')}</dt>
                    <dd>{$elem.keszlet}</dd>
                    <dt>{at('Foglalt')}</dt>
                    <dd>{if ($elem.foglalt != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termekid}" data-valtozatid="{$valtozatid}"
                                                    data-raktarid="{$elem.raktarid}" data-tipus="foglal">{$elem.foglalt}</a>{else}{$elem.foglalt}{/if}</dd>
                    <dt>{at('Érkezik')}</dt>
                    <dd>{if ($elem.erkezik != 0)}<a href="#" class="js-keszletbizonylatok" data-termekid="{$termekid}" data-valtozatid="{$valtozatid}"
                                                    data-raktarid="{$elem.raktarid}" data-tipus="erkezik">{$elem.erkezik}</a>{else}{$elem.erkezik}{/if}</dd>
                </dl>
            </div>
        {/foreach}
    </div>
{else}
    <div class="tetelkeszlet-uzenet">&ndash;</div>
{/if}
