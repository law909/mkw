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
    {* Az itt felsorolt törzsadatok önálló képernyőt kaptak (lásd az Egyebek menücsoportot).
       A maradék rács fokozatosan költözik át – a végén ez a lap tiszta linkgyűjtő lesz. *}
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid egyebadat-links">
            <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all">
                <span>{at('Önálló képernyőn')}</span>
            </div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/termekcsoport/viewlist"><span class="ui-button-text">{at('Termékcsoportok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/jelenlettipus/viewlist"><span class="ui-button-text">{at('Jelenlét típusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/kapcsolatfelveteltema/viewlist"><span class="ui-button-text">{at('Kapcsolatfelvétel témák')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/termekvaltozatadattipus/viewlist"><span class="ui-button-text">{at('Termékváltozat adattípusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/munkakor/viewlist"><span class="ui-button-text">{at('Munkakörök')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/arsav/viewlist"><span class="ui-button-text">{at('Ársávok')}</span></a></div>
            {if ($setup.bankpenztar)}
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/jogcim/viewlist"><span class="ui-button-text">{at('Jogcímek')}</span></a></div>
            {/if}
            {if ($setup.mpt)}
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptszekcio/viewlist"><span class="ui-button-text">{at('MPT szekciók')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mpttagozat/viewlist"><span class="ui-button-text">{at('MPT tagozatok')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mpttagsagforma/viewlist"><span class="ui-button-text">{at('MPT tagság formák')}</span></a></div>
            {/if}
            {if ($setup.mptngy)}
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngytemakor/viewlist"><span class="ui-button-text">{at('MPT NGY témakörök')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngytema/viewlist"><span class="ui-button-text">{at('MPT NGY témák')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngyszerepkor/viewlist"><span class="ui-button-text">{at('MPT NGY szerepkörök')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngyszakmaianyagtipus/viewlist"><span class="ui-button-text">{at('MPT NGY szakmai anyag típusok')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngyegyetem/viewlist"><span class="ui-button-text">{at('MPT NGY egyetemek')}</span></a></div>
            {/if}
        </div>
    </div>
    <div class="egyebadat-wrapper">
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
            <table id="irszamgrid"></table>
            <div id="irszamgridpager"></div>
        </div>
        {if ($setup.bankpenztar)}
            <div class="egyebadat-grid">
                <table id="penztargrid"></table>
                <div id="penztargridpager"></div>
            </div>
        {/if}
        <div class="egyebadat-grid">
            <table id="szotargrid"></table>
            <div id="szotargridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="megrid"></table>
            <div id="megridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="unnepnapgrid"></table>
            <div id="unnepnapgridpager"></div>
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
            <table id="felhasznalogrid"></table>
            <div id="felhasznalogridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="cskgrid"></table>
            <div id="cskgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="korzetszamgrid"></table>
            <div id="korzetszamgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="jogateremgrid"></table>
            <div id="jogateremgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="jogaoratipusgrid"></table>
            <div id="jogaoratipusgridpager"></div>
        </div>
        <div class="egyebadat-grid">
            <table id="rendezvenyallapotgrid"></table>
            <div id="rendezvenyallapotgridpager"></div>
        </div>
        {if ($setup.mptngy)}
            <div class="egyebadat-grid">
                <table id="mptngykargrid"></table>
                <div id="mptngykargridpager"></div>
            </div>
            <div class="egyebadat-grid">
                <table id="teremgrid"></table>
                <div id="teremgridpager"></div>
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
