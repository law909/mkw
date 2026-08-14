{extends "biz_paged_elolegbekero.tpl"}

{*
    A terméknév sorába bekerül a kiskép. A src fájlrendszerbeli út ($webroot), nem $mainurl:
    az mPDF a HTTP-s hivatkozást curl-lel töltené le a saját szerverétől, tételsoronként egyszer.
*}
{block "itemrows"}
    <tr class="tetelsor">
        <td>{$teteldb + 1}</td>
        <td width="14%" class="textaligncenter">
            {if ($tetel.kiskepurl)}<img src="{$webroot}{$tetel.kiskepurl}" alt="{$tetel.termeknev}" width="90">{/if}
        </td>
        <td colspan="{if ($egyed.kedvezmenycount > 0)}9{else}7{/if}" class="bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}({$tetel.vtszszam})</td>
    </tr>
    <tr class="tetelsor">
        <td width="{$w.sorszam}" class="dashedline"></td>
        <td width="{$w.termek}" class="dashedline"></td>
        <td width="{$w.mennyiseg}" class="textalignright dashedline">{bizformat($tetel.mennyiseg)}</td>
        <td width="{$w.me}" class="dashedline">{$tetel.me}</td>
        {if ($egyed.kedvezmenycount > 0)}
            <td width="{$w.ebrutto}" class="textalignright dashedline">{bizformat($tetel.ebruttoegysar)}</td>
            <td width="{$w.kedv}" class="textalignright dashedline">{bizformat($tetel.kedvezmeny)}</td>
        {/if}
        <td width="{$w.egysar}" class="textalignright dashedline">{bizformat($tetel.nettoegysar)}</td>
        <td width="{$w.netto}" class="textalignright dashedline">{bizformat($tetel.netto)}</td>
        <td width="{$w.afanev}" class="textalignright dashedline">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright dashedline">{bizformat($tetel.afa)}</td>
        <td width="{$w.brutto}" class="textalignright dashedline">{bizformat($tetel.brutto)}</td>
    </tr>
{/block}
