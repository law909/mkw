<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    {if ($shownavallapot)}
        <td class="cell{if ($_egyed.naveredmeny=='DONE')} greentext{/if}{if ($_egyed.naveredmeny=='ABORTED')} redtext{/if}">{$_egyed.naveredmeny}</td>
    {/if}
    {if ($showbizonylatstatuszeditor)}
        <td class="cell">
            <select id="BizonylatStatuszFuggobenEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit"
                    data-vanpartneremail="{if ($_egyed.partneremail)}1{else}0{/if}">
                <option value="">{at('válasszon')}</option>
                {foreach $_egyed.bizonylatstatuszlist as $_role}
                    <option value="{$_role.id}"
                            data-vanemailtemplate="{if ($_role.vanemailtemplate)}1{else}0{/if}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                {/foreach}
            </select>
        </td>
    {/if}
    <td class="cell{if ($_egyed.hibas)} tetelszamhiba{/if}">
        {if ($_egyed.editprinted || (!$_egyed.editprinted && !$_egyed.nyomtatva))}
            <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.id}</a>
        {else}
            {$_egyed.id}
        {/if}
        <a class="js-statusznaplobtn" href="#" data-id="{$_egyed.id}" title="{at('Bizonylat napló')}"><span class="ui-icon ui-icon-clipboard"></span></a>
        {if ($_egyed.nemrossz)}
            <a class="js-tetelellenorzes" href="/admin/bizonylatellenorzes/view?id={$_egyed.id|escape:'url'}" target="_blank"
               title="{at('Tételek ellenőrzése')}"><span class="ui-icon ui-icon-check"></span></a>
        {/if}
        {if (!$_egyed.hibas)}
            <a class="js-printbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
               title="{at('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
            {if ($showprint2)}
                {* a második forma nem jelöli nyomtatottnak a bizonylatot: az a fenti Nyomtat gomb dolga *}
                <a class="js-printbizonylat2" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="0"
                   title="{if ($tplcaption2)}{$tplcaption2}{else}{at('Nyomtat')}{/if}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
            {/if}
            {if ($showpdf)}
                {if (!$setup.pagedpdf)}
                    <a class="js-pdf" href="#" data-egyedid="{$_egyed.id}" data-oper="pdf" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
                       title="{at('PDF letöltés')}" target="_blank">PDF</a>
                {/if}
                <a class="js-emailpdf" href="#" data-egyedid="{$_egyed.id}" data-oper="emailpdf"
                   data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
                   title="{at('Küldés emailben')}" target="_blank"><span class="ui-icon ui-icon-mail-closed"></span></a>
            {/if}
            {if ($showemailbutton)}
                <a class="js-email" href="#" data-egyedid="{$_egyed.id}" title="{at('Email sablon küldése a partnernek')}"><span
                        class="ui-icon ui-icon-mail-open"></span></a>
            {/if}
            {if ($shownavallapot && $_egyed.navbekuldendo)}
                <a class="js-nav" href="#" data-egyedid="{$_egyed.id}" title="{at('NAV beküldés')}" target="_blank">NAV</a>
                <a class="js-navstat" href="#" data-egyedid="{$_egyed.id}" title="{at('NAV állapot lekérdezés')}" target="_blank">NAV stat</a>
            {/if}
            {if ($_egyed.nemrossz)}
                {if (($_egyed.bizonylattipusid=='megrendeles') || ($_egyed.bizonylattipusid=='b2brendeles'))}
                    <a class="js-printelolegbekero" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{at('Előleg bekérő')}" target="_blank"><span
                            class="ui-icon ui-icon-print"></span></a>
                {/if}
                {if ($showbackorder)}
                    <a class="js-backorder" href="#" data-egyedid="{$_egyed.id}" title="{at('Backorder')}"><span
                            class="ui-icon ui-icon-transferthick-e-w"></span></a>
                {/if}
                {if ($_egyed.bizonylattipusid=='szallmegr')}
                    <a class="js-mirexport" href="/admin/szallmegrfej/mirexport?id={$_egyed.id|escape:'url'}"
                       title="{at('Excel export')}" target="_blank">Xlsx</a>
                {/if}
                {if ($showslicemanufacturerbutton)}
                    <a class="js-slicemanufacturer" href="#" data-egyedid="{$_egyed.id}"
                       title="{at('Szétbontás gyártónként')}"><span class="ui-icon ui-icon-scissors"></span></a>
                {/if}
                {if ($showszallitobutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szallitofej" data-oper="inherit"
                       title="{at('Szállítólevél')}"><span{if (!$bizonylattipuslist['szallito'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['szallito']['azonosito']}</span></a>
                {/if}
                {if ($showszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szamlafej" data-oper="inherit"
                       title="{at('Számla')}"><span{if (!$bizonylattipuslist['szamla'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['szamla']['azonosito']}</span></a>
                {/if}
                {if ($showkeziszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="keziszamlafej" data-oper="inherit"
                       title="{at('Kézi számla')}"><span{if (!$bizonylattipuslist['keziszamla'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['keziszamla']['azonosito']}</span></a>
                {/if}
                {if ($showkivetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="kivetfej" data-oper="inherit" title="{at('Kivét')}"
                    ><span{if (!$bizonylattipuslist['kivet'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['kivet']['azonosito']}</span></a>
                {/if}
                {if ($showbevetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="bevetfej" data-oper="inherit" title="{at('Bevét')}"
                    ><span{if (!$bizonylattipuslist['bevet'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['bevet']['azonosito']}</span></a>
                {/if}
                {if ($showszallmegrbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szallmegrfej" data-oper="inherit"
                       title="{at('Szállítói megrendelés')}"><span{if (!$bizonylattipuslist['szallmegr'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['szallmegr']['azonosito']}</span></a>
                {/if}
                {if ($showcsomagbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="csomagfej" data-oper="inherit" title="{at('Csomag')}"
                    ><span{if (!$bizonylattipuslist['csomag'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['csomag']['azonosito']}</span></a>
                {/if}
                {if ($showfeketelistabutton)}
                    <a class="js-feketelista" href="#" data-email="{$_egyed.partneremail}" data-ip="{$_egyed.ip}" title="{at('Feketelista')}"
                    ><span
                            class="ui-icon ui-icon-alert"></span></a>
                {/if}
                {if ($showstorno)}
                    {if ($_egyed.naveredmeny=='DONE' || $_egyed.naveredmeny=='TESZT')}
                        <a class="js-stornobizonylat1" href="#" data-egyedid="{$_egyed.id}" data-egyednev="{$_egyed.bizonylattipusid}fej" data-oper="storno"
                           title="{at('Számlával egy tekintet alá eső okirat')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        <a class="js-stornobizonylat2" href="#" data-egyedid="{$_egyed.id}" data-egyednev="{$_egyed.bizonylattipusid}fej" data-oper="storno"
                           title="{at('Érvénytelenítő számla')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    {/if}
                {else}
                    <a class="js-rontbizonylat" href="#" data-egyedid="{$_egyed.id}" title="{at('Ront')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                {/if}
            {/if}
        {/if}
        <table>
            <tbody>
            {if ($showfelhasznalo)}
                <tr>
                    <td>{$_egyed.felhasznalonev}</td>
                </tr>
            {/if}
            <tr>
                <td colspan="2" class="mattable-important">
                    {$_egyed.partnernev}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    {$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca} {$_egyed.partnerhazszam}, {$_egyed.partnerorszagnev}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    {$_egyed.szallirszam} {$_egyed.szallvaros}, {$_egyed.szallutca} {$_egyed.szallhazszam}, {$_egyed.partnerszallorszagnev}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    {$_egyed.partnertelefon}
                </td>
            </tr>
            {if ($showeddigimegrendeleseiurl)}
                <tr>
                    <td colspan="2">
                        <a href="{$_egyed.tobbimegrendeleslink}" target="_blank">(eddigi megrendelései)</a>
                    </td>
                </tr>
            {/if}
            <tr>
                <td colspan="5" class="referrer">
                    {at('IP')}: {$_egyed.ip} {at('Ref.')}: {$_egyed.referrer}
                </td>
            </tr>
            <tr>
                <td>{at('Létrehozva')}:</td>
                <td>{$_egyed.createdby} {$_egyed.createdstr}</td>
            </tr>
            <tr>
                <td>{at('Módosítva')}:</td>
                <td>{$_egyed.updatedby} {$_egyed.lastmodstr}</td>
            </tr>
            {if ($_egyed.afaellenorzesnemkell)}
                <tr>
                    <td class="guestpartner">{at('ÁFA ellen. kikapcsolva')}:</td>
                    <td class="guestpartner">{$_egyed.afaellenorzesnemkellby} {$_egyed.afaellenorzesnemkellon}</td>
                </tr>
            {/if}
            {if ($_egyed.partnerfeketelistas)}
                <tr>
                    <td colspan="5">
                        <span class="feketelistas">{at('FEKETELISTÁS')}:</span> {$_egyed.partnerfeketelistaok}
                    </td>
                </tr>
            {/if}
            {if ((($_egyed.bizonylattipusid=='megrendeles') || ($_egyed.bizonylattipusid=='b2brendeles')) && ($_egyed.regmode > 0))}
                <tr>
                    <td colspan="5">
                        {at('Reg.mód')}
                        : {if ($_egyed.regmode == 1)}{at('vendég')}{elseif ($_egyed.regmode == 2)}{at('regisztrált')}{elseif ($_egyed.regmode == 3)}{at('bejelentkezett')}{/if}
                    </td>
                </tr>
            {/if}
            {if ($_egyed.termekertekeleskikuldve)}
                <tr>
                    <td colspan="5">
                        {at('Termék értékelés kérő kiküldve')}
                    </td>
                </tr>
            {/if}
            {if ($_egyed.belsomegjegyzes)}
                <tr>
                    <td colspan="5" class="guestpartner">
                        {$_egyed.belsomegjegyzes}
                    </td>
                </tr>
            {/if}
            {if ($_egyed.sysmegjegyzes)}
                <tr>
                    <td colspan="5" class="guestpartner">
                        {$_egyed.sysmegjegyzes}
                    </td>
                </tr>
            {/if}
            </tbody>
        </table>
        {if ($_egyed.hibas)}
            <div>{$_egyed.hibauzenetek}</div>
        {/if}
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td></td>
                <td>{$_egyed.raktarnev}</td>
            </tr>
            <tr>
                <td></td>
                <td>{$_egyed.fizmodnev}{if ($_egyed.isbarion)} <span class="barionstatus">({$_egyed.barionpaymentstatus})</span>{/if}{if ($_egyed.isstripe)}
                        <span class="barionstatus">({$_egyed.stripepaymentintentid})</span>{/if}</td>
            </tr>
            <tr>
                <td></td>
                <td>{if ($_egyed.penztmozgat)}{at('Kintlévőséget/tartozást képez')}{else}{at('Kintlévőséget/tartozást NEM képez')}{/if}</td>
            </tr>
            <tr>
                <td></td>
                <td>{$_egyed.szallitasimodnev}</td>
            </tr>
            {if ($_egyed.fedexservicetype)}
                <tr>
                    <td></td>
                    <td>{$_egyed.fedexservicetype}</td>
                </tr>
            {/if}
            {if (haveJog(90) && $_egyed.uzletkotonev)}
                <tr>
                    <td></td>
                    <td>{$_egyed.uzletkotonev} ({number_format($_egyed.uzletkotojutalek|default:0, 2, '.', ' ')} %)</td>
                </tr>
            {/if}
            {if (haveJog(90) && $_egyed.belsouzletkotonev)}
                <tr>
                    <td></td>
                    <td>(B){$_egyed.belsouzletkotonev} ({number_format($_egyed.belsouzletkotojutalek|default:0, 2, '.', ' ')} %)</td>
                </tr>
            {/if}
            {if ($showerbizonylatszam)}
                <tr>
                    <td>{at('Er.biz.szám')}:</td>
                    <td>{$_egyed.erbizonylatszam}</td>
                </tr>
            {/if}
            {if ($showfuvarlevelszam)}
                <tr>
                    <td>{at('Fuvarlevél')}:</td>
                    <td class="fuvarlevel">
                        {if ($_egyed.csomagkovetolink)}
                            <a href="{$_egyed.csomagkovetolink}" target="_blank">
                                {$_egyed.fuvarlevelszam}
                            </a>
                        {else}
                            {$_egyed.fuvarlevelszam}
                        {/if}
                        &nbsp;{if ($_egyed.isglsbekuldve)}<a href="#" class="js-delglsparcel" data-egyedid="{$_egyed.id}">GLS csomag törlés</a>{/if}
                        &nbsp;{if ($_egyed.isfedexbekuldve)}<a href="#" class="js-delfedexparcel" data-egyedid="{$_egyed.id}">Fedex csomag törlés</a>{/if}
                        &nbsp;{if ($_egyed.isfedexszallitas)}<a href="#" class="js-fedexrates" data-egyedid="{$_egyed.id}">{at('Fedex díj')}</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td>{at('GLS parcelid')}:</td>
                    <td class="fuvarlevel">{$_egyed.glsparcelid}</td>
                </tr>
                {if ($_egyed.foxpostbarcode)}
                    <tr>
                        <td>{at('Foxpost barcode')}:</td>
                        <td class="fuvarlevel">
                            {$_egyed.foxpostbarcode}
                        </td>
                    </tr>
                {/if}
                {if ($_egyed.glsparcellabelurl)}
                    <tr>
                        <td>{at('Címke')}:</td>
                        <td><a href="{$_egyed.glsparcellabelurl}" target="_blank">{at('letölt')}</a></td>
                    </tr>
                {/if}
                {if ($_egyed.fedexparcellabelurlek)}
                    <tr>
                        <td>{at('Címke')}:</td>
                        <td>
                            {foreach $_egyed.fedexparcellabelurlek as $_fedexlabelurl}
                            <a href="{$_fedexlabelurl}" target="_blank">
                                {at('letölt')}{if (count($_egyed.fedexparcellabelurlek) > 1)}&nbsp;{$_fedexlabelurl@iteration}.{/if}
                                </a>{if (!$_fedexlabelurl@last)},{/if}
                            {/foreach}
                        </td>
                    </tr>
                {/if}
                {if ($_egyed.shipdatestr)}
                    <tr>
                        <td>{at('Fedex szállítás')}:</td>
                        <td>{$_egyed.shipdatestr}</td>
                    </tr>
                {/if}
            {/if}
            {if ($showkupon)}
                <tr>
                    <td>{at('Kupon')}:</td>
                    <td class="kupon">{$_egyed.kupon}</td>
                </tr>
            {/if}
            <tr>
                <td>{at('Kelt')}:</td>
                <td>{$_egyed.keltstr}</td>
            </tr>
            {if ($showteljesites)}
                <tr>
                    <td>{at('Teljesítés')}:</td>
                    <td>{$_egyed.teljesitesstr}</td>
                </tr>
            {/if}
            {if ($showesedekesseg)}
                <tr class="mattable-important">
                    <td>{at('Esedékesség')}:</td>
                    <td>{$_egyed.esedekessegstr}</td>
                </tr>
            {/if}
            {if ($showhatarido)}
                <tr class="mattable-important">
                    <td>{at('Határidő')}:</td>
                    <td>{$_egyed.hataridostr}</td>
                </tr>
                <tr class="mattable-important">
                    <td>{at('Feladás')}:</td>
                    <td>{$_egyed.shipdatestr}</td>
                </tr>
            {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        <div class="kapcsbiz-szulo">
            <spam>{at('Szülő bizonylat')}:</spam>
            {if (!$_egyed.parbizonylat)}<strong>{at('nincs')}</strong>{/if}
            {if ($_egyed.parbizonylat)}
                <table>
                    <tbody>
                    <tr>
                        <td>
                            {if ($_egyed.parbizonylat.listaurl)}
                                <a href="{$_egyed.parbizonylat.listaurl}" target="_blank"
                                   title="{at('Ugrás a bizonylathoz')}">{$_egyed.parbizonylat.id}</a>
                            {else}
                                {$_egyed.parbizonylat.id}
                            {/if}
                        </td>
                        <td>{$_egyed.parbizonylat.tipusnev}</td>
                        <td>{$_egyed.parbizonylat.keltstr}</td>
                        <td>{$_egyed.parbizonylat.createdstr}</td>
                    </tr>
                    </tbody>
                </table>
            {/if}
        </div>
        {if ($_egyed.tarsbizonylat)}
            <div class="kapcsbiz-szulo">
                <span>{at('Társbizonylat')}:</span>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            {if ($_egyed.tarsbizonylat.listaurl)}
                                <a href="{$_egyed.tarsbizonylat.listaurl}" target="_blank"
                                   title="{at('Ugrás a bizonylathoz')}">{$_egyed.tarsbizonylat.id}</a>
                            {else}
                                {$_egyed.tarsbizonylat.id}
                            {/if}
                        </td>
                        <td>{$_egyed.tarsbizonylat.tipusnev}</td>
                        <td>{$_egyed.tarsbizonylat.keltstr}</td>
                        <td>{$_egyed.tarsbizonylat.createdstr}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        {/if}
        <div class="kapcsbiz-szarmazo">
            <span>{at('Keletkezett bizonylatok')}:</span>
            {if (!$_egyed.szarmazobizonylatcount)}<strong>{$_egyed.szarmazobizonylatcount}</strong>{/if}
            {if ($_egyed.szarmazobizonylatcount > 0)}
                {assign var="_rejtettdb" value=$_egyed.szarmazobizonylatcount-$szarmazobizonylatlimit}
                <table>
                    <tbody>
                    {foreach $_egyed.szarmazobizonylatok as $_sb}
                        <tr{if ($_sb@index >= $szarmazobizonylatlimit)} class="js-szarmazotobbi" style="display:none"{/if}>
                            <td>
                                {if ($_sb.listaurl)}
                                    <a href="{$_sb.listaurl}" target="_blank"
                                       title="{at('Ugrás a bizonylathoz')}">{$_sb.id}</a>
                                {else}
                                    {$_sb.id}
                                {/if}
                            </td>
                            <td>{$_sb.tipusnev}</td>
                            <td>{$_sb.keltstr}</td>
                            <td>{$_sb.createdstr}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
                {if ($_rejtettdb > 0)}
                    <a class="js-szarmazotobbigomb" href="#" title="{at('További')} {$_rejtettdb} {at('bizonylat')}">...</a>
                {/if}
            {/if}
        </div>
    </td>
    <td class="cell">
        {include 'dokumentumlinkek.tpl' doklinkek=$_egyed.doklinkek}
    </td>
    <td class="cell">
        <table>
            <tbody>
            {if ($_egyed.fizetve)}
                <tr>
                    <td>{at('Fizetve')}</td>
                </tr>
            {/if}
            {if ($setup.fakekintlevoseg && $_egyed.fakekintlevoseg && !$_egyed.fakekifizetve)}
                <tr>
                    <td><span class="lejartkiegyenlitetlen">{at('FAKE kintlévőség')}</span></td>
                </tr>
            {/if}
            {if ($setup.fakekintlevoseg && $_egyed.fakekifizetve)}
                <tr>
                    <td>{at('FAKE kifizetve')}</td>
                    <td>{$_egyed.fakekifizetesdatumstr}</td>
                </tr>
            {/if}
            <tr>
                <td></td>
                <td class="mattable-rightaligned">{$_egyed.valutanemnev}</td>
                {if ($showvalutanem)}
                    <td class="mattable-rightaligned hufprice">HUF</td>
                {/if}
            </tr>
            <tr>
                <td>{at('Nettó')}:</td>
                <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.netto, 2, '.', ' ')}</td>
                {if ($showvalutanem)}
                    <td class="mattable-rightaligned pricenowrap hufprice">{number_format($_egyed.nettohuf, 2, '.', ' ')}</td>
                {/if}
            </tr>
            <tr>
                <td>{at('ÁFA')}:</td>
                <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.afa, 2, '.', ' ')}</td>
                {if ($showvalutanem)}
                    <td class="mattable-rightaligned pricenowrap hufprice">{number_format($_egyed.afahuf, 2, '.', ' ')}
                    </td>
                {/if}
            </tr>
            <tr class="mattable-important">
                <td>{at('Bruttó')}:</td>
                <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.brutto, 2, '.', ' ')}</td>
                {if ($showvalutanem)}
                    <td class="mattable-rightaligned pricenowrap hufprice">{number_format($_egyed.bruttohuf, 2, '.', ' ')}</td>
                {/if}
            </tr>
            {if ($showvalutanem)}
                <tr>
                    <td class="hufprice">{at('Árfolyam')}:</td>
                    <td class="mattable-rightaligned pricenowrap hufprice">{number_format($_egyed.arfolyam, 2, '.', ' ')}</td>
                </tr>
            {/if}
            {if ($setup.bankpenztar)}
                {if ($_egyed.penzugyistatusz == -2)}
                    {$cls = 'lejartkiegyenlitetlen'}
                {elseif ($_egyed.penzugyistatusz == -1)}
                    {$cls = 'kiegyenlitetlen'}
                {elseif ($_egyed.penzugyistatusz == 0)}
                    {$cls = 'kiegyenlitett'}
                {elseif ($_egyed.penzugyistatusz == 1)}
                    {$cls = 'tulfizetett'}
                {/if}
                <tr>
                    <td class="{$cls}"><a href="#" data-id="{$_egyed.id}" class="js-folyoszamlabtn">{at('Egyenleg')}:</a></td>
                    <td class="mattable-rightaligned pricenowrap {$cls}"><a href="#" data-id="{$_egyed.id}"
                                                                            class="js-folyoszamlabtn">{number_format($_egyed.egyenleg, 2, '.', ' ')}</a></td>
                </tr>
                {if ($_egyed.kiegyenlitesurl)}
                    <tr>
                        <td colspan="2">
                            <a class="js-kiegyenlit" href="{$_egyed.kiegyenlitesurl}" target="_blank"
                               title="{at('Kiegyenlítő bizonylat rögzítése')}">{at('Kiegyenlít')}</a>
                        </td>
                    </tr>
                {/if}
                {if ($_egyed.ujbankbizonylaturl || $_egyed.ujpenztarbizonylaturl)}
                    <tr>
                        <td colspan="2">
                            {if ($_egyed.ujbankbizonylaturl)}
                                <a class="js-kiegyenlit" href="{$_egyed.ujbankbizonylaturl}" target="_blank"
                                   title="{at('Kiegyenlítő bizonylat rögzítése')}">{at('Új bankbizonylat')}</a>
                            {/if}
                            {if ($_egyed.ujpenztarbizonylaturl)}
                                <a class="js-kiegyenlit" href="{$_egyed.ujpenztarbizonylaturl}" target="_blank"
                                   title="{at('Kiegyenlítő bizonylat rögzítése')}">{at('Új pénztárbizonylat')}</a>
                            {/if}
                        </td>
                    </tr>
                {/if}
            {/if}
            </tbody>
        </table>
    </td>
    {if ($setup.osztottfizmod)}
        <td class="cell">
            <table>
                <tbody>
                {foreach $_egyed.osztottegyenlegek as $oe}
                    {if ($oe.penzugyistatusz == -2)}
                        {$cls = 'lejartkiegyenlitetlen'}
                    {elseif ($oe.penzugyistatusz == -1)}
                        {$cls = 'kiegyenlitetlen'}
                    {elseif ($oe.penzugyistatusz == 0)}
                        {$cls = 'kiegyenlitett'}
                    {elseif ($oe.penzugyistatusz == 1)}
                        {$cls = 'tulfizetett'}
                    {/if}
                    <tr>
                        <td class="{$cls}">{$oe.esedekesseg}:</td>
                        <td class="mattable-rightaligned pricenowrap {$cls}">{number_format($oe.egyenleg, 2, '.', ' ')}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </td>
    {/if}
</tr>