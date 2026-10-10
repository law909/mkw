<div id="mattkarb-header">
    <h3>{at('Bizonylattípus')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#MezokTab">{at('Látható mezők')}</a></li>
            <li><a href="#GombokTab">{at('Gombok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Azonosító" for="IdEdit" ujsor=true}
                    <input id="IdEdit" name="id" type="text" size="30" maxlength="30" value="{$egyed.id}"
                        required="required"{if ($oper !== 'add')} readonly="readonly"{/if}>
                {/mezo}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="40" maxlength="100" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Rövid azonosító" for="AzonositoEdit" ujsor=true}
                    <input id="AzonositoEdit" name="azonosito" type="text" size="10" maxlength="10" value="{$egyed.azonosito}">
                {/mezo}
                {mezo cimke="Irány" for="IranyEdit"}
                    <select id="IranyEdit" name="irany">
                        <option value="-1"{if ($egyed.irany == -1)} selected="selected"{/if}>{at('kivét (-1)')}</option>
                        <option value="0"{if ($egyed.irany == 0)} selected="selected"{/if}>{at('nincs (0)')}</option>
                        <option value="1"{if ($egyed.irany == 1)} selected="selected"{/if}>{at('bevét (1)')}</option>
                    </select>
                {/mezo}
                {mezo cimke="Kezdő sorszám" for="KezdosorszamEdit" ujsor=true}
                    <input id="KezdosorszamEdit" name="kezdosorszam" type="number" step="1" value="{$egyed.kezdosorszam}">
                {/mezo}
                {mezo cimke="Példányszám" for="PeldanyszamEdit"}
                    <input id="PeldanyszamEdit" name="peldanyszam" type="number" step="1" value="{$egyed.peldanyszam}">
                {/mezo}
                {mezo cimke="Nyomtatási sablon" for="TplnameEdit" ujsor=true}
                    <input id="TplnameEdit" name="tplname" type="text" size="40" maxlength="200" value="{$egyed.tplname}">
                {/mezo}
                {mezo cimke="Nyomtatási sablon (en)" for="Tplname_l1Edit"}
                    <input id="Tplname_l1Edit" name="tplname_l1" type="text" size="40" maxlength="200" value="{$egyed.tplname_l1}">
                {/mezo}
                {mezo cimke="2. nyomtatási sablon" for="Tplname2Edit" ujsor=true}
                    <input id="Tplname2Edit" name="tplname2" type="text" size="40" maxlength="200" value="{$egyed.tplname2}">
                {/mezo}
                {mezo cimke="2. nyomtatási sablon (en)" for="Tplname2_l1Edit"}
                    <input id="Tplname2_l1Edit" name="tplname2_l1" type="text" size="40" maxlength="200" value="{$egyed.tplname2_l1}">
                {/mezo}
                {mezo cimke="2. nyomtatás gomb felirata" for="Tplcaption2Edit" szeles=true}
                    <input id="Tplcaption2Edit" name="tplcaption2" type="text" size="60" maxlength="255" value="{$egyed.tplcaption2}">
                {/mezo}
                {mezo cimke="Nyomtatni kell" for="NyomtatniEdit" ujsor=true}
                    <input id="NyomtatniEdit" name="nyomtatni" type="checkbox"{if ($egyed.nyomtatni)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Készletet mozgat" for="MozgatEdit"}
                    <input id="MozgatEdit" name="mozgat" type="checkbox"{if ($egyed.mozgat)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Foglal" for="FoglalEdit" ujsor=true}
                    <input id="FoglalEdit" name="foglal" type="checkbox"{if ($egyed.foglal)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Pénzt mozgat" for="PenztmozgatEdit"}
                    <input id="PenztmozgatEdit" name="penztmozgat" type="checkbox"{if ($egyed.penztmozgat)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Nyomtatás után is szerkeszthető" for="EditprintedEdit" ujsor=true}
                    <input id="EditprintedEdit" name="editprinted" type="checkbox"{if ($egyed.editprinted)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kelt ellenőrzése" for="CheckkeltEdit"}
                    <input id="CheckkeltEdit" name="checkkelt" type="checkbox"{if ($egyed.checkkelt)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Email küldés mentés után" for="SendemailEdit" ujsor=true}
                    <input id="SendemailEdit" name="sendemail" type="checkbox"{if ($egyed.sendemail)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="NAV-hoz beküldendő" for="NavbekuldendoEdit"}
                    <input id="NavbekuldendoEdit" name="navbekuldendo" type="checkbox"{if ($egyed.navbekuldendo)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Automatikus pénztárbizonylat" for="AutopenztarbizonylatEdit" ujsor=true}
                    <input id="AutopenztarbizonylatEdit" name="autopenztarbizonylat" type="checkbox"{if ($egyed.autopenztarbizonylat)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kapcsolódó költséget számol" for="KellkapcsolodokoltsegetszamolniEdit"}
                    <input id="KellkapcsolodokoltsegetszamolniEdit" name="kellkapcsolodokoltsegetszamolni" type="checkbox"{if ($egyed.kellkapcsolodokoltsegetszamolni)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Tétel nélkül nem menthető" for="TetelkotelezoEdit" ujsor=true}
                    <input id="TetelkotelezoEdit" name="tetelkotelezo" type="checkbox"{if ($egyed.tetelkotelezo)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kintlévőség/tartozás kapcsoló a listán" for="ShowpenztmozgatEdit"}
                    <input id="ShowpenztmozgatEdit" name="showpenztmozgat" type="checkbox"{if ($egyed.showpenztmozgat)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Csomagolási lista" for="ShowcsomagolasilistaEdit" ujsor=true}
                    <input id="ShowcsomagolasilistaEdit" name="showcsomagolasilista" type="checkbox"{if ($egyed.showcsomagolasilista)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Megjegyzés megjelenik nyomtatásban" for="MegjegyzesnyomtatasbanEdit"}
                    <input id="MegjegyzesnyomtatasbanEdit" name="megjegyzesnyomtatasban" type="checkbox"{if ($egyed.megjegyzesnyomtatasban)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke='"Rögzítve" státusz' for="RogzitvestatuszEdit" szeles=true}
                    <select id="RogzitvestatuszEdit" name="rogzitvestatusz">
                        <option value="">{at('a Beállítások szerint')}</option>
                        {foreach $egyed.rogzitvestatuszlist as $_st}
                            <option value="{$_st.id}"{if ($_st.selected)} selected="selected"{/if}>{$_st.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke='"Teljesíthető" státusz' for="TeljesithetostatuszEdit" szeles=true}
                    <select id="TeljesithetostatuszEdit" name="teljesithetostatusz">
                        <option value="">{at('a Beállítások szerint')}</option>
                        {foreach $egyed.teljesithetostatuszlist as $_st}
                            <option value="{$_st.id}"{if ($_st.selected)} selected="selected"{/if}>{$_st.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke='"Backorder" státusz' for="BackorderstatuszEdit" szeles=true}
                    <select id="BackorderstatuszEdit" name="backorderstatusz">
                        <option value="">{at('a Beállítások szerint')}</option>
                        {foreach $egyed.backorderstatuszlist as $_st}
                            <option value="{$_st.id}"{if ($_st.selected)} selected="selected"{/if}>{$_st.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Email küldés sablonja" for="PdflevelsablonEdit" szeles=true}
                    <select id="PdflevelsablonEdit" name="pdflevelsablon">
                        <option value="">{at('nincs (nem küldhető emailben)')}</option>
                        {foreach $egyed.pdflevelsablonlist as $_sablon}
                            <option value="{$_sablon.id}"{if ($_sablon.selected)} selected="selected"{/if}>{$_sablon.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="MezokTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Teljesítés" for="ShowteljesitesEdit" ujsor=true}
                    <input id="ShowteljesitesEdit" name="showteljesites" type="checkbox"{if ($egyed.showteljesites)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Esedékesség" for="ShowesedekessegEdit"}
                    <input id="ShowesedekessegEdit" name="showesedekesseg" type="checkbox"{if ($egyed.showesedekesseg)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Határidő" for="ShowhataridoEdit" ujsor=true}
                    <input id="ShowhataridoEdit" name="showhatarido" type="checkbox"{if ($egyed.showhatarido)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Státusz szerkesztő" for="ShowbizonylatstatuszeditorEdit"}
                    <input id="ShowbizonylatstatuszeditorEdit" name="showbizonylatstatuszeditor" type="checkbox"{if ($egyed.showbizonylatstatuszeditor)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Üzenet" for="ShowuzenetEdit" ujsor=true}
                    <input id="ShowuzenetEdit" name="showuzenet" type="checkbox"{if ($egyed.showuzenet)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Szállítási cím" for="ShowszallitasicimEdit"}
                    <input id="ShowszallitasicimEdit" name="showszallitasicim" type="checkbox"{if ($egyed.showszallitasicim)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Eredeti bizonylatszám" for="ShowerbizonylatszamEdit" ujsor=true}
                    <input id="ShowerbizonylatszamEdit" name="showerbizonylatszam" type="checkbox"{if ($egyed.showerbizonylatszam)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Fuvarlevélszám" for="ShowfuvarlevelszamEdit"}
                    <input id="ShowfuvarlevelszamEdit" name="showfuvarlevelszam" type="checkbox"{if ($egyed.showfuvarlevelszam)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Haszonszázalék" for="ShowhaszonszazalekEdit" ujsor=true}
                    <input id="ShowhaszonszazalekEdit" name="showhaszonszazalek" type="checkbox"{if ($egyed.showhaszonszazalek)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kupon" for="ShowkuponEdit"}
                    <input id="ShowkuponEdit" name="showkupon" type="checkbox"{if ($egyed.showkupon)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Dolgozó" for="ShowfelhasznaloEdit"}
                    <input id="ShowfelhasznaloEdit" name="showfelhasznalo" type="checkbox"{if ($egyed.showfelhasznalo)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Garanciális adatok" for="ShowgarancialisadatokEdit" ujsor=true}
                    <input id="ShowgarancialisadatokEdit" name="showgarancialisadatok" type="checkbox"{if ($egyed.showgarancialisadatok)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Eddigi megrendelései link" for="ShoweddigimegrendeleseiurlEdit"}
                    <input id="ShoweddigimegrendeleseiurlEdit" name="showeddigimegrendeleseiurl" type="checkbox"{if ($egyed.showeddigimegrendeleseiurl)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Munkalap adatok" for="ShowmunkalapadatokEdit" ujsor=true}
                    <input id="ShowmunkalapadatokEdit" name="showmunkalapadatok" type="checkbox"{if ($egyed.showmunkalapadatok)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beérkezés dátuma" for="ShowbeerkezesEdit"}
                    <input id="ShowbeerkezesEdit" name="showbeerkezes" type="checkbox"{if ($egyed.showbeerkezes)} checked="checked"{/if}>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="GombokTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Számla" for="ShowszamlabuttonEdit" ujsor=true}
                    <input id="ShowszamlabuttonEdit" name="showszamlabutton" type="checkbox"{if ($egyed.showszamlabutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kézi számla" for="ShowkeziszamlabuttonEdit"}
                    <input id="ShowkeziszamlabuttonEdit" name="showkeziszamlabutton" type="checkbox"{if ($egyed.showkeziszamlabutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Szállítólevél" for="ShowszallitobuttonEdit" ujsor=true}
                    <input id="ShowszallitobuttonEdit" name="showszallitobutton" type="checkbox"{if ($egyed.showszallitobutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kivét" for="ShowkivetbuttonEdit"}
                    <input id="ShowkivetbuttonEdit" name="showkivetbutton" type="checkbox"{if ($egyed.showkivetbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Bevét" for="ShowbevetbuttonEdit" ujsor=true}
                    <input id="ShowbevetbuttonEdit" name="showbevetbutton" type="checkbox"{if ($egyed.showbevetbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Szállítói megrendelés" for="ShowszallmegrbuttonEdit"}
                    <input id="ShowszallmegrbuttonEdit" name="showszallmegrbutton" type="checkbox"{if ($egyed.showszallmegrbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Bolti eladás" for="ShowboltieladasbuttonEdit" ujsor=true}
                    <input id="ShowboltieladasbuttonEdit" name="showboltieladasbutton" type="checkbox"{if ($egyed.showboltieladasbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Csomag" for="ShowcsomagbuttonEdit"}
                    <input id="ShowcsomagbuttonEdit" name="showcsomagbutton" type="checkbox"{if ($egyed.showcsomagbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Előlegszámla" for="ShowelolegbuttonEdit" ujsor=true}
                    <input id="ShowelolegbuttonEdit" name="showelolegbutton" type="checkbox"{if ($egyed.showelolegbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Előleg beszámítás" for="ShowelolegbeszamitasEdit"}
                    <input id="ShowelolegbeszamitasEdit" name="showelolegbeszamitas" type="checkbox"{if ($egyed.showelolegbeszamitas)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Hiteles eladás" for="ShowhiteleseladasbuttonEdit"}
                    <input id="ShowhiteleseladasbuttonEdit" name="showhiteleseladasbutton" type="checkbox"{if ($egyed.showhiteleseladasbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Stornó" for="ShowstornoEdit" ujsor=true}
                    <input id="ShowstornoEdit" name="showstorno" type="checkbox"{if ($egyed.showstorno)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Gépjármű kísérő" for="ShowautokiserobuttonEdit"}
                    <input id="ShowautokiserobuttonEdit" name="showautokiserobutton" type="checkbox"{if ($egyed.showautokiserobutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Megrendelés" for="ShowmegrendelesbuttonEdit" ujsor=true}
                    <input id="ShowmegrendelesbuttonEdit" name="showmegrendelesbutton" type="checkbox"{if ($egyed.showmegrendelesbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="B2B rendelés" for="Showb2bmegrendelesbuttonEdit"}
                    <input id="Showb2bmegrendelesbuttonEdit" name="showb2bmegrendelesbutton" type="checkbox"{if ($egyed.showb2bmegrendelesbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Webshop rendelés" for="ShowwebshopmegrendelesbuttonEdit"}
                    <input id="ShowwebshopmegrendelesbuttonEdit" name="showwebshopmegrendelesbutton" type="checkbox"{if ($egyed.showwebshopmegrendelesbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Backorder" for="ShowbackorderEdit" ujsor=true}
                    <input id="ShowbackorderEdit" name="showbackorder" type="checkbox"{if ($egyed.showbackorder)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Szétbontás gyártónként" for="ShowslicemanufacturerbuttonEdit"}
                    <input id="ShowslicemanufacturerbuttonEdit" name="showslicemanufacturerbutton" type="checkbox"{if ($egyed.showslicemanufacturerbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Feketelista" for="ShowfeketelistabuttonEdit" ujsor=true}
                    <input id="ShowfeketelistabuttonEdit" name="showfeketelistabutton" type="checkbox"{if ($egyed.showfeketelistabutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Email sablon küldés" for="ShowemailbuttonEdit"}
                    <input id="ShowemailbuttonEdit" name="showemailbutton" type="checkbox"{if ($egyed.showemailbutton)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="PDF / email küldés" for="ShowpdfEdit"}
                    <input id="ShowpdfEdit" name="showpdf" type="checkbox"{if ($egyed.showpdf)} checked="checked"{/if}>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
