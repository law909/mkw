<div class="ui-widget ui-widget-content ui-corner-all">
    <div class="ui-widget-header ui-corner-top">
        <div class="mainboxinner ui-corner-top">NAV Online beküldő állapota</div>
    </div>
    <div class="mainboxinner">
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
</div>
