{*
    Advance invoice: the invoice layout is unchanged, only the title and one explanatory line differ.
    The offset shows up in the final invoice's line names and has nothing to do with this template.
*}
{extends "biz_paged_szamla.tpl"}

{block "title"}Előlegszámla{/block}

{block "headextra"}
    {$smarty.block.parent}
    <div style="padding: 0 5px;">Előlegszámla. A végszámlában beszámításra kerül.</div>
    <div class="topline topbottommargin"></div>
{/block}
