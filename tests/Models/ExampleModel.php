<?php declare(strict_types=1);

namespace NathanBarrett\LaravelRepositories\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    protected $guarded = ['id'];

    public function uniqueMethod(): string
    {
        return 'something';
    }
}
