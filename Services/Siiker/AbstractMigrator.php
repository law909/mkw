<?php

namespace Services\Siiker;

use Doctrine\ORM\EntityManager;
use mkw\store;

/**
 * A migrációs lépések közös alapja: forrás, riport, kötegelt flush és a kód→id térképek.
 *
 * A kötegelt clear() minden entitást leválaszt, ezért a lépések nem entitásokat, hanem
 * id-térképeket tartanak a memóriában, és a kapcsolatokat getReference()-szel kötik.
 */
abstract class AbstractMigrator
{
    const FLUSHBATCH = 100;

    protected EntityManager $em;
    protected SiikerSource $src;
    protected MigrationReport $report;
    protected array $options;
    protected int $limit;
    private int $pending = 0;

    public function __construct(SiikerSource $src, MigrationReport $report, array $options = [])
    {
        $this->em = store::getEm();
        $this->src = $src;
        $this->report = $report;
        $this->options = $options;
        $this->limit = max(0, (int)($options['limit'] ?? 0));
    }

    abstract public function run(): void;

    /** `mező érték => id` térkép egy entitás nem üres mezőjére. */
    protected function loadIdMap(string $entityClass, string $field): array
    {
        $rows = $this->em->createQueryBuilder()
            ->select('e.id AS id, e.' . $field . ' AS val')
            ->from($entityClass, 'e')
            ->where('e.' . $field . ' IS NOT NULL')
            ->getQuery()->getScalarResult();
        $map = [];
        foreach ($rows as $r) {
            $key = (string)$r['val'];
            if ($key !== '' && !isset($map[$key])) {
                $map[$key] = (int)$r['id'];
            }
        }
        return $map;
    }

    protected function ref(string $class, $id)
    {
        return $id ? $this->em->getReference($class, (int)$id) : null;
    }

    /** Meglévő entitás a térképből, vagy új példány; a második érték jelzi, hogy új-e. */
    protected function findOrNew(string $class, array $map, $key): array
    {
        $id = $map[(string)$key] ?? null;
        $entity = $id ? $this->em->find($class, $id) : null;
        if ($entity) {
            return [$entity, false];
        }
        return [new $class(), true];
    }

    /** Persist + számláló; a köteg végén flush és clear. Igaz, ha a köteg most ürült. */
    protected function save(object $entity, bool $isNew, string $counter): bool
    {
        $this->em->persist($entity);
        $isNew ? $this->report->created($counter) : $this->report->updated($counter);
        return $this->tick();
    }

    /** Egy kötegelem kész; a köteg végén flush és clear. Igaz, ha a köteg most ürült. */
    protected function tick(): bool
    {
        if (++$this->pending >= static::FLUSHBATCH) {
            $this->flushClear();
            return true;
        }
        return false;
    }

    protected function flushClear(): void
    {
        $this->em->flush();
        $this->em->clear();
        $this->pending = 0;
    }

    protected function flush(): void
    {
        $this->em->flush();
        $this->pending = 0;
    }

    protected function limitSql(): string
    {
        return $this->limit ? ' LIMIT ' . $this->limit : '';
    }

    protected function str($value, ?int $max = null): string
    {
        $value = trim((string)$value);
        if ($max !== null && mb_strlen($value) > $max) {
            $value = mb_substr($value, 0, $max);
        }
        return $value;
    }

    protected function strOrNull($value, ?int $max = null): ?string
    {
        $s = $this->str($value, $max);
        return $s === '' ? null : $s;
    }

    /** A SIIKer HTML-leírása `<body>`-ba csomagolt; a sima szöveg sortörésekkel jön át. */
    protected function html($html, $plain = ''): string
    {
        $html = trim((string)$html);
        if ($html !== '') {
            $html = preg_replace('~^<body[^>]*>|</body>$~i', '', $html);
            return trim($html);
        }
        $plain = trim((string)$plain);
        if ($plain === '') {
            return '';
        }
        return nl2br(htmlspecialchars($plain, ENT_QUOTES, 'UTF-8'));
    }

    protected function bool($value): bool
    {
        return (int)$value === 1;
    }
}
