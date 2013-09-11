<tr id="mattable-row_{$_partner.id}"{if ($_partner.vendeg)} class="guestpartner"{/if}>
<td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
<td class="cell">
<div><div>
<a class="mattable-editlink" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_partner.nev}</a>
{if ($setup.grideditbutton=='small')}
<span class="jobbra"><a class="mattable-dellink" href="#" data-partnerid="{$_partner.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
{/if}
<table><tbody>
<tr><td colspan="2">{$_partner.vezeteknev} {$_partner.keresztnev}</td></tr>
<tr><td>{t('Üzletkötő')}:</td><td>{$_partner.uzletkotonev}</td></tr><tr><td>{t('Adószám')}:</td><td>{$_partner.adoszam}</td></tr><tr><td>{t('Fizetési mód')}:</td><td>{$_partner.fizmodnev}</td></tr>
<tr><td>IP: {$_partner.ip}</td><td>Ref.: {$_partner.referrer}</td></tr>
</tbody></table>
</td>
<td class="cell">{$_partner.cim}<br />{if ($_partner.lcim!=='')}({t('Levélcím')}: {$_partner.lcim}){/if}</td>
<td class="cell"><table><tbody>
<tr><td>{$_partner.telefon}</td></tr><tr><td>{$_partner.mobil}</td></tr>{if ($_partner.fax!=='')}<tr><td>{t('Fax')}: {$_partner.fax}</td></tr>{/if}
{if ($_partner.email!=='')}<tr><td><a href="mailto:{$_partner.email}" title="{t('Levélküldés')}">{$_partner.email}</a></td></tr>{/if}
{if ($_partner.honlap!=='')}<tr><td><a href="{$_partner.honlap}" title="{t('Ugrás a honlapra')}" target="_blank">{$_partner.honlap}</a></td></tr>{/if}
</tbody></table></td>
<td class="cell">
{foreach $_partner.cimkek as $_cimke}
{$_cimke.nev};
{/foreach}
</td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-partnerid="{$_partner.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-partnerid="{$_partner.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>