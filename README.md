# Repository service layer for Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nathanbarrett/laravel-repositories.svg?style=flat-square)](https://packagist.org/packages/nathanbarrett/laravel-repositories)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nathanbarrett/laravel-repositories/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nathanbarrett/laravel-repositories/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nathanbarrett/laravel-repositories/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nathanbarrett/laravel-repositories/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nathanbarrett/laravel-repositories.svg?style=flat-square)](https://packagist.org/packages/nathanbarrett/laravel-repositories)

Repositories are meant to act as a middle layer between Models and higher level objectives like services. 
They are meant to abstract the data layer and provide a clean API for data access.
Use a repository when the action is mostly or entirely about the related model.

#### Layers of a Laravel application using Repositories
- **Service** - Highest level of abstraction. Uses Repositories and Models to perform actions. Example: `StripePaymentService`
- **Repository** - Middle layer of abstraction. Uses Models (not just the related Model) to perform actions. Example: `UserRepository`
- **Model** - Lowest level of abstraction. Represents a single table in the database. Use only for relations and light transforms of the data. Example: `User`

## Installation

You can install the package via composer:

```bash
composer require nathanbarrett/laravel-repositories
```

## Usage

First create a repository class

```php
use NathanBarrett\LaravelRepositories\Repository;
use App\Models\User;

/**
* @extends Repository<User>
 */
class UserRepository extends Repository
{
    public function modelClass(): string
    {
        return User::class;
    }
}
```

Generics are used to ensure that your IDE can provide code completion and type hinting.

```php

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
       //
    }

    public function store(Request $request)
    {
        // The IDE will understand that $user is an instance of User
        $user = $this->userRepository->create($request->all());
        return response()->json($user);
    }
}
```

Or you can quickly create one using the command

```bash
php artisan make:repository UserRepository
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
