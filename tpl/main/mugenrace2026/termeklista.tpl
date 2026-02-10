{extends "base.tpl"}

{block "kozep"}
    <div class="container page-header">
        <div class="row">
            <div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
            <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
                {if ($navigator|default)}
                    <a href="/" rel="v:url" property="v:title">
                        {t('Home')}
                    </a>
                    <i class="icon arrow-right"></i>







{foreach $navigator as $_navi}
                    {if ($_navi.url|default)}
                        <span typeof="v:Breadcrumb" class="breadcrumb-{$_navi.url}">
                                <a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
                                    {$_navi.caption|lower|capitalize}
                                </a>
                            </span>
                        <i class="icon arrow-right breadcrumb-{$_navi.url}"></i>
                    {else}
                        {$_navi.caption|lower|capitalize}
                    {/if}
                {/foreach}
                {/if}
            </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                {foreach $navigator as $_navi}
                    {if ($_navi@last)}
                        <h1 class="page-header__title" typeof="v:Breadcrumb">
                            <a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
                                {$_navi.caption|lower|capitalize}
                            </a>
                        </h1>
                    {/if}
                {/foreach}
            </div>
            <div class="col flex-cr">
                <button class="bordered product-filter__toggle">
                    <span>{t('Szűrők')}</span>
                    <i class="icon filter"></i>
                </button>
            </div>
        </div>

    </div>
    <div class="container whitebg">

        <div class="product-filter">
            <div class="product-filter__header flex-lc">
                <span class="product-filter__title bold">{t('Szűrőfeltételek')}</span>
                <span class="product-filter__close js-filterclose"><i class="icon close icon__click"></i></span>
            </div>

            <select name="elemperpage" class="elemperpageedit">
                {$elemszam = array(10, 20, 30, 40, $lapozo.elemcount)}
                {$elemnev = array("10 "|cat:t('darab'), "20 "|cat:t('darab'), "30 "|cat:t('darab'), "40 "|cat:t('darab'), t("Mind"))}
                {foreach $elemszam as $c}
                    <option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
                {/foreach}
            </select>

            <select name="order" class="orderedit">
                <option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
                <option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
                <option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
                <option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
                <option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
                <option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
            </select>
            <input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
            <input id="ListviewEdit" type="hidden" name="vt" value="{$vt}">
            <input id="CsakakciosEdit" type="hidden" name="csakakcios" value="{$csakakcios}">

            <div class="szurofej szurokontener js-filterclear bold">
                {t('Szűrőfeltételek törlése')}
            </div>

            {* <div class="szurokontener">
                <div class="szurofej closeupbutton" data-refcontrol="#ArSzuro">{t('Ár')} <i class="icon-chevron-up"></i></div>
                <div id="ArSzuro" class="szurodoboz">
                    <input id="ArSlider" type="slider" name="ar" value="{$minarfilter};{$maxarfilter}" data-maxar="{$maxar}" data-step="{$arfilterstep}">
                </div>
            </div> *}

            <form id="szuroform">
                {foreach $szurok as $_szuro}
                    <div class="szurokontener">
                        <div class="szurofej closeupbutton" data-refcontrol="#SzuroFej{$_szuro.id}">{$_szuro.caption|lower|capitalize} <i
                                class="icon-chevron-up"></i></div>
                        <div id="SzuroFej{$_szuro.id}" class="szurodoboz">
                            {foreach $_szuro.cimkek as $_ertek}
                                <div>
                                    <label class="checkbox" for="SzuroEdit{$_ertek.id}">
                                        <input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}"
                                               type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption|lower|capitalize}{if ($_ertek.termekdb|default)} ({$_ertek.termekdb}){/if}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                {/foreach}
            </form>
        </div>


        <div class="row">
            {* <div class="span3">
                <div class="szurofej szurokontener js-filterclear bold">
                    {t('Szűrőfeltételek törlése')}
                </div>
                <form id="szuroform">
                szűrők
                    {foreach $szurok as $_szuro}
                    <div class="szurokontener">
                        <div class="szurofej closeupbutton" data-refcontrol="#SzuroFej{$_szuro.id}">{$_szuro.caption} <i class="icon-chevron-up"></i></div>
                        <div id="SzuroFej{$_szuro.id}" class="szurodoboz">
                            {foreach $_szuro.cimkek as $_ertek}
                                <div>
                                    <label class="checkbox" for="SzuroEdit{$_ertek.id}">
                                        <input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}" type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption}{if ($_ertek.termekdb|default)} ({$_ertek.termekdb}){/if}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                    {/foreach}
                </form>
            </div> *}
            <div class="col">
                <div class="category-description">
                    {$kategoria.leiras2}
                </div>
                {$lntcnt=0}
                {if is_array($kiemelttermekek)}
                    {$lntcnt=count($kiemelttermekek)}
                {/if}
                {if ($lntcnt>0)}
                <div class="lapozo">
                    <h2 class="bold category-title">{t('Kiemelt termékeink')}</h2>
                </div>
                {$step=min(3, $lntcnt)}
                {if ($step==0)}
                    {$step=1}
                {/if}
                <div class="product-list">
                    {for $i=0 to $lntcnt-1 step $step}
                        {for $j=0 to $step-1}
                            {if ($i+$j<$lntcnt)}
                                {if (isset($kiemelttermekek[$i+$j]))}
                                    {$_termek=$kiemelttermekek[$i+$j]}
                                {else}
                                    {$_termek=null}
                                {/if}
                                {include 'blokkok/termek.tpl' termek=$_termek detailsbutton=false}
                            {/if}
                        {/for}
                    {/for}
                </div>
            <div class="product-list__divider divider"></div>
            {/if}
            <div class="lapozo">
                <form class="lapozoform" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
                    <table>
                        <tbody>
                        <tr>
                            {* <td class="lapozotalalat">
                                <select name="elemperpage" class="elemperpageedit">
                                    {$elemszam = array(10, 20, 30, 40, $lapozo.elemcount)}
                                    {$elemnev = array("10 "|cat:t('darab'), "20 "|cat:t('darab'), "30 "|cat:t('darab'), "40 "|cat:t('darab'), t("Mind"))}
                                    {foreach $elemszam as $c}
                                    <option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
                                    {/foreach}
                                </select>
                            </td> *}
                            {* <td class="lapozooldalak">
                                {if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
                                {for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
                                {if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
                            </td> *}
                            {* <td class="lapozorendezes">
                                <select name="order" class="orderedit">
                                    <option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
                                    <option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
                                    <option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
                                    <option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
                                    <option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
                                    <option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
                                </select>
                                <input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
                                <input id="ListviewEdit" type="hidden" name="vt" value="{$vt}">
                                <input id="CsakakciosEdit" type="hidden" name="csakakcios" value="{$csakakcios}">
                            </td> *}
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>


            {if ($lapozo.elemcount>0)}
            {$termekcnt=0}
            {if (is_array($termekek))}
                {$termekcnt=count($termekek)}
            {/if}
            {$step=4}
            <div class="product-list">
                {for $i=0 to $termekcnt-1 step $step}
                    {for $j=0 to $step-1}
                        {if (isset($termekek[$i+$j]))}
                            {$_termek=$termekek[$i+$j]}
                        {else}
                            {$_termek=null}
                        {/if}
                        {if ($_termek)}
                            {include 'blokkok/termek.tpl' termek=$_termek detailsbutton=false}
                        {/if}
                    {/for}
                {/for}
            </div>
        {else}
        {t('Nincs ilyen termék')}
        {/if}
        <div class="lapozo">
            <form class="lapozoform" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
                <table>
                    <tbody>
                    <tr>
                        <td class="lapozooldalak">
                            {if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
                            {for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit"
                                                                                                                                   data-pageno="{$i}">{$i}</a>{/if}{/for}
                            {if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    </div>
    </div>
    {include 'termekertesitomodal.tpl'}
{/block}
