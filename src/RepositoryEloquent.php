<?php

namespace Tbitencourt\LaravelRepositoryEloquent;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
     * @param bool $or
     * @return Builder
     */
    //    protected function customWhere($where, $or = false)
    //    {
    //        //        $relationWhere = $this->extractOnlyRelationWhere($where);
    //        //        $query         = $this->buildRelationsQuery($query, $relationWhere, $where, $or);
    //        //        $query         = $this->buildAttributesQuery($query, $where, $or);
    //
    //        return $this;
    //    }
}
