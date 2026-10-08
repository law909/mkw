<div id="mattkarb-header">
    <h3>{at('Raktár')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="50" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Készletet mozgat" for="MozgatEdit"}
                    <input id="MozgatEdit" name="mozgat" type="checkbox"{if ($egyed.mozgat)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Inaktív" for="ArchivEdit"}
                    <input id="ArchivEdit" name="archiv" type="checkbox"{if ($egyed.archiv)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Idegen kód" for="IdegenkodEdit"}
                    <input id="IdegenkodEdit" name="idegenkod" type="text" size="80" maxlength="255" value="{$egyed.idegenkod}">
                {/mezo}
                {mezo cimke="Készlete látszik"}
                    <input id="LathatoCheck" name="lathato" type="checkbox"{if ($egyed.lathato)} checked="checked"{/if}>{$webshop1name}
                    {if ($setup.multishop)}
                        {for $cikl = 2 to $enabledwebshops}
                            <input id="Lathato{$cikl}Check" name="lathato{$cikl}" type="checkbox"{if ($egyed["lathato$cikl"])} checked="checked"{/if}>{$webshop{$cikl}name}
                        {/for}
                    {/if}
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
