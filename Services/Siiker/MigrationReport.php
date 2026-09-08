<?php

namespace Services\Siiker;

use mkw\store;

/**
 * A migráció számlálói és naplója: entitásonként létrehozva/frissítve/kihagyva/hiba, a
 * megjegyzések és hibák a storage/logs alá kerülnek, az összesítő a képernyőre.
 */
class MigrationReport
{
    const MAXMESSAGES = 200;

    private array $counters = [];
    private array $messages = [];
    private int $dropped = 0;
    private string $logfile;
    private bool $quiet;
    private float $started;

    public function __construct(bool $quiet = false)
    {
        $this->quiet = $quiet;
        $this->started = microtime(true);
        $this->logfile = 'siikermigrate_' . date('Ymd_His') . '.txt';
        $this->writeLine('SIIKer migráció indul');
    }

    public function getLogfile(): string
    {
        return $this->logfile;
    }

    public function created(string $entity): void
    {
        $this->bump($entity, 'created');
    }

    public function updated(string $entity): void
    {
        $this->bump($entity, 'updated');
    }

    public function skipped(string $entity, ?string $message = null): void
    {
        $this->bump($entity, 'skipped');
        if ($message !== null) {
            $this->message($entity . ': ' . $message);
        }
    }

    public function error(string $entity, string $message): void
    {
        $this->bump($entity, 'error');
        $this->message('HIBA ' . $entity . ': ' . $message);
    }

    /** Megjegyzés a naplóba és a képernyőre. */
    public function note(string $message): void
    {
        $this->message($message);
        $this->say($message);
    }

    public function section(string $title): void
    {
        $this->writeLine('== ' . $title);
        $this->say(PHP_EOL . '== ' . $title);
    }

    public function counters(): array
    {
        return $this->counters;
    }

    public function count(string $entity, string $kind): int
    {
        return $this->counters[$entity][$kind] ?? 0;
    }

    public function hasErrors(): bool
    {
        foreach ($this->counters as $c) {
            if ($c['error']) {
                return true;
            }
        }
        return false;
    }

    public function summary(): string
    {
        $lines = [];
        $lines[] = sprintf('%-14s %9s %9s %9s %6s', 'entitás', 'új', 'frissítve', 'kihagyva', 'hiba');
        foreach ($this->counters as $entity => $c) {
            $lines[] = sprintf('%-14s %9d %9d %9d %6d', $entity, $c['created'], $c['updated'], $c['skipped'], $c['error']);
        }
        $lines[] = sprintf('Futásidő: %.1f s, csúcs memória: %d MB', microtime(true) - $this->started, memory_get_peak_usage(true) / 1048576);
        $lines[] = 'Napló: ' . store::logsUrl($this->logfile);
        return implode(PHP_EOL, $lines);
    }

    public function finish(): void
    {
        $summary = $this->summary();
        $this->writeLine($summary);
        if ($this->dropped) {
            $this->writeLine('… és további ' . $this->dropped . ' üzenet nem került a naplóba');
        }
        $this->say(PHP_EOL . $summary);
    }

    private function bump(string $entity, string $kind): void
    {
        if (!isset($this->counters[$entity])) {
            $this->counters[$entity] = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'error' => 0];
        }
        $this->counters[$entity][$kind]++;
    }

    private function message(string $message): void
    {
        if (count($this->messages) >= self::MAXMESSAGES) {
            $this->dropped++;
            return;
        }
        $this->messages[] = $message;
        $this->writeLine($message);
    }

    private function say(string $text): void
    {
        if (!$this->quiet) {
            echo $text, PHP_EOL;
        }
    }

    private function writeLine(string $text): void
    {
        $path = store::logsPath($this->logfile);
        if (!is_dir(dirname($path))) {
            @mkdir(dirname($path), 0775, true);
        }
        @file_put_contents($path, date('Y-m-d H:i:s') . ' ' . $text . PHP_EOL, FILE_APPEND);
    }
}
