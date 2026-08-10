<?php

namespace Services\Cron;

use Services\Cron\CronTask;

class TestTask implements CronTask
{

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Test task';
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function run(array $options = []): string
    {
        return 'Test task done';
    }
}