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

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasGlobalScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
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
            }
            if (is_string($model)) {
                $instance = app()->make($model);
            }
            if (is_object($model)) {
                $instance = $model;
            }
            if (!$instance instanceof Model) {
                throw new RepositoryException(
                    "Class {$instance} must be an instance of Illuminate\\Database\\Eloquent\\Model"
                );
            }

            return $instance;
        } catch (BindingResolutionException $ex) {
            report($ex);
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }
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
        if (empty($model)) {
            throw new RepositoryException("The parameter 'model' is required to CustomRepositoryEloquent!");
        }
        if (static::class !== self::class) {
            return (new static($model));
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
            $relatedRepoPath = $this->getRelatedRepositoryPath($related);
            //Second Step: Get related repository's name
            $relatedRepoClassName = $this->getRelatedRepositoryClassName($relatedRepoPath);
            if (class_exists($relatedRepoClassName)) {
                return app()->make($relatedRepoClassName);
            }

            return self::query($related);
        } catch (Exception $ex) {
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @param Model $related
     * @return string
     * @throws RepositoryException
     */
    protected function getRelatedRepositoryPath(Model $related): string
    {
        $modelGroupPath      = $this->repositoryConfig->getModelGroupPath();
        $repositoryGroupPath = $this->repositoryConfig->getRepositoryGroupPath();
        if (!$modelGroupPath && $repositoryGroupPath) {
            throw new RepositoryException('Repository group path configuration not supported!');
        }
        if ($modelGroupPath && $repositoryGroupPath) {
            return Str::replaceFirst(
                $this->repositoryConfig->getModelDefaultPath(),
                $this->repositoryConfig->getRepositoryPath(),
                get_class($related)
            );
        }
        $relatedRepoPath = $this->repositoryConfig->getRepositoryPath();
        $relatedRepoPath .= '\\';
        $relatedRepoPath .= class_basename($related);

        return $relatedRepoPath;
    }

    /**
     * @param string $relatedRepoPath
     * @return string
     */
    protected function getRelatedRepositoryClassName(string $relatedRepoPath): string
    {
        $modelSuffixName = $this->repositoryConfig->getModelSuffixName();
        if (empty($modelSuffixName)) {
            return $relatedRepoPath . $this->repositoryConfig->getRepositorySuffixName();
        }

        return Str::replaceFirst(
            $modelSuffixName,
            $this->repositoryConfig->getRepositorySuffixName(),
            get_class($relatedRepoPath)
        );
    }
}
