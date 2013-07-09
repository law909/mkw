<tr id="mattable-row_{$_cimke.id}" data-cimkeid="{$_cimke.id}">
<td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
<td class="cell">
<table><tbody>
<tr>{if ($kellkep)}<td><a class="toFlyout" href="{$_cimke.kepurl}" target="_blank"><img src="{$_cimke.kepurlsmall}"/></a></td>{/if}
<td><a class="mattable-editlink" href="#" data-cimkeid="{$_cimke.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_cimke.nev}</a></td>
{if ($setup.grideditbutton=='small')}
<td><span class="jobbra"><a class="mattable-dellink" href="#" data-cimkeid="{$_cimke.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span></td>
{/if}
</tr></tbody></table>
</td>
<td class="cell">{$_cimke.cimkekatnev}</td>
<td class="cell">
<a href="#" class="js-menulathatocheckbox{if ($_cimke.menu1lathato)} ui-state-hover{/if}" data-num=1>{t('Menü 1')}</a>
<a href="#" class="js-menulathatocheckbox{if ($_cimke.menu2lathato)} ui-state-hover{/if}" data-num=2>{t('Menü 2')}</a>
<a href="#" class="js-menulathatocheckbox{if ($_cimke.menu3lathato)} ui-state-hover{/if}" data-num=3>{t('Menü 3')}</a>
<a href="#" class="js-menulathatocheckbox{if ($_cimke.menu4lathato)} ui-state-hover{/if}" data-num=4>{t('Menü 4')}</a>
</td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-cimkeid="{$_cimke.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-cimkeid="{$_cimke.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>