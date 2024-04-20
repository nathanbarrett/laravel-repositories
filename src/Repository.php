<?php declare(strict_types=1);

namespace NathanBarrett\LaravelRepositories;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;

/**
 * Extend this class to create a repository for a model.
 * @template M of Model - the Eloquent Model for the repository
 */
abstract class Repository
{
    /**
     * @return class-string<M>
     */
    abstract public function modelClass(): string;

    /**
     * @return M
     */
    public function model()
    {
        return app($this->modelClass());
    }

    public function modelQuery(): Builder
    {
        return $this->model()::query();
    }

    /**
     * @return M|static
     */
    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        return $this->modelQuery()->firstOrCreate($attributes, $values);
    }

    /**
     * Attempt to create the record. If a unique constraint violation occurs, attempt to find the matching record.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return M|static
     */
    public function createOrFirst(array $attributes = [], array $values = [])
    {
        return $this->modelQuery()->createOrFirst($attributes, $values);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return M|static
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->modelQuery()->updateOrCreate($attributes, $values);
    }

    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param  array|string  $columns
     * @return M|static
     *
     * @throws ModelNotFoundException<Model>
     */
    public function firstOrFail(array|string $columns = ['*'])
    {
        return $this->modelQuery()->firstOrFail($columns);
    }

    /**
     * Execute the query and get the first result or call a callback.
     *
     * @param Closure|array|string  $columns
     * @param Closure|null  $callback
     * @return M|static|mixed
     */
    public function firstOr(Closure|array|string $columns = ['*'], ?Closure $callback = null)
    {
        return $this->modelQuery()->firstOr($columns, $callback);
    }

    /**
     * Add a basic where clause to the query, and return the first result.
     *
     * @param array|string|Closure|\Illuminate\Contracts\Database\Query\Expression $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @param string $boolean
     * @return M|null
     */
    public function firstWhere(array|string|Closure|\Illuminate\Contracts\Database\Query\Expression $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
    {
        return $this->modelQuery()->firstWhere($column, $operator, $value, $boolean);
    }

    /**
     * Execute the query and get the first result if it's the sole matching record.
     *
     * @param  array|string  $columns
     * @return M
     *
     * @throws ModelNotFoundException<M>
     * @throws MultipleRecordsFoundException
     */
    public function sole(array|string $columns = ['*'])
    {
        return $this->modelQuery()->sole($columns);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return M
     */
    public function create(array $attributes = [])
    {
        return $this->modelQuery()->create($attributes);
    }

    /**
     * Save a new model and return the instance. Allow mass-assignment.
     *
     * @param  array  $attributes
     * @return M
     */
    public function forceCreate(array $attributes)
    {
        return $this->modelQuery()->forceCreate($attributes);
    }

    /**
     * Save a new model instance with mass assignment without raising model events.
     *
     * @param  array  $attributes
     * @return M
     */
    public function forceCreateQuietly(array $attributes = [])
    {
        return $this->modelQuery()->forceCreateQuietly($attributes);
    }

    /**
     * Create and return an un-saved model instance.
     *
     * @param  array  $attributes
     * @return Builder|M
     */
    public function make(array $attributes = [])
    {
        return $this->modelQuery()->make($attributes);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array|string  $columns
     * @return M|null
     */
    public function find(mixed $id, array|string $columns = ['*'])
    {
        return $this->modelQuery()->find($id, $columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array|string  $columns
     * @return M
     */
    public function findOrFail(mixed $id, array|string $columns = ['*'])
    {
        return $this->modelQuery()->findOrFail($id, $columns);
    }

    /**
     * Find a model by its primary key or return fresh model instance.
     *
     * @param  mixed  $id
     * @param  array|string  $columns
     * @return M
     */
    public function findOrNew(mixed $id, array|string $columns = ['*'])
    {
        return $this->modelQuery()->findOrNew($id, $columns);
    }

    /**
     * Find a model by its primary key or call a callback.
     *
     * @param  mixed  $id
     * @param array|Closure|string $columns
     * @param  Closure|null  $callback
     * @return M|Collection|static[]|static|mixed
     */
    public function findOr(mixed $id, array|Closure|string $columns = ['*'], ?Closure $callback = null)
    {
        return $this->modelQuery()->findOr($id, $columns, $callback);
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return Builder|M
     */
    public function firstOrNew(array $attributes = [], array $values = [])
    {
        return $this->modelQuery()->firstOrNew($attributes, $values);
    }
}
