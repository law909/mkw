<?php

namespace Entities;

class MeretRepository extends \mkwhelpers\Repository
{
    // XS, 2XS, XXL, 3XL, M ...; the digit form means as many X-es
    private const LETTER = '(?:(\d)X|(X*))([SML])';

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Meret::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend és név szerint', 'order' => ['sorrend' => 'ASC', 'nev' => 'ASC']],
            '2' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
        $this->setBatches(['sorrendgen' => 'Sorrend újraképzése név szerint']);
    }

    /**
     * Orders by size (XS < S < M < L < XL < 2XL, 36 < 38, 30/30 < 30/34 ...) instead of by name.
     */
    public function regenerateSorrendByMeret(int $step = 20): int
    {
        $meretek = [];
        foreach ($this->findAll() as $meret) {
            $meretek[] = [
                'meret' => $meret,
                'key' => $this->getSortKey((string)$meret->getNev()),
                'nev' => mb_strtoupper((string)$meret->getNev()),
            ];
        }
        usort($meretek, fn($a, $b) => ($a['key'] <=> $b['key']) ?: strnatcmp($a['nev'], $b['nev']));
        $sorrend = 0;
        foreach ($meretek as $sor) {
            $sorrend += $step;
            $sor['meret']->setSorrend($sorrend);
        }
        $this->_em->flush();
        return count($meretek);
    }

    /**
     * Always five elements: PHP compares arrays of different length by their count.
     */
    private function getSortKey(string $nev, bool $withPrefix = true): array
    {
        $nev = preg_replace('/\s+/', ' ', mb_strtoupper(trim($nev)));
        $num = fn($s) => (float)str_replace(',', '.', $s);

        if (preg_match('/^(\d+(?:[.,]\d+)?) ?L$/', $nev, $m)) {
            return [40, $num($m[1]), 0, 0, 0];
        }
        if (preg_match('/^(\d+(?:[.,]\d+)?) ?M$/', $nev, $m)) {
            return [50, $num($m[1]), 0, 0, 0];
        }
        if (preg_match('/^(\d+)(?:\/(\d+))?$/', $nev, $m)) {
            return [30, (int)$m[1], (int)($m[2] ?? 0), 0, 0];
        }
        if (preg_match('/^Y' . self::LETTER . '$/', $nev, $m)) {
            return [10, $this->getLetterRank($m[1], $m[2], $m[3]), 0, 0, 0];
        }
        // "R 2XL", "S 30/30": product line prefix; "S 37-43" is size S with a sock size range
        if ($withPrefix && preg_match('/^([RS]) (.+)$/', $nev, $m) && !preg_match('/^\d+-\d+$/', $m[2])) {
            $sub = $this->getSortKey($m[2], false);
            return [$m[1] === 'R' ? 60 : 70, $sub[0], $sub[1], $sub[2], $sub[3]];
        }
        // S, S-M, LXL, 2XL/3XL, L/50, M1, XL 48-50
        if (preg_match('/^' . self::LETTER . '(?:[-\/ ]?' . self::LETTER . ')?(?:[-\/ ]?(\d+).*)?$/', $nev, $m)) {
            $rank = $this->getLetterRank($m[1], $m[2], $m[3]);
            $rank2 = isset($m[6]) && $m[6] !== '' ? $this->getLetterRank($m[4], $m[5], $m[6]) : $rank;
            return [20, $rank, $rank2, (int)($m[7] ?? 0), 0];
        }
        return [90, 0, 0, 0, 0];
    }

    private function getLetterRank(string $digit, string $xes, string $letter): int
    {
        $extra = $digit !== '' ? (int)$digit : strlen($xes);
        return match ($letter) {
            'S' => -1 - $extra,
            'M' => 0,
            'L' => 1 + $extra,
        };
    }
}
