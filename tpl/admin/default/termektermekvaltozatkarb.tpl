<table id="valtozattable_{$valtozat.id}"  class="ui-widget ui-widget-content ui-corner-all mattable-repeatable valtozattable"><tbody>
	<input name="valtozatid[]" type="hidden" value="{$valtozat.id}">
	<input name="valtozatoper_{$valtozat.id}" type="hidden" value="{$valtozat.oper}">
	<tr>
		<td class="mattable-cell">
			<label for="VElerhetoEdit{$valtozat.id}">{t('Elérhető')}:
			<input id="VElerhetoEdit{$valtozat.id}" name="valtozatelerheto_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto)} checked="checked"{/if}>
			</label>
		</td>
		<td class="mattable-cell">
			<label for="VLathatoEdit{$valtozat.id}">{t('Látható')}:
			<input id="VLathatoEdit{$valtozat.id}" name="valtozatlathato_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato)} checked="checked"{/if}>
			</label>
		</td>
		<td colspan="3" class="mattable-cell">
			<a class="valtozatdelbutton" href="#" data-id="{$valtozat.id}"{if ($valtozat.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
		</td>
	</tr>
	<tr>
		<td class="mattable-cell">
			<select name="valtozatadattipus1_{$valtozat.id}" required="required">
			<option value="">{t('válasszon')}</option>
			{foreach $valtozat.adattipus1lista as $at}
			<option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
			{/foreach}
			</select>
		</td>
		<td class="mattable-cell">
			<input name="valtozatertek1_{$valtozat.id}" type="text" value="{$valtozat.ertek1}" required="required">
		</td>
		<td class="mattable-cell">
			<label for="NettoEdit_{$valtozat.id}">{t('Nettó')}:</label>
		</td>
		<td class="mattable-cell">
			<input class="valtozatnetto" id="NettoEdit_{$valtozat.id}" name="valtozatnetto_{$valtozat.id}" type="number" step="any" value="{$valtozat.netto}">
		</td>
	</tr>
	<tr>
		<td class="mattable-cell">
			<select name="valtozatadattipus2_{$valtozat.id}">
			<option value="">{t('válasszon')}</option>
			{foreach $valtozat.adattipus2lista as $at}
			<option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
			{/foreach}
			</select>
		</td>
		<td class="mattable-cell">
			<input name="valtozatertek2_{$valtozat.id}" type="text" value="{$valtozat.ertek2}">
		</td>
		<td class="mattable-cell">
			<label for="BruttoEdit_{$valtozat.id}">{t('Bruttó')}:</label>
		</td>
		<td class="mattable-cell">
			<input class="valtozatbrutto" id="BruttoEdit_{$valtozat.id}" name="valtozatbrutto_{$valtozat.id}" type="number" step="any" value="{$valtozat.brutto}">
		</td>
	</tr>
	<tr>
		<td class="mattable-cell">
			<label for="CikkszamEdit_{$valtozat.id}">{t('Cikkszám')}:</label>
		</td>
		<td class="mattable-cell">
			<input id="CikkszamEdit_{$valtozat.id}" name="valtozatcikkszam_{$valtozat.id}" type="text" value="{$valtozat.cikkszam}">
		</td>
		<td class="mattable-cell">
			<label for="IdegenCikkszamEdit_{$valtozat.id}">{t('Szállítói cikkszám')}:</label>
		</td>
		<td class="mattable-cell">
			<input id="IdegenCikkszamEdit_{$valtozat.id}" name="valtozatidegencikkszam_{$valtozat.id}" type="text" value="{$valtozat.idegencikkszam}">
		</td>
	</tr>
	<tr>
		<td>
			<label for="ValtozatTermekKepCB_{$valtozat.id}">{t('A kép a termék főképe')}:</label>
			<input id="ValtozatTermekKepCB__{$valtozat.id}" name="valtozattermekfokep_{$valtozat.id}" type="checkbox"{if ($valtozat.termekfokep)} checked="checked"{/if}>
		</td>
	</tr>
	<tr>
		<td><label for="ValtozatKepEdit_{$valtozat.id}">{t('Kép')}:</label></td>
		<td colspan="3">
			<ul id="ValtozatKepEdit_{$valtozat.id}" class="valtozatkepedit">
				{foreach $valtozat.keplista as $kep}
					<li data-value="{$kep.id}" data-valtozatid="{$valtozat.id}" class="ui-state-default{if ($valtozat.kepid==$kep.id)} ui-selected ui-state-highlight{/if}"><img src="{$kep.url}"/></li>
				{/foreach}
			</ul>
			<input id="ValtozatKepId_{$valtozat.id}" name="valtozatkepid_{$valtozat.id}" type="hidden" value="{$valtozat.kepid}">
		</td>
	</tr>
</tbody></table>
{if ($valtozat.oper=='add')}
<a class="valtozatnewbutton" href="#" title="{t('Új')}" data-termekid="{$valtozat.termek.id}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}