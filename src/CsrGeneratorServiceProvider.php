<?php

namespace Vdhicts\CsrGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CsrGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-csr-generator')
            ->hasViews('csr-generator')
            ->hasConfigFile('csr-generator');
    }
}
