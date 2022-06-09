<?php

namespace Controllers;


class teljesitmenyjelentesController extends \mkwhelpers\MattableController {

    public function view() {
        $view = $this->createView('teljesitmenyjelentes.tpl');

        //$view->setVar('toldatum', date(\mkw\store::$DateFormat));
        //$view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $view->printTemplateResult();
    }

    public function getData($tol = null, $ig = null) {

        function per($a, $b) {
            $a = (float)$a;
            $b = (float)$b;
            if ($b) {
                return $a / $b;
            }
            return 0;
        }

        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');

        if (!$tol) {
            $evtol = \mkw\store::getParameter(\mkw\consts::TeljesitmenyKezdoEv, 2014);
            $hotol = '01';
            $naptol = '01';
            $tol = new \DateTime($evtol . '-' . $hotol . '-' . $naptol);
        }
        else {
            $tol = \mkw\store::toDate($tol);
            $evtol = (int)$tol->format('Y');
            $hotol = $tol->format('m');
            $naptol = $tol->format('d');
        }

        if (!$ig) {
            $ig = new \DateTime();
            $evig = (int)date('Y');
            $hoig = date('m');
            $napig = date('d');
        }
        else {
            $ig = \mkw\store::toDate($ig);
            $evig = (int)$ig->format('Y');
            $hoig = $ig->format('m');
            $napig = $ig->format('d');
        }

        $evek = array();
        for ($ev = $evtol; $ev <= $evig; $ev++) {
            $evek[] = array(
                'eleje' => (string)$ev . '-' . $hotol . '-' . $naptol,
                'vege' => (string)$ev . '-' . $hoig . '-' . $napig
            );
        }

        $elejenaphoz = new \DateTime($evtol . '-' . $hoig . '-' . $napig);
        $nap = $elejenaphoz->diff($tol);
        $nap = $nap->days + 1;

        $sqls = array();
        foreach ($evek as $ev) {
            $sqls[] = '((_xx.kelt>="' . $ev['eleje'] . '") AND (_xx.kelt<="' . $ev['vege'] . '"))';
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus_id', '=', 'megrendeles');
        $filter->addSql(implode(' OR ', $sqls));
        $sorok = $bfrepo->calcTeljesitmeny($filter);
        $adat = array();
        foreach ($sorok as $sor) {
            $ev = (int)$sor['ev'];
            $a = array();
            $a['ev'] = $ev;
            $a['megrendelesdb'] = (int)$sor['db'];
            $a['megrendelesdbpernap'] = per($sor['db'], $nap);
            $a['megrendelesnetto'] = (float)$sor['netto'];
            $a['megrendelesnettopernap'] = per($sor['netto'], $nap);
            $a['megrendelesnettoperdb'] = per($sor['netto'], $sor['db']);
            if (array_key_exists($ev - 1, $adat)) {
                $a['megrendelesdbvalt'] = per($a['megrendelesdb'], $adat[$ev - 1]['megrendelesdb']) * 100;
                $a['megrendelesnettovalt'] = per($a['megrendelesnetto'], $adat[$ev - 1]['megrendelesnetto']) * 100;
                $a['megrendelesnettoperdbvalt'] = per($a['megrendelesnettoperdb'], $adat[$ev - 1]['megrendelesnettoperdb']) * 100;
            }
            else {
                $a['megrendelesdbvalt'] = 0;
                $a['megrendelesnettovalt'] = 0;
                $a['megrendelesnettoperdbvalt'] = 0;
            }
            $adat[$ev] = $a;
        }
        $filter->clear();
        $filter->addFilter('bizonylattipus_id', '=', 'szamla');
        $filter->addSql(implode(' OR ', $sqls));
        $sorok = $bfrepo->calcTeljesitmeny($filter);
        foreach ($sorok as $sor) {
            $ev = $sor['ev'] * 1;
            $adat[$ev]['szamladb'] = $sor['db'] * 1;
            $adat[$ev]['teljratadb'] = per($adat[$ev]['szamladb'], $adat[$ev]['megrendelesdb']) * 100;
            $adat[$ev]['szamladbpernap'] = per($sor['db'], $nap);
            $adat[$ev]['szamlanetto'] = $sor['netto'] * 1;
            $adat[$ev]['teljratanetto'] = per($adat[$ev]['szamlanetto'], $adat[$ev]['megrendelesnetto']) * 100;
            $adat[$ev]['szamlanettopernap'] = per($sor['netto'], $nap);
            $adat[$ev]['szamlanettoperdb'] = per($sor['netto'], $sor['db']);
            $adat[$ev]['teljratanettoperdb'] = per($adat[$ev]['szamlanettoperdb'], $adat[$ev]['megrendelesnettoperdb']) * 100;
            if (array_key_exists($ev - 1, $adat)) {
                $adat[$ev]['szamladbvalt'] = per($adat[$ev]['szamladb'], $adat[$ev - 1]['szamladb']) * 100;
                $adat[$ev]['szamlanettovalt'] = per($adat[$ev]['szamlanetto'], $adat[$ev - 1]['szamlanetto']) * 100;
                $adat[$ev]['szamlanettoperdbvalt'] = per($adat[$ev]['szamlanettoperdb'], $adat[$ev - 1]['szamlanettoperdb']) * 100;
            }
            else {
                $adat[$ev]['szamladbvalt'] = 0;
                $adat[$ev]['szamlanettovalt'] = 0;
                $adat[$ev]['szamlanettoperdbvalt'] = 0;
            }
        }
        $bestmegrendelesdb = 0;
        $bestmegrendelesnetto = 0;
        $bestmegrendelesnettoperdb = 0;
        $bestszamladb = 0;
        $bestszamlanetto = 0;
        $bestszamlanettoperdb = 0;
        $keys = array_keys($adat);
        $len = count($keys) - 1;
        for ($i = 0; $i < $len; $i++) {
            if ($bestmegrendelesdb < $adat[$keys[$i]]['megrendelesdb']) {
                $bestmegrendelesdb = $adat[$keys[$i]]['megrendelesdb'];
            }
            if ($bestmegrendelesnetto < $adat[$keys[$i]]['megrendelesnetto']) {
                $bestmegrendelesnetto = $adat[$keys[$i]]['megrendelesnetto'];
            }
            if ($bestszamladb < $adat[$keys[$i]]['szamladb']) {
                $bestszamladb = $adat[$keys[$i]]['szamladb'];
            }
            if ($bestszamlanetto < $adat[$keys[$i]]['szamlanetto']) {
                $bestszamlanetto = $adat[$keys[$i]]['szamlanetto'];
            }
            if ($bestmegrendelesnettoperdb < $adat[$keys[$i]]['megrendelesnettoperdb']) {
                $bestmegrendelesnettoperdb = $adat[$keys[$i]]['megrendelesnettoperdb'];
            }
            if ($bestszamlanettoperdb < $adat[$keys[$i]]['szamlanettoperdb']) {
                $bestszamlanettoperdb = $adat[$keys[$i]]['szamlanettoperdb'];
            }
        }
        $adat['LB']['ev'] = 'Last/Best';
        $adat['LB']['megrendelesdbvalt'] = per($adat[$keys[$len]]['megrendelesdb'], $bestmegrendelesdb) * 100;
        $adat['LB']['megrendelesnettovalt'] = per($adat[$keys[$len]]['megrendelesnetto'], $bestmegrendelesnetto) * 100;
        $adat['LB']['megrendelesnettoperdbvalt'] = per($adat[$keys[$len]]['megrendelesnettoperdb'], $bestmegrendelesnettoperdb) * 100;
        $adat['LB']['szamladbvalt'] = per($adat[$keys[$len]]['szamladb'], $bestszamladb) * 100;
        $adat['LB']['szamlanettovalt'] = per($adat[$keys[$len]]['szamlanetto'], $bestszamlanetto) * 100;
        $adat['LB']['szamlanettoperdbvalt'] = per($adat[$keys[$len]]['szamlanettoperdb'], $bestszamlanettoperdb) * 100;
        return $adat;
    }

    public function refresh() {
        $view = $this->createView('teljesitmenyjelentesbody.tpl');
        $view->setVar('tjlista', $this->getData($this->params->getStringRequestParam('tol'), $this->params->getStringRequestParam('ig')));
        $view->printTemplateResult();
    }

}