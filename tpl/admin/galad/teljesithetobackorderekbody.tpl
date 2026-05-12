<table class="js-teljesithetobackorderek">
    <thead>
    <tr>
        <td>Biz.szám</td>
        <td>Kelt</td>
        <td>Határidő</td>
        <td>Partner</td>
        <td></td>
    </tr>
    </thead>
    {foreach $teljesithetobackorderek as $mr}
        <tr>
            <td><a href="{$mr.printurl}" target="_blank" title="Nyomtatási kép">{$mr.id}</a></td>
            <td><a href="{$mr.editurl}" target="_blank" title="Szerkesztés">{$mr.kelt}</a></td>
            <td>{$mr.hatarido}</td>
            <td>{$mr.partnernev}</td>
            <td><a class="js-backorder" href="#" data-egyedid="{$mr.id}" title="{t('Backorder')}"><span
                        class="ui-icon ui-icon-transferthick-e-w"></span></a></td>
        </tr>
    {/foreach}
</table>
