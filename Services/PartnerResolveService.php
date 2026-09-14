<?php

namespace Services;

use Entities\JogaBejelentkezes;
use Entities\Partner;

/**
 * A jelentkezések partnere emailcím alapján. A meglévő partner adatait nem írja át, csak az üres mezőit tölti ki:
 * a jelentkező bármilyen emailcímet beírhat.
 */
class PartnerResolveService
{
    private bool $created = false;
    private array $filledFields = [];

    public function findByEmail(?string $email): ?Partner
    {
        $email = trim((string)$email);
        // üres emailre az üres emailű partnerek bármelyikét eltalálná
        return $email === '' ? null : \mkw\store::getEm()->getRepository(Partner::class)->findOneBy(['email' => $email]);
    }

    /** persist, flush nélkül: a hívó a jelentkezéssel együtt menti */
    public function resolve($email, $nev, $irszam, $varos, $utca, $telefon = ''): Partner
    {
        $partner = $this->findByEmail($email);
        $this->created = !$partner;
        if ($this->created) {
            $partner = new Partner();
            $partner->setEmail(trim((string)$email));
            $nevreszek = JogaBejelentkezes::splitNev($nev);
            $partner->setNev(implode(' ', $nevreszek));
            $partner->setVezeteknev($nevreszek[0] ?? '');
            $partner->setKeresztnev(implode(' ', array_slice($nevreszek, 1)));
            $partner->setSzamlatipus(0);
            $partner->setVatstatus(2);
        }
        $filled = $partner->fillMissingCim($irszam, $varos, $utca);
        $telefon = trim((string)$telefon);
        if ($telefon !== '' && trim((string)$partner->getTelefon()) === '') {
            $partner->setTelefon($telefon);
            $filled[] = 'telefon';
        }
        $this->filledFields = $this->created ? [] : $filled;
        \mkw\store::getEm()->persist($partner);
        return $partner;
    }

    public function isCreated(): bool
    {
        return $this->created;
    }

    /** @return string[] a meglévő partneren pótolt mezők (irszam, varos, utca, telefon) */
    public function getFilledFields(): array
    {
        return $this->filledFields;
    }
}
