{* A kimutatások csoportosítás / megjelenítés / mentett nézet sora {mezocsoport}-on belül (reportgrouping.js).
   Paraméterek: alap1, alap2 (az 1. és 2. szint alapértéke), pivotertek (a kereszttábla cellatartalma választható) *}
{mezo cimke="Csoportosítás" for="Szint1Edit" szeles=true}
    <div class="kimutatas-szintek">
        {for $_i = 1 to $maxszint}
            {if ($_i > 1)}<span class="arbevetel-szintnyil">›</span>{/if}
            <select id="Szint{$_i}Edit" class="js-szint" name="szint[]" title="{$_i}. {at('szint')}">
                <option value="">{if ($_i == 1)}{at('nincs')}{else}–{/if}</option>
                {foreach $szintlist as $_szint}
                    <option value="{$_szint.id}" data-dim="{$_szint.dim}"{if (($_i == 1 && $_szint.id == ($alap1|default:'honap')) || ($_i == 2 && $_szint.id == ($alap2|default:'')))} selected="selected"{/if}>{$_szint.caption}</option>
                {/foreach}
            </select>
        {/for}
    </div>
{/mezo}
{mezo cimke="Megjelenítés" for="MegjelenitesEdit" ujsor=true}
    <div class="mattkarb-mezogomb">
        <select id="MegjelenitesEdit" name="megjelenites" title="{at('A kereszttáblához időszak-szint kell: az időszakok lesznek az oszlopok.')}">
            <option value="lista">{at('lista')}</option>
            <option value="kereszttabla">{at('kereszttábla')}</option>
        </select>
        {if ($pivotertek|default:false)}
            <select id="PivotertekEdit" name="pivotertek" title="{at('A kereszttábla celláiban')}">
                <option value="mennyiseg">{at('mennyiség')}</option>
                <option value="ertek">{at('érték')}</option>
            </select>
        {/if}
    </div>
{/mezo}
{mezo cimke="Mentett nézet" for="NezetEdit"}
    <div class="mattkarb-mezogomb">
        <select id="NezetEdit">
            <option value="">{at('válasszon')}</option>
        </select>
        <a href="#" class="js-nezetsave">{at('Mentés…')}</a>
        <a href="#" class="js-nezetdelete">{at('Törlés')}</a>
    </div>
{/mezo}
