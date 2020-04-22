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

namespace Tbitencourt\LaravelRepositoryEloquent\Eloquent;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tbitencourt\LaravelRepositoryEloquent\RepositoryEloquent;

/**
 * Class Builder
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent\Eloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */
class Builder extends BaseBuilder
{
    /**
     * The repository being queried.
     * @var RepositoryEloquent
     */
    protected $repository;

    /**
     * Add a basic where clause to the query.
     * @param string|array|Closure $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     * @throws Exception
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_array($column) || is_string($column)) {
            $this->customWhere(...func_get_args());
        } else {
            parent::where(...func_get_args());
        }

        return $this;
    }

    /**
     * Internal default implementation of where function
     * @param string|array|Closure $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     * @throws Exception
     */
    protected function customWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        $relationWhere = [];
        if (is_array($column)) {
            $this->addArrayWhereAndExtractRelation($relationWhere, $column, $boolean);
        } else {
            parent::where(...func_get_args());
        }
        if (empty($relationWhere)) {
            $this->buildRelationsQuery($relationWhere);
        }

        return $this;
    }

    /**
     * @param array $relationWhere
     * @param array $where
     * @param string $boolean
     * @return void
     * @throws Exception
     */
    private function addArrayWhereAndExtractRelation(array &$relationWhere, array $where, $boolean = 'and')
    {
        foreach ($where as $key => $value) {
            if (is_numeric($key) && is_array($value)) {
                parent::where(...array_values($value));
                continue;
            }
            if (Str::contains($key, '.')) {
                if (is_array($value)) {
                    array_shift($value);
                    $this->extractRelation($relationWhere, $key, ...$value);
                    continue;
                }
                $this->extractRelation($relationWhere, $key, $value);
                continue;
            }
            parent::where($key, '=', $value, $boolean);
        }
    }

    /**
     * @param array $relationWhere
     * @param string $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return void
     */
    private function extractRelation(
        array &$relationWhere,
        string $column,
        $operator = null,
        $value = null,
        $boolean = null
    ) {
        $arrayField                 = explode('.', $column);
        $field                      = array_pop($arrayField);
        $relation                   = implode(".", $arrayField);
        $relationWhere[$relation][] = $this->arrayFilterIsEmpty([$field, $operator, $value, $boolean]);
    }

    /**
     * @param array $array
     * @param bool $considerZeroEmpty
     * @return array
     */
    protected function arrayFilterIsEmpty(array $array, $considerZeroEmpty = true)
    {
        return array_filter(
            $array,
            function ($value) use ($considerZeroEmpty) {
                return !$this->isEmpty($value, $considerZeroEmpty);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param mixed $var
     * @param bool $considerZeroEmpty
     * @return bool
     */
    protected function isEmpty($var, $considerZeroEmpty = true)
    {
        if (!isset($var)) {
            return true;
        } else {
            if ($considerZeroEmpty && empty($var)) {
                return true;
            } else {
                if (is_string($var) && trim($var) == '') {
                    return true;
                } else {
                    if (is_array($var) && count($var) == 0) {
                        return true;
                    } else {
                        if (is_object($var) && ($var instanceof Collection) && count($var) == 0) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param array $relationWhere
     * @return void
     */
    private function buildRelationsQuery($relationWhere): void
    {
        foreach ($relationWhere as $relation => $relationWhereItem) {
            $this->whereHas(
                $relation,
                function (BaseBuilder $innerQuery) use ($relationWhereItem) {
                    foreach ($relationWhereItem as $item) {
                        $innerQuery->where(...$item);
                    }
                }
            );
        }
    }

    /**
     * @return RepositoryEloquent
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function getRepository()
    {
        return $this->repository ?? ($this->repository = RepositoryEloquent::query($this->model));
    }
}
