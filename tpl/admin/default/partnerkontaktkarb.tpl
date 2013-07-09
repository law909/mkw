<table id="kontakttable_{$kontakt.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<input name="kontaktid[]" type="hidden" value="{$kontakt.id}">
<input name="kontaktoper_{$kontakt.id}" type="hidden" value="{$kontakt.oper}">
<tr>
<td><label for="NevEdit{$kontakt.id}">{t('Név')}:</label></td>
<td colspan="5"><input id="NevEdit{$kontakt.id}" name="kontaktnev_{$kontakt.id}" type="text" value="{$kontakt.nev}" maxlength="255" size="80" required="required"></td>
</tr>
<tr>
<td><label for="TelefonEdit{$kontakt.id}">{t('Telefon')}:</label></td>
<td><input id="TelefonEdit{$kontakt.id}" name="kontakttelefon_{$kontakt.id}" type="text" value="{$kontakt.telefon}" maxlength="40" size="20"></td>
<td><label for="MobilEdit{$kontakt.id}">{t('Mobil')}:</label></td>
<td><input id="MobilEdit{$kontakt.id}" name="kontaktmobil_{$kontakt.id}" type="text" value="{$kontakt.mobil}" maxlength="40" size="20"></td>
<td><label for="FaxEdit{$kontakt.id}">{t('Fax')}:</label></td>
<td><input id="FaxEdit{$kontakt.id}" name="kontaktfax_{$kontakt.id}" type="text" value="{$kontakt.fax}" maxlength="40" size="20"></td>
</tr>
<tr>
<td><label for="EmailEdit{$kontakt.id}">{t('Email')}:</label></td>
<td><input id="EmailEdit{$kontakt.id}" name="kontaktemail_{$kontakt.id}" type="email" value="{$kontakt.email}"></td>
<td><label for="HonlapEdit{$kontakt.id}">{t('Honlap')}:</label></td>
<td><input id="HonlapEdit{$kontakt.id}" name="kontakthonlap_{$kontakt.id}" type="url" value="{$kontakt.honlap}"></td>
</tr>
<tr>
<td><label for="MegjegyzesEdit{$kontakt.id}">{t('Megjegyzés')}:</label></td>
<td colspan="5"><input id="MegjegyzesEdit{$kontakt.id}" name="kontaktmegjegyzes_{$kontakt.id}" type="text" value="{$kontakt.megjegyzes}" size="80"></td>
</tr>
<tr><td>
<a class="js-kontaktdelbutton" href="#" data-id="{$kontakt.id}"{if ($kontakt.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</td></tr>
</tbody></table>
{if ($kontakt.oper=='add')}
<a class="js-kontaktnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}