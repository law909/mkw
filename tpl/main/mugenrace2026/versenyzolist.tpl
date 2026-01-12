{extends "base.tpl"}

{block "kozep"}
    {foreach $versenyzolista as $_versenyzo}
        <div>
            <p>{$_versenyzo.nev}</p>
            {$_versenyzo.id}
            {$_versenyzo.nev}
            {$_versenyzo.slug}
            {$_versenyzo.versenysorozat}
            {$_versenyzo.rovidleiras}
            {$_versenyzo.leiras}
            {$_versenyzo.kepurl}
            {$_versenyzo.kepurlsmall}
            {$_versenyzo.kepurlmini}
            {$_versenyzo.kepleiras}
            {$_versenyzo.kepurl1}
            {$_versenyzo.kepurl1small}
            {$_versenyzo.kepleiras1}
            {$_versenyzo.kepurl2}
            {$_versenyzo.kepurl2small}
            {$_versenyzo.kepleiras2}
            {$_versenyzo.kepurl3}
            {$_versenyzo.kepurl3small}
            {$_versenyzo.kepleiras3}
            {$_versenyzo.csapatid}
            {$_versenyzo.csapatnev}

        </div>
    {/foreach}
{/block}