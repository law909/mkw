<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            {if ($loggedinuser.admin || ($_egyed.id == $loggedinuser.id))}
                <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
            {else}
                <span class="bizlista-partnernev mattable-important">{$_egyed.nev}</span>
            {/if}
        </div>
        {if ($loggedinuser.admin)}
            <div class="bizlista-muveletek">
                <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
            </div>
        {/if}
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Születés')}:</dt><dd>{$_egyed.szulidostr}{if ($_egyed.szulidostr && $_egyed.szulhely)} {/if}{$_egyed.szulhely}</dd></div>
            <div class="bizlista-sor"><dt>{at('Munkakör')}:</dt><dd>{$_egyed.munkakornev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Munkaviszony kezdete')}:</dt><dd>{$_egyed.munkaviszonykezdetestr}</dd></div>
            {if ($_egyed.havilevonas != 0)}
                <div class="bizlista-sor"><dt>{at('Havi levonás')}:</dt><dd>{$_egyed.havilevonas}</dd></div>
            {/if}
            {if ($_egyed.napilevonas != 0)}
                <div class="bizlista-sor"><dt>{at('Napi levonás')}:</dt><dd>{$_egyed.napilevonas}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Fizetési mód')}:</dt><dd>{$_egyed.fizmodnev}</dd></div>
            {if ($_egyed.szamlatad)}
                <div class="bizlista-sor bizlista-sor-teljes"><dd>{at('Számlát ad')}</dd></div>
            {/if}
            {if ($_egyed.oraelmaradaskonyvelonek)}
                <div class="bizlista-sor bizlista-sor-teljes"><dd>{at('Óra elmaradásról értesítjük a könyvelőt')}</dd></div>
            {/if}
            {if ($_egyed.autoszamla)}
                <div class="bizlista-sor bizlista-sor-teljes"><dd>{at('Bérlet eladáskor automatikusan készül számla')}</dd></div>
            {/if}
        </dl>
        {/strip}
    </td>
    <td class="cell">
        <div class="bizlista-partner">
            {if ($_egyed.irszam || $_egyed.varos || $_egyed.utca)}
                <div><span class="bizlista-cimke">{at('Cím')}:</span> {$_egyed.irszam} {$_egyed.varos}{if ($_egyed.utca)}, {$_egyed.utca}{/if}</div>
            {/if}
            {if ($_egyed.telefon)}
                <div>{$_egyed.telefon}</div>
            {/if}
            {if ($_egyed.email!=='')}
                <div><a href="mailto:{$_egyed.email}" title="{at('Levélküldés')}">{$_egyed.email}</a></div>
            {/if}
            {if ($_egyed.url!=='')}
                <div>{$_egyed.url}</div>
            {/if}
        </div>
    </td>
    {if ($setup.mptngy)}
        <td class="cell">
            {foreach $_egyed.mptngytemakorlist as $tl}
                <div>{$tl->getNev()}</div>
            {/foreach}
        </td>
        <td class="cell">
            {strip}
            <dl class="bizlista-adatok">
                <div class="bizlista-sor"><dt>{at('Maximum vállalt absztrakt')}:</dt><dd>{$_egyed.mptngymaxdb}</dd></div>
                <div class="bizlista-sor"><dt>{at('Kiosztott absztrakt')}:</dt><dd>{$_egyed.mptngykiosztottdb}</dd></div>
            </dl>
            {/strip}
        </td>
    {/if}
</tr>
