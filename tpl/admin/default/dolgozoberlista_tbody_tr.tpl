<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.rontott)} class="rontott"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{if ($_egyed.rontott)}{at('Megtekint')}{else}{at('Szerkeszt')}{/if}">{$_egyed.dolgozonev|escape}</a>
            {if ($_egyed.rontott)}
                <span class="bizlista-jelveny">{at('Rontott')}</span>
            {/if}
        </div>
        {if (!$_egyed.rontott)}
            <div class="bizlista-muveletek">
                <a class="js-rontber" href="#" data-egyedid="{$_egyed.id}" title="{at('Ront')}"><span class="ui-icon ui-icon-cancel"></span></a>
            </div>
        {/if}
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Rögzítette')}:</dt><dd>{$_egyed.createdby|escape} {$_egyed.createdstr}</dd></div>
            {if ($_egyed.rontott)}
                <div class="bizlista-sor"><dt>{at('Rontotta')}:</dt><dd>{$_egyed.rontottby|escape} {$_egyed.rontottonstr}</dd></div>
            {/if}
        </dl>
        {/strip}
    </td>
    <td class="cell">
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Dátum')}:</dt><dd>{$_egyed.datumstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Jogcím')}:</dt><dd>{$_egyed.berjogcimnev|escape}</dd></div>
            <div class="bizlista-sor"><dt>{at('Megjegyzés')}:</dt><dd>{$_egyed.megjegyzes|escape}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell textalignright mattable-important">{if ($_egyed.rontott)}<s>{bizformat($_egyed.osszeg, 0)}</s>{else}{bizformat($_egyed.osszeg, 0)}{/if}</td>
</tr>
