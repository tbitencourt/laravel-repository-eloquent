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

namespace Tbitencourt\LaravelRepositoryEloquent\Eloquent;

use Tbitencourt\LaravelRepositoryEloquent\RepositoryEloquent;

/**
 * Class CustomRepositoryEloquent
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent\Eloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */
class CustomRepositoryEloquent extends RepositoryEloquent
{
    /**
     * Specify \Illuminate\Database\Eloquent\Model class name
     * @return string
     */
    public function model()
    {
        return get_class($this->model);
    }
}
