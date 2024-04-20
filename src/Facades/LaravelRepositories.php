<?php

namespace NathanBarrett\LaravelRepositories\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NathanBarrett\LaravelRepositories\LaravelRepositories
 */
class LaravelRepositories extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NathanBarrett\LaravelRepositories\LaravelRepositories::class;
    }
}
