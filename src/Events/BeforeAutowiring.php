<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Events;

use Illuminate\Foundation\Events\Dispatchable;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class BeforeAutowiring
{
    use Dispatchable;

    public function __construct(
        public readonly AutowiresConfigs $subject,
    )
    {
    }
}
