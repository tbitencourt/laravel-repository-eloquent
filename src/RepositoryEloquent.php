<?php

namespace Tbitencourt\LaravelRepositoryEloquent;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tbitencourt\LaravelRepositoryEloquent\Contracts\Repository;
use Tbitencourt\LaravelRepositoryEloquent\Exceptions\RepositoryException;

/**
 * Class RepositoryEloquent
 * @package Tbitencourt\LaravelRepositoryEloquent
 * @author  Thales Bitencourt
 */
abstract class RepositoryEloquent extends Builder implements Repository
{
    /**
     * Create a new Repository instance using Eloquent query builder.
     * @param mixed $model
     * @throws RepositoryException
     */
    public function __construct($model = null)
    {
        $instance = $this->makeModel($model);
        parent::__construct($instance->getConnection()->query());
        $this->setModel($instance);
    }

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
            } else if (is_string($model)) {
                $instance = app()->make($model);
            } else if (is_object($model)) {
                $instance = $model;
            }
            if (!$instance instanceof Model) {
                throw new RepositoryException("Class {$instance} must be an instance of Illuminate\\Database\\Eloquent\\Model");
            }

            return $instance;
        } catch (BindingResolutionException $ex) {
            report($ex);
            throw new RepositoryException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * Specify \Illuminate\Database\Eloquent\Model class name
     * @return string
     */
    public abstract function model();

    /**
     * Internal default implementation of where function
     * @param array $where
     * @return $this
     */
    protected function customWhere($where)
    {
        $relationWhere = $this->extractOnlyRelationWhere($where);
        $this->buildRelationsQuery($relationWhere, $where);
        $this->buildAttributesQuery($where);

        return $this;
    }

    /**
     * @param array $where
     * @return array
     */
    protected function extractOnlyRelationWhere(array &$where)
    {
        $relationWhere = $fixWhere = [];
        //Search relations fitlers
        array_map(function($key, $value) use (&$relationWhere, &$fixWhere) {
            if (Str::contains($key, '.') || Str::contains($key, '->')) {
                if (Str::contains($key, '->')) {
                    $newKey = str_replace('->', '.', $key);
                    Arr::set($fixWhere, $key, $newKey);
                    $key = $newKey;
                }
                $arrayField               = explode('.', $key);
                $field                    = array_pop($arrayField);
                $relation                 = implode(".", $arrayField);
                $relationWhere[$relation] = [$field => $value];
                //                Arr::set($relationWhere, $key, $value);
            }
        }, array_keys($where), $where);
        //Change "->" character to "."
        array_map(function($originalKey, $newKey) use (&$where, &$teste) {
            $value = $where[$originalKey];
            unset($where[$originalKey]);
            $where[$newKey] = $value;
        }, array_keys($fixWhere), $fixWhere);

        return $relationWhere;
    }

    /**
     * Internal default implementation of where function
     * @param array $relationWhere
     * @param array $where
     * @return void
     */
    protected function buildRelationsQuery(array &$relationWhere, array &$where)
    {
        foreach ($relationWhere as $relation => $relationWhereItem) {
            $this->whereHas($relation, function($innerQuery) use ($relationWhereItem, $relation, &$where) {
                $this->buildAttributesQuery($relationWhereItem, $innerQuery, $relation, $where);
            });
        }
    }

    /**
     * @param array $where
     * @param Builder $query
     * @param string $relation
     * @param array $parentWhere
     * @return void
     */
    protected function buildAttributesQuery(array &$where, $query = null, $relation = null, array &$parentWhere = null)
    {
        $query = $query ?? $this;
        foreach ($where as $field => $value) {
            if (Str::contains($field, '.') || Str::contains($field, '->')) {
                Log::error("Ocorreu um erro inesperado: RelationWhere in BuildAttributesQuery");
            } else if ($value instanceof Closure) {
                $query->where($value);
            } else if (is_array($value)) {
                //To use operator
                if (count($value) === 2 || count($value) === 3 || count($value) === 4) {
                    if (count($value) === 4) {
                        //Get custom operator
                        list($field, $operator, $search, $or) = $value;
                    } else if (count($value) === 3) {
                        //Get custom operator
                        list($field, $operator, $search) = $value;
                        $or = false;
                    } else {
                        //Get default operator
                        list($field, $search) = $value;
                        $operator = '=';
                        $or       = false;
                    }
                    //Get where query
                    if (Str::lower($operator) == 'between' && is_array($search)) {
                        $query->whereBetween($field, $search);
                    } else if (Str::lower($operator) == 'in' && is_array($search)) {
                        $query->whereIn($field, $search);
                    } else if (Str::lower($operator) == 'notin' && is_array($search)) {
                        $query->whereNotIn($field, $search);
                    } else {
                        if (is_bool($or)) {
                            $boolean = $or ? 'and' : 'or';
                        } else if (is_string($or)) {
                            $boolean = $or;
                        } else {
                            $boolean = 'and';
                        }
                        $query->where($field, $operator, $search, $boolean);
                    }
                }
            } else {
                $query->where($field, $value);
            }
            //remove parent where
            if (!empty($parentWhere)) {
                $key = ($relation ? $relation . "." : "") . $field;
                unset($parentWhere[$key]);
            }
        }
    }
}
