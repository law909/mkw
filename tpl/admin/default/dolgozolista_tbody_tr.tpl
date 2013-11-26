<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
<td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
<td class="cell">
{if ($loggedinuser.admin || ($_egyed.id == $loggedinuser.id))}
<a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.nev}</a>
{else}
{$_egyed.nev}
{/if}
{if ($loggedinuser.admin)}
{if ($setup.grideditbutton=='small')}
<span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
{/if}
{/if}
<table><tbody>
<tr><td>{$_egyed.szulidostr} {$_egyed.szulhely}</td></tr>
<tr><td>{$_egyed.munkakornev} {$_egyed.munkaviszonykezdetestr} {t('óta')}</td></tr>
</tbody></table>
</td>
<td class="cell"><table><tbody>
<tr><td>{$_egyed.irszam} {$_egyed.varos}, {$_egyed.utca}</td></tr>
<tr><td>{$_egyed.telefon}</td></tr>
{if ($_egyed.email!=='')}<tr><td><a href="mailto:{$_egyed.email}" title="{t('Levélküldés')}">{$_egyed.email}</a></td></tr>{/if}
</tbody></table></td>

{if ($setup.grideditbutton=='big')}
<td class="cell">
{if ($loggedinuser.admin || ($_egyed.id == $loggedinuser.id))}
<table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
{if ($loggedinuser.admin)}
<tr><td><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
{/if}
</tbody></table>
{/if}
</td>
{/if}
</tr>