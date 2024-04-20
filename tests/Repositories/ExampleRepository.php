<?php declare(strict_types=1);

namespace NathanBarrett\LaravelRepositories\Tests\Repositories;

use NathanBarrett\LaravelRepositories\Repository;
use NathanBarrett\LaravelRepositories\Tests\Models\ExampleModel;

/**
 * @extends Repository<ExampleModel>
 */
class ExampleRepository extends Repository
{
    public function modelClass(): string
    {
        return ExampleModel::class;
    }

    public function testInfer(): string
    {
        // $model should be inferred as ExampleModel by IDE
        $model = $this->create(['name' => 'example']);

        // $output should be inferred as string by IDE
        $output = $model->uniqueMethod();

        return $output;
    }
}
