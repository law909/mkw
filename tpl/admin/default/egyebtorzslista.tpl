{extends "../base.tpl"}

{block "inhead"}
    <link rel="stylesheet" type="text/css" media="screen" href="/themes/admin/{$theme}/ui.jqgrid.css"/>
    <script type="text/javascript" src="/js/admin/default/grid.locale-hu.js"></script>
    <script type="text/javascript">
        $.jgrid.useJSON = true;
    </script>
    <script type="text/javascript" src="/js/admin/default/jquery.jqGrid.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/egyebtorzslista.js"></script>
{/block}

{block "kozep"}
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid">
            <table id="afagrid"></table>
            <div id="afagridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="vtszgrid"></table>
            <div id="vtszgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="valutanemgrid"></table>
            <div id="valutanemgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="arfolyamgrid"></table>
            <div id="arfolyamgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="raktargrid"></table>
            <div id="raktargridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="orszaggrid"></table>
            <div id="orszaggridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="irszamgrid"></table>
            <div id="irszamgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="termekcsoportgrid"></table>
            <div id="termekcsoportgridpager"></div>
        </div>
        {if ($setup.bankpenztar)}
            <div class="egyebadat-grid">
                <table id="jogcimgrid"></table>
                <div id="jogcimgridpager"></div>
            </div>
            <div class="egyebadat-grid">
                <table id="penztargrid"></table>
                <div id="penztargridpager"></div>
            </div>
        {/if}
        <div class="egyebadat-grid">
            <table id="szotargrid"></table>
            <div id="szotargridpager"></div>
        </div>
    </div>
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid">
            <table id="partnertipusgrid"></table>
            <div id="partnertipusgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="partnercimkekatgrid"></table>
            <div id="partnercimkekatgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="termekcimkekatgrid"></table>
            <div id="termekcimkekatgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="termekvaltozatadattipusgrid"></table>
            <div id="termekvaltozatadattipusgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="munkakorgrid"></table>
            <div id="munkakorgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="felhasznalogrid"></table>
            <div id="felhasznalogridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="jelenlettipusgrid"></table>
            <div id="jelenlettipusgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="kapcsolatfelveteltemagrid"></table>
            <div id="kapcsolatfelveteltemagridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="cskgrid"></table>
            <div id="cskgridpager"></div>
        </div>
        {if ($setup.receptura)}
            <div class="egyebadat-grid">
                <table id="termekrecepttipusgrid"></table>
                <div id="termekrecepttipusgridpager"></div>
            </div>
        {/if}
        {if ($setup.mijsz)}
        <div class="egyebadat-grid">
            <table id="mijszoklevelkibocsajtogrid"></table>
            <div id="mijszoklevelkibocsajtogridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="mijszoklevelszintgrid"></table>
            <div id="mijszoklevelszintgridpager"></div>
        </div>
        {/if}
        {if ($maintheme === 'darshan')}
            <div class="egyebadat-grid">
                <table id="jogateremgrid"></table>
                <div id="jogateremgridpager"></div>
            </div>
            <div class="egyebadat-grid">
                <table id="jogaoratipusgrid"></table>
                <div id="jogaoratipusgridpager"></div>
            </div>
        {/if}

    </div>
    <div class="egyebadat-longwrapper">
        <div class="egyebadat-grid">
            <table id="bankszamlagrid"></table>
            <div id="bankszamlagridpager"></div>
        </div>
        {if ($setup.rewrite301)}
        <div class="egyebadat-grid">
            <table id="rw301grid"></table>
            <div id="rw301gridpager"></div>
        </div>
        {/if}
    </div>
{/block}