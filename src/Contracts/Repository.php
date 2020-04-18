<?php

/**
 * PHP version 7.4
 * @category PHP
 * @package  LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */

declare(strict_types=1);

namespace Tbitencourt\LaravelRepositoryEloquent\Contracts;

/**
 * Interface Repository
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent\Contracts
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */
interface Repository
{
    /**
     * Find a model by its primary key.
     * @param mixed $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Execute the query as a "select" statement.
     * @param array $columns
     * @return mixed
     */
    public function get($columns = ['*']);

    /**
     * Execute the query and get the first result.
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*']);

    /**
     * Save a new model and return the instance.
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes = []);

    /**
     * Update a record in the database.
     * @param array $values
     * @return mixed
     */
    public function update(array $values);

    /**
     * Delete a record from the database.
     * @return mixed
     */
    public function delete();
}
