{extends "base.tpl"}

{block "kozep"}
    <div>
        <h3>{$csapat.nev}</h3>
        {$csapat.id}
        {$csapat.nev}
        {$csapat.slug}
        {$csapat.logourl}
        {$csapat.logourlsmall}
        {$csapat.logourlmini}
        {$csapat.logoleiras}
        {$csapat.leiras}
        {$csapat.kepurl}
        {$csapat.kepurlsmall}
        {$csapat.kepurlmini}
        {$csapat.kepleiras}
        {foreach $csapat.versenyzok as $_versenyzo}
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

        {/foreach}
    </div>
{/block}