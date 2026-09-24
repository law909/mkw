<?php

namespace Traits;

use Entities\Kimutatasnezet;

/**
 * Named, shared settings of a grouped report, kept per report under static::KIMUTATAS.
 * The user provides getNezetBeallitas(): what of the current request a view stores.
 */
trait SavedReportViews
{

    abstract protected function getNezetBeallitas(): array;

    private function echoNezetek(?int $selected = null): void
    {
        $nezetek = [];
        foreach ($this->getRepo(Kimutatasnezet::class)->getByKimutatas(static::KIMUTATAS) as $nezet) {
            $nezetek[] = [
                'id' => $nezet->getId(),
                'nev' => $nezet->getNev(),
                'beallitas' => $nezet->getBeallitas(),
                'createdby' => $nezet->getCreatedbyNev(),
            ];
        }
        header('Content-Type: application/json');
        echo json_encode(['nezetek' => $nezetek, 'selected' => $selected]);
    }

    public function nezetlista()
    {
        $this->echoNezetek();
    }

    /** Saves the current settings under a name; an existing name of the report is overwritten. */
    public function nezetsave()
    {
        $nev = trim($this->params->getStringRequestParam('nev'));
        if ($nev === '' || mb_strlen($nev) > 100) {
            $this->jsonError(t('A nézet neve 1–100 karakter lehet.'), 400);
            return;
        }
        $repo = $this->getRepo(Kimutatasnezet::class);
        $nezet = $repo->findOneBy(['kimutatas' => static::KIMUTATAS, 'nev' => $nev]) ?? new Kimutatasnezet();
        $nezet->setKimutatas(static::KIMUTATAS);
        $nezet->setNev($nev);
        $nezet->setBeallitas($this->getNezetBeallitas());
        $this->getEm()->persist($nezet);
        $this->getEm()->flush();
        $this->echoNezetek($nezet->getId());
    }

    public function nezetdelete()
    {
        $nezet = $this->getRepo(Kimutatasnezet::class)->findOneBy(['id' => $this->params->getIntRequestParam('id'), 'kimutatas' => static::KIMUTATAS]);
        if ($nezet) {
            $this->getEm()->remove($nezet);
            $this->getEm()->flush();
        }
        $this->echoNezetek();
    }
}
