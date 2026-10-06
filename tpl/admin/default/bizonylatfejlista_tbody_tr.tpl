<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    {if ($shownavallapot)}
        <td class="cell{if ($_egyed.naveredmeny=='DONE')} greentext{/if}{if ($_egyed.naveredmeny=='ABORTED')} redtext{/if}"><span class="bizlista-nav">{$_egyed.naveredmeny}</span></td>
    {/if}
    {* a státuszválasztó közvetlenül a sor cellájában marad: a JS a legközelebbi tr data-egyedid-jéből veszi a bizonylatot *}
    {if ($showbizonylatstatuszeditor)}
        <td class="cell">
            <select id="BizonylatStatuszFuggobenEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit"
                    data-vanpartneremail="{if ($_egyed.partneremail)}1{else}0{/if}"{if ($_egyed.munkalapkiszamlazva|default:false)} disabled="disabled"{/if}>
                <option value="">{at('válasszon')}</option>
                {foreach $_egyed.bizonylatstatuszlist as $_role}
                    <option value="{$_role.id}"
                            data-vanemailtemplate="{if ($_role.vanemailtemplate)}1{else}0{/if}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                {/foreach}
            </select>
        </td>
    {/if}
    {if ($showmunkalapadatok)}
        <td class="cell">
            <div class="bizlista-munkalap">
                <div class="mattable-important">{$_egyed.munkalapegyediazonosito|escape}</div>
                <div>{$_egyed.munkalaptermeknev|escape}{if ($_egyed.munkalaptermekvaltozatnev)} ({$_egyed.munkalaptermekvaltozatnev|escape}){/if}</div>
                <dl class="bizlista-adatok">
                    <div class="bizlista-sor"><dt>{at('Km óra')}:</dt><dd>{$_egyed.munkalapkmoraallas}</dd></div>
                    <div class="bizlista-sor"><dt>{at('Köv. szerviz')}:</dt><dd>{$_egyed.munkalapkovetkezoszervizstr} {if ($_egyed.munkalapkovetkezoszervizkm)}/ {$_egyed.munkalapkovetkezoszervizkm} km{/if}</dd></div>
                </dl>
                {if ($_egyed.munkalaphibaleiras)}
                    <div class="bizlista-megjegyzes">{$_egyed.munkalaphibaleiras|escape|nl2br}</div>
                {/if}
                {if ($_egyed.munkalapkiszamlazva)}
                    <span class="bizlista-jelveny">{at('Kiszámlázva')}</span>
                {/if}
            </div>
        </td>
    {/if}
    <td class="cell bizlista-fo{if ($_egyed.hibas)} tetelszamhiba{/if}">
        <div class="bizlista-fej">
            {if (($_egyed.editprinted || (!$_egyed.editprinted && !$_egyed.nyomtatva)) && !($showmunkalapadatok && $_egyed.munkalapkiszamlazva))}
                <a class="mattable-editlink bizlista-szam" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.id}</a>
                {if ($setup.vonalkod|default)}
                    <a class="mattable-poseditlink" href="#" data-egyedid="{$_egyed.id}" title="{at('Módosítás vonalkódos tételfelvitellel')}">V</a>
                {/if}
            {else}
                <span class="bizlista-szam">{$_egyed.id}</span>
            {/if}
            {if ($showrendszeres && $_egyed.rendszeres)}
                <span class="bizlista-jelveny">{at('Rendszeres')}</span>
            {/if}
        </div>
        <div class="bizlista-muveletek">
            <a class="js-statusznaplobtn" href="#" data-id="{$_egyed.id}" title="{at('Bizonylat napló')}"><span class="ui-icon ui-icon-clipboard"></span></a>
            {if ($_egyed.nemrossz)}
                <a class="js-tetelellenorzes" href="/admin/bizonylatellenorzes/view?id={$_egyed.id|escape:'url'}" target="_blank"
                   title="{at('Tételek ellenőrzése')}"><span class="ui-icon ui-icon-check"></span></a>
                {if ($showcsomagolasilista|default)}
                    <a href="/admin/csomagolasilista/view?id={$_egyed.id|escape:'url'}" target="_blank"
                       title="{at('Csomagolási lista')}"><span class="ui-icon ui-icon-suitcase"></span></a>
                {/if}
            {/if}
            {if (!$_egyed.hibas)}
                <a class="js-printbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
                   title="{at('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
                {if ($showprint2)}
                    <a class="js-printbizonylat2" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="0"
                       title="{if ($tplcaption2)}{$tplcaption2}{else}{at('Nyomtat')}{/if}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
                {/if}
                {if ($showpdf)}
                    {if (!$setup.pagedpdf)}
                        <a class="js-pdf" href="#" data-egyedid="{$_egyed.id}" data-oper="pdf" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
                           title="{at('PDF letöltés')}" target="_blank">PDF</a>
                    {/if}
                    {if ($showemailpdf && $_egyed.partneremail)}
                        <a class="js-emailpdf" href="#" data-egyedid="{$_egyed.id}" data-oper="emailpdf"
                           data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}"
                           title="{at('Küldés emailben')}" target="_blank"><span class="ui-icon ui-icon-mail-closed"></span></a>
                    {/if}
                {/if}
                {if ($showemailbutton)}
                    <a class="js-email" href="#" data-egyedid="{$_egyed.id}" title="{at('Email sablon küldése a partnernek')}"><span
                            class="ui-icon ui-icon-mail-open"></span></a>
                {/if}
                {if ($shownavallapot && $_egyed.navbekuldendo && $_egyed.nyomtatva)}
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
                    {if ($showelolegbutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="elolegszamlafej" data-oper="inherit"
                           title="{at('Előlegszámla')}"><span{if (!$bizonylattipuslist['elolegszamla'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['elolegszamla']['azonosito']}</span></a>
                    {/if}
                    {if ($showboltieladasbutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="boltieladasfej" data-oper="inherit"
                           title="{at('Bolti eladás')}"><span{if (!$bizonylattipuslist['boltieladas'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['boltieladas']['azonosito']}</span></a>
                    {/if}
                    {if ($showautokiserobutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="autokiserofej" data-oper="inherit"
                           title="{at('Gépjármű kísérő')}"><span{if (!$bizonylattipuslist['autokisero'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['autokisero']['azonosito']}</span></a>
                    {/if}
                    {if ($showmegrendelesbutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="megrendelesfej" data-oper="inherit"
                           title="{at('Megrendelés')}"><span{if (!$bizonylattipuslist['megrendeles'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['megrendeles']['azonosito']}</span></a>
                    {/if}
                    {if ($showb2bmegrendelesbutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="b2brendelesfej" data-oper="inherit"
                           title="{at('B2B rendelés')}"><span{if (!$bizonylattipuslist['b2brendeles'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['b2brendeles']['azonosito']}</span></a>
                    {/if}
                    {if ($showwebshopmegrendelesbutton)}
                        <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="webshopbizfej" data-oper="inherit"
                           title="{at('Webshop rendelés')}"><span{if (!$bizonylattipuslist['webshopbiz'])} class="ui-icon ui-icon-arrowreturnthick-1-e"{/if}>{$bizonylattipuslist['webshopbiz']['azonosito']}</span></a>
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
                    <a class="js-cimkenyomtatas" href="/admin/bizonylatfej/cimke?id={$_egyed.id|escape:'url'}" target="_blank"
                       title="{at('Címke nyomtatás')}"><span class="ui-icon ui-icon-tag"></span></a>
                    {if ($showstorno)}
                        {if ($_egyed.naveredmeny=='DONE' || $_egyed.naveredmeny=='TESZT')}
                            <a class="js-stornobizonylat1" href="#" data-egyedid="{$_egyed.id}" data-egyednev="{$_egyed.bizonylattipusid}fej" data-oper="storno"
                               title="{at('Számlával egy tekintet alá eső okirat')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                            <a class="js-stornobizonylat2" href="#" data-egyedid="{$_egyed.id}" data-egyednev="{$_egyed.bizonylattipusid}fej" data-oper="storno"
                               title="{at('Érvénytelenítő számla')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        {/if}
                    {elseif (!($showmunkalapadatok && $_egyed.munkalapkiszamlazva))}
                        <a class="js-rontbizonylat" href="#" data-egyedid="{$_egyed.id}" title="{at('Ront')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    {/if}
                {/if}
            {/if}
        </div>
        <div class="bizlista-partner">
            <div class="bizlista-partnernev mattable-important">{$_egyed.partnernev}</div>
            <div><span class="bizlista-cimke">{at('Számlázási')}:</span> {$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca} {$_egyed.partnerhazszam}, {$_egyed.partnerorszagnev}</div>
            {if ($_egyed.szallirszam || $_egyed.szallvaros || $_egyed.szallutca || $_egyed.szallhazszam)}
                <div><span class="bizlista-cimke">{at('Szállítási')}:</span> {$_egyed.szallirszam} {$_egyed.szallvaros}, {$_egyed.szallutca} {$_egyed.szallhazszam}, {$_egyed.partnerszallorszagnev}</div>
            {/if}
            {if ($_egyed.partneremail)}
                <div><a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a></div>
            {/if}
            {if ($_egyed.partnertelefon)}
                <div>{$_egyed.partnertelefon}</div>
            {/if}
            {if ($showeddigimegrendeleseiurl)}
                <div><a href="{$_egyed.tobbimegrendeleslink}" target="_blank">(eddigi megrendelései)</a></div>
            {/if}
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            {if ($showfelhasznalo)}
                <div class="bizlista-sor"><dt>{at('Felhasználó')}:</dt><dd>{$_egyed.felhasznalonev}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Webshop')}:</dt><dd>{$_egyed.webshopnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('IP')}:</dt><dd class="referrer">{$_egyed.ip}</dd></div>
            <div class="bizlista-sor"><dt>{at('Ref.')}:</dt><dd class="referrer">{$_egyed.referrer}</dd></div>
            <div class="bizlista-sor"><dt>{at('Létrehozva')}:</dt><dd>{$_egyed.createdby} {$_egyed.createdstr}</dd></div>
            <div class="bizlista-sor"><dt>{at('Módosítva')}:</dt><dd>{$_egyed.updatedby} {$_egyed.lastmodstr}</dd></div>
        </dl>
        {/strip}
        {if ($_egyed.afaellenorzesnemkell || $_egyed.partnerfeketelistas || ((($_egyed.bizonylattipusid=='megrendeles') || ($_egyed.bizonylattipusid=='b2brendeles')) && ($_egyed.regmode > 0)) || $_egyed.termekertekeleskikuldve || $_egyed.belsomegjegyzes || $_egyed.sysmegjegyzes)}
            <div class="bizlista-jelzesek">
                {if ($_egyed.afaellenorzesnemkell)}
                    <div class="guestpartner">{at('ÁFA ellen. kikapcsolva')}: {$_egyed.afaellenorzesnemkellby} {$_egyed.afaellenorzesnemkellon}</div>
                {/if}
                {if ($_egyed.partnerfeketelistas)}
                    <div><span class="feketelistas">{at('FEKETELISTÁS')}:</span> {$_egyed.partnerfeketelistaok}</div>
                {/if}
                {if ((($_egyed.bizonylattipusid=='megrendeles') || ($_egyed.bizonylattipusid=='b2brendeles')) && ($_egyed.regmode > 0))}
                    <div>{at('Reg.mód')}: {if ($_egyed.regmode == 1)}{at('vendég')}{elseif ($_egyed.regmode == 2)}{at('regisztrált')}{elseif ($_egyed.regmode == 3)}{at('bejelentkezett')}{/if}</div>
                {/if}
                {if ($_egyed.termekertekeleskikuldve)}
                    <div>{at('Termék értékelés kérő kiküldve')}</div>
                {/if}
                {if ($_egyed.belsomegjegyzes)}
                    <div class="guestpartner">{$_egyed.belsomegjegyzes}</div>
                {/if}
                {if ($_egyed.sysmegjegyzes)}
                    <div class="guestpartner">{$_egyed.sysmegjegyzes}</div>
                {/if}
            </div>
        {/if}
        {if ($_egyed.hibas)}
            <div class="bizlista-hiba">
                <div>{$_egyed.hibauzenetek}</div>
                <a class="js-recheck" href="#" data-egyedid="{$_egyed.id}" title="{at('Újraellenőrzés')}">{at('Újraellenőrzés')}</a>
            </div>
        {/if}
    </td>
    <td class="cell">
        {strip}
        <dl class="bizlista-adatok">
            <div class="bizlista-sor"><dt>{at('Raktár')}:</dt><dd>{$_egyed.raktarnev}</dd></div>
            <div class="bizlista-sor"><dt>{at('Fizetési mód')}:</dt><dd>{$_egyed.fizmodnev}{if ($_egyed.isbarion)} <span class="barionstatus">({$_egyed.barionpaymentstatus})</span>{/if}{if ($_egyed.isstripe)} <span class="barionstatus">({$_egyed.stripepaymentintentid})</span>{/if}{if ($_egyed.unasfizetesstatusz)} <span class="{if ($_egyed.unasfizetve)}greentext{else}redtext{/if}">(UNAS: {$_egyed.unasfizetesstatusznev|escape:'html':'UTF-8':false})</span>{/if}</dd></div>
            {if ($_egyed.penztmozgatkapcsolo|default)}
                <div class="bizlista-sor bizlista-sor-teljes"><dd class="bizlista-kapcsolocsoport"><a href="#" data-id="{$_egyed.id}" class="js-penztmozgatkapcsolo bizlista-kapcsolo{if ($_egyed.penztmozgat)} ui-state-hover{/if}">{at('Kintlévőséget/tartozást képez')}</a></dd></div>
            {else}
                <div class="bizlista-sor bizlista-sor-teljes"><dd>{if ($_egyed.penztmozgat)}{at('Kintlévőséget/tartozást képez')}{else}{at('Kintlévőséget/tartozást NEM képez')}{/if}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Szállítási mód')}:</dt><dd>{$_egyed.szallitasimodnev}</dd></div>
            {if ($_egyed.fedexservicetype)}
                <div class="bizlista-sor"><dt>{at('Fedex')}:</dt><dd>{$_egyed.fedexservicetype}</dd></div>
            {/if}
            {if (haveJog(90) && $_egyed.uzletkotonev)}
                <div class="bizlista-sor"><dt>{at('Üzletkötő')}:</dt><dd>{$_egyed.uzletkotonev} ({number_format($_egyed.uzletkotojutalek|default:0, 2, '.', ' ')} %)</dd></div>
            {/if}
            {if (haveJog(90) && $_egyed.belsouzletkotonev)}
                <div class="bizlista-sor"><dt>{at('Belső üzletkötő')}:</dt><dd>(B){$_egyed.belsouzletkotonev} ({number_format($_egyed.belsouzletkotojutalek|default:0, 2, '.', ' ')} %)</dd></div>
            {/if}
            {if ($showerbizonylatszam)}
                <div class="bizlista-sor"><dt>{at('Er.biz.szám')}:</dt><dd>{$_egyed.erbizonylatszam}</dd></div>
            {/if}
            {if ($showfuvarlevelszam)}
                <div class="bizlista-sor"><dt>{at('Fuvarlevél')}:</dt><dd class="fuvarlevel">
                    {if ($_egyed.csomagkovetolink)}<a href="{$_egyed.csomagkovetolink}" target="_blank">{$_egyed.fuvarlevelszam}</a>{else}{$_egyed.fuvarlevelszam}{/if}
                    {if ($_egyed.isglsbekuldve)}<a href="#" class="js-delglsparcel bizlista-hivatkozas" data-egyedid="{$_egyed.id}">GLS csomag törlés</a>{/if}
                    {if ($_egyed.isfedexbekuldve)}<a href="#" class="js-delfedexparcel bizlista-hivatkozas" data-egyedid="{$_egyed.id}">Fedex csomag törlés</a>{/if}
                    {if ($_egyed.isfedexszallitas)}<a href="#" class="js-fedexrates bizlista-hivatkozas" data-egyedid="{$_egyed.id}">{at('Fedex díj')}</a>{/if}
                </dd></div>
                <div class="bizlista-sor"><dt>{at('GLS parcelid')}:</dt><dd class="fuvarlevel">{$_egyed.glsparcelid}</dd></div>
                {if ($_egyed.foxpostbarcode)}
                    <div class="bizlista-sor"><dt>{at('Foxpost barcode')}:</dt><dd class="fuvarlevel">{$_egyed.foxpostbarcode}</dd></div>
                {/if}
                {if ($_egyed.glsparcellabelurl)}
                    <div class="bizlista-sor"><dt>{at('Címke')}:</dt><dd><a href="{$_egyed.glsparcellabelurl}" target="_blank">{at('letölt')}</a></dd></div>
                {/if}
                {if ($_egyed.fedexparcellabelurlek)}
                    <div class="bizlista-sor"><dt>{at('Címke')}:</dt><dd>
                        {foreach $_egyed.fedexparcellabelurlek as $_fedexlabelurl}
                            <a href="{$_fedexlabelurl}" target="_blank">{at('letölt')}{if (count($_egyed.fedexparcellabelurlek) > 1)}&nbsp;{$_fedexlabelurl@iteration}.{/if}</a>{if (!$_fedexlabelurl@last)}, {/if}
                        {/foreach}
                    </dd></div>
                {/if}
                {if ($_egyed.shipdatestr)}
                    <div class="bizlista-sor"><dt>{at('Fedex szállítás')}:</dt><dd>{$_egyed.shipdatestr}</dd></div>
                {/if}
            {/if}
            {if ($showkupon)}
                <div class="bizlista-sor"><dt>{at('Kupon')}:</dt><dd class="kupon">{$_egyed.kupon}</dd></div>
            {/if}
            <div class="bizlista-sor"><dt>{at('Kelt')}:</dt><dd>{$_egyed.keltstr}</dd></div>
            {if ($showteljesites)}
                <div class="bizlista-sor"><dt>{at('Teljesítés')}:</dt><dd>{$_egyed.teljesitesstr}</dd></div>
            {/if}
            {if ($showesedekesseg)}
                <div class="bizlista-sor mattable-important"><dt>{at('Esedékesség')}:</dt><dd>{$_egyed.esedekessegstr}</dd></div>
            {/if}
            {if ($showbeerkezes)}
                <div class="bizlista-sor mattable-important"><dt>{at('Beérkezés')}:</dt><dd>{$_egyed.beerkezesstr}</dd></div>
            {/if}
            {if ($showhatarido)}
                <div class="bizlista-sor mattable-important"><dt>{at('Határidő')}:</dt><dd>{$_egyed.hataridostr}</dd></div>
                <div class="bizlista-sor mattable-important"><dt>{at('Feladás')}:</dt><dd>{$_egyed.shipdatestr}</dd></div>
            {/if}
        </dl>
        {/strip}
    </td>
    <td class="cell">
        <div class="bizlista-kapcs">
            <div class="bizlista-kapcs-cim">{at('Szülő bizonylat')}:{if (!$_egyed.parbizonylat)} <strong>{at('nincs')}</strong>{/if}</div>
            {if ($_egyed.parbizonylat)}
                <div class="bizlista-kapcs-tetel">
                    {if ($_egyed.parbizonylat.listaurl)}
                        <a href="{$_egyed.parbizonylat.listaurl}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$_egyed.parbizonylat.id}</a>
                    {else}
                        {$_egyed.parbizonylat.id}
                    {/if}
                    <span>{$_egyed.parbizonylat.tipusnev}</span>
                    <span class="bizlista-halvany">{$_egyed.parbizonylat.keltstr} · {$_egyed.parbizonylat.createdstr}</span>
                </div>
            {/if}
        </div>
        {if ($_egyed.tarsbizonylat)}
            <div class="bizlista-kapcs">
                <div class="bizlista-kapcs-cim">{at('Társbizonylat')}:</div>
                <div class="bizlista-kapcs-tetel">
                    {if ($_egyed.tarsbizonylat.listaurl)}
                        <a href="{$_egyed.tarsbizonylat.listaurl}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$_egyed.tarsbizonylat.id}</a>
                    {else}
                        {$_egyed.tarsbizonylat.id}
                    {/if}
                    <span>{$_egyed.tarsbizonylat.tipusnev}</span>
                    <span class="bizlista-halvany">{$_egyed.tarsbizonylat.keltstr} · {$_egyed.tarsbizonylat.createdstr}</span>
                </div>
            </div>
        {/if}
        <div class="bizlista-kapcs">
            <div class="bizlista-kapcs-cim">{at('Keletkezett bizonylatok')}:{if (!$_egyed.szarmazobizonylatcount)} <strong>{$_egyed.szarmazobizonylatcount}</strong>{/if}</div>
            {if ($_egyed.szarmazobizonylatcount > 0)}
                {assign var="_rejtettdb" value=$_egyed.szarmazobizonylatcount-$szarmazobizonylatlimit}
                {foreach $_egyed.szarmazobizonylatok as $_sb}
                    {* a "..." gomb a data-bizonylat alapján kapcsolja a rejtett tételeket, nem a DOM-szerkezet alapján *}
                    <div class="bizlista-kapcs-tetel{if ($_sb@index >= $szarmazobizonylatlimit)} js-szarmazotobbi{/if}"{if ($_sb@index >= $szarmazobizonylatlimit)} data-bizonylat="{$_egyed.id|escape}" style="display:none"{/if}>
                        {if ($_sb.listaurl)}
                            <a href="{$_sb.listaurl}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$_sb.id}</a>
                        {else}
                            {$_sb.id}
                        {/if}
                        <span>{$_sb.tipusnev}</span>
                        <span class="bizlista-halvany">{$_sb.keltstr} · {$_sb.createdstr}</span>
                    </div>
                {/foreach}
                {if ($_rejtettdb > 0)}
                    <a class="js-szarmazotobbigomb" href="#" data-bizonylat="{$_egyed.id|escape}" title="{at('További')} {$_rejtettdb} {at('bizonylat')}">...</a>
                {/if}
            {/if}
        </div>
        {if ($_egyed.doklinkek)}
            <div class="bizlista-kapcs">
                <div class="bizlista-kapcs-cim">{at('Dokumentumok')}:</div>
                {include 'dokumentumlinkek.tpl' doklinkek=$_egyed.doklinkek}
            </div>
        {/if}
    </td>
    <td class="cell">
        <table class="bizlista-osszegek">
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