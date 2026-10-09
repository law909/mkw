{capture "compdatum"}
    <input id="DatumEdit" name="datum" data-datum="{$datum}">
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Dátum" for="DatumEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compdatum nofilter}{/mezo}
{else}
<div>
    <label for="DatumEdit">{at('Dátum')}:</label>
{$smarty.capture.compdatum nofilter}
</div>
{/if}
