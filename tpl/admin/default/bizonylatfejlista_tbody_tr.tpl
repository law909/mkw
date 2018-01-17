<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
        {if ($showbizonylatstatuszeditor)}
        <td class="cell">
            <select id="BizonylatStatuszFuggobenEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit">
                <option value="">{at('válasszon')}</option>
                {foreach $_egyed.bizonylatstatuszlist as $_role}
                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                {/foreach}
            </select>
        </td>
        {/if}
    <td class="cell">
        {if ($_egyed.editprinted || (!$_egyed.editprinted && !$_egyed.nyomtatva))}
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.id}</a>
        {else}
            {$_egyed.id}
        {/if}
            <a class="js-printbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="print" data-kellkerdezni="{!$_egyed.editprinted && !$_egyed.nyomtatva}" title="{at('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
            {if ($_egyed.nemrossz)}
                {if ($_egyed.bizonylattipusid=='megrendeles')}
                    <a class="js-printelolegbekero" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{at('Előleg bekérő')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
                {/if}
                {if ($showbackorder)}
                    <a class="js-backorder" href="#" data-egyedid="{$_egyed.id}" title="{at('Backorder')}"><span class="ui-icon ui-icon-transferthick-e-w"></span></a>
                {/if}
                {if ($showszallitobutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szallitofej" data-oper="inherit" title="{at('Szállítólevél')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szamlafej" data-oper="inherit" title="{at('Számla')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showkeziszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="keziszamlafej" data-oper="inherit" title="{at('Kézi számla')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showkivetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="kivetfej" data-oper="inherit" title="{at('Kivét')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showbevetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="bevetfej" data-oper="inherit" title="{at('Bevét')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showcsomagbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-egyednev="csomagfej" data-oper="inherit" title="{at('Csomag')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
                {/if}
                {if ($showmesebutton && havejog(99))}
                    <a class="js-mese" href="#" title="{at('Mese')}" data-href="/admin/mese?b={$_egyed.id}"><span class="ui-icon ui-icon-image"></span></a>
                {/if}
                {if (havejog(90))}
                    <a class="js-vissza" href="#" data-href="/admin/bizvissza?b={$_egyed.id}">V</a>
                {/if}
                {if ($showfeketelistabutton)}
                    <a class="js-feketelista" href="#" data-email="{$_egyed.partneremail}" data-ip="{$_egyed.ip}" title="{at('Feketelista')}" target="_blank"><span class="ui-icon ui-icon-alert"></span></a>
                {/if}
                {if ($_egyed.bizonylattipusid=='megrendeles' && $_egyed.otpayid)}
                    <a class="js-otpayrefund" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{at('OTPay refund')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span></a>
                    <a class="js-otpaystorno" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{at('OTPay storno')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span></a>
                {/if}
                {if ($showstorno)}
                    <a class="js-stornobizonylat1" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szamlafej" data-oper="storno" title="{at('Számlával egy tekintet alá eső okirat')}" target="_blank"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    <a class="js-stornobizonylat2" href="#" data-egyedid="{$_egyed.id}" data-egyednev="szamlafej" data-oper="storno" title="{at('Érvénytelenítő számla')}" target="_blank"><span class="ui-icon ui-icon-circle-minus"></span></a>
                {else}
                    <a class="js-rontbizonylat" href="#" data-egyedid="{$_egyed.id}" title="{at('Ront')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                {/if}
            {/if}
        <table>
            <tbody>
                {if ($showfelhasznalo)}
                <tr><td>{$_egyed.felhasznalonev}</td></tr>
                {/if}
                <tr><td  colspan="2" class="mattable-important">
                        {$_egyed.partnernev}
                    </td></tr>
                <tr><td colspan="2">
                        {$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca} {$_egyed.partnerhazszam}
                    </td></tr>
                <tr><td colspan="2">
                        <a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a>
                    </td></tr>
                <tr><td colspan="2">
                        {$_egyed.partnertelefon}
                    </td></tr>
                <tr><td colspan="5" class="referrer">
                        {at('IP')}: {$_egyed.ip} {at('Ref.')}: {$_egyed.referrer}
                    </td>
                </tr>
                <tr><td>{at('Létrehozva')}:</td><td>{$_egyed.createdby} {$_egyed.createdstr}</td></tr>
                <tr><td>{at('Módosítva')}:</td><td>{$_egyed.updatedby} {$_egyed.lastmodstr}</td></tr>
                {if ($_egyed.partnerfeketelistas)}
                <tr>
                    <td colspan="5">
                        <span class="feketelistas">{at('FEKETELISTÁS')}:</span> {$_egyed.partnerfeketelistaok}
                    </td>
                </tr>
                {/if}
                {if (($_egyed.bizonylattipusid=='megrendeles') && ($_egyed.regmode > 0))}
                    <tr><td colspan="5">
                        {at('Reg.mód')}: {if ($_egyed.regmode == 1)}{at('vendég')}{elseif ($_egyed.regmode == 2)}{at('regisztrált')}{elseif ($_egyed.regmode == 3)}{at('bejelentkezett')}{/if}
                        </td>
                    </tr>
                {/if}
                {if ($_egyed.belsomegjegyzes)}
                    <tr><td colspan="5" class="guestpartner">
                            {$_egyed.belsomegjegyzes}
                        </td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table><tbody>
                {if ($_egyed.showotpay)}
                    <tr><td></td><td>{$_egyed.otpayresulttext}</td></tr>
                {/if}
                {if ($setup.fanta && $_egyed.fix)}
                <tr><td></td><td>{at('Fix')}</td></tr>
                {/if}
                <tr><td></td><td>{$_egyed.raktarnev}</td></tr>
                <tr><td></td><td>{$_egyed.fizmodnev}</td></tr>
                <tr><td></td><td>{$_egyed.szallitasimodnev}</td></tr>
                {if ($_egyed.uzletkotonev)}
                <tr><td></td><td>{$_egyed.uzletkotonev} ({number_format($_egyed.uzletkotojutalek, 2, '.', ' ')} %)</td></tr>
                {/if}
                {if (haveJog(90) && $_egyed.belsouzletkotonev)}
                    <tr><td></td><td>(B){$_egyed.belsouzletkotonev} ({number_format($_egyed.belsouzletkotojutalek, 2, '.', ' ')} %)</td></tr>
                {/if}
                {if ($showerbizonylatszam)}
                    <tr><td>{at('Er.biz.szám')}:</td><td>{$_egyed.erbizonylatszam}</td></tr>
                {/if}
                {if ($showfuvarlevelszam)}
                    <tr><td>{at('Fuvarlevél')}:</td><td class="fuvarlevel">{$_egyed.fuvarlevelszam}</td></tr>
                {/if}
                {if ($showkupon)}
                    <tr><td>{at('Kupon')}:</td><td>{$_egyed.kupon}</td></tr>
                {/if}
                <tr><td>{at('Kelt')}:</td><td>{$_egyed.keltstr}</td></tr>
                {if ($showteljesites)}
                    <tr><td>{at('Teljesítés')}:</td><td>{$_egyed.teljesitesstr}</td></tr>
                {/if}
                {if ($showesedekesseg)}
                    <tr class="mattable-important"><td>{at('Esedékesség')}:</td><td>{$_egyed.esedekessegstr}</td></tr>
                {/if}
                {if ($showhatarido)}
                    <tr class="mattable-important"><td>{at('Határidő')}:</td><td>{$_egyed.hataridostr}</td></tr>
                {/if}
            </tbody></table>
    </td>
    <td class="cell">
        <table>
            <tbody>
                {if ($_egyed.fizetve)}
                    <tr><td>{at('Fizetve')}</td></tr>
                {/if}
                {if ($setup.fakekintlevoseg && $_egyed.fakekintlevoseg && !$_egyed.fakekifizetve)}
                    <tr><td><span class="lejartkiegyenlitetlen">{at('FAKE kintlévőség')}</span></td></tr>
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
                    <td>{at('Nettó')}:</td><td class="mattable-rightaligned pricenowrap">{number_format($_egyed.netto, 2, '.', ' ')}</td>
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
                        <td class="{$cls}">{at('Egyenleg')}:</td>
                        <td class="mattable-rightaligned pricenowrap {$cls}">{number_format($_egyed.egyenleg, 2, '.', ' ')}</td>
                    </tr>
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