{foreach $fizmodlist as $fizmod}
<label class="radio">
	<input type="radio" name="fizetesimod" value="{$fizmod.id}"{if ($fizmod.selected)} checked{/if} data-caption="{$fizmod.caption}">
	{$fizmod.caption}
</label>
{if ($fizmod.leiras)}
<div class="chk-courierdesc folyoszoveg">{$fizmod.leiras}</div>
{/if}
{/foreach}
