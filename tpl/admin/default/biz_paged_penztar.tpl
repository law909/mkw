{*
    Pénztárbizonylat. Az $egyed nem a Bizonylatfej::toLista()-ból jön, hanem a
    penztarbizonylatfejController::renderBizonylat() állítja össze, ezért a mezőnevek
    (partner*, jogcimnev, bruttokiirva, irany) eltérnek a többi bizonylatétól.
*}
{extends "biz_paged_base.tpl"}

{$w = ['jogcim'=>'20%','szoveg'=>'34%','hivatkozas'=>'22%','esedekesseg'=>'12%','osszeg'=>'12%']}

{block "title"}{$egyed.bizonylatnev}{if ($egyed.rontott)} <span style="color:red">(rontott)</span>{/if}{/block}

{block "headboxes"}
    <div class="bold" style="padding: 0 5px 4px 5px; font-size: 11pt;">
        {if ($egyed.irany > 0)}Bevételi pénztárbizonylat{else}Kiadási pénztárbizonylat{/if}
    </div>
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="50%" style="padding: 5px 5px 0 5px;">Kiállító:</td>
            <td width="50%" style="padding: 5px 5px 0 5px;">{if ($egyed.irany > 0)}Befizető:{else}Átvevő:{/if}</td>
        </tr>
        <tr>
            <td class="topalign" style="padding: 5px;">
                <span class="nev bold">{$egyed.tulajnev}</span><br />
                {$egyed.tulajirszam} {$egyed.tulajvaros}<br />
                {$egyed.tulajutca}<br />
                Adószám: {$egyed.tulajadoszam}
            </td>
            <td class="topalign" style="padding: 5px;">
                <span class="nev bold">{$egyed.partnernev}</span><br />
                {$egyed.partnerirszam} {$egyed.partnervaros}<br />
                {$egyed.partnerutca} {$egyed.partnerhazszam}
                {if ($egyed.partneradoszam)}<br />Adószám: {$egyed.partneradoszam}{/if}
                {if ($egyed.partnereuadoszam)}<br />EU adószám: {$egyed.partnereuadoszam}{/if}
            </td>
        </tr>
    </table>
{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="25%">Pénztár</td>
            <td width="20%">Kelt</td>
            <td width="17%">Valutanem</td>
            <td width="19%">Er.biz.szám</td>
            <td width="19%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.penztarnev|default:"&nbsp;"}</td>
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td>{$egyed.erbizonylatszam|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}

{block "headextra"}
    {if ($egyed.megjegyzes)}
        <div style="padding: 0 5px;">Közlemény: {$egyed.megjegyzes}</div>
        <div class="topline topbottommargin"></div>
    {/if}
{/block}

{block "columnheaders"}
    <tr class="bold">
        <td width="{$w.jogcim}">Jogcím</td>
        <td width="{$w.szoveg}">Szöveg</td>
        <td width="{$w.hivatkozas}">Hivatkozott bizonylat</td>
        <td width="{$w.esedekesseg}" class="textalignright">Esedékesség</td>
        <td width="{$w.osszeg}" class="textalignright">Összeg</td>
    </tr>
{/block}

{block "itemrows"}
    <tr class="tetelsor">
        <td width="{$w.jogcim}">{$tetel.jogcimnev}</td>
        <td width="{$w.szoveg}">{$tetel.szoveg}</td>
        <td width="{$w.hivatkozas}">{$tetel.hivatkozottbizonylat}</td>
        <td width="{$w.esedekesseg}" class="textalignright">{$tetel.hivatkozottdatumstr}</td>
        <td width="{$w.osszeg}" class="textalignright">{number_format($tetel.brutto,0,'',' ')}</td>
    </tr>
{/block}

{block "summary"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="osszesen bold">
            <td width="60%" class="textalignright">{if ($egyed.irany > 0)}Befizetve:{else}Kifizetve:{/if}</td>
            <td width="40%" class="textalignright">{number_format($egyed.brutto,0,'',' ')} {$egyed.valutanemnev}</td>
        </tr>
        <tr>
            <td colspan="2" class="textalignright keszult">azaz {$egyed.bruttokiirva}</td>
        </tr>
    </table>
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0" style="padding-top: 25px;">
        <tr class="textaligncenter">
            <td width="33%"><div class="topline"></div>Pénztáros</td>
            <td width="34%" style="padding: 0 10px;"><div class="topline"></div>{if ($egyed.irany > 0)}Befizető{else}Átvevő{/if}</td>
            <td width="33%"><div class="topline"></div>Utalványozó</td>
        </tr>
    </table>
{/block}
