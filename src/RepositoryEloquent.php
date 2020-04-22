<?php

/**
 * PHP version 7
 * @category PHP
 * @package  LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */

declare(strict_types=1);

namespace Tbitencourt\LaravelRepositoryEloquent;

use Closure;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasGlobalScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Tbitencourt\LaravelRepositoryEloquent\Config\RepositoryConfig;
use Tbitencourt\LaravelRepositoryEloquent\Contracts\Repository;
use Tbitencourt\LaravelRepositoryEloquent\Eloquent\CustomRepositoryEloquent;
use Tbitencourt\LaravelRepositoryEloquent\Exceptions\RepositoryException;

/**
 * Class RepositoryEloquent
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 * @method $this select(array|mixed ...$columns = ['*'])
 * @method $this selectRaw(string $expression, array $bindings = [])
 * @method $this selectSub(Closure|QueryBuilder|string $query, string $as)
 * @method $this addSelect(array|mixed $column)
 * @method $this distinct()
 * @method $this orderBy(string $column, string $direction = 'asc')
 * @method $this orderByDesc(string $column)
 * @method $this latest(string $column = 'created_at')
 * @method $this oldest(string $column = 'created_at')
 * @method $this inRandomOrder(string $seed = '')
 * @method $this orderByRaw(string $sql, array $bindings = [])
 * @method $this skip(int $value)
 * @method $this offset(int $value)
 * @method $this take(int $value)
 * @method $this limit(int $value)
 * @method $this from(Closure|QueryBuilder|string $table, string|null $as = null)
 * @method $this join(string $table, Closure|string $first, string|null $operator = null, string|null $second = null, string $type = 'inner', bool $where = false)
 * @method $this joinWhere(string $table, Closure|string $first, string $operator, string $second, string $type = 'inner')
 * @method $this joinSub(Closure|QueryBuilder|string $query, string $as, Closure|string $first, string|null $operator = null, string|null $second = null, string $type = 'inner', bool $where = false)
 * @method $this leftJoin(string $table, Closure|string $first, string|null $operator = null, string|null $second = null)
 * @method $this leftJoinWhere(string $table, Closure|string $first, string $operator, string $second)
 * @method $this leftJoinSub(Closure|QueryBuilder|string $query, string $as, Closure|string $first, string $operator = null, string $second = null)
 * @method $this rightJoin(string $table, Closure|string $first, string|null $operator = null, string|null $second = null)
 * @method $this rightJoinWhere(string $table, Closure|string $first, string $operator, string $second)
 * @method $this rightJoinSub(Closure|QueryBuilder|string $query, string $as, Closure|string $first, string $operator = null, string $second = null)
 * @method $this crossJoin(string $table, Closure|string|null $first = null, string|null $operator = null, string|null $second = null)
 * @method $this newJoinClause(QueryBuilder $parentQuery, string $type, string $table)
 * @method $this groupBy(array|string ...$groups)
 * @method $this having(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method $this orHaving(string $column, string $operator = null, string $value = null)
 * @method $this havingBetween(string $column, array $values, string $boolean = 'and', bool $not = false)
 * @method $this havingRaw(string $sql, array $bindings = [], $boolean = 'and')
 * @method $this orHavingRaw(string $sql, array $bindings = [])
 * @method $this mergeWheres(array $wheres, array $bindings)
 * @method $this where(string|array|Closure $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method $this orWhere(Closure|array|string $column, mixed $operator = null, mixed $value = null)
 * @method $this whereIn(string $column, mixed $values, string $boolean = 'and', bool $not = false)
 * @method $this orWhereIn(string $column, mixed $values)
 * @method $this whereNotIn(string $column, mixed $values, string $boolean = 'and')
 * @method $this orWhereNotIn(string $column, mixed $values)
 * @method $this whereNull(string|array $columns, string $boolean = 'and', bool $not = false)
 * @method $this orWhereNull(string $column)
 * @method $this whereNotNull(string|array $columns, string $boolean = 'and')
 * @method $this orWhereNotNull(string $column)
 * @method $this whereBetween(string $columns, array $values, string $boolean = 'and', bool $not = false)
 * @method $this orWhereBetween(string $column, array $values)
 * @method $this whereNotBetween(string $columns, array $values, string $boolean = 'and')
 * @method $this orWhereNotBetween(string $column, array $values)
 * @method $this whereHas(int|string $relation, Closure $param)
 * @method $this whereCode(mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method $this whereInCode(mixed $value = null, string $boolean = 'and', bool $not = false)
 * @method $this whereNotInCode(mixed $value = null, string $boolean = 'and')
 * @method $this with(mixed $relations)
 * @method $this withAndWhereHas(mixed $relations, mixed $constraint)
 * @method $this when(mixed $value, callable $callback, callable $default = null)
 * @method $this tap(callable $callback)
 * @method \Illuminate\Database\Eloquent\Collection get(array $columns = ['*'])
 * @method LengthAwarePaginator paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null)
 * @method Paginator simplePaginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null)
 * @method mixed find(int|string $id, array $columns = ['*'])
 * @method mixed findMany(Arrayable|array $ids, array $columns = ['*'])
 * @method Model|\Illuminate\Database\Eloquent\Collection findOrFail(mixed $id, array $columns = ['*'])
 * @method mixed findByCode(string $code, array $columns = ['*'])
 * @method Model|\Illuminate\Database\Eloquent\Collection findByCodeOrFail(string $code, array $columns = ['*'])
 * @method Model|$this findOrNew(mixed $id, array $columns = ['*'])
 * @method Model|$this firstOrNew(array $attributes, array $values = [])
 * @method Model|$this firstOrCreate(array $attributes, array $values = [])
 * @method Model|$this updateOrCreate(array $attributes, array $values = [])
 * @method Model|$this firstOrFail(array $columns = ['*'])
 * @method Model|$this|mixed firstOr(array $columns = ['*'], Closure $callback = null)
 * @method Model|object|null first(array $columns = ['*'])
 * @method Model create(array $attributes = [])
 * @method int update(array $values)
 * @method int updateByCode(string $code, array $values)
 * @method bool|null delete()
 * @method bool|null deleteByCode(string $code)
 * @method string toSql()
 * @method array getBindings()
 * @method Collection pluck(string $column, string $key = null)
 * @method int count(string $columns = '*')
 * @method mixed min(string $column)
 * @method mixed max(string $column)
 * @method mixed sum(string $column)
 * @method mixed avg(string $column)
 * @method mixed average(string $column)
 * @method mixed aggregate(string $function, $columns = ['*'])
 */
