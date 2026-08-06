{extends "../base.tpl"}

{block "kozep"}
    {* Minden törzsadat önálló mattable képernyőt kapott – ez a lap már csak gyűjtő.
       A menüpontok az Egyebek menücsoportban is elérhetők. *}
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid egyebadat-links">
            <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all">
                <span>{at('Kereskedelem, pénzügy')}</span>
            </div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/vtsz/viewlist"><span class="ui-button-text">{at('VTSZ')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/valutanem/viewlist"><span class="ui-button-text">{at('Valutanemek')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/arfolyam/viewlist"><span class="ui-button-text">{at('Árfolyamok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/bankszamla/viewlist"><span class="ui-button-text">{at('Bankszámlák')}</span></a></div>
            {if ($setup.bankpenztar)}
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/penztar/viewlist"><span class="ui-button-text">{at('Pénztárak')}</span></a></div>
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/jogcim/viewlist"><span class="ui-button-text">{at('Jogcímek')}</span></a></div>
            {/if}
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/raktar/viewlist"><span class="ui-button-text">{at('Raktárak')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/csk/viewlist"><span class="ui-button-text">{at('CSK kódok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/me/viewlist"><span class="ui-button-text">{at('Mennyiségi egységek')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/arsav/viewlist"><span class="ui-button-text">{at('Ársávok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/unnepnap/viewlist"><span class="ui-button-text">{at('Ünnepnapok')}</span></a></div>
        </div>
    </div>
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid egyebadat-links">
            <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all">
                <span>{at('Partner, termék')}</span>
            </div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/partnertipus/viewlist"><span class="ui-button-text">{at('Partner típusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/partnercimkekat/viewlist"><span class="ui-button-text">{at('Partnercímke csoportok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/termekcimkekat/viewlist"><span class="ui-button-text">{at('Termékcímke csoportok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/termekcsoport/viewlist"><span class="ui-button-text">{at('Termékcsoportok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/termekvaltozatadattipus/viewlist"><span class="ui-button-text">{at('Termékváltozat adattípusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/irszam/viewlist"><span class="ui-button-text">{at('Irányítószámok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/korzetszam/viewlist"><span class="ui-button-text">{at('Körzetszámok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/szotar/viewlist"><span class="ui-button-text">{at('Szótár')}</span></a></div>
            {if ($setup.rewrite301)}
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/rw301/viewlist"><span class="ui-button-text">{at('Átirányítások (301)')}</span></a></div>
            {/if}
        </div>
    </div>
    <div class="egyebadat-wrapper">
        <div class="egyebadat-grid egyebadat-links">
            <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all">
                <span>{at('HR, rendezvény, egyéb')}</span>
            </div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/munkakor/viewlist"><span class="ui-button-text">{at('Munkakörök')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/jelenlettipus/viewlist"><span class="ui-button-text">{at('Jelenlét típusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/kapcsolatfelveteltema/viewlist"><span class="ui-button-text">{at('Kapcsolatfelvétel témák')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/felhasznalo/viewlist"><span class="ui-button-text">{at('Felhasználók (webshop)')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/jogaterem/viewlist"><span class="ui-button-text">{at('Termek')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/jogaoratipus/viewlist"><span class="ui-button-text">{at('Óratípusok')}</span></a></div>
            <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                    href="/admin/rendezvenyallapot/viewlist"><span class="ui-button-text">{at('Rendezvény állapotok')}</span></a></div>
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
                <div><a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                        href="/admin/mptngykar/viewlist"><span class="ui-button-text">{at('MPT NGY karok')}</span></a></div>
            {/if}
        </div>
    </div>
{/block}
