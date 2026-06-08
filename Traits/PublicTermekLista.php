<?php

namespace Traits;

use Entities\Blogposzt;
use Entities\Szin;
use Entities\Termek;
use Entities\Termekcimketorzs;
use Entities\TermekFa;
use Entities\TermekMenu;
use Entities\TermekValtozat;
use mkw\store;
use mkwhelpers\FilterDescriptor;

trait PublicTermekLista
{

    private function getTermekIdsFromTermekListaForCimkeSzuro($filter)
    {
        $termekrepo = $this->getEm()->getRepository(Termek::class);
        $termekek = $termekrepo->getTermekLista($filter, []);
        $tid = [];
        foreach ($termekek as $termek) {
            $tid[] = $termek['id'];
        }
        return $tid;
    }

    private function getTermekIdsForCimkeSzuro($filter)
    {
        $termekrepo = $this->getEm()->getRepository(Termek::class);
        $termekids = $termekrepo->getTermekIds($filter);
        $tid = [];
        foreach ($termekids as $termek) {
            $tid[] = $termek['id'];
        }
        return $tid;
    }

    private function buildTermekLista(array $termekek, int $ujtermekminid = 0, int $top10min = 0): array
    {
        $termekrepo = $this->getEm()->getRepository(Termek::class);
        $t = [];
        foreach ($termekek as $termek) {
            /** @var \Entities\Termek $term */
            $term = $termekrepo->find($termek['id']);
            if ($termek['valtozatid']) {
                $valt = $this->getEm()->getRepository(TermekValtozat::class)->find($termek['valtozatid']);
            } else {
                $valt = null;
            }
            $tete = $term->toTermekLista($valt, $ujtermekminid, $top10min);
            $tete['kiemelt'] = false;
            $t[] = $tete;
        }
        return $t;
    }

    /**
     * $termekek getTermekLista nyers sorai (id, valtozatid, szin_id)
     */
    private function buildSzinesTermekLista(array $termekek, int $ujtermekminid = 0, int $top10min = 0): array
    {
        $termekrepo = $this->getEm()->getRepository(Termek::class);
        $t = [];
        foreach ($termekek as $termek) {
            $_termekidfilter = new FilterDescriptor();
            $_termekidfilter->addFilter('id', '=', $termek['id']);
            /** @var \Entities\Termek $term */
            $term = $termekrepo->getWithJoins($_termekidfilter);
            if (is_array($term)) {
                $term = $term[0];
            }
            $szin = null;
            $valt = null;
            if ($termek['valtozatid']) {
                /** @var \Entities\TermekValtozat $valt */
                $valt = $this->getEm()->getRepository(TermekValtozat::class)->find($termek['valtozatid']);
            } elseif ($termek['szin_id']) {
                $szin = $this->getEm()->getRepository(Szin::class)->find($termek['szin_id']);
            }
            $tete = $term->toSzinesTermekLista($valt, $ujtermekminid, $top10min, $szin);
            $tete['kiemelt'] = false;
            $t[] = $tete;
        }
        return $t;
    }

    /**
     * Kiemelt termékek sorai (toTermekLista formátum).
     */
    private function buildKiemeltTermekLista(FilterDescriptor $szuro, int $kiemelttermekdb, int $ujtermekminid = 0, int $top10min = 0)
    {
        $kiemelt = [];
        if ($kiemelttermekdb > 0) {
            $termekrepo = $this->getEm()->getRepository(Termek::class);
            foreach ($termekrepo->getKiemeltTermekek($szuro, $kiemelttermekdb) as $termek) {
                $term = $termekrepo->find($termek['id']);
                $valt = $termek['valtozatid']
                    ? $this->getEm()->getRepository(TermekValtozat::class)->find($termek['valtozatid'])
                    : null;
                $tete = $term->toTermekLista($valt, $ujtermekminid, $top10min);
                $tete['kiemelt'] = true;
                $kiemelt[] = $tete;
            }
        }
        return $kiemelt;
    }

    private function buildArfilter($minarfilter, $maxarfilter): FilterDescriptor
    {
        $arfilterstring = '(_xx.brutto>=' . $minarfilter . ')';
        if ($maxarfilter > 0) {
            $arfilterstring .= ' AND (_xx.brutto<=' . $maxarfilter . ')';
        }
        $arfilter = new FilterDescriptor();
        $arfilter->addSql('((' . $arfilterstring . ') OR (_xx.brutto IS NULL))');
        return $arfilter;
    }

