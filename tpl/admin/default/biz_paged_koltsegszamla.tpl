{*
    A default téma "teto" családjának lapozott alapja. Ezek a sablonok másképp épülnek fel, mint a
    számla-család: egy tétel EGY sor, az összesítő pedig ÁFA-részletezés + jobbra igazított
    végösszeg-tábla, "Átvevő:" aláírásvonallal.
*}
{extends "biz_paged_base.tpl"}

{$w = ['cikkszam'=>'14%','termek'=>'26%','mennyiseg'=>'11%','egysar'=>'11%','netto'=>'12%','afanev'=>'5%','afa'=>'10%','brutto'=>'11%']}

{block "title"}{$egyed.bizonylatnev}{/block}

{block "headboxes"}{include "biz_paged_headboxki_reverse.tpl"}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="18%">Eredeti biz.szám</td>
            <td width="16%">Fizetési mód</td>
            <td width="15%">Kelt</td>
            <td width="15%">Teljesítés</td>
            <td width="15%">Esedékesség</td>
            <td width="21%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.erbizonylatszam|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
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
        <td width="{$w.cikkszam}">Cikkszám</td>
        <td width="{$w.termek}">Termék neve</td>
        <td width="{$w.mennyiseg}" class="textalignright">Mennyiség</td>
        <td width="{$w.egysar}" class="textalignright">Nettó e.ár</td>
        <td width="{$w.netto}" class="textalignright">Nettó érték</td>
        <td width="{$w.afanev}" class="textalignright">ÁFA %</td>
        <td width="{$w.afa}" class="textalignright">ÁFA</td>
        <td width="{$w.brutto}" class="textalignright">Bruttó érték</td>
    </tr>
{/block}

{* ebben a családban egy tétel egyetlen sor, nem kettő *}
{block "itemrows"}
    <tr class="tetelsor">
        <td width="{$w.cikkszam}">{$tetel.cikkszam}</td>
        <td width="{$w.termek}">{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</td>
        <td width="{$w.mennyiseg}" class="textalignright">{number_format($tetel.mennyiseg,2,',',' ')} {$tetel.me}</td>
        <td width="{$w.egysar}" class="textalignright">{number_format($tetel.nettoegysar,2,',',' ')}</td>
        <td width="{$w.netto}" class="textalignright">{number_format($tetel.netto,2,',',' ')}</td>
        <td width="{$w.afanev}" class="textalignright">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright">{number_format($tetel.afa,2,',',' ')}</td>
        <td width="{$w.brutto}" class="textalignright">{number_format($tetel.brutto,2,',',' ')}</td>
    </tr>
{/block}

{block "summary"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="50%" class="topalign" style="padding-right: 8px;">
                <div class="bold" style="padding-bottom: 3px;">ÁFA részletezés</div>
                <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
                    <tr class="bold">
                        <td width="25%">ÁFA kulcs</td>
                        <td width="25%" class="textalignright">Nettó érték</td>
                        <td width="25%" class="textalignright">ÁFA érték</td>
                        <td width="25%" class="textalignright">Bruttó érték</td>
                    </tr>
                    {foreach $afaosszesito as $a}
                        <tr>
                            <td>{$a.caption}</td>
                            <td class="textalignright">{number_format($a.netto,2,',',' ')}</td>
                            <td class="textalignright">{number_format($a.afa,2,',',' ')}</td>
                            <td class="textalignright">{number_format($a.brutto,2,',',' ')}</td>
                        </tr>
                    {/foreach}
                </table>
            </td>
            <td width="50%" class="topalign">
                <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
                    {if ($egyed.valutasszamla)}
                        <tr>
                            <td>Árfolyam:</td>
                            <td class="textalignright">{number_format($egyed.arfolyam,2,',',' ')} HUF/{$egyed.valutanemnev}</td>
                            <td></td>
                        </tr>
                    {/if}
                    <tr>
                        <td width="34%">Nettó:</td>
                        <td width="33%" class="textalignright">{number_format($egyed.netto,2,',',' ')} {$egyed.valutanemnev}</td>
                        <td width="33%" class="textalignright">{if ($egyed.valutasszamla)}{number_format($egyed.nettohuf,2,',',' ')} HUF{/if}</td>
                    </tr>
                    <tr>
                        <td>ÁFA:</td>
                        <td class="textalignright">{number_format($egyed.afa,2,',',' ')} {$egyed.valutanemnev}</td>
                        <td class="textalignright">{if ($egyed.valutasszamla)}{number_format($egyed.afahuf,2,',',' ')} HUF{/if}</td>
                    </tr>
                    <tr>
                        <td>Bruttó:</td>
                        <td class="textalignright">{number_format($egyed.brutto,2,',',' ')} {$egyed.valutanemnev}</td>
                        <td class="textalignright">{if ($egyed.valutasszamla)}{number_format($egyed.bruttohuf,2,',',' ')} HUF{/if}</td>
                    </tr>
                    <tr class="bold">
                        <td class="topline">Fizetendő:</td>
                        <td class="topline textalignright">{number_format($egyed.fizetendo,2,',',' ')} {$egyed.valutanemnev}</td>
                        <td class="topline textalignright">{if ($egyed.valutasszamla)}{number_format($egyed.bruttohuf,2,',',' ')} HUF{/if}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="keszult">{$egyed.fizetendokiirva} {$egyed.valutanemnev}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0" style="padding-top: 10px;">
        <tr>
            <td width="50%">Átvevő:<div class="topline" style="margin-top: 12px;"></div></td>
            <td width="50%" class="textalignright topalign">{block "thanks"}Köszönjük, hogy nálunk vásárolt!{/block}</td>
        </tr>
    </table>
    <div class="textaligncenter" style="padding-top: 5px;">{if ($egyed.nyomtatva)}Másolat{else}Eredeti példány{/if}.</div>
{/block}
