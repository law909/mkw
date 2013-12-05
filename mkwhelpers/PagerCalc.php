<?php

namespace mkwhelpers;

class PagerCalc {

    protected $elemcount;
    protected $elemperpage;
    protected $pageno;
    protected $offset;

    public function __construct($elemcount, $elemperpage, $pageno) {
        $this->initValues($elemcount, $elemperpage, $pageno);
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getElemPerPage() {
        return $this->elemperpage;
    }

    public function getPageCount() {
        if ($this->elemperpage <> 0) {
            $pc = ceil($this->elemcount / $this->elemperpage);
        }
        else {
            $pc = 0;
        }
        return $pc;
    }

    public function initValues($elemcount, $elemperpage, $pageno) {
        $this->elemcount = $elemcount;
        if ($elemperpage <= 0) {
            $this->elemperpage = 30;
        }
        else {
            $this->elemperpage = $elemperpage;
        }
        $this->pageno = min($pageno, ceil($this->elemcount / $this->elemperpage));
        if ($this->pageno <= 0) {
            $this->pageno = 1;
        }
        $this->offset = $this->pageno * $this->elemperpage - $this->elemperpage;
    }

    public function loadValues($ide = array()) {
        $ide['firstelemno'] = $this->offset + 1;
        $ide['lastelemno'] = $this->offset + $this->elemperpage;
        $ide['elemperpage'] = $this->elemperpage;
        $ide['pageno'] = $this->pageno;
        $ide['pagecount'] = $this->getPageCount();
        $ide['elemcount'] = $this->elemcount;
        return $ide;
    }

}