    /**
     * A "filter" string ("x_<kategoria>_<ertek>,...") dekódolása
     * kategóriánként csoportosított érték-tömbbé.
     */
    private function decodeCimkeSzuroString(string $szurostr): array
    {
        $szurotomb = [];
        foreach (explode(',', $szurostr) as $egyszuro) {
            $egyreszei = explode('_', $egyszuro);
            if (count($egyreszei) >= 3) {
                $szurotomb[$egyreszei[1]][] = $egyreszei[2] * 1;
            }
        }
        return $szurotomb;
    }

    /**
     * A címkeszűrőből (decodeCimkeSzuroString()) (AND kapcsolat) adódó termék-id szűrő.
     * Üres találat esetén szándékosan kizáró szűrőt ad (id = false).
     */
    private function buildTermekidFilter(array $szurotomb): FilterDescriptor
    {
        $termekidfilter = new FilterDescriptor();
        if (count($szurotomb) === 0) {
            return $termekidfilter;
        }
        $termekidfiltered = [];
        foreach (\mkw\store::getEm()->getRepository(Termekcimketorzs::class)->getTermekIdsWithCimkeAnd($szurotomb) as $sor) {
            $termekidfiltered[] = $sor['termek_id'];
        }
        if (count($termekidfiltered) > 0) {
            $termekidfilter->addFilter('id', null, $termekidfiltered);
        } else {
            $termekidfilter->addFilter('id', '=', false);
        }
        return $termekidfilter;
    }

    /**
     * Az "arfilter" paraméter ("min;max") felbontása.
     * Ha a két érték egyenlő, a szűrő ki van kapcsolva (0;0).
     */
    private function parseArfilter(string|null $arfilter): array
    {
        $arfiltertomb = explode(';', $arfilter);
        $minarfilter = (float)($arfiltertomb[0] ?? 0);
        $maxarfilter = (float)($arfiltertomb[1] ?? 0);
        if ($minarfilter == $maxarfilter) {
            $minarfilter = 0;
            $maxarfilter = 0;
        }
        return [$minarfilter, $maxarfilter];
    }

    private function buildAkciosFilter($akcios): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($akcios) {
            $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
            $filter->addSql($termekrepo->getAkciosFilterSQL());
        }
        return $filter;
    }

    private function buildTermekfaFilter(TermekFa|null $kategoria): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($kategoria) {
            $filter->addFilter(['_xx.termekfa1', '_xx.termekfa2', '_xx.termekfa3'], '=', $kategoria->getId());
        }
        return $filter;
    }

    private function buildNativTermekfaFilter(TermekFa|null $kategoria): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($kategoria) {
            $filter->addFilter(['_xx.termekfa1_id', '_xx.termekfa2_id', '_xx.termekfa3_id'], '=', $kategoria->getId());
        }
        return $filter;
    }

    private function buildTermekmenuFilter(TermekMenu|array|null $termekmenu): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($termekmenu) {
            if (is_array($termekmenu)) {
                $filter->addFilter('_xx.termekmenu1', '=', $termekmenu['id']);
            } else {
                $filter->addFilter('_xx.termekmenu1', '=', $termekmenu->getId());
            }
        }
        return $filter;
    }

    private function buildNativTermekmenuFilter(TermekMenu|null $termekmenu): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($termekmenu) {
            $filter->addFilter('_xx.termekmenu1_id', '=', $termekmenu->getId());
        }
        return $filter;
    }

    private function buildKeresoszoFilter(string|null $keresoszo): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        if ($keresoszo) {
            $filter->addFilter(['_xx.nev', '_xx.oldalcim', '_xx.cikkszam', '_xx.leiras'], 'LIKE', '%' . $keresoszo . '%');
        }
        return $filter;
    }

    private function orderMap(string|null $ord): array
    {
        return match ($ord) {
            'nevasc' => ['_xx.nev' => 'ASC'],
            'nevdesc' => ['_xx.nev' => 'DESC'],
            'arasc' => ['_xx.brutto' => 'ASC'],
            'ardesc' => ['_xx.brutto' => 'DESC'],
            'idasc' => ['_xx.id' => 'ASC'],
            'iddesc' => ['_xx.id' => 'DESC'],
            default => [],
        };
    }

    private function buildBlogposztLista(TermekFa|null $parent): array
    {
        $bpt = [];
        if (!$parent) {
            $blogposztok = \mkw\store::getEm()->getRepository(Blogposzt::class)->getByTermekfa($parent);
            if ($blogposztok) {
                /** @var \Entities\Blogposzt $poszt */
                foreach ($blogposztok as $poszt) {
                    $bpt[] = $poszt->convertToArray();
                }
            }
        }
        return $bpt;
    }

}