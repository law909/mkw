<?php
function run() {
    runsql('UPDATE bizonylattipus SET showkupon=1 WHERE id="megrendeles"');
    runsql('UPDATE bizonylattipus SET showkupon=1 WHERE id="szamla"');
}
