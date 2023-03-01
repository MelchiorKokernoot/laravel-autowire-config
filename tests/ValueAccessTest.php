<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\DummyClass;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\NullableDummyClass;

use function app;
use function config;

class ValueAccessTest extends TestCase
{
    public function testItCanAccessTheConfigValueThroughTheVShorthand(): void
    {
        config()->set('foo.bar', 'fooBar');

        $dummy = app(DummyClass::class);

        $this->assertSame('fooBar', $dummy->fooBar->v);
        $this->assertIsString($dummy->fooBar->v);
    }

    public function testItReturnsNullWhenAccessingANonExistentPropertyOnTheConfigValueWrapper(): void
    {
        config()->set('foo.bar', 'fooBar');
        $dummy = app(NullableDummyClass::class);
        $this->assertNull($dummy->fooString->nonExistentProperty);
    }
}
