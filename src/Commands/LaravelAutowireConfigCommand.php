<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Commands;

use Illuminate\Console\Command;

class LaravelAutowireConfigCommand extends Command
{
    public $signature = 'laravel-autowire-config';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
