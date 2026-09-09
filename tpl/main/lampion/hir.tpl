{extends "base.tpl"}

{block "kozep"}
    <div class="hasab szoveglap">
        <article>
            <h1>{$hir.cim}</h1>
            <div class="hirdatum">{$hir.forras} {$hir.datum}</div>
            {$hir.szoveg}
        </article>
    </div>
{/block}
