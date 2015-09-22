{foreach $valtozatlist as $valtozat}
<tr>
    <td>{$valtozat.caption}</td>
    <td><input name="tetelmennyiseg_{$valtozat.tetelid}" data-termektetelid="{$valtozat.termektetelid}" type="number" step="any" maxlength="20" size="10" class="js-quickmennyiseginput mattable-important"></td>
    <input name="tetelid[]" type="hidden" value="{$valtozat.tetelid}">
    <input name="teteloper_{$valtozat.tetelid}" type="hidden" value="add">
    <input name="teteltermek_{$valtozat.tetelid}" type="hidden" value="{$valtozat.termekid}">
    <input name="tetelvaltozat_{$valtozat.tetelid}" type="hidden" value="{$valtozat.id}">
</tr>
{/foreach}