<div class="js-noallapotbody mainboxinner">
    <ul class="unstyled-list">
    {foreach $noerrors as $noerror}
        <li class="redtext">{$noerror['code']} - {$noerror['message']} ({$noversion})</li>
        {foreachelse}
        <li class="greentext">Elérhető ({$noresult}) ({$noversion})</li>
    {/foreach}
    </ul>
    <ul class="unstyled-list">
        {foreach $nohibalista as $nohiba}
            <li class="redtext">{$nohiba}</li>
        {/foreach}
    </ul>
</div>
