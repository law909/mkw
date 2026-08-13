<?php

namespace Controllers;

use Traits\MediatarGuard;

/**
 * A dokumentum fülek közös „Azonnali feltöltés” végpontja (termék, partner).
 *
 * Egy végpont elég mindegyik törzshöz: a válaszként visszaadott
 * `dokumentumtarkarb.tpl` sor mezőnevei nem függenek attól, melyik entitáshoz
 * tartozik a dokumentum – az `osztaly` diszkriminátort a karb mentése tölti ki.
 *
 * A sor `oper = add`-del jön vissza, tehát a **rekord csak a karbantartó
 * mentésekor** születik meg. Ez szándékos: új terméknél/partnernél még nincs mihez
 * kötni, a fájl viszont már a mappában van.
 *
 * A `mediatar` kapcsolótól függetlenül él: a `path.dokumentum` a CKFinder-es
 * telepítéseken is a `path.ckfinder` gyökér alatt értendő.
 */
class dokumentumtarController extends \mkwhelpers\Controller
{

    use MediatarGuard;

    public function quickUpload()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $this->checkPostMaxSize();
            $file = $_FILES['file'] ?? null;
            if (!$file) {
                throw new \RuntimeException(t('Nem érkezett fájl'));
            }
            $res = \Services\DokumentumUploadService::upload($file);

            $view = $this->createView('dokumentumtarkarb.tpl');
            $view->setVar('dok', [
                'oper' => 'add',
                'id' => \mkw\store::createUID(),
                'url' => '',
                'path' => $res['url'],
                'leiras' => $res['name'],
            ]);
            $this->json(['ok' => true, 'html' => $view->getTemplateResult(), 'url' => $res['url']]);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

}
