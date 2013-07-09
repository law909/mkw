<tr id="mattable-row_{$_termek.id}">
<td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
<td class="cell">
<div><div>
<table><tbody>
<tr><td><a class="js-toflyout" href="{$_termek.kepurl}" target="_blank"><img src="{$_termek.kepurlsmall}"/></a></td>
<td><a class="mattable-editlink" href="#" data-termekid="{$_termek.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_termek.nev}</a></td>
{if ($setup.grideditbutton=='small')}
<td><span class="jobbra"><a class="mattable-dellink" href="#" data-termekid="{$_termek.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span></td>
{/if}
</tr>
<tr><td>{$_termek.termekfa1nev}</td><td>{$_termek.termekfa2nev}</td><td>{$_termek.termekfa3nev}</td></tr>
</tbody></table>
<table><tbody>
		<tr><td>{t('Link')}:</td><td colspan="3"><a href="/termek/{$_termek.slug}" target="_blank">/termek/{$_termek.slug}</a></td></tr>
<tr><td>{t('Megtekintve')}:</td><td>{$_termek.megtekintesdb}</td><td>{t('Megvásárolva')}:</td><td>{$_termek.megvasarlasdb}</td></tr>
<tr><td>{t('Cikkszám')}:</td><td colspan="3">{$_termek.cikkszam}</td>
<tr><td>{t('ME')}:</td><td colspan="3">{$_termek.me}</td></tr>
</tbody></table>
</td>
<td class="cell">
{$_termek.cimkek}
</td>
<td class="cell"><table><tbody>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="inaktiv" class="js-flagcheckbox{if ($_termek.inaktiv)} ui-state-hover{/if}">{t('Inaktív')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="lathato" class="js-flagcheckbox{if ($_termek.lathato)} ui-state-hover{/if}">{t('Látható')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="ajanlott" class="js-flagcheckbox{if ($_termek.ajanlott)} ui-state-hover{/if}">{t('Ajánlott')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="kiemelt" class="js-flagcheckbox{if ($_termek.kiemelt)} ui-state-hover{/if}">{t('Kiemelt')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="hozzaszolas" class="js-flagcheckbox{if ($_termek.hozzaszolas)} ui-state-hover{/if}">{t('Hozzá lehet szólni')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="mozgat" class="js-flagcheckbox{if ($_termek.mozgat)} ui-state-hover{/if}">{t('Készletet mozgat')}</a></td></tr>
<tr><td><a href="#" data-id="{$_termek.id}" data-flag="nemkaphato" class="js-flagcheckbox{if ($_termek.nemkaphato)} ui-state-hover{/if}">{t('Nem kapható')}</a></td></tr>
<tr><td>{t('Hűségpont arány')}: {$_termek.hparany}</td></tr>
</tbody></table></td>
{if ($setup.grideditbutton=='big')}
<td class="cell"><table class="kozepre"><tbody>
<tr><td><a class="mattable-editlink" href="#" data-termekid="{$_termek.id}" data-oper="edit" title="{t('Szerkeszt')}"><span class="ui-icon ui-icon-pencil"></span></a></td></tr>
<tr><td><a class="mattable-dellink" href="#" data-termekid="{$_termek.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td></tr>
</tbody></table></td>
{/if}
</tr>