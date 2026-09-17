{* Lapozó linkek. A hívó a $lapozourl-ben adja meg az oldal saját, paraméter nélküli URL-jét.
   A href valódi cím (a kereső így végigjárja a lapokat), a data-pageno-t a JS használja, hogy a
   felhasználó által beállított rendezés/szűrés megmaradjon. Az 1. oldal linkje paraméter nélküli. *}
{if ($lapozo.pageno>1)}<a class="pageedit" rel="prev" data-pageno="{$lapozo.pageno-1}" href="{$lapozourl}{if ($lapozo.pageno-1>1)}?pageno={$lapozo.pageno-1}{/if}">&lt; {t('Előző')}</a>{/if}
{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap" aria-current="page">{$i}</span>{else}<a class="pageedit" data-pageno="{$i}" href="{$lapozourl}{if ($i>1)}?pageno={$i}{/if}">{$i}</a>{/if}{/for}
{if ($lapozo.pageno<$lapozo.pagecount)}<a class="pageedit" rel="next" data-pageno="{$lapozo.pageno+1}" href="{$lapozourl}?pageno={$lapozo.pageno+1}">{t('Következő')} &gt;</a>{/if}