abstract class RepositoryEloquent implements Repository
{
    use ForwardsCalls;
    use HasGlobalScopes;

    /**
     * The model being queried.
     * @var Model
     */
    protected $model;
    /**
     * The base query builder instance.
     * @var Builder
     */
    protected $query;
    /**
     * @var RepositoryConfig
     */
    protected $repositoryConfig;

    /**
     * Create a new Repository instance using Eloquent query builder.
     * @param mixed $model model
     * @throws RepositoryException
     */
    public function __construct($model = null)
    {
        $this->model = $this->makeModel($model);
        $this->newQuery();
        $this->configRepository();
    }

    /**
     * Specify \Illuminate\Database\Eloquent\Model class name
     * @return string
     */
    abstract public function model();

    /**
     * @param mixed $model
     * @return Model
     * @throws RepositoryException
     */
    private function makeModel($model = null)
    {
        try {
            /** @var Model $instance */
            $instance = null;
            if (empty($model)) {
                $instance = app()->make($this->model());
            } else {
                if (is_string($model)) {
                    $instance = app()->make($model);
                } else {
                    if (is_object($model)) {
                        $instance = $model;
                    }
                }
            }
            if (!$instance instanceof Model) {
                throw new RepositoryException("Class {$instance} must be an instance of Illuminate\\Database\\Eloquent\\Model");
            }

            return $instance;
        } catch (BindingResolutionException $ex) {
            report($ex);
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }//end try
    }

    /**
     * @return void
     * @throws RepositoryException
     */
    public function configRepository()
    {
        try {
            $this->repositoryConfig = app()->make(RepositoryConfig::class);
        } catch (BindingResolutionException $ex) {
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * Handle dynamic method calls into the model.
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->query, $method, $parameters);
    }

    /**
     * Handle dynamic static method calls into the method.
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws RepositoryException
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = static::query();

        return $instance->$method(...$parameters);
    }

    /**
     * Begin querying the model.
     * @param mixed $model
     * @return $this
     * @noinspection PhpDocMissingThrowsInspection
     */
    public static function query($model = null)
    {
        if (static::class !== self::class) {
            return (new static($model));
        } else {
            if (empty($model)) {
                throw new RepositoryException("The parameter 'model' is required to CustomRepositoryEloquent!");
            }
        }

        return (new CustomRepositoryEloquent($model));
    }

    /**
     * Get a new query builder for the model's table.
     * @return RepositoryEloquent
     */
    public function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Get a new query builder that doesn't have any global scopes.
     * @return RepositoryEloquent
     */
    public function newQueryWithoutScopes()
    {
        $this->query = $this->model->newQueryWithoutScopes();

        return $this;
    }

    /**
     * Get a new query instance without a given scope.
     * @param Scope|string $scope
     * @return RepositoryEloquent
     */
    public function newQueryWithoutScope($scope)
    {
        $this->query = $this->model->newQueryWithoutScope($scope);

        return $this;
    }

    /**
     * @param Model $related
     * @return RepositoryEloquent
     * @throws RepositoryException
     */
    public function getRepositoryByRelated($related)
    {
        try {
            //First Step: Get related repository's path
            $modelGroupPath      = $this->repositoryConfig->getModelGroupPath();
            $repositoryGroupPath = $this->repositoryConfig->getRepositoryGroupPath();
            if ($modelGroupPath && $repositoryGroupPath) {
                $relatedRepositoryPath = Str::replaceFirst(
                    $this->repositoryConfig->getModelDefaultPath(),
                    $this->repositoryConfig->getRepositoryDefaultPath(),
                    get_class($related)
                );
            } else {
                if (!$modelGroupPath && $repositoryGroupPath) {
                    throw new RepositoryException('Repository group path configuration not supported!');
                } else {
                    $relatedRepositoryPath = $this->repositoryConfig->getRepositoryDefaultPath();
                    $relatedRepositoryPath .= '\\';
                    $relatedRepositoryPath .= class_basename($related);
                }
            }
            //Second Step: Get related repository's name
            $modelSuffixName = $this->repositoryConfig->getModelSuffixName();
            if (empty($modelSuffixName)) {
                $relatedRepositoryClassName = $relatedRepositoryPath . $this->repositoryConfig->getRepositorySuffixName();
            } else {
                $relatedRepositoryClassName = Str::replaceFirst(
                    $modelSuffixName,
                    $this->repositoryConfig->getRepositorySuffixName(),
                    get_class($relatedRepositoryPath)
                );
            }
            if (class_exists($relatedRepositoryClassName)) {
                return app()->make($relatedRepositoryClassName);
            } else {
                return RepositoryEloquent::query($related);
            }
        } catch (Exception $ex) {
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }//end try
    }
}
