<?php

namespace NathanBarrett\LaravelRepositories;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use NathanBarrett\LaravelRepositories\Commands\MakeRepositoryCommand;

class LaravelRepositoriesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-repositories')
            ->hasCommand(MakeRepositoryCommand::class);
    }
}
