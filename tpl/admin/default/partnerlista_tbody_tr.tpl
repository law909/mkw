<tr id="mattable-row_{$_partner.id}" data-egyedid="{$_partner.id}"{if ($_partner.vendeg)} class="guestpartner"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_partner.nev}</a>
            {if ($_partner.vendeg)}
                <span class="bizlista-jelveny">{at('Vendég')}</span>
            {/if}
        </div>
        <div class="bizlista-muveletek">
            {if (!$_partner.anonym && !$_partner.anonymizalnikell)}
                <a class="js-anonym" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{at('Anonymizál')}">{at('Anonym')}</a>
            {/if}
            {if (!$_partner.vendeg && $_partner.email!=='')}
                <a class="js-sendjelszo" href="#" data-partnerid="{$_partner.id}" data-email="{$_partner.email|escape}"
                   title="{at('Új jelszó generálása és kiküldése emailben')}">{at('Új jelszó')}</a>
            {/if}
            <a class="mattable-dellink" href="#" data-partnerid="{$_partner.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Személynév')}:</dt><dd>{$_partner.vezeteknev}{if ($_partner.vezeteknev && $_partner.keresztnev)} {/if}{$_partner.keresztnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Azonosító')}:</dt><dd>{$_partner.id}</dd></div>
            <div class="bizlista-sor"><dt>{at('Címkék')}:</dt><dd class="bizlista-cimkek">
                {foreach $_partner.cimkek as $_cimke}
                    <span class="bizlista-cimkecsempe">{$_cimke.nev}</span>
                {/foreach}
            </dd></div>
            <div class="bizlista-sor"><dt>{at('Létrehozva')}:</dt><dd>{$_partner.createdby} {$_partner.createdstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Módosítva')}:</dt><dd>{$_partner.updatedby} {$_partner.lastmodstr}</dd></div>
        </dl>
        {/strip}
        {if ($_partner.megjegyzes)}
            <div class="bizlista-kapcs">
                <div class="bizlista-kapcs-cim">{at('Megjegyzés')}:</div>
                <div class="bizlista-megjegyzes">{$_partner.megjegyzes}</div>
            </div>
        {/if}
        {if ($_partner.doklinkek)}
            <div class="bizlista-kapcs">
                <div class="bizlista-kapcs-cim">{at('Dokumentumok')}:</div>
                {include 'dokumentumlinkek.tpl' doklinkek=$_partner.doklinkek}
            </div>
        {/if}
    </td>
    <td class="cell">
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Partner típus')}:</dt><dd>{$_partner.partnertipusnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Üzletkötő')}:</dt><dd>{$_partner.uzletkotonev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Adószám')}:</dt><dd>{$_partner.adoszam}</dd></div>
            <div class="bizlista-sor"><dt>{at('Csoportos adószám')}:</dt><dd>{$_partner.csoportosadoszam}</dd></div>
            <div class="bizlista-sor"><dt>{at('Fizetési mód')}:</dt><dd>{$_partner.fizmodnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Szállítási mód')}:</dt><dd>{$_partner.szallitasimodnev}</dd></div>
            {if ($setup.multilang)}
                <div class="bizlista-sor"><dt>{at('Bizonylatok nyelve')}:</dt><dd>{$_partner.bizonylatnyelv}</dd></div>
            {/if}
            {if ($setup.arsavok)}
                <div class="bizlista-sor"><dt>{at('Valutanem')}:</dt><dd>{$_partner.valutanemnev}</dd></div>
                <div class="bizlista-sor"><dt>{at('Ársáv')}:</dt><dd>{$_partner.arsavnev}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('API')}:</dt><dd>{$_partner.apinev}</dd></div>
            {if ($setup.mptngy)}
                <div class="bizlista-sor"><dt>{at('Egyetem')}:</dt><dd>{$_partner.mptngyegyetemnev}{if ($_partner.mptngyegyetemnev && $_partner.mptngykarnev)} - {/if}{$_partner.mptngykarnev}</dd></div>
                <div class="bizlista-sor"><dt>{at('Egyetem egyéb')}:</dt><dd>{$_partner.mptngyegyetemegyeb}</dd></div>
                {if ($_partner.mptngyphd)}
                    <div class="bizlista-sor bizlista-sor-teljes"><dd>{at('Phd hallgató')}</dd></div>
                {/if}
                {if ($_partner.mptngydiak)}
                    <div class="bizlista-sor bizlista-sor-teljes"><dd>{at('Hallgató')}</dd></div>
                {/if}
            {/if}
        </dl>
        {/strip}
    </td>
    <td class="cell">
        <div class="bizlista-partner">
            {if ($setup.mptngy && $_partner.szlanev)}
                <div><span class="bizlista-cimke">{at('Számlázási név')}:</span> {$_partner.szlanev}</div>
            {/if}
            {if ($_partner.orszagnev || $_partner.cim)}
                <div><span class="bizlista-cimke">{at('Számlázási')}:</span> {$_partner.orszagnev}{if ($_partner.orszagnev && $_partner.cim)}, {/if}{$_partner.cim}</div>
            {/if}
            {if ($_partner.szallorszagnev || $_partner.szallcim)}
                <div><span class="bizlista-cimke">{at('Szállítási')}:</span> {$_partner.szallorszagnev}{if ($_partner.szallorszagnev && $_partner.szallcim)}, {/if}{$_partner.szallcim}</div>
            {/if}
            {if ($_partner.lcim!=='')}
                <div><span class="bizlista-cimke">{at('Levelezési')}:</span> {$_partner.lcim}</div>
            {/if}
            {if ($_partner.email!=='')}
                <div><a href="mailto:{$_partner.email}" title="{at('Levélküldés')}">{$_partner.email}</a></div>
            {/if}
            {if ($_partner.telefon)}
                <div>{$_partner.telefon}</div>
            {/if}
            {if ($_partner.mobil)}
                <div>{$_partner.mobil}</div>
            {/if}
            {if ($_partner.fax!=='')}
                <div><span class="bizlista-cimke">{at('Fax')}:</span> {$_partner.fax}</div>
            {/if}
            {if ($_partner.honlap!=='')}
                <div><a href="{$_partner.honlap}" title="{at('Ugrás a honlapra')}" target="_blank">{$_partner.honlap}</a></div>
            {/if}
        </div>
    </td>
    {if ($setup.mptngy)}
        <td class="cell">
            {if ($_partner.mptngybefizetes > 0)}
                {strip}
                <dl class="bizlista-adatok">
                    <div class="bizlista-sor mattable-important"><dt>{at('Befizetett összeg')}:</dt><dd>{number_format($_partner.mptngybefizetes, 0, '.', ' ')}</dd></div>
                    <div class="bizlista-sor"><dt>{at('Befizetés dátuma')}:</dt><dd>{$_partner.mptngybefizetesdatum}</dd></div>
                    <div class="bizlista-sor"><dt>{at('Fiz.mód')}:</dt><dd>{$_partner.mptngybefizetesmodnev}</dd></div>
                </dl>
                {/strip}
            {/if}
        </td>
    {/if}
    <td class="cell">
        {* a bekapcsolt állapot jele a ui-state-hover, a partner.js ezt váltja *}
        <div class="bizlista-kapcsolocsoport">
            <a href="#" data-id="{$_partner.id}" data-flag="inaktiv"
               class="js-flagcheckbox bizlista-kapcsolo{if ($_partner.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a>
            <a href="#" data-id="{$_partner.id}" data-flag="gyarto"
               class="js-flagcheckbox bizlista-kapcsolo{if ($_partner.gyarto)} ui-state-hover{/if}">{at('Gyártó')}</a>
            <a href="#" data-id="{$_partner.id}" data-flag="szallito"
               class="js-flagcheckbox bizlista-kapcsolo{if ($_partner.szallito)} ui-state-hover{/if}">{at('Beszállító')}</a>
        </div>
    </td>
</tr>
