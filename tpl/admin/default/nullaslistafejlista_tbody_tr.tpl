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
{t('Kelt')}:{$_egyed.keltstr}
</td>
<td class="cell">
<table><tbody>
<tr><td></td><td class="mattable-rightaligned">{$_egyed.valutanemnev}</td><td class="mattable-rightaligned">HUF?</td></tr>
<tr><td>{t('Nettó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.netto,2)}</td><td class="mattable-rightaligned">{number_format($_egyed.nettohuf,2)}</td></tr>
<tr><td>{t('ÁFA')}:</td><td class="mattable-rightaligned">{number_format($_egyed.afa,2)}</td><td class="mattable-rightaligned">{number_format($_egyed.afahuf,2)}</td></tr>
<tr class="mattable-important"><td>{t('Bruttó')}:</td><td class="mattable-rightaligned">{number_format($_egyed.brutto,2)}</td><td class="mattable-rightaligned">{number_format($_egyed.bruttohuf,2)}</td></tr>
<tr><td>{t('Árfolyam')}:</td><td class="mattable-rightaligned">{number_format($_egyed.arfolyam,2)}</td></tr>
</tbody></table>
</td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>