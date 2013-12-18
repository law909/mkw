<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
<td class="cell"><input class="maincheckbox" type="checkbox"></td>
{if ($showbizonylatstatuszeditor)}
<td class="cell"><select id="BizonylatStatuszFuggobenEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit">
    <option value="">{t('válasszon')}</option>
    {foreach $_egyed.bizonylatstatuszlist as $_role}
    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
    {/foreach}
</select></td>
{/if}
<td class="cell">
<a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.id}</a>
{if ($setup.grideditbutton=='small')}
<a class="js-printbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{t('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
{if ($showinheritbutton)}
<a class="js-inheritbizonylat" href="#" data-egyedid="{$_egyed.id}" data-oper="inherit" title="{t('Számláz')}" target="_blank"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></a>
{/if}
<a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
{/if}
<table><tbody>
<tr><td  colspan="2" class="mattable-important">
{$_egyed.partnernev}
</td></tr>
<tr><td colspan="2">
{$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca}
</td></tr>
<tr><td colspan="2">
<a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a>
</td></tr>
<tr><td colspan="2">
{$_egyed.partnertelefon}
</td></tr>
<tr><td colspan="5">
IP: {$_egyed.ip} Ref.: {$_egyed.referrer}
</td>
</tr>
</tbody></table>
</td>
<td class="cell">
<table><tbody>
<tr><td></td><td>{$_egyed.fizmodnev}</td></tr>
<tr><td></td><td>{$_egyed.szallitasimodnev}</td></tr>
<tr><td>Fuvarlevél:</td><td>{$_egyed.fuvarlevelszam}</td></tr>
<tr><td>{t('Kelt')}:</td><td>{$_egyed.keltstr}</td></tr>
{if ($showteljesites)}<tr><td>{t('Teljesítés')}:</td><td>{$_egyed.teljesitesstr}</td></tr>{/if}
{if ($showesedekesseg)}<tr class="mattable-important"><td>{t('Esedékesség')}:</td><td>{$_egyed.esedekessegstr}</td></tr>{/if}
{if ($showhatarido)}<tr class="mattable-important"><td>{t('Határidő')}:</td><td>{$_egyed.hataridostr}</td></tr>{/if}
</tbody></table>
</td>
<td class="cell">
<table><tbody>
<tr><td></td><td class="mattable-rightaligned">{$_egyed.valutanemnev}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">HUF?</td>{/if}</tr>
<tr><td>{t('Nettó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.netto,2,'.',' ')}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.nettohuf,2,'.',' ')}</td>{/if}</tr>
<tr><td>{t('ÁFA')}:</td><td class="mattable-rightaligned">{number_format($_egyed.afa,2,'.',' ')}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.afahuf,2,'.',' ')}</td>{/if}</tr>
<tr class="mattable-important"><td>{t('Bruttó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.brutto,2,'.',' ')}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.bruttohuf,2,'.',' ')}</td>{/if}</tr>
<tr>{if ($showvalutanem)}<td>{t('Árfolyam')}:</td><td class="mattable-rightaligned">{number_format($_egyed.arfolyam,2,'.',' ')}</td>{/if}</tr>
</tbody></table>
</td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>