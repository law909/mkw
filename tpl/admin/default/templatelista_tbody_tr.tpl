<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
<td class="cell">
<a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.caption}</a>
</td>

{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>