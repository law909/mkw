<div id="mattkarb-header">
    <h3>{at('Cron futás')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label>{at('Feladat')}:</label></td>
                    <td>{$egyed.feladat}</td>
                </tr>
                <tr>
                    <td><label>{at('Állapot')}:</label></td>
                    <td>{$egyed.allapot}</td>
                </tr>
                <tr>
                    <td><label>{at('Kezdet')}:</label></td>
                    <td>{$egyed.kezdet}</td>
                </tr>
                <tr>
                    <td><label>{at('Vég')}:</label></td>
                    <td>{$egyed.veg}</td>
                </tr>
                <tr>
                    <td><label>{at('Időtartam')}:</label></td>
                    <td>{$egyed.idotartam}</td>
                </tr>
                <tr>
                    <td><label>{at('Gép')}:</label></td>
                    <td>{$egyed.host} (pid {$egyed.pid})</td>
                </tr>
                <tr>
                    <td><label for="UzenetEdit">{at('Üzenet')}:</label></td>
                    <td><textarea id="UzenetEdit" rows="8" cols="80" readonly="readonly">{$egyed.uzenet}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mattkarb-footer">
        <a id="mattkarb-cancelbutton" href="#">{at('Bezár')}</a>
    </div>
</form>
