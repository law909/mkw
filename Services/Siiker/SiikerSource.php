<?php

namespace Services\Siiker;

use Doctrine\DBAL\Connection;
use mkw\store;

/**
 * A SIIKer adatbázis olvasója. A két séma egy MySQL szerveren van, ezért az MKW saját
 * kapcsolatán, sémanévvel kvalifikált táblákból olvasunk – nincs második kapcsolat.
 *
 * A forrás oszlopai latin1-nek vannak deklarálva, de a bájtok CP1250 kódolásúak (ő = F5, ű = FB),
 * ezért minden szöveget a {@see text()} kifejezéssel kell lekérni, különben õ/û jön.
 */
class SiikerSource
{
    const CONFIGKEY = 'siiker.dbname';

    private Connection $conn;
    private string $schema;

    public function __construct(Connection $conn, string $schema)
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $schema)) {
            throw new \RuntimeException('Érvénytelen forrás sémanév: ' . $schema);
        }
        $this->conn = $conn;
        $this->schema = $schema;
    }

    public static function fromConfig(): self
    {
        $schema = trim((string)store::getConfigValue(self::CONFIGKEY, ''));
        if ($schema === '') {
            throw new \RuntimeException('Hiányzik a config.ini ' . self::CONFIGKEY . ' kulcsa');
        }
        return new self(store::getEm()->getConnection(), $schema);
    }

    public function getSchema(): string
    {
        return $this->schema;
    }

    public function table(string $name): string
    {
        return '`' . $this->schema . '`.`' . $name . '`';
    }

    /** CP1250-ből átkódolt szövegoszlop kifejezése a SELECT listába. */
    public function text(string $col, ?string $alias = null): string
    {
        return 'CONVERT(CAST(' . $col . ' AS BINARY) USING cp1250) AS `' . ($alias ?: $col) . '`';
    }

    /**
     * SELECT-lista: a sima oszlopok változatlanul, a szövegesek átkódolva.
     *
     * @param string[] $plain
     * @param string[] $text
     */
    public function columns(array $plain, array $text, string $prefix = ''): string
    {
        $cols = [];
        foreach ($plain as $c) {
            $cols[] = $prefix . $c;
        }
        foreach ($text as $c) {
            $cols[] = $this->text($prefix . $c, $c);
        }
        return implode(', ', $cols);
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->conn->fetchAllAssociative($sql, $params);
    }

    public function fetchOne(string $sql, array $params = [])
    {
        return $this->conn->fetchOne($sql, $params);
    }

    /**
     * Egy dokumentumtár-blob kitömörítve. A SIIKer zlib-bel tömörítve tárolja a képeket;
     * a tömörítetlen (nyers JPEG) blobot változatlanul adja vissza.
     */
    public function fetchBlob(int $kod): ?string
    {
        $raw = $this->conn->fetchOne('SELECT binarisadat FROM ' . $this->table('dokumentumtar') . ' WHERE kod = ?', [$kod]);
        if ($raw === false || $raw === null || $raw === '') {
            return null;
        }
        if (is_resource($raw)) {
            $raw = stream_get_contents($raw);
        }
        if (strncmp($raw, "\xFF\xD8", 2) === 0) {
            return $raw;
        }
        $data = @gzuncompress($raw);
        return $data === false ? null : $data;
    }
}
