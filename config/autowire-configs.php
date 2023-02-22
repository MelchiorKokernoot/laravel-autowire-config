<?php

declare(strict_types=1);

use MelchiorKokernoot\LaravelAutowireConfig\Strategies\PropNameStrategy;

return [
    //Either AttributeStrategy::class or PropNameStrategy::class
    'strategy' => PropNameStrategy::class,
];
