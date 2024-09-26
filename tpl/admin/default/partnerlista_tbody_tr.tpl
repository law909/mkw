<tr id="mattable-row_{$_partner.id}" data-egyedid="{$_partner.id}"{if ($_partner.vendeg)} class="guestpartner"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <div>
            <a class="mattable-editlink" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_partner.nev}</a>
            {if (!$_partner.anonym && !$_partner.anonymizalnikell)}
                <a class="js-anonym" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{at('Anonymizál')}">{at('Anonym')}</a>
            {/if}
            <span class="jobbra"><a class="mattable-dellink" href="#" data-partnerid="{$_partner.id}" data-oper="del" title="{at('Töröl')}"><span
                        class="ui-icon ui-icon-circle-minus"></span></a></span>
        </div>
        <table class="fullwidth">
            <tbody>
            <tr>
                <td colspan="2">{$_partner.vezeteknev} {$_partner.keresztnev}</td>
            </tr>
            <tr>
                <td>{at('Azonosító')}:</td>
                <td>{$_partner.id}</td>
                <td>{at('WooCommerce ID:')}</td>
                <td>{$_partner.wcid}</td>
            </tr>
            <tr>
                <td>{at('Üzletkötő')}:</td>
                <td>{$_partner.uzletkotonev}</td>
            </tr>
            <tr>
                <td>{at('Adószám')}:</td>
                <td>{$_partner.adoszam}</td>
            </tr>
            <tr>
                <td>{at('Csoportos adószám')}:</td>
                <td>{$_partner.csoportosadoszam}</td>
            </tr>
            <tr>
                <td>{at('Fizetési mód')}:</td>
                <td>{$_partner.fizmodnev}</td>
            </tr>
            <tr>
                <td>{at('Szállítási mód')}:</td>
                <td>{$_partner.szallitasimodnev}</td>
            </tr>
            <tr>
                <td>{at('Partner típus')}:</td>
                <td>{$_partner.partnertipusnev}</td>
            </tr>
            {if ($setup.multilang)}
                <tr>
                    <td>{at('Bizonylatok nyelve')}:</td>
                    <td>{$_partner.bizonylatnyelv}</td>
                </tr>
            {/if}
            {if ($setup.arsavok)}
                <tr>
                    <td>{at('Valutanem')}:</td>
                    <td>{$_partner.valutanemnev}</td>
                </tr>
                <tr>
                    <td>{at('Ársáv')}:</td>
                    <td>{$_partner.arsavnev}</td>
                </tr>
            {/if}
            {if ($_partner.apinev)}
                <tr>
                    <td>{at('API')}:</td>
                    <td>{$_partner.apinev}</td>
                </tr>
            {/if}
            </tbody>
        </table>
        <table>
            <tbody>
            <tr>
                <td>{at('Létrehozva')}:</td>
                <td>{$_partner.createdby} {$_partner.createdstr}</td>
            </tr>
            <tr>
                <td>{at('Módosítva')}:</td>
                <td>{$_partner.updatedby} {$_partner.lastmodstr}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {if ($setup.mptngy)}
            <div>Munkahely: {$_partner.mpt_munkahelynev}</div>
            <div>Számlázási név: {$_partner.szlanev}</div>
        {/if}
        <div>Számlázási cím: {$_partner.orszagnev}, {$_partner.cim}</div>
        <div>Szállítási cím: {$_partner.szallorszagnev}, {$_partner.szallcim}</div>
        {if ($_partner.lcim!=='')}
            <div>{at('Levelezési cím')}: {$_partner.lcim}</div>
        {/if}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{$_partner.telefon}</td>
            </tr>
            <tr>
                <td>{$_partner.mobil}</td>
            </tr>
            {if ($_partner.fax!=='')}
                <tr>
                <td>{at('Fax')}: {$_partner.fax}</td></tr>{/if}
            {if ($_partner.email!=='')}
                <tr>
                <td><a href="mailto:{$_partner.email}" title="{at('Levélküldés')}">{$_partner.email}</a></td></tr>{/if}
            {if ($_partner.honlap!=='')}
                <tr>
                <td><a href="{$_partner.honlap}" title="{at('Ugrás a honlapra')}" target="_blank">{$_partner.honlap}</a></td></tr>{/if}
            </tbody>
        </table>
    </td>
    {if ($setup.mptngy)}
        <td class="cell">
            {if ($_partner.mptngybefizetes > 0)}
                <div>Befizetett összeg: <b>{number_format($_partner.mptngybefizetes, 0, '.', ' ')}</b></div>
                <div>Befizetés dátuma: <b>{$_partner.mptngybefizetesdatum}</b></div>
                <div>Fiz.mód: <b>{$_partner.mptngybefizetesmodnev}</b></div>
            {/if}
        </td>
    {/if}
    <td class="cell">
        <span>{$_partner.megjegyzes}</span>
    </td>
    <td class="cell">
        {foreach $_partner.cimkek as $_cimke}
            {$_cimke.nev};
        {/foreach}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>
                    <a href="#"
                       data-id="{$_partner.id}"
                       data-flag="inaktiv"
                       class="js-flagcheckbox{if ($_partner.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>