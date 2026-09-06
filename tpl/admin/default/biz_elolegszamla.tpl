{*
    The advance invoice's non-paged (legacy) print form. The title comes from the document type's
    name, only the explanatory line has to be added. BizonylatPrintService picks between this and
    biz_paged_elolegszamla.tpl according to the pagedpdf switch.
*}
{extends "biz_szamla.tpl"}

{block "elolegmegjegyzes"}
    <div>Előlegszámla. A végszámlában beszámításra kerül.</div>
{/block}
