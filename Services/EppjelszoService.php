<?php

namespace Services;

use Doctrine\ORM\EntityManagerInterface;
use Entities\Dolgozo;
use Entities\Eppjelszo;
use Entities\Idopontfoglalas;

/**
 * WP oldal jelszó kiadása egy jelentkezőnek. Egy jelentkezőnek (email) egy oldalhoz egy érvényes
 * jelszava van: az új kiadása a régit visszavonja.
 */
class EppjelszoService
{

    public const MAXHONAP = 120;

    private $em;

    public function __construct(?EntityManagerInterface $em = null)
    {
        $this->em = $em ?? \mkw\store::getEm();
    }

    /**
     * Mától $honap hónap, a nap végéig. A hónap végén túlcsúszó nap a célhónap utolsó napja lesz
     * (jan. 31 + 1 hónap = febr. 28), nem március eleje.
     */
    public static function calcLejarat(int $honap, ?\DateTimeInterface $most = null): \DateTime
    {
        $most = $most ? \DateTime::createFromInterface($most) : new \DateTime();
        $cel = (clone $most)->modify('first day of this month')->modify('+' . $honap . ' months');
        $nap = min((int)$most->format('j'), (int)$cel->format('t'));
        return $cel->setDate((int)$cel->format('Y'), (int)$cel->format('n'), $nap)->setTime(23, 59, 59);
    }

    /**
     * @return Eppjelszo[] a jelentkező (email) érvényes jelszavai ehhez az oldalhoz
     */
    public function findAktiv(int $oldalid, string $email): array
    {
        return $this->em->createQuery(
            'SELECT j FROM Entities\Eppjelszo j'
            . ' WHERE j.oldalid = :oldalid AND j.email = :email AND j.visszavonvaon IS NULL AND j.lejarat > :most'
            . ' ORDER BY j.lejarat DESC'
        )
            ->setParameters(['oldalid' => $oldalid, 'email' => mb_strtolower(trim($email)), 'most' => new \DateTime()])
            ->getResult();
    }

    /**
     * A jelentkezés utolsó, ebből generált és vissza nem vont jelszava (a lista sorához).
     */
    public function findByIdopontfoglalas(Idopontfoglalas $foglalas): ?Eppjelszo
    {
        return $this->em->createQuery(
            'SELECT j FROM Entities\Eppjelszo j'
            . ' WHERE j.idopontfoglalas = :foglalas AND j.visszavonvaon IS NULL ORDER BY j.id DESC'
        )
            ->setParameter('foglalas', $foglalas)
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * Új jelszó, a jelentkező korábbi érvényes jelszavai ehhez az oldalhoz visszavonva. Flush-ol.
     *
     * @return array{0: Eppjelszo, 1: string} az entitás és a nyers jelszó (csak most érhető el)
     */
    public function issue(
        int $oldalid,
        string $nev,
        string $email,
        int $honap,
        ?Idopontfoglalas $foglalas = null,
        ?Dolgozo $dolgozo = null
    ): array {
        if ($oldalid <= 0 || trim($email) === '' || $honap < 1 || $honap > self::MAXHONAP) {
            throw new \InvalidArgumentException('Hiányzó oldal, email, vagy érvénytelen hónapszám.');
        }
        foreach ($this->findAktiv($oldalid, $email) as $regi) {
            $regi->revoke($dolgozo);
        }
        $eppjelszo = new Eppjelszo();
        $eppjelszo->setOldalid($oldalid);
        $eppjelszo->setNev($nev);
        $eppjelszo->setEmail($email);
        $eppjelszo->setHonap($honap);
        $eppjelszo->setLejarat(self::calcLejarat($honap));
        $eppjelszo->setIdopontfoglalas($foglalas);
        $jelszo = $eppjelszo->generateJelszo();
        $this->em->persist($eppjelszo);
        $this->em->flush();
        return [$eppjelszo, $jelszo];
    }

}
