<?php

namespace Vdhicts\CsrGenerator\Tests;

use Vdhicts\CsrGenerator\CsrGeneratorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /** @var array<string, string> */
    protected array $additionalOptions = [];

    protected function getPackageProviders($app): array
    {
        return [
            CsrGeneratorServiceProvider::class,
        ];
    }
}
