<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
<td class="cell"><input class="maincheckbox" type="checkbox"></td>
<td class="cell">
<table><tbody>
<tr><td>
<a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.id}</a>
</td>
{if ($setup.grideditbutton=='small')}
<td><span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
</td>
{/if}
</tr>
<tr><td class="mattable-important">
{$_egyed.partnernev}
</td></tr>
<tr><td>
{$_egyed.partnerirszam} {$_egyed.partnervaros}, {$_egyed.partnerutca}
</td></tr>
</tbody></table>
</td>
<td class="cell">
<table><tbody>
<tr><td></td><td>{$_egyed.fizmodnev}</td></tr>
<tr><td>{t('Kelt')}:</td><td>{$_egyed.keltstr}</td></tr>
{if ($showteljesites)}<tr><td>{t('Teljesítés')}:</td><td>{$_egyed.teljesitesstr}</td></tr>{/if}
{if ($showesedekesseg)}<tr class="mattable-important"><td>{t('Esedékesség')}:</td><td>{$_egyed.esedekessegstr}</td></tr>{/if}
{if ($showhatarido)}<tr class="mattable-important"><td>{t('Határidő')}:</td><td>{$_egyed.hataridostr}</td></tr>{/if}
</tbody></table>
</td>
<td class="cell">
<table><tbody>
<tr><td></td><td class="mattable-rightaligned">{$_egyed.valutanemnev}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">HUF?</td>{/if}</tr>
<tr><td>{t('Nettó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.netto,2)}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.nettohuf,2)}</td>{/if}</tr>
<tr><td>{t('ÁFA')}:</td><td class="mattable-rightaligned">{number_format($_egyed.afa,2)}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.afahuf,2)}</td>{/if}</tr>
<tr class="mattable-important"><td>{t('Bruttó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.brutto,2)}</td>{if ($showvalutanem)}<td class="mattable-rightaligned">{number_format($_egyed.bruttohuf,2)}</td>{/if}</tr>
<tr>{if ($showvalutanem)}<td>{t('Árfolyam')}:</td><td class="mattable-rightaligned">{number_format($_egyed.arfolyam,2)}</td>{/if}</tr>
</tbody></table>
</td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>