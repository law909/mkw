<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\BizonylatfejRepository;

/**
 * A bizonylat nyomtatás egy helyen: sablonfeloldás, renderelés, PDF motor és a nyomtatás-útvonal
 * kimenete. Minden hívó (nyomtatás, PDF letöltés, számlalevél, könyvelői ZIP, Fedex számlakép)
 * ezen megy át, így mindegyik bájtazonos fájlt kap.
 *
 * Két üzemmód, a setup.ini `pagedpdf` kapcsolója szerint (lásd {@see \mkw\store::isPagedPdf()}):
 *  - lapozott: a biz_paged_* sablon mPDF-fel A4 PDF-be renderel,
 *  - régi: a bizonylattípus sablonja HTML-t ad, a PDF a `pdfmode` szerinti motorral készül.
 *
 * A váltás bizonylattípusonként külön is fokozatos: amelyik sablonhoz nincs biz_paged_* párja,
 * az a kapcsoló bekapcsolt állapotában is a régi úton megy.
 */
class BizonylatPrintService
{
    private const PAGED_PREFIX = 'biz_paged_';
    private const TEMPLATE_PREFIX = 'biz_';

    /**
     * A bizonylathoz tartozó sablon neve: egyedi reportfile, különben a bizonylattípus
     * bizonylatnyelvre lokalizált tplname-je, végül a lokalizálatlan tplname.
     */
    public function resolveTemplate(Bizonylatfej $o)
    {
        if ($o->getReportfile()) {
            return $o->getReportfile();
        }
        $biztipus = $o->getBizonylattipus();
        if (!$biztipus) {
            return '';
        }
        return $biztipus->getLocalizedFieldValue('tplname', $o->getBizonylatnyelv())
            ?: $biztipus->getTplname();
    }

    /**
     * A lapozott sablonváltozat neve, ha a kapcsoló be van kapcsolva és a fájl létezik a téma-
     * vagy az alapértelmezett könyvtárban; különben null, és a hívó marad a régi HTML-nél.
     *
     * A "paged_" a biz_ előtag mögé kerül, nem a végére: a
     * {@see BizonylatfejRepository::getReportfileSelectList()} biz_<biztipus> előtagra illeszt,
     * így a lapozott sablonok nem szivárognak be a bizonylat reportfile legördülőjébe.
     */
    public function findPagedTemplate($tplname)
    {
        if (!$tplname || !\mkw\store::isPagedPdf()) {
            return null;
        }
        if (strpos($tplname, self::PAGED_PREFIX) === 0) {
            $paged = $tplname;
        } elseif (strpos($tplname, self::TEMPLATE_PREFIX) === 0) {
            $paged = self::PAGED_PREFIX . substr($tplname, strlen(self::TEMPLATE_PREFIX));
        } else {
            return null;
        }
        $tf = \mkw\store::getTemplateFactory();
        if (file_exists($tf->getTemplate() . $paged) || file_exists($tf->getTemplateDefault() . $paged)) {
            return $paged;
        }
        return null;
    }

    /**
     * Kirenderelt sablon tetszőleges változókkal. A lapozott sablon két könyvtárból oldódik fel
     * (téma, majd alapértelmezett), hogy az {extends}/{include} is átlépje a témahatárt.
     *
     * @return array{html: string, paged: bool}
     */
    public function renderTemplate($tplname, array $vars)
    {
        $paged = $this->findPagedTemplate($tplname);
        $view = $paged
            ? \mkw\store::getTemplateFactory()->createFallbackView($paged)
            : \mkw\store::getTemplateFactory()->createView($tplname);
        // ez adja a sablonoknak a $teszt, $setup, $mainurl változókat
        \mkw\store::getGdl()->loadData($view);
        foreach ($vars as $key => $value) {
            $view->setVar($key, $value);
        }
        return ['html' => $view->getTemplateResult(), 'paged' => (bool)$paged];
    }

    /**
     * Egy bizonylat kirenderelve. A $tplname megadásával a bizonylattípus sablonja megkerülhető
     * (az előlegbekérő él ezzel).
     *
     * @return array{html: string, paged: bool}|null null, ha nincs ilyen bizonylat
     */
    public function render($id, $tplname = null)
    {
        $o = $this->getRepo()->findForPrint($id);
        if (!$o) {
            return null;
        }
        return $this->renderBizonylat($o, $tplname);
    }

    /**
     * @return array{html: string, paged: bool}
     */
    public function renderBizonylat(Bizonylatfej $o, $tplname = null)
    {
        $tplname = $tplname ?: $this->resolveTemplate($o);
        $paged = $this->findPagedTemplate($tplname);
        $view = $paged
            ? \mkw\store::getTemplateFactory()->createFallbackView($paged)
            : \mkw\store::getTemplateFactory()->createView($tplname);
        \mkw\store::getGdl()->loadData($view);
        $o->getBizonylattipus()?->setTemplateVars($view);
        // a képes sablonok ezzel hivatkoznak a fájlrendszerre: az mPDF a $mainurl-lel HTTP-n
        // toltene le a sajat szerveretol minden tetelkepet
        $view->setVar('webroot', getcwd());
        $view->setVar('egyed', $o->toLista());
        $view->setVar('afaosszesito', $this->getRepo()->getAFAOsszesito($o));
        return ['html' => $view->getTemplateResult(), 'paged' => (bool)$paged];
    }

    /**
     * A bizonylat PDF motorja: lapozott sablon esetén mindig mPDF (a sablon mPDF-specifikus
     * tageket tartalmaz), különben a `pdfmode` szerinti motor.
     *
     * @return \mkw\mkwmpdf|\mkw\mkwdompdf|\mkw\mkwwkhtmltopdf|null
     */
    public function createEngine($id, $tplname = null)
    {
        $r = $this->render($id, $tplname);
        if (!$r || !$r['html']) {
            return null;
        }
        return $this->createEngineFor($r);
    }

    /**
     * @param array{html: string, paged: bool} $r
     * @return \mkw\mkwmpdf|\mkw\mkwdompdf|\mkw\mkwwkhtmltopdf
     */
    public function createEngineFor(array $r)
    {
        return $r['paged'] ? new \mkw\mkwmpdf($r['html']) : \mkw\store::getPDFEngine($r['html']);
    }

    /**
     * A nyomtatás gomb kimenete: lapozott módban a böngésző PDF nézőjébe való inline PDF,
     * különben a régi HTML. Semmit nem szabad kiírni előtte, mert a PDF fejlécet küld.
     */
    public function output($id, $tplname = null)
    {
        $r = $this->render($id, $tplname);
        if (!$r) {
            return;
        }
        $this->outputResult($r, $id);
    }

    /**
     * @param array{html: string, paged: bool} $r
     */
    public function outputResult(array $r, $id)
    {
        if ($r['paged']) {
            (new \mkw\mkwmpdf($r['html']))->inline(\mkw\store::urlize($id) . '.pdf');
            return;
        }
        echo $r['html'];
    }

    /**
     * @return BizonylatfejRepository
     */
    private function getRepo()
    {
        return \mkw\store::getEm()->getRepository(Bizonylatfej::class);
    }
}
