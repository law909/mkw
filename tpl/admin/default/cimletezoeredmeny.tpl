<thead>
<tr>
    <td>Címlet</td>
    <td>Darab</td>
</tr>
</thead>
<tbody>
{foreach $cimletek.cimletek as $cimlet=>$darab}
    {if ($darab > 0)}
    <tr>
        <td>{$cimlet}</td>
        <td>{$darab}</td>
    </tr>
    {/if}
{/foreach}
</tbody>
<tfoot>
<tr>
    <td>Összesen:</td>
    <td>{$cimletek.osszesen}</td>
</tr>
</tfoot>
