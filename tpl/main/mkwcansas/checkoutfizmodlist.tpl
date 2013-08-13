{foreach $fizmodlist as $fizmod}
<label class="radio">
	<input type="radio" name="fizetesimod" value="{$fizmod.id}"{if ($fizmod.selected)} checked{/if}>
	{$fizmod.caption}
</label>
{if ($fizmod.leiras)}
<div class="chk-courierdesc folyoszoveg">{$fizmod.leiras}</div>
{/if}
{/foreach}
