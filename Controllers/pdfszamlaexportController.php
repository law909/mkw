<?php

namespace Controllers;

use mikehaertl\wkhtmlto\Pdf;

class pdfszamlaexportController extends \mkwhelpers\MattableController {

    private $files = array();
    private $mar;
    private $esetimar;

    public function view() {
        $view = $this->createView('pdfszamlaexport.tpl');

        $view->setVar('utolsoszamla', \mkw\store::getParameter(\mkw\consts::PDFUtolsoSzamlaszam));
        $view->setVar('utolsoesetiszamla', \mkw\store::getParameter(\mkw\consts::PDFUtolsoEsetiSzamlaszam));

        $view->printTemplateResult();
    }

    private function getPDFs($biztipus, $utolsoszamla) {
        $bizrepo = $this->getEm()->getRepository('Entities\Bizonylatfej');
        $bizctrl = bizonylatfejController::factory($biztipus, $this->params);
        $bt = \mkw\store::getEm()->getRepository('Entities\Bizonylattipus')->find($biztipus);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', $bt);
        $mar = $utolsoszamla;
        if ($mar) {
            $filter->addFilter('id', '>', $mar);
        }
        $r = $bizrepo->getAll($filter, array('id' => 'ASC'));
        /** @var \Entities\Bizonylatfej $bizonylat */
        foreach ($r as $bizonylat) {
            $mar = $bizonylat->getId();
            $filenev = \mkw\store::storagePath(\mkw\store::urlize($mar) . '.pdf');
            $html = $bizctrl->getBizonylatHTML($mar);
            $pdf = new Pdf($html);
            $pdf->setOptions(array('encoding' => 'UTF-8'));
            $pdf->saveAs($filenev);
            $this->files[] = $filenev;
        }
        return $mar;
    }

    protected function createZip() {
        $this->mar = $this->getPDFs('szamla', $this->params->getStringRequestParam('utolsoszamla'));
        $this->esetimar = $this->getPDFs('esetiszamla', $this->params->getStringRequestParam('utolsoesetiszamla'));

        if ($this->files) {

            $zipname = \mkw\store::storagePath('konyvelonek.zip');
            $zip = new \ZipArchive();
            $zip->open($zipname, \ZipArchive::CREATE);
            foreach ($this->files as $fname) {
                $zip->addFile($fname);
            }
            $r = $zip->close();
            foreach ($this->files as $fname) {
                @unlink($fname);
            }
            if ($r) {
                return $zipname;
            }
        }
        return false;
    }

    public function sendEmail() {

        $res = array('msg' => at('A feldolgozás során hiba lépett fel!'));

        $zipname = $this->createZip();
        if ($zipname) {

            $email = \mkw\store::getParameter(\mkw\consts::KonyveloEmail);
            if ($email) {
                $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::KonyvelolevelSablon));
                if ($emailtpl) {

                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));

                    $mailer = \mkw\store::getMailer();

                    $mailer->setAttachment($zipname);
                    $mailer->addTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());

                    $mailer->send();


                    \mkw\store::setParameter(\mkw\consts::PDFUtolsoSzamlaszam, $this->mar);
                    \mkw\store::setParameter(\mkw\consts::PDFUtolsoEsetiSzamlaszam, $this->esetimar);
                    $res['msg'] = at('Az email sikeresen elküldve.');
                }
                else {
                    $res['msg'] = at('Nincs megadva könyvelő levél sablon!');
                }
            }
            else {
                $res['msg'] = at('Nincs megadva könyvelő email!');
            }
            @unlink($zipname);
        }
        echo json_encode($res);
    }

    public function download() {

        $zipname = $this->createZip();
        if ($zipname) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$zipname."\"");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($zipname));
            ob_end_flush();
            @readfile($zipname);
            @unlink($zipname);
        }
    }
}