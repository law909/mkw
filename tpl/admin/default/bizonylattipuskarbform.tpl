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
            <table>
                <tbody>
                <tr>
                    <td><label for="IdEdit">{at('Azonosító')}:</label></td>
                    <td><input id="IdEdit" name="id" type="text" size="30" maxlength="30" value="{$egyed.id}"
                               required="required"{if ($oper !== 'add')} readonly="readonly"{/if}></td>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="40" maxlength="100" value="{$egyed.nev|escape}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="AzonositoEdit">{at('Rövid azonosító')}:</label></td>
                    <td><input id="AzonositoEdit" name="azonosito" type="text" size="10" maxlength="10" value="{$egyed.azonosito}"></td>
                    <td><label for="IranyEdit">{at('Irány')}:</label></td>
                    <td>
                        <select id="IranyEdit" name="irany">
                            <option value="-1"{if ($egyed.irany == -1)} selected="selected"{/if}>{at('kivét (-1)')}</option>
                            <option value="0"{if ($egyed.irany == 0)} selected="selected"{/if}>{at('nincs (0)')}</option>
                            <option value="1"{if ($egyed.irany == 1)} selected="selected"{/if}>{at('bevét (1)')}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="KezdosorszamEdit">{at('Kezdő sorszám')}:</label></td>
                    <td><input id="KezdosorszamEdit" name="kezdosorszam" type="number" step="1" value="{$egyed.kezdosorszam}"></td>
                    <td><label for="PeldanyszamEdit">{at('Példányszám')}:</label></td>
                    <td><input id="PeldanyszamEdit" name="peldanyszam" type="number" step="1" value="{$egyed.peldanyszam}"></td>
                </tr>
                <tr>
                    <td><label for="TplnameEdit">{at('Nyomtatási sablon')}:</label></td>
                    <td><input id="TplnameEdit" name="tplname" type="text" size="40" maxlength="200" value="{$egyed.tplname}"></td>
                    <td><label for="Tplname_l1Edit">{at('Nyomtatási sablon (en)')}:</label></td>
                    <td><input id="Tplname_l1Edit" name="tplname_l1" type="text" size="40" maxlength="200" value="{$egyed.tplname_l1}"></td>
                </tr>
                <tr>
                    <td><label for="Tplname2Edit">{at('2. nyomtatási sablon')}:</label></td>
                    <td><input id="Tplname2Edit" name="tplname2" type="text" size="40" maxlength="200" value="{$egyed.tplname2}"></td>
                    <td><label for="Tplname2_l1Edit">{at('2. nyomtatási sablon (en)')}:</label></td>
                    <td><input id="Tplname2_l1Edit" name="tplname2_l1" type="text" size="40" maxlength="200" value="{$egyed.tplname2_l1}"></td>
                </tr>
                <tr>
                    <td><label for="Tplcaption2Edit">{at('2. nyomtatás gomb felirata')}:</label></td>
                    <td colspan="3"><input id="Tplcaption2Edit" name="tplcaption2" type="text" size="60" maxlength="255" value="{$egyed.tplcaption2|escape}"></td>
                </tr>
                    <tr>
                        <td><label for="NyomtatniEdit">Nyomtatni kell:</label></td>
                        <td><input id="NyomtatniEdit" name="nyomtatni" type="checkbox"{if ($egyed.nyomtatni)} checked="checked"{/if}></td>
                        <td><label for="MozgatEdit">Készletet mozgat:</label></td>
                        <td><input id="MozgatEdit" name="mozgat" type="checkbox"{if ($egyed.mozgat)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="FoglalEdit">Foglal:</label></td>
                        <td><input id="FoglalEdit" name="foglal" type="checkbox"{if ($egyed.foglal)} checked="checked"{/if}></td>
                        <td><label for="PenztmozgatEdit">Pénzt mozgat:</label></td>
                        <td><input id="PenztmozgatEdit" name="penztmozgat" type="checkbox"{if ($egyed.penztmozgat)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="EditprintedEdit">Nyomtatás után is szerkeszthető:</label></td>
                        <td><input id="EditprintedEdit" name="editprinted" type="checkbox"{if ($egyed.editprinted)} checked="checked"{/if}></td>
                        <td><label for="CheckkeltEdit">Kelt ellenőrzése:</label></td>
                        <td><input id="CheckkeltEdit" name="checkkelt" type="checkbox"{if ($egyed.checkkelt)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="SendemailEdit">Email küldés mentés után:</label></td>
                        <td><input id="SendemailEdit" name="sendemail" type="checkbox"{if ($egyed.sendemail)} checked="checked"{/if}></td>
                        <td><label for="NavbekuldendoEdit">NAV-hoz beküldendő:</label></td>
                        <td><input id="NavbekuldendoEdit" name="navbekuldendo" type="checkbox"{if ($egyed.navbekuldendo)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="AutopenztarbizonylatEdit">Automatikus pénztárbizonylat:</label></td>
                        <td><input id="AutopenztarbizonylatEdit" name="autopenztarbizonylat" type="checkbox"{if ($egyed.autopenztarbizonylat)} checked="checked"{/if}></td>
                        <td><label for="KellkapcsolodokoltsegetszamolniEdit">Kapcsolódó költséget számol:</label></td>
                        <td><input id="KellkapcsolodokoltsegetszamolniEdit" name="kellkapcsolodokoltsegetszamolni" type="checkbox"{if ($egyed.kellkapcsolodokoltsegetszamolni)} checked="checked"{/if}></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="MezokTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                    <tr>
                        <td><label for="ShowteljesitesEdit">Teljesítés:</label></td>
                        <td><input id="ShowteljesitesEdit" name="showteljesites" type="checkbox"{if ($egyed.showteljesites)} checked="checked"{/if}></td>
                        <td><label for="ShowesedekessegEdit">Esedékesség:</label></td>
                        <td><input id="ShowesedekessegEdit" name="showesedekesseg" type="checkbox"{if ($egyed.showesedekesseg)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowhataridoEdit">Határidő:</label></td>
                        <td><input id="ShowhataridoEdit" name="showhatarido" type="checkbox"{if ($egyed.showhatarido)} checked="checked"{/if}></td>
                        <td><label for="ShowbizonylatstatuszeditorEdit">Státusz szerkesztő:</label></td>
                        <td><input id="ShowbizonylatstatuszeditorEdit" name="showbizonylatstatuszeditor" type="checkbox"{if ($egyed.showbizonylatstatuszeditor)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowuzenetEdit">Üzenet:</label></td>
                        <td><input id="ShowuzenetEdit" name="showuzenet" type="checkbox"{if ($egyed.showuzenet)} checked="checked"{/if}></td>
                        <td><label for="ShowszallitasicimEdit">Szállítási cím:</label></td>
                        <td><input id="ShowszallitasicimEdit" name="showszallitasicim" type="checkbox"{if ($egyed.showszallitasicim)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowerbizonylatszamEdit">Eredeti bizonylatszám:</label></td>
                        <td><input id="ShowerbizonylatszamEdit" name="showerbizonylatszam" type="checkbox"{if ($egyed.showerbizonylatszam)} checked="checked"{/if}></td>
                        <td><label for="ShowfuvarlevelszamEdit">Fuvarlevélszám:</label></td>
                        <td><input id="ShowfuvarlevelszamEdit" name="showfuvarlevelszam" type="checkbox"{if ($egyed.showfuvarlevelszam)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowhaszonszazalekEdit">Haszonszázalék:</label></td>
                        <td><input id="ShowhaszonszazalekEdit" name="showhaszonszazalek" type="checkbox"{if ($egyed.showhaszonszazalek)} checked="checked"{/if}></td>
                        <td><label for="ShowkuponEdit">Kupon:</label></td>
                        <td><input id="ShowkuponEdit" name="showkupon" type="checkbox"{if ($egyed.showkupon)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowfoxpostterminaleditorEdit">Foxpost automata:</label></td>
                        <td><input id="ShowfoxpostterminaleditorEdit" name="showfoxpostterminaleditor" type="checkbox"{if ($egyed.showfoxpostterminaleditor)} checked="checked"{/if}></td>
                        <td><label for="ShowfelhasznaloEdit">Dolgozó:</label></td>
                        <td><input id="ShowfelhasznaloEdit" name="showfelhasznalo" type="checkbox"{if ($egyed.showfelhasznalo)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowgarancialisadatokEdit">Garanciális adatok:</label></td>
                        <td><input id="ShowgarancialisadatokEdit" name="showgarancialisadatok" type="checkbox"{if ($egyed.showgarancialisadatok)} checked="checked"{/if}></td>
                        <td><label for="ShoweddigimegrendeleseiurlEdit">Eddigi megrendelései link:</label></td>
                        <td><input id="ShoweddigimegrendeleseiurlEdit" name="showeddigimegrendeleseiurl" type="checkbox"{if ($egyed.showeddigimegrendeleseiurl)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowmunkalapadatokEdit">Munkalap adatok:</label></td>
                        <td><input id="ShowmunkalapadatokEdit" name="showmunkalapadatok" type="checkbox"{if ($egyed.showmunkalapadatok)} checked="checked"{/if}></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="GombokTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                    <tr>
                        <td><label for="ShowszamlabuttonEdit">Számla:</label></td>
                        <td><input id="ShowszamlabuttonEdit" name="showszamlabutton" type="checkbox"{if ($egyed.showszamlabutton)} checked="checked"{/if}></td>
                        <td><label for="ShowkeziszamlabuttonEdit">Kézi számla:</label></td>
                        <td><input id="ShowkeziszamlabuttonEdit" name="showkeziszamlabutton" type="checkbox"{if ($egyed.showkeziszamlabutton)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowszallitobuttonEdit">Szállítólevél:</label></td>
                        <td><input id="ShowszallitobuttonEdit" name="showszallitobutton" type="checkbox"{if ($egyed.showszallitobutton)} checked="checked"{/if}></td>
                        <td><label for="ShowkivetbuttonEdit">Kivét:</label></td>
                        <td><input id="ShowkivetbuttonEdit" name="showkivetbutton" type="checkbox"{if ($egyed.showkivetbutton)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowbevetbuttonEdit">Bevét:</label></td>
                        <td><input id="ShowbevetbuttonEdit" name="showbevetbutton" type="checkbox"{if ($egyed.showbevetbutton)} checked="checked"{/if}></td>
                        <td><label for="ShowszallmegrbuttonEdit">Szállítói megrendelés:</label></td>
                        <td><input id="ShowszallmegrbuttonEdit" name="showszallmegrbutton" type="checkbox"{if ($egyed.showszallmegrbutton)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowcsomagbuttonEdit">Csomag:</label></td>
                        <td><input id="ShowcsomagbuttonEdit" name="showcsomagbutton" type="checkbox"{if ($egyed.showcsomagbutton)} checked="checked"{/if}></td>
                        <td><label for="ShowstornoEdit">Stornó:</label></td>
                        <td><input id="ShowstornoEdit" name="showstorno" type="checkbox"{if ($egyed.showstorno)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowbackorderEdit">Backorder:</label></td>
                        <td><input id="ShowbackorderEdit" name="showbackorder" type="checkbox"{if ($egyed.showbackorder)} checked="checked"{/if}></td>
                        <td><label for="ShowslicemanufacturerbuttonEdit">Szétbontás gyártónként:</label></td>
                        <td><input id="ShowslicemanufacturerbuttonEdit" name="showslicemanufacturerbutton" type="checkbox"{if ($egyed.showslicemanufacturerbutton)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowfeketelistabuttonEdit">Feketelista:</label></td>
                        <td><input id="ShowfeketelistabuttonEdit" name="showfeketelistabutton" type="checkbox"{if ($egyed.showfeketelistabutton)} checked="checked"{/if}></td>
                        <td><label for="ShowemailbuttonEdit">Email sablon küldés:</label></td>
                        <td><input id="ShowemailbuttonEdit" name="showemailbutton" type="checkbox"{if ($egyed.showemailbutton)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="ShowpdfEdit">PDF / email küldés:</label></td>
                        <td><input id="ShowpdfEdit" name="showpdf" type="checkbox"{if ($egyed.showpdf)} checked="checked"{/if}></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
